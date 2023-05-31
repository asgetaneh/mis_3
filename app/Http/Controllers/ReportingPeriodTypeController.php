<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ReportingPeriodTypeStoreRequest;
use App\Http\Requests\ReportingPeriodTypeUpdateRequest;

class ReportingPeriodTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', ReportingPeriodType::class);

        $search = $request->get('search', '');

        $reportingPeriodTypes = ReportingPeriodType::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.reporting_period_types.index',
            compact('reportingPeriodTypes', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', ReportingPeriodType::class);

        return view('app.reporting_period_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ReportingPeriodTypeStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', ReportingPeriodType::class);

        $validated = $request->validated();

        $reportingPeriodType = ReportingPeriodType::create($validated);

        return redirect()
            ->route('reporting-period-types.edit', $reportingPeriodType)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): View {
        $this->authorize('view', $reportingPeriodType);

        return view(
            'app.reporting_period_types.show',
            compact('reportingPeriodType')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): View {
        $this->authorize('update', $reportingPeriodType);

        return view(
            'app.reporting_period_types.edit',
            compact('reportingPeriodType')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReportingPeriodTypeUpdateRequest $request,
        ReportingPeriodType $reportingPeriodType
    ): RedirectResponse {
        $this->authorize('update', $reportingPeriodType);

        $validated = $request->validated();

        $reportingPeriodType->update($validated);

        return redirect()
            ->route('reporting-period-types.edit', $reportingPeriodType)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): RedirectResponse {
        $this->authorize('delete', $reportingPeriodType);

        $reportingPeriodType->delete();

        return redirect()
            ->route('reporting-period-types.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
