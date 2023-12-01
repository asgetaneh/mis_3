@extends('layouts.app')
@section('title', 'EMIS Staff - Overview')

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
                    <h3 class="card-title">Overview List</h3>
                </div>
            </div>
            <div class="card-body">

                {{-- <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Filter</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="display: none;">
                    <form role="form" class="" method="post" action="">
                        @csrf
                        <div class="row m-0 p-0">
                            <div class="">
                                <label class="label" for="filters">Option 1:</label>
                                <div>
                                    <select class="form-control select2" name="name">
                                        <option value="">Select</option>
                                    </select>
                                </div>

                            </div>
                            <div class="">
                                <label class="" for="filters">Option 2:</label>
                                <div>
                                    <select class="form-control select2" name="name">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="">
                                <label class="label" for="action">Action</label>
                                <div id="action">
                                    <button class="btn btn-primary" value="filter" name="filter"
                                        type="submit">Filter</button>
                                    <span class="border border-right mx-3"></span>
                                    <button class="btn btn-success" value="excel" name="excel" type="submit">Download
                                        Excel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div> --}}

                <button class="btn btn-success">Download Excel</button>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-left">
                                    staff_national_id
                                </th>
                                <th>first_name
                                </th>
                                <th class="text-left">
                                    first_name_lng
                                </th>
                                <th class="text-left">
                                    last_name
                                </th>
                                <th>
                                    last_name_lng
                                </th>
                                <th class="text-left">
                                    father_name
                                </th>
                                <th>
                                    father_name_lng
                                </th>
                                <th>grandfather_name</th>
                                <th class="text-left">
                                    grandfather_name_lng
                                </th>
                                <th>date_of_birth
                                </th>
                                <th class="text-left">
                                    place_of_birth
                                </th>
                                <th class="text-left">
                                    email
                                </th>
                                <th>
                                    phone
                                </th>
                                <th>gender</th>
                                <th>country</th>
                                <th>citizenship</th>
                                <th>region</th>
                                <th>zone</th>
                                <th>woreda</th>
                                <th>kebele</th>
                                <th>specialization_local_band</th>
                                <th>specialization_substudy_field
                                </th>
                                <th>retirement_date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $key => $employee)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td> --</td>
                                    <td> {{ $employee->first_name }} </td>
                                    <td> {{ $employee->first_name_am }} </td>
                                    <td> {{ $employee->father_name }} </td>
                                    <td> {{ $employee->father_name_am }} </td>
                                    <td> {{ $employee->father_name }} </td>
                                    <td> {{ $employee->father_name_am }} </td>
                                    <td> {{ $employee->grand_father_name }} </td>
                                    <td> {{ $employee->grand_father_name_am }} </td>
                                    <td> {{ $employee->date_of_birth }} </td>
                                    <td> {{ $employee->birth_city }} </td>
                                    <td> {{ $employee->email }} </td>
                                    <td> {{ $employee->phone_number }} </td>
                                    <td> {{ $employee->gender }} </td>
                                    <td>ET</td>
                                   
                                    <td> {{ $employee->nation }} </td>
                                    <td> {{ $employee->region }} </td>
                                    <td> {{ $employee->zone }} </td>
                                    <td> {{ $employee->woreda }} </td>
                                    <td> {{ $employee->kebele }} </td>
                                    <td> ?</td>
                                    <td> {{ $employee->field_of_study }} </td>
                                    <td> ? </td>
                                   
                                  
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
                    {!! $overviews->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
