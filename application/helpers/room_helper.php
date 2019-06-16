<?php

class RoomHelper
{


	use app\helpers\DataTrait;


	public static function bookable_chip($room, $params = [])
	{
		$defaults = [
			'class' => '',
		];

		$data = array_merge($defaults, $params);

		$class = $data['class'];

		if ($room->bookable == 1) {
			$icon = icon('check');
			$label = lang('yes');
			$class = 'item-status-active';
		} else {
			$icon = icon('x');
			$label = lang('no');
			$class = 'item-status-inactive';
		}

		$out = "<div class='chip chip-info chip-no-size chip-no-bg {$class} {$data['class']}'>";
		$out .= $icon;
		$out .= $label;
		$out .= "</div>";

		return $out;
	}


	public static function has_photo($room)
	{
		return ( ! empty($room->photo) && is_file(FCPATH . 'uploads/' . $room->photo));
	}


	public static function photo_popover($room, $content = '')
	{
		if ( ! self::has_photo($room)) {
			return '';
		}
		$img = img('uploads/' . $room->photo, FALSE, "class='img-responsive'");
		$card = "<div class='card'><div class='card-body'>{$img}</div></div>";
		$container = "<div class='popover-container'>{$card}</div>";
		$popover = "<div class='popover popover-top'>{$content}{$container}</div>";
		return $popover;
	}


}
