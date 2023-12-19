<tr>
    <tr>

        {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
        @if(reportStatusOffice($office, $planAcc->kpi_id, $planning_year->id ?? NULL) > auth()->user()->offices[0]->level)
            <th class="bg-light">
                {{-- <input class="form-check" type ="checkbox" name="approve[]" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}"
                title="Appove for {{$office->officeTranslations[0]->name}}"/> --}}

                <div class="icheck-success d-inline">
                    <input class="office-checkbox-kpi-{{ $planAcc->kpi_id }}" name="approve[]" type="checkbox" id="{{ $planAcc->kpi_id }}-{{$office->id}}" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}">
                    <label for="{{ $planAcc->kpi_id }}-{{$office->id}}">
                        Select Office
                    </label>
                </div>
            </td>

        @else
            <th>
                <p class="badge badge-success d-inline">APPROVED</p>
            </th>
        @endif
        <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }} ">
            Offices: {{ $office->officeTranslations[0]->name }}

            <a class="btn btn-sm float-right btn-info text-white write-comment ml-2"
                    data-toggle="modal" data-target="#modal-lg"
                    data-id="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}">
                    <i class="fas fa fa-comments mr-1"></i> Write Comment
                </a>

                @if (reportCommentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year->id ?? NULL, 2)->count() >
                        0)
                    <p class="float-right text-primary"><u>Waiting for reply!</u></p>
                @else
                    @if (
                        !empty(reportCommentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year->id ?? NULL, 1)) &&
                        reportCommentorTextStatus(
                                $office,
                                auth()->user()->offices[0]->id,
                                $planAcc->kpi_id,
                                $planning_year->id ?? NULL,
                                1)->count() > 0)
                        <a class="btn btn-sm view-reply-comment text-primary float-right" data-toggle="modal"
                            data-target="#view-reply-comment"
                            data-id="{{ $office->id }}-{{ 1 }}-{{ $planAcc->Kpi->id }}-{{ $planning_year->id ?? NULL }}">
                            <u id="view-reply-tag"><mark>You've a reply! Click to view</mark></u>                        </a>
                    @endif
                @endif
        </th>
    </tr>
    <td colspan="2  ">#</td>
    @foreach ($planAcc->Kpi->kpiChildOnes as $one)
        <td>
            {{ $one->kpiChildOneTranslations[0]->name }}
        </td>
    @endforeach
    <th>Sum</th>
    </tr>
        @forelse(getReportingQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
             @php
                $childAndHimOffKpi_array = [];
                $childAndHimOffKpi = $office->offices;
                foreach ($childAndHimOffKpi as $key => $value) {
                    $childAndHimOffKpi_array[$key] = $value->id;
                }
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
                $planKpiOfficeYear = reportSumOfKpi($planAcc->Kpi->id, $office, 2);
                $narration = getReportNarration($planAcc->Kpi->id,$planning_year->id ?? NULL, $office, $period->id);
            @endphp
            <tr>
                <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
                    {{ $period->reportingPeriodTs[0]->name }}
                </th>
                @foreach ($planAcc->Kpi->kpiChildTwos as $two)
                    <td>
                        {{ $two->kpiChildTwoTranslations[0]->name }}
                    </td>
                    @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                        <td>
                            @php
                                $planOneTwo = reportOneTwo($planAcc->Kpi->id, $one->id, $two->id, $office, $period->id, 2);
                            @endphp
                            {{ $planOneTwo }}
                        </td>
                    @endforeach
                    {{-- total ch2 --}}
                    <td>
                        @php
                            $planSumch2_array = [];
                            $planSumch2 = $office->offices;
                            foreach ($planSumch2 as $key => $value) {
                                $planSumch2_array[$key] = $value->id;
                            }
                            $planSumch2_array = array_merge($planSumch2_array, [$office->id]);

                            // $planSumch2Total = planIndividualChTwoSum($planAcc->Kpi->id, $two->id, $planSumch2_array,$period->id);
                            $planSumch2Total = reportIndividualChTwoSum($planAcc->Kpi->id, $two->id, $office,$period->id, 2);
                        @endphp
                        {{ $planSumch2Total }}
                    </td>
                    {{-- end total ch2 --}}
            </tr>
        @endforeach
    @empty
        @endforelse
        {{-- total ch1ch3 --}}
        <tr>
            <th colspan='2' style="background:#ffeecc;">
                {{ 'Total' }}
                </td>
                @foreach ($planAcc->Kpi->kpiChildOnes as $one)
            <td>
                @php
                    $offices_array = [];
                    $userChild = $office->offices;
                    foreach ($userChild as $key => $value) {
                        $offices_array[$key] = $value->id;
                    }
                    $offices_array = array_merge($offices_array, [$office->id]);

                    // $planSumch1ch3 = planIndividualChOnech($planAcc->Kpi->id, $one->id, $two->id, $offices_array);
                    $planSumch1ch3 = reportIndividualChOnech($planAcc->Kpi->id, $one->id, $two->id, $office, 2);
                @endphp
                {{ $planSumch1ch3 }}
            </td>
            @endforeach
            <td> {{ $planKpiOfficeYear }}</td>
        </tr>
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
        {{-- end total ch1ch3 --}}
