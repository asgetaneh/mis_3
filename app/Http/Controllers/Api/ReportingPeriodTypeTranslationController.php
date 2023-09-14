<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\ReportingPeriodTypeTranslation;
use App\Http\Resources\ReportingPeriodTypeTranslationResource;
use App\Http\Resources\ReportingPeriodTypeTranslationCollection;
use App\Http\Requests\ReportingPeriodTypeTranslationStoreRequest;
use App\Http\Requests\ReportingPeriodTypeTranslationUpdateRequest;

class ReportingPeriodTypeTranslationController extends Controller
{
    public function index(
        Request $request
    ): ReportingPeriodTypeTranslationCollection {
        $this->authorize('view-any', ReportingPeriodTypeTranslation::class);

        $search = $request->get('search', '');

        $reportingPeriodTypeTranslations = ReportingPeriodTypeTranslation::search(
            $search
        )
            ->latest()
            ->paginate();

        return new ReportingPeriodTypeTranslationCollection(
            $reportingPeriodTypeTranslations
        );
    }

    public function store(
        ReportingPeriodTypeTranslationStoreRequest $request
    ): ReportingPeriodTypeTranslationResource {
        $this->authorize('create', ReportingPeriodTypeTranslation::class);

        $validated = $request->validated();

        $reportingPeriodTypeTranslation = ReportingPeriodTypeTranslation::create(
            $validated
        );

        return new ReportingPeriodTypeTranslationResource(
            $reportingPeriodTypeTranslation
        );
    }

    public function show(
        Request $request,
        ReportingPeriodTypeTranslation $reportingPeriodTypeTranslation
    ): ReportingPeriodTypeTranslationResource {
        $this->authorize('view', $reportingPeriodTypeTranslation);

        return new ReportingPeriodTypeTranslationResource(
            $reportingPeriodTypeTranslation
        );
    }

    public function update(
        ReportingPeriodTypeTranslationUpdateRequest $request,
        ReportingPeriodTypeTranslation $reportingPeriodTypeTranslation
    ): ReportingPeriodTypeTranslationResource {
        $this->authorize('update', $reportingPeriodTypeTranslation);

        $validated = $request->validated();

        $reportingPeriodTypeTranslation->update($validated);

        return new ReportingPeriodTypeTranslationResource(
            $reportingPeriodTypeTranslation
        );
    }

    public function destroy(
        Request $request,
        ReportingPeriodTypeTranslation $reportingPeriodTypeTranslation
    ): Response {
        $this->authorize('delete', $reportingPeriodTypeTranslation);

        $reportingPeriodTypeTranslation->delete();

        return response()->noContent();
    }
}
