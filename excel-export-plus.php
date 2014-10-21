<?php
/*
Plugin Name: Excel Export Plus
Plugin URI: https://wordpress.org/plugins/excel-export-plus/
Description: Excel Export Plus allows you to export selected records to native .xls, .xlsx, csv and osd formats
Version: 0.3.0RC1
Author: Michael Cannon
Author URI: http://aihr.us/resume/
License: GPLv2 or later
Text Domain: excel-export-plus
Domain Path: /languages
 */

/**
WordPress Starter
Copyright (C) 2014  Michael Cannon

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'EEP_AIHR_VERSION' ) ) {
	define( 'EEP_AIHR_VERSION', '1.1.5' );
}

if ( ! defined( 'EEP_BASE' ) ) {
	define( 'EEP_BASE', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'EEP_DIR' ) ) {
	define( 'EEP_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'EEP_DIR_INC' ) ) {
	define( 'EEP_DIR_INC', EEP_DIR . 'includes/' );
}

if ( ! defined( 'EEP_DIR_LIB' ) ) {
	define( 'EEP_DIR_LIB', EEP_DIR_INC . 'libraries/' );
}

if ( ! defined( 'EEP_NAME' ) ) {
	define( 'EEP_NAME', 'Excel Export Plus' );
}

if ( ! defined( 'EEP_VERSION' ) ) {
	define( 'EEP_VERSION', '0.3.0RC1' );
}

//CHECKS IF IT IS AN ADMIN PAGE TO INITIATE THE PLUGIN
if (is_admin())
{
	require EEP_DIR_INC . 'controllers/config.php';

	//GET ALL THE CLASSES NEEDED
	require_once EEP_DIR_LIB . 'PHPExcel.php';
	require_once EEP_DIR_INC . 'controllers/class.phimind.php';
	require_once EEP_DIR_INC . 'controllers/class.phimind_excel_export_plus_helpers.php';
	require_once EEP_DIR_INC . 'controllers/class.phimind_excel_export_plus.php';

	//INSTANTIATE THE BASE CLASS AND TRIGGER THE INIT_CONFIGURATION METHOD
	$phimind_plugin_manager = new phimind_plugin_manager_0_1($_PHIMIND_CURRENT_CONFIG_VARS);
	$phimind_plugin_manager->init_configuration();

	//TRIGGER AN AJAX METHOD WHEN CALLED
	if ( ! empty( $_REQUEST["action"] ) && $_REQUEST["action"] == "phimind_excel_export_plus_ajax_call" )
	{
		$method = $_REQUEST["method"];
		if (@$_REQUEST["class"] != '')
			$class = new $_REQUEST["class"]();
		else
			$class = $phimind_plugin_manager;

		call_user_method($method, $class);
	}

}

?>