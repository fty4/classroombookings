<?php

$items = [];

$items[] = [
	'icon' => 'corner-up-left',
	'label' => lang('holidays_page_year'),
	'id' => 'admin/holidays/year',
	'url' => "holidays/year/{$year->year_id}",
];

$items[] = [
	'icon' => 'edit-2',
	'label' => lang('holidays_page_update'),
	'id' => 'admin/holidays/update',
	'url' => "holidays/update/{$holiday->holiday_id}",
];

$items[] = [
	'icon' => 'trash-2',
	'label' => lang('holidays_page_delete'),
	'id' => 'admin/holidays/delete',
	'url' => "holidays/delete/{$holiday->holiday_id}",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
