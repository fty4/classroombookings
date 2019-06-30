<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function random_password($len = 8)
{
	$pool = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
	return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
}
