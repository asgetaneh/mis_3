@extends('layouts.app')
@section('title', 'EMIS Student - Applicant')

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
                    <h3 class="card-title">Applicants List</h3>
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

                {{-- <button class="btn btn-success">Download Excel</button> --}}

                <div class="mt-3">
                    <table id="emisTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-left">#</th>
                                <th class="text-left">
                                    applicant_id
                                </th>
                                <th class="text-left">
                                    academic_year
                                </th>
                                <th>
                                    secondary_education_stream
                                </th>
                                <th>qualification_level
                                </th>
                                <th class="text-left">
                                    hed_institution_code
                                </th>
                                <th class="text-left">
                                    department_code
                                </th>
                                <th>
                                    program_code
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
                                <th>woreda</th>
                                <th>kebele</th>
                                <th>area_type</th>
                                <th>hed_acceptance_score
                                </th>
                                <th>public_hed_acceptance_score
                                </th>
                                <th>national_exam_score
                                </th>
                                <th>hed_acceptance_status
                                </th>
                                <th>student_disabilty</th>
                                <th>specially_gifted
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applicants as $key => $applicant)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $applicant->student_id ?? '' }}</td>
                                    <td>{{ $applicant->academic_year ? str_replace('/', '', $applicant->academic_year) : '' }}</td>
                                    <td>{{ $applicant->secondary_education_stream ?? '' }}</td>
                                    <td>{{ $applicant->program_level_code ?? '' }}</td>
                                    <td>{{ $applicant->hed_institution_code ?? '' }}</td>
                                    <td>{{ $applicant->department_code ?? '' }}</td>
                                    <td>{{ $applicant->program_code ?? '' }}</td>
                                    <td>{{ $applicant->first_name ?? '' }}</td>
                                    <td>{{ $applicant->first_name_lng ?? '' }}</td>
                                    <td>{{ $applicant->fathers_name ?? '' }}</td>
                                    <td>{{ $applicant->fathers_name_lng ?? '' }}</td>
                                    <td>{{ $applicant->grand_fathers_name ?? '' }}</td>
                                    <td>{{ $applicant->grand_fathers_name_lng ?? '' }}</td>
                                    <td>{{ $applicant->grand_fathers_name ?? '' }}</td>
                                    <td>{{ $applicant->last_name_lng ?? '' }}</td>
                                    <td>{{ $applicant->birth_date ?? '' }}</td>
                                    <td>{{ $applicant->place_of_birth ?? '' }}</td>
                                    <td>{{ $applicant->email_address ?? '' }}</td>
                                    <td>{{ $applicant->telephone ?? '' }}</td>
                                    <td>{{ $applicant->sex ?? '' }}</td>
                                    <td>{{ $applicant->country_code ?? '' }}</td>
                                    <td>{{ $applicant->country_code ?? '' }}</td>
                                    <td>{{ $applicant->state_code ?? '' }}</td>
                                    <td>{{ $applicant->zone_code ?? '' }}</td>
                                    <td>{{ $applicant->woreda_code ?? '' }}</td>
                                    <td>{{ $applicant->kebele ?? '' }}</td>
                                    <td>{{ $applicant->area_type ?? 'NPS' }}</td>
                                    <td>{{ $applicant->hed_acceptance_score ?? '' }}</td>
                                    <td>{{ $applicant->public_hed_acceptance_score ?? '' }}</td>
                                    <td>{{ $applicant->national_exam_score ?? '' }}</td>
                                    <td>{{ $applicant->hed_acceptance_status ?? '' }}</td>
                                    <td>{{ $applicant->student_disability ?? '' }}</td>
                                    <td>{{ $applicant->specially_gifted ?? 'N' }}</td>
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
                {{-- <div class="float-right mt-3">
                    {!! $applicants->render() !!}
                </div> --}}
            </div>
        </div>
    </div>
@endsection
