<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\KeyPeformanceIndicator;
use App\Http\Resources\KeyPeformanceIndicatorResource;
use App\Http\Resources\KeyPeformanceIndicatorCollection;
use App\Http\Requests\KeyPeformanceIndicatorStoreRequest;
use App\Http\Requests\KeyPeformanceIndicatorUpdateRequest;

class KeyPeformanceIndicatorController extends Controller
{
    public function index(Request $request): KeyPeformanceIndicatorCollection
    {
        $this->authorize('view-any', KeyPeformanceIndicator::class);

        $search = $request->get('search', '');

        $keyPeformanceIndicators = KeyPeformanceIndicator::search($search)
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorCollection($keyPeformanceIndicators);
    }

    public function store(
        KeyPeformanceIndicatorStoreRequest $request
    ): KeyPeformanceIndicatorResource {
        $this->authorize('create', KeyPeformanceIndicator::class);

        $validated = $request->validated();

        $keyPeformanceIndicator = KeyPeformanceIndicator::create($validated);

        return new KeyPeformanceIndicatorResource($keyPeformanceIndicator);
    }

    public function show(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): KeyPeformanceIndicatorResource {
        $this->authorize('view', $keyPeformanceIndicator);

        return new KeyPeformanceIndicatorResource($keyPeformanceIndicator);
    }

    public function update(
        KeyPeformanceIndicatorUpdateRequest $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): KeyPeformanceIndicatorResource {
        $this->authorize('update', $keyPeformanceIndicator);

        $validated = $request->validated();

        $keyPeformanceIndicator->update($validated);

        return new KeyPeformanceIndicatorResource($keyPeformanceIndicator);
    }

    public function destroy(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): Response {
        $this->authorize('delete', $keyPeformanceIndicator);

        $keyPeformanceIndicator->delete();

        return response()->noContent();
    }
}
