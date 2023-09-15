<table class="table table-bordered">
    <tr>
        <th>
            Offices
        </th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th> {{ $period->reportingPeriodTs[0]->name }} </th>
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
    <tr>
        <td rowspan="2">{{ $office->officeTranslations[0]->name }}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
               // $planOfOfficePlan = $planAcc->planSum($planAcc->Kpi->id, $office, $period->id,true,$planning_year);
                $planOfOfficePlan =$planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,true,$planning_year[0]->id ,null,null,null);

                $narration = $planAcc->getReportNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
            @endphp
            <td>
                {{ $planOfOfficePlan[1] }}
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
