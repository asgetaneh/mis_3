<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Perspective;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\PerspectiveTranslation;
use App\Http\Requests\PerspectiveTranslationStoreRequest;
use App\Http\Requests\PerspectiveTranslationUpdateRequest;

class PerspectiveTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', PerspectiveTranslation::class);

        $search = $request->get('search', '');

        $perspectiveTranslations = PerspectiveTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.perspective_translations.index',
            compact('perspectiveTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', PerspectiveTranslation::class);

        $perspectives = Perspective::pluck('id', 'id');

        return view(
            'app.perspective_translations.create',
            compact('perspectives')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        PerspectiveTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', PerspectiveTranslation::class);

        $validated = $request->validated();

        $perspectiveTranslation = PerspectiveTranslation::create($validated);

        return redirect()
            ->route('perspective-translations.edit', $perspectiveTranslation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        PerspectiveTranslation $perspectiveTranslation
    ): View {
        $this->authorize('view', $perspectiveTranslation);

        return view(
            'app.perspective_translations.show',
            compact('perspectiveTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        PerspectiveTranslation $perspectiveTranslation
    ): View {
        $this->authorize('update', $perspectiveTranslation);

        $perspectives = Perspective::pluck('id', 'id');

        return view(
            'app.perspective_translations.edit',
            compact('perspectiveTranslation', 'perspectives')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        PerspectiveTranslationUpdateRequest $request,
        PerspectiveTranslation $perspectiveTranslation
    ): RedirectResponse {
        $this->authorize('update', $perspectiveTranslation);

        $validated = $request->validated();

        $perspectiveTranslation->update($validated);

        return redirect()
            ->route('perspective-translations.edit', $perspectiveTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        PerspectiveTranslation $perspectiveTranslation
    ): RedirectResponse {
        $this->authorize('delete', $perspectiveTranslation);

        $perspectiveTranslation->delete();

        return redirect()
            ->route('perspective-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
