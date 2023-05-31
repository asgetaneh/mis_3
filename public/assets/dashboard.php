<?php
/**
* Dashboard AdminLTE For SLiMS
*
* Customize by Drajat Hasan 2019 (drajathasan20@gmail.com)
* Mixing code between AdminLTE layout with SLiMS Dashboard Script
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*
*/

//be sure that this file not accessed directly
if (!defined('INDEX_AUTH')) {
    die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
    die("can not access this file directly");
}

ob_start();

// generate warning messages
$warnings = array();
// check GD extension
if (!extension_loaded('gd')) {
    $warnings[] = __('<strong>PHP GD</strong> extension is not installed. Please install it or application won\'t be able to create image thumbnail and barcode.');
} else {
    // check GD Freetype
    if (!function_exists('imagettftext')) {
        $warnings[] = __('<strong>Freetype</strong> support is not enabled in PHP GD extension. Rebuild PHP GD extension with Freetype support or application won\'t be able to create barcode.');
    }
}
// check for overdue
$overdue_q = $dbs->query('SELECT COUNT(loan_id) FROM loan AS l WHERE (l.is_lent=1 AND l.is_return=0 AND TO_DAYS(due_date) < TO_DAYS(\''.date('Y-m-d').'\')) GROUP BY member_id');
$num_overdue = $overdue_q->num_rows;
if ($num_overdue > 0) {
    $warnings[] = str_replace('{num_overdue}', $num_overdue, __('There are currently <strong>{num_overdue}</strong> library members having overdue. Please check at <b>Circulation</b> module at <b>Overdues</b> section for more detail')); //mfc
    $overdue_q->free_result();
}
// check if images dir is writable or not
if (!is_writable(IMGBS) OR !is_writable(IMGBS.'barcodes') OR !is_writable(IMGBS.'persons') OR !is_writable(IMGBS.'docs')) {
    $warnings[] = __('<strong>Images</strong> directory and directories under it is not writable. Make sure it is writable by changing its permission or you won\'t be able to upload any images and create barcodes');
}
// check if file repository dir is writable or not
if (!is_writable(REPOBS)) {
    $warnings[] = __('<strong>Repository</strong> directory is not writable. Make sure it is writable (and all directories under it) by changing its permission or you won\'t be able to upload any bibliographic attachments.');
}
// check if file upload dir is writable or not
if (!is_writable(UPLOAD)) {
    $warnings[] = __('<strong>File upload</strong> directory is not writable. Make sure it is writable (and all directories under it) by changing its permission or you won\'t be able to upload any file, create report files and create database backups.');
}
// check mysqldump
if (!file_exists($sysconf['mysqldump'])) {
    $warnings[] = __('The PATH for <strong>mysqldump</strong> program is not right! Please check configuration file or you won\'t be able to do any database backups.');
}
// check installer directory
if (is_dir('../install/')) {
    $warnings[] = __('Installer folder is still exist inside your server. Please remove it or rename to another name for security reason.');
}

// check need to be repaired mysql database
$query_of_tables    = $dbs->query('SHOW TABLES');
$num_of_tables      = $query_of_tables->num_rows;
$prevtable          = '';
$repair             = '';
$is_repaired        = false;

