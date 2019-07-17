<?php

namespace app\queries;

class UserQuery extends BaseQuery
{


	public function filter_username_like($col, $value, $data = [])
	{
		if (strlen($value)) {
			$username = $this->db->escape_like_str($value);
			return " username LIKE '%{$username}%' ";
		}

		return '';
	}


	public function filter_name_like($col, $value, $data = [])
	{
		if (strlen($value)) {
			$value = $this->db->escape_like_str($value);
			return " displayname LIKE '%{$value}%' OR firstname LIKE '%{$value}%' OR lastname LIKE '%{$value}%' ";
		}

		return '';
	}


}
