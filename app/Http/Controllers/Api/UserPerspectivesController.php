<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PerspectiveResource;
use App\Http\Resources\PerspectiveCollection;

class UserPerspectivesController extends Controller
{
    public function index(Request $request, User $user): PerspectiveCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $perspectives = $user
            ->perspectives2()
            ->search($search)
            ->latest()
            ->paginate();

        return new PerspectiveCollection($perspectives);
    }

    public function store(Request $request, User $user): PerspectiveResource
    {
        $this->authorize('create', Perspective::class);

        $validated = $request->validate([]);

        $perspective = $user->perspectives2()->create($validated);

        return new PerspectiveResource($perspective);
    }
}
