@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    @if (auth()->user()->offices->count() > 0)
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3" id="" title="click to view detail">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="fas fa-link"></i>
                    </span>
                    <a href="{{ route('key-peformance-indicators.index') }}">
                        <div class="info-box-content">
                            <span class="info-box-text">KPI</span>
                            <span class="info-box-number">{{ $totalKpis }}</span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box " id="" title="click to view detail">
                    <span class="info-box-icon bg-secondary elevation-1">
                        <i class="fas fa-eye"></i>
                    </span>
                    <a href="{{ route('perspectives.index') }}">
                        <div class="info-box-content">
                            <span class="info-box-text">Perspectives</span>
                            <span class="info-box-number">
                                {{ $totalPerspectives }}
                            </span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box " id="" title="click to view detail">
                    <span class="info-box-icon bg-warning elevation-1">
                        <i class="fas fa-bullseye"></i>
                    </span>
                    <a href="{{ route('goals.index') }}">
                        <div class="info-box-content">
                            <span class="info-box-text">Goals</span>
                            <span class="info-box-number">
                                {{ $totalGoals }}
                            </span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-12 col-sm-6 col-md-3">

                <div class="info-box " id="" title="click to view detail">
                    <span class="info-box-icon bg-info elevation-1">
                        <i class="fas fa-list"></i>
                    </span>
                    <a href="{{ route('objectives.index') }}">
                        <div class="info-box-content">
                            <span class="info-box-text">Objectives</span>
                            <span class="info-box-number">
                                {{ $totalObjectives }}
                            </span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>




            {{-- Only admin --}}
            @if (auth()->user()->is_admin === true ||
                    auth()->user()->hasRole('super-admin'))
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3" id="" title="click to view detail">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-building"></i>
                        </span>
                        <a href="{{ route('office-translations.index') }}">
                            <div class="info-box-content">
                                <span class="info-box-text">Offices</span>
                                <span class="info-box-number">{{ $totalOffices }}</span>
                            </div>
                        </a>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3" id="" title="click to view detail">
                        <span class="info-box-icon bg-primary elevation-1">
                            <i class="fas fa-users"></i>
                        </span>
                        <a href="{{ route('users.index') }}">

                            <div class="info-box-content">
                                <span class="info-box-text">Users</span>
                                <span class="info-box-number">{{ $totalUsers }}</span>
                            </div>
                        </a>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                {{-- <div class="col-12 col-sm-6 col-md-3">

                    <div class="info-box " id="" title="click to view detail">
                        <span class="info-box-icon bg-success elevation-1">
                            <i class="fas fa-check"></i>
                        </span>
                        <a href="{{ route('users.index') }}">
                            <div class="info-box-content">
                                <span class="info-box-text">Active Users</span>
                                <span class="info-box-number">
                                    0
                                </span>
                            </div>
                        </a>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3" id="" title="click to view detail">
                        <span class="info-box-icon bg-danger elevation-1">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>

                        <a href="{{ route('users.index') }}">
                            <div class="info-box-content">
                                <span class="info-box-text">Inactive Users</span>
                                <span class="info-box-number">0</span>
                            </div>
                        </a>
                        <!-- /.info-box-content -->
                    </div>
                </div> --}}
            @endif
            <!-- /.info-box -->
        </div>
    @else
        <div class="row">
            <h5>SMIS Dashboard.</h5>
        </div>
    @endif

    @if (auth()->user()->is_admin === true ||
            auth()->user()->hasRole('super-admin'))
        <form action="{{ route('home') }}" method="Get" id="comment-form">
            <div class="card border card-light">
                <div class="card-header">
                    <div class ="row" style="background:#64b5f6">
                        <div class="col-md-4">
                            <label class="label" for="filters">
                                Key Peformance Indicator:
                            </label>
                            <select class="form-control select2" name="kpi">
                                <option disabled selected value="">
                                    Select Key Peformance Indicator
                                </option>
                                @forelse(getAllKpi() as $kpi)
                                    <option value="{{ $kpi->id }}">
                                        {{ $kpi->keyPeformanceIndicatorTs[0]->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label" for="filters">Office:</label>
                            <select class="form-control select2" name="office">
                                <option disabled selected value="">
                                    Select Office
                                </option>
                                @forelse(getAllOffices() as $office)
                                    <option value="{{ $office->id }}">
                                        {{ $office->officeTranslations[0]->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="label" for="filters">
                                Reporting Period:
                            </label>
                            <select class="form-control select2" name="period">
                                <option disabled selected value="">Select Reporting Period</option>
                                @forelse(getAllReportingPeriod() as $ReportingPeriod)
                                    <option value="{{ $ReportingPeriod->id }}">
                                        {{ $ReportingPeriod->reportingPeriodTs[0]->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-info">Filter</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="bar-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border card-light">
                <div class="card-header">
                    <div class ="row" style="background:#64b5f6">
                        <div class="col-md-4">
                            <label class="label" for="filters">
                                Key Peformance Indicator:
                            </label>
                            <select class="form-control select2" name="kpi2">
                                <option disabled selected value="">
                                    Select Key Peformance Indicator
                                </option>
                                @forelse(getAllKpi() as $kpi)
                                    <option value="{{ $kpi->id }}">
                                        {{ $kpi->keyPeformanceIndicatorTs[0]->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label" for="filters">
                                Office:
                            </label>
                            <select class="form-control select2" name="office2">
                                <option disabled selected value="">
                                    Select Office
                                </option>
                                @forelse(getAllOffices() as $office)
                                    <option value="{{ $office->id }}">
                                        {{ $office->officeTranslations[0]->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="label" for="filters">
                                Reporting Period:
                            </label>
                            <select class="form-control select2" name="period2">
                                <option disabled selected value="">
                                Select Reporting Period
                                </option>
                                @forelse(getAllReportingPeriod() as $ReportingPeriod)
                                    <option value="{{ $ReportingPeriod->id }}">
                                        {{ $ReportingPeriod->reportingPeriodTs[0]->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        {{-- <div class="col-md-1">
                            <button type="submit" class="btn btn-info">Filter</button>
                        </div> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="bar-chart2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>


                <style>
                    .morris-hover {
                        position: absolute;
                        z-index: 1000;
                    }

                    .morris-hover.morris-default-style {
                        border-radius: 10px;
                        padding: 6px;
                        color: #666;
                        background: rgba(255, 255, 255, 0.8);
                        border: solid 2px rgba(230, 230, 230, 0.8);
                        font-family: sans-serif;
                        font-size: 12px;
                        text-align: center;
                    }

                    .morris-hover.morris-default-style .morris-hover-row-label {
                        font-weight: bold;
                        margin: 0.25em 0;
                    }

                    .morris-hover.morris-default-style .morris-hover-point {
                        white-space: nowrap;
                        margin: 0.1em 0;
                    }

                    svg {
                        width: 100%;
                    }
                </style>


                <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
                <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
                <script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
                <meta charset=utf-8 />
                <script>
                    $(document).ready(function() {
                        barChart();
                        barChart2();
                        lineChart();
                        areaChart();
                        donutChart();

                        $(window).resize(function() {
                            window.barChart.redraw();
                            window.barChart2.redraw();
                            window.lineChart.redraw();
                            window.areaChart.redraw();
                            window.donutChart.redraw();
                        });
                    });

                    function barChart() {
                        window.barChart = Morris.Bar({
                            element: 'bar-chart',
                            data: [
                                <?php

                                foreach ($kpis as $kpii) {
                                    echo '{';
                                    $ll = substr($kpii->keyPeformanceIndicatorTs[0]->name, 0, 20) . '...';
                                    echo 'KPI:';
                                    echo "'" . $ll . "',";
                                    $k_plan_report = getKpiPlan($kpii, $offices, $period);
                                    echo 'Plan:' . $k_plan_report[0] . ',';
                                    $k_plan_report = getKpiPlan($kpii, $offices, $period);
                                    echo 'Report:' . $k_plan_report[1];
                                    echo '},';
                                }
                                ?>
                                /* { y: '2006', a: 100, b: 90 },*/
                            ],
                            xkey: 'KPI',
                            ykeys: ['Plan', 'Report'],
                            labels: ['Plan ', 'Report'],
                            lineColors: ['#188e5', '#ff3321'],
                            lineWidth: '1px',
                            resize: true,
                            redraw: true
                        });
                    }

                    function barChart2() {
                        window.barChart2 = Morris.Bar({
                            element: 'bar-chart2',
                            data: [
                                <?php

                                foreach ($kpis2 as $kpii) {
                                    echo '{';
                                    $ll = substr($kpii->keyPeformanceIndicatorTs[0]->name, 0, 20) . '...';
                                    echo 'KPI:';
                                    echo "'" . $ll . "',";
                                    $k_plan_report = getKpiPlan($kpii, $offices2, $period2);
                                    echo 'Plan:' . $k_plan_report[0] . ',';
                                    $k_plan_report = getKpiPlan($kpii, $offices2, $period2);
                                    echo 'Report:' . $k_plan_report[1];
                                    echo '},';
                                }
                                ?>
                                /* { y: '2006', a: 100, b: 90 },*/
                            ],
                            xkey: 'KPI',
                            ykeys: ['Plan', 'Report'],
                            labels: ['Plan ', 'Report'],
                            lineColors: ['#1e88e5', '#ff3321'],
                            lineWidth: '1px',
                            resize: true,
                            redraw: true
                        });
                    }
                </script>
    @endif
@endsection
