@extends('layouts.app')
@section('title', 'Reporting')

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

</style>
@endsection

@section('content')

<div class="row justify-content-left mt-3">
    <div class="col-md-2">

        <div class="card border border-secondary">
            <div class="card-header bg-info h5">Goal List</div>
            <div class="card-body border rounded" style="background-color: #e9ecef;">
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="mb-3 row">
                        <table class="table table-borderless table-hover">
                            <tbody>

                                @forelse($kpis['goal'] as $goal)
                                <tr>
                                    {{-- <td class="rounded "> --}}
                                    <a class="border border-secondary btn btn-light btn-block text-left {{ Request::is('smis/report/get-objectives-reporting/' . $goal->id) ? 'bg-primary' : '' }}" href='{{ route('get-objectives-reporting', $goal->id) }}' role="button" aria-expanded="false" aria-controls="collapseExample">
                                        {{ optional($goal->goalTranslations[0])->name ?? '-' }}
                                    </a>
                                    {{-- </td> --}}


                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
    $isIterated = false;
    $isContented = false;
    $objCounter = null;
    $appended = false;
    $kpiList = [];
    $objectiveList = [];
    @endphp

    <div class="col-md-10">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                {{-- @dd($objectives) --}}
                <div class="card-header p-0 border-bottom-0 objectives-list-tab">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        @forelse ($objectives as $objective)
                            @php
                                array_push($objectiveList, $objective->id);
                            @endphp
                        {{-- @dd($objective->objectiveTranslations) --}}


                        {{-- @forelse($objective as $kpi) --}}
                        {{-- @dd($kpi->objective->objectiveTranslations) --}}

                        @forelse($objective->objectiveTranslations as $obj_ts)
                        @if (app()->getLocale() == $obj_ts->locale)
                        @if ($isIterated)
                        <li class="nav-item">
                            <a class="nav-link  btn-info btn-rounded"  id="{{ $obj_ts->translation_id . '-tab' }}" data-toggle="pill" href="{{ '#objective-' . $obj_ts->translation_id }}" role="tab" aria-controls="{{ $obj_ts->translation_id }}" aria-selected="false" title="{{ $obj_ts->name }}">{{ Str::of($obj_ts->name)->limit(10) }}
                            </a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link active  btn-info btn-rounded" id="{{ $obj_ts->translation_id . '-tab' }}" data-toggle="pill" href="{{ '#objective-' . $obj_ts->translation_id }}" role="tab" aria-controls="{{ $obj_ts->translation_id }}" aria-selected="true" title="{{ $obj_ts->name }}">{{ Str::of($obj_ts->name)->limit(10) }}
                            </a>
                        </li>

                        @php
                        $isIterated = true;
                        @endphp
                        @endif
                        @endif
                        @empty
                        {{-- <p>gufdgfhj</p> --}}
                        @endforelse

                        {{-- @empty
                        <p>hguyftdf</p>
                        @endforelse --}}

                        @empty
                        @endforelse
                    </ul>
                </div>

                <div class="card-body">
                    {{-- <button class="btn" onclick="expandAll()"><h5><i class="fas fa-plus"></i> Expand All</h5></button> --}}
                    <div class="tab-content" id="custom-tabs-four-tabContent">

                        @forelse($objectives as $objective)
                        {{-- @dd($objective) --}}
                        {{-- @forelse($objective as $kpi) --}}

                        @if ($isContented)
                        <div class="tab-pane fade" id="{{ 'objective-' . $objective->id }}" role="tabpanel" aria-labelledby="{{ $objective->id . '-tab' }}">
                            @else
                            <div class="tab-pane fade active show" id="{{ 'objective-' . $objective->id }}" role="tabpanel" aria-labelledby="{{ $objective->id . '-tab' }}">
                                @php
                                $isContented = true;
                                @endphp
                                @endif

                                <form action="{{ route('report.save') }}" method="POST" id="reporting-form" onsubmit="return validateReportForm()" enctype="multipart/form-data">
                                    @csrf

                                    {{-- @if ($objective) --}}
                                    @php
                                    $reporting_time_id = getReportingPeriod();
                                    $KeyPeformanceIndicators = getKeyperormanceIndicatorsForReporting($objective, $user_offices,$reporting_time_id);
                                    @endphp
                                    @forelse($KeyPeformanceIndicators as $kpi)
                                    @php
                                    $checkPlanedForKpi = checkPlanedForKpi($planning_year->id ?? NULL, $kpi->id, auth()->user()->offices[0]->id); //dd($checkPlanedForKpi->plan_status);
                                    @endphp
                                    @if($checkPlanedForKpi)
                                    @if($checkPlanedForKpi->plan_status==1)
                                            @php
                                                array_push($kpiList, $kpi->id)
                                            @endphp
                                        <p class="kpi-under-obj-{{ $objective->id }}"></p>

                                    <div class="card p-2" style="border: 1px solid #b1b1b1;">
                                        <div class="card-header bg-light">
                                            <h3 class="card-title">KPI:
                                                {{ $kpi->KeyPeformanceIndicatorTs[0]->name }}
                                                 @if ($kpi ->measurement)
                                                       <b> {{"( in "}}{{$kpi ->measurement['slug'] }} {{")" }}</b>
                                                @endif
                                                @php
                                                    $kpi_id = $kpi->id;
                                                    $behavior = $kpi->behavior->slug;
                                                @endphp
                                                <b>({{ $kpi->behavior->behaviorTranslations[0]->name }})</b>
                                                (Reporting:{{ $kpi->reportingPeriodType->reportingPeriodTypeTs[0]->name }})
                                                @php
                                                $kpi_id = $kpi->id;
                                                $checkPlanedForKpi = checkPlanedForKpi($planning_year->id ?? NULL, $kpi->id, auth()->user()->offices[0]->id);
                                                @endphp

                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body reporting-container">

                                            @if (hasOfficeActiveReportComment(auth()->user()->offices[0]->id, $kpi_id, $planning_year->id ?? NULL)->count() > 0)
                                            <div class="bg-light w-5 float-right p-3">
                                                <p class="m-auto">You have comment from <u>{{ getReportCommentorInfo(auth()->user()->offices[0]->id, $kpi->id, $planning_year->id ?? NULL)->name ?? '-' }}</u>
                                                    <a class="btn btn-sm btn-flat btn-info text-white view-comment" data-toggle="modal" data-target="#view-comment-modal" data-id="{{ getReportCommentorInfo(auth()->user()->offices[0]->id, $kpi->id, $planning_year->id ?? NULL)->translation_id ?? '-' }}-{{$kpi_id}}-{{$planning_year->id ?? NULL}}">
                                                        <i class="fas fa fa-eye mr-1"></i>View Comment
                                                    </a>
                                                </p>
                                            </div>
                                            @endif

                                            <table class="table table-bordered">
                                                <thead>
                                                    {{-- If KPI has Child ones (UG, PG) --}}
                                                    @if (!$kpi->kpiChildOnes->isEmpty())
                                                    @if (!$kpi->kpiChildTwos->isEmpty())
                                                    @if (!$kpi->kpiChildThrees->isEmpty())
                                                    <!-- <tr id="child-ones"> -->
                                                    <tr>
                                                        <th rowspan="2" colspan="2">#</th>
                                                        @foreach ($kpi->kpiChildOnes as $one)
                                                        <th colspan="{{ $kpi->kpiChildThrees->count() }}">
                                                            {{ $one->kpiChildOneTranslations[0]->name }}
                                                            {{-- Enter your name: <input type="text"
                                                                                id="fname" onkeyup="myFunction()">

                                                                            <script>
                                                                                function myFunction() {
                                                                                    let x = document.getElementById("fname");
                                                                                    x.value = x.value.toUpperCase();
                                                                                }

                                                                                function myFunction1() {
                                                                                    var x = document.getElementById("fname").value;
                                                                                    document.getElementById("demo").innerHTML = x;
                                                                                }
                                                                            </script>--}}
                                                        </th>
                                                        @endforeach
                                                    </tr>
                                                    <tr>
                                                        @foreach ($kpi->kpiChildOnes as $one)
                                                        @foreach ($kpi->kpiChildThrees as $kpiThree)
                                                        <th>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
                                                        </th>
                                                        @endforeach
                                                        @endforeach
                                                    </tr>
                                                    @forelse(getReportingQuarter($kpi->reportingPeriodType->id) as $period)
                                                    <tr>
                                                        <th rowspan="{{ $kpi->kpiChildTwos->count() }}">
                                                            {{ $period->reportingPeriodTs[0]->name }}
                                                            {{ 'Total=' }}<br />
                                                            <span name="{{ $kpi_id }}" id="{{ $kpi_id }}{{ $period->id }}"></span>

                                                        </th>
                                                        @foreach ($kpi->kpiChildTwos as $two)
                                                        <th>
                                                            {{ $two->kpiChildTwoTranslations[0]->name }}
                                                        </th>
                                                        @foreach ($kpi->kpiChildOnes as $one)
                                                        @foreach ($kpi->kpiChildThrees as $kpiThree)
                                                        @php
                                                        $inputname = $kpi->id . $period->id;
                                                        //echo ($inputname)."<br />";
                                                        $plan = getSavedPlanIndividualOneTwoThree($planning_year->id ?? NULL, $kpi->id, $period->id, $one->id, $two->id, $kpiThree->id, auth()->user()->offices[0]->id);
                                                        $off_level = auth()->user()->offices[0]->level;
                                                        $disabled ="";
                                                        @endphp
                                                        @if ($plan && $plan->accom_value)
                                                        @if($off_level ===1)
                                                        @if ($off_level === $plan->accom_status)
                                                        @php $disabled ="disabled"; @endphp
                                                        @endif
                                                        @elseif ($off_level != $plan->accom_status)
                                                        @php $disabled ="disabled"; @endphp
                                                        @endif
                                                        <td>
                                                            <input name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}-{{ $kpiThree->id }}" value="{{ $plan->accom_value }}" class="form-control {{ $inputname }}" type="number" required {{$disabled}}>
                                                        </td>
                                                        @else
                                                        <td>
                                                            <input id="selectProducts" name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}-{{ $kpiThree->id }}" @if ($period->slug == 1) id = "yearly"
                                                            @else
                                                            id = "period" @endif
                                                            class="form-control {{ $inputname }}"
                                                            type="number" required>

                                                        </td>
                                                        @endif
                                                        <script>
                                                            $(".{{ $inputname }}").keyup(function() {
                                                                var tot = 0;
                                                                var periodtot = 0;
                                                                $(".{{ $inputname }}").each(function() {
                                                                    tot += Number($(this).val());
                                                                });
                                                                $('#{{ $kpi_id }}{{ $period->id }}').text(tot);

                                                            });

                                                        </script>
                                                        @endforeach
                                                        @endforeach
                                                    </tr>
                                                    @endforeach
                                                    @empty
                                                    @endforelse

                                                    {{-- KPI has  child one and child two --}}
                                                    @else
                                                    <tr>
                                                        <th colspan="2">#</th>
                                                        @foreach ($kpi->kpiChildOnes as $one)
                                                        <th>
                                                            {{ $one->kpiChildOneTranslations[0]->name }}
                                                        </th>
                                                        @endforeach
                                                    </tr>
                                                    @forelse(getReportingQuarter($kpi->reportingPeriodType->id) as $period)
                                                    <tr>
                                                        <th rowspan="{{ $kpi->kpiChildTwos->count() }}">
                                                            {{ $period->reportingPeriodTs[0]->name }}
                                                        </th>
                                                        @foreach ($kpi->kpiChildTwos as $two)
                                                        <th>
                                                            {{ $two->kpiChildTwoTranslations[0]->name }}
                                                        </th>
                                                        @foreach ($kpi->kpiChildOnes as $one)
                                                        @php
                                                        $plan12 = getSavedPlanIndividualOneTwo($planning_year->id ?? NULL, $kpi->id, $period->id, $one->id, $two->id, auth()->user()->offices[0]->id);
                                                        @endphp
                                                        @if ($plan12)
                                                        @php
                                                        $inputname = $kpi->id;
                                                        $period->id;
                                                        $one->id;
                                                        $two->id;
                                                        $off_level = auth()->user()->offices[0]->level;
                                                        $disabled ="";

                                                        @endphp
                                                        @if($off_level ===1)
                                                        @if ($off_level === $plan12->accom_status)
                                                        @php $disabled ="disabled"; @endphp
                                                        @endif
                                                        @elseif ($off_level != $plan12->accom_status)
                                                        @php $disabled ="disabled"; @endphp
                                                        @endif
                                                        <td>
                                                            <input name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}" class="form-control" value="{{ $plan12->accom_value }}" type="number" required {{$disabled}}>
                                                        </td>
                                                        @else
                                                        <td>
                                                            <input name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}" id="koneT{{ $one->id }}{{ $two->id }}{{ $period->slug }}" class="form-control" type="number" required>
                                                            <span id="spankOneT{{ $one->id }}{{ $period->slug }}"></span>
                                                        </td>
                                                        @endif
                                                        @endforeach
                                                    </tr>
                                                    @endforeach
                                                    @empty
                                                    @endforelse
                                                    <script>
                                                        $(function() {
                                                            $('input[id=koneT{{ $one->id }}{{ $two->id }}5]').on('change', function() {
                                                                //document.write('{{ $one->id }}{{ $two->id }}');
                                                                var KOTvalues1 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}1').value;
                                                                var KOTvalues2 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}2').value;
                                                                var KOTvalues2 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}2').value;
                                                                var KOTvalues3 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}3').value;
                                                                var KOTvalues4 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}4').value;
                                                                var KOTvalues5 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}5').value;
                                                                sumOT = parseFloat(KOTvalues2) + parseFloat(KOTvalues3) + parseFloat(KOTvalues4) + parseFloat(KOTvalues5);
                                                                if (KOTvalues1 != sumOT) {
                                                                    document.getElementById("spankOneT{{ $one->id }}{{ $two->id }}1").innerHTML = "Period plan not matched with yearly";
                                                                    $('#koneT{{ $one->id }}{{ $two->id }}1').val("");
                                                                }


                                                            });
                                                        });

                                                    </script>
                                                    @endif
                                                    {{-- KPI has  child one only --}}
                                                    @else
                                                    <tr>
                                                        <th>#</th>
                                                        @forelse(getReportingQuarter($kpi->reportingPeriodType->id) as $period)
                                                        <th>
                                                            {{ $period->reportingPeriodTs[0]->name }}
                                                        </th>
                                                        @empty
                                                        @endforelse
                                                        @foreach ($kpi->kpiChildOnes as $one)
                                                    <tr>
                                                        <th>
                                                            {{ $one->kpiChildOneTranslations[0]->name }}<br />
                                                        </th>
                                                        @forelse(getReportingQuarter($kpi->reportingPeriodType->id) as $period)
                                                        @php
                                                        $inputname = '{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}';
                                                        $plan1 = getSavedPlanIndividualOne($planning_year->id ?? NULL, $kpi->id, $period->id, $one->id, auth()->user()->offices[0]->id);
                                                        $off_level = auth()->user()->offices[0]->level;
                                                        $disabled ="";

                                                        @endphp
                                                        @if ($plan1 && $plan1->accom_value)
                                                        @if($off_level ===1)
                                                        @if ($off_level === $plan1->accom_status)
                                                        @php $disabled ="disabled"; @endphp
                                                        @endif
                                                        @elseif ($off_level != $plan1->accom_status)
                                                        @php $disabled ="disabled"; @endphp
                                                        @endif
                                                        <td>
                                                            <input name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}" ; class="form-control" value="{{ $plan1->accom_value }}" type="number" required {{$disabled}}>
                                                        </td>
                                                        @else
                                                        <td>
                                                            <input name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}" id="kone{{ $one->id }}{{ $period->slug }}" class="form-control" type="number" required>
                                                            <span id="spankOne{{ $one->id }}{{ $period->slug }}"></span>
                                                        </td>
                                                        @endif
                                                        @empty
                                                        @endforelse
                                                        <script>
                                                            $(function() {
                                                                $('input[id=kone{{ $one->id }}5]').on('change', function() {
                                                                    var KOvalues1 = document.getElementById('kone{{ $one->id }}1').value;
                                                                    var KOvalues2 = document.getElementById('kone{{ $one->id }}2').value;
                                                                    var KOvalues2 = document.getElementById('kone{{ $one->id }}2').value;
                                                                    var KOvalues3 = document.getElementById('kone{{ $one->id }}3').value;
                                                                    var KOvalues4 = document.getElementById('kone{{ $one->id }}4').value;
                                                                    var KOvalues5 = document.getElementById('kone{{ $one->id }}5').value;
                                                                    sum = parseFloat(KOvalues2) + parseFloat(KOvalues3) + parseFloat(KOvalues4) + parseFloat(KOvalues5);
                                                                    if (KOvalues1 != sum) {
                                                                        document.getElementById("spankOne{{ $one->id }}1").innerHTML = "Period plan not matched with yearly";
                                                                        $('#kone{{ $one->id }}1').val("");
                                                                    }


                                                                });
                                                            });

                                                        </script>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </thead>
                                            </table>
                                            {{-- KPI has no child one, which means just only plain input --}}
                                            @else
                                            <table class="table table-bordered">
                                                <tr>
                                                    @forelse(getReportingQuarter($kpi->reportingPeriodType->id) as $period)
                                                    <th>
                                                        {{ $period->reportingPeriodTs[0]->name }}
                                                    </th>
                                                    @empty
                                                    @endforelse
                                                </tr>
                                                @forelse(getReportingQuarter($kpi->reportingPeriodType->id) as $period)
                                                <p class="mb-3">
                                                    @php
                                                    $inputname = '{{ $kpi->id }}-{{ $period->id }}';
                                                    $planP = getSavedPlanIndividual($planning_year->id ?? NULL, $kpi->id,
                                                    $period->id, auth()->user()->offices[0]->id,true);
                                                    $off_level = auth()->user()->offices[0]->level;
                                                    $disabled ="";
                                                    @endphp
                                                    @if ($planP && $planP->accom_value)
                                                    @if($off_level ===1)
                                                    @if ($off_level === $planP->accom_status)
                                                    @php $disabled ="disabled"; @endphp
                                                    @endif
                                                    @elseif ($off_level != $planP->accom_status)
                                                    @php $disabled ="disabled"; @endphp
                                                    @endif
                                                    <td>
                                                        <input name="{{ $kpi->id }}-{{ $period->id }}" class="form-control" value="{{ $planP->accom_value }}" id="{{ $period->slug }}" type="number" required {{$disabled}}>
                                                        <span id="s{{ $period->slug }}"></span>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <input class="form-control" type="number" placeholder="Enter KPI value" id="{{ $period->slug }}" name="{{ $kpi->id }}-{{ $period->id }}" required>
                                                        <span id="s{{ $period->slug }}"></span>
                                                    </td>
                                                    @endif
                                                    <script>
                                                        $(function() {
                                                            $('input[id=5]').on('change', function() {
                                                                var sum = 0;
                                                                // Get the values, turn them into numbers
                                                                var values1 = document.getElementById(1).value;
                                                                var values2 = document.getElementById(2).value;
                                                                var values3 = document.getElementById(3).value;
                                                                var values4 = document.getElementById(4).value;
                                                                var values5 = document.getElementById(5).value;

                                                                // Sum them up
                                                                sum = parseFloat(sum) + parseFloat(values2) + parseFloat(values3) + parseFloat(values4) +
                                                                    parseFloat(values5);
                                                                // Show the result
                                                                if (values1 != sum) {
                                                                    document.getElementById("s1").innerHTML = "Period plan not matched with yearly";
                                                                    $('#1').val("");
                                                                }
                                                            });
                                                        });

                                                    </script>
                                                    @empty
                                                    @endforelse
                                                    </tr>
                                            </table>
                                            <script>
                                                $(".summe").keyup(function() {
                                                    var tot = 0;
                                                    $(".summe").each(function() {
                                                        tot += Number($(this).val());
                                                    });
                                                    $('#tot-qty').text(tot + ' usd');
                                                });

                                            </script>
                                            </p>
                                            @endif
                                            @php
                                            $report_naration = getSavedReportNaration($planning_year->id ?? NULL, $period->id, $kpi->id, auth()->user()->offices[0]->id);
                                            $plan_docment = getSavedPlanDocument($planning_year->id ?? NULL, $period->id, $kpi->id, auth()->user()->offices[0]->id);
                                            $upload_documents = getUploadDocuments($kpi->id, $period->id, auth()->user()->offices[0]->id, $planning_year->id);
                                            @endphp
                                            {{-- @dump($kpi->id); --}}
                                            @if ($report_naration)
                                                <label for="summernote">Major Activities</label>
                                                <input type="hidden" name="type" value="yes">
                                                @if ($disabled === "disabled")
                                                    <textarea name="dx-{{ $kpi->id }}-{{ $period->id }}" style="height: 100px;" class="form-control summernote" id="narration-field-{{ $kpi->id }}" placeholder="Narration here" disabled>{!! $report_naration !!}
                                                    </textarea>
                                                    <script>
                                                        $(document).ready(function() {
                                                            @if($disabled === "disabled")
                                                                $('#narration-field-{{ $kpi->id }}').summernote('disable');
                                                            @endif
                                                        });
                                                    </script>
                                                @else
                                                    <textarea name="dx-{{ $kpi->id }}-{{ $period->id }}" style="height: 100px;" class="form-control summernote" id="narration-field-{{ $kpi->id }}" placeholder="Narration here">{!! $report_naration !!}
                                                    </textarea>
                                                @endif
                                                <p class="narration-field-{{ $kpi->id }} text-danger" style="display: none;">Please fill Major Activities field!</p>
                                            @else
                                                <label for="summernote">Major Activities</label>
                                                <textarea name="dx-{{ $kpi->id }}-{{ $period->id }}" style="height: 100px;" class="form-control summernote" id="narration-field-{{ $kpi->id }}" placeholder="Narration here"></textarea>
                                                <p class="narration-field-{{ $kpi->id }} text-danger" style="display: none;">Please fill Major Activities field!</p>
                                            @endif
                                             @if (count($upload_documents)> 0)
                                                     <!-- Display Uploaded Files -->
                                                <h4>Uploaded Files</h4>
                                                <ul class="list-group mb-4">
                                                    @foreach ($upload_documents as $filee)
                                                     {{-- @dump($filee); --}}
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="{{ asset('storage/uploads/' . basename($filee->name)) }}" target="_blank">
                                                                {{ basename($filee->name) }}
                                                            </a>
                                                            <button class="btn btn-danger btn-sm delete-file"
                                                            data-file="{{ basename($filee->name) }}"
                                                            data-kpi="{{ $kpi->id }}"
                                                            data-period="{{ $period->id }}"
                                                            data-office="{{ auth()->user()->offices[0]->id }}"
                                                            data-year="{{ $planning_year->id }}">
                                                            <i class="icon ion-md-trash"></i>
                                                        </button>                                                        </li>
                                                     @endforeach
                                                </ul>
                                            @else
                                                <label for="file" class="form-label">Choose Supporting document (Images, PDFs, Audio, Word, etc.)</label>
                                                <input type="file" name="myfile[]" id="file" class="form-control" multiple>

                                                {{-- <label class="form-label" for="inputImage">Supporting document(in pdf):
                                                </label>
                                                <input  type="file"  name="myfile"   id="inputImage" class="form-control"> --}}
                                            @endif
                                        </div>
                                    </div>
                                    @else

                                    <div class="callout callout-warning">
                                        <i class="icon fas fa-info-circle mr-2"></i>
                                        <p class="d-inline">{{"Plan approvement is not completed"}} for kpi: <u>{{ $kpi->KeyPeformanceIndicatorTs[0]->name }}</u></p>
                                    </div>

                                    @endif
                                    @else

                                        <div class="callout callout-warning">
                                            <i class="icon fas fa-info-circle mr-2"></i>
                                            <p class="d-inline">{{"Plan record not found for Key Performance Indicator"}}: <u>{{ $kpi->KeyPeformanceIndicatorTs[0]->name }}</u></p>
                                        </div>
                                    @endif
                                    @empty
                                        <div class="callout callout-warning">
                                            <i class="icon fas fa-info-circle mr-2"></i>
                                            <p class="d-inline">No KPI exist with active reporting period in this office and Objective!</p>
                                        </div>
                                    @endforelse
                                    <button type="submit" class="btn btn-primary" id="submit-for-{{ $objective->id }}">Submit</button>
                            </div>
                            </form>
                            {{-- </div>
                </div> --}}

                            @empty
                            {{-- <p>ugyftrdy</p> --}}
                            @endforelse



                        </div>
                    </div>

                    <!-- /.card -->
                </div>
            </div>
        </div>

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
                    <input type="hidden" id="hidden-input-view-comment" class="hidden-input-view-comment" value="" name="view-commented-office-info">
                    <div class="modal-body">
                        <h5 class="view-commented-by bg-light border p-3 overflow-auto"><u>Comment: </u>
                            <p class="mw-75"></p>
                        </h5>
                        <br>
                        {{-- content to be filled after ajax request here --}}
                        <textarea class="form-control summernote" name="reply_comment" id="" cols="30" rows="10" placeholder="Enter your comment"></textarea>
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


    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> --}}
    {{-- <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 230
            });
            $('.dropdown-toggle').dropdown()
        });

    </script>
    <script>
        $(document).ready(function() {
        $(".delete-file").click(function(e) {
            e.preventDefault()
            var btn = $(this);
            var fileName = btn.data("file");
            var kpi = btn.data("kpi");
            var period = btn.data("period");
            var office = btn.data("office");
            var year = btn.data("year");

            if (confirm("Are you sure you want to delete " + fileName + "?")) {
                $.ajax({
                    url: "{{ route('delete.file') }}",
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}",
                        file: fileName,
                        kpi: kpi,
                        period: period,
                        office: office,
                        year: year
                    },
                    success: function(response) {
                        alert(response.success);
                        btn.closest("li").remove(); // Remove file from UI
                    },
                    error: function(xhr) {
                        alert("Error deleting file.");
                    }
                });
            }
        });
    });
    </script>

    <script>

        function validateReportForm(){
            // $('.tab-pane.active #reporting-form').on('submit', function(e) {

                let kpiList = {{ json_encode($kpiList) }};
                console.log(kpiList);

                for (let i = 0; i < kpiList.length; i++) {
                    $(`.tab-pane.active .narration-field-${kpiList[i]}`).css("display", "none");
                    let summernoteSelector = `.tab-pane.active #narration-field-${kpiList[i]}`;
                    // console.log($(summernoteSelector).length)

                    if ($(summernoteSelector).length > 0 && $(summernoteSelector).summernote('isEmpty')) {

                        // focus on the empty field
                        $(`.tab-pane.active #narration-field-${kpiList[i]}`).focus();
                        $(`.tab-pane.active #narration-field-${kpiList[i]}`).summernote('focus');

                        $(`.tab-pane.active .narration-field-${kpiList[i]}`).css("display", "block");

                        // cancel submit
                        return false;
                        preventDefault();
                    }

                }
            // })
        }
    </script>

    <script>
        let objectiveList = {{ json_encode($objectiveList) }};

        for (let i = 0; i < objectiveList.length; i++) {
            let selector = `.kpi-under-obj-${objectiveList[i]}`;
            var pTag = $(selector);

            if (pTag.length <= 0) {
                $(`#submit-for-${objectiveList[i]}`).css("display", "none");
            }

        }
    </script>

    {{-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}

    {{-- <script>
        $(document).ready(function() {
            $('#goal-card').on('click', '.goal-list', function() {
                var goalId = $(this).attr('data-id');

                var url = "{{ route('get-objectives', [':id']) }}";
    url = url.replace(':id', goalId);

    $('.fillable-objective').empty();

    $.ajax({
    url: url,
    dataType: 'json',
    success: function(response) {

    // foreach(response as r){
    // console.log(r)
    // }
    console.log(response);
    $('.fillable-objective').html(response);
    }
    });

    })

    })
    </script> --}}

    <script>
        function expandAll() {

        }

        $(function() {
            $('input[name=selectProducts]').on('change', function() {
                // Get the values, turn them into numbers
                var values = $('input[name=selectProducts]:checked, input[name=selectProducts][type=text]')
                    .map(function() {
                        return +this.value;
                        //     ^---- coerce to number
                    }).get();
                // Sum them up
                var sum = values.reduce(function(a, b) {
                    return a + b;
                });
                // Show the result
                $('#products').text(sum);
            });
        });

    </script>

    {{-- View Comment --}}
    <script>
        // Listen for the view comment click event
        $('.reporting-container').on('click', '.view-comment', function() {

            var id = $(this).attr('data-id');

            // AJAX request with the information attached
            var url = "{{ route('report-comment.view-comment', [':id']) }}";
            url = url.replace(':id', id);

            // Empty office name
            $('.commented-from #office-name').empty();

            $.ajax({
                url: url
                , dataType: 'json'
                , success: function(response) {
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

    @endsection
