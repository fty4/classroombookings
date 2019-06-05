<?php

class YearHelper
{

	use app\helpers\DataTrait;


	public static function is_current($year)
	{
		$start_date = new \DateTime($year->date_start);
		$end_date = new \DateTime($year->date_end);

		if ( ! $start_date || ! $end_date) {
			return FALSE;
		}

		$today = new \DateTime();
		return ($today >= $start_date && $today <= $end_date);
	}



	public static function current_chip($year, $params = [])
	{
		$defaults = [
			'class' => '',
		];

		$data = array_merge($defaults, $params);

		$class = $data['class'];

		if (self::is_current($year)) {
			$icon = icon('check');
			$label = lang('yes');
			$class = 'item-status-active';
		} else {
			$icon = icon('x');
			$label = lang('no');
			$class = 'item-status-inactive';
		}

		$out = "<div class='chip chip-info chip-no-bg {$class} {$data['class']}'>";
		$out .= $icon;
		$out .= $label;
		$out .= "</div>";

		return $out;
	}


}
