<?php

use App\Models\Office;
use App\Models\Baseline;
use App\Models\PlanComment;
use App\Models\PlaningYear;
use App\Models\ReportingPeriod;
use App\Models\ReportNarration;
use App\Models\OfficeTranslation;
use App\Models\PlanAccomplishment;
use Illuminate\Support\Facades\DB;
use App\Models\KeyPeformanceIndicator;

/**
 * Write code on Method
 *
 * @return response()
 */

function isLastOfficeBelongToKpi($office, $kpi)
{

     $data = KeyPeformanceIndicator::select('key_peformance_indicators.*')
         ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
         ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
         ->where('kpi_office.kpi_id', '=', $kpi)
         ->where('kpi_office.office_id', $office->id)
         ->get();

     return $data;
}
function isOfficeBelongToKpi($office, $kpi)
{

    $allOffices = office_all_childs_ids($office);
    $allOffices = array_merge($allOffices, array($office->id));

    $data = KeyPeformanceIndicator::select('key_peformance_indicators.id')
        ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
        ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
        ->where('kpi_office.kpi_id', '=', $kpi)
        ->whereIn('kpi_office.office_id', $allOffices)
        ->get();//dump($data);

    return $data;
}

// get if the child office of the current father has a plan for the kpi provided
function getOfficePlanRecord($kpi, $office, $year)
{
    $planRecord = PlanAccomplishment::select()
        ->where('kpi_id', $kpi)
        ->where('office_id', $office->id)
        ->where('planning_year_id', $year)
        ->get();

    return $planRecord;
}

// get all current father's children KPIs
function getKpiImmediateChilds($offices)
{
    $allKpis = KeyPeformanceIndicator::select('key_peformance_indicators.*')
        ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
        ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
        // ->where('offices.id' , '=', $offices)
        ->whereIn('kpi_office.office_id', $offices)
        ->get();

    $kpiList = [];
    foreach ($allKpis as $key => $value) {
        $kpiList[$key] = $value->id;
    }

    return $kpiList;
}

// get if current office children are approved so that for the kpi provided their plan value will be summed with their parent
function getOfficeChildrenApprovedList($kpi, $office, $year, $suffix)
{

    $childAndHimOffKpi = office_all_childs_ids($office);
    if ($suffix == 1) {

        $planRecord = PlanAccomplishment::select('office_id')
            ->where('kpi_id', $kpi)
            // ->whereIn('office_id', $childOffices)
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('planning_year_id', $year)->distinct('office_id')
            ->where('plan_status', $office->level)
            ->get();

        $officeIds = [];
        if ($planRecord) {
            foreach ($planRecord as $plan) {
                array_push($officeIds, $plan->office_id);
            }
        }

        return $officeIds;
    }
    else if($suffix == 2){
        $planRecordOffice = PlanAccomplishment::select('office_id')
            ->where('kpi_id', $kpi)
            // ->whereIn('office_id', $childOffices)
            ->where('office_id', $office->id)
            ->where('planning_year_id', $year)->distinct('office_id')
            ->get();

        $planRecord = PlanAccomplishment::select('office_id')
            ->where('kpi_id', $kpi)
            // ->whereIn('office_id', $childOffices)
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('planning_year_id', $year)->distinct('office_id')
            ->where('plan_status', '<',$office->level)
            ->orWhere('plan_status', '=', $office->level)
            // ->where(function ($q) {
            //     $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            // })
            ->get();
                $officeIds = [];

        if ($planRecordOffice) {
            foreach ($planRecordOffice as $plan) {
                array_push($officeIds, $plan->office_id);
            }
        }
        if ($planRecord) {
            foreach ($planRecord as $plan) {
                array_push($officeIds, $plan->office_id);
            }
        }

        return $officeIds;
    }
    else {
        $merged = array_merge($childAndHimOffKpi, array($office->id));

        $planRecord = PlanAccomplishment::select()
            ->where('kpi_id', $kpi)
            // ->whereIn('office_id', $childOffices)
            ->whereIn('office_id', $merged)
            ->where('planning_year_id', $year)->distinct('office_id')
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        $officeIds = [];
        if ($planRecord) {
            foreach ($planRecord as $plan) {
                array_push($officeIds, $plan->office_id);
            }
        }

        return $officeIds;
    }
}






