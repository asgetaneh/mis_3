<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\KpiChildTwo;
use Illuminate\Http\Request;
use App\Models\KpiChildThree;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\KpiChildThreeStoreRequest;
use App\Http\Requests\KpiChildThreeUpdateRequest;

class KpiChildThreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KpiChildThree::class);

        $search = $request->get('search', '');

        $kpiChildThrees = KpiChildThree::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.kpi_child_threes.index',
            compact('kpiChildThrees', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KpiChildThree::class);

        $kpiChildTwos = KpiChildTwo::pluck('id', 'id');

        return view('app.kpi_child_threes.create', compact('kpiChildTwos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KpiChildThreeStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', KpiChildThree::class);

        $validated = $request->validated();

        $kpiChildThree = KpiChildThree::create($validated);

        return redirect()
            ->route('kpi-child-threes.edit', $kpiChildThree)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, KpiChildThree $kpiChildThree): View
    {
        $this->authorize('view', $kpiChildThree);

        return view('app.kpi_child_threes.show', compact('kpiChildThree'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, KpiChildThree $kpiChildThree): View
    {
        $this->authorize('update', $kpiChildThree);

        $kpiChildTwos = KpiChildTwo::pluck('id', 'id');

        return view(
            'app.kpi_child_threes.edit',
            compact('kpiChildThree', 'kpiChildTwos')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KpiChildThreeUpdateRequest $request,
        KpiChildThree $kpiChildThree
    ): RedirectResponse {
        $this->authorize('update', $kpiChildThree);

        $validated = $request->validated();

        $kpiChildThree->update($validated);

        return redirect()
            ->route('kpi-child-threes.edit', $kpiChildThree)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KpiChildThree $kpiChildThree
    ): RedirectResponse {
        $this->authorize('delete', $kpiChildThree);

        $kpiChildThree->delete();

        return redirect()
            ->route('kpi-child-threes.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
