<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Rooms_model extends MY_Model
{


	public $table = 'rooms';
	public $primary_key = 'room_id';


	public function __construct()
	{
		parent::__construct();
	}


	public function insert($data = [])
	{
		$insert = parent::insert($data);

		if ($insert && $insert > 0 && array_key_exists('custom_fields', $data)) {
			$row = self::find_one(['room_id' => $insert]);
			$this->fields_model->save_field_values('RM', $insert, $data['custom_fields']);
		}

		return $insert;
	}


	public function update($data = array(), $where = array())
	{
		$update = parent::update($data, $where);

		if ($update && array_key_exists('custom_fields', $data)) {
			$row = self::find_one($where);
			$this->fields_model->save_field_values('RM', $row->room_id, $data['custom_fields']);
		}

		return $update;
	}


	public function delete($where = array(), $limit = 1)
	{
		$row = self::find_one($where);
		$delete = parent::delete($where, $limit);
		if ($delete) {
			$this->fields_model->delete_field_values('RM', $row->room_id);
		}

		return $delete;
	}


	public function wake_values($row, $find_query = NULL)
	{
		$row->user = null;
		$row->custom_fields = [];

		if ($find_query) {

			$include = $find_query->get_include();

			if (in_array('user', $include)) {
				$row = $this->populate_user($row);
			}

			if (in_array('custom_fields', $include)) {
				$row = $this->populate_custom_fields($row);
			}

		}

		return $row;
	}


	public function populate_user($row)
	{
		$this->load->model('users_model');

		$user = $this->users_model->find_one([
			'user_id' => $row->user_id,
		]);

		$row->user = $user;

		return $row;
	}


	public function populate_custom_fields($row)
	{
		$this->load->model('fields_model');
		$row->custom_fields = $this->fields_model->get_field_values('RM', $row->room_id);
		return $row;
	}


	/**
	 * Gets room ID and name of one room owned by the given user id
	 *
	 * @param	int	$school_id	School ID
	 * @param	int	$user_id	ID of user to lookup
	 * @return	mixed	object if result, false on no results
	 *
	 */
	function _GetByUser($user_id)
	{
		$user_id = (int) $user_id;

		$sql = "SELECT room_id, name
				FROM rooms
				WHERE user_id={$user_id}
				ORDER BY name
				LIMIT 1";

		$query = $this->db->query($sql);

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return FALSE;
		}
	}


}
