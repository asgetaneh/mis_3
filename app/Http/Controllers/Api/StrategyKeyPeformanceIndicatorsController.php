<?php

namespace App\Http\Controllers\Api;

use App\Models\Strategy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KeyPeformanceIndicatorResource;
use App\Http\Resources\KeyPeformanceIndicatorCollection;

class StrategyKeyPeformanceIndicatorsController extends Controller
{
    public function index(
        Request $request,
        Strategy $strategy
    ): KeyPeformanceIndicatorCollection {
        $this->authorize('view', $strategy);

        $search = $request->get('search', '');

        $keyPeformanceIndicators = $strategy
            ->keyPeformanceIndicators()
            ->search($search)
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorCollection($keyPeformanceIndicators);
    }

    public function store(
        Request $request,
        Strategy $strategy
    ): KeyPeformanceIndicatorResource {
        $this->authorize('create', KeyPeformanceIndicator::class);

        $validated = $request->validate([
            'weight' => ['required', 'numeric'],
            'objective_id' => ['required', 'exists:objectives,id'],
            'created_by_id' => ['required', 'exists:users,id'],
            'reporting_period_type_id' => [
                'required',
                'exists:reporting_period_types,id',
            ],
        ]);

        $keyPeformanceIndicator = $strategy
            ->keyPeformanceIndicators()
            ->create($validated);

        return new KeyPeformanceIndicatorResource($keyPeformanceIndicator);
    }
}
