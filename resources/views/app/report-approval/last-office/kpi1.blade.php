<tr>
        {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
        @if(reportStatusOffice(auth()->user()->offices[0], $planAcc->kpi_id, $planning_year[0]->id) == null || reportStatusOffice(auth()->user()->offices[0], $planAcc->kpi_id, $planning_year[0]->id) == auth()->user()->offices[0]->level)
            <th class="bg-light">
                <input class="hidden-self-input-{{ $planAcc->Kpi->id }}" type ="hidden" name="approve[]" value="{{$planAcc->Kpi->id}}-{{auth()->user()->offices[0]->id}}-{{$planning_year[0]->id}}"
                title="Appove for {{auth()->user()->offices[0]->officeTranslations[0]->name}}"/>

                <p class="badge badge-warning d-inline">NOT APPROVED</p>
            </td>

        @else
            <th class="bg-light">
                <p class="badge badge-success d-inline">APPROVED</p>
            </th>
        @endif
    <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }} ">
        Offices: {{ auth()->user()->offices[0]->officeTranslations[0]->name }}
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
            $childAndHimOffKpi = auth()->user()->offices[0]->offices;
            foreach ($childAndHimOffKpi as $key => $value) {
                $childAndHimOffKpi_array[$key] = $value->id;
            }
            $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [auth()->user()->offices[0]->id]);
            $planKpiOfficeYear = reportSumOfKpi($planAcc->Kpi->id, auth()->user()->offices[0], 1);
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
                $planOne = reportOne($planAcc->Kpi->id, $one->id, auth()->user()->offices[0], $period->id, 1);
                $narration = getReportNarration($planAcc->Kpi->id, $planning_year[0]->id, auth()->user()->offices[0], $period->id);
            @endphp
            {{ $planOne }}
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
