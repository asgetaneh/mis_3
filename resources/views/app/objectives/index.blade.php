@extends('layouts.app')
@section('title', 'Objective Index')

@section('content')
    <div class="container-fluid">
        <div class="searchbar mt-0 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <form>
                        <div class="input-group">
                            <input id="indexSearch" type="text" name="search" placeholder="{{ __('crud.common.search') }}"
                                value="{{ $search ?? '' }}" class="form-control" autocomplete="off" />
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
                        <a href="{{ route('objectives.create') }}" class="btn btn-primary">
                            <i class="icon ion-md-add"></i> @lang('crud.common.create')
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body" >
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="card-title">@lang('crud.objectives.index_title')</h4>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>
                            @if (!($objective_ts->total() < $objAllCount))
                                @forelse($goals as $goal)
                                    <tr style="background:#96fffd45;">
                                        <th colspan="8">{{ $goal->goalTranslations[0]->name }}</th>
                                    </tr>
                                    <tr>
                                        <th style="background:#96fffd45;"></th>
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

                            @forelse($goal->objectives as $objective)
                                @if (app()->getLocale() == $objective->objectiveTranslations[0]->locale)
                                    <tr>
                                         <td style="background:#96fffd45;"> </td>
                                        <td>{{ $count++ }}</td>
                                        <td>
                                            {{ $objective->objectiveTranslations[0]->name ?? '-' }}


                                        </td>
                                        <td>
                                            {!! html_entity_decode($objective->objectiveTranslations[0]->description ?? '-'  ) !!}
                                        </td>
                                        <td>
                                            {!! html_entity_decode($objective->objectiveTranslations[0]->out_put ?? '-'  ) !!}
                                        </td>
                                        <td>
                                            {!! html_entity_decode($objective->objectiveTranslations[0]->out_come ?? '-') !!}
                                        </td>
                                        <td>{{ $objective->objectiveTranslations[0]->objective->weight ?? '-' }}</td>
                                        <td class="text-center" style="width: 134px;">
                                            <div role="group" aria-label="Row Actions" class="btn-group">
                                                @can('update', $objective->objectiveTranslations[0])
                                                    <a
                                                        href="{{ route('objectives.edit', $objective->objectiveTranslations[0]->translation_id) }}">
                                                        <button type="button" class="btn btn-light">
                                                            <i class="icon ion-md-create"></i>
                                                        </button>
                                                    </a>
                                                    @endcan @can('view', $objective->objectiveTranslations[0])
                                                    <a
                                                        href="{{ route('objectives.show', $objective->objectiveTranslations[0]->translation_id) }}">
                                                        <button type="button" class="btn btn-light">
                                                            <i class="icon ion-md-eye"></i>
                                                        </button>
                                                    </a>
                                                    @endcan @can('delete', $objective->objectiveTranslations[0])
                                                    <form
                                                        action="{{ route('objectives.destroy', $objective->objectiveTranslations[0]->translation_id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-light text-danger">
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
                        @empty
                            <tr>
                                <td colspan="7">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                            @endforelse

                            {{-- Handle searching --}}
                        @else
                            @forelse($goals as $goal)
                                @forelse ($objective_ts as $objective)
                                    @if ($objective->objective->goal_id == $goal->id)
                                        <tr style="background:#96fffd45;">
                                            <th colspan="7">{{ $goal->goalTranslations[0]->name }}</th>
                                        </tr>
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
                            @if (app()->getLocale() == $objective->locale)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        {{ $objective->name ?? '-' }}


                                    </td>
                                    <td>
                                        {{ $objective->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $objective->out_put ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $objective->out_come ?? '-' }}
                                    </td>
                                    <td>{{ $objective->objective->weight ?? '-' }}</td>
                                    <td class="text-center" style="width: 134px;">
                                        <div role="group" aria-label="Row Actions" class="btn-group">
                                            @can('update', $objective)
                                                <a href="{{ route('objectives.edit', $objective->translation_id) }}">
                                                    <button type="button" class="btn btn-light">
                                                        <i class="icon ion-md-create"></i>
                                                    </button>
                                                </a>
                                                @endcan @can('view', $objective)
                                                <a href="{{ route('objectives.show', $objective->translation_id) }}">
                                                    <button type="button" class="btn btn-light">
                                                        <i class="icon ion-md-eye"></i>
                                                    </button>
                                                </a>
                                                @endcan @can('delete', $objective)
                                                <form action="{{ route('objectives.destroy', $objective->translation_id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-light text-danger">
                                                        <i class="icon ion-md-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @else
                            @continue
                            @endif
                        @empty
                            <tr>
                                <td colspan="7">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                        @break

                        @endforelse
                    @empty
                        <tr>
                            <td colspan="7">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse

                        @endif
                    </tbody>
                </table>
                {{-- <div class="float-right">
                    {!! $objective->objectiveTranslations[0]->render() !!}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
