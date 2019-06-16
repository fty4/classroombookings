<?php


/**
 * This function returns the maximum files size that can be uploaded.
 *
 * @return int File size in bytes
 *
 */
function max_upload_file_size()
{
    return min(php_size_to_bytes(ini_get('post_max_size')), php_size_to_bytes(ini_get('upload_max_filesize')));
}


/**
* This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
*
* @param string $sSize
* @return integer The value in bytes
*
*/
function php_size_to_bytes($sSize)
{
    //
    $sSuffix = strtoupper(substr($sSize, -1));
    if (!in_array($sSuffix,array('P','T','G','M','K'))){
        return (int)$sSize;
    }
    $iValue = substr($sSize, 0, -1);
    switch ($sSuffix) {
        case 'P':
            $iValue *= 1024;
            // Fallthrough intended
        case 'T':
            $iValue *= 1024;
            // Fallthrough intended
        case 'G':
            $iValue *= 1024;
            // Fallthrough intended
        case 'M':
            $iValue *= 1024;
            // Fallthrough intended
        case 'K':
            $iValue *= 1024;
            break;
    }
    return (int)$iValue;
}


function setting($key, $group = 'crbs')
{
	$CI =& get_instance();
	return $CI->settings_model->get($key, $group);
}



function pagination_config($params = [])
{
	$defaults = [
		'full_tag_open' => '<ul class="pagination">',
		'full_tag_close' => '</ul>',

		// first tag
		'first_tag_open' => '<li class="page-item">',
		'first_tag_close' => '</li>',


		// last tag
		'last_link' => 'Last',
		'last_tag_open' => '<li class="page-item">',
		'last_tag_close' => '</li>',

		// next tag
		'first_link' => 'First',

		'next_link' => 'Next',
		'next_tag_open' => '<li class="page-item">',
		'next_tag_close' => '</li>',

		// prev tag
		'prev_link' => 'Previous',
		'prev_tag_open' => '<li class="page-item">',
		'prev_tag_close' => '</li>',

		// current page tag
		'cur_tag_open' => '<li class="page-item active"><a href="javascript:;">',
		'cur_tag_close' => '</a></li>',

		// digit page tag
		'num_tag_open' => '<li class="page-item">',
		'num_tag_close' => '</li>',
	];

	$config = array_merge($defaults, $params);
	return $config;
}



function results_dropdown($id_key = '', $value_key = '', $results = array(), $default = NULL)
{
	$out = array();

	if ($default !== NULL)
	{
		$out[''] = $default;
	}

	foreach ($results as $row)
	{
		if (is_callable($value_key)) {
			$value = $value_key($row);
		} else {
			$value = $row->$value_key;
		}
		$out[ $row->$id_key ] = $value;
	}

	return $out;
}


function get_property($name = '', $obj = NULL, $default = NULL)
{
	if (is_object($obj) && property_exists($obj, $name)) {
		return $obj->$name;
	}

	return $default;
}





/**
 * If there is a results file in the session, remove it, and unset the key.
 *
 */
function cleanup_import()
{
	if (array_key_exists('import_results', $_SESSION)) {
		$file = $_SESSION['import_results'];
		@unlink(FCPATH . "local/{$file}");
		unset($_SESSION['import_results']);
	}
}
