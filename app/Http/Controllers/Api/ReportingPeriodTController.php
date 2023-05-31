<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ReportingPeriodT;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodTResource;
use App\Http\Resources\ReportingPeriodTCollection;
use App\Http\Requests\ReportingPeriodTStoreRequest;
use App\Http\Requests\ReportingPeriodTUpdateRequest;

class ReportingPeriodTController extends Controller
{
    public function index(Request $request): ReportingPeriodTCollection
    {
        $this->authorize('view-any', ReportingPeriodT::class);

        $search = $request->get('search', '');

        $reportingPeriodTs = ReportingPeriodT::search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodTCollection($reportingPeriodTs);
    }

    public function store(
        ReportingPeriodTStoreRequest $request
    ): ReportingPeriodTResource {
        $this->authorize('create', ReportingPeriodT::class);

        $validated = $request->validated();

        $reportingPeriodT = ReportingPeriodT::create($validated);

        return new ReportingPeriodTResource($reportingPeriodT);
    }

    public function show(
        Request $request,
        ReportingPeriodT $reportingPeriodT
    ): ReportingPeriodTResource {
        $this->authorize('view', $reportingPeriodT);

        return new ReportingPeriodTResource($reportingPeriodT);
    }

    public function update(
        ReportingPeriodTUpdateRequest $request,
        ReportingPeriodT $reportingPeriodT
    ): ReportingPeriodTResource {
        $this->authorize('update', $reportingPeriodT);

        $validated = $request->validated();

        $reportingPeriodT->update($validated);

        return new ReportingPeriodTResource($reportingPeriodT);
    }

    public function destroy(
        Request $request,
        ReportingPeriodT $reportingPeriodT
    ): Response {
        $this->authorize('delete', $reportingPeriodT);

        $reportingPeriodT->delete();

        return response()->noContent();
    }
}
