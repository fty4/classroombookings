<?php

class FieldHelper
{


	use app\helpers\DataTrait;


	public static function entity_label($field, $other = '')
	{
		$items = get_instance()->fields_model->get_entities();
		$value = $field->entity;
		return array_key_exists($value, $items) ? $items[$value] : $other;
	}


	public static function type_label($field, $other = '')
	{
		$items = get_instance()->fields_model->get_types();
		$value = $field->type;
		return array_key_exists($value, $items) ? $items[$value] : $other;
	}


	/**
	 * Return the data array of form group + input configuration for a given custom field.
	 *
	 */
	public static function custom_field_group($field)
	{

		$name = "custom_fields[field_{$field->field_id}]";
		$value = set_value($name, get_property('value', $field), FALSE);
		$label = $field->title;
		$hint = $field->hint;

		$yes = lang('yes');

		switch ($field->type) {

			case 'text_single':

				$size = 'xl';
				$input = form_input([
					'class' => 'form-input',
					'name' => $name,
					'id' => $name,
					'tabindex' => tab_index(),
					'value' => $value,
					'maxlength' => 255,
				]);

			break;

			case 'text_multi':
				$size = 'xl';
				$input = form_textarea([
					'class' => 'form-input',
					'name' => $name,
					'id' => $name,
					'rows' => 5,
					'tabindex' => tab_index(),
					'value' => $value,
				]);
			break;

			case 'checkbox':

				$size = '';
				$hidden = form_hidden($name, '0');
				$check = form_checkbox([
					'name' => $name,
					'id' => $name,
					'value' => '1',
					'checked' => ($value == '1'),
					'tabindex' => tab_index(),
				]);
				$input = "{$hidden}<label class='form-checkbox'>{$check}<i class='form-icon'></i>{$yes}</label>";

			break;

			case 'select':
				$size = 'xl';
				$options = ['' => ''];
				foreach ($field->options as $k => $v) {
					$options["{$k}"] = html_escape($v);
				}
				$input = form_dropdown($name, $options, $value, 'class="form-select"');
			break;

		}

		return [
			'size' => $size,
			'field' => $name,
			'label' => $label,
			'hint' => $hint,
			'input' => $input,
		];
	}


}
