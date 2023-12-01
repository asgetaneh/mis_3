<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\GenderTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GenderTranslationStoreRequest;
use App\Http\Requests\GenderTranslationUpdateRequest;

class StaffEMIS extends Controller
{

    public function overview(Request $request): View
    {
        $search = $request->get('search', '');

        $overviews = DB::table('users')->paginate(10);
        
    //     $employees=DB::connection('mysql_hrm')->table('employees as emp')
        
    //     ->join('field_of_studies as fs','fs.id','=','emp.field_of_study_id')
    //     ->join('nationalities as nation','nation.id','=','emp.nationality_id')
    //     ->join('regions as r', 'emp.region_id', '=', 'r.id')
    //     ->join('zones as z', 'emp.zone_id', '=', 'z.id')
    //     ->join('woredas as w', 'emp.woreda_id', '=', 'w.id')
    //     ->select(
    //         'emp.first_name',
    //     'emp.father_name',
    //     'emp.grand_father_name',
    //     'emp.gender',
    //     'emp.date_of_birth',
    //     'emp.birth_city',
    //     'emp.email',
    //     'emp.phone_number',
    //     'nation.nation',
    //     'r.name',
    //     // 'w.name',
    //     'z.name',
    //     'fs.name'
    // ) 
    // ->paginate(10);

    $employees = DB::connection('mysql_hrm')->table('employees as emp')
    ->leftJoin('field_of_studies as fs', 'fs.id', '=', 'emp.field_of_study_id')
    ->leftJoin('nationalities as nation', 'nation.id', '=', 'emp.nationality_id')
    ->leftJoin('regions as r', 'emp.region_id', '=', 'r.id')
    ->leftJoin('zones as z', 'emp.zone_id', '=', 'z.id')
    ->leftJoin('woredas as w', 'emp.woreda_id', '=', 'w.id')
    ->leftJoin('kebeles as k', 'emp.kebele_id', '=', 'k.id')
    ->select(
        'emp.first_name_am',
        'emp.first_name',
        'emp.father_name_am',
        'emp.father_name',
        'emp.grand_father_name_am',
        'emp.grand_father_name',
        'emp.gender',
        'emp.date_of_birth',
        'emp.birth_city',
        'emp.email',
        'emp.phone_number',
        'nation.nation',
        'r.name as region' ,
        'w.name as woreda',
        'z.name as zone',
        'fs.name as field_of_study',
        'k.name as kebele'
    ) 
    ->paginate(10);

    // dd($employees);
        return view(
            'app.emis.staff.overview.index',
            compact('overviews', 'search','employees')
        );
    }

    public function assignment(Request $request): View
    {
        $search = $request->get('search', '');

        $assignments = DB::table('users')->paginate(10);

        return view(
            'app.emis.staff.assignment.index',
            compact('assignments', 'search')
        );
    }

    public function development(Request $request): View
    {
        $search = $request->get('search', '');

        $developments = DB::table('users')->paginate(10);

        return view(
            'app.emis.staff.development.index',
            compact('developments', 'search')
        );
    }

    public function attrition(Request $request): View
    {
        $search = $request->get('search', '');

        $attritions = DB::table('users')->paginate(10);

        return view(
            'app.emis.staff.attrition.index',
            compact('attritions', 'search')
        );
    }

    public function awards(Request $request): View
    {
        $search = $request->get('search', '');

        $awards = DB::table('users')->paginate(10);

        return view(
            'app.emis.staff.awards.index',
            compact('awards', 'search')
        );
    }


    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request): View
    // {
    //     $this->authorize('view-any', GenderTranslation::class);

    //     $search = $request->get('search', '');

    //     $genderTranslations = GenderTranslation::search($search)
    //         ->latest()
    //         ->paginate(5)
    //         ->withQueryString();

    //     return view(
    //         'app.gender_translations.index',
    //         compact('genderTranslations', 'search')
    //     );
    // }
}
