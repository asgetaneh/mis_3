<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ObjectiveResource;
use App\Http\Resources\ObjectiveCollection;

class UserObjectivesController extends Controller
{
    public function index(Request $request, User $user): ObjectiveCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $objectives = $user
            ->objectives2()
            ->search($search)
            ->latest()
            ->paginate();

        return new ObjectiveCollection($objectives);
    }

    public function store(Request $request, User $user): ObjectiveResource
    {
        $this->authorize('create', Objective::class);

        $validated = $request->validate([
            'goal_id' => ['required', 'exists:goals,id'],
            'perspective_id' => ['required', 'exists:perspectives,id'],
            'weight' => ['required', 'numeric'],
        ]);

        $objective = $user->objectives2()->create($validated);

        return new ObjectiveResource($objective);
    }
}
