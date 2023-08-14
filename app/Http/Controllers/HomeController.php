<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\Performer;
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
                ->paginate(5)
                ->withQueryString();
             $l_offices=$offices->where("parent_office_id",null);
             $ll_offices=$offices->where("parent_office_id",1);
             $lll_offices=$offices->where("parent_office_id",2);
             return view('app.offices.choose_office', compact('user','l_offices', 'll_offices', 'lll_offices','search'));
        }
        else{  
            return view('layouts.dashboard');
        }
        
        // return view('home');
    }
    public function assignOffice(Request $request){
         $this->authorize('create', Office::class);
         $data = $request->input();
        $search = $request->get('search', '');
        $performer = DB::select('select * from performers where user_id='.$data['user']);
        if($performer){
             $performer->office_id =   $data['urofficelll'];
            $performer->user_id =   $data['user'];
        }
        else{
            $performer = new Performer;
            $performer->office_id =   $data['urofficelll'];
            $performer->user_id =   $data['user'];
        }        
        $performer->save();
            return view('layouts.dashboard');
    }
}
