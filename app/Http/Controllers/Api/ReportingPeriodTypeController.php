<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ReportingPeriodType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodTypeResource;
use App\Http\Resources\ReportingPeriodTypeCollection;
use App\Http\Requests\ReportingPeriodTypeStoreRequest;
use App\Http\Requests\ReportingPeriodTypeUpdateRequest;

class ReportingPeriodTypeController extends Controller
{
    public function index(Request $request): ReportingPeriodTypeCollection
    {
        $this->authorize('view-any', ReportingPeriodType::class);

        $search = $request->get('search', '');

        $reportingPeriodTypes = ReportingPeriodType::search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodTypeCollection($reportingPeriodTypes);
    }

    public function store(
        ReportingPeriodTypeStoreRequest $request
    ): ReportingPeriodTypeResource {
        $this->authorize('create', ReportingPeriodType::class);

        $validated = $request->validated();

        $reportingPeriodType = ReportingPeriodType::create($validated);

        return new ReportingPeriodTypeResource($reportingPeriodType);
    }

    public function show(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): ReportingPeriodTypeResource {
        $this->authorize('view', $reportingPeriodType);

        return new ReportingPeriodTypeResource($reportingPeriodType);
    }

    public function update(
        ReportingPeriodTypeUpdateRequest $request,
        ReportingPeriodType $reportingPeriodType
    ): ReportingPeriodTypeResource {
        $this->authorize('update', $reportingPeriodType);

        $validated = $request->validated();

        $reportingPeriodType->update($validated);

        return new ReportingPeriodTypeResource($reportingPeriodType);
    }

    public function destroy(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): Response {
        $this->authorize('delete', $reportingPeriodType);

        $reportingPeriodType->delete();

        return response()->noContent();
    }
}
