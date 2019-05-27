<?php

$layout = 'horizontal';

echo form_open(current_url(), ['class' => 'form-horizontal']);


$fields = [];


$field = 'current_password';
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'lg',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_password([
		'autofocus' => TRUE,
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
	]),
]);


$field = 'new_password_1';
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'lg',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_password([
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
	]),
]);


$field = 'new_password_2';
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'lg',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_password([
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
	]),
]);


echo form_fieldset([
	'title' => lang('user_password_fieldset_password'),
	'content' => implode("\n", $fields),
]);


$submit_button = form_button([
	'type' => 'submit',
	'content' => lang('user_password_action_change'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);


echo form_close();

