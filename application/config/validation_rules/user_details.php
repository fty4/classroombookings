<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'firstname',
		'label' => 'lang:user_field_firstname',
		'rules' => 'trim|ucfirst|max_length[255]',
	],
	[
		'field' => 'lastname',
		'label' => 'lang:user_field_lastname',
		'rules' => 'trim|ucfirst|max_length[255]',
	],
	[
		'field' => 'displayname',
		'label' => 'lang:user_field_displayname',
		'rules' => 'trim|ucfirst|max_length[255]',
	],
	[
		'field' => 'authlevel',
		'label' => 'lang:user_field_authlevel',
		'rules' => 'required',
	],
	[
		'field' => 'enabled',
		'label' => 'lang:user_field_enabled',
		'rules' => 'required|is_natural',
	],
	[
		'field' => 'department_id',
		'label' => 'lang:user_field_department_id',
		'rules' => 'is_natural_no_zero',
	],
	[
		'field' => 'ext',
		'label' => 'lang:user_field_ext',
		'rules' => 'max_length[50]',
	],
];
