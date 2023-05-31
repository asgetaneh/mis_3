<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use Illuminate\Http\RedirectResponse;
use App\Models\ReportingPeriodTranslation;
use App\Http\Requests\ReportingPeriodTranslationStoreRequest;
use App\Http\Requests\ReportingPeriodTranslationUpdateRequest;

class ReportingPeriodTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', ReportingPeriodTranslation::class);

        $search = $request->get('search', '');

        $reportingPeriodTranslations = ReportingPeriodTranslation::search(
            $search
        )
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.reporting_period_translations.index',
            compact('reportingPeriodTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', ReportingPeriodTranslation::class);

        $reportingPeriods = ReportingPeriod::pluck('start_date', 'id');

        return view(
            'app.reporting_period_translations.create',
            compact('reportingPeriods')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ReportingPeriodTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', ReportingPeriodTranslation::class);

        $validated = $request->validated();

        $reportingPeriodTranslation = ReportingPeriodTranslation::create(
            $validated
        );

        return redirect()
            ->route(
                'reporting-period-translations.edit',
                $reportingPeriodTranslation
            )
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        ReportingPeriodTranslation $reportingPeriodTranslation
    ): View {
        $this->authorize('view', $reportingPeriodTranslation);

        return view(
            'app.reporting_period_translations.show',
            compact('reportingPeriodTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        ReportingPeriodTranslation $reportingPeriodTranslation
    ): View {
        $this->authorize('update', $reportingPeriodTranslation);

        $reportingPeriods = ReportingPeriod::pluck('start_date', 'id');

        return view(
            'app.reporting_period_translations.edit',
            compact('reportingPeriodTranslation', 'reportingPeriods')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReportingPeriodTranslationUpdateRequest $request,
        ReportingPeriodTranslation $reportingPeriodTranslation
    ): RedirectResponse {
        $this->authorize('update', $reportingPeriodTranslation);

        $validated = $request->validated();

        $reportingPeriodTranslation->update($validated);

        return redirect()
            ->route(
                'reporting-period-translations.edit',
                $reportingPeriodTranslation
            )
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        ReportingPeriodTranslation $reportingPeriodTranslation
    ): RedirectResponse {
        $this->authorize('delete', $reportingPeriodTranslation);

        $reportingPeriodTranslation->delete();

        return redirect()
            ->route('reporting-period-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
