@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="searchbar mt-0 mb-4">
        <div class="row">
            <div class="col-md-6">
                <form>
                    <div class="input-group">
                        <input
                            id="indexSearch"
                            type="text"
                            name="search"
                            placeholder="{{ __('crud.common.search') }}"
                            value="{{ $search ?? '' }}"
                            class="form-control"
                            autocomplete="off"
                        />
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
                <a
                    href="{{ route('key-peformance-indicators.create') }}"
                    class="btn btn-primary"
                >
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
                        <tr>
                            <th>#</th>
                            <th class="text-left">
                                Name
                            </th>
                            <th class="text-left">
                                Description
                            </th>
                            <th class="text-left">
                                Output
                            </th>
                            <th class="text-left">
                                Outcome
                            </th>
                            {{-- <th class="text-left">
                                Strategy
                            </th> --}}
                            <th class="text-left">
                                Reporting Period Type
                            </th>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicators.inputs.weight')
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
                        @forelse($keyPeformanceIndicator_ts as $keyPeformanceIndicator_t)
                        @if(app()->getLocale() == $keyPeformanceIndicator_t->locale)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>
                                {{
                                $keyPeformanceIndicator_t->name
                                ?? '-' }}

                                <div class="bg-light mt-2 p-2">

                                    @forelse($keyPeformanceIndicator_t->keyPeformanceIndicator->objective->goal->goalTranslations as $key => $goal)
                                        @if(app()->getLocale() == $goal->locale)
                                            <p class="text-primary d-inline"><span class="text-secondary">Goal:</span> {{ $goal->name }}</p>
                                            <br>
                                        @endif
                                    @empty
                                        <p>-</p>
                                    @endforelse

                                    @forelse($keyPeformanceIndicator_t->keyPeformanceIndicator->objective->objectiveTranslations as $key => $objective)
                                        @if(app()->getLocale() == $objective->locale)
                                            <p class="text-primary d-inline"><span class="text-secondary">Objective:</span> {{ $objective->name }}</p>
                                            <br>
                                        @endif
                                    @empty
                                        <p>-</p>
                                    @endforelse

                                    @forelse($keyPeformanceIndicator_t->keyPeformanceIndicator->strategy->strategyTranslations as $key => $strategy)
                                        @if(app()->getLocale() == $strategy->locale)
                                            <p class="text-primary d-inline"><span class="text-secondary">Strategy:</span> {{ $strategy->name }}</p>
                                        @endif
                                    @empty
                                        <p>-</p>
                                    @endforelse

                                </div>

                            </td>
                            <td>
                                {{
                                $keyPeformanceIndicator_t->description
                                ?? '-' }}
                            </td>
                            <td>
                                {{
                                $keyPeformanceIndicator_t->out_put
                                ?? '-' }}
                            </td>
                            <td>
                                {{
                                $keyPeformanceIndicator_t->out_come
                                ?? '-' }}
                            </td>

                            @php
                                $strategy = '';
                                foreach ($keyPeformanceIndicator_t->keyPeformanceIndicator->strategy->strategyTranslations as $key => $value) {
                                    if (app()->getLocale() == $value->locale){
                                        $strategy = $value->name;
                                    }
                                }
                            @endphp

                            @php
                            $reportingPeriodType = '';
                                foreach ($keyPeformanceIndicator_t->keyPeformanceIndicator->reportingPeriodType->reportingPeriodTypeTs as $key => $value) {
                                    if (app()->getLocale() == $value->locale){
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
                                {{
                                $reportingPeriodType
                                ?? '-' }}
                            </td>
                            <td>
                                {{ $keyPeformanceIndicator_t->keyPeformanceIndicator->weight ?? '-' }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                >
                                <div class="d-flex">
                                    @can('update', $keyPeformanceIndicator_t)
                                    <a
                                        href="{{ route('kpi-Chain',$keyPeformanceIndicator_t->keyPeformanceIndicator) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-add">One</i>
                                        </button>
                                    </a>
                                    <a
                                        href="{{ route('kpi-chain-two.create',$keyPeformanceIndicator_t->keyPeformanceIndicator) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-add">Two</i>
                                        </button>
                                    </a>
                                    <a
                                        href="{{ route('kpi-chain-three.create',$keyPeformanceIndicator_t->keyPeformanceIndicator) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-add">Three</i>
                                        </button>
                                    </a>

                                    @endcan
                                </div>

                                <div class="d-flex mt-2">
                                    @can('update', $keyPeformanceIndicator_t)
                                    <a
                                        href="{{ route('key-peformance-indicators.edit', $keyPeformanceIndicator_t->translation_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view',
                                    $keyPeformanceIndicator_t)
                                    <a
                                        href="{{ route('key-peformance-indicators.show', $keyPeformanceIndicator_t->translation_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete',
                                    $keyPeformanceIndicator_t)
                                    <form
                                        action="{{ route('key-peformance-indicators.destroy', $keyPeformanceIndicator_t->translation_id) }}"
                                        method="POST"
                                        onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
                                    >
                                        @csrf @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-light text-danger"
                                        >
                                            <i class="icon ion-md-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
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
