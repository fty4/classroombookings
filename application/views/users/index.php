<?php

$table = \Jupitern\Table\Table::instance();
$table->attr('table', 'class', 'table');
$table->setData($users);

$table->column()
	->title('Username')
	->value(function($user) {
		return anchor('users/view/' . $user->user_id, html_escape($user->username));
	})
	->attr('td', 'class', 'table-title-cell')
	->add();

$table->column()
	->title(lang('user_field_displayname'))
	->value(function($user) {
		return UserHelper::best_display_name($user);
	})
	->add();

$table->column()
	->title('Type')
	->value(function($user) {
		return UserHelper::type($user);
	})
	->add();

$table->column()
	->title('Last logged in')
	->value(function($user) {
		return UserHelper::last_login($user, lang('never'));
	})
	->add();

$table->column()
	->title('Status')
	->value(function($user) {
		return UserHelper::status_chip($user);
	})
	->add();

$content = $table->render(true);

// echo anchor('users/add', icon('plus-circle') . lang('users_action_add'), 'class="btn btn-link btn-action-link"');
// echo anchor('users/add', icon('download') . lang('users_action_import'), 'class="btn btn-link btn-action-link"');

echo table_box([
	'title' => '',	//lang('users_index_page_title'),
	'table' => $content,
]);
