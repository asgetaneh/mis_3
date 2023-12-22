@extends('layouts.app')
@section('title', 'Assigned Tasks Index')

@section('style')
    <style>
        /* Prevent any data making a break on the td tags */
        .td-wrap {
            white-space: nowrap !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="searchbar mt-0 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <form>
                        <div class="input-group">
                            <input id="indexSearch" type="text" name="search" placeholder="{{ __('crud.common.search') }}"
                                value="{{ $search ?? '' }}" class="form-control" autocomplete="off" />
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon ion-md-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="card-title">Assigned Tasks List</h4>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-left">
                                    Task Name
                                </th>

                                <th class="text-left">
                                    Description
                                </th>
                                <th class="text-left">
                                    Start Date
                                </th>
                                <th class="text-left">
                                    End Date
                                </th>
                                <th class="text-left">
                                    Expected Value
                                </th>
                                {{-- <th class="text-left">
                                    Time Gap
                                </th> --}}
                                <th class="text-center">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @forelse($assignedTasks as $task)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $task->task?->name ?? '-' }}</td>
                                    <td>{!! html_entity_decode($task->task->description) ?? '-' !!} </td>
                                    <td class="td-wrap">{{ $task->start_date ?? '-' }} </td>
                                    <td class="td-wrap">{{ $task->end_date ?? '-' }} </td>
                                    <td class="td-wrap">{{ $task->expected_value }} </td>
                                    {{-- <td>{{ $task->time_gap ?? '-' }}</td> --}}
                                    <td class="text-center" style="width: 134px;">
                                        <div role="group" aria-label="Row Actions" class="btn-group">
                                            {{-- @can('update', $task) --}}

                                            @php
                                                $activePeriodList = getReportingQuarter($task->task->period->reportingPeriodType->id);
                                                $activePeriodArray = [];
                                            @endphp

                                            @forelse ($activePeriodList as $period)
                                                @php
                                                    array_push($activePeriodArray, $period->id);
                                                @endphp
                                            @empty
                                            @endforelse

                                            @if (!in_array($task->task->period->id, $activePeriodArray))
                                                <p class="text-danger">Reporting time expired!</p>
                                            @else
                                                @if ($task->status === 1)
                                                    <a class="btn btn-sm btn-flat btn-info text-white report-task"
                                                        id="report-task" data-toggle="modal" data-target="#report-modal"
                                                        data-task-id="{{ $task->id }}">
                                                        <i class="fas fa fa-list mr-1"></i>Report
                                                    </a>
                                                @elseif($task->status === 0)
                                                    <form action="{{ route('assigned-task.status') }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to accept this Task?')"
                                                        class="mr-2">
                                                        @csrf
                                                        <input type="hidden" value="{{ $task->id }}"
                                                            name="taskAssignId">
                                                        <input type="hidden" value="accept" name="type">

                                                        <button type="submit" class="btn btn-sm btn-flat btn-success"
                                                            value="accept">
                                                            <i class="fas fa fa-check"></i>
                                                            Accept
                                                        </button>
                                                    </form>

                                                    <a class="btn btn-sm btn-flat btn-danger reject-task" id="reject-task"
                                                        data-toggle="modal" data-target="#reject-modal"
                                                        data-task-id="{{ $task->id }}">
                                                        <i class="fas fa fa-ban mr-1"></i>Reject
                                                    </a>
                                                @elseif($task->status === 2)
                                                    <span class="badge bg-info p-1">TASK REPORTED</span>
                                                @elseif($task->status === 3)
                                                    <span class="badge bg-success p-1">TASK ACCEPTED</span>
                                                @endif
                                            @endif
                                            {{-- @endcan --}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="float-right">
                        {!! $assignedTasks->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Task modal --}}
    <div class="modal fade reject-modal" id="reject-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title">Reject Task</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('assigned-task.status') }}" method="POST" id=""
                    onsubmit="return validateForm()">
                    @csrf
                    <input type="hidden" value="reject" name="type">
                    <input type="hidden" value="" name="taskAssignId" id="reject-task-id-modal">

                    <div class="modal-body">
                        <h5 class="reject-task bg-light border p-3"><u>Enter Reject Reason:</u></h5>
                        <textarea class="form-control summernote" name="reject_text" id="reject-task-summernote" cols="30" rows="10"
                            placeholder="Enter your reason..."></textarea>
                        <p class="reject-task-field text-danger" style="display: none;">Please fill the form!</p>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    {{-- Report form Modal --}}
    <div class="modal fade report-modal" id="report-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title">Report Task</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('assigned-task.report') }}" method="POST" id=""
                    onsubmit="return validateReportForm()">
                    @csrf

                    <input type="hidden" value="" name="taskAssignId" id="report-task-id-modal">

                    <div class="modal-body">

                        <label for="reported-value">Enter Result Value</label>
                        <input type="number" class="form-control" name="reported-value" id="reported-value" required>
                        <br>

                        <label for="report-task-summernote">Description</label>
                        <textarea class="form-control summernote" name="report_text" id="report-task-summernote" cols="30"
                            rows="10" placeholder="Enter your description..."></textarea>
                        <p class="report-task-field text-danger" style="display: none;">Please fill the description!</p>
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 200
            });
        });

        $(document).ready(function() {
            $('.reject-task').click(function() {
                var taskId = $(this).data('task-id');
                $('#reject-task-id-modal').val(taskId);
                $('#reject-modal').show();
            });
        });

        $(document).ready(function() {
            $('.report-task').click(function() {
                var taskId = $(this).data('task-id');
                $('#report-task-id-modal').val(taskId);
                $('#report-modal').show();
            });
        });

        function validateForm() {

            $(`.reject-task-field`).css("display", "none");
            let rejectTaskField = '#reject-task-summernote';

            if ($(rejectTaskField).length > 0 && $(rejectTaskField).summernote('isEmpty')) {

                // // focus on the empty field
                // $(`.reject-task-field`).focus();
                // $(`.reject-task-field`).summernote('focus');

                $(`.reject-task-field`).css("display", "block");

                // cancel submit
                return false;
                preventDefault();
            }
        }

        function validateReportForm() {

            $(`.report-task-field`).css("display", "none");
            let reportTaskField = '#report-task-summernote';

            if ($(reportTaskField).length > 0 && $(reportTaskField).summernote('isEmpty')) {

                // // focus on the empty field
                // $(`.reject-task-field`).focus();
                // $(`.reject-task-field`).summernote('focus');

                $(`.report-task-field`).css("display", "block");

                // cancel submit
                return false;
                preventDefault();
            }
        }
    </script>

@endsection
