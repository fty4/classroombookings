<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['error_prefix'] = '<p class="hint error"><span>';
$config['error_suffix'] = '</span></p>';

$config['settings_options'] = require("validation_rules/settings_options.php");
$config['settings_visual'] = require("validation_rules/settings_visual.php");
$config['user_details'] = require("validation_rules/user_details.php");
$config['user_password'] = require("validation_rules/user_password.php");
$config['users_add_update'] = require("validation_rules/users_add_update.php");
$config['weeks_add_update'] = require("validation_rules/weeks_add_update.php");

