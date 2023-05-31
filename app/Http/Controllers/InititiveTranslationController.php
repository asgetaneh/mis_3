<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Inititive;
use Illuminate\Http\Request;
use App\Models\InititiveTranslation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\InititiveTranslationStoreRequest;
use App\Http\Requests\InititiveTranslationUpdateRequest;

class InititiveTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', InititiveTranslation::class);

        $search = $request->get('search', '');

        $inititiveTranslations = InititiveTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.inititive_translations.index',
            compact('inititiveTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', InititiveTranslation::class);

        $inititives = Inititive::pluck('id', 'id');

        return view('app.inititive_translations.create', compact('inititives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        InititiveTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', InititiveTranslation::class);

        $validated = $request->validated();

        $inititiveTranslation = InititiveTranslation::create($validated);

        return redirect()
            ->route('inititive-translations.edit', $inititiveTranslation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        InititiveTranslation $inititiveTranslation
    ): View {
        $this->authorize('view', $inititiveTranslation);

        return view(
            'app.inititive_translations.show',
            compact('inititiveTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        InititiveTranslation $inititiveTranslation
    ): View {
        $this->authorize('update', $inititiveTranslation);

        $inititives = Inititive::pluck('id', 'id');

        return view(
            'app.inititive_translations.edit',
            compact('inititiveTranslation', 'inititives')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        InititiveTranslationUpdateRequest $request,
        InititiveTranslation $inititiveTranslation
    ): RedirectResponse {
        $this->authorize('update', $inititiveTranslation);

        $validated = $request->validated();

        $inititiveTranslation->update($validated);

        return redirect()
            ->route('inititive-translations.edit', $inititiveTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        InititiveTranslation $inititiveTranslation
    ): RedirectResponse {
        $this->authorize('delete', $inititiveTranslation);

        $inititiveTranslation->delete();

        return redirect()
            ->route('inititive-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
