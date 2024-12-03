<table class="table table-bordered" style ="background:#F0F8FF">
    @if ($first == '1')
        <tr>
            <th style="width:30%">
                Offices
            </th>
            <th  rowspan="">{{"Baseline"}}</th>
            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th>
             {{ $period->reportingPeriodTs[0]->name }}
            </th>
            @empty
            @endforelse
             <td rowspan="3">
            @if(!$office->offices->isEmpty())
            <p>
                <a class="btn btn-info" data-toggle="collapse"
                    href="#off{{ $office->id }}{{$planAcc->Kpi->id}}" role="button"
                    aria-expanded="false" aria-controls="collapseExample0">
                     Details
                </a>
            </p>
             @else
                {{"no child "}}
            @endif
        </td>
        </tr>
    @endif
    <tr>
        <td rowspan="2" style="width:30%">{{ $office->officeTranslations[0]->name }}</td>
        @php 
            $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$office, $planning_year->id, $period->id,null,null,null);
         @endphp
        <td>{{ $baselineOfOfficePlan }}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                //$planOfOfficePlan = $planAcc->planSum($planAcc->Kpi->id, $office, $period->id,false,$planning_year);
                $planOfOfficePlan =$planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,null,null,null);
                //dump($planOfOfficePlan[0]);
                $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
                $userOffice =auth()->user()->offices[0];
                $office_level = $userOffice->level;
                if($office_level == 0) $office_level=1;
            @endphp
            <td>
                @if($planOfOfficePlan[2] <= $office_level)
                     {{ $planOfOfficePlan[0] }} 
                @else
                    {{0}}
                @endif
                <!-- {{ $planOfOfficePlan[0] }} -->
            </td>
        @empty
        @endforelse
    </tr>
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="5">
             @foreach ($narration as $key => $plannaration)
                <p>
                    {!! html_entity_decode($plannaration->plan_naration) !!}
                </p>
            @endforeach
        </td>
    </tr>
