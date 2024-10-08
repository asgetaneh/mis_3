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
                            @php $kpi_repeat[0] ='0'; $c = 1; @endphp
                            @forelse($planAccomplishments as $planAcc)  
                                 @php
                               $offices = $planAcc->getOfficeFromKpiAndOfficeList($planAcc->Kpi,$all_office_list);
                                @endphp  
                                  @if(!in_array($planAcc->Kpi->id, $kpi_repeat))
                                    <div class="card collapsed-card p-2">
                                        <div class="card-header">
                                            <h3 class="card-title" > 
                                            @forelse($planAcc->Kpi->KeyPeformanceIndicatorTs as $kpiT)
                                                  @if(app()->getLocale() == $kpiT->locale) 
                                                    <div class ="row" style ="background:#CDCDCDCD;width:100%">
                                                    <div class ="col-md-6">
                                                        KPI: {{$kpiT->name}}
                                                    </div>
                                                    <div class ="col-md-6">
                                                        <input name="sum" class="form-control"  type="number" value="{{$planAcc->sum}}"> 
                                                    </div>
                                                    </div>
                                                    
                                                @endif
                                            @empty
                                                <h4>No KPI name!</h4>
                                            @endforelse
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body" style="display: none;">
                                        {{-- If KPI has Child ones (UG, PG) --}}
                                         @forelse($offices  as $office)
                                        @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                             <table class="table table-bordered">
                                             <thead>
                                            @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                        <!-- <tr id="child-ones"> -->
                                                        <tr>
                                                            <th colspan="{{ $planAcc->Kpi->kpiChildThrees ->count() }}*{{ $planAcc->Kpi->kpiChildOnes ->count()}} ">
                                                            <u>
                                                            Offices: {{$office->officeTranslations[0]->name}}
                                                            </u>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2">#</th>
                                                            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                                <th colspan="{{ $planAcc->Kpi->kpiChildThrees ->count() }}" >{{ $one->kpiChildOneTranslations[0]->name }}
                                                                </th>
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                                @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                                                                    <th  >{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
                                                                    </th>
                                                                @endforeach
                                                            @endforeach
                                                        </tr>
                                                        
                                                            @foreach ($planAcc->Kpi->kpiChildTwos as $two)
                                                            <tr>
                                                                    <th>
                                                                    {{ $two->kpiChildTwoTranslations[0]->name }}
                                                                    </th>
                                                                    @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                                    @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                                                                    <td>
                                                                    @php 
                                                                    $plan123 = $planAcc->planIndividual($planAcc->Kpi->id, $one->id, $two->id,$kpiThree->id,$office->id,true);
                                                                    @endphp
                                                                    {{$plan123}}
                                                                    </td>
                                                                @endforeach
                                                            @endforeach
                                                            </tr>
                                                            @endforeach  
                                                  {{-- KPI has  child one and child two --}}
                                                @else
                                                     <tr>
                                                     <tr>
                                                        <th colspan="{{ $planAcc->Kpi->kpiChildOnes ->count()}} ">
                                                        <u>
                                                        Offices: {{$office->officeTranslations[0]->name}}
                                                        </u>
                                                        </th>
                                                    </tr>
                                                      <th>#</th>
                                                         @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                        <th>
                                                        {{ $one->kpiChildOneTranslations[0]->name }}
                                                        </th>
                                                        @endforeach
                                                    </tr>
                                                   
                                                        @foreach ($planAcc->Kpi->kpiChildTwos as $two)
                                                         <tr>
                                                        <th>
                                                        {{ $two->kpiChildTwoTranslations[0]->name }}
                                                        </th>
                                                            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                                <td> 
                                                                @php 
                                                                    $planOneTwo
                                                                     = $planAcc->planOneTwo($planAcc->Kpi->id, $one->id, $two->id,$office->id,true);
                                                                    @endphp
                                                                    {{$planOneTwo}}
                                                            </td>
                                                          @endforeach
                                                    </tr>
                                                     @endforeach
                                                @endif
                                            {{-- KPI has  child one only --}}
                                            @else
                                                <tr>
                                                    <th colspan="{{ $planAcc->Kpi->kpiChildOnes ->count()}} ">
                                                    <u>
                                                    Offices: {{$office->officeTranslations[0]->name}}
                                                    </u>
                                                    </th>
                                                </tr>
                                                 <tr>
                                                     @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                    <th>
                                                    {{ $one->kpiChildOneTranslations[0]->name }}
                                                    </th>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                     @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                    <td>
                                                      @php 
                                                        $planOne
                                                            = $planAcc->planOne($planAcc->Kpi->id, $one->id,$office->id, true);
                                                        @endphp
                                                        {{$planOne}}
                                                    </td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        </thead>
                                        </table>
                                        {{-- KPI has no child one, which means just only plain input --}}
                                        @else
                                            <p>
                                            <table class="table table bordered">
                                                <tr>
                                                <th>Offices: {{$office->officeTranslations[0]->name}}
                                                </th>
                                                @php 
                                                    $planOfOfficePlan
                                                        = $planAcc->planSum($planAcc->Kpi->id,$office->id,true,$planning_year);
                                                    @endphp
                                                <th> <input name="sum"    type="number" value="{{$planOfOfficePlan}}"> 
                                                </th>
                                            </tr>
                                            </table>  
                                            </p>
                                        @endif
                                        @empty
                                            <h4>No offices!</h4>
                                        @endforelse
                                    </div>
                                    </div>
                                
                                @php  
                                    $kpi_repeat[$c] =$planAcc->Kpi->id; 
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
        function expandAll(){

        }
    </script>

@endsection
