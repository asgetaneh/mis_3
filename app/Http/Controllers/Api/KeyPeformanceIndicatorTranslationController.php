<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\KeyPeformanceIndicatorTranslation;
use App\Http\Resources\KeyPeformanceIndicatorTranslationResource;
use App\Http\Resources\KeyPeformanceIndicatorTranslationCollection;
use App\Http\Requests\KeyPeformanceIndicatorTranslationStoreRequest;
use App\Http\Requests\KeyPeformanceIndicatorTranslationUpdateRequest;

class KeyPeformanceIndicatorTranslationController extends Controller
{
    public function index(
        Request $request
    ): KeyPeformanceIndicatorTranslationCollection {
        $this->authorize('view-any', KeyPeformanceIndicatorTranslation::class);

        $search = $request->get('search', '');

        $keyPeformanceIndicatorTranslations = KeyPeformanceIndicatorTranslation::search(
            $search
        )
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorTranslationCollection(
            $keyPeformanceIndicatorTranslations
        );
    }

    public function store(
        KeyPeformanceIndicatorTranslationStoreRequest $request
    ): KeyPeformanceIndicatorTranslationResource {
        $this->authorize('create', KeyPeformanceIndicatorTranslation::class);

        $validated = $request->validated();

        $keyPeformanceIndicatorTranslation = KeyPeformanceIndicatorTranslation::create(
            $validated
        );

        return new KeyPeformanceIndicatorTranslationResource(
            $keyPeformanceIndicatorTranslation
        );
    }

    public function show(
        Request $request,
        KeyPeformanceIndicatorTranslation $keyPeformanceIndicatorTranslation
    ): KeyPeformanceIndicatorTranslationResource {
        $this->authorize('view', $keyPeformanceIndicatorTranslation);

        return new KeyPeformanceIndicatorTranslationResource(
            $keyPeformanceIndicatorTranslation
        );
    }

    public function update(
        KeyPeformanceIndicatorTranslationUpdateRequest $request,
        KeyPeformanceIndicatorTranslation $keyPeformanceIndicatorTranslation
    ): KeyPeformanceIndicatorTranslationResource {
        $this->authorize('update', $keyPeformanceIndicatorTranslation);

        $validated = $request->validated();

        $keyPeformanceIndicatorTranslation->update($validated);

        return new KeyPeformanceIndicatorTranslationResource(
            $keyPeformanceIndicatorTranslation
        );
    }

    public function destroy(
        Request $request,
        KeyPeformanceIndicatorTranslation $keyPeformanceIndicatorTranslation
    ): Response {
        $this->authorize('delete', $keyPeformanceIndicatorTranslation);

        $keyPeformanceIndicatorTranslation->delete();

        return response()->noContent();
    }
}
