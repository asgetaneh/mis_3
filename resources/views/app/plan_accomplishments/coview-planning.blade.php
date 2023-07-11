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

    <div class="row justify-content-center mt-5">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        @php
                            $kpi_repeat[0] = '0';
                            $c = 1;
                        @endphp
                        @forelse($planAccomplishments as $planAcc)
                            @php
                                $offices = $planAcc->getOfficeFromKpiAndOfficeList($planAcc->Kpi, $only_child_array);
                            @endphp
                            @if (!in_array($planAcc->Kpi->id, $kpi_repeat))
                                <div class="card collapsed-card p-2">
                                    <div class="card-header">
                                        @forelse($planAcc->Kpi->KeyPeformanceIndicatorTs as $kpiT)
                                            @if (app()->getLocale() == $kpiT->locale)
                                                <table class="table">
                                                    <tr style="background:#CDCDCD;">
                                                        <th style="width:80%;"> KPI: {{ $kpiT->name }}</th>
                                                        <th> <input name="sum" class="form-control" type="number"
                                                                value="{{ $planAcc->sum }}">
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

                                    @forelse($offices  as $office)
                                        @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                            <table class="table table-bordered">
                                                <thead>
                                                    @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                        @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                            @include('app.plan_accomplishments.kpi123')
                                                            {{-- KPI has  child one and child two --}}
                                                        @else
                                                            @include('app.plan_accomplishments.kpi12')
                                                        @endif
                                                        {{-- KPI has  child one only --}}
                                                    @else
                                                        @include('app.plan_accomplishments.kpi1')
                                                    @endif

                                                </thead>
                                            </table>
                                            {{-- KPI has no child one, which means just only plain input --}}
                                        @else
                                            @include('app.plan_accomplishments.kpi')
                                        @endif
                                    @empty
                                        <h4>No offices!</h4>
                                    @endforelse
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
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
    </script>

@endsection
