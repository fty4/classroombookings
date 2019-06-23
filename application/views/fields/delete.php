<?php

echo form_open();
echo form_hidden('field_id', $custom_field->field_id);

$data = [
	'title' => lang('fields_delete_title') . ' ' . html_escape($custom_field->title),
	'description' => lang('fields_delete_description'),
	'icon' => 'alert-triangle',
	'class' => 'empty-danger',
	'action' => form_button([
		'type' => 'submit',
		'content' => sprintf(lang('fields_delete_action'), html_escape($custom_field->title)),
		'class' => 'btn btn-primary btn-negative',
		'tabindex' => tab_index(),
		'data-confirm' => lang('action_confirm'),
		'name' => 'action',
		'value' => 'delete',
	]),
];

$this->load->view('partials/empty', $data);

echo form_close();
