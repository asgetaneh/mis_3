<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KeyPeformanceIndicatorResource;
use App\Http\Resources\KeyPeformanceIndicatorCollection;

class UserKeyPeformanceIndicatorsController extends Controller
{
    public function index(
        Request $request,
        User $user
    ): KeyPeformanceIndicatorCollection {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $keyPeformanceIndicators = $user
            ->keyPeformanceIndicators()
            ->search($search)
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorCollection($keyPeformanceIndicators);
    }

    public function store(
        Request $request,
        User $user
    ): KeyPeformanceIndicatorResource {
        $this->authorize('create', KeyPeformanceIndicator::class);

        $validated = $request->validate([
            'weight' => ['required', 'numeric'],
            'objective_id' => ['required', 'exists:objectives,id'],
            'strategy_id' => ['required', 'exists:strategies,id'],
            'reporting_period_type_id' => [
                'required',
                'exists:reporting_period_types,id',
            ],
        ]);

        $keyPeformanceIndicator = $user
            ->keyPeformanceIndicators()
            ->create($validated);

        return new KeyPeformanceIndicatorResource($keyPeformanceIndicator);
    }
}
