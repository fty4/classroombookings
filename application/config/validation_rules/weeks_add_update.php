<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'name',
		'label' => 'lang:week_field_name',
		'rules' => 'trim|max_length[20]',
	],
	[
		'field' => 'colour',
		'label' => 'lang:week_field_colour',
		'rules' => 'trim|alpha_numeric|max_length[6]',
	],
];
