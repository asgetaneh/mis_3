<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use App\Models\Language;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\OfficeTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OfficeTranslationStoreRequest;
use App\Http\Requests\OfficeTranslationUpdateRequest;
use Spatie\Permission\Models\Role;


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
            ->get();
        $officesT = OfficeTranslation ::select('office_translations.*')
         ->join('offices', 'offices.id', '=', 'office_translations.translation_id')->where('offices.parent_office_id','=',NULL)->get();
        return view(
            'app.office_translations.index',
            compact('officeTranslations', 'officesT','search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', OfficeTranslation::class);
        $offices = OfficeTranslation::all();
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
            if(isset($request->parent_name)){
                $parentLevel = Office::where('id', $request->parent_name)->first();
                $office->parent_office_id = $request->parent_name;
                $office->level =  $parentLevel ? $parentLevel->level + 1 : null;
            }else{
                $office->level = 0;
            }

            $office->created_at= new \DateTime();
            $office->updated_at =new \DateTime();
            $office->save();
             foreach ($language as $key => $value) {
                // code...
                $office_translation = new OfficeTranslation;

                $office_translation ->translation_id=$office->id;
                $office_translation ->name = $data['name_'.$value->locale];
                 $office_translation ->locale = $value->locale;
                $office_translation ->description = $data['description_'.$value->locale];
                $office_translation->save();
                }
         return redirect()
            ->route('office_translations.index', $office_translation)
            ->withSuccess(__('crud.common.created'));
        } catch (\Exception $e) {
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

        $officeTranslations = $officeTranslation->office->officeTranslations->groupBy('locale');
        // dd($officeTranslation);
        $offices = OfficeTranslation::all();

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.office_translations.edit',
            compact('officeTranslation', 'offices', 'languages', 'officeTranslations')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        OfficeTranslation $officeTranslation
    ): RedirectResponse {
        $this->authorize('update', $officeTranslation);

        $data = $request->input();
        $language = Language::all();

        $currentOffice = (int)$request->officeId;
        $parentOfficeId = $request->parentOfficeId;
        // dd($parentOfficeId);

        // check if parent office is changed
        if($parentOfficeId !== $request->parent_name){

            // according to the selected parent office value update the level of each office recursively
            if(isset($request->parent_name)){

                $parentLevel = Office::where('id', $request->parent_name)->first();

                $levelCurrentOffice = $parentLevel->level + 1;
                $officeTranslation->office->update([
                    'parent_office_id' => $request->parent_name,
                    'level' => $levelCurrentOffice,
                    'updated_at' => new \DateTime(),
                ]);

                $updateRecursively = updateLevels($officeTranslation->office, $levelCurrentOffice);

            }else{

                $changedLevel = 1;
                $officeTranslation->office->update([
                    'parent_office_id' => null,
                    'level' => $changedLevel,
                    'updated_at' => new \DateTime(),
                ]);

                $updateRecursively = updateLevels($officeTranslation->office, $changedLevel);

            }

        }else{
            $officeTranslation->office->update([
                'updated_at' => new \DateTime(),
            ]);
        }

        $isNewLangAdded = false;
        $localeArray = [];

        foreach ($request->except('_token', '_method', 'parent_name', 'parentOfficeId' , 'officeId') as $key => $value) {

            $locale = str_replace(['name_', 'description_'], '', $key);

            $officeTranslations = $officeTranslation->office->officeTranslations->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($officeTranslations) {
                $officeTranslations->update([
                    $column => $value
                ]);
            }else{
                $isNewLangAdded = true;
                array_push($localeArray, $locale);
            }
        }

        // handle editing if new language was added but translation has no recored for the new language
        if($isNewLangAdded){
            $localeArray = array_unique($localeArray);
            foreach($localeArray as $locale){
                // dd($locale);

                $loc = $locale;
                $inputName = 'name_'.$loc;
                $inputDescription = 'description_'.$loc;

                $name = $request->input($inputName);
                $description = $request->input($inputDescription);

                $officeT = new OfficeTranslation;
                $officeT->translation_id = $officeTranslation->translation_id;
                $officeT->name = $name;
                $officeT->locale = $locale;
                $officeT->description = $description;
                $officeT->save();
            }
        }

        return redirect()
            ->route('office-translations.index', $officeTranslation)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        OfficeTranslation $officeTranslation
    ): RedirectResponse {
        $this->authorize('delete', $officeTranslation);

        $officeTranslation->office->delete();

        return redirect()
            ->route('office-translations.index')
            ->withSuccess(__('crud.common.removed'));
    }

    public function assignIndex(){
        // $offices = OfficeTranslation::all();
        // $users = User::all();

        $assigned = DB::table('manager')->get();

        $assignedOfficesList = [];
        foreach ($assigned as $office){
            array_push($assignedOfficesList, $office->office_id);
        }

        $assignedUsersList = [];
        foreach ($assigned as $user){
            array_push($assignedUsersList, $user->user_id);
        }


        $offices = OfficeTranslation::whereNotIn('translation_id', $assignedOfficesList)->get();
        $users = User::whereNotIn('id', $assignedUsersList)->get();

        return view('app.office_translations.assign', compact('offices', 'users'));
    }

    public function assignStore(Request $request){
        $data = $request->all();

        $userExists = DB::select("SELECT * FROM manager WHERE user_id = :id", ['id' => $data['user']]);
        $userExists = $userExists ? User::where('id', $userExists[0]->user_id)->first() : '';

        if($userExists){
            return redirect()->back()->with('error', 'User '.$userExists->name.' has already been assigned');
        }

        $store = DB::insert('insert into manager (user_id, office_id) values (?, ?)', [$data['user'], $data['office']]);

        $role = Role::where('id', 1)->first(); // get staff role

        $staffUser = User::find($data['user']);
        $staffUser->assignRole($role);

        return redirect()
        ->route('office-translations.index')
        ->withSuccess(__('crud.common.assigned'));
    }

    public function removeManager(Request $request, $id){
        $user = DB::delete("delete from manager where user_id = :id", ['id' => $id]);

        return redirect()
        ->route('office-translations.index')
        ->withSuccess(__('crud.common.revoked'));
    }

}
