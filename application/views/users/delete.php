<?php

echo form_open();
echo form_hidden('user_id', $user->user_id);

$data = [
	'title' => lang('users_delete_title'),
	'description' => lang('users_delete_description'),
	'icon' => 'alert-triangle',
	'class' => 'empty-danger',
	'action' => form_button([
		'type' => 'submit',
		'content' => sprintf(lang('users_delete_action'), html_escape($user->username)),
		'class' => 'btn btn-primary btn-negative',
		'tabindex' => tab_index(),
		'data-confirm' => lang('action_confirm'),
		'name' => 'action',
		'value' => 'delete',
	]),
];


$this->load->view('partials/empty', $data);

echo form_close();
