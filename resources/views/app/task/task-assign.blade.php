@extends('layouts.app')
@section('title', 'Assign Task')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <a href="{{ route('tasks.index') }}" class="mr-4"><i
                            class="icon ion-md-arrow-back"></i></a>
                            Add Performers for Task: ({{ $task->name }})
                </h4>
                <br>
                <x-form method="POST" action="{{ route('task-assign.store') }}" class="mt-4">
                    @include('app.task.task-assign-form-input')
                    <button type="submit" class="btn btn-primary float-right">
                        <i class="icon ion-md-save"></i>
                        Save
                    </button>
                    <div class="mt-4">
                        <a href="{{ route('tasks.index') }}" class="btn btn-light">
                            <i class="icon ion-md-return-left text-primary"></i>
                            @lang('crud.common.back')
                        </a>
                    </div>
                </x-form>
                <br>
                <div class="table-responsive">
                    <h4 class="card-title"><u>
                        Added Performers list for task: ({{ $task->name }})</u>
                    </h4>
                    <div class="p-3"></div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-left">
                                    Performer Name
                                </th>
                                <th>
                                    Start Date
                                </th>
                                <th>
                                    End Date
                                </th>
                                <th>
                                    Expected Value
                                </th>
                                <th class="text-left">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @forelse($assignedPerformers as $performerAdd)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        {{ App\Models\User::find($performerAdd->assigned_to_id)->name ?? '-' }}
                                    </td>
                                    <td>{{ App\Models\TaskAssign::where('task_id', $task->id)->where('assigned_to_id', $performerAdd->assigned_to_id)->first()->start_date ?? '-' }}</td>
                                    <td>{{ App\Models\TaskAssign::where('task_id', $task->id)->where('assigned_to_id', $performerAdd->assigned_to_id)->first()->end_date ?? '-' }}</td>
                                    <td>{{ App\Models\TaskAssign::where('task_id', $task->id)->where('assigned_to_id', $performerAdd->assigned_to_id)->first()->expected_value ?? '-' }}</td>
                                    <td>
                                        <div role="group" aria-label="Row Actions" class="btn-group">

                                            {{-- <a href="{{ route('tasks.edit', $performerAdd->assigned_to_id) }}">
                                                <button type="button" class="btn btn-light">
                                                    <i class="icon ion-md-create"></i>
                                                </button>
                                            </a> --}}

                                            @php
                                            $user = auth()->user();
                                            @endphp
                                            {{-- @if($user->hasPermission('view tasks')) --}}
                                                <form
                                                    action="{{ route('task-remove.destroy', [$performerAdd->assigned_to_id, $task->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-light text-danger" title="Remove Performer">
                                                        <i class="icon ion-md-trash"></i>
                                                    </button>
                                                </form>
                                            {{-- @endif --}}

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('.select2').select2();

        });
    </script>

    <script>

        $(document).ready(function() {

            // sets the date input to Ethiopian using keith wood jquery plugin
            var calendar = $.calendars.instance('ethiopian', 'am');
            $('#start_date').calendarsPicker({calendar: calendar});
            $('#end_date').calendarsPicker({calendar: calendar});

        });
    </script>

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/inputmask/jquery.inputmask.bundle.js') }}"></script>

    <script>

        $(":input[data-inputmask-mask]").inputmask();
        $(":input[data-inputmask-alias]").inputmask();
        $(":input[data-inputmask-regex]").inputmask("Regex");

    </script>
@endsection
