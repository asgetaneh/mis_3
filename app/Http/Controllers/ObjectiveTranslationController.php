<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Objective;
use Illuminate\Http\Request;
use App\Models\ObjectiveTranslation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ObjectiveTranslationStoreRequest;
use App\Http\Requests\ObjectiveTranslationUpdateRequest;

class ObjectiveTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', ObjectiveTranslation::class);

        $search = $request->get('search', '');

        $objectiveTranslations = ObjectiveTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.objective_translations.index',
            compact('objectiveTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', ObjectiveTranslation::class);

        $objectives = Objective::pluck('id', 'id');

        return view('app.objective_translations.create', compact('objectives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ObjectiveTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', ObjectiveTranslation::class);

        $validated = $request->validated();

        $objectiveTranslation = ObjectiveTranslation::create($validated);

        return redirect()
            ->route('objective-translations.edit', $objectiveTranslation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        ObjectiveTranslation $objectiveTranslation
    ): View {
        $this->authorize('view', $objectiveTranslation);

        return view(
            'app.objective_translations.show',
            compact('objectiveTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        ObjectiveTranslation $objectiveTranslation
    ): View {
        $this->authorize('update', $objectiveTranslation);

        $objectives = Objective::pluck('id', 'id');

        return view(
            'app.objective_translations.edit',
            compact('objectiveTranslation', 'objectives')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ObjectiveTranslationUpdateRequest $request,
        ObjectiveTranslation $objectiveTranslation
    ): RedirectResponse {
        $this->authorize('update', $objectiveTranslation);

        $validated = $request->validated();

        $objectiveTranslation->update($validated);

        return redirect()
            ->route('objective-translations.edit', $objectiveTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        ObjectiveTranslation $objectiveTranslation
    ): RedirectResponse {
        $this->authorize('delete', $objectiveTranslation);

        $objectiveTranslation->delete();

        return redirect()
            ->route('objective-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
