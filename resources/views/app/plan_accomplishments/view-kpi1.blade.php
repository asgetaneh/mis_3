{{-- level one (directores and same level) --}}
<table class="table table-bordered" style="background:#12cd4322;">
    <tr>
        <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 2 }} " style="width:90%">
            Offices: {{ $office->officeTranslations[0]->name }}
        </th>
         <td rowspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }}">
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
        <th>#</th>
        <th  rowspan="">{{"Baseline"}}</th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th>
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
        @empty
        @endforelse

        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <tr>
        <td>
            {{ $one->kpiChildOneTranslations[0]->name }}
        </td>
        @php
            $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$office, $planning_year->id, $period->id,$one->id,null,null);
            //dump($office);
        @endphp
        <td>
            {{ $baselineOfOfficePlan }}
        </td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <td>
                @php
                   // $planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id,false);
                    $planOne = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,$one->id,null,null);
                    $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
                    $office_level = $office->level;
                    if($office_level == 0) $office_level=1;
                @endphp
                @if($planOne[2] <= $office_level)
                     {{ $planOne[0] }} 
                @else
                    {{0}}
                @endif
                 <!-- {{ $planOne[0] }} -->
            </td>
        @empty
        @endforelse
    </tr>
    @endforeach
    <tr>
        <th>
            Major Activities
        </th>
        <td colspan="{{count(getQuarter($planAcc->Kpi->reportingPeriodType->id))+1}}">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->plan_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
    </tr>
