<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PlanAccomplishment;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanAccomplishmentResource;
use App\Http\Resources\PlanAccomplishmentCollection;
use App\Http\Requests\PlanAccomplishmentStoreRequest;
use App\Http\Requests\PlanAccomplishmentUpdateRequest;

class PlanAccomplishmentController extends Controller
{
    public function index(Request $request): PlanAccomplishmentCollection
    {
        $this->authorize('view-any', PlanAccomplishment::class);

        $search = $request->get('search', '');

        $planAccomplishments = PlanAccomplishment::search($search)
            ->latest()
            ->paginate();

        return new PlanAccomplishmentCollection($planAccomplishments);
    }

    public function store(
        PlanAccomplishmentStoreRequest $request
    ): PlanAccomplishmentResource {
        $this->authorize('create', PlanAccomplishment::class);

        $validated = $request->validated();

        $planAccomplishment = PlanAccomplishment::create($validated);

        return new PlanAccomplishmentResource($planAccomplishment);
    }

    public function show(
        Request $request,
        PlanAccomplishment $planAccomplishment
    ): PlanAccomplishmentResource {
        $this->authorize('view', $planAccomplishment);

        return new PlanAccomplishmentResource($planAccomplishment);
    }

    public function update(
        PlanAccomplishmentUpdateRequest $request,
        PlanAccomplishment $planAccomplishment
    ): PlanAccomplishmentResource {
        $this->authorize('update', $planAccomplishment);

        $validated = $request->validated();

        $planAccomplishment->update($validated);

        return new PlanAccomplishmentResource($planAccomplishment);
    }

    public function destroy(
        Request $request,
        PlanAccomplishment $planAccomplishment
    ): Response {
        $this->authorize('delete', $planAccomplishment);

        $planAccomplishment->delete();

        return response()->noContent();
    }
}
