<?php

namespace App\Http\Controllers\Api;

use App\Models\Goal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GoalTranslationResource;
use App\Http\Resources\GoalTranslationCollection;

class GoalGoalTranslationsController extends Controller
{
    public function index(
        Request $request,
        Goal $goal
    ): GoalTranslationCollection {
        $this->authorize('view', $goal);

        $search = $request->get('search', '');

        $goalTranslations = $goal
            ->goalTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new GoalTranslationCollection($goalTranslations);
    }

    public function store(Request $request, Goal $goal): GoalTranslationResource
    {
        $this->authorize('create', GoalTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'out_put' => ['required', 'max:255', 'string'],
            'out_come' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
            'locale' => ['required', 'max:8', 'string'],
        ]);

        $goalTranslation = $goal->goalTranslations()->create($validated);

        return new GoalTranslationResource($goalTranslation);
    }
}
