<?php
defined('BASEPATH') OR exit('No direct script access allowed');

namespace app\components;

/**
 * Filter form class
 *
 */
class Filter
{


	const RADIO_LIST = 'radio_list';
	const CHECK_LIST = 'check_list';
	const TEXT = 'text';
	const LIMIT = 'limit';


	/**
	 * Filter data
	 *
	 * @var  array
	 *
	 */
	public $data = [];


	/**
	 * URL that the filter is loaded on
	 *
	 * @var string
	 *
	 */
	public $base_url = '';


	/**
	 * Filter items
	 *
	 * @var array
	 *
	 */
	public $items = [];


	private $_filter_id;

	private $_default_item = [
		'visible' => TRUE,
		'size' => '',
		'label' => '',
		'name' => '',
		'type' => self::TEXT,
		'input' => FALSE,
		'options' => [],
		'blank' => FALSE,
	];


	public function __construct($config = [])
	{
		$this->CI =& get_instance();
		$this->initialise($config);
	}


	public function initialise($config = [])
	{
		foreach ($config as $key => $val) {
			if (isset($this->$key)) {
				$method = 'set_'.$key;
				if (method_exists($this, $method)) {
					$this->$method($val);
				} else {
					$this->$key = $val;
				}
			}
		}

		$this->filter_id = "fltr_" . uniqid();

		return $this;
	}


	public function set_items($items = [])
	{
		$this->clear_items();

		foreach ($items as $item) {
			$this->add_item($item);
		}

		return $this;
	}


	public function add_item($item = [])
	{
		$data = array_merge($this->_default_item, $item);
		$this->items[] = $data;

		return $this;
	}


	public function add_items($items = [])
	{
		foreach ($items as $item) {
			$this->add_item($item);
		}

		return $this;
	}


	public function clear_items()
	{
		$this->items = [];
		return $this;
	}


	public function render()
	{
		$items = [];

		$items[] = $this->render_done_check();

		foreach ($this->items as $item) {
			$items[] = $this->render_item($item);
		}

		$items_html = implode("\n", $items);

		return $items_html;
	}


	public function render_done_check()
	{
		return "<input type='radio' class='toggle-check toggle-check-filter' name='{$this->filter_id}' value='' id='{$this->filter_id}_done'>";
	}


	public function render_item($item = [])
	{
		$out = '';

		if ($item['visible'] == FALSE) {
			return $out;
		}

		$class_list = ['filter-dropdown'];
		if (strlen($item['name'])) {
			$class_list[] = "filter-dropdown-item-{$item['name']}";
		}

		$value = $this->get_value($item['name']);
		if (is_scalar($value) && strlen($value) && $item['type'] !== 'limit') {
			$class_list[] = 'has-value';
		}

		$input = "<input type='radio' class='toggle-check toggle-check-filter' name='{$this->filter_id}' value='{$item['name']}' id='{$this->filter_id}_{$item['name']}'>";

		$button = $this->render_item_button($item);
		$content = $this->render_item_content($item);

		$classes = implode(" ", $class_list);

		$out = "<div class='{$classes}'>\n{$button}\n{$input}\n{$content}</div>";

		return $out;
	}


	public function render_item_button($item = [])
	{
		$tab_index = tab_index();
		return "<label for='{$this->filter_id}_{$item['name']}' class='btn btn-filter' tabindex='{$tab_index}'>{$item['label']} <i class='icon icon-caret'></i></label>";
	}


	public function render_item_content($item = [])
	{
		$menu_content = '';

		if (empty($item['type']) && ! empty($item['input'])) {

			$menu_content = $item['input'];

		} else {

			switch ($item['type']) {

				case self::TEXT:
					$menu_content = $this->render_item_type_text($item);
				break;

				case self::CHECK_LIST:
					$menu_content = $this->render_item_type_check_list($item);
				break;

				case self::RADIO_LIST:
					$menu_content = $this->render_item_type_radio_list($item);
				break;

				case self::LIMIT:
					$menu_content = $this->render_item_type_limit($item);
				break;

			}
		}

		$menu_footer = $this->render_item_footer($item);

		$classes = "menu menu-filter";

		if (strlen($item['size'])) {
			$classes .= " menu-size-{$item['size']}";
		}

		$out = "<ul class='{$classes}'>{$menu_content}\n{$menu_footer}</ul>";

		return $out;
	}


