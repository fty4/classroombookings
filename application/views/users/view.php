<div class="columns columns-info">

	<?php

	 $not_set = '<em class="not-set">' . lang('not_set') . '</em>';

	$values = [];
	$values[ lang('user_field_username') ] = html_escape($user->username);
	$values[ lang('user_field_names') ] = UserHelper::names($user, $not_set);
	$values[ lang('user_field_displayname') ] = UserHelper::value_or_other($user, 'displayname', $not_set);
	$values[ lang('user_field_email' )] = UserHelper::value_or_other($user, 'email', $not_set);
	$values[ lang('user_field_authlevel' )] = UserHelper::type($user);
	$values[ lang('user_field_status' )] = UserHelper::status_chip($user);
	$values[ lang('user_field_department_id' )] = UserHelper::department($user, $not_set);

	$values[ lang('user_field_created_at' )] = UserHelper::created_at($user, lang('unknown'));
	$values[ lang('user_field_last_login' )] = UserHelper::last_login($user, lang('never'));

	foreach ($values as $label => $value) {

		$dl = render_dl([
			'class' => 'view-item-info',
			'template' => '{dt}{dd}',
			'dt' => $label,
			'dd' => $value,
		]);

		echo "<div class='column col-sm-12 col-md-6 col-4'>{$dl}</div>";
	}

	?>

</div>
