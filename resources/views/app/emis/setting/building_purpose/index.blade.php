@extends('layouts.app')
@section('title', 'EMIS Student - Enrollment')

@section('style')
    <style>
        /* Prevent any data making a break on the td tags */
        td {
            white-space: nowrap !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
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
                             <a
                                href="{{ route('emis.setting.building.purpose.new') }}"
                                class="btn btn-primary"
                            >
                                <i class="icon ion-md-add"></i> @lang('crud.common.create')
                            </a>
                         </div>
                    </div>
                </div>
            </div>
            <div class="card-body">



                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-left">
                                     code
                                </th>
                                <th class="text-left">
                                    name
                                </th>
                                 <th>
                                    description
                                </th>
                                 
                                <th>settlement_type</th>
                                <th>settlement_date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($building_purpose as $key => $ob)
                              
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                     <td>{{ $ob->code ?? ''  }}</td>
                                    <td>{{ $ob->name ?? ''  }}</td>
                                    <td>{{ $ob->description ?? '' }}</td>
                                     
                                    <td>{{ $ob->created_at ?? '' }}</td>
                                    <td>{{ $ob->updated_at ?? '' }}</td>
                                    <td>
                                         <a
                                        href="{{ route('emis.setting.building.purpose.edit', $ob->id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    </td>
                                   
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="float-right mt-3">
                    {!! $building_purpose->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
