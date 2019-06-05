<?php

$items = [];

$items[] = [
	'icon' => 'award',
	'label' => lang('years_page_index'),
	'id' => 'admin/years',
	'url' => "academic_years",
];

$items[] = [
	'icon' => 'plus-circle',
	'label' => lang('years_action_add'),
	'id' => 'admin/years/add',
	'url' => "academic_years/add",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