if ($_SESSION['uid'] === '1') {
  $warnings[] = __('<strong><i>You are logged in as Super User. With great power comes great responsibility.</i></strong>');
  if (isset ($_POST['do_repair'])) {
    if ($_POST['do_repair'] == 1) {
      while ($row = $query_of_tables->fetch_row()) {
        $sql_of_repair = 'REPAIR TABLE '.$row[0];
        $query_of_repair = $dbs->query ($sql_of_repair);
      }
    }
  }

  while ($row = $query_of_tables->fetch_row()) {
    $query_of_check = $dbs->query('CHECK TABLE '.$row[0]);
    while ($rowcheck = $query_of_check->fetch_assoc()) {
      if (!(($rowcheck['Msg_type'] == "status") && ($rowcheck['Msg_text'] == "OK"))) {
        if ($row[0] != $prevtable) {
          $repair .= '<li>Table '.$row[0].' might need to be repaired.</li>';
        }
        $prevtable = $row[0];
        $is_repaired = true;
      }
    }
  }
  if (($is_repaired) && !isset($_POST['do_repair'])) {
    echo '<div class="message">';
    echo '<ul>';
    echo $repair;
    echo '</ul>';
    echo '</div>';
    echo ' <form method="POST" style="margin:0 10px;">
        <input type="hidden" name="do_repair" value="1">
        <input type="submit" value="'.__('Click Here To Repair The Tables').'" class="button btn btn-block btn-default">
        </form>';
  }
}

// generate dashboard content
$get_date       = '';
$get_loan       = '';
$get_return     = '';
$get_extends    = '';
$start_date     = date('Y-m-d'); // set date from TODAY

// get date transaction
$sql_date = 
        "SELECT 
            DATE_FORMAT(loan_date,'%d/%m') AS loandate,
            loan_date
        FROM 
            loan
        WHERE 
            loan_date BETWEEN DATE_SUB('".$start_date."', INTERVAL 8 DAY) AND '".$start_date."' 
        GROUP BY 
            loan_date
        ORDER BY 
            loan_date";

// echo $sql_date; //for debug purpose only
$set_date       = $dbs->query($sql_date);
if($set_date->num_rows > 0 ) {
    while ($transc_date = $set_date->fetch_object()) {
        // set transaction date
        $get_date .= '"'.$transc_date->loandate.'",';

        // get latest loan
        $sql_loan = 
                "SELECT 
                    COUNT(loan_date) AS countloan
                FROM 
                    loan
                WHERE 
                    loan_date = '".$transc_date->loan_date."' 
                    AND is_lent = 1 
                    AND renewed = 0
                    AND is_return = 0
                GROUP BY 
                    loan_date";

        $set_loan       = $dbs->query($sql_loan);
        if($set_loan->num_rows > 0) {
            $transc_loan    = $set_loan->fetch_object();
            $get_loan      .= $transc_loan->countloan.',';            
        } else {
            $get_loan       .= '0,';
        }

        // get latest return
        $sql_return = 
                "SELECT 
                    COUNT(loan_date) AS countloan
                FROM 
                    loan
                WHERE 
                    loan_date = '".$transc_date->loan_date."' 
                    AND is_lent = 1 
                    AND renewed = 0
                    AND is_return = 1
                GROUP BY 
                    return_date";

        $set_return       = $dbs->query($sql_return);                     
        if($set_return->num_rows > 0) {
            $transc_return    = $set_return->fetch_object();
            $get_return      .= $transc_return->countloan.',';
        } else {
            $get_return       .= '0,';
        }

        // get latest extends
        $sql_extends = 
                "SELECT 
                    COUNT(loan_date) AS countloan
                FROM 
                    loan
                WHERE 
                    loan_date = '".$transc_date->loan_date."' 
                    AND is_lent     = 1 
                    AND renewed     = 1
                GROUP BY 
                    return_date";
        $set_extends       = $dbs->query($sql_extends);   
        if($set_extends->num_rows > 0) {              
            $transc_extends    = $set_extends->fetch_object();
            $get_extends      .= $transc_extends->countloan.',';
        } else {
            $get_extends      .= '0,';
        }
    }
}
// return transaction date
$get_date       = substr($get_date,0,-1);
$get_loan       = substr($get_loan,0,-1);
$get_return     = substr($get_return,0,-1);
$get_extends    = substr($get_extends,0,-1);

// get total summary
$sql_total_coll = ' SELECT 
                        COUNT(loan_id) AS total
                    FROM 
                        loan';
$total_coll = $dbs->query($sql_total_coll);
$total      = $total_coll->fetch_object();
$get_total  = $total->total;

