<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KeyPeformanceIndicator;
use App\Http\Resources\KeyPeformanceIndicatorTranslationResource;
use App\Http\Resources\KeyPeformanceIndicatorTranslationCollection;

class KeyPeformanceIndicatorKeyPeformanceIndicatorTranslationsController extends
    Controller
{
    public function index(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): KeyPeformanceIndicatorTranslationCollection {
        $this->authorize('view', $keyPeformanceIndicator);

        $search = $request->get('search', '');

        $keyPeformanceIndicatorTranslations = $keyPeformanceIndicator
            ->keyPeformanceIndicatorTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorTranslationCollection(
            $keyPeformanceIndicatorTranslations
        );
    }

    public function store(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): KeyPeformanceIndicatorTranslationResource {
        $this->authorize('create', KeyPeformanceIndicatorTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
            'out_put' => ['required', 'max:255', 'string'],
            'out_come' => ['required', 'max:255', 'string'],
        ]);

        $keyPeformanceIndicatorTranslation = $keyPeformanceIndicator
            ->keyPeformanceIndicatorTranslations()
            ->create($validated);

        return new KeyPeformanceIndicatorTranslationResource(
            $keyPeformanceIndicatorTranslation
        );
    }
}
