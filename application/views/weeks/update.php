<?php

$layout = 'horizontal';

echo form_open(current_url(), ['class' => 'form-horizontal']);


// Details
//


$fields = [];

$field = 'name';
$value = set_value($field, get_property($field, $week), FALSE);
$label = lang("week_field_{$field}");
$hint = lang("week_field_hint_{$field}");

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


$field = 'colour';
$value = set_value($field, get_property($field, $week), FALSE);
$label = lang("week_field_{$field}");
$hint = lang("week_field_hint_{$field}");

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


echo form_fieldset([
	'title' => lang('weeks_update_fieldset_details'),
	'content' => implode("\n", $fields),
]);


// Week dates
//


$fields = [];



$field = 'firstname';
$value = set_value($field, get_property($field, $week), FALSE);
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'md',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input([
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


$field = 'lastname';
$value = set_value($field, get_property($field, $week), FALSE);
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group(array(
	'layout' => $layout,
	'size' => 'md',
	'label' => $label,
	'hint' => $hint,
	'input' => form_input(array(
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	)),
));


$field = 'displayname';
$value = set_value($field, get_property($field, $week), FALSE);
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group(array(
	'layout' => $layout,
	'size' => 'lg',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input(array(
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	)),
));


$field = 'ext';
$value = set_value($field, get_property($field, $week), FALSE);
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group(array(
	'layout' => $layout,
	'size' => 'sm',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input(array(
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	)),
));


// echo form_fieldset([
// 	'title' => lang('users_update_fieldset_personal'),
// 	'content' => implode("\n", $fields),
// ]);





$submit_button = form_button([
	'type' => 'submit',
	'content' => empty($week) ? lang('weeks_action_add') : lang('weeks_action_update'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);



echo form_close();
