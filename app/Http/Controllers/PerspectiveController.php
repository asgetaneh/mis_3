<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use App\Models\Perspective;
use App\Models\PerspectiveTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PerspectiveStoreRequest;
use App\Http\Requests\PerspectiveUpdateRequest;

class PerspectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        if (! Gate::allows('view keypeformanceindicators', KeyPeformanceIndicator::class)) {
                    abort(403);
                }

        $search = $request->get('search', '');

        $perspective_ts = PerspectiveTranslation::search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'app.perspectives.index',
            compact('perspective_ts', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Perspective::class);
        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $users = User::pluck('name', 'id');

        return view('app.perspectives.create', compact('languages', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Perspective::class);
        //
        $data = $request->input();
        $language = Language::all();
         //$lastperspective = perspective::select('id')->orderBy('id','desc')->first();
        try {
            $perspective = new Perspective;

            $perspective->created_at= new \DateTime();
             $perspective->updated_by_id= auth()->user()->id;
            $perspective->created_by_id= auth()->user()       ->id;
            $perspective->updated_at =new \DateTime();
            $perspective->save();
            if ($perspective->id) {
                // code...
                echo $perspective->id;
            }
             foreach ($language as $key => $value) {
                // code...
                $perspective_translation = new PerspectiveTranslation;
                $perspective_translation ->translation_id=$perspective->id;
                $perspective_translation ->name = $data['name_'.$value->locale];
                 $perspective_translation ->locale = $value->locale;
                $perspective_translation ->description = $data['description_'.$value->locale];
                $perspective_translation->save();
         }
         return redirect()
         ->route('perspectives.index', $perspective)
         ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('perspective-new')->withErrors(['errors' => $e]);
            }
        $perspective = perspectiveTranslation::all();
        return redirect()
            ->route('perspectives.index', $perspectives)
            ->withSuccess(__('crud.common.created'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, Perspective $perspective): View
    {
        $this->authorize('view', $perspective);

        return view('app.perspectives.show', compact('perspective'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Perspective $perspective): View
    {
        $this->authorize('update', $perspective);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $perspectiveTranslations = $perspective->perspectiveTranslations->groupBy('locale');

        return view(
            'app.perspectives.edit',
            compact('perspective', 'perspectiveTranslations', 'languages')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        Perspective $perspective
    ): RedirectResponse {
        $this->authorize('update', $perspective);
        $perspective->update([
            'updated_at' => new \DateTime(),
            'updated_by_id' => auth()->user()->id,
            // 'created_by_id' => $goal->created_by_id || '',
        ]);

        $isNewLangAdded = false;
        $localeArray = [];

        foreach ($request->except('_token', '_method') as $key => $value) {

            $locale = str_replace(['name_', 'description_'], '', $key);

            $perspectiveTranslation = $perspective->perspectiveTranslations->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($perspectiveTranslation) {
                $perspectiveTranslation->update([
                    $column => $value
                ]);
            }else{
                $isNewLangAdded = true;
                array_push($localeArray, $locale);
            }
        }

        // handle editing if new language was added but translation has no recored for the new language
        if($isNewLangAdded){
            $localeArray = array_unique($localeArray);
            foreach($localeArray as $locale){
                // dd($locale);

                $loc = $locale;
                $inputName = 'name_'.$loc;
                $inputDescription = 'description_'.$loc;

                $name = $request->input($inputName);
                $description = $request->input($inputDescription);

                $perspectiveTranslation = new PerspectiveTranslation;
                $perspectiveTranslation->translation_id = $perspective->id;
                $perspectiveTranslation->name = $name;
                $perspectiveTranslation->locale = $locale;
                $perspectiveTranslation->description = $description;
                $perspectiveTranslation->save();
            }
        }

        return redirect()
            ->route('perspectives.index', $perspective)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Perspective $perspective
    ): RedirectResponse {
        $this->authorize('delete', $perspective);

        $perspective->delete();

        return redirect()
            ->route('perspectives.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
