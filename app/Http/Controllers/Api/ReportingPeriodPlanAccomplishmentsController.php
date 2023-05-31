<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanAccomplishmentResource;
use App\Http\Resources\PlanAccomplishmentCollection;

class ReportingPeriodPlanAccomplishmentsController extends Controller
{
    public function index(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): PlanAccomplishmentCollection {
        $this->authorize('view', $reportingPeriod);

        $search = $request->get('search', '');

        $planAccomplishments = $reportingPeriod
            ->planAccomplishments()
            ->search($search)
            ->latest()
            ->paginate();

        return new PlanAccomplishmentCollection($planAccomplishments);
    }

    public function store(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): PlanAccomplishmentResource {
        $this->authorize('create', PlanAccomplishment::class);

        $validated = $request->validate([
            'suitable_kpi_id' => ['required', 'exists:suitable_kpis,id'],
            'plan_value' => ['required', 'numeric'],
            'accom_value' => ['required', 'numeric'],
            'plan_status' => ['required', 'max:255'],
            'accom_status' => ['required', 'max:255'],
        ]);

        $planAccomplishment = $reportingPeriod
            ->planAccomplishments()
            ->create($validated);

        return new PlanAccomplishmentResource($planAccomplishment);
    }
}
