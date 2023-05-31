<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodTypeTResource;
use App\Http\Resources\ReportingPeriodTypeTCollection;

class ReportingPeriodTypeReportingPeriodTypeTSController extends Controller
{
    public function index(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): ReportingPeriodTypeTCollection {
        $this->authorize('view', $reportingPeriodType);

        $search = $request->get('search', '');

        $reportingPeriodTypeTs = $reportingPeriodType
            ->reportingPeriodTypeTs()
            ->search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodTypeTCollection($reportingPeriodTypeTs);
    }

    public function store(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): ReportingPeriodTypeTResource {
        $this->authorize('create', ReportingPeriodTypeT::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ]);

        $reportingPeriodTypeT = $reportingPeriodType
            ->reportingPeriodTypeTs()
            ->create($validated);

        return new ReportingPeriodTypeTResource($reportingPeriodTypeT);
    }
}
