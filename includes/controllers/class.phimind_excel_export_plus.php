<?php

class phimind_excel_export_plus extends phimind_plugin_manager_0_1
{

	var $array_wp_status = array();
	var $array_wp_columns = array();
	var $array_wp_custom_columns = array();
	var $array_wp_filter_columns = array();
	var $array_wp_filter_custom_columns = array();
	var $array_meta_columns = array();

	var $records_header = array();
	var $records_data = array();

	var $query_args;
	var $query_sql;

	function __construct() {
		require 'config.php';

		parent::__construct($_PHIMIND_CURRENT_CONFIG_VARS);
		parent::init_configuration();

		$this->array_wp_columns["ID"] = "ID";
		$this->array_wp_columns["post_name"] = "Name (slug)";
		$this->array_wp_columns["post_title"] = "Title";
		$this->array_wp_columns["post_date"] = "Date";
		$this->array_wp_columns["post_date_gmt"] = "Date GMT";
		$this->array_wp_columns["post_author"] = "Author ID";
		$this->array_wp_columns["post_content"] = "Content";
		$this->array_wp_columns["post_excerpt"] = "Excerpt";
		$this->array_wp_columns["post_status"] = "Status";
		$this->array_wp_columns["comment_status"] = "Comment Status";
		$this->array_wp_columns["ping_status"] = "Ping Status";
		$this->array_wp_columns["to_ping"] = "To Ping";
		$this->array_wp_columns["pinged"] = "Pinged";
		$this->array_wp_columns["post_password"] = "Post Password";
		$this->array_wp_columns["post_modified"] = "Post Modified Date";
		$this->array_wp_columns["post_modified_gmt"] = "Post Modified Date GMT";
		$this->array_wp_columns["post_content_filtered"] = "Content Filtered";
		$this->array_wp_columns["post_parent"] = "Post Parent ID";
		$this->array_wp_columns["guid"] = "Guid (Explicit URL)";
		$this->array_wp_columns["menu_order"] = "Menu Order";
		$this->array_wp_columns["post_type"] = "Post Type";
		$this->array_wp_columns["post_mime_type"] = "Post Mime Type";
		$this->array_wp_columns["comment_count"] = "Comment Count";

		$this->array_wp_custom_columns["permalink"] = "Permalink (Nice URL)";
		$this->array_wp_custom_columns["post_author_name"] = "Author Name";
		$this->array_wp_custom_columns["post_parent_title"] = "Post Parent Title";
		$this->array_wp_custom_columns["post_parent_name"] = "Post Parent Name (Slug)";
		$this->array_wp_custom_columns["post_parent_permalink"] = "Post Parent Permalink";
		//   $this->array_wp_custom_columns["post_type_nice_name"] = "Post Type (Nice name)";

		$this->array_wp_filter_columns["ID"] = "ID";
		$this->array_wp_filter_columns["post_name"] = "Name (slug)";
		$this->array_wp_filter_columns["post_status"] = "Status";
		$this->array_wp_filter_columns["post_parent"] = "Post Parent ID";
		$this->array_wp_filter_custom_columns["post_author_name"] = "Author Name";

		$this->array_wp_status["publish"] = __("Published");
		$this->array_wp_status["pending"] = __("Pending");
		$this->array_wp_status["draft"] = __("Draft");
		$this->array_wp_status["auto-draft"] = __("Auto-Draft");
		$this->array_wp_status["future"] = __("Scheduled");
		$this->array_wp_status["private"] = __("Private");
		$this->array_wp_status["inherit"] = __("Inherit");
		$this->array_wp_status["trash"] = __("Trash");
		$this->array_wp_status["any"] = __("Any");

	}


