<?php

namespace App\Http\Controllers\Api;

use App\Models\Perspective;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\PerspectiveResource;
use App\Http\Resources\PerspectiveCollection;
use App\Http\Requests\PerspectiveStoreRequest;
use App\Http\Requests\PerspectiveUpdateRequest;

class PerspectiveController extends Controller
{
    public function index(Request $request): PerspectiveCollection
    {
        $this->authorize('view-any', Perspective::class);

        $search = $request->get('search', '');

        $perspectives = Perspective::search($search)
            ->latest()
            ->paginate();

        return new PerspectiveCollection($perspectives);
    }

    public function store(PerspectiveStoreRequest $request): PerspectiveResource
    {
        $this->authorize('create', Perspective::class);

        $validated = $request->validated();

        $perspective = Perspective::create($validated);

        return new PerspectiveResource($perspective);
    }

    public function show(
        Request $request,
        Perspective $perspective
    ): PerspectiveResource {
        $this->authorize('view', $perspective);

        return new PerspectiveResource($perspective);
    }

    public function update(
        PerspectiveUpdateRequest $request,
        Perspective $perspective
    ): PerspectiveResource {
        $this->authorize('update', $perspective);

        $validated = $request->validated();

        $perspective->update($validated);

        return new PerspectiveResource($perspective);
    }

    public function destroy(
        Request $request,
        Perspective $perspective
    ): Response {
        $this->authorize('delete', $perspective);

        $perspective->delete();

        return response()->noContent();
    }
}
