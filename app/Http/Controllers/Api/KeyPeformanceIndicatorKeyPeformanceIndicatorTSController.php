<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KeyPeformanceIndicator;
use App\Http\Resources\KeyPeformanceIndicatorTResource;
use App\Http\Resources\KeyPeformanceIndicatorTCollection;

class KeyPeformanceIndicatorKeyPeformanceIndicatorTSController extends
    Controller
{
    public function index(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): KeyPeformanceIndicatorTCollection {
        $this->authorize('view', $keyPeformanceIndicator);

        $search = $request->get('search', '');

        $keyPeformanceIndicatorTs = $keyPeformanceIndicator
            ->keyPeformanceIndicatorTs()
            ->search($search)
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorTCollection($keyPeformanceIndicatorTs);
    }

    public function store(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): KeyPeformanceIndicatorTResource {
        $this->authorize('create', KeyPeformanceIndicatorT::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
            'out_put' => ['required', 'max:255', 'string'],
            'out_come' => ['required', 'max:255', 'string'],
        ]);

        $keyPeformanceIndicatorT = $keyPeformanceIndicator
            ->keyPeformanceIndicatorTs()
            ->create($validated);

        return new KeyPeformanceIndicatorTResource($keyPeformanceIndicatorT);
    }
}
