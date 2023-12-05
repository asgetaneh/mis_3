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
                
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="card-title">{{"Assigned Task List"}}</h4>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>
                            {{-- @if (!$kpis->empty()) --}}
                                @forelse($performers as $performer)
                                    <tr style="background:#96fffd45;">
                                        <th colspan="8">{{"Performer: "}}{{ $performer->user->name }} </th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">
                                            Name
                                        </th>
                                        <th class="text-left">
                                            Description
                                        </th>
                                        <th class="text-left">
                                            Expected value
                                        </th>
                                        <th class="text-left">
                                            Reported value
                                        </th>
                                        <th class="text-left">
                                            Task measurements
                                        </th>     
                                         <th class="text-left">
                                            Accomplishments Value
                                        </th>                                     
                                    </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp

                            @forelse($performer->getTaskByperformer($performer->id) as $task)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>
                                            {{ $task->name ?? '-' }}
                                        </td>
                                        <td>
                                            {!! html_entity_decode($task->description ?? '-'  ) !!}
                                        </td>
                                         <td>
                                            @php
                                             $assignment =   $task->getTaskAssignment($task->id,$performer->id);
                                            @endphp
                                            {{ $assignment[0]->expected_value }}
                                        </td>
                                        <td>
                                            @if($assignment[0]->taskAccomplishments)
                                            {{ $assignment[0]->taskAccomplishments->reported_value }}
                                            @else
                                            {{"--"}}
                                            @endif
                                        </td>
                                        <td> 
                                            @forelse($task->taskMeasurement  as $task_measure)
                                                {{ $task_measure->name ?? '-' }}<br/>
                                            @empty
                                                {{"--"}}
                                            @endforelse
                                         </td>
                                         <td>
                                            @forelse($task->taskMeasurement  as $task_measure)
                                                @php $acomm_id = 0; @endphp
                                                @if($assignment[0]->taskAccomplishments)
                                                    @php
                                                     $acomm_id = $assignment[0]->taskAccomplishments->id;
                                                    @endphp
                                                @endif
                                                @php
                                                $accomplish_value =   $task->getTaskAccomplishmentValue($task_measure->id, $acomm_id);
                                                 @endphp 
                                                {{ $accomplish_value }}<br/>
                                            @empty
                                                 {{"--"}}
                                            @endforelse
                                            
                                             
                                         </td>
                                       
                                        
                                    </tr>
                             @empty
                                <tr>
                                    <td colspan="7">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                            @endforelse
                        @empty
                            <tr>
                                <td colspan="7">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                            @endforelse

                            {{-- Handle searching --}}
                        {{-- @else
                            @forelse($goals as $goal)
                                @forelse ($objective_ts as $objective)
                                    @if ($objective->objective->goal_id == $goal->id)
                                        <tr style="background:#96fffd45;">
                                            <th colspan="7">{{ $goal->goalTranslations[0]->name }}</th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-left">
                                                Name
                                            </th>
                                            <th class="text-left">
                                                Description
                                            </th>
                                            <th class="text-left">
                                                Output
                                            </th>
                                            <th class="text-left">
                                                Outcome
                                            </th>
                                            <th class="text-right">
                                                @lang('crud.objectives.inputs.weight')
                                            </th>
                                            <th class="text-center">
                                                @lang('crud.common.actions')
                                            </th>
                                        </tr>
                                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @if (app()->getLocale() == $objective->locale)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        {{ $objective->name ?? '-' }}


                                    </td>
                                    <td>
                                        {{ $objective->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $objective->out_put ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $objective->out_come ?? '-' }}
                                    </td>
                                    <td>{{ $objective->objective->weight ?? '-' }}</td>
                                    <td class="text-center" style="width: 134px;">
                                        <div role="group" aria-label="Row Actions" class="btn-group">
                                            @can('update', $objective)
                                                <a href="{{ route('objectives.edit', $objective->translation_id) }}">
                                                    <button type="button" class="btn btn-light">
                                                        <i class="icon ion-md-create"></i>
                                                    </button>
                                                </a>
                                                @endcan @can('view', $objective)
                                                <a href="{{ route('objectives.show', $objective->translation_id) }}">
                                                    <button type="button" class="btn btn-light">
                                                        <i class="icon ion-md-eye"></i>
                                                    </button>
                                                </a>
                                                @endcan @can('delete', $objective)
                                                <form action="{{ route('objectives.destroy', $objective->translation_id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-light text-danger">
                                                        <i class="icon ion-md-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @else
                            @continue
                            @endif
                        @empty
                            <tr>
                                <td colspan="7">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                        @break

                        @endforelse
                    @empty
                        <tr>
                            <td colspan="7">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse --}}

                        {{-- @endif --}}
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
