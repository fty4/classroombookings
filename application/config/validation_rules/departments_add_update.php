<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'name',
		'label' => 'lang:department_field_name',
		'rules' => 'trim|max_length[50]',
	],
	[
		'field' => 'description',
		'label' => 'lang:department_field_description',
		'rules' => 'trim|max_length[255]',
	],
	[
		'field' => 'colour',
		'label' => 'lang:department_field_colour',
		'rules' => 'trim|alpha_numeric|max_length[6]',
	],
	[
		'field' => 'icon',
		'label' => 'lang:department_field_icon',
		'rules' => 'trim|alpha_numeric|max_length[64]',
	],
];
