<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class MY_Model extends CI_Model
{


	/**
	 * Which table this class is for.
	 *
	 * @var string
	 *
	 */
	public $table = '';


	/**
	 * Primary key for the table
	 *
	 * @var string
	 *
	 */
	public $primary_key = '';


	/**
	 * Class name used for building queries
	 *
	 * @var string
	 *
	 */
	public $query_class = 'BaseQuery';


	/**
	 * Columns for the table.
	 *
	 * @var array
	 *
	 */
	public $table_columns = [];


	public $blame = [
		'created' => 'created_by',
		'updated' => 'updated_by',
	];


	public $timestamp = [
		'created' => 'created_at',
		'updated' => 'updated_at',
	];


	public function __construct()
	{
		parent::__construct();
	}



	public function get_query_instance()
	{
		$class = "app\\queries\\{$this->query_class}";
		return new $class($this);
	}


	public function count($data = [])
	{
		$find_query = $this->get_query_instance();
		$find_query->set_data($data);
		return $find_query->count();
	}


	public function find($data = [])
	{
		$find_query = $this->get_query_instance();
		$find_query->set_data($data);

		$result = $find_query->result();
		foreach ($result as &$row) {
			$row = $this->wake_values($row, $find_query);
		}
		return $result;
	}


	public function find_one($data = [])
	{
		$data['limit'] = 1;
		$find_query = $this->get_query_instance();
		$find_query->set_data($data);
		$row = $find_query->row();
		if ($row) {
			return $this->wake_values($row, $find_query);
		}
		return FALSE;
	}


	public function insert($data = [])
	{
		$data = $this->populate_blame_data($data, 'created');
		$data = $this->populate_timestamp_data($data, 'created');
		$data = $this->sleep_values($data);

		$res = $this->db->insert($this->table, $data);

		if ($res) {
			return $this->db->insert_id();
		}

		return $res;
	}


	public function update($data = array(), $where = array())
	{
		$data = $this->populate_blame_data($data, 'updated');
		$data = $this->populate_timestamp_data($data, 'updated');
		$data = $this->sleep_values($data);

		$update = $this->db->update($this->table, $data, $where, 1);
		return $update;
	}


	public function delete($where = array(), $limit = 1)
	{
		return $this->db->delete($this->table, $where, $limit);
	}


	public function populate_blame_data($data = array(), $column = '')
	{
		if (empty($column)) {
			return $data;
		}

		if (is_array($this->blame) && array_key_exists($column, $this->blame)) {
			$field = $this->blame[$column];
			// $data[ $field ] = $this->auth->get_user()->user_id;
			$data[ $field ] = $this->userauth->user->user_id;
		}


		return $data;
	}


	public function populate_timestamp_data($data = array(), $column = '')
	{
		if (empty($column)) {
			return $data;
		}

		if (is_array($this->timestamp) && array_key_exists($column, $this->timestamp)) {
			$field = $this->timestamp[$column];
			$data[ $field ] = date('Y-m-d H:i:s');
		}

		return $data;
	}



	/**
	 * Sanitise the data to be inserted/updated.
	 *
	 * Checks that the fields exist (look at $this->_table_columns) and that the values are not uninsertable
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 *
	 */
	public function sleep_values($data = array())
	{
		// Lazyload the table column info the first time we need it
		if (empty($this->_table_columns) && ! empty($this->table)) {
			$this->_table_columns = $this->db->list_fields($this->table);
		}

		foreach ($data as $key => $value) {

			if (is_string($value) && $value === '') {
				$data[$key] = NULL;
			}

			// If we don't know about the table columns, then let it pass anyway
			$valid_key = (empty($this->_table_columns) ? TRUE : in_array($key, $this->_table_columns));
			// Scalar values only please
			$valid_value = (is_scalar($value) || $value === NULL);

			if ( ! $valid_key || ! $valid_value)
			{
				unset($data[$key]);
			}
		}

		return $data;
	}


	public function wake_values($row)
	{
		return $row;
	}


	public function items_to_array($items = [], $keyed_by = NULL)
	{
		$out = [];

		foreach ($items as $item) {

			if ($keyed_by !== NULL) {
				$key = get_property($keyed_by, $item);
				$out[$key] = $this->item_to_array($item);
			} else {
				$out[] = $this->item_to_array($item);
			}
		}

		return $out;
	}


	public function item_to_array($item)
	{
		$out = [];
		$str = json_encode($item);
		$arr = json_decode_safe($str, TRUE);
		return $arr;
	}


	public function set_link_table($params = [])
	{
		$defaults = [
			'table' => '',
			'local_field' => '',
			'local_value' => '',
			'foreign_field' => '',
			'values' => [],
		];

		$data = array_merge($defaults, $params);
		extract($data);

		if (empty($table) || empty($local_field) || empty($local_value) || empty($foreign_field)) {
			return FALSE;
		}

		$this->db->delete($table, [$local_field => $local_value]);

		if ( ! is_array($values)) {
			$values = [];
		}

		$inserts = [];
		foreach ($values as $v) {
			$inserts[] = [
				$local_field => $local_value,
				$foreign_field => $v,
			];
		}

		if ( ! empty($inserts)) {
			return $this->db->insert_batch($table, $inserts);
		}

		return TRUE;
	}


}
