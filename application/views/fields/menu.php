<?php

$items = [];

$items[] = [
	'icon' => 'code',
	'label' => lang('fields_page_index'),
	'id' => 'admin/fields',
	'url' => "custom_fields",
];

$items[] = [
	'icon' => 'plus-circle',
	'label' => lang('fields_action_add'),
	'id' => 'admin/fields/add',
	'url' => "custom_fields/add",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
