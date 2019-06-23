<?php

$layout = 'horizontal';

echo form_open_multipart(current_url(), ['class' => 'form-horizontal']);


// Details
//


$fields = [];


$field = 'name';
$value = set_value($field, get_property($field, $room), FALSE);
$label = lang("room_field_{$field}");
$hint = lang("room_field_hint_{$field}");

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


$field = 'user_id';
$value = set_value($field, get_property($field, $room), FALSE);
$label = lang("room_field_{$field}");
$hint = lang("room_field_hint_{$field}");

$fields[] = form_group([
	'layout' => $layout,
	'size' => 'lg',
	'field' => $field,
	'label' => $label,
	'hint' => $hint,
	'input' => form_dropdown($field, $users, $value, 'class="form-select"'),
]);


$field = 'bookable';
$value = set_value($field, get_property($field, $room, '1'), FALSE);
$label = lang("room_field_{$field}");
$hint = lang("room_field_hint_{$field}");
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
	'title' => lang('rooms_update_fieldset_details'),
	'subtitle' => lang('rooms_update_fieldset_hint_details'),
	'content' => implode("\n", $fields),
]);



// Photo
//

$fields = [];


$field = 'new_photo';
$label = lang("room_field_{$field}");
$hint = lang("room_field_hint_{$field}");
$max_size = sprintf(lang('max_file_size'), $max_size_human);

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
	'field' => 'new_photo',
	'label' => $label,
	'hint' => $hint,
	'input' => form_upload([
		'name' => 'userfile',
		'id' => 'userfile',
		'tabindex' => tab_index(),
		'value' => '',
		'class' => 'form-input',
	]) . "<span class='form-input-hint'>{$max_size}</span>",
]);

$photo = get_property('photo', $room);
if ($room && RoomHelper::has_photo($room)) {

	$field = 'current_photo';

	$fields[] = form_group([
		'layout' => $layout,
		'size' => 'md',
		'field' => 'current_photo',
		'label' => '',
		'input' => img('uploads/' . $room->photo, FALSE, "class='img-responsive'"),
	]);


	$field = 'delete_photo';
	$label = lang("room_field_{$field}");
	$hint = lang("room_field_hint_{$field}");
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

echo form_fieldset([
	'title' => lang('rooms_update_fieldset_photo'),
	'subtitle' => lang('rooms_update_fieldset_hint_photo'),
	'content' => implode("\n", $fields),
]);


// Custom fields
//

$custom_fields = empty($room) ? $custom_fields : $room->custom_fields;
if ( ! empty($custom_fields)) {

	$fields = [];

	foreach ($custom_fields as $custom_field) {

		$group_data = FieldHelper::custom_field_group($custom_field);
		$group_data['layout'] = $layout;

		$fields[] = form_group($group_data);

	}

	echo form_fieldset([
		'title' => 'Custom fields',
		'content' => implode("\n", $fields),
	]);

}




$submit_button = form_button([
	'type' => 'submit',
	'content' => empty($room) ? lang('rooms_action_add') : lang('rooms_action_update'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);



echo form_close();
