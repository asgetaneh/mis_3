{{-- level one (directores and same level) --}}
<table border="1" class="table" style="border-collapse: collapse; margin-bottom: 15px; border: 1px solid #000;">
    {{-- <tr>
        <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }} ">
            Offices: {{ $office->officeTranslations[0]->name }}
        </td>

    </tr> --}}
    <tr>
        <th> </th>
        <th>Baseline</th>
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
               // $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
            @endphp
        @empty
        @endforelse

        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            @php
                $baselineLastYear = getBaselineLastYear($planAcc->Kpi->id, $planning_year[0]->id, 1, auth()->user()->offices[0]->id, $one->id);
            @endphp
            <tr>
                <td>
                    {{ $one->kpiChildOneTranslations[0]->name }}
                </td>

                {{-- baseline --}}
                @if (!empty($baselineLastYear))
                    <td>
                        {{ $baselineLastYear }}
                    </td>
                @else
                    <td>
                        -
                    </td>
                @endif

                @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                    <td>
                        @php
                        // $planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id,false);
                            $planOne = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year[0]->id ,$one->id,null,null);
                            $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
                        @endphp
                        {{ $planOne[0] }}
                    </td>
                @empty
                @endforelse
            </tr>
    @endforeach
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="{{count(getQuarter($planAcc->Kpi->reportingPeriodType->id))}}">
            @foreach ($narration as $key => $plannaration)
                {!! $plannaration->plan_naration !!}
                {{-- @php
                    echo '<br/>';
                @endphp --}}
                {{-- <br/> --}}
            @endforeach
        </td>
    </tr>
</table>
