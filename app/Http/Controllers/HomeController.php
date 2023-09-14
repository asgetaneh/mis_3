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
        $inactiveUsers = '';

        if($user_office->isEmpty()){
            $search = $request->get('search', '');
            $offices = Office::search($search)
                ->latest()
                ->paginate(500)
                ->withQueryString();
             $l_offices=$offices->where("parent_office_id",null);
             $ll_offices=$offices->where("parent_office_id",1);
             $lll_offices=$offices->where("parent_office_id",2);
             return view('app.offices.choose_office', compact('user','l_offices', 'll_offices', 'lll_offices','offices','search'));
        }
        else{
            return view('layouts.dashboard', [
                'totalUsers' => $totalUsers,
                'totalKpis' => $totalKpis,
                'totalGoals' => $totalGoals,
                'totalObjectives' => $totalObjectives,
                'totalPerspectives' => $totalPerspectives,
                'totalOffices' => $totalOffices,
            ]);
        }

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
