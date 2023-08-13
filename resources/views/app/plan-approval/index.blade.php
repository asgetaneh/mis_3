@extends('layouts.app')

@section('style')
    <style>
        tr,
        th,
        td {
            padding: 10px;
        }

        table {
            border-collapse: collapse;
        }
    </style>
@endsection

@section('content')

    <div class="row justify-content-center mt-5">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        @php
                            $kpi_repeat[0] = '0';
                            $c = 1;
                            $first = 1;
                        @endphp
                        @forelse($planAccomplishments as $planAcc)
                            @php

                                $offices = getOfficeFromKpiAndOfficeList($planAcc->Kpi, $only_child_array);
                                $period = getQuarter($planAcc->Kpi->reportingPeriodType->id);
                                $isOfficeBelongToKpi = getKpiImmediateChilds($only_child_array);
                            @endphp

                            @if (!in_array($planAcc->Kpi->id, $kpi_repeat))
                                <div class="card">
                                    <div class="card-header">

                                        @forelse($planAcc->Kpi->KeyPeformanceIndicatorTs as $kpiT)
                                            @if (app()->getLocale() == $kpiT->locale)
                                                <table class="table">
                                                    <tr style="background:#CDCDCD;" class="">
                                                        <th style="width:80%;"> KPI: {{ $kpiT->name }}</th>
                                                        <th> <input name="sum" class="form-control" type="number"
                                                                value="{{ $planAcc->sum }}">
                                                        </th>
                                                        <th>
                                                            <button type="button" class="btn btn-tool bg-primary"
                                                                data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </table>
                                            @endif
                                        @empty
                                            <h4>No KPI name!</h4>
                                        @endforelse

                                    </div>
                                    <div class="card-body" style="display: block;">
                                        {{-- If KPI has Child ones (UG, PG) --}}
                                        <form method="POST" action="{{ route('plan-approve') }}" class="">
                                            @csrf
                                            <div class="icheck-success float-right bg-light border p-3">
                                                <input class="checkAllOffices" name="checkAll" type="checkbox"
                                                    id="checkAll{{ $planAcc->kpi_id }}" value="{{ $planAcc->kpi_id }}">
                                                <label for="checkAll{{ $planAcc->kpi_id }}">
                                                    Select All
                                                </label>
                                            </div>
                                            @forelse($offices  as $office)
                                                {{-- @dd($offices) --}}

                                                {{-- get all children of current office so that its parent can see the sum of the plan for the current kpi plan --}}
                                                @php
                                                    $hasChildrenOfficesPlannedAndApproved = getOfficeChildrenApprovedList($planAcc->kpi_id, $office, $planAcc->planning_year_id, 1);
                                                @endphp

                                                {{-- if the office has children that have a plan for the current kpi and are also approved by him,
                                                    so that add their sum with their parent and display to the leader --}}
                                                {{-- @dd($hasChildrenOfficesPlannedAndApproved) --}}
                                                @if (count($hasChildrenOfficesPlannedAndApproved) > 0)
                                                    @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                    @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                        @include('app.plan-approval.kpi-unapproved.kpi123')
                                                                        {{-- KPI has  child one and child two --}}
                                                                    @else
                                                                        @include('app.plan-approval.kpi-unapproved.kpi12')
                                                                    @endif
                                                                    {{-- KPI has  child one only --}}
                                                                @else
                                                                    @include('app.plan-approval.kpi-unapproved.kpi1')
                                                                @endif

                                                            </thead>
                                                        </table>
                                                        {{-- KPI has no child one, which means just only plain input --}}
                                                    @else
                                                        @include('app.plan-approval.kpi-unapproved.kpi')
                                                    @endif
                                                @else
                                                    @php
                                                        $anyChildrenOfficePlannedAndApproved = getOfficeChildrenApprovedList($planAcc->kpi_id, $office, $planAcc->planning_year_id, 2);
                                                    @endphp
                                                    {{-- @dd($anyChildrenOfficePlannedAndApproved) --}}

                                                    {{-- Including current office and its children, are they approved and they are up to their grandfather --}}
                                                    @if (count($anyChildrenOfficePlannedAndApproved) > 0)
                                                        @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                        @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                            @include('app.plan-approval.kpi-approved.kpi123-with-approved')
                                                                            {{-- KPI has  child one and child two --}}
                                                                        @else
                                                                            @include('app.plan-approval.kpi-approved.kpi12-with-approved')
                                                                        @endif
                                                                        {{-- KPI has  child one only --}}
                                                                    @else
                                                                        @include('app.plan-approval.kpi-approved.kpi1-with-approved')
                                                                    @endif

                                                                </thead>
                                                            </table>
                                                            {{-- KPI has no child one, which means just only plain input --}}
                                                        @else
                                                            @include('app.plan-approval.kpi-approved.kpi-with-approved')
                                                        @endif
                                                    @else
                                                        {{-- check if direct child has plan --}}
                                                        @php
                                                            $hasOfficePlan = getOfficePlanRecord($planAcc->kpi_id, $office, $planAcc->planning_year_id);
                                                        @endphp
                                                        {{-- @dd($hasOfficePlan) --}}

                                                        @if ($hasOfficePlan->count() > 0)
                                                            @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                                            @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                                @include('app.plan-approval.office-plan-only.office-plan-only123')
                                                                                {{-- KPI has  child one and child two --}}
                                                                            @else
                                                                                @include('app.plan-approval.office-plan-only.office-plan-only12')
                                                                            @endif
                                                                            {{-- KPI has  child one only --}}
                                                                        @else
                                                                            @include('app.plan-approval.office-plan-only.office-plan-only1')
                                                                        @endif

                                                                    </thead>
                                                                </table>
                                                                {{-- KPI has no child one, which means just only plain input --}}
                                                            @else
                                                                @include('app.plan-approval.office-plan-only.office-plan-only')
                                                            @endif
                                                        @else
                                                            @php
                                                                $isOfficeBelongToKpi = isOfficeBelongToKpi($office, $planAcc->Kpi->id);
                                                            @endphp

                                                            @if ($isOfficeBelongToKpi->count() > 0)
                                                                @if (count(office_all_childs_ids($office)) > 0)
                                                                    <p>Office
                                                                        <u>{{ $office->officeTranslations[0]->name }}</u>
                                                                        has
                                                                        no plan or not approved its children yet!
                                                                    </p>
                                                                @else
                                                                    <p>Office
                                                                        <u>{{ $office->officeTranslations[0]->name }}</u>
                                                                        has
                                                                        not planned for this KPI yet!
                                                                    </p>
                                                                @endif
                                                            @else
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @empty
                                                <h4>No offices!</h4>
                                            @endforelse

                                            @php
                                                $officeNameList = [];
                                            @endphp
                                            @forelse ($offices as $key => $office)
                                                @php
                                                    // array_push($officeNameList, $office->officeTranslations[0]->name);
                                                    $officeNameList[$office->id] = $office->officeTranslations[0]->name;
                                                @endphp
                                            @empty
                                            @endforelse

                                            {{-- @dd($officeNameList) --}}
                                            <tr>
                                                <td colspan="8">
                                                    <button type="submit" class="btn btn-primary float-right"
                                                        onclick="return checkSelectedOffices({{ $planAcc->kpi_id }}, {{ json_encode($officeNameList) }})"><i
                                                            class="fa fa-check nav-icon"></i> Approve</button>
                                                </td>
                                            </tr>
                                        </form>
                                    </div>
                                </div>

                                @php
                                    $kpi_repeat[$c] = $planAcc->Kpi->id;
                                    $c++;

                                @endphp
                            @endif
                        @empty
                            <p>No KPI!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Modal for comment --}}
    <div class="modal fade vehicle-modal" id="modal-lg" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-primary">Write Comment for Office: (office here...)</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('plan-comment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="" class="" value="">
                    <div class="modal-body vehicle-modal-body">
                        {{-- content to be filled after ajax request here --}}
                        <textarea class="form-control summernote" name="plan_comment" id="" cols="30" rows="10"
                            placeholder="Enter your comment"></textarea>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    {{-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}

    {{-- <script>
        $(document).ready(function() {
            $('#goal-card').on('click', '.goal-list', function() {
                var goalId = $(this).attr('data-id');

                var url = "{{ route('get-objectives', [':id']) }}";
                url = url.replace(':id', goalId);

                $('.fillable-objective').empty();

                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function(response) {

                        // foreach(response as r){
                        //     console.log(r)
                        // }
                        console.log(response);
                        $('.fillable-objective').html(response);
                    }
                });

            })

        })
    </script> --}}

    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 200
            });
            $('.dropdown-toggle').dropdown()
        });
    </script>

    <script>
        function checkSelectedOffices(kpiId, offices) {

            let selector = `.office-checkbox-kpi-${kpiId}`;
            let officeNamesObject = offices;

            // Get the checkbox elements
            var checkboxes = $(selector);

            let unselectedOffices = [];
            let officeIdList = [];
            let officesNameList = [];

            // get all unchecked offices value
            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    continue;
                } else {
                    unselectedOffices.push(checkboxes[i].value);
                }
            }

            // check if there are unselected checkboxes of offices then store their id
            if (unselectedOffices !== []) {
                for (let i = 0; i < unselectedOffices.length; i++) {
                    let office = unselectedOffices[i].split("-")
                    officeIdList.push(office[1])
                }
            }

            // check if the unselected offices id exist in the object
            for (let id in officeNamesObject) {
                if (officeIdList.includes(id)) {
                    officesNameList.push(officeNamesObject[id])
                }
            }

            // merge all unselected offices as single string
            let mergedOfficeNames = '';
            if (officesNameList !== []) {
                for (let i = 0; i < officesNameList.length; i++) {
                    mergedOfficeNames += `${officesNameList[i]}, `;
                }
            }

            // let remind the user if there are unselected offices
            if (mergedOfficeNames) {
                return confirm(`You have not selected '${mergedOfficeNames}', Are you sure you want to continue?`);
            } else {
                return confirm('Are you sure you want to approve all selected offices?')
            }

            // return true;

        }

        // select all checkboxes at once
        let checkAllList = $('.checkAllOffices');
        console.log(checkAllList);

        for (let i = 0; i < checkAllList.length; i++) {

            let selecto = `#${checkAllList[i].id}`;
            $(selecto).change(function() {

                let value = $(selecto).val();

                // var setcheckbox = function() {
                //     this.prop("checked", true);
                // };

                if ($(this).is(':checked')) {
                    $(`.office-checkbox-kpi-${value}`).prop('checked', this.checked);
                } else {
                    $(`.office-checkbox-kpi-${value}`).prop('checked', this.checked);
                }

            });
        }
    </script>

@endsection
