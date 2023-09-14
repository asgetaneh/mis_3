<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\GenderTranslation;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenderTranslationResource;
use App\Http\Resources\GenderTranslationCollection;
use App\Http\Requests\GenderTranslationStoreRequest;
use App\Http\Requests\GenderTranslationUpdateRequest;

class GenderTranslationController extends Controller
{
    public function index(Request $request): GenderTranslationCollection
    {
        $this->authorize('view-any', GenderTranslation::class);

        $search = $request->get('search', '');

        $genderTranslations = GenderTranslation::search($search)
            ->latest()
            ->paginate();

        return new GenderTranslationCollection($genderTranslations);
    }

    public function store(
        GenderTranslationStoreRequest $request
    ): GenderTranslationResource {
        $this->authorize('create', GenderTranslation::class);

        $validated = $request->validated();

        $genderTranslation = GenderTranslation::create($validated);

        return new GenderTranslationResource($genderTranslation);
    }

    public function show(
        Request $request,
        GenderTranslation $genderTranslation
    ): GenderTranslationResource {
        $this->authorize('view', $genderTranslation);

        return new GenderTranslationResource($genderTranslation);
    }

    public function update(
        GenderTranslationUpdateRequest $request,
        GenderTranslation $genderTranslation
    ): GenderTranslationResource {
        $this->authorize('update', $genderTranslation);

        $validated = $request->validated();

        $genderTranslation->update($validated);

        return new GenderTranslationResource($genderTranslation);
    }

    public function destroy(
        Request $request,
        GenderTranslation $genderTranslation
    ): Response {
        $this->authorize('delete', $genderTranslation);

        $genderTranslation->delete();

        return response()->noContent();
    }
}
