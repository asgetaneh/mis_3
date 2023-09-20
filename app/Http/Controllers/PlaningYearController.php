<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Language;
use Illuminate\View\View;
use App\Models\PlaningYear;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PlaningYearStoreRequest;
use App\Http\Requests\PlaningYearUpdateRequest;
use App\Models\PlaningYearTranslation;

class PlaningYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', PlaningYear::class);

        $search = $request->get('search', '');

        $planing_year_ts = PlaningYearTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.planing_years.index',
            compact('planing_year_ts', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', PlaningYear::class);
        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $users = User::pluck('name', 'id');

        return view('app.planing_years.create', compact('users', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', PlaningYear::class);

        // $validated = $request->validated();

        // $planingYear = PlaningYear::create($validated);

        $data = $request->input();
        $language = Language::all();

        try {
            $planningYear = new PlaningYear;
            // if the table is modified with new columns change below code
            // $planningYear->updated_by_id= auth()->user()->id;
            // $planningYear->created_by_id= auth()->user()->id;
            $planningYear->save();

            foreach ($language as $key => $value) {
                // code...
                $planning_year_translation = new PlaningYearTranslation();
                $planning_year_translation->planing_year_id=$planningYear->id;
                $planning_year_translation->name = $data['name_'.$value->locale];
                $planning_year_translation->locale = $value->locale;
                $planning_year_translation->description = $data['description_'.$value->locale];
                $planning_year_translation->save();
         }
         return redirect()
         ->route('planing-years.index')
         ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('planing-years/create')->withErrors(['errors' => $e]);
            }

        return redirect()
            ->route('planing-years.edit', $planingYear)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, PlaningYear $planingYear): View
    {
        $this->authorize('view', $planingYear);

        return view('app.planing_years.show', compact('planingYear'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, PlaningYear $planingYear): View
    {
        $this->authorize('update', $planingYear);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $planingTranslations = $planingYear->planingYearTranslations->groupBy('locale');

        return view('app.planing_years.edit', compact('planingYear', 'languages', 'planingTranslations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        PlaningYear $planingYear
    ): RedirectResponse {
        $this->authorize('update', $planingYear);

        // $planingYear->update([
        //     'updated_at' => new \DateTime() ,
        // ]);

        $isNewLangAdded = false;
        $localeArray = [];

        foreach ($request->except('_token', '_method') as $key => $value) {

            $locale = str_replace(['name_', 'description_'], '', $key);

            $planingTranslations = $planingYear->planingYearTranslations->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($planingTranslations) {
                $planingTranslations->update([
                    $column => $value
                ]);
            }else{
                $isNewLangAdded = true;
                array_push($localeArray, $locale);
            }
        }

        if($isNewLangAdded){
            $localeArray = array_unique($localeArray);
            foreach($localeArray as $locale){
                // dd($locale);

                $loc = $locale;
                $inputName = 'name_'.$loc;
                $inputDescription = 'description_'.$loc;

                $name = $request->input($inputName);
                $description = $request->input($inputDescription);

                $strategyTranslation = new PlaningYearTranslation;
                $strategyTranslation->planing_year_id = $planingYear->id;
                $strategyTranslation->name = $name;
                $strategyTranslation->locale = $locale;
                $strategyTranslation->description = $description;
                $strategyTranslation->save();
            }
        }

        return redirect()
            ->route('planing-years.index', $planingYear)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        PlaningYear $planingYear
    ): RedirectResponse {
        $this->authorize('delete', $planingYear);

        $planingYear->delete();

        return redirect()
            ->route('planing-years.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
