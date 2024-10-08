<?php

namespace App\Http\Controllers;
use App\Models\Language;

use Illuminate\View\View;
use App\Models\KpiChildOne;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\KpiChildOneTranslation;
use App\Http\Requests\KpiChildOneTranslationStoreRequest;
use App\Http\Requests\KpiChildOneTranslationUpdateRequest;

class KpiChildOneTranslationController extends Controller
{

    public function add(Request $request,$id=null): View
    {
        $this->authorize('view-any', KpiChildOneTranslation::class);

        $search = $request->get('search', '');

        $kpiChildOneTranslations = KpiChildOneTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();
            $url = $request->fullUrl();
         return view(
            'app.kpi_child_one_translations.index',
            compact('kpiChildOneTranslations', 'search')
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$id=null): View
    {
        $this->authorize('view-any', KpiChildOneTranslation::class);

        $search = $request->get('search', '');

        $kpiChildOneTranslations = KpiChildOneTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();
            $url = $request->fullUrl();
         return view(
            'app.kpi_child_one_translations.index',
            compact('kpiChildOneTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $search = $request->get('search', '');
        $this->authorize('create', KpiChildOneTranslation::class);
         $kpiChildOnes = KpiChildOneTranslation::pluck('id', 'id');

        $languages = Language::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'app.kpi_child_one_translations.create',
            compact('kpiChildOnes','languages')
        );
    }
    // public function createNew($id): View
    // {
    //     $this->authorize('create', KpiChildOneTranslation::class);
    //     dd($kpiChildOneTranslation->id);
    //     $kpiChildOnes = KpiChildOne::pluck('id', 'id');
    //      $languages = Language::search($search)
    //         ->latest()
    //         ->paginate(5)
    //         ->withQueryString();

    //     return view(
    //         'app.kpi_child_one_translations.create',
    //         compact('kpiChildOnes','languages')
    //     );
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request
    ): RedirectResponse {
        $this->authorize('create', KpiChildOneTranslation::class);

        $data = $request->input();
        $language = Language::all();
         //$lastGoal = Goal::select('id')->orderBy('id','desc')->first();
        try {
            $KpiChildOne = new KpiChildOne;
            $KpiChildOne->created_at= new \DateTime();
            $KpiChildOne->updated_at =new \DateTime();
            $KpiChildOne->save();
             foreach ($language as $key => $value) {
                 $kpi_child_one_translation = new KpiChildOneTranslation;
                $kpi_child_one_translation ->kpiChildOne_id=$KpiChildOne->id;
                $kpi_child_one_translation ->name = $data['name_'.$value->locale];
                 $kpi_child_one_translation ->locale = $value->locale;
                $kpi_child_one_translation ->description = $data['description_'.$value->locale];
                $kpi_child_one_translation->save();
         }
         $kpiChildOneTranslations = KpiChildOneTranslation::all();
         return redirect()
            ->route('kpi-child-one-translations.index')
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('kpi-child-one-translations/new')->withErrors(['errors' => $e]);
            }
        $kpiChildOneTranslations = KpiChildOneTranslation::all();
        return view('kpi-child-one-translations.index',['goals', $kpiChildOneTranslations]);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        KpiChildOneTranslation $kpiChildOneTranslation
    ): View {
        $this->authorize('view', $kpiChildOneTranslation);

        return view(
            'app.kpi_child_one_translations.show',
            compact('kpiChildOneTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        KpiChildOneTranslation $kpiChildOneTranslation
    ): View {
        $this->authorize('update', $kpiChildOneTranslation);
        $kpiChildOnes = KpiChildOne::pluck('id', 'id');

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $childOneTranslations = $kpiChildOneTranslation->kpiChildOne->kpiChildOneTranslations->groupBy('locale');

        return view(
            'app.kpi_child_one_translations.edit',
            compact('kpiChildOneTranslation', 'kpiChildOnes', 'childOneTranslations', 'languages')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        KpiChildOneTranslation $kpiChildOneTranslation
    ): RedirectResponse {
        $this->authorize('update', $kpiChildOneTranslation);

        $isNewLangAdded = false;
        $localeArray = [];

        foreach ($request->except('_token', '_method') as $key => $value) {

            $locale = str_replace(['name_', 'description_'], '', $key);

            $childOneTranslation = $kpiChildOneTranslation->kpiChildOne->kpiChildOneTranslations->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($childOneTranslation) {
                $childOneTranslation->update([
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
                // dd($localeArray);

                $loc = $locale;
                $inputName = 'name_'.$loc;
                $inputDescription = 'description_'.$loc;

                $name = $request->input($inputName);
                $description = $request->input($inputDescription);

                $kpiChildOneT = new KpiChildOneTranslation;
                $kpiChildOneT->kpiChildOne_id = $kpiChildOneTranslation->kpiChildOne_id;
                $kpiChildOneT->name = $name;
                $kpiChildOneT->locale = $locale;
                $kpiChildOneT->description = $description;
                $kpiChildOneT->save();
            }

        }

        return redirect()
            ->route('kpi-child-one-translations.index', $kpiChildOneTranslation)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KpiChildOneTranslation $kpiChildOneTranslation
    ): RedirectResponse {
        $this->authorize('delete', $kpiChildOneTranslation);
        $kpiChildOneTranslation->kpiChildOne->delete();

        return redirect()
            ->route('kpi-child-one-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
