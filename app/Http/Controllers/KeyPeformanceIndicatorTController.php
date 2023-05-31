<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Models\KeyPeformanceIndicatorT;
use App\Http\Requests\KeyPeformanceIndicatorTStoreRequest;
use App\Http\Requests\KeyPeformanceIndicatorTUpdateRequest;

class KeyPeformanceIndicatorTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KeyPeformanceIndicatorT::class);

        $search = $request->get('search', '');

        $keyPeformanceIndicatorTs = KeyPeformanceIndicatorT::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.key_peformance_indicator_ts.index',
            compact('keyPeformanceIndicatorTs', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KeyPeformanceIndicatorT::class);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.key_peformance_indicator_ts.create',
            compact('keyPeformanceIndicators')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        KeyPeformanceIndicatorTStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', KeyPeformanceIndicatorT::class);

        $validated = $request->validated();

        $keyPeformanceIndicatorT = KeyPeformanceIndicatorT::create($validated);

        return redirect()
            ->route(
                'key-peformance-indicator-ts.edit',
                $keyPeformanceIndicatorT
            )
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        KeyPeformanceIndicatorT $keyPeformanceIndicatorT
    ): View {
        $this->authorize('view', $keyPeformanceIndicatorT);

        return view(
            'app.key_peformance_indicator_ts.show',
            compact('keyPeformanceIndicatorT')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        KeyPeformanceIndicatorT $keyPeformanceIndicatorT
    ): View {
        $this->authorize('update', $keyPeformanceIndicatorT);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.key_peformance_indicator_ts.edit',
            compact('keyPeformanceIndicatorT', 'keyPeformanceIndicators')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KeyPeformanceIndicatorTUpdateRequest $request,
        KeyPeformanceIndicatorT $keyPeformanceIndicatorT
    ): RedirectResponse {
        $this->authorize('update', $keyPeformanceIndicatorT);

        $validated = $request->validated();

        $keyPeformanceIndicatorT->update($validated);

        return redirect()
            ->route(
                'key-peformance-indicator-ts.edit',
                $keyPeformanceIndicatorT
            )
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KeyPeformanceIndicatorT $keyPeformanceIndicatorT
    ): RedirectResponse {
        $this->authorize('delete', $keyPeformanceIndicatorT);

        $keyPeformanceIndicatorT->delete();

        return redirect()
            ->route('key-peformance-indicator-ts.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
