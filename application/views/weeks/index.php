<?php

if (count($weeks) > 0) {

	$cards = [];
	foreach ($weeks as $week) {
		$cards[] = render_view('weeks/card', ['week' => $week]);
	}

	echo "<div class='columns'>" . implode("\n", $cards) . "</div>";

} else {

	$this->load->view('partials/empty', [
		'title' => lang('weeks_none'),
		'description' => lang('weeks_none_hint'),
		'icon' => 'repeat',
		'action' => anchor("weeks/add", lang('weeks_action_add'), 'class="btn btn-primary"'),
	]);

}
