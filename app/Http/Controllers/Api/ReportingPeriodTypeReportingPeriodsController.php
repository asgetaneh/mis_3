<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodResource;
use App\Http\Resources\ReportingPeriodCollection;

class ReportingPeriodTypeReportingPeriodsController extends Controller
{
    public function index(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): ReportingPeriodCollection {
        $this->authorize('view', $reportingPeriodType);

        $search = $request->get('search', '');

        $reportingPeriods = $reportingPeriodType
            ->reportingPeriods()
            ->search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodCollection($reportingPeriods);
    }

    public function store(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): ReportingPeriodResource {
        $this->authorize('create', ReportingPeriod::class);

        $validated = $request->validate([
            'planing_year_id' => ['required', 'exists:planing_years,id'],
            'start_date' => ['required', 'max:255', 'string'],
            'end_date' => ['required', 'max:255', 'string'],
        ]);

        $reportingPeriod = $reportingPeriodType
            ->reportingPeriods()
            ->create($validated);

        return new ReportingPeriodResource($reportingPeriod);
    }
}
