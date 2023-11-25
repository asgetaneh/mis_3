@extends('layouts.app')
@section('title', 'Show Perspective')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('perspectives.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.perspectives.show_title')
            </h4>

            <div class="mt-4 pt-5">
                <div class="mb-4">
                    <h5>Name</h5>
                        @forelse($perspective->perspectiveTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="">{{ $value->name ?? '-' }}</p>
                            @endif
                        @empty

                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Description</h5>
                    @forelse($perspective->perspectiveTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="text-justify">{{ $value->description ?? '-' }}</p>
                            @endif
                        @empty

                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('perspectives.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Perspective::class)
                <a
                    href="{{ route('perspectives.create') }}"
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
