<?php

namespace App\Http\Controllers\Api;

use App\Models\Gender;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenderTranslationResource;
use App\Http\Resources\GenderTranslationCollection;

class GenderGenderTranslationsController extends Controller
{
    public function index(
        Request $request,
        Gender $gender
    ): GenderTranslationCollection {
        $this->authorize('view', $gender);

        $search = $request->get('search', '');

        $genderTranslations = $gender
            ->genderTranslations()
            ->search($search)
            ->latest()
            ->paginate();

        return new GenderTranslationCollection($genderTranslations);
    }

    public function store(
        Request $request,
        Gender $gender
    ): GenderTranslationResource {
        $this->authorize('create', GenderTranslation::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ]);

        $genderTranslation = $gender->genderTranslations()->create($validated);

        return new GenderTranslationResource($genderTranslation);
    }
}
