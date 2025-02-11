<table class="table table-bordered">
    @php
        $ospan = $planAccKpiChildThree->count() * $getQuarter->count() + 2;
    @endphp
    <!-- <tr id="child-ones"> -->
    <tr >

        <th colspan="{{ $ospan }} " style="background:#fff7e6;width:100%">
            Offices: {{ $office->officeTranslations[0]->name }}
        </th>
         <td rowspan="{{ $planAccKpiChildTwo->count() *count($getQuarter)+4}}">
        @if (!$office->offices->isEmpty())
            <p>
                <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}{{$planAccKpi->id}}" role="button"
                    aria-expanded="false" aria-controls="collapseExample0">
                    Details
                </a>
            </p>
        @else
            {{ 'no child ' }}
        @endif
    </td>
    </tr>
    {{-- <tr>
        <td rowspan="2" colspan="2">#</td>
        @foreach ($planAccKpiChildOne as $one)
            <td colspan="{{ $planAccKpiChildThree->count() }}">{{ $one->kpiChildOneTranslations[0]->name }}
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach ($planAccKpiChildOne as $one)
            @foreach ($planAccKpiChildThree as $kpiThree)
                <td>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
            @endforeach
        @endforeach
    </tr> --}}


    <tr>
        <th rowspan="2" colspan="2">#</th>

        @forelse($getQuarter as $period)
            <th colspan="{{ $planAccKpiChildThree->count() }}">
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
        @empty
        @endforelse
    </tr>

    <tr>

        @forelse($getQuarter as $period)
            @foreach ($planAccKpiChildThree as $kpiThree)
                <th>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
                </th>
            @endforeach
        @empty
        @endforelse
    </tr>


    @forelse ($planAccKpiChildOne as $one)

    <tr>
        <th rowspan="{{ $planAccKpiChildTwo->count() }}">
            {{ $one->kpiChildOneTranslations[0]->name }}
        </th>
    @foreach ($planAccKpiChildTwo as $two)
            <th>
                {{ $two->kpiChildTwoTranslations[0]->name }}
            </th>

    @forelse($getQuarter as $period)
        {{-- <tr>
            <th rowspan="{{ $planAccKpiChildTwo->count() }}">
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
            @foreach ($planAccKpiChildTwo as $two)
                <td>
                    {{ $two->kpiChildTwoTranslations[0]->name }}
                </td>
                @foreach ($planAccKpiChildOne as $one) --}}
                    @php
                        $childAndHim_array = [];
                    @endphp
                    @foreach ($planAccKpiChildThree as $kpiThree)
                        <td>
                            @php
                                $childAndHim = $office->offices;
                                foreach ($childAndHim as $key => $value) {
                                    $childAndHim_array[$key] = $value->id;
                                }
                                $childAndHim_array = array_merge($childAndHim_array, [$office->id]);
                                //$plan123 = $planAcc->planIndividual($planAccKpi->id, $one->id, $two->id, $kpiThree->id, $office, $period->id,true);
                                $plan123 = $planAcc->KpiOTT($planAccKpi->id, $office, $period->id,true,$planning_year->id ?? NULL ,$one->id, $two->id, $kpiThree->id);
                                $narration = $planAcc->getReportNarration($planAccKpi->id, $planning_year->id ?? NULL, $office, $period->id);

                            @endphp
                            {{ $plan123[1] }}
                        </td>
                    @endforeach
                {{-- @endforeach
        </tr>
    @endforeach --}}
@empty
    @endforelse

</tr>

@endforeach

@endforeach

    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="4">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->report_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
    </tr>
</table>
