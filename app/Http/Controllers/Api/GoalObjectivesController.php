<?php

namespace App\Http\Controllers\Api;

use App\Models\Goal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ObjectiveResource;
use App\Http\Resources\ObjectiveCollection;

class GoalObjectivesController extends Controller
{
    public function index(Request $request, Goal $goal): ObjectiveCollection
    {
        $this->authorize('view', $goal);

        $search = $request->get('search', '');

        $objectives = $goal
            ->objectives()
            ->search($search)
            ->latest()
            ->paginate();

        return new ObjectiveCollection($objectives);
    }

    public function store(Request $request, Goal $goal): ObjectiveResource
    {
        $this->authorize('create', Objective::class);

        $validated = $request->validate([
            'perspective_id' => ['required', 'exists:perspectives,id'],
            'created_by_id' => ['required', 'exists:users,id'],
            'updated_by_id' => ['required', 'exists:users,id'],
            'weight' => ['required', 'numeric'],
        ]);

        $objective = $goal->objectives()->create($validated);

        return new ObjectiveResource($objective);
    }
}
