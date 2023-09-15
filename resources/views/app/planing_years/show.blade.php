@extends('layouts.app')
@section('title', 'Show Planning Year')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('planing-years.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.planing_years.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        Name
                    </h5>
                    @forelse($planingYear->planingYearTranslations as $key => $planing)
                            @if (app()->getLocale() == $planing->locale)
                                <p class="">{{ $planing->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>
                        Description
                    </h5>
                    @forelse($planingYear->planingYearTranslations as $key => $planing)
                            @if (app()->getLocale() == $planing->locale)
                                <p class="">{{ $planing->description ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('planing-years.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\PlaningYear::class)
                <a
                    href="{{ route('planing-years.create') }}"
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
