<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\KpiChildTwo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\KpiChildTwoTranslation;
use App\Http\Requests\KpiChildTwoTranslationStoreRequest;
use App\Http\Requests\KpiChildTwoTranslationUpdateRequest;

class KpiChildTwoTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KpiChildTwoTranslation::class);

        $search = $request->get('search', '');

        $kpiChildTwoTranslations = KpiChildTwoTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.kpi_child_two_translations.index',
            compact('kpiChildTwoTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KpiChildTwoTranslation::class);

        $kpiChildTwos = KpiChildTwo::pluck('id', 'id');

        return view(
            'app.kpi_child_two_translations.create',
            compact('kpiChildTwos')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        KpiChildTwoTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', KpiChildTwoTranslation::class);

        $validated = $request->validated();

        $kpiChildTwoTranslation = KpiChildTwoTranslation::create($validated);

        return redirect()
            ->route('kpi-child-two-translations.edit', $kpiChildTwoTranslation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        KpiChildTwoTranslation $kpiChildTwoTranslation
    ): View {
        $this->authorize('view', $kpiChildTwoTranslation);

        return view(
            'app.kpi_child_two_translations.show',
            compact('kpiChildTwoTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        KpiChildTwoTranslation $kpiChildTwoTranslation
    ): View {
        $this->authorize('update', $kpiChildTwoTranslation);

        $kpiChildTwos = KpiChildTwo::pluck('id', 'id');

        return view(
            'app.kpi_child_two_translations.edit',
            compact('kpiChildTwoTranslation', 'kpiChildTwos')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KpiChildTwoTranslationUpdateRequest $request,
        KpiChildTwoTranslation $kpiChildTwoTranslation
    ): RedirectResponse {
        $this->authorize('update', $kpiChildTwoTranslation);

        $validated = $request->validated();

        $kpiChildTwoTranslation->update($validated);

        return redirect()
            ->route('kpi-child-two-translations.edit', $kpiChildTwoTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KpiChildTwoTranslation $kpiChildTwoTranslation
    ): RedirectResponse {
        $this->authorize('delete', $kpiChildTwoTranslation);

        $kpiChildTwoTranslation->delete();

        return redirect()
            ->route('kpi-child-two-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
