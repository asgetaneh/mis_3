<?php

namespace App\Http\Controllers;

use DateTime;
use Redirect;
use Carbon\Carbon;
use App\Models\Goal;
use App\Models\User;
use App\Models\Office;
use App\Models\Baseline;
use App\Models\Objective;
use Illuminate\View\View;
use App\Models\PlaningYear;
use App\Models\SuitableKpi;
use Illuminate\Support\Str;
use Andegna\DateTimeFactory;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use App\Models\ReportingPeriod;
use App\Models\ReportNarration;
use Barryvdh\DomPDF\Facade\Pdf;
use Andegna\DateTime as Et_date;
use App\Exports\PlanExcelExport;
use PhpOffice\PhpWord\IOFactory;
use App\Models\PlanAccomplishment;
use Illuminate\Support\Facades\DB;
use App\Models\ReportingPeriodType;
use Illuminate\Support\Facades\App;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\ReportNarrationReport;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Http\Requests\PlanAccomplishmentStoreRequest;
use App\Http\Requests\PlanAccomplishmentUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


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
                $fun_goal[$key] = $kpi->objective->goal->orderBy($kpi->objective->goal->id);
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
       $planning_year = PlaningYear::where('is_active',true)->first();

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

    // dd($request->all());
       $kpi = $request->except('_token');
       $user = auth()->user()->id;
       $getuser = User::find($user);
       $user_offices = $getuser->offices[0]->id;
       $getoffice = Office::find($user_offices);
       $plan_status=$getoffice->level;
       if($getoffice->level ===1){
        $plan_status =11;
       }
        $submit = "create";
         $getNaration =null;
         $index = [];
        $planning = PlaningYear::where('is_active',true)->first();

         foreach ($kpi as $key => $value) {
             if(str_contains($key, 'baseline') && next($kpi) == 'yes') {
              $submit = "update";
            }else if (str_contains($key, 'baseline') && next($kpi) == 'no'){
                $submit = "create";
            }
             $str_key= (string)$key ;

              if($str_key!='_token'){
               // if($value=="yes"){ $submit = "update";}
                // first time planning
                if($submit == "create"){
                    if($str_key[0]!='d' && $str_key!="myfile" && !str_contains($str_key, 'type')){
                        $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                        $trueindex =0;
                        $index = [];
                        foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                            $index[$trueindex] =$splitvalue;
                            $trueindex++;
                            //echo $splitvalue;
                        }
                        if($index[0] == 'files') continue;

                        if($index[0] == 'baseline'){ //dd();
                            $length =  count($index) - 1;
                            $baseline = new Baseline;
                            $baseline->kpi_id = $index[1];
                            $baseline->baseline = $value;
                            $baseline->plan_status = $plan_status;

                            if ($length > 1) {
                                $baseline->kpi_one_id = $index[2];
                                if ($length > 2) {
                                    $baseline->kpi_two_id = $index[3];
                                    if ($length > 3) {
                                        $baseline->kpi_three_id = $index[4];
                                    }
                                }
                            }

                            $baseline->office_id = $user_offices;
                            $baseline->planning_year_id = $planning->id;
                            $baseline->save();

                        }else{
                            $length =  count($index);
                            $plan_accom = new PlanAccomplishment;
                            $plan_accom->kpi_id = $index[0]; //dd($index);
                            $quarter = $index[1];
                            $plan_accom->reporting_period_id = $index[1];

                            $getkpi = KeyPeformanceIndicator::find($plan_accom->kpi_id);
                            //dump($getkpi);
                            $report_period_type = $getkpi->reportingPeriodType->id;
                            if ($length > 2) {
                                $plan_accom->kpi_child_one_id = $index[2];
                                if ($length > 3) {
                                    $plan_accom->kpi_child_two_id = $index[3];
                                    if ($length > 4) {
                                        $plan_accom->kpi_child_three_id = $index[4];
                                    }
                                }
                            }
                            $plan_accom->office_id = $user_offices;
                            $plan_accom->plan_value = $value;
                            //$plan_accom->accom_value=0;
                            $plan_accom->plan_status = $plan_status;
                            $plan_accom->accom_status = $getoffice->level;
                            $plan_accom->planning_year_id = $planning->id;
                            $plan_accom->save();
                            $kpi_match_for_naration = $index[0];
                        }
                    }
                    elseif($str_key[0]=='d'){ // continue; dd("x");
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
                        $naration->planing_year_id=$planning->id;
                        $naration->save();
                        $getNaration =$naration;
                     }
                     elseif($str_key=="myfile"){

                        $fileName = $request->file('myfile')->getClientOriginalName();
                        $fileName = date('Y-m-d')."_".time().'_'.$fileName;
                        // $filePath = 'uploads/' . $fileName;
                        $path = $request->file('myfile')->storeAs( 'uploads', $fileName);
                        $getNaration->approval_text=$fileName;
                        $getNaration->save();
                    }
                }

                else{
                    if(!str_contains($key, 'type')){ //dd($kpi);

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

                            if($index[0] == 'baseline'){ //dd(next($kpi));
                                $length =  count($index) - 1;
                                if($length > 1){
                                    $kpi_one_id = (int)$index[2];
                                        if($length > 2){
                                            $kpi_two_id= (int)$index[3];
                                            if($length > 3){
                                                $kpi_three_id= (int)$index[4];
                                            }
                                            else{$kpi_three_id= NULL;  }
                                        }
                                         else{
                                            $kpi_two_id= NULL;
                                            $kpi_three_id= NULL;
                                        }
                                    }
                                    else{
                                        $kpi_one_id= NULL;
                                        $kpi_two_id= NULL;
                                        $kpi_three_id= NULL;
                                }

                                $updated = tap(DB::table('baselines')
                                    ->where('planning_year_id' , '=', $planning->id ?? NULL)
                                    ->where('office_id' , '=', $user_offices)
                                    ->where('kpi_id' , '=', (int)$index[1])
                                    ->where('kpi_one_id' , '=', $kpi_one_id)
                                    ->where('kpi_two_id' , '=', $kpi_two_id)
                                    ->where('kpi_three_id' , '=',$kpi_three_id))
                                    ->update(['baseline' => $value])
                                    ->first();

                            }else{
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
                            //  where('planning_year_id' , '=', $planning->id ?? NULL)
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
                             ->where('planning_year_id' , '=', $planning->id ?? NULL)
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
                    else{
                         $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                         $index = [];
                             foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                                $index[$splitkey] =$splitvalue;
                              }
                         $updated2 = tap(DB::table('report_narrations')
                         ->where('planing_year_id' , '=', $planning->id ?? NULL)->where('office_id' , '=', $user_offices)->where('key_peformance_indicator_id' , '=', $index[1]))
                            ->update(['plan_naration' => $value])
                            ->first();
                    }
                }
              }
          }
         }
        $search = $request->get('search', '');
          $planAccomplishments = PlanAccomplishment::where('office_id' , '=', $user_offices)->where('planning_year_id' , '=', $planning->id ?? NULL)

            ->latest()
            ->paginate(15)
            ->withQueryString(); //dd("o");
            return redirect()
            ->back()
            ->withSuccess(__('crud.common.saved'));

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
        $planning_year = PlaningYear::where('is_active',true)->first();
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
        $planning_year = PlaningYear::where('is_active', true)->select('id')->first();
        $all_office_list = $this->allChildAndChildChild($office);
        $only_child_array = array_merge($all_office_list,array($office_id));

           DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $is_admin = auth()->user()->isSuperAdmin();



        // Plan printing code below
        $isFiltered = $request->has('office');
        $officeSentToBlade = '';

        if ($request->has('pdf') || $request->has('excel') || $request->has('word')) {

            // dd($request->all());

            $kpi_array = [];
            $all_kpis = getAllKpi();
            foreach ($all_kpis as $key => $value) {
                $kpi_array = array_merge($kpi_array, array($value->id));
            }

            // if both kpi and office filtered
            if (($request->has('office') && $request->filled('office')) && ($request->has('kpi') && $request->filled('kpi'))) {

                $office_id = (int)$request->input('office');
                $kpiId = (int)$request->input('kpi');

                $office = Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list, array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;

                $officeSentToBlade = $office->officeTranslations[0]->name ? $office->officeTranslations[0]->name : '-';

                $manager = DB::table('manager')->select()->where('office_id', $office_id)->first();
                $managerName = $manager ? User::where('id', $manager->user_id)->first() : '';
                $managerName = $managerName ? $managerName->name : '-';

                $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                    ->whereIn('office_id', $only_child_array)
                    ->where('kpi_id', $kpiId)
                    ->select('*', DB::raw('SUM(plan_value) AS sum'))
                    ->where('reporting_periods.slug', "=", 1)
                    ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->groupBy('kpi_id')
                    ->get();

                // check what to export
                if($request->has('pdf')){
                    view()->share([
                        'planAccomplishments' => $planAccomplishments,
                        'only_child_array' => $only_child_array,
                        'off_level' => $off_level,
                        'imagen_off' => $imagen_off,
                        'planning_year' => $planning_year,
                        'officeSentToBlade' => $officeSentToBlade,
                        'managerName' => $managerName,
                    ]);

                    $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))
                        ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                        ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                    $pdf->loadView('app.plan_accomplishments.plan-export.pdf-plan');
                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return $pdf->download($officeSentToBlade . '_Office-Plan-For-' . $planYear . '.pdf');
                }

                // Word
                elseif ($request->has('word')) {

                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Load the Blade template into a variable
                    $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                    // Add the content to the PhpWord document
                    $section = $phpWord->addSection();
                    $section->addHtml($content);

                    // Save the PhpWord document as a Word file
                    $filename = 'your-file-name.docx';
                    $phpWord->save($filename, 'Word2007');

                    // Return the generated Word file as a response
                    return response()->download($filename)->deleteFileAfterSend(true);
                }

                // Excel
                else {
                    return view('app.plan_accomplishments.plan-export.excel-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Plan-For-' . $planYear . '.xlsx');
                }


            } else if ($request->has('office') && $request->filled('office')) {

                $office_id = (int)$request->input('office');

                $office = Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list, array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;

                $officeSentToBlade = $office->officeTranslations[0]->name ? $office->officeTranslations[0]->name : '-';

                $manager = DB::table('manager')->select()->where('office_id', $office_id)->first();
                $managerName = $manager ? User::where('id', $manager->user_id)->first() : '';
                $managerName = $managerName ? $managerName->name : '-';

                $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                    ->whereIn('office_id', $only_child_array)
                    ->whereIn('kpi_id', $kpi_array)
                    ->select('*', DB::raw('SUM(plan_value) AS sum'))
                    ->where('reporting_periods.slug', "=", 1)
                    ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->groupBy('kpi_id')
                    ->get();

                // check what to export
                if($request->has('pdf')){
                    view()->share([
                        'planAccomplishments' => $planAccomplishments,
                        'only_child_array' => $only_child_array,
                        'off_level' => $off_level,
                        'imagen_off' => $imagen_off,
                        'planning_year' => $planning_year,
                        'officeSentToBlade' => $officeSentToBlade,
                        'managerName' => $managerName,
                    ]);

                    $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))
                        ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                        ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                    $pdf->loadView('app.plan_accomplishments.plan-export.pdf-plan');
                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return $pdf->download($officeSentToBlade . '_Office-Plan-For-' . $planYear . '.pdf');
                }

                // Word
                elseif ($request->has('word')) {

                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Load the Blade template into a variable
                    $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                    // Add the content to the PhpWord document
                    $section = $phpWord->addSection();
                    $section->addHtml($content);

                    // Save the PhpWord document as a Word file
                    $filename = 'your-file-name.docx';
                    $phpWord->save($filename, 'Word2007');

                    // Return the generated Word file as a response
                    return response()->download($filename)->deleteFileAfterSend(true);
                }

                // Excel
                else {
                    return view('app.plan_accomplishments.plan-export.excel-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Plan-For-' . $planYear . '.xlsx');
                }

            } else if ($request->has('kpi') && $request->filled('kpi')) {

                $kpiId = (int)$request->input('kpi');

                $office = Office::find(auth()->user()->offices[0]->id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list, array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;

                if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                    // $office = Office::find(1);
                    // $imagen_off = $office;
                    // $off_level = 1;
                    // $all_office_list = $this->allChildAndChildChild($office);
                    // $only_child_array = array_merge($all_office_list,array($office_id));

                    $imagen_off = Office::find(1); // immaginery office of which contain all office kpi plan
                    $off_level = 0;
                    $all_offices = getAllOffices();
                    $only_child_array = [];

                    foreach ($all_offices as $key => $value) {
                        $only_child_array = array_merge($only_child_array, array($value->id));
                    }

                }

                $officeSentToBlade = $office->officeTranslations[0]->name ? $office->officeTranslations[0]->name : '-';

                $manager = DB::table('manager')->select()->where('office_id', $office_id)->first();
                $managerName = $manager ? User::where('id', $manager->user_id)->first() : '';
                $managerName = $managerName ? $managerName->name : '-';

                $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                    ->whereIn('office_id', $only_child_array)
                    ->where('kpi_id', $kpiId)
                    ->select('*', DB::raw('SUM(plan_value) AS sum'))
                    ->where('reporting_periods.slug', "=", 1)
                    ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->groupBy('kpi_id')
                    ->get();

                if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                    $planAccomplishments = PlanAccomplishment::where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->where('kpi_id', $kpiId)
                    ->groupBy('kpi_id')
                    ->orderBy('reporting_period_id')
                    ->get();
                }

                // check what to export
                if($request->has('pdf')){
                    view()->share([
                        'planAccomplishments' => $planAccomplishments,
                        'only_child_array' => $only_child_array,
                        'off_level' => $off_level,
                        'imagen_off' => $imagen_off,
                        'planning_year' => $planning_year,
                        'officeSentToBlade' => $officeSentToBlade,
                        'managerName' => $managerName,
                    ]);

                    if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'))
                            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                            ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.plan_accomplishments.plan-export.pdf-plan-all');
                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return $pdf->download('All-Office-Plan-For-' . $planYear . '.pdf');
                    }

                    $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))
                        ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                        ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                    $pdf->loadView('app.plan_accomplishments.plan-export.pdf-plan');
                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return $pdf->download($officeSentToBlade . '_Office-Plan-For-' . $planYear . '.pdf');
                }

                // Word
                elseif ($request->has('word')) {

                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Load the Blade template into a variable
                    $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                    // Add the content to the PhpWord document
                    $section = $phpWord->addSection();
                    $section->addHtml($content);

                    // Save the PhpWord document as a Word file
                    $filename = 'your-file-name.docx';
                    $phpWord->save($filename, 'Word2007');

                    // Return the generated Word file as a response
                    return response()->download($filename)->deleteFileAfterSend(true);
                }

                // Excel
                else {
                    return view('app.plan_accomplishments.plan-export.excel-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Plan-For-' . $planYear . '.xlsx');
                }

            }

            // Print all offices
            else {

                // if admin/plan office, print all offices
                if (auth()->user()->is_admin || auth()->user()->hasRole('super-admin')) {

                    $imagen_off = Office::find(1); // immaginery office of which contain all office kpi plan
                    $off_level = 0;
                    $all_offices = getAllOffices();
                    $only_child_array = [];

                    foreach ($all_offices as $key => $value) {
                        $only_child_array = array_merge($only_child_array, array($value->id));
                    }

                    $planAccomplishments = PlanAccomplishment::where('planning_year_id', '=', $planning_year->id ?? NULL)
                        ->groupBy('kpi_id')
                        ->orderBy('reporting_period_id')
                        ->get();

                    // dd($planAccomplishments);

                    // PDF
                    if ($request->has('pdf')) {
                        view()->share([
                            'planAccomplishments' => $planAccomplishments,
                            'only_child_array' => $only_child_array,
                            'off_level' => $off_level,
                            'imagen_off' => $imagen_off,
                            'planning_year' => $planning_year,

                        ]);

                        // return view('app.plan_accomplishments.plan-export.pdf-plan-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'))
                            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                            ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.plan_accomplishments.plan-export.pdf-plan-all');

                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';

                        return $pdf->download('All-Office-Plan-For-' . $planYear . '.pdf');
                    }

                    // Word
                    elseif ($request->has('word')) {

                        // Create a new PhpWord instance
                        $phpWord = new PhpWord();

                        // Load the Blade template into a variable
                        $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                        // Add the content to the PhpWord document
                        $section = $phpWord->addSection();
                        $section->addHtml($content);

                        // Save the PhpWord document as a Word file
                        $filename = 'your-file-name.docx';
                        $phpWord->save($filename, 'Word2007');

                        // Return the generated Word file as a response
                        return response()->download($filename)->deleteFileAfterSend(true);
                    }

                    // Excel
                    else {
                        return view('app.plan_accomplishments.plan-export.excel-plan-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), 'Office-' . $officeSentToBlade . '-Plan.xlsx');
                    }
                }

                // Only print for that current logged in user office
                else {

                    $office_id = auth()->user()->offices[0]->id;
                    $office = Office::find($office_id);
                    $imagen_off = $office;
                    $off_level = $office->level;
                    $planning_year = PlaningYear::where('is_active', true)->first();
                    $all_office_list = $this->allChildAndChildChild($office);
                    $only_child_array = array_merge($all_office_list, array($office_id));

                    $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                        ->whereIn('office_id', $only_child_array)
                        ->whereIn('kpi_id', $kpi_array)
                        ->select('*', DB::raw('SUM(plan_value) AS sum'))
                        ->where('reporting_periods.slug', "=", 1)
                        ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                        ->groupBy('kpi_id')
                        ->get();

                    // dd($planAccomplishments);

                    // PDF
                    if ($request->has('pdf')) {
                        view()->share([
                            'planAccomplishments' => $planAccomplishments,
                            'only_child_array' => $only_child_array,
                            'off_level' => $off_level,
                            'imagen_off' => $imagen_off,
                            'planning_year' => $planning_year,

                        ]);

                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'))
                            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                            ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.plan_accomplishments.plan-export.pdf-plan-all');
                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return $pdf->download('All-Office-Plan-For-' . $planYear . '.pdf');
                    }

                    // Word
                    elseif ($request->has('word')) {

                        // Create a new PhpWord instance
                        $phpWord = new PhpWord();

                        // Load the Blade template into a variable
                        $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                        // Add the content to the PhpWord document
                        $section = $phpWord->addSection();
                        $section->addHtml($content);

                        // Save the PhpWord document as a Word file
                        $filename = 'your-file-name.docx';
                        $phpWord->save($filename, 'Word2007');

                        // Return the generated Word file as a response
                        return response()->download($filename)->deleteFileAfterSend(true);
                    }

                    // Excel
                    else {
                        return view('app.plan_accomplishments.plan-export.excel-plan-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Plan-For-' . $planYear . '.xlsx');
                    }
                }
            }

        }


        if (isset($request->search))
        {

            if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                $office = Office::find(1);
                $imagen_off = $office;
                $off_level = 1;
                $planning_year = PlaningYear::where('is_active',true)->first();
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list,array($office_id));
            }

            $kpi_array = [];
            if ($request->input('kpi')  && $request->filled('kpi')) {
                $kpi = (int)$request->input('kpi');//dd($kpi);
                $kpi_array = array_merge($kpi_array,array($kpi));
            }else {
                $all_kpis = getAllKpi();
                 foreach ($all_kpis as $key => $value) {
                $kpi_array = array_merge($kpi_array,array($value->id));
            }
            }
            if ($request->input('office')  && $request->filled('office')) {
                $office_id = $request->input('office');
                $office =Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list,array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;
            }

            // $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')->whereIn('office_id', $only_child_array)->whereIn('kpi_id', $kpi_array)->select('*', DB::raw('SUM(plan_value) AS sum'))
            // -> where('reporting_periods.slug',"=", 1)
            // -> where('planning_year_id','=', $planning_year->id ?? NULL)
            // ->groupBy('kpi_id')  ->get();

            $planAccomplishments = PlanAccomplishment::
            join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
            ->whereIn('office_id', $only_child_array)
            ->whereIn('kpi_id', $kpi_array)
            ->select(
                'plan_accomplishments.id as id',
                'plan_accomplishments.kpi_id',
                'plan_accomplishments.planning_year_id',
                'plan_accomplishments.plan_value',
                'plan_accomplishments.accom_value',
                'reporting_periods.id as reporting_period_id',
                DB::raw('SUM(plan_value) AS sum')
            )            ->where('reporting_periods.slug',"=", 1)
            ->where('planning_year_id','=', $planning_year->id ?? NULL)
            ->groupBy('kpi_id')
            ->paginate(2);

            // to persist the select input values
            $request->flash();

        }
        else{
            $planAccomplishments = PlanAccomplishment::whereIn('office_id', $only_child_array)
                //->select('*', DB::raw('SUM(plan_value) AS sum'))
                -> where('planning_year_id','=', $planning_year->id ?? NULL)
                ->groupBy('kpi_id')
                ->paginate(2);
            //     $user_office = auth()->user()->offices[0]->id;
            //     $check = PlanAccomplishment::getOfficePlans($user_office);
            //  dump($check);
            //      $planAccomplishments = PlanAccomplishment::join('reporting_periods',
            //      'reporting_periods.id', '=',
            //      'plan_accomplishments.reporting_period_id')
            //     ->whereIn('office_id', $only_child_array)
            //     ->select('*', DB::raw('SUM(plan_value) AS sum'))
            //     // -> where('reporting_periods.slug',"=", 1)
            //     -> where('planning_year_id','=', $planning_year->id ?? NULL)
            //     ->groupBy('reporting_period_id')
            //    ->groupBy('kpi_id')  ->get();

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
                  join('key_peformance_indicators', 'plan_accomplishments.kpi_id', '=', 'key_peformance_indicators.id')
                ->join('objectives', 'key_peformance_indicators.objective_id', '=', 'objectives.id')
               // select('*', DB::raw('SUM(plan_value) AS sum'))
                // -> where('reporting_periods.slug',"=", 1)
                // -> where('plan_status',"=", 2)
                 -> where('planning_year_id','=', $planning_year->id ?? NULL)
                //->groupBy('reporting_period_id')
                ->orderBy('objective_id')
                ->groupBy('kpi_id')
                ->orderBy('reporting_period_id')
                ->paginate(3);
                //dd($planAccomplishments);
         }
        }
         return view(
            'app.plan_accomplishments.view-planning-acc',
            compact( 'planAccomplishments','all_office_list','only_child_array','planning_year','office','off_level','imagen_off','planning_year','search')
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
        $all_ids=array_merge($all_ids,array($office->id));
        //dd($all_ids);
        return $all_ids;
   }
      public function planApproved(Request $request){
        // getofficel level i.e first approved,second,...
         $approved_list = $request->input(); //dd($approved_list);
          foreach ($approved_list as $key => $value) {
            $str_key= (string)$key ;
              if($str_key!='_token'){
                foreach ($value as $key2 => $value2) {
                     $arr_to_split_text = preg_split("/[_,\- ]+/", $value2);
                     $index = [];
                    foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                        $index[$splitkey] =$splitvalue;
                 }
                    //dump($index);
                }
                }
              }
        //dd("c");
        $updated = tap(DB::table('plan_accomplishments')
            ->where('planning_year_id' , '=', $planning->id ?? NULL)
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
       $planning_year = PlaningYear::where('is_active',true)->first();

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
       $kpi = $request->all();
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
        $planning = PlaningYear::where('is_active',true)->first();
         foreach ($kpi as $key => $value) {
            $str_key= (string)$key ;
              //dd($kpi);
              if($str_key!='_token'){
                if($str_key[0]!='d' && $str_key!='type' && $str_key!="myfile"){
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
                        ->where('planning_year_id' , '=', $planning->id ?? NULL)
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
                    if($str_key=='type'){  $submit = "update";}
                    if($submit == "create"){
                        if($str_key[0]=='d'){
                            $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                            $index = [];
                            foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                            $index[$splitkey] =$splitvalue;
                            }
                            $naration =new ReportNarrationReport;
                            $naration->report_naration=$value;
                            $naration->key_peformance_indicator_id=$index[1];
                            $naration->office_id=$user_offices;
                            $naration->reporting_period_id=$index[2];
                            $naration->planing_year_id=$planning->id;
                            $naration->save();
                            $getNaration =$naration;
                        }
                        elseif($str_key=="myfile"){
                            $fileName = $request->file('myfile')->getClientOriginalName();
                            $fileName = date('Y-m-d')."_".time().'_'.$fileName;
                            // $filePath = 'uploads/' . $fileName;
                            $path = $request->file('myfile')->storeAs( 'uploads', $fileName);
                            $getNaration->approval_text=$fileName;
                            $getNaration->save();
                        }
                    }
                    else{
                       if($str_key[0]=='d' ){
                         $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                         $index = [];
                         foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                            $index[$splitkey] =$splitvalue;
                        }
                        $updated2 = tap(DB::table('report_narration_reports')
                        ->where('planing_year_id' , '=', $planning
                        ->id ?? NULL)
                        ->where('office_id' , '=', $user_offices)
                        ->where('key_peformance_indicator_id' , '=', $index[1])
                        ->where('reporting_period_id' , '=', $index[2]))
                        ->update(['report_naration' => $value])
                        ->first();
                        $report_narration_report_ob = ReportNarrationReport::find($updated2->id);
                          }
                         elseif($str_key=="myfile"){
                            $fileName = $request->file('myfile')->getClientOriginalName();
                            $fileName = date('Y-m-d')."_".time().'_'.$fileName;
                            // $filePath = 'uploads/' . $fileName;
                            $path = $request->file('myfile')->storeAs( 'uploads', $fileName);
                            $report_narration_report_ob->approval_text=$fileName;
                            $report_narration_report_ob->save();
                         }
                    }
                }
              }
          } //dd("end");
        $search = $request->get('search', '');
          $planAccomplishments = PlanAccomplishment::where('office_id' , '=', $user_offices)->where('planning_year_id' , '=', $planning->id ?? NULL)

            ->latest()
            ->paginate(15)
            ->withQueryString(); //dd("o");
            return redirect()
            ->back()
            ->withSuccess(__('crud.common.saved'));

   }
   public function viewReportAccomplishment(Request $request){
       $search = $request->get('search', '');
       $office_id = auth()->user()->offices[0]->id;
       $office =Office::find($office_id);
        $imagen_off = $office;
        $off_level = $office->level;
       $all_office_list = $this->allChildAndChildChild($office);
       $planning_year = PlaningYear::where('is_active',true)->first();
       $only_child_array = array_merge($all_office_list,array($office_id));

      DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $is_admin = auth()->user()->isSuperAdmin();




        // Report printing code below
        $isFiltered = $request->has('office');
        $officeSentToBlade = '';

        if ($request->has('pdf') || $request->has('excel') || $request->has('word')) {

            // dd($request->all());

            $kpi_array = [];
            $all_kpis = getAllKpi();
            foreach ($all_kpis as $key => $value) {
                $kpi_array = array_merge($kpi_array, array($value->id));
            }

            // if both kpi and office filtered
            if (($request->has('office') && $request->filled('office')) && ($request->has('kpi') && $request->filled('kpi'))) {

                $office_id = (int)$request->input('office');
                $kpiId = (int)$request->input('kpi');

                $office = Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list, array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;

                $officeSentToBlade = $office->officeTranslations[0]->name ? $office->officeTranslations[0]->name : '-';

                $manager = DB::table('manager')->select()->where('office_id', $office_id)->first();
                $managerName = $manager ? User::where('id', $manager->user_id)->first() : '';
                $managerName = $managerName ? $managerName->name : '-';

                $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                    ->whereIn('office_id', $only_child_array)
                    ->where('kpi_id', $kpiId)
                    ->select('*', DB::raw('SUM(accom_value) AS sum'))
                    ->where('reporting_periods.slug', "=", 1)
                    ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->groupBy('kpi_id')
                    ->get();

                // check what to export
                if($request->has('pdf')){
                    view()->share([
                        'planAccomplishments' => $planAccomplishments,
                        'only_child_array' => $only_child_array,
                        'off_level' => $off_level,
                        'imagen_off' => $imagen_off,
                        'planning_year' => $planning_year,
                        'officeSentToBlade' => $officeSentToBlade,
                        'managerName' => $managerName,
                    ]);

                    // return view('app.report_accomplishments.report-export.pdf-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));


                    $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))
                    ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                    ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                $pdf->loadView('app.report_accomplishments.report-export.pdf-report');
                $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                return $pdf->download($officeSentToBlade . '_Office-Report-For-' . $planYear . '.pdf');
                }

                // Word
                elseif ($request->has('word')) {

                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Load the Blade template into a variable
                    $content = view('app.report_accomplishments.report-export.word-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                    // Add the content to the PhpWord document
                    $section = $phpWord->addSection();
                    $section->addHtml($content);

                    // Save the PhpWord document as a Word file
                    $filename = 'your-file-name.docx';
                    $phpWord->save($filename, 'Word2007');

                    // Return the generated Word file as a response
                    return response()->download($filename)->deleteFileAfterSend(true);
                }

                // Excel
                else {
                    return view('app.report_accomplishments.report-export.excel-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Report-For-' . $planYear . '.xlsx');
                }


            } else if ($request->has('office') && $request->filled('office')) {

                $office_id = (int)$request->input('office');

                $office = Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list, array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;

                $officeSentToBlade = $office->officeTranslations[0]->name ? $office->officeTranslations[0]->name : '-';

                $manager = DB::table('manager')->select()->where('office_id', $office_id)->first();
                $managerName = $manager ? User::where('id', $manager->user_id)->first() : '';
                $managerName = $managerName ? $managerName->name : '-';

                $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                    ->whereIn('office_id', $only_child_array)
                    ->whereIn('kpi_id', $kpi_array)
                    ->select('*', DB::raw('SUM(accom_value) AS sum'))
                    ->where('reporting_periods.slug', "=", 1)
                    ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->groupBy('kpi_id')
                    ->get();

                // check what to export
                if($request->has('pdf')){
                    view()->share([
                        'planAccomplishments' => $planAccomplishments,
                        'only_child_array' => $only_child_array,
                        'off_level' => $off_level,
                        'imagen_off' => $imagen_off,
                        'planning_year' => $planning_year,
                        'officeSentToBlade' => $officeSentToBlade,
                        'managerName' => $managerName,
                    ]);

                    $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))
                        ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                        ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.report_accomplishments.report-export.pdf-report');
                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return $pdf->download($officeSentToBlade . '_Office-Report-For-' . $planYear . '.pdf');
                }

                // Word
                elseif ($request->has('word')) {

                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Load the Blade template into a variable
                    $content = view('app.report_accomplishments.report-export.word-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                    // Add the content to the PhpWord document
                    $section = $phpWord->addSection();
                    $section->addHtml($content);

                    // Save the PhpWord document as a Word file
                    $filename = 'your-file-name.docx';
                    $phpWord->save($filename, 'Word2007');

                    // Return the generated Word file as a response
                    return response()->download($filename)->deleteFileAfterSend(true);
                }

                // Excel
                else {
                    return view('app.report_accomplishments.report-export.excel-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Report-For-' . $planYear . '.xlsx');
                }

            } else if ($request->has('kpi') && $request->filled('kpi')) {

                $kpiId = (int)$request->input('kpi');

                if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                    $office = Office::find(1);
                }else{
                    $office = Office::find(auth()->user()->offices[0]->id);
                }

                $all_office_list = $this->allChildAndChildChild($office);

                if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                    $office_id = 1;
                    $only_child_array = $all_office_list;
                    $off_level = 1;
                }else{
                    $office_id = auth()->user()->offices[0]->id;
                    $only_child_array = array_merge($all_office_list, array($office_id));
                    $off_level = $office->level;

                }

                // $only_child_array = array_merge($all_office_list, array($office_id));
                // $off_level = $office->level;
                $imagen_off = $office;

                if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                    // $office = Office::find(1);
                    // $imagen_off = $office;
                    // $off_level = 1;
                    // $all_office_list = $this->allChildAndChildChild($office);
                    // $only_child_array = array_merge($all_office_list,array($office_id));

                    $imagen_off = Office::find(1); // immaginery office of which contain all office kpi plan
                    $off_level = 0;
                    $all_offices = getAllOffices();
                    $only_child_array = [];

                    foreach ($all_offices as $key => $value) {
                        $only_child_array = array_merge($only_child_array, array($value->id));
                    }

                }

                $officeSentToBlade = $office->officeTranslations[0]->name ? $office->officeTranslations[0]->name : '-';

                $manager = DB::table('manager')->select()->where('office_id', $office_id)->first();
                $managerName = $manager ? User::where('id', $manager->user_id)->first() : '';
                $managerName = $managerName ? $managerName->name : '-';

                $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                    ->whereIn('office_id', $only_child_array)
                    ->where('kpi_id', $kpiId)
                    ->select('*', DB::raw('SUM(accom_value) AS sum'))
                    ->where('reporting_periods.slug', "=", 1)
                    ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->groupBy('kpi_id')
                    ->get();

                if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                    $planAccomplishments = PlanAccomplishment::where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->where('kpi_id', $kpiId)
                    ->groupBy('kpi_id')
                    ->orderBy('reporting_period_id')
                    ->get();
                }

                // check what to export
                if($request->has('pdf')){
                    view()->share([
                        'planAccomplishments' => $planAccomplishments,
                        'only_child_array' => $only_child_array,
                        'off_level' => $off_level,
                        'imagen_off' => $imagen_off,
                        'planning_year' => $planning_year,
                        'officeSentToBlade' => $officeSentToBlade,
                        'managerName' => $managerName,
                    ]);

                    if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))
                        ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                        ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.report_accomplishments.report-export.pdf-report-all');
                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return $pdf->download('All-Office-Report-For-' . $planYear . '.pdf');
                    }

                    $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))
                        ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                        ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.report_accomplishments.report-export.pdf-report');
                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return $pdf->download($officeSentToBlade . '_Office-Report-For-' . $planYear . '.pdf');
                }

                // Word
                elseif ($request->has('word')) {

                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Load the Blade template into a variable
                    $content = view('app.report_accomplishments.report-export.word-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                    // Add the content to the PhpWord document
                    $section = $phpWord->addSection();
                    $section->addHtml($content);

                    // Save the PhpWord document as a Word file
                    $filename = 'your-file-name.docx';
                    $phpWord->save($filename, 'Word2007');

                    // Return the generated Word file as a response
                    return response()->download($filename)->deleteFileAfterSend(true);
                }

                // Excel
                else {
                    return view('app.report_accomplishments.report-export.excel-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Report-For-' . $planYear . '.xlsx');
                }
            }

            // Print all offices
            else {

                // if admin/plan office, print all offices
                if (auth()->user()->is_admin || auth()->user()->hasRole('super-admin')) {

                    $imagen_off = Office::find(1); // immaginery office of which contain all office kpi plan
                    $off_level = 0;
                    $all_offices = getAllOffices();
                    $only_child_array = [];

                    foreach ($all_offices as $key => $value) {
                        $only_child_array = array_merge($only_child_array, array($value->id));
                    }

                    $planAccomplishments = PlanAccomplishment::where('planning_year_id', '=', $planning_year->id ?? NULL)
                        ->groupBy('kpi_id')
                        ->orderBy('reporting_period_id')
                        ->get();

                    // dd($planAccomplishments);

                    // PDF
                    if ($request->has('pdf')) {
                        view()->share([
                            'planAccomplishments' => $planAccomplishments,
                            'only_child_array' => $only_child_array,
                            'off_level' => $off_level,
                            'imagen_off' => $imagen_off,
                            'planning_year' => $planning_year,

                        ]);

                        // return view('app.report_accomplishments.report-export.pdf-report-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'))
                            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                            ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                            $pdf->loadView('app.report_accomplishments.report-export.pdf-report-all');
                            $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                            return $pdf->download('All-Office-Report-For-' . $planYear . '.pdf');
                    }

                    // Word
                    elseif ($request->has('word')) {

                        // Create a new PhpWord instance
                        $phpWord = new PhpWord();

                        // Load the Blade template into a variable
                        $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                        // Add the content to the PhpWord document
                        $section = $phpWord->addSection();
                        $section->addHtml($content);

                        // Save the PhpWord document as a Word file
                        $filename = 'your-file-name.docx';
                        $phpWord->save($filename, 'Word2007');

                        // Return the generated Word file as a response
                        return response()->download($filename)->deleteFileAfterSend(true);
                    }

                    // Excel
                    else {
                        return view('app.plan_accomplishments.plan-export.excel-plan-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), 'Office-' . $officeSentToBlade . '-Report.xlsx');
                    }
                }

                // Only print for that current logged in user office
                else {

                    $office_id = auth()->user()->offices[0]->id;
                    $office = Office::find($office_id);
                    $imagen_off = $office;
                    $off_level = $office->level;
                    $planning_year = PlaningYear::where('is_active', true)->first();
                    $all_office_list = $this->allChildAndChildChild($office);
                    $only_child_array = array_merge($all_office_list, array($office_id));

                    $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                        ->whereIn('office_id', $only_child_array)
                        ->whereIn('kpi_id', $kpi_array)
                        ->select('*', DB::raw('SUM(accom_value) AS sum'))
                        ->where('reporting_periods.slug', "=", 1)
                        ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                        ->groupBy('kpi_id')
                        ->get();

                    // dd($planAccomplishments);

                    // PDF
                    if ($request->has('pdf')) {
                        view()->share([
                            'planAccomplishments' => $planAccomplishments,
                            'only_child_array' => $only_child_array,
                            'off_level' => $off_level,
                            'imagen_off' => $imagen_off,
                            'planning_year' => $planning_year,

                        ]);

                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'))
                            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                            ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                            $pdf->loadView('app.report_accomplishments.report-export.pdf-report-all');
                            $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                            return $pdf->download('All-Office-Report-For-' . $planYear . '.pdf');
                    }

                    // Word
                    elseif ($request->has('word')) {

                        // Create a new PhpWord instance
                        $phpWord = new PhpWord();

                        // Load the Blade template into a variable
                        $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                        // Add the content to the PhpWord document
                        $section = $phpWord->addSection();
                        $section->addHtml($content);

                        // Save the PhpWord document as a Word file
                        $filename = 'your-file-name.docx';
                        $phpWord->save($filename, 'Word2007');

                        // Return the generated Word file as a response
                        return response()->download($filename)->deleteFileAfterSend(true);
                    }

                    // Excel
                    else {
                        return view('app.plan_accomplishments.plan-export.excel-plan-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Report-For-' . $planYear . '.xlsx');
                    }
                }
            }

        }



        if (isset($request->search))
        {

            if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin')){
                $office = Office::find(1);
                $imagen_off = $office;
                $off_level = 1;
                $planning_year = PlaningYear::where('is_active',true)->first();
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = $all_office_list;
            }

            $kpi_array = [];
            if ($request->input('kpi') && $request->filled('kpi')) {
                $kpi = $request->input('kpi');//dd($kpi);
                $kpi_array = array_merge($kpi_array,array($kpi));
            }else {
                $all_kpis = getAllKpi();
                 foreach ($all_kpis as $key => $value) {
                $kpi_array = array_merge($kpi_array,array($value->id));
            }
            }
            if ($request->input('office') && $request->filled('office')) {
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
            -> where('planning_year_id','=', $planning_year->id ?? NULL)
                //-> where('reporting_periods.slug',"=", 1)
            ->groupBy('kpi_id')
            ->get();

            // to persist the select input values
            $request->flash();
        }
        else{
        $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
        ->whereIn('office_id', $only_child_array)
        ->select('*', DB::raw('SUM(accom_value) AS sum'))
        -> where('planning_year_id','=', $planning_year->id ?? NULL)
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
                -> where('planning_year_id','=', $planning_year->id ?? NULL)
                ->groupBy('objective_id')->groupBy('kpi_id') ->get();
        }
        }
       //dd($planAccomplishments);
         $planning_year = PlaningYear::where('is_active',true)->first();
        return view(
            'app.report_accomplishments.view-reporting-acc',
            compact('planAccomplishments','all_office_list','only_child_array','planning_year','office','off_level','imagen_off','search')
        );

   }


    public function downloadPlan(Request $request)
    {

        $office_id = auth()->user()->offices[0]->id;
        $office = Office::find($office_id);
        $imagen_off = $office;
        $off_level = $office->level;
        $planning_year = PlaningYear::where('is_active', true)->first();
        $all_office_list = $this->allChildAndChildChild($office);
        $only_child_array = array_merge($all_office_list, array($office_id));

        // handle printing excel and pdf for either filtered or all KPI Plans
        $isFiltered = $request->has('office');
        $officeSentToBlade = '';

        $planning_year = PlaningYear::where('is_active', true)->first();
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        $planAccomplishments = '';

        if ($request->has('pdf') || $request->has('excel') || $request->has('word')) {

            $kpi_array = [];
            $all_kpis = getAllKpi();
            foreach ($all_kpis as $key => $value) {
                $kpi_array = array_merge($kpi_array, array($value->id));
            }

            // if office is filtered or selected in form
            if ($isFiltered) {

                $office_id = (int)$request->input('office');
                $office = Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list, array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;

                $officeSentToBlade = $office->officeTranslations[0]->name ? $office->officeTranslations[0]->name : '-';

                $manager = DB::table('manager')->select()->where('office_id', $office_id)->first();
                $managerName = $manager ? User::where('id', $manager->user_id)->first() : '';
                $managerName = $managerName ? $managerName->name : '-';

                $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                ->whereIn('office_id', $only_child_array)
                    ->whereIn('kpi_id', $kpi_array)
                    ->select('*', DB::raw('SUM(plan_value) AS sum'))
                    ->where('reporting_periods.slug', "=", 1)
                    ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->groupBy('kpi_id')
                    ->get();

                // dd($planAccomplishments);

                // return view('app.plan_accomplishments.pdf-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                // PDF
                if ($request->has('pdf')) {
                    view()->share([
                        'planAccomplishments' => $planAccomplishments,
                        'only_child_array' => $only_child_array,
                        'off_level' => $off_level,
                        'imagen_off' => $imagen_off,
                        'planning_year' => $planning_year,
                        'officeSentToBlade' => $officeSentToBlade,
                        'managerName' => $managerName,
                    ]);

                    $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))
                        ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                        ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                    $pdf->loadView('app.plan_accomplishments.plan-export.pdf-plan');
                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return $pdf->download($officeSentToBlade . '_Office-Plan-For-' . $planYear . '.pdf');
                }

                // Word
                elseif ($request->has('word')) {

                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Load the Blade template into a variable
                    $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                    // Add the content to the PhpWord document
                    $section = $phpWord->addSection();
                    $section->addHtml($content);

                    // Save the PhpWord document as a Word file
                    $filename = 'your-file-name.docx';
                    $phpWord->save($filename, 'Word2007');

                    // Return the generated Word file as a response
                    return response()->download($filename)->deleteFileAfterSend(true);
                }

                // Excel
                else {
                    return view('app.plan_accomplishments.plan-export.excel-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Plan-For-' . $planYear . '.xlsx');
                }
            }


            // Print all offices
            else {

                // if admin/plan office, print all offices
                if (auth()->user()->is_admin || auth()->user()->hasRole('super-admin')) {

                    $imagen_off = Office::find(1); //immaginery office of which contain all office kpi plan
                    $off_level = 1;
                    $all_offices = getAllOffices();
                    $only_child_array = [];

                    foreach ($all_offices as $key => $value) {
                        $only_child_array = array_merge($only_child_array, array($value->id));
                    }

                    $planAccomplishments = PlanAccomplishment::where('planning_year_id', '=', $planning_year->id ?? NULL)
                        ->groupBy('kpi_id')
                        ->orderBy('reporting_period_id')
                        ->get();

                    // dd($planAccomplishments);

                    // PDF
                    if ($request->has('pdf')) {
                        view()->share([
                            'planAccomplishments' => $planAccomplishments,
                            'only_child_array' => $only_child_array,
                            'off_level' => $off_level,
                            'imagen_off' => $imagen_off,
                            'planning_year' => $planning_year,

                        ]);

                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'))
                            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                            ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.plan_accomplishments.plan-export.pdf-plan-all');
                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return $pdf->download('All-Office-Plan-For-' . $planYear . '.pdf');
                    }

                    // Word
                    elseif ($request->has('word')) {

                        // Create a new PhpWord instance
                        $phpWord = new PhpWord();

                        // Load the Blade template into a variable
                        $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                        // Add the content to the PhpWord document
                        $section = $phpWord->addSection();
                        $section->addHtml($content);

                        // Save the PhpWord document as a Word file
                        $filename = 'your-file-name.docx';
                        $phpWord->save($filename, 'Word2007');

                        // Return the generated Word file as a response
                        return response()->download($filename)->deleteFileAfterSend(true);
                    }

                    // Excel
                    else {
                        return view('app.plan_accomplishments.plan-export.excel-plan-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), 'Office-' . $officeSentToBlade . '-Plan.xlsx');
                    }
                }

                // Only print for that current logged in user office
                else {

                    $office_id = auth()->user()->offices[0]->id;
                    $office = Office::find($office_id);
                    $imagen_off = $office;
                    $off_level = $office->level;
                    $planning_year = PlaningYear::where('is_active', true)->first();
                    $all_office_list = $this->allChildAndChildChild($office);
                    $only_child_array = array_merge($all_office_list, array($office_id));

                    $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                    ->whereIn('office_id', $only_child_array)
                        ->whereIn('kpi_id', $kpi_array)
                        ->select('*', DB::raw('SUM(plan_value) AS sum'))
                        ->where('reporting_periods.slug', "=", 1)
                        ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                        ->groupBy('kpi_id')
                        ->get();

                    // dd($planAccomplishments);

                    // PDF
                    if ($request->has('pdf')) {
                        view()->share([
                            'planAccomplishments' => $planAccomplishments,
                            'only_child_array' => $only_child_array,
                            'off_level' => $off_level,
                            'imagen_off' => $imagen_off,
                            'planning_year' => $planning_year,

                        ]);

                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'))
                            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                            ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.plan_accomplishments.plan-export.pdf-plan-all');
                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return $pdf->download('All-Office-Plan-For-' . $planYear . '.pdf');
                    }

                    // Word
                    elseif ($request->has('word')) {

                        // Create a new PhpWord instance
                        $phpWord = new PhpWord();

                        // Load the Blade template into a variable
                        $content = view('app.plan_accomplishments.plan-export.word-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                        // Add the content to the PhpWord document
                        $section = $phpWord->addSection();
                        $section->addHtml($content);

                        // Save the PhpWord document as a Word file
                        $filename = 'your-file-name.docx';
                        $phpWord->save($filename, 'Word2007');

                        // Return the generated Word file as a response
                        return response()->download($filename)->deleteFileAfterSend(true);
                    }

                    // Excel
                    else {
                        return view('app.plan_accomplishments.plan-export.excel-plan-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Plan-For-' . $planYear . '.xlsx');
                    }
                }
            }
        }
    }

    public function downloadReport(Request $request){
        // dd($request->all());

        $office_id = auth()->user()->offices[0]->id;
        $office = Office::find($office_id);
        $imagen_off = $office;
        $off_level = $office->level;
        $planning_year = PlaningYear::where('is_active', true)->first();
        $all_office_list = $this->allChildAndChildChild($office);
        $only_child_array = array_merge($all_office_list, array($office_id));

        // handle printing excel and pdf for either filtered or all KPI Plans
        $isFiltered = $request->has('office');
        $officeSentToBlade = '';

        $planning_year = PlaningYear::where('is_active', true)->first();
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        $planAccomplishments = '';

        if ($request->has('pdf') || $request->has('excel') || $request->has('word')) {

            $kpi_array = [];
            $all_kpis = getAllKpi();
            foreach ($all_kpis as $key => $value) {
                $kpi_array = array_merge($kpi_array, array($value->id));
            }

            // if office is filtered or selected in form
            if ($isFiltered) {

                $office_id = (int)$request->input('office');
                $office = Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list, array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;

                $officeSentToBlade = $office->officeTranslations[0]->name ? $office->officeTranslations[0]->name : '-';

                $manager = DB::table('manager')->select()->where('office_id', $office_id)->first();
                $managerName = $manager ? User::where('id', $manager->user_id)->first() : '';
                $managerName = $managerName ? $managerName->name : '-';

                $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                ->whereIn('office_id', $only_child_array)
                    ->whereIn('kpi_id', $kpi_array)
                    ->select('*', DB::raw('SUM(plan_value) AS sum'))
                    ->where('reporting_periods.slug', "=", 1)
                    ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                    ->groupBy('kpi_id')
                    ->get();

                // dd($planAccomplishments);

                // return view('app.plan_accomplishments.pdf-plan', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                // PDF
                if ($request->has('pdf')) {
                    view()->share([
                        'planAccomplishments' => $planAccomplishments,
                        'only_child_array' => $only_child_array,
                        'off_level' => $off_level,
                        'imagen_off' => $imagen_off,
                        'planning_year' => $planning_year,
                        'officeSentToBlade' => $officeSentToBlade,
                        'managerName' => $managerName,
                    ]);

                    $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))
                        ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                        ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                    $pdf->loadView('app.report_accomplishments.report-export.pdf-report');
                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return $pdf->download($officeSentToBlade . '_Office-Report-For-' . $planYear . '.pdf');
                }

                // Word
                elseif ($request->has('word')) {

                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Load the Blade template into a variable
                    $content = view('app.report_accomplishments.report-export.word-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                    // Add the content to the PhpWord document
                    $section = $phpWord->addSection();
                    $section->addHtml($content);

                    // Save the PhpWord document as a Word file
                    $filename = 'your-file-name.docx';
                    $phpWord->save($filename, 'Word2007');

                    // Return the generated Word file as a response
                    return response()->download($filename)->deleteFileAfterSend(true);
                }

                // Excel
                else {
                    return view('app.report_accomplishments.report-export.excel-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'));

                    $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                    return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Report-For-' . $planYear . '.xlsx');
                }
            }


            // Print all offices
            else {

                // if admin/plan office, print all offices
                if (auth()->user()->is_admin || auth()->user()->hasRole('super-admin')) {

                    $imagen_off = Office::find(1); //immaginery office of which contain all office kpi plan
                    $off_level = 1;
                    $all_offices = getAllOffices();
                    $only_child_array = [];

                    foreach ($all_offices as $key => $value) {
                        $only_child_array = array_merge($only_child_array, array($value->id));
                    }

                    $planAccomplishments = PlanAccomplishment::where('planning_year_id', '=', $planning_year->id ?? NULL)
                        ->groupBy('kpi_id')
                        ->orderBy('reporting_period_id')
                        ->get();

                    // dd($planAccomplishments);

                    // PDF
                    if ($request->has('pdf')) {
                        view()->share([
                            'planAccomplishments' => $planAccomplishments,
                            'only_child_array' => $only_child_array,
                            'off_level' => $off_level,
                            'imagen_off' => $imagen_off,
                            'planning_year' => $planning_year,

                        ]);

                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'))
                            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                            ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.report_accomplishments.report-export.pdf-report-all');
                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return $pdf->download('All-Office-Report-For-' . $planYear . '.pdf');
                    }

                    // Word
                    elseif ($request->has('word')) {

                        // Create a new PhpWord instance
                        $phpWord = new PhpWord();

                        // Load the Blade template into a variable
                        $content = view('app.report_accomplishments.report-export.word-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                        // Add the content to the PhpWord document
                        $section = $phpWord->addSection();
                        $section->addHtml($content);

                        // Save the PhpWord document as a Word file
                        $filename = 'your-file-name.docx';
                        $phpWord->save($filename, 'Word2007');

                        // Return the generated Word file as a response
                        return response()->download($filename)->deleteFileAfterSend(true);
                    }

                    // Excel
                    else {
                        return view('app.report_accomplishments.report-export.excel-report-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), 'Office-' . $officeSentToBlade . '-Report.xlsx');
                    }
                }

                // Only print for that current logged in user office
                else {

                    $office_id = auth()->user()->offices[0]->id;
                    $office = Office::find($office_id);
                    $imagen_off = $office;
                    $off_level = $office->level;
                    $planning_year = PlaningYear::where('is_active', true)->first();
                    $all_office_list = $this->allChildAndChildChild($office);
                    $only_child_array = array_merge($all_office_list, array($office_id));

                    $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                    ->whereIn('office_id', $only_child_array)
                        ->whereIn('kpi_id', $kpi_array)
                        ->select('*', DB::raw('SUM(plan_value) AS sum'))
                        ->where('reporting_periods.slug', "=", 1)
                        ->where('planning_year_id', '=', $planning_year->id ?? NULL)
                        ->groupBy('kpi_id')
                        ->get();

                    // dd($planAccomplishments);

                    // PDF
                    if ($request->has('pdf')) {
                        view()->share([
                            'planAccomplishments' => $planAccomplishments,
                            'only_child_array' => $only_child_array,
                            'off_level' => $off_level,
                            'imagen_off' => $imagen_off,
                            'planning_year' => $planning_year,

                        ]);

                        // return view('app.report_accomplishments.report-export.pdf-report-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        $pdf = App::make('dompdf.wrapper', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'))
                            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                            ->setPaper([0, 0, 419.53 + 350, 595.28], 'portrait');
                        $pdf->loadView('app.report_accomplishments.report-export.pdf-report-all');
                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return $pdf->download('All-Office-Report-For-' . $planYear . '.pdf');
                    }

                    // Word
                    elseif ($request->has('word')) {

                        // Create a new PhpWord instance
                        $phpWord = new PhpWord();

                        // Load the Blade template into a variable
                        $content = view('app.report_accomplishments.report-export.word-report', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year', 'officeSentToBlade', 'managerName'))->render();

                        // Add the content to the PhpWord document
                        $section = $phpWord->addSection();
                        $section->addHtml($content);

                        // Save the PhpWord document as a Word file
                        $filename = 'your-file-name.docx';
                        $phpWord->save($filename, 'Word2007');

                        // Return the generated Word file as a response
                        return response()->download($filename)->deleteFileAfterSend(true);
                    }

                    // Excel
                    else {
                        return view('app.report_accomplishments.report-export.excel-report-all', compact('planAccomplishments', 'only_child_array', 'off_level', 'imagen_off', 'planning_year'));

                        $planYear = !empty($planning_year) ? $planning_year->planingYearTranslations[0]->name : '';
                        return Excel::download(new PlanExcelExport($planAccomplishments, $only_child_array, $off_level, $imagen_off, $planning_year, $officeSentToBlade, $managerName), $officeSentToBlade . '_Office-Report-For-' . $planYear . '.xlsx');
                    }
                }
            }
        }
    }
    public function viewFile($path){
        $filename = 'test.pdf';
        $path = storage_path('app/uploads/'.$path);
        //dd($path);
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);
       // dd($path);
    }

    public function getDetails($id, $kpi_id, $year_id, Request $request)
    {
        $planAccom = $request->route()->parameters();
        $kpi = KeyPeformanceIndicator::find($kpi_id);
        // if (!count($kpi->kpiChildOnes) == 0) {
        //     dump($kpi->kpiChildOnes);
        // }
        $kpi_children_name =[];
        foreach ($kpi->kpiChildOnes as $key => $value) {
            $kpi_children_name[] = $value->kpiChildOneTranslations[0]->name;
         }
        $planning_year = PlaningYear::find($year_id);
        $planAccomplishment = PlanAccomplishment::where('kpi_id',$kpi->id)
        ->where('planning_year_id',$planning_year->id)
        ->first();
        $get_office = Office::find($id); // Use office ID from request
        $parent_office_name = $get_office->officeTranslations[0]->name;
        $reportin_periods = getQuarter($planAccomplishment->Kpi->reportingPeriodType->id);
        $period_array = [];
        $office_trans_array = []; // Initialize the array to store structured data
        $parent_office_trans_array = []; // Initialize the array to store structured data
        $narrations = [];
        $userOffice = auth()->user()->offices[0];
        $child_offices = $get_office->offices;
        $kpi_children_data = []; // Array to hold KPI child data
        $baseline_self =null;

        // $childAndHimOffKpi = office_all_childs_ids($get_office);

        // KPI has child three, two, one
        if(!count($kpi->kpiChildThrees) == 0){

        }

        // KPI has child  two, one
        else if(!count($kpi->kpiChildTwos) == 0){
            $parent_office_data_chOne = [
                'id' => $get_office->id,
                'office_name' => $get_office->officeTranslations[0]->name,
                 'kpi_id' => $kpi->id,
                'pp_year' => $planning_year->id,
                'kpi_child_one_count' => count($kpi->kpiChildOnes),
                'kpi_child_two_count' => count($kpi->kpiChildTwos),
                "narration" => $planAccomplishment->getNarration(
                    $childAndHimOffKpi,
                    $kpi->id,
                    $planning_year->id ?? null,
                    $get_office
                ),
                'planAndBaseline' => [],
              ];
             foreach ($kpi->kpiChildOnes as $one) {
                foreach ($kpi->kpiChildTwos as $two) {
                    $kpi_officeSelf_plans = [];
                    foreach ($reportin_periods as $period) {
                        $planOfOfficePlanSelf = $planAccomplishment->OnlyKpiOTT(
                            $childAndHimOffKpi,
                            $kpi->id, $get_office, $period->id,  false,
                            $planning_year->id ?? null,$one->id, $two->id,null
                        );
                        $kpi_officeSelf_plans[] = [
                            'reporting_period' => $period->reportingPeriodTs[0]->name,
                            'plan_value' => $planOfOfficePlanSelf[0] ?? 0,
                            'plan_status' => $planOfOfficePlan[2] ?? 0
                        ];
                        // Add the reporting period name to the unique period array
                        $period_array[] = $period->reportingPeriodTs[0]->name;
                        $period_array = array_unique($period_array);
                    }
                    $parent_office_data_chOne['planAndBaseline'][] = [
                        'kpi_child_one_name' => $one->kpiChildOneTranslations[0]->name,
                        'kpi_child_two_name' => $two->kpiChildTwoTranslations[0]->name,

                        'kpi_child_baseline' => OnlyKpiOttBaseline(
                            $childAndHimOffKpi,
                            $kpi->id,
                            $get_office,
                            $planning_year->id,
                            null,
                            $one->id,
                            $two->id,
                            null
                        ),
                        'plans' => $kpi_officeSelf_plans
                    ];
                }
            }
            foreach ($child_offices as $office) {
                $hasChild = !$office->offices->isEmpty();
                $office_level = $office->level ?: 1;

                // Initialize the office data
                $office_data = [
                    'id' => $office->id,
                    'office_name' => $office->officeTranslations[0]->name,
                    'has_child' => $hasChild,
                    'office_level' => $office_level,
                    'kpi_id' => $kpi->id,
                    'pp_year' => $planning_year->id,
                    "narration" => $planAccomplishment->getNarration(
                        $childAndHimOffKpi,
                        $kpi->id,
                        $planning_year->id ?? null,
                        $office
                    ),
                    'plans' => [] // Initialize to store plans for all KPI children
                ];
                // Process each KPI child
                foreach ($kpi->kpiChildOnes as $one) {
                    foreach ($kpi->kpiChildTwos as $two) {
                        // Add plan data for each reporting period
                        $kpi_child_plans = [];
                        foreach ($reportin_periods as $period) {
                            $planOfOfficePlan = $planAccomplishment->KpiOTT(
                                $childAndHimOffKpi,
                                $kpi->id,
                                $office,
                                $period->id,
                                false,
                                $planning_year->id ?? null,
                                $one->id,
                                $two->id,
                                null
                            );
                            //dump($office->id);
                            //dump($planOfOfficePlan[0]);
                            $kpi_child_plans[] = [
                                'reporting_period' => $period->reportingPeriodTs[0]->name,
                                'plan_value' => $planOfOfficePlan[0] ?? 0,
                                'plan_status' => $planOfOfficePlan[2] ?? 0
                            ];

                        }

                        // Add the plans for the current KPI child
                        $office_data['plans'][] = [
                            'kpi_child_one_name' => $one->kpiChildOneTranslations[0]->name,
                            'kpi_child_two_name' => $two->kpiChildTwoTranslations[0]->name,
                            'kpi_child_baseline' => planBaseline(
                                $childAndHimOffKpi,
                                $kpi->id,
                                $office,
                                $planning_year->id,
                                null,
                                $one->id,
                                $two->id,
                                null
                            ),
                            'plans' => $kpi_child_plans
                        ];
                    }
                }
                // Add the completed office data to the array
                $office_trans_array[] = $office_data;
            }
            $parent_office_trans_array[] = $parent_office_data_chOne;
            //dump($parent_office_trans_array );

        }
         // KPI has child  one
        else if(!count($kpi->kpiChildOnes) == 0){
            $parent_office_data_chOne = [
                'id' => $get_office->id,
                'office_name' => $get_office->officeTranslations[0]->name,
                'kpi_id' => $kpi->id,
                'pp_year' => $planning_year->id,
                "narration" => $planAccomplishment->getNarration(
                    $kpi->id,
                    $planning_year->id ?? null,
                    $get_office
                ),
                'planAndBaseline' => [],
            ];
             foreach ($planAccomplishment->Kpi->kpiChildOnes as $one) {
                $kpi_officeSelf_plans = [];
                foreach ($reportin_periods as $period) {
                    $planOfOfficePlanSelf = $planAccomplishment->OnlyKpiOTT(
                         $kpi->id, $get_office, $period->id,  false,
                          $planning_year->id ?? null,$one->id, null,null
                    );
                    $kpi_officeSelf_plans[] = [
                        'reporting_period' => $period->reportingPeriodTs[0]->name,
                        'plan_value' => $planOfOfficePlanSelf[0] ?? 0,
                        'plan_status' => $planOfOfficePlan[2] ?? 0
                    ];
                    // Add the reporting period name to the unique period array
                    $period_array[] = $period->reportingPeriodTs[0]->name;
                    $period_array = array_unique($period_array);
                }
                $parent_office_data_chOne['planAndBaseline'][] = [
                    'kpi_child_name' => $one->kpiChildOneTranslations[0]->name,
                    'kpi_child_baseline' => OnlyKpiOttBaseline(
                        $kpi->id,
                        $get_office,
                        $planning_year->id,
                        null,
                        $one->id,
                        null,
                        null
                    ),
                    'plans' => $kpi_officeSelf_plans
                ];
            }
            foreach ($child_offices as $office) {
                $hasChild = !$office->offices->isEmpty();
                $office_level = $office->level ?: 1;

                // Initialize the office data
                $office_data = [
                    'id' => $office->id,
                    'office_name' => $office->officeTranslations[0]->name,
                    'has_child' => $hasChild,
                    'office_level' => $office_level,
                    'kpi_id' => $kpi->id,
                    'pp_year' => $planning_year->id,
                    "narration" => $planAccomplishment->getNarration(
                        $kpi->id,
                        $planning_year->id ?? null,
                        $office
                    ),
                    'plans' => [] // Initialize to store plans for all KPI children
                ];
                // Process each KPI child
                foreach ($planAccomplishment->Kpi->kpiChildOnes as $one) {

                    // Add plan data for each reporting period
                    $kpi_child_plans = [];
                    foreach ($reportin_periods as $period) {
                        $planOfOfficePlan = $planAccomplishment->KpiOTT(
                            $kpi->id,
                            $office,
                            $period->id,
                            false,
                            $planning_year->id ?? null,
                            $one->id,
                            null,
                            null
                        );
                        //dump($office->id);
                         //dump($planOfOfficePlan[0]);
                        $kpi_child_plans[] = [
                            'reporting_period' => $period->reportingPeriodTs[0]->name,
                            'plan_value' => $planOfOfficePlan[0] ?? 0,
                            'plan_status' => $planOfOfficePlan[2] ?? 0
                        ];
                        // Add the reporting period name to the unique period array
                        $period_array[] = $period->reportingPeriodTs[0]->name;
                        $period_array = array_unique($period_array);
                    }

                    // Add the plans for the current KPI child
                    $office_data['plans'][] = [
                        'kpi_child_name' => $one->kpiChildOneTranslations[0]->name,
                        'kpi_child_baseline' => planBaseline(
                            $kpi->id,
                            $office,
                            $planning_year->id,
                            null,
                            $one->id,
                            null,
                            null
                        ),
                        'plans' => $kpi_child_plans
                     ];
                }
                // Add the completed office data to the array
                $office_trans_array[] = $office_data;
            }
            $parent_office_trans_array[] = $parent_office_data_chOne;
            //dump($parent_office_trans_array );
        }
        // KPI has no child
        else{ //dump($child_offices);
            foreach ($child_offices as $office) {
                $hasChild = !$office->offices->isEmpty();
                $office_level = $office->level ?: 1;
                //dump($planAccomplishment->);
                $office_data = [
                    'id' => $office->id,
                    'office_name' => $office->officeTranslations[0]->name,
                    'has_child' => $hasChild,
                    'office_level' => $office_level,
                    'kpi_id' => $kpi->id,
                    'pp_year' => $planning_year->id,
                    'baseline' => planBaseline(
                        $kpi->id,
                        $office,
                        $planning_year->id,
                        null,
                        $planAccomplishment->kpi_child_one_id,
                        $planAccomplishment->kpi_child_two_id,
                        $planAccomplishment->kpi_child_three_id
                    ),
                    "narration" => $planAccomplishment->getNarration(
                        $kpi->id,
                        $planning_year->id ?? null,
                        $office
                    ),
                    'plans' => [] // Store plans per reporting period
                ];
                  // get office self baseline
                $baseline_self = OnlyKpiOttBaseline( $kpi->id,$get_office, $planning_year->id, null, $planAccomplishment->kpi_child_one_id, $planAccomplishment->kpi_child_two_id,$planAccomplishment->kpi_child_three_id);
                foreach ($reportin_periods as $period) {
                    $planOfOfficePlan = $planAccomplishment->KpiOTT(
                        $kpi->id,
                        $office,
                        $period->id,
                        false,
                        $planning_year->id ?? null,
                        $planAccomplishment->kpi_child_one_id,
                        $planAccomplishment->kpi_child_two_id,
                        $planAccomplishment->kpi_child_three_id
                    );
                     //dump($planAccomplishment);
                    //dump($office->id);
                    //dump($planAccomplishment->kpi_child_one_id);

                    $office_data['plans'][] = [
                        'period_name' => $period->reportingPeriodTs[0]->name,
                        'plan_value' => $planOfOfficePlan[0],
                        'plan_status' => $planOfOfficePlan[2]?->plan_status,
                    ];

                    $period_array[] = $period->reportingPeriodTs[0]->name;
                    $period_array = array_unique($period_array); // Avoid duplicates
                }

                $office_trans_array[] = $office_data;


            }  //dump($office_level);
        }

        //dump($planning_year);
        //dump($kpi);
        //dump($get_office);


        foreach ($reportin_periods as $period) {
             $planOfOfficePlan_self[] = $planAccomplishment->OnlyKpiOTT($planAccomplishment->Kpi->id,$get_office, $period->id, false, $planning_year->id ?? null,$planAccomplishment->kpi_child_one_id, $planAccomplishment->kpi_child_two_id,$planAccomplishment->kpi_child_three_id);
        }
        $narrations_self[] = $planAccomplishment->getNarrationSelf(
            $planAccomplishment->Kpi->id,
            $planning_year->id ?? null,
            $get_office
        ); //dump($office_trans_array);

        $parent_office_data[] = [
            'parent_office_name' => $parent_office_name,
            'baseline_self' => $baseline_self,
            'planOfOfficePlan_self' => $planOfOfficePlan_self
        ];
        try {
            // Your logic to fetch and return data
            return response()->json([
                'office' => $get_office,
                'office_trans_array' => $office_trans_array,
                'parent_office_trans_array' => $parent_office_trans_array,
                 'period_array' => $period_array,
                'narrations_self' => $narrations_self,
                'planAccId' => $planAccomplishment->id,
                'office_level' => $office_level,
                'parent_office_data' => $parent_office_data,
                'kpi_children_data' => $kpi_children_data,
                'kpi_children' => $kpi_children_name

            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch data', 'message' => $e->getMessage()], 500);
        }


    }
    public function getDetailsTwo($id, $kpi_id, $year_id, Request $request)
    {
        $kpi = KeyPeformanceIndicator::find($kpi_id);
        // if (!count($kpi->kpiChildOnes) == 0) {
        //     dump($kpi->kpiChildOnes);
        // }

        $planning_year = PlaningYear::find($year_id);
        $planAccomplishment = PlanAccomplishment::where('kpi_id',$kpi_id)
        ->where('planning_year_id',$planning_year->id)
        ->first();
        $get_office = Office::find($id); // Use office ID from request
        $parent_office_name = $get_office->officeTranslations[0]->name;
        $reportin_periods = getQuarter($kpi->reportingPeriodType->id);
        $period_array = [];
        $office_trans_array = []; // Initialize the array to store structured data
        $parent_office_trans_array = []; // Initialize the array to store structured data
        $narrations = [];
        $userOffice = auth()->user()->offices[0];
        $child_offices = $get_office->offices;
        $kpi_children_data = []; // Array to hold KPI child data
        $baseline_self =null;

        $childAndHimOffKpi = office_all_childs_ids($get_office);

        if(!count($kpi->kpiChildThrees) == 0){
            $parent_office_data_chOne = [
                'id' => $get_office->id,
                'office_name' => $get_office->officeTranslations[0]->name,
                 'kpi_id' => $kpi->id,
                'pp_year' => $planning_year->id,
                'kpi_child_one_count' => count($kpi->kpiChildOnes),
                'kpi_child_two_count' => count($kpi->kpiChildTwos),
                'kpi_child_three_count' => count($kpi->kpiChildThrees),
                "narration" => $planAccomplishment->getNarration(
                    $childAndHimOffKpi,
                    $kpi->id,
                    $planning_year->id ?? null,
                    $get_office
                ),
                'planAndBaseline' => [],
              ];
             foreach ($kpi->kpiChildOnes as $one) {
                foreach ($kpi->kpiChildTwos as $two) {
                    foreach ($kpi->kpiChildThrees as $three) {
                        $kpi_officeSelf_plans = [];
                        foreach ($reportin_periods as $period) {
                            $planOfOfficePlanSelf = $planAccomplishment->OnlyKpiOTT(
                                $childAndHimOffKpi,
                                $kpi->id, $get_office, $period->id,  false,
                                $planning_year->id ?? null,$one->id, $two->id,$three->id
                            );
                            $kpi_officeSelf_plans[] = [
                                'reporting_period' => $period->reportingPeriodTs[0]->name,
                                'plan_value' => $planOfOfficePlanSelf[0] ?? 0,
                                'plan_status' => $planOfOfficePlan[2] ?? 0
                            ];
                            // Add the reporting period name to the unique period array
                            $period_array[] = $period->reportingPeriodTs[0]->name;
                            $period_array = array_unique($period_array);
                        }
                        $parent_office_data_chOne['planAndBaseline'][] = [
                            'kpi_child_one_name' => $one->kpiChildOneTranslations[0]->name,
                            'kpi_child_two_name' => $two->kpiChildTwoTranslations[0]->name,
                            'kpi_child_three_name' => $three->kpiChildThreeTranslations[0]->name,
                            'kpi_child_baseline' => OnlyKpiOttBaseline(
                                $childAndHimOffKpi,
                                $kpi->id,
                                $get_office,
                                $planning_year->id,
                                null,
                                $one->id,
                                $two->id,
                                $three->id
                            ),
                            'plans' => $kpi_officeSelf_plans
                        ];
                    }
                }
            }
            foreach ($child_offices as $office) {
                $hasChild = !$office->offices->isEmpty();
                $office_level = $office->level ?: 1;

                // Initialize the office data
                $office_data = [
                    'id' => $office->id,
                    'office_name' => $office->officeTranslations[0]->name,
                    'has_child' => $hasChild,
                    'office_level' => $office_level,
                    'kpi_id' => $kpi->id,
                    'pp_year' => $planning_year->id,
                    "narration" => $planAccomplishment->getNarration(
                        $childAndHimOffKpi,
                        $kpi->id,
                        $planning_year->id ?? null,
                        $office
                    ),
                    'plans' => [] // Initialize to store plans for all KPI children
                ];
                // Process each KPI child
                foreach ($kpi->kpiChildOnes as $one) {
                    foreach ($kpi->kpiChildTwos as $two) {
                        foreach ($kpi->kpiChildThrees as $three) {
                            // Add plan data for each reporting period
                            $kpi_child_plans = [];
                            foreach ($reportin_periods as $period) {
                                $planOfOfficePlan = $planAccomplishment->KpiOTT(
                                    $childAndHimOffKpi,
                                    $kpi->id,
                                    $office,
                                    $period->id,
                                    false,
                                    $planning_year->id ?? null,
                                    $one->id,
                                    $two->id,
                                    $three->id
                                );
                                //dump($office->id);
                                //dump($planOfOfficePlan[0]);
                                $kpi_child_plans[] = [
                                    'reporting_period' => $period->reportingPeriodTs[0]->name,
                                    'plan_value' => $planOfOfficePlan[0] ?? 0,
                                    'plan_status' => $planOfOfficePlan[2] ?? 0
                                ];

                            }

                            // Add the plans for the current KPI child
                            $office_data['plans'][] = [
                                'kpi_child_one_name' => $one->kpiChildOneTranslations[0]->name,
                                'kpi_child_two_name' => $two->kpiChildTwoTranslations[0]->name,
                                'kpi_child_baseline' => planBaseline(
                                    $childAndHimOffKpi,
                                    $kpi->id,
                                    $office,
                                    $planning_year->id,
                                    null,
                                    $one->id,
                                    $two->id,
                                    $three->id
                                ),
                                'plans' => $kpi_child_plans
                            ];
                        }
                    }
                }
                // Add the completed office data to the array
                $office_trans_array[] = $office_data;
            }
            $parent_office_trans_array[] = $parent_office_data_chOne;
            //dump($parent_office_trans_array );
        }
        else{
            $parent_office_data_chOne = [
                'id' => $get_office->id,
                'office_name' => $get_office->officeTranslations[0]->name,
                 'kpi_id' => $kpi->id,
                'pp_year' => $planning_year->id,
                'kpi_child_one_count' => count($kpi->kpiChildOnes),
                'kpi_child_two_count' => count($kpi->kpiChildTwos),
                "narration" => $planAccomplishment->getNarration(
                    $childAndHimOffKpi,
                    $kpi->id,
                    $planning_year->id ?? null,
                    $get_office
                ),
                'planAndBaseline' => [],
              ];
             foreach ($kpi->kpiChildOnes as $one) {
                foreach ($kpi->kpiChildTwos as $two) {
                    $kpi_officeSelf_plans = [];
                    foreach ($reportin_periods as $period) {
                        $planOfOfficePlanSelf = $planAccomplishment->OnlyKpiOTT(
                            $childAndHimOffKpi,
                            $kpi->id, $get_office, $period->id,  false,
                            $planning_year->id ?? null,$one->id, $two->id,null
                        );
                        $kpi_officeSelf_plans[] = [
                            'reporting_period' => $period->reportingPeriodTs[0]->name,
                            'plan_value' => $planOfOfficePlanSelf[0] ?? 0,
                            'plan_status' => $planOfOfficePlan[2] ?? 0
                        ];
                        // Add the reporting period name to the unique period array
                        $period_array[] = $period->reportingPeriodTs[0]->name;
                        $period_array = array_unique($period_array);
                    }
                    $parent_office_data_chOne['planAndBaseline'][] = [
                        'kpi_child_one_name' => $one->kpiChildOneTranslations[0]->name,
                        'kpi_child_two_name' => $two->kpiChildTwoTranslations[0]->name,

                        'kpi_child_baseline' => OnlyKpiOttBaseline(
                            $childAndHimOffKpi,
                            $kpi->id,
                            $get_office,
                            $planning_year->id,
                            null,
                            $one->id,
                            $two->id,
                            null
                        ),
                        'plans' => $kpi_officeSelf_plans
                    ];
                }
            }
            foreach ($child_offices as $office) {
                $hasChild = !$office->offices->isEmpty();
                $office_level = $office->level ?: 1;

                // Initialize the office data
                $office_data = [
                    'id' => $office->id,
                    'office_name' => $office->officeTranslations[0]->name,
                    'has_child' => $hasChild,
                    'office_level' => $office_level,
                    'kpi_id' => $kpi->id,
                    'pp_year' => $planning_year->id,
                    "narration" => $planAccomplishment->getNarration(
                        $childAndHimOffKpi,
                        $kpi->id,
                        $planning_year->id ?? null,
                        $office
                    ),
                    'plans' => [] // Initialize to store plans for all KPI children
                ];
                // Process each KPI child
                foreach ($kpi->kpiChildOnes as $one) {
                    foreach ($kpi->kpiChildTwos as $two) {
                        // Add plan data for each reporting period
                        $kpi_child_plans = [];
                        foreach ($reportin_periods as $period) {
                            $planOfOfficePlan = $planAccomplishment->KpiOTT(
                                $childAndHimOffKpi,
                                $kpi->id,
                                $office,
                                $period->id,
                                false,
                                $planning_year->id ?? null,
                                $one->id,
                                $two->id,
                                null
                            );
                            //dump($office->id);
                            //dump($planOfOfficePlan[0]);
                            $kpi_child_plans[] = [
                                'reporting_period' => $period->reportingPeriodTs[0]->name,
                                'plan_value' => $planOfOfficePlan[0] ?? 0,
                                'plan_status' => $planOfOfficePlan[2] ?? 0
                            ];

                        }

                        // Add the plans for the current KPI child
                        $office_data['plans'][] = [
                            'kpi_child_one_name' => $one->kpiChildOneTranslations[0]->name,
                            'kpi_child_two_name' => $two->kpiChildTwoTranslations[0]->name,
                            'kpi_child_baseline' => planBaseline(
                                $childAndHimOffKpi,
                                $kpi->id,
                                $office,
                                $planning_year->id,
                                null,
                                $one->id,
                                $two->id,
                                null
                            ),
                            'plans' => $kpi_child_plans
                        ];
                    }
                }
                // Add the completed office data to the array
                $office_trans_array[] = $office_data;
            }
            $parent_office_trans_array[] = $parent_office_data_chOne;
            //dump($parent_office_trans_array );
        }
        foreach ($reportin_periods as $period) {
             $planOfOfficePlan_self[] = $planAccomplishment->OnlyKpiOTT($childAndHimOffKpi, $planAccomplishment->Kpi->id,$get_office, $period->id, false, $planning_year->id ?? null,$planAccomplishment->kpi_child_one_id, $planAccomplishment->kpi_child_two_id,$planAccomplishment->kpi_child_three_id);
        }
        $narrations_self[] = $planAccomplishment->getNarrationSelf(
            $planAccomplishment->Kpi->id,
            $planning_year->id ?? null,
            $get_office
        ); //dump($office_trans_array);

        $parent_office_data[] = [
            'parent_office_name' => $parent_office_name,
            'baseline_self' => $baseline_self,
            'planOfOfficePlan_self' => $planOfOfficePlan_self
        ];
        try {
            // Your logic to fetch and return data
            return response()->json([
                'office' => $get_office,
                'office_trans_array' => $office_trans_array,
                'parent_office_trans_array' => $parent_office_trans_array,
                 'period_array' => $period_array,
                'narrations_self' => $narrations_self,
                'planAccId' => $planAccomplishment->id,
                'office_level' => $office_level,
                'parent_office_data' => $parent_office_data,
                'kpi_children_data' => $kpi_children_data,

            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch data', 'message' => $e->getMessage()], 500);
        }


    }
}
