<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ReportingPeriodTypeT;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodTypeTResource;
use App\Http\Resources\ReportingPeriodTypeTCollection;
use App\Http\Requests\ReportingPeriodTypeTStoreRequest;
use App\Http\Requests\ReportingPeriodTypeTUpdateRequest;

class ReportingPeriodTypeTController extends Controller
{
    public function index(Request $request): ReportingPeriodTypeTCollection
    {
        $this->authorize('view-any', ReportingPeriodTypeT::class);

        $search = $request->get('search', '');

        $reportingPeriodTypeTs = ReportingPeriodTypeT::search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodTypeTCollection($reportingPeriodTypeTs);
    }

    public function store(
        ReportingPeriodTypeTStoreRequest $request
    ): ReportingPeriodTypeTResource {
        $this->authorize('create', ReportingPeriodTypeT::class);

        $validated = $request->validated();

        $reportingPeriodTypeT = ReportingPeriodTypeT::create($validated);

        return new ReportingPeriodTypeTResource($reportingPeriodTypeT);
    }

    public function show(
        Request $request,
        ReportingPeriodTypeT $reportingPeriodTypeT
    ): ReportingPeriodTypeTResource {
        $this->authorize('view', $reportingPeriodTypeT);

        return new ReportingPeriodTypeTResource($reportingPeriodTypeT);
    }

    public function update(
        ReportingPeriodTypeTUpdateRequest $request,
        ReportingPeriodTypeT $reportingPeriodTypeT
    ): ReportingPeriodTypeTResource {
        $this->authorize('update', $reportingPeriodTypeT);

        $validated = $request->validated();

        $reportingPeriodTypeT->update($validated);

        return new ReportingPeriodTypeTResource($reportingPeriodTypeT);
    }

    public function destroy(
        Request $request,
        ReportingPeriodTypeT $reportingPeriodTypeT
    ): Response {
        $this->authorize('delete', $reportingPeriodTypeT);

        $reportingPeriodTypeT->delete();

        return response()->noContent();
    }
}
