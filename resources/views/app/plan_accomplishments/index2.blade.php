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
                                  @if(!in_array($planAcc->Kpi->id, $kpi_repeat))
                                    <div class="card collapsed-card p-2">
                                        <div class="card-header">
                                            <h3 class="card-title">KPI: 
                                            @forelse($planAcc->Kpi->KeyPeformanceIndicatorTs as $kpiT)
                                                  @if(app()->getLocale() == $kpiT->locale) 
                                                        {{$kpiT->name}}
                                                         @if ($planAcc->Kpi->kpiChildOnes->isEmpty())
                                                           ({{"Plan: "}} {{$planAcc->plan_value}})
                                                           @else
                                                           <p id ="{{$planAcc->Kpi->id}}"></p>
                                                         @endif
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

                                        @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                            <table class="table table-bordered">
                                                <thead>
                                                    <!-- <tr id="child-ones"> -->
                                                    <tr>
                                                        <th rowspan="2"></th>
                                                        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                            <th colspan="{{ $one->kpiChildTwos ->count() }}" >{{ $one->kpiChildOneTranslations[0]->name }}
                                                            </th>
                                                         @endforeach
                                                    </tr>
                                                    <tr>
                                                         @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                         @if($one->kpiChildTwos ->count()> 0)
                                                              @foreach ($one->kpiChildTwos as $kpiTwo)
                                                                
                                                                <th  >{{ $kpiTwo->kpiChildTwoTranslations[0]->name }}
                                                                </th>
                                                             @endforeach
                                                             @else
                                                                    <th  >{{ "-" }}</th>
                                                                @endif
                                                        @endforeach
                                                    </tr>
                                                    {{-- get count of level 3--}}
                                                    @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                       @php $count3 =0 @endphp
                                                        @foreach ($one->kpiChildTwos as $kpiTwo)
                                                            @if($kpiTwo->kpiChildThrees->count() > $count3)
                                                                @php
                                                                 $count3 = $kpiTwo->kpiChildThrees->count();
                                                                @endphp
                                                            @endif
                                                         @endforeach
                                                    @endforeach

                                                    @for($i =1; $i<= $count3; $i++)
                                                     <tr>
                                                        @if($i==1)
                                                         <th  >Male </th>
                                                         @else
                                                          <th  >Female </th>
                                                         @endif
                                                         @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                            @if($one->kpiChildTwos ->count()> 0)
                                                              @foreach ($one->kpiChildTwos as $kpiTwo)
                                                             
                                                               <td>
                                                                @php 
                                                               $plan = $planAcc->planIndividual($planAcc->Kpi->id, $one->id, $kpiTwo->id,$i,auth()->user()->offices[0]->id);
                                                               $kpi_plan = $planAcc->planSum($planAcc->Kpi->id,auth()->user()->offices[0]->id);
                                                               $sum = 0;
                                                                foreach($kpi_plan as $kpi_pla){
                                                                   $sum = $sum+$kpi_pla->plan_value;
                                                                }
                                                                   @endphp
                                                               {{$plan[0]->plan_value}}
                                                               <script>
                                                                    var s = 
                                                                     document.getElementById('{{$planAcc->Kpi->id}}');
                                                                     if(s){
                                                                        s.innerHTML = {{$sum}};
                                                                        }
                                                                     
                                                                </script>
                                                                 </td>
                                                              @endforeach
                                                              @else
                                                                <td><input name="{{$planAcc->Kpi->id.$one->id.$i}}" class="form-control" type="number" required></td>
                                                                @endif
                                                        @endforeach
                                                    </tr>
                                                    @endfor
                                                </thead>

                                            </table>

                                            {{-- KPI has no child one, which means just only plain input --}}
                                        @else
                                            {{"Plan: "}}{{$planAcc->plan_value}}
                                        @endif
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
