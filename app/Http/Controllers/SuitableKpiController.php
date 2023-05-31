<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\View\View;
use App\Models\SuitableKpi;
use App\Models\PlaningYear;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Http\Requests\SuitableKpiStoreRequest;
use App\Http\Requests\SuitableKpiUpdateRequest;

class SuitableKpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', SuitableKpi::class);

        $search = $request->get('search', '');

        $suitableKpis = SuitableKpi::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.suitable_kpis.index',
            compact('suitableKpis', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', SuitableKpi::class);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');
        $offices = Office::pluck('id', 'id');
        $planingYears = PlaningYear::pluck('id', 'id');

        return view(
            'app.suitable_kpis.create',
            compact('keyPeformanceIndicators', 'offices', 'planingYears')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuitableKpiStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', SuitableKpi::class);

        $validated = $request->validated();

        $suitableKpi = SuitableKpi::create($validated);

        return redirect()
            ->route('suitable-kpis.edit', $suitableKpi)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, SuitableKpi $suitableKpi): View
    {
        $this->authorize('view', $suitableKpi);

        return view('app.suitable_kpis.show', compact('suitableKpi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, SuitableKpi $suitableKpi): View
    {
        $this->authorize('update', $suitableKpi);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');
        $offices = Office::pluck('id', 'id');
        $planingYears = PlaningYear::pluck('id', 'id');

        return view(
            'app.suitable_kpis.edit',
            compact(
                'suitableKpi',
                'keyPeformanceIndicators',
                'offices',
                'planingYears'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        SuitableKpiUpdateRequest $request,
        SuitableKpi $suitableKpi
    ): RedirectResponse {
        $this->authorize('update', $suitableKpi);

        $validated = $request->validated();

        $suitableKpi->update($validated);

        return redirect()
            ->route('suitable-kpis.edit', $suitableKpi)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        SuitableKpi $suitableKpi
    ): RedirectResponse {
        $this->authorize('delete', $suitableKpi);

        $suitableKpi->delete();

        return redirect()
            ->route('suitable-kpis.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
