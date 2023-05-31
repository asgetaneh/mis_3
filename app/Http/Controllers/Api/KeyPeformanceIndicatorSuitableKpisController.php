<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KeyPeformanceIndicator;
use App\Http\Resources\SuitableKpiResource;
use App\Http\Resources\SuitableKpiCollection;

class KeyPeformanceIndicatorSuitableKpisController extends Controller
{
    public function index(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): SuitableKpiCollection {
        $this->authorize('view', $keyPeformanceIndicator);

        $search = $request->get('search', '');

        $suitableKpis = $keyPeformanceIndicator
            ->suitableKpis()
            ->search($search)
            ->latest()
            ->paginate();

        return new SuitableKpiCollection($suitableKpis);
    }

    public function store(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): SuitableKpiResource {
        $this->authorize('create', SuitableKpi::class);

        $validated = $request->validate([
            'office_id' => ['required', 'exists:offices,id'],
            'planing_year_id' => ['required', 'exists:planing_years,id'],
        ]);

        $suitableKpi = $keyPeformanceIndicator
            ->suitableKpis()
            ->create($validated);

        return new SuitableKpiResource($suitableKpi);
    }
}
