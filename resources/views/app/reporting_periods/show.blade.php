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
                        Name
                    </h5>
                    @forelse($reportingPeriod->reportingPeriodTs as $key => $period)
                            @if (app()->getLocale() == $period->locale)
                                <p class="">{{ $period->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Description</h5>
                    @forelse($reportingPeriod->reportingPeriodTs as $key => $period)
                            @if (app()->getLocale() == $period->locale)
                                <p class="">{{ $period->description ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>@lang('crud.reporting_periods.inputs.start_date')</h5>
                    <span>{{ $reportingPeriod->start_date ?? '-' }}</span>
                </div>
                <hr>
                <div class="mb-4">
                    <h5>@lang('crud.reporting_periods.inputs.end_date')</h5>
                    <span>{{ $reportingPeriod->end_date ?? '-' }}</span>
                </div>
                <hr>
                <div class="mb-4">
                    <h5>
                        @lang('crud.reporting_periods.inputs.reporting_period_type_id')
                    </h5>
                    @forelse($reportingPeriod->reportingPeriodType->reportingPeriodTypeTs as $key => $reportType)
                            @if (app()->getLocale() == $reportType->locale)
                                <p class="">{{ $reportType->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
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
