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
                                    institution_code
                                </th>
                                <th class="text-left">
                                    student_national_id
                                </th>
                                <th>
                                    academic_year
                                </th>
                                <th>academic_period
                                </th>
                                <th class="text-left">
                                    academic_term
                                </th>
                                <th class="text-left">
                                    campus_code
                                </th>
                                <th>
                                    program
                                </th>
                                <th>program_modality</th>
                                <th>target_qualification
                                </th>
                                <th>year_level</th>
                                <th class="text-left">
                                    enrollment_type
                                </th>
                                <th class="text-left">
                                    foreign_program
                                </th>
                                <th>
                                    economically_supported
                                </th>
                                <th>required_academic_periods</th>
                                <th>required_credits</th>
                                <th>current_registered_credits</th>
                                <th>cumulative_registred_credits
                                </th>
                                <th>cumulative_completed_credits
                                </th>
                                <th>cumulative_gpa</th>
                                <th>outgoing_exchange
                                </th>
                                <th>incoming_exchange
                                </th>
                                <th>exchange_country
                                </th>
                                <th>exchange_institution
                                </th>
                                <th>exchange_institution_lng
                                </th>
                                <th>sponsorship
                                </th>
                                <th>student_economical_status
                                </th>
                                <th>student_disability
                                </th>
                                <th>specially_gifted
                                </th>
                                <th>food_service_type
                                </th>
                                <th>dormitory_service_type
                                </th>
                                <th>cost_sharing_loan</th>
                                <th>current_cost_sharing</th>
                                <th>accumulated_cost_sharing</th>
                                <th>settlement_type</th>
                                <th>settlement_date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $key => $enrollment)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $enrollment->institution_code ?? '' }}</td>
                                    <td>{{ $enrollment->student_id ?? '' }}</td>
                                    <td>{{ $enrollment->academic_year ?? '' }}</td>
                                    <td>{{ $enrollment->academic_period ?? '' }}</td>
                                    <td>{{ $enrollment->academic_term ?? '' }}</td>
                                    <td>{{ $enrollment->campus_code ?? '' }}</td>
                                    <td>{{ $enrollment->program_code ?? '' }}</td>
                                    <td>{{ $enrollment->program_modality ?? '' }}</td>
                                    <td>{{ $enrollment->target_qualification ?? '' }}</td>
                                    <td>{{ $enrollment->year_level ?? '' }}</td>
                                    <td>{{ $enrollment->enrollment_type ?? '' }}</td>
                                    <td>{{ $enrollment->foreign_program ?? '' }}</td>
                                    <td>{{ $enrollment->economically_supported ?? '' }}</td>
                                    <td>{{ $enrollment->required_academic_periods ?? '' }}</td>
                                    <td>{{ $enrollment->required_credits ?? '' }}</td>
                                    <td>{{ $enrollment->current_registered_credits ?? '' }}</td>
                                    <td>{{ $enrollment->cumulative_registered_credits ?? '' }}</td>
                                    <td>{{ $enrollment->cumulative_completed_credits ?? '' }}</td>
                                    <td>{{ $enrollment->cumulative_gpa ?? '' }}</td>
                                    <td>{{ $enrollment->outgoing_exchange ?? '' }}</td>
                                    <td>{{ $enrollment->incoming_exchange ?? '' }}</td>
                                    <td>{{ $enrollment->exchange_country ?? '' }}</td>
                                    <td>{{ $enrollment->exchange_institution ?? '' }}</td>
                                    <td>{{ $enrollment->exchange_institution_lng ?? '' }}</td>
                                    <td>{{ $enrollment->sponsorship ?? '' }}</td>
                                    <td>{{ $enrollment->student_economical_status ?? '' }}</td>
                                    <td>{{ $enrollment->student_disability ?? '' }}</td>
                                    <td>{{ $enrollment->specially_gifted ?? '' }}</td>
                                    <td>{{ $enrollment->food_service_type ?? '' }}</td>
                                    <td>{{ $enrollment->dormitory_service_type ?? '' }}</td>
                                    <td>{{ $enrollment->cost_sharing_loan ?? '' }}</td>
                                    <td>{{ $enrollment->current_cost_sharing ?? '' }}</td>
                                    <td>{{ $enrollment->accumulated_cost_sharing ?? '' }}</td>
                                    <td>{{ $enrollment->settlement_type ?? '' }}</td>
                                    <td>{{ $enrollment->settlment_date ?? '' }}</td>
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
                    {!! $enrollments->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
