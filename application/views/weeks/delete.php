<?php

echo form_open();
echo form_hidden('week_id', $week->week_id);

$data = [
	'title' => lang('weeks_delete_title'),
	'description' => lang('weeks_delete_description'),
	'icon' => 'alert-triangle',
	'class' => 'empty-danger',
	'action' => form_button([
		'type' => 'submit',
		'content' => sprintf(lang('weeks_delete_action'), html_escape($week->name)),
		'class' => 'btn btn-primary btn-negative',
		'tabindex' => tab_index(),
		'data-confirm' => lang('action_confirm'),
		'name' => 'action',
		'value' => 'delete',
	]),
];


$this->load->view('partials/empty', $data);

echo form_close();
