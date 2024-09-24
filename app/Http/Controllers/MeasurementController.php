<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\View\View;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\MeasurementTranslation;

class MeasurementController extends Controller
{
    public function add(Request $request, $id = null): View
    {
        $this->authorize('view-any', MeasurementTranslation::class);

        $search = $request->get('search', '');

        $MeasurementTranslations = MeasurementTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();
        $url = $request->fullUrl();
        return view(
            'app.kpi_measurements.index',
            compact('MeasurementTranslations', 'search')
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null): View
    {
        $this->authorize('view-any', MeasurementTranslation::class);

        $search = $request->get('search', '');

        $MeasurementTranslations = MeasurementTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();
        $url = $request->fullUrl();
        return view(
            'app.kpi_measurements.index',
            compact('MeasurementTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $search = $request->get('search', '');
        $this->authorize('create', MeasurementTranslation::class);
        $Measurements = MeasurementTranslation::pluck('id', 'id');

        $languages = Language::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'app.kpi_measurements.create',
            compact('Measurements', 'languages')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $this->authorize('create', MeasurementTranslation::class);

        $data = $request->input();
        $language = Language::all();
        try {
            $Measurement = new Measurement;
            $Measurement->slug = $data['slug'];
            $Measurement->save();

            foreach ($language as $key => $value) {
                $measurementTranslation = new MeasurementTranslation;
                $measurementTranslation->translation_id = $Measurement->id;
                $measurementTranslation->name = $data['name_' . $value->locale];
                // $measurementTranslation->slug = $data['slug_' . $value->locale];
                $measurementTranslation->locale = $value->locale;
                $measurementTranslation->description = $data['description_' . $value->locale];
                $measurementTranslation->save();
            }

            return redirect()
                ->route('kpi-measurements.index')
                ->withSuccess(__('crud.common.created'));
        } catch (\Exception $e) {
            return redirect('kpi_measurements/new')->withErrors(['errors' => $e]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        MeasurementTranslation $MeasurementTranslation
    ): View {
        $this->authorize('view', $MeasurementTranslation);

        return view(
            'app.kpi_measurements.show',
            compact('MeasurementTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        MeasurementTranslation $kpiMeasurement
    ): View {
        $this->authorize('update', $kpiMeasurement);
        $Measurements = Measurement::pluck('id', 'id');

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $measurementTranslations = $kpiMeasurement->measurement->measurementTranslations->groupBy('locale');

        return view(
            'app.kpi_measurements.edit',
            compact('kpiMeasurement', 'Measurements', 'measurementTranslations', 'languages')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        MeasurementTranslation $kpiMeasurement
    ): RedirectResponse {
        $this->authorize('update', $kpiMeasurement);

        $isNewLangAdded = false;
        $localeArray = [];

        $kpiMeasurement?->measurement?->update([
            'slug' => $request['slug']
        ]);

        foreach ($request->except('_token', '_method', 'slug') as $key => $value) {

            $locale = str_replace(['name_', 'description_'], '', $key);

            $measurementTranslation = $kpiMeasurement->measurement->measurementTranslations->where('locale', $locale)->first();

            $column = str_replace('_' . $locale, '', $key);

            if ($measurementTranslation) {
                $measurementTranslation->update([
                    $column => $value
                ]);
            } else {
                $isNewLangAdded = true;
                array_push($localeArray, $locale);
            }
        }

        // handle editing if new language was added but translation has no recored for the new language
        if ($isNewLangAdded) {
            $localeArray = array_unique($localeArray);
            foreach ($localeArray as $locale) {
                // dd($localeArray);

                $loc = $locale;
                $inputName = 'name_' . $loc;
                $inputDescription = 'description_' . $loc;
                // $inputSlug = 'slug_' . $loc;

                $name = $request->input($inputName);
                $description = $request->input($inputDescription);
                // $slug = $request->input($inputSlug);

                $MeasurementT = new MeasurementTranslation;
                $MeasurementT->name = $name;
                $MeasurementT->locale = $locale;
                $MeasurementT->description = $description;
                // $MeasurementT->slug = $slug;
                $MeasurementT->save();
            }
        }

        return redirect()
            ->route('kpi-measurements.index', $kpiMeasurement)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        MeasurementTranslation $MeasurementTranslation
    ): RedirectResponse {
        $this->authorize('delete', $MeasurementTranslation);
        $MeasurementTranslation->Measurement->delete();

        return redirect()
            ->route('kpi-child-one-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
