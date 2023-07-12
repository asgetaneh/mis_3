<tr>
    <th colspan="{{ $planAcc->Kpi->kpiChildOnes ->count()+1}} ">
        Offices: {{$office->officeTranslations[0]->name}}
    </td>
</tr>
<tr>
    @foreach ($planAcc->Kpi->kpiChildOnes as $one)
        <td>
            {{ $one->kpiChildOneTranslations[0]->name }}
        </td>
    @endforeach
    <th rowspan ="1">
            @php 
                $childAndHimOffKpi_array =[];
                $childAndHimOffKpi = $office->offices; 
                foreach ($childAndHimOffKpi as $key => $value) {
                    $childAndHimOffKpi_array[$key] = $value->id;
                } 
                $childAndHimOffKpi_array = array_merge( $childAndHimOffKpi_array, array($office->id));
                $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id,$childAndHimOffKpi_array);  
                @endphp
                {{"Total:"}}
            {{$planKpiOfficeYear}}
    </td>
</tr>
<tr>
    @foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <td>
        @php 
        $planOne
            = $planAcc->planOne($planAcc->Kpi->id, $one->id,$childAndHimOffKpi_array);
        @endphp
        {{$planOne}}
    </td>
    @endforeach

    {{--total ch2
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
            </td>--}}
            {{--end total ch2--}}
</tr>