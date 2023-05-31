<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\User;
use App\Models\Objective;
use App\Models\Language;
use App\Models\GoalTranslation;
use Illuminate\View\View;
use App\Models\Perspective;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ObjectiveStoreRequest;
use App\Http\Requests\ObjectiveUpdateRequest;

class ObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Objective::class);

        $search = $request->get('search', '');

        $objectives = Objective::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.objectives.index', compact('objectives', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Objective::class);
        $search = $request->get('search', '');
        $goals = Goal::all();
         $perspectives = Perspective::all();
        $users = User::pluck('name', 'id');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.objectives.create',
            compact('goals', 'perspectives', 'users', 'users', 'languages')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ObjectiveStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Objective::class);

        $validated = $request->validated();

        $objective = Objective::create($validated);

        return redirect()
            ->route('objectives.edit', $objective)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Objective $objective): View
    {
        $this->authorize('view', $objective);

        return view('app.objectives.show', compact('objective'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Objective $objective): View
    {
        $this->authorize('update', $objective);

        $goals = Goal::pluck('id', 'id');
        $perspectives = Perspective::pluck('id', 'id');
        $users = User::pluck('name', 'id');

        return view(
            'app.objectives.edit',
            compact('objective', 'goals', 'perspectives', 'users', 'users')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ObjectiveUpdateRequest $request,
        Objective $objective
    ): RedirectResponse {
        $this->authorize('update', $objective);

        $validated = $request->validated();

        $objective->update($validated);

        return redirect()
            ->route('objectives.edit', $objective)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Objective $objective
    ): RedirectResponse {
        $this->authorize('delete', $objective);

        $objective->delete();

        return redirect()
            ->route('objectives.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
