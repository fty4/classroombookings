<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Rooms_model extends MY_Model
{


	public $table = 'rooms';
	public $primary_key = 'room_id';
	public $query_class = 'RoomQuery';


	public function __construct()
	{
		parent::__construct();
	}


	public function insert($data = [])
	{
		$insert = parent::insert($data);

		if ($insert && $insert > 0) {

			// Save custom field values
			if (array_key_exists('custom_fields', $data)) {
				$row = self::find_one(['room_id' => $insert]);
				$this->fields_model->save_field_values('RM', $insert, $data['custom_fields']);
			}

			// Set the rooms that it can be used in, if RM entity.
			$set_bookings_fields = $this->set_link_table([
				'table' => 'link_fields_rooms',
				'local_field' => 'room_id',
				'local_value' => $insert,
				'foreign_field' => 'field_id',
				'values' => isset($data['booking_field_ids']) ? $data['booking_field_ids'] : [],
			]);
		}

		return $insert;
	}


	public function update($data = array(), $where = array())
	{
		$update = parent::update($data, $where);

		if ($update) {
			if (array_key_exists('custom_fields', $data)) {
				$row = self::find_one($where);
				$this->fields_model->save_field_values('RM', $row->room_id, $data['custom_fields']);
			}

			// Set the Bookings fields
			$row = $this->find_one($where);
			$set_bookings_fields = $this->set_link_table([
				'table' => 'link_fields_rooms',
				'local_field' => 'room_id',
				'local_value' => $row->room_id,
				'foreign_field' => 'field_id',
				'values' => isset($data['booking_field_ids']) ? $data['booking_field_ids'] : [],
			]);
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

			if (in_array('fields_bookings', $include)) {
				$row = $this->populate_fields_bookings($row);
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


	public function populate_fields_bookings($row)
	{
		$this->load->model('fields_model');

		$row->fields_bookings = $this->fields_model->find([
			'link_fields_rooms.room_id' => $row->room_id,
			'limit' => NULL,
		]);

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
