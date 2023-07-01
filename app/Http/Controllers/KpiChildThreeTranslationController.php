<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\KpiChildThree;
use Illuminate\Http\RedirectResponse;
use App\Models\KpiChildThreeTranslation;
use App\Http\Requests\KpiChildThreeTranslationStoreRequest;
use App\Http\Requests\KpiChildThreeTranslationUpdateRequest;

class KpiChildThreeTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KpiChildThreeTranslation::class);

        $search = $request->get('search', '');

        $kpiChildThreeTranslations = KpiChildThreeTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'app.kpi_child_three_translations.index',
            compact('kpiChildThreeTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KpiChildThreeTranslation::class);

        $kpiChildThrees = KpiChildThree::pluck('id', 'id');
        $search = $request->get('search', '');

        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.kpi_child_three_translations.create',
            compact('kpiChildThrees', 'languages')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $this->authorize('create', KpiChildThreeTranslation::class);

        $data = $request->input();
        $language = Language::all();

        try {
            $kpiChildThree = new kpiChildThree;
            // $kpiChildThree->kpi_child_two_id= auth()->user()->id;
            $kpiChildThree->save();

            foreach ($language as $key => $value) {
                // code...
                $kpiChildThreeTranslation = new KpiChildThreeTranslation;
                $kpiChildThreeTranslation->kpiChildThree_id=$kpiChildThree->id;
                $kpiChildThreeTranslation->name = $data['name'.$value->locale];
                $kpiChildThreeTranslation->locale = $value->locale;
                $kpiChildThreeTranslation->description = $data['description'.$value->locale];
                $kpiChildThreeTranslation->save();
         }
         return redirect()
            ->route('kpi-child-three-translations.index', $kpiChildThree)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('kpi-child-three-translations/create')->withErrors(['errors' => $e]);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        KpiChildThreeTranslation $kpiChildThreeTranslation
    ): View {
        $this->authorize('view', $kpiChildThreeTranslation);

        return view(
            'app.kpi_child_three_translations.show',
            compact('kpiChildThreeTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        KpiChildThreeTranslation $kpiChildThreeTranslation
    ): View {
        $this->authorize('update', $kpiChildThreeTranslation);

        $kpiChildThrees = KpiChildThree::pluck('id', 'id');

        return view(
            'app.kpi_child_three_translations.edit',
            compact('kpiChildThreeTranslation', 'kpiChildThrees')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KpiChildThreeTranslationUpdateRequest $request,
        KpiChildThreeTranslation $kpiChildThreeTranslation
    ): RedirectResponse {
        $this->authorize('update', $kpiChildThreeTranslation);

        $validated = $request->validated();

        $kpiChildThreeTranslation->update($validated);

        return redirect()
            ->route(
                'kpi-child-three-translations.edit',
                $kpiChildThreeTranslation
            )
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KpiChildThreeTranslation $kpiChildThreeTranslation
    ): RedirectResponse {
        $this->authorize('delete', $kpiChildThreeTranslation);

        $kpiChildThreeTranslation->delete();

        return redirect()
            ->route('kpi-child-three-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
