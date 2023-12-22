@php
    $ospan =  $planAcc->Kpi->kpiChildThrees ->count()*$planAcc->Kpi->kpiChildOnes ->count()+2;
@endphp
<!-- <tr id="child-ones"> -->
<tr style ="background:#fff7e6;width:100%">

    {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
    @if(reportStatusOffice(auth()->user()->offices[0], $planAcc->kpi_id, $planning_year->id ?? NULL) !== auth()->user()->offices[0]->level)
        @if (isset($setter))
            <th class="bg-light">
                {{-- <input class="form-check" type ="checkbox" name="approve[]" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}"
            title="Appove for {{$office->officeTranslations[0]->name}}"/> --}}

                <div class="icheck-success d-inline">
                    <input class="office-checkbox-kpi-{{ $planAcc->kpi_id }}" name="approve[]" type="checkbox"
                        id="{{ $planAcc->kpi_id }}-{{ auth()->user()->offices[0]->id }}"
                        value="{{ $planAcc->Kpi->id }}-{{ auth()->user()->offices[0]->id }}-{{ $planning_year->id ?? NULL }}">
                    <label for="{{ $planAcc->kpi_id }}-{{ auth()->user()->offices[0]->id }}">
                        Select Office
                    </label>
                </div>
            </th>
        @else
            <th class="bg-light">
                <input class="hidden-self-input-{{ $planAcc->Kpi->id }}" type ="hidden" name="approve[]" value="{{$planAcc->Kpi->id}}-{{auth()->user()->offices[0]->id}}-{{$planning_year->id ?? NULL}}"
                title="Appove for {{auth()->user()->offices[0]->officeTranslations[0]->name}}"/>

                <p class="badge badge-warning d-inline">NOT APPROVED</p>
            </td>
        @endif

    @else
        <th class="bg-light">
            <p class="badge badge-success d-inline">APPROVED</p>
        </th>
    @endif

    <th colspan="{{$ospan }} ">
        Offices: {{auth()->user()->offices[0]->officeTranslations[0]->name}} <span class="mark">{{ isset($setter) ? '(Own Report)' : '' }}</span>
</td>
</tr>
<tr>
    <td rowspan="2" colspan="2">#</td>
    @foreach ($planAcc->Kpi->kpiChildOnes as $one)

        <td colspan="{{ $planAcc->Kpi->kpiChildThrees ->count() }}" >{{ $one->kpiChildOneTranslations[0]->name }}
        </td>
    @endforeach
    <td rowspan ="2">
    @php
        $childAndHimOffKpi_array =[];
        $childAndHimOffKpi = auth()->user()->offices[0]->offices;
        foreach ($childAndHimOffKpi as $key => $value) {
            $childAndHimOffKpi_array[$key] = $value->id;
        }
        $childAndHimOffKpi_array = array_merge( $childAndHimOffKpi_array, array(auth()->user()->offices[0]->id));
        $planKpiOfficeYear = reportSumOfKpi($planAcc->Kpi->id,auth()->user()->offices[0], 7);
        @endphp
        {{"Total:"}}
    {{$planKpiOfficeYear}}
    </td>
</tr>
<tr>
    @foreach ($planAcc->Kpi->kpiChildOnes as $one)
        @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
            <td  >{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
            </td>
        @endforeach
    @endforeach
</tr>
 @forelse(getReportingQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
    <tr>
        <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
            {{ $period->reportingPeriodTs[0]->name }}
        </th>
    @foreach ($planAcc->Kpi->kpiChildTwos as $two)
             <td>
            {{ $two->kpiChildTwoTranslations[0]->name }}
            </td>
            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                    @php
                $childAndHim_array = [];
                @endphp
            @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
            <td>
            @php
                $childAndHim = auth()->user()->offices[0]->offices;
                foreach ($childAndHim as $key => $value) {
                $childAndHim_array[$key] = $value->id;
                }
                $childAndHim_array = array_merge($childAndHim_array,array(auth()->user()->offices[0]->id));
                $plan123 = reportIndividual($planAcc->Kpi->id, $one->id, $two->id,$kpiThree->id,auth()->user()->offices[0],$period->id, 7);
                $narration = getReportNarrationSelfOffice($planAcc->Kpi->id,$planning_year->id ?? NULL, auth()->user()->offices[0], $period->id);

            @endphp
            {{$plan123}}
            </td>
        @endforeach
    @endforeach
        {{--total ch2--}}
            <td>
        @php
            $planSumch2_array= [];
            $planSumch2 = auth()->user()->offices[0]->offices;
            foreach ($planSumch2 as $key => $value) {
                $planSumch2_array[$key] = $value->id;
            }
            $planSumch2_array = array_merge( $planSumch2_array, array(auth()->user()->offices[0]->id));

            // $planSumch2Total = planIndividualChTwoSum($planAcc->Kpi->id,  $two->id,$planSumch2_array,$period->id);
            $planSumch2Total = reportIndividualChTwoSum($planAcc->Kpi->id,  $two->id,auth()->user()->offices[0],$period->id, 7);
            @endphp
            {{$planSumch2Total}}
            </td>

        {{--end total ch2--}}
    </tr>
    @endforeach
    @empty
    @endforelse
    {{--total ch1ch3--}}
    <tr>
        <th style ="background:#ffeecc;" colspan="2">
        {{ "Total"}}
        </td>
        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
            <td>
            @php
                $offices_array= [];
                $userChild = auth()->user()->offices[0]->offices;
                foreach ($userChild as $key => $value) {
                    $offices_array[$key] = $value->id;
                }
                $offices_array = array_merge($offices_array, array(auth()->user()->offices[0]->id));

                // $planSumch1ch3 = planIndividualChOnechThreeSum($planAcc->Kpi->id, $one->id, $two->id,$kpiThree->id,$offices_array);
                $planSumch1ch3 = reportIndividualChOnechThreeSum($planAcc->Kpi->id, $one->id, $two->id,$kpiThree->id,auth()->user()->offices[0], 7);
                @endphp
                    {{$planSumch1ch3}}
            </td>
        @endforeach
            @endforeach
        <td>  {{$planKpiOfficeYear}}</td>
</tr>
<tr>
   <td>
        Major Activities
    </td>
    <td colspan="8">
        @foreach ($narration as $key => $plannaration)
              {!! html_entity_decode($plannaration->report_naration) !!}
              @php
              echo "<br/>"
              @endphp
        @endforeach
    </td>
 </tr>

{{--end total ch1ch3--}}
