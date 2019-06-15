<?php

echo form_open();
echo form_hidden('period_id', $period->period_id);

$data = [
	'title' => lang('periods_delete_title') . ' ' . html_escape($period->name),
	'description' => lang('periods_delete_description'),
	'icon' => 'alert-triangle',
	'class' => 'empty-danger',
	'action' => form_button([
		'type' => 'submit',
		'content' => sprintf(lang('periods_delete_action'), html_escape($period->name)),
		'class' => 'btn btn-primary btn-negative',
		'tabindex' => tab_index(),
		'data-confirm' => lang('action_confirm'),
		'name' => 'action',
		'value' => 'delete',
	]),
];

$this->load->view('partials/empty', $data);

echo form_close();
