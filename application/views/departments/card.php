<div class="column col-4 col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-8">

	<div class="card">
		<div class="card-header">
			<a href="<?= site_url("departments/update/{$department->department_id}") ?>" class="card-title">
				<?php
				echo DepartmentHelper::icon($department, ['class' => 'chip-inline']);
				echo html_escape($department->name);
				?>
			</a>
			<div class="card-subtitle text-gray"><?= $department->description ?></div>
		</div>
	</div>

</div>
