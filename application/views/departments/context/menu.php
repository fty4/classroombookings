<?php

$items = [];

$items[] = [
	'icon' => 'edit-2',
	'label' => lang('departments_page_update'),
	'id' => 'admin/departments/update',
	'url' => "departments/update/{$department->department_id}",
];

$items[] = [
	'icon' => 'trash-2',
	'label' => lang('departments_page_delete'),
	'id' => 'admin/departments/delete',
	'url' => "departments/delete/{$department->department_id}",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
