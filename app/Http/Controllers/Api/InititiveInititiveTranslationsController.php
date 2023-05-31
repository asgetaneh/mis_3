<?php

namespace App\Http\Controllers\Api;

use App\Models\Inititive;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\InititiveTranslationResource;
use App\Http\Resources\InititiveTranslationCollection;

class InititiveInititiveTranslationsController extends Controller
{
    public function index(
        Request $request,
        Inititive $inititive
    ): InititiveTranslationCollection {
        $this->authorize('view', $inititive);

        $search = $request->get('search', '');

        $inititiveTranslations = $inititive
            ->inititiveTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new InititiveTranslationCollection($inititiveTranslations);
    }

    public function store(
        Request $request,
        Inititive $inititive
    ): InititiveTranslationResource {
        $this->authorize('create', InititiveTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ]);

        $inititiveTranslation = $inititive
            ->inititiveTranslations()
            ->create($validated);

        return new InititiveTranslationResource($inititiveTranslation);
    }
}
