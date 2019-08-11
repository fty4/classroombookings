<?php

$layout = 'horizontal';

$hidden = ['field_id' => get_property('field_id', $custom_field)];

echo form_open(current_url(), ['class' => 'form-horizontal', 'data-form' => 'field_update'], $hidden);


// Details
//


$fields = [];


$field = 'title';
$label = lang("field_field_{$field}");
$hint = lang("field_field_hint_{$field}");
$value = set_value($field, get_property($field, $custom_field), FALSE);

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


$field = 'entity';
$label = lang("field_field_{$field}");
$hint = lang("field_field_hint_{$field}");
$value = set_value($field, get_property($field, $custom_field), FALSE);

if (empty($custom_field)) {
	$input = form_radio_list([
		'name' => $field,
		'value' => $value,
		'options' => $entity_options,
		'style' => 'stacked',
	]);
} else {
	$input = '<span class="form-label text-gray">' . FieldHelper::entity_label($custom_field) . '</span>';
	$input .= form_hidden($field, $value);
	$hint = '';
}

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => $input,
]);



$field = 'type';
$label = lang("field_field_{$field}");
$hint = lang("field_field_hint_{$field}");
$value = set_value($field, get_property($field, $custom_field), FALSE);

if (empty($custom_field)) {
	$input = form_radio_list([
		'name' => $field,
		'value' => $value,
		'options' => $type_options,
		'style' => 'stacked',
	]);
} else {
	$input = '<span class="form-label text-gray">' . FieldHelper::type_label($custom_field) . '</span>';
	$input .= form_hidden($field, $value);
	$hint = '';
}

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => $input,
]);


$field = 'options';
$label = lang("field_field_{$field}");
$hint = lang("field_field_hint_{$field}");

if (empty($custom_field)) {
	$value = set_value($field, NULL, FALSE);
} else {
	$options = get_property($field, $custom_field);
	$value = '';
	foreach ($options as $id => $item) {
		$value .= "{$id}={$item}\n";
	}
}

$style = '';
if ( ! $custom_field || $custom_field->type !== 'select') {
	$style = "display:none";
}
$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'group_attrs' => "style='{$style}'",
	'input' => form_textarea([
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'rows' => 10,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);



$field = 'required';
$value = set_value($field, get_property($field, $custom_field), FALSE);
$label = lang("field_field_{$field}");
$hint = lang("field_field_hint_{$field}");
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


$field = 'hint';
$label = lang("field_field_{$field}");
$hint = lang("field_field_hint_{$field}");
$value = set_value($field, get_property($field, $custom_field), FALSE);

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xl',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_textarea([
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'rows' => 2,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


$field = 'position';
$label = lang("field_field_{$field}");
$hint = lang("field_field_hint_{$field}");
$position = isset($position) ? $position : 0;
$value = set_value($field, get_property($field, $custom_field, $position), FALSE);

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'xs',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_input([
		'min' => 0,
		'step' => 1,
		'class' => 'form-input',
		'name' => $field,
		'id' => $field,
		'tabindex' => tab_index(),
		'value' => $value,
	]),
]);


echo form_fieldset([
	'title' => lang('fields_update_fieldset_details'),
	'subtitle' => lang('fields_update_fieldset_hint_details'),
	'content' => implode("\n", $fields),
]);


// Rooms
//

$fields = [];

$field = 'room_ids';
$field_rooms = get_property('rooms', $custom_field, []);
$field_room_ids = array_column($field_rooms, 'room_id');
$value = set_value($field, $field_room_ids, FALSE);
$label = 'Rooms';
$hint = '';

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_check_list([
		'options' => $rooms,
		'columns' => 1,
		'name' => "{$field}",
		'value' => $value,
	]),
]);


echo form_fieldset([
	'title' => 'Applicable rooms',
	// 'subtitle' => 'Choose which custom fields for Bookings will be displayed when making bookings in this room.',
	'subtitle' => "This custom field can be turned on or off depending on the room being booked.\n\nSpecify which rooms will display this field when a booking is being made in it.",
	'content' => implode("\n", $fields),
	'attrs' => 'data-fieldset="rooms"',
]);




$submit_button = form_button([
	'type' => 'submit',
	'content' => empty($custom_field) ? lang('fields_action_add') : lang('fields_action_update'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);



echo form_close();
