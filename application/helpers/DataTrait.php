<?php

namespace app\helpers;

defined('BASEPATH') OR exit('No direct script access allowed');


trait DataTrait
{


	public static function value_or_other($obj, $key, $other = 'Unknown')
	{
		if ( ! property_exists($obj, $key)) {
			return $other;
		}

		$value = $obj->$key;

		if ( ! strlen($value)) {
			return $other;
		}

		return html_escape($value);
	}


	public static function datetime($obj, $key, $format = 'd/m/Y', $unknown = '')
	{
		if ( ! property_exists($obj, $key)) {
			return $unknown;
		}

		$value = $obj->$key;

		if ( ! strlen($value) || $value == '0000-00-00' || $value == '0000-00-00 00:00:00') {
			return $unknown;
		}

		return nice_date($value, $format);
	}



}
