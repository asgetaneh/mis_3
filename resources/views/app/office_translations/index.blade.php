@extends('layouts.app')
@section('title', 'Office Index')

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
                    @can('create', App\Models\OfficeTranslation::class)
                        <a href="{{ route('office-translations.create') }}" class="btn btn-primary">
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
                        @lang('crud.offices.index_title')
                        @if (auth()->user()->is_admin === true)
                            <span>
                                <a href="{{ route('office.hierarchy') }}" class="btn btn-outline-primary ml-3">Tree View</a>
                            </span>
                        @endif
                    </h4>
                    <a href="{{ route('office-assign.index') }}" class="btn btn-outline-success">
                        Assign Manager
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Sub-office</th>
                                <th class="text-left">
                                    #
                                </th>
                                <th class="text-left">
                                    @lang('crud.office_translations.inputs.name')
                                </th>
                                <th class="text-left">
                                    @lang('crud.office_translations.inputs.description')
                                </th>
                                <th class="text-left">
                                    @lang('crud.office_translations.inputs.translation_id')
                                </th>
                                <th>
                                    Manager
                                </th>
                                <th class="text-center">
                                    @lang('crud.common.actions')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $count =0;@endphp
                            @forelse($officesT as $officeTranslation)
                                @if (app()->getLocale() == $officeTranslation->locale)
                                    @php $office = $officeTranslation->office; @endphp
                                    @php $count = $count+1;@endphp
                                    <tr>
                                        {{-- @dd($office->id) --}}
                                        <td>
                                            <p>
                                                <a class="btn btn-flat btn-info" data-toggle="collapse"
                                                    href="#off{{ $office->id }}" role="button" aria-expanded="false"
                                                    aria-controls="collapseExample0">
                                                    >>
                                                </a>
                                            </p>
                                        <td>

                                            {{ $count }}
                                        </td>
                                        <td>{{ $officeTranslation->name ?? '-' }}</td>
                                        <td>
                                            {{ $officeTranslation->description ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $officeTranslation->office->office->officeTranslations[0]->name ?? '-' }}
                                        </td>

                                        <td>
                                            {!! $officeTranslation->office->users[0]->name ?? '<span class="badge badge-secondary">Not assigned</span>' !!}
                                            @if ($officeTranslation->office->users->count() > 0)

                                                @if ($officeTranslation->translation_id == 1)
                                                @else
                                                    <form
                                                        action="{{ route('office-manager.remove', $officeTranslation->office->users[0]->id) }}"
                                                        class="d-inline" method="POST">
                                                        @csrf
                                                        <button
                                                            class="btn btn-sm btn-outline-danger ml-3 float-right">Remove</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="text-center" style="width: 134px;">
                                            <div role="group" aria-label="Row Actions" class="btn-group">
                                                @can('update', $officeTranslation)
                                                    @if ($officeTranslation->translation_id == 1)
                                                    @else
                                                        <a href="{{ route('office-translations.edit', $officeTranslation) }}">
                                                            <button type="button" class="btn btn-light">
                                                                <i class="icon ion-md-create"></i>
                                                            </button>
                                                        </a>
                                                    @endif
                                                    @endcan @can('view', $officeTranslation)
                                                    <a href="{{ route('office-translations.show', $officeTranslation) }}">
                                                        <button type="button" class="btn btn-light">
                                                            <i class="icon ion-md-eye"></i>
                                                        </button>
                                                    </a>
                                                @endcan
                                                {{-- @can('delete', $officeTranslation)
                                        <form
                                            action="{{ route('office-translations.destroy', $officeTranslation) }}"
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
                                    @endcan --}}
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

                    @forelse($officesT as $officeTranslation)
                        @if (app()->getLocale() == $officeTranslation->locale)
                            @php $office = $officeTranslation->office; @endphp
                            {{-- level two (directores and same level) --}}
                            <div class="collapse" id="off{{ $office->id }}">
                                <div class="card card-body" style="background:#8cffad23;">
                                    @php
                                        $offices_twos = $office->offices;
                                    @endphp
                                    @forelse ($offices_twos as $office)
                                        @include('app.office_translations.sub')
                                        <div class="collapse" id="off{{ $office->id }}">
                                            <div class="card card-body">
                                                @php
                                                    $offices_threes = $office->offices;
                                                @endphp
                                                @forelse ($offices_threes as $office)
                                                    @include('app.office_translations.sub')
                                                    <div class="collapse" id="off{{ $office->id }}">
                                                        <div class="card card-body">
                                                            @php
                                                                $offices_fours = $office->offices;
                                                            @endphp
                                                            @forelse ($offices_fours as $office)
                                                                @include('app.office_translations.sub')
                                                                <div class="collapse" id="off{{ $office->id }}">
                                                                    <div class="card card-body">
                                                                        @php
                                                                            $offices_fives = $office->offices;
                                                                        @endphp
                                                                        @forelse ($offices_fives as $office)
                                                                            @include('app.office_translations.sub')
                                                                            <div class="collapse"
                                                                                id="off{{ $office->id }}">
                                                                                <div class="card card-body">
                                                                                    @php
                                                                                        $offices_sixs = $office->offices;
                                                                                    @endphp
                                                                                    @forelse ($offices_sixs as $office)
                                                                                        @include('app.office_translations.sub')
                                                                                        <div class="collapse"
                                                                                            id="off{{ $office->id }}">
                                                                                            <div class="card card-body">

                                                                                            </div>
                                                                                        </div>
                                                                                    @empty
                                                                                        <h5>No offices found!</h5>
                                                                                    @endforelse
                                                                                </div>
                                                                            </div>
                                                                        @empty
                                                                            <h5>No offices found!</h5>
                                                                        @endforelse
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <h5>No offices found!</h5>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                @empty
                                                    <h5>No offices found!</h5>
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                        <h5>No offices found!</h5>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                    @empty
                    @endforelse



                    <div class="float-right">
                        {!! $officeTranslations->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
