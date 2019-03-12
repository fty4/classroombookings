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


	/**
	 * Find and return a single record or false if not exists.
	 *
	 */
	public function find_one($params = array())
	{
		$this->apply_params($params);

		$query = $this->db->get($this->table, 1);

		if ($query->num_rows() === 1) {
			return $this->wake_values($query->row());
		}
		return FALSE;
	}


	/**
	 * Find multiple records and return an array.
	 *
	 * @param  array  $params  [description]
	 * @param  array  $options [description]
	 * @return [type]          [description]
	 *
	 */
	public function find($params = array(), $options = array())
	{
		$this->apply_params($params);

		if (array_key_exists('limit', $options)) {
			$limit = $options['limit'];
			if (is_string($limit) && strpos($limit, ',')) {
				$limit = explode(',', $limit);
			}
			$parts = is_array($limit) ? $limit : array($limit);
			if (count($parts) == 2) {
				$this->db->limit($parts[0], $parts[1]);
			} else {
				$this->db->limit($parts[0]);
			}
		}

		if (array_key_exists('sort', $options)) {
			foreach ($options['sort'] as $col => $dir) {
				$this->db->order_by($col, $dir);
			}
		}

		$query = $this->db->get($this->table);

		if ($query->num_rows() > 0) {
			$result = $query->result();
			foreach ($result as &$row) {
				$row = $this->wake_values($row);
			}
			return $result;
		}

		return [];
	}


	public function insert($data = array())
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


	public function apply_params($where = array())
	{
		foreach ($where as $col => $value) {

			if (strpos($col, '.') === FALSE) {
				$col = "{$this->table}.{$col}";
			}

			$this->db->where($col, $value);
		}
	}


	function Add($data)
	{
		$query = $this->db->insert('users', $data);
		return ($query ? $this->db->insert_id() : $query);
	}


	function Edit($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		$result = $this->db->update('users', $data);
		return ($result ? $user_id : FALSE);
	}


	/**
	 * Delete a user
	 *
	 * @param   int   $id   ID of user to delete
	 *
	 */
	/*function Delete($id)
	{
		$this->db->where('user_id', $id);
		$this->db->delete('bookings');

		$set = array('user_id' => 0);
		$where = array('user_id' => $id);
		$this->db->update('rooms', $set, $where);

		$this->db->where('user_id', $id);
		return $this->db->delete('users');
	}*/


}
