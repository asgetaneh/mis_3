<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportingPeriodTypeTranslationResource;
use App\Http\Resources\ReportingPeriodTypeTranslationCollection;

class ReportingPeriodTypeReportingPeriodTypeTranslationsController extends
    Controller
{
    public function index(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): ReportingPeriodTypeTranslationCollection {
        $this->authorize('view', $reportingPeriodType);

        $search = $request->get('search', '');

        $reportingPeriodTypeTranslations = $reportingPeriodType
            ->reportingPeriodTypeTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new ReportingPeriodTypeTranslationCollection(
            $reportingPeriodTypeTranslations
        );
    }

    public function store(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): ReportingPeriodTypeTranslationResource {
        $this->authorize('create', ReportingPeriodTypeTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ]);

        $reportingPeriodTypeTranslation = $reportingPeriodType
            ->reportingPeriodTypeTranslations()
            ->create($validated);

        return new ReportingPeriodTypeTranslationResource(
            $reportingPeriodTypeTranslation
        );
    }
}
