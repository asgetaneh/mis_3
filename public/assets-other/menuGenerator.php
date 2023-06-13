<?php
/**
* Custom Menu Layout
*
* Copyright (C) 2019 Drajat Hasan (drajathasan20@gmail.com)
* some code taken from SLiMS 8 Menu Layout
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

include_once '../sysconfig.inc.php';

function menuGenerator()
{
	global $dbs;
	// Get query
	$mod      = $dbs->query("SELECT * FROM mst_module");
	$mod_list = array();
	$modules_dir = 'modules';
	$icon           = array(
    'home'           => 'fa fa-home',
    'bibliography'   => 'fa fa-clone',
    'circulation'    => 'fa fa-history',
    'membership'     => 'fa fa-user-circle',
    'master_file'    => 'fa fa-files-o',
    'stock_take'     => 'fa fa-archive',
    'system'         => 'fa fa-sliders',
    'reporting'      => 'fa fa-line-chart',
    'serial_control' => 'fa fa-feed',
    'logout'         => 'fa fa-close',
    'opac'           => 'fa fa-desktop'
    );
	// Loop
	while ($mod_d = $mod->fetch_assoc()) {
		$mod_list[] = array('name' => $mod_d['module_name'], 'path' => $mod_d['module_path'], 'desc' => $mod_d['module_desc']);
	}

	$menu  = '';
	$menu .= '<li><a class="opac" href="../index.php" target="blindSubmit"><i class="fa fa-desktop"></i> <span>Opac</span></a></li>';
	$menu .= '<li><a class="dashboard" href="index.php" target="blindSubmit"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>';
	foreach ($mod_list as $key => $_module) {
		$_formated_module_name = ucwords(str_replace('_', ' ', $_module['name']));
		$_mod_dir = $_module['path'];
		if (isset($_SESSION['priv'][$_module['path']]['r']) && $_SESSION['priv'][$_module['path']]['r'] && file_exists($modules_dir.DS.$_mod_dir)) {
			$icon_set  = (isset($icon[$_module['name']]))?$icon[$_module['name']]:'fa fa-bars';
			$menu .= '<li class="treeview tv'.$key.'">';
			$menu .= '<a href="'.MWB.$_module['path'].'/index.php" target="blindSubmit">';
			$menu .= '<i class="'.$icon_set.'"></i> <span>'.__($_formated_module_name).'</span>';
			$menu .= '<span class="pull-right-container">';
			$menu .= '<i class="fa fa-angle-left pull-right"></i>';
			$menu .= '</span>';
			$menu .= '</a>';
			$menu .= '<ul class="treeview-menu">';
			/*$menu .= '<li class="active">';
			$menu .= '<a href="<?php echo MWB;?>membership/index.php">';
			$menu .= '<i class="fa fa-circle-o"></i> Dashboard v1';
			$menu .= '</a>';
			$menu .= '</li>';*/
			$menu .= submenu($_module['path'], $key);
			$menu .= '</ul>';
			$menu .= '</li>';
		}
	}
	$menu .= '<li class="logout"><a href="logout.php"><i class="'.$icon['logout'].'"></i> <span>Logout</span></a></li>';
	return $menu;
}

function submenu($module_path, $key)
{
	global $dbs;
	// Module dir
	$module_dir   = 'modules';
	$submenu_file = $module_dir.DS.$module_path.DS.'submenu.php';
	// Checking
	if (file_exists($submenu_file)) {
		include $submenu_file;
	} else {
		include 'default/submenu.php';
	}

	// iterate menu array
	$_submenu = '';
    foreach ($menu as $i=>$_list) {
      if ($_list[0] == 'Header') {
        $_submenu .= '<li class="header" style="color: white;">'.$menu[$i][1].'</li>'."\n";
      } else {
      	$_submenu .= '<li><a class="lte-submenu" key="'.$key.'" href="'.$menu[$i][1].'" title="'.( isset($menu[$i][2])?$menu[$i][2]:$menu[$i][0] ).'"><i class="fa fa-circle-o"></i> '.$menu[$i][0].'</a></li>'."\n";
        // $_submenu .= '<li><a class="submenu-'.$i.' '.strtolower(str_replace(' ', '-', $menu[$i][0])).'" href="'.$menu[$i][1].'" title="'.( isset($menu[$i][2])?$menu[$i][2]:$menu[$i][0] ).'"><i class="nav-icon fa fa-bars"></i> '.$menu[$i][0].'</a></li>'."\n";
      }
    }
    return $_submenu;
}