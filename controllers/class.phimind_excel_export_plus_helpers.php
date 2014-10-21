<?php 

	$wp_phimind_securitymanager_form_boolean = array();
	$wp_phimind_securitymanager_form_boolean[0] = array(1, "Yes");
	$wp_phimind_securitymanager_form_boolean[1] = array(0, "No");
	$GLOBALS["wp_phimind_securitymanager_form_boolean"] = $wp_phimind_securitymanager_form_boolean;

	class wp_phimind_excel_export_plus_helpers
	{

		public static function validate_form_submission($array_validation_fields, $post)
		{
			foreach ($array_validation_fields as $field)
			{
				if ($post[$field] == '')
					return 0;
			}
			return 1;
		}

		public static function format_elements($elements_array)
		{
			$array_temp = array();
			if (!empty($elements_array))
				foreach ($elements_array as $element)
					array_push($array_temp, array($element, $element));
			return $array_temp;
		}

		public static function concat_string_key(&$item, $key)
		{
			if ($item != '')
				$item = $key.'="'.$item.'"';
			else
				$item = $key;
		}

		public static function form_input($name, $options = array())
		{
			$type = @$options['type'];
			$value = @$options['value'];
			$elements = @$options['elements'];
			$id = @$options['id'];
			if ($id == '') 
				$id = $name;
			$label = @$options['label'];
			if ($label != '')
				$label = '<label for='.$id.'>'.$label.'</label>&nbsp;';

			if ($type == "checkbox")
				if (@$options['checked'] == "true" || @$options['checked'] == "1") 
					$checked = 'checked';
				else
					$checked = '';
			if (@$options['class']) 
				$class = @$options['class'];
			else
				$class = '';

			if (@$options["name"] != '')
				$name = $options["name"];

			//KILL THE OPTIONS ALREADY USED
			unset($options['name']);
			unset($options['class']);
			unset($options['value']);
			unset($options['id']);
			unset($options['label']);
			unset($options['checked']);
			unset($options['type']);
			unset($options['elements']);

			if (substr($name, -2) == "[]")
				$name = 'data['.substr($name, 0, -2).'][]';
			else
				$name = 'data['.$name.']';

			//MAKE A STRING WITH THE OPTIONS LEFT
			array_walk($options, 'self::concat_string_key');
			$options_string = implode(" " , $options);

			//CHECKBOXES
			//CHECKBOXES
			//CHECKBOXES
			//CHECKBOXES
			if ($type == 'checkbox')
			{
				$input_field = '<input type="'.$type.'" class="'.$class.'" '.$options_string.' value="'.$value.'" '.$checked.' name="'.$name.'" id="'.$id.'" />&nbsp;'.$label;
			}

			//TEXTBOXES
			//TEXTBOXES
			//TEXTBOXES
			//TEXTBOXES
			if ($type == 'text' || $type == 'button' || $type == '' || $type == 'hidden')
			{
				$input_field = $label.'<input type="'.$type.'" class="'.$class.'" '.$options_string.' value="'.$value.'" name="'.$name.'" id="'.$id.'" />';
			}

			//DATEPICKERS
			//DATEPICKERS
			//DATEPICKERS
			//DATEPICKERS
			//DATEPICKERS
			if ($type == 'date' || $type == '')
			{
				if ($value == '0000-00-00' || $value == '0000-00-00 00:00:00' || $value == '0000-00-00 00:00')
					$value = '';
				if (strlen($value) > 10)
					$value = substr($value , 0 , 10);

				$input_field = $label.'<input type="text" class="wplm_date_input" '.$options_string.' value="'.$value.'" name="'.$name.'" id="'.$id.'" />
				
				<script>
					set_date_field("'.$id.'");
				</script>
				
				';
			}
			
			//DATETIMEPICKERS
			//DATETIMEPICKERS
			//DATETIMEPICKERS
			//DATETIMEPICKERS
			if ($type == 'datetime' || $type == '')
			{
				if ($value == '0000-00-00 00:00:00' || $value == '0000-00-00 00:00')
					$value = '';

				$input_field = $label.'<input type="text" class="wplm_datetime_input" '.$options_string.' value="'.$value.'" name="'.$name.'" id="'.$id.'" />
				
				<script>
				jQuery(document).ready(function($) {
					set_date_field("'.$id.'");
				});
				</script>

				';
			}

			//SELECT BOX
			//SELECT BOX
			//SELECT BOX
			//SELECT BOX
			//SELECT BOX
			//SELECT BOX
			if ($type == 'select')
			{
				$input_field = $label.'<select class="'.$class.'" '.$options_string.' name="'.$name.'" id="'.$id.'">';
				if (!empty($elements))
				{
					foreach($elements as $element)
					{
						$selected = '';
						if (is_array($value))
						{
							if (array_search((string)$element[0], $value) !== false)
								$selected = 'selected';
						} else {
							if ($value == $element[0])
								$selected = 'selected';
						}

						$input_field .= '<option '.$selected.' value="'.$element[0].'">'.$element[1].'</option>';
					}
				}
				$input_field .= '</select>';
			}


			return $input_field;
		}

	}

?>