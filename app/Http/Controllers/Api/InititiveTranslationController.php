<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\InititiveTranslation;
use App\Http\Controllers\Controller;
use App\Http\Resources\InititiveTranslationResource;
use App\Http\Resources\InititiveTranslationCollection;
use App\Http\Requests\InititiveTranslationStoreRequest;
use App\Http\Requests\InititiveTranslationUpdateRequest;

class InititiveTranslationController extends Controller
{
    public function index(Request $request): InititiveTranslationCollection
    {
        $this->authorize('view-any', InititiveTranslation::class);

        $search = $request->get('search', '');

        $inititiveTranslations = InititiveTranslation::search($search)
            ->latest()
            ->paginate();

        return new InititiveTranslationCollection($inititiveTranslations);
    }

    public function store(
        InititiveTranslationStoreRequest $request
    ): InititiveTranslationResource {
        $this->authorize('create', InititiveTranslation::class);

        $validated = $request->validated();

        $inititiveTranslation = InititiveTranslation::create($validated);

        return new InititiveTranslationResource($inititiveTranslation);
    }

    public function show(
        Request $request,
        InititiveTranslation $inititiveTranslation
    ): InititiveTranslationResource {
        $this->authorize('view', $inititiveTranslation);

        return new InititiveTranslationResource($inititiveTranslation);
    }

    public function update(
        InititiveTranslationUpdateRequest $request,
        InititiveTranslation $inititiveTranslation
    ): InititiveTranslationResource {
        $this->authorize('update', $inititiveTranslation);

        $validated = $request->validated();

        $inititiveTranslation->update($validated);

        return new InititiveTranslationResource($inititiveTranslation);
    }

    public function destroy(
        Request $request,
        InititiveTranslation $inititiveTranslation
    ): Response {
        $this->authorize('delete', $inititiveTranslation);

        $inititiveTranslation->delete();

        return response()->noContent();
    }
}
