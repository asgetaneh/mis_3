<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Office;
use App\Models\Manager;
use App\Models\Objective;
use App\Models\Performer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KeyPeformanceIndicator;
use App\Models\Perspective;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\ReportingPeriod;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $user_office = $user->offices;

        $totalKpis = KeyPeformanceIndicator::count() ?? 'None';
        $totalGoals = Goal::count() ?? 'None';
        $totalObjectives = Objective::count() ?? 'None';
        $totalUsers = User::count() ?? 'None';
        $totalPerspectives = Perspective::count() ?? 'None';
        $totalOffices = Office::count() ?? 'None';
        $activeUsers = '';
        $inactiveUsers = '';//dd($request->method());

        // $Objectives = Objective::all();
        // $Users = User::all();
        $Offices = Office::all()->take(2);
        $Offices2 = $Offices;
        $kpis = KeyPeformanceIndicator::all();
        $kpis2 = $kpis;
        $all_child_and_subchildoffices = [];
        $all_child_and_subchildoffices2 = [];

        foreach ($Offices as $key => $value) {
            $all_child_and_subchildoffices=array_merge($all_child_and_subchildoffices,array($value->id));
        }
        foreach ($Offices2 as $key => $value) {
            $all_child_and_subchildoffices2=array_merge($all_child_and_subchildoffices2,array($value->id));
        }

        //$periods = ReportingPeriod::all();
        $period =null;
        $period2 =null;
        //$period_or_quarter = getReportingQuarter($kpii->reportingPeriodType->id);
        $input = $request->all();//dd($input);
        if($input){
            if($request->has('kpi')){
                $kpis = KeyPeformanceIndicator::where('id', $request->input('kpi'))->get();
             }
             if($request->has('kpi2')){
                $kpis2 = KeyPeformanceIndicator::where('id', $request->input('kpi2'))->get();
             }
            if($request->has('office')){
                 $Offices = Office::where('id',$request->input('office'))->get();
                 foreach ($Offices as $key => $value) {
                    $all_child_and_subchildoffices = office_all_childs_ids($value);
                    $all_child_and_subchildoffices = array_merge($all_child_and_subchildoffices, array($value->id));
                }
            }
            if($request->has('office2')){
                 $Offices2 = Office::where('id',$request->input('office2'))->get();
                 foreach ($Offices2 as $key => $value) {
                    $all_child_and_subchildoffices2 = office_all_childs_ids($value);
                    $all_child_and_subchildoffices2 = array_merge($all_child_and_subchildoffices2, array($value->id));
                }
            }
            if($request->has('period')){
                 $period = ReportingPeriod::where('id',$request->input('period'))->get();
            }
            if($request->has('period2')){
                 $period = ReportingPeriod::where('id',$request->input('period2'))->get();
            }
         }




        //dd($all_child_and_subchildoffices);
        // if($user_office->isEmpty()){
        //     $search = $request->get('search', '');
        //     $offices = Office::search($search)
        //         ->latest()
        //         ->paginate(500)
        //         ->withQueryString();
        //      $l_offices=$offices->where("parent_office_id",null);
        //      $ll_offices=$offices->where("parent_office_id",1);
        //      $lll_offices=$offices->where("parent_office_id",2);
        //      return view('app.offices.choose_office', compact('user','l_offices', 'll_offices', 'lll_offices','offices','search'));
        // }
        // else{
            return view('layouts.dashboard', [
                'totalUsers' => $totalUsers,
                'totalKpis' => $totalKpis,
                'totalGoals' => $totalGoals,
                'totalObjectives' => $totalObjectives,
                'totalPerspectives' => $totalPerspectives,
                'totalOffices' => $totalOffices,
                 'kpis' => $kpis,
                'offices' => $all_child_and_subchildoffices,
                'period' => $period,
                 'kpis2' => $kpis2,
                'offices2' => $all_child_and_subchildoffices2,
                'period2' => $period2,
            ]);
      //  }

        // return view('home');
    }
    public function assignOffice(Request $request){
        // $this->authorize('create', Office::class);
         $data = $request->input();
        $search = $request->get('search', '');
        $offices = Office::search($search)
                ->latest()
                ->paginate(500)
                ->withQueryString();
        $manager = DB::select('select * from manager where office_id=:id', ['id' => $data['urofficel']]);
        if($manager){
             return redirect()->back()->with('error', 'Office  has already been assigned for Manager');
             return redirect()
                ->route('home')
                ->withSuccess(__('crud.common.removed'));
        }
        else{
             $manager = DB::insert('insert into manager (office_id, user_id) values (?, ?)', [$data['urofficel'], $data['user']]);

             $role = Role::where('name', '=', 'staff')->first();

             $staffUser = User::find($data['user']);
             $staffUser->assignRole($role);


        } return redirect()
                ->route('home')
                 ->withSuccess(__('crud.common.created'));
             return view('layouts.dashboard');
    }
}
