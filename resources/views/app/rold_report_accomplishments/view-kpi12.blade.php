<tr>
<tr>
    <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }} ">
        Offices: {{ $office->officeTranslations[0]->name }}
    </th>
</tr>
<td colspan="2  ">#</td>
@foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <td>
        {{ $one->kpiChildOneTranslations[0]->name }}
    </td>
@endforeach
{{-- <th>Sum</th> --}}
</tr>
    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
         @php
            $childAndHimOffKpi_array = [];
            $childAndHimOffKpi = $office->offices;
            foreach ($childAndHimOffKpi as $key => $value) {
                $childAndHimOffKpi_array[$key] = $value->id;
            }
            $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
            $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
            $narration = $planAcc->getNarration($planAcc->Kpi->id,$planning_year->id ?? NULL, $office, $period->id);
        @endphp
        <tr>
            <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
            @foreach ($planAcc->Kpi->kpiChildTwos as $two)
                <td>
                    {{ $two->kpiChildTwoTranslations[0]->name }}
                </td>
                @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                    <td>
                        @php
                            $planOneTwo = $planAcc->planOneTwo($planAcc->Kpi->id, $one->id, $two->id, $office, $period->id);
                        @endphp
                        {{ $planOneTwo[1] }}
                    </td>
                @endforeach
                {{-- total ch2 --}}
                {{-- <td>
                    @php
                        $planSumch2_array = [];
                        $planSumch2 = $office->offices;
                        foreach ($planSumch2 as $key => $value) {
                            $planSumch2_array[$key] = $value->id;
                        }
                        $planSumch2_array = array_merge($planSumch2_array, [$office->id]);

                        $planSumch2Total = $planAcc->planIndividualChTwoSum($planAcc->Kpi->id, $two->id, $planSumch2_array,$period->id);
                    @endphp
                    {{ $planSumch2Total }}
                </td> --}}
                {{-- end total ch2 --}}
        </tr>
    @endforeach
@empty
    @endforelse
    {{-- total ch1ch3 --}}
    {{-- <tr>
        <th colspan='2' style="background:#ffeecc;">
            {{ 'Total' }}
            </td>
            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
        <td>
            @php
                $offices_array = [];
                $userChild = $office->offices;
                foreach ($userChild as $key => $value) {
                    $offices_array[$key] = $value->id;
                }
                $offices_array = array_merge($offices_array, [$office->id]);

                $planSumch1ch3 = $planAcc->planIndividualChOnech($planAcc->Kpi->id, $one->id, $two->id, $offices_array);
            @endphp
            {{ $planSumch1ch3 }}
        </td>
        @endforeach
        <td> {{ $planKpiOfficeYear }}</td>
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
    {{-- end total ch1ch3 --}}
