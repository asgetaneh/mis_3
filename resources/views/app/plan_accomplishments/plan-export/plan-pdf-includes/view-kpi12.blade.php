<table border="1" class="table" style="border-collapse: collapse; margin-bottom: 15px; border: 1px solid #000;">
    <tbody>
    <tr>
        <td colspan="2"> </td>
        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            <td>{{ $one->kpiChildOneTranslations[0]->name }}</td>
        @endforeach
    </tr>

    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
        @php
            $childAndHimOffKpi_array = [];
            $childAndHimOffKpi = $office->offices;
            foreach ($childAndHimOffKpi as $key => $value) {
                $childAndHimOffKpi_array[$key] = $value->id;
            }
            $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
            $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
        @endphp

        <tr>
            <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">{{ $period->reportingPeriodTs[0]->name }}</th>
            @foreach ($planAcc->Kpi->kpiChildTwos as $two)
                <td>{{ $two->kpiChildTwoTranslations[0]->name }}</td>
                @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                    <td>
                        @php
                            $planOneTwo = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id, false, $planning_year[0]->id, $one->id, $two->id, null);
                        @endphp
                        {{ $planOneTwo[0] }}
                    </td>
                @endforeach
            </tr>

            @endforeach

    @empty
    @endforelse

    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }}">
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