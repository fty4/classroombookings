<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'delete_logo',
		'label' => 'lang:settings_visual_field_delete_logo',
		'rules' => 'in_list[0,1]',
		'errors' => [
			'in_list' => 'Invalid value.',
		],
	],
	[
		'field' => 'userfile',
		'label' => 'lang:settings_visual_field_new_logo',
		'rules' => '',
	],
	[
		'field' => 'theme',
		'label' => 'lang:settings_visual_field_theme',
		'rules' => 'alpha',
	],
	[
		'field' => 'displaytype',
		'label' => 'lang:settings_visual_field_displaytype',
		'rules' => 'alpha',
	],
	[
		'field' => 'd_columns',
		'label' => 'lang:settings_visual_field_d_columns',
		'rules' => 'alpha',
	],
];
