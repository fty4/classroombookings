<?php

$items = [];

$items[] = [
	'icon' => 'calendar',
	'label' => lang('weeks_page_index'),
	'id' => 'admin/weeks',
	'url' => "weeks",
];

$items[] = [
	'icon' => 'plus-circle',
	'label' => lang('weeks_action_add'),
	'id' => 'admin/weeks/add',
	'url' => "weeks/add",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
