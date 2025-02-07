<style>
    .level-1 {
        background-color: #12cd4322;
        /* Light blue for parent */
    }

    .level-2 {
        background-color: #e6ffe6;
        /* Light green for child */
    }

    .level-3 {
        background-color: #fff8e6;
        /* Light yellow for sub-child */
    }

    .level-4 {
        background-color: #ffe6e6;
        /* Light red for sub-sub-child */
    }
</style>
<table class="table table-bordered" style ="background:#CDCDCD; padding:30">
    <tr>
        <th style="width:30%">
            Offices
        </th>
        <th rowspan="">{{ 'Baseline' }}</th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th>
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
        @empty
        @endforelse
        <td rowspan="3">
            @if (!$office->offices->isEmpty())
                <button class="btn btn-primary btn-expand" data-id="{{ $office->id }}-plain-{{ $planAcc->Kpi->id }}"
                    data-url="{{ url('/smis/plan/plan-accomplishment/' . $office->id . '/details/' . $planAcc->kpi->id. '/kpi/' . $planAcc->planning_year_id) }}">
                    Details
                </button>
            @else
                {{ 'no child ' }}
            @endif
        </td>
    </tr>
    <tr>
        <td rowspan="" style="width:30%">{{ $office->officeTranslations[0]->name }}</td>
        @php
            $baselineOfOfficePlan = planBaseline(
                $planAcc->Kpi->id,
                $office,
                $planning_year->id,
                $period->id,
                null,
                null,
                null,
            );
        @endphp
        <td>{{ $baselineOfOfficePlan }}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                $planOfOfficePlan = $planAcc->KpiOTT(
                    $planAcc->Kpi->id,
                    $office,
                    $period->id,
                    false,
                    $planning_year->id ?? null,
                    null,
                    null,
                    null,
                );
                $narration = $planAcc->getNarration(
                    $planAcc->Kpi->id,
                    $planning_year->id ?? null,
                    $office,
                    $period->id,
                );
                $userOffice = auth()->user()->offices[0];
                $office_level = $userOffice->level;
                if ($office_level == 0) {
                    $office_level = 1;
                }
            @endphp
            <td>
                @if ($planOfOfficePlan[2]?->plan_status <= $office_level)
                    {{ $planOfOfficePlan[0] }}
                @else
                    {{ 0 }}
                @endif
                <!-- {{ $planOfOfficePlan[0] }} -->
            </td>
        @empty
        @endforelse
    </tr>
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="6">
            @foreach ($narration as $key => $plannaration)
                <p>
                    {!! html_entity_decode($plannaration->plan_naration) !!}
                </p>
            @endforeach
        </td>
    </tr>
</table>
<script>
    const kpi_id = {{ $planAcc->kpi->id }};
    const planning_year = {{ $planAcc->id }};
    const planAccId = {{ $planAcc->id }};
</script>
<div class="table table-bordered details-row"
      id="details-{{ $office->id }}-plain-{{ $planAcc->Kpi->id }}" style="padding: 10px; border: 1px solid; display: none;">
    <table id="details-data-{{ $office->id }}-plain-{{ $planAcc->Kpi->id }}"  style="padding: 10px; width:100%; border: 1px solid;"> </table>
</div>

