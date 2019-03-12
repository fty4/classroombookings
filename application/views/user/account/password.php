<?php
$layout = 'horizontal';
echo form_open('', ['class' => 'form-horizontal']);
?>

<div class="card card-lg">

	<div class="card-header">
		<h2 class="card-title"><?= $title ?></h2>
	</div>


	<div class="card-body">

		<?php

		$field = 'current_password';
		$value = set_value($field, '', FALSE);

		echo form_group(array(
			'layout' => $layout,
			'size' => 'md',
			'field' => $field,
			'label' => lang('user_field_' . $field),
			'input' => form_password(array(
				'autofocus' => TRUE,
				'class' => 'form-input',
				'name' => $field,
				'id' => $field,
				'tabindex' => tab_index(),
			)),
		));

		$field = 'new_password_1';
		echo form_group(array(
			'layout' => $layout,
			'size' => 'md',
			'field' => $field,
			'label' => lang('user_field_' . $field),
			'hint' => '',
			'input' => form_password(array(
				'class' => 'form-input',
				'name' => $field,
				'id' => $field,
				'tabindex' => tab_index(),
			)),
		));

		$field = 'new_password_2';
		echo form_group(array(
			'layout' => $layout,
			'size' => 'md',
			'field' => $field,
			'label' => lang('user_field_' . $field),
			'hint' => '',
			'input' => form_password(array(
				'class' => 'form-input',
				'name' => $field,
				'id' => $field,
				'tabindex' => tab_index(),
			)),
		));

		?>

	</div>

	<div class="card-footer">
		<?php

		$submit_button = form_button(array(
			'type' => 'submit',
			'content' => lang('user_password_action_change'),
			'class' => 'btn btn-primary ',
			'tabindex' => tab_index(),
		));

		echo form_group(array(
			'group_class' => 'form-group',
			'label' => '',
			'hint' => '',
			'input' => $submit_button,
		));

		?>
	</div>

</div>


<?= form_close() ?>
