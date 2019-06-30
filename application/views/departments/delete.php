<?php

echo form_open();
echo form_hidden('department_id', $department->department_id);

$data = [
	'title' => lang('departments_delete_title'),
	'description' => lang('departments_delete_description'),
	'icon' => 'alert-triangle',
	'class' => 'empty-danger',
	'action' => form_button([
		'type' => 'submit',
		'content' => sprintf(lang('departments_delete_action'), html_escape($department->name)),
		'class' => 'btn btn-primary btn-negative',
		'tabindex' => tab_index(),
		'data-confirm' => lang('action_confirm'),
		'name' => 'action',
		'value' => 'delete',
	]),
];


$this->load->view('partials/empty', $data);

echo form_close();
