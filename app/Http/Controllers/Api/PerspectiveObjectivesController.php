<?php

namespace App\Http\Controllers\Api;

use App\Models\Perspective;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ObjectiveResource;
use App\Http\Resources\ObjectiveCollection;

class PerspectiveObjectivesController extends Controller
{
    public function index(
        Request $request,
        Perspective $perspective
    ): ObjectiveCollection {
        $this->authorize('view', $perspective);

        $search = $request->get('search', '');

        $objectives = $perspective
            ->objectives()
            ->search($search)
            ->latest()
            ->paginate();

        return new ObjectiveCollection($objectives);
    }

    public function store(
        Request $request,
        Perspective $perspective
    ): ObjectiveResource {
        $this->authorize('create', Objective::class);

        $validated = $request->validate([
            'goal_id' => ['required', 'exists:goals,id'],
            'created_by_id' => ['required', 'exists:users,id'],
            'updated_by_id' => ['required', 'exists:users,id'],
            'weight' => ['required', 'numeric'],
        ]);

        $objective = $perspective->objectives()->create($validated);

        return new ObjectiveResource($objective);
    }
}
