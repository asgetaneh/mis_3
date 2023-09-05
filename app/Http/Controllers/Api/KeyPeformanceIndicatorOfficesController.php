<?php
namespace App\Http\Controllers\Api;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\KeyPeformanceIndicator;
use App\Http\Resources\OfficeCollection;

class KeyPeformanceIndicatorOfficesController extends Controller
{
    public function index(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): OfficeCollection {
        $this->authorize('view', $keyPeformanceIndicator);

        $search = $request->get('search', '');

        $offices = $keyPeformanceIndicator
            ->offices()
            ->search($search)
            ->latest()
            ->paginate();

        return new OfficeCollection($offices);
    }

    public function store(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator,
        Office $office
    ): Response {
        $this->authorize('update', $keyPeformanceIndicator);

        $keyPeformanceIndicator->offices()->syncWithoutDetaching([$office->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator,
        Office $office
    ): Response {
        $this->authorize('update', $keyPeformanceIndicator);

        $keyPeformanceIndicator->offices()->detach($office);

        return response()->noContent();
    }
}
