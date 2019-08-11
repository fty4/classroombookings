<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Fields_model extends MY_Model
{


	public $table = 'fields';
	public $primary_key = 'field_id';
	public $query_class = 'FieldQuery';


	public function __construct()
	{
		parent::__construct();
	}


	public function get_entities()
	{
		return [
			'BK' => lang('fields_entity_BK'),
			'RM' => lang('fields_entity_RM'),
		];
	}


	public function get_types()
	{
		return [
			'text_single' => lang('fields_type_text_single'),
			'text_multi' => lang('fields_type_text_multi'),
			'checkbox' => lang('fields_type_checkbox'),
			'select' => lang('fields_type_select'),
		];
	}


	/**
	 * Save a field entry: Generate and encode values before saving to database.
	 *
	 */
	public function sleep_values($data = [])
	{
		// On creating a new field, generate an internal name based on the title.
		// Used to make the table name
		if ( ! array_key_exists('field_id', $data) || empty($data['field_id'])) {
			$name = url_title($data['title'], '_', TRUE);
			$data['name'] = substr($name, 0, 54);
		}

		// If we have options, encode them for storage
		if (array_key_exists('options', $data) && strlen($data['options'])) {
			$data['options'] = $this->encode_options($data['options']);
		}

		return parent::sleep_values($data);
	}


	/**
	 * On loading data from the database, parse and transform values
	 *
	 */
	public function wake_values($row, $find_query = NULL)
	{
		$row->options = $this->decode_options($row->options);
		$row->rooms = [];

		if ($find_query) {

			$include = $find_query->get_include();

			if ($row->entity == 'BK' && in_array('rooms', $include)) {
				$row = $this->populate_rooms($row);
			}
		}

		return $row;
	}



	public function populate_rooms($row)
	{
		$this->load->model('rooms_model');

		$row->rooms = $this->rooms_model->find([
			'link_fields_rooms.field_id' => $row->field_id,
			'limit' => NULL,
		]);

		return $row;
	}


	/**
	 * Add new field entry.
	 *
	 */
	public function insert($data = [])
	{
		$insert = parent::insert($data);

		if ($insert && $insert > 0) {

			// Create the field data storage table itself
			$row = $this->find_one(['field_id' => $insert]);
			$this->create_field($row);

			// Set the rooms that it can be used in, if RM entity.
			if ($data['entity'] == 'BK') {
				$set_rooms = $this->set_link_table([
					'table' => 'link_fields_rooms',
					'local_field' => 'field_id',
					'local_value' => $insert,
					'foreign_field' => 'room_id',
					'values' => isset($data['room_ids']) ? $data['room_ids'] : [],
				]);
			}

		}

		return $insert;
	}


	public function update($data = array(), $where = array())
	{
		$update = parent::update($data, $where);

		if ($update) {
			$row = $this->find_one($where);
			if ($row->entity == 'BK') {
				$set_rooms = $this->set_link_table([
					'table' => 'link_fields_rooms',
					'local_field' => 'field_id',
					'local_value' => $row->field_id,
					'foreign_field' => 'room_id',
					'values' => isset($data['room_ids']) ? $data['room_ids'] : [],
				]);
			}
		}
		return $update;
	}


	/**
	 * Delete a field: Drop the storage table as well
	 *
	 */
	public function delete($where = array(), $limit = 1)
	{
		$row = $this->find_one($where);

		$delete = parent::delete($where, $limit);

		if ($delete) {
			$table_name = $this->get_table_name($row);
			$this->load->dbforge();
			$this->dbforge->drop_table($table_name, TRUE);
		}

		return $delete;
	}


	/**
	 * Encode the options string into an encoded array for storage. Called via sleep_values.
	 *
	 * @param  string $options		As submitted by form, one item per line. Optionally id=item
	 * @return  string Value of options to save to DB.
	 *
	 */
	public function encode_options($options)
	{
		$items = [];

		$input = explode("\n", $options);

		$i = 1;
		foreach ($input as $line) {
			if (strpos($line, '=') !== FALSE) {
				list($i, $line) = explode('=', $line);
			}
			$line = trim($line);
			if (empty($line)) {
				continue;
			}
			$items[$i] = trim($line);
			$i++;
		}

		$value = serialize($items);
		$value = 'b64:' . base64_encode($value);
		return $value;
	}


	/**
	 * On wakeup: Decode the options data into an array (as stored by `encode_options()`)
	 *
	 * @param  string Encoded data from DB
	 *
	 */
	public function decode_options($value = '')
	{
		if (substr($value, 0, 4) === 'b64:') {
			$value = substr($value, 4);
			$value = base64_decode($value);
		}

		$data = @unserialize($value);
		if ($data !== FALSE) {
			return $data;
		}

		return [];
	}


	/**
	 * Given a row for a field, using that information, create the DB table to hold its data.
	 *
	 */
	public function create_field($row)
	{
		$this->load->dbforge();

		$schema = $this->get_schema($row);

		$this->dbforge->add_field($schema);
		$this->dbforge->add_key('entity_id', TRUE);

		$this->dbforge->create_table($this->get_table_name($row), TRUE, array('ENGINE' => 'InnoDB'));
	}


	/**
	 * Given a row for a field, generate the table name that should be used to hold its data.
	 *
	 */
	public function get_table_name($row)
	{
		return "field_{$row->name}_{$row->field_id}";
	}


	public function get_next_position()
	{
		$sql = 'SELECT MAX(position) AS last_pos FROM fields';
		$query = $this->db->query($sql);
		$row = $query->row();
		$next_pos = ( (int) $row->last_pos) + 1;
		return $next_pos;
	}

	/**
	 * Given a row for a field, generate the required schema for the DB table.
	 *
	 */
	public function get_schema($field)
	{
		$schema = [
			'entity_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			],
		];

		switch ($field->type) {
			case 'text_single':
				$schema['data'] = [
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null' => TRUE,
				];
			break;
			case 'text_multi':
				$schema['data'] = [
					'type' => 'TEXT',
					'null' => TRUE,
				];
			break;
			case 'checkbox':
				$schema['data'] = [
					'type' => 'TINYINT',
					'constraint' => 1,
					'unsigned' => TRUE,
					'null' => FALSE,
					'default' => 0,
				];
			break;
			case 'select':
				$schema['data'] = [
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'null' => TRUE,
				];
			break;
			default:
				throw new Exception("Unrecognised field type");
		}

		return $schema;
	}


	/**
	 * Get configured fields by entity type
	 *
	 * @param  string entity type (BK/RM)
	 * @return  array Result array of fields for entity type.
	 *
	 */
	public function fields_by_entity($entity)
	{
		$fields = parent::find([
			'entity' => $entity,
			'sort' => 'position, title',
			'limit' => NULL,
		]);

		return $fields;
	}


	/**
	 * Get all relevant fields for given entity type + ID and their values.
	 *
	 * @param  string $entity		Type of entity (BK/RM)
	 * @param  int $id		ID of entity
	 * @param  array Result array of fields for entity type, and values (`value` property) for entity of given ID.
	 *
	 */
	public function get_field_values($entity, $entity_id = NULL)
	{
		if ($entity_id === NULL) {
			return $this->fields_by_entity($entity);
		}

		switch ($entity) {
			case 'BK':
				$this->load->model('bookings_model');
				$query = new app\queries\FieldQuery($this->bookings_model);
				$data = ['booking_id' => $entity_id];
			break;
			case 'RM':
				$this->load->model('rooms_model');
				$query = new app\queries\FieldQuery($this->rooms_model);
				$data = ['room_id' => $entity_id];
			break;
		}

		$query->set_data($data);

		$fields = $this->fields_by_entity($entity);
		$query->set_fields($fields);

		$values = $query->row();

		foreach ($fields as &$field) {
			$key = "field_{$field->field_id}";
			$value = get_property($key, $values);
			$field->value = $value;
		}

		return $fields;
	}


	public function get_validation_rules($entity)
	{
		$out = [];

		$fields = $this->fields_by_entity($entity);

		foreach ($fields as $field) {

			$key = "field_{$field->field_id}";
			$post_field = "custom_fields[$key]";

			$rules = [];

			if ($field->required == '1') {
				$rules[] = 'required';
			}

			switch ($field->type) {

				case 'text_single':
					$rules[] = 'max_length[255]';
				break;

				case 'text_multi':
					$rules[] = 'max_length[65535]';
				break;

				case 'checkbox':
					$rules[] = 'exact_length[1]';
					$rules[] = 'in_list[0,1]';
				break;

				case 'select':
					$rules[] = 'is_natural';
				break;
			}

			$out[] = [
				'field' => $post_field,
				'label' => $field->title,
				'rules' => $rules,
			];
		}

		return $out;
	}


	public function save_field_values($entity, $entity_id = NULL, $data = [])
	{
		$fields = $this->fields_by_entity($entity);

		foreach ($fields as $field) {

			$table = $this->get_table_name($field);
			$key = "field_{$field->field_id}";

			$value = array_key_exists($key, $data) ? $data[$key] : NULL;
			if ( ! strlen($value)) {
				$value = NULL;
			}

			$row = [
				'entity_id' => $entity_id,
				'data' => $value,
			];

			$this->db->replace($table, $row);
		}

		return TRUE;
	}


	public function delete_field_values($entity, $entity_id)
	{
		$fields = $this->fields_by_entity($entity);

		foreach ($fields as $field) {
			$table = $this->get_table_name($field);
			$where = ['entity_id' => $entity_id];
			$this->db->delete($table, $where, 1);
		}

		return TRUE;
	}


}
