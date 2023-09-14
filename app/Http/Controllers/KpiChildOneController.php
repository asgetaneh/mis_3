<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\View\View;
use App\Models\KpiChildOne;
use App\Models\KpiChildTwo;
use App\Models\KpiChildTwoTranslation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Http\Requests\KpiChildOneStoreRequest;
use App\Http\Requests\KpiChildOneUpdateRequest;
use App\Models\KpiChildOneTranslation;

class KpiChildOneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KpiChildOne::class);

        $search = $request->get('search', '');

        $kpiChildOnes = KpiChildOne::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.kpi_child_ones.index',
            compact('kpiChildOnes', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KpiChildOne::class);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.kpi_child_ones.create',
            compact('keyPeformanceIndicators')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KpiChildOneStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', KpiChildOne::class);

        $validated = $request->validated();

        $kpiChildOne = KpiChildOne::create($validated);

        return redirect()
            ->route('kpi-child-ones.edit', $kpiChildOne)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, KpiChildOne $kpiChildOne): View
    {
        $this->authorize('view', $kpiChildOne);

        return view('app.kpi_child_ones.show', compact('kpiChildOne'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, KpiChildOne $kpiChildOne): View
    {
        $this->authorize('update', $kpiChildOne);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.kpi_child_ones.edit',
            compact('kpiChildOne', 'keyPeformanceIndicators')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KpiChildOneUpdateRequest $request,
        KpiChildOne $kpiChildOne
    ): RedirectResponse {
        $this->authorize('update', $kpiChildOne);

        $validated = $request->validated();

        $kpiChildOne->update($validated);

        return redirect()
            ->route('kpi-child-ones.edit', $kpiChildOne)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KpiChildOne $kpiChildOne
    ): RedirectResponse {
        $this->authorize('delete', $kpiChildOne);

        $kpiChildOne->delete();

        return redirect()
            ->route('kpi-child-ones.index')
            ->withSuccess(__('crud.common.removed'));
    }

    
}
