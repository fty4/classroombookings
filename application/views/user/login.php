
<section class="container grid-lg">

	<div class="columns">

		<div class="column col-xs-12 col-sm-12 col-md-12 col-5">

			<?php
			echo form_open();

			$field = 'username';
			$value = set_value($field, '', FALSE);

			echo form_group(array(
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

			$field = 'password';
			echo form_group(array(
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

			$submit_button = form_button(array(
				'type' => 'submit',
				'content' => lang('user_action_log_in'),
				'class' => 'btn btn-primary',
				'tabindex' => tab_index(),
			));
			$reset_link = '';	//anchor('user/reset-password', 'Reset password', array('class' => 'btn btn-link'));

			echo form_group(array(
				'label' => '',
				'hint' => '',
				'input' => $submit_button . $reset_link,
			));

			echo form_close();
			?>


		</div>

		<div class="column col-xs-12 col-sm-12 col-md-12 col-5 col-ml-auto">
			<?php
			echo render_logo(array(
				'class' => 'logo-login',
			));
			?>
		</div>

	</div>

</section>
