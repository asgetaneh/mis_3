<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use App\Models\ReportingPeriodT;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ReportingPeriodTStoreRequest;
use App\Http\Requests\ReportingPeriodTUpdateRequest;

class ReportingPeriodTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', ReportingPeriodT::class);

        $search = $request->get('search', '');

        $reportingPeriodTs = ReportingPeriodT::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.reporting_period_ts.index',
            compact('reportingPeriodTs', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', ReportingPeriodT::class);

        $reportingPeriods = ReportingPeriod::pluck('start_date', 'id');

        return view(
            'app.reporting_period_ts.create',
            compact('reportingPeriods')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ReportingPeriodTStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', ReportingPeriodT::class);

        $validated = $request->validated();

        $reportingPeriodT = ReportingPeriodT::create($validated);

        return redirect()
            ->route('reporting-period-ts.edit', $reportingPeriodT)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        ReportingPeriodT $reportingPeriodT
    ): View {
        $this->authorize('view', $reportingPeriodT);

        return view(
            'app.reporting_period_ts.show',
            compact('reportingPeriodT')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        ReportingPeriodT $reportingPeriodT
    ): View {
        $this->authorize('update', $reportingPeriodT);

        $reportingPeriods = ReportingPeriod::pluck('start_date', 'id');

        return view(
            'app.reporting_period_ts.edit',
            compact('reportingPeriodT', 'reportingPeriods')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReportingPeriodTUpdateRequest $request,
        ReportingPeriodT $reportingPeriodT
    ): RedirectResponse {
        $this->authorize('update', $reportingPeriodT);

        $validated = $request->validated();

        $reportingPeriodT->update($validated);

        return redirect()
            ->route('reporting-period-ts.edit', $reportingPeriodT)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        ReportingPeriodT $reportingPeriodT
    ): RedirectResponse {
        $this->authorize('delete', $reportingPeriodT);

        $reportingPeriodT->delete();

        return redirect()
            ->route('reporting-period-ts.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
