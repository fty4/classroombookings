<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function form_fieldset($params = [])
{
	$CI =& get_instance();
	$CI->load->library('parser');

	$defaults = [
		'layout' => 'horizontal',
		'actions' => FALSE,
		'class' => '',
		'left_class' => 'col-md-12 col-lg-4 col-xl-3 col-3',
		'right_class' => 'col-md-12 col-lg-8 col-xl-9 col-9',
		'title' => '',
		'subtitle' => '',
		'content' => '',
	];

	$data = array_merge($defaults, $params);

	if ($data['actions']) {
		$data['class'] .= 'form-fieldset-actions';
	}

	$data['class'] .= " form-fieldset-layout-{$data['layout']}";

	$vars = [
		'title' => '',
		'subtitle' => '',
		'class' => $data['class'],
		'content' => $data['content'],
		'left_class' => $data['left_class'],
		'right_class' => $data['right_class'],
	];

	if (strlen($data['title'])) {
		$vars['title'] = "<legend class='form-legend'>{$data['title']}</legend>";
	}

	if (strlen($data['subtitle'])) {
		$vars['subtitle'] = "<p class='form-legend-subtitle'>{$data['subtitle']}</p>";
	}

	if (empty($vars['title']) && empty($vars['subtitle']) /*&& $data['actions'] === FALSE*/) {

		$template = "<fieldset class='form-fieldset {class}'>";
		$template .= "{content}";
		$template .= "</fieldset>";

	} else {

		switch ($data['layout']) {
			case 'vertical':
				$template = "<fieldset class='form-fieldset {class}'>";
				$template .= "<div class='form-legend-wrapper'>{title}\n{subtitle}\n</div>";
				$template .= "<div class='form-content-wrapper'>{content}</div>";
				$template .= "</fieldset>";
			break;
			case 'horizontal':
				$template = "<fieldset class='form-fieldset {class}'>";
				$template .= "<div class='columns'>";
				$template .= "<div class='column {left_class}'>{title}{subtitle}</div>";
				$template .= "<div class='column {right_class}'>{content}</div>";
				$template .= "</div>";
				$template .= "</fieldset>";
			break;
		}
	}

	$out = $CI->parser->parse_string($template, $vars, TRUE);
	return $out;
}


function form_group($params = [])
{
	$defaults = array(
		'layout' => 'vertical',
		'size' => '',
		'left_class' => 'col-label col-md-12 col-lg-4 col-4',
		'right_class' => 'col-input col-md-12 col-lg-8 col-8',
		'group_class' => 'form-group',
		'required_class' => 'required',
		'required' => FALSE,
		'label' => '',
		'label_class' => 'form-label',
		'label_for' => '',
		'label_pos' => 'before',
		'input' => '',
		'hint' => '',
		'hint_class' => 'form-hint',
		'error_class' => 'has-error',
		'field' => '',
		'error_field' => '',
	);

	$data = array_merge($defaults, $params);

	$sizes = [
		'horizontal' => [
			'none' => 'col-auto',
			'auto' => 'col-auto',
			'xs' => 'col-2 col-xs-6 col-sm-8 col-lg-4 col-xl-4',
			'sm' => 'col-md-6 col-lg-5 col-3',
			'md' => 'col-md-9 col-lg-6 col-4',
			'lg' => 'col-md-12 col-lg-7 col-5',
			'xl' => 'col-md-12 col-lg-8 col-6',
		],
		'vertical' => [
			'none' => 'col-auto',
			'auto' => 'col-auto',
			'xs' => 'col-md-6 col-lg-4 col-2',
			'sm' => 'col-md-6 col-lg-6 col-4',
			'md' => 'col-md-9 col-lg-6 col-6',
			'lg' => 'col-md-12 col-lg-8 col-8',
			'xl' => 'col-md-12 col-lg-8 col-10',
		],
	];

/*	$sizes = [
		'none' => 'col-auto',
		'auto' => 'col-auto',
		'xs' => 'col-md-6 col-lg-4 col-2',
		'sm' => 'col-md-6 col-lg-5 col-3',
		'md' => 'col-md-9 col-lg-6 col-4',
		'lg' => 'col-md-12 col-lg-7 col-5',
		'xl' => 'col-md-12 col-lg-8 col-6',
	];*/


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

	$error_field = strlen($data['error_field']) ? $data['error_field'] : $data['field'];
	$error = form_error($error_field, '', '');
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

			if (strlen($data['size']) && array_key_exists($data['size'], $sizes['horizontal'])) {
				$data['right_class'] = 'col-input ' . $sizes['horizontal'][$data['size']];
			}

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

			$data['right_class'] = 'col-input';
			if (strlen($data['size']) && array_key_exists($data['size'], $sizes['vertical'])) {
				$data['right_class'] .= ' ' . $sizes['vertical'][$data['size']];
			}

			$label_before_el = '';
			$label_after_el = '';

			switch ($data['label_pos']) {
				case 'before': $label_before_el = $label_el; break;
				case 'after': $label_after_el = $label_el; break;
			}

			$group_el = "<div class='{$data['group_class']}' data-field='{$data['field']}'>\n";
			$group_el .= $label_before_el . "\n";
			$group_el .= $hint_el . "\n";
			$group_el .= "<div class='{$data['right_class']}'>\n{$input_el}\n</div>\n";
			$group_el .= $label_after_el . "\n";
			$group_el .= $error_el . "\n";
			$group_el .= "</div>\n";

		break;
	}

	return $group_el;

}


