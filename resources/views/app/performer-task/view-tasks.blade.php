@extends('layouts.app')
@section('title', 'View Task History')

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
        {{-- <div class="searchbar mt-0 mb-4">
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
        </div> --}}

        <div class="w-25 mb-3">
            <div>
                <form action="{{ route('assigned-task.history') }}" method="get">
                    <label for="filter-status">Status</label>
                    <div class="d-flex">
                        <select name="status-filter" id="filter-status" class="form-control select2">
                            <option disabled selected value="iuy">Filter By Status</option>
                            <option {{ old('status-filter') == 2 ? 'selected' : '' }} value="2">REJECTED</option>
                            <option {{ old('status-filter') == 3 ? 'selected' : '' }} value="3">REPORTED</option>
                            <option {{ old('status-filter') == 4 ? 'selected' : '' }} value="4">EVALUATED</option>
                        </select>
                        <button id="btn-filter" type="submit" class="btn btn-primary ml-2">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="card-title">Task History</h4>
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
                                <th class="text-left">
                                    Reported Value
                                </th>
                                <th class="text-left">
                                    Time Gap
                                </th>
                                <th class="text-center">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @forelse($taskHistory as $task)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $task->task?->name ?? '-' }}</td>
                                    <td>{!! html_entity_decode($task->task->description) ?? '-' !!} </td>
                                    <td class="td-wrap">{{ $task->start_date ?? '-' }} </td>
                                    <td class="td-wrap">{{ $task->end_date ?? '-' }} </td>
                                    <td class="td-wrap">{{ $task->expected_value ?? '-' }} </td>
                                    <td class="td-wrap">{{ $task->taskAccomplishments?->reported_value ?? '-' }} </td>
                                    <td>{{ $task->time_gap ?? '-' }}</td>
                                    <td class="text-center" style="width: 134px;">
                                        <div>
                                            {{-- @can('update', $task) --}}

                                            {{-- @if ($task->status === 1)
                                                <span class="badge bg-success p-1">ACCEPTED</span>

                                            @elseif($task->status === 0)
                                                <span class="badge bg-info p-1">ASSIGNED</span> --}}
                                            @if($task->status === 2)
                                                <span class="badge bg-danger p-1">TASK REJECTED</span>
                                            @elseif($task->status === 3)
                                                <p><span class="badge bg-success p-1">TASK REPORTED</span></p>
                                                <p><a title="View Report" class="btn btn-sm btn-info view-report" id="view-report-btn"
                                                    data-toggle="modal" data-target="#view-report"
                                                    data-id="{{ $task->id }}">
                                                    <i class="fas fa fa-eye mr-1"></i>View Report
                                                </a></p>

                                            @elseif($task->status === 4)
                                            <p><span class="badge bg-success p-1">TASK EVALUATED</span></p>
                                            <p><a title="View Evaluation" class="btn btn-sm btn-info view-evaluation" id="view-evaluation-btn"
                                                data-toggle="modal" data-target="#view-evaluation"
                                                data-id="{{ $task->id }}">
                                                <i class="fas fa fa-eye mr-1"></i>View Evaluation
                                            </a></p>
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
                    {!! $taskHistory->render() !!}
                </div>
                </div>
            </div>
        </div>
    </div>

    {{-- View Evaluation modal --}}
    <div class="modal fade view-evaluation-modal" id="view-evaluation" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title">Evaluation</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body evaluation-modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Measurement Type</th>
                            <th>Accomplishment Value</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- View Report modal --}}
    <div class="modal fade view-report-modal" id="view-report" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title">Report</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body report-modal-body">
                    <h5>Reported Value:</h5>
                    <p><strong id="reported-value" class=""></strong></p>
                    <h5>Description: </h5>
                    <p><strong id="reported-description"></strong></p>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- View Report --}}
    <script>
        // Listen for the view report click event
        $('#view-report-btn').on('click', function() {

            var id = $(this).attr('data-id');
            console.log(id);

            // AJAX request with the information attached
            var url = "{{ route('performer-report.view-report', [':id']) }}";
            url = url.replace(':id', id);

            // Empty modal data
            $('.report-modal-body #reported-value').empty();
            $('.report-modal-body #reported-description').empty();

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('.report-modal-body #reported-value').html(response.reported_value);
                    $('.report-modal-body #reported-description').html(response.reported_description);

                    $('.view-report-modal').modal('show');
                }
            });

        });
    </script>

    {{-- View Evaluation --}}
    <script>
        // Listen for the view evaluation click event
        $('#view-evaluation-btn').on('click', function() {

            var id = $(this).attr('data-id');
            console.log(id);

            // AJAX request with the information attached
            var url = "{{ route('performer-report.view-evaluation', [':id']) }}";
            url = url.replace(':id', id);

            // Empty modal data
            $('.evaluation-modal-body #evaluated-value').empty();
            $('.evaluation-modal-body #evaluated-description').empty();

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    // console.log(response.looper);

                    if(response.looper.length > 0){
                        $.each(response.looper, function(key, value) {
                            $('.evaluation-modal-body table').append(`
                                <tr>
                                    <td>${value[0]}</td>
                                    <td>${value[1]}</td>
                                </tr>
                            `);
                        });
                    }else{
                        $('.evaluation-modal-body table').html('No Evaluation Made!');
                    }


                    $('.view-evaluation-modal').modal('show');
                }
            });

        });
    </script>

@endsection
