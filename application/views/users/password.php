<?php

$layout = 'horizontal';

echo form_open(current_url(), ['class' => 'form-horizontal']);

$new_password = random_string('alnum', 8);

// Password
//

$fields = [];



$field = 'new_password_1';
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'lg',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input([
		'class' => 'form-input font-mono',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $new_password,
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
	'input' => form_input([
		'class' => 'form-input font-mono',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $new_password,
	]),
]);


echo form_fieldset([
	'title' => lang('users_update_fieldset_password'),
	'subtitle' => lang('users_update_fieldset_password_hint'),
	'content' => implode("\n", $fields),
]);


$submit_button = form_button([
	'type' => 'submit',
	'content' => lang('users_action_change_password'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);



echo form_close();
