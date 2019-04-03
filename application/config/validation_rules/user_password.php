<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'new_password_1',
		'label' => 'lang:user_field_new_password_1',
		'rules' => 'required|trim|min_length[8]',
	],
	[
		'field' => 'new_password_2',
		'label' => 'lang:user_field_new_password_2',
		'rules' => 'required|trim|min_length[8]',
	],
];
