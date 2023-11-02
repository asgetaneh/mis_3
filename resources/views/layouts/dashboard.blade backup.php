@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
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
        @if (auth()->user()->is_admin === true || auth()->user()->hasRole('super-admin'))
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

    @if (auth()->user()->is_admin === true || auth()->user()->hasRole('super-admin'))
        {{-- <section class="content"> --}}
        <div class="">
            <div class="row">
                 <div class="col-md-12">
                     <div class="card border card-light">
                        <form action="{{ route('home') }}" method="Get" id="comment-form">
                         <div class="card-header">
                             <div class ="row">
                                <div class="col-md-4">
                                    <label class="label" for="filters">Key Peformance Indicator:</label>
                                <select class="form-control select2" name="kpi">
                                    <option disabled selected value="">Select Key Peformance Indicator</option>
                                    @forelse(getAllKpi() as $kpi)
                                            <option value="{{ $kpi->id }}">{{ $kpi->keyPeformanceIndicatorTs[0]->name }}
                                            </option>
                                    @empty
                                    @endforelse
                                </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="label" for="filters">Office:</label>
                                <select class="form-control select2" name="office">
                                    <option disabled selected value="">Select Office</option>
                                    @forelse(getAllOffices() as $office)
                                            <option value="{{ $office->id }}">{{ $office->officeTranslations[0]->name }}
                                            </option>
                                    @empty
                                    @endforelse
                                </select>
                                </div>
                                 <div class="col-md-3">
                                    <label class="label" for="filters">Reporting Period:</label>
                                <select class="form-control select2" name="period">
                                    <option disabled selected value="">Select Reporting Period</option>
                                    @forelse(getAllReportingPeriod() as $ReportingPeriod)
                                            <option value="{{ $ReportingPeriod->id }}">{{ $ReportingPeriod->reportingPeriodTs[0]->name }}
                                            </option>
                                    @empty
                                    @endforelse
                                </select>
                                </div>
                                <div class="col-md-2">
                                <button type="submit" class="btn btn-info">Filter</button>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-md-12">
<style>

.morris-hover {
  position:absolute;
  z-index:1000;
}

.morris-hover.morris-default-style {     border-radius:10px;
  padding:6px;
  color:#666;
  background:rgba(255, 255, 255, 0.8);
  border:solid 2px rgba(230, 230, 230, 0.8);
  font-family:sans-serif;
  font-size:12px;
  text-align:center;
}

.morris-hover.morris-default-style .morris-hover-row-label {
  font-weight:bold;
  margin:0.25em 0;
}

.morris-hover.morris-default-style .morris-hover-point {
  white-space:nowrap;
  margin:0.1em 0;
}

svg { width: 100%; }
</style>


<link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<meta charset=utf-8 />
<title>Morris.js Responsive Charts Example + Bootstrap</title>
</head>
<body>
  <section class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div id="bar-chart"></div>
      </div>
      
      <div class="col-md-12">
        <div id="line-chart"></div>
      </div>
      
      <div class="col-md-6">
        <div id="area-chart"></div>
      </div>
      
      <div class="col-md-6">
        <div id="donut-chart"></div>
      </div>
      
      <div class="col-md-8">
        <div id="pie-chart"></div>
      </div>
    </div>
  </section>
</body>
</html>
<script>


$(document).ready(function() {
  barChart();
  lineChart();
  areaChart();
  donutChart();

  $(window).resize(function() {
    window.barChart.redraw();
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
             
            foreach($kpis as $kpii){ 
                echo("{");
                    $ll=substr($kpii->keyPeformanceIndicatorTs[0]->name,0,20) . '...';
                    echo ('KPI:');
                    echo ("'".$ll."',");
                    $k_plan_report = getKpiPlan($kpii,$offices,$period);
                    echo ('Plan:'.$k_plan_report[0] .',');
                    $k_plan_report = getKpiPlan($kpii,$offices,$period);
                    echo ("Report:". $k_plan_report[1]);
                echo('},');
            }
            ?>
       /* { y: '2006', a: 100, b: 90 },*/
    ],
    xkey: 'KPI',
    ykeys: ['Plan', 'Report'],
    labels: ['Plan ', 'Report'],
    lineColors: ['#1e88e5','#ff3321'],
    lineWidth: '1px',
    resize: true,
    redraw: true
  });
}

