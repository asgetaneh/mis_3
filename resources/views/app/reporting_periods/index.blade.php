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
                @can('create', App\Models\ReportingPeriod::class)
                <a
                    href="{{ route('reporting-periods.create') }}"
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
                    @lang('crud.reporting_periods.index_title')
                </h4>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th class="text-left">
                                @lang('crud.reporting_periods.inputs.planing_year_id')
                            </th>
                            <th class="text-left">
                                @lang('crud.reporting_periods.inputs.start_date')
                            </th>
                            <th class="text-left">
                                @lang('crud.reporting_periods.inputs.end_date')
                            </th>
                            <th class="text-left">
                                @lang('crud.reporting_periods.inputs.reporting_period_type_id')
                            </th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportingPeriodTS as $reporting_period_t)
                        @if(app()->getLocale() == $reporting_period_t->locale)
                        <tr>
                            @php
                                $planningYear = "";
                                foreach ($reporting_period_t->reportingPeriod->planingYear->planingYearTranslations as $key => $value){
                                    if(app()->getLocale() == $value->locale){
                                        $planningYear = $value->name;
                                    }
                                }
                            @endphp
                            <td>
                                {{ $planningYear
                                ?? '-' }}
                            </td>
                            <td>{{ $reporting_period_t->reportingPeriod->start_date ?? '-' }}</td>
                            <td>{{ $reporting_period_t->reportingPeriod->end_date ?? '-' }}</td>

                            @php
                                $reportPeriodType = "";
                                foreach ($reporting_period_t->reportingPeriod->reportingPeriodType->reportingPeriodTypeTs as $key => $value){
                                    if(app()->getLocale() == $value->locale){
                                        $reportPeriodType = $value->name;
                                    }
                                }
                            @endphp

                            <td>
                                {{
                                $reportPeriodType
                                ?? '-' }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $reporting_period_t)
                                    <a
                                        href="{{ route('reporting-periods.edit', $reporting_period_t) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view', $reporting_period_t)
                                    <a
                                        href="{{ route('reporting-periods.show', $reporting_period_t) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete', $reporting_period_t)
                                    <form
                                        action="{{ route('reporting-periods.destroy', $reporting_period_t) }}"
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
                            <td colspan="5">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                {!! $reportingPeriodTS->render() !!}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
