<?php

namespace App\Http\Controllers;

use App\Models\Inititive;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Http\Requests\InititiveStoreRequest;
use App\Http\Requests\InititiveUpdateRequest;

class InititiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Inititive::class);

        $search = $request->get('search', '');

        $inititives = Inititive::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.inititives.index', compact('inititives', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Inititive::class);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.inititives.create',
            compact('keyPeformanceIndicators')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InititiveStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Inititive::class);

        $validated = $request->validated();

        $inititive = Inititive::create($validated);

        return redirect()
            ->route('inititives.edit', $inititive)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Inititive $inititive): View
    {
        $this->authorize('view', $inititive);

        return view('app.inititives.show', compact('inititive'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Inititive $inititive): View
    {
        $this->authorize('update', $inititive);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.inititives.edit',
            compact('inititive', 'keyPeformanceIndicators')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        InititiveUpdateRequest $request,
        Inititive $inititive
    ): RedirectResponse {
        $this->authorize('update', $inititive);

        $validated = $request->validated();

        $inititive->update($validated);

        return redirect()
            ->route('inititives.edit', $inititive)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Inititive $inititive
    ): RedirectResponse {
        $this->authorize('delete', $inititive);

        $inititive->delete();

        return redirect()
            ->route('inititives.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
