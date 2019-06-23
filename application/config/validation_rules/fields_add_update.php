<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return [
	[
		'field' => 'title',
		'label' => 'lang:field_field_title',
		'rules' => 'trim|required|max_length[30]',
	],
	[
		'field' => 'entity',
		'label' => 'lang:field_field_entity',
		'rules' => 'trim|required|exact_length[2]',
	],
	[
		'field' => 'type',
		'label' => 'lang:field_field_type',
		'rules' => 'trim|required|max_length[64]',
	],
	[
		'field' => 'hint',
		'label' => 'lang:field_field_hint',
		'rules' => 'trim|max_length[255]',
	],
	[
		'field' => 'required',
		'label' => 'lang:field_field_required',
		'rules' => 'trim|required|is_natural',
	],
];
