<?php

namespace App\Http\Controllers\Api;

use App\Models\Strategy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StrategyTranslationResource;
use App\Http\Resources\StrategyTranslationCollection;

class StrategyStrategyTranslationsController extends Controller
{
    public function index(
        Request $request,
        Strategy $strategy
    ): StrategyTranslationCollection {
        $this->authorize('view', $strategy);

        $search = $request->get('search', '');

        $strategyTranslations = $strategy
            ->strategyTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new StrategyTranslationCollection($strategyTranslations);
    }

    public function store(
        Request $request,
        Strategy $strategy
    ): StrategyTranslationResource {
        $this->authorize('create', StrategyTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'discription' => ['required', 'max:255', 'string'],
        ]);

        $strategyTranslation = $strategy
            ->strategyTranslations()
            ->create($validated);

        return new StrategyTranslationResource($strategyTranslation);
    }
}
