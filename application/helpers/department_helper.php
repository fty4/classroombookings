<?php

class DepartmentHelper
{

	use app\helpers\DataTrait;


	public static function icon($department, $params = [])
	{
		$defaults = [
			'class' => '',
			'label' => FALSE,
		];

		$data = array_merge($defaults, $params);

		$class = $data['class'];
		$class .= ' chip-department icon-department';

		$icon = $department->icon;
		$colour = $department->colour;

		$out = "<span class='chip chip-info chip-no-bg {$class}'>";
		$out .= icon($icon, ['color' => "#{$colour}"]);
		if ($data['label']) {
			$out .= $department->name;
		}
		$out .= "</span>";

		return $out;
	}


}
