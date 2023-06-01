<?php
/**
* AdminLTE For SLiMS
*
* Customize by Drajat Hasan 2019 (drajathasan20@gmail.com)
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
/* Include menu generator */
include 'menuGenerator.php';

/* Set additional function */
function setPict()
{
  global $sysconf;
  $img = SWB.'images/persons/'.$_SESSION['upict']; 
  if (isset($_SESSION['upict'])) {
     if ($_SESSION['upict'] == 'person.png') {
        $img = AWB.'admin_template/'.$sysconf['admin_template']['theme'].'/dist/img/mainavatar.png';
     }
  }
  return $img;
}
?>
<!-- 
    _       _           _       _   _____ _____     ____  _     _ __  __ ____  
   / \   __| |_ __ ___ (_)_ __ | | |_   _| ____|   / ___|| |   (_)  \/  / ___| 
  / _ \ / _` | '_ ` _ \| | '_ \| |   | | |  _| ____\___ \| |   | | |\/| \___ \ 
 / ___ \ (_| | | | | | | | | | | |___| | | |__|_____|__) | |___| | |  | |___) |
/_/   \_\__,_|_| |_| |_|_|_| |_|_____|_| |_____|   |____/|_____|_|_|  |_|____/ 
                                                                               
                                                        Customize by Drajat Hasan
                                                        Copyright 2014-2016 Almsaeed Studio All rights reserved.
                                                        https://adminlte.io 
 -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $page_title; ?></title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, post-check=0, pre-check=0" />
  <meta http-equiv="Expires" content="Sat, 26 Jul 1997 05:00:00 GMT" />
  
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/font-awesome/css/font-awesome.min.css">
  
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/Ionicons/css/ionicons.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/dist/css/AdminLTE.min.css">
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/dist/css/skins/_all-skins.min.css">
  
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/morris.js/morris.css">
  
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/jvectormap/jquery-jvectormap.css">
  
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  
  <!-- ChartJS -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/chart.js/Chart.bundle.min.js"></script>

  <!-- SLiMS -->
  <link rel="icon" href="<?php echo SWB; ?>webicon.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="<?php echo SWB; ?>webicon.ico" type="image/x-icon" />
  <link href="<?php echo SWB; ?>template/core.style.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo JWB; ?>colorbox/colorbox.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo JWB; ?>chosen/chosen.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo JWB; ?>jquery.imgareaselect/css/imgareaselect-default.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/main.css">
  <!-- <link href="<?php echo $sysconf['admin_template']['css']; ?>" rel="stylesheet" type="text/css" /> -->

  <script type="text/javascript" src="<?php echo JWB; ?>jquery.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>updater.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>gui.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>form.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>calendar.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>ckeditor/ckeditor.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>keyboard.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>chosen/chosen.jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>chosen/ajax-chosen.min.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>tooltipsy.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>colorbox/jquery.colorbox-min.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>jquery.imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>webcam.js"></script>
  <script type="text/javascript" src="<?php echo JWB; ?>scanner.js"></script>
  <!-- End SLiMS -->
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <!-- Logo -->
      <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b>LTE-SLiMS</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
            <li class="dropdown messages-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-envelope-o"></i>
                <span class="label label-success">4</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 4 messages</li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu">
                    <li><!-- start message -->
                      <a href="#">
                        <div class="pull-left">
                          <img src="<?php echo setPict();?>" class="img-circle" alt="User Image">
                        </div>
                        <h4>
                          Fitur Chat?
                          <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <p>Fitur ini hanya tersedia dalam versi lain :). Hub <a href="mailto:drajathasan20@gmail.com">drajathasan20@gmail.com</a></p>
                      </a>
                    </li>
                    <!-- end message -->
                  </ul>
                </li>
                <li class="footer"><a href="#">See All Messages</a></li>
              </ul>
            </li>
            <!-- Notifications: style can be found in dropdown.less -->
            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning">10</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 10 notifications</li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu">
                    <li>
                      <a href="#">
                        <i class="fa fa-users text-aqua"></i> Fitur ini belum tersedia dalam versi Free.
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="footer"><a href="#">View all</a></li>
              </ul>
            </li>
            <!-- Tasks: style can be found in dropdown.less -->
            <li class="dropdown tasks-menu" style="display: none;">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-flag-o"></i>
                <span class="label label-danger">9</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 9 tasks</li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu">
                    <li><!-- Task item -->
                      <a href="#">
                        <h3>
                          Design some buttons
                          <small class="pull-right">20%</small>
                        </h3>
                        <div class="progress xs">
                          <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                               aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                            <span class="sr-only">20% Complete</span>
                          </div>
                        </div>
                      </a>
                    </li>
                    <!-- end task item -->
                    <li><!-- Task item -->
                      <a href="#">
                        <h3>
                          Create a nice theme
                          <small class="pull-right">40%</small>
                        </h3>
                        <div class="progress xs">
                          <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar"
                               aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                            <span class="sr-only">40% Complete</span>
                          </div>
                        </div>
                      </a>
                    </li>
                    <!-- end task item -->
                    <li><!-- Task item -->
                      <a href="#">
                        <h3>
                          Some task I need to do
                          <small class="pull-right">60%</small>
                        </h3>
                        <div class="progress xs">
                          <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar"
                               aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        </div>
                      </a>
                    </li>
                    <!-- end task item -->
                    <li><!-- Task item -->
                      <a href="#">
                        <h3>
                          Make beautiful transitions
                          <small class="pull-right">80%</small>
                        </h3>
                        <div class="progress xs">
                          <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar"
                               aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                            <span class="sr-only">80% Complete</span>
                          </div>
                        </div>
                      </a>
                    </li>
                    <!-- end task item -->
                  </ul>
                </li>
                <li class="footer">
                  <a href="#">View all tasks</a>
                </li>
              </ul>
            </li>
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?php echo setPict();?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $_SESSION['realname'];?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="<?php echo setPict();?>" class="img-circle" alt="User Image">

                  <p>
                    <?php echo $_SESSION['realname'];?>
                    <!-- <small>Member since Nov. 2012</small> -->
                  </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                  <!-- <div class="row">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </div> -->
                  <!-- /.row -->
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="#" class="profile btn btn-default btn-flat">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <li>
              <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="<?php echo setPict();?>" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo $_SESSION['realname'];?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input type="text" name="q" class="form-control" onkeyup="searchString(this)" placeholder="Search...">
            <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                  </button>
                </span>
          </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <div id="sidepan">
          <ul class="sidebar-menu" data-widget="tree">
            <?php
            echo menuGenerator();
            ?>
          </ul>
        </div>
      </section>
      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <div class="loader"></div>
      <!-- Main content -->
      <section class="content" id="mainContent">
        <?php 
           if (isset($_GET['mod']) AND ($_GET['mod'] == 'system')) {
              echo '<script>$(\'#mainContent\').simbioAJAX(\''.MWB.'system/index.php\');</script>';
           } else {
             include_once SB.'admin/admin_template/'.$sysconf['admin_template']['theme'].'/dashboard.php';
             echo $mainContent;
           }
        ?>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b>Version</b> <?php echo SENAYAN_VERSION;?>
      </div>
      <strong><?php echo $sysconf['library_name'].' - '.$sysconf['library_subname'];?>.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Create the tabs -->
      <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
      </ul>
      <!-- Tab panes -->
      <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane" id="control-sidebar-home-tab">
          <h3 class="control-sidebar-heading">Recent Activity</h3>
          <ul class="control-sidebar-menu">
            <li>
              <a href="javascript:void(0)">
                <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                <div class="menu-info">
                  <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                  <p>Will be 23 on April 24th</p>
                </div>
              </a>
            </li>
            <li>
              <a href="javascript:void(0)">
                <i class="menu-icon fa fa-user bg-yellow"></i>

                <div class="menu-info">
                  <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                  <p>New phone +1(800)555-1234</p>
                </div>
              </a>
            </li>
            <li>
              <a href="javascript:void(0)">
                <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                <div class="menu-info">
                  <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                  <p>nora@example.com</p>
                </div>
              </a>
            </li>
            <li>
              <a href="javascript:void(0)">
                <i class="menu-icon fa fa-file-code-o bg-green"></i>

                <div class="menu-info">
                  <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                  <p>Execution time 5 seconds</p>
                </div>
              </a>
            </li>
          </ul>
          <!-- /.control-sidebar-menu -->

          <h3 class="control-sidebar-heading">Tasks Progress</h3>
          <ul class="control-sidebar-menu">
            <li>
              <a href="javascript:void(0)">
                <h4 class="control-sidebar-subheading">
                  Custom Template Design
                  <span class="label label-danger pull-right">70%</span>
                </h4>

                <div class="progress progress-xxs">
                  <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                </div>
              </a>
            </li>
            <li>
              <a href="javascript:void(0)">
                <h4 class="control-sidebar-subheading">
                  Update Resume
                  <span class="label label-success pull-right">95%</span>
                </h4>

                <div class="progress progress-xxs">
                  <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                </div>
              </a>
            </li>
            <li>
              <a href="javascript:void(0)">
                <h4 class="control-sidebar-subheading">
                  Laravel Integration
                  <span class="label label-warning pull-right">50%</span>
                </h4>

                <div class="progress progress-xxs">
                  <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                </div>
              </a>
            </li>
            <li>
              <a href="javascript:void(0)">
                <h4 class="control-sidebar-subheading">
                  Back End Framework
                  <span class="label label-primary pull-right">68%</span>
                </h4>

                <div class="progress progress-xxs">
                  <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                </div>
              </a>
            </li>
          </ul>
          <!-- /.control-sidebar-menu -->

        </div>
        <!-- /.tab-pane -->
        <!-- Stats tab content -->
        <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
        <!-- /.tab-pane -->
        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
          <form method="post">
            <h3 class="control-sidebar-heading">General Settings</h3>

            <div class="form-group">
              <label class="control-sidebar-subheading">
                Report panel usage
                <input type="checkbox" class="pull-right" checked>
              </label>

              <p>
                Some information about this general settings option
              </p>
            </div>
            <!-- /.form-group -->

            <div class="form-group">
              <label class="control-sidebar-subheading">
                Allow mail redirect
                <input type="checkbox" class="pull-right" checked>
              </label>

              <p>
                Other sets of options are available
              </p>
            </div>
            <!-- /.form-group -->

            <div class="form-group">
              <label class="control-sidebar-subheading">
                Expose author name in posts
                <input type="checkbox" class="pull-right" checked>
              </label>

              <p>
                Allow the user to show his name in blog posts
              </p>
            </div>
            <!-- /.form-group -->

            <h3 class="control-sidebar-heading">Chat Settings</h3>

            <div class="form-group">
              <label class="control-sidebar-subheading">
                Show me as online
                <input type="checkbox" class="pull-right" checked>
              </label>
            </div>
            <!-- /.form-group -->

            <div class="form-group">
              <label class="control-sidebar-subheading">
                Turn off notifications
                <input type="checkbox" class="pull-right">
              </label>
            </div>
            <!-- /.form-group -->

            <div class="form-group">
              <label class="control-sidebar-subheading">
                Delete chat history
                <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
              </label>
            </div>
            <!-- /.form-group -->
          </form>
        </div>
        <!-- /.tab-pane -->
      </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
  <iframe name="blindSubmit" style="display: none;"></iframe>
  <script type="text/javascript">
    /* Function to search string */
    function searchString(e) {
      /* set for in case-insensitive */
      jQuery.expr[':'].contains = function(a, i, m) {
        return jQuery(a).text().toUpperCase()
            .indexOf(m[3].toUpperCase()) >= 0;
      };

     /* Set Varibel */
     var toHide = $('.lte-submenu,.treeview');
     var string = $('<li class="header note" style="color: white;">No result found!</li>');
     var note   = $('.note');
     var lts    = $('.lte-submenu');
     /* Prevent Empty Value */
     if (e.value != "") {
       /* Ser Var if not empty */
       var lts    = $(".lte-submenu:contains('"+e.value+"')");
       var key    = lts.attr('key');
       var tv     = $('.tv'+key);
       var header = $('.header');
       /* Hiden object */
       if (lts.length > 0) {
         toHide.hide();
         /* Set active class */
         tv.addClass('active');
         /* Show in*/
         tv.show();
         lts.show();
         /* Set Hightlight */
         lts.attr('style', 'background: yellow;color:black');
       } else if (lts.length == 0) {
         toHide.hide();
         header.remove();
         string.insertBefore('.logout');

       }
     } else {
        // Set to default.
        toHide.show();
        toHide.removeClass('active');
        note.remove();
        lts.removeAttr('style');
     }
    }

    /* Dashboard */
    $('.dashboard').click(function(e){
        e.preventDefault();
        window.location = $(this).attr('href');
    });

    /* Opac */
    $('.opac').bind('click', function(evt) {
      evt.preventDefault();
      top.jQuery.colorbox({iframe:true,
        href: $(this).attr('href'),
          width: function() { return parseInt($(window).width())-50; },
          height: function() { return parseInt($(window).height())-50; },
          title: function() { return 'Online Public Access Catalog'; } }
        );
    });

    /* Profile */
    $('.profile').bind('click', function(evt) {
      evt.preventDefault();
      $('#mainContent').simbioAJAX("<?php echo MWB;?>system/app_user.php?changecurrent=true&action=detail");
      $('.user-menu').removeClass('open');
      return false;
    });

    /* LTE */
    $('.lte-submenu').click(function(e){
      $("html, body").animate({ scrollTop: 0 }, "slow");
    });

    /* Main Dashboard */
    //$('#mainContent').simbioAJAX("<?php echo AWB;?>admin_template/adminlte/dashboard.php");
  </script>
  <!-- ./wrapper -->

  <!-- jQuery 3 . We don't need it. Just use SLiMS jQuery-->
  <!-- <script src="bower_components/jquery/dist/jquery.min.js"></script> -->
  <!-- jQuery UI 1.11.4 -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    // $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- Morris.js charts -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/raphael/raphael.min.js"></script>
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/morris.js/morris.min.js"></script>
  <!-- Sparkline -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
  <!-- jvectormap -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/moment/min/moment.min.js"></script>
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- datepicker -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <!-- Slimscroll -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!-- <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/dist/js/pages/dashboard.js"></script> -->
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/dist/js/demo.js"></script> -->
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!-- <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/dist/js/pages/dashboard2.js"></script> -->
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="<?php echo AWB; ?>admin_template/<?php echo $sysconf['admin_template']['theme']?>/dist/js/demo.js"></script> -->
</body>
</html>
