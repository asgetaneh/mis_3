<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Strategy;
use Illuminate\View\View;
use App\Models\Objective;
use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Http\Requests\KeyPeformanceIndicatorStoreRequest;
use App\Http\Requests\KeyPeformanceIndicatorUpdateRequest;

class KeyPeformanceIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KeyPeformanceIndicator::class);

        $search = $request->get('search', '');

        $keyPeformanceIndicators = KeyPeformanceIndicator::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.key_peformance_indicators.index',
            compact('keyPeformanceIndicators', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KeyPeformanceIndicator::class);

        $objectives = Objective::pluck('id', 'id');
        $strategies = Strategy::pluck('id', 'id');
        $users = User::pluck('name', 'id');
        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.key_peformance_indicators.create',
            compact('objectives', 'strategies', 'users', 'reportingPeriodTypes')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        KeyPeformanceIndicatorStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', KeyPeformanceIndicator::class);

        $validated = $request->validated();

        $keyPeformanceIndicator = KeyPeformanceIndicator::create($validated);

        return redirect()
            ->route('key-peformance-indicators.edit', $keyPeformanceIndicator)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): View {
        $this->authorize('view', $keyPeformanceIndicator);

        return view(
            'app.key_peformance_indicators.show',
            compact('keyPeformanceIndicator')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): View {
        $this->authorize('update', $keyPeformanceIndicator);

        $objectives = Objective::pluck('id', 'id');
        $strategies = Strategy::pluck('id', 'id');
        $users = User::pluck('name', 'id');
        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.key_peformance_indicators.edit',
            compact(
                'keyPeformanceIndicator',
                'objectives',
                'strategies',
                'users',
                'reportingPeriodTypes'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KeyPeformanceIndicatorUpdateRequest $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): RedirectResponse {
        $this->authorize('update', $keyPeformanceIndicator);

        $validated = $request->validated();

        $keyPeformanceIndicator->update($validated);

        return redirect()
            ->route('key-peformance-indicators.edit', $keyPeformanceIndicator)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): RedirectResponse {
        $this->authorize('delete', $keyPeformanceIndicator);

        $keyPeformanceIndicator->delete();

        return redirect()
            ->route('key-peformance-indicators.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
