@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('suitable-kpis.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.suitable_kpis.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.suitable_kpis.inputs.key_peformance_indicator_id')
                    </h5>
                    <span
                        >{{ optional($suitableKpi->keyPeformanceIndicator)->id
                        ?? '-' }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.suitable_kpis.inputs.office_id')</h5>
                    <span>{{ optional($suitableKpi->office)->id ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.suitable_kpis.inputs.planing_year_id')</h5>
                    <span
                        >{{ optional($suitableKpi->planingYear)->id ?? '-'
                        }}</span
                    >
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('suitable-kpis.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\SuitableKpi::class)
                <a
                    href="{{ route('suitable-kpis.create') }}"
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