</table>
{{-- level two (directores and same level) --}}
<div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
    <div class="card card-body" style="background:#12cd4322; padding: 70px; border: 1px solid;">
        @php
            $office_one_self = $office;
            $offices_twos = $office->offices;
        @endphp
        @forelse ($offices_twos as $office)

            @include('app.plan_accomplishments.view-kpi11')
            <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                <div class="card card-body" style="background:#D3D3D3; padding: 70px; border: 1px solid;">
                    @php
                        $office_two_self = $office;
                        $offices_threes = $office->offices;
                    @endphp
                    @forelse ($offices_threes as $office)
                        @include('app.plan_accomplishments.view-kpi11')
                        <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                            <div class="card card-body" style="background:# ; padding: 70px; border: 1px solid;">
                                @php
                                    $office_three_self = $office;
                                    $offices_fours = $office->offices;
                                @endphp
                                @forelse ($offices_fours as $office)
                                    @include('app.plan_accomplishments.view-kpi11')
                                    <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                        <div class="card card-body" style="background:#D3D3D3; padding: 70px; border: 1px solid;">
                                            @php
                                                $office_four_self = $office;
                                                $offices_fives = $office->offices;
                                            @endphp
                                            @forelse ($offices_fives as $office)
                                                @include('app.plan_accomplishments.view-kpi11')
                                                <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                                    <div class="card card-body" style="background:# ; padding: 70px; border: 1px solid;">
                                                        @php
                                                            $offices_sixs = $office->offices;
                                                        @endphp
                                                        @forelse ($offices_sixs as $office)
                                                            @include('app.plan_accomplishments.view-kpi11')
                                                            <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                                                <div class="card card-body" style="background:#D3D3D3; padding: 70px; border: 1px solid;">

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
                                            <!-- start of table to display leader(director) office plan in the level-->
                                            <table class="table table-bordered" >
                                                <tr>
                                                    <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 2 }} ">
                                                        Offices: {{ $office_four_self->officeTranslations[0]->name }}
                                                        </td>
                                                    <td rowspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }}">
                                                        {{ ' Self plan ' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width:20%">#</th>
                                                    <th  rowspan="">{{"Baseline"}}</th>
                                                    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                                        <th>
                                                            {{ $period->reportingPeriodTs[0]->name }}
                                                        </th>
                                                    @empty
                                                    @endforelse

                                                    @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                                <tr>
                                                    <td style="width:20%">
                                                        {{ $one->kpiChildOneTranslations[0]->name }}
                                                    </td>
                                                    @php
                                                        $baselineOfOfficePlan  = OnlyKpiOttBaseline($planAcc->Kpi->id,$office_four_self, $planning_year->id, $period->id,$one->id,null,null);
                                                        //dump($office);
                                                    @endphp
                                                    <td>
                                                        {{ $baselineOfOfficePlan }}
                                                    </td>
                                                    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                                        <td>
                                                            @php
                                                            // $planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id,false);
                                                                $planOne = $planAcc->OnlyKpiOTT($planAcc->Kpi->id, $office_four_self, $period->id,false,$planning_year->id ?? NULL ,$one->id,null,null);
                                                                $narration = $planAcc->OnlygetNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office_four_self, $period->id);
                                                                $office_level = $office_four_self->level;
                                                                if($office_level == 0) $office_level=1;
                                                            @endphp
                                                        @if($planOne[2] <= $office_level)
                                                                {{ $planOne[0] }} 
                                                            @else
                                                                {{0}}
                                                            @endif
                                                            <!-- {{ $planOne[0] }} -->
                                                        </td>
                                                    @empty
                                                    @endforelse
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <th>
                                                        Major Activities
                                                    </th>
                                                    <td colspan="{{count(getQuarter($planAcc->Kpi->reportingPeriodType->id))+1}}">
                                                        @foreach ($narration as $key => $plannaration)
                                                            {!! html_entity_decode($plannaration->plan_naration) !!}
                                                            @php
                                                                echo '<br/>';
                                                            @endphp
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </table>
                                            <!-- end of table to display leader(director) office plan in the level -->
                                        </div>
                                    </div>
                                @empty
                                    <h4>on child!</h4>
                                @endforelse
                                 <!-- start of table to display leader(director) office plan in the level-->
                                <table class="table table-bordered" >
                                    <tr>
                                        <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 2 }} ">
                                            Offices: {{ $office_three_self->officeTranslations[0]->name }}
                                            </td>
                                        <td rowspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }}">
                                              {{ ' Self plan ' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th  rowspan="">{{"Baseline"}}</th>
                                        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                            <th>
                                                {{ $period->reportingPeriodTs[0]->name }}
                                            </th>
                                        @empty
                                        @endforelse

                                        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                                    <tr>
                                        <td>
                                            {{ $one->kpiChildOneTranslations[0]->name }}
                                        </td>
                                        @php
                                            $baselineOfOfficePlan  = OnlyKpiOttBaseline($planAcc->Kpi->id,$office_three_self, $planning_year->id, $period->id,$one->id,null,null);
                                            //dump($office);
                                        @endphp
                                        <td>
                                            {{ $baselineOfOfficePlan }}
                                        </td>
                                        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                            <td>
                                                @php
                                                // $planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id,false);
                                                    $planOne = $planAcc->OnlyKpiOTT($planAcc->Kpi->id, $office_three_self, $period->id,false,$planning_year->id ?? NULL ,$one->id,null,null);
                                                    $narration = $planAcc->OnlygetNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office_three_self, $period->id);
                                                    $office_level = $office_three_self->level;
                                                    if($office_level == 0) $office_level=1;
                                                @endphp
                                            @if($planOne[2] <= $office_level)
                                                    {{ $planOne[0] }} 
                                                @else
                                                    {{0}}
                                                @endif
                                                <!-- {{ $planOne[0] }} -->
                                            </td>
                                        @empty
                                        @endforelse
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th>
                                            Major Activities
                                        </th>
                                        <td colspan="{{count(getQuarter($planAcc->Kpi->reportingPeriodType->id))+1}}">
                                            @foreach ($narration as $key => $plannaration)
                                                {!! html_entity_decode($plannaration->plan_naration) !!}
                                                @php
                                                    echo '<br/>';
                                                @endphp
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                                <!-- end of table to display leader(director) office plan in the level -->
                            </div>
                        </div>
                    @empty
                        <h4>on child!</h4>
                    @endforelse
                    <!-- start of table to display leader(director) office plan in the level-->
                    <table class="table table-bordered" >
                        <tr>
                            <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 2 }} ">
                                Offices: {{ $office_two_self->officeTranslations[0]->name }}
                                </td>
                            <td rowspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }}">
                                {{ ' Self plan ' }}
                            </td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th  rowspan="">{{"Baseline"}}</th>
                            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                <th>
                                    {{ $period->reportingPeriodTs[0]->name }}
                                </th>
                            @empty
                            @endforelse

                            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                        <tr>
                            <td>
                                {{ $one->kpiChildOneTranslations[0]->name }}
                            </td>
                            @php
                                $baselineOfOfficePlan  = OnlyKpiOttBaseline($planAcc->Kpi->id,$office_two_self, $planning_year->id, $period->id,$one->id,null,null);
                                //dump($office);
                            @endphp
                            <td>
                                {{ $baselineOfOfficePlan }}
                            </td>
                            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                <td>
                                    @php
                                    // $planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id,false);
                                        $planOne = $planAcc->OnlyKpiOTT($planAcc->Kpi->id, $office_two_self, $period->id,false,$planning_year->id ?? NULL ,$one->id,null,null);
                                        $narration = $planAcc->OnlygetNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office_two_self, $period->id);
                                        $office_level = $office_two_self->level;
                                        if($office_level == 0) $office_level=1;
                                    @endphp
                                @if($planOne[2] <= $office_level)
                                        {{ $planOne[0] }} 
                                    @else
                                        {{0}}
                                    @endif
                                    <!-- {{ $planOne[0] }} -->
                                </td>
                            @empty
                            @endforelse
                        </tr>
                        @endforeach
                        <tr>
                            <th>
                                Major Activities
                            </th>
                            <td colspan="{{count(getQuarter($planAcc->Kpi->reportingPeriodType->id))+1}}">
                                @foreach ($narration as $key => $plannaration)
                                    {!! html_entity_decode($plannaration->plan_naration) !!}
                                    @php
                                        echo '<br/>';
                                    @endphp
                                @endforeach
                            </td>
                        </tr>
                    </table>
                    <!-- end of table to display leader(director) office plan in the level -->
                </div>
            </div>
        @empty
            <h4>on child!</h4>
        @endforelse

        <!-- table to display leader(director) office plan in the level--> 
        <table class="table table-bordered" >
        {{-- style="background:#34214322;" --}}
            <tr>
                <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 2 }} ">
                    Offices: {{ $office_one_self->officeTranslations[0]->name }}
                    </td>
                <td rowspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }}">
                     {{ ' Self plan ' }}
                 </td>
            </tr>
            <tr>
                <th>#</th>
                <th  rowspan="">{{"Baseline"}}</th>
                @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                    <th>
                        {{ $period->reportingPeriodTs[0]->name }}
                    </th>
                @empty
                @endforelse

                @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            <tr>
                <td>
                    {{ $one->kpiChildOneTranslations[0]->name }}
                </td>
                @php
                    $baselineOfOfficePlan  = OnlyKpiOttBaseline($planAcc->Kpi->id,$office_one_self, $planning_year->id, $period->id,$one->id,null,null);
                    //dump($office);
                @endphp
                <td>
                    {{ $baselineOfOfficePlan }}
                </td>
                @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                    <td>
                        @php
                        // $planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id,false);
                            $planOne = $planAcc->OnlyKpiOTT($planAcc->Kpi->id, $office_one_self, $period->id,false,$planning_year->id ?? NULL ,$one->id,null,null);
                            $narration = $planAcc->OnlygetNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office_one_self, $period->id);
                            $office_level = $office_one_self->level;
                            if($office_level == 0) $office_level=1;
                        @endphp
                    @if($planOne[2] <= $office_level)
                            {{ $planOne[0] }} 
                        @else
                            {{0}}
                        @endif
                        <!-- {{ $planOne[0] }} -->
                    </td>
                @empty
                @endforelse
            </tr>
            @endforeach
            <tr>
                <th>
                    Major Activities
                </th>
                <td colspan="{{count(getQuarter($planAcc->Kpi->reportingPeriodType->id))+1}}">
                    @foreach ($narration as $key => $plannaration)
                        {!! html_entity_decode($plannaration->plan_naration) !!}
                        @php
                            echo '<br/>';
                        @endphp
                    @endforeach
                </td>
            </tr>
        </table>

    </div>
</div>
