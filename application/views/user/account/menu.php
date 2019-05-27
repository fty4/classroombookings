<?php

$items = [];

$items[] = [
	'label' => lang('user_page_account_details_title'),
	'icon' => 'edit-2',
	'url' => 'user',
];

$items[] = [
	'label' => lang('user_page_password_title'),
	'icon' => 'lock',
	'url' => 'user/password',
];

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
