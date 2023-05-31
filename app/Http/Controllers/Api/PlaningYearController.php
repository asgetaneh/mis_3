<?php

namespace App\Http\Controllers\Api;

use App\Models\PlaningYear;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlaningYearResource;
use App\Http\Resources\PlaningYearCollection;
use App\Http\Requests\PlaningYearStoreRequest;
use App\Http\Requests\PlaningYearUpdateRequest;

class PlaningYearController extends Controller
{
    public function index(Request $request): PlaningYearCollection
    {
        $this->authorize('view-any', PlaningYear::class);

        $search = $request->get('search', '');

        $planingYears = PlaningYear::search($search)
            ->latest()
            ->paginate();

        return new PlaningYearCollection($planingYears);
    }

    public function store(PlaningYearStoreRequest $request): PlaningYearResource
    {
        $this->authorize('create', PlaningYear::class);

        $validated = $request->validated();

        $planingYear = PlaningYear::create($validated);

        return new PlaningYearResource($planingYear);
    }

    public function show(
        Request $request,
        PlaningYear $planingYear
    ): PlaningYearResource {
        $this->authorize('view', $planingYear);

        return new PlaningYearResource($planingYear);
    }

    public function update(
        PlaningYearUpdateRequest $request,
        PlaningYear $planingYear
    ): PlaningYearResource {
        $this->authorize('update', $planingYear);

        $validated = $request->validated();

        $planingYear->update($validated);

        return new PlaningYearResource($planingYear);
    }

    public function destroy(
        Request $request,
        PlaningYear $planingYear
    ): Response {
        $this->authorize('delete', $planingYear);

        $planingYear->delete();

        return response()->noContent();
    }
}
