<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\OfficeTranslation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OfficeTranslationStoreRequest;
use App\Http\Requests\OfficeTranslationUpdateRequest;

class OfficeTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', OfficeTranslation::class);

        $search = $request->get('search', '');

        $officeTranslations = OfficeTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.office_translations.index',
            compact('officeTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', OfficeTranslation::class);

        $offices = Office::pluck('id', 'id');

        return view('app.office_translations.create', compact('offices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        OfficeTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', OfficeTranslation::class);

        $validated = $request->validated();

        $officeTranslation = OfficeTranslation::create($validated);

        return redirect()
            ->route('office-translations.edit', $officeTranslation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        OfficeTranslation $officeTranslation
    ): View {
        $this->authorize('view', $officeTranslation);

        return view(
            'app.office_translations.show',
            compact('officeTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        OfficeTranslation $officeTranslation
    ): View {
        $this->authorize('update', $officeTranslation);

        $offices = Office::pluck('id', 'id');

        return view(
            'app.office_translations.edit',
            compact('officeTranslation', 'offices')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        OfficeTranslationUpdateRequest $request,
        OfficeTranslation $officeTranslation
    ): RedirectResponse {
        $this->authorize('update', $officeTranslation);

        $validated = $request->validated();

        $officeTranslation->update($validated);

        return redirect()
            ->route('office-translations.edit', $officeTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        OfficeTranslation $officeTranslation
    ): RedirectResponse {
        $this->authorize('delete', $officeTranslation);

        $officeTranslation->delete();

        return redirect()
            ->route('office-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
