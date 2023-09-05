<?php

namespace App\Http\Controllers\Api;

use App\Models\PlaningYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodResource;
use App\Http\Resources\ReportingPeriodCollection;

class PlaningYearReportingPeriodsController extends Controller
{
    public function index(
        Request $request,
        PlaningYear $planingYear
    ): ReportingPeriodCollection {
        $this->authorize('view', $planingYear);

        $search = $request->get('search', '');

        $reportingPeriods = $planingYear
            ->reportingPeriods()
            ->search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodCollection($reportingPeriods);
    }

    public function store(
        Request $request,
        PlaningYear $planingYear
    ): ReportingPeriodResource {
        $this->authorize('create', ReportingPeriod::class);

        $validated = $request->validate([
            'start_date' => ['required', 'max:255', 'string'],
            'end_date' => ['required', 'max:255', 'string'],
            'reporting_period_type_id' => [
                'required',
                'exists:reporting_period_types,id',
            ],
        ]);

        $reportingPeriod = $planingYear->reportingPeriods()->create($validated);

        return new ReportingPeriodResource($reportingPeriod);
    }
}
