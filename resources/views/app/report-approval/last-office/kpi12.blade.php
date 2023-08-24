<tr>
<tr>

    {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
    @if(reportStatusOffice(auth()->user()->offices[0], $planAcc->kpi_id, $planning_year[0]->id) == auth()->user()->offices[0]->level)
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

    <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }} ">
        Offices: {{ auth()->user()->offices[0]->officeTranslations[0]->name }}

    </th>
</tr>
<td colspan="2  ">#</td>
@foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <td>
        {{ $one->kpiChildOneTranslations[0]->name }}
    </td>
@endforeach
<th>Sum</th>
</tr>
    @forelse(getReportingQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
         @php
            $childAndHimOffKpi_array = [];
            $childAndHimOffKpi = auth()->user()->offices[0]->offices;
            foreach ($childAndHimOffKpi as $key => $value) {
                $childAndHimOffKpi_array[$key] = $value->id;
            }
            $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [auth()->user()->offices[0]->id]);
            $planKpiOfficeYear = reportSumOfKpi($planAcc->Kpi->id, auth()->user()->offices[0], 1);
            $narration = getReportNarrationSelfOffice($planAcc->Kpi->id,$planning_year[0]->id, auth()->user()->offices[0], $period->id);
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
                            $planOneTwo = reportOneTwo($planAcc->Kpi->id, $one->id, $two->id, auth()->user()->offices[0], $period->id, 1);
                        @endphp
                        {{ $planOneTwo }}
                    </td>
                @endforeach
                {{-- total ch2 --}}
                <td>
                    @php
                        $planSumch2_array = [];
                        $planSumch2 = auth()->user()->offices[0]->offices;
                        foreach ($planSumch2 as $key => $value) {
                            $planSumch2_array[$key] = $value->id;
                        }
                        $planSumch2_array = array_merge($planSumch2_array, [auth()->user()->offices[0]->id]);

                        // $planSumch2Total = planIndividualChTwoSum($planAcc->Kpi->id, $two->id, $planSumch2_array,$period->id);
                        $planSumch2Total = reportIndividualChTwoSum($planAcc->Kpi->id, $two->id, auth()->user()->offices[0],$period->id, 1);
                    @endphp
                    {{ $planSumch2Total }}
                </td>
                {{-- end total ch2 --}}
        </tr>
    @endforeach
@empty
    @endforelse
    {{-- total ch1ch3 --}}
    <tr>
        <th colspan='2' style="background:#ffeecc;">
            {{ 'Total' }}
            </td>
            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
        <td>
            @php
                $offices_array = [];
                $userChild = auth()->user()->offices[0]->offices;
                foreach ($userChild as $key => $value) {
                    $offices_array[$key] = $value->id;
                }
                $offices_array = array_merge($offices_array, [auth()->user()->offices[0]->id]);

                // $planSumch1ch3 = planIndividualChOnech($planAcc->Kpi->id, $one->id, $two->id, $offices_array);
                $planSumch1ch3 = reportIndividualChOnech($planAcc->Kpi->id, $one->id, $two->id, auth()->user()->offices[0], 1);
            @endphp
            {{ $planSumch1ch3 }}
        </td>
        @endforeach
        <td> {{ $planKpiOfficeYear }}</td>
    </tr>
    <tr>
       <td>
        Major Activities
    </td>
    <td colspan="5">
        @foreach ($narration as $key => $plannaration)
              {!! html_entity_decode($plannaration->report_naration) !!}
              @php
              echo "<br/>"
              @endphp
        @endforeach
    </td>
    </tr>
    {{-- end total ch1ch3 --}}
