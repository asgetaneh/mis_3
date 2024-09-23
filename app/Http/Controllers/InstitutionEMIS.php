<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\GenderTranslation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GenderTranslationStoreRequest;
use App\Http\Requests\GenderTranslationUpdateRequest;

class InstitutionEMIS extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', GenderTranslation::class);

        $search = $request->get('search', '');

        $genderTranslations = GenderTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.gender_translations.index',
            compact('genderTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', GenderTranslation::class);

        $genders = Gender::pluck('id', 'id');

        return view('app.gender_translations.create', compact('genders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        GenderTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', GenderTranslation::class);

        $validated = $request->validated();

        $genderTranslation = GenderTranslation::create($validated);

        return redirect()
            ->route('gender-translations.edit', $genderTranslation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        GenderTranslation $genderTranslation
    ): View {
        $this->authorize('view', $genderTranslation);

        return view(
            'app.gender_translations.show',
            compact('genderTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        GenderTranslation $genderTranslation
    ): View {
        $this->authorize('update', $genderTranslation);

        $genders = Gender::pluck('id', 'id');

        return view(
            'app.gender_translations.edit',
            compact('genderTranslation', 'genders')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        GenderTranslationUpdateRequest $request,
        GenderTranslation $genderTranslation
    ): RedirectResponse {
        $this->authorize('update', $genderTranslation);

        $validated = $request->validated();

        $genderTranslation->update($validated);

        return redirect()
            ->route('gender-translations.edit', $genderTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        GenderTranslation $genderTranslation
    ): RedirectResponse {
        $this->authorize('delete', $genderTranslation);

        $genderTranslation->delete();

        return redirect()
            ->route('gender-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
