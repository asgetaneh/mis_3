<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GenderStoreRequest;
use App\Http\Requests\GenderUpdateRequest;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Gender::class);

        $search = $request->get('search', '');

        $genders = Gender::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.genders.index', compact('genders', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Gender::class);

        return view('app.genders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenderStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Gender::class);

        $validated = $request->validated();

        $gender = Gender::create($validated);

        return redirect()
            ->route('genders.edit', $gender)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Gender $gender): View
    {
        $this->authorize('view', $gender);

        return view('app.genders.show', compact('gender'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Gender $gender): View
    {
        $this->authorize('update', $gender);

        return view('app.genders.edit', compact('gender'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        GenderUpdateRequest $request,
        Gender $gender
    ): RedirectResponse {
        $this->authorize('update', $gender);

        $validated = $request->validated();

        $gender->update($validated);

        return redirect()
            ->route('genders.edit', $gender)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Gender $gender): RedirectResponse
    {
        $this->authorize('delete', $gender);

        $gender->delete();

        return redirect()
            ->route('genders.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
