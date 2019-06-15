<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'name',
		'label' => 'lang:period_field_name',
		'rules' => 'trim|required|max_length[30]',
	],
	[
		'field' => 'time_start',
		'label' => 'lang:period_field_time_start',
		'rules' => 'trim|required|min_length[5]|max_length[8]',
	],
	[
		'field' => 'time_end',
		'label' => 'lang:period_field_time_end',
		'rules' => 'trim|required|min_length[5]|max_length[8]',
	],
	[
		'field' => 'bookable',
		'label' => 'lang:period_field_bookable',
		'rules' => 'trim|required|is_natural',
	],
	[
		'field' => 'day_1',
		'label' => 'lang:period_field_days',
		'rules' => 'trim|required|is_natural',
	],
	[
		'field' => 'day_2',
		'label' => 'lang:period_field_days',
		'rules' => 'trim|required|is_natural',
	],
	[
		'field' => 'day_3',
		'label' => 'lang:period_field_days',
		'rules' => 'trim|required|is_natural',
	],
	[
		'field' => 'day_4',
		'label' => 'lang:period_field_days',
		'rules' => 'trim|required|is_natural',
	],
	[
		'field' => 'day_5',
		'label' => 'lang:period_field_days',
		'rules' => 'trim|required|is_natural',
	],
	[
		'field' => 'day_6',
		'label' => 'lang:period_field_days',
		'rules' => 'trim|required|is_natural',
	],
	[
		'field' => 'day_7',
		'label' => 'lang:period_field_days',
		'rules' => 'trim|required|is_natural',
	],
];