function lineChart() {
  window.lineChart = Morris.Line({
    element: 'line-chart',
    data: [
      { y: '2006', a: 100, b: 90 },
      { y: '2007', a: 75,  b: 65 },
      { y: '2008', a: 50,  b: 40 },
      { y: '2009', a: 75,  b: 65 },
      { y: '2010', a: 50,  b: 40 },
      { y: '2011', a: 75,  b: 65 },
      { y: '2012', a: 100, b: 90 }
    ],
    xkey: 'y',
    ykeys: ['a', 'b'],
    labels: ['Series A', 'Series B'],
    lineColors: ['#1e88e5','#ff3321'],
    lineWidth: '3px',
    resize: true,
    redraw: true
  });
}

function areaChart() {
  window.areaChart = Morris.Area({
    element: 'area-chart',
    data: [
      { y: '2006', a: 100, b: 90 },
      { y: '2007', a: 75,  b: 65 },
      { y: '2008', a: 50,  b: 40 },
      { y: '2009', a: 75,  b: 65 },
      { y: '2010', a: 50,  b: 40 },
      { y: '2011', a: 75,  b: 65 },
      { y: '2012', a: 100, b: 90 }
    ],
    xkey: 'y',
    ykeys: ['a', 'b'],
    labels: ['Series A', 'Series B'],
    lineColors: ['#1e88e5','#ff3321'],
    lineWidth: '3px',
    resize: true,
    redraw: true
  });
}

function donutChart() {
  window.donutChart = Morris.Donut({
  element: 'donut-chart',
  data: [
    {label: "Download Sales", value: 50},
    {label: "In-Store Sales", value: 25},
    {label: "Mail-Order Sales", value: 5},
    {label: "Uploaded Sales", value: 10},
    {label: "Video Sales", value: 10}
  ],
  resize: true,
  redraw: true
});
}

function pieChart() {
  var paper = Raphael("pie-chart");
paper.piechart(
  100, // pie center x coordinate
  100, // pie center y coordinate
  90,  // pie radius
   [18.373, 18.686, 2.867, 23.991, 9.592, 0.213], // values
   {
   legend: ["Windows/Windows Live", "Server/Tools", "Online Services", "Business", "Entertainment/Devices", "Unallocated/Other"]
   }
 );
}
</script>
                                </div>
                            </div>   
                        
                        <!-- you need to include the shieldui css and js assets in order for the charts to work -->
                        {{-- <link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light/all.min.css" /> --}}
                        <script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>

                        <script type="text/javascript">
                            jQuery(function ($) {
                                var data1 = [
                                    <?php 
                                    foreach($kpis as $kpii){ 
                                        $k_plan_report = getKpiPlan($kpii,$offices,$period);
                                        echo ($k_plan_report[1]);
                                        echo(',');
                                    }?>
                                ];
                                var data2 = [
                                    <?php 
                                    foreach($kpis as $kpii){ 
                                        $k_plan_report = getKpiPlan($kpii,$offices,$period);
                                        echo ($k_plan_report[0]);
                                        echo(',');
                                    }?>
                                ];
                                 $("#chart22").shieldChart({ 
                                    
                                    exportOptions: {
                                        image: false,
                                        print: false
                                    },
                                    axisX: {
                                        title: {
                                            text: "KPI",
                                            labels: [
                                        <?php 
                                        foreach($kpis as $kpii){ 
                                                $ll=substr($kpii->keyPeformanceIndicatorTs[0]->name,0,20) . '...';
                                            echo ("'".$ll."'".',');
                                        }?>
                        
                                    ], 
                                        }
                                    },
                                    axisY: {
                                        title: {
                                            text: "Total"
                                        }
                                    },
                                    dataSeries: [{
                                        seriesType: "bar",
                                        data: data2
                                    }, {
                                        seriesType: "bar",
                                        data: data1
                                    }]
                                });
                            });
                        </script>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        </form>
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


                    <div class="card border card-light">
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

                    <div class="card border card-light">
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
                <canvas id="donutChart"></canvas> 
                <canvas id="areaChart"></canvas>
 
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
                labels: [
                    <?php 
                    foreach($kpis as $kpii){ 
                            $ll=substr($kpii->keyPeformanceIndicatorTs[0]->name,0,20) . '...';
                         echo ("'".$ll."'".',');
                     }?>
    
                 ],
                datasets: [{
                        label: 'Report',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: [<?php 
                                foreach($kpis as $kpii){ 
                                     $k_plan_report = getKpiPlan($kpii,$offices,$period);
                                    echo ($k_plan_report[1]);
                                     echo(',');
                                }?>]
                    },
                    {
                        label: 'Plan',
                        ttle: '7',
                        backgroundColor: 'rgba(150, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: [
                                <?php 
                                foreach($kpis as $kpii){
                                   $k_plan_report = getKpiPlan($kpii,$offices,$period);
                                    echo($k_plan_report[0].',');
                                 }?>
                        ]
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
           /* new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            })*/
 
            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
         /*   var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
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
            })*/

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
