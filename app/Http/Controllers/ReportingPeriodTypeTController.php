<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use App\Models\ReportingPeriodTypeT;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ReportingPeriodTypeTStoreRequest;
use App\Http\Requests\ReportingPeriodTypeTUpdateRequest;

class ReportingPeriodTypeTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', ReportingPeriodTypeT::class);

        $search = $request->get('search', '');

        $reportingPeriodTypeTs = ReportingPeriodTypeT::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.reporting_period_type_ts.index',
            compact('reportingPeriodTypeTs', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', ReportingPeriodTypeT::class);

        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.reporting_period_type_ts.create',
            compact('reportingPeriodTypes')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ReportingPeriodTypeTStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', ReportingPeriodTypeT::class);

        $validated = $request->validated();

        $reportingPeriodTypeT = ReportingPeriodTypeT::create($validated);

        return redirect()
            ->route('reporting-period-type-ts.edit', $reportingPeriodTypeT)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        ReportingPeriodTypeT $reportingPeriodTypeT
    ): View {
        $this->authorize('view', $reportingPeriodTypeT);

        return view(
            'app.reporting_period_type_ts.show',
            compact('reportingPeriodTypeT')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        ReportingPeriodTypeT $reportingPeriodTypeT
    ): View {
        $this->authorize('update', $reportingPeriodTypeT);

        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.reporting_period_type_ts.edit',
            compact('reportingPeriodTypeT', 'reportingPeriodTypes')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReportingPeriodTypeTUpdateRequest $request,
        ReportingPeriodTypeT $reportingPeriodTypeT
    ): RedirectResponse {
        $this->authorize('update', $reportingPeriodTypeT);

        $validated = $request->validated();

        $reportingPeriodTypeT->update($validated);

        return redirect()
            ->route('reporting-period-type-ts.edit', $reportingPeriodTypeT)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        ReportingPeriodTypeT $reportingPeriodTypeT
    ): RedirectResponse {
        $this->authorize('delete', $reportingPeriodTypeT);

        $reportingPeriodTypeT->delete();

        return redirect()
            ->route('reporting-period-type-ts.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
