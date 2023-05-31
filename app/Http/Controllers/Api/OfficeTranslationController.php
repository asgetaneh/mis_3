<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\OfficeTranslation;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeTranslationResource;
use App\Http\Resources\OfficeTranslationCollection;
use App\Http\Requests\OfficeTranslationStoreRequest;
use App\Http\Requests\OfficeTranslationUpdateRequest;

class OfficeTranslationController extends Controller
{
    public function index(Request $request): OfficeTranslationCollection
    {
        $this->authorize('view-any', OfficeTranslation::class);

        $search = $request->get('search', '');

        $officeTranslations = OfficeTranslation::search($search)
            ->latest()
            ->paginate();

        return new OfficeTranslationCollection($officeTranslations);
    }

    public function store(
        OfficeTranslationStoreRequest $request
    ): OfficeTranslationResource {
        $this->authorize('create', OfficeTranslation::class);

        $validated = $request->validated();

        $officeTranslation = OfficeTranslation::create($validated);

        return new OfficeTranslationResource($officeTranslation);
    }

    public function show(
        Request $request,
        OfficeTranslation $officeTranslation
    ): OfficeTranslationResource {
        $this->authorize('view', $officeTranslation);

        return new OfficeTranslationResource($officeTranslation);
    }

    public function update(
        OfficeTranslationUpdateRequest $request,
        OfficeTranslation $officeTranslation
    ): OfficeTranslationResource {
        $this->authorize('update', $officeTranslation);

        $validated = $request->validated();

        $officeTranslation->update($validated);

        return new OfficeTranslationResource($officeTranslation);
    }

    public function destroy(
        Request $request,
        OfficeTranslation $officeTranslation
    ): Response {
        $this->authorize('delete', $officeTranslation);

        $officeTranslation->delete();

        return response()->noContent();
    }
}
