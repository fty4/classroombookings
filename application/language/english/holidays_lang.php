<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Tabs
$lang['holidays_page_year'] = 'Holidays';
$lang['holidays_page_update'] = 'Edit';
$lang['holidays_page_delete'] = 'Delete';

// Actions
$lang['holidays_action_add'] = 'Add new holiday';
$lang['holidays_action_update'] = 'Update holiday';

// Page titles
$lang['holidays_add_page_title'] = 'Add new holiday';
$lang['holidays_update_page_title'] = 'Edit holiday';
$lang['holidays_delete_page_title'] = 'Delete holiday';

$lang['holidays_not_found'] = 'Could not find the requsted holiday.';

$lang['holidays_none'] = "No holidays have been added yet.";
$lang['holidays_none_hint'] = "Add a holiday to prevent bookings being made on a specific date or range of dates.";

// Update
//

$lang['holidays_update_status_success'] = 'The holiday has been updated.';
$lang['holidays_update_status_error'] = 'There was an error updating the holiday.';
$lang['holidays_update_fieldset_details'] = 'Details';
$lang['holidays_update_fieldset_hint_details'] = 'Both start date and end date must be in the academic year.';

$lang['holidays_delete_status_success'] = 'The holiday <strong>{name}</strong> has been deleted.';
$lang['holidays_delete_status_error'] = 'There was an error deleting the holiday.';

$lang['holidays_add_status_success'] = 'The new holiday has been added.';
$lang['holidays_add_status_error'] = 'There was an error adding the new holiday.';

// Delete
$lang['holidays_delete_title'] = 'Delete holiday';
$lang['holidays_delete_description'] = 'If deleted, the dates in this holiday will become bookable.';
$lang['holidays_delete_action'] = 'Delete %s';
