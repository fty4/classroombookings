
<div class="columns">

	<div class="column col-xs-12 col-sm-12 col-md-12 col-8 col-mx-auto">

		<?php

		echo form_open(current_url(), ['class' => 'form-vertical']);

		$fields = [];


		$field = 'username';
		$label = lang("user_field_{$field}");
		$value = set_value($field, '', FALSE);

		$input_options = [
			'class' => 'form-input',
			'name' => $field,
			'id' => $field,
			'tabindex' => tab_index(),
			'value' => $value,
		];

		if (empty($value)) {
			$input_options['autofocus'] = TRUE;
		}

		$fields[] = form_group([
			'field' => $field,
			'label' => $label,
			'input' => form_input($input_options),
		]);


		$field = 'password';
		$label = lang("user_field_{$field}");

		$input_options = [
			'class' => 'form-input',
			'name' => $field,
			'id' => $field,
			'tabindex' => tab_index(),
		];

		if ( ! empty($value)) {
			$input_options['autofocus'] = TRUE;
		}

		$fields[] = form_group([
			'field' => $field,
			'label' => $label,
			'input' => form_password($input_options),
		]);


		$fields[] = form_group([
			'input' => form_button([
				'type' => 'submit',
				'content' => lang('user_action_log_in'),
				'class' => 'btn btn-primary',
				'tabindex' => tab_index(),
			]),
		]);


		echo form_fieldset([
			'title' => lang('user_page_login_title'),
			'subtitle' => html_escape(setting('login_hint')),
			'content' => implode($fields),
		]);


		echo form_close();

		?>

	</div>

</div>
