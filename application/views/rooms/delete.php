<?php

echo form_open();
echo form_hidden('room_id', $room->room_id);

$data = [
	'title' => lang('rooms_delete_title') . ' ' . html_escape($room->name),
	'description' => lang('rooms_delete_description'),
	'icon' => 'alert-triangle',
	'class' => 'empty-danger',
	'action' => form_button([
		'type' => 'submit',
		'content' => sprintf(lang('rooms_delete_action'), html_escape($room->name)),
		'class' => 'btn btn-primary btn-negative',
		'tabindex' => tab_index(),
		'data-confirm' => lang('action_confirm'),
		'name' => 'action',
		'value' => 'delete',
	]),
];

$this->load->view('partials/empty', $data);

echo form_close();
