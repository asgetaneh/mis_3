<table class="table table-bordered" style="background:#12cd4322;">
    @php
        $ospan = $planAccKpiChildThree->count() * $getQuarter->count() + 2;
    @endphp
    <!-- <tr id="child-ones"> -->
    <tr>

        <th colspan="{{ $ospan }} "  style="background:#fff7e6;width:100%">
            Offices: {{ $office->officeTranslations[0]->name }}
        </th>
        <td rowspan="{{ $planAccKpiChildTwo->count() *count($getQuarter)+4}}">
        @if (!$officeOffices->isEmpty())
            <p>
                <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}{{$planAccKpi->id}}" role="button"
                    aria-expanded="false" aria-controls="collapseExample0">
                    Details
                </a>
            </p>
        @else
            {{ '' }}
        @endif
    </td>
    </tr>
    {{-- <tr>
        <td rowspan="2" colspan="2">#</td>
        @foreach ($planAccKpiChildOne as $one)
            <td colspan="{{ $planAccKpiChildThree->count() }}">{{ $one->kpiChildOneTranslations[0]->name }}
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
        $planKpiOfficeYear = $planAcc->planSumOfKpi($planAccKpi->id,$office);
        @endphp
        {{$planKpiOfficeYear}}

        {{"Sum"}}
    </td> --}}
    {{-- </tr>
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
                                        $childAndHim = $officeOffices;
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

                    $planSumch2Total = $planAcc->planIndividualChTwoSum($planAccKpi->id,  $two->id,$planSumch2_array,$period->id);
                    @endphp
                    {{$planSumch2Total}}
                    </td>

                {{-- end total ch2 --}}
                {{-- </tr>
            @endforeach --}}
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
        @foreach ($planAccKpiChildOne as $one)
            @foreach ($planAccKpiChildThree as $kpiThree)
            <td>
            @php
                $offices_array= [];
                $userChild = $office->offices;
                foreach ($userChild as $key => $value) {
                    $offices_array[$key] = $value->id;
                }
                $offices_array = array_merge($offices_array, array($office->id));

                $planSumch1ch3 = $planAcc->planIndividualChOnechThreeSum($planAccKpi->id, $one->id, $two->id,$kpiThree->id,$offices_array);
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
                {!! html_entity_decode($plannaration->report_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
    </tr>
</table>
{{-- end total ch1ch3 --}}
{{-- level two (directores and same level) --}}
<div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
    <div class="card card-body">
        @php
            $offices_twos = $officeOffices;
        @endphp
        @forelse ($offices_twos as $office)

            @include('app.report_accomplishments.view-kpi1233')
            <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                <div class="card card-body">
                    @php
                        $offices_threes = $office->offices;
                    @endphp
                    @forelse ($offices_threes as $office)
                        @include('app.report_accomplishments.view-kpi1233')
                        <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                            <div class="card card-body">
                                @php
                                    $offices_fours = $office->offices;
                                @endphp
                                @forelse ($offices_fours as $office)
                                    @include('app.report_accomplishments.view-kpi1233')
                                    <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                        <div class="card card-body">
                                            @php
                                                $offices_fives = $office->offices;
                                            @endphp
                                            @forelse ($offices_fives as $office)
                                                @include('app.report_accomplishments.view-kpi1233')
                                                <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                                    <div class="card card-body">
                                                        @php
                                                            $offices_sixs = $office->offices;
                                                        @endphp
                                                        @forelse ($offices_sixs as $office)
                                                            @include('app.report_accomplishments.view-kpi1233')
                                                            <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                                                <div class="card card-body">

                                                                </div>
                                                            </div>
                                                        @empty
                                                            <h4>on child!</h4>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            @empty
                                                <h4>on child!</h4>
                                            @endforelse
                                        </div>
                                    </div>
                                @empty
                                    <h4>on child!</h4>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <h4>on child!</h4>
                    @endforelse
                </div>
            </div>
        @empty
            <h4>on child!</h4>
        @endforelse
    </div>
</div>
