<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodTResource;
use App\Http\Resources\ReportingPeriodTCollection;

class ReportingPeriodReportingPeriodTSController extends Controller
{
    public function index(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): ReportingPeriodTCollection {
        $this->authorize('view', $reportingPeriod);

        $search = $request->get('search', '');

        $reportingPeriodTs = $reportingPeriod
            ->reportingPeriodTs()
            ->search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodTCollection($reportingPeriodTs);
    }

    public function store(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): ReportingPeriodTResource {
        $this->authorize('create', ReportingPeriodT::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ]);

        $reportingPeriodT = $reportingPeriod
            ->reportingPeriodTs()
            ->create($validated);

        return new ReportingPeriodTResource($reportingPeriodT);
    }
}
