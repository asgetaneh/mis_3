@extends('layouts.app')

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
    </style>
@endsection

@section('content')

    <div class="row justify-content-left mt-5">
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
                                                    aria-selected="false">{{ $obj_ts->name }}
                                                </a>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a class="nav-link active" id="{{ $obj_ts->translation_id . '-tab' }}"
                                                    data-toggle="pill" href="{{ '#objective-' . $obj_ts->translation_id }}"
                                                    role="tab" aria-controls="{{ $obj_ts->translation_id }}"
                                                    aria-selected="true">{{ $obj_ts->name }}
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

                                <form action="{{ route('plan.save') }}" method="POST">
                                    @csrf

                                    {{-- @if ($objective) --}}
                                    @php
                                        $KeyPeformanceIndicators = getKeyperormanceIndicators($objective, $user_offices);
                                    @endphp
                                    @forelse($KeyPeformanceIndicators as $kpi)
                                        <div class="card collapsed-card p-2">
                                            <div class="card-header">
                                                <h3 class="card-title">KPI:
                                                    {{ $kpi->KeyPeformanceIndicatorTs[0]->name }}
                                                    @php
                                                        $kpi_id = $kpi->id;
                                                    @endphp
                                                    (Reporting:{{ $kpi->reportingPeriodType->reportingPeriodTypeTs[0]->name }})
                                                    {{--  <strong  > ({{ $period->reportingPeriodTs[0]->name }})</strong>
                                                        <span name="{{ $kpi_id }}" id="{{$kpi_id}}{{$period->id}}"></span>

                                                          getReportingPeriod($kpi->reportingPeriodType->id,$date) --}}
                                                </h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
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
                                                                                    @endphp
                                                                                    @if ($plan)
                                                                                        <td>
                                                                                            <input type="hidden"
                                                                                                name="type"
                                                                                                value="yes">
                                                                                            <input
                                                                                                name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}-{{ $kpiThree->id }}"
                                                                                                value="{{ $plan }}"
                                                                                                class="form-control {{ $inputname }}"
                                                                                                type="number" required>


                                                                                        </td>
                                                                                    @else
                                                                                        <td>
                                                                                            <input id="selectProducts"
                                                                                                name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}-{{ $kpiThree->id }}"
                                                                                                class="form-control {{ $inputname }}"
                                                                                                type="number" required>

                                                                                        </td>
                                                                                    @endif
                                                                                    <script>
                                                                                        $(".{{ $inputname }}").keyup(function() {
                                                                                            var tot = 0;
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
                                                                            @if ($plan12 != 0)
                                                                                @php
                                                                                    $inputname = $kpi->id;
                                                                                    $period->id;
                                                                                    $one->id;
                                                                                    $two->id;
                                                                                @endphp
                                                                                <td>
                                                                                    <input type="hidden" name="type"
                                                                                        value="yes">
                                                                                    <input
                                                                                        name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}"
                                                                                        class="form-control"
                                                                                        value="{{ $plan12 }}"
                                                                                        type="number" required>
                                                                                </td>
                                                                            @else
                                                                                <td>
                                                                                    <input
                                                                                        name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}-{{ $two->id }}"
                                                                                        class="form-control" type="number"
                                                                                        required>
                                                                                </td>
                                                                            @endif
                                                                        @endforeach
                                                                </tr>
                                                            @endforeach
                                                        @empty
                                                        @endforelse
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
                                                            {{ $one->kpiChildOneTranslations[0]->name }}
                                                        </th>
                                                        @forelse(getQuarter($kpi->reportingPeriodType->id) as $period)
                                                            @php
                                                                $inputname = '{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}';
                                                                $plan1 = getSavedPlanIndividualOne($planning_year[0]->id, $kpi->id, $period->id, $one->id, auth()->user()->offices[0]->id);
                                                            @endphp
                                                            @if ($plan1)
                                                                <td>
                                                                    <input type="hidden" name="type" value="yes">
                                                                    <input
                                                                        name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}";
                                                                        class="form-control" value="{{ $plan1 }}"
                                                                        type="number" required>
                                                                </td>
                                                            @else
                                                                <td>
                                                                    <input
                                                                        name="{{ $kpi->id }}-{{ $period->id }}-{{ $one->id }}"
                                                                        class="form-control" type="number" required>
                                                                </td>
                                                            @endif
                                                        @empty
                                                        @endforelse
                                                        <script>
                                                            $(function() {
                                                                $('input[name={{ $inputname }}]').on('change', function() {
                                                                    // Get the values, turn them into numbers
                                                                    var values = $(
                                                                        'input[name={{ $inputname }}]:checked, input[name={{ $inputname }}][type=text]'
                                                                    ).map(function() {
                                                                        return +this.value;
                                                                        //     ^---- coerce to number
                                                                    }).get();
                                                                    // Sum them up
                                                                    var sum = values.reduce(function(a, b) {
                                                                        return a + b;
                                                                    });
                                                                    // Show the result
                                                                    $('#{{ $kpi_id }}').text(sum);
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
                                            $inputname = '{{ $kpi->id }}-{{ $period->id }}';
                                            $plan = getSavedPlanIndividual($planning_year[0]->id, $kpi->id, $period->id, auth()->user()->offices[0]->id);
                                        @endphp
                                        @if ($plan)
                                            <td>
                                                <input type="hidden" name="type" value="yes">
                                                <input name="{{ $kpi->id }}-{{ $period->id }}"
                                                    class="form-control" value="{{ $plan }}" type="number"
                                                    required>
                                            </td>
                                        @else
                                            <td>
                                                <input class="form-control" type="number" placeholder="Enter KPI value"
                                                    name="{{ $kpi->id }}-{{ $period->id }}" required>
                                            </td>
                                        @endif
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
                                $plan_naration = getSavedPlanNaration($planning_year[0]->id, $period->id, $kpi->id, auth()->user()->offices[0]->id);
                            @endphp
                            @if ($plan_naration)
                                <label for="summernote">Major Activities</label>
                                <input type="hidden" name="type" value="yes">
                                <textarea name="dx-{{ $kpi->id }}-{{ $period->id }}" style="height: 100px;" class="form-control summernote"
                                    id="summernote" placeholder="Narration here" required>{!! $plan_naration !!}</textarea>
                            @else
                                <label for="summernote">Major Activities</label>
                                <textarea name="dx-{{ $kpi->id }}-{{ $period->id }}" style="height: 100px;" class="form-control summernote"
                                    id="summernote" placeholder="Narration here" required></textarea>
                            @endif

                        </div>

                    @empty
                        <h4>No KPI registered for this Goal and Objective!</h4>
                        @endforelse
                        <button type="submit" class="btn btn-primary">Submit</button>

                        </form>
                    </div>
                </div>

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

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> --}}
    {{-- <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 150
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

@endsection
