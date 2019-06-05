<?php

$table = \Jupitern\Table\Table::instance();
$table->attr('class', 'table');
$table->setData($weeks);


$table->column()
	->title('')
	->value(function($week) {
		return WeekHelper::icon($week);
	})
	->attr('style', 'width:5%')
	->add();

$table->column()
	->title('Name')
	->value(function($week) {
		return anchor('weeks/update/' . $week->week_id, html_escape($week->name));
	})
	->attr('class', 'table-title-cell')
	->add();

$content = $table->render(true);

echo table_box([
	'title' => '',
	'table' => $content,
]);
