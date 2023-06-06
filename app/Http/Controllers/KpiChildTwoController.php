<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\KpiChildTwo;
use App\Models\KpiChildOne;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\KpiChildTwoStoreRequest;
use App\Http\Requests\KpiChildTwoUpdateRequest;

class KpiChildTwoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KpiChildTwo::class);

        $search = $request->get('search', '');

        $kpiChildTwos = KpiChildTwo::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.kpi_child_twos.index',
            compact('kpiChildTwos', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KpiChildTwo::class);

        $kpiChildOnes = KpiChildOne::pluck('id', 'id');

        return view('app.kpi_child_twos.create', compact('kpiChildOnes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KpiChildTwoStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', KpiChildTwo::class);

        $validated = $request->validated();

        $kpiChildTwo = KpiChildTwo::create($validated);

        return redirect()
            ->route('kpi-child-twos.edit', $kpiChildTwo)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, KpiChildTwo $kpiChildTwo): View
    {
        $this->authorize('view', $kpiChildTwo);

        return view('app.kpi_child_twos.show', compact('kpiChildTwo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, KpiChildTwo $kpiChildTwo): View
    {
        $this->authorize('update', $kpiChildTwo);

        $kpiChildOnes = KpiChildOne::pluck('id', 'id');

        return view(
            'app.kpi_child_twos.edit',
            compact('kpiChildTwo', 'kpiChildOnes')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KpiChildTwoUpdateRequest $request,
        KpiChildTwo $kpiChildTwo
    ): RedirectResponse {
        $this->authorize('update', $kpiChildTwo);

        $validated = $request->validated();

        $kpiChildTwo->update($validated);

        return redirect()
            ->route('kpi-child-twos.edit', $kpiChildTwo)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KpiChildTwo $kpiChildTwo
    ): RedirectResponse {
        $this->authorize('delete', $kpiChildTwo);

        $kpiChildTwo->delete();

        return redirect()
            ->route('kpi-child-twos.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
