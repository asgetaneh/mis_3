@extends('layouts.app')
@section('title', 'Show Objective')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('objectives.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.objectives.show_title')
            </h4>

            <div class="mt-4 pt-5">
                <div class="mb-4">
                    <h5>Goal</h5>
                    @forelse($objective->goal->goalTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="">{{ $value->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>No goal for this Objective!</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Perspective</h5>
                    @forelse($objective->perspective->perspectiveTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="">{{ $value->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>No perspective for this Objective!</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Name</h5>
                    @forelse($objective->objectiveTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="">{{ $value->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Description</h5>
                    @forelse($objective->objectiveTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="text-justify">{{ $value->description ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Output</h5>
                    @forelse($objective->objectiveTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="text-justify">{{ $value->out_put ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Outcome</h5>
                    @forelse($objective->objectiveTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="text-justify">{{ $value->out_come ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('objectives.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Objective::class)
                <a
                    href="{{ route('objectives.create') }}"
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
