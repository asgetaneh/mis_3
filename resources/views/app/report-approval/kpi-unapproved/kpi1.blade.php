<tr>
    {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
    {{-- @if(planStatusOffice($office, $planAcc->kpi_id, $planning_year[0]->id) == 0) --}}
        <th class="bg-light">
            {{-- <input class="form-check" type ="checkbox" name="approve[]" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year[0]->id}}"
            title="Appove for {{$office->officeTranslations[0]->name}}"/> --}}

            <div class="icheck-success d-inline">
                <input class="office-checkbox-kpi-{{ $planAcc->kpi_id }}" name="approve[]" type="checkbox" id="{{$office->id}}" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year[0]->id}}">
                <label for="{{$office->id}}">
                    Select Office
                </label>
            </div>
        </td>

    {{-- @else
        <th>
            <p class="badge badge-success d-inline">APPROVED</p>
        </th>
    @endif --}}
<th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }} ">
    Offices: {{ $office->officeTranslations[0]->name }}
    </td>

    {{-- @if (planStatusOffice($office, $planAcc->kpi_id, $planning_year[0]->id) > auth()->user()->offices[0]->level) --}}
    @if (empty(reportCommentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year[0]->id)))
        <a class="btn btn-sm float-right btn-info text-white write-comment"
            data-toggle="modal" data-target="#modal-lg"
            data-id="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year[0]->id}}">
            <i class="fas fa fa-comments mr-1"></i> Write Comment
        </a>
    @else
        @if (!empty(reportCommentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year[0]->id)) && reportCommentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year[0]->id)->reply_comment !== null)
            <a class="btn btn-sm float-right btn-info text-white view-reply-comment"
                data-toggle="modal" data-target="#view-reply-comment"
                data-id="{{ $office->id }}-{{ reportCommentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year[0]->id)->id }}-{{$planAcc->Kpi->id}}-{{$planning_year[0]->id}}">
                <i class="fas fa fa-eye mr-1"></i> View Reply
            </a>
        @else
            <p class="float-right text-primary"><u>Waiting for reply!</u></p>
        @endif
    @endif
    {{-- @endif --}}
</tr>
<tr>
<th>#</th>
@forelse(getReportingQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
    <th>
        {{ $period->reportingPeriodTs[0]->name }}
    </th>
    @php
        $childAndHimOffKpi_array = [];
        $childAndHimOffKpi = $office->offices;
        foreach ($childAndHimOffKpi as $key => $value) {
            $childAndHimOffKpi_array[$key] = $value->id;
        }
        $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
        $planKpiOfficeYear = reportSumOfKpi($planAcc->Kpi->id, $office, 1);
    @endphp
@empty
@endforelse

@foreach ($planAcc->Kpi->kpiChildOnes as $one)
<tr>
<td>
    {{ $one->kpiChildOneTranslations[0]->name }}
</td>
@forelse(getReportingQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
    <td>
        @php
            $planOne = reportOne($planAcc->Kpi->id, $one->id, $office, $period->id, 1);
            $narration = getReportNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
        @endphp
        {{ $planOne }}
    </td>
@empty
@endforelse
</tr>
@endforeach{{--
<tr>
<th rowspan="1">
    {{ 'sum' }}
</th>
<th rowspan="1">
    {{ $planKpiOfficeYear }}
</th>
</tr> --}}
<tr>
<td>
    Major Activities
</td>
<td colspan="5">
    @foreach ($narration as $key => $plannaration)
          {!! html_entity_decode($plannaration->report_naration) !!}
          @php
          echo "<br/>"
          @endphp
    @endforeach
</td>
</tr>
@foreach ($planAcc->Kpi->kpiChildOnes as $one)
@endforeach

{{-- total ch2
        <td>
        @php
            $planSumch2_array= [];
            $planSumch2 = $office->offices;
            foreach ($planSumch2 as $key => $value) {
                $planSumch2_array[$key] = $value->id;
            }
            $planSumch2_array = array_merge($planSumch2_array, array($office->id));

            $planSumch1Total = $planAcc->planIndividualChOneSum($planAcc->Kpi->id,  $planSumch2_array);
            @endphp
            {{$planSumch1Total}}
        </td> --}}
{{-- end total ch2 --}}
</tr>
