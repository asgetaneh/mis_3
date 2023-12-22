@php
    $ospan =  $planAcc->Kpi->kpiChildThrees ->count()*$planAcc->Kpi->kpiChildOnes ->count()+2;
@endphp
<!-- <tr id="child-ones"> -->
<tr style ="background:#fff7e6;width:100%">
    <th>
        <input type ="checkbox" name="approve[]" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}"
        title="Appove for {{$office->officeTranslations[0]->name}}"/>
    </td>
    <th colspan="{{$ospan }} ">
            Offices: {{$office->officeTranslations[0]->name}}
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
        $childAndHimOffKpi = $office->offices;
        foreach ($childAndHimOffKpi as $key => $value) {
            $childAndHimOffKpi_array[$key] = $value->id;
        }
        $childAndHimOffKpi_array = array_merge( $childAndHimOffKpi_array, array($office->id));
        $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id,$office);
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
 @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
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
                $childAndHim = $office->offices;
                foreach ($childAndHim as $key => $value) {
                $childAndHim_array[$key] = $value->id;
                }
                $childAndHim_array = array_merge($childAndHim_array,array($office->id));
                $plan123 = $planAcc->planIndividual($planAcc->Kpi->id, $one->id, $two->id,$kpiThree->id,$office,$period->id);
                $narration = $planAcc->getNarration($planAcc->Kpi->id,$planning_year->id ?? NULL, $office, $period->id);

            @endphp
            {{$plan123[0]}}
            </td>
        @endforeach
    @endforeach
        {{--total ch2--}}
            <td>
        @php
            $planSumch2_array= [];
            $planSumch2 = $office->offices;
            foreach ($planSumch2 as $key => $value) {
                $planSumch2_array[$key] = $value->id;
            }
            $planSumch2_array = array_merge( $planSumch2_array, array($office->id));

            $planSumch2Total = $planAcc->planIndividualChTwoSum($planAcc->Kpi->id,  $two->id,$planSumch2_array,$period->id);
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
                $userChild = $office->offices;
                foreach ($userChild as $key => $value) {
                    $offices_array[$key] = $value->id;
                }
                $offices_array = array_merge($offices_array, array($office->id));

                $planSumch1ch3 = $planAcc->planIndividualChOnechThreeSum($planAcc->Kpi->id, $one->id, $two->id,$kpiThree->id,$offices_array);
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
              {!! html_entity_decode($plannaration->plan_naration) !!}
              @php
              echo "<br/>"
              @endphp
        @endforeach
    </td>
 </tr>

{{--end total ch1ch3--}}
