<?php

$layout = 'horizontal';

echo form_open(current_url(), ['class' => 'form-horizontal']);


// Details
//


$fields = [];


$field = 'name';
$value = set_value($field, get_property($field, $period), FALSE);
$label = lang("period_field_{$field}");
$hint = lang("period_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'lg',
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


$field = 'time_start';
$value = set_value($field, get_property($field, $period), FALSE);
$label = lang("period_field_{$field}");
$hint = lang("period_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xs',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input([
		'type' => 'time',
		'step' => 60 * 5,
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


$field = 'time_end';
$value = set_value($field, get_property($field, $period), FALSE);
$label = lang("period_field_{$field}");
$hint = lang("period_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xs',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input([
		'type' => 'time',
		'step' => 60 * 5,
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


$field = 'bookable';
$value = set_value($field, get_property($field, $period, '1'), FALSE);
$label = lang("period_field_{$field}");
$hint = lang("period_field_hint_{$field}");
$yes = lang('yes');

$input = form_hidden($field, '0') . form_checkbox([
	'name' => $field,
	'id' => $field,
	'value' => '1',
	'checked' => ($value == '1'),
]);

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => "<label class='form-checkbox'>{$input}<i class='form-icon'></i>{$yes}</label>",
]);


$field = 'days';
$label = lang("period_field_{$field}");
$hint = lang("period_field_hint_{$field}");

$inputs = [];
foreach ($days as $day_num) {
	$name = "day_{$day_num}";
	$title = lang("day_{$day_num}_long");
	$default = ($period == NULL) ? ($day_num < 6 ? '1' : '0') : get_property($name, $period);
	$value = set_value($name, $default);

	$hidden = form_hidden($name, '0');
	$check = form_checkbox([
		'name' => $name,
		'id' => $name,
		'value' => '1',
		'checked' => ($value == '1'),
	]);
	$inputs[] = "<label class='form-checkbox'>{$hidden}{$check}<i class='form-icon'></i>{$title}</label>";
}

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'lg',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => implode("\n", $inputs),
]);



echo form_fieldset([
	'title' => lang('periods_update_fieldset_details'),
	'subtitle' => lang('periods_update_fieldset_hint_details'),
	'content' => implode("\n", $fields),
]);



$submit_button = form_button([
	'type' => 'submit',
	'content' => empty($period) ? lang('periods_action_add') : lang('periods_action_update'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);



echo form_close();
