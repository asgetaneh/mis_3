<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use App\Models\Objective;
use App\Models\Goal;
use Illuminate\View\View;
use App\Models\SuitableKpi;
use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use App\Models\PlanAccomplishment;
use App\Models\KeyPeformanceIndicator;
use Illuminate\Support\Facades\DB;
use App\Models\ReportingPeriodType;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PlanAccomplishmentStoreRequest;
use App\Http\Requests\PlanAccomplishmentUpdateRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Andegna\DateTime as Et_date;
use Andegna\DateTimeFactory;
use App\Models\PlaningYear;
use App\Models\ReportNarration;
use DateTime;
use Redirect;





class PlanAccomplishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', PlanAccomplishment::class);

        $search = $request->get('search', '');

        $planAccomplishments = PlanAccomplishment::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.plan_accomplishments.index',
            compact('planAccomplishments', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', PlanAccomplishment::class);

        $suitableKpis = SuitableKpi::pluck('id', 'id');
        $reportingPeriods = ReportingPeriod::pluck('start_date', 'id');

        return view(
            'app.plan_accomplishments.create',
            compact('suitableKpis', 'reportingPeriods')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        PlanAccomplishmentStoreRequest $request
    ): RedirectResponse {
        $this->authorize('create', PlanAccomplishment::class);

        $validated = $request->validated();

        $planAccomplishment = PlanAccomplishment::create($validated);

        return redirect()
            ->route('plan-accomplishments.edit', $planAccomplishment)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        PlanAccomplishment $planAccomplishment
    ): View {
        $this->authorize('view', $planAccomplishment);

        return view(
            'app.plan_accomplishments.show',
            compact('planAccomplishment')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        PlanAccomplishment $planAccomplishment
    ): View {
        $this->authorize('update', $planAccomplishment);

        $suitableKpis = SuitableKpi::pluck('id', 'id');
        $reportingPeriods = ReportingPeriod::pluck('start_date', 'id');

        return view(
            'app.plan_accomplishments.edit',
            compact('planAccomplishment', 'suitableKpis', 'reportingPeriods')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        PlanAccomplishmentUpdateRequest $request,
        PlanAccomplishment $planAccomplishment
    ): RedirectResponse {
        $this->authorize('update', $planAccomplishment);

        $validated = $request->validated();

        $planAccomplishment->update($validated);

        return redirect()
            ->route('plan-accomplishments.edit', $planAccomplishment)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        PlanAccomplishment $planAccomplishment
    ): RedirectResponse {
        $this->authorize('delete', $planAccomplishment);

        $planAccomplishment->delete();

        return redirect()
            ->route('plan-accomplishments.index')
            ->withSuccess(__('crud.common.removed'));
    }
    public function getParentOffice($office){
        $office = $office->parent_office_id;
        return $office;
    }

    public function officeKpiObjectiveGoal($user){
        $getuser = User::find($user);
        $user_offices = $getuser->offices[0]->id;
        $kpis = $this->getOfficeKPI($user_offices);
          //dd($kpis);
          $suitableKpis =[];
          return view(
            'app.plan_accomplishments.list_goal',
            compact('kpis','user_offices','suitableKpis')
        );

    }
    public function getOfficeKPI($off){
        $fun_kpi =[];
         $fun_goal =[];
        $getoffice = Office::find($off);
            if(!$getoffice->keyPeformanceIndicators->isEmpty()){
                foreach ($getoffice->keyPeformanceIndicators as $key => $kpi) {
                    $fun_kpi[$key] = $kpi;
                   $fun_goal[$key] = $kpi->objective->goal;
                }
                $fun_kpi= array_unique($fun_kpi);
                 $fun_goal= array_unique($fun_goal);
               // $fun_goal = $fun_goal->unique(); // for collection
                 return ['kpi'=>$fun_kpi,'goal'=>$fun_goal,'offwithkpi'=>$getoffice];

             }
            // get parent office kpi
            else{
                return ['kpi'=>[],'goal'=>[],'offwithkpi'=>$getoffice];
                 if($getoffice->parent_office_id==null){
                    echo "office has no parent office and Kpi registered";
                }
              $office = $getoffice->parent_office_id;
              $goalKpi = $this->getOfficeKPI($office);
              return $goalKpi;
            }
            return $fun_kpi;

    }

   public function planaccomplishmentGoalClick($off,$gol_id,$offwithkpi){
        $this->authorize('create', PlanAccomplishment::class);
       $user = auth()->user()->id;
       $getuser = User::find($user);
       $user_offices = $getuser->offices[0]->id;
       $getoffice = Office::find($offwithkpi);
        if(!$getoffice->keyPeformanceIndicators->isEmpty()){
            foreach ($getoffice->keyPeformanceIndicators as $key => $kpi) {
                $fun_kpi[$key] = $kpi;
                $fun_goal[$key] = $kpi->objective->goal;
            }
            $fun_kpi= array_unique($fun_kpi);
            $fun_goal= array_unique($fun_goal);
           $kpis = ['kpi'=>$fun_kpi,'goal'=>$fun_goal,'offwithkpi'=>$getoffice];

            }
       $suitableKpis = SuitableKpi::select('suitable_kpis.*')
                    ->join('offices', 'suitable_kpis.office_id', '=', 'offices.id')
                    ->join('key_peformance_indicators', 'suitable_kpis.key_peformance_indicator_id', '=', 'key_peformance_indicators.id')
                    ->join('objectives', 'key_peformance_indicators.objective_id', '=', 'objectives.id')
                    ->join('goals', 'objectives.goal_id', '=', 'goals.id')
                    ->where('offices.id' , '=', $offwithkpi)
                    ->where('goals.id' , '=', $gol_id)
                    ->get();
                   // dd($suitableKpis);

       return view(
            'app.plan_accomplishments.list_goal',
            compact('kpis','user_offices','suitableKpis')
        );


   }

   public function getAllObjectives($goalId)
   {
       $goal = Goal::find($goalId);
       $user = auth()->user()->id;
       $getuser = User::find($user);
       $user_offices = $getuser->offices[0]->id;

       $objectives = Objective::select('objectives.*')
                     ->join('key_peformance_indicators', 'key_peformance_indicators.objective_id', '=', 'objectives.id')
                     ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                     ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
                      
                     ->join('goals', 'objectives.goal_id', '=', 'goals.id')
                     ->where('offices.id' , '=', $user_offices)
                    ->where('goals.id' , '=', $goal->id)
                    ->get();
                    $objectives = $objectives->unique();
       $reportingTypes = ReportingPeriodType::all();
       $planning_year = PlaningYear::where('is_active',true)->get(); 
       
       $getoffice = Office::find($user_offices);
        $kpis = ['kpi' => [],'goal' => $goal, 'offwithkpi' => $getoffice];
        //dd($getoffice->keyPeformanceIndicators);
       if (!$getoffice->keyPeformanceIndicators->isEmpty()) {
           foreach ($getoffice->keyPeformanceIndicators as $key => $kpi) {
               $fun_kpi[$key] = $kpi;
               $fun_goal[$key] = $kpi->objective->goal;
           }
           $fun_kpi = array_unique($fun_kpi);
           $fun_goal = array_unique($fun_goal);
           $kpis = ['kpi' => $fun_kpi, 'goal' => $fun_goal, 'offwithkpi' => $getoffice];
       }

       return view('app.plan_accomplishments.planning', [
           'objectives' => $objectives,
           'kpis' => $kpis,
            'planning_year' => $planning_year,
           'user_offices' => $user_offices,
           'reportingTypes' => $reportingTypes,
       ]);
   }

   public function savePlan(Request $request){
       $kpi = $request->input();
       $user = auth()->user()->id;
       $getuser = User::find($user);
       $user_offices = $getuser->offices[0]->id;
       $getoffice = Office::find($user_offices);
         $submit = "create";
         $index = [];
        $planning = PlaningYear::where('is_active',true)->get(); 
         foreach ($kpi as $key => $value) {
            $str_key= (string)$key ;
            //dd($kpi);
              if($str_key!='_token'){
                if($value=="yes"){ $submit = "update";}
                // first time planning                
                if($submit == "create"){//dd($submit);
                if($str_key[0]!='d'){ 
                    $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                    $trueindex =0;
                    $index = [];
                    foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                        $index[$trueindex] =$splitvalue;
                        $trueindex++;
                        //echo $splitvalue;
                    }
                    if($index[0] == 'files') continue;
                     $length =  count($index);
                     $plan_accom = new PlanAccomplishment;
                    $plan_accom->kpi_id= $index[0];
                    $quarter = $index[1]; 
                    $plan_accom->reporting_period_id = $index[1];

                    $getkpi = KeyPeformanceIndicator::find($plan_accom->kpi_id);
                    //dump($getkpi);
                     $report_period_type = $getkpi->reportingPeriodType->id;    
                   if($length > 2){
                        $plan_accom->kpi_child_one_id= $index[2];
                        if($length > 3){
                             $plan_accom->kpi_child_two_id= $index[3];
                             if($length > 4){
                                $plan_accom->kpi_child_three_id= $index[4];
                            }
                        }
                    }
                    $plan_accom->office_id=$user_offices;
                    $plan_accom->plan_value=$value;
                    $plan_accom->accom_value=0;
                    $plan_accom->plan_status=0;
                    $plan_accom->accom_status=0;
                     $plan_accom->planning_year_id=$planning[0]->id;
                  $plan_accom->save();
                $kpi_match_for_naration = $index[0];
                }
                else{ //dd($index[0]);
                    $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                    $trueindex =0;
                     $index =[];
                    foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                        $index[$trueindex] =$splitvalue;
                        $trueindex++;
                        } 
                   $naration =new ReportNarration;
                   $naration->plan_naration=$value;
                    $naration->key_peformance_indicator_id=$index[1];
                    $naration->office_id=$user_offices;
                    $naration->reporting_period_id=$index[2];
                    $naration->planing_year_id=$planning[0]->id;
                    $naration->save();
                 }
                }
                else{
                    if($key!="type"){
                       // $this->updatePlan($key,$str_key,$length);
                        if($str_key[0]!='d'){
                            $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                            $trueindex =0;
                            $index =[];
                            foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                                $index[$trueindex] =$splitvalue;
                                $trueindex++;
                                //echo $splitvalue;
                            }
                            if($index[0] == 'files') continue;
                            $length =  count($index);
                            if($length > 2){
                            $kpi_child_one_id= $index[2];
                                if($length > 3){
                                    $kpi_child_two_id= $index[3];
                                    if($length > 4){
                                        $kpi_child_three_id= $index[4];
                                    }
                                    else{$kpi_child_three_id= NULL;  }
                                }
                                 else{
                                    $kpi_child_two_id= NULL;
                                    $kpi_child_three_id= NULL;
                                }
                            }
                            else{
                                 $kpi_child_one_id= NULL;
                                $kpi_child_two_id= NULL;
                                $kpi_child_three_id= NULL;
                            } 

                        //     $check = PlanAccomplishment::
                        //  where('planning_year_id' , '=', $planning[0]->id)
                        // ->where('office_id' , '=', $user_offices)
                        // ->where('kpi_id' , '=', $index[0])
                        // ->where('reporting_period_id' , '=', $index[1])
                        //  ->where('kpi_child_one_id' , '=', $kpi_child_one_id)
                        //   ->where('kpi_child_two_id' , '=', $kpi_child_two_id)
                        //    ->where('kpi_child_three_id' , '=',$kpi_child_three_id)
                        //      ->get();
                        //       dump( $index);
                        //      dump( $check);
                
                        $updated = tap(DB::table('plan_accomplishments')
                         ->where('planning_year_id' , '=', $planning[0]->id)
                        ->where('office_id' , '=', $user_offices)
                        ->where('kpi_id' , '=', $index[0])
                        ->where('reporting_period_id' , '=', $index[1])
                         ->where('kpi_child_one_id' , '=', $kpi_child_one_id)
                          ->where('kpi_child_two_id' , '=', $kpi_child_two_id)
                           ->where('kpi_child_three_id' , '=',$kpi_child_three_id))
                            ->update(['plan_value' => (string)$value])
                            ->first();
                     }
                    else{ //dd($value);
                         $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key); 
                         $index = [];
                             foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                                $index[$splitkey] =$splitvalue;
                              }
                         $updated2 = tap(DB::table('report_narrations')
                         ->where('planing_year_id' , '=', $planning[0]->id)->where('office_id' , '=', $user_offices)->where('key_peformance_indicator_id' , '=', $index[1])->where('reporting_period_id' , '=', $index[2]))
                            ->update(['plan_naration' => $value])
                            ->first();
                    }
                }                
              }
            // echo $length1.'->'.$i[0].'->'.$i[1].'->'.$i[2].'->'. "->".$value."<br/>";
            # code...
         }
         } //dd("end");
        $search = $request->get('search', '');
          $planAccomplishments = PlanAccomplishment::where('office_id' , '=', $user_offices)->where('planning_year_id' , '=', $planning[0]->id)  

            ->latest()
            ->paginate(15)
            ->withQueryString(); //dd("o");
            return Redirect::back();
            return redirect()
            ->route('view-plan-accomplishment')
            ->withSuccess(__('crud.common.created'));

         return view(
            'app.plan_accomplishments.index',
            compact('planAccomplishments', 'search')
        );
         
   }
   
   public function viewPlanAccomplishment(Request $request){
       $search = $request->get('search', '');
       $office = auth()->user()->offices[0]->id;
        $obj_office =Office::find($office);
        $all_child_and_subchild = office_all_childs_ids($obj_office);
        $all_office_list = $all_child_and_subchild;
        //$all_office_list = array_merge( $all_child_and_subchild,array($office));
        $only_child = $obj_office->offices; 
        $only_child_array = [];
        foreach ($only_child as $key => $value) {
           $only_child_array[$key] = $value->id;
        } 
        $only_child_array = array_merge( $only_child_array,array($office));
        //dd($only_child_array) ;
        //$only_child_array = $all_child_and_subchild;
        if($obj_office->offices->isEmpty()){
                $all_office_list = array($office); 
            }
          DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        //re-enable ONLY_FULL_GROUP_BY
        //DB::statement("SET sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");
        
         $planAccomplishment_all = DB::table('plan_accomplishments')
             ->whereIn('office_id', $all_office_list)->groupBy('kpi_id')
              ->sum('plan_accomplishments.plan_value');

        $kpii = KeyPeformanceIndicator::select('key_peformance_indicators.*')
                      ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                     ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
                      ->whereIn('office_id', $all_office_list)
                     ->get();
                     foreach ($kpii as $key => $value) {
                       //dd($value->planacc);
                     }
 
       $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')->whereIn('office_id', $all_office_list)->select('*', DB::raw('SUM(plan_value) AS sum'))->
        where('reporting_periods.slug',"=", 1)
       ->groupBy('kpi_id') 
             ->get(); 
             //dd($planAccomplishments);
        if($obj_office->offices->isEmpty()){  // office with no child
              return redirect()
            ->route('plan-accomplishment',$obj_office);
                         }
        $planning_year = PlaningYear::where('is_active',true)->get(); 
        return view(
            'app.plan_accomplishments.coview-planning',
            compact('planAccomplishments', 'planAccomplishment_all','all_office_list','only_child_array','planning_year','obj_office','search')
        );

   }
   
   public function getReportingPeriod($report_period_type){
        $report_period_list = ReportingPeriod::all();
        $date = new \DateTime() ;
        $ethiopic_today = DateTimeFactory::fromDateTime($date); 
        foreach ($report_period_list as $key => $value) {
            // today date
             $ethiopic_today_tostring = $ethiopic_today->getYear().'-'.$ethiopic_today->getMonth().'-'.$ethiopic_today->getDay();
            $now_et_date = DateTime::createFromFormat('Y-m-d',  $ethiopic_today_tostring);
            // start date
             $from_String_start_date = [$year, $month, $day] = explode('-', $value->start_date);
           $start_date = DateTime::createFromFormat('Y-m-d',  $from_String_start_date[0].'-'.$from_String_start_date[1].'-'.$from_String_start_date[2]);
            // end date
            $from_String_end_date = [$year, $month, $day] = explode('-', $value->end_date);
            $end_date = DateTime::createFromFormat('Y-m-d',  $from_String_end_date[0].'-'.$from_String_end_date[1].'-'.$from_String_end_date[2]); 
             if($start_date < $now_et_date && $end_date > $now_et_date){
                $report_period = ReportingPeriod::where('id' , '=', $value->id)->where('reporting_period_type_id' , '=', $report_period_type)->get();
                    if($report_period){
                             return $report_period;
                    }
                
           
            }
        }        
   }
      public function planApproved(Request $request){
        // getofficel level i.e first approved,second,...
         $approved_list = $request->input();
          foreach ($approved_list as $key => $value) {
            $str_key= (string)$key ;
              if($str_key!='_token'){             
                foreach ($value as $key2 => $value2) {
                     $arr_to_split_text = preg_split("/[_,\- ]+/", $value2);
                     $index = [];
                    foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                        $index[$splitkey] =$splitvalue;
                 }
                    dump($index);
                }
                }
              }
        dd("c");
        $updated = tap(DB::table('plan_accomplishments')
            ->where('planning_year_id' , '=', $planning[0]->id)
        ->where('office_id' , '=', $user_offices)
        ->where('kpi_id' , '=', $index[0])
        ->where('reporting_period_id' , '=', $index[1])
            ->where('kpi_child_one_id' , '=', $kpi_child_one_id)
            ->where('kpi_child_two_id' , '=', $kpi_child_two_id)
            ->where('kpi_child_three_id' , '=',$kpi_child_three_id))
            ->update(['plan_value' => (string)$value])
            ->first();
       
      }


}
