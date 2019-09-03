<?php

/**
 * Ceon Manual Card Admin Script.
 *
 * @package     ceon_manual_card
 * @author      Conor Kerr <zen-cart.ceon-manual-card@ceon.net>
 * @copyright   Copyright 2006-2012 Ceon
 * @copyright   Portions Copyright 2003-2006 Zen Cart Development Team
 * @copyright   Portions Copyright 2003 osCommerce
 * @link        http://ceon.net/software/business/zen-cart/ceon-manual-card
 * @license     http://www.gnu.org/copyleft/gpl.html   GNU Public License V2.0
 * @version     $Id: ceon_manual_card_admin_notification.php 1093 2012-11-14 19:03:30Z conor $
 */

// Only output the Admin if transaction information was recorded for this order!
if (isset($ceon_manual_card_result->fields)) {
	
	// Check if the card details have been deleted
	$details_deleted = (!is_null($ceon_manual_card_result->fields['deleted_datetime']));
	
	// Has the delete button been pressed?
	if (!$details_deleted && isset($_POST) && sizeof($_POST) != 0) {
		foreach ($_POST as $post_var => $post_var_value) {
			if (substr($post_var, 0, 31) == 'ceon-manual-card-delete-details') {
				$delete_details_sql = "
					UPDATE
						" . TABLE_CEON_MANUAL_CARD . "
					SET
						cc_start = NULL,
						cc_issue = NULL,
						cc_card_number = NULL,
						cc_cv2 = NULL
					WHERE
						id = '" . $ceon_manual_card_result->fields['id'] . "';";
				
				$delete_details_result = $db->Execute($delete_details_sql);
				
				$delete_details_sql = "
					UPDATE
						" . TABLE_ORDERS . "
					SET
						cc_type = NULL,
						cc_owner = NULL,
						cc_number = NULL,
						cc_expires = NULL
					WHERE
						orders_id = '" . $ceon_manual_card_result->fields['order_id'] . "';";
				
				$delete_details_result = $db->Execute($delete_details_sql);
				
				// Record the date/time and user who deleted the details
				$admin_name_result = $db->Execute("
					SELECT
						admin_name
					FROM
						" . TABLE_ADMIN . "
					WHERE
						admin_id = " . (int) $_SESSION['admin_id'] . ";");
				
				$admin_name = $admin_name_result->fields['admin_name'];
				
				$record_deletion_details_sql = "
					UPDATE
						" . TABLE_CEON_MANUAL_CARD . "
					SET
						deleted_by = '" . zen_db_input($admin_name) . "',
						deleted_datetime = NOW()
					WHERE
						id = '" . $ceon_manual_card_result->fields['id'] . "';";
				
				$record_deletion_details_result = $db->Execute($record_deletion_details_sql);
				
				// Must update array of information for transaction
				$transaction_info_sql = "
					SELECT
						*
					FROM
						" . TABLE_CEON_MANUAL_CARD . "
					WHERE
						order_id = '" . $zen_order_id . "'";
				
				$ceon_manual_card_result = $db->Execute($transaction_info_sql);
				
				// Must let user know that deletion has taken place
				$deletion_just_took_place = true;
				
				$details_deleted = true;
				
				break;
			}
		}
	}
	
	// Build transaction admin/information output //////
	$output = '';
	
	// If details have just been deleted, try to redirect so that the user can that they have been. A refresh of
	// the page is necessary as the standard details will have already been output before this software had a
	// chance to run and carry out the actual deletion. If JavaScript is disabled at least the module will output
	// an informative message to the user, to try and avoid potential confusion
	if ($deletion_just_took_place) {
		$refresh_uri = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null);
		
		if (is_null($refresh_uri)) {
			$params = zen_get_all_get_params();
			
			$refresh_uri = zen_href_link(FILENAME_ORDERS, $params);
		}
		
		$output .= <<<JS
<script type="text/javascript">
<!--
self.location = '$refresh_uri';
// -->
</script>
JS;
	}
	
	$output .= "<td>\n";
	
	// Styles for admin/info
	$background_image = DIR_WS_IMAGES . '/' . 'ceon-manual-card-background.png';
	
	$output .= <<< STYLEBLOCK
<style type="text/css">
	#ceon-manual-card-admin { padding: 0.7em 0 0.7em 0; border-top: 1px solid #000; border-bottom: 1px solid #000; background: url($background_image) top left no-repeat; }
	#ceon-manual-card-admin img { border: 0; }
	#ceon-manual-card-admin p { margin: 0 0 0.8em 0; }
	#ceon-manual-card-admin h2 { font-size: 1em; margin: 0 0 1em 0; padding: 0; }
	#ceon-manual-card-admin fieldset { margin-bottom: 0.48em; }
	#ceon-logo { float: right; margin: 5px 0 5px 1em; }
	#ceon-manual-card-delete-card-details-wrapper { width: 20em; float: right; text-align: right; margin: 0 1.5em 0 1.3em; }
	
	legend { font-weight: bold; }
	
	@media print {
		#ceon-manual-card-admin { display: none; }
	}
</style>
STYLEBLOCK;
	
	// Main wrapper for Ceon Manual Card transaction info
	$output .= '<div id="ceon-manual-card-admin">';
	
	$output .= '<a href="http://ceon.net/software/business/zen-cart" target="_blank"><img src="' . DIR_WS_IMAGES .
		'ceon-button-logo.png" alt="Ceon" id="ceon-logo" /></a>' . "\n";
	
	if (!$details_deleted && strtolower(CEON_MANUAL_CARD_ADMIN_SHOW_DELETE_BUTTON) == 'yes') {
		// Add the button to delete the card details
		$output .= '<fieldset id="ceon-manual-card-delete-card-details-wrapper">' . "\n";
		
		$output .= '<legend>' . CEON_MANUAL_CARD_ADMIN_TEXT_DELETE_DETAILS . '</legend>' . "\n";
		
		$confirm_deletion_message = addslashes(CEON_MANUAL_CARD_ADMIN_TEXT_CONFIRM_DELETE_DETAILS);
		
		$output .= <<<JS
<script type="text/javascript">
<!--
function ceonManualCardConfirmDeletion()
{
	var confirm_deletion = confirm('$confirm_deletion_message');
	
	if (confirm_deletion) {
		return true;
	} else {
		return false;
	}
}
// -->
</script>
JS;
		
		$action_uri = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null);
		
		if (is_null($action_uri)) {
			$params = zen_get_all_get_params();
			
			$action_uri = zen_href_link(FILENAME_ORDERS, $params);
		}
		
		$output .= '<form action="' . $action_uri . '" method="POST">' . "\n";
		
		$output .= '<input type="hidden" name="securityToken" value="' . $_SESSION['securityToken'] . '" />';
		
		$output .= zen_hide_session_id();
		
		$output .= zen_image_submit('button_delete.gif', IMAGE_DELETE,
			'name="ceon-manual-card-delete-details" id="ceon-manual-card-delete-details" value="1"' .
			' onClick="javascript:return ceonManualCardConfirmDeletion();"');
		
		$output .= '</form>';
		
		$output .= '</fieldset>';
	}
	
	
	if (!$details_deleted && strtolower(CEON_MANUAL_CARD_ADMIN_SHOW_DELETE_BUTTON) == 'yes') {
		$output .= '<fieldset id="ceon-manual-card-main-section-wrapper">' . "\n";
		
		$output .= '<legend>' . CEON_MANUAL_CARD_ADMIN_TEXT_TITLE . '</legend>' . "\n";
	} else {
		$output .= '<div><h2>' . CEON_MANUAL_CARD_ADMIN_TEXT_TITLE . "</h2>\n";
	}
	
	if (isset($details_deleted_message) || $details_deleted) {
		// Output information about details having been deleted
		$hour = substr($ceon_manual_card_result->fields['deleted_datetime'], 11, 2);
		$minute = substr($ceon_manual_card_result->fields['deleted_datetime'], 14, 2);
		$second = substr($ceon_manual_card_result->fields['deleted_datetime'], 17, 2);
		$year = substr($ceon_manual_card_result->fields['deleted_datetime'], 0, 4);
		$month = substr($ceon_manual_card_result->fields['deleted_datetime'], 5, 2);
		$day = substr($ceon_manual_card_result->fields['deleted_datetime'], 8, 2);
		
		$details_deleted_ts = mktime($hour, $minute, $second, $month, $day, $year);
		
		if (isset($deletion_just_took_place)) {
			$details_deleted_message = CEON_MANUAL_CARD_ADMIN_TEXT_DETAILS_DELETED_CONFIRMED_NOTICE;
		} else {
			$details_deleted_message = CEON_MANUAL_CARD_ADMIN_TEXT_DETAILS_DELETED_NOTICE;
		}
		
		$details_deleted_message =
			str_replace('{ceon:time}', date('H:i:s' , $details_deleted_ts), $details_deleted_message);
		
		$details_deleted_message =
			str_replace('{ceon:date}', date('l, jS F Y' , $details_deleted_ts), $details_deleted_message);
		
		$details_deleted_message = str_replace('{ceon:admin-user-name}',
			htmlentities($ceon_manual_card_result->fields['deleted_by'], ENT_COMPAT, CHARSET),
			$details_deleted_message);
		
		$output .= '<p>' . $details_deleted_message . "</p>\n";
		
		if (isset($deletion_just_took_place)) {
			$output .= '<p>' . CEON_MANUAL_CARD_ADMIN_TEXT_DETAILS_DELETION_JUST_TOOK_PLACE . "</p>\n";
		}
	} else {
		
		$cc_start = (!is_null($ceon_manual_card_result->fields['cc_start']) &&
			strlen($ceon_manual_card_result->fields['cc_start']) > 0 ?
			$ceon_manual_card_result->fields['cc_start'] : null);
		
		$cc_issue = (!is_null($ceon_manual_card_result->fields['cc_issue']) &&
			strlen($ceon_manual_card_result->fields['cc_issue']) > 0 ?
			$ceon_manual_card_result->fields['cc_issue'] : null);
		
		$cc_card_number = (!is_null($ceon_manual_card_result->fields['cc_card_number']) &&
			strlen($ceon_manual_card_result->fields['cc_card_number']) > 0 ?
			$ceon_manual_card_result->fields['cc_card_number'] : null);
		
		$cc_cv2 = (!is_null($ceon_manual_card_result->fields['cc_cv2']) &&
			strlen($ceon_manual_card_result->fields['cc_cv2']) > 0 ? $ceon_manual_card_result->fields['cc_cv2'] :
			null);
		
		if (is_null($cc_card_number) && is_null($cc_cv2)) {
			// No card number is recorded so the card details were e-mailed
			$output .= '<p>' . CEON_MANUAL_CARD_ADMIN_TEXT_EMAIL_NOTICE . "</p>\n";
		}
		
		// Output a message indicating that no extra details were recorded or show the information about any
		// details recorded
		if (is_null($cc_card_number) && is_null($cc_start) && is_null($cc_issue)) {
			if ($this->_showStartDate() && $this->_showIssueNumber()) {
				$output .= '<p>' . CEON_MANUAL_CARD_ADMIN_TEXT_NO_START_DATE_OR_ISSUE_NUMBER . "</p>\n";
			} else if ($this->_showStartDate() && !$this->_showIssueNumber()) {
				$output .= '<p>' . CEON_MANUAL_CARD_ADMIN_TEXT_NO_START_DATE . "</p>\n";
			} else if (!$this->_showStartDate() && $this->_showIssueNumber()) {
				$output .= '<p>' . CEON_MANUAL_CARD_ADMIN_TEXT_NO_ISSUE_NUMBER . "</p>\n";
			}
		} else {
			// Add the table with any information about the recorded details
			$output .= '<table border="0" cellspacing="0" cellpadding="2">' . "\n";
			
			if (!is_null($cc_card_number)) {
				$output .= '<tr><td class="main">' . "\n";
				$output .= CEON_MANUAL_CARD_ADMIN_TEXT_CARD_NUMBER . "\n";
				$output .= '</td><td class="main">';
				$output .= $cc_card_number . "\n";
				$output .= '</td></tr>' . "\n";
			}
			
			if (!is_null($cc_cv2)) {
				$output .= '<tr><td class="main">' . "\n";
				$output .= CEON_MANUAL_CARD_ADMIN_TEXT_CV2_NUMBER . "\n";
				$output .= '</td><td class="main">';
				$output .= $cc_cv2 . "\n";
				$output .= '</td></tr>' . "\n";
			}
			
			if (!is_null($cc_start)) {
				$output .= '<tr><td class="main">' . "\n";
				$output .= CEON_MANUAL_CARD_ADMIN_TEXT_START_DATE . "\n";
				$output .= '</td><td class="main">';
				$output .= $cc_start . "\n";
				$output .= '</td></tr>' . "\n";
			}
			
			if (!is_null($cc_issue)) {
				$output .= '<tr><td class="main">' . "\n";
				$output .= CEON_MANUAL_CARD_ADMIN_TEXT_ISSUE_NUMBER . "\n";
				$output .= '</td><td class="main">';
				$output .= $cc_issue . "\n";
				$output .= '</td></tr>' . "\n";
			}
			
			$output .='</table>' . "\n";
			
			if (!is_null($cc_card_number) && is_null($cc_cv2)) {
				$output .= '<p>' . CEON_MANUAL_CARD_ADMIN_TEXT_NO_CV2_NUMBER . "</p>\n";
			}
		}
	}
	
	if (!$details_deleted && strtolower(CEON_MANUAL_CARD_ADMIN_SHOW_DELETE_BUTTON) == 'yes') {
		$output .= '</fieldset>';
	} else {
		$output .= '</div>';
	}
	
	$output .= '</div>';
	
	$output .= "</td>\n";
}