	function index() {
		add_thickbox();

		global $wpdb;

		//GET ALL POST TYPES REGISTERED WITHIN WP (SYSTEM AND CUSTOM ONES)
		$post_types = get_post_types(null, 'objects');
		$this->set('post_types', $post_types);

		//GET ALL META FIELDS USED WITHIN WP (SYSTEM AND CUSTOM ONES)
		$query = 'SELECT DISTINCT('.$wpdb->postmeta.'.meta_key) as "meta_key"
						FROM '.$wpdb->postmeta.'
						ORDER BY '.$wpdb->postmeta.'.meta_key';
		$meta_keys = $wpdb->get_results($query, OBJECT);
		$this->set('meta_keys', $meta_keys);

		//GET ALL PRESETS ALREADY REGISTERED
		$presets = get_option('phimind_excel_export_presets');
		/*
			$presetsX = array(
				array(
					'name' => 'teste de preset 01',
					'post_types' => array('question'),
					'fields' => array(
						array('ID'),
						array('post_title'),
						array('post_date'),
						array('post_status'),
						array('permalink'),
						array('custom_field' , 'meta_question_user_name'),
						array('custom_field' , 'meta_question_user_email'),
						array('custom_field' , 'meta_question_user_data_de_nascimento'),
						array('custom_field' , 'meta_question_user_cidade'),
						array('custom_field' , 'meta_estado'),
						array('custom_field' , 'meta_reply'),
						array('custom_field' , 'meta_tema')
					),
					'filters' => array(
						array('post_status', 'any'),
						array('ID', '=', '189'),
						array('custom_field', 'meta_cargo', 'CHAR', 'BETWEEN', 'xpto', 'yzx')
					)
				),
				array(
					'name' => 'teste de preset 02',
					'post_types' => array('question', 'politico'),
					'fields' => array('ID', 'post_name', 'custom_field' => array('meta_cargo'), 'custom_field' => array('imagem_id')),
					'filters' => array('post_name' => 'teste', 'post_status' => 'any')
				)
			);
			update_option('phimind_excel_export_presets', $presets);
*/

		$this->set('presets', $presets);
		$this->set('json_presets', json_encode($presets));
		$this->set('array_wp_columns', $this->array_wp_columns);
		$this->set('array_wp_custom_columns', $this->array_wp_custom_columns);
		$this->set('array_wp_filter_columns', $this->array_wp_filter_columns);
		$this->set('array_wp_filter_custom_columns', $this->array_wp_filter_custom_columns);
		$this->set('array_wp_status', $this->array_wp_status);

		$this->render('index');
	}


