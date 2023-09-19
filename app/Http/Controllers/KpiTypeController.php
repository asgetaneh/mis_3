<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\KpiType;
use App\Models\Language;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\KpiTypeTranslation;

class KpiTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KpiType::class);

        $search = $request->get('search', '');
        $kpiType_ts = KpiTypeTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('app.kpi-types.index', compact('kpiType_ts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KpiType::class);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.kpi-types.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', KpiType::class);

        $data = $request->input();
        $language = Language::all();

        try {
            $kpiType = new KpiType;
            $kpiType->created_at = new \DateTime();
            $kpiType->updated_at = new \DateTime();
            $kpiType->save();
            foreach ($language as $key => $value) {
                // code...
                $kpiType_translation = new KpiTypeTranslation;
                $kpiType_translation->type_id = $kpiType->id;
                $kpiType_translation->name = $data['name_' . $value->locale];
                $kpiType_translation->description = $data['description_' . $value->locale];
                $kpiType_translation->locale = $value->locale;
                $kpiType_translation->save();
            }
            return redirect()
                ->route('types.index', $kpiType)
                ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(KpiType $type): View
    {
        $this->authorize('view', $type);

        return view('app.kpi-types.show', compact('type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, KpiType $type)
    {
        $this->authorize('update', $type);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $kpiTypeTranslations = $type->kpiTypeTranslations->groupBy('locale');

        return view('app.kpi-types.edit', compact('type', 'languages', 'kpiTypeTranslations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KpiType $type)
    {
        $this->authorize('update', $type);

        $type->update([
            'updated_at' => new \DateTime(),
        ]);

        $isNewLangAdded = false;
        $localeArray = [];

        foreach ($request->except('_token', '_method') as $key => $value) {

            $locale = str_replace(['name_', 'description_'], '', $key);

            $kpiTypeTranslation = $type->kpiTypeTranslations->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($kpiTypeTranslation) {
                $kpiTypeTranslation->update([
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

                $typeTranslation = new KpiTypeTranslation;
                $typeTranslation->type_id = $type->id;
                $typeTranslation->name = $name;
                $typeTranslation->locale = $locale;
                $typeTranslation->description = $description;
                $typeTranslation->save();
            }
        }

        return redirect()
            ->route('types.index', $type)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KpiType $type)
    {
        $this->authorize('delete', $type);

        $type->delete();

        return redirect()
            ->route('types.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
