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
                                <th class="text-left"> Institution code </th>
                                <th class="text-left"> Building code </th>
                                <th class="text-left"> Building name</th>
                                <th class="text-left"> Contractor_name</th>
                                <th class="text-left">consultant_name</th>
                                <th class="text-left">date_started</th>
                                <th class="text-left">date_completed</th>
                                <th class="text-left">total_cost</th>
                                <th class="text-left"> building_purposes</th>
                                <th class="text-left">building_type</th>
                                <th class="text-left">building_owner</th>
                                <th class="text-left">meets_standard</th>
                                <th class="text-left">meets_psn_standard</th>
                                <th> description </th>
                                <th>settlement_type</th>
                                <th>settlement_date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($building as $key => $ob)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $ob->code ?? ''  }}</td>
                                    <td>{{ $ob->code ?? ''  }}</td>
                                    <td>{{ $ob->name ?? ''  }}</td>
                                    <td>{{ $ob->contractor_name ?? '' }}</td>
                                     <td>{{ $ob->consultant_name ?? ''  }}</td>
                                    <td>{{ $ob->date_started ?? ''  }}</td>
                                    <td>{{ $ob->date_completed ?? ''  }}</td>
                                    <td>{{ $ob->total_cost ?? ''  }}</td>
                                    <td>{{ $ob->building_purposes ?? ''  }}</td>
                                    <td>{{ $ob->building_type ?? ''  }}</td>
                                    <td>{{ $ob->building_owner ?? ''  }}</td>
                                    <td>{{ $ob->meets_standard ?? ''  }}</td>
                                    <td>{{ $ob->meets_psn_standard ?? ''  }}</td>
                                    <td>{{ $ob->created_at ?? '' }}</td>
                                    <td>{{ $ob->updated_at ?? '' }}</td>
                                    <td>
                                         <a href="{{ route('emis.setting.building.purpose.edit', $ob->id) }}">
                                        <button  type="button"  class="btn btn-light" >
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
                    {!! $building->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
