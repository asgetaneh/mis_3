<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\PlaningYear;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\PlaningYearTranslation;
use App\Http\Requests\PlaningYearTranslationStoreRequest;
use App\Http\Requests\PlaningYearTranslationUpdateRequest;

class PlaningYearTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', PlaningYearTranslation::class);

        $search = $request->get('search', '');

        $planingYearTranslations = PlaningYearTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.planing_year_translations.index',
            compact('planingYearTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', PlaningYearTranslation::class);

        $planingYears = PlaningYear::pluck('id', 'id');

        return view(
            'app.planing_year_translations.create',
            compact('planingYears')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        PlaningYearTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', PlaningYearTranslation::class);

        $validated = $request->validated();

        $planingYearTranslation = PlaningYearTranslation::create($validated);

        return redirect()
            ->route('planing-year-translations.edit', $planingYearTranslation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        PlaningYearTranslation $planingYearTranslation
    ): View {
        $this->authorize('view', $planingYearTranslation);

        return view(
            'app.planing_year_translations.show',
            compact('planingYearTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        PlaningYearTranslation $planingYearTranslation
    ): View {
        $this->authorize('update', $planingYearTranslation);

        $planingYears = PlaningYear::pluck('id', 'id');

        return view(
            'app.planing_year_translations.edit',
            compact('planingYearTranslation', 'planingYears')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        PlaningYearTranslationUpdateRequest $request,
        PlaningYearTranslation $planingYearTranslation
    ): RedirectResponse {
        $this->authorize('update', $planingYearTranslation);

        $validated = $request->validated();

        $planingYearTranslation->update($validated);

        return redirect()
            ->route('planing-year-translations.edit', $planingYearTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        PlaningYearTranslation $planingYearTranslation
    ): RedirectResponse {
        $this->authorize('delete', $planingYearTranslation);

        $planingYearTranslation->delete();

        return redirect()
            ->route('planing-year-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
