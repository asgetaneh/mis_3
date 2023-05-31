<?php

namespace App\Http\Controllers\Api;

use App\Models\Office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeTranslationResource;
use App\Http\Resources\OfficeTranslationCollection;

class OfficeOfficeTranslationsController extends Controller
{
    public function index(
        Request $request,
        Office $office
    ): OfficeTranslationCollection {
        $this->authorize('view', $office);

        $search = $request->get('search', '');

        $officeTranslations = $office
            ->officeTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new OfficeTranslationCollection($officeTranslations);
    }

    public function store(
        Request $request,
        Office $office
    ): OfficeTranslationResource {
        $this->authorize('create', OfficeTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ]);

        $officeTranslation = $office->officeTranslations()->create($validated);

        return new OfficeTranslationResource($officeTranslation);
    }
}
