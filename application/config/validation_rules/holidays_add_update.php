<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'name',
		'label' => 'lang:holiday_field_name',
		'rules' => 'trim|required|max_length[50]',
	],
];
