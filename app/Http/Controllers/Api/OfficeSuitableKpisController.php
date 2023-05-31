<?php

namespace App\Http\Controllers\Api;

use App\Models\Office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SuitableKpiResource;
use App\Http\Resources\SuitableKpiCollection;

class OfficeSuitableKpisController extends Controller
{
    public function index(
        Request $request,
        Office $office
    ): SuitableKpiCollection {
        $this->authorize('view', $office);

        $search = $request->get('search', '');

        $suitableKpis = $office
            ->suitableKpis()
            ->search($search)
            ->latest()
            ->paginate();

        return new SuitableKpiCollection($suitableKpis);
    }

    public function store(Request $request, Office $office): SuitableKpiResource
    {
        $this->authorize('create', SuitableKpi::class);

        $validated = $request->validate([
            'key_peformance_indicator_id' => [
                'required',
                'exists:key_peformance_indicators,id',
            ],
            'planing_year_id' => ['required', 'exists:planing_years,id'],
        ]);

        $suitableKpi = $office->suitableKpis()->create($validated);

        return new SuitableKpiResource($suitableKpi);
    }
}
