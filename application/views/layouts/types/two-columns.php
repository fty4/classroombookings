<?php
if (isset($blocks['header'])) {
	echo $blocks['header'];
}
?>

<div class="columns">

	<div class="column column col-3 col-xl-3 col-lg-3 col-md-4 col-sm-12">
		<?= $blocks['sidebar'] ?>
	</div>

	<div class="column column col-9 col-xl-9 col-lg-9 col-md-8 col-sm-12">
		<?= $blocks['content'] ?>
	</div>

</div>

<?php
if (isset($blocks['footer'])) {
	echo $blocks['footer'];
}
?>
