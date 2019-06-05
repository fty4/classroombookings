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
