<?php

namespace App\Http\Controllers\Api;

use App\Models\PlaningYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlaningYearTranslationResource;
use App\Http\Resources\PlaningYearTranslationCollection;

class PlaningYearPlaningYearTranslationsController extends Controller
{
    public function index(
        Request $request,
        PlaningYear $planingYear
    ): PlaningYearTranslationCollection {
        $this->authorize('view', $planingYear);

        $search = $request->get('search', '');

        $planingYearTranslations = $planingYear
            ->planingYearTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new PlaningYearTranslationCollection($planingYearTranslations);
    }

    public function store(
        Request $request,
        PlaningYear $planingYear
    ): PlaningYearTranslationResource {
        $this->authorize('create', PlaningYearTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ]);

        $planingYearTranslation = $planingYear
            ->planingYearTranslations()
            ->create($validated);

        return new PlaningYearTranslationResource($planingYearTranslation);
    }
}
