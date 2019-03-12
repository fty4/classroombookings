<div class="empty empty-error">

	<?php if (strlen($icon)): ?>
	<div class="empty-icon">
		<?= icon($icon, ['width' => 48, 'height' => 48]) ?>
	</div>
	<?php endif; ?>

	<p class="empty-title h5"><?= $title ?></p>
	<p class="empty-subtitle"><?= $description ?></p>

	<?php if (strlen($action)): ?>
	<div class="empty-action">
		<?= $action ?>
	</div>
	<?php endif; ?>

</div>
