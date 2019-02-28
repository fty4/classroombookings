<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function lang($line, $vars = array())
{
	$line = get_instance()->lang->line($line);

	if ( ! empty($data)) {
		$line = get_instance()->parser->parse_string($line, $vars, TRUE);
	}

	return $line;
}
