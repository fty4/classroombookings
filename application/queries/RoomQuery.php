<?php

namespace app\queries;

class RoomQuery extends BaseQuery
{


	public function columns()
	{
		$include = $this->get_include();
		$columns = parent::columns();

		if (in_array('_owner', $include)) {
			$columns['owner.displayname'] = 'users.displayname';
			$columns['owner.firstname'] = 'users.firstname';
			$columns['owner.lastname'] = 'users.lastname';
			$columns['owner.username'] = 'users.username';
		}

		return $columns;
	}


	public function joins()
	{
		$include = $this->get_include();
		$joins = [];

		if (in_array('_owner', $include)) {
			$joins[] = "LEFT JOIN users ON rooms.user_id = users.user_id";
		}

		foreach ($this->data as $k => $v) {
			if (preg_match('/^link_fields_rooms\./', $k)) {
				$joins[] = 'LEFT JOIN link_fields_rooms USING (room_id)';
				break;
			}
		}

		return $joins;
	}


}
