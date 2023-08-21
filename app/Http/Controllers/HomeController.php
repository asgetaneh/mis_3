<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\Performer;
use App\Models\Manager;
use Illuminate\Support\Facades\DB;


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
            return view('layouts.dashboard');
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
        $manager = DB::select('select * from manager where office_id='.$data['urofficel']);
        if($manager){
             return redirect()->back()->with('error', 'Office  has already been assigned for Manager');
             return redirect()
                ->route('home')
                ->withSuccess(__('crud.common.removed'));
        }
        else{
             $manager = DB::insert('insert into manager (office_id, user_id) values (?, ?)', [$data['urofficel'], $data['user']]);
             
        } return redirect()
                ->route('home')
                 ->withSuccess(__('crud.common.created'));        
             return view('layouts.dashboard');
    }
}
