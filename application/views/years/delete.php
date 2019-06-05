<?php

echo form_open();
echo form_hidden('year_id', $year->year_id);

$data = [
	'title' => lang('years_delete_title'),
	'description' => lang('years_delete_description'),
	'icon' => 'alert-triangle',
	'class' => 'empty-danger',
	'action' => form_button([
		'type' => 'submit',
		'content' => sprintf(lang('years_delete_action'), html_escape($year->name)),
		'class' => 'btn btn-primary btn-negative',
		'tabindex' => tab_index(),
		'data-confirm' => lang('action_confirm'),
		'name' => 'action',
		'value' => 'delete',
	]),
];


$this->load->view('partials/empty', $data);

echo form_close();
