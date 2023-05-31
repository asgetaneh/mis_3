@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('key-peformance-indicator-ts.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.key_peformance_indicator_translations.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicator_translations.inputs.name')
                    </h5>
                    <span>{{ $keyPeformanceIndicatorT->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicator_translations.inputs.description')
                    </h5>
                    <span
                        >{{ $keyPeformanceIndicatorT->description ?? '-'
                        }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicator_translations.inputs.out_put')
                    </h5>
                    <span>{{ $keyPeformanceIndicatorT->out_put ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicator_translations.inputs.out_come')
                    </h5>
                    <span>{{ $keyPeformanceIndicatorT->out_come ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.key_peformance_indicator_translations.inputs.translation_id')
                    </h5>
                    <span
                        >{{
                        optional($keyPeformanceIndicatorT->keyPeformanceIndicator)->id
                        ?? '-' }}</span
                    >
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('key-peformance-indicator-ts.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\KeyPeformanceIndicatorT::class)
                <a
                    href="{{ route('key-peformance-indicator-ts.create') }}"
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
