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


	public function wake_values($row, $find_query = NULL)
	{
		$row->user = null;

		if ($find_query) {

			$include = $find_query->get_include();

			if (in_array('user', $include)) {
				$row = $this->populate_user($row);
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


	// const FIELD_CHECKBOX = 'CHECKBOX';
	// const FIELD_SELECT = 'SELECT';
	// const FIELD_TEXT = 'TEXT';

	// public $options = array();


	// public function __construct()
	// {
	// 	parent::__construct();

	// 	$this->options[self::FIELD_CHECKBOX] = 'Checkbox';
	// 	$this->options[self::FIELD_SELECT] = 'Dropdown list';
	// 	$this->options[self::FIELD_TEXT] = 'Text';
	// }




	/**
	 * Retrieve all rooms
	 *
	 * @return				array				All items in table
	 *
	 */
	function _Get($room_id = NULL)
	{
		$this->db->select(
			'rooms.*,'
			.'users.user_id,'
			.'users.username,'
			.'users.displayname'
		);
		$this->db->from('rooms');
		$this->db->join('users', 'users.user_id = rooms.user_id', 'left');

		if ($room_id != NULL) {
			// Getting one specific room
			$this->db->where('room_id', $room_id);
			$this->db->limit('1');
			$query = $this->db->get();
			if ($query->num_rows() == 1) {
				return $query->row();
			} else {
				return FALSE;
			}
		} else {
			$this->db->order_by('name asc');
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->result();
			} else {
				return FALSE;
			}
		}
	}




	/**
	 * Gets all information on the room - joins all the fields as well
	 *
	 */
	function _GetInfo($room_id)
	{
		$data = array();

		// Query for room
		//

		$this->db->select(array(
			'rooms.*',
			'users.user_id',
			'users.username',
			'users.displayname',
		));

		$this->db->from('rooms');
		$this->db->join('users', 'users.user_id = rooms.user_id', 'left');
		$this->db->where('rooms.room_id', $room_id);

		$query = $this->db->get();

		$data['room'] = $query->row();

		// Query for fields data
		//

		$this->db->select(array(
			'roomfields.*',
			'roomoptions.*',
			'roomvalues.*',
		));

		$this->db->from('roomvalues');
		$this->db->join('roomoptions', 'roomoptions.field_id = roomvalues.field_id', 'left');
		$this->db->join('roomfields', 'roomfields.field_id = roomvalues.field_id');
		$this->db->where('roomvalues.room_id', $room_id);

		$query = $this->db->get();

		$data['fields'] = $query->result();

		return $data;
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




	function _add($data)
	{
		// Run query to insert blank row
		$this->db->insert('rooms', array('room_id' => NULL));
		// Get id of inserted record
		$room_id = $this->db->insert_id();
		// Now call the edit function to update the actual data for this new row now we have the ID
		$this->edit($room_id, $data);
		return $room_id;
	}




	function _edit($room_id, $data)
	{
		$this->db->where('room_id', $room_id);
		$result = $this->db->update('rooms', $data);
		// Return bool on success
		if ($result){
			return true;
		} else {
			return false;
		}
	}




	/**
	 * Deletes a room with the given ID
	 *
	 * @param   int   $id   ID of room to delete
	 *
	 */
	function _delete($id)
	{
		$this->delete_photo($id);
		$this->db->where('room_id', $id);
		return $this->db->delete('rooms');
	}





	/**
	 * Deletes a photo
	 *
	 */
	function _delete_photo($room_id)
	{
		$row = $this->Get($room_id);
		$photo = $row->photo;
		$file_path = FCPATH . "uploads/{$photo}";
		if (file_exists($file_path)) {
			@unlink($file_path);
		}
		$this->db->where('room_id', $room_id);
		$this->db->update('rooms', array('photo' => ''));
		return TRUE;
	}





	/**
	 * Get room fields
	 *
	 */
	function _GetFields($field_id = NULL)
	{
		$this->db->select('*');
		$this->db->from('roomfields');

		if ($field_id != NULL) {

			// Getting one specific field
			$this->db->where('roomfields.field_id', $field_id);
			$this->db->limit('1');
			$query = $this->db->get();

			if ($query->num_rows() == 1) {
				// One row, match!
				$row = $query->row();
				$row->options = $this->GetOptions($field_id);
				return $row;
			} else {
				// None
				return FALSE;
			}

		} else {

			// Getting all
			$this->db->order_by('roomfields.type asc, roomfields.name asc');
			$query = $this->db->get();

			if ($query->num_rows() > 0) {

				// Got some rooms, return result
				$result = $query->result();

				foreach ($result as $item) {
					if ($item->type == self::FIELD_SELECT) {
						$item->options = $this->GetOptions($item->field_id);
					}
				}

				return $result;
			} else {
				// No rooms!
				return FALSE;
			}
		}
	}




	function _field_add($data)
	{
		// Run query to insert blank row
		$this->db->insert('roomfields', array('field_id' => NULL));
		// Get id of inserted record
		$field_id = $this->db->insert_id();
		// Now call the edit function to update the actual data for this new row now we have the ID
		$this->field_edit($field_id, $data);
		return $field_id;
	}




	function _field_edit($field_id, $data)
	{
		// We don't add the options column to the roomfields table, so get it then remove it from the array that gets added
		$options = $data['options'];
		unset($data['options']);

		$this->db->where('field_id', $field_id);
		$result = $this->db->update('roomfields', $data);

		// Delete row options of the field
		//		We don't yet know if the type is a SELECT, but we delete
		//		them first anyway incase they changed it
		//		from a SELECT to something else. The new options get inserted next.
		$this->delete_field_options($field_id);

		if ($data['type'] == self::FIELD_SELECT) {

			// Explode at newline into array
			$arr_options = explode("\n", $options);

			// Loop through options and insert a new row for each one
			foreach ($arr_options as $key => $value) {
				$this->db->insert('roomoptions', array(
					'field_id' => $field_id,
					'value' => $value,
				));
			}

		}

		return ($result ? TRUE : FALSE);
	}




	/**
	 * Deletes a field with the given ID
	 *
	 * @param   int   $id   ID of field to delete
	 *
	 */
	function _field_delete($id)
	{
		$this->db->where('field_id', $id);
		$this->db->delete('roomfields');
		$this->db->where('field_id', $id);
		$this->db->delete('roomvalues');
	}




	/**
	 * Get options for a field
	 *
	 */
	function _GetOptions($field_id)
	{
		$this->db->select('*');
		$this->db->from('roomoptions');
		$this->db->order_by('value asc');
		$this->db->where('field_id', $field_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}




	/**
	 * Delete all options for a given field
	 *
	 */
	function _delete_field_options($field_id)
	{
		$this->db->where('field_id', $field_id);
		return $this->db->delete('roomoptions');
	}





	function _save_field_values($room_id, $data)
	{
		$this->db->where('room_id', $room_id);
		$this->db->delete('roomvalues');

		foreach ($data as $field_id => $value) {
			$this->db->insert('roomvalues', array(
				'room_id' => $room_id,
				'field_id' => $field_id,
				'value' => $value,
			));
		}

		return TRUE;
	}




	function _GetFieldValues($room_id)
	{
		$this->db->select('field_id, value');
		$this->db->from('roomvalues');
		$this->db->order_by('value_id asc');
		$this->db->where('room_id', $room_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$result = $query->result();
			$values = array();
			foreach ($result as $item) {
				$values[$item->field_id] = $item->value;
			}
			return $values;
		} else {
			return array();
		}
	}




}
