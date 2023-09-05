<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ObjectiveTranslation;
use App\Http\Controllers\Controller;
use App\Http\Resources\ObjectiveTranslationResource;
use App\Http\Resources\ObjectiveTranslationCollection;
use App\Http\Requests\ObjectiveTranslationStoreRequest;
use App\Http\Requests\ObjectiveTranslationUpdateRequest;

class ObjectiveTranslationController extends Controller
{
    public function index(Request $request): ObjectiveTranslationCollection
    {
        $this->authorize('view-any', ObjectiveTranslation::class);

        $search = $request->get('search', '');

        $objectiveTranslations = ObjectiveTranslation::search($search)
            ->latest()
            ->paginate();

        return new ObjectiveTranslationCollection($objectiveTranslations);
    }

    public function store(
        ObjectiveTranslationStoreRequest $request
    ): ObjectiveTranslationResource {
        $this->authorize('create', ObjectiveTranslation::class);

        $validated = $request->validated();

        $objectiveTranslation = ObjectiveTranslation::create($validated);

        return new ObjectiveTranslationResource($objectiveTranslation);
    }

    public function show(
        Request $request,
        ObjectiveTranslation $objectiveTranslation
    ): ObjectiveTranslationResource {
        $this->authorize('view', $objectiveTranslation);

        return new ObjectiveTranslationResource($objectiveTranslation);
    }

    public function update(
        ObjectiveTranslationUpdateRequest $request,
        ObjectiveTranslation $objectiveTranslation
    ): ObjectiveTranslationResource {
        $this->authorize('update', $objectiveTranslation);

        $validated = $request->validated();

        $objectiveTranslation->update($validated);

        return new ObjectiveTranslationResource($objectiveTranslation);
    }

    public function destroy(
        Request $request,
        ObjectiveTranslation $objectiveTranslation
    ): Response {
        $this->authorize('delete', $objectiveTranslation);

        $objectiveTranslation->delete();

        return response()->noContent();
    }
}
