<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\KeyPeformanceIndicatorT;
use App\Http\Resources\KeyPeformanceIndicatorTResource;
use App\Http\Resources\KeyPeformanceIndicatorTCollection;
use App\Http\Requests\KeyPeformanceIndicatorTStoreRequest;
use App\Http\Requests\KeyPeformanceIndicatorTUpdateRequest;

class KeyPeformanceIndicatorTController extends Controller
{
    public function index(Request $request): KeyPeformanceIndicatorTCollection
    {
        $this->authorize('view-any', KeyPeformanceIndicatorT::class);

        $search = $request->get('search', '');

        $keyPeformanceIndicatorTs = KeyPeformanceIndicatorT::search($search)
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorTCollection($keyPeformanceIndicatorTs);
    }

    public function store(
        KeyPeformanceIndicatorTStoreRequest $request
    ): KeyPeformanceIndicatorTResource {
        $this->authorize('create', KeyPeformanceIndicatorT::class);

        $validated = $request->validated();

        $keyPeformanceIndicatorT = KeyPeformanceIndicatorT::create($validated);

        return new KeyPeformanceIndicatorTResource($keyPeformanceIndicatorT);
    }

    public function show(
        Request $request,
        KeyPeformanceIndicatorT $keyPeformanceIndicatorT
    ): KeyPeformanceIndicatorTResource {
        $this->authorize('view', $keyPeformanceIndicatorT);

        return new KeyPeformanceIndicatorTResource($keyPeformanceIndicatorT);
    }

    public function update(
        KeyPeformanceIndicatorTUpdateRequest $request,
        KeyPeformanceIndicatorT $keyPeformanceIndicatorT
    ): KeyPeformanceIndicatorTResource {
        $this->authorize('update', $keyPeformanceIndicatorT);

        $validated = $request->validated();

        $keyPeformanceIndicatorT->update($validated);

        return new KeyPeformanceIndicatorTResource($keyPeformanceIndicatorT);
    }

    public function destroy(
        Request $request,
        KeyPeformanceIndicatorT $keyPeformanceIndicatorT
    ): Response {
        $this->authorize('delete', $keyPeformanceIndicatorT);

        $keyPeformanceIndicatorT->delete();

        return response()->noContent();
    }
}
