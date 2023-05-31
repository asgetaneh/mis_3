<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\LanguageCollection;

class UserLanguagesController extends Controller
{
    public function index(Request $request, User $user): LanguageCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $languages = $user
            ->languages()
            ->search($search)
            ->latest()
            ->paginate();

        return new LanguageCollection($languages);
    }

    public function store(Request $request, User $user): LanguageResource
    {
        $this->authorize('create', Language::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
            'locale' => ['required', 'max:8', 'string'],
        ]);

        $language = $user->languages()->create($validated);

        return new LanguageResource($language);
    }
}
