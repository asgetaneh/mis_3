<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Language;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\LanguageStoreRequest;
use App\Http\Requests\LanguageUpdateRequest;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Language::class);

        $search = $request->get('search', '');

        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.languages.index', compact('languages', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Language::class);

        $users = User::pluck('name', 'id');

        return view('app.languages.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LanguageStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Language::class);

        $validated = $request->validated();

        $language = Language::create($validated);

        return redirect()
            ->route('languages.index', $language)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Language $language): View
    {
        $this->authorize('view', $language);

        return view('app.languages.show', compact('language'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Language $language): View
    {
        $this->authorize('update', $language);

        $users = User::pluck('name', 'id');

        return view('app.languages.edit', compact('language', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        LanguageUpdateRequest $request,
        Language $language
    ): RedirectResponse {
        $this->authorize('update', $language);

        $validated = $request->validated();

        $language->update($validated);

        return redirect()
            ->route('languages.index', $language)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Language $language
    ): RedirectResponse {
        $this->authorize('delete', $language);

        $language->delete();

        return redirect()
            ->route('languages.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
