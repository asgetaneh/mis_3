@extends('layouts.app')
@section('title', 'EMIS Student - Other Reports')

@section('content')
    <div class="container-fluid">
        <div class="card card-outline-primary">
            <div class="card-header">
                <h3 class="card-title">Filtered Reports</h3>
            </div>
            <div class="card-body">
                <form id="" action="{{ route('emis.student.others.filter') }}" method="get">
                    {{-- @csrf --}}
                    <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                <label>Select Report Type</label>
                                <select class="form-control select2" name="report-type" required id="report-type">
                                    <option disabled {{ old('report-type') == '' ? 'selected' : '' }} value="">Select
                                        Report Type</option>
                                    <option value="1" {{ old('report-type') == '1' ? 'selected' : '' }}>Student's age by Sex</option>
                                    <option value="2" {{ old('report-type') == '2' ? 'selected' : '' }}>Completion Rate of UG Students in Percent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>Select Academic Year</label>
                                <select class="form-control select2" name="academic-year" id="academic-year">
                                    <option disabled {{ old('academic-year') == '' ? 'selected' : '' }} value="">
                                        Select Academic Year</option>
                                    <option value="">ALL ACADEMIC YEARS</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="">...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>Select Semester</label>
                                <select class="form-control select2" name="semester" id="semester">
                                    <option disabled {{ old('semester') == '' ? 'selected' : '' }} value="">Select
                                        Semester</option>
                                    <option value="">ALL SEMESTERS</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>

                        {{-- Didn't get the purpose of this 'year' filter option --}}
                        <div class="col-2">
                            <div class="form-group">
                                <label>Select Year</label>
                                <select class="form-control select2" name="year" id="year">
                                    <option disabled {{ old('year') == '' ? 'selected' : '' }} value="">Select Year
                                    </option>
                                    <option value="">ALL YEAR</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                </select>
                            </div>
                        </div>

                        <div class="">
                            <div class="form-group">
                                <label>Actions</label>
                                <div>
                                    <button id="" class="btn btn-primary mr-2">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Table to be drawn after AJAX request --}}
                <table class="table table-bordered report-table" id="report-table">
                    @if (isset($query))
                        <button class="btn btn-flat btn-success" value="{{ $reportType }}">Download Filtered Excel</button>
                        {!! $output !!}
                    @endif
                </table>

                @if (isset($query))
                    <div class="float-right mt-2" id="pagination">
                        {!! $query->appends(['report-type' => request('report-type')])->render() !!}
                    </div>
                @endif


            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            $('#filterReport').click(function() {
                event.preventDefault(); // Prevent the default form submission

                // get all value from the select fields
                var reportType = $('#report-type').val();
                var academicYear = $('#academic-year').val();
                var semester = $('#semester').val();
                var year = $('#year').val();

                // call ajax to the controller to get data based on provided filters
                $.ajax({
                    type: "GET",
                    url: "{{ url('/emis/student/others/filter') }}",
                    data: {
                        reportType: reportType,
                        academicYear: academicYear,
                        semester: semester,
                        year: year,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);

                        // draw the table according to the report type needed below
                        document.getElementById('report-table').innerHTML = response.data;

                        $('#pagination').html(response.links);

                    }
                });
            });
        });
    </script>

@endsection
