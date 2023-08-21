@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('kpi-child-two-translations.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Show Disaggregation Two
            </h4>
            <br>
            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        Name
                    </h5>
                    @forelse($kpiChildTwoTranslation->kpiChildTwo->kpiChildTwoTranslations as $key => $two)
                    @if (app()->getLocale() == $two->locale)
                        <p class="">{{ $two->name ?? '-' }}</p>
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
                    @forelse($kpiChildTwoTranslation->kpiChildTwo->kpiChildTwoTranslations as $key => $two)
                    @if (app()->getLocale() == $two->locale)
                        <p class="">{{ $two->description ?? '-' }}</p>
                    @endif
                @empty
                    <p>-</p>
                @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('kpi-child-two-translations.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\KpiChildTwoTranslation::class)
                <a
                    href="{{ route('kpi-child-two-translations.create') }}"
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
