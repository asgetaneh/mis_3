<?php

namespace App\Http\Controllers\Api;

use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenderResource;
use App\Http\Resources\GenderCollection;
use App\Http\Requests\GenderStoreRequest;
use App\Http\Requests\GenderUpdateRequest;

class GenderController extends Controller
{
    public function index(Request $request): GenderCollection
    {
        $this->authorize('view-any', Gender::class);

        $search = $request->get('search', '');

        $genders = Gender::search($search)
            ->latest()
            ->paginate();

        return new GenderCollection($genders);
    }

    public function store(GenderStoreRequest $request): GenderResource
    {
        $this->authorize('create', Gender::class);

        $validated = $request->validated();

        $gender = Gender::create($validated);

        return new GenderResource($gender);
    }

    public function show(Request $request, Gender $gender): GenderResource
    {
        $this->authorize('view', $gender);

        return new GenderResource($gender);
    }

    public function update(
        GenderUpdateRequest $request,
        Gender $gender
    ): GenderResource {
        $this->authorize('update', $gender);

        $validated = $request->validated();

        $gender->update($validated);

        return new GenderResource($gender);
    }

    public function destroy(Request $request, Gender $gender): Response
    {
        $this->authorize('delete', $gender);

        $gender->delete();

        return response()->noContent();
    }
}
