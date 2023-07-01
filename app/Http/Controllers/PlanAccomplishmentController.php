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
use DateTime;




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
       //dd($goal);
       $objectives = Objective::where('goal_id', $goalId)->get();
       $reportingTypes = ReportingPeriodType::all();
       
       $user = auth()->user()->id;
       $getuser = User::find($user);
       $user_offices = $getuser->offices[0]->id;
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
           'allData' => $objectives,
           'kpis' => $kpis,
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
        //dd($kpi);
         foreach ($kpi as $key => $value) {
            $str_key= (string)$key ;
            $length =  Str::length($str_key);
            
              if($str_key[0]!='_'){
                echo $key.'--'.$value."<br/>";
                if($str_key[0]!='d'){
                    $plan_accom = new PlanAccomplishment;
                    $plan_accom->kpi_id= $str_key[0];
                    $kpi = KeyPeformanceIndicator::find( $plan_accom->kpi_id);
                    $report_period_type = $kpi->reportingPeriodType->id;
                    // get current date
                   /// $Date = Carbon::now();
                    //$ethipic = new Et_date($Date);
                    //$ethiopian_date = \Andegna\DateTimeFactory::now();
                    $report_period = $this->getReportingPeriod($report_period_type);                 
                   if($length > 1){
                        $plan_accom->kpi_child_one_id= $str_key[1];
                        if($length > 2){
                             $plan_accom->kpi_child_two_id= $str_key[2];
                             if($length > 3){
                                $plan_accom->kpi_child_three_id= $str_key[3];
                            }
                        }
                    }
                    $plan_accom->office_id=$user_offices;
                    $plan_accom->plan_value=$value;
                    $plan_accom->accom_value=0;
                    $plan_accom->plan_status=0;
                    $plan_accom->accom_status=0;
                    $plan_accom->reporting_period_id=$report_period[0]->id;
                   // dd($str_key[0]);
                    $plan_accom->save();
                
                }
                else{
                    echo "here is ".$str_key[4]."<br/>";
                }
                
              }
            // echo $length1.'->'.$i[0].'->'.$i[1].'->'.$i[2].'->'. "->".$value."<br/>";
            # code...
         }
        $search = $request->get('search', '');
          $planAccomplishments = PlanAccomplishment::where('office_id' , '=', $user_offices)->where('reporting_period_id' , '=', '1')  

            ->latest()
            ->paginate(15)
            ->withQueryString();
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
        $planAccomplishments = PlanAccomplishment::where('office_id' , '=', $office)->where('reporting_period_id' , '=', '1') 
            ->latest()
            ->paginate(9999999999999)
            ->withQueryString();
        return view(
            'app.plan_accomplishments.index2',
            compact('planAccomplishments', 'search')
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

}
