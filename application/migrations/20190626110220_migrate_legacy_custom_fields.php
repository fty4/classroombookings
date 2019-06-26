<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Migrate_legacy_custom_fields extends CI_Migration
{


	public function up()
	{
		$this->migrate_legacy_fields();
	}


	public function down()
	{
	}


	public function migrate_legacy_fields()
	{
		$data = $this->get_current_data();

		foreach ($data['fields'] as $field) {
			$new_field = $this->create_field($field, $data['options']);
			$this->migrate_data($new_field, $data['values']);
		}
	}


	private function get_current_data()
	{
		$fields = [];
		$values = [];
		$options = [];

		$query = $this->db->get('roomfields');
		foreach ($query->result() as $row) {
			$row->field_id = $row->field_id + 10;
			$fields[ $row->field_id ] = $row;
		}

		$query = $this->db->get('roomvalues');
		foreach ($query->result() as $row) {
			$row->field_id = $row->field_id + 10;
			$values[ $row->field_id ][] = $row;
		}

		$query = $this->db->get('roomoptions');
		foreach ($query->result() as $row) {
			$row->field_id = $row->field_id + 10;
			$options[ $row->field_id ][] = $row;
		}

		return compact('fields', 'values', 'options');
	}


	private function convert_type($old_type)
	{
		$lookup = [
			'TEXT' => 'text_single',
			'CHECKBOX' => 'checkbox',
			'SELECT' => 'select',
		];

		if (array_key_exists($old_type, $lookup)) {
			return $lookup[$old_type];
		}

		return 'text_single';
	}


	private function create_field($old_config, $options = [])
	{
		$name = url_title($old_config->name, '_', TRUE);

		// New field config
		$data = [
			'field_id' => $old_config->field_id,
			'entity' => 'RM',
			'type' => $this->convert_type($old_config->type),
			'title' => $old_config->name,
			'name' => $name,
			'hint' => NULL,
			'required' => 0,
			'position' => $this->get_next_position(),
		];

		// Get options if it is a SELECT
		if ($old_config->type == 'SELECT') {
			// get options
			if (array_key_exists($old_config->field_id, $options)) {
				$new_options = [];
				$old_options = $options[$old_config->field_id];
				foreach ($old_options as $opt) {
					$new_options[ $opt->option_id ] = $opt->value;
				}
				$value = serialize($new_options);
				$value = 'b64:' . base64_encode($value);
				$data['options'] = $value;
			}
		}

		$this->db->insert('fields', $data);

		// Create field data table

		$schema = [
			'entity_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			],
			'data' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE,
			],
		];

		$table_name = "field_{$name}_{$old_config->field_id}";

		$this->dbforge->add_field($schema);
		$this->dbforge->add_key('entity_id', TRUE);
		$this->dbforge->create_table($table_name, TRUE, array('ENGINE' => 'InnoDB'));

		$query = $this->db->get_where('fields', array('field_id' => $data['field_id']), 1);
		$row = $query->row();
		return $row;
	}


	private function get_next_position()
	{
		$sql = 'SELECT MAX(position) AS last_pos FROM fields';
		$query = $this->db->query($sql);
		$row = $query->row();
		$next_pos = ( (int) $row->last_pos) + 1;
		return $next_pos;
	}


	private function migrate_data($field, $values = [])
	{
		if ( ! array_key_exists($field->field_id, $values)) {
			return TRUE;
		}

		$table_name = "field_{$field->name}_{$field->field_id}";
		$rows = [];

		foreach ($values[ $field->field_id ] as $value) {
			$rows[] = [
				'entity_id' => $value->room_id,
				'data' => $value->value,
			];
		}

		$this->db->insert_batch($table_name, $rows);
	}


}
