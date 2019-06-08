<?php

echo form_open();
echo form_hidden('holiday_id', $holiday->holiday_id);

$data = [
	'title' => lang('holidays_delete_title'),
	'description' => lang('holidays_delete_description'),
	'icon' => 'alert-triangle',
	'class' => 'empty-danger',
	'action' => form_button([
		'type' => 'submit',
		'content' => sprintf(lang('holidays_delete_action'), html_escape($holiday->name)),
		'class' => 'btn btn-primary btn-negative',
		'tabindex' => tab_index(),
		'data-confirm' => lang('action_confirm'),
		'name' => 'action',
		'value' => 'delete',
	]),
];

$this->load->view('partials/empty', $data);

echo form_close();
