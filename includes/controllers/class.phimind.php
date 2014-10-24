<?php

class phimind_plugin_manager_0_1 {
	var $version = 0.1;
	var $params;
	var $layout = 'default';
	var $view_vars;

	/*PLUGIN CONFIGURATION VALUES*/
	var $plugin_root;
	var $plugin_root_web;
	var $plugin_menu_name;
	var $plugin_page_name;
	var $plugin_index_class_name;

	function __construct($_PHIMIND_CURRENT_CONFIG_VARS) {

		foreach ($_PHIMIND_CURRENT_CONFIG_VARS as $var_key => $var_value)
			$this->$var_key = $var_value;

		//ADD ALL AJAX CALLS
		$class_methods = get_class_methods($this);
		foreach ($class_methods as $method_name)
			if (substr($method_name, 0, 6) == 'ajax__')
				add_action('wp_ajax_'.$method_name, array($this, $method_name));
	}


	function _copy_parent_vars($parent_obj) {
		//GET PARAMS FROM PARENT TO USE IN DIRECT INSTANCING
		$this->plugin_root = $parent_obj->plugin_root;
		$this->plugin_root_web = $parent_obj->plugin_root_web;
		$this->plugin_menu_name = $parent_obj->plugin_menu_name;
		$this->plugin_page_name = $parent_obj->plugin_page_name;
	}


	function init_configuration() {
		//QUEUE THE JS/CSS FOR THIS PLUGIN IF THE PAGE BEING DISPLAYED IS FOR THE PLUGIN
		//THIS AVOIDS ANY KIND OF CONFLICT WITH CSS THAT IS NOT WELL WRITTEN
		if ( ! empty( $_GET['page'] ) && $_GET['page'] == $this->plugin_page_name)
			add_action('admin_enqueue_scripts', array($this, 'setup_scripts'));

		//GENERATE THE MENU
		add_action('admin_menu', array($this, 'configure_main_menu'));
		//GENERATE THE BASE MENU
	}


	function configure_main_menu() {
		$index_class = new $this->plugin_index_class_name();
		$capability  = apply_filters( 'phimind_excel_export_capability', 'edit_plugins' );
		add_menu_page( $this->plugin_menu_name, $this->plugin_menu_name, $capability, $this->plugin_page_name, array( $index_class, 'index' ) );
	}


	function setup_scripts() {
		wp_register_script('bootstrap', EEP_URL_LIB . 'bootstrap/js/bootstrap.js', array('jquery'), '2.3.2', true);
		wp_enqueue_script('bootstrap');

		wp_register_style('bootstrap', EEP_URL_LIB . 'bootstrap/css/bootstrap.css', array(), '2.3.2', 'all');
		wp_enqueue_style('bootstrap');

		wp_register_script('global_js', EEP_URL . 'assets/js/global.js', array('jquery'), $this->version, true);
		wp_enqueue_script('global_js');

		wp_register_style('global_css', EEP_URL . 'assets/css/global.css', array(), $this->version, 'all');
		wp_enqueue_style('global_css');
	}


	function set($var_name, $var_value) {
		$this->view_vars[$var_name] = $var_value;
	}


	function render($template, $render_to_variable = false) {
		require EEP_DIR_INC . 'config.php';

		foreach ($this->view_vars as $view_var_name => $view_var_value)
			${$view_var_name} = $view_var_value;

		$render_output_layout = '';
		$render_output_view = '';

		//RENDER THE LAYOUT FIRST
		if (!empty($this->layout)) {
			ob_start();
			include EEP_DIR_INC . 'views/layouts/' . $this->layout . '.php';
			$render_output_layout = ob_get_contents();
			ob_end_clean();
		}

		//RENDER THE VIEW

		if ($render_to_variable)
			ob_start();

		if (empty($this->layout)) {
			include EEP_DIR_INC . 'views/' . $template . '.php';
		} else {
			$contents_before = substr($render_output_layout, 0, strpos($render_output_layout, '{content_block'));
			$contents_after = substr($render_output_layout, strpos($render_output_layout, '}') + 1);

			echo $contents_before;
			include_once EEP_DIR_INC . 'views/' . $template . '.php';
			echo $contents_after;
		}

		if ($render_to_variable) {
			$render_var = ob_get_contents();
			ob_end_clean();
			return $render_var;
		}
	}


}


?>
