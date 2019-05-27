<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function json_decode_safe($json = '', $as_array = TRUE)
{
	if (is_array($json)) {
		throw new Exception("Invalid JSON data");
	} elseif ($json === null || $json === '') {
		return NULL;
	}

	$decode = @json_decode( (string) $json, $as_array);
	$error = json_last_error();

	if ($error === JSON_ERROR_NONE) {
		return $decode;
	}

	throw new Exception("JSON parse error {$error}");
}


function json_encode_html($value)
{
	return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS);
}
