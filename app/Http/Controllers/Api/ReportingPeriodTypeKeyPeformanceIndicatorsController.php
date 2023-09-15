<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use App\Http\Controllers\Controller;
use App\Http\Resources\KeyPeformanceIndicatorResource;
use App\Http\Resources\KeyPeformanceIndicatorCollection;

class ReportingPeriodTypeKeyPeformanceIndicatorsController extends Controller
{
    public function index(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): KeyPeformanceIndicatorCollection {
        $this->authorize('view', $reportingPeriodType);

        $search = $request->get('search', '');

        $keyPeformanceIndicators = $reportingPeriodType
            ->keyPeformanceIndicators()
            ->search($search)
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorCollection($keyPeformanceIndicators);
    }

    public function store(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): KeyPeformanceIndicatorResource {
        $this->authorize('create', KeyPeformanceIndicator::class);

        $validated = $request->validate([
            'weight' => ['required', 'numeric'],
            'objective_id' => ['required', 'exists:objectives,id'],
            'strategy_id' => ['required', 'exists:strategies,id'],
            'created_by_id' => ['required', 'exists:users,id'],
        ]);

        $keyPeformanceIndicator = $reportingPeriodType
            ->keyPeformanceIndicators()
            ->create($validated);

        return new KeyPeformanceIndicatorResource($keyPeformanceIndicator);
    }
}