	private function _set_query_args() {

		if (empty($_REQUEST["post_type"]))
			$array_post_type = array();
		else
			$array_post_type = $_REQUEST["post_type"];

		$array_fields = array();
		$array_custom_fields = array();
		if (!empty($_REQUEST["column"])) {
			for ($i = 0 ; $i < count($_REQUEST["column"]["column_name"]); $i++) {
				if (!empty($_REQUEST["column"]["column_name"][$i]) && $_REQUEST["column"]["column_name"][$i] != 'custom_field' && empty($this->array_wp_custom_columns[$_REQUEST["column"]["column_name"][$i]]))
					array_push($array_fields, $_REQUEST["column"]["column_name"][$i]);
				elseif (!empty($this->array_wp_custom_columns[$_REQUEST["column"]["column_name"][$i]]))
					array_push($array_custom_fields, $_REQUEST["column"]["column_name"][$i]);
			}
		}

		$args = array(
			'numberposts'   => -1,
			'posts_per_page'   => 10,
			'orderby'   => 'name',
			'order'    => 'ASC',
			'fields'   => $array_fields,
			'custom_fields'  => $array_custom_fields,
			'post_type'   => $array_post_type,
		);

		$meta_array = array();
		if (!empty($_REQUEST["filter"])) {
			for ($i = 0 ; $i < count($_REQUEST["filter"]) ; $i++) {
				$filter_field = $_REQUEST["filter"]["filter_field"][$i];
				$filter_custom_name = $_REQUEST["filter"]["filter_custom_name"][$i];
				$filter_rule = $_REQUEST["filter"]["filter_rule"][$i];
				$filter_type = $_REQUEST["filter"]["filter_custom_type"][$i];
				$filter_status = $_REQUEST["filter"]["filter_field_status"][$i];
				$filter_value_1 = $_REQUEST["filter"]["filter_value_1"][$i];
				$filter_value_2 = $_REQUEST["filter"]["filter_value_2"][$i];

				//SPECIFIC FIELDS HAVE ONLY SOME SPECIFIC FILTER RULES AVAILABLE
				switch ($filter_field) {
				case "ID":
					if ($filter_rule == "=")
						$args["post__in"] = explode(",", $filter_value_1);
					elseif ($filter_rule == "!=")
						$args["post__not_in"] = explode(",", $filter_value_1);
					break;
				case "post_name":
					$args["name"] = $filter_value_1;
					break;
				case "post_author":
					$args["author"] = $filter_value_1;
					break;
				case "post_status":
					$args["post_status"] = $filter_status;
					break;
				case "post_parent":
					$args["post_parent"] = $filter_value_1;
					break;
				case "custom_field":

					if ($filter_rule == "IN" || $filter_rule == "NOT IN")
						$filter_value = explode(",", $filter_value_1);
					elseif ($filter_rule == "BETWEEN" || $filter_rule == "NOT BETWEEN")
						$filter_value = array($filter_value_1, $filter_value_2);
					else
						$filter_value = $filter_value_1;

					if ($filter_type == '')
						$filter_type = 'CHAR';

					$meta_array[] = array('key' => $filter_custom_name, 'type' => $filter_type, 'compare' => $filter_rule, 'value' => $filter_value);
					break;
				}
			}
		}

		if (empty($args["post_status"]))
			$args['post_status'] = 'publish';

		if (!empty($meta_array))
			$args['meta_query'] = $meta_array;

		$this->query_args = $args;
	}


	function _filter_query_for_fields($current_query_args) {
		global $wpdb;

		//FETCH ONLY THE FIELDS THAT ARE NOT EMPTY
		$array_valid_fields = array();
		foreach ($this->query_args["fields"] as $field)
			if (!empty($field) && empty($this->array_wp_custom_columns[$field]))
				array_push($array_valid_fields, $wpdb->posts.'.'.$field);

			//CREATE THE STRING FOR THE FIELDS
			$fields = implode(", ", $array_valid_fields);

		//APPEND THE ID/POST_PARENT/NAME/AUTHOR_ID FIELDS THAT WP NEEDS FOR BASIC FUNCTIONS LIKE GET_PERMALINK, ETC...
		$fields = $fields.', '.$wpdb->posts.'.ID, '.$wpdb->posts.'.post_parent, '.$wpdb->posts.'.post_name, '.$wpdb->posts.'.post_author, '.$wpdb->posts.'.post_type';

		//SET THE FIELDS ON THE QUERY
		$current_query_args['fields'] = $fields;

		return $current_query_args;
	}


