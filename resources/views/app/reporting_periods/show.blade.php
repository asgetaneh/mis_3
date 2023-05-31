@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('reporting-periods.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.reporting_periods.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.reporting_periods.inputs.planing_year_id')
                    </h5>
                    <span
                        >{{ optional($reportingPeriod->planingYear)->id ?? '-'
                        }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.reporting_periods.inputs.start_date')</h5>
                    <span>{{ $reportingPeriod->start_date ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.reporting_periods.inputs.end_date')</h5>
                    <span>{{ $reportingPeriod->end_date ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.reporting_periods.inputs.reporting_period_type_id')
                    </h5>
                    <span
                        >{{ optional($reportingPeriod->reportingPeriodType)->id
                        ?? '-' }}</span
                    >
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('reporting-periods.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\ReportingPeriod::class)
                <a
                    href="{{ route('reporting-periods.create') }}"
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
