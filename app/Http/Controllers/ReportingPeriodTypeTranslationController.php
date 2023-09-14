<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use Illuminate\Http\RedirectResponse;
use App\Models\ReportingPeriodTypeTranslation;
use App\Http\Requests\ReportingPeriodTypeTranslationStoreRequest;
use App\Http\Requests\ReportingPeriodTypeTranslationUpdateRequest;

class ReportingPeriodTypeTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', ReportingPeriodTypeTranslation::class);

        $search = $request->get('search', '');

        $reportingPeriodTypeTranslations = ReportingPeriodTypeTranslation::search(
            $search
        )
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.reporting_period_type_translations.index',
            compact('reportingPeriodTypeTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', ReportingPeriodTypeTranslation::class);

        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.reporting_period_type_translations.create',
            compact('reportingPeriodTypes')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ReportingPeriodTypeTranslationStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', ReportingPeriodTypeTranslation::class);

        $validated = $request->validated();

        $reportingPeriodTypeTranslation = ReportingPeriodTypeTranslation::create(
            $validated
        );

        return redirect()
            ->route(
                'reporting-period-type-translations.edit',
                $reportingPeriodTypeTranslation
            )
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        ReportingPeriodTypeTranslation $reportingPeriodTypeTranslation
    ): View {
        $this->authorize('view', $reportingPeriodTypeTranslation);

        return view(
            'app.reporting_period_type_translations.show',
            compact('reportingPeriodTypeTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        ReportingPeriodTypeTranslation $reportingPeriodTypeTranslation
    ): View {
        $this->authorize('update', $reportingPeriodTypeTranslation);

        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.reporting_period_type_translations.edit',
            compact('reportingPeriodTypeTranslation', 'reportingPeriodTypes')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReportingPeriodTypeTranslationUpdateRequest $request,
        ReportingPeriodTypeTranslation $reportingPeriodTypeTranslation
    ): RedirectResponse {
        $this->authorize('update', $reportingPeriodTypeTranslation);

        $validated = $request->validated();

        $reportingPeriodTypeTranslation->update($validated);

        return redirect()
            ->route(
                'reporting-period-type-translations.edit',
                $reportingPeriodTypeTranslation
            )
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        ReportingPeriodTypeTranslation $reportingPeriodTypeTranslation
    ): RedirectResponse {
        $this->authorize('delete', $reportingPeriodTypeTranslation);

        $reportingPeriodTypeTranslation->delete();

        return redirect()
            ->route('reporting-period-type-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
