<?php
  use App\Models\OwnershipTranslation;
  use App\Models\SuppervisionTranslation;
  use App\Models\ReportingPeriod;
  use App\Models\PlanAccomplishment;
  use App\Models\ReportNarration;
 use App\Models\KeyPeformanceIndicator;



/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('gettrans')) {
    function getQuarter($type)
    {
    	$reservations = ReportingPeriod::where('reporting_period_type_id', '=', $type) ->get();
 
        return $reservations;
    }
    
   function getSavedPlanIndividualOneTwoThree($year,$kpi,$period, $one, $two,$three,$office){
     $planAccomplishments = PlanAccomplishment::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('reporting_period_id' , '=', $period)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_two_id' , '=', $two)->where('kpi_child_three_id' , '=', $three)->get();//dd($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment->plan_value;
        }
      //return $kpi.$period.$one.$two.$three.$office;
   }
    function getSavedPlanIndividualOneTwo($year,$kpi,$period, $one, $two,$office){
     $planAccomplishments = PlanAccomplishment::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('reporting_period_id' , '=', $period)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_two_id' , '=', $two)->get();//dd($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment->plan_value;
        }
    }
   function getSavedPlanIndividualOne($year,$kpi,$period, $one, $office){
     $planAccomplishments = PlanAccomplishment::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('reporting_period_id' , '=', $period)->where('kpi_child_one_id' , '=', $one)->get();//dd($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment->plan_value;
        }
    }
   function getSavedPlanIndividual($year,$kpi,$period, $office){
     $planAccomplishments = PlanAccomplishment::select()->where('planning_year_id' , '=', $year)->where('office_id' , '=', $office)->where('kpi_id' , '=', $kpi)->where('reporting_period_id' , '=', $period)->get();//dd($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment->plan_value;
        }
    }
    function getSavedPlanNaration($year,$kpi, $office){
     $ReportNarrations = ReportNarration::select()->where('planing_year_id' , '=', $year)->where('office_id' , '=', $office)->where('key_peformance_indicator_id' , '=', $kpi)->get();//dd($planAccomplishments);
        foreach ($ReportNarrations as $key => $ReportNarration) {
            return $ReportNarration->plan_naration;
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
}