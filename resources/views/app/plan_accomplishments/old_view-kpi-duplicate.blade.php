<table class="table table-bordered">
    <tr>
        <th style="width:20%">
            Offices
        </th>
        <th  rowspan="">{{"Baseline"}}</th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th> {{ $period->reportingPeriodTs[0]->name }} </th>
        @empty
        @endforelse
        <td rowspan="3">
            @if (!$office->offices->isEmpty())
                <p>
                    <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}{{$planAcc->Kpi->id}}" role="button"
                        aria-expanded="false" aria-controls="collapseExample2">
                        Details
                    </a>
                </p>
            @else
                {{ 'no child ' }}
            @endif
        </td>
    </tr>
    <tr>
        <td rowspan="2" style="width:20%">{{ $office->officeTranslations[0]->name }}</td>
        @php 
            $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id, $office, $planning_year->id, $period->id,null,null,null);
        @endphp
        <td>{{ $baselineOfOfficePlan }}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
              //  $planOfOfficePlan = $planAcc->planSum($planAcc->Kpi->id, $office, $period->id,false,$planning_year);
                $planOfOfficePlan = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,null,null,null);//dump($planOfOfficePlan[0]);
                $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
                 $office_level = $office->level;
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
        <td colspan="4">
            @foreach ($narration as $key => $plannaration)
                <p>
                    {!! html_entity_decode($plannaration->plan_naration) !!}
                </p>
            @endforeach
        </td>
    </tr>
    <tr>
    </table>
