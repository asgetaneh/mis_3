<?php

namespace App\Http\Controllers\Api;

use App\Models\SuitableKpi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\SuitableKpiResource;
use App\Http\Resources\SuitableKpiCollection;
use App\Http\Requests\SuitableKpiStoreRequest;
use App\Http\Requests\SuitableKpiUpdateRequest;

class SuitableKpiController extends Controller
{
    public function index(Request $request): SuitableKpiCollection
    {
        $this->authorize('view-any', SuitableKpi::class);

        $search = $request->get('search', '');

        $suitableKpis = SuitableKpi::search($search)
            ->latest()
            ->paginate();

        return new SuitableKpiCollection($suitableKpis);
    }

    public function store(SuitableKpiStoreRequest $request): SuitableKpiResource
    {
        $this->authorize('create', SuitableKpi::class);

        $validated = $request->validated();

        $suitableKpi = SuitableKpi::create($validated);

        return new SuitableKpiResource($suitableKpi);
    }

    public function show(
        Request $request,
        SuitableKpi $suitableKpi
    ): SuitableKpiResource {
        $this->authorize('view', $suitableKpi);

        return new SuitableKpiResource($suitableKpi);
    }

    public function update(
        SuitableKpiUpdateRequest $request,
        SuitableKpi $suitableKpi
    ): SuitableKpiResource {
        $this->authorize('update', $suitableKpi);

        $validated = $request->validated();

        $suitableKpi->update($validated);

        return new SuitableKpiResource($suitableKpi);
    }

    public function destroy(
        Request $request,
        SuitableKpi $suitableKpi
    ): Response {
        $this->authorize('delete', $suitableKpi);

        $suitableKpi->delete();

        return response()->noContent();
    }
}
