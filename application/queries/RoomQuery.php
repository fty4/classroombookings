<?php

namespace app\queries;

class RoomQuery extends BaseQuery
{


	public function joins()
	{
		$joins = [];

		foreach ($this->data as $k => $v) {
			if (preg_match('/^link_fields_rooms\./', $k)) {
				$joins[] = 'LEFT JOIN link_fields_rooms USING (room_id)';
				break;
			}
		}

		return $joins;
	}


}
