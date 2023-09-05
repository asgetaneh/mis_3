@extends('layouts.app')
@section('title', 'KPI Level Three Index')

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
                @can('create', App\Models\KpiChildThreeTranslation::class)
                <a
                    href="{{ route('kpi-child-three-translations.create') }}"
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
                    Disaggregation Three List
                </h4>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered mt-3 table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-left">
                                Name
                            </th>
                            <th class="text-left">
                                Description
                            </th>
                            {{-- <th class="text-left">
                                @lang('crud.kpi_child_three_translations.inputs.kpiChildThree_id')
                            </th> --}}
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @forelse($kpiChildThreeTranslations as $kpiChildThreeTranslation)
                        @if(app()->getLocale() == $kpiChildThreeTranslation->locale)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>
                                {{ $kpiChildThreeTranslation->name ?? '-' }}
                            </td>
                            <td>
                                {{ $kpiChildThreeTranslation->description ?? '-'
                                }}
                            </td>
                            {{-- <td>
                                {{
                                optional($kpiChildThreeTranslation->kpiChildThree)->id
                                ?? '-' }}
                            </td> --}}
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $kpiChildThreeTranslation)
                                    <a
                                        href="{{ route('kpi-child-three-translations.edit', $kpiChildThreeTranslation) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view',
                                    $kpiChildThreeTranslation)
                                    <a
                                        href="{{ route('kpi-child-three-translations.show', $kpiChildThreeTranslation) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete',
                                    $kpiChildThreeTranslation)
                                    <form
                                        action="{{ route('kpi-child-three-translations.destroy', $kpiChildThreeTranslation) }}"
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
                    {!! $kpiChildThreeTranslations->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
