<div class="columns columns-info">

	<?php

	$not_set = '<em class="not-set">' . lang('not_set') . '</em>';

	$values = [];
	$values[ lang('year_field_date_start') ] = nice_date($year->date_start, 'D j F Y');
	$values[ lang('year_field_date_end') ] = nice_date($year->date_end, 'D j F Y');
	$values[ lang('year_current') ] = YearHelper::current_chip($year);

	foreach ($values as $label => $value) {

		$dl = render_dl([
			'class' => 'view-item-info',
			'template' => '{dt}{dd}',
			'dt' => $label,
			'dd' => $value,
		]);

		echo "<div class='column col-sm-12 col-md-6 col-4'>{$dl}</div>";
	}

	?>

</div>

<div class="divider"></div>
<br>

<p class="tip">Click on any date to cycle through the available timetable weeks and apply it to that week in the calendar.
	<a href="javascript:;" data-dismisstip="academic_year_calendar">Dismiss.</a>
</p>

<?php
$key = '';
foreach ($weeks as $week) {
	$key .= WeekHelper::icon($week, ['label' => TRUE, 'class' => 'mr-4']);
}
?>
<p>Key: <?= $key ?></p>

<?php
echo form_open(current_url());
?>

<div class="year-calendar" data-ui="year_calendar" data-weeks='<?= json_encode($this->weeks_model->items_to_array($weeks)) ?>'>
	<div class="columns">
		<?php
		$months = $calendar->get_all_months();
		foreach ($months as $month) {
			echo "<div class='column col-sm-12 col-md-2 col-lg-4 col-xl-4 col-4'>";
			echo $month;
			echo "</div>";
		}
		?>
	</div>
</div>

<?php

$submit_button = form_button([
	'type' => 'submit',
	'content' => lang('years_action_update_week_assignments'),
	'class' => 'btn btn-primary ',
	'tabindex' => tab_index(),
]);

echo form_fieldset([
	'actions' => TRUE,
	'content' => $submit_button,
]);


echo form_close();
?>

<style type="text/css">
<?= $calendar->get_css() ?>
</style>
