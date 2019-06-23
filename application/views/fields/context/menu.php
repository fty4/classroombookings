<?php

$items = [];

$items[] = [
	'icon' => 'corner-up-left',
	'label' => lang('fields_page_index'),
	'id' => 'admin/fields',
	'url' => "custom_fields",
];

$items[] = [
	'icon' => 'edit-2',
	'label' => lang('fields_page_update'),
	'id' => 'admin/fields/update',
	'url' => "custom_fields/update/{$custom_field->field_id}",
];

$items[] = [
	'icon' => 'trash-2',
	'label' => lang('fields_page_delete'),
	'id' => 'admin/fields/delete',
	'url' => "custom_fields/delete/{$custom_field->field_id}",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
