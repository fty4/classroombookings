<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Given a whitelist of safe keys $keys, fill a clean array with those $keys from $source.
 *
 */
function array_fill_safe($keys = [], $source = [])
{
	$out = [];

	foreach ($keys as $key) {
		if (array_key_exists($key, $source)) {
			$out[$key] = $source[$key];
		}
	}

	return $out;
}
