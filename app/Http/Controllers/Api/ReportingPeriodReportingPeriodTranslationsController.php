<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodTranslationResource;
use App\Http\Resources\ReportingPeriodTranslationCollection;

class ReportingPeriodReportingPeriodTranslationsController extends Controller
{
    public function index(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): ReportingPeriodTranslationCollection {
        $this->authorize('view', $reportingPeriod);

        $search = $request->get('search', '');

        $reportingPeriodTranslations = $reportingPeriod
            ->reportingPeriodTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodTranslationCollection(
            $reportingPeriodTranslations
        );
    }

    public function store(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): ReportingPeriodTranslationResource {
        $this->authorize('create', ReportingPeriodTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ]);

        $reportingPeriodTranslation = $reportingPeriod
            ->reportingPeriodTranslations()
            ->create($validated);

        return new ReportingPeriodTranslationResource(
            $reportingPeriodTranslation
        );
    }
}
