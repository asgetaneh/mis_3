<tr>
    <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }} ">
        Offices: {{ $office->officeTranslations[0]->name }}
        </td>
</tr>
<tr>
    <th>#</th>
    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
        <th>
            {{ $period->reportingPeriodTs[0]->name }}
        </th>
        @php
            $childAndHimOffKpi_array = [];
            $childAndHimOffKpi = $office->offices;
            foreach ($childAndHimOffKpi as $key => $value) {
                $childAndHimOffKpi_array[$key] = $value->id;
            }
            $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
            $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
        @endphp
    @empty
    @endforelse

    @foreach ($planAcc->Kpi->kpiChildOnes as $one)
<tr>
    <td>
        {{ $one->kpiChildOneTranslations[0]->name }}
    </td>
    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
        <td>
            @php
                $planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id);
                $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
            @endphp
            {{ $planOne[0] }}
        </td>
    @empty
    @endforelse
</tr>
@endforeach{{--
<tr>
    <th rowspan="1">
        {{ 'sum' }}
    </th>
    <th rowspan="1">
        {{ $planKpiOfficeYear }}
    </th>
</tr> --}}
<tr>
    <td>
        Major Activities
    </td>
    <td colspan="5">
        @foreach ($narration as $key => $plannaration) 
              {!! html_entity_decode($plannaration->plan_naration) !!}
              @php
              echo "<br/>"  
              @endphp
        @endforeach
    </td>
</tr>
@foreach ($planAcc->Kpi->kpiChildOnes as $one)
@endforeach

{{-- total ch2
            <td>
            @php 
                $planSumch2_array= [];
                $planSumch2 = $office->offices; 
                foreach ($planSumch2 as $key => $value) {
                    $planSumch2_array[$key] = $value->id;
                } 
                $planSumch2_array = array_merge($planSumch2_array, array($office->id));

                $planSumch1Total = $planAcc->planIndividualChOneSum($planAcc->Kpi->id,  $planSumch2_array);
                @endphp
                {{$planSumch1Total}}
            </td> --}}
{{-- end total ch2 --}}
</tr>