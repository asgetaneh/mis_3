@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('plan-accomplishments.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.plan_accomplishments.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.plan_accomplishments.inputs.suitable_kpi_id')
                    </h5>
                    <span
                        >{{ optional($planAccomplishment->suitableKpi)->id ??
                        '-' }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.plan_accomplishments.inputs.reporting_period_id')
                    </h5>
                    <span
                        >{{
                        optional($planAccomplishment->reportingPeriod)->start_date
                        ?? '-' }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.plan_accomplishments.inputs.plan_value')
                    </h5>
                    <span>{{ $planAccomplishment->plan_value ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.plan_accomplishments.inputs.accom_value')
                    </h5>
                    <span>{{ $planAccomplishment->accom_value ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.plan_accomplishments.inputs.plan_status')
                    </h5>
                    <span>{{ $planAccomplishment->plan_status ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.plan_accomplishments.inputs.accom_status')
                    </h5>
                    <span>{{ $planAccomplishment->accom_status ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('plan-accomplishments.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\PlanAccomplishment::class)
                <a
                    href="{{ route('plan-accomplishments.create') }}"
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
