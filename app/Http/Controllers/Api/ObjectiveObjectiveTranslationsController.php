<?php

namespace App\Http\Controllers\Api;

use App\Models\Objective;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ObjectiveTranslationResource;
use App\Http\Resources\ObjectiveTranslationCollection;

class ObjectiveObjectiveTranslationsController extends Controller
{
    public function index(
        Request $request,
        Objective $objective
    ): ObjectiveTranslationCollection {
        $this->authorize('view', $objective);

        $search = $request->get('search', '');

        $objectiveTranslations = $objective
            ->objectiveTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new ObjectiveTranslationCollection($objectiveTranslations);
    }

    public function store(
        Request $request,
        Objective $objective
    ): ObjectiveTranslationResource {
        $this->authorize('create', ObjectiveTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
            'out_put' => ['required', 'max:255', 'string'],
            'out_come' => ['required', 'max:255', 'string'],
        ]);

        $objectiveTranslation = $objective
            ->objectiveTranslations()
            ->create($validated);

        return new ObjectiveTranslationResource($objectiveTranslation);
    }
}
