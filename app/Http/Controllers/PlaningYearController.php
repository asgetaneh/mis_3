<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\PlaningYear;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PlaningYearStoreRequest;
use App\Http\Requests\PlaningYearUpdateRequest;

class PlaningYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', PlaningYear::class);

        $search = $request->get('search', '');

        $planingYears = PlaningYear::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.planing_years.index',
            compact('planingYears', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', PlaningYear::class);

        return view('app.planing_years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlaningYearStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', PlaningYear::class);

        $validated = $request->validated();

        $planingYear = PlaningYear::create($validated);

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

        return view('app.planing_years.edit', compact('planingYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        PlaningYearUpdateRequest $request,
        PlaningYear $planingYear
    ): RedirectResponse {
        $this->authorize('update', $planingYear);

        $validated = $request->validated();

        $planingYear->update($validated);

        return redirect()
            ->route('planing-years.edit', $planingYear)
            ->withSuccess(__('crud.common.saved'));
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
