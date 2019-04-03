<?php
$layout = 'vertical';
echo form_open('settings/options', ['class' => 'form-vertical']);


// School details
//
$fields = [];


$field = 'name';
$value = set_value($field, element('name', $settings), FALSE);

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xl',
	'field' => $field,
	'label' => lang("settings_general_field_{$field}"),
	'hint' => lang("settings_general_field_hint_{$field}"),
	'input' => form_input([
		'autofocus' => TRUE,
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


$field = 'website';
$value = set_value($field, element('website', $settings), FALSE);

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xl',
	'field' => $field,
	'label' => lang("settings_general_field_{$field}"),
	'hint' => lang("settings_general_field_hint_{$field}"),
	'input' => form_input([
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


echo form_fieldset([
	'title' => lang('settings_options_section_school'),
	'content' => implode("\n", $fields),
]);



// Booking preferences
//
$fields = [];


$field = 'bia';
$value = (int) set_value('bia', element('bia', $settings), FALSE);

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xs',
	'field' => $field,
	'label' => lang("settings_general_field_{$field}"),
	'hint' => lang("settings_general_field_hint_{$field}"),
	'input' => form_input([
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


echo form_fieldset([
	'title' => lang('settings_options_section_preferences'),
	'content' => implode("\n", $fields),
]);





// Login
//
$fields = [];


$field = 'login_hint';
$value = set_value($field, element($field, $settings), FALSE);

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xl',
	'field' => $field,
	'label' => lang("settings_general_field_{$field}"),
	'hint' => lang("settings_general_field_hint_{$field}"),
	'input' => form_textarea([
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'rows' => 3,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


echo form_fieldset([
	'title' => lang('settings_options_section_login'),
	'content' => implode("\n", $fields),
]);


$submit_button = form_button([
	'type' => 'submit',
	'content' => lang('action_save'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => form_group([
		'group_class' => 'form-group',
		'label' => '',
		'hint' => '',
		'input' => $submit_button,
	]),
]);



echo form_close();
