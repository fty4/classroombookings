<?php

$table = \Jupitern\Table\Table::instance();
$table->attr('table', 'class', 'table');
$table->setData($rooms);

$table->column()
	->title(lang('room_field_name'))
	->value(function($room) {
		return anchor('rooms/update/' . $room->room_id, html_escape($room->name));
	})
	->attr('td', 'class', 'table-title-cell')
	->add();

$table->column()
	->title(lang('room_field_user_id'))
	->value(function($room) {
		if ($room->user) {
			$label = UserHelper::best_display_name($room->user);
			return anchor("users/view/{$room->user->user_id}", $label);
		}
	})
	->add();

$table->column()
	->title(lang('room_field_photo'))
	->css('td', 'width', '15%')
	->value(function($room) {
		$icon = icon('image');
		return RoomHelper::photo_popover($room, $icon);
	})
	->add();

$table->column()
	->title(lang('room_field_bookable'))
	->css('td', 'width', '15%')
	->value(function($room) {
		if ($room->bookable) {
			return RoomHelper::bookable_chip($room);
		}
	})
	->add();

$content = $table->render(true);

if (count($rooms) > 0) {

	echo table_box([
		'title' => '',
		'table' => $content,
	]);

} else {

	$this->load->view('partials/empty', [
		'title' => lang('rooms_none'),
		'description' => lang('rooms_none_hint'),
		'icon' => 'monitor',
		'action' => anchor("rooms/add", lang('rooms_action_add'), 'class="btn btn-primary"'),
	]);

}
