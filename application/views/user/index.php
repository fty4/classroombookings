
<section class="container grid-lg">

	<ul class="tab">
		<li class="tab-item active">
			<a href="#">Update details</a>
		</li>
		<li class="tab-item">
			<a href="#" class="">Password</a>
		</li>
	</ul>
	<br><br>

	<div class="columns">

		<div class="column col-xs-12 col-md-9 col-8">


			<?php
			echo form_open();

			echo "<h3>Account details</h3>";

			$field = 'email';
			$value = set_value($field, $user->email, FALSE);

			echo form_group(array(
				'field' => $field,
				'label' => lang('user_field_' . $field),
				'group_class' => 'form-group col-6',
				'hint' => 'This will be used to send notifications to.',
				'input' => form_input(array(
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
				'field' => $field,
				'label' => lang('user_field_' . $field),
				'group_class' => 'form-group col-4',
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
				'field' => $field,
				'label' => lang('user_field_' . $field),
				'group_class' => 'form-group col-4',
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
				'field' => $field,
				'label' => lang('user_field_' . $field),
				'group_class' => 'form-group col-6',
				'input' => form_input(array(
					'class' => 'form-input',
					'name' => $field,
					'id' => $field,
					'tabindex' => tab_index(),
					'value' => $value,
				)),
			));


			$submit_button = form_button(array(
				'type' => 'submit',
				'content' => lang('user_update_action_save'),
				'class' => 'btn btn-primary btn-lg',
				'tabindex' => tab_index(),
			));
			$reset_link = '';	//anchor('user/reset-password', 'Reset password', array('class' => 'btn btn-link'));

			echo form_group(array(
				'group_class' => 'form-group form-group-actions',
				'label' => '',
				'hint' => '',
				'input' => $submit_button . $reset_link,
			));

			echo form_close();
			?>


		</div>

	</div>

</section>
