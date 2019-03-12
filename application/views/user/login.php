
	<div class="columns">

		<div class="column col-xs-12 col-sm-12 col-md-12 col-5 col-mx-auto">

			<?php
			echo form_open();
			?>

			<div class="card card-lg">

				<div class="card-header">
					<h2 class="card-title"><?= $title ?></h2>
				</div>

				<div class="card-body">
					<?php

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

					?>
				</div>

				<div class="card-footer">
					<?php

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
					?>
				</div>

			</div>

			<?= form_close() ?>

		</div>

		<!-- <div class="column col-xs-12 col-sm-12 col-md-12 col-5 col-ml-auto">
			<?php
			echo render_logo(array(
				'class' => 'logo-login',
			));
			?>
		</div> -->

	</div>
