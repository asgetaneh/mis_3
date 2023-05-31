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
                            <th class="text-right">
                                @lang('crud.key_peformance_indicators.inputs.weight')
                            </th>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicators.inputs.objective_id')
                            </th>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicators.inputs.strategy_id')
                            </th>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicators.inputs.created_by_id')
                            </th>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicators.inputs.reporting_period_type_id')
                            </th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keyPeformanceIndicators as
                        $keyPeformanceIndicator)
                        <tr>
                            <td>
                                {{ $keyPeformanceIndicator->weight ?? '-' }}
                            </td>
                            <td>
                                {{
                                optional($keyPeformanceIndicator->objective)->id
                                ?? '-' }}
                            </td>
                            <td>
                                {{
                                optional($keyPeformanceIndicator->strategy)->id
                                ?? '-' }}
                            </td>
                            <td>
                                {{ optional($keyPeformanceIndicator->user)->name
                                ?? '-' }}
                            </td>
                            <td>
                                {{
                                optional($keyPeformanceIndicator->reportingPeriodType)->id
                                ?? '-' }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $keyPeformanceIndicator)
                                    <a
                                        href="{{ route('key-peformance-indicators.edit', $keyPeformanceIndicator) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view',
                                    $keyPeformanceIndicator)
                                    <a
                                        href="{{ route('key-peformance-indicators.show', $keyPeformanceIndicator) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete',
                                    $keyPeformanceIndicator)
                                    <form
                                        action="{{ route('key-peformance-indicators.destroy', $keyPeformanceIndicator) }}"
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
                                {!! $keyPeformanceIndicators->render() !!}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
