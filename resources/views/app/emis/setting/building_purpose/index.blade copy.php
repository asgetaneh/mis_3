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
                <div style="">
                    <h3 class="card-title">Enrollment List</h3>
                </div>&nbsp;&nbsp;
                <button class="btn btn-success">Download Excel</button>
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
                            @forelse($campus as $key => $campus_ob)
                              
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                     <td>{{ $campus_ob->code ?? ''  }}</td>
                                    <td>{{ $campus_ob->name ?? ''  }}</td>
                                    <td>{{ $campus_ob->description ?? '' }}</td>
                                     
                                    <td>{{ $campus_ob->created_at ?? '' }}</td>
                                    <td>{{ $campus_ob->updated_at ?? '' }}</td>
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
                    {!! $campus->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
