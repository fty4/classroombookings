<?php

$layout = 'horizontal';

echo form_open_multipart(current_url(), ['class' => 'form-horizontal', 'data-form' => 'import']);


// Source
//

$fields = [];

$field = 'userfile';
$label = lang("users_import_field_{$field}");
$hint = lang("users_import_field_hint_{$field}");
$hint .= lang('max_file_size') . ': ' . $max_size_human;

$guidance = "<p>Your CSV file should be in this format:</p>
	<pre><code>username, firstname, lastname, email, password</code></pre>
	<p>Header row is optional, it will be skipped if one is present. Any usernames that already exist will be ignored.</p>";

$fields[] = form_group([
	'layout' => $layout,
	'field' => $field,
	'size' => 'xl',
	'label' => $label,
	'hint' => $hint,
	'input' => form_upload([
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => '',
		'class' => 'form-input',
	])
]);

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'label' => '',
	'hint' => '',
	'input' => $guidance,
]);


echo form_fieldset([
	'title' => lang('users_import_section_source'),
	'content' => implode("\n", $fields),
]);


// Default values
//


$fields = [];



$field = 'password';
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
		'value' => '',
	]),
]);


$field = 'authlevel';
$value = set_value($field, TEACHER, FALSE);
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
$value = set_value($field, '1', FALSE);
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

echo form_fieldset([
	'title' => lang('users_import_section_default'),
	'subtitle' => lang('users_import_section_hint_default'),
	'content' => implode("\n", $fields),
]);


// Submit
//


$submit_button = form_button([
	'type' => 'submit',
	'content' => lang('users_import_action_create'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
	'_content' => form_group([
		'layout' => $layout,
		'group_class' => 'form-group',
		'input' => $submit_button,
	]),
]);
