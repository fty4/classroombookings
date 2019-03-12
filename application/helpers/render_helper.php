<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
function render_user_menu()
{
	$out = '';

	$CI =& get_instance();
	$CI->load->model('menu_model');

	$items = $CI->menu_model->get_user_menu();

	foreach ($items as $item)
	{
		if ( ! $item['visible']) {
			continue;
		}

		$icon = icon($item['icon']);
		$label = html_escape($item['label']);
		$url = $item['url'];

		$out .= "<a class='btn btn-link' href='{$url}'>{$icon}<span>{$label}</span></a>\n";
	}

	return $out;
}


function render_main_menu()
{
	$out = '';

	$CI =& get_instance();
	$CI->load->model('menu_model');

	$items = $CI->menu_model->get_main_menu();

	foreach ($main_menu as $item)
	{
		if ( ! $item['visible']) {
			continue;
		}
		$icon = icon($item['icon']);
		$label = html_escape($item['label']);
		$url = $item['url'];
		$out .= "<a class='btn btn-link' href='{$url}'>{$icon}<span>{$label}</span></a>\n";
	}

	return $out;
}*/


function render_menu($params = array())
{
	$CI =& get_instance();
	$CI->load->library('parser');

	$defaults = array(
		'active' => FALSE,
		'active_tag' => 'item',
		'items' => array(),
		'item_template' => '<li class="{item_class} {item_active_class}">{link}</li>',
		'item_class' => '',
		'active_class' => 'active',
		'link_template' => '<a class="{link_class} {link_active_class}" href="{url}" {link_attrs}>{icon}<span>{label}</span></a>',
		'link_class' => '',
	);

	$data = array_merge($defaults, $params);
	extract($data);

	$out = '';

	foreach ($items as $item) {

		if (array_key_exists('visible', $item) && $item['visible'] === FALSE) {
			continue;
		}

		$icon = array_key_exists('icon', $item) ? icon($item['icon']) : '';

		$link_attrs = '';
		if (array_key_exists('link_attrs', $item)) {
			$link_attrs = _stringify_attributes($item['link_attrs']);
		}

		$url = $item['url'];
		if (substr($url, 0, 4) !== 'http') {
			$url = site_url($url);
		}

		$vars = array(
			'link_active_class' => '',
			'item_active_class' => '',
			'item_class' => $item_class,
			'link_class' => $link_class,
			'link_attrs' => $link_attrs,
			'icon' => (isset($item['icon']) ? '<span class="btn-icon">' . icon($item['icon']) . '</span>' : ''),
			'label' => html_escape($item['label']),
			'url' => $url,
		);

		$is_active_class = '';
		if (($active !== FALSE && $item['url'] == $active )
		    || (isset($item['active']) && $item['active'] == TRUE)
		) {
			$is_active_class = $active_class;
		}

		switch ($active_tag) {
			case 'item': $vars['item_active_class'] = $is_active_class; break;
			case 'link': $vars['link_active_class'] = $is_active_class; break;
		}

		$link = $CI->parser->parse_string($link_template, $vars, TRUE);

		$vars['link'] = $link;
		$item = $CI->parser->parse_string($item_template, $vars, TRUE);

		$out .= "{$item}\n";
	}


	return $out;
}



function render_breadcrumbs($breadcrumbs = array())
{
	$out = '';

	if (empty($breadcrumbs)) {
		return $out;
	}

	$out .= '<section class="bread-wrapper">';
	$out .= '<ul class="breadcrumb">';

	foreach ($breadcrumbs as $item) {

		$url = $item[0];
		if (substr($url, 0, 4) !== 'http') {
			$url = site_url($url);
		}

		$label = html_escape($item[1]);

		$link = "<a href='{$url}'>{$label}</a>";
		$item = "<li class='breadcrumb-item'>{$link}</li>";

		$out .= $item . "\n";
	}

	$out .= '</ul>';
	$out .= '</section>';

	return $out . "\n";

}


function render_notice($params = array())
{
	$types = array(
		'error' => ['class' => 'toast-error', 'icon' => 'x-circle'],
		'warning' => ['class' => 'toast-warning', 'icon' => 'alert-triangle'],
		'info' => ['class' => 'toast-info', 'icon' => 'info'],
		'success' => ['class' => 'toast-success', 'icon' => 'check-circle'],
	);

	$default = array(
		'class' => '',
		'icon' => '',
		'type' => 'success',
		'content' => '',
		'close' => FALSE,
	);

	if (array_key_exists('type', $params) && array_key_exists($params['type'], $types)) {
		$type_data = $types[$params['type']];
		$default = array_merge($default, $type_data);
	}

	$data = array_merge($default, $params);

	$out = '';

	$out .= "<div class='toast {$data['class']}'>";

	if ( ! empty($data['icon'])) {
		$icon = icon($data['icon']);
		$out .= $icon;
	}

	$out .= $data['content'];

	if ($data['close']) {
		$out .= "<button class='btn btn-clear float-right'></button>";
	}

	$out .= "</div>\n";

	return $out;
}



function render_notices()
{
	if ( ! array_key_exists('notices', $_SESSION)) {
		return '';
	}

	$out = '';

	foreach ($_SESSION['notices'] as $notice) {
		$out .= render_notice($notice);
	}

	$_SESSION['notices'] = [];

	return $out;
}




function render_logo($params = array())
{
	$defaults = array(
		'class' => '',
	);

	$data = array_merge($defaults, $params);

	$logo_file = setting('logo');

	if (strlen($logo_file) && file_exists(FCPATH . "uploads/{$logo_file}")) {
		return img(base_url("uploads/{$logo_file}"), FALSE, array(
			'class' => 'img-responsive ' . $data['class'],
			'alt' => 'Logo'
		));
	}

	return '';
}
