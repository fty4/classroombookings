<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function form_group($params = array())
{
	$defaults = array(
		'layout' => 'vertical',
		'size' => '',
		'left_class' => 'col-label col-md-12 col-4',
		'right_class' => 'col-input col-md-12 col-8',
		'group_class' => 'form-group',
		'required_class' => 'required',
		'required' => FALSE,
		'label' => '',
		'label_class' => 'form-label',
		'label_for' => '',
		'input' => '',
		'hint' => '',
		'hint_class' => 'form-hint',
		'error_class' => 'has-error',
		'field' => '',
	);

	$data = array_merge($defaults, $params);

	$sizes = [
		'none' => 'col-auto',
		'auto' => 'col-auto',
		'xs' => 'col-md-6 col-lg-4 col-2',
		'sm' => 'col-md-6 col-lg-5 col-3',
		'md' => 'col-md-9 col-lg-6 col-4',
		'lg' => 'col-md-12 col-lg-7 col-5',
		'xl' => 'col-md-12 col-lg-8 col-6',
	];

	if (strlen($data['size']) && array_key_exists($data['size'], $sizes)) {
		$data['right_class'] = 'col-input ' . $sizes[$data['size']];
	}

	// No specific label_for attr and we have a field ref? Use that for label.
	if (empty($data['label_for']) && ! empty($data['field'])) {
		$data['label_for'] = $data['field'];
	}

	$label_el = '';
	$hint_el = '';
	$input_el = '';
	$error_el = '';
	$group_el = '';

	if ($data['required']) {
		$data['group_class'] .= " {$data['required_class']}";
	}

	$error = form_error($data['field'], '', '');
	if ($error) {
		$data['group_class'] .= " {$data['error_class']}";
		$error_el = "<div class='form-input-hint'>{$error}</div>";
	}

	if (strlen($data['label'])) {
		$label_el =  form_label(html_escape($data['label']), $data['label_for'], array('class' => $data['label_class']));
	}

	if (strlen($data['input'])) {
		$input_el = $data['input'];
	}

	if (strlen($data['hint'])) {
		$hint_el = "<div class='{$data['hint_class']}'>{$data['hint']}</div>";
	}

	switch ($data['layout']) {

		case 'horizontal':

			$left = "<div class='{$data['left_class']}'>\n";
			$left .= $label_el . "\n";
			$left .= $hint_el . "\n";
			$left .= "</div>";

			$right = "<div class='{$data['right_class']}'>\n";
			$right .= $input_el . "\n";
			$right .= $error_el . "\n";
			$right .= "</div>";

			$group_el = "<div class='{$data['group_class']}'>\n";
			$group_el .= $left;
			$group_el .= $right;
			$group_el .= "</div>\n";

		break;

		case 'vertical':

			$group_el = "<div class='{$data['group_class']}'>\n";
			$group_el .= $label_el . "\n";
			$group_el .= $hint_el . "\n";
			$group_el .= $input_el . "\n";
			$group_el .= $error_el . "\n";
			$group_el .= "</div>\n";

		break;
	}

	return $group_el;

}
