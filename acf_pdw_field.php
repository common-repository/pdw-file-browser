<?php

class acf_Pdwimage extends acf_Field
{
	
	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*
	*	@author Mattias Fjellvang
	*	@since 1.0.0
	*	@updated 1.0.1
	* 
	*-------------------------------------------------------------------------------------*/
	
	function __construct($parent)
	{
    	parent::__construct($parent);
    	
    	$this->name = 'pdwimage';
		$this->title = __("PDW Image select",'acf');
		
   	}
   

	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*
	*	@author Mattias Fjellvang
	*	@since 1.0.0
	*	@updated 1.0.1
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_field($field)
	{
		echo '<input type="text" value="' . $field['value'] . '" id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" /><a style="cursor:pointer;" onclick="openFileBrowser(\'' . $field['name'] . '\');">Select from PDW Filebrowser</a>';

		$format = isset($field['preview']) ? $field['preview'] : 'yes';
		
		if(!empty($field['value']) && $format == 'yes') {
			echo '<IMG src="' . $field['value'] . '" id="' . $field['name'] . '-preview" style="width:100%; height:200px;">';
		}
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_options
	*
	*	@author Mattias Fjellvang
	*	@since 1.0.0
	*	@updated 1.0.1
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_options($key, $field)
	{
		// defaults
		$field['default_value'] = isset($field['default_value']) ? $field['default_value'] : '';
		$field['preview'] = isset($field['preview']) ? $field['preview'] : 'yes';
		
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Default Value",'acf'); ?></label>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'text',
					'name'	=>	'fields['.$key.'][default_value]',
					'value'	=>	$field['default_value'],
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Preview",'acf'); ?></label>
				<p class="description"><?php _e("Define if preview of file/image should be shown",'acf'); ?></p>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][preview]',
					'value'	=>	$field['preview'],
					'choices' => array(
						'yes'	=>	__("Yes",'acf'),
						'no'	=>	__("No",'acf')
					)
				));
				?>
			</td>
		</tr>
		<?php
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value
	*
	*	@author Mattias Fjellvang
	*	@since 1.0.0
	*	@updated 1.0.1
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value($post_id, $field)
	{
		$value = parent::get_value($post_id, $field);
		
		$value = htmlspecialchars($value, ENT_QUOTES);
		
		return $value;
	}
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value_for_api
	*
	*	@author Mattias Fjellvang
	*	@since 1.0.0
	*	@updated 1.0.1
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value_for_api($post_id, $field)
	{
		$value = parent::get_value($post_id, $field);
		$value = html_entity_decode($value);
		
		return $value;
	}
	
}

?>