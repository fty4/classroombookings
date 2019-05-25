<?php

$layout = 'horizontal';

echo form_open_multipart(current_url(), ['class' => 'form-horizontal', 'data-form' => 'settings_visual']);


// Branding
//

$fields = [];


$field = 'new_logo';
$label = lang("settings_visual_field_{$field}");
$hint = lang("settings_visual_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => 'new_logo',
	'label' => $label,
	'hint' => $hint,
	'input' => form_upload([
		'name' => 'userfile',
		'id' => 'userfile',
		'tabindex' => tab_index(),
		'value' => '',
		'class' => 'form-input',
	])
]);

$logo = element('logo', $settings);
if ( ! empty($logo) && is_file(FCPATH . 'uploads/' . $logo)) {

	$field = 'current_logo';

	$fields[] = form_group([
		'layout' => $layout,
		'size' => 'md',
		'field' => 'current_logo',
		'label' => '',
		'input' => img('uploads/' . $logo, FALSE, "class='img-responsive'"),
	]);


	$field = 'delete_logo';
	$label = lang("settings_visual_field_{$field}");
	$hint = lang("settings_visual_field_hint_{$field}");
	$input = form_hidden($field, '0') . form_checkbox([
		'name' => $field,
		'id' => $field,
		'value' => '1',
	]);

	$fields[] = form_group([
		'layout' => $layout,
		'size' => '',
		'field' => $field,
		'input' => "<label class='form-checkbox'>{$input}<i class='form-icon'></i>{$label}</label>",
	]);
}



$field = 'theme';
$value = set_value($field, element($field, $settings), 'blue');
$label = lang("settings_visual_field_{$field}");
$hint = lang("settings_visual_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'md',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_dropdown($field, $theme_options, $value, 'class="form-select"'),
]);


echo form_fieldset([
	'title' => lang('settings_visual_section_branding'),
	'content' => implode("\n", $fields),
]);


// Bookings
//

$fields = [];


$field = 'displaytype';
$label = lang("settings_visual_field_{$field}");
$hint = lang("settings_visual_field_hint_{$field}");
$value = set_value($field, element($field, $settings), FALSE);

$options = [
	'day' => lang("settings_visual_field_{$field}_day"),
	'room' => lang("settings_visual_field_{$field}_room"),
];

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_radio_list([
		'name' => $field,
		'value' => $value,
		'options' => $options,
		'style' => 'stacked',
	]),
]);


$field = 'd_columns';
$label = lang("settings_visual_field_{$field}");
$hint = lang("settings_visual_field_hint_{$field}");
$value = set_value($field, element($field, $settings), FALSE);

$options = [
	'periods' => lang("settings_visual_field_{$field}_periods"),
	'rooms' => lang("settings_visual_field_{$field}_rooms"),
	'days' => lang("settings_visual_field_{$field}_days"),
];

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => lang("settings_visual_field_{$field}"),
	'hint' => lang("settings_visual_field_{$field}_hint"),
	'input' => form_radio_list([
		'name' => $field,
		'value' => $value,
		'options' => $options,
		'style' => 'stacked',
	]),
]);


echo form_fieldset([
	'title' => lang('settings_visual_section_bookings'),
	'subtitle' => lang('settings_visual_section_hint_bookings'),
	'content' => implode("\n", $fields),
]);


// Save
//

$submit_button = form_button([
	'type' => 'submit',
	'content' => lang('action_save'),
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
