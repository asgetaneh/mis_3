<table border="1" class="table" style="border-collapse: collapse; margin-bottom: 15px; border: 1px solid #000;">
    @php
        $ospan = $planAcc->Kpi->kpiChildThrees->count() * $planAcc->Kpi->kpiChildOnes->count() + 2;
    @endphp
        <tr>
            <td rowspan="2" colspan="2"> </td>
            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                <td colspan="{{ $planAcc->Kpi->kpiChildThrees->count() }}">{{ $one->kpiChildOneTranslations[0]->name }}</td>
            @endforeach
        </tr>

        <tr>
            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                    <td>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}</td>
                @endforeach
            @endforeach
        </tr>

        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <tr>
                <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">{{ $period->reportingPeriodTs[0]->name }}</th>
                @forelse ($planAcc->Kpi->kpiChildTwos as $two)
                    <td>{{ $two->kpiChildTwoTranslations[0]->name }}</td>
                    @forelse ($planAcc->Kpi->kpiChildOnes as $one)
                        @php
                            $childAndHim_array = [];
                        @endphp
                        @forelse ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                            <td>
                                @php
                                    $childAndHim = $office->offices;
                                    foreach ($childAndHim as $key => $value) {
                                        $childAndHim_array[$key] = $value->id;
                                    }
                                    $childAndHim_array = array_merge($childAndHim_array, [$office->id]);
                                    $plan123 = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year[0]->id ,$one->id, $two->id, $kpiThree->id);
                                    $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);

                                @endphp
                                {{ $plan123[0] }}
                            </td>
                        @empty
                        @endforelse
                    @empty
                    @endforelse

                @empty
                @endforelse
            </tr>

        @empty
        @endforelse

        <tr>
            <td>Major Activities</td>
            <td colspan="{{ $planAcc->Kpi->kpiChildOnes->count() *$planAcc->Kpi->kpiChildThrees->count()+1}}">
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
