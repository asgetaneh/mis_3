<?php

namespace App\Http\Controllers\Api;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\GoalResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\GoalCollection;
use App\Http\Requests\GoalStoreRequest;
use App\Http\Requests\GoalUpdateRequest;

class GoalController extends Controller
{
    public function index(Request $request): GoalCollection
    {
        $this->authorize('view-any', Goal::class);

        $search = $request->get('search', '');

        $goals = Goal::search($search)
            ->latest()
            ->paginate();

        return new GoalCollection($goals);
    }

    public function store(GoalStoreRequest $request): GoalResource
    {
        $this->authorize('create', Goal::class);

        $validated = $request->validated();

        $goal = Goal::create($validated);

        return new GoalResource($goal);
    }

    public function show(Request $request, Goal $goal): GoalResource
    {
        $this->authorize('view', $goal);

        return new GoalResource($goal);
    }

    public function update(GoalUpdateRequest $request, Goal $goal): GoalResource
    {
        $this->authorize('update', $goal);

        $validated = $request->validated();

        $goal->update($validated);

        return new GoalResource($goal);
    }

    public function destroy(Request $request, Goal $goal): Response
    {
        $this->authorize('delete', $goal);

        $goal->delete();

        return response()->noContent();
    }
}
