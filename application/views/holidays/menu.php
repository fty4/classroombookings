<?php

$items = [];

$items[] = [
	'icon' => 'corner-up-left',
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
	'icon' => 'plus-circle',
	'label' => lang('holidays_action_add'),
	'id' => 'admin/holidays/add',
	'url' => "holidays/add/{$year->year_id}",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
