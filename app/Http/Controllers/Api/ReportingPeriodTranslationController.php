<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\ReportingPeriodTranslation;
use App\Http\Resources\ReportingPeriodTranslationResource;
use App\Http\Resources\ReportingPeriodTranslationCollection;
use App\Http\Requests\ReportingPeriodTranslationStoreRequest;
use App\Http\Requests\ReportingPeriodTranslationUpdateRequest;

class ReportingPeriodTranslationController extends Controller
{
    public function index(
        Request $request
    ): ReportingPeriodTranslationCollection {
        $this->authorize('view-any', ReportingPeriodTranslation::class);

        $search = $request->get('search', '');

        $reportingPeriodTranslations = ReportingPeriodTranslation::search(
            $search
        )
            ->latest()
            ->paginate();

        return new ReportingPeriodTranslationCollection(
            $reportingPeriodTranslations
        );
    }

    public function store(
        ReportingPeriodTranslationStoreRequest $request
    ): ReportingPeriodTranslationResource {
        $this->authorize('create', ReportingPeriodTranslation::class);

        $validated = $request->validated();

        $reportingPeriodTranslation = ReportingPeriodTranslation::create(
            $validated
        );

        return new ReportingPeriodTranslationResource(
            $reportingPeriodTranslation
        );
    }

    public function show(
        Request $request,
        ReportingPeriodTranslation $reportingPeriodTranslation
    ): ReportingPeriodTranslationResource {
        $this->authorize('view', $reportingPeriodTranslation);

        return new ReportingPeriodTranslationResource(
            $reportingPeriodTranslation
        );
    }

    public function update(
        ReportingPeriodTranslationUpdateRequest $request,
        ReportingPeriodTranslation $reportingPeriodTranslation
    ): ReportingPeriodTranslationResource {
        $this->authorize('update', $reportingPeriodTranslation);

        $validated = $request->validated();

        $reportingPeriodTranslation->update($validated);

        return new ReportingPeriodTranslationResource(
            $reportingPeriodTranslation
        );
    }

    public function destroy(
        Request $request,
        ReportingPeriodTranslation $reportingPeriodTranslation
    ): Response {
        $this->authorize('delete', $reportingPeriodTranslation);

        $reportingPeriodTranslation->delete();

        return response()->noContent();
    }
}
