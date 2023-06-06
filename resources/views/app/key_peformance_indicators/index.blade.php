@extends('layouts.app')

@section('content')
<div class="container">
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
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
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
                            <th class="text-left">
                                Objective
                            </th>
                            <th class="text-left">
                                Strategy
                            </th>
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
                        @forelse($keyPeformanceIndicator_ts as $keyPeformanceIndicator_t)
                        @if(app()->getLocale() == $keyPeformanceIndicator_t->locale)
                        <tr>
                            <td>
                                {{
                                $keyPeformanceIndicator_t->name
                                ?? '-' }}
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
                                $objective = '';
                                foreach ($keyPeformanceIndicator_t->keyPeformanceIndicator->objective->objectiveTranslations as $key => $value) {
                                    if (app()->getLocale() == $value->locale){
                                        $objective = $value->name;
                                    }
                                }
                            @endphp

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

                            <td>
                                {{
                                $objective ?? '-' }}
                            </td>
                            <td>
                                {{
                                $strategy
                                ?? '-' }}
                            </td>
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
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $keyPeformanceIndicator_t)
                                    <a
                                        href="{{ route('kpi-child-one-translations.create',$keyPeformanceIndicator_t) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-add"></i>
                                        </button>
                                    </a>
                                    <a
                                        href="{{ route('key-peformance-indicators.edit', $keyPeformanceIndicator_t) }}"
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
                                        href="{{ route('key-peformance-indicators.show', $keyPeformanceIndicator_t) }}"
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
                                        action="{{ route('key-peformance-indicators.destroy', $keyPeformanceIndicator_t) }}"
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
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="6">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                {!! $keyPeformanceIndicator_ts->render() !!}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
