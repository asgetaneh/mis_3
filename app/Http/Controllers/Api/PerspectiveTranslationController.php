<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\PerspectiveTranslation;
use App\Http\Resources\PerspectiveTranslationResource;
use App\Http\Resources\PerspectiveTranslationCollection;
use App\Http\Requests\PerspectiveTranslationStoreRequest;
use App\Http\Requests\PerspectiveTranslationUpdateRequest;

class PerspectiveTranslationController extends Controller
{
    public function index(Request $request): PerspectiveTranslationCollection
    {
        $this->authorize('view-any', PerspectiveTranslation::class);

        $search = $request->get('search', '');

        $perspectiveTranslations = PerspectiveTranslation::search($search)
            ->latest()
            ->paginate();

        return new PerspectiveTranslationCollection($perspectiveTranslations);
    }

    public function store(
        PerspectiveTranslationStoreRequest $request
    ): PerspectiveTranslationResource {
        $this->authorize('create', PerspectiveTranslation::class);

        $validated = $request->validated();

        $perspectiveTranslation = PerspectiveTranslation::create($validated);

        return new PerspectiveTranslationResource($perspectiveTranslation);
    }

    public function show(
        Request $request,
        PerspectiveTranslation $perspectiveTranslation
    ): PerspectiveTranslationResource {
        $this->authorize('view', $perspectiveTranslation);

        return new PerspectiveTranslationResource($perspectiveTranslation);
    }

    public function update(
        PerspectiveTranslationUpdateRequest $request,
        PerspectiveTranslation $perspectiveTranslation
    ): PerspectiveTranslationResource {
        $this->authorize('update', $perspectiveTranslation);

        $validated = $request->validated();

        $perspectiveTranslation->update($validated);

        return new PerspectiveTranslationResource($perspectiveTranslation);
    }

    public function destroy(
        Request $request,
        PerspectiveTranslation $perspectiveTranslation
    ): Response {
        $this->authorize('delete', $perspectiveTranslation);

        $perspectiveTranslation->delete();

        return response()->noContent();
    }
}