</table>
{{-- level two (directores and same level) --}}
<div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
    <div class="card card-body" style="background:#12cd4322; padding: 70px; border: 1px solid;">
       
        @php
            $offices_twos = $office->offices;
            $office_one_self = $office;
         @endphp
        @forelse ($offices_twos as $office)
            @include('app.plan_accomplishments.view-kpi-duplicate')
            <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                <div class="card card-body" style="background:#D3D3D3; padding: 70px; border: 1px solid;">
                    @php
                        $offices_threes = $office->offices;
                        $office_two_self = $office;
                    @endphp
                    @forelse ($offices_threes as $office)
                        @include('app.plan_accomplishments.view-kpi-duplicate')
                        <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                            <div class="card card-body" style="background:# ; padding: 70px; border: 1px solid;">
                                @php
                                    $offices_fours = $office->offices;
                                    $office_three_self = $office;
                                @endphp
                                @forelse ($offices_fours as $office)
                                    @include('app.plan_accomplishments.view-kpi-duplicate')
                                    <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                        <div class="card card-body" style="background:#D3D3D3; padding: 70px; border: 1px solid;">
                                            @php
                                                $offices_fives = $office->offices;
                                            @endphp
                                            @forelse ($offices_fives as $office)
                                                @include('app.plan_accomplishments.view-kpi-duplicate')
                                                <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                                    <div class="card card-body" style="background:# ; padding: 70px; border: 1px solid;">
                                                        @php
                                                            $offices_sixs = $office->offices;
                                                        @endphp
                                                        @forelse ($offices_sixs as $office)
                                                            @include('app.plan_accomplishments.view-kpi-duplicate')
                                                            <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                                                <div class="card card-body" style="background:#D3D3D3; padding: 70px; border: 1px solid;">

                                                                </div>
                                                            </div>
                                                        @empty
                                                            <h4>on child!</h4>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            @empty
                                                <h4>on child!</h4>
                                            @endforelse
                                        </div>
                                    </div>
                                @empty
                                    <h4>on child!</h4>
                                @endforelse
                                 <!-- table to display leader(director) office plan in the level-->
                                <table class="table table-bordered">
                                    <tr>
                                        <th>
                                            Offices
                                        </th>
                                        <th  rowspan="">{{"Baseline"}}</th>
                                        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                            <th> {{ $period->reportingPeriodTs[0]->name }} </th>
                                        @empty
                                        @endforelse
                                    </tr>
                                    <tr>
                                          <td rowspan="4">{{ $office_three_self->officeTranslations[0]->name }}</td>
                                    </tr>
                                    @php 
                                        $baselineOfOfficePlan  = OnlyKpiOttBaseline($planAcc->Kpi->id,$office_three_self, $planning_year->id, $period->id,null,null,null);
                                    @endphp
                                    <td>{{ $baselineOfOfficePlan }}</td>
                                         @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                        @php
                                            $planOfOfficePlan = $planAcc->OnlyKpiOTT($planAcc->Kpi->id, $office_three_self, $period->id,false,$planning_year->id ?? NULL ,null,null,null);//dump($planOfOfficePlan[0]);
                                            $narration = $planAcc->OnlygetNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office_three_self, $period->id);
                                            $userOffice =auth()->user()->offices[0];
                                            $office_level = $userOffice->level;
                                            if($office_level == 0) $office_level=1;
                                        @endphp 
                                        <td>
                                             @if($planOfOfficePlan[2] <= $office_level)
                                                 {{ $planOfOfficePlan[0] }} 
                                            @else
                                                {{0}}
                                            @endif
                                        </td>
                                    @empty
                                    @endforelse
                                </tr>
                                <tr>
                                    <td rowspan="2">
                                        Major Activities
                                    </td>
                                    <td colspan="5">
                                        @foreach ($narration as $key => $plannaration)
                                            <p>
                                                {!! html_entity_decode($plannaration->plan_naration) !!}
                                            </p>
                                        @endforeach
                                    </td>
                                </tr>
                                </table>
                                <!-- end table to display leader(director) office plan in the level-->
                            </div>
                        </div>
                    @empty
                        <h4>on child!</h4>
                    @endforelse
                    
                     <!-- table to display leader(director) office plan in the level-->
                    <table class="table table-bordered">
                        <tr>
                            <th>
                                Offices
                            </th>
                            <th  rowspan="">{{"Baseline"}}</th>
                            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                <th> {{ $period->reportingPeriodTs[0]->name }} </th>
                            @empty
                            @endforelse
                        </tr>
                        <tr>
                              <td rowspan="4">{{ $office_two_self->officeTranslations[0]->name }}</td>
                              @php 
                                    $baselineOfOfficePlan  = OnlyKpiOttBaseline($planAcc->Kpi->id,$office_two_self, $planning_year->id, $period->id,null,null,null);
                              @endphp
                              <td>{{ $baselineOfOfficePlan }}</td>
                              @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                            @php
                                $planOfOfficePlan = $planAcc->OnlyKpiOTT($planAcc->Kpi->id, $office_two_self, $period->id,false,$planning_year->id ?? NULL ,null,null,null);//dump($planOfOfficePlan[0]);
                                $narration = $planAcc->OnlygetNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office_two_self, $period->id);
                                $userOffice =auth()->user()->offices[0];
                                $office_level = $userOffice->level;
                                if($office_level == 0) $office_level=1;
                            @endphp 
                            <td>
                                 @if($planOfOfficePlan[2] <= $office_level)
                                     {{ $planOfOfficePlan[0] }} 
                                @else
                                    {{0}}
                                @endif
                            </td>
                        @empty
                        @endforelse
                    </tr>
                    <tr>
                        <td rowspan="2">
                            Major Activities
                        </td>
                        <td colspan="5">
                            @foreach ($narration as $key => $plannaration)
                                <p>
                                    {!! html_entity_decode($plannaration->plan_naration) !!}
                                </p>
                            @endforeach
                        </td>
                    </tr>
                    </table>
                    <!-- end table to display leader(director) office plan in the level-->
                </div>
            </div>
        @empty
            <h4>on child!</h4>
        @endforelse
        <!-- table to display leader(director) office plan in the level-->
        <table class="table table-bordered">
            <tr>
                <th>
                    Offices
                </th>
                <th  rowspan="">{{"Baseline"}}</th>
                @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                    <th> {{ $period->reportingPeriodTs[0]->name }} </th>
                @empty
                @endforelse
            </tr>
            <tr>
                  <td rowspan="4">{{ $office_one_self->officeTranslations[0]->name }}</td>
                    @php 
                                                 
                        $baselineOfOfficePlan  = OnlyKpiOttBaseline($planAcc->Kpi->id,$office_one_self, $planning_year->id, $period->id,null,null,null);
                    @endphp 
                <td>{{ $baselineOfOfficePlan }}</td>
                 @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                @php
                    $planOfOfficePlan = $planAcc->OnlyKpiOTT($planAcc->Kpi->id, $office_one_self, $period->id,false,$planning_year->id ?? NULL ,null,null,null);//dump($planOfOfficePlan[0]);
                    $narration = $planAcc->OnlygetNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office_one_self, $period->id);
                     $office_level = $office->level;
                    if($office_level == 0) $office_level=1;
                @endphp 
                <td>
                     @if($planOfOfficePlan[2] <= $office_level)
                         {{ $planOfOfficePlan[0] }} 
                    @else
                        {{0}}
                    @endif
                </td>
            @empty
            @endforelse
        </tr>
        <tr>
            <td rowspan="2">
                Major Activities
            </td>
            <td colspan="5">
                @foreach ($narration as $key => $plannaration)
                    <p>
                        {!! html_entity_decode($plannaration->plan_naration) !!}
                    </p>
                @endforeach
            </td>
        </tr>
        </table>
          
    </div>
</div>
 