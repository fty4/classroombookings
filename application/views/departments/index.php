<?php

if (count($departments) > 0) {

	$cards = [];
	foreach ($departments as $department) {
		$cards[] = render_view('departments/card', ['department' => $department]);
	}

	echo "<div class='columns'>" . implode("\n", $cards) . "</div>";

} else {

	$this->load->view('partials/empty', [
		'title' => lang('departments_none'),
		'description' => lang('departments_none_hint'),
		'icon' => 'monitor',
		'action' => anchor("departments/add", lang('departments_action_add'), 'class="btn btn-primary"'),
	]);

}