	private function _fetch_posts_records_list() {
		if ( is_admin() ) {
			require_once ABSPATH . 'wp-includes/pluggable.php';
		}

		//FILTER THE ENTIRE WP_QUERY TO FETCH ONLY SPECIFIC FIELDS (WP DOES NOT DO IT CORRECTLY)
		add_filter('posts_clauses', array($this, '_filter_query_for_fields'), 20, 1);

		//FETCH THE RECORDS BASED ON THE SELECTION AND FILTERS USED
		$records = new WP_Query($this->query_args);

		//SET THE EXPLICIT SQL FOR DEBUGGING PURPOSES
		$this->query_sql = $records->request;

		//REMOVE THE FILTER AFTER USAGE
		remove_filter('posts_clauses', array($this, '_filter_query_for_fields'));

		$array_fields = array();
		if (!empty($_REQUEST["column"])) {
			for ($i = 0 ; $i < count($_REQUEST["column"]["column_name"]); $i++) {
				$column_name = $_REQUEST["column"]["column_name"][$i];

				//FETCH META VALUES
				if ($column_name == 'custom_field') {
					$meta_name = $_REQUEST["column"]["column_custom_name"][$i];
					if (!empty($meta_name)) {
						array_push($this->array_meta_columns, $meta_name);
						foreach ($records->posts as $post) {
							$column_value = get_post_meta($post->ID, $meta_name, true);
							$post->$meta_name = $column_value;
						}
					}
				}
				elseif (!empty($this->array_wp_custom_columns[$column_name])) {
					//FETCH CUSTOM COLUMN VALUES
					foreach ($records->posts as $post) {
						$field_value = 'TO-DO';
						switch ($column_name) {
						case "post_author_name":
							//INCLUDE PLUGABBLE TO GET THE FUNCTION FOR USER SEARCH
							require_once ABSPATH.'/wp-includes/pluggable.php';
							$user = get_userdata($post->post_author);
							$field_value = $user->display_name;
							break;
						case "permalink":
							$field_value = get_permalink($post->ID);
							break;
						case "post_parent_title":
							$parent_post = get_post($post->post_parent);
							$field_value = $parent_post->post_title;
							break;
						case "post_parent_name":
							$parent_post = get_post($post->post_parent);
							$field_value = $parent_post->post_name;
							break;
						case "post_parent_permalink":
							$field_value = get_permalink($post->post_parent);
							break;
						}
						$post->$column_name = $field_value;
					}
				}
			}
		}
		return $records;
	}


	private function _show_posts_records_list() {
		$this->layout = false;
		$records = $this->_fetch_posts_records_list();
		$this->_generate_records_table($records);
		$this->set('records', $records);
		$this->set('records_header', $this->records_header);
		$this->set('records_data', $this->records_data);
		$records_html = $this->render('partials/record_listing', true);

		$query_sql = $this->query_sql;
		$query_sql = str_replace("FROM", "<br>FROM", $query_sql);
		$query_sql = str_replace("WHERE", "<br>WHERE", $query_sql);
		$query_sql = str_replace("AND", "<br>AND", $query_sql);
		$query_sql = str_replace("LIMIT", "<br>LIMIT", $query_sql);
		$query_sql = str_replace("ORDER BY", "<br>ORDER BY", $query_sql);

		$array_response = array();
		$array_response["record_count"] = $records->found_posts;
		$array_response["records_html"] = $records_html;
		$array_response["debug"] = json_encode($this->query_args);
		$array_response["sql"] = json_encode($query_sql);
		echo json_encode($array_response);
		die;
	}


	/*
			CREATE AN ARRAY WITH THE HEADER AND ANOTHER WITH THE DATA
			FORMATED THAT CAN BE USED IN THE EXPORT METHOD AND ALSO IN THE RESULT METHOD
		*/
	private function _generate_records_table($records) {
		$array_headers = array();
		$array_data = array();

		//SET THE HEADERS FOR THE BASIC FIELDS
		foreach ($records->query_vars["fields"] as $field)
			array_push($array_headers, $this->array_wp_columns[$field]);

		//SET THE HEADERS FOR THE CUSTOM FIELDS
		foreach ($records->query_vars["custom_fields"] as $field)
			array_push($array_headers, $this->array_wp_custom_columns[$field]);

		//SET THE HEADERS FOR THE META FIELDS
		foreach ($this->array_meta_columns as $meta_field)
			array_push($array_headers, $meta_field);

		$this->records_header = $array_headers;

		//SET THE DATA
		foreach ($records->posts as $record) {
			$array_single_record = array();

			//SET THE DATA FOR THE BASIC FIELDS
			foreach ($records->query_vars["fields"] as $field) {
				$field_value = $record->$field;
				array_push($array_single_record, $field_value);
			}

			//SET THE DATA FOR THE CUSTOM FIELDS
			foreach ($records->query_vars["custom_fields"] as $field)
				array_push($array_single_record, $record->$field);

			//SET THE DATA FOR THE META FIELDS
			foreach ($this->array_meta_columns as $meta_field)
				array_push($array_single_record, $record->$meta_field);

			array_push($array_data, $array_single_record);
		}
		$this->records_data = $array_data;

	}


