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
    @php     $first=1; @endphp
    <div class="row justify-content-center mt-5">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body">
                         <form role="form" class="form-horizontal" method="get" action="{{ route('view-report-accomplishment') }}">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="label" for="filters">Offices:</label>
                                    <select class="form-control" name="office">
                                        <option value=" ">Select Office</option>
                                        @forelse(getAllOffices() as $office)
                                            <option value="{{$office->id }}">{{$office->officeTranslations[0]->name}}</option>
                                         @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class=" " for="filters">KPI:</label>
                                    <select class="form-control" name="kpi">
                                        <option value=" ">Select KPI</option>
                                       @forelse(getAllKpi() as $kpi)
                                            <option value="{{$kpi->id }}">{{$kpi->keyPeformanceIndicatorTs[0]->name}}
                                            </option>
                                         @empty
                                        @endforelse
                                    </select>

                                </div>
                                <div class="col-md-1"><br/>
                                    <button class="btn btn-primary" value="search" name="search"
                                        type="submit">Search</button>
                                 </div>
                            </div>
                        </form>
                 </div>
            </div>
        </div>
    </div>


    <div class="row justify-content-center mt-5">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        @php
                            $kpi_repeat[0] = '0';
                            $c = 1;
                            $objective_array =[];
                        @endphp
                        @forelse($planAccomplishments as $planAcc)
                            @php
                                $offices = $planAcc->getOfficeFromKpiAndOfficeList($only_child_array);
                                
                            @endphp

                            @if (!in_array($planAcc->Kpi->id, $kpi_repeat))
                                <div class="card collapsed-card p-2">
                                    <div class="card-header">
                                        @forelse($planAcc->Kpi->KeyPeformanceIndicatorTs as $kpiT)
                                            @if (app()->getLocale() == $kpiT->locale)
                                                <table class="table">
                                                    <tr style="background:#87cdc6;">
                                                        @if(!in_array($planAcc->Kpi ->objective->id,$objective_array))
                                                         <th  colspan="3" style="width:100%;"> Objective: {{ $planAcc->Kpi ->objective->objectiveTranslations[0]->name}}</th>
                                                        <th> 
                                                        @endif
                                                        @php
                                                            $objective_array = array_merge($objective_array,array($planAcc->Kpi ->objective->id));
                                                         @endphp
                                                         </tr>
                                                          <tr style="background:#21212121;">
                                                        <th style="width:5%;">  
                                                        </th>
                                                         <th style="width:75%;"> KPI: {{ $kpiT->name }}
                                                        </th>
                                                        <th> 
                                                            @php
                                                                $kpi_report =getKpiReport($planAcc->Kpi,auth()->user());
                                                            @endphp
                                                            <input name="sum" class="form-control" type="number"
                                                                value="{{ $kpi_report }}">
                                                        </th>
                                                        <th>
                                                            <button type="button" class="btn btn-tool"
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
                                    <div class="card-body" style="display: none;">
                                        {{-- If KPI has Child ones (UG, PG) --}}
                                        <x-form method="POST" action="{{ route('approve-plan') }}" class="mt-4">

                                            @forelse($offices  as $office)
                                                @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                    @include('app.report_accomplishments .view-kpi123')
                                                                    {{-- KPI has  child one and child two --}}
                                                                @else
                                                                    @include('app.report_accomplishments .view-kpi12')
                                                                @endif
                                                                {{-- KPI has  child one only --}}
                                                            @else
                                                                @include('app.report_accomplishments .view-kpi1')
                                                            @endif

                                                        </thead>
                                                    </table>
                                                    {{-- KPI has no child one, which means just only plain input --}}
                                                @else
                                                    @include('app.report_accomplishments .view-kpi')
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