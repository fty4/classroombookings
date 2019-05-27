<?php

$table = \Jupitern\Table\Table::instance();
$table->attr('class', 'table');
$table->setData($result);

$table->column()
	->title(lang('users_import_results_field_row'))
	->value(function($row) {
		return "#{$row->line}";
	})
	->attr('class', 'table-title-cell')
	->add();

$table->column()
	->title(lang('user_field_username'))
	->value(function($row) {
		$label = html_escape($row->user->username);
		$out = $label;
		if ($row->status == 'success' && ! empty($row->id)) {
			$out = anchor("users/view/{$row->id}", $label);
		}
		return $out;
	})
	->add();

$table->column()
	->title(lang('users_import_results_field_created'))
	->value(function($row) {
		return ($row->status == 'success' ? 'Yes' : 'No');
	})
	->add();

$table->column()
	->title(lang('users_import_results_field_status'))
	->value(function($row) {

		if ($row->status == 'success') {
			$icon = 'check';
			$class = 'chip-status-positive';
		} else {
			$icon = 'alert-triangle';
			$class = 'chip-status-negative';
		}

		$label = $row->status;
		if (empty($label)) {
			$label = 'unknown';
		}
		$label = lang("users_import_results_status_{$label}");

		$icon = icon($icon);

		$out = "<div class='chip chip-info chip-no-bg {$class}'>{$icon}{$label}</div>";
		return $out;
	})
	->add();

$content = $table->render(true);

echo table_box([
	'title' => lang('users_import_page_results'),
	'subtitle' => lang('users_import_page_results_hint'),
	'table' => $content,
]);
