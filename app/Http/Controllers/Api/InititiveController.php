<?php

namespace App\Http\Controllers\Api;

use App\Models\Inititive;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\InititiveResource;
use App\Http\Resources\InititiveCollection;
use App\Http\Requests\InititiveStoreRequest;
use App\Http\Requests\InititiveUpdateRequest;

class InititiveController extends Controller
{
    public function index(Request $request): InititiveCollection
    {
        $this->authorize('view-any', Inititive::class);

        $search = $request->get('search', '');

        $inititives = Inititive::search($search)
            ->latest()
            ->paginate();

        return new InititiveCollection($inititives);
    }

    public function store(InititiveStoreRequest $request): InititiveResource
    {
        $this->authorize('create', Inititive::class);

        $validated = $request->validated();

        $inititive = Inititive::create($validated);

        return new InititiveResource($inititive);
    }

    public function show(
        Request $request,
        Inititive $inititive
    ): InititiveResource {
        $this->authorize('view', $inititive);

        return new InititiveResource($inititive);
    }

    public function update(
        InititiveUpdateRequest $request,
        Inititive $inititive
    ): InititiveResource {
        $this->authorize('update', $inititive);

        $validated = $request->validated();

        $inititive->update($validated);

        return new InititiveResource($inititive);
    }

    public function destroy(Request $request, Inititive $inititive): Response
    {
        $this->authorize('delete', $inititive);

        $inititive->delete();

        return response()->noContent();
    }
}
