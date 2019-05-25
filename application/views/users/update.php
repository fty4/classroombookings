<?php

$layout = 'horizontal';

echo form_open(current_url(), ['class' => 'form-horizontal']);


// Account
//


$fields = [];

$field = 'username';
$value = set_value($field, get_property($field, $user), FALSE);
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

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


$field = 'authlevel';
$value = set_value($field, get_property($field, $user), FALSE);
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_radio_list([
		'name' => $field,
		'value' => $value,
		'options' => $authlevel_options,
		'style' => 'stacked',
	]),
]);


$field = 'enabled';
$value = set_value($field, get_property($field, $user), FALSE);
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");
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


$field = 'email';
$value = set_value($field, get_property($field, $user), FALSE);
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xl',
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


echo form_fieldset([
	'title' => lang('users_update_fieldset_account'),
	'content' => implode("\n", $fields),
]);


// Personal details
//


$fields = [];


$field = 'department_id';
$value = set_value($field, get_property($field, $user), FALSE);
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'lg',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_dropdown($field, $departments, $value, 'class="form-select"'),
]);


$field = 'firstname';
$value = set_value($field, get_property($field, $user), FALSE);
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
$value = set_value($field, get_property($field, $user), FALSE);
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
$value = set_value($field, get_property($field, $user), FALSE);
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
$value = set_value($field, get_property($field, $user), FALSE);
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


echo form_fieldset([
	'title' => lang('users_update_fieldset_personal'),
	'content' => implode("\n", $fields),
]);



// Password
//

if ( ! $user) {

	$new_password = random_string('alnum', 8);

	$fields = [];

	$field = 'set_password_1';
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


	$field = 'set_password_2';
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

}


$submit_button = form_button([
	'type' => 'submit',
	'content' => empty($user) ? lang('users_action_add') : lang('users_action_update'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);



echo form_close();
