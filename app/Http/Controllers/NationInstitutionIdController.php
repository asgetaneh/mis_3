<?php

namespace App\Http\Controllers;

use App\Models\NationInstitutionId;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\IdsImport;
use Maatwebsite\Excel\Facades\Excel;

class NationInstitutionIdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search', '');

        $NationInstitution = NationInstitutionId::search($search)
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return view(
            'app.emis.student.overview.index2',
            compact('NationInstitution', 'search')
        );
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
       
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import() 
    {
        Excel::import(new IdsImport,request()->file('file'));
               
        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NationInstitutionId $nationInstitutionId)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NationInstitutionId $nationInstitutionId)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NationInstitutionId $nationInstitutionId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NationInstitutionId $nationInstitutionId)
    {
        //
    }
}
