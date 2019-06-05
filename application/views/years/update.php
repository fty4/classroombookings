<?php

$layout = 'horizontal';

echo form_open(current_url(), ['class' => 'form-horizontal']);


// Details
//


$fields = [];

$field = 'name';
$value = set_value($field, get_property($field, $year), FALSE);
$label = lang("year_field_{$field}");
$hint = lang("year_field_hint_{$field}");

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


$field = 'date_start';
$year_value = get_property($field, $year);
$value = set_value($field, $year_value, FALSE);
$label = lang("year_field_{$field}");
$hint = lang("year_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'md',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input([
		'type' => 'date',
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


$field = 'date_end';
$year_value = get_property($field, $year);
$value = set_value($field, $year_value, FALSE);
$label = lang("year_field_{$field}");
$hint = lang("year_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'md',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input([
		'type' => 'date',
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);




echo form_fieldset([
	'title' => lang('years_update_fieldset_details'),
	'content' => implode("\n", $fields),
]);





$submit_button = form_button([
	'type' => 'submit',
	'content' => empty($year) ? lang('years_action_add') : lang('years_action_update'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);



echo form_close();
