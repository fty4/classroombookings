<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class MY_Model extends CI_Model
{


	/**
	 * Which table this class is for.
	 *
	 * @var string
	 */
	public $table = '';


	public function __construct()
	{
		parent::__construct();
	}


	public function find_one($params = array())
	{
		$this->db->where($params);
		$query = $this->db->get($this->table, 1);
		if ($query->num_rows() === 1) {
			return $query->row();
		}
		return false;
	}


	public function find($params = array(), $options = array())
	{
		$this->db->where($params);

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

		if ($query->num_rows() === 1) {
			return $query->row();
		}

		return false;
	}


	/*function Get($user_id = NULL, $pp = 10, $start = 0)
	{
		if ($user_id == NULL) {
			return $this->crud_model->Get('users', NULL, NULL, NULL, 'enabled asc, username asc', $pp, $start);
		} else {
			return $this->crud_model->Get('users', 'user_id', $user_id);
		}
	}*/


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
	function Delete($id)
	{
		$this->db->where('user_id', $id);
		$this->db->delete('bookings');

		$set = array('user_id' => 0);
		$where = array('user_id' => $id);
		$this->db->update('rooms', $set, $where);

		$this->db->where('user_id', $id);
		return $this->db->delete('users');
	}


}
