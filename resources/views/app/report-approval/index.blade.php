@extends('layouts.app')
@section('title', 'Report Approval')

@section('style')
    <style>
        tr,
        th,
        td {
            padding: 10px;
        }

        table {
            border-collapse: collapse;
        }

        #view-comment-paragraph * {
            margin-bottom: 0 !important;
        }
        #view-reply-tag {
            text-align: center;
            font-size: 18px;
            animation: animate
                2s linear infinite;
            }

            @keyframes animate {
                0% {
                    opacity: 0;
                }

                50% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                }
            }
    </style>
@endsection

@section('content')

        {{-- <div class="row justify-content-center">
            <div class="col-12">
                <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                    <div class="card-body">
                        <form role="form" class="form-horizontal" method="get"
                            action="{{ route('plan-approve.index') }}">

                            <div class="row">
                                <div class="col-md-5">
                                    <label class="label" for="filters">Office:</label>
                                    <select class="form-control select2" name="office">
                                        <option disabled selected value="">Select Office</option>
                                        @if (auth()->user()->is_admin === true)
                                            @forelse(getAllOffices() as $office)
                                                <option value="{{ $office->id }}">{{ $office->officeTranslations[0]->name }}
                                                </option>
                                            @empty
                                            @endforelse
                                        @else
                                            @forelse(auth()->user()->offices[0]->offices as $office)
                                                <option value="{{ $office->id }}">{{ $office->officeTranslations[0]->name }}
                                                </option>
                                            @empty
                                            @endforelse
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class=" " for="filters">KPI:</label>
                                    <select class="form-control select2" name="kpi">
                                        <option disabled selected value="">Select KPI</option>
                                        @forelse(getAllKpi() as $kpi)
                                            <option value="{{ $kpi->id }}">{{ $kpi->keyPeformanceIndicatorTs[0]->name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>

                                </div>
                                <div class="col-md-1"><br />
                                    <button class="btn btn-flat btn-info" value="search" name="search" type="submit">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        @php
                            $kpi_repeat[0] = '0';
                            $c = 1;
                            $first = 1;
                            $kpiList = [];
                            $objective_array = [];
                        @endphp

                        @php
                            $ownReportVisible = false;
                        @endphp


                        {{-- @dd($planAccomplishmentsLastOffice) --}}

                        {{-- Own Plan of last office if exists --}}
                        @if (count($planAccomplishmentsLastOffice) > 0)
                            <div class="p-3 bg-light mb-3 rounded shadow-sm own-report-div" style="border: 1px solid #d3d2d2;">
                                <h5 class=""><u>Own Report Approval</u></h5>
                                @forelse ($planAccomplishmentsLastOffice as $planAcc)
                                    @php
                                        $period = getQuarter($planAcc->Kpi->reportingPeriodType->id);
                                        $isOfficeBelongToKpi = isLastOfficeBelongToKpi(auth()->user()->offices[0], $planAcc->Kpi->id);
                                        array_push($kpiList, $planAcc->kpi_id);
                                    @endphp
                                    {{-- @dd($planAcc) --}}
                                    @if (!in_array($planAcc->kpi_id, $allKpisListChildren))
                                        @if ($isOfficeBelongToKpi->count() > 0)
                                            {{-- @if (!in_array($planAcc->Kpi->id, $kpi_repeat)) --}}
                                            @php
                                                $ownReportVisible = true;
                                            @endphp
                                            <div class="card collapsed-card" style="border: 1px solid #d3d2d2;">
                                                <div class="card-header p-0">
                                                    @forelse($planAcc->Kpi->KeyPeformanceIndicatorTs as $kpiT)
                                                        @if (app()->getLocale() == $kpiT->locale)
                                                            <table class="table m-0 p-0">
                                                                <tr style="background-color: #e8f1ff;" class="border">
                                                                    {{-- @if (!in_array($planAcc->Kpi->objective->id, $objective_array)) --}}
                                                                        @forelse ($planAcc->Kpi->objective->objectiveTranslations as $objective)
                                                                            @if (app()->getLocale() == $objective->locale)
                                                                                <th colspan="2" style="width:100%;"> Objective:
                                                                                    {{ $objective->name }}
                                                                                </th>
                                                                                <th></th>
                                                                            @endif
                                                                        @empty
                                                                            <th>No objective name!</th>
                                                                            <th></th>
                                                                        @endforelse
                                                                    {{-- @endif --}}
                                                                </tr>
                                                                <tr class="bg-light border">
                                                                    <th style="width:75%;"> KPI: {{ $kpiT->name }}</th>
                                                                    <th style="width: 25%;" class="bg-light border">
                                                                        <p class="m-auto py-2 px-1">Total:
                                                                            <u>{{ $planAcc->sum }}</u></p>
                                                                    </th>
                                                                    <th class="border">
                                                                        <button type="button"
                                                                            class="btn btn-flat btn-tool bg-primary m-auto py-2 px-4"
                                                                            data-card-widget="collapse"><i
                                                                                class="fas fa-plus"></i>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        @endif
                                                    @empty
                                                        <h4>No KPI name!</h4>
                                                    @endforelse
                                                </div>

                                                <div class="card-body" style="display: none;">
                                                    <form method="POST" action="{{ route('report-approve') }}"
                                                        class="approve-form" id="approve-form">
                                                        @csrf

                                                        @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                        @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                            @include('app.report-approval.last-office.kpi123')
                                                                            {{-- KPI has  child one and child two --}}
                                                                        @else
                                                                            @include('app.report-approval.last-office.kpi12')
                                                                        @endif
                                                                        {{-- KPI has  child one only --}}
                                                                    @else
                                                                        @include('app.report-approval.last-office.kpi1')
                                                                    @endif

                                                                </thead>
                                                            </table>
                                                            {{-- KPI has no child one, which means just only plain input --}}
                                                        @else
                                                            @include('app.report-approval.last-office.kpi')
                                                        @endif
                                                        <tr>
                                                            <td colspan="8">
                                                                <button onclick="return confirm('Are you sure?')"
                                                                    type="submit" class="btn btn-primary float-right"
                                                                    id="approve-for-self-{{ $planAcc->kpi_id }}"><i
                                                                        class="fa fa-check nav-icon"></i> Approve
                                                                    Self</button>
                                                            </td>
                                                        </tr>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @empty
                                    <p>No KPI!</p>
                                @endforelse

                            </div>
                        @endif


                        @php
                            $unapprovedOfficeCount = 0;
                            $kpiListForApproval = [];
                        @endphp

                        @forelse($planAccomplishments as $planAcc)
                            @php

                                $offices = getOfficeFromKpiAndOfficeList($planAcc->Kpi, $only_child_array);
                                $period = getQuarter($planAcc->Kpi->reportingPeriodType->id);
                                $isOfficeBelongToKpi = getKpiImmediateChilds($only_child_array);
                                array_push($kpiList, $planAcc->kpi_id);
                            @endphp

                            @if (!in_array($planAcc->Kpi->id, $kpi_repeat))
                                <div class="card collapsed-card" style="border: 1px solid #d3d2d2;">
                                    <div class="card-header p-0">

                                        @forelse($planAcc->Kpi->KeyPeformanceIndicatorTs as $kpiT)
                                            @if (app()->getLocale() == $kpiT->locale)

                                                @php
                                                    array_push($kpiListForApproval, $kpiT->translation_id);
                                                @endphp

                                                <table class="table p-0 m-0">
                                                    {{-- <tr style="background-color: #e8f1ff;" class="border">
                                                            @forelse ($planAcc->Kpi->objective->objectiveTranslations as $objective)
                                                                @if (app()->getLocale() == $objective->locale)
                                                                    <th colspan="2" style="width:100%;"> Objective:
                                                                        {{ $objective->name }}
                                                                    </th>
                                                                    <th></th>
                                                                @endif
                                                            @empty
                                                                <th>No objective name!</th>
                                                                <th></th>
                                                            @endforelse
                                                    </tr> --}}

                                                    {{-- ---------- --}}
                                                    <tr style="background-color: #e8f1ff;" class="border">
                                                        @if (!in_array($planAcc->Kpi->objective->id, $objective_array))
                                                             <th colspan="2" style="width:100%;"> Objective:
                                                                {{ $planAcc->Kpi->objective->objectiveTranslations[0]->name }}
                                                            </th>
                                                            <th></th>
                                                         @endif
                                                        @php
                                                            $objective_array = array_merge($objective_array, [$planAcc->Kpi->objective->id]);
                                                        @endphp
                                                    </tr>

                                                    <tr style="" class="border">
                                                        <th style="width:75%;" class="">
                                                            <p class="m-auto py-2 px-1">KPI: {{ $kpiT->name }}
                                                                <span style="font-size: 16px !important;" class="badge bg-info ml-2 px-2 py-1" id="{{ $kpiT->translation_id }}-unapproved"></span>
                                                            </p>
                                                        </th>
                                                        <th style="width: 25%;" class="bg-light border">
                                                            <p class="m-auto py-2 px-1">Total : <u>
                                                                @php
                                                                $avarage =0;
                                                                $avarage_total =0;
                                                                $denominator = 1;
                                                                $getPeriod = getQuarterWithRTypeAndSlug($planAcc->Kpi->reportingPeriodType,1);
                                                                $active_period = getReportingQuarter($planAcc->Kpi->reportingPeriodType->id);
                                                                if($planAcc->Kpi->measurement){
                                                                    if($planAcc->Kpi->measurement->slug=='percent'){
                                                                        if(!$planAcc->kpi_child_three_id == null){
                                                                            foreach($planAcc->Kpi->kpiChildThrees as $key=>$value3){
                                                                                foreach($planAcc->Kpi->kpiChildTwos as $key=>$value2){
                                                                                    foreach($planAcc->Kpi->kpiChildOnes as $key=>$value1){
                                                                                        $avarage = $planAcc->KpiOTT($planAcc->Kpi->id, auth()->user()->offices[0], $active_period[0]->id, true, $planning_year->id, $value1->id,$value2->id,$value3->id);
                                                                                        $avarage_total = $avarage_total+$avarage[0];
                                                                                    }
                                                                                }
                                                                            }
                                                                            $denominator = $planAcc->Kpi->kpiChildOnes->count()*$planAcc->Kpi->kpiChildTwos->count()*$planAcc->Kpi->kpiChildThrees->count();
                                                                        }else if(!$planAcc->kpi_child_two_id == null){
                                                                            foreach($planAcc->Kpi->kpiChildTwos as $key=>$value2){
                                                                                foreach($planAcc->Kpi->kpiChildOnes as $key=>$value1){
                                                                                    $avarage = $planAcc->KpiOTT($planAcc->Kpi->id, auth()->user()->offices[0], $active_period[0]->id, true, $planning_year->id,  $value1->id,$value2->id,null);
                                                                                    $avarage_total = $avarage_total+$avarage[0];
                                                                                }
                                                                            }
                                                                            $denominator = $planAcc->Kpi->kpiChildOnes->count()*$planAcc->Kpi->kpiChildTwos->count();
                                                                        }else if(!$planAcc->kpi_child_one_id == null){
                                                                            foreach($planAcc->Kpi->kpiChildOnes as $key=>$value1){
                                                                                $avarage = $planAcc->KpiOTT($planAcc->Kpi->id, auth()->user()->offices[0], $active_period[0]->id, true, $planning_year->id,  $value1->id,null,null);
                                                                                $avarage_total = $avarage_total+$avarage[0];
                                                                            }
                                                                            $denominator = $planAcc->Kpi->kpiChildOnes->count();
                                                                        }else{
                                                                            $avarage = $planAcc->KpiOTT($planAcc->Kpi->id, auth()->user()->offices[0], $active_period[0]->id, true, $planning_year->id,  null,null,null);
                                                                            $avarage_total = $avarage_total+$avarage[0];
                                                                             $denominator = 1;
                                                                        }
                                                                    }
                                                                }
                                                                @endphp
                                                                @if ($avarage_total >0 && $denominator >0)
                                                                    {{ $avarage_total/$denominator }} {{" %"}}
                                                                @else
                                                                 {{ $planAcc->sum }}
                                                                @endif
                                                            </u>
                                                            </p>
                                                        </th>
                                                        <th class="">
                                                            <button type="button"
                                                                class="btn btn-flat btn-tool bg-primary m-auto py-2 px-4"
                                                                data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </table>
                                            @endif
                                        @empty
                                            <h4>No KPI name!</h4>
                                        @endforelse

                                    </div>
                                    <div class="card-body approval-container" style="display: none;">
                                        @if (hasOfficeActiveReportComment(auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year->id ?? NULL)->count() > 0)
                                            <div class="bg-light w-5 float-right p-3">
                                                <p class="m-auto">You have comment from
                                                    <u>{{ getReportCommentorInfo(auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year->id ?? NULL)->name ?? '-' }}</u>
                                                    <a class="btn btn-sm btn-flat btn-info text-white view-comment"
                                                        data-toggle="modal" data-target="#view-comment-modal"
                                                        data-id="{{ getReportCommentorInfo(auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year->id ?? NULL)->translation_id ?? '-' }}-{{ $planAcc->Kpi->id }}-{{ $planning_year->id ?? NULL }}">
                                                        <i class="fas fa fa-eye mr-1"></i>View/Reply
                                                    </a>
                                                    <a data-toggle="modal" data-target="#disapprove-modal"
                                                        data-id="{{ auth()->user()->offices[0]->id }}-{{ $planAcc->Kpi->id }}-{{ $planning_year->id ?? NULL }}"
                                                        class="btn btn-danger btn-sm btn-flat disapprove-plan"
                                                        id="disapprove-for-{{ $planAcc->Kpi->id }}">
                                                        Disapprove
                                                    </a>
                                                </p>

                                            </div>
                                        @endif
                                        {{-- If KPI has Child ones (UG, PG) --}}
                                        <form method="POST" action="{{ route('report-approve') }}" class="approve-form"
                                            id="approve-form">
                                            @csrf
                                            <div class="d-flex justify-content-end align-center">
                                                <div class="icheck-success float-right bg-light border p-3"
                                                    id="checkAll-div{{ $planAcc->kpi_id }}">
                                                    <input class="checkAllOffices" name="checkAll" type="checkbox"
                                                        id="checkAll{{ $planAcc->kpi_id }}" value="{{ $planAcc->kpi_id }}">
                                                    <label for="checkAll{{ $planAcc->kpi_id }}">
                                                        Select All
                                                    </label>
                                                </div>
                                            </div>
                                            @forelse($offices  as $office)
                                                {{-- @dd($offices) --}}
                                                @if ($office->level === 1)
                                                    @php
                                                        $setter = true;
                                                        $isLastOfficeBelongToKpi = isLastOfficeBelongToKpi($office, $planAcc->Kpi->id);
                                                        $hasOfficePlan = getOfficeReportRecord($planAcc->kpi_id, $office, $planAcc->planning_year_id);
                                                    @endphp

                                                    @if ($hasOfficePlan)

                                                        @if(reportStatusOffice(auth()->user()->offices[0], $planAcc->kpi_id, $planning_year->id ?? NULL) !== auth()->user()->offices[0]->level)
                                                                @if (isset($setter))
                                                                    @if (isset($unapprovedOfficeCount))
                                                                        @php
                                                                            $unapprovedOfficeCount++;
                                                                        @endphp
                                                                    @endif
                                                                @endif
                                                        @endif

                                                        <div class="border border-secondary my-3">
                                                            @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                                <table class="table table-bordered mb-0">
                                                                    <thead>
                                                                        @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                            @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                                @include('app.report-approval.last-office.kpi123')
                                                                                {{-- KPI has  child one and child two --}}
                                                                            @else
                                                                                @include('app.report-approval.last-office.kpi12')
                                                                            @endif
                                                                        {{-- KPI has  child one only --}}
                                                                        @else
                                                                            @include('app.report-approval.last-office.kpi1')
                                                                        @endif

                                                                    </thead>
                                                                </table>

                                                                {{-- KPI has no child one, which means just only plain input --}}
                                                            @else
                                                                @include('app.report-approval.last-office.kpi')
                                                            @endif
                                                        </div>
                                                    @else
                                                        @if ($isLastOfficeBelongToKpi->count() > 0)
                                                            <p class="mark p-3">You have not reported for this KPI yet!</p>
                                                        @else
                                                            {{-- Last office not belong to KPI. --}}
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($office->level !== auth()->user()->offices[0]->level)
                                                        {{-- get all children of current office so that its parent can see the sum of the plan for the current kpi plan --}}
                                                        @php
                                                            $hasChildrenOfficesPlannedAndApproved = getOfficeChildrenReportApprovedList($planAcc->kpi_id, $office, $planAcc->planning_year_id, 1);
                                                        @endphp

                                                        {{-- if the office has children that have a plan for the current kpi and are also approved by him,
                                                        so that add their sum with their parent and display to the leader --}}
                                                        {{-- @dd($hasChildrenOfficesPlannedAndApproved) --}}
                                                        @if (count($hasChildrenOfficesPlannedAndApproved) > 0)
                                                            @php
                                                                $unapprovedOfficeCount++;
                                                            @endphp

                                                            @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                            @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                                @include('app.report-approval.kpi-unapproved.kpi123')
                                                                                {{-- KPI has  child one and child two --}}
                                                                            @else
                                                                                @include('app.report-approval.kpi-unapproved.kpi12')
                                                                            @endif
                                                                            {{-- KPI has  child one only --}}
                                                                        @else
                                                                            @include('app.report-approval.kpi-unapproved.kpi1')
                                                                        @endif

                                                                    </thead>
                                                                </table>
                                                                {{-- KPI has no child one, which means just only plain input --}}
                                                            @else
                                                                @include('app.report-approval.kpi-unapproved.kpi')
                                                            @endif
                                                        @else
                                                            @php
                                                                $anyChildrenOfficePlannedAndApproved = getOfficeChildrenReportApprovedList($planAcc->kpi_id, $office, $planAcc->planning_year_id, 2);
                                                            @endphp
                                                            {{-- @dd($anyChildrenOfficePlannedAndApproved) --}}

                                                            {{-- Including current office and its children, are they approved and they are up to their grandfather --}}
                                                            @if (count($anyChildrenOfficePlannedAndApproved) > 0)
                                                                @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                                @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                                    @include('app.report-approval.kpi-approved.kpi123-with-approved')
                                                                                    {{-- KPI has  child one and child two --}}
                                                                                @else
                                                                                    @include('app.report-approval.kpi-approved.kpi12-with-approved')
                                                                                @endif
                                                                                {{-- KPI has  child one only --}}
                                                                            @else
                                                                                @include('app.report-approval.kpi-approved.kpi1-with-approved')
                                                                            @endif

                                                                        </thead>
                                                                    </table>
                                                                    {{-- KPI has no child one, which means just only plain input --}}
                                                                @else
                                                                    @include('app.report-approval.kpi-approved.kpi-with-approved')
                                                                @endif
                                                            @else
                                                                {{-- check if direct child has plan --}}
                                                                @php
                                                                    $hasOfficePlan = getOfficeReportRecord($planAcc->kpi_id, $office, $planAcc->planning_year_id);
                                                                @endphp
                                                                {{-- @dd($hasOfficePlan) --}}

                                                                @if ($hasOfficePlan)

                                                                    @if(reportStatusOffice($office, $planAcc->kpi_id, $planning_year->id ?? NULL) > auth()->user()->offices[0]->level)
                                                                        @php
                                                                            $unapprovedOfficeCount++;
                                                                        @endphp

                                                                    @endif

                                                                    @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                                    @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                                        @include('app.report-approval.office-plan-only.office-plan-only123')
                                                                                        {{-- KPI has  child one and child two --}}
                                                                                    @else
                                                                                        @include('app.report-approval.office-plan-only.office-plan-only12')
                                                                                    @endif
                                                                                    {{-- KPI has  child one only --}}
                                                                                @else
                                                                                    @include('app.report-approval.office-plan-only.office-plan-only1')
                                                                                @endif

                                                                            </thead>
                                                                        </table>
                                                                        {{-- KPI has no child one, which means just only plain input --}}
                                                                    @else
                                                                        @include('app.report-approval.office-plan-only.office-plan-only')
                                                                    @endif
                                                                @else
                                                                    @php
                                                                        $isOfficeBelongToKpi = isOfficeBelongToKpi($office, $planAcc->Kpi->id);
                                                                    @endphp

                                                                    @if ($isOfficeBelongToKpi->count() > 0)
                                                                        @if (count(office_all_childs_ids($office)) > 0)
                                                                            {{-- <p>Office
                                                                                <u>{{ $office->officeTranslations[0]->name }}</u>
                                                                                has
                                                                                no report or not approved its offices yet!
                                                                            </p> --}}
                                                                            {{-- <div class="alert alert-warning alert-dismissible">
                                                                                <h5><i class="icon fas fa-info-circle"></i> Office
                                                                                    <u>{{ $office->officeTranslations[0]->name }}</u>
                                                                                    has
                                                                                    no report or not approved its offices yet!</h5>
                                                                            </div> --}}

                                                                            <div class="callout callout-warning">
                                                                                <i class="icon fas fa-info-circle mr-2"></i>
                                                                                Office
                                                                                <u>{{ $office->officeTranslations[0]->name }}</u>
                                                                                has
                                                                                no report or not approved its offices yet!
                                                                            </div>
                                                                        @else
                                                                            {{-- <p>Office
                                                                                <u>{{ $office->officeTranslations[0]->name }}</u>
                                                                                has
                                                                                not reported for this KPI yet!
                                                                            </p> --}}
                                                                            {{-- <div class="alert alert-warning alert-dismissible">
                                                                                <h5><i class="icon fas fa-info-circle"></i> Office
                                                                                    <u>{{ $office->officeTranslations[0]->name }}</u>
                                                                                    has
                                                                                    not reported for this KPI yet!</h5>
                                                                            </div> --}}

                                                                            <div class="callout callout-warning">
                                                                                <i class="icon fas fa-info-circle mr-2"></i>
                                                                                Office
                                                                                <u>{{ $office->officeTranslations[0]->name }}</u>
                                                                                has
                                                                                not reported for this KPI yet!
                                                                            </div>
                                                                        @endif
                                                                    @else
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                    @endif
                                                @endif

                                                @if ($unapprovedOfficeCount > 0)
                                                    <p class="value-for-{{ $kpiT->translation_id }}"></p>
                                                    @php
                                                        $unapprovedOfficeCount = 0;
                                                    @endphp
                                                @endif

                                            @empty
                                                <h4>No offices!</h4>
                                            @endforelse

                                            @php
                                                $officeNameList = [];
                                            @endphp
                                            @forelse ($offices as $key => $office)
                                                @php
                                                    // array_push($officeNameList, $office->officeTranslations[0]->name);
                                                    $officeNameList[$office->id] = $office->officeTranslations[0]->name;
                                                @endphp
                                            @empty
                                            @endforelse

                                            @if (auth()->user()->offices[0]->level === 1)
                                                @php
                                                    $hasOfficePlan = getOfficeReportRecord($planAcc->kpi_id, auth()->user()->offices[0], $planAcc->planning_year_id);
                                                @endphp
                                                @if ($hasOfficePlan)
                                                    @php
                                                        // array_push($officeNameList, $office->officeTranslations[0]->name);
                                                        $officeNameList[auth()->user()->offices[0]->id] = auth()->user()->offices[0]->officeTranslations[0]->name;
                                                    @endphp
                                                @endif
                                            @endif

                                            {{-- @dd($officeNameList) --}}
                                            <tr>
                                                <td colspan="8">
                                                    <button type="submit" class="btn btn-primary float-right"
                                                        id="approve-for-{{ $planAcc->kpi_id }}"
                                                        onclick="return checkSelectedOffices({{ $planAcc->kpi_id }}, {{ json_encode($officeNameList) }})"><i
                                                            class="fa fa-check nav-icon"></i> Approve</button>
                                                </td>
                                            </tr>
                                        </form>
                                    </div>
                                </div>

                                @php
                                    $kpi_repeat[$c] = $planAcc->Kpi->id;
                                    $c++;

                                @endphp
                            @endif


                            @php
                                $unapprovedOfficeCount = 0;
                            @endphp

                        @empty
                            <div class="callout callout-warning">
                                <h5>No Record!</h5>

                                <p>Either KPI is not assigned or no office has reported yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Modal for comment --}}
    <div class="modal fade comment-modal" id="modal-lg" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title">Write Comment for Office: <u id="office-name"></u></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('report-comment.store') }}" method="POST" id="comment-form">
                    @csrf
                    <input type="hidden" id="hidden-input" class="hidden-input" value=""
                        name="commented-office-info">
                    <div class="modal-body">
                        <h5 class="modal-kpi">KPI: <u></u></h5>
                        <br>
                        {{-- content to be filled after ajax request here --}}
                        <textarea class="form-control summernote" name="report_comment" id="" cols="30" rows="10"
                            placeholder="Enter your comment"></textarea>
                        <p class="text-danger comment-error" style="display: none;">Please fill the form!</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- Modal for View Comment --}}
    <div class="modal fade view-comment-modal" id="view-comment-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title commented-from">Commented from: <u id="office-name"></u></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('replyreport-comment.store') }}" method="POST" id="comment-form">
                    @csrf
                    <input type="hidden" id="hidden-input-view-comment" class="hidden-input-view-comment"
                        value="" name="view-commented-office-info">
                    <div class="modal-body">
                        <h5 class="view-commented-by bg-light border p-3 overflow-auto">Comment: <p class="mw-75"></p>
                        </h5>
                        <br>
                        {{-- content to be filled after ajax request here --}}
                        <textarea class="form-control summernote" name="reply_comment" id="" cols="30" rows="10"
                            placeholder="Enter your comment"></textarea>
                        <p class="text-danger comment-error" style="display: none;">Please fill the form!</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Reply</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- Modal for Disapprove confirmation --}}
    <div class="modal fade disapprove-modal" id="disapprove-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title">Disapproval</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('disapprove-report.store') }}" method="POST" id="">
                    @csrf
                    <input type="hidden" id="hidden-disapproval-input" class="hidden-dispproval-input" value=""
                        name="disapprove-office-info">
                    <div class="modal-body">
                        <div class="form-group">
                            <select class="offices select2 col-sm-12" multiple="multiple"
                                data-placeholder="Select office" label="Office" required name="disapproved-office-list[]"
                                id="disapproval-select">

                            </select>
                        </div>
                        <br>
                        <textarea class="form-control summernote" name="written_comment" id="" cols="30" rows="10"
                            placeholder="Enter your comment"></textarea>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Disapprove</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- View reply comment modal --}}
    <div class="modal fade view-reply-comment-modal" id="view-reply-comment" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title">Replied Comment</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                {{-- <form action="{{ route('disapprove-plan.store') }}" method="POST" id="">
                    @csrf
                    <input type="hidden" id="hidden-reply-input" class="hidden-reply-input" value=""
                        name="disapprove-office-info"> --}}
                <div class="modal-body">
                    <h5 class="view-reply-comment-text bg-light border p-3"><u>Reply message:</u>
                        <p></p>
                    </h5>
                    <br>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{-- <button type="submit" class="btn btn-primary">Yes</button> --}}
                </div>
                {{-- </form> --}}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    {{-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}


    <script>
        // Listen for the write comment click event
        $('.approval-container').on('click', '.write-comment', function() {

            var id = $(this).attr('data-id');

            // AJAX request with the information attached
            var url = "{{ route('report-comment.ajax', [':id']) }}";
            url = url.replace(':id', id);

            // Empty office name
            $('.modal-title #office-name').empty();

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('.modal-title u').html(response.officeName);
                    $('.modal-kpi u').html(response.kpi);

                    let inputData = response.info;

                    $("#hidden-input").val(inputData);

                    $('.comment-modal').modal('show');
                }
            });

        });
    </script>

    <script>
        $('#comment-form').on('submit', function(e) {

            if ($('.summernote').summernote('isEmpty')) {

                $('.comment-error').show();
                // cancel submit
                e.preventDefault();
            } else {
                // do action
            }
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 200
            });
            $('.dropdown-toggle').dropdown();

            let ownReportVisible = {{ json_encode($ownReportVisible) }}

            if(ownReportVisible == false){
                $('.own-report-div').hide();
            }

            let kpiList = {{ json_encode($kpiList) }};

            for (let i = 0; i < kpiList.length; i++) {
                let selector = `.office-checkbox-kpi-${kpiList[i]}`;
                var checkboxes = $(selector);

                if (checkboxes.length <= 0) {
                    $(`#checkAll-div${kpiList[i]}`).css("display", "none");
                    $(`#approve-for-${kpiList[i]}`).css("display", "none");
                }

                let anyApprovedSelector = $(`#any-approved-${kpiList[i]}`);

                // console.log(anyApprovedSelector.length);
                if (anyApprovedSelector.length <= 0) {
                    $(`#disapprove-for-${kpiList[i]}`).css("display", "none");
                }


            }

            for (let i = 0; i < kpiList.length; i++) {
                let selector = `.hidden-self-input-${kpiList[i]}`;
                var checkboxes = $(selector);

                if (checkboxes.length <= 0) {
                    $(`#approve-for-self-${kpiList[i]}`).css("display", "none");
                }

            }

        });
    </script>

    <script>
        function checkSelectedOffices(kpiId, offices) {

            let selector = `.office-checkbox-kpi-${kpiId}`;
            let officeNamesObject = offices;

            // Get the checkbox elements
            var checkboxes = $(selector);

            let unselectedOffices = [];
            let officeIdList = [];
            let officesNameList = [];

            // get all unchecked offices value
            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    continue;
                } else {
                    unselectedOffices.push(checkboxes[i].value);
                }
            }

            // check if there are unselected checkboxes of offices then store their id
            if (unselectedOffices !== []) {
                for (let i = 0; i < unselectedOffices.length; i++) {
                    let office = unselectedOffices[i].split("-")
                    officeIdList.push(office[1])
                }
            }

            // check if the unselected offices id exist in the object
            for (let id in officeNamesObject) {
                if (officeIdList.includes(id)) {
                    officesNameList.push(officeNamesObject[id])
                }
            }

            // merge all unselected offices as single string
            let mergedOfficeNames = '';
            if (officesNameList !== []) {
                let counted = 0;
                for (let i = 0; i < officesNameList.length; i++) {
                    if (counted == 0) {
                        mergedOfficeNames += `${officesNameList[i]}`;
                    } else {
                        mergedOfficeNames += `, ${officesNameList[i]}`;
                    }
                    counted++;
                }
            }

            // let remind the user if there are unselected offices
            if (checkboxes.length === unselectedOffices.length) {
                alert('You need to select at least one office!');
                return false;
            } else {
                if (mergedOfficeNames) {
                    return confirm(`You have not selected '${mergedOfficeNames}', Are you sure you want to continue?`);
                } else {
                    return confirm('Are you sure you want to approve all selected offices?')
                }
            }

            // return true;

        }

        // select all checkboxes at once
        let checkAllList = $('.checkAllOffices');
        console.log(checkAllList);

        for (let i = 0; i < checkAllList.length; i++) {

            let selecto = `#${checkAllList[i].id}`;
            $(selecto).change(function() {

                let value = $(selecto).val();

                // var setcheckbox = function() {
                //     this.prop("checked", true);
                // };

                if ($(this).is(':checked')) {
                    $(`.office-checkbox-kpi-${value}`).prop('checked', this.checked);
                } else {
                    $(`.office-checkbox-kpi-${value}`).prop('checked', this.checked);
                }

            });
        }
    </script>

    {{-- View Comment --}}
    <script>
        // Listen for the view comment click event
        $('.approval-container').on('click', '.view-comment', function() {

            var id = $(this).attr('data-id');

            // AJAX request with the information attached
            var url = "{{ route('report-comment.view-comment', [':id']) }}";
            url = url.replace(':id', id);

            // Empty office name
            $('.commented-from #office-name').empty();

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('.commented-from u').html(response.officeName);
                    $('.view-commented-by p').html(response.commentText);

                    let inputData = response.info;

                    $("#hidden-input-view-comment").val(inputData);

                    $('.view-comment-modal').modal('show');
                }
            });

        });
    </script>

    {{-- Disapprove plan --}}
    <script>
        // Listen for the disapprove click event
        $('.approval-container').on('click', '.disapprove-plan', function() {

            var id = $(this).attr('data-id');
            // alert(id);

            // AJAX request with the information attached
            var url = "{{ route('disapprove-report.ajax', [':id']) }}";
            url = url.replace(':id', id);

            $("#disapproval-select").html('');
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    let inputData = response.info;

                    $("#hidden-disapproval-input").val(inputData);

                    $.each(response.offices, function(key, value) {
                        console.log(key);
                        $("#disapproval-select").append('<option value="' + key + '">' + value +
                            '</option>');
                    });

                    $('.disapprove-modal').modal('show');
                }
            });

        });
    </script>

    {{-- reply comment --}}
    <script>
        // Listen for the view reply click event
        $('.approval-container').on('click', '.view-reply-comment', function() {

            var id = $(this).attr('data-id');
            // alert(id);

            // AJAX request with the information attached
            var url = "{{ route('replyreport-comment.ajax', [':id']) }}";
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {

                    let inputData = response.info;

                    $('.view-reply-comment-text p').html(response.replyText);
                    // $("#hidden-reply-input").val(inputData);

                    $('.view-reply-comment-modal').modal('show');
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {

            $('.select2').select2();

        });
    </script>

    <script>
        $(document).ready(function() {
            let unapprovedOfficeCount = {{ json_encode($unapprovedOfficeCount) }};
            let kpiListForApproval = {{ json_encode($kpiListForApproval) }};

            console.log(kpiListForApproval);

            for (let i = 0; i < kpiListForApproval.length; i++) {

                let selector = `.value-for-${kpiListForApproval[i]}`;
                var pTagValue = $(selector);
                console.log(pTagValue);
                // console.log(pTagValue.length);

                if (pTagValue.length > 0) {
                    $(`#${kpiListForApproval[i]}-unapproved`).text(`${pTagValue.length} unapproved`);
                }

            }


        });
    </script>

@endsection
