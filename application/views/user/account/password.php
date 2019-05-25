<?php

$layout = 'vertical';

echo form_open(current_url(), ['class' => 'form-vertical']);


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



$fields[] = form_group([
	'input' => form_button([
		'type' => 'submit',
		'content' => lang('user_password_action_change'),
		'class' => 'btn btn-primary ',
		'tabindex' => tab_index(),
	]),
]);


echo "<div class='columns'>";
echo "<div class='column col-xs-12 col-sm-12 col-md-8 col-lg-6 col-xl-6 col-6'>";
echo form_fieldset([
	'layout' => 'vertical',
	'content' => implode("\n", $fields),
]);
echo "</div></div>";



echo form_close();

