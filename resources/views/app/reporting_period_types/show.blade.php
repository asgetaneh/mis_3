@extends('layouts.app')
@section('title', 'Show Reporting Period Type')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('reporting-period-types.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.reporting_period_types.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>Name</h5>
                    @forelse($reportingPeriodType->reportingPeriodTypeTs as $key => $reportingPeriod)
                        @if (app()->getLocale() == $reportingPeriod->locale)
                            <p class="">{{ $reportingPeriod->name ?? '-' }}</p>
                        @endif
                    @empty
                        <p>-</p>
                    @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>Description</h5>
                    @forelse($reportingPeriodType->reportingPeriodTypeTs as $key => $reportingPeriod)
                        @if (app()->getLocale() == $reportingPeriod->locale)
                            <p class="">{{ $reportingPeriod->description ?? '-' }}</p>
                        @endif
                    @empty
                        <p>-</p>
                    @endforelse
                </div>
                {{-- <div class="mb-4">
                    <h5>@lang('crud.languages.inputs.created_by_id')</h5>
                    <span>{{ optional($language->user)->name ?? '-' }}</span>
                </div> --}}
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('reporting-period-types.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\ReportingPeriodType::class)
                <a
                    href="{{ route('reporting-period-types.create') }}"
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
