<?php
namespace App\Http\Controllers\Api;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\KeyPeformanceIndicator;
use App\Http\Resources\KeyPeformanceIndicatorCollection;

class OfficeKeyPeformanceIndicatorsController extends Controller
{
    public function index(
        Request $request,
        Office $office
    ): KeyPeformanceIndicatorCollection {
        $this->authorize('view', $office);

        $search = $request->get('search', '');

        $keyPeformanceIndicators = $office
            ->keyPeformanceIndicators()
            ->search($search)
            ->latest()
            ->paginate();

        return new KeyPeformanceIndicatorCollection($keyPeformanceIndicators);
    }

    public function store(
        Request $request,
        Office $office,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): Response {
        $this->authorize('update', $office);

        $office
            ->keyPeformanceIndicators()
            ->syncWithoutDetaching([$keyPeformanceIndicator->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        Office $office,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): Response {
        $this->authorize('update', $office);

        $office->keyPeformanceIndicators()->detach($keyPeformanceIndicator);

        return response()->noContent();
    }
}
