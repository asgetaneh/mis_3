<table border="1" class="table" style="border-collapse: collapse; margin-bottom: 15px; border: 1px solid #000;">
    <tbody>

        <tr>
            <th colspan="2">#</th>
            <th>
                Baseline
            </th>

            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th>
                    {{ $period->reportingPeriodTs[0]->name }}
                </th>
            @empty
            @endforelse
        </tr>

    {{-- <tr>
        <td colspan="2"> </td>
        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            <td>{{ $one->kpiChildOneTranslations[0]->name }}</td>
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

                    @php
                        $baseline = getBaselineIndividualOneTwo($planning_year->id ?? NULL, $planAcc->Kpi->id, $one->id, $two->id, auth()->user()->offices[0]->id);
                        $baselineLastYear = getBaselineLastYear($planAcc->Kpi->id, $planning_year->id ?? NULL, 1, auth()->user()->offices[0]->id, $one->id, $two->id);

                    @endphp
                    {{-- @if ($baseline)
                        <td>
                            {{ $baseline->baseline ?? '-' }}
                        </td>
                    @else
                        <td>
                            {{ $baseline->baseline ?? '-' }}
                        </td>
                    @endif --}}
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
                        @php
                            $childAndHimOffKpi_array = [];
                            $childAndHimOffKpi = $office->offices;
                            foreach ($childAndHimOffKpi as $key => $value) {
                                $childAndHimOffKpi_array[$key] = $value->id;
                            }
                            $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
                            $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
                        @endphp

                        {{-- <tr> --}}
                            {{-- <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">{{ $period->reportingPeriodTs[0]->name }}</th> --}}
                            {{-- @foreach ($planAcc->Kpi->kpiChildTwos as $two)
                                <td>{{ $two->kpiChildTwoTranslations[0]->name }}</td>
                                @foreach ($planAcc->Kpi->kpiChildOnes as $one) --}}
                                    <td>
                                        @php
                                            $planOneTwo = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id, false, $planning_year->id ?? NULL, $one->id, $two->id, null);
                                        @endphp
                                        {{ $planOneTwo[0] }}
                                    </td>
                                {{-- @endforeach
                            </tr>

                            @endforeach --}}

                    @empty
                    @endforelse
                </tr>
            @endforeach
        </tr>
    @endforeach

    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="{{ 7 }}">
            @foreach ($narration as $key => $plannaration)
                {!! $plannaration->plan_naration !!}
            @endforeach
        </td>
    </tr>

</tbody>
</table>




{{-- <table class="table table-bordered" style="background: rgba(18, 205, 67, 0.133); --darkreader-inline-bgimage: initial; --darkreader-inline-bgcolor: rgba(13, 156, 51, 0.13);" data-darkreader-inline-bgimage="" data-darkreader-inline-bgcolor="">
    <tbody>
        <tr>
            <td colspan="2">#</td>
            <td>UG</td>
            <td>PG</td>
            <td>PHD</td>
        </tr>
        <tr>
            <th rowspan="2">Yearly</th>
            <td>R</td>
            <td>15</td>
            <td>81</td>
            <td>73</td>
        </tr>
        <tr>
            <td>EX</td>
            <td>74</td>
            <td>55</td>
            <td>19</td>
        </tr>
    </tbody>
</table> --}}
