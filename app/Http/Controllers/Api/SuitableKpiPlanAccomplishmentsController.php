<?php

namespace App\Http\Controllers\Api;

use App\Models\SuitableKpi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanAccomplishmentResource;
use App\Http\Resources\PlanAccomplishmentCollection;

class SuitableKpiPlanAccomplishmentsController extends Controller
{
    public function index(
        Request $request,
        SuitableKpi $suitableKpi
    ): PlanAccomplishmentCollection {
        $this->authorize('view', $suitableKpi);

        $search = $request->get('search', '');

        $planAccomplishments = $suitableKpi
            ->planAccomplishments()
            ->search($search)
            ->latest()
            ->paginate();

        return new PlanAccomplishmentCollection($planAccomplishments);
    }

    public function store(
        Request $request,
        SuitableKpi $suitableKpi
    ): PlanAccomplishmentResource {
        $this->authorize('create', PlanAccomplishment::class);

        $validated = $request->validate([
            'reporting_period_id' => [
                'required',
                'exists:reporting_periods,id',
            ],
            'plan_value' => ['required', 'numeric'],
            'accom_value' => ['required', 'numeric'],
            'plan_status' => ['required', 'max:255'],
            'accom_status' => ['required', 'max:255'],
        ]);

        $planAccomplishment = $suitableKpi
            ->planAccomplishments()
            ->create($validated);

        return new PlanAccomplishmentResource($planAccomplishment);
    }
}
