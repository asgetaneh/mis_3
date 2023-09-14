<?php

namespace App\Http\Controllers\Api;

use App\Models\Perspective;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PerspectiveTranslationResource;
use App\Http\Resources\PerspectiveTranslationCollection;

class PerspectivePerspectiveTranslationsController extends Controller
{
    public function index(
        Request $request,
        Perspective $perspective
    ): PerspectiveTranslationCollection {
        $this->authorize('view', $perspective);

        $search = $request->get('search', '');

        $perspectiveTranslations = $perspective
            ->perspectiveTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new PerspectiveTranslationCollection($perspectiveTranslations);
    }

    public function store(
        Request $request,
        Perspective $perspective
    ): PerspectiveTranslationResource {
        $this->authorize('create', PerspectiveTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ]);

        $perspectiveTranslation = $perspective
            ->perspectiveTranslations()
            ->create($validated);

        return new PerspectiveTranslationResource($perspectiveTranslation);
    }
}
