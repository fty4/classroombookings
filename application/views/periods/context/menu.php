<?php

$items = [];

$items[] = [
	'icon' => 'corner-up-left',
	'label' => lang('periods_page_index'),
	'id' => 'admin/periods',
	'url' => "periods",
];

$items[] = [
	'icon' => 'edit-2',
	'label' => lang('periods_page_update'),
	'id' => 'admin/periods/update',
	'url' => "periods/update/{$period->period_id}",
];

$items[] = [
	'icon' => 'trash-2',
	'label' => lang('periods_page_delete'),
	'id' => 'admin/periods/delete',
	'url' => "periods/delete/{$period->period_id}",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
