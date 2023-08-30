<table class="table table-bordered" style ="background:#F0F8FF">
    @if ($first == '1')
        <tr>
            <th>
                Offices
            </th>
            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th> 
             {{ $period->reportingPeriodTs[0]->name }} 
            </th>
            @empty
            @endforelse
        </tr>
    @endif
    <tr>
        <td rowspan="2">{{ $office->officeTranslations[0]->name }}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                $planOfOfficePlan = $planAcc->planSum($planAcc->Kpi->id, $office, $period->id);//dump($planOfOfficePlan);
                $narration = $planAcc->getReportNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
            @endphp
            <td>
                {{ $planOfOfficePlan[1] }}
            </td>
        @empty
        @endforelse
    </tr>
    <tr>
        <td>
            Major Activities  
        </td>
        <td colspan="4">
             @foreach ($narration as $key => $plannaration)
                <p>
                    {!! html_entity_decode($plannaration->plan_naration) !!}
                </p>
            @endforeach
        </td>
        <td>
        @if(!$office->offices->isEmpty())
            <p>
                <a class="btn btn-info" data-toggle="collapse"
                    href="#off{{ $office->id }}" role="button"
                    aria-expanded="false" aria-controls="collapseExample0">
                     Details
                </a>
            </p>
             @else
                {{"no child "}}
            @endif
        </td>
    </tr>
</table>
{{-- level two (directores and same level) --}}
<div class="collapse" id="off{{ $office->id }}">
    <div class="card card-body" style="background:#12cd4322;" >
        @php
            $offices_twos = $office->offices;
        @endphp
        @forelse ($offices_twos as $office)
            @include('app.report_accomplishments.view-kpi-duplicate')
            <div class="collapse" id="off{{ $office->id }}">
                <div class="card card-body">
                    @php
                        $offices_threes = $office->offices;
                    @endphp
                    @forelse ($offices_threes as $office)
                        @include('app.report_accomplishments.view-kpi-duplicate')
                        <div class="collapse" id="off{{ $office->id }}">
                            <div class="card card-body">
                                @php
                                    $offices_fours = $office->offices;
                                @endphp
                                @forelse ($offices_fours as $office)
                                    @include('app.report_accomplishments.view-kpi-duplicate')
                                    <div class="collapse" id="off{{ $office->id }}">
                                        <div class="card card-body">
                                            @php
                                                $offices_fives = $office->offices;
                                            @endphp
                                            @forelse ($offices_fives as $office)
                                                @include('app.report_accomplishments.view-kpi-duplicate')
                                                <div class="collapse" id="off{{ $office->id }}">
                                                    <div class="card card-body">
                                                        @php
                                                            $offices_sixs = $office->offices;
                                                        @endphp
                                                        @forelse ($offices_sixs as $office)
                                                            @include('app.report_accomplishments.view-kpi-duplicate')
                                                            <div class="collapse" id="off{{ $office->id }}">
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