<?php

$items = [];

$items[] = [
	'icon' => 'monitor',
	'label' => lang('rooms_page_index'),
	'id' => 'admin/rooms',
	'url' => "rooms",
];

$items[] = [
	'icon' => 'plus-circle',
	'label' => lang('rooms_action_add'),
	'id' => 'admin/rooms/add',
	'url' => "rooms/add",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
