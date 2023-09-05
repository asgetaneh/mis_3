<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\StrategyTranslation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StrategyTranslationStoreRequest;
use App\Http\Requests\StrategyTranslationUpdateRequest;

class StrategyTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', StrategyTranslation::class);

        $search = $request->get('search', '');

        $strategyTranslations = StrategyTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.strategy_translations.index',
            compact('strategyTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', StrategyTranslation::class);

        $strategies = Strategy::pluck('id', 'id');

        return view('app.strategy_translations.create', compact('strategies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StrategyTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', StrategyTranslation::class);

        $validated = $request->validated();

        $strategyTranslation = StrategyTranslation::create($validated);

        return redirect()
            ->route('strategy-translations.edit', $strategyTranslation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        StrategyTranslation $strategyTranslation
    ): View {
        $this->authorize('view', $strategyTranslation);

        return view(
            'app.strategy_translations.show',
            compact('strategyTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        StrategyTranslation $strategyTranslation
    ): View {
        $this->authorize('update', $strategyTranslation);

        $strategies = Strategy::pluck('id', 'id');

        return view(
            'app.strategy_translations.edit',
            compact('strategyTranslation', 'strategies')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        StrategyTranslationUpdateRequest $request,
        StrategyTranslation $strategyTranslation
    ): RedirectResponse {
        $this->authorize('update', $strategyTranslation);

        $validated = $request->validated();

        $strategyTranslation->update($validated);

        return redirect()
            ->route('strategy-translations.edit', $strategyTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        StrategyTranslation $strategyTranslation
    ): RedirectResponse {
        $this->authorize('delete', $strategyTranslation);

        $strategyTranslation->delete();

        return redirect()
            ->route('strategy-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
