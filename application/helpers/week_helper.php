<?php

class WeekHelper
{

	use app\helpers\DataTrait;


	public static function icon($week, $params = [])
	{
		$defaults = [
			'class' => '',
		];

		$data = array_merge($defaults, $params);

		$class = $data['class'];
		$class .= 'chip-week';

		$icon = 'square';
		$colour = $week->colour;

		$out = "<div class='chip chip-info chip-no-bg {$class}'>";
		$out .= icon($icon, ['fill' => "#{$colour}"]);
		$out .= "</div>";

		return $out;
	}


}
