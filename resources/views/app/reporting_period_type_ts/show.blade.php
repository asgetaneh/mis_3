@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('reporting-period-type-ts.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.reporting_period_type_translations.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.reporting_period_type_translations.inputs.reporting_period_type_id')
                    </h5>
                    <span
                        >{{
                        optional($reportingPeriodTypeT->reportingPeriodType)->id
                        ?? '-' }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.reporting_period_type_translations.inputs.name')
                    </h5>
                    <span>{{ $reportingPeriodTypeT->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.reporting_period_type_translations.inputs.description')
                    </h5>
                    <span>{{ $reportingPeriodTypeT->description ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('reporting-period-type-ts.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\ReportingPeriodTypeT::class)
                <a
                    href="{{ route('reporting-period-type-ts.create') }}"
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
