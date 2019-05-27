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
];
