<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'name',
		'label' => 'lang:settings_general_field_name',
		'rules' => 'required|trim|max_length[255]',
	],
	[
		'field' => 'website',
		'label' => 'lang:settings_general_field_website',
		'rules' => 'trim|prep_url|max_length[255]|valid_url',
	],
	[
		'field' => 'bia',
		'label' => 'lang:settings_general_field_bia',
		'rules' => 'required|integer',
	],
	[
		'field' => 'week_starts',
		'label' => 'lang:settings_general_field_week_starts',
		'rules' => 'required|is_natural_no_zero',
	],
];
