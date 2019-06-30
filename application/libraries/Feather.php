<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Wrapper class for accessing Feather\Icons library.
 *
 * By extending it, and loading this library via CodeIgniter,
 * we can access the Icons() class via $this->feather->...
 *
 */
class Feather extends Feather\Icons
{


	public function __construct()
	{
		parent::__construct();

		$this->setAttributes(array(
			'width' => 20,
			'height' => 20,
		));
	}


}
