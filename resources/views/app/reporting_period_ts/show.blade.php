@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('reporting-period-ts.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.reporting_period_translations.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.reporting_period_translations.inputs.name')
                    </h5>
                    <span>{{ $reportingPeriodT->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.reporting_period_translations.inputs.description')
                    </h5>
                    <span>{{ $reportingPeriodT->description ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.reporting_period_translations.inputs.reporting_period_id')
                    </h5>
                    <span
                        >{{
                        optional($reportingPeriodT->reportingPeriod)->start_date
                        ?? '-' }}</span
                    >
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('reporting-period-ts.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\ReportingPeriodT::class)
                <a
                    href="{{ route('reporting-period-ts.create') }}"
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
