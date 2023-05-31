<?php

namespace App\Http\Controllers\Api;

use App\Models\Objective;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KeyPeformanceIndicatorResource;
use App\Http\Resources\KeyPeformanceIndicatorCollection;

class ObjectiveKeyPeformanceIndicatorsController extends Controller
{
    public function index(
        Request $request,
        Objective $objective
    ): KeyPeformanceIndicatorCollection {
        $this->authorize('view', $objective);

        $search = $request->get('search', '');

        $keyPeformanceIndicators = $objective
            ->keyPeformanceIndicators()
            ->search($search)
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorCollection($keyPeformanceIndicators);
    }

    public function store(
        Request $request,
        Objective $objective
    ): KeyPeformanceIndicatorResource {
        $this->authorize('create', KeyPeformanceIndicator::class);

        $validated = $request->validate([
            'weight' => ['required', 'numeric'],
            'strategy_id' => ['required', 'exists:strategies,id'],
            'created_by_id' => ['required', 'exists:users,id'],
            'reporting_period_type_id' => [
                'required',
                'exists:reporting_period_types,id',
            ],
        ]);

        $keyPeformanceIndicator = $objective
            ->keyPeformanceIndicators()
            ->create($validated);

        return new KeyPeformanceIndicatorResource($keyPeformanceIndicator);
    }
}
