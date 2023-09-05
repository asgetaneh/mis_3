@extends('layouts.app')
@section('title', 'Show KPI Type')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('types.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Show KPI Type
            </h4>
            <br>
            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        Name
                    </h5>
                        @forelse($type->kpiTypeTranslations as $key => $ty)
                            @if (app()->getLocale() == $ty->locale)
                                <p class="">{{ $ty->name ?? '-' }}</p>
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
                        @forelse($type->kpiTypeTranslations as $key => $ty)
                            @if (app()->getLocale() == $ty->locale)
                                <p class="">{{ $ty->description ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('types.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\KpiTypeTranslation::class)
                <a
                    href="{{ route('types.create') }}"
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
