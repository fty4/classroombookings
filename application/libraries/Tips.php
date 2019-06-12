<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Wrapper class for accessing Feather\Icons library.
 *
 * By extending it, and loading this library via CodeIgniter,
 * we can access the Icons() class via $this->feather->...
 *
 */
class Tips
{


	private $CI;
	private $settings;


	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->lang->load('tips');

		$this->user_id = $this->CI->userauth->user ? $this->CI->userauth->user->user_id : NULL;

		if ($this->user_id) {
			$this->settings = $this->CI->settings_model->get_all("tips.{$this->user_id}");
		}
	}


	public function show($name)
	{
		if (empty($this->user_id)) {
			return '';
		}

		if (array_key_exists($name, $this->settings)) {
			return "<!-- tip {$name} hidden at {$this->settings[$name]} -->\n";
		}

		$content = $this->CI->lang->line("tip_{$name}");
		$dismiss = $this->CI->lang->line('dismiss');

		if (empty($content)) {
			return "<!-- tip {$name} not found -->";
		}

		$url = site_url("user/dismiss_tip");
		$params = ['tip' => $name];
		$params_json = json_encode_html($params);
		$link = "<a class='tip-dismiss' href='javascript:;' data-ic-post-to='{$url}' data-ic-include='{$params_json}' data-ic-target='closest .tip'>{$dismiss}.</a>";
		return "<p class='tip'>{$content} {$link}</p>";
	}


	public function dismiss($name = '')
	{
		$group = "tips.{$this->user_id}";
		$key = $name;
		$this->CI->settings_model->set($name, time(), $group);
		return '';
	}


}
