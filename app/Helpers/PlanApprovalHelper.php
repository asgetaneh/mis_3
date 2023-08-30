<?php

use App\Models\Office;
use App\Models\ReportingPeriod;
use App\Models\ReportNarration;
use App\Models\PlanAccomplishment;
use App\Models\KeyPeformanceIndicator;
use App\Models\OfficeTranslation;
use App\Models\PlanComment;

/**
 * Write code on Method
 *
 * @return response()
 */

function isOfficeBelongToKpi($office, $kpi)
{

    $allOffices = office_all_childs_ids($office);
    $allOffices = array_merge($allOffices, array($office->id));

    $data = KeyPeformanceIndicator::select('key_peformance_indicators.*')
        ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
        ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
        ->where('kpi_office.kpi_id', '=', $kpi)
        ->whereIn('kpi_office.office_id', $allOffices)
        ->get();

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

    if ($suffix == 1) {
        $childAndHimOffKpi = office_all_childs_ids($office);

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

        $childAndHimOffKpi = office_all_childs_ids($office);
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
        $childAndHimOffKpi = office_all_childs_ids($office);
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
    $offices = Office::select('offices.*')
        ->whereIn('id', $only_child_array)
        //               ->where('kpi_id' , '=', $kpi->id)
        ->get();

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
    $childAndHimOffKpi = office_all_childs_ids($office);
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
    $childAndHimOffKpi = office_all_childs_ids($office);
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
function planOne($kkp, $one, $office, $period, $suffix)
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
    $childAndHimOffKpi = office_all_childs_ids($office);
    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    $sum1 = 0;
    $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id', $office->id)->where('kpi_id', '=', $kkp)->where('kpi_child_one_id', '=', $one)->where('reporting_period_id', '=', $period)->get();
    foreach ($planAccomplishments as $key => $planAccomplishment) {
        $sum1 = $sum1 + $planAccomplishment->plan_value;
    }
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
    $childAndHimOffKpi = office_all_childs_ids($office);
    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    $sum12 = 0;
    // $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id', $childAndHimOffKpi_array)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_two_id' , '=', $two)->where('reporting_period_id' , '=', $period)->get();//dd($planAccomplishments);
    $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id', $office->id)->where('kpi_id', '=', $kkp)->where('kpi_child_one_id', '=', $one)->where('kpi_child_two_id', '=', $two)->where('reporting_period_id', '=', $period)->get(); //dd($planAccomplishments);
    foreach ($planAccomplishments as $key => $planAccomplishment) {
        $sum12 = $sum12 + $planAccomplishment->plan_value;
    }
    return $sum12;
}
function planSum($kkp, $office, $period, $suffix)
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
        $planAccomplishments = PlanAccomplishment::select()
            // ->where('office_id', $office->id)
            ->whereIn('office_id', array_merge($childAndHimOffKpi, array($office->id)))
            ->where('kpi_id', $kkp)
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
    $childAndHimOffKpi = office_all_childs_ids($office);
    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    $sum = 0;
    $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id', $office->id)->where('kpi_id', '=', $kkp)->where('reporting_period_id', '=', $period)->get();
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
