<?php

$table = \Jupitern\Table\Table::instance();
$table->attr('class', 'table');
$table->setData($holidays);

$table->column()
	->title(lang('holiday_field_name'))
	->value(function($holiday) {
		return anchor('holidays/update/' . $holiday->holiday_id, html_escape($holiday->name));
	})
	->attr('class', 'table-title-cell')
	->add();

$table->column()
	->title(lang('holiday_field_date_start'))
	->value(function($holiday) {
		return nice_date($holiday->date_start, 'D j F Y');
	})
	->add();

$table->column()
	->title(lang('holiday_field_date_end'))
	->value(function($holiday) {
		return nice_date($holiday->date_end, 'D j F Y');
	})
	->add();

$content = $table->render(true);

if (count($holidays) > 0) {

	echo table_box([
		'title' => '',
		'table' => $content,
	]);

} else {

	$this->load->view('partials/empty', [
		'title' => lang('holidays_none'),
		'description' => lang('holidays_none_hint'),
		'icon' => 'sun',
		'action' => anchor("holidays/add/{$year->year_id}", lang('holidays_action_add'), 'class="btn btn-primary"'),
	]);

}
