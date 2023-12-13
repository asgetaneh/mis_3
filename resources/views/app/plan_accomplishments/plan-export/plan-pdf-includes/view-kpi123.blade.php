<table border="1" class="table" style="border-collapse: collapse; margin-bottom: 15px; border: 1px solid #000;">
    @php
        $ospan = $planAcc->Kpi->kpiChildThrees->count() * $planAcc->Kpi->kpiChildOnes->count() + 2;
    @endphp

        <tr>
            <th rowspan="2" colspan="2">#</th>
            <th colspan="{{ $planAcc->Kpi->kpiChildThrees->count() }}">
                Baseline
            </th>

            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                <th colspan="{{ $planAcc->Kpi->kpiChildThrees->count() }}">
                    {{ $period->reportingPeriodTs[0]->name }}
                </th>
            @empty
            @endforelse
        </tr>

        <tr>

            {{-- Display level 3 for baseline column --}}
            @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                <th>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
                </th>
            @endforeach

            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                    <th>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
                    </th>
                @endforeach
            @empty
            @endforelse
        </tr>

        {{-- <tr>
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

                @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)

                    {{-- Fetch baseline sum for this KPI, for now just a character --}}
                    @php
                        $baseline = getBaselineIndividualOneTwoThree($planning_year[0]->id, $planAcc->Kpi->id, $one->id, $two->id, $kpiThree->id, auth()->user()->offices[0]->id);
                        $baselineLastYear = getBaselineLastYear($planAcc->Kpi->id, $planning_year[0]->id, 1, auth()->user()->offices[0]->id, $one->id, $two->id, $kpiThree->id);

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
                @endforeach

            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                {{-- <tr> --}}
                    @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
{{--
                    <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">{{ $period->reportingPeriodTs[0]->name }}</th>
                    @forelse ($planAcc->Kpi->kpiChildTwos as $two)
                        <td>{{ $two->kpiChildTwoTranslations[0]->name }}</td>
                        @forelse ($planAcc->Kpi->kpiChildOnes as $one) --}}
                            @php
                                $childAndHim_array = [];
                            @endphp
                            {{-- @forelse ($planAcc->Kpi->kpiChildThrees as $kpiThree) --}}
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
                            {{-- @empty
                            @endforelse
                        @empty
                        @endforelse --}}

                    {{-- @empty
                    @endforelse --}}

                    @endforeach
            @empty
            @endforelse
        </tr>

            @endforeach


        @endforeach

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
