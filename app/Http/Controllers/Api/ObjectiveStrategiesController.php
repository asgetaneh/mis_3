<?php

namespace App\Http\Controllers\Api;

use App\Models\Objective;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StrategyResource;
use App\Http\Resources\StrategyCollection;

class ObjectiveStrategiesController extends Controller
{
    public function index(
        Request $request,
        Objective $objective
    ): StrategyCollection {
        $this->authorize('view', $objective);

        $search = $request->get('search', '');

        $strategies = $objective
            ->strategies()
            ->search($search)
            ->latest()
            ->paginate();

        return new StrategyCollection($strategies);
    }

    public function store(
        Request $request,
        Objective $objective
    ): StrategyResource {
        $this->authorize('create', Strategy::class);

        $validated = $request->validate([
            'created_by_id' => ['required', 'exists:users,id'],
            'updated_by_id' => ['required', 'exists:users,id'],
        ]);

        $strategy = $objective->strategies()->create($validated);

        return new StrategyResource($strategy);
    }
}