	public function render_item_footer($item = [])
	{
		$remove_button = '';

		$params = $this->data;
		$key = $item['name'];
		$value = $this->get_value($item['name']);
		$has_value = (is_string($value) && strlen($value) || (is_array($value) && ! empty($value)));

		if (array_key_exists($key, $params) && $has_value) {
			unset($params[$key]);
			$url = "?" . http_build_query($params);
			$remove_button = anchor($this->base_url . $url, lang('filter_remove'), 'class="btn btn-link btn-sm text-gray mt-2"');
		}

		$done_label = lang('filter_done');
		$done_button = "<label for='{$this->filter_id}_done' class='btn btn-block btn-link btn-sm'>{$done_label}</label>";

		$out = "<li class='menu-item menu-item-done'>{$done_button}\n{$remove_button}</li>";
		return $out;
	}


	public function render_item_type_text($item = [])
	{
		$input = form_input([
			'class' => 'form-input',
			'name' => $item['name'],
			'id' => isset($item['id']) ? $item['id'] : $item['name'],
			'tabindex' => tab_index(),
			'value' => $this->get_value($item['name']),
			'autocomplete' => 'off',
		]);

		$out = "<li class='menu-item'>{$input}</li>";

		return $out;
	}


	public function render_item_type_check_list($item = [])
	{
		$inputs = [];

		$options = [];
		if ($item['blank'] !== FALSE) {
			$options[''] = $item['blank'];
			foreach ($item['options'] as $k => $v) {
				$options["{$k}"] = $v;
			}
		} else {
			$options = $item['options'];
		}

		foreach ($options as $value => $label) {

			$checked = in_array($value, $this->get_value($item['name'], []));

			$input = "<label class='form-checkbox'>";
			$input .= form_checkbox([
				'name' => "{$item['name']}[]",
				'id' => "{$item['name']}_{$value}",
				'value' => $value,
				'checked' => $checked,
			]);
			$input .= "<i class='form-icon'></i> ";
			$input .= $label;
			$input .= "</label>";

			$inputs[] = "<li class='menu-item'>{$input}</li>";
		}

		return implode("\n", $inputs);
	}


	public function render_item_type_radio_list($item = [])
	{
		$inputs = [];

		$filter_value = $this->get_value($item['name']);

		$options = [];
		if ($item['blank'] !== FALSE) {
			$options[''] = $item['blank'];
			foreach ($item['options'] as $k => $v) {
				$options["{$k}"] = $v;
			}
		} else {
			$options = $item['options'];
		}

		foreach ($options as $value => $label) {

			$has_val = ($value == $filter_value && is_scalar($filter_value) && strlen($filter_value));
			$empty_match = ( ! strlen($value) && ! strlen($filter_value));
			$checked = ($has_val || $empty_match);

			$input = "<label class='form-radio'>";
			$input .= form_radio([
				'name' => $item['name'],
				'id' => "{$item['name']}_{$value}",
				'value' => $value,
				'checked' => $checked,
			]);
			$input .= "<i class='form-icon'></i> ";
			$input .= $label;
			$input .= "</label>";

			$inputs[] = "<li class='menu-item'>{$input}</li>";
		}

		return implode("\n", $inputs);
	}


	public function render_item_type_limit($item = [])
	{
		$options = [];
		foreach ($item['options'] as $pp) {
			$options[ $pp ] = $pp;
		}

		$item['options'] = $options;

		return $this->render_item_type_radio_list($item);
	}


	public function get_value($name = '', $default = FALSE)
	{
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		}

		return $default;
	}


}

