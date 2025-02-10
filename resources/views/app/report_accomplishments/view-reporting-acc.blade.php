@extends('layouts.app')
@section('title', 'View Report')

@section('style')
    <style>
        tr,
        th,
        td {
            padding: 10px;
            border: 1px solid #289CD8;
        }

        table {
            border-collapse: collapse;
            border: 1px solid #289CD8;
        }
    </style>
@endsection

@section('content')
    @php     $first=1; @endphp
    <div class="justify-content-center">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body">
                    <form role="form" class="form-horizontal" method="get"
                        action="{{ route('view-report-accomplishment') }}">
                        <div class="row">
                            <div class="col-md-4">

                                {{-- If admin put all offices --}}
                                @if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin'))
                                    <label class="label" for="filters">Offices:</label>
                                    <select class="form-control select2" name="office">
                                        <option disabled {{ old('office') == "" ? 'selected' : '' }} value="">Select Office</option>
                                        {{-- <option value="">ALL OFFICES</option> --}}
                                        @forelse(getAllOffices() as $office)
                                            @if ($office->id == 1)
                                                @continue
                                            @endif
                                            <option {{ old('office') == $office->id ? 'selected' : '' }} value="{{ $office->id }}">{{ $office->officeTranslations[0]->name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                @else
                                    {{-- If normal office, check and only avail the belonged offices --}}
                                    <label class="label" for="filters">Offices:</label>
                                    <select class="form-control select2" name="office">
                                        <option disabled {{ old('office') == "" ? 'selected' : '' }} value="">Select Office</option>
                                        {{-- <option value="">ALL OFFICES</option> --}}
                                        @forelse(getAllOffices() as $office)
                                            @if ($office->parent_office_id == auth()->user()->offices[0]->id || $office->id == auth()->user()->offices[0]->id)
                                                <option {{ old('office') == $office->id ? 'selected' : '' }} value="{{ $office->id }}">{{ $office->officeTranslations[0]->name }}
                                                </option>
                                            @endif
                                        @empty
                                        @endforelse
                                    </select>
                                @endif

                            </div>
                            <div class="col-md-4">
                                <label class=" " for="filters">KPI:</label>
                                <select class="form-control select2" name="kpi">
                                    <option disabled {{ old('kpi') == "" ? 'selected' : '' }} value="">Select KPI</option>
                                    {{-- <option value="">ALL KPI</option> --}}
                                    @forelse(getAllKpi() as $kpi)
                                        <option {{ old('kpi') == $kpi->id ? 'selected' : '' }} value="{{ $kpi->id }}">{{ $kpi->keyPeformanceIndicatorTs[0]->name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>

                            </div>
                            <div class="col-md-4">
                                <label class="label" for="action">Action</label>
                                <div id="action">
                                    <button class="btn btn-primary" value="search" name="search" type="submit">Filter</button>
                                    <span class="border border-right mx-3"></span>
                                    <button class="btn btn-info" value="pdf" name="pdf" type="submit">PDF</button>
                                    {{-- <button class="btn btn-success" value="excel" name="excel" type="submit">Excel</button> --}}
                                    {{-- <button class="btn btn-primary" value="word" name="word" type="submit">Word</button> --}}

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>




    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        @php
                            $kpi_repeat[0] = null;
                            $c = 1;
                            $objective_array = [];
                        @endphp
                        @forelse($planAccomplishments as $planAcc)
                            @php
                                $offices = $planAcc->getOfficeFromKpiAndOfficeList($only_child_array,$off_level);

                            @endphp

                            @if (!in_array($planAcc->Kpi->id, $kpi_repeat))
                                <div class="card collapsed-card p-2">
                                    <div class="card-header">
                                        @forelse($planAcc->Kpi->KeyPeformanceIndicatorTs as $kpiT)
                                            @if (app()->getLocale() == $kpiT->locale)
                                                <table class="table">
                                                    <tr style="background:#87cdc6;">
                                                        @if (!in_array($planAcc->Kpi->objective->id, $objective_array))
                                                             <th colspan="8" style="width:100%;"> Objective:
                                                                {{ $planAcc->Kpi->objective->objectiveTranslations[0]->name }}
                                                            </th>
                                                           {{-- @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                                                <th> {{ $period->reportingPeriodTs[0]->name }}
                                                                 </th>
                                                            @empty
                                                            @endforelse --}}
                                                             {{-- <th>   </th> --}}
                                                         @endif
                                                        @php
                                                            $objective_array = array_merge($objective_array, [$planAcc->Kpi->objective->id]);
                                                        @endphp
                                                    </tr>
                                                    <tr style="background:#21212121;">
                                                        <th  rowspan="2"></th>
                                                         <td  rowspan="2" style="width:30%">
                                                            KPI:   {{$kpiT->name }}
                                                             @if ($kpiT->keyPeformanceIndicator ->measurement)
                                                               <b> {{"( in "}}{{$kpiT->keyPeformanceIndicator ->measurement['slug'] }} {{")" }}</b>
                                                             @endif
                                                        </td>
                                                         @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                                            <th> {{ $period->reportingPeriodTs[0]->name }} </th>
                                                        @empty
                                                        @endforelse
                                                        <th> Action  </th>
                                                    </tr>
                                                    <tr>
                                                        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                                            @php
                                                                $one =null;
                                                                $three =null;
                                                                $two =null; //dump($planAcc);
                                                                $planOfOfficePlan = $planAcc->ForKpi($planAcc->Kpi->id, $imagen_off, $period->id,true,$planning_year->id ?? NULL,$planAcc->kpi_child_one_id ,$planAcc->kpi_child_two_id ,$planAcc->kpi_child_three_id);//dump($planOfOfficePlan);
                                                                $narration = $planAcc->getReportNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $imagen_off, $period->id);
                                                                 $activeQuarter = getReportingQuarter($planAcc->Kpi->reportingPeriodType->id);

                                                            @endphp
                                                             @forelse($activeQuarter as $aQ)
                                                             @if($period->id!= $aQ->id)
                                                            <td>
                                                                {{ $planOfOfficePlan[1] }}
                                                            </td>
                                                            @else
                                                            <td style="background:#99cd99;">
                                                            <span >  {{ $planOfOfficePlan[1] }}</span>
                                                            </td>
                                                            @endif
                                                            @empty
                                                            @endforelse
                                                        @empty
                                                        @endforelse

                                                        <th>
                                                            <button type="button" title="Expand"
                                                                class="btn btn-flat btn-tool bg-primary m-auto py-2 px-4"
                                                                data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                         <th></th>

                                                        <td colspan="7">
                                                         <h6>
                                                            Major Activities
                                                        </h6>
                                                            @foreach ($narration as $key => $plannaration)
                                                                <p>
                                                                    {!! html_entity_decode($plannaration->plan_naration) !!}
                                                                </p>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                        @empty
                                            <h4>No KPI name!</h4>
                                        @endforelse
                                    </div>
                                    <div class="card-body" style="display: none;">
                                        {{-- If KPI has Child ones (UG, PG) --}}
                                        <x-form method="POST" action="{{ route('approve-plan') }}" class="mt-4">

                                            @forelse($offices  as $office)
                                                @php
                                                    $isOfficeBelongToKpi = isOfficeBelongToKpi($office, $planAcc->Kpi->id);
                                                @endphp

                                                @if ($isOfficeBelongToKpi->count() > 0)
                                                    @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                        {{-- <table class="table table-bordered">
                                                            <thead> --}}
                                                                @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                    @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                        @include('app.report_accomplishments.view-kpi123')
                                                                        {{-- KPI has  child one and child two --}}
                                                                    @else
                                                                        @include('app.report_accomplishments.view-kpi12')
                                                                    @endif
                                                                    {{-- KPI has  child one only --}}
                                                                @else
                                                                    @include('app.report_accomplishments.view-kpi1')
                                                                @endif

                                                            {{-- </thead>
                                                        </table> --}}
                                                        {{-- KPI has no child one, which means just only plain input --}}
                                                    @else
                                                        @include('app.report_accomplishments.view-kpi')
                                                    @endif
                                                @endif
                                            @empty
                                                <h4>No offices!</h4>
                                            @endforelse
                                            <tr>
                                                {{-- <td colspan="8">
                                            <button type="submit" class="btn-primary float-right">Appove</button>
                                        </td> --}}
                                            </tr>
                                        </x-form>
                                    </div>
                                </div>

                                @php
                                    $kpi_repeat[$c] = $planAcc->Kpi->id;
                                    $c++;

                                @endphp
                            @endif
                        @empty
                            {{-- <p>ugyftrdy</p> --}}
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.select2').select2();

        });
    </script>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}

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
    </script>

@endsection
