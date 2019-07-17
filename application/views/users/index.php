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

// Filter
//
echo form_open("users", ['method' => 'get', 'class' => 'mb-8']);

echo $filter_ui->render();

$submit = form_button(array(
	'type' => 'submit',
	'name' => '_action',
	'value' => 'filter',
	'content' => "<span class='btn-icon'>" . icon('filter') . '</span> ' . lang('filter_filter'),
	'class' => 'btn btn-primary ml-4',
	'tabindex' => tab_index(),
));

$cancel = anchor('users', lang('filter_clear'), array('class' => 'btn btn-link '));

echo "{$submit} \n {$cancel}";

echo form_close();


// Table
//

echo table_box([
	'title' => '',	//lang('users_index_page_title'),
	'table' => $content,
]);
