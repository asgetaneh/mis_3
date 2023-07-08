@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <a href="{{ route('key-peformance-indicators.index') }}" class="mr-4"><i
                            class="icon ion-md-arrow-back"></i></a>
                    @lang('crud.key_peformance_indicators.show_title')
                </h4>

                <div class="mt-4 pt-4">
                    <div class="mb-4">
                        <h5>Goal</h5>
                        @forelse($keyPeformanceIndicator->objective->goal->goalTranslations as $key => $goal)
                            @if (app()->getLocale() == $goal->locale)
                                <p class="">{{ $goal->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>No goal for this KPI!</p>
                        @endforelse
                    </div>
                    <hr>
                    <div class="mb-4">
                        <h5>
                            @lang('crud.key_peformance_indicators.inputs.objective_id')
                        </h5>
                        @forelse($keyPeformanceIndicator->objective->objectiveTranslations as $key => $objective)
                            @if (app()->getLocale() == $objective->locale)
                                <p class="">{{ $objective->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>No Objective for this KPI!</p>
                        @endforelse
                    </div>
                    <hr>
                    <div class="mb-4">
                        <h5>
                            @lang('crud.key_peformance_indicators.inputs.strategy_id')
                        </h5>
                        @forelse($keyPeformanceIndicator->strategy->strategyTranslations as $key => $strategy)
                            @if (app()->getLocale() == $strategy->locale)
                                <p class="">{{ $strategy->name }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                    </div>
                    <hr>
                    <div class="mb-4">
                        <h5>KPI Name</h5>
                        @forelse($keyPeformanceIndicator->keyPeformanceIndicatorTs as $key => $kpi)
                            @if (app()->getLocale() == $kpi->locale)
                                <p class="">{{ $kpi->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                    </div>
                    <hr>
                    <div class="mb-4">
                        <h5>
                            @lang('crud.key_peformance_indicators.inputs.reporting_period_type_id')
                        </h5>
                        @forelse ($keyPeformanceIndicator->reportingPeriodType->reportingPeriodTypeTs as $key => $reportingType)
                            @if (app()->getLocale() == $reportingType->locale)
                                <p class="">{{ $reportingType->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                    </div>
                    <hr>
                    <div class="mb-4">
                        <h5>
                            @lang('crud.key_peformance_indicators.inputs.weight')
                        </h5>
                        <span>{{ $keyPeformanceIndicator->weight ?? '-' }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('key-peformance-indicators.index') }}" class="btn btn-light">
                        <i class="icon ion-md-return-left"></i>
                        @lang('crud.common.back')
                    </a>

                    @can('create', App\Models\KeyPeformanceIndicator::class)
                        <a href="{{ route('key-peformance-indicators.create') }}" class="btn btn-light">
                            <i class="icon ion-md-add"></i> @lang('crud.common.create')
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
