
	<script>

		json_presets = <?php echo $json_presets?>;

	</script>

	<div style="border:0px solid #CCCCCC; width:820px;">

		<div class="tabbable">
			<ul class="nav nav-tabs" style="margin-bottom:0px;" id="eep_tab_menu">
				<li class="active"><a href="#tab1">Step 1 : Select the Records</a></li>
				<li><a href="#tab2">Step 2 : Export Options</a></li>
				<li><a href="#tab3">Step 3 : Download the files</a></li>
			</ul>
			<div class="tab-content" style="padding:10px; border-bottom:1px solid #CCCCCC; border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">
				<div class="tab-pane active" id="tab1">
					<form name="frm_query_builder" id="frm_query_builder" method="post" action="">

						<div id="div_load_preset" style="display:none;">
						<?php if ( ! empty( $presets ) && is_array( $presets ) ) : ?>
							<p>
								<select class="cmb_select_preset" name="cmb_select_preset" id="cmb_select_preset">
									<option value="">Select a preset</option>
									<?php foreach ($presets as $preset):?>
									<option value="<?php echo $preset["name"]?>"><?php echo $preset["name"]?></option>
									<?php endforeach;?>
								</select>
								<a class="btn btn-small btn_add_data_block btn_apply_preset" href="#" title="Apply Preset"><i class="icon-ok"></i> Apply Preset</a>
								<a class="btn btn-small btn_add_data_block btn_delete_preset" href="#" title="Delete Preset"><i class="icon-minus"></i> Delete Preset</a>
								<div id="load_preset_configuration"></div>
							</p>
						<?php endif; ?>
						</div>

						<div id="div_save_preset" style="display:none;">
							<p>
								<input type="text" value="" name="preset_name" id="preset_name" class="preset_name input-medium" placeholder="Preset Name">
								<br>
								<a class="btn btn-small btn_add_data_block btn_save_preset_execute" href="#" title="Save Preset"><i class="icon-ok"></i> Save Preset</a>
								<div id="save_preset_configuration"></div>
							</p>
						</div>

						<div class="preset_buttons">
							<a class="btn btn-small btn-block btn_add_data_block thickbox btn_load_preset" href="#TB_inline?width=600&height=400&inlineId=div_load_preset" title="Load Preset"><i class="icon-folder-open"></i> Load Preset</a>
							<a class="btn btn-small btn-block btn_add_data_block thickbox btn_save_preset" href="#TB_inline?width=600&height=400&inlineId=div_save_preset"><i class="icon-plus-sign"></i> Save Preset</a>
						</div>

						<input type="hidden" value="" id="record_count" name="record_count">

						<div class="pagination-centered">
							<a class="btn step_1 next_step_button" href="#" rel="1">Proceed to Step 2 - Export Options <i class="icon-arrow-right"></i></a>
						</div>

						<fieldset class="parameter_container container_post_types" style="position:relative">
							<legend>Post Types</legend>

							<div class="data_block">

								<a class="btn btn-small btn_remove_data_block" href="#"><i class="icon-minus-sign"></i></a>
								<select name="post_type[]" class="post_type_selector">
									<option value="">Select a Post Type</option>
									<optgroup label="Wordpress Native Post Types">
								<?php

$flag_custom_post_type_title = 0;
foreach ($post_types as $post_type):
	if ($post_type->_builtin == "" && $flag_custom_post_type_title == 0) {
		echo '<optgroup label="Custom Post Types">';
		$flag_custom_post_type_title = 1;
	}
