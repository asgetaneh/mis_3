<tr>
    {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
    {{-- @if(planStatusOffice($office, $planAcc->kpi_id, $planning_year->id ?? NULL) == 0)
        <th class="bg-light">
            <div class="icheck-success d-inline">
                <input class="office-checkbox-kpi-{{ $planAcc->kpi_id }}" name="approve[]" type="checkbox" id="{{$office->id}}" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}">
                <label for="{{$office->id}}">
                    Select Office
                </label>
            </div>
        </td>

    @else --}}
        <th>
            <p class="badge badge-success d-inline" id="any-approved-{{ $planAcc->Kpi->id }}">APPROVED</p>
        </th>
    {{-- @endif --}}
<th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }} ">
    Offices: {{ $office->officeTranslations[0]->name }}
    </td>
</tr>
<tr>
<th>#</th>
<th> Baseline </th>
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
        $planKpiOfficeYear = planSumOfKpi($planAcc->Kpi->id, $office, $planning_year->id ?? NULL, 3);
        @endphp
@empty
@endforelse

@foreach ($planAcc->Kpi->kpiChildOnes as $one)
<tr>
    @php
        $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$office, $planning_year->id, $period->id,$one->id,null,null);
        // $planOne = planOne($planAcc->Kpi->id, $one->id, $office, $period->id, 3,$planning_year);
        $planOne = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,$one->id,null,null);
        $narration = getNarrationApproved($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
    @endphp
    <td>
        {{ $one->kpiChildOneTranslations[0]->name }}
    </td>
    <td> {{ $baselineOfOfficePlan }} </td>
    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
    <td>
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
