<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function field($validation, $database = NULL, $last = ''){
	$value = (isset($validation)) ? $validation : ( (isset($database)) ? $database : $last);
	return $value;
}




function iconbar($items = array()) {

	$html = "<p class='iconbar'>";
	$i = 1;
	$max = count($items);

	foreach ($items as $item) {
		list($link, $name, $icon) = $item;
		$html .= img("assets/images/ui/{$icon}", FALSE, "alt='{$name}' align='top' hspace='0' border='0'");
		$html .= ' ' . anchor($link, $name);
		if ($i < $max) {
			$html .= img("assets/images/sep.gif", FALSE, "alt='|' align='top' hspace='0' border='0' style='margin:0px 6px;'");
		}
		$i++;
	}

	$html .= "</p>";

	return $html;
}


function tab_index($reset = NULL)
{
	static $_tab_index;

	if ( ! strlen($_tab_index) || $_tab_index === 0)
	{
		$_tab_index = 0;
	}
	else
	{
		$_tab_index++;
	}

	return $_tab_index;
}




function msgbox($type = 'error', $content = '', $escape = TRUE)
{
	if ($escape)
	{
		$content = html_escape($content);
	}

	$html = "<p class='msgbox {$type}'>{$content}</p>";
	return $html;
}



function icon($name, $attributes = array())
{
	$CI =& get_instance();
	return $CI->feather->get($name, $attributes, FALSE);
}



function colour_widget($params = array())
{

	$defaults = array(
		'name' => '',
		'tabindex' => '',
		'value' => '',
	);

	$data = array_merge($defaults, $params);

	$out = form_input(array(
		'name' => $data['name'],
		'id' => $data['name'],
		'size' => '7',
		'maxlength' => '7',
		'tabindex' => $data['tabindex'],
		'value' => $data['value'],
	));

	return $out;

}




function table_box($params = [])
{
	$CI =& get_instance();
	$CI->load->library('parser');

	$defaults = [
		'class' => '',
		'title' => '',
		'subtitle' => '',
		'table' => '',
	];

	$data = array_merge($defaults, $params);

	$vars = [
		'title' => '',
		'subtitle' => '',
		'class' => $data['class'],
		'table' => $data['table'],
		'pagination' => '',
	];

	if (strlen($data['title'])) {
		$vars['title'] = "<h3 class='table-box-title'>{$data['title']}</h3>";
	}

	if (strlen($data['subtitle'])) {
		$vars['subtitle'] = "<p class='table-box-subtitle'>{$data['subtitle']}</p>";
	}

	$pagination = $CI->pagination->create_links();
	if (strlen($pagination)) {
		$vars['pagination'] = "<div class='table-box-pagination flex-centered'>{$pagination}</div>";
	}

	$template = "<div class='table-box {class}'>\n";

	if (strlen($vars['title']) || strlen($vars['subtitle'])) {
		$template .= "<div class='table-box-header'>{title}\n{subtitle}\n</div>\n";
	}

	$template .= "<div class='table-box-table'>{table}</div>\n";
	$template .= "{pagination}\n";
	$template .= "</div>";

	$out = $CI->parser->parse_string($template, $vars, TRUE);
	return $out;
}
