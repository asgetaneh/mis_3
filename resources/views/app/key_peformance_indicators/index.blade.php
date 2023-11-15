@extends('layouts.app')
@section('title', 'KPI Index')

@section('content')
    <div class="container-fluid">
        <div class="searchbar mt-0 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <form>
                        <div class="input-group">
                            <input id="indexSearch" type="text" name="search" placeholder="{{ __('crud.common.search') }}"
                                value="{{ $search ?? '' }}" class="form-control" autocomplete="off" />
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon ion-md-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    @can('create', App\Models\KeyPeformanceIndicator::class)
                        <a href="{{ route('key-peformance-indicators.create') }}" class="btn btn-primary">
                            <i class="icon ion-md-add"></i> @lang('crud.common.create')
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="card-title">
                        @lang('crud.key_peformance_indicators.index_title')
                    </h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mt-3">
                        <thead>
                            @if (!($keyPeformanceIndicator_ts->total() < $kpiAllCount))

                                @forelse($objectives as $objective)
                                    <tr style="background:#96fffd45;">
                                        <th colspan="6">Objective: {{ $objective->objectiveTranslations[0]->name }} (kpi:
                                            {{ count($objective->keyPeformanceIndicators) }}) </th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">
                                            Name
                                        </th>
                                        <th>
                                            Type
                                        </th>
                                        <th class="text-left">
                                            Description
                                        </th>
                                        {{-- <th class="text-left">
                                Output
                            </th>
                            <th class="text-left">
                                Outcome
                            </th> --}}
                                        {{-- <th class="text-left">
                                Strategy
                            </th> --}}
                                        <th class="text-left">
                                            Reporting Period Type
                                        </th>
                                        <th class="text-center">
                                            @lang('crud.common.actions')
                                        </th>
                                    </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @forelse($objective->keyPeformanceIndicators as $keyPeformanceIndicator)
                                @if (app()->getLocale() == $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->locale)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        {{-- <td>{{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->id }}</td> --}}
                                        <td>
                                            {{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->name ?? '-' }}
                                        </td>
                                        @php
                                            $kpiType = '';
                                            foreach ($keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->keyPeformanceIndicator->kpiType->kpiTypeTranslations as $key => $value) {
                                                if (app()->getLocale() == $value->locale) {
                                                    $kpiType = $value->name;
                                                }
                                            }
                                        @endphp
                                        <td>
                                            {{ $kpiType ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->description ?? '-' }}
                                        </td>
                                        {{-- <td>
                                {{
                                $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->out_put
                                ?? '-' }}
                            </td>
                            <td>
                                {{
                                $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->out_come
                                ?? '-' }}
                            </td> --}}

                                        @php
                                            $strategy = '';
                                            foreach ($keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->keyPeformanceIndicator->strategy->strategyTranslations as $key => $value) {
                                                if (app()->getLocale() == $value->locale) {
                                                    $strategy = $value->name;
                                                }
                                            }
                                        @endphp

                                        @php
                                            $reportingPeriodType = '';
                                            foreach ($keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->keyPeformanceIndicator->reportingPeriodType->reportingPeriodTypeTs as $key => $value) {
                                                if (app()->getLocale() == $value->locale) {
                                                    $reportingPeriodType = $value->name;
                                                }
                                            }
                                        @endphp

                                        {{-- <td>
                                {{
                                $strategy
                                ?? '-' }}
                            </td> --}}
                                        <td>
                                            {{ $reportingPeriodType ?? '-' }}
                                        </td>

                                        <td class="text-center" style="width: 134px;">
                                            <div>
                                                <div class="d-flex">
                                                    @can('update', $keyPeformanceIndicator->keyPeformanceIndicatorTs[0])
                                                        <a
                                                            href="{{ route('kpi-Chain', $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->keyPeformanceIndicator) }}">
                                                            <button type="button" class="btn btn-light"
                                                                title="Add Disaggregation One">
                                                                <i class="icon ion-md-add">One</i>
                                                            </button>
                                                        </a>
                                                        <a
                                                            href="{{ route('kpi-chain-two.create', $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->keyPeformanceIndicator) }}">
                                                            <button type="button" class="btn btn-light"
                                                                title="Add Disaggregation Two">
                                                                <i class="icon ion-md-add">Two</i>
                                                            </button>
                                                        </a>
                                                        <a
                                                            href="{{ route('kpi-chain-three.create', $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->keyPeformanceIndicator) }}">
                                                            <button type="button" class="btn btn-light"
                                                                title="Add Disaggregation Three">
                                                                <i class="icon ion-md-add">Three</i>
                                                            </button>
                                                        </a>
                                                    @endcan
                                                </div>

                                                <div class="d-flex mt-2">
                                                    @can('update', $keyPeformanceIndicator->keyPeformanceIndicatorTs[0])
                                                        <a
                                                            href="{{ route('key-peformance-indicators.edit', $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->translation_id) }}">
                                                            <button type="button" class="btn btn-light">
                                                                <i class="icon ion-md-create"></i>
                                                            </button>
                                                        </a>
                                                        @endcan 
                                                        @can('view',
                                                        $keyPeformanceIndicator->keyPeformanceIndicatorTs[0])
                                                        <a
                                                            href="{{ route('key-peformance-indicators.show', $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->translation_id) }}">
                                                            <button type="button" class="btn btn-light">
                                                                <i class="icon ion-md-eye"></i>
                                                            </button>
                                                        </a>
                                                        @endcan 
                                                        @can('delete',
                                                        $keyPeformanceIndicator->keyPeformanceIndicatorTs[0])
                                                        <form
                                                            action="{{ route('key-peformance-indicators.destroy', $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->translation_id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-light text-danger">
                                                                <i class="icon ion-md-trash"></i>
                                                            </button>
                                                           
                                                        </form>
                                                    @endcan
                                                    @php
                                                    $user = auth()->user();
                                                    @endphp 
                                                    @if($user->hasPermission('view keypeformanceindicators'))

                                                        <a
                                                            href="{{ route('kpi-assign-tooffices', $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->keyPeformanceIndicator) }}">
                                                        <button type="button" class="btn btn-info"
                                                                title="Add Disaggregation Three">
                                                                 Cascade
                                                        </button>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                            @endforelse
                        @empty
                            <tr>
                                <td colspan="7">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                            @endforelse

                            {{-- Handle searching --}}
                        @else
                            @forelse($objectives as $objective)
                                @forelse ($keyPeformanceIndicator_ts as $keyPeformanceIndicator)
                                    @if ($keyPeformanceIndicator->keyPeformanceIndicator->objective_id == $objective->id)
                                        <tr style="background:#96fffd45;">
                                            <th colspan="6">Objective: {{ $objective->objectiveTranslations[0]->name }}
                                                (kpi: {{ count($objective->keyPeformanceIndicators) }})
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-left">
                                                Name
                                            </th>
                                            <th>
                                                Type
                                            </th>
                                            <th class="text-left">
                                                Description
                                            </th>
                                            {{-- <th class="text-left">
                               Output
                           </th>
                           <th class="text-left">
                               Outcome
                           </th> --}}
                                            {{-- <th class="text-left">
                               Strategy
                           </th> --}}
                                            <th class="text-left">
                                                Reporting Period Type
                                            </th>
                                            <th class="text-center">
                                                @lang('crud.common.actions')
                                            </th>
                                        </tr>
                                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @if (app()->getLocale() == $keyPeformanceIndicator->locale)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    {{-- <td>{{ $keyPeformanceIndicator->id }}</td> --}}
                                    <td>
                                        {{ $keyPeformanceIndicator->name ?? '-' }}
                                    </td>
                                    @php
                                        $kpiType = '';
                                        foreach ($keyPeformanceIndicator->keyPeformanceIndicator->kpiType->kpiTypeTranslations as $key => $value) {
                                            if (app()->getLocale() == $value->locale) {
                                                $kpiType = $value->name;
                                            }
                                        }
                                    @endphp
                                    <td>
                                        {{ $kpiType ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $keyPeformanceIndicator->description ?? '-' }}
                                    </td>
                                    {{-- <td>
                               {{
                               $keyPeformanceIndicator->out_put
                               ?? '-' }}
                           </td>
                           <td>
                               {{
                               $keyPeformanceIndicator->out_come
                               ?? '-' }}
                           </td> --}}

                                    @php
                                        $strategy = '';
                                        foreach ($keyPeformanceIndicator->keyPeformanceIndicator->strategy->strategyTranslations as $key => $value) {
                                            if (app()->getLocale() == $value->locale) {
                                                $strategy = $value->name;
                                            }
                                        }
                                    @endphp

                                    @php
                                        $reportingPeriodType = '';
                                        foreach ($keyPeformanceIndicator->keyPeformanceIndicator->reportingPeriodType->reportingPeriodTypeTs as $key => $value) {
                                            if (app()->getLocale() == $value->locale) {
                                                $reportingPeriodType = $value->name;
                                            }
                                        }
                                    @endphp

                                    {{-- <td>
                               {{
                               $strategy
                               ?? '-' }}
                           </td> --}}
                                    <td>
                                        {{ $reportingPeriodType ?? '-' }}
                                    </td>

                                    <td class="text-center" style="width: 134px;">
                                        <div>
                                            <div class="d-flex">
                                                @can('update', $keyPeformanceIndicator)
                                                    <a
                                                        href="{{ route('kpi-Chain', $keyPeformanceIndicator->keyPeformanceIndicator) }}">
                                                        <button type="button" class="btn btn-light"
                                                            title="Add Disaggregation One">
                                                            <i class="icon ion-md-add">One</i>
                                                        </button>
                                                    </a>
                                                    <a
                                                        href="{{ route('kpi-chain-two.create', $keyPeformanceIndicator->keyPeformanceIndicator) }}">
                                                        <button type="button" class="btn btn-light"
                                                            title="Add Disaggregation Two">
                                                            <i class="icon ion-md-add">Two</i>
                                                        </button>
                                                    </a>
                                                    <a
                                                        href="{{ route('kpi-chain-three.create', $keyPeformanceIndicator->keyPeformanceIndicator) }}">
                                                        <button type="button" class="btn btn-light"
                                                            title="Add Disaggregation Three">
                                                            <i class="icon ion-md-add">Three</i>
                                                        </button>
                                                    </a>
                                                @endcan
                                            </div>

                                            <div class="d-flex mt-2">
                                                @can('update', $keyPeformanceIndicator)
                                                    <a
                                                        href="{{ route('key-peformance-indicators.edit', $keyPeformanceIndicator->translation_id) }}">
                                                        <button type="button" class="btn btn-light">
                                                            <i class="icon ion-md-create"></i>
                                                        </button>
                                                    </a>
                                                    @endcan @can('view', $keyPeformanceIndicator)
                                                    <a
                                                        href="{{ route('key-peformance-indicators.show', $keyPeformanceIndicator->translation_id) }}">
                                                        <button type="button" class="btn btn-light">
                                                            <i class="icon ion-md-eye"></i>
                                                        </button>
                                                    </a>
                                                    @endcan @can('delete', $keyPeformanceIndicator)
                                                    <form
                                                        action="{{ route('key-peformance-indicators.destroy', $keyPeformanceIndicator->translation_id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-light text-danger">
                                                            <i class="icon ion-md-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @else
                            @continue
                            @endif
                        @empty
                            <tr>
                                <td colspan="7">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                        @break

                        @endforelse
                    @empty
                        <tr>
                            <td colspan="7">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse

                        @endif
                    </tbody>
                </table>
                <div class="float-right">
                    {!! $keyPeformanceIndicator_ts->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
