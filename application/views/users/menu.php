<?php

$items = [];

$items[] = [
	'icon' => 'users',
	'label' => lang('users_page_index'),
	'id' => 'admin/users',
	'url' => "users",
];

$items[] = [
	'icon' => 'upload',
	'label' => lang('users_import_page_index'),
	'id' => 'admin/users/import',
	'url' => "users_import",
];

$items[] = [
	'icon' => 'plus-circle',
	'label' => lang('users_action_add'),
	'id' => 'admin/users/add',
	'url' => "users/add",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
