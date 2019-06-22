<?php

$table = \Jupitern\Table\Table::instance();
$table->attr('table', 'class', 'table');
$table->setData($years);

$table->column()
	->title(lang('year_field_name'))
	->value(function($year) {
		return anchor('academic_years/view/' . $year->year_id, html_escape($year->name));
	})
	->attr('td', 'class', 'table-title-cell')
	->add();

$table->column()
	->title(lang('year_field_date_start'))
	->value(function($year) {
		return nice_date($year->date_start, 'D j F Y');
	})
	->add();

$table->column()
	->title(lang('year_field_date_end'))
	->value(function($year) {
		return nice_date($year->date_end, 'D j F Y');
	})
	->add();

$table->column()
	->title('Current')
	->value(function($year) {
		if (YearHelper::is_current($year)) {
			return YearHelper::current_chip($year);
		}
		return '';
	})
	->add();

$content = $table->render(true);

echo table_box([
	'title' => '',
	'table' => $content,
]);
