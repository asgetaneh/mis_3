@extends('layouts.app')
@section('title', 'Planning Year Index')

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
                @can('create', App\Models\PlaningYear::class)
                <a
                    href="{{ route('planing-years.create') }}"
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
                    @lang('crud.planing_years.index_title')
                </h4>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered mt-3 table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                Name
                            </th>
                            <th>
                                Description
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
                        @forelse($planing_year_ts as $planing_year_t)
                        @if(app()->getLocale() == $planing_year_t->locale)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>
                                {{ $planing_year_t->name }}
                            </td>
                            <td>
                                {{ $planing_year_t->description }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $planing_year_t)
                                    <a
                                        href="{{ route('planing-years.edit', $planing_year_t->planing_year_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view', $planing_year_t)
                                    <a
                                        href="{{ route('planing-years.show', $planing_year_t->planing_year_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete', $planing_year_t)
                                    <form
                                        action="{{ route('planing-years.destroy', $planing_year_t->planing_year_id) }}"
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
                            <td colspan="4">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="float-right">
                    {!! $planing_year_ts->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
