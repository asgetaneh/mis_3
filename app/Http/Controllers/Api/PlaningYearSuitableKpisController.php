<?php

namespace App\Http\Controllers\Api;

use App\Models\PlaningYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SuitableKpiResource;
use App\Http\Resources\SuitableKpiCollection;

class PlaningYearSuitableKpisController extends Controller
{
    public function index(
        Request $request,
        PlaningYear $planingYear
    ): SuitableKpiCollection {
        $this->authorize('view', $planingYear);

        $search = $request->get('search', '');

        $suitableKpis = $planingYear
            ->suitableKpis()
            ->search($search)
            ->latest()
            ->paginate();

        return new SuitableKpiCollection($suitableKpis);
    }

    public function store(
        Request $request,
        PlaningYear $planingYear
    ): SuitableKpiResource {
        $this->authorize('create', SuitableKpi::class);

        $validated = $request->validate([
            'key_peformance_indicator_id' => [
                'required',
                'exists:key_peformance_indicators,id',
            ],
            'office_id' => ['required', 'exists:offices,id'],
        ]);

        $suitableKpi = $planingYear->suitableKpis()->create($validated);

        return new SuitableKpiResource($suitableKpi);
    }
}
