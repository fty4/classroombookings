<?php

$items = [];

$items[] = [
	'icon' => 'corner-up-left',
	'label' => lang('rooms_page_index'),
	'id' => 'admin/rooms',
	'url' => "rooms",
];

$items[] = [
	'icon' => 'edit-2',
	'label' => lang('rooms_page_update'),
	'id' => 'admin/rooms/update',
	'url' => "rooms/update/{$room->room_id}",
];

$items[] = [
	'icon' => 'trash-2',
	'label' => lang('rooms_page_delete'),
	'id' => 'admin/rooms/delete',
	'url' => "rooms/delete/{$room->room_id}",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
