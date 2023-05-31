<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KeyPeformanceIndicator;
use App\Http\Resources\InititiveResource;
use App\Http\Resources\InititiveCollection;

class KeyPeformanceIndicatorInititivesController extends Controller
{
    public function index(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): InititiveCollection {
        $this->authorize('view', $keyPeformanceIndicator);

        $search = $request->get('search', '');

        $inititives = $keyPeformanceIndicator
            ->inititives()
            ->search($search)
            ->latest()
            ->paginate();

        return new InititiveCollection($inititives);
    }

    public function store(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): InititiveResource {
        $this->authorize('create', Inititive::class);

        $validated = $request->validate([]);

        $inititive = $keyPeformanceIndicator->inititives()->create($validated);

        return new InititiveResource($inititive);
    }
}
