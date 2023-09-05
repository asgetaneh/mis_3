<table class="table table-bordered">
    @php
        $ospan = $planAcc->Kpi->kpiChildThrees->count() * $planAcc->Kpi->kpiChildOnes->count() + 2;
    @endphp
    <!-- <tr id="child-ones"> -->
    <tr>

        <th colspan="{{ $ospan }} "  style="background:#fff7e6;width:100%">
            Offices: {{ $office->officeTranslations[0]->name }}
        </td>
         <td rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() *count(getQuarter($planAcc->Kpi->reportingPeriodType->id))+4}}">
        @if (!$office->offices->isEmpty())
            <p>
                <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}{{$planAcc->Kpi->id}}" role="button"
                    aria-expanded="false" aria-controls="collapseExample0">
                    Details
                </a>
            </p>
        @else
            {{ 'no child ' }}
        @endif
    </td>
    </tr>
    <tr>
        <td rowspan="2" colspan="2">#</td>
        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            <td colspan="{{ $planAcc->Kpi->kpiChildThrees->count() }}">{{ $one->kpiChildOneTranslations[0]->name }}
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                <td>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
            @endforeach
        @endforeach
    </tr>
    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
        <tr>
            <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
            @foreach ($planAcc->Kpi->kpiChildTwos as $two)
                <td>
                    {{ $two->kpiChildTwoTranslations[0]->name }}
                </td>
                @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                    @php
                        $childAndHim_array = [];
                    @endphp
                    @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                        <td>
                            @php
                                $childAndHim = $office->offices;
                                foreach ($childAndHim as $key => $value) {
                                    $childAndHim_array[$key] = $value->id;
                                }
                                $childAndHim_array = array_merge($childAndHim_array, [$office->id]);
                               // $plan123 = $planAcc->planIndividual($planAcc->Kpi->id, $one->id, $two->id, $kpiThree->id, $office, $period->id,false);
                                  $plan123 = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year[0]->id ,$one->id, $two->id, $kpiThree->id);
                                $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
                                
                            @endphp
                            {{ $plan123[0] }}
                        </td>
                    @endforeach
                @endforeach
        </tr>
    @endforeach
@empty
    @endforelse
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="{{ $planAcc->Kpi->kpiChildOnes->count() *$planAcc->Kpi->kpiChildThrees->count()+1}}">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->plan_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td> 
    </tr>
</table>
