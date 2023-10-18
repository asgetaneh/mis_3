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
use App\Models\PlaningYear;
// use DateTime;
// use Redirect;



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
                $report_period = ReportingPeriod::where('id' , '=', $value->id)->where('reporting_period_type_id', '=', $type)->get();
                    if($report_period){
                        foreach ($report_period as $key2 => $period) {
                            $acctive_period_list[$key2] = $period;
                        }
                     }
            }
        }
        //dd($acctive_period_list);
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
}