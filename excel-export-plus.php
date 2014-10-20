<?php
	/*
	Plugin Name: Phimind Excel Export Plus
	Plugin URI: http://www.phimind.com/phimind_excel_export_plus
	Description: WP Phimind Excel Export Plus allows you to export selected records to native .xls, .xlsx, csv and osd formats
	Version: 0.3.0RC1
	Author: PhiMind.com
	Author URI: http://www.phimind.com/
	*/

	//CHECKS IF IT IS AN ADMIN PAGE TO INITIATE THE PLUGIN
	if (is_admin())
	{
		define('DS' , DIRECTORY_SEPARATOR);

		require('controllers'.DS.'config.php');

		//GET ALL THE CLASSES NEEDED
		require_once($_PHIMIND_CURRENT_CONFIG_VARS["plugin_root"].DS.'vendors'.DS.'PHPExcel.php');
		require_once($_PHIMIND_CURRENT_CONFIG_VARS["plugin_root"].DS.'controllers'.DS.'class.phimind.php');
		require_once($_PHIMIND_CURRENT_CONFIG_VARS["plugin_root"].DS.'controllers'.DS.'class.phimind_excel_export_plus_helpers.php');
		require_once($_PHIMIND_CURRENT_CONFIG_VARS["plugin_root"].DS.'controllers'.DS.'class.phimind_excel_export_plus.php');

		//INSTANTIATE THE BASE CLASS AND TRIGGER THE INIT_CONFIGURATION METHOD
		$phimind_plugin_manager = new phimind_plugin_manager_0_1($_PHIMIND_CURRENT_CONFIG_VARS);
		$phimind_plugin_manager->init_configuration();

		//TRIGGER AN AJAX METHOD WHEN CALLED
		if ($_REQUEST["action"] == "phimind_excel_export_plus_ajax_call")
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