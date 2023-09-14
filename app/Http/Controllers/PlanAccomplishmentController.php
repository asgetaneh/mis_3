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
use App\Models\ReportNarrationReport;

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
       $plan_status=$getoffice->level;
       if($getoffice->level ===1){
        $plan_status =11;
       }
         $submit = "create";
         $index = [];
        $planning = PlaningYear::where('is_active',true)->get();
         foreach ($kpi as $key => $value) {
            $str_key= (string)$key ;
            // dd($kpi);
              if($str_key!='_token'){
                if($value=="yes"){ $submit = "update";}
                // first time planning
                if($submit == "create"){ //dd($submit);
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
                    //$plan_accom->accom_value=0;
                    $plan_accom->plan_status=$plan_status;
                    $plan_accom->accom_status=$getoffice->level;
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
                    $naration->approval_status=$getoffice->level;
                   // $naration->reporting_period_id=$index[2];
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
                    else{
                         $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                         $index = [];
                             foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                                $index[$splitkey] =$splitvalue;
                              }
                         $updated2 = tap(DB::table('report_narrations')
                         ->where('planing_year_id' , '=', $planning[0]->id)->where('office_id' , '=', $user_offices)->where('key_peformance_indicator_id' , '=', $index[1]))
                            ->update(['plan_naration' => $value])
                            ->first();
                    }
                }
              }
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

   public function approvePlanAccomplishment(Request $request){
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
   public function viewPlanAccomplishment(Request $request){
       $search = $request->get('search', '');

       $office_id = auth()->user()->offices[0]->id;
        $office =Office::find($office_id);
        $imagen_off = $office;
        $off_level = $office->level;
        $planning_year = PlaningYear::where('is_active',true)->get();  
        $all_office_list = $this->allChildAndChildChild($office);
        $only_child_array = array_merge($all_office_list,array($office_id));

           DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $is_admin = auth()->user()->isSuperAdmin();
        if (isset($request->search))
        {   $kpi_array = [];
            if ($request->input('kpi')) {
                $kpi = $request->input('kpi');//dd($kpi);
                $kpi_array = array_merge($kpi_array,array($kpi));
            }else {
                $all_kpis = getAllKpi();
                 foreach ($all_kpis as $key => $value) {
                $kpi_array = array_merge($kpi_array,array($value->id));
            }
            }
            if ($request->input('office')) {
                $office_id = $request->input('office');
                $office =Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list,array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;
            }

            $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')->whereIn('office_id', $only_child_array)->whereIn('kpi_id', $kpi_array)->select('*', DB::raw('SUM(plan_value) AS sum'))
            -> where('reporting_periods.slug',"=", 1)
            -> where('planning_year_id','=', $planning_year[0]->id) 
            ->groupBy('kpi_id')  ->get();
                
        }
        else{
        $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')->whereIn('office_id', $only_child_array)->select('*', DB::raw('SUM(plan_value) AS sum'))
        -> where('reporting_periods.slug',"=", 1)
        -> where('planning_year_id','=', $planning_year[0]->id) 
       ->groupBy('kpi_id')  ->get();
         if( $is_admin){
            $imagen_off = Office::find(1); //immaginery office of which contain all office kpi plan
            $off_level = 1;
            $all_offices = getAllOffices();
            $only_child_array = [];
            foreach ($all_offices as $key => $value) {
                $only_child_array = array_merge($only_child_array,array($value->id));
            }
            $planAccomplishments = PlanAccomplishment::
            // join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
            //     ->join('offices', 'offices.id', '=', 'plan_accomplishments.office_id')
            //      ->join('key_peformance_indicators', 'plan_accomplishments.kpi_id', '=', 'key_peformance_indicators.id')
            //     ->join('objectives', 'key_peformance_indicators.objective_id', '=', 'objectives.id')
               // select('*', DB::raw('SUM(plan_value) AS sum'))
                // -> where('reporting_periods.slug',"=", 1) 
                // -> where('plan_status',"=", 2) 
                  where('planning_year_id','=', $planning_year[0]->id) 
                //->groupBy('reporting_period_id')
                //->groupBy('objective_id')
                ->groupBy('kpi_id')
                ->orderBy('reporting_period_id')
                ->get();
                //dd($planAccomplishments);
         }
        }
         return view(
            'app.plan_accomplishments.view-planning-acc',
            compact('planAccomplishments','all_office_list','only_child_array','planning_year','office','off_level','imagen_off','planning_year','search')
        );

   }

   public function allChildAndChildChild($office){
     $all_ids = [];
        if ($office->offices->count() > 0) {
            foreach ($office->offices as $child) {
                $all_ids[] = $child->id;
                $all_ids=array_merge($all_ids,is_array(office_all_childs_ids($child))?office_all_childs_ids($child):[] );
            }
        }
        return $all_ids;
   }
      public function planApproved(Request $request){
        // getofficel level i.e first approved,second,...
         $approved_list = $request->input(); dd($approved_list);
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

      // reporting
      public function officeKpiObjectiveGoalreporting($user){
        $getuser = User::find($user);
        $user_offices = $getuser->offices[0]->id;
        $kpis = $this->getOfficeKPI($user_offices);
          //dd($kpis);
          $suitableKpis =[];
          return view(
            'app.plan_accomplishments.list_goal_reporting',
            compact('kpis','user_offices','suitableKpis')
        );

    }
     public function getAllObjectivesReporting($goalId){
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

       return view('app.report_accomplishments.reporting', [
           'objectives' => $objectives,
           'kpis' => $kpis,
            'planning_year' => $planning_year,
           'user_offices' => $user_offices,
           'reportingTypes' => $reportingTypes,
       ]);
     }
     public function saveReport(Request $request){
       $kpi = $request->input();
       $user = auth()->user()->id;
       $getuser = User::find($user);
       $user_offices = $getuser->offices[0]->id;
       $getoffice = Office::find($user_offices);
       $accom_status=$getoffice->level;
       if($getoffice->level ===1){
        $accom_status =11;
       }
        $submit = "create";
         $index = [];
        $planning = PlaningYear::where('is_active',true)->get();
         foreach ($kpi as $key => $value) {
            $str_key= (string)$key ;
              //dd($kpi);
              if($str_key!='_token'){
                if($str_key[0]!='d' && $str_key!='type'){
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
                    $off_level = auth()->user()->offices[0]->level;
                    $updated = tap(DB::table('plan_accomplishments')
                        ->where('planning_year_id' , '=', $planning[0]->id)
                        ->where('office_id' , '=', $user_offices)
                        ->where('kpi_id' , '=', $index[0])
                        ->where('reporting_period_id' , '=', $index[1])
                        ->where('kpi_child_one_id' , '=', $kpi_child_one_id)
                        ->where('kpi_child_two_id' , '=', $kpi_child_two_id)
                        ->where('kpi_child_three_id' , '=',$kpi_child_three_id))
                            ->update(['accom_value' => (string)$value])
                            ->update(['accom_status' => (string)$accom_status]);
                      }
                    else{
                        if($value=="yes"){ $submit = "update"; continue;}
                         $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                         $index = [];
                             foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                                $index[$splitkey] =$splitvalue;
                              }
                              if($submit == "create"){
                                $naration =new ReportNarrationReport;
                                $naration->report_naration=$value;
                                $naration->key_peformance_indicator_id=$index[1];
                                $naration->office_id=$user_offices;
                                $naration->reporting_period_id=$index[2];
                                $naration->planing_year_id=$planning[0]->id;
                                $naration->save();
                            }
                            else{
                                $updated2 = tap(DB::table('report_narration_reports')
                                ->where('planing_year_id' , '=', $planning[0]
                                ->id)->where('office_id' , '=', $user_offices)
                                ->where('key_peformance_indicator_id' , '=', $index[1])
                                ->where('reporting_period_id' , '=', $index[2]))
                                ->update(['report_naration' => $value])
                                    ->first();
                            }
                    }
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
            'app.report_accomplishments.index',
            compact('planAccomplishments', 'search')
        );

   }
   public function viewReportAccomplishment(Request $request){
       $search = $request->get('search', '');
       $office_id = auth()->user()->offices[0]->id;
       $office =Office::find($office_id);
        $imagen_off = $office;
        $off_level = $office->level;
       $all_office_list = $this->allChildAndChildChild($office);
       $planning_year = PlaningYear::where('is_active',true)->get();  
       $only_child_array = array_merge($all_office_list,array($office_id));

      DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $is_admin = auth()->user()->isSuperAdmin();
        if (isset($request->search))
        {   $kpi_array = [];
            if ($request->input('kpi')) {
                $kpi = $request->input('kpi');//dd($kpi);
                $kpi_array = array_merge($kpi_array,array($kpi));
            }else {
                $all_kpis = getAllKpi();
                 foreach ($all_kpis as $key => $value) {
                $kpi_array = array_merge($kpi_array,array($value->id));
            }
            }
            if ($request->input('office')) {
                $office_id = $request->input('office');
                $office =Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list,array($office_id));
                 $off_level = $office->level;
                $imagen_off = $office;
            }

            $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
            ->whereIn('office_id', $only_child_array)
            ->whereIn('kpi_id', $kpi_array)
            ->select('*', DB::raw('SUM(accom_value) AS sum'))
            -> where('planning_year_id','=', $planning_year[0]->id) 
                //-> where('reporting_periods.slug',"=", 1)
            ->groupBy('kpi_id')
            ->get();
        }
        else{
        $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
        ->whereIn('office_id', $only_child_array)
        ->select('*', DB::raw('SUM(accom_value) AS sum'))
        -> where('planning_year_id','=', $planning_year[0]->id) 
        //-> where('reporting_periods.slug',"=", 1)
       ->groupBy('kpi_id')
       ->get();
         if( $is_admin){
             $imagen_off = Office::find(1); //immaginery office of which contain all office kpi plan
            $off_level = 1;
            $all_offices = getAllOffices();
            $only_child_array = [];
            foreach ($all_offices as $key => $value) {
                $only_child_array = array_merge($only_child_array,array($value->id));
            }
            $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                ->join('offices', 'offices.id', '=', 'plan_accomplishments.office_id')
                 ->join('key_peformance_indicators', 'plan_accomplishments.kpi_id', '=', 'key_peformance_indicators.id')
                    ->join('objectives', 'key_peformance_indicators.objective_id', '=', 'objectives.id')
                ->select('*', DB::raw('SUM(accom_value) AS sum'))
                //-> where('reporting_periods.slug',"=", 2)
                -> where('planning_year_id','=', $planning_year[0]->id) 
                ->groupBy('objective_id')->groupBy('kpi_id') ->get();
        }
        }
       //dd($planAccomplishments);
         $planning_year = PlaningYear::where('is_active',true)->get();
        return view(
            'app.report_accomplishments.view-reporting-acc',
            compact('planAccomplishments','all_office_list','only_child_array','planning_year','office','off_level','imagen_off','search')
        );

   }
}
