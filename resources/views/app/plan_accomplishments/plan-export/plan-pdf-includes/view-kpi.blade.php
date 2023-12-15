<table border="1" class="table" style="border-collapse: collapse; margin-bottom: 15px; border: 1px solid #000;">
    @if ($first == '1')
        <tr>
            {{-- <th>
                Offices
            </th> --}}
            <th>Baseline</th>
            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th>
             {{ $period->reportingPeriodTs[0]->name }}
            </th>
            @empty
            @endforelse

        </tr>
    @endif
        @php
            $baselineLastYear = getBaselineLastYear($planAcc->Kpi->id, $planning_year->id ?? NULL, 1, auth()->user()->offices[0]->id);
        @endphp
    <tr>
        {{-- <td rowspan="2">{{ $office->officeTranslations[0]->name }}</td> --}}

        {{-- baseline --}}
        @if (!empty($baselineLastYear))
            <td>
                {{ $baselineLastYear }}
            </td>
        @else
            <td>
                -
            </td>
        @endif
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                //$planOfOfficePlan = $planAcc->planSum($planAcc->Kpi->id, $office, $period->id,false,$planning_year);
                $planOfOfficePlan =$planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,null,null,null);
                //dump($planOfOfficePlan[0]);
                $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
            @endphp
            <td>
                {{ $planOfOfficePlan[0] }}
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
                {{-- <p> --}}
                    {!! $plannaration->plan_naration !!}
                {{-- </p> --}}
            @endforeach
        </td>
    </tr>
</table>
