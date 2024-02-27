<?php

namespace App\Http\Controllers;

use App\Models\BuildingPurpose;
use Illuminate\Http\Request;
use App\Models\Language;

class BuildingPurposeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search', '');

        $building_purpose = BuildingPurpose::search($search)
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return view(
            'app.emis.setting.building_purpose.index',
            compact('building_purpose', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $search = $request->get('search', ''); 
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.emis.setting.building_purpose.create',
            compact('languages')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->input();
        $language = Language::all();
         try {
            foreach ($language as $key => $value) {
                $BuildingPurpose = new BuildingPurpose;
                $BuildingPurpose->code= $data['code_'.$value->locale];
                $BuildingPurpose->name= $data['name_'.$value->locale];
                $BuildingPurpose->description= $data['description_'.$value->locale];
                $BuildingPurpose->created_at= new \DateTime();
                $BuildingPurpose->updated_at =new \DateTime();
                $BuildingPurpose->save();
            }
            
         return redirect()
         ->route('emis.setting.building.purpose', $BuildingPurpose)
         ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('emis.setting.building.purpose.new')->withErrors(['errors' => $e]);
            }
        $perspective = perspectiveTranslation::all();
        return redirect()
            ->route('emis.setting.building.purpose', $perspectives)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BuildingPurpose $buildingPurpose)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BuildingPurpose $buildingPurpose)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BuildingPurpose $buildingPurpose)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BuildingPurpose $buildingPurpose)
    {
        //
    }
}
