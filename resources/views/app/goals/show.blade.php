@extends('layouts.app')
@section('title', 'Show Goal')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('goals.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.goals.show_title')
            </h4>

            <div class="mt-4 pt-5">
                <div class="mb-4">
                    <h5>Name</h5>
                    @forelse($goal->goalTranslations as $key => $value)
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
                    @forelse($goal->goalTranslations as $key => $value)
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
                    @forelse($goal->goalTranslations as $key => $value)
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
                    @forelse($goal->goalTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="text-justify">{{ $value->out_come ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('goals.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Goal::class)
                <a href="{{ route('goals.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