// get loan summary
$sql_loan_coll = ' SELECT 
                        COUNT(loan_id) AS total
                    FROM 
                        loan
                    WHERE
                        is_lent = 1
                        AND is_return = 0';
$total_loan         = $dbs->query($sql_loan_coll);
$loan               = $total_loan->fetch_object();
$get_total_loan     = $loan->total;

// get return summary
$sql_return_coll = ' SELECT 
                        COUNT(loan_id) AS total
                    FROM 
                        loan
                    WHERE
                        is_lent = 1
                        AND is_return = 1';
$total_return         = $dbs->query($sql_return_coll);
$return               = $total_return->fetch_object();
$get_total_return     = $return->total;

// get extends summary
$sql_extends_coll = ' SELECT 
                        COUNT(loan_id) AS total
                    FROM 
                        loan
                    WHERE
                        is_lent = 1
                        AND renewed = 1
                        AND is_return = 0';
$total_extends         = $dbs->query($sql_extends_coll);
$renew                 = $total_extends->fetch_object();
$get_total_extends     = $renew->total;

// get overdue
$sql_overdue_coll = ' SELECT 
                        COUNT(fines_id) AS total
                    FROM 
                        fines';
$total_overdue         = $dbs->query($sql_overdue_coll);
$overdue               = $total_overdue->fetch_object();
$get_total_overdue     = $overdue->total;

// get titles
$sql_title_coll = ' SELECT 
                        COUNT(biblio_id) AS total
                    FROM 
                        biblio';
$total_title         = $dbs->query($sql_title_coll);
$title               = $total_title->fetch_object();
$get_total_title     = number_format($title->total,0,'.',',');

// get item
$sql_item_coll = ' SELECT 
                        COUNT(item_id) AS total
                    FROM 
                        item';
$total_item          = $dbs->query($sql_item_coll);
$item                = $total_item->fetch_object();
$get_total_item      = number_format($item->total,0,'.',',');
$get_total_available = $item->total - $get_total_loan;
$get_total_available = number_format($get_total_available,0,'.',',');

/* Label and color */
$label = array(__('Total'), __('Loan'), __('Return'), __('Extend'), __('Overdue'));
$color = array('#D81B60', '#4300ff', '#00a65a', '#ff851b', '#39CCCC');
?>
<!-- Main content -->
<section class="content">
  <div>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-ban"></i> Alert!</h4>
      <?php
      if ($warnings) {
        echo '<ul style="list-style-type: none; font-size: 12pt;margin-left:-13px;">';
        foreach ($warnings as $warning_msg) {
            echo '<li>'.$warning_msg.'</li>';
        }
        echo '</ul>';
      }
      ?>
    </div>
  </div>
  <!-- Info boxes -->
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="ion ion-ios-book"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><?php echo __('Total of Collections') ?></span>
          <span class="info-box-number"><?php echo $get_total_title?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="ion ion-android-document"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><?php echo __('Total of Items') ?></span>
          <span class="info-box-number"><?php echo $get_total_item?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="ion ion-log-out"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><?php echo __('Lent') ?></span>
          <span class="info-box-number"><?php echo $get_total_loan?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="ion ion-bookmark"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><?php echo __('Available') ?></span>
          <span class="info-box-number"><?php echo $get_total_available?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <div class="row">
    <div class="col-md-12">
      <div class="box" style="border-top: 2px solid #56b1ff;">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo __('Latest Transactions') ?></h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <div class="btn-group">
              <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
              </ul>
            </div>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-8">
              <p class="text-center">
                <!-- <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong> -->
              </p>

              <div class="chart">
                <!-- Sales Chart Canvas -->
                <canvas id="line-chartjs" style="height: 280px;"></canvas>
              </div>
              <div class="row">
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block border-right">
                        <h5 class="description-header" style="color: <?php echo $color[0];?>"><i class="fa fa-square"></i></h5>
                        <span class="description-text">Return</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block border-right">
                        <h5 class="description-header" style="color: <?php echo $color[3];?>"><i class="fa fa-square"></i></h5>
                        <span class="description-text">Extends</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block border-right">
                        <h5 class="description-header" style="color: <?php echo $color[4];?>"><i class="fa fa-square"></i></h5>
                        <span class="description-text">New</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                  </div>
              <!-- /.chart-responsive -->
            </div>
            <!-- /.col -->
            <div class="col-md-4">
              <p class="text-center">
                <!-- <strong>Goal Completion</strong> -->
              </p>
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo __('Summary') ?></h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <div class="row">
                      <div class="col-md-8">
                        <div class="chart-responsive">
                          <canvas id="doughnutChart" style="height: 180px;"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-4">
                        <ul class="chart-legend clearfix">
                          <?php 
                            for ($i=0; $i < 5; $i++) { 
                               echo '<li><i class="fa fa-circle" style="color: '.$color[$i].'"></i> '.$label[$i].'</li>'  ;
                            }
                          ?>
                        </ul>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer no-padding">
                    <ul class="nav nav-pills nav-stacked">
                      <?php
                            // Data
                            $array_data = array($get_total, $get_total_loan, $get_total_return, $get_total_extends, $get_total_overdue);
                            // Loop
                            for ($i=0; $i < 5; $i++) { 
                               echo '<li><a href="#">'.$label[$i].'<span class="pull-right" style="color: '.$color[$i].'"> '.$array_data[$i].'</span></a></li>';
                            }
                      ?>
                    </ul>
                  </div>
  </div>
