<div class="column col-4 col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-8">

	<div class="card">
		<div class="card-header">
			<a href="<?= site_url("weeks/update/{$week->week_id}") ?>" class="card-title">
				<?php
				echo WeekHelper::icon($week, ['class' => 'chip-inline']);
				echo html_escape($week->name);
				?>
			</a>
		</div>
	</div>

</div>
