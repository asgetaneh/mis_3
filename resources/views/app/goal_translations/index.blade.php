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
                @can('create', App\Models\GoalTranslation::class)
                <a
                    href="{{ route('goal-translations.create') }}"
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
                    @lang('crud.goal_translations.index_title')
                </h4>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th class="text-left">
                                @lang('crud.goal_translations.inputs.translation_id')
                            </th>
                            <th class="text-left">
                                @lang('crud.goal_translations.inputs.name')
                            </th>
                            <th class="text-left">
                                @lang('crud.goal_translations.inputs.out_put')
                            </th>
                            <th class="text-left">
                                @lang('crud.goal_translations.inputs.out_come')
                            </th>
                            <th class="text-left">
                                @lang('crud.goal_translations.inputs.description')
                            </th>
                            <th class="text-left">
                                @lang('crud.goal_translations.inputs.locale')
                            </th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($goalTranslations as $goalTranslation)
                        <tr>
                            <td>
                                {{ optional($goalTranslation->goal)->id ?? '-'
                                }}
                            </td>
                            <td>{{ $goalTranslation->name ?? '-' }}</td>
                            <td>{{ $goalTranslation->out_put ?? '-' }}</td>
                            <td>{{ $goalTranslation->out_come ?? '-' }}</td>
                            <td>{{ $goalTranslation->description ?? '-' }}</td>
                            <td>{{ $goalTranslation->locale ?? '-' }}</td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $goalTranslation)
                                    <a
                                        href="{{ route('goal-translations.edit', $goalTranslation) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view', $goalTranslation)
                                    <a
                                        href="{{ route('goal-translations.show', $goalTranslation) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete', $goalTranslation)
                                    <form
                                        action="{{ route('goal-translations.destroy', $goalTranslation) }}"
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
                            <td colspan="7">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                {!! $goalTranslations->render() !!}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
