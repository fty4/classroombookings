<?php

class WeekHelper
{

	use app\helpers\DataTrait;


	public static function icon($week, $params = [])
	{
		$defaults = [
			'class' => '',
			'label' => FALSE,
		];

		$data = array_merge($defaults, $params);

		$class = $data['class'];
		$class .= ' chip-week';

		$icon = 'square';
		$colour = $week->colour;

		$out = "<span class='chip chip-info chip-no-bg {$class}'>";
		$out .= icon($icon, ['fill' => "#{$colour}"]);
		if ($data['label']) {
			$out .= $week->name;
		}
		$out .= "</span>";

		return $out;
	}


}
