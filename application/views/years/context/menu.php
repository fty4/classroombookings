<?php

$items = [];


$items[] = [
	'icon' => 'calendar',
	'label' => lang('years_page_view'),
	'id' => 'admin/years/view',
	'url' => "academic_years/view/{$year->year_id}",
];

$items[] = [
	'icon' => 'sun',
	'label' => lang('holidays_page_year'),
	'id' => 'admin/holidays/year',
	'url' => "holidays/year/{$year->year_id}",
];

$items[] = [
	'icon' => 'edit-2',
	'label' => lang('years_page_update'),
	'id' => 'admin/years/update',
	'url' => "academic_years/update/{$year->year_id}",
];

$items[] = [
	'icon' => 'trash-2',
	'label' => lang('years_page_delete'),
	'id' => 'admin/years/delete',
	'url' => "academic_years/delete/{$year->year_id}",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
