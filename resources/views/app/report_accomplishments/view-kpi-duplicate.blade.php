<table class="table table-bordered">
    <tr>
        <th style="width:30%">
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
                {{ "" }}
            @endif
        </td>
    </tr>
    <tr>
        <td rowspan="1">{{ $office->officeTranslations[0]->name }}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
               // $planOfOfficePlan = $planAcc->planSum($planAcc->Kpi->id, $office, $period->id,true,$planning_year);
                $planOfOfficePlan =$planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,true,$planning_year->id ?? NULL ,null,null,null);

                $narration = $planAcc->getReportNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
                $activeQuarter = getReportingQuarter($planAcc->Kpi->reportingPeriodType->id);
            @endphp
            @forelse($activeQuarter as $aQ)
            @if($period->id!= $aQ->id)
            <td>
                {{ $planOfOfficePlan[1] }}
            </td>
            @else
            <td style="background:#99cd99;">
                <span> {{ $planOfOfficePlan[1] }}</span>
            </td>
            @endif
            @empty
            @endforelse
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
    <tr>
    </table>
