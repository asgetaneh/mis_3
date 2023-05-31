<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\GoalTranslation;
use App\Http\Controllers\Controller;
use App\Http\Resources\GoalTranslationResource;
use App\Http\Resources\GoalTranslationCollection;
use App\Http\Requests\GoalTranslationStoreRequest;
use App\Http\Requests\GoalTranslationUpdateRequest;

class GoalTranslationController extends Controller
{
    public function index(Request $request): GoalTranslationCollection
    {
        $this->authorize('view-any', GoalTranslation::class);

        $search = $request->get('search', '');

        $goalTranslations = GoalTranslation::search($search)
            ->latest()
            ->paginate();

        return new GoalTranslationCollection($goalTranslations);
    }

    public function store(
        GoalTranslationStoreRequest $request
    ): GoalTranslationResource {
        $this->authorize('create', GoalTranslation::class);

        $validated = $request->validated();

        $goalTranslation = GoalTranslation::create($validated);

        return new GoalTranslationResource($goalTranslation);
    }

    public function show(
        Request $request,
        GoalTranslation $goalTranslation
    ): GoalTranslationResource {
        $this->authorize('view', $goalTranslation);

        return new GoalTranslationResource($goalTranslation);
    }

    public function update(
        GoalTranslationUpdateRequest $request,
        GoalTranslation $goalTranslation
    ): GoalTranslationResource {
        $this->authorize('update', $goalTranslation);

        $validated = $request->validated();

        $goalTranslation->update($validated);

        return new GoalTranslationResource($goalTranslation);
    }

    public function destroy(
        Request $request,
        GoalTranslation $goalTranslation
    ): Response {
        $this->authorize('delete', $goalTranslation);

        $goalTranslation->delete();

        return response()->noContent();
    }
}
