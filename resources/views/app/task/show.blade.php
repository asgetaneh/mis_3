@extends('layouts.app')
@section('title', 'Show Task')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('tasks.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Show Task Detail
            </h4>

            <div class="mt-4 pt-5">
                <div class="mb-4">
                    <h5>Key Performance Indicator</h5>
                    @forelse($task->kpi->keyPeformanceIndicatorTs as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="">{{ $value->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>No Kpi for this Task!</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Reporting Period</h5>
                    @forelse($task->period->reportingPeriodTs as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="">{{ $value->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>No period for this Task!</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Name</h5>
                    <p class="">{{ $task->name ?? '-' }}</p>
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Description</h5>
                    <p class="text-justify">{!! $task->description ?? '-' !!}</p>
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Weight</h5>
                    <p class="text-justify">{{ $task->weight ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('tasks.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Task::class)
                <a
                    href="{{ route('tasks.create') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
