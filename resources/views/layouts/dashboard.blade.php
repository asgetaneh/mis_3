@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <!-- Small boxes (Stat box) -->
    {{-- <div class="row">
                <!-- <h1>{{ __('1.welcome') }}</h1> -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-primary">
            <div class="inner">
              <h3>150</h3>

              <p>KPI</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>16<sup style="font-size: 20px"></sup></h3>

              <p>Objectives</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div> --}}

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
        @if (auth()->user()->is_admin === true)
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

            <div class="col-12 col-sm-6 col-md-3">

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
            </div>
        @endif
        <!-- /.info-box -->
    </div>

    @if (auth()->user()->is_admin === true)
        {{-- <section class="content"> --}}
        <div class="">
            <div class="row">

                <div class="col-md-6">

                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Bar Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="barChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 442px;"
                                    width="442" height="250" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>

                    </div>


                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Stacked Bar Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="stackedBarChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 442px;"
                                    width="442" height="250" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>

                    </div>

                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Line Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="lineChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 442px;"
                                    width="442" height="250" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Donut Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="donutChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 442px;"
                                width="442" height="250" class="chartjs-render-monitor"></canvas>
                        </div>

                    </div>


                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Pie Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="pieChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 442px;"
                                width="442" height="250" class="chartjs-render-monitor"></canvas>
                        </div>

                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Area Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="areaChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 442px;"
                                    width="442" height="250" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
        {{-- </section> --}}
    @endif

    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */

            //--------------
            //- AREA CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

            var areaChartData = {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                        label: 'Digital Goods',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: [28, 48, 40, 19, 86, 27, 90]
                    },
                    {
                        label: 'Electronics',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: [65, 59, 80, 81, 56, 55, 40]
                    },
                ]
            }

            var areaChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }]
                }
            }

            // This will get the first returned node in the jQuery collection.
            new Chart(areaChartCanvas, {
                type: 'line',
                data: areaChartData,
                options: areaChartOptions
            })

            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
            var lineChartOptions = $.extend(true, {}, areaChartOptions)
            var lineChartData = $.extend(true, {}, areaChartData)
            lineChartData.datasets[0].fill = false;
            lineChartData.datasets[1].fill = false;
            lineChartOptions.datasetFill = false

            var lineChart = new Chart(lineChartCanvas, {
                type: 'line',
                data: lineChartData,
                options: lineChartOptions
            })

            //-------------
            //- DONUT CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
            var donutData = {
                labels: [
                    'Chrome',
                    'IE',
                    'FireFox',
                    'Safari',
                    'Opera',
                    'Navigator',
                ],
                datasets: [{
                    data: [700, 500, 400, 600, 300, 100],
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                }]
            }
            var donutOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            })

            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData = donutData;
            var pieOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
            })

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })

            //---------------------
            //- STACKED BAR CHART -
            //---------------------
            var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
            var stackedBarChartData = $.extend(true, {}, barChartData)

            var stackedBarChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }

            new Chart(stackedBarChartCanvas, {
                type: 'bar',
                data: stackedBarChartData,
                options: stackedBarChartOptions
            })
        })
    </script>
@endsection
