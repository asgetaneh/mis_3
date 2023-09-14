<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\GoalResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\GoalCollection;

class UserGoalsController extends Controller
{
    public function index(Request $request, User $user): GoalCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $goals = $user
            ->goals()
            ->search($search)
            ->latest()
            ->paginate();

        return new GoalCollection($goals);
    }

    public function store(Request $request, User $user): GoalResource
    {
        $this->authorize('create', Goal::class);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
            'created_by_id' => ['required', 'max:255'],
        ]);

        $goal = $user->goals()->create($validated);

        return new GoalResource($goal);
    }
}
