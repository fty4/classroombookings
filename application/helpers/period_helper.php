<?php

class PeriodHelper
{

	use app\helpers\DataTrait;


	public static function bookable_chip($period, $params = [])
	{
		$defaults = [
			'class' => '',
		];

		$data = array_merge($defaults, $params);

		$class = $data['class'];

		if ($period->bookable == 1) {
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


}
