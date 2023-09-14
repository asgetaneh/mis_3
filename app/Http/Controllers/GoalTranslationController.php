<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\GoalTranslation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GoalTranslationStoreRequest;
use App\Http\Requests\GoalTranslationUpdateRequest;

class GoalTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', GoalTranslation::class);

        $search = $request->get('search', '');

        $goalTranslations = GoalTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.goal_translations.index',
            compact('goalTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', GoalTranslation::class);

        $goals = Goal::pluck('id', 'id');

        return view('app.goal_translations.create', compact('goals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        GoalTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', GoalTranslation::class);

        $validated = $request->validated();

        $goalTranslation = GoalTranslation::create($validated);

        return redirect()
            ->route('goal-translations.edit', $goalTranslation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        GoalTranslation $goalTranslation
    ): View {
        $this->authorize('view', $goalTranslation);

        return view('app.goal_translations.show', compact('goalTranslation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        GoalTranslation $goalTranslation
    ): View {
        $this->authorize('update', $goalTranslation);

        $goals = Goal::pluck('id', 'id');

        return view(
            'app.goal_translations.edit',
            compact('goalTranslation', 'goals')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        GoalTranslationUpdateRequest $request,
        GoalTranslation $goalTranslation
    ): RedirectResponse {
        $this->authorize('update', $goalTranslation);

        $validated = $request->validated();

        $goalTranslation->update($validated);

        return redirect()
            ->route('goal-translations.edit', $goalTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        GoalTranslation $goalTranslation
    ): RedirectResponse {
        $this->authorize('delete', $goalTranslation);

        $goalTranslation->delete();

        return redirect()
            ->route('goal-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
