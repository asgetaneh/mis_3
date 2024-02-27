@extends('layouts.app')
@section('title', 'EMIS Staff - Assignment')

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
                    <h3 class="card-title">Assignment List</h3>
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
                                <th>institution_code</th>
                                <th>staff_national_id</th>
                                <th>academic_year</th>
                                <th>academic_period</th>
                                <th>campus_code</th>
                                <th>staff_category</th>
                                <th>job_title</th>
                                <th>academic_rank</th>
                                <th>employment_date</th>
                                <th>employment_type</th>
                                <th>monthly_salary</th>
                                <th>isced_education_level</th>
                                <th>is_under_qualified</th>
                                <th>nbr_courses_taught</th>
                                <th>nbr_credit_hours_taught</th>
                                <th>nbr_modules_taught</th>
                                <th>nbr_postgraduates_advised</th>
                                <th>standard_teaching_load</th>
                                <th>community_service_load</th>
                                <th>research_load</th>
                                <th>teaching_overload</th>
                                <th>overload_reason</th>
                                <th>science_training</th>
                                <th>research_training</th>
                                <th>teaching_license</th>
                                <th>completed_cpd_hdp</th>
                                <th>outgoing_exchange</th>
                                <th>exchange_country</th>
                                <th>industry_experience</th>
                                <th>leadership_training</th>
                                <th>merit_based</th>
                                <th>digital_skills_training</th>
                                <th>professional_training</th>
                                <th>research_ethics_training</th>
                                <th>digital_instruction_training</th>
                                <th>elip_training</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $key => $assignment)
                                <tr>
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
                    {!! $assignments->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