</section>
<!-- <script src="<?php echo JWB?>chartjs/Chart.min.js"></script> -->
<script>
$(function(){  
    var lineChartData = {
      labels : [<?php echo $get_date?>],
      datasets : 
        [
            {
                backgroundColor : "<?php echo $color[4];?>",
                data : [<?php echo $get_loan?>]
            },{
                backgroundColor : "<?php echo $color[0];?>",
                data : [<?php echo $get_return?>]
            },{
                backgroundColor : "<?php echo $color[3];?>",
                data : [<?php echo $get_extends?>]
            }
        ]
    }

    var c = $('#line-chartjs');
    var container = $(c).parent();
    var ct = c.get(0).getContext("2d");
    $(window).resize( respondCanvas );
    function respondCanvas(){ 
        c.attr('width', $(container).width() ); //max width
        c.attr('height', $(container).height() ); //max height
        
        //Call a function to redraw other content (texts, images etc)
        var myPieChart = new Chart(ct,{
            type: 'bar',
            data: lineChartData,
            options: {
              barShowStroke: false,
              barDatasetSpacing : 4,
              animation: false,
              legend: {
                    display: false
              }
            }
        });
    }
    respondCanvas();

    var dCanvas = $("#doughnutChart").get(0).getContext("2d");
    var dData = {
    labels: [
        "<?php echo __('Total');?>",
        "<?php echo __('Loan');?>",
        "<?php echo __('Return');?>",
        "<?php echo __('Extend');?>",
        "<?php echo __('Overdue');?>",
    ],
    datasets: [
        {
            data: [<?php echo $get_total.', '.$get_total_loan.', '.$get_total_return.', '.$get_total_extends.', '.$get_total_overdue;?>],
            backgroundColor: [
                "<?php echo $color[0];?>",
                "<?php echo $color[1];?>",
                "<?php echo $color[2];?>",
                "<?php echo $color[3];?>",
                "<?php echo $color[4];?>"
            ]
        }]
    };

    var doughnutChart = new Chart(dCanvas, {
      type: 'doughnut',
      data: dData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        scaleShowVerticalLines: false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:false,
                    display: false
                }
            }]
        },
        legend: {
          display: false
        }
      }

    });

});    

</script>
<?php
$mainContent = ob_get_clean();
?>
<!-- /.content