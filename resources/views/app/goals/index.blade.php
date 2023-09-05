@extends('layouts.app')
@section('title', 'Goal Index')

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
                @can('create', App\Models\Goal::class)
                <a href="{{ route('goals.create') }}" class="btn btn-primary">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between;">
                <h4 class="card-title">@lang('crud.goals.index_title')</h4>
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
                            <th class="text-center">
                                Output
                            </th>
                            <th class="text-center">
                                Outcome
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
                        @forelse($goal_ts as $goal_t)
                         @if(app()->getLocale() ==$goal_t->locale)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>{{ $goal_t->name ?? '-' }}</td>
                            <td>{{ $goal_t->description ?? '-' }}</td>
                            <td>{{ strip_tags($goal_t->out_put ?? '-' )}}</td>
                            <td>{{ strip_tags($goal_t->out_come ?? '-') }}</td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $goal_t)
                                    <a href="{{ route('goals.edit', $goal_t->translation_id) }}">
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view', $goal_t)
                                    <a href="{{ route('goals.show', $goal_t->translation_id) }}">
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete', $goal_t)
                                    <form
                                        action="{{ route('goals.destroy', $goal_t->translation_id) }}"
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
                </table>
                <div class="float-right">
                    {!! $goal_ts->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
