<?php

$items = [];

$items[] = [
	'label' => 'Edit details',
	'url' => 'user',
	'icon' => 'user',
];

$items[] = [
	'label' => 'Change password',
	'url' => 'user/password',
	'icon' => 'lock',
];

echo "<ul class='nav nav-secondary'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'nav-item',
));

echo "</nav>";
