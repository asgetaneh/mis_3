<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\PlaningYear;
use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use App\Models\ReportingPeriodType;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ReportingPeriodStoreRequest;
use App\Http\Requests\ReportingPeriodUpdateRequest;

class ReportingPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', ReportingPeriod::class);

        $search = $request->get('search', '');

        $reportingPeriods = ReportingPeriod::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.reporting_periods.index',
            compact('reportingPeriods', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', ReportingPeriod::class);

        $planingYears = PlaningYear::pluck('id', 'id');
        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.reporting_periods.create',
            compact('planingYears', 'reportingPeriodTypes')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ReportingPeriodStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', ReportingPeriod::class);

        $validated = $request->validated();

        $reportingPeriod = ReportingPeriod::create($validated);

        return redirect()
            ->route('reporting-periods.edit', $reportingPeriod)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): View {
        $this->authorize('view', $reportingPeriod);

        return view('app.reporting_periods.show', compact('reportingPeriod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): View {
        $this->authorize('update', $reportingPeriod);

        $planingYears = PlaningYear::pluck('id', 'id');
        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.reporting_periods.edit',
            compact('reportingPeriod', 'planingYears', 'reportingPeriodTypes')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReportingPeriodUpdateRequest $request,
        ReportingPeriod $reportingPeriod
    ): RedirectResponse {
        $this->authorize('update', $reportingPeriod);

        $validated = $request->validated();

        $reportingPeriod->update($validated);

        return redirect()
            ->route('reporting-periods.edit', $reportingPeriod)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): RedirectResponse {
        $this->authorize('delete', $reportingPeriod);

        $reportingPeriod->delete();

        return redirect()
            ->route('reporting-periods.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
