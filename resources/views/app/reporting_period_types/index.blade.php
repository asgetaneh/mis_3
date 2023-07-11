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
                @can('create', App\Models\ReportingPeriodType::class)
                <a
                    href="{{ route('reporting-period-types.create') }}"
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
                    @lang('crud.reporting_period_types.index_title')
                </h4>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered mt-3 table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="">
                                Name
                            </th>
                            <th class="">
                                Description
                            </th>
                            <th class="">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                        @endphp

                        @forelse($reportingPeriodTypeTS as $reportingPeriodType_t)
                        @if(app()->getLocale() == $reportingPeriodType_t->locale)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>
                                {{ $reportingPeriodType_t->name ?? '-' }}
                            </td>
                            <td>
                                {{ $reportingPeriodType_t->description ?? '-' }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $reportingPeriodType_t)
                                    <a
                                        href="{{ route('reporting-period-types.edit', $reportingPeriodType_t->reporting_period_type_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view', $reportingPeriodType_t)
                                    <a
                                        href="{{ route('reporting-period-types.show', $reportingPeriodType_t->reporting_period_type_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete', $reportingPeriodType_t)
                                    <form
                                        action="{{ route('reporting-period-types.destroy', $reportingPeriodType_t->reporting_period_type_id) }}"
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
                            <td colspan="3">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="float-right">
                    {!! $reportingPeriodTypeTS->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