?>
									<option class="opt_posttype" id="post_type_<?php echo $post_type->name?>" value="<?php echo $post_type->name?>"> <?php echo $post_type->labels->name?></option>
								<?php endforeach;?>
								</select>

							</div>
							<a class="btn btn-small btn_add_data_block" href="#"><i class="icon-plus-sign"></i> Add Post Type</a>

							<!--
							<div class="alert pull-right alert-success" style="position:absolute; top:50px; right:10px; width:280px">
								First select a Post Type. You can select <strong>System PostTypes</strong> like common <strong>Posts</strong> or <strong>Pages</strong> or <strong>Custom PostTypes</strong> ones created by themes and plugins.
							</div>

							<a style="position:absolute; top:50px; right:210px; width:280px" href="" id="xxx" data-color="green" rel="popover" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."></a>
							-->

						</fieldset>

						<fieldset class="parameter_container container_fields">
							<legend>Fields</legend>

							<?php foreach (array('ID', 'post_name', 'post_title', 'post_date') as $default_column):?>
							<div class="data_block">
								<a class="btn btn-small btn_remove_data_block" href="#"><i class="icon-minus-sign"></i></a>
								<select name="column[column_name][]" class="fields_selector">
									<option value="">Select a field</option>
									<optgroup label="Basic Columns">
									<?php foreach ($array_wp_columns as $column_name => $column_description):?>
									<option value="<?php echo $column_name?>" <?php if ($column_name == $default_column):?>selected<?php endif?>> <?php echo $column_description?></option>
									<?php endforeach;?>
									<optgroup label="Custom Columns">
									<?php foreach ($array_wp_custom_columns as $column_name => $column_description):?>
									<option value="<?php echo $column_name?>" <?php if ($column_name == $default_column):?>selected<?php endif?>> <?php echo $column_description?></option>
									<?php endforeach;?>
									<option value="custom_field"> Custom/Meta Field</option>
								</select>

								<select name="column[column_custom_name][]" class="column_custom_name_selector" style="display:none" >
									<option value="">Select a Custom/Meta field</option>
									<?php foreach ($meta_keys as $meta_key):?>
									<option value="<?php echo $meta_key->meta_key?>"> <?php echo $meta_key->meta_key?></option>
									<?php endforeach;?>
								</select>

								<!--<input type="text" value="" name="column[column_custom_name][]" class="column_custom_name_selector" style="display:none" placeholder="Name">-->
								<div style="clear:both"></div>
							</div>
							<?php endforeach;?>

							<a class="btn btn-small btn_add_data_block" href="#"><i class="icon-plus-sign"></i> Add Column</a>

						</fieldset>

						<fieldset class="parameter_container container_filters">
							<legend>Filters</legend>

							<div class="data_block">
								<a class="btn btn-small btn_remove_data_block" href="#"><i class="icon-minus-sign"></i></a>
								<select name="filter[filter_field][]" class="filter_selector filter_field input-medium">
									<option value="">Select a field</option>
									<optgroup label="Basic Columns">
									<?php foreach ($array_wp_filter_columns as $column_name => $column_description):?>
									<option value="<?php echo $column_name?>"> <?php echo $column_description?></option>
									<?php endforeach;?>
									<optgroup label="Special Fields">
										<option value="custom_field"> Custom Field</option>
								</select>

								<input type="text" value="" name="filter[filter_custom_name][]" class="filter_custom_name_selector input-medium" style="display:none;" placeholder="Name">

								<select name="filter[filter_custom_type][]" class="filter_custom_type_selector input-medium" style="display:none; width:100px;">
									<option>Select a Type</option>
									<option value="NUMERIC">Numeric</option>
									<option value="BINARY">Binary</option>
									<option value="CHAR">Char</option>
									<option value="DATETIME">DateTime</option>
									<option value="DECIMAL">Decimal</option>
									<option value="SIGNED">Signed</option>
									<option value="TIME">Time</option>
									<option value="UNSIGNED">Unsigned</option>
								</select>

								<select name="filter[filter_rule][]" class="filter_rule input-medium" style="width:120px;">
									<option>Select a Rule</option>
									<option value="=">=</option>
									<option value="!=">!=</option>
									<option value="<">&lt;</option>
									<option value="<=">&lt;=</option>
									<option value=">">&gt;</option>
									<option value=">=">&gt;=</option>
									<option value="LIKE">LIKE</option>
									<option value="NOT LIKE">NOT LIKE</option>
									<option value="IN">IN</option>
									<option value="NOT IN">NOT IN</option>
									<option value="BETWEEN">BETWEEN</option>
									<option value="NOT BETWEEN">NOT BETWEEN</option>
									<option value="EXISTS">EXISTS</option>
									<option value="NOT EXISTS">NOT EXISTS</option>
								</select>

								<input style="" type="text" value="" name="filter[filter_value_1][]" class="filter_value_1 input-small" placeholder="Filter Value">
								<span style="display:none" class="filter_value_2_container">
									and
									<input type="text" value="" name="filter[filter_value_2][]" class="filter_value_2 input-small" placeholder="Filter Value">
								</span>

								<span class="filter_post_status_container" style="display:none;">
									<select name="filter[filter_field_status][]" class="filter_selector filter_post_status">
										<option>Select a Rule</option>
										<?php foreach ($array_wp_status as $column_name => $column_description):?>
										<option value="<?php echo $column_name?>"> <?php echo $column_description?></option>
										<?php endforeach;?>
									</select>
								</span>

								<div style="clear:both"></div>
							</div>

							<a class="btn btn-small btn_add_data_block" href="#"><i class="icon-plus-sign"></i> Add Filter</a>
						</fieldset>

						<fieldset class="parameter_container">
							<legend>Result Preview</legend>

							<div class="pagination-centered" style="padding-bottom:10px;">
								<a class="btn btn-small force_refresh_listing_button" href="#"><i class="icon-refresh"></i> Force Refresh</a>
							</div>

							<div id="record_result"></div>
							<div style="text-align:right;"><a class="thickbox" href="#TB_inline?width=600&height=400&inlineId=div_debug_sql">SQL Debug</a></div>
							<div id="div_debug_sql" style="display:none;"><p><div id="debug_sql"></div></p></div>

						</fieldset>

						<div style="clear:both"></div>

						<div class="pagination-centered">
							<a class="btn step_1 next_step_button" href="#" rel="1">Proceed to Step 2 - Export Options <i class="icon-arrow-right"></i></a>
						</div>

					</form>
				</div>
				<div class="tab-pane" id="tab2">
					<form name="frm_export_options" id="frm_export_options" method="post" action="">

						<div class="pagination-centered">
							<a class="btn step_2 next_step_button" href="#" rel="0"><i class="icon-arrow-left"></i> Go back to Step 1 - Select the Records</a>
							<a class="btn step_2 next_step_button" href="#" rel="2"> Proceed to Step 3 - Download Files <i class="icon-arrow-right"></i></a>
						</div>

						<div style="clear:both"></div>

						<fieldset class="parameter_container">
							<legend>Format</legend>
							<div style="padding-left:25px;">
								<ul>
									<li><input type="radio" name="rad_format" value="xlsx"> .xlsx (MS Excel 2007 and newer)</li>
									<li><input type="radio" name="rad_format" value="xls"> .xls (MS Excel 2005 and older)</li>
									<li><input type="radio" name="rad_format" value="csv"> .csv (Comma-separated values)</li>
								</ul>
							</div>
						</fieldset>

						<fieldset class="parameter_container">
							<legend>File Configuration</legend>
							<div style="padding-left:20px; padding-bottom:10px;">
								Split into multiples files with <input type="text" value="" name="split_file_records_number" id="split_file_records_number" class="input-mini"> records each
								<br>
								<span id="record_count_html"></span> record(s) to be exported.
								<br>
								<span id="file_count_html">1</span> file(s) will be generated
							</div>
						</fieldset>

						<fieldset class="parameter_container">
							<legend>Progress</legend>

							<div class="progress progress-striped" id="progress_bar_export">
								<div class="bar bar-success" style="width: 0%;"></div>
							</div>

							<div class="pagination-centered">
								<a class="btn form_export_button" href="#"><i class="icon-play"></i> Export Records</a>
							</div>

							<br>

							<div id="export_result" class="alert alert-block alert-success hidden" style="margin-left: 95px; width:550px;"></div>

						</fieldset>

						<div class="pagination-centered">
							<a class="btn step_2 next_step_button" href="#" rel="0"><i class="icon-arrow-left"></i> Go back to Step 1 - Select the Records</a>
							<a class="btn step_2 next_step_button" href="#" rel="2"> Proceed to Step 3 - Download Files <i class="icon-arrow-right"></i></a>
						</div>

					</form>
				</div>
				<div class="tab-pane" id="tab3">

					<div class="pagination-centered">
						<a class="btn step_3 next_step_button" href="#" rel="1"><i class="icon-arrow-left"></i> Go back to Step 2 - Export Options</a>
					</div>

					<br>
					<br>

					<div class="download_links"></div>
				</div>
			</div>
		</div>

		<div id="debug"></div>
