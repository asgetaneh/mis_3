@extends('layouts.app')
@section('title', 'Task Index')

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
                <div class="col-md-6 text-right">
                    <a href="{{ route('TaskMeasurements.create') }}" class="btn btn-primary">
                        <i class="icon ion-md-add"></i> @lang('crud.common.create')
                    </a>
                 </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="card-title">{{"Task Measurements List"}}</h4>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>                                     
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">
                                            Name
                                        </th>
                                        <th class="text-left">
                                            Description
                                        </th>
                                        {{-- <th class="text-left">
                                            expected value
                                        </th>    --}}
                                        <th class="text-center">
                                            @lang('crud.common.actions')
                                        </th>
                                    </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp

                            @forelse($task_measurements as $task_measurement) 
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>
                                            {{ $task_measurement->name ?? '-' }}
                                        </td>
                                        <td>
                                            {!! html_entity_decode($task_measurement->description ?? '-'  ) !!}
                                        </td>
                                         
                                        {{-- <td>
                                            {{ $task_measurement->expected_value }}
                                        </td> --}}
                                        <td class="text-center" style="width: 134px;">
                                            <div role="group" aria-label="Row Actions" class="btn-group">
                                                {{-- @can('update', $task) --}}
                                                    <a
                                                        href="{{ route('TaskMeasurements.edit', $task_measurement) }}">
                                                        <button type="button" class="btn btn-light">
                                                            <i class="icon ion-md-create"></i>
                                                        </button>
                                                    </a>
                                                {{-- @endcan
                                                @can('view', $task) --}}
                                                    <a
                                                        href="{{ route('TaskMeasurements.show', $task_measurement) }}">
                                                        <button type="button" class="btn btn-light">
                                                            <i class="icon ion-md-eye"></i>
                                                        </button>
                                                    </a>
                                                {{-- @endcan
                                                @can('delete', $task) --}}
                                                    <form
                                                        action="{{ route('TaskMeasurements.destroy', $task_measurement) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-light text-danger">
                                                            <i class="icon ion-md-trash"></i>
                                                        </button>
                                                    </form>
                                                
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
                {{-- <div class="float-right">
                    {!! $objective->objectiveTranslations[0]->render() !!}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
