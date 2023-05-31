<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Models\KeyPeformanceIndicatorTranslation;
use App\Http\Requests\KeyPeformanceIndicatorTranslationStoreRequest;
use App\Http\Requests\KeyPeformanceIndicatorTranslationUpdateRequest;

class KeyPeformanceIndicatorTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KeyPeformanceIndicatorTranslation::class);

        $search = $request->get('search', '');

        $keyPeformanceIndicatorTranslations = KeyPeformanceIndicatorTranslation::search(
            $search
        )
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.key_peformance_indicator_translations.index',
            compact('keyPeformanceIndicatorTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KeyPeformanceIndicatorTranslation::class);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.key_peformance_indicator_translations.create',
            compact('keyPeformanceIndicators')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        KeyPeformanceIndicatorTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', KeyPeformanceIndicatorTranslation::class);

        $validated = $request->validated();

        $keyPeformanceIndicatorTranslation = KeyPeformanceIndicatorTranslation::create(
            $validated
        );

        return redirect()
            ->route(
                'key-peformance-indicator-translations.edit',
                $keyPeformanceIndicatorTranslation
            )
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        KeyPeformanceIndicatorTranslation $keyPeformanceIndicatorTranslation
    ): View {
        $this->authorize('view', $keyPeformanceIndicatorTranslation);

        return view(
            'app.key_peformance_indicator_translations.show',
            compact('keyPeformanceIndicatorTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        KeyPeformanceIndicatorTranslation $keyPeformanceIndicatorTranslation
    ): View {
        $this->authorize('update', $keyPeformanceIndicatorTranslation);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.key_peformance_indicator_translations.edit',
            compact(
                'keyPeformanceIndicatorTranslation',
                'keyPeformanceIndicators'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KeyPeformanceIndicatorTranslationUpdateRequest $request,
        KeyPeformanceIndicatorTranslation $keyPeformanceIndicatorTranslation
    ): RedirectResponse {
        $this->authorize('update', $keyPeformanceIndicatorTranslation);

        $validated = $request->validated();

        $keyPeformanceIndicatorTranslation->update($validated);

        return redirect()
            ->route(
                'key-peformance-indicator-translations.edit',
                $keyPeformanceIndicatorTranslation
            )
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KeyPeformanceIndicatorTranslation $keyPeformanceIndicatorTranslation
    ): RedirectResponse {
        $this->authorize('delete', $keyPeformanceIndicatorTranslation);

        $keyPeformanceIndicatorTranslation->delete();

        return redirect()
            ->route('key-peformance-indicator-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
