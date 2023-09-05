@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('behaviors.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Show Behavior
            </h4>

            <div class="mt-4 pt-5">
                <div class="mb-4">
                    <h5>Name</h5>
                    @forelse($behavior->behaviorTranslations as $key => $value)
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
                    @forelse($behavior->behaviorTranslations as $key => $value)
                            @if (app()->getLocale() == $value->locale)
                                <p class="text-justify">{{ $value->description ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('behaviors.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Behavior::class)
                <a href="{{ route('behaviors.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
