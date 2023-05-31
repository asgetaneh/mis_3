<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StrategyResource;
use App\Http\Resources\StrategyCollection;

class UserStrategiesController extends Controller
{
    public function index(Request $request, User $user): StrategyCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $strategies = $user
            ->strategies2()
            ->search($search)
            ->latest()
            ->paginate();

        return new StrategyCollection($strategies);
    }

    public function store(Request $request, User $user): StrategyResource
    {
        $this->authorize('create', Strategy::class);

        $validated = $request->validate([
            'objective_id' => ['required', 'exists:objectives,id'],
        ]);

        $strategy = $user->strategies2()->create($validated);

        return new StrategyResource($strategy);
    }
}
