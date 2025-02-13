<table class="table table-bordered" >
<tr>
<tr>
    <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 3 }} ">
        Offices: {{ $office->officeTranslations[0]->name }}
    </th>
     <td rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() *count(getQuarter($planAcc->Kpi->reportingPeriodType->id))+3}}">
        @if (!$office->offices->isEmpty())
            <p>
                <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}{{$planAcc->Kpi->id}}" role="button"
                    aria-expanded="false" aria-controls="collapseExample0">
                    Details
                </a>
            </p>
        @else
            {{ '' }}
        @endif
    </td>
</tr>
{{-- <td colspan="2  ">#</td>
@foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <td>
        {{ $one->kpiChildOneTranslations[0]->name }}
    </td>
@endforeach --}}
{{-- <th>Sum</th> --}}
</tr>

<tr>
    <th colspan="2">#</th>
    <th colspan="">Baseline</th>
    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
    <th>
            {{ $period->reportingPeriodTs[0]->name }}
        </th>
    @empty
    @endforelse
</tr>


@forelse ($planAcc->Kpi->kpiChildOnes as $one)

<tr>
    <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
        {{ $one->kpiChildOneTranslations[0]->name }}
    </th>
    @foreach ($planAcc->Kpi->kpiChildTwos as $two)
        <th>
            {{ $two->kpiChildTwoTranslations[0]->name }}
        </th>
        @php
            $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$office, $planning_year->id, $period->id,$one->id,$two->id,null);
        @endphp
        <td>{{ $baselineOfOfficePlan }}</td>
@forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
    @php
        $childAndHimOffKpi_array = [];
        $childAndHimOffKpi = $office->offices;
        foreach ($childAndHimOffKpi as $key => $value) {
            $childAndHimOffKpi_array[$key] = $value->id;
        }
        $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
       // $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
        $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
    @endphp
    {{-- <tr>
        <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
            {{ $period->reportingPeriodTs[0]->name }}
        </th>
        @foreach ($planAcc->Kpi->kpiChildTwos as $two)
            <td>
                {{ $two->kpiChildTwoTranslations[0]->name }}
            </td>
            @foreach ($planAcc->Kpi->kpiChildOnes as $one) --}}
                <td>
                    @php
                        //$planOneTwo = $planAcc->planOneTwo($planAcc->Kpi->id, $one->id, $two->id, $office, $period->id,false);
                        $planOneTwo = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,$one->id, $two->id,null);
                        $office_level = $office->level;
                        if($office_level == 0) $office_level=1;
                    @endphp
                    @if($planOneTwo[2] <= $office_level)
                         {{ $planOneTwo[0] }}
                    @else
                        {{0}}
                    @endif
                    <!-- {{ $planOneTwo[0] }} -->
                </td>
            {{-- @endforeach
    </tr>
@endforeach --}}
@empty
@endforelse

</tr>
@endforeach
</tr>
@endforeach

<tr>
    <td>
        Major Activities
    </td>
    <td colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }}">
        @foreach ($narration as $key => $plannaration)
            {!! html_entity_decode($plannaration->plan_naration) !!}
            @php
                echo '<br/>';
            @endphp
        @endforeach
    </td>
</tr>
</table>
