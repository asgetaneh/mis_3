<table border="1" class="table table-bordered" style="border-collapse: collapse; margin-bottom: 15px; border: 1px solid #000;">
    @php
        $ospan = $planAcc->Kpi->kpiChildThrees->count() * $planAcc->Kpi->kpiChildOnes->count() + 2;
        $activeQuarter = getReportingQuarter($planAcc->Kpi->reportingPeriodType->id);

    @endphp

    <!-- <tr id="child-ones"> -->
    {{-- <tr>

        <th colspan="{{ $ospan }} "  style="background:#fff7e6;width:100%">
            Office: {{ $office->officeTranslations[0]->name }}
        </th>
    </tr> --}}

    <tr>
        <th rowspan="2" colspan="2">#</th>

        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th colspan="{{ $planAcc->Kpi->kpiChildThrees->count() }}">
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
        @empty
        @endforelse
    </tr>

    <tr>

        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                <th>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
                </th>
            @endforeach
        @empty
        @endforelse
    </tr>

    {{-- <tr>
        <td rowspan="2" colspan="2">#</td>
        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            <td colspan="{{ $planAcc->Kpi->kpiChildThrees->count() }}">{{ $one->kpiChildOneTranslations[0]->name }}
            </td>
        @endforeach --}}
        {{-- <td rowspan ="2">
    @php
        $childAndHimOffKpi_array =[];
        $childAndHimOffKpi = $office->offices;
        foreach ($childAndHimOffKpi as $key => $value) {
            $childAndHimOffKpi_array[$key] = $value->id;
        }
        $childAndHimOffKpi_array = array_merge( $childAndHimOffKpi_array, array($office->id));
        $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id,$office);
        @endphp
        {{$planKpiOfficeYear}}

        {{"Sum"}}
    </td> --}}
    {{-- </tr> --}}



    {{-- <tr>
        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                <td>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
            @endforeach
        @endforeach
    </tr> --}}

    @forelse ($planAcc->Kpi->kpiChildOnes as $one)

    <tr>
        <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
            {{ $one->kpiChildOneTranslations[0]->name }}
        </th>
    @foreach ($planAcc->Kpi->kpiChildTwos as $two)
            <th>
                {{ $two->kpiChildTwoTranslations[0]->name }}
            </th>

    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
        {{-- <tr>
            <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
            @foreach ($planAcc->Kpi->kpiChildTwos as $two)
                <td>
                    {{ $two->kpiChildTwoTranslations[0]->name }}
                </td>
                @foreach ($planAcc->Kpi->kpiChildOnes as $one) --}}
                    @php
                        $childAndHim_array = [];
                    @endphp
                    @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                        <td style="">
                            @php
                                $childAndHim = $office->offices;
                                foreach ($childAndHim as $key => $value) {
                                    $childAndHim_array[$key] = $value->id;
                                }
                                $childAndHim_array = array_merge($childAndHim_array, [$office->id]);
                                //$plan123 = $planAcc->planIndividual($planAcc->Kpi->id, $one->id, $two->id, $kpiThree->id, $office, $period->id,true);
                                $plan123 = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,true,$planning_year[0]->id ,$one->id, $two->id, $kpiThree->id);
                                $beforePlan = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year[0]->id ,$one->id, $two->id, $kpiThree->id);

                                $narration = $planAcc->getReportNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);

                            @endphp

                            <div style="display: flex;">
                                <div style="flex: 1; padding: 5px; border: 1px solid #000; background-color: rgb(255, 255, 198);">
                                    <span style="">{{ $beforePlan[0] }}</span>
                                </div>
                                <div style="flex: 1; padding: 5px; text-align: center; background-color: green; border: 1px solid #000;">
                                    <span style="color: #fff;">{{ $plan123[1] }}</span>
                                </div>
                            </div>

                                {{-- <mark style="">{{ $beforePlan }}</mark> --}}

                                {{-- {{ $plan123 }} --}}

                        </td>
                    @endforeach
                {{-- @endforeach --}}
                {{-- total ch2 --
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

        {{-- end total ch2 --}}
        {{-- </tr> --}}
    {{-- @endforeach --}}
@empty
    @endforelse
    </tr>

    @endforeach

    @endforeach
    {{-- total ch1ch3
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
</tr> --}}
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="4">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->plan_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
    </tr>
</table>
{{-- end total ch1ch3 --}}
