@extends('layouts.app')
@section('title', 'EMIS Student - Employment')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div style="">
                    <h3 class="card-title">Employment List</h3>
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
                                <th class="text-left">
                                    institution_code
                                </th>
                                <th class="text-left">
                                    student_national_id
                                </th>
                                <th>
                                    academic_period
                                </th>
                                <th>
                                    academic_year
                                </th>

                                <th class="text-left">
                                    email
                                </th>
                                <th>
                                    phone
                                </th>
                                <th>graduation_date
                                </th>
                                <th class="text-left">
                                    employment_status
                                </th>
                                <th class="text-left">
                                    employment_date
                                </th>
                                <th>
                                    employer
                                </th>
                                <th>salary_range
                                </th>
                                <th class="text-left">
                                    isic_industry_sector
                                </th>
                                <th class="text-left">
                                    employer_type
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employments as $key => $employment)
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
                    {!! $employments->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
