<?php

namespace App\Http\Controllers\Api;

use App\Models\Objective;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ObjectiveResource;
use App\Http\Resources\ObjectiveCollection;
use App\Http\Requests\ObjectiveStoreRequest;
use App\Http\Requests\ObjectiveUpdateRequest;

class ObjectiveController extends Controller
{
    public function index(Request $request): ObjectiveCollection
    {
        $this->authorize('view-any', Objective::class);

        $search = $request->get('search', '');

        $objectives = Objective::search($search)
            ->latest()
            ->paginate();

        return new ObjectiveCollection($objectives);
    }

    public function store(ObjectiveStoreRequest $request): ObjectiveResource
    {
        $this->authorize('create', Objective::class);

        $validated = $request->validated();

        $objective = Objective::create($validated);

        return new ObjectiveResource($objective);
    }

    public function show(
        Request $request,
        Objective $objective
    ): ObjectiveResource {
        $this->authorize('view', $objective);

        return new ObjectiveResource($objective);
    }

    public function update(
        ObjectiveUpdateRequest $request,
        Objective $objective
    ): ObjectiveResource {
        $this->authorize('update', $objective);

        $validated = $request->validated();

        $objective->update($validated);

        return new ObjectiveResource($objective);
    }

    public function destroy(Request $request, Objective $objective): Response
    {
        $this->authorize('delete', $objective);

        $objective->delete();

        return response()->noContent();
    }
}
