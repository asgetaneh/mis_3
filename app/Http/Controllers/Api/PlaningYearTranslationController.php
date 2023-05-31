<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\PlaningYearTranslation;
use App\Http\Resources\PlaningYearTranslationResource;
use App\Http\Resources\PlaningYearTranslationCollection;
use App\Http\Requests\PlaningYearTranslationStoreRequest;
use App\Http\Requests\PlaningYearTranslationUpdateRequest;

class PlaningYearTranslationController extends Controller
{
    public function index(Request $request): PlaningYearTranslationCollection
    {
        $this->authorize('view-any', PlaningYearTranslation::class);

        $search = $request->get('search', '');

        $planingYearTranslations = PlaningYearTranslation::search($search)
            ->latest()
            ->paginate();

        return new PlaningYearTranslationCollection($planingYearTranslations);
    }

    public function store(
        PlaningYearTranslationStoreRequest $request
    ): PlaningYearTranslationResource {
        $this->authorize('create', PlaningYearTranslation::class);

        $validated = $request->validated();

        $planingYearTranslation = PlaningYearTranslation::create($validated);

        return new PlaningYearTranslationResource($planingYearTranslation);
    }

    public function show(
        Request $request,
        PlaningYearTranslation $planingYearTranslation
    ): PlaningYearTranslationResource {
        $this->authorize('view', $planingYearTranslation);

        return new PlaningYearTranslationResource($planingYearTranslation);
    }

    public function update(
        PlaningYearTranslationUpdateRequest $request,
        PlaningYearTranslation $planingYearTranslation
    ): PlaningYearTranslationResource {
        $this->authorize('update', $planingYearTranslation);

        $validated = $request->validated();

        $planingYearTranslation->update($validated);

        return new PlaningYearTranslationResource($planingYearTranslation);
    }

    public function destroy(
        Request $request,
        PlaningYearTranslation $planingYearTranslation
    ): Response {
        $this->authorize('delete', $planingYearTranslation);

        $planingYearTranslation->delete();

        return response()->noContent();
    }
}
