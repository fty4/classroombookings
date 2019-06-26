<?php

namespace app\queries;

class FieldQuery extends BaseQuery
{


	protected $fields = [];


	public function set_fields($fields = [])
	{
		$this->fields = $fields;
		return $this;
	}



	public function columns()
	{
		$columns = [];

		foreach ($this->fields as $field) {
			$table = $this->CI->fields_model->get_table_name($field);
			$alias = "field_{$field->field_id}";
			$columns[$alias] = "`{$table}`.`data`";
		}

		return $columns;
	}


	public function joins()
	{
		$joins = [];

		foreach ($this->data as $k => $v) {
			if (preg_match('/^link_fields_rooms\./', $k)) {
				$joins[] = 'LEFT JOIN link_fields_rooms USING (field_id)';
				break;
			}
		}

		foreach ($this->fields as $field) {

			switch ($field->entity) {
				case 'RM': $join_with = 'room_id'; break;
				case 'BK': $join_with = 'booking_id'; break;
			}

			$table = $this->CI->fields_model->get_table_name($field);

			$joins[] = "LEFT JOIN `{$table}` ON `{$join_with}` = `{$table}`.`entity_id`";
		}

		return $joins;
	}


}
