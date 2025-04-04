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
                    <a href="{{ route('office-assign.index') }}" class="btn btn-outline-success" title="Assign Manager for Office">
                        Assign Manager
                    </a>
                </div>

                <div class="table-responsive" style="background:#F0F8FF;">
                    <table class="table table-bordered table-hover mt-3">
                        <thead>
                            <tr>
                                <th width="5%">Sub-office</th>
                                <th class="text-left" width="5%">
                                    #
                                </th>
                                <th class="text-left" width="20%">
                                    @lang('crud.office_translations.inputs.name')
                                </th>
                                <th class="text-left" width="20%">
                                    @lang('crud.office_translations.inputs.description')
                                </th>
                                <th class="text-left" width="20%">
                                    @lang('crud.office_translations.inputs.translation_id')
                                </th>
                                <th width="20%">
                                    Manager
                                </th>
                                <th class="text-center">
                                    @lang('crud.common.actions')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $count =0;@endphp

                            @if (!empty($search))
                                @forelse($officeTranslations as $officeTranslation)
                                    @if (app()->getLocale() == $officeTranslation->locale)
                                        @php $office = $officeTranslation->office; @endphp
                                        @php $count = $count+1;@endphp
                                        <tr>
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
                                            <td>{{ $officeTranslation->name ?? '-' }} {{ "(" }} <span style="color:blue" title="Number of Sub offices">{{$officeTranslation->office->offices->count()}} </span>{{ ")" }}</td>
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
                            @else
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
                                            <td>{{ $officeTranslation->name ?? '-' }} {{ "(" }} <span style="color:blue" title="Number of Sub offices">{{$officeTranslation->office->offices->count()}} </span>{{ ")" }}</td>
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
                                                        @endcan
                                                        @can('view', $officeTranslation)
                                                            <a href="{{ route('office-translations.show', $officeTranslation) }}">
                                                                <button type="button" class="btn btn-light">
                                                                    <i class="icon ion-md-eye"></i>
                                                                </button>
                                                            </a>
                                                        @endcan
                                                        @can('delete', $officeTranslation)
                                                            <form action="{{ route('office-translations.destroy', $officeTranslation) }}"
                                                                method="POST" onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-light text-danger">
                                                                    <i class="icon ion-md-trash"></i>
                                                                </button>
                                                            </form>
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
                            @endif

                        </tbody>
                    </table>

                    @if (!empty($search))
                        @forelse($officeTranslations as $officeTranslation)
                            @if (app()->getLocale() == $officeTranslation->locale)
                                @php $office = $officeTranslation->office; @endphp
                                {{-- level two (directores and same level) --}}
                                <div class="collapse mx-auto" id="off{{ $office->id }}">
                                    <div class="card card-body" style="background:#8cffad23; padding: 70px; border: 1px solid;">
                                        @php
                                            $offices_twos = $office->offices;
                                        @endphp
                                        @php $count1 =0;@endphp
                                        @forelse ($offices_twos as $office)
                                            @php
                                                $count1 = $count1+1;
                                                $count =  $count1;
                                            @endphp
                                            @include('app.office_translations.sub')
                                            <div class="collapse" id="off{{ $office->id }}">
                                                <div class="card card-body" style="padding: 70px; border: 1px solid;">
                                                    @php
                                                        $offices_threes = $office->offices;
                                                        $count2 =0;
                                                    @endphp
                                                    @forelse ($offices_threes as $office)
                                                        @php
                                                            $count2 = $count2+1;
                                                            $count =  $count2;
                                                        @endphp
                                                        @include('app.office_translations.sub')
                                                        <div class="collapse" id="off{{ $office->id }}">
                                                            <div class="card card-body" style="background:#8cffad23; padding: 70px; border: 1px solid;">
                                                                @php
                                                                    $offices_fours = $office->offices;
                                                                    $count3 =0;
                                                                @endphp
                                                                @forelse ($offices_fours as $office)
                                                                    @php
                                                                        $count3 = $count3+1;
                                                                        $count =  $count3;
                                                                    @endphp
                                                                    @include('app.office_translations.sub')
                                                                    <div class="collapse" id="off{{ $office->id }}">
                                                                        <div class="card card-body" style="background:#D3D3D3; padding: 70px; border: 1px solid;">
                                                                            @php
                                                                                $offices_fives = $office->offices;
                                                                                $count4 =0;
                                                                            @endphp
                                                                            @forelse ($offices_fives as $office)
                                                                                @php
                                                                                    $count4 = $count4+1;
                                                                                    $count =  $count4;
                                                                                @endphp
                                                                                @include('app.office_translations.sub')
                                                                                <div class="collapse"
                                                                                    id="off{{ $office->id }}">
                                                                                    <div class="card card-body" style=" padding: 70px; border: 1px solid;">
                                                                                        @php
                                                                                            $offices_sixs = $office->offices;
                                                                                            $count5 = 0;
                                                                                        @endphp
                                                                                        @forelse ($offices_sixs as $office)
                                                                                            @php
                                                                                                $count5 = $count5+1;
                                                                                                $count =  $count5;
                                                                                            @endphp
                                                                                            @include('app.office_translations.sub')
                                                                                            <div class="collapse"
                                                                                                id="off{{ $office->id }}">
                                                                                                <div class="card card-body">
                                                                                                    @php
                                                                                                        $offices_sevens = $office->offices;
                                                                                                        $count6 = 0;
                                                                                                    @endphp
                                                                                                    @forelse ($offices_sevens as $office)
                                                                                                        @php
                                                                                                            $count6 = $count6+1;
                                                                                                            $count =  $count6;
                                                                                                        @endphp
                                                                                                        @include('app.office_translations.sub')
                                                                                                        <div class="collapse"
                                                                                                            id="off{{ $office->id }}">
                                                                                                            <div class="card card-body">
                                                                                                                {{-- uytty --}}
                                                                                                                @php
                                                                                                                    $offices_eights = $office->offices;
                                                                                                                    $count7 = 0;
                                                                                                                @endphp
                                                                                                                @forelse ($offices_eights as $office)
                                                                                                                    @php
                                                                                                                        $count7 = $count7+1;
                                                                                                                        $count =  $count7;
                                                                                                                    @endphp
                                                                                                                    @include('app.office_translations.sub')
                                                                                                                    <div class="collapse"
                                                                                                                        id="off{{ $office->id }}">
                                                                                                                        <div class="card card-body">

                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                @empty
                                                                                                                    <h5>No offices found!</h5>
                                                                                                                @endforelse
                                                                                                                {{-- hygutfgyhk --}}
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
                                        @empty
                                            <h5>No offices found!</h5>
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                        @empty
                        @endforelse
                    @else
                        {{-- @php
                            $officeT = !empty($search) ? $officeTranslations : $officesT;
                        @endphp --}}
                        @forelse($officesT as $officeTranslation)
                            @if (app()->getLocale() == $officeTranslation->locale)
                                @php $office = $officeTranslation->office; @endphp
                                {{-- level two (directores and same level) --}}
                                <div class="collapse mx-auto" id="off{{ $office->id }}">
                                    <div class="card card-body" style="background:#8cffad23; padding: 70px; border: 1px solid;">
                                        @php
                                            $offices_twos = $office->offices;
                                        @endphp
                                        @php $count1 =0;@endphp
                                        @forelse ($offices_twos as $office)
                                            @php
                                                $count1 = $count1+1;
                                                $count =  $count1;
                                            @endphp
                                            @include('app.office_translations.sub')
                                            <div class="collapse" id="off{{ $office->id }}">
                                                <div class="card card-body" style="padding: 70px; border: 1px solid;">
                                                    @php
                                                        $offices_threes = $office->offices;
                                                        $count2 =0;
                                                    @endphp
                                                    @forelse ($offices_threes as $office)
                                                        @php
                                                            $count2 = $count2+1;
                                                            $count =  $count2;
                                                        @endphp
                                                        @include('app.office_translations.sub')
                                                        <div class="collapse" id="off{{ $office->id }}">
                                                            <div class="card card-body" style="background:#8cffad23; padding: 70px; border: 1px solid;">
                                                                @php
                                                                    $offices_fours = $office->offices;
                                                                    $count3 =0;
                                                                @endphp
                                                                @forelse ($offices_fours as $office)
                                                                    @php
                                                                        $count3 = $count3+1;
                                                                        $count =  $count3;
                                                                    @endphp
                                                                    @include('app.office_translations.sub')
                                                                    <div class="collapse" id="off{{ $office->id }}">
                                                                        <div class="card card-body" style="background:#D3D3D3; padding: 70px; border: 1px solid;">
                                                                            @php
                                                                                $offices_fives = $office->offices;
                                                                                $count4 =0;
                                                                            @endphp
                                                                            @forelse ($offices_fives as $office)
                                                                                @php
                                                                                    $count4 = $count4+1;
                                                                                    $count =  $count4;
                                                                                @endphp
                                                                                @include('app.office_translations.sub')
                                                                                <div class="collapse"
                                                                                    id="off{{ $office->id }}">
                                                                                    <div class="card card-body" style=" padding: 70px; border: 1px solid;">
                                                                                        @php
                                                                                            $offices_sixs = $office->offices;
                                                                                            $count5 = 0;
                                                                                        @endphp
                                                                                        @forelse ($offices_sixs as $office)
                                                                                            @php
                                                                                                $count5 = $count5+1;
                                                                                                $count =  $count5;
                                                                                            @endphp
                                                                                            @include('app.office_translations.sub')
                                                                                            <div class="collapse"
                                                                                                id="off{{ $office->id }}">
                                                                                                <div class="card card-body">
                                                                                                    @php
                                                                                                        $offices_sevens = $office->offices;
                                                                                                        $count6 = 0;
                                                                                                    @endphp
                                                                                                    @forelse ($offices_sevens as $office)
                                                                                                        @php
                                                                                                            $count6 = $count6+1;
                                                                                                            $count =  $count6;
                                                                                                        @endphp
                                                                                                        @include('app.office_translations.sub')
                                                                                                        <div class="collapse"
                                                                                                            id="off{{ $office->id }}">
                                                                                                            <div class="card card-body">
                                                                                                                {{-- uytty --}}
                                                                                                                @php
                                                                                                                    $offices_eights = $office->offices;
                                                                                                                    $count7 = 0;
                                                                                                                @endphp
                                                                                                                @forelse ($offices_eights as $office)
                                                                                                                    @php
                                                                                                                        $count7 = $count7+1;
                                                                                                                        $count =  $count7;
                                                                                                                    @endphp
                                                                                                                    @include('app.office_translations.sub')
                                                                                                                    <div class="collapse"
                                                                                                                        id="off{{ $office->id }}">
                                                                                                                        <div class="card card-body">

                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                @empty
                                                                                                                    <h5>No offices found!</h5>
                                                                                                                @endforelse
                                                                                                                {{-- hygutfgyhk --}}
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
                                        @empty
                                            <h5>No offices found!</h5>
                                        @endforelse
                                    </div>
                                </div>
                            @endif

                        @empty
                        @endforelse
                    @endif

                    {{-- <div class="float-right">
                        {!! $officeTranslations->render() !!}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
