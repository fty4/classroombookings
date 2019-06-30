<?php

$items = [];

$items[] = [
	'icon' => 'layers',
	'label' => lang('departments_page_index'),
	'id' => 'admin/departments',
	'url' => "departments",
];

$items[] = [
	'icon' => 'plus-circle',
	'label' => lang('departments_action_add'),
	'id' => 'admin/departments/add',
	'url' => "departments/add",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
