<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'name',
		'label' => 'lang:room_field_name',
		'rules' => 'trim|required|max_length[20]',
	],
	[
		'field' => 'bookable',
		'label' => 'lang:period_field_bookable',
		'rules' => 'trim|required|is_natural',
	],
	[
		'field' => 'user_id',
		'label' => 'lang:period_field_user_id',
		'rules' => 'is_natural_no_zero',
	],
];
