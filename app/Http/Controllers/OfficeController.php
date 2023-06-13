<?php

namespace App\Http\Controllers;
use App\Models\Language;

use App\Models\User;
use App\Models\Office;
use App\Models\OfficeTranslation;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OfficeStoreRequest;
use App\Http\Requests\OfficeUpdateRequest;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Office::class);

        $search = $request->get('search', '');

        $offices = Office::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.offices.index', compact('offices', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Office::class);
        $search = $request->get('search', '');
        $users = User::pluck('name', 'id');
        $offices = Office::pluck('id', 'id');
         $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.offices.create', compact('users', 'offices', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Office::class);

       $data = $request->input();
        $language = Language::all();
         //$lastoffice = office::select('id')->orderBy('id','desc')->first();
        try {
            $office = new Office;
            $office->parent_office_id = $data['parent_name'];
            $office->created_at= new \DateTime();
            $office->updated_at =new \DateTime();
            $office->save();
             foreach ($language as $key => $value) {
                // code...
                $office_translation = new OfficeTranslation;
                 
                $office_translation ->translation_id=$office->id;
                $office_translation ->name = $data['name'.$value->locale];
                 $office_translation ->locale = $value->locale;
                $office_translation ->description = $data['description'.$value->locale];
                $office_translation->save();
         }
         return redirect()
            ->route('offices.index', $office)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) { 
            return redirect('office/new')->withErrors(['errors' => $e]);
            }
        $offices = officeTranslation::all();
        return view('office.index',['offices', $offices]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Office $office): View
    {
        $this->authorize('view', $office);

        return view('app.offices.show', compact('office'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Office $office): View
    {
        $this->authorize('update', $office);

        $users = User::pluck('name', 'id');
        $offices = Office::pluck('id', 'id');

        return view('app.offices.edit', compact('office', 'users', 'offices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        OfficeUpdateRequest $request,
        Office $office
    ): RedirectResponse {
        $this->authorize('update', $office);

        $validated = $request->validated();

        $office->update($validated);

        return redirect()
            ->route('offices.edit', $office)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Office $office): RedirectResponse
    {
        $this->authorize('delete', $office);

        $office->delete();

        return redirect()
            ->route('offices.index')
            ->withSuccess(__('crud.common.removed'));
    }
    
}
