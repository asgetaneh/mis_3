<?php
  use App\Models\OwnershipTranslation;
  use App\Models\SuppervisionTranslation;
  use App\Models\ReportingPeriod;
  use App\Models\Office;
  use App\Models\PlanAccomplishment;
  use App\Models\ReportNarration;
 use App\Models\KeyPeformanceIndicator;
use App\Models\ReportNarrationReport;

use Carbon\Carbon;
use Andegna\DateTime as Et_date;
use Andegna\DateTimeFactory;
use App\Models\Baseline;
use App\Models\PlaningYear;
// use DateTime;
// use Redirect;

use Illuminate\Support\Collection;

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('gettrans')) {
    function getAllOffices()
    {
    	$off = Office::get();

        return $off;
    }
     function getAllKpi()
    {
    	$kpi = KeyPeformanceIndicator::get();

        return $kpi;
    }
    function  getKpiPlan($kpii,$list_offices,$selected_period)
    {
        $planning_year = PlaningYear::where('is_active',true)->first();
        $active_period = getReportingQuarter($kpii->reportingPeriodType->id);
        if($selected_period){
            $active_period = $selected_period;
        }     //dd($list_offices);

        $planAccomplishments = '';
        if($active_period){
            $planAccomplishments = PlanAccomplishment::
            join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
            -> where('reporting_periods.id',"=", $active_period[0]->id)
            -> where('kpi_id' , '=', $kpii->id)
            ->where('planning_year_id' , '=', $planning_year->id ?? NULL)
            ->where('plan_status' , '=', 1)
         ->whereIn('office_id', $list_offices)
        // -> where('reporting_periods.slug',"=", 1)
        //->groupBy('kpi_id')
             ->get();
        }
        else{
            $planAccomplishments = PlanAccomplishment::
            join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
             -> where('kpi_id' , '=', $kpii->id)
            ->where('planning_year_id' , '=', $planning_year->id ?? NULL)
            ->where('plan_status' , '=', 1)
            ->whereIn('office_id', $list_offices)
            -> where('reporting_periods.slug',"=", 1)
        //->groupBy('kpi_id')
             ->get();
        }
        $plan_value = 0;
        $acc_value = 0;
        $plan_accom_array = [];
        if(!empty($planAccomplishments)) {
            foreach ($planAccomplishments as $key => $planAccomplishment) {
                $plan_value = $plan_value+ $planAccomplishment->plan_value;
                $acc_value = $acc_value+ $planAccomplishment->accom_value;
            }
        }

        $plan_accom_array = array_merge( $plan_accom_array,array($plan_value));
            $plan_accom_array = array_merge( $plan_accom_array,array($acc_value));
            return $plan_accom_array;
    }

    function getAllReportingPeriod()
    {
    	$ReportingPeriod = ReportingPeriod::get();

        return $ReportingPeriod;
    }
    function getKpiReport($Kpi,$office){
        $offices =  allChildAndChildChild($office);
         $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')->whereIn('office_id', $offices)->where('kpi_id','=', $Kpi->id);
        //  $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')->whereIn('office_id', $offices)->where('kpi_id','=', $Kpi->id);
               $total = 0;
        // constant
        if($Kpi->behavior->slug == 1){//dd($planAccomplishments);
            $planAccomplishments=  $planAccomplishments->select('*')->get();
            foreach ($planAccomplishments as $key => $planAccomplishment) {
               $total = $total+ $planAccomplishment->accom_value;
            }
            return $total;
        }
        // constant
        else if($Kpi->behavior->slug == 2){
            $current_report_periods = getReportingQuarter($Kpi->reportingPeriodType->id);
            foreach ($current_report_periods as $key => $current_report_period) {
               $period = $current_report_period->id;
            }
             $planAccomplishments= $planAccomplishments->where('plan_accomplishments.reporting_period_id','=', $period)->select('*')->get();
            foreach ($planAccomplishments as $key => $planAccomplishment) {
                $total = $total+ $planAccomplishment->accom_value;
            }
            return $total;
        }
        //incrimental
        else if($Kpi->behavior->slug == 3){
             $current_report_periods = getReportingQuarter($Kpi->reportingPeriodType->id);
            foreach ($current_report_periods as $key => $current_report_period) {
               $period = $current_report_period->id;
            }
             $planAccomplishments= $planAccomplishments->where('plan_accomplishments.reporting_period_id','=', $period)->select('*')->get();
            foreach ($planAccomplishments as $key => $planAccomplishment) {
                $total = $total+ $planAccomplishment->accom_value;
            }
            return $total;
        }
         //decrimental
        else if($Kpi->behavior->slug == 4){
             $current_report_periods = getReportingQuarter($Kpi->reportingPeriodType->id);
            foreach ($current_report_periods as $key => $current_report_period) {
               $period = $current_report_period->id;
            }
             $planAccomplishments= $planAccomplishments->where('plan_accomplishments.reporting_period_id','=', $period)->select('*')->get();
            foreach ($planAccomplishments as $key => $planAccomplishment) {
                $total = $total+ $planAccomplishment->accom_value;
            }
            return $total;
        }
    }
     function getQuarter($type)
    {
    	$reservations = ReportingPeriod::where('reporting_period_type_id', '=', $type) ->get();

        return $reservations;
    }
     function getReportingQuarter($type)
    {
        $acctive_period_list =[];
        //$report_period_list = ReportingPeriod::all();
        $report_period_list = ReportingPeriod::where('reporting_period_type_id', '=', $type)->get();
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
                $report_period = ReportingPeriod::where('id' , '=', $value->id)->get();
                    if($report_period){
                        foreach ($report_period as $key2 => $period) {
                            $acctive_period_list[$key2] = $period;
                        }
                     }
            }
        }
         return $acctive_period_list;

     }

    function checkPlanedForKpi($year,$kpi,$office){
     $planAccomplishments = PlanAccomplishment::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi) ->get();//dump($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment;
        }
      //return $kpi.$period.$one.$two.$three.$office;
   }
   function getSavedPlanIndividualOneTwoThree($year,$kpi,$period, $one, $two,$three,$office){
     $planAccomplishments = PlanAccomplishment::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('reporting_period_id' , '=', $period)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_two_id' , '=', $two)->where('kpi_child_three_id' , '=', $three)->get();//dump($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment;
        }
      //return $kpi.$period.$one.$two.$three.$office;
   }
    function getSavedPlanIndividualOneTwo($year,$kpi,$period, $one, $two,$office){
     $planAccomplishments = PlanAccomplishment::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('reporting_period_id' , '=', $period)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_two_id' , '=', $two)->get();//dd($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment;
        }
    }
   function getSavedPlanIndividualOne($year,$kpi,$period, $one, $office){
     $planAccomplishments = PlanAccomplishment::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('reporting_period_id' , '=', $period)->where('kpi_child_one_id' , '=', $one)->get();//dd($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment;
        }
    }
   function getSavedPlanIndividual($year,$kpi,$period, $office){
     $planAccomplishments = PlanAccomplishment::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('reporting_period_id' , '=', $period)->get();//dd($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment;
        }
    }
    function getSavedPlanNaration($year,$kpi, $office){
     $ReportNarrations = ReportNarration::select()->where('planing_year_id' , '=', $year)->where('office_id' , '=', $office)->where('key_peformance_indicator_id' , '=', $kpi)->get();//dd($planAccomplishments);
        foreach ($ReportNarrations as $key => $ReportNarration) {
            return $ReportNarration->plan_naration;
        }
    }
    function getSavedPlanDocument($year,$period,$kpi, $office){
     $ReportNarrationreports = ReportNarrationReport::select()->where('planing_year_id' , '=', $year)->where('reporting_period_id' , '=', $period)->where('office_id' , '=', $office)->where('key_peformance_indicator_id' , '=', $kpi)->get();//dd($ReportNarrationreports);
        foreach ($ReportNarrationreports as $key => $ReportNarrationreport) {
           // dd($ReportNarrationreport->approval_text);
            return $ReportNarrationreport->approval_text;
        }
    }
    function getSavedReportNaration($year,$period,$kpi, $office){
     $ReportNarrations = ReportNarrationReport::select()->where('planing_year_id' , '=', $year)->where('reporting_period_id' , '=', $period)->where('office_id' , '=', $office)->where('key_peformance_indicator_id' , '=', $kpi)->get();
        foreach ($ReportNarrations as $key => $ReportNarration) {
            return $ReportNarration->report_naration;
        }
    }
    function gettransSuppervision($locale, $idd)
    {
      $reservations = SuppervisionTranslation::where('suppervision_id', '=', $idd)
                           ->where('locale', '=', $locale)
                           ->get();

        return $reservations;
    }
    function getKeyperormanceIndicators($objective, $office){
         $KeyPeformanceIndicators = KeyPeformanceIndicator::select('key_peformance_indicators.*')
                     ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                     ->join('offices', 'offices.id', '=', 'kpi_office.office_id')

                     ->join('objectives', 'key_peformance_indicators.objective_id', '=', 'objectives.id')
                     ->where('offices.id' , '=', $office)
                    ->where('objective_id' , '=', $objective->id)
                    ->get();

             return $KeyPeformanceIndicators;

    }
    function office_all_childs_ids(App\Models\Office $office)
        {
            $all_ids = [];
            if ($office->offices->count() > 0) {
                foreach ($office->offices as $child) {
                    $all_ids[] = $child->id;
                    $all_ids=array_merge($all_ids,is_array(office_all_childs_ids($child))?office_all_childs_ids($child):[] );
                }
            }
            return $all_ids;
        }
    //function getTimeMatchedReportingPeriod(){
    function getReportingPeriod(){
        $acctive_period_list =[];
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
                $report_period = ReportingPeriod::where('id' , '=', $value->id)->get();
                    if($report_period){
                        foreach ($report_period as $key2 => $period) {
                            $acctive_period_list[$key] = $period->id;
                        }
                     }
            }
        }
        return $acctive_period_list;
    }
    function getKeyperormanceIndicatorsForReporting($objective, $office,$period){
         $KeyPeformanceIndicators = KeyPeformanceIndicator::select('key_peformance_indicators.*')
                     ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                     ->join('offices', 'offices.id', '=', 'kpi_office.office_id')

                     ->join('objectives', 'key_peformance_indicators.objective_id', '=', 'objectives.id')
                    ->join('reporting_period_types', 'key_peformance_indicators.reporting_period_type_id', '=', 'reporting_period_types.id')
                    ->join('reporting_periods', 'reporting_periods.reporting_period_type_id', '=', 'reporting_period_types.id')
                     ->whereIn('reporting_periods.id', $period)
                     ->where('offices.id' , '=', $office)
                    ->where('objective_id' , '=', $objective->id)
                    ->get();

             return $KeyPeformanceIndicators;

    }
     function allChildAndChildChild($office){
     $all_ids = [];
        if ($office->offices->count() > 0) {
            foreach ($office->offices as $child) {
                $all_ids[] = $child->id;
                $all_ids=array_merge($all_ids,is_array(office_all_childs_ids($child))?office_all_childs_ids($child):[] );
            }
        }
        return $all_ids;
   }


   // Baseline functions
   function getBaselineIndividual($year,$kpi, $office){
    $planAccomplishments = Baseline::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->get();
       foreach ($planAccomplishments as $key => $planAccomplishment) {
           return $planAccomplishment;
       }
   }

   function getBaselineIndividualOne($year,$kpi, $one, $office){
    $planAccomplishments = Baseline::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('kpi_one_id' , '=', $one)->get();
       foreach ($planAccomplishments as $key => $planAccomplishment) {
           return $planAccomplishment;
       }
   }

   function getBaselineIndividualOneTwo($year,$kpi, $one, $two,$office){
    $planAccomplishments = Baseline::select()->where('planning_year_id' , '=', $year)
        ->where('office_id' , '=', $office)
        ->where('kpi_id' , '=', $kpi)
        ->where('kpi_one_id' , '=', $one)
        ->where('kpi_two_id' , '=', $two)
        ->get();

       foreach ($planAccomplishments as $key => $planAccomplishment) {
           return $planAccomplishment;
       }
   }

   function getBaselineIndividualOneTwoThree($year,$kpi, $one, $two,$three,$office){
    $planAccomplishments = Baseline::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('kpi_one_id' , '=', $one)->where('kpi_two_id' , '=', $two)->where('kpi_three_id' , '=', $three)->get();
       foreach ($planAccomplishments as $key => $planAccomplishment) {
           return $planAccomplishment;
       }
  }

  function getBaselineLastYear($kpi, $planningYear, $period, $office, $one = null, $two = null, $three = null){

    $previousYear = '';

    $lessYear = Carbon::now();
    $lessYear->year = $lessYear->year - 1;

    $previousYear = PlaningYear::where('is_active', 0)
        ->where('created_at', '<', $lessYear)
        ->first();

    $baseline = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
        ->where('kpi_id', $kpi)
        ->where('office_id', $office)
        ->where('planning_year_id', $previousYear->id ?? null)
        ->where('kpi_child_one_id', $one)
        ->where('kpi_child_two_id', $two)
        ->where('kpi_child_three_id', $three)
        ->where('reporting_periods.slug',"=", 1)
        ->first();

    return $baseline->plan_value ?? '';
}
 function getAcademicRecords($enrollments)
    {
    $stu_record = collect();

    foreach ($enrollments as $key => $enrolment) {
        // code...
    $precedingRecordId = DB::connection('mysql_srs')
        ->table('student_info')
        ->where('student_id', $enrolment->stu_info_stu_id)
        ->where('id', '<', $enrolment->stu_info_id)
        ->max('id'); // Gets the maximum ID less than the active record's ID

    if ($precedingRecordId) {
         // check previous record of active record is not active
         $precedingRecordInactive = DB::connection('mysql_srs')
            ->table('student_info as ifo')
            ->select('ifo.record_status as record_status')
            ->where('id', $precedingRecordId)
             ->get();
         if($precedingRecordInactive[0]->record_status==0){
            //dd($precedingRecordInactive[0]->record_status);
        // 3. Fetch the Record with that Maximum ID
        $stud_enrolmet_var = DB::connection('mysql_srs')
            ->table('student as s')
            ->join('sf_guard_user as sf', 'sf.id', '=', 's.sf_guard_user_id')
            ->join('student_info as ifo', 's.id', '=', 'ifo.student_id')
             ->join('check_list as cl', 'ifo.check_list_id', '=', 'cl.id')
            ->join('student_detail as sd', 's.id', '=', 'sd.student_id')
            ->leftJoin('campus as ca', 'sd.campus_id', '=', 'ca.id')
            ->leftJoin('sponsor as sp', 'sd.sponsor_id', '=', 'sp.id')
            ->join('disabled_students as ds', 's.student_id', '=', 'ds.disabled_student_id')
            ->join('disability as di', 'ds.disability_id', '=', 'di.id')
            ->join('foreign_program as fp', 'sd.foreign_program_id', '=', 'fp.id')
            ->join('program as p', 'ifo.program_id', '=', 'p.id')
            ->join('program_level as pl', 'p.program_level_id', '=', 'pl.id')
            ->join('enrollment_type as et', 'p.enrollment_type_id', '=', 'et.id')
            ->join('department as d', 'd.id', '=', 'p.department_id')
             ->select(
                's.student_id as student_id_number',
                'sp.sponsor_code',
                'ifo.student_id as stu_info_stu_id',
                'ifo.id as stu_info_id',
                'ifo.academic_year',
                'ifo.semester AS academic_period', // later check where each academic period data code is stored, for now just the value

                // Not sure which columns match the excel colummns for gpa and ECTS based data, figure out later
                'ifo.total_ects AS cumulative_registered_credits',
                'ifo.semester_ects AS current_registered_credits',
                 'ifo.previous_total_ects AS cumulative_completed_credits',
                 'cl.required_credit as required_credits',
                 'cl.number_of_semesters AS required_academic_periods',

                DB::raw('ROUND(ifo.total_grade_points / ifo.total_ects, 2) as cumulative_gpa'),

                'ifo.year AS year_level',
                'ca.campus_name AS campus_code',
                // 'col.code AS college_code',
                'd.department_code',
                'p.program_code',
                'pl.code AS target_qualification',
                'et.enrollment_type_code AS program_modality',
                'di.disability_code as student_disability',
                'fp.foreign_program_code as foreign_program',

            )
            ->where('ifo.id', $precedingRecordId)
            ->first(); // Retrieves the record

            $stu_record->push($stud_enrolmet_var);
     }
    }
    }
         return $stu_record; 
    }

     function getAcademicRecordsOfStudentResult($forresults) 
        {  
        $stu_record_result = collect();

        foreach ($forresults as $key => $forresult) {
            // code...
        $precedingRecordId = DB::connection('mysql_srs')
            ->table('student_info')
            ->where('student_id', $forresult->stu_info_stu_id)
            ->where('id', '<', $forresult->stu_info_id)
            ->max('id'); // Gets the maximum ID less than the active record's ID
        $precedingRecordInactive = DB::connection('mysql_srs')
                ->table('student_info as ifo')
                ->select('ifo.student_id as student_id')
                ->where('ifo.id', $precedingRecordId)
                ->where('ifo.record_status', 0)
                 ->get(); 
             // check previous record of active record is not active and also has prevous records
        if ($precedingRecordInactive) {
                     // 3. Fetch the Record with that Maximum ID
            $stud_result_var = DB::connection('mysql_srs')
                ->table('student as s')
                ->join('sf_guard_user as sf', 'sf.id', '=', 's.sf_guard_user_id')
                ->join('student_info as ifo', 's.id', '=', 'ifo.student_id')
                ->join('student_status as ss', 'ifo.status_id', '=', 'ss.id')
                ->join('program as p', 'ifo.program_id', '=', 'p.id')
                ->join('department as d', 'd.id', '=', 'p.department_id')
                ->select(
                's.student_id',
                'd.department_code',
                'ifo.academic_year',
                'ifo.laction',
                'ifo.semester AS academic_period', // later check where each academic period data code is stored, for now just the value
                'ss.id AS result', // used the id column to check the status of pass and fail

                // Not sure which columns match the excel columns for gpa and ECTS based data, figure out later
                'ifo.total_ects AS total_accumulated_credits',
                DB::raw('ROUND(ifo.semester_grade_points / ifo.semester_ects ,2) as gpa'),
                DB::raw('ROUND(ifo.total_grade_points / ifo.total_ects, 2) as cgpa'),

                // I think this is all the semester count taken in that year, not sure yet
                // DB::raw('count(ifo.semester) as total_academic_periods'),
            )
            ->orderBy('d.department_code', 'desc')
            ->get();

                $stu_record_result->push($stud_result_var); 
        }
        }dd( $stu_record_result);
             return $stu_record_result;      
        }
}
