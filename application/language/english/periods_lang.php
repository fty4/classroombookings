<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Tabs
$lang['periods_page_index'] = 'Periods';
$lang['periods_page_update'] = 'Edit';
$lang['periods_page_delete'] = 'Delete';

// Actions
$lang['periods_action_add'] = 'Add new period';
$lang['periods_action_update'] = 'Update period';

// Page titles
$lang['periods_add_page_title'] = 'Add new period';
$lang['periods_update_page_title'] = 'Edit period';
$lang['periods_delete_page_title'] = 'Delete period';

$lang['periods_not_found'] = 'Could not find the requsted period.';

$lang['periods_none'] = "No periods have been added yet.";
$lang['periods_none_hint'] = "Add periods to specify which chunks of time on the specified days can be booked.";

// Update
//


$lang['periods_update_fieldset_details'] = 'Details';
$lang['periods_update_fieldset_time_start'] = 'Start time';
$lang['periods_update_fieldset_time_end'] = 'End time';

$lang['periods_update_status_success'] = 'The period has been updated.';
$lang['periods_update_status_error'] = 'There was an error updating the period.';

$lang['periods_delete_status_success'] = 'The period <strong>{name}</strong> has been deleted.';
$lang['periods_delete_status_error'] = 'There was an error deleting the period.';

$lang['periods_add_status_success'] = 'The new period has been added.';
$lang['periods_add_status_error'] = 'There was an error adding the new period.';

// Delete
$lang['periods_delete_title'] = 'Delete period';
$lang['periods_delete_description'] = 'Deleting this period will also remove all bookings made for this period. This is permanent and cannot be undone.';
$lang['periods_delete_action'] = 'Permanently delete %s and associated bookings';
