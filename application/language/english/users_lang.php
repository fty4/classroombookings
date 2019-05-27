<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Tabs
$lang['users_page_index'] = 'Users';
$lang['users_page_view'] = 'Summary';
$lang['users_page_update'] = 'Edit';
$lang['users_page_password'] = 'Change password';
$lang['users_page_delete'] = 'Delete';

// Actions
$lang['users_action_add'] = 'Add new user';
$lang['users_action_import'] = 'Import users';
$lang['users_action_update'] = 'Update user';
$lang['users_action_change_password'] = 'Change password';

// Page titles
$lang['users_add_page_title'] = 'Add new user account';
$lang['users_update_page_title'] = 'Edit account';
$lang['users_change_password_page_title'] = 'Change password';
$lang['users_delete_page_title'] = 'Delete account';

$lang['users_not_found'] = 'Could not find the requsted user.';

$lang['users_update_fieldset_account'] = 'Account details';
$lang['users_update_fieldset_personal'] = 'Personal information';
$lang['users_update_fieldset_change_password'] = 'Change password';
$lang['users_update_fieldset_password'] = 'Password';
$lang['users_update_fieldset_password_hint'] = 'A random password has been generated, but this can be replaced if you want to use something else.';

$lang['users_update_status_success'] = 'The user account has been updated.';
$lang['users_update_status_error'] = 'There was an error updating the user account.';

$lang['users_change_password_status_success'] = 'The password has been set to <strong>{password}</strong>.';
$lang['users_change_password_status_error'] = 'There was an error changing the password.';

$lang['users_delete_status_success'] = 'The user account <strong>{username}</strong> has been deleted.';
$lang['users_delete_status_error'] = 'There was an error deleting the user account.';

$lang['users_add_status_success'] = 'The new user account has been created with the username <strong>{username}</strong> and password <strong>{password}</strong>.';
$lang['users_add_status_error'] = 'There was an error creating the new user account.';

// Delete
$lang['users_delete_title'] = 'Delete user account';
$lang['users_delete_description'] = 'Deleting this user will also remove all of their bookings. It is permanent and cannot be undone.';
$lang['users_delete_action'] = 'Permanently delete %s';
