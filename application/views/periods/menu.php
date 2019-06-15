<?php

$items = [];

$items[] = [
	'icon' => 'clock',
	'label' => lang('periods_page_index'),
	'id' => 'admin/periods',
	'url' => "periods",
];

$items[] = [
	'icon' => 'plus-circle',
	'label' => lang('periods_action_add'),
	'id' => 'admin/periods/add',
	'url' => "periods/add",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
