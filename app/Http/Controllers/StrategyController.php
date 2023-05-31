<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Strategy;
use Illuminate\View\View;
use App\Models\Objective;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StrategyStoreRequest;
use App\Http\Requests\StrategyUpdateRequest;

class StrategyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Strategy::class);

        $search = $request->get('search', '');

        $strategies = Strategy::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.strategies.index', compact('strategies', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Strategy::class);

        $objectives = Objective::pluck('id', 'id');
        $users = User::pluck('name', 'id');

        return view(
            'app.strategies.create',
            compact('objectives', 'users', 'users')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StrategyStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Strategy::class);

        $validated = $request->validated();

        $strategy = Strategy::create($validated);

        return redirect()
            ->route('strategies.edit', $strategy)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Strategy $strategy): View
    {
        $this->authorize('view', $strategy);

        return view('app.strategies.show', compact('strategy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Strategy $strategy): View
    {
        $this->authorize('update', $strategy);

        $objectives = Objective::pluck('id', 'id');
        $users = User::pluck('name', 'id');

        return view(
            'app.strategies.edit',
            compact('strategy', 'objectives', 'users', 'users')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        StrategyUpdateRequest $request,
        Strategy $strategy
    ): RedirectResponse {
        $this->authorize('update', $strategy);

        $validated = $request->validated();

        $strategy->update($validated);

        return redirect()
            ->route('strategies.edit', $strategy)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Strategy $strategy
    ): RedirectResponse {
        $this->authorize('delete', $strategy);

        $strategy->delete();

        return redirect()
            ->route('strategies.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
