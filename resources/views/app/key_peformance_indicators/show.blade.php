@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('key-peformance-indicators.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.key_peformance_indicators.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicators.inputs.weight')
                    </h5>
                    <span>{{ $keyPeformanceIndicator->weight ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicators.inputs.objective_id')
                    </h5>
                    <span
                        >{{ optional($keyPeformanceIndicator->objective)->id ??
                        '-' }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicators.inputs.strategy_id')
                    </h5>
                    <span
                        >{{ optional($keyPeformanceIndicator->strategy)->id ??
                        '-' }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicators.inputs.created_by_id')
                    </h5>
                    <span
                        >{{ optional($keyPeformanceIndicator->user)->name ?? '-'
                        }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicators.inputs.reporting_period_type_id')
                    </h5>
                    <span
                        >{{
                        optional($keyPeformanceIndicator->reportingPeriodType)->id
                        ?? '-' }}</span
                    >
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('key-peformance-indicators.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\KeyPeformanceIndicator::class)
                <a
                    href="{{ route('key-peformance-indicators.create') }}"
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
