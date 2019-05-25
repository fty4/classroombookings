<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [

	// Account
	//

	[
		'field' => 'authlevel',
		'label' => 'lang:user_field_authlevel',
		'rules' => 'is_natural_no_zero',
	],
	[
		'field' => 'enabled',
		'label' => 'lang:user_field_enabled',
		'rules' => 'is_natural',
	],

	// Personal
	//

	[
		'field' => 'department_id',
		'label' => 'lang:user_field_department_id',
		'rules' => 'is_natural_no_zero',
	],
	[
		'field' => 'firstname',
		'label' => 'lang:user_field_firstname',
		'rules' => 'trim|max_length[50]',
	],
	[
		'field' => 'lastname',
		'label' => 'lang:user_field_lastname',
		'rules' => 'trim|max_length[50]',
	],
	[
		'field' => 'displayname',
		'label' => 'lang:user_field_displayname',
		'rules' => 'trim|max_length[50]',
	],
	[
		'field' => 'ext',
		'label' => 'lang:user_field_ext',
		'rules' => 'trim|max_length[50]',
	],

];
