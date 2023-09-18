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
                @can('create', App\Models\Role::class)
                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between;">
                <h4 class="card-title">@lang('crud.roles.index_title')</h4>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-left">
                                @lang('crud.roles.inputs.name')
                            </th>
                            {{-- <th>Total users</th> --}}
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @forelse($roles as $role)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>{{ $role->name ?? '-' }}</td>
                            {{-- <td>
                                <span class="badge badge-primary badge-pill"> {{ $role->users->count() }}</span>
                            </td> --}}
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('view', $role)
                                        <a href="{{ route('roles.permissions', $role) }}" title="Add Permission">
                                            <button
                                                type="button"
                                                class="btn btn-light"
                                            >
                                                <i class="icon fas fa-check-double fa-sm"></i>
                                            </button>
                                        </a>
                                    @endcan

                                    @can('view', $role)
                                        <a href="{{ route('roles.users', $role) }}" title="Add Users">
                                            <button
                                                type="button"
                                                class="btn btn-light"
                                            >
                                                <i class="icon fas fa-users fa-sm"></i>
                                            </button>
                                        </a>
                                    @endcan

                                    @can('update', $role)
                                    <a href="{{ route('roles.edit', $role) }}" title="Edit role">
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon fas fa-pen fa-sm"></i>
                                        </button>
                                    </a>
                                    @endcan
                                    {{-- @can('view', $role)
                                    <a href="{{ route('roles.show', $role) }}">
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan --}}
                                    {{-- @can('delete', $role)
                                    <form
                                        action="{{ route('roles.destroy', $role) }}"
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
                        @empty
                        <tr>
                            <td colspan="2">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="float-right">
                    {!! $roles->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
