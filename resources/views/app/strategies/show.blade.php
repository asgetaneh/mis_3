@extends('layouts.app')
@section('title', 'Show Strategy')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('strategies.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.strategies.show_title')
            </h4>

            <div class="mt-4 pt-5">
                <div class="mb-4">
                    <h5>Objective</h5>
                    @forelse($strategy->objective->objectiveTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="">{{ $value->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>No objective for this Strategy!</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Name</h5>
                    @forelse($strategy->strategyTranslations as $key => $value)
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
                    @forelse($strategy->strategyTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="text-justify">{{ $value->discription ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('strategies.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Strategy::class)
                <a
                    href="{{ route('strategies.create') }}"
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
