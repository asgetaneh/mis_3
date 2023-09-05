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
                @can('create', App\Models\Objective::class)
                <a
                    href="{{ route('objectives.create') }}"
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
                <h4 class="card-title">@lang('crud.objectives.index_title')</h4>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
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
                            <th class="text-right">
                                @lang('crud.objectives.inputs.weight')
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

                        @forelse($objective_ts as $objective_t)
                        @if(app()->getLocale() ==$objective_t->locale)

                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>
                                {{ $objective_t->name ?? '-' }}

                                <div class="bg-light mt-2 p-2">

                                    @forelse($objective_t->objective->goal->goalTranslations as $key => $goal)
                                        @if(app()->getLocale() == $goal->locale)
                                            <p class="text-primary d-inline"><span class="text-secondary">Goal:</span> {{ $goal->name }}</p>
                                            <br>
                                        @endif
                                    @empty
                                        <p>-</p>
                                    @endforelse

                                    @forelse($objective_t->objective->perspective->perspectiveTranslations as $key => $perspective)
                                        @if(app()->getLocale() == $perspective->locale)
                                            <p class="text-primary d-inline"><span class="text-secondary">Perspective:</span> {{ $perspective->name }}</p>
                                        @endif
                                    @empty
                                        <p>-</p>
                                    @endforelse

                                </div>

                            </td>
                            <td>
                                {{ $objective_t->description ?? '-'
                                }}
                            </td>
                            <td>
                                {{ $objective_t->out_put ?? '-' }}
                            </td>
                            <td>
                                {{ $objective_t->out_come ?? '-' }}
                            </td>
                            <td>{{ $objective_t->objective->weight ?? '-' }}</td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $objective_t)
                                    <a
                                        href="{{ route('objectives.edit', $objective_t->translation_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view', $objective_t)
                                    <a
                                        href="{{ route('objectives.show', $objective_t->translation_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete', $objective_t)
                                    <form
                                        action="{{ route('objectives.destroy', $objective_t->translation_id) }}"
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
                            <td colspan="7">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="float-right">
                    {!! $objective_ts->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