// ----------------------------------------------------------------
// code from PlanAccomplishment model, to prevent conflicts
// ----------------------------------------------------------------
function getOfficeFromKpiAndOfficeList($kpi, $only_child_array)
{ //dump($off_list);

    $only_child_array = array_merge($only_child_array, array(auth()->user()->offices[0]->id));
    $offices = Office::select('offices.*')
        ->whereIn('id', $only_child_array)
        //               ->where('kpi_id' , '=', $kpi->id)
        ->get();
        // dd($offices);

    // $offices = Office::select('offices.*')
    //     ->join('kpi_office', 'offices.id', '=', 'kpi_office.office_id')
    //     ->whereIn('id', $only_child_array)
    //     ->where('kpi_id', '=', $kpi->id)
    //     ->get();

    // $offices = Office::select('offices.*')
    //               ->join('kpi_office', 'offices.id', '=', 'kpi_office.office_id')
    //              ->join('key_peformance_indicators', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
    //               ->whereIn('office_id', $only_child_array)
    //               ->where('kpi_id' , '=', $kpi->id)
    //              ->get();
    return $offices;
}
function planIndividual($kkp, $one, $two, $three, $office, $period, $suffix)
{
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
    $planAccomplishmentsCurrent = '';
    $planAccomplishmentsChildren = '';

    if ($suffix == 1) {

        // All current office children if exist with the logged in user office level
        $planAccomplishmentsChildParent = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', $one)
            ->where('kpi_child_two_id', '=', $two)
            ->where('kpi_child_three_id', '=', $three)
            ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        // Current offices record
        $planAccomplishmentsCurrent = PlanAccomplishment::select()
            ->where('office_id', $office->id)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', $one)
            ->where('kpi_child_two_id', '=', $two)
            ->where('kpi_child_three_id', '=', $three)
            ->where('reporting_period_id', '=', $period)
            ->get();

        // Current office children record if exists
        $planAccomplishmentsChildren = PlanAccomplishment::select()
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', '=', $kkp)
            ->where('kpi_child_one_id', '=', $one)
            ->where('kpi_child_two_id', '=', $two)
            ->where('kpi_child_three_id', '=', $three)
            ->where('plan_status', $office->level)
            ->where('reporting_period_id', '=', $period)
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishmentsCurrent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildren as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildParent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    } elseif ($suffix == 3) {
        // All current office children if exist with the logged in user office level
        $planAccomplishments = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', array_merge($childAndHimOffKpi, array($office->id)))
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', $one)
            ->where('kpi_child_two_id', '=', $two)
            ->where('kpi_child_three_id', '=', $three)
            ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishments as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    }

    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    $sum_of_sub_office = 0;
    // $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id', $childAndHimOffKpi_array)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_two_id' , '=', $two)->where('kpi_child_three_id' , '=', $three)->where('reporting_period_id' , '=', $period)->get();//dd($planAccomplishments);
    $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id', $office->id)->where('kpi_id', '=', $kkp)->where('kpi_child_one_id', '=', $one)->where('kpi_child_two_id', '=', $two)->where('kpi_child_three_id', '=', $three)->where('reporting_period_id', '=', $period)->get(); //dd($planAccomplishments);
    foreach ($planAccomplishments as $key => $planAccomplishment) {
        $sum_of_sub_office = $sum_of_sub_office + $planAccomplishment->plan_value;
    }
    return $sum_of_sub_office;
}
function planIndividualChOnechThreeSum($kkp, $one, $two, $three, $office, $suffix)
{
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
    $planAccomplishmentsCurrent = '';
    $planAccomplishmentsChildren = '';

    if ($suffix == 1) {

        // All current office children if exist with the logged in user office level
        $planAccomplishmentsChildParent = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', '=', $two)
            ->where('kpi_child_three_id', '=', $three)
            // ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        // Current offices record
        $planAccomplishmentsCurrent = PlanAccomplishment::select()
            ->where('office_id', $office->id)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', '=', $two)
            ->where('kpi_child_three_id', '=', $three)
            // ->where('reporting_period_id', '=', $period)
            ->get();

        // Current office children record if exists
        $planAccomplishmentsChildren = PlanAccomplishment::select()
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', '=', $kkp)
            ->where('plan_status', $office->level)
            ->where('kpi_child_one_id', '=', $two)
            ->where('kpi_child_three_id', '=', $three)
            // ->where('reporting_period_id', '=', $period)
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishmentsCurrent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildren as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildParent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    } elseif ($suffix == 3) {
        // All current office children if exist with the logged in user office level
        $planAccomplishments = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', array_merge($childAndHimOffKpi, array($office->id)))
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', '=', $two)
            ->where('kpi_child_three_id', '=', $three)
            // ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishments as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    }

    $sumch1ch3_value = 0;
    // $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id' , $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_three_id' , '=', $three)->get();
    $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id', $office->id)->where('kpi_id', '=', $kkp)->where('kpi_child_one_id', '=', $one)->where('kpi_child_three_id', '=', $three)->get();
    foreach ($planAccomplishments as $key => $planAccomplishment) {

        $sumch1ch3_value = $sumch1ch3_value + $planAccomplishment->plan_value;
    }
    return $sumch1ch3_value;
}
function planIndividualChOnech($kkp, $one, $two, $office, $suffix)
{
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
    $planAccomplishmentsCurrent = '';
    $planAccomplishmentsChildren = '';

    if ($suffix == 1) {

        // All current office children if exist with the logged in user office level
        $planAccomplishmentsChildParent = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', '=', $two)
            // ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        // Current offices record
        $planAccomplishmentsCurrent = PlanAccomplishment::select()
            ->where('office_id', $office->id)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', '=', $two)
            // ->where('reporting_period_id', '=', $period)
            ->get();

        // Current office children record if exists
        $planAccomplishmentsChildren = PlanAccomplishment::select()
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', '=', $kkp)
            ->where('plan_status', $office->level)
            ->where('kpi_child_one_id', '=', $two)
            // ->where('reporting_period_id', '=', $period)
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishmentsCurrent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildren as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildParent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    } elseif ($suffix == 3) {
        // All current office children if exist with the logged in user office level
        $planAccomplishments = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', array_merge($childAndHimOffKpi, array($office->id)))
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', '=', $two)
            // ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishments as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    }

    $sumch1ch3_value = 0;
    // $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id' , $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->get();
    $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id', $office->id)->where('kpi_id', '=', $kkp)->where('kpi_child_one_id', '=', $one)->get();
    foreach ($planAccomplishments as $key => $planAccomplishment) {

        $sumch1ch3_value = $sumch1ch3_value + $planAccomplishment->plan_value;
    }
    return $sumch1ch3_value;
}
function planIndividualChTwoSum($kkp, $two, $office, $period, $suffix)
{
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
    $planAccomplishmentsCurrent = '';
    $planAccomplishmentsChildren = '';

    if ($suffix == 1) {

        // All current office children if exist with the logged in user office level
        $planAccomplishmentsChildParent = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_two_id', '=', $two)
            ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        // Current offices record
        $planAccomplishmentsCurrent = PlanAccomplishment::select()
            ->where('office_id', $office->id)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_two_id', '=', $two)
            ->where('reporting_period_id', '=', $period)
            ->get();

        // Current office children record if exists
        $planAccomplishmentsChildren = PlanAccomplishment::select()
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', '=', $kkp)
            ->where('plan_status', $office->level)
            ->where('kpi_child_two_id', '=', $two)
            ->where('reporting_period_id', '=', $period)
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishmentsCurrent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildren as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildParent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    } elseif ($suffix == 3) {
        // All current office children if exist with the logged in user office level
        $planAccomplishments = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', array_merge($childAndHimOffKpi, array($office->id)))
            ->where('kpi_id', $kkp)
            ->where('kpi_child_two_id', '=', $two)
            ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishments as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    }

    $sumch1ch3_value = 0;
    // $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id' , $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_two_id' , '=', $two)->where('reporting_period_id' , '=', $period)->get();
    $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id', $office->id)->where('kpi_id', '=', $kkp)->where('kpi_child_two_id', '=', $two)->where('reporting_period_id', '=', $period)->get();
    foreach ($planAccomplishments as $key => $planAccomplishment) {

        $sumch1ch3_value = $sumch1ch3_value + $planAccomplishment->plan_value;
    }
    return $sumch1ch3_value;
}

function planIndividualChOneSum($kkp, $office)
{
    $sumch1ch3_value = 0;
    $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id', $office)->where('kpi_id', '=', $kkp)->where('kpi_child_two_id', '=', $two)->get();
    foreach ($planAccomplishments as $key => $planAccomplishment) {

        $sumch1ch3_value = $sumch1ch3_value + $planAccomplishment->plan_value;
    }
    return $sumch1ch3_value;
}

function planSumOfKpi($kkp, $office, $suffix)
{
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
    $planAccomplishmentsCurrent = '';
    $planAccomplishmentsChildren = '';

    if ($suffix == 1) {

        // All current office children if exist with the logged in user office level
        $planAccomplishmentsChildParent = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', $kkp)
            // ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        // Current offices record
        $planAccomplishmentsCurrent = PlanAccomplishment::select()
            ->where('office_id', $office->id)
            ->where('kpi_id', $kkp)
            // ->where('reporting_period_id', '=', $period)
            ->get();

        // Current office children record if exists
        $planAccomplishmentsChildren = PlanAccomplishment::select()
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', '=', $kkp)
            ->where('plan_status', $office->level)
            // ->where('reporting_period_id', '=', $period)
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishmentsCurrent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildren as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildParent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    } elseif ($suffix == 3) {
        // All current office children if exist with the logged in user office level
        $planAccomplishments = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', array_merge($childAndHimOffKpi, array($office->id)))
            ->where('kpi_id', $kkp)
            // ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishments as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    }

    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    // dd($childAndHimOffKpi_array);
    $sumch1ch3_value = 0;
    // $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id', $childAndHimOffKpi_array)->where('kpi_id' , '=', $kkp)->get();
    $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id', $office->id)->where('kpi_id', '=', $kkp)->get();
    foreach ($planAccomplishments as $key => $planAccomplishment) {
        $sumch1ch3_value = $sumch1ch3_value + $planAccomplishment->plan_value;
    } //dd($sumch1ch3_value);
    return $sumch1ch3_value;
}
function planOne($kkp, $one, $office, $period, $suffix, $planning_year)
{
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
    $planAccomplishmentsCurrent = '';
    $planAccomplishmentsChildren = '';
    $getKpi = KeyPeformanceIndicator::find($kkp);
    $sum1 = 0;
    if ($suffix == 1) {
        if($getKpi->measurement){
            if($getKpi->measurement->slug =="percent"){
                $planAccomplishments = calculateAveragePlan($kkp,$office,$period,false,$planning_year ,$one,null,null);
               // dump($planAccomplishments);
                //dump($office->officeTranslations[0]->name);
                if($planAccomplishments[1]!=0){
                    $sum1 = $planAccomplishments[0]/$planAccomplishments[1];
                }

            }
            else{
                // All current office children if exist with the logged in user office level
                    $planAccomplishmentsChildParent = PlanAccomplishment::select()
                    // ->where('office_id', $office->id)
                    ->whereIn('office_id', $childAndHimOffKpi)
                    ->where('kpi_id', $kkp)
                    ->where('kpi_child_one_id', $one)
                    ->where('reporting_period_id', '=', $period)
                    ->where(function ($q) {
                        $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
                    })
                    ->get();

                // Current offices record
                $planAccomplishmentsCurrent = PlanAccomplishment::select()
                    ->where('office_id', $office->id)
                    ->where('kpi_id', $kkp)
                    ->where('kpi_child_one_id', $one)
                    ->where('planning_year_id', '=', $planning_year->id)
                    ->where('reporting_period_id', '=', $period)
                    ->get();

                // Current office children record if exists
                $planAccomplishmentsChildren = PlanAccomplishment::select()
                    ->whereIn('office_id', $childAndHimOffKpi)
                    ->where('kpi_id', '=', $kkp)
                    ->where('kpi_child_one_id', '=', $one)
                    ->where('plan_status', $office->level)
                    ->where('reporting_period_id', '=', $period)
                    ->get();


                foreach ($planAccomplishmentsCurrent as $key => $planAccomplishment) {
                    $sum1 += $planAccomplishment->plan_value;
                }
                foreach ($planAccomplishmentsChildren as $key => $planAccomplishment) {
                    $sum1 += $planAccomplishment->plan_value;
                }
                foreach ($planAccomplishmentsChildParent as $key => $planAccomplishment) {
                    $sum1 += $planAccomplishment->plan_value;
                }
                // dd($sum1);
                return $sum1;
           }
        }else{
            echo "please set KPI measurement Type?";
        }


    } elseif ($suffix == 3) {
        // All current office children if exist with the logged in user office level
        $planAccomplishments = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', array_merge($childAndHimOffKpi, array($office->id)))
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', $one)
            ->where('planning_year_id', '=', $planning_year->id)
            ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishments as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    }

    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    $sum1 = 0;
    $planAccomplishments = PlanAccomplishment::select('plan_value')
        ->where('office_id', $office->id)
        ->where('kpi_id', '=', $kkp)
        ->where('kpi_child_one_id', '=', $one)
        ->where('planning_year_id', '=', $planning_year->id)
        ->where('reporting_period_id', '=', $period)
        ->get();
    foreach ($planAccomplishments as $key => $planAccomplishment) {
        $sum1 = $sum1 + $planAccomplishment->plan_value;
    }//dump($office);
    return $sum1;
}
function planOneTwo($kkp, $one, $two, $office, $period, $suffix)
{
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
    $planAccomplishmentsCurrent = '';
    $planAccomplishmentsChildren = '';

    if ($suffix == 1) {

        // All current office children if exist with the logged in user office level
        $planAccomplishmentsChildParent = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', $one)
            ->where('kpi_child_two_id', '=', $two)
            ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        // Current offices record
        $planAccomplishmentsCurrent = PlanAccomplishment::select()
            ->where('office_id', $office->id)
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', $one)
            ->where('kpi_child_two_id', '=', $two)
            ->where('reporting_period_id', '=', $period)
            ->get();

        // Current office children record if exists
        $planAccomplishmentsChildren = PlanAccomplishment::select()
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', '=', $kkp)
            ->where('kpi_child_one_id', '=', $one)
            ->where('kpi_child_two_id', '=', $two)
            ->where('plan_status', $office->level)
            ->where('reporting_period_id', '=', $period)
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishmentsCurrent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildren as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildParent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    } elseif ($suffix == 3) {
        // All current office children if exist with the logged in user office level
        $planAccomplishments = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', array_merge($childAndHimOffKpi, array($office->id)))
            ->where('kpi_id', $kkp)
            ->where('kpi_child_one_id', $one)
            ->where('kpi_child_two_id', '=', $two)
            ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishments as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    }

    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    $sum12 = 0;
    // $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id', $childAndHimOffKpi_array)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_two_id' , '=', $two)->where('reporting_period_id' , '=', $period)->get();//dd($planAccomplishments);
    $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id', $office->id)->where('kpi_id', '=', $kkp)->where('kpi_child_one_id', '=', $one)->where('kpi_child_two_id', '=', $two)->where('reporting_period_id', '=', $period)->get(); //dd($planAccomplishments);
    foreach ($planAccomplishments as $key => $planAccomplishment) {
        $sum12 = $sum12 + $planAccomplishment->plan_value;
    }
    return $sum12;
}
function planSum($kkp, $office, $period, $suffix, $planning_year)
{
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
    $planAccomplishmentsCurrent = '';
    $planAccomplishmentsChildren = '';
    $kpi = KeyPeformanceIndicator::find($kkp);
    if ($suffix == 1) {

        // All current office children if exist with the logged in user office level
        $planAccomplishmentsChildParent = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', $kkp)
            ->where('reporting_period_id', '=', $period)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        // Current offices record
        $planAccomplishmentsCurrent = PlanAccomplishment::select()
            ->where('office_id', $office->id)
            ->where('kpi_id', $kkp)
            ->where('reporting_period_id', '=', $period)
            ->get();

        // Current office children record if exists
        $planAccomplishmentsChildren = PlanAccomplishment::select()
            ->whereIn('office_id', $childAndHimOffKpi)
            ->where('kpi_id', '=', $kkp)
            ->where('plan_status', $office->level)
            ->where('reporting_period_id', '=', $period)
            ->get();

        $sum1 = 0;

        foreach ($planAccomplishmentsCurrent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildren as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        foreach ($planAccomplishmentsChildParent as $key => $planAccomplishment) {
            $sum1 += $planAccomplishment->plan_value;
        }
        // dd($sum1);
        return $sum1;
    } elseif ($suffix == 3) {
        // All current office children if exist with the logged in user office level
        $sum1 = 0;
        if($kpi->measurement){
            if($kpi->measurement->slug =="percent"){
                $planAccomplishments = calculateAveragePlan($kkp,$office,$period,false,$planning_year ,null,null,null);
                $sum1 = $planAccomplishments[0]/$planAccomplishments[1];
                //dump($planAccomplishments);
            }else{
                $planAccomplishments = PlanAccomplishment::select()
                // ->where('office_id', $office->id)
                ->whereIn('office_id', array_merge($childAndHimOffKpi, array($office->id)))
                ->where('kpi_id', $kkp)
                ->where('planning_year_id', '=', $planning_year)
                ->where('reporting_period_id', '=', $period)
                ->where(function ($q) {
                    $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
                })
                ->get();
                foreach ($planAccomplishments as $key => $planAccomplishment) {
                    $sum1 += $planAccomplishment->plan_value;
                }
            }
        }else{
            echo "please set KPI measurement Type?";
        }

        // dd($sum1);
        return $sum1;
    }

    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    $sum = 0;
    $planAccomplishments = PlanAccomplishment::select('plan_value')
        ->where('office_id', $office->id)->where('kpi_id', '=', $kkp)
        ->where('planning_year_id', '=', $planning_year->id)
        ->where('reporting_period_id', '=', $period)
        ->get();
    foreach ($planAccomplishments as $key => $planAccomplishment) {
        $sum = $sum + $planAccomplishment->plan_value;
    }
    return $sum;
}
function getNarration($kkp, $year, $office, $period)
{
    // get all child and subchild offices for login user
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    // $plannarations = ReportNarration::select('plan_naration')->whereIn('office_id' , $childAndHimOffKpi_array)->where('key_peformance_indicator_id' , '=', $kkp)->where('planing_year_id' , '=', $year)->get();
    $plannarations = ReportNarration::select('plan_naration')->where('office_id', $office->id)->where('key_peformance_indicator_id', '=', $kkp)->where('planing_year_id', '=', $year)->get();
    return $plannarations;
    foreach ($plannarations as $key => $plannaration) {
        return $plannaration->plan_naration;
    }
}
function getAllPeriod()
{
    return 11;
}

function getNarrationApproved($kkp, $year, $office, $period)
{
    // get all child and subchild offices for login user
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);

    // get all children that are approved and planned for the given kpi
    $childAndHimOffKpi = getOfficeChildrenApprovedList($kkp, $office, $year, 2);

    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    $plannarations = ReportNarration::select('plan_naration')->whereIn('office_id', $childAndHimOffKpi_array)->where('key_peformance_indicator_id', '=', $kkp)->where('planing_year_id', '=', $year)->get();
    return $plannarations;
    foreach ($plannarations as $key => $plannaration) {
        return $plannaration->plan_naration;
    }
}
//    function getAllPeriod(){
//        return 11;
//    }

function planStatusOffice($office, $kpi, $year)
{
    $status = PlanAccomplishment::select('plan_status')
        ->where('office_id', $office->id)
        ->where('kpi_id', $kpi)
        ->where('planning_year_id', $year)
        ->first();

    // dd($status);
    return $status->plan_status ?? '';
}

function planStatusOfficeApproved($office, $kpi, $year, $childrenApproved)
{
    $status = PlanAccomplishment::select('plan_status')
        ->whereIn('office_id', $childrenApproved)
        ->where('kpi_id', $kpi)
        ->where('planning_year_id', $year)
        ->first();

    // dd($status);
    return $status->plan_status ?? '';
}

function isCurrentOfficeApproved($kpi, $office, $year)
{
    $status = PlanAccomplishment::select('plan_status')
        ->where('office_id', $office->id)
        ->where('kpi_id', $kpi)
        ->where(function ($q) {
            $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
        })
        ->where('planning_year_id', $year)
        ->first();

    // dd($status);
    return $status ?? '';
}

function hasOfficeActiveComment($office, $kpi, $year){

    $comment = PlanComment::select()
        ->where('kpi_id', $kpi)
        ->where('office_id', $office)
        ->where('planning_year_id', $year)
        ->where('status', 1)
        ->get();

    // dd($comment);
    return $comment;
}

function getPlanCommentorInfo($office, $kpi, $year){
    $info = PlanComment::select()
        ->where('kpi_id', $kpi)
        ->where('office_id', $office)
        ->where('planning_year_id', $year)
        ->first();

    $officeName = $info ? OfficeTranslation::where('translation_id', $info->commented_by)->first() : null;

    return $officeName ?? '-';
}

function commentorTextStatus($office, $commentorId, $kpi, $year, $suffix){

    // dd($office->id);

    $status = "";
    if($suffix == 1){
        $status = PlanComment::select()
            ->where('kpi_id', $kpi)
            ->where('commented_by', $commentorId)
            ->where('office_id', $office->id)
            ->where('planning_year_id', $year)
            ->where('replied_active', 1)
            ->get();
    }elseif($suffix == 2){
        $status = PlanComment::select()
            ->where('kpi_id', $kpi)
            ->where('commented_by', $commentorId)
            ->where('office_id', $office->id)
            ->where('planning_year_id', $year)
            ->where('status', 1)
            ->get();
    }else{
        $status = PlanComment::select()
            ->where('kpi_id', $kpi)
            ->where('commented_by', $commentorId)
            ->where('office_id', $office->id)
            ->where('planning_year_id', $year)
            ->first();
    }

    // dd($status);
    return $status ?? '';
}
// get basline  with parametres for only one office
function OnlyKpiOttBaseline($kpi_id,$office, $planning_year_id, $period, $one, $two, $three){
    $plan_accom = [];
    $getkpi = KeyPeformanceIndicator::find($kpi_id);
    $status = getStatus($kpi_id,$office,$period,false,$planning_year_id ,null,null,null);


   //dd($kpi_id);
   $childAndHimOffKpi_array =[];
   $childAndHimOffKpi = office_all_childs_ids($office);
   $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
   $office_baseline = 0;
   $office_level = $office->level;
   if($office_level == 0) $office_level=1;
   $planBaseline = Baseline::select()
    ->where('office_id','=', $office->id)
    //->where('office_id', $off_list)
    ->where('kpi_id', $kpi_id)
    ->where('planning_year_id', '=', $planning_year_id)
    ->where('kpi_one_id',  '=', $one)
    ->where('kpi_two_id',  '=', $two)
    ->where('kpi_three_id',  '=', $three)
    ->get();
    if($planBaseline->isEmpty()){
        $planning_year = PlaningYear::where('is_active',true)->first();
        $previous_year = PlaningYear::where('id', '<', $planning_year_id)->orderby('id', 'desc')->first();
        if($previous_year){
            $planBaseline = Baseline::select()
            ->where('office_id','=', $office->id)
             ->where('kpi_id', $kpi_id)
            ->where('planning_year_id', '=', $previous_year->id)
            ->where('kpi_one_id',  '=', $one)
            ->where('kpi_two_id',  '=', $two)
            ->where('kpi_three_id',  '=', $three)
            ->first();
        } //dump($kpi_id);

    }
    foreach ($planBaseline as $key => $value2) {
        $office_baseline = $office_baseline+$value2->baseline;
    }
   return $office_baseline;
}

function planBaseline($kpi_id,$office, $planning_year_id, $period,$one,$two,$three)
{
    $childAndHimOffKpi_array = [];
    $childAndHimOffKpi = office_all_childs_ids($office);
     $all_office_list = array_merge( $childAndHimOffKpi,array($office->id));
    $planAccomplishmentsCurrent = '';
    $office_baseline = 0;
    $kpi = KeyPeformanceIndicator::find($kpi_id);
    //echo "kpi= ".$kpi_id." and office =".$office->id."<br/>"; dump($kpi->measurement);
    // get baseline for kpi

    $office_level = $office->level;
    if($office_level == 0) $office_level=1;
    if($kpi->measurement){
        if($kpi->measurement->slug =="percent"){
            $planBaseline = calculateAverageBaseline($kpi_id,$office,$period,false,$planning_year_id ,$one,$two,$three);
            if($planBaseline[0]!=0){
                $office_baseline = $planBaseline[0]/$planBaseline[1];
            }else{ $office_baseline =0; }

        }else{
            $planBaseline = Baseline::select()
            //->whereIn('office_id', $all_office_list)
            ->whereIn('office_id', $all_office_list)
            ->where('kpi_id', $kpi_id)
            ->where('planning_year_id', '=', $planning_year_id)
            ->where('kpi_one_id', '=', $one)
            ->where('kpi_two_id', '=', $two)
            ->where('kpi_three_id', '=', $three)
            ->where('plan_status' , '<=', $office_level)
            ->get();

            if(!$planBaseline){
                $planning_year = PlaningYear::where('is_active',true)->first();
                $previous_year = PlaningYear::where('id', '<', $planning_year_id)->orderby('id', 'desc')->first();
                if($previous_year){
                    $planBaseline = Baseline::select('baseline', 'office_id')
                    ->whereIn('office_id', $all_office_list)
                    ->where('kpi_id', $kpi_id)
                    ->where('planning_year_id', $previous_year->id)
                    ->where('kpi_one_id', $one)
                    ->where('kpi_two_id', $two)
                    ->where('kpi_three_id', $three)
                    ->where('plan_status' , '<=', $office_level)
                    ->get();
                }
            }

            $office_baseline = $planBaseline->sum('baseline');
        }

            // foreach ($all_office_list as $key => $off_list) {

            //     $planBaseline = Baseline::select()
            //     //->whereIn('office_id', $all_office_list)
            //     ->where('office_id', $off_list)
            //     ->where('kpi_id', $kpi_id)
            //     ->where('planning_year_id', '=', $planning_year_id)
            //     ->where('kpi_one_id', '=', $one)
            //     ->where('kpi_two_id', '=', $two)
            //     ->where('kpi_three_id', '=', $three)
            //     ->get();
            //     // $planBaseline = DB::select("
            //     //     SELECT baseline  FROM baselines
            //     //     WHERE office_id = ?
            //     //     AND kpi_id = ?
            //     //     AND planning_year_id = ?
            //     //     AND kpi_one_id = ?
            //     //     AND kpi_two_id = ?
            //     //     AND kpi_three_id = ?
            //     // ", [
            //     //     $off_list,$kpi_id,$planning_year_id,$one,$two,$three
            //     // ]);
            //     if(!$planBaseline){
            //         $planning_year = PlaningYear::where('is_active',true)->first();
            //         $previous_year = PlaningYear::where('id', '<', $planning_year_id)->orderby('id', 'desc')->first();
            //         if($previous_year){
            //             $planBaseline = DB::select("
            //             SELECT * FROM baseline
            //             WHERE office_id = ?
            //             AND kpi_id = ?
            //             AND planning_year_id = ?
            //             AND kpi_one_id = ?
            //             AND kpi_two_id = ?
            //             AND kpi_three_id = ?
            //         ", [
            //             $off_list,$kpi_id,$previous_year,$one,$two,$three
            //         ]);
            //         }

            //     }
            //     foreach ($planBaseline as $key => $value) {
            //         $office_baseline = $office_baseline+$value->baseline;
            //      }
            // }

    }else{
        echo "kpi should has measurement";
    }

    return $office_baseline;
}

function planBaselineSelf($kpi_id, $office, $planning_year_id, $one, $two, $three){
    // 1. Query for current year's baseline
        $planBaseline = DB::selectOne("
        SELECT *  FROM baselines
        WHERE office_id = ?
        AND kpi_id = ?
        AND planning_year_id = ?
        AND kpi_one_id = ?
        AND kpi_two_id = ?
        AND kpi_three_id = ?
        LIMIT 1
        ", [
        $office->id,  $kpi_id, $planning_year_id,  $one, $two,  $three
        ]);

        if (!$planBaseline) {
        // 2. Query for active planning year
        $planning_year = DB::selectOne("
            SELECT *   FROM planning_years  WHERE is_active = true  LIMIT 1
        ");

        // 3. Query for the previous planning year
        $previous_year = DB::selectOne("
            SELECT *
            FROM planning_years
            WHERE id < ?
            ORDER BY id DESC
            LIMIT 1
        ", [
            $planning_year_id
        ]);

        if ($previous_year) {
            // 4. Query for previous year's baseline
            $planBaseline = DB::selectOne("
                SELECT *
                FROM baselines
                WHERE office_id = ?
                AND kpi_id = ?
                AND planning_year_id = ?
                AND kpi_one_id = ?
                AND kpi_two_id = ?
                AND kpi_three_id = ?
                LIMIT 1
            ", [
                $office->id,
                $kpi_id,
                $previous_year->id,
                $one,
                $two,
                $three
            ]);
        }
        }
    return $planBaseline->baseline;
}



// below function is used for the Update functionality of office CRUD, it's out of this helper file purpose
function updateLevels($officeTranslation, $changedLevel) {
    if ($officeTranslation->offices->count() > 0) {
        foreach ($officeTranslation->offices as $childOne) {
            $childOne->update([
                'level' => $changedLevel + 1
            ]);
            updateLevels($childOne, $changedLevel + 1);
        }
    }

    return 1;
}