function form_radio_list($params = [])
{
	$defaults = [
		'options' => [],
		'name' => '',
		'value' => FALSE,
		'style' => 'stacked',
	];

	$data = array_merge($defaults, $params);

	$out = '';
	$inputs = array();

	foreach ($data['options'] as $value => $label) {

		$id = md5($data['name'].$value);
		$input = "<label class='form-radio'>";
		$input .= form_radio([
			'name' => $data['name'],
			'id' => $id,
			'value' => $value,
			'data-name' => $data['name'],
			'data-value' => $value,
			'checked' => ($value == $data['value'] && is_scalar($data['value'])),
			'tabindex' => tab_index(),
		]);
		$input .= "<i class='form-icon'></i> ";
		$input .= $label;
		$input .= "</label>";

		$inputs[] = $input;

	}

	switch ($data['style']) {

		case 'stacked':
			return implode("\n", $inputs);
		break;

		case 'inline':

			$out = '';
			$out .= "<div class='columns'>";
			foreach ($inputs as $el) {
				$out .= "<div class='column col-auto'>{$el}</div>";
			}
			$out .= "</div>";
			return $out;

		break;
	}

	return '';
}


function form_colour_picker($options = [])
{
	$defaults = [
		'name' => '',
		'value' => '',
		'attrs' => '',
		'random' => TRUE,	// if value empty, pick random colour
		'palette' => 'material',
		'item_template' => "<label class='colour-picker-item' for='{id}'>{input}</label>",
		'input_template' => "<input type='radio' name='{name}' value='{value}' id='{id}' {checked}><div class='colour-picker-icon' style='background-color:{colour}'><span></span></div>",
		'template' => "<div class='colour-picker' {attrs}>{items}</div>",
	];

	$data = array_merge($defaults, $options);

	// Format value so we can match it reliably
	$data['value'] = str_replace('#', '', $data['value']);
	$data['value'] = strtolower($data['value']);

	$palettes = [];

	$palettes['clrs'] = [
		'001f3f',
		'0074d9',
		'7fdbff',
		'39cccc',
		'3d9970',
		'2ecc40',
		'01ff70',
		'ffdc00',
		'ff851b',
		'ff4136',
		'f012be',
		'b10dc9',
		'85144b',
		'aaaaaa',
	];

	$palettes['material'] = [
		'e51c23',
		'e91e63',
		'9c27b0',
		'673ab7',
		'3f51b5',
		'5677fc',
		'03a9f4',
		'00bcd4',
		'009688',
		'259b24',
		'8bc34a',
		'cddc39',
		'ffeb3b',
		'ffc107',
		'ff9800',
		'ff5722',
		'795548',
		'9e9e9e',
		'607d8b',
	];

	$colours = $palettes[$data['palette']];

	if (empty($data['value'])) {
		$data['value'] = $colours[ array_rand($colours) ];
	}

	$CI =& get_instance();
	$CI->load->library('parser');

	// Build items
	$items = [];
	foreach ($colours as $colour) {

		$hex_colour = "#{$colour}";
		$checked = $colour == $data['value'] ? 'checked="checked"' : '';

		$vars = [
			'id' => "{$data['name']}_colour_{$colour}",
			'name' => $data['name'],
			'value' => $colour,
			'colour' => $hex_colour,
			'checked' => $checked,
		];

		$vars['input'] = $CI->parser->parse_string($data['input_template'], $vars, TRUE);

		$items[] = $CI->parser->parse_string($data['item_template'], $vars, TRUE) . "\n";
	}

	// Build final output

	$items_html = implode("\n", $items);

	$vars = [
		'attrs' => $data['attrs'],
		'items' => $items_html,
	];

	return $CI->parser->parse_string($data['template'], $vars, TRUE);
}
