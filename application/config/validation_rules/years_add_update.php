<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'name',
		'label' => 'lang:year_field_name',
		'rules' => 'trim|max_length[50]',
	],
	[
		'field' => 'date_start',
		'label' => 'lang:year_field_date_start',
		'rules' => 'trim|max_length[10]',
	],
	[
		'field' => 'date_end',
		'label' => 'lang:year_field_date_end',
		'rules' => 'trim|max_length[10]',
	],
];
