
	jQuery(document).ready(function($) {

		//INITIALIZE POPOVER MANUALLY 
		//READ : http://twitter.github.io/bootstrap/javascript.html#popovers
		$("[data-toggle=popover]")
			.popover()
			.click(function(e) {
			e.preventDefault()
		});
		$('#xxx').popover('show');

		var current_file_number = 1;
		var total_file_count;
		var records_per_page;
		var record_count;
		var flag_update_record_list_enabled = 1;

		$('.force_refresh_listing_button').bind('click', function() {
			update_record_list();
			return false;
		});

		$('.next_step_button').bind('click', function() {
			if ($(this).hasClass('enabled'))
				show_menu($(this).attr('rel'));
			calculate_file_count();
			return false;
		});

		$('.chk_posttype, .chk_column').bind('click', function() {
			$(this).parents('form').find('#paged').val(1);
			update_record_list();
		});

		$('.form_export_button').bind('click', function() {
			export_record_list();
			return false;
		});

		$('[name=rad_format]').bind('click', function() {
			$('.form_export_button').addClass('enabled');
			$('.step_menu .item:nth(1) .status').removeClass('pending').addClass('checked');
		});

		$('#split_file_records_number').bind('keyup', function() {
			calculate_file_count();
		});

		$('.btn_add_data_block').bind('click', function() {
			
			data_block = $(this).parent().find('.data_block:last');
			data_block_clone = data_block.clone();

			//CLONES AND APPENDS
			$(data_block).after(data_block_clone);

			//REBIND ALL FIELDS
			bind_fields();

			//CLEAN THE FIELDS IN THE CLONE BLOCK
			clear_block(data_block_clone);

			//UPDATE THE LISTING
			update_record_list();

			return false;
		});

		$('#faq_list .question').bind('click', function() {
			$(this).next().toggle();
			$(this).find('i').toggleClass('icon-plus-sign').toggleClass('icon-minus-sign');
		});

		$('#eep_tab_menu .menu_item_enabled').bind('click', function() {
			show_menu($(this).index());
		});

		function show_menu(menu_index)
		{
			$('#eep_tab_menu li:eq(' + menu_index + ') a').tab('show');
		}

		function calculate_file_count()
		{
			record_count = parseInt($('#record_count').val(), 10);
			records_per_page = parseInt($('#split_file_records_number').val(), 10);

			if (isNaN(records_per_page))
				records_per_page = record_count;

			if (records_per_page >= record_count)
				total_file_count = 1;
			else
				total_file_count = Math.ceil(record_count / records_per_page);

			$('#file_count_html').html(total_file_count);
		}

		function clear_block(block)
		{
			$(block).find('input[type="text"]').val('');
			$(block).find('select').val('');
			$(block).find('select').trigger('change');
		}

		function bind_fields()
		{
			//FIRST UNBIND THE ELEMENTS THEN BIND AGAIN TO AVOID MULTIPLE BINDINGS


			$('.fields_selector').unbind('change');
			$('.fields_selector').bind('change', function() {
				current_value = $(this).val();

				//HIDE ALL ELEMENTS
				$(this).parent().find('.column_custom_name_selector').hide();

				if (current_value == "custom_field")
					$(this).parent().find('.column_custom_name_selector').show();

				//UPDATE THE LISTING
				update_record_list();
			});

			$('.filter_selector').unbind('change');
			$('.filter_selector').bind('change', function() {
				current_value = $(this).val();

				//HIDE ALL ELEMENTS
				$(this).parent().find('.filter_custom_name_selector').hide();
				$(this).parent().find('.filter_custom_type_selector').hide();

				if (current_value == "custom_field")
				{
					$(this).parent().find('.filter_custom_name_selector').show();
					$(this).parent().find('.filter_custom_type_selector').show();
				}

				//RESET THE BASIC FIELDS
				$(this).parent().find('.filter_rule option').removeAttr('disabled');
				$(this).parent().find('.filter_rule').val('');
				$(this).parent().find('.filter_rule').show();
				$(this).parent().find('.filter_value_1').show();

				//REMOVE ALL RULES 
				$(this).parent().find('.filter_rule option').attr({ disabled: 'disabled' });

				//HIDE SPECIFIC FIELD VALUES
				$(this).parent().find('.filter_post_status_container').hide();

				//ENABLE ONLY THE RULES AVAILABLE TO EACH SPECIFIC FIELD
				switch (current_value) {
					case "ID":
					case "author_id":
					case "post_parent":
						$(this).parent().find('.filter_rule option[value="="]').removeAttr('disabled');
						$(this).parent().find('.filter_rule option[value="!="]').removeAttr('disabled');
						break;
					case "post_name":
					case "post_title":
						$(this).parent().find('.filter_rule option[value="="]').removeAttr('disabled');
						$(this).parent().find('.filter_rule option[value="!="]').removeAttr('disabled');
						break;
					case "post_status":
						$(this).parent().find('.filter_post_status_container').show();
						$(this).parent().find('.filter_rule').hide();
						$(this).parent().find('.filter_value_1').hide();
						$(this).parent().find('.filter_rule option[value="="]').removeAttr('disabled');
						$(this).parent().find('.filter_rule option[value="!="]').removeAttr('disabled');
						break;
					case "custom_field":
						$(this).parent().find('.filter_rule option').removeAttr('disabled');
						break;
				}

				//UPDATE THE LISTING
				update_record_list();
			});

			$('.btn_remove_data_block').unbind('click');
			$('.btn_remove_data_block').bind('click', function() {
				//CHECK TO SEE IF THIS IS THE LAST DATA BLOCK
				data_block_count = $(this).parents('.parameter_container').find('.data_block').length;

				if (data_block_count > 1)
					$(this).parent('.data_block').remove();
				else
					clear_block($(this).parent('.data_block'));
				
				bind_fields();

				//UPDATE THE LISTING
				update_record_list();

				return false;
			});

			$('.filter_rule').unbind('change');
			$('.filter_rule').bind('change', function() {
				current_value = $(this).val();
				if (current_value == "BETWEEN" || current_value == "NOT BETWEEN")
				{
					$(this).parent().find('.filter_value_2_container').show();
				} else {
					$(this).parent().find('.filter_value_2_container').hide();
				}

				//UPDATE THE LISTING
				update_record_list();
			});

			$('.post_type_selector, .column_custom_name_selector').unbind('change');
			$('.post_type_selector, .column_custom_name_selector').bind('change', function() {
				//UPDATE THE LISTING
				update_record_list();
			});

		}

		function bind_pagination_links()
		{

			//UNBIND PAGINATION LINKS TO AVOID DOUBLE BINDS
			$('.pagination_link').unbind('click');

			//BIND PAGINATION LINKS TO REBUILD LISTING
			$('.pagination_link').bind('click', function(event) {
				
				event.preventDefault();

				current_page = $(this).parents('form').find('#paged').val();
				new_page = $(this).attr('rel');
				$(this).parents('form').find('#paged').val(new_page);
				update_record_list();

				return false;
			});

		}

		function export_record_list()
		{
			if (current_file_number == 1)
			{
				calculate_file_count();
				$('#progress_bar_export .bar').css('width', '0%');
				$('#export_result').show();
				$('#export_result').html('Exporting file No.' + current_file_number + ' with ' + records_per_page + ' record(s)<br><br>Please wait...');
				$('.download_links').html('');
			}

			qs = $('#frm_query_builder').serialize();
			qs = qs + '&' + $('#frm_export_options').serialize();
			qs = qs + '&action=phimind_excel_export_plus_ajax_call';
			qs = qs + '&class=phimind_excel_export_plus';
			qs = qs + '&method=ajax__export_execute';
			qs = qs + '&paged=' + current_file_number;

			$.get(ajaxurl, qs, function(response) {

				progress_percentage = Math.ceil(100 / total_file_count) * current_file_number;
				if (progress_percentage > 100)
					progress_percentage = 100;

				$('#progress_bar_export .bar').css('width', progress_percentage + '%');
				$('#export_result').html(response.msg);
				$('.download_links').append(response.download_url);

				current_file_number = current_file_number + 1;

				if (total_file_count >= current_file_number)
				{
					export_record_list();
				} else {
					$('#export_result').html('All files generated successfully. Proceed to step 3 to download them.');
					total_file_count = 0;
					current_file_number = 1;
					$('.step_2.next_step_button[rel=2]').addClass('enabled');
					$('.step_3.next_step_button[rel=1]').addClass('enabled');
				}

			}, "json");

		}

		function update_record_list()
		{
			if (flag_update_record_list_enabled == 0)
				return;

			//CLEAR THE STATUS ON ALL TABS
			$('.step_menu .item .status').removeClass('checked').addClass('pending');

			//CLEAR THE DATA/RESULT ON ALL TABS
			$('#record_result').html('');
			$('#export_result').html('');

			//CLEAR SPECIFIC VARIABLES
			current_file_number = 1;

			//SET ALL NEXT STEP BUTTONS AS DISABLED
			$('.next_step_button').removeClass('enabled');

			//CHECK IF THERE IS A CPT SELECTED
			if ($('#frm_query_builder .post_type_selector').val() == "")
				return;

			//DISPLAY LOADING MESSAGE
			$('#record_result').html('Loading records... please wait');

			//BUILD THE AJAX QUERY_STRING
			qs = $('#frm_query_builder').serialize();
			qs = qs + '&action=phimind_excel_export_plus_ajax_call';
			qs = qs + '&class=phimind_excel_export_plus';
			qs = qs + '&method=ajax__fetch_records_list';

			//FETCH THE DATA
			$.get(ajaxurl, qs, function(response) {

				$('#record_result').html(response.records_html);
				$('#record_count').val(response.record_count);
				$('#record_count_html').html(response.record_count);
				$('#debug').html(jQuery.parseJSON(response.debug));
				$('#debug_sql').html(jQuery.parseJSON(response.sql));

				if (response.record_count > 0)
				{
					bind_pagination_links();

					//SET THE TAB AS CHECKED
					$('.step_menu .item:nth(0) .status').removeClass('pending').addClass('checked');

					//SET STEP 1 - NEXT BUTTON AS ENABLED
					$('.step_1.next_step_button').addClass('enabled');

					//SET STEP 2 - PREVIOUS BUTTON AS ENABLED
					$('.step_2.next_step_button[rel=0]').addClass('enabled');
				}

			}, "json");

		}

		//PRESETS VIEW
		$('.cmb_select_preset').unbind('change');
		$('.cmb_select_preset').bind('change', function() {
			$('#load_preset_configuration').html(show_preset_configuration($(this).val()));
		});

		//PRESETS SELECT
		$('.btn_apply_preset').unbind('click');
		$('.btn_apply_preset').bind('click', function() {
			apply_preset($('.cmb_select_preset').val());
			tb_remove();
		});

		//PRESET LOAD THICKBOX
		$('.btn_load_preset').unbind('click');
		$('.btn_load_preset').bind('click', function() {
	        tb_show();
		});

		//PRESET SAVE THICKBOX
		$('.btn_save_preset').unbind('click');
		$('.btn_save_preset').bind('click', function() {
	        tb_show();
	    });

		//PRESET SAVE EXECUTE
		$('.btn_save_preset_execute').unbind('click');
		$('.btn_save_preset_execute').bind('click', function() {

			//BUILD THE AJAX QUERY_STRING
			qs = $('#frm_query_builder').serialize();
			qs = qs + '&action=phimind_excel_export_plus_ajax_call';
			qs = qs + '&class=phimind_excel_export_plus';
			qs = qs + '&method=ajax__save_preset';
			qs = qs + '&preset_name=' + $('#preset_name').val();

			//FETCH THE DATA
			$.get(ajaxurl, qs, function(response) {
				tb_remove();
			}, "json");

		});



		//PRESET SAVE EXECUTE
		$('.btn_delete_preset').unbind('click');
		$('.btn_delete_preset').bind('click', function() {

			if ($('#cmb_select_preset').val() == "")
			{
				alert("Select a Preset to delete.");
				return;
			}

			if (confirm('Are you sure you want to delete this Preset?') != 1)
				return;

			//BUILD THE AJAX QUERY_STRING
			qs = $('#frm_query_builder').serialize();
			qs = qs + '&action=phimind_excel_export_plus_ajax_call';
			qs = qs + '&class=phimind_excel_export_plus';
			qs = qs + '&method=ajax__delete_preset';
			qs = qs + '&preset_name=' + $('#cmb_select_preset').val();

			//FETCH THE DATA
			$.get(ajaxurl, qs, function(response) {

				if (response == 1)
				{
					$("#cmb_select_preset option:selected").remove();
					$('#load_preset_configuration').html('');
				}

			}, "json");

		});




		function show_preset_configuration(preset_name)
		{

			html_return = '';
			spacing = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

			$.each(json_presets, function(key, value) {

				if (value["name"] == preset_name)
				{

					html_return += '<strong>Post Types</strong><br>';
					post_types = value["post_types"];
					$.each(post_types, function(key, value) {
						html_return += spacing + value + '<br>';
					});

					html_return += '<strong>Fields</strong><br>';
					fields = value["fields"];
					$.each(fields, function(key, value) {
						html_return += spacing + value;

						if (value[0] == "custom_field")
							html_return += ' (' + value[1] + ')';

						html_return += '<br>';
					});

					html_return += '<strong>Filters</strong><br>';
					filters = value["filters"];
					$.each(filters, function(key, value) {

						html_return += spacing + value[0];

						if (value[0] == "post_status")
						{
							html_return += ' = ' + value[1];
						} else if (value[0] == "custom_field") {
							html_return += ' - ' + value[1];
							html_return += ' (' + value[2] + ')';
							html_return += ' ' + value[3];
							html_return += ' "' + value[4];
							html_return += '" and "' + value[5] + '"';
						} else {
							html_return += ' ' + value[1];
							html_return += ' ' + value[2];
						}

						html_return += '<br>';

					});

				}

			});

			return html_return;

		}


		function apply_preset(preset_name)
		{
			//FORCE THE NO RELOAD FLAG (WILL BE ENABLED IN THE END OF THIS FUNCTION)
			flag_update_record_list_enabled = 0;

			//CLEAR ALL CURRENT OPTIONS
			$('.btn_remove_data_block').click();

			$.each(json_presets, function(key, value) {

				if (value["name"] == preset_name)
				{

					post_types = value["post_types"];
					$.each(post_types, function(key, value) {
						$('.container_post_types .btn_add_data_block').click()
						$('.container_post_types .post_type_selector:last').val(value);
					});
					$('.container_post_types .btn_remove_data_block:first').click();

					fields = value["fields"];
					$.each(fields, function(key, value) {
						$('.container_fields .btn_add_data_block').click()
						$('.container_fields .fields_selector:last').val(value);
						$('.container_fields .fields_selector:last').change();

						if (value[0] == "custom_field")
							$('.container_fields .column_custom_name_selector:last').val(value[1]);
					});
					$('.container_fields .btn_remove_data_block:first').click();

					filters = value["filters"];
					$.each(filters, function(key, value) {
						$('.container_filters .btn_add_data_block').click()
						$('.container_filters .filter_field:last').val(value[0]);
						$('.container_filters .filter_field:last').change();

						if (value[0] == "post_status")
						{
							$('.container_filters .filter_post_status:last').val(value[1]);
						} else if (value[0] == "custom_field") {
							$('.container_filters .filter_custom_name_selector:last').val(value[1]);
							$('.container_filters .filter_custom_type_selector:last').val(value[2]);
							$('.container_filters .filter_rule:last').val(value[3]);
							$('.container_filters .filter_rule:last').change();
							$('.container_filters .filter_value_1:last').val(value[4]);
							$('.container_filters .filter_value_2:last').val(value[5]);
						} else {
							$('.container_filters .filter_rule:last').val(value[1]);
							$('.container_filters .filter_value_1:last').val(value[2]);
						}

					});
					$('.container_filters .btn_remove_data_block:first').click();

				}


			});

			//ENABLE THE RELOADING OF THE RECORDS AGAIN
			flag_update_record_list_enabled = 1;

			//FORCE THE REFRESH NOW WITH THE PRESET LOADED
			update_record_list();

		}







		bind_fields();

	});

