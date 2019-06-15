<?php

$table = \Jupitern\Table\Table::instance();
$table->attr('class', 'table');
$table->setData($periods);

$table->column()
	->title(lang('period_field_name'))
	->value(function($period) {
		return anchor('periods/update/' . $period->period_id, html_escape($period->name));
	})
	->attr('class', 'table-title-cell')
	->add();

$table->column()
	->title(lang('period_field_time_start'))
	->css('width', '15%', true)
	->value(function($period) {
		return nice_date($period->time_start, 'H:i');
	})
	->add();

$table->column()
	->title(lang('period_field_time_end'))
	->css('width', '15%', true)
	->value(function($period) {
		return nice_date($period->time_end, 'H:i');
	})
	->add();

$table->column()
	->title(lang('period_field_days'))
	->css('width', '35%', true)
	->value(function($period) use ($days) {
		$week = [];
		foreach ($days as $day_num) {
			$prop = "day_{$day_num}";
			$val = get_property($prop, $period);
			$label = lang("day_{$day_num}_short");
			if ($val == '0') {
				$label = "<s>{$label}</s>";
			} else {
				$label = "<span>{$label}</span>";
			}
			$week[] = $label;
		}
		return '<div class="period-day-list">' . implode(' ', $week) . '</div>';
	})
	->add();

$table->column()
	->title(lang('period_field_bookable'))
	->css('width', '15%')
	->value(function($period) {
		if ($period->bookable) {
			return PeriodHelper::bookable_chip($period);
		}
	})
	->add();

$table->rowAttr('data-tag', function($period) use ($days) {
	$tags = [];
	foreach ($days as $day_num) {
		$prop = "day_{$day_num}";
		$val = get_property($prop, $period);
		if ($val == '1') {
			$tags[] = "tag-{$day_num}";
		}
	}
	return implode(' ', $tags);
});

$table->rowAttr('class', 'filter-item');

$content = $table->render(true);

?>

<div class="filter">

	<?php

	$filter_nav_items = [];
	$filter_inputs = [];

	$filter_inputs[] = form_radio([
		'id' => "tag-0",
		'class' => "filter-tag",
		'name' => "filter-radio",
		'hidden' => 'hidden',
		'checked' => true,
	]);

	$all_name = lang('all');
	$filter_nav_items[] = "<label class='chip' for='tag-0'>{$all_name}</label>";

	foreach ($days as $day_num) {
		$day_name = lang("day_{$day_num}_long");
		$filter_nav_items[] = "<label class='chip' for='tag-{$day_num}'>{$day_name}</label>";
		$filter_inputs[] = form_radio([
			'id' => "tag-{$day_num}",
			'class' => "filter-tag",
			'name' => "filter-radio",
			'hidden' => 'hidden',
		]);
	}

	echo implode("\n", $filter_inputs);
	echo "<div class='filter-nav'>" . implode("\n", $filter_nav_items) . "</div>";

	echo "<div class='filter-body filter-body-block'>";

	echo table_box([
		'title' => '',
		'table' => $content,
	]);

	echo "</div>";

	?>

</div>
