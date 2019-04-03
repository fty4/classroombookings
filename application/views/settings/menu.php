<?php

$items = $menus['settings'];

echo "<ul class='nav nav-secondary'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'nav-item',
));

echo "</nav>";
