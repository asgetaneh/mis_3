<?php

namespace App\Http\Controllers\Api;

use App\Models\Office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeResource;
use App\Http\Resources\OfficeCollection;

class OfficeOfficesController extends Controller
{
    public function index(Request $request, Office $office): OfficeCollection
    {
        $this->authorize('view', $office);

        $search = $request->get('search', '');

        $offices = $office
            ->offices()
            ->search($search)
            ->latest()
            ->paginate();

        return new OfficeCollection($offices);
    }

    public function store(Request $request, Office $office): OfficeResource
    {
        $this->authorize('create', Office::class);

        $validated = $request->validate([
            'holder_id' => ['required', 'exists:users,id'],
        ]);

        $office = $office->offices()->create($validated);

        return new OfficeResource($office);
    }
}
