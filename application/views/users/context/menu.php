<?php

$items = [];

$items[] = [
	'icon' => 'user',
	'label' => lang('users_page_view'),
	'id' => 'settings/users/view',
	'url' => "users/view/{$user->user_id}",
];

$items[] = [
	'icon' => 'edit-2',
	'label' => lang('users_page_update'),
	'id' => 'settings/users/update',
	'url' => "users/update/{$user->user_id}",
];

$items[] = [
	'icon' => 'lock',
	'label' => lang('users_page_password'),
	'id' => 'settings/users/password',
	'url' => "users/change_password/{$user->user_id}",
];

if ($user->user_id !== $this->userauth->user->user_id) {
	$items[] = [
		'icon' => 'trash-2',
		'label' => lang('users_page_delete'),
		'id' => 'settings/users/delete',
		'url' => "users/delete/{$user->user_id}",
	];
}

echo "<ul class='tab tab-menu'>";

echo render_menu(array(
	'active' => (isset($menu_active) ? $menu_active : NULL),
	'items' => $items,
	'item_class' => 'tab-item',
));

echo "</ul>";
