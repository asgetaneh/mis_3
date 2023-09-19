@extends('layouts.app')
@section('title', 'Reporting Period Index')

@section('content')
<div class="container-fluid">
    <div class="searchbar mt-0 mb-4">
        <div class="row">
            <div class="col-md-6">
                <form>
                    <div class="input-group">
                        <input
                            id="indexSearch"
                            type="text"
                            name="search"
                            placeholder="{{ __('crud.common.search') }}"
                            value="{{ $search ?? '' }}"
                            class="form-control"
                            autocomplete="off"
                        />
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon ion-md-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right">
                @can('create', App\Models\ReportingPeriod::class)
                <a
                    href="{{ route('reporting-periods.create') }}"
                    class="btn btn-primary"
                >
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between;">
                <h4 class="card-title">
                    @lang('crud.reporting_periods.index_title')
                </h4>
            </div>

            <div class="table-responsive" id="example1_wrapper">
                <table class="table table-bordered mt-3 table-hover" id="reporting-periods">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-left">
                               Name
                            </th>
                            <th class="text-left">
                                @lang('crud.reporting_periods.inputs.start_date')
                            </th>
                            <th class="text-left">
                                @lang('crud.reporting_periods.inputs.end_date')
                            </th>
                            <th class="text-left">
                                @lang('crud.reporting_periods.inputs.reporting_period_type_id')
                            </th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @forelse($reportingPeriodTS as $reporting_period_t)
                        @if(app()->getLocale() == $reporting_period_t->locale)
                        <tr>
                            <td>{{ $count++ }}</td>
                             <td>{{ $reporting_period_t->name ?? '-' }}
                            </td>
                            <td>{{ $reporting_period_t->reportingPeriod->start_date ?? '-' }}
                            </td>
                            <td>{{ $reporting_period_t->reportingPeriod->end_date ?? '-' }}
                            </td>

                            @php
                                $reportPeriodType = "";
                                 foreach ($reporting_period_t->reportingPeriod->reportingPeriodType->reportingPeriodTypeTs as $key => $value){
                                    if(app()->getLocale() == $value->locale){
                                        $reportPeriodType = $value->name;
                                    }
                                }
                            @endphp
                            <td>
                                {{
                                $reportPeriodType
                                ?? '-' }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $reporting_period_t)
                                    <a
                                        href="{{ route('reporting-periods.edit', $reporting_period_t->reporting_period_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view', $reporting_period_t)
                                    <a
                                        href="{{ route('reporting-periods.show', $reporting_period_t->reporting_period_id) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete', $reporting_period_t)
                                    <form
                                        action="{{ route('reporting-periods.destroy', $reporting_period_t->reporting_period_id) }}"
                                        method="POST"
                                        onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
                                    >
                                        @csrf @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-light text-danger"
                                        >
                                            <i class="icon ion-md-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="5">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="float-right">
                    {!! $reportingPeriodTS->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        $("#reporting-periods").DataTable({
            "ordering" : true,
            // "paging" : false,
            "pageLength" : 10,
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        //     buttons: [
        //     {
        //         extend: 'excelHtml5',
        //         exportOptions: {
        //             columns: [ 0, 1, 2, 3, 4 ]
        //         }
        //     },
        //     {
        //         extend: 'pdfHtml5',
        //         exportOptions: {
        //             columns: [ 0, 1, 2, 3, 4 ]
        //         }
        //     },
        //     {
        //         extend: 'csvHtml5',
        //         exportOptions: {
        //             columns: [0,1,2,3,4]
        //         }
        //     },
        //     {
        //         extend: 'print',
        //         exportOptions: {
        //             columns: [0,1,2,3,4]
        //         }
        //     },
        // ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    </script>

@endpush
