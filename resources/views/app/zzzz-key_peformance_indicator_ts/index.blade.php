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
                @can('create', App\Models\KeyPeformanceIndicatorT::class)
                <a
                    href="{{ route('key-peformance-indicator-ts.create') }}"
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
                    @lang('crud.key_peformance_indicator_translations.index_title')
                </h4>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicator_translations.inputs.name')
                            </th>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicator_translations.inputs.description')
                            </th>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicator_translations.inputs.out_put')
                            </th>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicator_translations.inputs.out_come')
                            </th>
                            <th class="text-left">
                                @lang('crud.key_peformance_indicator_translations.inputs.translation_id')
                            </th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keyPeformanceIndicatorTs as
                        $keyPeformanceIndicatorT)
                        <tr>
                            <td>{{ $keyPeformanceIndicatorT->name ?? '-' }}</td>
                            <td>
                                {{ $keyPeformanceIndicatorT->description ?? '-'
                                }}
                            </td>
                            <td>
                                {{ $keyPeformanceIndicatorT->out_put ?? '-' }}
                            </td>
                            <td>
                                {{ $keyPeformanceIndicatorT->out_come ?? '-' }}
                            </td>
                            <td>
                                {{
                                optional($keyPeformanceIndicatorT->keyPeformanceIndicator)->id
                                ?? '-' }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $keyPeformanceIndicatorT)
                                    <a
                                        href="{{ route('key-peformance-indicator-ts.edit', $keyPeformanceIndicatorT) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view',
                                    $keyPeformanceIndicatorT)
                                    <a
                                        href="{{ route('key-peformance-indicator-ts.show', $keyPeformanceIndicatorT) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete',
                                    $keyPeformanceIndicatorT)
                                    <form
                                        action="{{ route('key-peformance-indicator-ts.destroy', $keyPeformanceIndicatorT) }}"
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
                                {!! $keyPeformanceIndicatorTs->render() !!}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