	function ajax__fetch_records_list() {
		$this->_set_query_args();

		if (!empty($_REQUEST["paged"]))
			$paged = $_REQUEST["paged"];
		else
			$paged = 1;

		$this->query_args["paged"] = $paged;

		$this->_show_posts_records_list();
		die;
	}


	function ajax__export_execute() {
		$this->layout = false;
		$this->_set_query_args();

		if (empty($_REQUEST["split_file_records_number"])) {
			$this->query_args["paged"] = '1';
			$this->query_args["numberposts"] = '-1';
			$this->query_args["posts_per_page"] = '-1';
		} else {
			if (empty($_REQUEST["paged"]))
				$this->query_args["paged"] = '1';
			else
				$this->query_args["paged"] = $_REQUEST["paged"];
			$this->query_args["numberposts"] = $_REQUEST["split_file_records_number"];
			$this->query_args["posts_per_page"] = $_REQUEST["split_file_records_number"];
		}

		$records = $this->_fetch_posts_records_list();
		$this->_generate_records_table($records);

		$record_count = 0;
		$file_list = '';
		$file_path = EEP_DIR . 'tmp/';

		if ($records->max_num_pages > 1) {
			$file_name = 'Excel_Export_Plus_'.$records->found_posts.'_records__File_'.$this->query_args["paged"].'_of_'.$records->max_num_pages.'_('.$records->post_count.'_records)';
			$from_record = (($this->query_args["paged"] - 1) * $_REQUEST["split_file_records_number"]) + 1;
			$to_record = (($this->query_args["paged"] - 1) * $_REQUEST["split_file_records_number"]) + $_REQUEST["split_file_records_number"];
			if ($to_record > $records->found_posts)
				$to_record = $records->found_posts;
			$tab_title = 'Exported Records '.$from_record.' to '.$to_record;
		} else {
			$file_name = 'Excel_Export_Plus_'.$records->post_count.'_records';
			$tab_title = 'Exported '.$records->post_count.' Records';
		}

		$excel = new PHPExcel();
		$excel->setActiveSheetIndex(0);
		$excel->getActiveSheet()->setTitle($tab_title);

		$row = 1;
		$col = 0;
		foreach ($this->records_header as $field) {
			$excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);
			$excel->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getFont()->setBold(true);
			$excel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
			$col++;
		}

