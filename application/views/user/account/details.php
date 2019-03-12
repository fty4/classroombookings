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

		$field = 'email';
		$value = set_value($field, $user->$field, FALSE);
		$label = lang('user_field_' . $field);

		echo form_group(array(
			'layout' => $layout,
			'size' => 'xl',
			'field' => $field,
			'label' => $label,
			'hint' => 'This will be used to send notifications to.',
			'input' => form_input(array(
				'autofocus' => TRUE,
				'class' => 'form-input',
				'name' => $field,
				'id' => $field,
				'tabindex' => tab_index(),
				'value' => $value,
			)),
		));


		$field = 'firstname';
		$value = set_value($field, $user->firstname, FALSE);

		echo form_group(array(
			'layout' => $layout,
			'size' => 'md',
			'field' => $field,
			'label' => lang('user_field_' . $field),
			'input' => form_input(array(
				'class' => 'form-input',
				'name' => $field,
				'id' => $field,
				'tabindex' => tab_index(),
				'value' => $value,
			)),
		));


		$field = 'lastname';
		$value = set_value($field, $user->lastname, FALSE);

		echo form_group(array(
			'layout' => $layout,
			'size' => 'md',
			'field' => $field,
			'label' => lang('user_field_' . $field),
			'input' => form_input(array(
				'class' => 'form-input',
				'name' => $field,
				'id' => $field,
				'tabindex' => tab_index(),
				'value' => $value,
			)),
		));


		$field = 'displayname';
		$value = set_value($field, $user->displayname, FALSE);

		echo form_group(array(
			'layout' => $layout,
			'size' => 'xl',
			'field' => $field,
			'label' => lang('user_field_' . $field),
			'input' => form_input(array(
				'class' => 'form-input',
				'name' => $field,
				'id' => $field,
				'tabindex' => tab_index(),
				'value' => $value,
			)),
		));
		?>

	</div>

	<div class="card-footer">
		<?php

		$submit_button = form_button(array(
			'type' => 'submit',
			'content' => lang('action_save'),
			'class' => 'btn btn-primary ',
			'tabindex' => tab_index(),
		));
		$reset_link = '';	//anchor('user/reset-password', 'Reset password', array('class' => 'btn btn-link'));

		echo form_group(array(
			'group_class' => 'form-group',
			'label' => '',
			'hint' => '',
			'input' => $submit_button . $reset_link,
		));

		?>
	</div>

</div>


<?= form_close() ?>
