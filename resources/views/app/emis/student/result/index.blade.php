@extends('layouts.app')
@section('title', 'EMIS Student - Result')

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
                    <h3 class="card-title">Results List</h3>
                </div> &nbsp;&nbsp;
                {{-- <button class="btn btn-success">Download Excel</button> --}}
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
                    <table id="emisTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-left">
                                    institution_code
                                </th>
                                <th class="text-left">
                                    student_national_id
                                </th>
                                 <th class="text-left">
                                    JU_student_id
                                </th>
                                <th>
                                    academic_year
                                </th>
                                <th>academic_period
                                </th>
                                <th>
                                    total_accumulated_credits

                                </th>
                                <th>cgpa
                                </th>
                                <th class="text-left">
                                    total_academic_periods

                                </th>
                                <th class="text-left">
                                    result
                                </th>
                                <th>
                                    transfer
                                </th>
                                <th>gpa
                                </th>
                                <th class="text-left">
                                    digital_literacy_training

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $key => $result)
                             @php
                                  $nation_id = $nation_institute_id-> getNationalId($result->student_id);
                                 @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $result->department_code ?? '' }}</td>
                                    <td>{{ $nation_id[0]['nation_id'] ?? '' }}</td>
                                     <td>{{ $result->student_id ?? '' }}</td>
                                    <td>{{ $result->academic_year ? str_replace('/', '', $result->academic_year) : '' }}</td>
                                    <td>{{ isset($result->academic_period) ? 'S'.$result->academic_period : '' }}</td>
                                    <td>{{ $result->total_accumulated_credits ?? '' }}</td>
                                    <td>{{ $result->cgpa ?? '' }}</td>
                                    <td>{{ $result->total_academic_periods ?? '' }}</td>
                                    {{-- <td>{{ $result->result ?? '' }}</td> --}}

                                    <td>

                                        {{-- This checking is based on the id to identify only pass and fail statuses. --}}
                                        @if(isset($result->result))
                                            @if(in_array($result->result, [0,1,4,5]))
                                                {{ 'P' }}
                                            @elseif(in_array($result->result, [2,3,10]))
                                                {{ 'F' }}
                                            @else
                                                {{ '' }}
                                            @endif
                                        @else
                                            {{ '' }}
                                        @endif
                                    </td>
                                    <td>{{ isset($result->laction) ? ($result->laction == 6 ? 'Y' : 'N') : 'N' }}</td>
                                    <td>{{ $result->gpa ?? '' }}</td>
                                    <td>{{ $result->digital_literacy_training ?? 'N' }}</td>
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
                    {!! $results->render() !!}
                </div> --}}
            </div>
        </div>
    </div>
@endsection
