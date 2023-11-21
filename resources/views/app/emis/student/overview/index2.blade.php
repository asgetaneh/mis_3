@extends('layouts.app')
@section('title', 'EMIS Student - Overview')

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
                    <h3 class="card-title">Id match List
                     </h3>
                 </div>
                   
            </div>
            <div class="card-body">

                
                <div class="table-responsive mt-3">
                <form action="{{ route('emis.setting.student-id-import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control">
                    <button class="btn btn-success">Import User Data</button>
                </form>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-left">
                                    Student_national_id
                                </th>
                                <th class="text-left">
                                    Student_institution_id
                                </th>
                                 
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($NationInstitution as $key => $overview)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $overview->nation_id ?? '' }}</td>
                                    <td>{{ $overview->institution_id ?? '' }}</td>
                                     

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
                    {!! $NationInstitution->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
