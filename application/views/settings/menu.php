<?php

$items = [];

$items[] = [
	'icon' => 'settings',
	'label' => lang('settings_page_title'),
	'id' => 'admin/settings',
	'url' => "settings",
];

$items[] = [
	'icon' => 'eye',
	'label' => lang('settings_visual_page_title'),
	'id' => 'admin/settings/appearance',
	'url' => "settings/visual",
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
