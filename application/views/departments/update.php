<?php

$layout = 'horizontal';

echo form_open(current_url(), ['class' => 'form-horizontal']);


// Details
//


$fields = [];

$field = 'name';
$value = set_value($field, get_property($field, $department), FALSE);
$label = lang("department_field_{$field}");
$hint = lang("department_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'md',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input([
		'autofocus' => TRUE,
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


$field = 'description';
$value = set_value($field, get_property($field, $department), FALSE);
$label = lang("department_field_{$field}");
$hint = lang("department_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xl',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_textarea([
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'rows' => 4,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);




$field = 'colour';
$value = set_value($field, get_property($field, $department), FALSE);
$label = lang("department_field_{$field}");
$hint = lang("department_field_hint_{$field}");

$colour_input = form_colour_picker([
	'name' => $field,
	'value' => $value,
]);

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'lg',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => $colour_input,
]);


$field = 'icon';
$value = set_value($field, get_property($field, $department), FALSE);
$label = lang("department_field_{$field}");
$hint = lang("department_field_hint_{$field}");

$colour_input = form_icon_picker([
	'items' => $icons,
	'class' => 'icon-department',
	'name' => $field,
	'value' => $value,
]);

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => $colour_input,
]);

echo form_fieldset([
	'title' => lang('departments_update_fieldset_details'),
	'content' => implode("\n", $fields),
]);





$submit_button = form_button([
	'type' => 'submit',
	'content' => empty($department) ? lang('departments_action_add') : lang('departments_action_update'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);



echo form_close();
