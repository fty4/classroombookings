<?php

$layout = 'vertical';

echo form_open(current_url(), ['class' => 'form-vertical']);


$fields = [];


$field = 'email';
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");
$value = set_value($field, $user->$field, FALSE);

$fields[] = form_group([
	'layout' => $layout,
	'size' => '',
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


$field = 'firstname';
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");
$value = set_value($field, $user->firstname, FALSE);

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
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");
$value = set_value($field, $user->lastname, FALSE);

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
$label = lang("user_field_{$field}");
$hint = lang("user_field_hint_{$field}");
$value = set_value($field, $user->displayname, FALSE);

$fields[] = form_group(array(
	'layout' => $layout,
	'size' => 'xl',
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



$fields[] = form_group([
	'input' => form_button([
		'type' => 'submit',
		'content' => lang('action_save'),
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
