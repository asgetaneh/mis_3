<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\StrategyTranslation;
use App\Http\Controllers\Controller;
use App\Http\Resources\StrategyTranslationResource;
use App\Http\Resources\StrategyTranslationCollection;
use App\Http\Requests\StrategyTranslationStoreRequest;
use App\Http\Requests\StrategyTranslationUpdateRequest;

class StrategyTranslationController extends Controller
{
    public function index(Request $request): StrategyTranslationCollection
    {
        $this->authorize('view-any', StrategyTranslation::class);

        $search = $request->get('search', '');

        $strategyTranslations = StrategyTranslation::search($search)
            ->latest()
            ->paginate();

        return new StrategyTranslationCollection($strategyTranslations);
    }

    public function store(
        StrategyTranslationStoreRequest $request
    ): StrategyTranslationResource {
        $this->authorize('create', StrategyTranslation::class);

        $validated = $request->validated();

        $strategyTranslation = StrategyTranslation::create($validated);

        return new StrategyTranslationResource($strategyTranslation);
    }

    public function show(
        Request $request,
        StrategyTranslation $strategyTranslation
    ): StrategyTranslationResource {
        $this->authorize('view', $strategyTranslation);

        return new StrategyTranslationResource($strategyTranslation);
    }

    public function update(
        StrategyTranslationUpdateRequest $request,
        StrategyTranslation $strategyTranslation
    ): StrategyTranslationResource {
        $this->authorize('update', $strategyTranslation);

        $validated = $request->validated();

        $strategyTranslation->update($validated);

        return new StrategyTranslationResource($strategyTranslation);
    }

    public function destroy(
        Request $request,
        StrategyTranslation $strategyTranslation
    ): Response {
        $this->authorize('delete', $strategyTranslation);

        $strategyTranslation->delete();

        return response()->noContent();
    }
}
