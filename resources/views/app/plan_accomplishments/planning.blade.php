@extends('layouts.app')
@section('title', 'Planning')

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
        #view-comment-paragraph *{
            margin-bottom: 0 !important;
        }
    </style>
@endsection

@section('content')

    <div class="row justify-content-left mt-3">
        <div class="col-md-2">

            <div class="card">
                <div class="card-header">Goal List</div>
                <div class="card-body">
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="mb-3 row">
                            <table class="table table-borderless table-hover">
                                <tbody>

                                    @forelse($kpis['goal'] as $goal)
                                        <tr>
                                            {{-- <td class="rounded "> --}}
                                            <a class="border btn btn-light btn-block text-left {{ Request::is('smis/plan/get-objectives/' . $goal->id) ? 'bg-primary' : '' }}"
                                                href='{{ route('get-objectives', $goal->id) }}' role="button"
                                                aria-expanded="false" aria-controls="collapseExample">
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
        @endphp

        <div class="col-md-10">
            <div class="col-12">
                <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                    {{-- @dd($objectives) --}}
                    <div class="card-header p-0 border-bottom-0 objectives-list-tab">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            @forelse ($objectives as $objective)
                                {{-- @dd($objective->objectiveTranslations) --}}


                                {{-- @forelse($objective as $kpi) --}}
                                {{-- @dd($kpi->objective->objectiveTranslations) --}}

                                @forelse($objective->objectiveTranslations as $obj_ts)
                                    @if (app()->getLocale() == $obj_ts->locale)
                                        @if ($isIterated)
                                            <li class="nav-item">
                                                <a class="nav-link" id="{{ $obj_ts->translation_id . '-tab' }}"
                                                    data-toggle="pill" href="{{ '#objective-' . $obj_ts->translation_id }}"
                                                    role="tab" aria-controls="{{ $obj_ts->translation_id }}"
                                                    aria-selected="false" title="{{ $obj_ts->name }}">{{ Str::of($obj_ts->name)->limit(10) }}
                                                </a>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a class="nav-link active" id="{{ $obj_ts->translation_id . '-tab' }}"
                                                    data-toggle="pill" href="{{ '#objective-' . $obj_ts->translation_id }}"
                                                    role="tab" aria-controls="{{ $obj_ts->translation_id }}"
                                                    aria-selected="true" title="{{ $obj_ts->name }}">{{ Str::of($obj_ts->name)->limit(10) }}
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
                                    <div class="tab-pane fade" id="{{ 'objective-' . $objective->id }}" role="tabpanel"
                                        aria-labelledby="{{ $objective->id . '-tab' }}">
                                    @else
                                        <div class="tab-pane fade active show" id="{{ 'objective-' . $objective->id }}"
                                            role="tabpanel" aria-labelledby="{{ $objective->id . '-tab' }}">
                                            @php
                                                $isContented = true;
                                            @endphp
                                @endif
                                
                                <form action="{{ route('plan.save') }}" method="POST" onsubmit="return validateTheForm()">
                                    @csrf

                                    {{-- @if ($objective) --}}
                                    @php
                                        $KeyPeformanceIndicators = getKeyperormanceIndicators($objective, $user_offices);
                                    @endphp
                                    @forelse($KeyPeformanceIndicators as $kpi)
                                        <div class="card p-2" style="border: 1px solid #b1b1b1;">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">KPI:
                                                    {{ $kpi->KeyPeformanceIndicatorTs[0]->name }}
                                                    @php
                                                        $kpi_id = $kpi->id;
                                                        $behavior =  $kpi->behavior->slug;
                                                      @endphp
                                                    (Reporting:{{ $kpi->reportingPeriodType->reportingPeriodTypeTs[0]->name }})
                                                    ({{ $kpi->behavior->behaviorTranslations[0]->name }})
                                                    {{--  <strong  > ({{ $period->reportingPeriodTs[0]->name }})</strong> --}}
                                                    {{--
                                                          getReportingPeriod($kpi->reportingPeriodType->id,$date) --}}
                                                </h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body planning-container">
                                                @if (hasOfficeActiveComment(auth()->user()->offices[0]->id, $kpi_id, $planning_year[0]->id)->count() > 0)
                                                    <div class="bg-light w-5 float-right p-3">
                                                        <p class="m-auto">You have comment from <u>{{ getPlanCommentorInfo(auth()->user()->offices[0]->id, $kpi_id, $planning_year[0]->id)->name ?? '-' }}</u>
                                                            <a  class="btn btn-sm btn-flat btn-info text-white view-comment"
                                                                data-toggle="modal" data-target="#view-comment-modal"
                                                                data-id="{{ getPlanCommentorInfo(auth()->user()->offices[0]->id, $kpi_id, $planning_year[0]->id)->translation_id ?? 0 }}-{{$kpi_id}}-{{$planning_year[0]->id}}">
                                                                <i class="fas fa fa-eye mr-1"></i>View Comment
                                                            </a>
                                                            {{-- <a
                                                                data-toggle="modal" data-target="#disapprove-modal"
                                                                data-id="{{auth()->user()->offices[0]->id}}-{{$planAcc->Kpi->id}}-{{$planning_year[0]->id}}"
                                                                class="btn btn-danger btn-sm btn-flat disapprove-plan" id="disapprove-for-{{ $planAcc->Kpi->id }}">
                                                                Disapprove
                                                            </a> --}}
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
                                                                            {{--  Enter your name: <input type="text"
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
                                                                            </script> --}}
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
                                                                @forelse(getQuarter($kpi->reportingPeriodType->id) as $period)
                                                                    <tr>
                                                                        <th rowspan="{{ $kpi->kpiChildTwos->count() }}">
                                                                            {{ $period->reportingPeriodTs[0]->name }}
                                                                            {{ 'Total=' }}<br />
                                                                            <span name="{{ $kpi_id }}"
                                                                                id="{{ $kpi_id }}{{ $period->id }}"></span>

                                                                        </th>
                                                                        @foreach ($kpi->kpiChildTwos as $two)
                                                                            <th>
                                                                                {{ $two->kpiChildTwoTranslations[0]->name }}
                                                                            </th>
                                                                            @foreach ($kpi->kpiChildOnes as $one)
                                                                                @foreach ($kpi->kpiChildThrees as $kpiThree)
                                                                                    @php
                                                                                        $inputname = $kpi->id . $period->id;
                                                                                        //echo ($inputname)."<br/>";
                                                                                        $plan = getSavedPlanIndividualOneTwoThree($planning_year[0]->id, $kpi->id, $period->id, $one->id, $two->id, $kpiThree->id, auth()->user()->offices[0]->id);
                                                                                        $off_level = auth()->user()->offices[0]->level;
                                                                                        $disabled = '';
                                                                                    @endphp
                                                                                    @if ($plan)
                                                                                        @if($off_level ===1) 
                                                                                            @if ($off_level === $plan->plan_status)
                                                                                                @php $disabled ="disabled"; @endphp
                                                                                            @endif
                                                                                        @elseif ($off_level != $plan->plan_status)
                                                                                            @php $disabled ="disabled"; @endphp
                                                                                        @endif 
                                                                                        <td>
                                                                                            <input type="hidden"
                                                                                                name="type"
                                                                                                value="yes">
                                                                                            <input
                                                                                                name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}-{{ $kpiThree->id }}"
                                                                                                value="{{ $plan->plan_value }}"
                                                                                                class="form-control {{ $inputname }}"
                                                                                                type="number" required
                                                                                                {{ $disabled }}>


                                                                                        </td>
                                                                                    @else
                                                                                        <td>
                                                                                            <input id="selectProducts"
                                                                                                name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}-{{ $kpiThree->id }}"
                                                                                                @if ($period->slug == 1) id = "yearly"
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
                                                            @forelse(getQuarter($kpi->reportingPeriodType->id) as $period)
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
                                                                                $plan12 = getSavedPlanIndividualOneTwo($planning_year[0]->id, $kpi->id, $period->id, $one->id, $two->id, auth()->user()->offices[0]->id);
                                                                            @endphp
                                                                            @if ($plan12)
                                                                                @php
                                                                                    $inputname = $kpi->id;
                                                                                    $period->id;
                                                                                    $one->id;
                                                                                    $two->id;
                                                                                    $off_level = auth()->user()->offices[0]->level;
                                                                                    $disabled = '';
                                                                                @endphp

                                                                               @if($off_level ===1) 
                                                                                    @if ($off_level === $plan12->plan_status)
                                                                                        @php $disabled ="disabled"; @endphp
                                                                                    @endif
                                                                                @elseif ($off_level != $plan12->plan_status)
                                                                                    @php $disabled ="disabled"; @endphp
                                                                                @endif 
                                                                                <td>
                                                                                    <input type="hidden" name="type"
                                                                                        value="yes">
                                                                                    <input
                                                                                        name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}"
                                                                                        class="form-control"
                                                                                        value="{{ $plan12->plan_value }}"
                                                                                        type="number" required
                                                                                        {{ $disabled }}>
                                                                                </td>
                                                                            @else
                                                                                <td>
                                                                                    <input
                                                                                        name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}"
                                                                                        id="koneT{{ $one->id }}{{ $two->id }}{{ $period->slug }}"
                                                                                        class="form-control" type="number"
                                                                                        required>
                                                                                    <span
                                                                                        id="spankOneT{{ $one->id }}{{ $period->slug }}"></span>
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
                                                                    var KOTvalues1 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}1')
                                                                        .value;
                                                                    var KOTvalues2 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}2')
                                                                        .value;
                                                                    var KOTvalues2 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}2')
                                                                        .value;
                                                                    var KOTvalues3 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}3')
                                                                        .value;
                                                                    var KOTvalues4 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}4')
                                                                        .value;
                                                                    var KOTvalues5 = document.getElementById('koneT{{ $one->id }}{{ $two->id }}5')
                                                                        .value;
                                                                    sumOT = parseFloat(KOTvalues2) + parseFloat(KOTvalues3) + parseFloat(KOTvalues4) +
                                                                        parseFloat(KOTvalues5);
                                                                    if (KOTvalues1 != sumOT) {
                                                                        document.getElementById("spankOneT{{ $one->id }}{{ $two->id }}1")
                                                                            .innerHTML = "Period plan not matched with yearly";
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
                                                        @forelse(getQuarter($kpi->reportingPeriodType->id) as $period)
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
                                                        @forelse(getQuarter($kpi->reportingPeriodType->id) as $period)
                                                            @php
                                                                $inputname = '{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}';
                                                                $plan1 = getSavedPlanIndividualOne($planning_year[0]->id, $kpi->id, $period->id, $one->id, auth()->user()->offices[0]->id);
                                                                $off_level = auth()->user()->offices[0]->level;
                                                                $disabled = '';
                                                            @endphp
                                                            @if ($plan1)
                                                                @if($off_level ===1) 
                                                                    @if ($off_level === $plan1->plan_status)
                                                                        @php $disabled ="disabled"; @endphp
                                                                    @endif
                                                                @elseif ($off_level != $plan1->plan_status)
                                                                    @php $disabled ="disabled"; @endphp
                                                                @endif 
                                                                <td>
                                                                    <input type="hidden" name="type" value="yes">
                                                                    <input
                                                                        name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}";
                                                                        class="form-control"
                                                                        value="{{ $plan1->plan_value }}" type="number"
                                                                        required {{ $disabled }}>
                                                                </td>
                                                            @else
                                                                <td>
                                                                    <input
                                                                        name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}"
                                                                        id="kone{{ $one->id }}{{ $period->slug }}"
                                                                        class="form-control" type="number" required>
                                                                    <span
                                                                        id="spankOne{{ $one->id }}{{ $period->slug }}"></span>
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
                                                                    sum = parseFloat(KOvalues2) + parseFloat(KOvalues3) + parseFloat(KOvalues4) + parseFloat(
                                                                        KOvalues5);
                                                                    if (KOvalues1 != sum) {
                                                                        document.getElementById("spankOne{{ $one->id }}1").innerHTML =
                                                                            "Period plan not matched with yearly";
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
                                    @forelse(getQuarter($kpi->reportingPeriodType->id) as $period)
                                        <th>
                                            {{ $period->reportingPeriodTs[0]->name }}
                                        </th>
                                    @empty
                                    @endforelse
                                </tr>
                                @forelse(getQuarter($kpi->reportingPeriodType->id) as $period)
                                    <p class="mb-3">
                                        @php
                                            $last_period = count(getQuarter($kpi->reportingPeriodType->id));

                                            $inputid = $kpi->id . $last_period;
                                            $plan = getSavedPlanIndividual($planning_year[0]->id, $kpi->id, $period->id, auth()->user()->offices[0]->id);
                                            $off_level = auth()->user()->offices[0]->level;
                                            $disabled = '';
                                        @endphp
                                        @if ($plan)
                                             @if($off_level ===1)
                                                @if ($off_level === $plan->plan_status)
                                                    @php $disabled ="disabled"; @endphp
                                                @endif
                                            @elseif ($off_level != $plan->plan_status)
                                                @php $disabled ="disabled"; @endphp
                                             @endif
                                            <td>
                                                <input type="hidden" name="type" value="yes">
                                                <input name="{{ $kpi->id }}-{{ $period->id }}"
                                                    class="form-control" value="{{ $plan->plan_value }}"
                                                    id="{{ $kpi->id }}{{ $period->slug }}" type="number"
                                                    required {{ $disabled }}>
                                                 <span id="s{{ $kpi->id }}{{ $period->slug }}"></span>
                                            </td>

                                    @else
                                        <td>
                                            <input class="form-control" type="number" placeholder="Enter KPI value"
                                                id="{{ $kpi->id }}{{ $period->slug }}"
                                                name="{{ $kpi->id }}-{{ $period->id }}" required>
                                            <span id="s{{ $kpi->id }}{{ $period->slug }}"></span>
                                        </td>
                                    @endif

                                @empty
                                @endforelse
                                </tr>
                            </table>
                                    
                             <script>
                                /* function validateTheForm(){
                                        var validation = (document.getElementById('{{ $kpi->id }}{{ $period->slug }}').value == 'gmail');
                                        if(!validation){
                                            alert('Something went wrong...Plese write gmail intext box and click');
                                            return false;
                                        }
                                        return true;
                                    }*/
                                $(function() {
                                    $('input[id={{ $inputid }}]').on('change', function() {
                                        var sum = 0;
                                        var loop = {{ $last_period }};
                                        var behavior = String({{ $behavior }});
                                        var idd_y = String({{ $kpi->id }}) + String(1);
                                        var yearly = document.getElementById(idd_y).value;
                                        // addtive
                                        if (behavior == 1) {
                                            for (var i = loop; i > 1; i--) {
                                                var idd = String({{ $kpi->id }}) + String(i);
                                                var values = document.getElementById(idd).value;
                                                sum = parseFloat(sum) + parseFloat(values);
                                            }
                                            if (yearly != sum) {
                                                document.getElementById("s{{ $kpi->id }}{{ 1 }}").innerHTML =
                                                    "Period plan not matched with yearly";
                                                for (var i = loop; i > 1; i--) {
                                                    var idd = String({{ $kpi->id }}) + String(i);
                                                    $('#' + idd).val("");
                                                 }
                                                $('#' + idd_y).val("");
                                            }
                                        }
                                        // constant
                                        else if (behavior == 2) {
                                            for (var i = loop; i > 2; i--) {
                                                var idd = String({{ $kpi->id }}) + String(i);
                                                var iddd = String({{ $kpi->id }}) + String(i - 1);
                                                var values = document.getElementById(idd).value;
                                                var values2 = document.getElementById(iddd).value;
                                            }
                                            if (values != values2) {
                                                document.getElementById("s{{ $kpi->id }}{{ 1 }}").innerHTML =
                                                    "Plan should be constant";
                                                $('#' + idd_y).val("");
                                            }
                                        }
                                        // incremental
                                        else if (behavior == 3) {
                                            for (var i = loop; i > 2; i--) {
                                                var idd = String({{ $kpi->id }}) + String(i);
                                                var iddd = String({{ $kpi->id }}) + String(i - 1);
                                                var values = document.getElementById(idd).value;
                                                var values2 = document.getElementById(iddd).value;
                                                if (values < values2) {

                                                    document.getElementById("s{{ $kpi->id }}{{ 1 }}")
                                                        .innerHTML = "Plan should be incremental";
                                                    $('#' + idd_y).val("");
                                                }
                                            }
                                        }
                                        // decrimental
                                        else if (behavior == 4) {
                                            for (var i = loop; i > 2; i--) {
                                                var idd = String({{ $kpi->id }}) + String(i);
                                                var iddd = String({{ $kpi->id }}) + String(i - 1);
                                                var values = document.getElementById(idd).value;
                                                var values2 = document.getElementById(iddd).value;
                                            }
                                            if (values > values2) {
                                                document.getElementById("s{{ $kpi->id }}{{ 1 }}").innerHTML =
                                                    "Plan should be incremental";
                                                $('#' + idd_y).val("");
                                            }
                                        } else {
                                            document.getElementById("s{{ $kpi->id }}{{ 1 }}").innerHTML =
                                                "problem";
                                        }


                                    });
                                });
                            </script>
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
                                $plan_naration = getSavedPlanNaration($planning_year[0]->id, $kpi->id, auth()->user()->offices[0]->id);
                            @endphp
                            @if ($plan_naration)
                                <label for="summernote">Major Activities</label>
                                <input type="hidden" name="type" value="yes">
                                <textarea name="dx-{{ $kpi->id }}-{{ $period->id }}" style="height: 100px;"
                                    class="form-control summernote" id="summernote" placeholder="Narration here" required>{!! $plan_naration !!}</textarea>
                            @else
                                <label for="summernote">Major Activities</label>
                                <textarea name="dx-{{ $kpi->id }}-{{ $period->id }}" style="height: 100px;"
                                    class="form-control summernote" id="summernote" placeholder="Narration here" required></textarea>
                            @endif

                        </div>
                                            </div>

                    @empty
                        <h4>No KPI registered for this Goal and Objective!</h4>
                        @endforelse
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                <form action="{{ route('reply-comment.store') }}" method="POST" id="comment-form">
                    @csrf
                    <input type="hidden" id="hidden-input-view-comment" class="hidden-input-view-comment" value=""
                        name="view-commented-office-info">
                    <div class="modal-body">
                        <h5 class="view-commented-by bg-light border p-3 overflow-auto"><u>Comment: </u><p class="mw-75"></p></h5>
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
                        //     console.log(r)
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
        $('.planning-container').on('click', '.view-comment', function() {

            var id = $(this).attr('data-id');

            // AJAX request with the information attached
            var url = "{{ route('plan-comment.view-comment', [':id']) }}";
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

@endsection
