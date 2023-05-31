<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ReportingPeriod;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodResource;
use App\Http\Resources\ReportingPeriodCollection;
use App\Http\Requests\ReportingPeriodStoreRequest;
use App\Http\Requests\ReportingPeriodUpdateRequest;

class ReportingPeriodController extends Controller
{
    public function index(Request $request): ReportingPeriodCollection
    {
        $this->authorize('view-any', ReportingPeriod::class);

        $search = $request->get('search', '');

        $reportingPeriods = ReportingPeriod::search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodCollection($reportingPeriods);
    }

    public function store(
        ReportingPeriodStoreRequest $request
    ): ReportingPeriodResource {
        $this->authorize('create', ReportingPeriod::class);

        $validated = $request->validated();

        $reportingPeriod = ReportingPeriod::create($validated);

        return new ReportingPeriodResource($reportingPeriod);
    }

    public function show(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): ReportingPeriodResource {
        $this->authorize('view', $reportingPeriod);

        return new ReportingPeriodResource($reportingPeriod);
    }

    public function update(
        ReportingPeriodUpdateRequest $request,
        ReportingPeriod $reportingPeriod
    ): ReportingPeriodResource {
        $this->authorize('update', $reportingPeriod);

        $validated = $request->validated();

        $reportingPeriod->update($validated);

        return new ReportingPeriodResource($reportingPeriod);
    }

    public function destroy(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): Response {
        $this->authorize('delete', $reportingPeriod);

        $reportingPeriod->delete();

        return response()->noContent();
    }
}
