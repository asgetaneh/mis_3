<tr>
        {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
        @if(planStatusOffice($office, $planAcc->kpi_id, $planning_year->id ?? NULL) == 0)
            <th class="bg-light">
                {{-- <input class="form-check" type ="checkbox" name="approve[]" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}"
                title="Appove for {{$office->officeTranslations[0]->name}}"/> --}}

                <div class="icheck-success d-inline">
                    <input class="office-checkbox-kpi-{{ $planAcc->kpi_id }}" name="approve[]" type="checkbox" id="{{$office->id}}" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}">
                    <label for="{{$office->id}}">
                        Select Office
                    </label>
                </div>
            </td>

        @else
            <th>
                <p class="badge badge-success d-inline">APPROVED</p>
            </th>
        @endif
    <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }} ">
        Offices: {{ $office->officeTranslations[0]->name }}
        </td>

        @if (planStatusOffice($office, $planAcc->kpi_id, $planning_year->id ?? NULL) > auth()->user()->offices[0]->level)
            <a href="" class="btn btn-sm float-right btn-info text-white"
                data-toggle="modal" data-target="#modal-lg"
                data-id="">
                <i class="fas fa fa-comments mr-1"></i> Write Comment
            </a>
        @endif
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
            $planKpiOfficeYear = planSumOfKpi($planAcc->Kpi->id, $office);
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
                $planOne = planOne($planAcc->Kpi->id, $one->id, $office, $period->id,$planning_year);
                $narration = getNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
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
