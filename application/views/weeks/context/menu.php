<?php

$items = [];

$items[] = [
	'icon' => 'edit-2',
	'label' => lang('weeks_page_update'),
	'id' => 'admin/weeks/update',
	'url' => "weeks/update/{$week->week_id}",
];

$items[] = [
	'icon' => 'trash-2',
	'label' => lang('weeks_page_delete'),
	'id' => 'admin/weeks/delete',
	'url' => "weeks/delete/{$week->week_id}",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
