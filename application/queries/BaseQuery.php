<?php

namespace app\queries;

class BaseQuery
{


	protected $model = NULL;
	protected $data = [];


	public function __construct($model)
	{
		$this->CI =& get_instance();
		$this->db = $this->CI->db;
		$this->model = $model;
	}


	public function joins()
	{
		return [];
	}


	public function columns()
	{
		$columns = [
			"{$this->model->table}.*",
		];

		return $columns;
	}


	public function get_include()
	{
		$out = [];

		if (array_key_exists('include', $this->data)) {
			if ( ! is_array($this->data['include'])) {
				$out = explode(',', $this->data['include']);
				$out = array_filter($out, 'trim');
			} else {
				$out = $this->data['include'];
			}
		}

		return $out;
	}


	public function set_data($data = [])
	{
		$this->data = $data;
	}


	public function get_select()
	{
		$select = [];
		$columns = $this->columns();

		if (empty($columns)) {
			$columns = ['*'];
		}

		foreach ($columns as $alias => $src) {
			if (is_numeric($alias)) {
				$select[] = $src;
				continue;
			}

			$select[] = "{$src} AS `{$alias}`";
		}

		return "SELECT " . implode(",\n", $select);
	}


	public function get_count()
	{
		if ($this->model->primary_key) {
			$count = "DISTINCT " . $this->db->escape_identifiers("{$this->model->table}.{$this->model->primary_key}");
		} else {
			$count = "*";
		}

		return "SELECT COUNT($count) AS `total`";
	}


	public function get_from()
	{
		return "FROM `{$this->model->table}`";
	}


	public function get_joins()
	{
		return implode("\n", $this->joins());
	}


	public function get_where()
	{
		$out = "WHERE 1 = 1";
		$params = [];

		$skip = ['limit', 'offset', 'sort', 'include'];

		// Lazyload the table column info the first time we need it
		if (empty($this->model->table_columns) && ! empty($this->model->table)) {
			$this->model->table_columns = $this->db->list_fields($this->model->table);
		}

		foreach ($this->data as $col => $value) {

			if (in_array($col, $skip)) continue;

			$fn_name = "filter_{$col}";

			// Query strings can't have '.' char (for tables). Replace | with ..
			$col = str_replace('|', '.', $col);

			$is_dotted = (strpos($col, '.') !== FALSE);
			$is_column = in_array($col, $this->model->table_columns);
			$is_fn = method_exists($this, $fn_name);

			if ($is_fn && ! $is_dotted) {
				$res = $this->{$fn_name}($col, $value, $this->data);
				if (strlen($res)) {
					$res = "({$res})";
				}
				$params[] = $res;
				continue;
			}

			if ($is_column || $is_dotted) {

				$col = ($is_dotted ? $col : "{$this->model->table}.{$col}");
				$col = $this->db->escape_identifiers($col);

				if ($value === NULL) {
					$params[] = "({$col} IS NULL)";
					continue;
				}

				// `col` = 'x'
				if (is_scalar($value)) {

					// No actual value? Carry on.
					// if (strlen($value) === 0) {
					// 	continue;
					// }

					$value = $this->db->escape($value);
					$params[] = "({$col} = {$value})";
					continue;
				}

				// `col` IN (x, y, ...)
				if (is_array($value)) {
					$value_items = [];
					foreach ($value as $value_item) {
						$value_items[] = $this->db->escape($value_item);
					}
					if ( ! empty($value_items)) {
						$in_str = implode(',', $value_items);
						$params[] = "( {$col} IN ({$in_str}) )";
						continue;
					}
				}

			}
		}

		$params = array_filter($params, 'strlen');
		if ( ! empty($params)) {
			$params_str = implode("\nAND ", $params);
			$out .= "\nAND {$params_str}";
		}

		return $out;
	}


	public function get_limit()
	{
		$offset = 0;
		$limit = 10;

		if (array_key_exists('limit', $this->data)) {
			// No limit if value supplied is `NULL`
			if ($this->data['limit'] === NULL) {
				return '';
			}

			$limit = (int) $this->data['limit'];
		}

		if (array_key_exists('offset', $this->data)) {
			$offset = (int) $this->data['offset'];
		}

		return "LIMIT {$offset}, {$limit}";
	}


	public function get_group_by()
	{
		if ($this->model->primary_key) {
			$cols = $this->db->escape_identifiers("{$this->model->table}.{$this->model->primary_key}");
			return "GROUP BY {$cols}";
		}

		return '';
	}


	public function get_order_by()
	{
		$orders = [];

		if (array_key_exists('sort', $this->data)) {
			$sort_cols = explode(',', $this->data['sort']);
			$sort_cols = array_filter($sort_cols, 'trim');
			foreach ($sort_cols as $sort_col) {
				$sort_col = trim($sort_col);
				$dir = ($sort_col[0] === '-' ? 'DESC' : 'ASC');
				$col = str_replace('-', '', $sort_col);
				$col = $this->db->escape_identifiers($col);
				$orders[] = "{$col} {$dir}";
			}
		}

		if (empty($orders)) {
			return '';
		}

		return "ORDER BY " . implode(", ", $orders);
	}


	/**
	 * Get SQL query for result
	 *
	 */
	public function result_sql()
	{
		$parts = [
			$this->get_select(),
			$this->get_from(),
			$this->get_joins(),
			$this->get_where(),
			$this->get_group_by(),
			$this->get_order_by(),
			$this->get_limit(),
		];

		$parts = array_filter($parts, 'strlen');
		return implode(" \n", $parts);
	}


	/**
	 * Get SQL query for counting results
	 *
	 */
	public function count_sql()
	{
		$parts = [
			$this->get_count(),
			$this->get_from(),
			$this->get_joins(),
			$this->get_where(),
		];

		$parts = array_filter($parts, 'strlen');
		return implode(" \n", $parts);
	}


	/**
	 * Array of result objects
	 *
	 */
	public function result()
	{
		$sql = $this->result_sql();
		$query = $this->db->query($sql);
		return ($query->num_rows() > 0 ? $query->result() : []);
	}


	/**
	 * Single row result object
	 *
	 */
	public function row()
	{
		$sql = $this->result_sql();
		$query = $this->db->query($sql);
		return ($query->num_rows() === 1 ? $query->row() : FALSE);
	}


	/**
	 * Integer row result count
	 *
	 */
	public function count()
	{
		$sql = $this->count_sql();
		$query = $this->db->query($sql);
		if ($query->num_rows() === 1) {
			$row = $query->row();
			return $row->total;
		}

		return 0;
	}


}
