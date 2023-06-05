<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\OfficeTranslation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OfficeTranslationStoreRequest;
use App\Http\Requests\OfficeTranslationUpdateRequest;
use App\Models\Language;


class OfficeTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', OfficeTranslation::class);

        $search = $request->get('search', '');

        $officeTranslations = OfficeTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'app.office_translations.index',
            compact('officeTranslations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', OfficeTranslation::class);
        $offices = Office::all();
        // $offices = Office::pluck('id', 'id');
        $search = $request->get('search', '');
           $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.office_translations.create', compact('offices','languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(  Request $request
    ): RedirectResponse {
        $this->authorize('create', Office::class);

       $data = $request->input();
        $language = Language::all();
         try {
            $office = new Office;
            if(isset($_POST['parent_name'])){
                $office->parent_office_id = $data['parent_name'];
            }
            
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
            ->route('office_translations.index', $office_translation)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) { 
            return redirect('app.office_translations/new')->withErrors(['errors' => $e]);
            }
        $offices = officeTranslation::all();
        return view('app.office_translations.index',['offices', $offices]);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        OfficeTranslation $officeTranslation
    ): View {
        $this->authorize('view', $officeTranslation);

        return view(
            'app.office_translations.show',
            compact('officeTranslation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        OfficeTranslation $officeTranslation
    ): View {
        $this->authorize('update', $officeTranslation);

        $offices = Office::pluck('id', 'id');

        return view(
            'app.office_translations.edit',
            compact('officeTranslation', 'offices')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        OfficeTranslationUpdateRequest $request,
        OfficeTranslation $officeTranslation
    ): RedirectResponse {
        $this->authorize('update', $officeTranslation);

        $validated = $request->validated();

        $officeTranslation->update($validated);

        return redirect()
            ->route('office-translations.edit', $officeTranslation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        OfficeTranslation $officeTranslation
    ): RedirectResponse {
        $this->authorize('delete', $officeTranslation);

        $officeTranslation->delete();

        return redirect()
            ->route('office-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