		$row = 2;
		$col = 0;
		foreach ($this->records_data as $data) {
			$col = 0;
			foreach ($data as $field) {
				$field_value = $field;
				$excel->getActiveSheet()->getCellByColumnAndRow($col, $row)->getStyle()->getFont()->setBold(false);
				$excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field_value);
				$col++;
			}
			$row++;
		}

		switch ($_REQUEST["rad_format"]) {
		case "xlsx":
			$php_excel_format = 'Excel2007';
			$file_extension = 'xlsx';
			break;
		case "xls":
			$php_excel_format = 'Excel5';
			$file_extension = 'xls';
			break;
		case "csv":
			$php_excel_format = 'CSV';
			$file_extension = 'csv';
			break;
		case "ods":
			$php_excel_format = 'ODS';
			$file_extension = 'ods';
			break;
		}

		$objWriter = PHPExcel_IOFactory::createWriter($excel, $php_excel_format);
		if ($_REQUEST["rad_format"] == "csv")
			$objWriter->setUseBOM(true);
		$objWriter->save($file_path.$file_name.'.'.$file_extension);

		$download_file_url = '<a href="'.$this->plugin_root_web.'/tmp/'.$file_name.'.'.$file_extension.'">'.$file_name.'.'.$file_extension.'</a><br>';

		if ($records->post_count > 1)
			$records_sufix = 's';
		else
			$records_sufix = '';

		$msg =
			'File : '.$this->query_args["paged"].' out of '.$records->max_num_pages.' generated with '.$records->post_count.' record'.$records_sufix.'
				<br>
				Filename : <strong>'.$file_name.'.'.$file_extension.'</strong>
				<br>
				<br>
				Generating next file(s).
				<br>
				Please wait...';

		$array_response = array();
		$array_response["msg"] = $msg;
		$array_response["download_url"] = $download_file_url;
		echo json_encode($array_response);
		die;
	}


	function ajax__save_preset() {

		//GET ALL PRESETS ALREADY REGISTERED
		$presets = get_option('phimind_excel_export_presets');

		$array_fields = array();
		for ($i = 0 ; $i < count($_REQUEST["column"]["column_name"]) ; $i++) {
			$array_field = array();
			$array_field[] = $_REQUEST["column"]["column_name"][$i];
			if (!empty($_REQUEST["column"]["column_custom_name"][$i]))
				$array_field[] = $_REQUEST["column"]["column_custom_name"][$i];
			$array_fields[] = $array_field;
		}

		$array_filters = array();
		$filter_status_count = 0;
		$filter_rule_count = 0;
		$filter_custom_type_count = 0;
		for ($i = 0 ; $i < count($_REQUEST["filter"]["filter_field"]) ; $i++) {
			$array_filter = array();
			$array_filter[] = $_REQUEST["filter"]["filter_field"][$i];

			//CUSTOM FIELD
			if ($_REQUEST["filter"]["filter_field"][$i] == "custom_field") {
				$array_filter[] = $_REQUEST["filter"]["filter_custom_name"][$i];
				$array_filter[] = $_REQUEST["filter"]["filter_custom_type"][$filter_custom_type_count];
				$filter_custom_type_count++;
				$array_filter[] = $_REQUEST["filter"]["filter_rule"][$filter_rule_count];
				$filter_rule_count++;
				$array_filter[] = $_REQUEST["filter"]["filter_value_1"][$i];
				$array_filter[] = $_REQUEST["filter"]["filter_value_2"][$i];
			}

			//POST STATUS
			if ($_REQUEST["filter"]["filter_field"][$i] == "post_status") {
				$array_filter[] = $_REQUEST["filter"]["filter_field_status"][$filter_status_count];
				$filter_status_count++;
			}

			//POST ID
			if ($_REQUEST["filter"]["filter_field"][$i] == "ID") {
				$array_filter[] = $_REQUEST["filter"]["filter_rule"][$filter_rule_count];
				$array_filter[] = $_REQUEST["filter"]["filter_value_1"][$i];
				$filter_rule_count++;
			}

			$array_filters[] = $array_filter;
		}

		$new_preset = array(
			'name' => $_REQUEST["preset_name"],
			'post_types' => $_REQUEST["post_type"],
			'fields' => $array_fields,
			'filters' => $array_filters
		);

		$presets[] = $new_preset;
		update_option('phimind_excel_export_presets', $presets);

	}


	function ajax__delete_preset() {

		//GET ALL PRESETS ALREADY REGISTERED
		$presets = get_option('phimind_excel_export_presets');

		//ONLY RE-SAVE PRESETS THAT ARE DIFERENT THAN THE ONE BEING DELETED
		$presets_final = array();
		foreach ($presets as $preset) {
			if ($preset["name"] != $_REQUEST["preset_name"])
				$presets_final[] = $preset;
		}

		//UPDATE THE PRESETS OBJECT
		update_option('phimind_excel_export_presets', $presets_final);

		echo '1';

		die;

	}


}


?>
