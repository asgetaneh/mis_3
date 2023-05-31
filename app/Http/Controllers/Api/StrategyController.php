<?php

namespace App\Http\Controllers\Api;

use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\StrategyResource;
use App\Http\Resources\StrategyCollection;
use App\Http\Requests\StrategyStoreRequest;
use App\Http\Requests\StrategyUpdateRequest;

class StrategyController extends Controller
{
    public function index(Request $request): StrategyCollection
    {
        $this->authorize('view-any', Strategy::class);

        $search = $request->get('search', '');

        $strategies = Strategy::search($search)
            ->latest()
            ->paginate();

        return new StrategyCollection($strategies);
    }

    public function store(StrategyStoreRequest $request): StrategyResource
    {
        $this->authorize('create', Strategy::class);

        $validated = $request->validated();

        $strategy = Strategy::create($validated);

        return new StrategyResource($strategy);
    }

    public function show(Request $request, Strategy $strategy): StrategyResource
    {
        $this->authorize('view', $strategy);

        return new StrategyResource($strategy);
    }

    public function update(
        StrategyUpdateRequest $request,
        Strategy $strategy
    ): StrategyResource {
        $this->authorize('update', $strategy);

        $validated = $request->validated();

        $strategy->update($validated);

        return new StrategyResource($strategy);
    }

    public function destroy(Request $request, Strategy $strategy): Response
    {
        $this->authorize('delete', $strategy);

        $strategy->delete();

        return response()->noContent();
    }
}
