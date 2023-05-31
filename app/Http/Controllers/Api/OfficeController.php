<?php

namespace App\Http\Controllers\Api;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeResource;
use App\Http\Resources\OfficeCollection;
use App\Http\Requests\OfficeStoreRequest;
use App\Http\Requests\OfficeUpdateRequest;

class OfficeController extends Controller
{
    public function index(Request $request): OfficeCollection
    {
        $this->authorize('view-any', Office::class);

        $search = $request->get('search', '');

        $offices = Office::search($search)
            ->latest()
            ->paginate();

        return new OfficeCollection($offices);
    }

    public function store(OfficeStoreRequest $request): OfficeResource
    {
        $this->authorize('create', Office::class);

        $validated = $request->validated();

        $office = Office::create($validated);

        return new OfficeResource($office);
    }

    public function show(Request $request, Office $office): OfficeResource
    {
        $this->authorize('view', $office);

        return new OfficeResource($office);
    }

    public function update(
        OfficeUpdateRequest $request,
        Office $office
    ): OfficeResource {
        $this->authorize('update', $office);

        $validated = $request->validated();

        $office->update($validated);

        return new OfficeResource($office);
    }

    public function destroy(Request $request, Office $office): Response
    {
        $this->authorize('delete', $office);

        $office->delete();

        return response()->noContent();
    }
}
