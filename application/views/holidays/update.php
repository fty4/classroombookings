<?php

$layout = 'horizontal';

echo form_open(current_url(), ['class' => 'form-horizontal']);

echo form_hidden('year_id', $year->year_id);


// Details
//


$fields = [];

$field = 'year';
$value = $year->name;
$label = lang("holiday_field_{$field}");
$hint = lang("holiday_field_hint_{$field}");

$year_range = nice_date($year->date_start, 'D j F Y') . ' - ' . nice_date($year->date_end, 'D j F Y');
$input_after = "<h6 class='mb-0'>{$year->name}</h6><small>{$year_range}</small>";

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => $input_after,
]);


$field = 'name';
$value = set_value($field, get_property($field, $holiday), FALSE);
$label = lang("holiday_field_{$field}");
$hint = lang("holiday_field_hint_{$field}");

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


$field = 'date_start';
$holiday_value = get_property($field, $holiday);
$value = set_value($field, $holiday_value, FALSE);
$label = lang("holiday_field_{$field}");
$hint = lang("holiday_field_hint_{$field}");

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
$holiday_value = get_property($field, $holiday);
$value = set_value($field, $holiday_value, FALSE);
$label = lang("holiday_field_{$field}");
$hint = lang("holiday_field_hint_{$field}");

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
	'title' => lang('holidays_update_fieldset_details'),
	'subtitle' => lang('holidays_update_fieldset_hint_details'),
	'content' => implode("\n", $fields),
]);





$submit_button = form_button([
	'type' => 'submit',
	'content' => empty($holiday) ? lang('holidays_action_add') : lang('holidays_action_update'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);



echo form_close();
