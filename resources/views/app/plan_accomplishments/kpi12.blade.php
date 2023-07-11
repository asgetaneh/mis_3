<tr>
        <tr>
            <th colspan="{{ $planAcc->Kpi->kpiChildOnes ->count()+2}} ">
                Offices: {{$office->officeTranslations[0]->name}}
            </td>
    </tr>
        <td>#</td>
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
    
    @foreach ($planAcc->Kpi->kpiChildTwos as $two)
        <tr>
            <td>
                {{ $two->kpiChildTwoTranslations[0]->name }}
            </td>
            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                <td> 
                    @php 
                    $planOneTwo
                        = $planAcc->planOneTwo($planAcc->Kpi->id, $one->id, $two->id,$childAndHimOffKpi_array);  
                    @endphp
                    {{$planOneTwo}}
                </td>
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

                $planSumch2Total = $planAcc->planIndividualChTwoSum($planAcc->Kpi->id,  $two->id,$planSumch2_array);
                @endphp
                {{$planSumch2Total}}
            </td>
            {{--end total ch2--}}
        </tr>
    @endforeach
    {{--total ch1ch3--}}
    <tr>
        <th style ="background:#ffeecc;">
        {{ "Total"}}
        </td>
        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
             <td>
            @php 
                $offices_array= [];
                $userChild = $office->offices; 
                foreach ($userChild as $key => $value) {
                    $offices_array[$key] = $value->id;
                } 
                $offices_array = array_merge($offices_array, array($office->id));

                $planSumch1ch3 = $planAcc->planIndividualChOnech($planAcc->Kpi->id, $one->id, $two->id,$offices_array); 
                @endphp
                    {{$planSumch1ch3}}
            </td>
        @endforeach
        <td>  {{$planKpiOfficeYear}}</td>
</tr>
{{--end total ch1ch3--}}
