<?php

class UserHelper
{

	use app\helpers\DataTrait;


	public static function names($user, $other = '')
	{
		$str = "{$user->firstname} {$user->lastname}";

		$trimmed = trim($str);
		if ( ! strlen($trimmed)) {
			return $other;
		}

		return html_escape($str);
	}


	public static function best_display_name($user)
	{
		$strings = [];

		$strings[] = self::display_name($user, '');
		$strings[] = self::names($user, '');
		$strings[] = html_escape($user->username);

		$strings = array_filter($strings, 'strlen');
		return array_shift($strings);
	}


	public static function display_name($user, $other = '')
	{
		return empty($user->displayname) ? $other : html_escape($user->displayname);
	}


	public static function type($user)
	{
		if ($user->authlevel == ADMINISTRATOR) {
			return lang('user_authlevel_administrator');
		} elseif ($user->authlevel == TEACHER) {
			return lang('user_authlevel_teacher');
		}
	}


	public static function last_login($user, $unknown = '')
	{
		return self::datetime($user, 'lastlogin', 'd/m/Y H:i', $unknown);
	}


	public static function created_at($user, $unknown = '')
	{
		return self::datetime($user, 'created', 'd/m/Y', $unknown);
	}


	public static function status_chip($user, $params = [])
	{
		$defaults = [
			'class' => '',
		];

		$data = array_merge($defaults, $params);

		$class = $data['class'];

		if ($user->enabled == 1) {
			$icon = 'check';
			$class .= 'user-status-active';
			$label = lang('user_status_active');
		} else {
			$icon = 'x';
			$class .= 'user-status-inactive';
			$label = lang('user_status_inactive');
		}

		$out = "<div class='chip chip-info chip-no-bg {$class}'>";
		$out .= icon($icon);
		$out .= $label;
		$out .= "</div>";

		return $out;
	}


	public static function department($user, $other = '')
	{
		if ( ! empty($user->department) && is_object($user->department)) {
			return html_escape($user->department->name);
		}

		return $other;
	}


}
