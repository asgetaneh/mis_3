<table class="table table-bordered" style ="background:#F0F8FF">
    @if ($first == '1')
        <tr>
            <th style="width:25%">
                Offices
            </th>
            @php
                // Get the total count of reportingPeriodTs across all periods
                $totalPeriods = collect($getQuarter)->sum(fn($p) => count($p->reportingPeriodTs));
            @endphp
            @forelse($getQuarter as $period)
                @foreach($period->reportingPeriodTs as $report)
                    <th style="width: {{ 60 / max(1, $totalPeriods) }}%;">
                        {{ $report->name }}
                    </th>
                @endforeach
            @empty
                <th>No data available</th>
            @endforelse
            <td rowspan="2"style="padding:10px">
            @if(!$officeOffices->isEmpty())
            <p>
                <a class="btn btn-info" data-toggle="collapse"
                    href="#off{{ $office->id }}{{$planAccKpi->id}}" role="button"
                    aria-expanded="false" aria-controls="collapseExample0">
                     Details
                </a>
            </p>
             @else
                {{ "" }}
            @endif
        </td>
        </tr>
    @endif
    <tr>
        <td rowspan="1">{{ $office->officeTranslations[0]->name }}</td>
        @forelse($getQuarter as $period)
            @php
                //$planOfOfficePlan = $planAcc->planSum($planAccKpi->id, $office, $period->id,true,$planning_year);
                $display = 0;
                $activeQuarter = $getReportingQuarter;
                 $planOfOfficePlan =$planAcc->KpiOTT($planAccKpi->id, $office, $period->id,true,$planning_year->id ?? NULL ,null,null,null);
                $narration = $planAcc->getReportNarration($planAccKpi->id, $planning_year->id ?? NULL, $office, $period->id);
               //dump($planOfOfficePlan[2]->accom_status);
                if(auth()->user()->offices[0]->level == $planOfOfficePlan[2]->accom_status){
                    $display = $planOfOfficePlan[1];
                }
            @endphp
            @if($activeQuarter)
                @if($period->id!= $activeQuarter[0]?->id)
                <td>
                    {{ $display }}
                </td>
                @else
                <td style="background-color:#99cd99;">
                  <span >  {{ $display }}</span>
                </td>
                @endif
            @else
                <td style="background-color:red;">
                  <span >  {{ " Period not set" }}</span>
                </td>
            @endif
         @empty
        @endforelse
    </tr>
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="{{ $getQuarter->count() }}">
             @foreach ($narration as $key => $plannaration)
                <p>
                    {!! html_entity_decode($plannaration->report_naration) !!}
                </p>
            @endforeach
        </td>
    </tr>
</table>
{{-- level two (directores and same level) --}}
<div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
    <div class="card card-body" style="background:#12cd4322;" >
        @php
            $offices_twos = $officeOffices;
        @endphp
        @forelse ($offices_twos as $office)
            @include('app.report_accomplishments.view-kpi-duplicate')
            <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                <div class="card card-body">
                    @php
                        $offices_threes = $office->offices;
                    @endphp
                    @forelse ($offices_threes as $office)
                        @include('app.report_accomplishments.view-kpi-duplicate')
                        <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                            <div class="card card-body">
                                @php
                                    $offices_fours = $office->offices;
                                @endphp
                                @forelse ($offices_fours as $office)
                                    @include('app.report_accomplishments.view-kpi-duplicate')
                                    <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                        <div class="card card-body">
                                            @php
                                                $offices_fives = $office->offices;
                                            @endphp
                                            @forelse ($offices_fives as $office)
                                                @include('app.report_accomplishments.view-kpi-duplicate')
                                                <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                                    <div class="card card-body">
                                                        @php
                                                            $offices_sixs = $office->offices;
                                                        @endphp
                                                        @forelse ($offices_sixs as $office)
                                                            @include('app.report_accomplishments.view-kpi-duplicate')
                                                            <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                                                <div class="card card-body">

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
