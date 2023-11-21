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
                    <h3 class="card-title">Overview List</h3>
                    <button class="btn btn-success pull-right" style="float:50%"> Download Excel</button>
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


                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-left">
                                    student_national_id
                                </th>
                                <th class="text-left">
                                    student_institution_id
                                </th>
                                <th>first_name
                                </th>
                                <th class="text-left">
                                    first_name_lng
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
                                <th class="text-left">
                                    last_name
                                </th>
                                <th>
                                    last_name_lng
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
                                <th>kebele</th>
                                <th>woreda</th>
                                <th>area_type</th>
                                <th>student_national_examiniation_id
                                </th>
                                <th>student_tax_identification_number
                                </th>
                                <th>student_national_system_id
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($overviews as $key => $overview)
                                @php
                                  $nation_id = $nation_institute_id-> getNationalId($overview->student_id); 
                                 @endphp
                                
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $nation_id[0]['nation_id'] ?? '' }}</td>
                                    <td>{{ $overview->student_id ?? '' }}</td>
                                    <td>{{ $overview->first_name ?? '' }}</td>
                                    <td>{{ $overview->first_name_lng ?? '' }}</td>
                                    <td>{{ $overview->fathers_name ?? '' }}</td>
                                    <td>{{ $overview->fathers_name_lng ?? '' }}</td>
                                    <td>{{ $overview->grand_fathers_name ?? '' }}</td>
                                    <td>{{ $overview->grand_fathers_name_lng ?? '' }}</td>
                                    <td>{{ $overview->grand_fathers_name ?? '' }}</td>
                                    <td>{{ $overview->last_name_lng ?? '' }}</td>
                                    <td>{{ $overview->birth_date ?? '' }}</td>
                                    <td>{{ $overview->place_of_birth ?? '' }}</td>
                                    <td>{{ $overview->email_address ?? '' }}</td>
                                    <td>{{ $overview->telephone ?? '' }}</td>
                                    <td>{{ $overview->sex ?? '' }}</td>
                                    <td>{{ $overview->country_code ?? '' }}</td>
                                    <td>{{ $overview->country_code ?? '' }}</td>
                                    <td>{{ $overview->state_code ?? '' }}</td>
                                    <td>{{ $overview->zone_code ?? '' }}</td>
                                    <td>{{ $overview->kebele ?? '' }}</td>
                                    <td>{{ $overview->woreda_code ?? '' }}</td>
                                    <td>{{ $overview->area_type ?? 'NPS' }}</td>
                                    <td>{{ $overview->entrance_exam_id ?? '' }}</td>
                                    <td>{{ $overview->student_tax_identification_number ?? '' }}</td>
                                    <td>{{ $overview->student_national_system_id ?? '' }}</td>

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
