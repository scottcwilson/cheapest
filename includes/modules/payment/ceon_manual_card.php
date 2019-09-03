<?php

/**
 * Ceon Manual Card Payment Module.
 *
 * @package     ceon_manual_card
 * @author      Conor Kerr <zen-cart.ceon-manual-card@ceon.net>
 * @copyright   Copyright 2006-2012 Ceon
 * @copyright   Portions Copyright 2003-2006 Zen Cart Development Team
 * @copyright   Portions Copyright 2003 osCommerce
 * @link        http://ceon.net/software/business/zen-cart/ceon-manual-card
 * @license     http://www.gnu.org/copyleft/gpl.html   GNU Public License V2.0
 * @version     $Id: ceon_manual_card.php 1093 2012-11-14 19:03:30Z conor $
 */


// {{{ ceon_manual_card

/**
 * Payment Module conforming to Zen Cart format. This module is used for MANUAL processing of card data collected
 * from customers.
 * 
 * It should ONLY be used if no other gateway is suitable. Also, SSL must be active on the server, for a minimal
 * level of data protection for customers.
 *
 * Card details entered can be retained throughout the checkout process, with Blowfish encryption being used to
 * encrypt any sensitive card details, but this functionality may have to be disabled to comply with any
 * applicable data protection laws.
 * 
 * @package     ceon_manual_card
 * @author      Conor Kerr <zen-cart.ceon-manual-card@ceon.net>
 * @copyright   Copyright 2006-2012 Ceon
 * @copyright   Portions Copyright 2003-2006 Zen Cart Development Team
 * @copyright   Portions Copyright 2003 osCommerce
 * @link        http://ceon.net/software/business/zen-cart/ceon-manual-card
 * @license     http://www.gnu.org/copyleft/gpl.html   GNU Public License V2.0
 */
class ceon_manual_card
{
	// {{{ properties
	
	/**
	 * The internal Zen Cart code name used to designate this payment module.
	 *
	 * @var     string
	 * @access  public
	 */
	var $code = 'ceon_manual_card';
	
	/**
	 * The Zen Cart version string for this module.
	 *
	 * @var     string
	 * @access  public
	 */
	var $version = null;
	
	/**
	 * The Ceon base model code for this module.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_ceon_base_model_code = 'S-ZC-CMC';
	
	/**
	 * The Ceon model edition code of this module.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_ceon_model_edition_code = null;
	
	/**
	 * The version of this module.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_version = '4.0.1';
	
	/**
	 * The start year of the copyright range for the module.
	 *
	 * @var     integer
	 * @access  public
	 */
	var $copyright_start_year = 2006;
	
	/**
	 * The currently installed version of this module.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_current_config_version = null;
	
	/**
	 * The Ceon model edition code of the currently installed version of this module.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_current_config_edition_code = null;
	
	/**
	 * The current database version for this module.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_current_database_version = null;
	
	/**
	 * The Ceon model edition code of the current database version for this module.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_current_database_edition_code = null;
	
	/**
	 * Variable holds status of configuration checks so that module can be disabled if it cannot perform its
	 * function.
	 *
	 * @var     boolean
	 * @access  protected
	 */
	var $_critical_config_problem = false;
	
	/**
	 * Variable stores any module configuration messages to be displayed when in admin mode.
	 *
	 * @var     array
	 * @access  protected
	 */
	var $_config_messages = array();
	
	/**
	 * The name to be displayed for this payment method.
	 *
	 * @var     string
	 * @access  public
	 */
	var $title;
	
	/**
	 * The description to be displayed for this payment method.
	 *
	 * @var     string
	 * @access  public
	 */
	var $description = '';
	
	/**
	 * Module status - set based on various config and zone criteria.
	 *
	 * @var     boolean
	 * @access  public
	 */
	var $enabled;
	
	/**
	 * The zone to which this module is restricted for use.
	 *
	 * @var     integer
	 * @access  public
	 */
	var $zone;
	
	/**
	 * The sort order of display for this module within the checkout's payment method listing.
	 *
	 * @var     integer
	 * @access  public
	 */
	var $sort_order;
	
	/**
	 * The order status setting for orders which have been passed to Ceon Manual Card.
	 *
	 * @var     integer
	 * @access  public
	 */
	var $order_status = 0;
	
	/**
	 * The name of the holder/owner of the card being used.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_card_holder;
	
	/**
	 * The type of the card being used.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_card_type;
	
	/**
	 * The number of the card being used.
	 *
	 * @var     integer
	 * @access  protected
	 */
	var $_card_number;
	
	/**
	 * The expiry date of the card being used.
	 *
	 * @var     integer
	 * @access  protected
	 */
	var $_card_expiry;
	
	/**
	 * The CV2 number of the card being used.
	 *
	 * @var     integer
	 * @access  protected
	 */
	var $_card_cv2_number;
	
	/**
	 * The start date of the card being used (not always present).
	 *
	 * @var     integer
	 * @access  protected
	 */
	var $_card_start;
	
	/**
	 * The issue number of the card being used (not always present).
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_card_issue_number;
	
	/**
	 * The middle digits of the card number, to be sent via e-mail.
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_card_number_middle_digits;
	
	// }}}
	
	
	// {{{ Class Constructor
	
	/**
	 * Creates a new instance of the ceon_manual_card class and checks the server and module's configuration. If in
	 * admin mode, additional checks can be made and output built to show to the admin user.
	 * 
	 * @access  public
	 */
	function __construct()
	{
		global $order, $db;
		
		// Assign the value to the publicly accessible Zen Cart version property
		$this->version = (!is_null($this->_ceon_model_edition_code) ?
			$this->_ceon_model_edition_code . '-' : '') . $this->_version;
		
		// Ensure compatibility with Zen Cart 1.2.x
		if (!defined('IS_ADMIN_FLAG')) {
			// Must be using Zen Cart 1.2.x!
			define('IS_ADMIN_FLAG', ((!isset($_GET['main_page']) || $_GET['main_page'] == '') ? true : false));
		}
		
		// Handle missing language code variable in Zen Cart 1.2.x
		if (!isset($_SESSION['languages_code']) && isset($_SESSION['languages_id'])) {
			$language_code_result = $db->Execute("
				SELECT
					code
				FROM
					" . TABLE_LANGUAGES . " 
				WHERE
					languages_id = '" . zen_db_input($_SESSION['languages_id']) . "'");
			
			if (!$language_code_result->EOF) {
				$_SESSION['languages_code'] = $language_code_result->fields['code'];
			}
		}
		
		// Get the installed version of the module
		if (defined('CEON_MANUAL_CARD_MADE_BY_CEON')) {
			// Any edition code precedes the version number, with the two seperated by a dash
			$version_parts = explode('-', CEON_MANUAL_CARD_MADE_BY_CEON);
			
			if (sizeof($version_parts) == 1) {
				$this->_current_config_version = $version_parts[0];
			} else {
				$this->_current_config_edition_code = $version_parts[0];
				
				$this->_current_config_version = $version_parts[1];
			}
		} else if (defined('MODULE_PAYMENT_CEON_MANUAL_CARD_MADE_BY_CEON')) {
			$this->_current_config_version = MODULE_PAYMENT_CEON_MANUAL_CARD_MADE_BY_CEON;
		} else if (defined('MODULE_PAYMENT_CEON_MANUAL_CARD_STATUS')) {
			$this->_current_config_version = '1.0.0';
		} else {
			// Module not installed!
			$this->_current_config_version = null;
		}
		
		$this->_checkModuleEnvironment();
		
		// Set the title based on the mode the module is in: Admin or Catalog
		if (IS_ADMIN_FLAG === true) {
			// In Admin mode
			$this->title = sprintf(CEON_MANUAL_CARD_TEXT_ADMIN_TITLE, $this->_version .
				(!is_null($this->_ceon_model_edition_code) ? ' ' .
				$this->_getEditionTitle($this->_ceon_model_edition_code) : ''));
		} else {
			// In Catalog mode
			$this->title = CEON_MANUAL_CARD_TEXT_CATALOG_TITLE;
		}
		
		// Description is only displayed when user is looking at the module in the payment modules admin
		if (IS_ADMIN_FLAG === true &&
				isset($_GET['set']) && $_GET['set'] == 'payment' &&
				isset($_GET['module']) && $_GET['module'] == $this->code &&
				(!isset($_GET['action']) || $_GET['action'] == 'edit')) {
			
			$this->description = $this->_getConfigurationMessagesOutput();
			
			if (!is_null($this->_current_config_version)) {
				$this->description .= CEON_MANUAL_CARD_TEXT_DESCRIPTION;
			} else {
				$this->description .= CEON_MANUAL_CARD_TEXT_NOT_INSTALLED;
			}
		}
		
		// Disable the module if configured as such or a critical configuration problem was found
		$this->enabled = ((defined('CEON_MANUAL_CARD_STATUS') && strtolower(CEON_MANUAL_CARD_STATUS) == 'yes' &&
			$this->_critical_config_problem == false) ? true : false);
		
		if (defined('CEON_MANUAL_CARD_SORT_ORDER')) {
			$this->sort_order = CEON_MANUAL_CARD_SORT_ORDER;
		}
		
		if (defined('CEON_MANUAL_CARD_ORDER_STATUS_ID') && (int) CEON_MANUAL_CARD_ORDER_STATUS_ID > 0) {
			$this->order_status = CEON_MANUAL_CARD_ORDER_STATUS_ID;
		}
		
		if (defined('CEON_MANUAL_CARD_ZONE')) {
			$this->zone = (int) CEON_MANUAL_CARD_ZONE;
		}
		
		if (IS_ADMIN_FLAG !== true && is_object($order)) {
			$this->update_status();
		}
	}
	
	// }}}
	
	
	// {{{ javascript_validation()
	
	/**
	 * Builds and returns the code to validate the card details via JavaScript.
	 *
	 * @access  public
	 * @return  string    The JavaScript needed to check the submitted card details.
	 */
	function javascript_validation()
	{
		$js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
			'    var num_ceon_manual_card_errors = 0;' . "\n" .
			'    var ceon_manual_card_error_class = "CeonManualCardFormGadgetError";' . "\n" .
			'    var ceon_manual_card_card_holder_gadget = document.getElementById(\'ceon-manual-card-card-holder\');' . "\n" .
			'    var ceon_manual_card_card_number_gadget = document.getElementById(\'ceon-manual-card-card-number\');' . "\n" .
			'    var ceon_manual_card_card_type_gadget = document.getElementById(\'ceon-manual-card-card-type\');' . "\n" .
			'    var ceon_manual_card_card_type_gadget_value = ceon_manual_card_card_type_gadget.options[ceon_manual_card_card_type_gadget.selectedIndex].value;' . "\n" .
			'    var ceon_manual_card_card_expiry_month_gadget = document.getElementById(\'ceon-manual-card-card-expiry-month\');' . "\n" .
			'    var ceon_manual_card_card_expiry_month_gadget_value = ceon_manual_card_card_expiry_month_gadget.options[ceon_manual_card_card_expiry_month_gadget.selectedIndex].value;' . "\n" .
			'    var ceon_manual_card_card_expiry_year_gadget = document.getElementById(\'ceon-manual-card-card-expiry-year\');' . "\n" .
			'    var ceon_manual_card_card_expiry_year_gadget_value = ceon_manual_card_card_expiry_year_gadget.options[ceon_manual_card_card_expiry_year_gadget.selectedIndex].value;' . "\n";
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			$js .= '    var ceon_manual_card_card_cv2_number_gadget = document.getElementById(\'ceon-manual-card-card-cv2-number\');' . "\n" .
				'    if (document.getElementById(\'ceon-manual-card-card-cv2-number-not-present\') != undefined) {
				         var ceon_manual_card_card_cv2_number_not_present_gadget = document.getElementById(\'ceon-manual-card-card-cv2-number-not-present\');
				     } else {
				         var ceon_manual_card_card_cv2_number_not_present_gadget = null;
				     }
				';
		}
		
		if ($this->_showStartDate()) {
			$js .= '    var ceon_manual_card_card_start_month_gadget = document.getElementById(\'ceon-manual-card-card-start-month\');' . "\n" .
			'    var ceon_manual_card_card_start_month_gadget_value = ceon_manual_card_card_start_month_gadget.options[ceon_manual_card_card_start_month_gadget.selectedIndex].value;' . "\n" .
			'    var ceon_manual_card_card_start_year_gadget = document.getElementById(\'ceon-manual-card-card-start-year\');' . "\n" .
			'    var ceon_manual_card_card_start_year_gadget_value = ceon_manual_card_card_start_year_gadget.options[ceon_manual_card_card_start_year_gadget.selectedIndex].value;' . "\n";
		}
		
		$js .= '    if (ceon_manual_card_card_holder_gadget.value == "" || ceon_manual_card_card_holder_gadget.value.length < ' .
			(is_numeric(CC_OWNER_MIN_LENGTH) ? CC_OWNER_MIN_LENGTH : 2) . ') {' . "\n" .
			'      num_ceon_manual_card_errors++;' . "\n" .
			'      error_message = error_message + "' .
			CEON_MANUAL_CARD_ERROR_JS_CARD_HOLDER_MIN_LENGTH . '";' . "\n" .
			'      error = 1;' . "\n" .
			'      // Update the form gadget\'s class to give visual feedback to customer' . "\n" .
			'      if (ceon_manual_card_card_holder_gadget.className.indexOf(ceon_manual_card_error_class) == -1) {' . "\n" .
			'        ceon_manual_card_card_holder_gadget.className = ceon_manual_card_card_holder_gadget.className + " " + ceon_manual_card_error_class;' . "\n" .
			'      }' . "\n" .
			'    } else {' . "\n" .
			'      // Reset error status if necessary' . "\n" .
			'      ceon_manual_card_card_holder_gadget.className = ceon_manual_card_card_holder_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
			'    }' . "\n" .
			'    if (ceon_manual_card_card_type_gadget_value == "") {' . "\n" .
			'      num_ceon_manual_card_errors++;' . "\n" .
			'      error_message = error_message + "' .
			CEON_MANUAL_CARD_ERROR_JS_CARD_TYPE . '";' . "\n" .
			'      error = 1;' . "\n" .
			'      // Update the form gadget\'s class to give visual feedback to customer' . "\n" .
			'      if (ceon_manual_card_card_type_gadget.className.indexOf(ceon_manual_card_error_class) == -1) {' . "\n" .
			'        ceon_manual_card_card_type_gadget.className = ceon_manual_card_card_type_gadget.className + " " + ceon_manual_card_error_class;' . "\n" .
			'      }' . "\n" .
			'    } else {' . "\n" .
			'      // Reset error status if necessary' . "\n" .
			'      ceon_manual_card_card_type_gadget.className = ceon_manual_card_card_type_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
			'    }' . "\n" .
			'    if (ceon_manual_card_card_number_gadget.value == "" || ceon_manual_card_card_number_gadget.value.length < ' .
			(is_numeric(CC_NUMBER_MIN_LENGTH) ?  CC_NUMBER_MIN_LENGTH : 16) . ') {' . "\n" .
			'      num_ceon_manual_card_errors++;' . "\n" .
			'      error_message = error_message + "' .
			CEON_MANUAL_CARD_ERROR_JS_CARD_NUMBER_MIN_LENGTH . '";' . "\n" .
			'      error = 1;' . "\n" .
			'      // Update the form gadget\'s class to give visual feedback to customer' . "\n" .
			'      if (ceon_manual_card_card_number_gadget.className.indexOf(ceon_manual_card_error_class) == -1) {' . "\n" .
			'        ceon_manual_card_card_number_gadget.className = ceon_manual_card_card_number_gadget.className + " " + ceon_manual_card_error_class;' . "\n" .
			'      }' . "\n" .
			'    } else {' . "\n" .
			'      // Reset error status if necessary' . "\n" .
			'      ceon_manual_card_card_number_gadget.className = ceon_manual_card_card_number_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
			'    }' . "\n" .
			'    if (ceon_manual_card_card_expiry_month_gadget_value == "" || ceon_manual_card_card_expiry_year_gadget_value == "") {' . "\n" .
			'      num_ceon_manual_card_errors++;' . "\n" .
			'      error_message = error_message + "' .
			CEON_MANUAL_CARD_ERROR_JS_CARD_EXPIRY_DATE_INVALID . '";' . "\n" .
			'      error = 1;' . "\n" .
			'    }' . "\n" .
			'    if (ceon_manual_card_card_expiry_month_gadget_value == "") {' . "\n" .
			'      // Update the form gadget\'s class to give visual feedback to customer' . "\n" .
			'      if (ceon_manual_card_card_expiry_month_gadget.className.indexOf(ceon_manual_card_error_class) == -1) {' . "\n" .
			'        ceon_manual_card_card_expiry_month_gadget.className = ceon_manual_card_card_expiry_month_gadget.className + " " + ceon_manual_card_error_class;' . "\n" .
			'      }' . "\n" .
			'    } else {' . "\n" .
			'      // Reset error status if necessary' . "\n" .
			'      ceon_manual_card_card_expiry_month_gadget.className = ceon_manual_card_card_expiry_month_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
			'    }' . "\n" .
			'    if (ceon_manual_card_card_expiry_year_gadget_value == "") {' . "\n" .
			'      // Update the form gadget\'s class to give visual feedback to customer' . "\n" .
			'      if (ceon_manual_card_card_expiry_year_gadget.className.indexOf(ceon_manual_card_error_class) == -1) {' . "\n" .
			'        ceon_manual_card_card_expiry_year_gadget.className = ceon_manual_card_card_expiry_year_gadget.className + " " + ceon_manual_card_error_class;' . "\n" .
			'      }' . "\n" .
			'    } else {' . "\n" .
			'      // Reset error status if necessary' . "\n" .
			'      ceon_manual_card_card_expiry_year_gadget.className = ceon_manual_card_card_expiry_year_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
			'    }' . "\n";
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			if (strtolower(CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER) == 'yes') {
				$js .= '    if ((ceon_manual_card_card_cv2_number_not_present_gadget == null &&' . "\n" .
					'        ((ceon_manual_card_card_type_gadget_value == "AMERICAN_EXPRESS" &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.value.search(/^[0-9]{4}$/) == -1) ||' . "\n" .
					'        (ceon_manual_card_card_type_gadget_value == "" &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.value.search(/^[0-9]{3,4}$/) == -1) ||' . "\n" .
					'        (ceon_manual_card_card_type_gadget_value != "AMERICAN_EXPRESS" &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.value.search(/^[0-9]{3}$/) == -1))) ||' . "\n" .
					'        ((ceon_manual_card_card_cv2_number_not_present_gadget != null &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_not_present_gadget.checked == false) &&' . "\n" .
					'        ((ceon_manual_card_card_type_gadget_value == "AMERICAN_EXPRESS" &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.value.search(/^[0-9]{4}$/) == -1) ||' . "\n" .
					'        (ceon_manual_card_card_type_gadget_value == "" &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.value.search(/^[0-9]{3,4}$/) == -1) ||' . "\n" .
					'        (ceon_manual_card_card_type_gadget_value != "AMERICAN_EXPRESS" &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.value.search(/^[0-9]{3}$/) == -1)))) {' . "\n" .
					'      num_ceon_manual_card_errors++;' . "\n";
				
				if (strtolower(CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS) == 'yes') {
					$js .= "      if (ceon_manual_card_card_type_gadget_value == 'AMERICAN_EXPRESS') {\n";
					
					$js .= '        error_message = error_message + "' .
						CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_INDICATE_AMERICAN_EXPRESS . '";' . "\n";
					$js .= "      } else if (ceon_manual_card_card_type_gadget_value == '') {\n" .
						'        error_message = error_message + "' .
						CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_INDICATE_POSS_AMERICAN_EXPRESS . '";' . "\n";
					
					$js .= "      } else {\n" .
						'        error_message = error_message + "' .
						CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_INDICATE_NOT_AMERICAN_EXPRESS . '";' . "\n";
					
					$js .= "      }\n";
				} else {
					$js .= '      error_message = error_message + "' .
						CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_INDICATE_NOT_AMERICAN_EXPRESS . '";' . "\n";
				}
				
				$js .= '      error = 1;' . "\n" .
					'      // Update the form gadget\'s class to give visual feedback to customer' . "\n" .
					'      if (ceon_manual_card_card_cv2_number_gadget.className.indexOf(ceon_manual_card_error_class) == -1) {' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.className = ceon_manual_card_card_cv2_number_gadget.className + " " + ceon_manual_card_error_class;' . "\n" .
					'      }' . "\n" .
					'    } else {' . "\n" .
					'      // Reset error status if necessary' . "\n" .
					'      ceon_manual_card_card_cv2_number_gadget.className = ceon_manual_card_card_cv2_number_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
					'    }' . "\n";
			} else {
				$js .= '    if ((ceon_manual_card_card_type_gadget_value == "AMERICAN_EXPRESS" &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.value.search(/^[0-9]{4}$/) == -1) ||' . "\n" .
					'        (ceon_manual_card_card_type_gadget_value == "" &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.value.search(/^[0-9]{3,4}$/) == -1) ||' . "\n" .
					'        (ceon_manual_card_card_type_gadget_value != "AMERICAN_EXPRESS" &&' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.value.search(/^[0-9]{3}$/) == -1)) {' . "\n" .
					'      num_ceon_manual_card_errors++;' . "\n";
				
				if (strtolower(CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS) == 'yes') {
					$js .= "      if (ceon_manual_card_card_type_gadget_value == 'AMERICAN_EXPRESS') {\n";
					
					$js .= '        error_message = error_message + "' .
						CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_AMERICAN_EXPRESS . '";' . "\n";
					$js .= "      } else if (ceon_manual_card_card_type_gadget_value == '') {\n" .
						'        error_message = error_message + "' .
						CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_POSS_AMERICAN_EXPRESS . '";' . "\n";
					
					$js .= "      } else {\n" .
						'        error_message = error_message + "' .
						CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_NOT_AMERICAN_EXPRESS . '";' . "\n";
					
					$js .= "      }\n";
				} else {
					$js .= '      error_message = error_message + "' .
						CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_NOT_AMERICAN_EXPRESS . '";' . "\n";
				}
				
				$js .= '      error = 1;' . "\n" .
					'      // Update the form gadget\'s class to give visual feedback to customer' . "\n" .
					'      if (ceon_manual_card_card_cv2_number_gadget.className.indexOf(ceon_manual_card_error_class) == -1) {' . "\n" .
					'        ceon_manual_card_card_cv2_number_gadget.className = ceon_manual_card_card_cv2_number_gadget.className + " " + ceon_manual_card_error_class;' . "\n" .
					'      }' . "\n" .
					'    } else {' . "\n" .
					'      // Reset error status if necessary' . "\n" .
					'      ceon_manual_card_card_cv2_number_gadget.className = ceon_manual_card_card_cv2_number_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
					'    }' . "\n";
			}
		}
		
		if ($this->_showStartDate()) {
			$js .=
				'    if ((ceon_manual_card_card_start_month_gadget_value == "" && ceon_manual_card_card_start_year_gadget_value != "")' . "\n" .
				'       || (ceon_manual_card_card_start_month_gadget_value != "" && ceon_manual_card_card_start_year_gadget_value == "")) {' . "\n" .
				'        num_ceon_manual_card_errors++;' . "\n" .
				'        error_message = error_message + "' .
				CEON_MANUAL_CARD_ERROR_JS_CARD_START_DATE_INVALID . '";' . "\n" .
				'        error = 1;' . "\n" .
				'        if (ceon_manual_card_card_start_month_gadget_value == "") {' . "\n" .
				'          // Update the form gadget\'s class to give visual feedback to customer' . "\n" .
				'          if (ceon_manual_card_card_start_month_gadget.className.indexOf(ceon_manual_card_error_class) == -1) {' . "\n" .
				'            ceon_manual_card_card_start_month_gadget.className = ceon_manual_card_card_start_month_gadget.className + " " + ceon_manual_card_error_class;' . "\n" .
				'          }' . "\n" .
				'        } else {' . "\n" .
				'          // Reset error status if necessary' . "\n" .
				'          ceon_manual_card_card_start_month_gadget.className = ceon_manual_card_card_start_month_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
				'        }' . "\n" .
				'        if (ceon_manual_card_card_start_year_gadget_value == "") {' . "\n" .
				'          // Update the form gadget\'s class to give visual feedback to customer' . "\n" .
				'          if (ceon_manual_card_card_start_year_gadget.className.indexOf(ceon_manual_card_error_class) == -1) {' . "\n" .
				'            ceon_manual_card_card_start_year_gadget.className = ceon_manual_card_card_start_year_gadget.className + " " + ceon_manual_card_error_class;' . "\n" .
				'          }' . "\n" .
				'        } else {' . "\n" .
				'          // Reset error status if necessary' . "\n" .
				'          ceon_manual_card_card_start_year_gadget.className = ceon_manual_card_card_start_year_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
				'        }' . "\n" .
				'    } else {' . "\n" .
				'        // Make sure that, if customer hasn\'t used either start date field, they aren\'t marked as having an error' . "\n" .
				'        ceon_manual_card_card_start_month_gadget.className = ceon_manual_card_card_start_month_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
				'        ceon_manual_card_card_start_year_gadget.className = ceon_manual_card_card_start_year_gadget.className.replace(ceon_manual_card_error_class, "");' . "\n" .
				'    }' . "\n";
		}
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes' &&
				strtolower(CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER) == 'yes') {
			$js .= '    if (ceon_manual_card_card_cv2_number_not_present_gadget == null &&' . "\n" .
				'        ceon_manual_card_card_cv2_number_gadget.value == "") {' . "\n" .
				'      parent_el = ceon_manual_card_card_cv2_number_gadget.parentNode;' . "\n" .
				'      try {' . "\n" .
				'        wrapper_div_el = document.createElement("<div>");' . "\n" .
				'      } catch (e) {' . "\n" .
				'        wrapper_div_el = document.createElement("div");' . "\n" .
				'      }' . "\n" .
				'      parent_el.insertBefore(wrapper_div_el,ceon_manual_card_card_cv2_number_gadget);' . "\n" .
				'      wrapper_div_el.appendChild(ceon_manual_card_card_cv2_number_gadget);' . "\n" .
				'      try {' . "\n" .
				'        br_el = document.createElement("<br>");' . "\n" .
				'      } catch (e) {' . "\n" .
				'        br_el = document.createElement("br");' . "\n" .
				'      }' . "\n" .
				'      wrapper_div_el.appendChild(br_el);' . "\n" .
				'      try {' . "\n" .
				'        cv2_number_not_present_checkbox_el = document.createElement(\'<input name="ceon-manual-card-card-cv2-number-not-present" id="ceon-manual-card-card-cv2-number-not-present" type="checkbox" value="true" />\');' . "\n" .
				'      } catch (e) {' . "\n" .
				'        cv2_number_not_present_checkbox_el = document.createElement(\'input\');' . "\n" .
				'        cv2_number_not_present_checkbox_el.setAttribute(\'id\', \'ceon-manual-card-card-cv2-number-not-present\');' . "\n" .
				'        cv2_number_not_present_checkbox_el.setAttribute(\'Name\', \'ceon-manual-card-card-cv2-number-not-present\');' . "\n" .
				'        cv2_number_not_present_checkbox_el.setAttribute(\'type\', \'checkbox\');' . "\n" .
				'        cv2_number_not_present_checkbox_el.setAttribute(\'value\', \'true\');' . "\n" .
				'      }' . "\n" .
				'      wrapper_div_el.appendChild(cv2_number_not_present_checkbox_el);' . "\n" .
				'      new_text_node_el = document.createTextNode(\' ' .
				addslashes(CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER_TICK_NOT_PRESENT) . '\');' . "\n" .
				'      wrapper_div_el.appendChild(new_text_node_el);' . "\n" .
				'    }' . "\n";
		}
		
		$js .= '  }' . "\n";
		
		return $js;
	}
	
	// }}}
	
	
	// {{{ selection()
	
	/**
	 * Builds the card details submission fields for display on the checkout payment page.
	 *
	 * @access  public
	 * @return  array     The data needed to build the card details submission form.
	 *
	 *                    Array Format:
	 *
	 *                    id       string    The name of this payment class
	 *                    module   string    The title for this payment method
	 *                    fields   array     A list of the titles and form gadgets to be used to build the form
	 *
	 *                                       Array Format:
	 *
	 *                    title    string    The title for a form field
	 *                    field    string    The HTML source for the form gadget for a form field
	 */
	function selection()
	{
		global $order;
		
		// Variable keeps track of whether any errors have occurred, so that the customer can be better informed
		// about what they have to do
		$error_encountered = false;
		
		if (isset($_SESSION['ceon_manual_card_error_encountered'])) {
			$error_encountered = true;
		}
		
		// Build the options for the expiry and start date select gadgets //////
		$today = getdate();
		
		$expiry_month_options[] = array(
			'id' => '',
			'text' => CEON_MANUAL_CARD_TEXT_SELECT_MONTH
			);
		
		for ($i = 1; $i < 13; $i++) {
			$expiry_month_options[] = array(
				'id' => sprintf('%02d', $i),
				'text' => strftime(CEON_MANUAL_CARD_SELECT_MONTH_FORMAT, mktime(0, 0, 0, $i, 1, 2000))
				);
		}
		
		// The expiry year options include the next ten years and this year
		$expiry_year_options[] = array(
			'id' => '',
			'text' => CEON_MANUAL_CARD_TEXT_SELECT_YEAR
			);
		
		for ($i = $today['year']; $i < $today['year'] + 10; $i++) {
			$expiry_year_options[] = array(
				'id' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'text' => strftime(CEON_MANUAL_CARD_SELECT_YEAR_FORMAT, mktime(0, 0, 0, 1, 1, $i))
				);
		}
		
		$start_month_options[] = array(
			'id' => '',
			'text' => CEON_MANUAL_CARD_TEXT_SELECT_MONTH
			);
		
		for ($i = 1; $i < 13; $i++) {
			$start_month_options[] = array(
				'id' => sprintf('%02d', $i),
				'text' => strftime(CEON_MANUAL_CARD_SELECT_MONTH_FORMAT, mktime(0, 0, 0, $i, 1, 2000))
				);
		}
		
		// The start year options include the past four years and this year
		$start_year_options[] = array(
			'id' => '',
			'text' => CEON_MANUAL_CARD_TEXT_SELECT_YEAR
			);
		
		for ($i = $today['year'] - 4; $i <= $today['year']; $i++) {
			$start_year_options[] = array(
				'id' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'text' => strftime(CEON_MANUAL_CARD_SELECT_YEAR_FORMAT, mktime(0, 0, 0, 1, 1, $i))
				);
		}
		
		// Build the options for the card type //////
		// Card Type selection is necessary for offering information about any card surcharges/discounts 
		$card_type_options[] = array(
			'id' => '',
			'text' => CEON_MANUAL_CARD_TEXT_SELECT_CARD_TYPE
			);
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_VISA) == 'yes') {
			$card_type_options[] = array(
				'id' => 'VISA',
				'text' => $this->_getCardTypeNameForCode('VISA')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD) == 'yes') {
			$card_type_options[] = array(
				'id' => 'MASTERCARD',
				'text' => $this->_getCardTypeNameForCode('MASTERCARD')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_VISA_DEBIT) == 'yes') {
			$card_type_options[] = array(
				'id' => 'VISA_DEBIT',
				'text' => $this->_getCardTypeNameForCode('VISA_DEBIT')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT) == 'yes') {
			$card_type_options[] = array(
				'id' => 'MASTERCARD_DEBIT',
				'text' => $this->_getCardTypeNameForCode('MASTERCARD_DEBIT')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_MAESTRO) == 'yes') {
			$card_type_options[] = array(
				'id' => 'MAESTRO',
				'text' => $this->_getCardTypeNameForCode('MAESTRO')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_VISA_ELECTRON) == 'yes') {
			$card_type_options[] = array(
				'id' => 'VISA_ELECTRON',
				'text' => $this->_getCardTypeNameForCode('VISA_ELECTRON')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS) == 'yes') {
			$card_type_options[] = array(
				'id' => 'AMERICAN_EXPRESS',
				'text' => $this->_getCardTypeNameForCode('AMERICAN_EXPRESS')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_DINERS_CLUB) == 'yes') {
			$card_type_options[] = array(
				'id' => 'DINERS_CLUB',
				'text' => $this->_getCardTypeNameForCode('DINERS_CLUB')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_JCB) == 'yes') {
			$card_type_options[] = array(
				'id' => 'JCB',
				'text' => $this->_getCardTypeNameForCode('JCB')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_LASER) == 'yes' &&
				strtoupper($_SESSION['currency']) == 'EUR') {
			// Laser is only allowed for Euro transactions
			$card_type_options[] = array(
				'id' => 'LASER',
				'text' => $this->_getCardTypeNameForCode('LASER')
				);
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_DISCOVER) == 'yes') {
			$card_type_options[] = array(
				'id' => 'DISCOVER',
				'text' => $this->_getCardTypeNameForCode('DISCOVER')
				);
		}
		
		// Initialise the default data to be used in the input form ////////////////////////////////
		$card_holder = $order->billing['firstname'] . ' ' . $order->billing['lastname'];
		$card_type = '';
		$card_number = '';
		$card_expiry_month = '';
		$card_expiry_year = '';
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			$card_cv2_number = '';
			
			if (strtolower(CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER) == 'yes') {
				$card_cv2_number_not_present = false;
			}
		}
		
		$card_start_month = '';
		$card_start_year = '';
		$card_issue_number = '';
		
		// Check if the customer has already entered their data. If so, use it to populate the form
		if (isset($_SESSION['ceon_manual_card_card_type'])) {
			// Basic details are stored unencrypted in the session
			$card_holder = $_SESSION['ceon_manual_card_card_holder'];
			$card_type = $_SESSION['ceon_manual_card_card_type'];
			$card_expiry_month = $_SESSION['ceon_manual_card_card_expiry_month'];
			$card_expiry_year = $_SESSION['ceon_manual_card_card_expiry_year'];
			
			if (isset($_SESSION['ceon_manual_card_card_cv2_number_not_present'])) {
				$card_cv2_number_not_present = $_SESSION['ceon_manual_card_card_cv2_number_not_present'];
			}
			
			if (isset($_SESSION['ceon_manual_card_card_start_month'])) {
				$card_start_month = $_SESSION['ceon_manual_card_card_start_month'];
				$card_start_year = $_SESSION['ceon_manual_card_card_start_year'];
			}
			
			if (isset($_SESSION['ceon_manual_card_card_issue_number'])) {
				$card_issue_number = $_SESSION['ceon_manual_card_card_issue_number'];
			}
			
			if (strtolower(CEON_MANUAL_CARD_STORE_SENSITIVE_DETAILS_IN_SESSION) == 'yes') {
				// Sensitive card details are also being stored in the session
				
				// Make sure that the customer has been directly involved with the checkout process in the previous
				// step, otherwise this data should be considered expired
				
				if (!$this->_referredFromCheckoutProcessURI()) {
					// Have not arrived here from another part of the checkout process or by a redirect the result
					// of a callback from a 3D-Secure check: data should be considered invalid! Remove it from the
					// session.
					unset($_SESSION['ceon_manual_card_data_entered']);
					
				} else if (isset($_SESSION['ceon_manual_card_data_entered'])) {
					// Have arrived here from another part of the checkout process
					// Restore the data previously entered by the customer
					
					// Decrypt the sensitive card details. See pre_confirmation_check for encryption information
					require_once(DIR_WS_CLASSES . 'class.CeonBlowfishEncryption.php');
					
					$bf = new CeonBlowfishEncryption(
						substr(CEON_MANUAL_CARD_BLOWFISH_ENCRYPTION_KEYPHRASE, 0, 56));
					
						$plaintext = $bf->decrypt($_SESSION['ceon_manual_card_data_entered']);
					
					$sensitive_data = unserialize(base64_decode($plaintext));
					
					$card_number = $sensitive_data['card_number'];
					
					if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
						$card_cv2_number = $sensitive_data['card_cv2_number'];
					}
				}
			}
		}
		
		$selection = array(
			'id' => $this->code,
			'module' => $this->title
			);
		
		// Display icons for the list of cards accepted?
		if (strtolower(CEON_MANUAL_CARD_SHOW_CARDS_ACCEPTED) == 'yes') {
			// Build the list of cards accepted
			$cards_accepted_images_source = '';
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_VISA) == 'yes') {
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES . 'card-icons/visa.png',
					CEON_MANUAL_CARD_TEXT_VISA, '', '', 'class="CeonManualCardCardIcon"');
			}
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD) == 'yes' ||
					strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT) == 'yes') {
				if (strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD) == 'yes' &&
						strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT) == 'yes') {
					$alt_text = CEON_MANUAL_CARD_TEXT_MASTERCARD . ' / ' .
						CEON_MANUAL_CARD_TEXT_MASTERCARD_DEBIT;
				} else if (strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD) == 'yes') {
					$alt_text = CEON_MANUAL_CARD_TEXT_MASTERCARD;
				} else {
					$alt_text = CEON_MANUAL_CARD_TEXT_MASTERCARD_DEBIT;
				}
				
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES . 'card-icons/mastercard.png',
					$alt_text, '', '', 'class="CeonManualCardCardIcon"');
			}
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_VISA_DEBIT) == 'yes') {
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES . 'card-icons/visa-debit.png',
					CEON_MANUAL_CARD_TEXT_VISA_DEBIT, '', '', 'class="CeonManualCardCardIcon"');
			}
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_MAESTRO) == 'yes') {
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES . 'card-icons/maestro.png',
					CEON_MANUAL_CARD_TEXT_MAESTRO, '', '', 'class="CeonManualCardCardIcon"');
			}
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_VISA_ELECTRON) == 'yes') {
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES . 'card-icons/visa-electron.png',
					CEON_MANUAL_CARD_TEXT_VISA_ELECTRON, '', '', 'class="CeonManualCardCardIcon"');
			}
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS) == 'yes') {
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES .
					'card-icons/american-express.png', CEON_MANUAL_CARD_TEXT_AMERICAN_EXPRESS, '', '',
					'class="CeonManualCardCardIcon"');
			}
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_DINERS_CLUB) == 'yes') {
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES . 'card-icons/diners-club.png',
					CEON_MANUAL_CARD_TEXT_DINERS_CLUB, '', '', 'class="CeonManualCardCardIcon"');
			}
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_JCB) == 'yes') {
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES . 'card-icons/jcb.png',
					CEON_MANUAL_CARD_TEXT_JCB, '', '', 'class="CeonManualCardCardIcon"');
			}
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_LASER) == 'yes' &&
					strtoupper($_SESSION['currency']) == 'EUR') {
				// Laser is only allowed for Euro transactions
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES . 'card-icons/laser.png',
					CEON_MANUAL_CARD_TEXT_LASER, '', '', 'class="CeonManualCardCardIcon"');
			}
			
			if (strtolower(CEON_MANUAL_CARD_ACCEPT_DISCOVER) == 'yes') {
				$cards_accepted_images_source .= zen_image(DIR_WS_TEMPLATE_IMAGES  .
					'card-icons/discover.png', CEON_MANUAL_CARD_TEXT_DISCOVER, '',
					'', 'class="CeonManualCardCardIcon"');
			}
			
			$selection['fields'][] = array(
				'title' => CEON_MANUAL_CARD_TEXT_CARDS_ACCEPTED,
				'field' => $cards_accepted_images_source
				);
		}
		
		// Automatically select Ceon Manual Card as the payment method if the customer has entered any data or made
		// any selection in the payment form
		$on_focus_handler = ' onfocus="javascript:selectCeonManualCard();"';
		
		// Add the JavaScript method needed to select Ceon Manual Card automatically to the card  holder field's
		// output
		$js = '<script type="text/javascript">' . "\n" .
			'<!--' . "\n" .
			'function selectCeonManualCard()' . "\n" .
			'{' . "\n" .
			"	if (document.getElementById('pmt-" . $this->code . "')) {\n" .
			"		document.getElementById('pmt-" . $this->code . "').checked = 'checked';\n" .
			'	}' . "\n" .
			'}' . "\n" .
			'// -->' . "\n" .
			'</script>' . "\n";
		
		$selection['fields'][] = array(
			'title' => CEON_MANUAL_CARD_TEXT_CARD_HOLDER,
			'field' => $js . zen_draw_input_field('ceon-manual-card-card-holder',
				$card_holder, 'id="ceon-manual-card-card-holder"' . $on_focus_handler)
			);
		
		// Display any custom message specified in the admin
		if (strtolower(CEON_MANUAL_CARD_ENABLE_SURCHARGES_DISCOUNTS) == 'yes' &&
				strtolower(CEON_MANUAL_CARD_ENABLE_CUSTOM_SURCHARGES_DISCOUNTS_MESSAGE) == 'yes' &&
				defined('CEON_MANUAL_CARD_CUSTOM_SURCHARGES_DISCOUNTS_MESSAGE') &&
				strlen(CEON_MANUAL_CARD_CUSTOM_SURCHARGES_DISCOUNTS_MESSAGE) > 0) {
			
			$selection['fields'][] = array(
				'title' => '',
				'field' => CEON_MANUAL_CARD_CUSTOM_SURCHARGES_DISCOUNTS_MESSAGE
				);
		}
		
		$selection['fields'][] = array(
			'title' => CEON_MANUAL_CARD_TEXT_CARD_TYPE,
			'field' => zen_draw_pull_down_menu('ceon-manual-card-card-type', $card_type_options, $card_type,
				'id="ceon-manual-card-card-type"' . $on_focus_handler)
			);
		
		
		// If errors are being displayed and sensitive details aren't being stored in the session then the card
		// number and CV2 number have to be entered again. Make things easier on the customer by highlighting the
		// appropriate field(s)!
		$card_number_error_class_string = '';
		$card_cv2_number_error_class_string = '';
		
		if ($error_encountered && strtolower(CEON_MANUAL_CARD_STORE_SENSITIVE_DETAILS_IN_SESSION) != 'yes') {
			
			$card_number_error_class_string = ' class="CeonManualCardFormGadgetError"';
			
			if (!isset($card_cv2_number_not_present) || !$card_cv2_number_not_present) {
				// Either customer can't indicate if card has no CV2 number or they haven't done so. Need to mark
				// the CV2 number field as they will have to enter the number again (or indicate that there is no
				// CV2 number on the card, if that is an option).
				$card_cv2_number_error_class_string = ' class="CeonManualCardFormGadgetError"';
			}
		}
		
		if (strtolower(CEON_MANUAL_CARD_DISABLE_CARD_NUMBER_AUTOCOMPLETE) == 'yes') {
			$selection['fields'][] = array(
				'title' => CEON_MANUAL_CARD_TEXT_CARD_NUMBER,
				'field' => zen_draw_input_field('ceon-manual-card-card-number',
					$card_number, 'id="ceon-manual-card-card-number"' . ' autocomplete="off"' .
					$on_focus_handler . $card_number_error_class_string)
				);
		} else {
			$selection['fields'][] = array(
				'title' => CEON_MANUAL_CARD_TEXT_CARD_NUMBER,
				'field' => zen_draw_input_field('ceon-manual-card-card-number',
					$card_number, 'id="ceon-manual-card-card-number"' .
					$on_focus_handler . $card_number_error_class_string)
				);
			
		}
		
		$selection['fields'][] = array(
			'title' => CEON_MANUAL_CARD_TEXT_CARD_EXPIRY_DATE,
			'field' => zen_draw_pull_down_menu('ceon-manual-card-card-expiry-month',
				$expiry_month_options, $card_expiry_month, 'id="ceon-manual-card-card-expiry-month"' .
				$on_focus_handler) . '&nbsp;' .
				zen_draw_pull_down_menu('ceon-manual-card-card-expiry-year',
				$expiry_year_options, $card_expiry_year, 'id="ceon-manual-card-card-expiry-year"' .
				$on_focus_handler)
			);
		
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			// Check if customer has indicated that their card has no CV2 number, or if the option
			// to specify as such should be made available
			$cv2_number_not_present_field = '';
			
			if (strtolower(CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER) == 'yes') {
				if ($card_cv2_number_not_present || (isset($_SESSION['ceon_manual_card_card_type']) &&
						$card_cv2_number == '')) {
					$cv2_number_not_present_field = '<br />' .
						zen_draw_checkbox_field('ceon-manual-card-card-cv2-number-not-present', 'true',
						$card_cv2_number_not_present, 'id="ceon-manual-card-card-cv2-number-not-present"' .
						$on_focus_handler) . '&nbsp;' . CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER_TICK_NOT_PRESENT;
				}
			}
			
			if (strtolower(CEON_MANUAL_CARD_DISABLE_CV2_NUMBER_AUTOCOMPLETE) == 'yes') {
				$selection['fields'][] = array(
					'title' => CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER_WITH_POPUP_LINK,
					'field' => zen_draw_input_field('ceon-manual-card-card-cv2-number', $card_cv2_number,
						'size="4" maxlength="4" autocomplete="off" id="ceon-manual-card-card-cv2-number"' .
						$on_focus_handler . $card_cv2_number_error_class_string) . $cv2_number_not_present_field
					);
			} else {
				$selection['fields'][] = array(
					'title' => CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER_WITH_POPUP_LINK,
					'field' => zen_draw_input_field('ceon-manual-card-card-cv2-number', $card_cv2_number,
						'size="4" maxlength="4" id="ceon-manual-card-card-cv2-number"' . $on_focus_handler .
						$card_cv2_number_error_class_string) . $cv2_number_not_present_field
					);
				
			}
		}
		
		if ($this->_showStartDate()) {
			$selection['fields'][] = array(
				'title' => CEON_MANUAL_CARD_TEXT_CARD_START_DATE_IF_ON_CARD,
				'field' => zen_draw_pull_down_menu('ceon-manual-card-card-start-month',
					$start_month_options, $card_start_month, 'id="ceon-manual-card-card-start-month"' .
					$on_focus_handler) . '&nbsp;' .
					zen_draw_pull_down_menu('ceon-manual-card-card-start-year', $start_year_options,
					$card_start_year, 'id="ceon-manual-card-card-start-year"' . $on_focus_handler)
				);
		}
		
		if ($this->_showIssueNumber()) {
			$selection['fields'][] = array(
				'title' => CEON_MANUAL_CARD_TEXT_CARD_ISSUE_NUMBER_IF_ON_CARD,
				'field' => zen_draw_input_field('ceon-manual-card-card-issue-number', $card_issue_number,
					'size="2" maxlength="2" id="ceon-manual-card-card-issue-number"' . $on_focus_handler)
				);
		}
		
		return $selection;
	}
	
	// }}}
	
	
	// {{{ pre_confirmation_check()
	
	/**
	 * Evaluates the credit/debit card type for acceptance and the validity of the card number & expiration date.
	 * Redirects back to card details entry page if invalid data detected.
	 *
	 * @access  public
	 * @return  none
	 */
	function pre_confirmation_check()
	{
		global $messageStack;
		
		// Reset tracking variable
		unset($_SESSION['ceon_manual_card_error_encountered']);
		
		// Variable holds data entered by customer (allows cleaning/formatting as appropriate)
		$data_entered = array();
		
		$data_entered['ceon_manual_card_card_holder'] = trim($_POST['ceon-manual-card-card-holder']);
		
		$data_entered['ceon_manual_card_card_type'] = $_POST['ceon-manual-card-card-type'];
		
		$data_entered['ceon_manual_card_card_number'] =
			preg_replace('/[^0-9]/', '', $_POST['ceon-manual-card-card-number']);
		
		$data_entered['ceon_manual_card_card_expiry_month'] = $_POST['ceon-manual-card-card-expiry-month'];
		
		$data_entered['ceon_manual_card_card_expiry_year'] = $_POST['ceon-manual-card-card-expiry-year'];
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			if (isset($_POST['ceon-manual-card-card-cv2-number'])) {
				$data_entered['ceon_manual_card_card_cv2_number'] = 
					preg_replace('/[^0-9]/', '', $_POST['ceon-manual-card-card-cv2-number']);
			} else {
				$data_entered['ceon_manual_card_card_cv2_number'] = '';
			}
			
			if (strtolower(CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER) == 'yes') {
				// Check if customer has indicated that their card doesn't have a CV2 number
				if (isset($_POST['ceon-manual-card-card-cv2-number-not-present'])) {
					$data_entered['ceon_manual_card_card_cv2_number_not_present'] = true;
					$data_entered['ceon_manual_card_card_cv2_number'] = '';
				} else {
					$data_entered['ceon_manual_card_card_cv2_number_not_present'] = false;
				}
			}
		}
		
		if (isset($_POST['ceon-manual-card-card-start-year'])) {
			$data_entered['ceon_manual_card_card_start_month'] = $_POST['ceon-manual-card-card-start-month'];
			
			$data_entered['ceon_manual_card_card_start_year'] = $_POST['ceon-manual-card-card-start-year'];
		}
		
		if (isset($_POST['ceon-manual-card-card-issue-number'])) {
			$data_entered['ceon_manual_card_card_issue_number'] =
				preg_replace('/[^0-9]/', '', $_POST['ceon-manual-card-card-issue-number']);
		}
		
		// Store the data entered so far in the session so that customer is not required to enter everything again
		// if anything is wrong or if they come back to the payment page to change some detail(s)
		foreach ($data_entered as $key => $value) {
			// Don't store sensitive card details directly in session
			if ($key != 'ceon_manual_card_card_number' && $key != 'ceon_manual_card_card_cv2_number') {
				$_SESSION[$key] = $value;
			}
		}
		
		// Store sensitive details only if configured to do so
		if (strtolower(CEON_MANUAL_CARD_STORE_SENSITIVE_DETAILS_IN_SESSION) == 'yes') {
			// Sensitive data entered is stored in the session as a base64 encoded, serialised array, encrypted
			// using Blowfish Encryption. As other users on the server can possibly access the session file (if
			// sessions are stored in a file in a temporary directory etc.), the possiblity of obtaining these
			// sensitive card details is a very real one.. the encryption can likely only delay the accessing of
			// this data. Storing these details is in breach of PCI compliance rules and some card issuers' terms
			// and conditions. Ceon takes no responsibility for the usage of this module in this way, but it does
			// provide a significantly enhanced customer experience if the card details form is ever to be
			// re-displayed.
			$sensitive_data = array(
				'card_number' => $data_entered['ceon_manual_card_card_number']
				);
			
			if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
				$sensitive_data['card_cv2_number'] = $data_entered['ceon_manual_card_card_cv2_number'];
			}
			
			$plaintext = base64_encode(serialize($sensitive_data));
			
			require_once(DIR_WS_CLASSES . 'class.CeonBlowfishEncryption.php');
			
			$bf = new CeonBlowfishEncryption(substr(CEON_MANUAL_CARD_BLOWFISH_ENCRYPTION_KEYPHRASE, 0, 56));
			
			$encrypted = $bf->encrypt($plaintext);
			
			$_SESSION['ceon_manual_card_data_entered'] = $encrypted;
		}
		
		// Check the data //////
		$errors = array();
		
		// Check the card holder
		$card_holder = $data_entered['ceon_manual_card_card_holder'];
		
		$card_holder_min_length =
			(is_numeric(CC_OWNER_MIN_LENGTH) && CC_OWNER_MIN_LENGTH > 2 ? CC_OWNER_MIN_LENGTH : 2);
		
		if (strlen($card_holder) == 0) {
			$errors[] = CEON_MANUAL_CARD_ERROR_CARD_HOLDER_REQUIRED;
		} else if (strlen($card_holder) < $card_holder_min_length) {
			$errors[] = CEON_MANUAL_CARD_ERROR_CARD_HOLDER_LENGTH;
		}
		
		// Check the card's type
		$card_type = $data_entered['ceon_manual_card_card_type'];
		
		if ($card_type == '') {
			// Type of card not selected!
			$errors[] = CEON_MANUAL_CARD_ERROR_CARD_TYPE;
		}
		
		// Check the card number using a basic LUHN check
		$card_number_valid = false;
		
		$card_number = $data_entered['ceon_manual_card_card_number'];
		
		if (strlen($card_number) == 0) {
			// No card number entered so obviously not valid!
			$errors[] = CEON_MANUAL_CARD_ERROR_CARD_NUMBER_REQUIRED;
		} else {
			$temp_card_number = strrev($card_number);
			
			$numSum = 0;
			
			for ($i = 0; $i < strlen($temp_card_number); $i++) {
				$current_number = substr($temp_card_number, $i, 1);
				
				// Double every second digit
				if ($i % 2 == 1) {
					$current_number *= 2;
				}
				
				// Add digits of 2-digit numbers together
				if ($current_number > 9) {
					$first_number = $current_number % 10;
					$second_number = ($current_number - $first_number) / 10;
					$current_number = $first_number + $second_number;
				}
				
				$numSum += $current_number;
			}
			
			if ($numSum % 10 != 0) {
				$errors[] = CEON_MANUAL_CARD_ERROR_CARD_NUMBER_INVALID;
			} else {
				$card_number_valid = true;
			}
		}
		
		// If a valid card number has been entered and a card type selected, must check if a MasterCard
		// credit/debit card is being used.
		// These checks are necessary in case the customer is trying to use a card type that is not accepted by the
		// store and to ensure that, if surcharges/discounts can be selected based on the card type, that the
		// correct card type has been selected by the customer.
		if ($card_number_valid && ($card_type == 'MASTERCARD' || $card_type == 'MASTERCARD_DEBIT')) {
			
			$card_is_mastercard_debit = $this->_isCardNumberMasterCardDebit($card_number);
			
			if ((!$card_is_mastercard_debit && $card_type != 'MASTERCARD') ||
					($card_is_mastercard_debit && $card_type != 'MASTERCARD_DEBIT')) {
				// Customer's card type selection doesn't match the card type indicated by the card number
				if ($card_is_mastercard_debit && $card_type == 'MASTERCARD' &&
						strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD) != 'yes') {
					// MasterCard credit cards aren't accepted
					$errors[] = CEON_MANUAL_CARD_ERROR_MASTERCARD_CREDIT_NOT_ACCEPTED;
				} else if (!$card_is_mastercard_debit && $card_type == 'MASTERCARD_DEBIT' &&
						strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT) != 'yes') {
					// MasterCard debit cards aren't accepted
					$errors[] = CEON_MANUAL_CARD_ERROR_MASTERCARD_DEBIT_NOT_ACCEPTED;
				} else {
					// If surcharges/discounts apply for either of the MasterCard card types then a
					// surcharge/discount could be used. Must warned warn the customer that they picked the wrong
					// card
					
					// The quick way to check if a surcharge/discount applies for a card type is to compare its
					// generated name against the stock name
					if ($this->_getCardTypeNameForCode('MASTERCARD', true) !=
							CEON_MANUAL_CARD_TEXT_MASTERCARD ||
							$this->_getCardTypeNameForCode('MASTERCARD_DEBIT', true) !=
							CEON_MANUAL_CARD_TEXT_MASTERCARD_DEBIT) {
						if ($card_type == 'MASTERCARD') {
							$errors[] = CEON_MANUAL_CARD_ERROR_CARD_IS_MASTERCARD_DEBIT_NOT_CREDIT;
						} else {
							$errors[] = CEON_MANUAL_CARD_ERROR_CARD_IS_MASTERCARD_CREDIT_NOT_DEBIT;
						}
					} else {
						// No surcharges in use so simply update the customer's selection to the correct card type
						// "behind the scenes"
						if ($card_type == 'MASTERCARD') {
							$card_type = 'MASTERCARD_DEBIT';
						} else {
							$card_type == 'MASTERCARD';
						}
					}
				}
			}
		}
		
		// Check the expiry date
		$expiry_month = $data_entered['ceon_manual_card_card_expiry_month'];
		$expiry_year = $data_entered['ceon_manual_card_card_expiry_year'];
		
		$current_year = date('Y');
		
		if (!is_numeric($expiry_year) || ($expiry_year < $current_year) || ($expiry_year > ($current_year + 10))) {
			$errors[] = CEON_MANUAL_CARD_ERROR_CARD_EXPIRY_DATE_INVALID;
		} else {
			$current_month = date('n');
			
			if (!is_numeric($expiry_month) || ($expiry_month <= 0) || ($expiry_month > 12) ||
					($expiry_year == $current_year && $expiry_month < $current_month)) {
				$errors[] = CEON_MANUAL_CARD_ERROR_CARD_EXPIRY_DATE_INVALID;
			}
		}
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			// Check if customer has indicated that their card doesn't have a CV2 number
			if (strtolower(CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER) == 'yes' &&
					$data_entered['ceon_manual_card_card_cv2_number_not_present']) {
				$card_cv2_number = '000';
			} else {
				// User must enter a CV2 number
				if (strlen($data_entered['ceon_manual_card_card_cv2_number']) < 3 ||
						($card_type == 'AMERICAN_EXPRESS' &&
						strlen($data_entered['ceon_manual_card_card_cv2_number']) < 4) ||
						!is_numeric($data_entered['ceon_manual_card_card_cv2_number'])) {
					// The CV2 number entered isn't long enough
					// Store the error message and redirect
					
					// Error message to be displayed differs depending on whether or not American Express cards are
					// an option as they require different instructions for identifying the CV2 number ("unique
					// card code")
					if (strtolower(CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS) == 'yes' &&
							($card_type == '' || $card_type == 'AMERICAN_EXPRESS')) {
						if ($card_type == 'AMERICAN_EXPRESS') {
							if (strlen($data_entered['ceon_manual_card_card_cv2_number']) == 0) {
								if (strtolower(CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER) == 'yes') {
									$errors[] =
										CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_MISSING_INDICATE_AMERICAN_EXPRESS;
								} else {
									$errors[] =
										CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_REQUIRED_AMERICAN_EXPRESS;
								}
							} else {
								$errors[] =
									CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_INVALID_AMERICAN_EXPRESS;
							}
						} else {
							// No card type has been selected so it could be any card type, must display fully
							// inclusive message
							if (strlen($data_entered['ceon_manual_card_card_cv2_number']) == 0) {
								if (strtolower(CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER) == 'yes') {
									$errors[] =
										CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_MISSING_INDICATE_POSS_AMERICAN_EXPRESS;
								} else {
									$errors[] =
										CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_REQUIRED_POSS_AMERICAN_EXPRESS;
								}
							} else {
								$errors[] =
									CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_INVALID_POSS_AMERICAN_EXPRESS;
							}
						}
					} else {
						if (strlen($data_entered['ceon_manual_card_card_cv2_number']) == 0) {
							if (strtolower(CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER) == 'yes') {
								$errors[] =
									CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_MISSING_INDICATE_NOT_AMERICAN_EXPRESS;
							} else {
								$errors[] = CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_REQUIRED_NOT_AMERICAN_EXPRESS;
							}
						} else {
							$errors[] = CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_INVALID_NOT_AMERICAN_EXPRESS;
						}
					}
				} else {
					$card_cv2_number = $data_entered['ceon_manual_card_card_cv2_number'];
				}
			}
		}
		
		// Check the start date
		if ($this->_showStartDate()) {
			$start_month = $data_entered['ceon_manual_card_card_start_month'];
			$start_year = $data_entered['ceon_manual_card_card_start_year'];
			
			if ($start_month != '' || $start_year != '') {
				// Start date has been specified (at least in part) so must validate it
				if (!is_numeric($start_year) || ($start_year > $current_year)) {
					$errors[] = CEON_MANUAL_CARD_ERROR_CARD_START_DATE_INVALID;
				} else {
					$current_month = date('n');
					
					if (!is_numeric($start_month) || ($start_month <= 0) || ($start_month > 12) ||
							($start_year == $current_year && $start_month > $current_month)) {
						$errors[] = CEON_MANUAL_CARD_ERROR_CARD_START_DATE_INVALID;
					}
				}
			}
		}
		
		if (sizeof($errors) > 0) {
			// The customer has not entered valid card details, redirect back to the input form
			$_SESSION['ceon_manual_card_error_encountered'] = true;
			
			// Store the error message(s) and redirect
			foreach ($errors as $error_message) {
				$messageStack->add_session('checkout_payment', $error_message, 'error');
			}
			
			zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
		}
		
		// Data seems to be valid, store the card details
		$this->_card_holder = $card_holder;
		$this->_card_type = $card_type;
		$this->_card_number = $card_number;
		$this->_card_expiry_month = $expiry_month;
		$this->_card_expiry_year = $expiry_year;
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			$this->_card_cv2_number = $card_cv2_number;
		}
		
		if ($this->_showStartDate()) {
			$this->_card_start_month = $start_month;
			$this->_card_start_year = $start_year;
		}
		
		if ($this->_showIssueNumber()) {
			$this->_card_issue_number = $data_entered['ceon_manual_card_card_issue_number'];
		}	
		
		$this->_buildSurchargeOrDiscount();
	}
	
	// }}}
	
	
	// {{{ confirmation()
	
	/**
	 * Builds the card information for display on the checkout confirmation page.
	 *
	 * @access  public
	 * @return  array     The list of Field Titles and their associated Values.
	 *
	 *                    Format:
	 *
	 *                    fields   array   The list of field titles and values stored as hashes.
	 */
	function confirmation()
	{
		// To reorder the output just adjust the order that the fields are added to the array
		// E.g. Below, "Expiry" can be reordered below "Start" by moving its section appropriately
		
		// Get the name for the card type selected as defined in the language definitions file
		$card_type_name = $this->_getCardTypeNameForCode($this->_card_type, false);
		
		$confirmation = array(
			'fields' => array(
				array(
					'title' => CEON_MANUAL_CARD_TEXT_CARD_TYPE . '&nbsp;',
					'field' => $card_type_name
					),
				array(
					'title' => CEON_MANUAL_CARD_TEXT_CARD_HOLDER . '&nbsp;',
					'field' => $this->_card_holder
					),
				array(
					'title' => CEON_MANUAL_CARD_TEXT_CARD_NUMBER . '&nbsp;',
					'field' => substr($this->_card_number, 0, 4) .
						str_repeat('X', (strlen($this->_card_number) - 8)) . substr($this->_card_number, -4)
					)
				)
			);
		
		$confirmation['fields'][] = array(
			'title' => CEON_MANUAL_CARD_TEXT_CARD_EXPIRY_DATE . '&nbsp;',
			'field' => strftime('%B, %Y', mktime(0, 0, 0, $this->_card_expiry_month, 1, $this->_card_expiry_year))
			);
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			if ($this->_card_cv2_number != '000') {
				$confirmation['fields'][] = array(
					'title' => CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER . '&nbsp;',
					'field' => $this->_card_cv2_number
					);
			} else {
				$confirmation['fields'][] = array(
					'title' => CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER . '&nbsp;',
					'field' => CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER_NOT_PRESENT
					);
			}
		}
		
		if ($this->_showStartDate() && $this->_card_start_year != '') {
			$confirmation['fields'][] = array(
				'title' => CEON_MANUAL_CARD_TEXT_CARD_START_DATE . '&nbsp;',
				'field' =>
					strftime('%B, %Y', mktime(0, 0, 0, $this->_card_start_month, 1, $this->_card_start_year))
				);
		}
		
		if ($this->_showIssueNumber() && $this->_card_issue_number != '') {
			$confirmation['fields'][] = array(
				'title' => CEON_MANUAL_CARD_TEXT_CARD_ISSUE_NUMBER . '&nbsp;',
				'field' => $this->_card_issue_number
				);
		}
		
		return $confirmation;
	}
	
	// }}}
	
	
	// {{{ process_button()
	
	/**
	 * Builds a list of the card details for this transaction, each piece of data being stored as a hidden HTML
	 * form field.
	 *
	 * @access  public
	 * @return  string    The card details for this transaction as a list of hidden form fields.
	 */
	function process_button()
	{
		// These are hidden fields on the checkout confirmation page
		$process_button_string = zen_draw_hidden_field('card-holder', $this->_card_holder) .
			zen_draw_hidden_field('card-type', $this->_card_type) .
			zen_draw_hidden_field('card-number', $this->_card_number) .
			zen_draw_hidden_field('card-expiry', $this->_card_expiry_month . substr($this->_card_expiry_year, -2));
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			$process_button_string .= zen_draw_hidden_field('card-cv2-number', $this->_card_cv2_number);
		}
		
		if ($this->_showStartDate()) {
			$process_button_string .= zen_draw_hidden_field('card-start', $this->_card_start_month .
				substr($this->_card_start_year, -2));
		}
		
		if ($this->_showIssueNumber()) {
			$process_button_string .= zen_draw_hidden_field('card-issue-number', $this->_card_issue_number);
		}
		
		$process_button_string .= zen_draw_hidden_field(zen_session_name(), zen_session_id());
		
		return $process_button_string;
	}
	
	// }}}
	
	
	// {{{ before_process()
	
	/**
	 * The main guts of the payment module as it were, this method formats the data appropriately for sending to
	 * the store owner. If a problem occurs this will redirect back to the Card Details entry page so that an error
	 * message can be displayed.
	 *
	 * @access  public
	 * @return  none
	 */
	function before_process()
	{
		global $order, $messageStack;
		
		// Store the card details for this order //////
		$order->info['cc_owner'] = $_POST['card-holder'];
		$order->info['cc_type'] = substr($this->_getCardTypeNameForCode($_POST['card-type'], false), 0, 20);
		
		if ($this->_mustEMailMiddleDigitsAndCV2Number()) {
			// Format the card number for storage in the database and e-mailing
			$len = strlen($_POST['card-number']);
			
			$this->_card_number_middle_digits = substr($_POST['card-number'], 4, ($len - 8));
			
			$order->info['cc_number'] = substr($_POST['card-number'], 0, 4) . str_repeat('X', ($len - 8)) .
				substr($_POST['card-number'], -4);
		} else {
			// Entire card number is to be stored in the database
			$this->_card_number = $_POST['card-number'];
		}
		
		$order->info['cc_expires'] = $_POST['card-expiry'];
		
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			if ($this->_mustEMailMiddleDigitsAndCV2Number()) {
				$this->_card_cv2_number = $_POST['card-cv2-number'];
			} else {
				$this->_card_cv2_number = $_POST['card-cv2-number'];
			}
		}
		
		if ($this->_showStartDate()) {
			$order->info['cc_start'] = $_POST['card-start'];
		}
		
		if ($this->_showIssueNumber()) {
			$order->info['cc_issue'] = $_POST['card-issue-number'];
		}
		
		// Debugging output
		if (strtolower(CEON_MANUAL_CARD_DEBUGGING_ENABLED) == 'yes') {
			echo "<html><head><title>Ceon Manual Card Debug Output</title></head><body>\n";
			echo "<pre>\n\n";
			echo "-----------------------\n";
			echo "Card Details Submitted:\n";
			echo "------------------------------------------\n\n";
			
			echo CEON_MANUAL_CARD_TEXT_CARD_NUMBER . ' ' . $_POST['card-number'] . "\n";
			
			echo CEON_MANUAL_CARD_TEXT_CARD_TYPE . ' ' . 
				$this->_getCardTypeNameForCode($_POST['card-type'], false) . "\n";
			
			echo CEON_MANUAL_CARD_TEXT_CARD_HOLDER . ' ' . $_POST['card-holder'] . "\n";
			
			echo CEON_MANUAL_CARD_TEXT_CARD_EXPIRY_DATE . ' ' . $_POST['card-expiry'] . "\n";
			
			if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
				if ($_POST['card-cv2-number'] == '000') {
					echo CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER . ' ' .
						CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER_NOT_PRESENT . "\n";
				} else {
					echo CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER . ' ' . $_POST['card-cv2-number'] . "\n";
				}
			}
			
			if ($this->_showStartDate()) {
				echo CEON_MANUAL_CARD_TEXT_CARD_START_DATE . ' ' . $_POST['card-start'] . "\n";
			}
			
			if ($this->_showIssueNumber()) {
				echo CEON_MANUAL_CARD_TEXT_CARD_ISSUE_NUMBER . ' ' . $_POST['card-issue-number'] . "\n";
			}
			
			echo "------------------------------------------\n\n";
			
			if (strtolower(CEON_MANUAL_CARD_STORE_SENSITIVE_DETAILS_IN_SESSION) == 'yes') {
				echo "--------------------------------------------------------------------\n";
				echo "Sensitive Details being stored in session using Blowfish encryption.\n";
				echo "--------------------------------------------------------------------\n\n";
			} else {
				echo "--------------------------------------------------------------------------\n";
				echo "Sensitive Details not being stored in session so encryption not necessary.\n";
				echo "--------------------------------------------------------------------------";
				echo "\n\n";
			}
			
			// End current page's debug output
			echo "\n\n</pre>\n</body>\n</html>";
			
			session_write_close();
			
			exit;
		}
	}
	
	// }}}
	
	
	// {{{ after_process()
	
	/**
	 * If module is running in e-mail mode, sends the CV2 number and middle digits of the card number via e-mail.
	 *
	 * @access  public
	 * @return  none
	 */
	function after_process()
	{
		global $insert_id;
		
		if (!$this->_mustEMailMiddleDigitsAndCV2Number()) {
			// Not e-mailing details, job is done
			// return;
		}
	  $message = "Middle digits email"; 	
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
			if ($this->_card_cv2_number == '000') {
				$message = sprintf(CEON_MANUAL_CARD_TEXT_EMAIL_CV2_NUMBER_NOT_PRESENT, $insert_id,
					$this->_card_number_middle_digits);
			} else {
				$message = sprintf(CEON_MANUAL_CARD_TEXT_EMAIL, $insert_id, $this->_card_number_middle_digits,
					$this->_card_cv2_number);
			}
		} else {
			$message = sprintf(CEON_MANUAL_CARD_TEXT_EMAIL_CV2_NUMBER_NOT_REQUESTED, $insert_id,
				$this->_card_number_middle_digits);
		}
		
		$html_msg['EMAIL_MESSAGE_HTML'] = str_replace("\n\n", '<br />', $message);
		
		zen_mail('', CEON_MANUAL_CARD_EMAIL, CEON_MANUAL_CARD_TEXT_EMAIL_SUBJECT . $insert_id, $message, STORE_OWNER, EMAIL_FROM, $html_msg, 'cc_middle_digs');
	}
	
	// }}}
	
	
	// {{{ after_order_create()
	
	/**
	 * Saves the additional credit card information for this transaction (that which is not normally stored in the
	 * orders table - Start Date, Issue Number and, if not in e-mail mode, any CV2 Number).
	 *
	 * @access  public
	 * @param   integer   $zen_order_id   The order id associated with this completed transaction.
	 * @return  none
	 */
	function after_order_create($zen_order_id)
	{
		global $db, $order;
		
		// Store the start date and issue number (if specified), and the CV2 number if it is to be stored instead
		// of being e-mailed
		$other_card_details = array(
			'order_id' => $zen_order_id,
			'cc_start' => (isset($order->info['cc_start']) ? $order->info['cc_start'] : ''),
			'cc_issue' => (isset($order->info['cc_issue']) ? $order->info['cc_issue'] : '')
			);
		
		if (!$this->_mustEMailMiddleDigitsAndCV2Number()) {
			
			$other_card_details['cc_card_number'] = $this->_card_number;
			
			if (strtolower(CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER) == 'yes') {
				$other_card_details['cc_cv2'] = $this->_card_cv2_number;
			}
		}
		
		zen_db_perform(TABLE_CEON_MANUAL_CARD, $other_card_details);
	}
	
	// }}}
	
	
	// {{{ admin_notification()
	
	/**
	 * Displays the additional transaction information on the order details page in the Admin.
	 *
	 * @access  public
	 * @param   integer   $zen_order_id   The id of the order linked with the transaction.
	 * @return  string    The HTML, CSS and JavaScript for the Ceon Manual Card Admin panels.
	 */
	function admin_notification($zen_order_id)
	{
		global $db, $messageStack;
		
		$transaction_info_sql = "
			SELECT
				*
			FROM
				" . TABLE_CEON_MANUAL_CARD . "
			WHERE
				order_id = '" . $zen_order_id . "'";
		
		$ceon_manual_card_result = $db->Execute($transaction_info_sql);
		
		require(DIR_FS_CATALOG. DIR_WS_MODULES .
			'payment/ceon_manual_card/ceon_manual_card_admin_notification.php');
		
		return $output;
	}
	
	// }}}
	
	
	// {{{ _referredFromCheckoutProcessURI()
	
	/**
	 * Checks if Ceon Manual Card is being used on a page that the user has arrived at from another page in the
	 * checkout process.
	 *
	 * @access  protected
	 * @return  boolean   Whether or not the referring URI is from the checkout process.
	 */
	function _referredFromCheckoutProcessURI()
	{
		$referring_uri = getenv('HTTP_REFERER');
		
		if ($referring_uri !== false) {
			$referring_uri = strtolower($referring_uri);
			$referring_uri = str_replace('&amp;', '&', $referring_uri);
			
			// Build a list of the standard Zen Cart checkout process URIs
			$checkout_page_uris = array(
				zen_href_link(FILENAME_SHOPPING_CART, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false)
				);
			
			// Add checkout URIs for installed third party modules to the list
			if (defined('FILENAME_CHECKOUT')) {
				$checkout_page_uris[] = zen_href_link(FILENAME_CHECKOUT, '', 'SSL', false);
			}
			
			if (defined('FILENAME_FEC_CONFIRMATION')) {
				$checkout_page_uris[] = zen_href_link(FILENAME_FEC_CONFIRMATION, '', 'SSL', false);
			}
			
			if (defined('FILENAME_QUICK_CHECKOUT')) {
				$checkout_page_uris[] = zen_href_link(FILENAME_QUICK_CHECKOUT, '', 'SSL', false);
			}
			
			foreach ($checkout_page_uris as $checkout_page_uri) {
				// Format URI to be tested to the same format the referring URI is in (lowercase and no encoded
				// ampersands)
				$checkout_page_uri = strtolower($checkout_page_uri);
				$checkout_page_uri = str_replace('&amp;', '&', $checkout_page_uri);
				
				// Only concerned with main part of URI
				$checkout_page_uri = preg_replace('|https?://[^/]+|', '', $checkout_page_uri);
				
				if (strpos($referring_uri, $checkout_page_uri) !== false) {
					// Referring URI is a valid checkout URI
					return true;
				}
			}
		}
		
		return false;
	}
	
	// }}}
	
	
	// {{{ _showStartDate()
	
	/**
	 * Examines the list of cards accepted and determines whether at least one of them may need a start date to be
	 * supplied for card processing to take place.
	 *
	 * @access  protected
	 * @return  boolean   Whether at least one of the cards accepted may need a start date to be supplied for card
	 *                    processing to take place (boolean true for yes!)
	 */
	function _showStartDate()
	{
		if (strtolower(CEON_MANUAL_CARD_ASK_FOR_START_DATE) != 'yes') {
			return false;
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_MAESTRO) == 'yes') {
			return true;
		}
		
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS) == 'yes') {
			return true;
		}
		
		return false;
	}
	
	// }}}
	
	
	// {{{ _showIssueNumber()
	
	/**
	 * Examines the list of cards accepted and determines whether at least one of them may need an issue number to
	 * be supplied for card processing to take place.
	 *
	 * @access  protected
	 * @return  boolean   Whether at least one of the cards accepted may need an issue number to be supplied for
	 *                    card processing to take place (true for yes).
	 */
	function _showIssueNumber()
	{
		if (strtolower(CEON_MANUAL_CARD_ACCEPT_MAESTRO) == 'yes') {
			return true;
		}
		
		return false;
	}
	
	// }}}
	
	
	// {{{ _mustEMailMiddleDigitsAndCV2Number()
	
	/**
	 * Simply returns whether the middle digits of the card number and the CV2 number (if it is being used) should
	 * be e-mailed, or stored in the database in full.
	 *
	 * @access  protected
	 * @return  boolean   Whether the middle digits of the card number and the CV2 number (if it is being used)
	 *                    should be e-mailed. (Otherwise they are stored in the database in full).
	 */
	function _mustEMailMiddleDigitsAndCV2Number()
	{
		return (strpos(strtolower(CEON_MANUAL_CARD_MODE), 'e-mail') !== false);
	}
	
	// }}}
	
	
	// {{{ _getCardTypeNameForCode()
	
	/**
	 * Returns the name of the card type for the given card type code. If a surcharge or discount has been defined
	 * for the card type which matches the order, details of the surcharge/discount are appended to the name. This
	 * surcharge/discount is then applied by the Ceon Payments/Surcharges Order Total module.
	 *
	 * @access  protected
	 * @param   string    $card_type_code                The code of the card type for which the name should be
	 *                                                   returned.
	 * @param   boolean   $add_surcharge_discount_info   Whether or not to add details about surcharge/discount
	 *                                                   after the name.
	 * @return  string    The name of the card type (optionally inc any surcharge/discount info).
	 */
	function _getCardTypeNameForCode($card_type_code, $add_surcharge_discount_info = true)
	{
		$card_type_name = '';
		
		switch ($card_type_code) {
			case 'VISA':
				$card_type_name = CEON_MANUAL_CARD_TEXT_VISA;
				break;
			case 'MASTERCARD':
				$card_type_name = CEON_MANUAL_CARD_TEXT_MASTERCARD;
				break;
			case 'VISA_DEBIT':
				$card_type_name = CEON_MANUAL_CARD_TEXT_VISA_DEBIT;
				break;
			case 'MASTERCARD_DEBIT':
				$card_type_name = CEON_MANUAL_CARD_TEXT_MASTERCARD_DEBIT;
				break;
			case 'MAESTRO':
				$card_type_name = CEON_MANUAL_CARD_TEXT_MAESTRO;
				break;
			case 'VISA_ELECTRON':
				$card_type_name = CEON_MANUAL_CARD_TEXT_VISA_ELECTRON;
				break;
			case 'AMERICAN_EXPRESS':
				$card_type_name = CEON_MANUAL_CARD_TEXT_AMERICAN_EXPRESS;
				break;
			case 'DINERS_CLUB':
				$card_type_name = CEON_MANUAL_CARD_TEXT_DINERS_CLUB;
				break;
			case 'JCB':
				$card_type_name = CEON_MANUAL_CARD_TEXT_JCB;
				break;
			case 'LASER':
				$card_type_name = CEON_MANUAL_CARD_TEXT_LASER;
				break;
			case 'DISCOVER':
				$card_type_name = CEON_MANUAL_CARD_TEXT_DISCOVER;
				break;
			default:
				break;
		}
		
		// Check if the Surcharges/Discounts Order Total module is in use and if so, whether any
		// surcharges/discounts have been specified for the specified card type and should be appended to the card
		// type's name
		if (strtolower(CEON_MANUAL_CARD_ENABLE_SURCHARGES_DISCOUNTS) == 'yes' &&
				isset($GLOBALS['ceon_pymnt_surcharges_discounts']) && $add_surcharge_discount_info) {
			
			// Check if there are any surcharges/discounts defined for the specified card type
			$tables_of_rates = $this->_getSurchargeDiscountTablesOfRates($card_type_code);
			
			if ($tables_of_rates !== false) {
				// Check if any rate applies to the current order
				
				$surcharge_or_discount_calculated =
					$GLOBALS['ceon_pymnt_surcharges_discounts']->calculateSurchargeOrDiscount($tables_of_rates);
				
				if ($surcharge_or_discount_calculated == false) {
					// There was a problem determining the rate. Alert the customer about the error
					$card_type_name .= ' (' . $GLOBALS['ceon_pymnt_surcharges_discounts']->getErrorMessage() . ')';
				} else {
					// A surcharge or discount may apply to this card type and order value
					
					// Get any tables of short descriptions defined for this card type
					$tables_of_short_descs = trim(constant('CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_' .
						$card_type_code . '_SHORT_' . strtoupper($_SESSION['languages_code'])));
					
					$short_desc = $GLOBALS['ceon_pymnt_surcharges_discounts']->
						getShortDescription($tables_of_short_descs, CEON_MANUAL_CARD_TEXT_SURCHARGE_SHORT,
							CEON_MANUAL_CARD_TEXT_DISCOUNT_SHORT);
					
					if (strlen($short_desc) > 0) {
						// A surcharge or discount applies to this card type and order value. Alert the customer to
						// the amount
						$card_type_name .= ' (' . $short_desc . ')';
					}
				}
			}
		}
		
		return $card_type_name;
	}
	
	// }}}
	
	
	// {{{ _isCardNumberMasterCardDebit()
	
	/**
	 * Checks if the specified card number is for a MasterCard debit card.
	 *
	 * @access  protected
	 * @param   integer   $card_number   The number of the card to be checked.
	 * @return  boolean   Whether the card is a MasterCard debit card or not.
	 */
	function _isCardNumberMasterCardDebit($card_number)
	{
		// Information from http://www.barclaycard.co.uk/business/documents/pdfs/bin_rules.pdf
		$mastercard_debit_bin_codes = array(
			'512499',
			'512746',
			'516001',
			'516730-516979',
			'517000-517049',
			'524342',
			'527591',
			'535110-535309',
			'535420-535819',
			'537210-537609',
			'557347-557496',
			'557498-557547'
			);
		
		$first_six_digits = substr($card_number, 0, 6);
		
		foreach ($mastercard_debit_bin_codes as $mastercard_debit_bin_code) {
			if (strpos('-', $mastercard_debit_bin_code) !== false) {
				// Compare against a range of codes
				$bin_code_limits = split('-', $mastercard_debit_bin_code);
				
				if ((int) $first_six_digits >= (int) $bin_code_limits[0] &&
						(int) $first_six_digits <= (int) $bin_code_limits[1]) {
					return true;
				}
			} else {
				// Compare against a single code
				if ($first_six_digits == $mastercard_debit_bin_code) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	// }}}
	
	
	// {{{ _buildSurchargeOrDiscount()
	
	/**
	 * Helper method, simply checks if the Ceon Surcharges/Discounts Order Total module is in use and, if so,
	 * configures the module with the tables of rates and long descriptions it should use when calculating a
	 * surcharge/discount.
	 *
	 * @access  protected
	 * @return  none
	 */
	function _buildSurchargeOrDiscount()
	{
		if (strtolower(CEON_MANUAL_CARD_ENABLE_SURCHARGES_DISCOUNTS) == 'yes' &&
				isset($GLOBALS['ceon_pymnt_surcharges_discounts'])) {
			
			// Check if there are any surcharges/discounts defined for the specified card type
			$tables_of_rates = $this->_getSurchargeDiscountTablesOfRates($this->_card_type);
			
			if ($tables_of_rates !== false) {
				// Get any tables of long descriptions defined for the card type
				$tables_of_long_descs = trim(constant('CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_' .
					$this->_card_type . '_LONG_' . strtoupper($_SESSION['languages_code'])));
				
				$GLOBALS['ceon_pymnt_surcharges_discounts']->setTablesOfRatesAndLongDescriptions(
					$tables_of_rates, $tables_of_long_descs, CEON_MANUAL_CARD_TEXT_SURCHARGE_LONG,
					CEON_MANUAL_CARD_TEXT_DISCOUNT_LONG);
			}
		}
	}
	
	// }}}
	
	
	// {{{ _getSurchargeDiscountTablesOfRates()
	
	/**
	 * Gets any surcharge/discount tables of rates defined for the specified card type.
	 *
	 * @access  protected
	 * @param   string    $card_type_code   The code of the card type for which the surcharge/discount tables of
	 *                                      rates should be returned.
	 * @return  string    The surcharge/discount tables of rates for the specified card type.
	 */
	function _getSurchargeDiscountTablesOfRates($card_type_code)
	{
		$surcharges_discounts = preg_replace('/[\s]+/', '', constant(
			'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_' . $card_type_code));
		
		if (!is_null($surcharges_discounts) && strlen($surcharges_discounts) > 0) {
			return $surcharges_discounts;
		}
		
		return false;
	}
	
	// }}}
	// {{{ _checkModuleEnvironment()
	
	/**
	 * Checks that the module has a valid, up-to-date configuration and database table. If not, attempts to update
	 * the configuration appropriately and/or create or update the database table as required. If any errors are
	 * encountered, the error property can be set and error messages stored to be displayed to the user later, in
	 * the module's description.
	 * 
	 * @access  protected
	 * @return  none
	 */
	function _checkModuleEnvironment()
	{
		global $db, $messageStack;
		
		// Main control variable, determines what checks can take place (are worth doing)
		$config_verified = false;
		
		$this->_checkServerEnvironment();
		
		// If module is being used in the admin, check for common mistake of missing file(s)
		$admin_files_missing = false;
		
		if (IS_ADMIN_FLAG === true) {
			$admin_files_missing = $this->_checkForMissingAdminFiles();
		}
		
		// Check if essential catalog files which are commonly left out aren't in fact missing
		$catalog_files_missing = $this->_checkForMissingCatalogFiles();
		
		// Check if the module needs to be upgraded
		if (!is_null($this->_current_config_version) && ($this->_current_config_version == $this->_version &&
				$this->_current_config_edition_code == $this->_ceon_model_edition_code) &&
				!isset($_GET['check-config'])) {
			// Configuration is up to date and manual call to check configuration hasn't been made
			$config_verified = true;
			
		} else if (!is_null($this->_current_config_version) &&
				($this->_current_config_version != $this->_version ||
				$this->_current_config_edition_code != $this->_ceon_model_edition_code) ||
				isset($_GET['check-config'])) {
			// Installed version doesn't match expected version
			
			// Disable module as it must be successfully upgraded and reloaded before it can be used
			$this->_critical_config_problem = true;
			
			// Only upgrade the module when being viewed in admin mode
			if (IS_ADMIN_FLAG === true && isset($_GET['set']) && $_GET['set'] == 'payment' &&
					isset($_GET['module']) && $_GET['module'] == $this->code) {
				// Expected files are present and user is looking at the Ceon Manual Card module in the payment
				// admin, so attempt to upgrade the module
				
				$this->_upgradeConfiguration();
				
				$config_up_to_date_result = $db->Execute("
					SELECT
						configuration_value
					FROM
						" . TABLE_CONFIGURATION . "
					WHERE
						configuration_key = 'MODULE_PAYMENT_CEON_MANUAL_CARD_MADE_BY_CEON'
					OR
						configuration_key = 'CEON_MANUAL_CARD_MADE_BY_CEON';");
				
				if ($config_up_to_date_result->EOF ||
						$config_up_to_date_result->fields['configuration_value'] != $this->version) {
					$config_up_to_date = false;
				} else {
					$config_up_to_date = true;
				}
				
				if ($config_up_to_date && $this->_action_performed) {
					// As upgrade has worked, must reload payment page so that options are parsed and loaded
					// correctly (so they can be checked)
					if (isset($_SERVER['REQUEST_URI']) && isset($_SERVER['QUERY_STRING'])) {
						// Rebuild exact URI to reload this page
						$reload_uri = $_SERVER['REQUEST_URI'];
						
					} else {
						// Can't get exact URI used to reload this page so build a best guess
						$query_string = 'set=payment' . '&module=' . $this->code;
						
						$reload_uri = zen_href_link(FILENAME_MODULES, $query_string, 'NONSSL');
					}
					
					// Rather than telling user to reload the page, give them a link to click
					$this->_config_messages[] =
						'<p style="color: green"><strong>Module has been upgraded!</strong></p>';
					
					$this->_config_messages[] = '<p style="color: red">However it now needs to reload itself..' .
						' you must click the following link:</p>';
					
					$this->_config_messages[] = '<p><a href="' . htmlspecialchars($reload_uri) . '">' .
						'Reload module' . '</a></p>';
					
				} else if ($this->_action_performed) {
					$this->_config_messages[] =
						'<p style="color: red"><strong>Module is out of date!</strong></p>';
					
					$this->_config_messages[] = '<p style="color: red">The module could not be upgraded' .
						' automatically..</p>';
					
					$this->_config_messages[] = '<p>Please navigate back to this page to try again. If all else' .
						' fails, &ldquo;Remove&rdquo; the module and &ldquo;Install&rdquo; it once more' .
						' according to the installation instructions.</p>';
				}
			}
		}
		
		// Perform any configuration/checks which can take place only when the module is installed
		if ($config_verified) {
			// Check that the module has been configured properly 
			if (defined('CEON_MANUAL_CARD_MODE') && $this->_mustEMailMiddleDigitsAndCV2Number() &&
					defined('CEON_MANUAL_CARD_EMAIL') && !zen_validate_email(CEON_MANUAL_CARD_EMAIL)) {
				$this->_critical_config_problem = true;
				
				$this->_config_messages[] .= '<p style="color: red">A valid e-mail address hasn\'t been entered' .
					' in the configuration.</p><p>Currently only simple e-mail addresses in format' .
					' user@domain.com are supported (i.e. multiple addresses aren\'t supported, nor are' .
					' addresses in the format &quot;User Name&quot; &lt;user@domain.com&gt;).</p>';
			}
		}
		
		// If the module is installed, check that the database table exists and that it is up to date
		if (!is_null($this->_current_config_version) && !$admin_files_missing) {
			$table_creation_attempted = false;
			
			$table_exists_query = 'SHOW TABLES LIKE "' . TABLE_CEON_MANUAL_CARD . '";';
			
			$table_exists_result = $db->Execute($table_exists_query);
			
			if ($table_exists_result->EOF) {
				// Attempt to create the database as the module is installed but the user has not created the
				// database!
				$this->_createDatabaseTable();
				
				$table_creation_attempted = true;
				
				// Check if the attempt to create the database succeeded
				$table_exists_query = 'SHOW TABLES LIKE "' . TABLE_CEON_MANUAL_CARD . '";';
				
				$table_exists_result = $db->Execute($table_exists_query);
			}
			
			if ($table_exists_result->EOF) {
				// Database doesn't exist and could not be created
				$this->_critical_config_problem = true;
				
				$this->_config_messages[] = '<p><strong><span style="color: red">Error:</span>' .
					'<br />The Ceon Manual Card Database Table Does Not Exist!</strong></p>';
				
				$this->_config_messages[] = '<p>The database table could not be created! The' .
					' database user may not have CREATE TABLE privileges?!</p>';
					
				$this->_config_messages[] = '<p>Check/fix the database user\'s privileges and come back to this' .
					' page once more to have the module attempt to create the database table again.</p>';
				
			} else if (IS_ADMIN_FLAG === true && $table_creation_attempted) {
				// Let the user know that the database table was just created
				$messageStack->add_session('Ceon Manual Card database table successfully created.', 'success');
				
				// Database table is up to date
				$this->_current_database_version = $this->_version;
				
				$this->_current_database_edition_code = $this->_ceon_model_edition_code;
				
			} else {
				// Make sure the database is up to date
				$database_up_to_date = $this->_checkAndUpgradeDatabaseTable();
				
				if (!$database_up_to_date) {
					// Couldn't update database table
					$this->_critical_config_problem = true;
					
					$this->_config_messages[] = '<p><strong><span style="color: red">Warning:</span>' .
						'<br />The Ceon Manual Card Database Table is Out Of Date!</strong></p>';
					
					$this->_config_messages[] = '<p>The database table could not be updated! The database user' .
						' may not have ALTER privileges?!</p>';
						
					$this->_config_messages[] = '<p>Check/fix the database user\'s privileges and come back to' .
						' this page once more to have the module attempt to update the database table again.</p>';
				}
			}
		}
		
		// If user is looking at the module in the payment admin, check for common mistake of missing/out-of-date
		// surcharges/discounts module
		if ($config_verified && IS_ADMIN_FLAG === true &&
				isset($_GET['set']) && $_GET['set'] == 'payment' &&
				isset($_GET['module']) && $_GET['module'] == $this->code) {
			
			$this->_checkSurchargeDiscountsModule();
		}
	}
	
	// }}}
	
	
	// {{{ _checkServerEnvironment()
	
	/**
	 * Checks if the required software is present on the server. Only needed to check if software used for
	 * automatic version checking is missing when automatic version checking is enabled.
	 * 
	 * @access  protected
	 * @return  none
	 */
	function _checkServerEnvironment()
	{
		// cURL functionality is required for the automatic version checker
		if (IS_ADMIN_FLAG === true && defined('CEON_MANUAL_CARD_VERSION_CHECK') &&
				strtolower(CEON_MANUAL_CARD_VERSION_CHECK) == 'automatic' &&
				!extension_loaded('curl')) {
			$this->_config_messages[] = '<strong><span style="color: red">Warning:</span>' .
				'<br />The cURL extension is not loaded in the PHP installation!</strong>' .
				'<br /><br />The automatic version checking cannot take place.' .
				'<br /><br />The module\'s version can only be checked manually.<br /><br />';
		}
	}
	
	// }}}
	
	
	// {{{ _checkForMissingAdminFiles()
	
	/**
	 * Checks if the required admin files are present on the server. If any aren't, updates the main configuration
	 * status variable and builds instructions for the admin user.
	 * 
	 * @access  protected
	 * @return  boolean   True if one or more files are missing, false otherwise.
	 */
	function _checkForMissingAdminFiles()
	{
		$admin_file_missing = false;
		
		// If the database table name file is not uploaded to the correct place in the admin as well as the store's
		// side then the database table won't be able to be found in the admin
		$admin_database_table_name_file = DIR_FS_ADMIN . DIR_WS_INCLUDES . 'extra_datafiles/' .
			'ceon_manual_card_database_tables.php';
		
		if (!file_exists($admin_database_table_name_file)) {
			
			$admin_file_missing = true;
			
			$this->_critical_config_problem = true;
			
			$this->_config_messages[] = '<p style="color: red"><strong>Admin file missing!</strong></p>' .
				'<p style="color: red">At least one Ceon Manual Card admin file is missing:</p>';
			
			$this->_config_messages[] = '<p>' . $admin_database_table_name_file . '</p>';
			
			// Attempt to work out what has been done wrongly, so things can be fixed more easily
			$admin_dir_name = DIR_WS_ADMIN;
			
			if (substr($admin_dir_name, 0, 1) == '/' || substr($admin_dir_name, 0, 1) == '\\') {
				$admin_dir_name = substr($admin_dir_name, 1, strlen($admin_dir_name) - 1);
			}
			
			if (substr($admin_dir_name, -1) == '/' || substr($admin_dir_name, -1) == '\\') {
				$admin_dir_name = substr($admin_dir_name, 0, strlen($admin_dir_name) - 1);
			}
			
			$admin_dir_path = DIR_FS_ADMIN;
			
			if (substr($admin_dir_path, -1) == '/' || substr($admin_dir_path, -1) == '\\') {
				$admin_dir_path = substr($admin_dir_path, 0, strlen($admin_dir_path) - 1);
			}
			
			if (strtolower($admin_dir_name) != 'admin') {
				$this->_config_messages[] = '<p style="color: red">' .
					'The store\'s admin folder is not called &ldquo;admin&rdquo; but has been changed to' .
					' &ldquo;' . $admin_dir_name . '&rdquo;.</p>';
				
				if (strtolower(substr($admin_dir_path, -1 * strlen($admin_dir_name))) !=
						strtolower($admin_dir_name)) {
					$this->_config_messages[] =  '<p style="color: red">' .
						'The value of DIR_FS_ADMIN in the configure.php file does not appear to match the' .
						' admin\'s directory: &ldquo;' . $admin_dir_name . '&rdquo;.</p>';
					
					$this->_config_messages[] =  '<p>' .
						'Please edit the admin configure.php file to check/fix the values for DIR_WS_ADMIN and' .
						' DIR_FS_ADMIN.</p>';
				} else {
					$this->_config_messages[] =  '<p style="color: red">' .
						'Check that the Ceon Manual Card admin files have been placed in' .
						' &ldquo;' . $admin_dir_name . '&rdquo; and not &ldquo;admin&rdquo;.</p>';
				}
			} else {
				if (strtolower(substr($admin_dir_path, -5)) != 'admin') {
					$this->_config_messages[] =  '<p style="color: red">' .
						'The value of DIR_FS_ADMIN in the configure.php file does not appear to match the' .
						' admin\'s directory: &ldquo;admin&rdquo;.</p>';
					
					$this->_config_messages[] =  '<p>' .
						'Please edit the admin configure.php file to check/fix the values for DIR_WS_ADMIN and' .
						' DIR_FS_ADMIN.</p>';
				} else {
					$this->_config_messages[] = '<p style="color: red">' .
						'Please carefully upload all the module\'s files once again, according to the' .
						' installation instructions.</p>';
				}
			}
		}
		
		return $admin_file_missing;
	}
	
	// }}}
	
	
	// {{{ _checkForMissingCatalogFiles()
	
	/**
	 * Checks if the required catalog files are present on the server. If any aren't, updates the main
	 * configuration status variable and builds instructions for the admin user.
	 * 
	 * @access  protected
	 * @return  boolean   True if one or more files are missing, false otherwise.
	 */
	function _checkForMissingCatalogFiles()
	{
		$missing_store_files = array();
		
		$store_database_table_name_file = DIR_FS_CATALOG . DIR_WS_INCLUDES .
			'extra_datafiles/ceon_manual_card_database_tables.php';
		
		if (!file_exists($store_database_table_name_file)) {
			$missing_store_files[] = $store_database_table_name_file;
		}
		
		if (sizeof($missing_store_files) > 0) {
			
			$this->_critical_config_problem = true;
			
			if (sizeof($missing_store_files) == 1) {
				$this->_config_messages[] = '<p style="color: red"><strong>File missing!</strong></p>' .
					'<p style="color: red">At least one Ceon Manual Card file is missing:</p>';
			} else {
				$this->_config_messages[] = '<p style="color: red"><strong>Files missing!</strong></p>' .
					'<p style="color: red">At the very least these Ceon Manual Card files are missing:</p>';
			}
			
			foreach ($missing_store_files as $missing_store_file) {
				$this->_config_messages[] = '<p>' . $missing_store_file . '</p>';
			}
			
			$this->_config_messages[] = '<p style="color: red">' .
				'Please carefully upload all the module\'s files once again, according to the installation' .
				' instructions.</p>';
		}
		
		return (sizeof($missing_store_files) > 0);
	}
	
	// }}}
	
	
	// {{{ _checkSurchargeDiscountsModule()
	
	/**
	 * Builds instructions for the admin user if surcharge/discount functionality enabled but module not
	 * installed/enabled.
	 * 
	 * @access  protected
	 * @return  none
	 */
	function _checkSurchargeDiscountsModule()
	{
		if ((defined('CEON_MANUAL_CARD_ENABLE_SURCHARGES_DISCOUNTS') &&
				strtolower(CEON_MANUAL_CARD_ENABLE_SURCHARGES_DISCOUNTS) == 'yes')) {
			if (defined('CEON_PAYMENT_SURCHARGES_DISCOUNTS_ENABLED') &&
					substr(CEON_PAYMENT_SURCHARGES_DISCOUNTS_MADE_BY_CEON, 0, 1) < 3) {
				
				$this->_config_messages[] = sprintf('<p><strong><span style="color: red">Warning:</span>' .
					'<br />Surcharge/Discount functionality has been enabled but the version of the Ceon Payment' .
					' Surcharges/Discounts module which is installed (%s) is out of date!</strong></p>',
					CEON_PAYMENT_SURCHARGES_DISCOUNTS_MADE_BY_CEON);
				
				$this->_config_messages[] =
					'<p>Please install the latest version of the software (3.0.1 or better).</p>';
				
			} else if (!defined('CEON_PAYMENT_SURCHARGES_DISCOUNTS_ENABLED') &&
					defined('MODULE_ORDER_TOTAL_PAYMENT_SURCHARGES_DISCOUNTS_ENABLED')) {
				
				$this->_config_messages[] = '<p><strong><span style="color: red">Warning:</span>' .
					'<br />Up-to-date version of Ceon Payment Surcharges/Discounts module not installed!' .
					'</strong></p>';
				
				$this->_config_messages[] = '<p>Surcharge/Discount functionality has been enabled but the' .
					' Payment Surcharges/Discounts Order Total module installed is an old version!</p>';
				
				$this->_config_messages[] =
					'<p>Please install the latest version of the software (3.0.1 or better).</p>';
				
			} else if (!defined('CEON_PAYMENT_SURCHARGES_DISCOUNTS_ENABLED')) {
				$this->_config_messages[] = '<p><strong><span style="color: red">Warning:</span>' .
					'<br />Ceon Payment Surcharges/Discounts module not installed!</strong></p>';
				
				$this->_config_messages[] = '<p>Surcharge/Discount functionality has been enabled but the Ceon' .
					' Payment Surcharges/Discounts Order Total module has not been installed!</p>';
				
			} else if (strtolower(CEON_PAYMENT_SURCHARGES_DISCOUNTS_ENABLED) != 'yes') {
				$this->_config_messages[] = '<p><strong><span style="color: red">Warning:</span>' .
					'<br />Ceon Payment Surcharges/Discounts module not enabled!</strong></p>';
				
				$this->_config_messages[] = '<p>Surcharge/Discount functionality has been enabled but the Ceon' .
					' Payment Surcharges/Discounts Order Total module has been disabled!</p>';
				
				$this->_config_messages[] = '<p>Please enable the Ceon Payment Surcharges/Discounts Order Total' .
					' module.</p>';
			}
		}
	}
	
	// }}}
	
	
	// {{{ _getConfigurationMessagesOutput()
	
	/**
	 * Builds the list of configuration messages, information about the version of the files and database being
	 * used as well as information about/links to any update for the software, to be displayed to the admin user.
	 * 
	 * @access  protected
	 * @return  string    The configuration information to be displayed to the admin user.
	 */
	function _getConfigurationMessagesOutput()
	{
		$config_messages = '';
		
		// Add styles as IE looks pretty poor when rendering unstyled fieldsets
		if (is_null($this->_current_config_version)) {
			// Styles are only added here if the module isn't installed as they'll be present already otherwise
			$config_messages .= '<style type="text/css">' . "\n" .
				'fieldset { padding: 0.6em; }' . "\n" .
				'fieldset p { margin: 0 0 0.8em 0; }' . "\n" .
				'td.infoBoxContent { padding-left: 0.6em; padding-right: 0.6em; }' . "\n" .
				"</style>\n";
		}
		
		// If any problems have been detected, output an overview
		if (sizeof($this->_config_messages) > 0) {
			$config_messages .= '<fieldset style="background: #f7f6f0; margin-bottom: 1.5em">' .
				'<legend style="font-size: 1.2em; font-weight: bold">Configuration Issues</legend>';
			
			foreach ($this->_config_messages as $config_message) {
				$config_messages .= $config_message;
			}
			
			$config_messages .= '</fieldset>';
		}
		
		// Output version information
		$config_messages .= '<fieldset style="background: #f7f6f0; margin-bottom: 1.5em">' .
			'<legend style="font-size: 1.2em; font-weight: bold">' . 'Module Version Information</legend>';
		
		$config_messages .= '<p>Files Version: ' . $this->_version .
			(!is_null($this->_ceon_model_edition_code) ? ' ' .
			$this->_getEditionTitle($this->_ceon_model_edition_code) : '');
		
		$config_messages .= '<br />Installed Version: ' .
			(is_null($this->_current_config_version) ? 'Module Not Installed' :
			$this->_current_config_version . (!is_null($this->_current_config_edition_code) ? ' ' .
			$this->_getEditionTitle($this->_current_config_edition_code) : ''));
		
		if (!is_null($this->_current_database_version)) {
			$config_messages .= '<br />Database Version: ' . $this->_current_database_version .
				(!is_null($this->_current_database_edition_code) ? ' ' .
				$this->_getEditionTitle($this->_current_database_edition_code) : '');
		}
		
		$config_messages .= '</p>';
		
		// Output any information about the latest version or a link to check the version manually
		$config_messages .= $this->_getVersionCheckerOutput();
		
		$config_messages .= '</fieldset>';
		
		
		return $config_messages;
	}
	
	// }}}
	
	
	// {{{ _getVersionCheckerOutput()
	
	/**
	 * If automatic version checking is enabled, connects to Ceon's version checker server to check if the current
	 * version is out of date. If the current version is out of date, information about the latest version and a
	 * link to download the update are returned. If an error is encountered, it is returned for display.
	 *
	 * If automatic checking is disabled, a link to check the version manually is returned.
	 * 
	 * @access  protected
	 * @return  string    The information about the latest version, an error message, or a link to manually check
	 *                    the version.
	 */
	function _getVersionCheckerOutput()
	{
		$output = '';
		
		$output_manual_link = false;
		
		if (isset($_GET['reset-version-checker-response']) &&
				isset($_SESSION[$this->_ceon_base_model_code . '_vc_response'])) {
			unset($_SESSION[$this->_ceon_base_model_code . '_vc_response']);
		}
		
		if (extension_loaded('curl') &&
				((defined('CEON_MANUAL_CARD_VERSION_CHECK') &&
				strtolower(CEON_MANUAL_CARD_VERSION_CHECK) == 'automatic') ||
				!defined('CEON_MANUAL_CARD_VERSION_CHECK'))) {
			// If a response has been recorded for this session, don't try again in this session
			if (isset($_SESSION[$this->_ceon_base_model_code . '_vc_response'])) {
				$version_checker_response = $_SESSION[$this->_ceon_base_model_code . '_vc_response'];
			} else {
				// Post the information needed for the version check to the Ceon version checker server
				
				// Build the data required for the version check
				$uri = 'https://version-checker.ceon.net/';
				
				$data = 'base_model_code=' . $this->_ceon_base_model_code;
				
				if (!is_null($this->_ceon_model_edition_code)) {
					$data .= '&model_edition_code=' . $this->_ceon_model_edition_code;
				}
				
				$data .= '&version=' . $this->_version;
				
				$data .= '&language_code=' . $_SESSION['languages_code'];
				
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $uri);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, '5');
				curl_setopt($ch, CURLOPT_REFERER, HTTP_SERVER);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				$version_checker_response = curl_exec($ch);
				
				curl_close($ch);
			}
			
			// Parse information returned from version check and display appropriate output to user
			if (strlen($version_checker_response) == 0) {
				$output .= '<p style="margin-bottom: 0.4em"><strong>' .
					'Problem occurred performing version check: no response received.</strong></p>';
				
				$output_manual_link = true;
				
			} else if ($version_checker_response == '1') {
				// Version is up to date
				$_SESSION[$this->_ceon_base_model_code . '_vc_response'] = $version_checker_response;
				
				$output .= '<p style="margin-bottom: 0em">';
				
				if (!is_null($this->_current_database_version)) {
					$output .= 'Most up-to-date version is installed.';
				} else {
					$output .= 'Most up-to-date version of files are present.';
				}
				
				$output .= '</p>';
				
			} else if (substr($version_checker_response, 0, 1) == '-') {
				// Error occurred looking up version number
				$_SESSION[$this->_ceon_base_model_code . '_vc_response'] = $version_checker_response;
				
				$output .= '<p style="margin-bottom: 0.4em"><strong>' .
					'Problem occurred performing version check:' . '</strong></p>';
				
				$output .= '<p style="margin-top: 0; margin-bottom: 0.8em">' .
					substr($version_checker_response, 3) . '</p>';
				
				$output_manual_link = true;
				
			} else if (preg_match('/^[0-9]+\.[0-9]+\.[0-9]+[^,]*,/', $version_checker_response)) {
				// Version is out of date, display latest version info and download link
				$_SESSION[$this->_ceon_base_model_code . '_vc_response'] = $version_checker_response;
				
				$latest_version_info = explode(',', $version_checker_response);
				
				$output .= '<p style="margin-bottom: 0em">' .
					'<strong style="color: red">Out of Date!</strong>' . '</p>';
				
				$output .= '<p style="margin-top: 0.4em">' .
					'The latest version of the software is <strong>' . $latest_version_info[0] .
					(!is_null($this->_ceon_model_edition_code) ? ' ' .
					$this->_getEditionTitle($this->_ceon_model_edition_code) : '') . '</strong>.</p>';
				
				if (isset($latest_version_info[2])) {
					// Display any additional message provided by the version checker server
					$output .= '<p><strong>' . $latest_version_info[2] . '</strong></p>';
				}
				
				$output .= '<p style="margin-bottom: 0em">' .
					'<a href="' . $latest_version_info[1] . '" target="_blank">' .
					'Click here to download the latest version</a>.</p>';
			} else {
				$output .= '<p style="margin-bottom: 0.4em"><strong>' .
					'Problem occurred performing version check: Unable to parse response.' . '</strong></p>';
				
				// Output the response received as a HTML comment so that the error can be possibly be identified
				$output .= "<!--\n" . $version_checker_response . "\n-->\n";
				
				$output_manual_link = true;
			}
		} else {
			$output_manual_link = true;
		}
		
		if ($output_manual_link) {
			// Can't autosubmit the version check form, user must use manual link
			$output .= '<p><a href="' .
				'http://ceon.net/software/business/zen-cart/ceon-manual-card/version-checker/' .
				(!is_null($this->_ceon_model_edition_code) ? $this->_ceon_model_edition_code . '-' : '') .
				$this->_version . '" target="_blank">Check for updates</a></p>';
		}
		
		return $output;
	}
	
	// }}}
	
	
	// {{{ update_status()
	
	/**
	 * Determines whether or not this payment method can be used for the current zone.
	 *
	 * @access  public
	 * @return  none
	 */
	function update_status()
	{
		global $order, $db;
		
		if (($this->enabled == true) && ($this->zone > 0)) {
			$check_flag = false;
			
			$sql = "
				SELECT
					zone_id
				FROM
					" . TABLE_ZONES_TO_GEO_ZONES . "
				WHERE
					geo_zone_id = '" . $this->zone . "'
				AND
					zone_country_id = '" . $order->billing['country']['id'] . "'
				ORDER BY
					zone_id
				";
			
			$check = $db->Execute($sql);
			
			while (!$check->EOF) {
				if ($check->fields['zone_id'] < 1) {
					$check_flag = true;
					break;
				} elseif ($check->fields['zone_id'] == $order->billing['zone_id']) {
					$check_flag = true;
					break;
				}
				
				$check->MoveNext();
			}
			
			if ($check_flag == false) {
				$this->enabled = false;
			}
		}
	}
	
	// }}}
	
	
	// {{{ install()
	
	/**
	 * Creates the necessary database table for the module and adds the module's configuration settings to the main
	 * Zen Cart configuration table.
	 *
	 * @access  public
	 * @return  boolean   Returns false if there is a problem with the installation but Zen Cart doesn't currently
	 *                    use the return value so installation is effectively aborted when an error occurs.
	 */
	function install()
	{
		global $db, $messageStack;
		
		// Reset the version check status as it may change when the software is installed if a
		// previous version was installed
		if (isset($_SESSION[$this->_ceon_base_model_code . '_vc_response'])) {
			unset($_SESSION[$this->_ceon_base_model_code . '_vc_response']);
		}
		
		// Create the database table //////
		$table_creation_attempted = false;
		
		// Check the table doesn't already exist (if the module is being reinstalled)
		$table_exists_query = 'SHOW TABLES LIKE "' . TABLE_CEON_MANUAL_CARD . '";';
		$table_exists_result = $db->Execute($table_exists_query);
		
		if ($table_exists_result->EOF) {
			// Database table doesn't exist
			$this->_createDatabaseTable();
			
			$table_creation_attempted = true;
			
		} else {
			// Table exists. Check if it is an old version of the database. If so, upgrade it.
			$database_table_updated = $this->_checkAndUpgradeDatabaseTable();
			
			if (!$database_table_updated) {
				$messageStack->add_session('Couldn\'t update database table! The database user may not have' .
					' ALTER TABLE privileges?!', 'error');
				
				return false;
			}
		}
		
		// Check the table was created
		$table_exists_query = 'SHOW TABLES LIKE "' . TABLE_CEON_MANUAL_CARD . '";';
		$table_exists_result = $db->Execute($table_exists_query);
		
		if ($table_exists_result->EOF) {
			$messageStack->add_session('Database table could not be created! The database user may not have' .
				' CREATE TABLE privileges?!', 'error');
			
			return false;
			
		} else if ($table_creation_attempted) {
			$messageStack->add_session('Database table successfully created.', 'success');
		} else {
			$messageStack->add_session('Existing database table found and being used.', 'success');
		}
		
		$this->_addConfigurationSettings();
	}
	
	// }}}
	
	
	// {{{ _createDatabaseTable()
	
	/**
	 * Creates the necessary database table for the module.
	 *
	 * @access  protected
	 * @return  none
	 */
	function _createDatabaseTable()
	{
		global $db;
		
		$db->Execute("
			CREATE TABLE
				" . TABLE_CEON_MANUAL_CARD . "
			(
				`id` INT(11) UNSIGNED NOT NULL auto_increment,
				`order_id` INT(11) NOT NULL default '0',
				`cc_start` VARCHAR(4) default NULL,
				`cc_issue` VARCHAR(2) default NULL,
				`cc_card_number` VARCHAR(20) default NULL,
				`cc_cv2` INT(3) UNSIGNED default NULL,
				`deleted_by` VARCHAR(32) default NULL,
				`deleted_datetime` DATETIME default NULL,
				PRIMARY KEY (`id`)
			);");
	}
	
	// }}}
	
	
	// {{{ _checkAndUpgradeDatabaseTable()
	
	/**
	 * Checks the database table for the module and upgrades it if necessary.
	 *
	 * @access  protected
	 * @return  boolean   True if the database table is up to date, false if it is out of date and there was a
	 *                    problem updating it.
	 */
	function _checkAndUpgradeDatabaseTable()
	{
		global $db, $messageStack;
		
		// Determine the version of the database table by checking if the cc_issue columns is the wrong length
		$columns_query = 'SHOW COLUMNS FROM ' . TABLE_CEON_MANUAL_CARD . ';';
		$columns_result = $db->Execute($columns_query);
		
		$columns = array();
		
		while (!$columns_result->EOF) {
			$columns[] = $columns_result->fields['Field'];
			
			$columns_result->MoveNext();
		}
		
		if (strtolower(str_replace(' ', '', $columns['cc_issue'])) == 'varchar(1)') {
			// Table is from an earlier version, upgrade it to the current version
			$this->_current_database_version = '1.x';
			
			$this->_current_database_edition_code = $this->_ceon_model_edition_code;
		}
		
		if ($this->_current_database_version == '1.x') {
			// Update (fix) the maximum length the column's values can be
			$db->Execute("
				ALTER TABLE
					" . TABLE_CEON_MANUAL_CARD . "
				CHANGE
					`cc_issue` `cc_issue` VARCHAR(2) default NULL;");
			
			// Verify that the column was changed
			$columns_info_query = 'SHOW COLUMNS FROM ' . TABLE_CEON_MANUAL_CARD . ';';
			$columns_info_result = $db->Execute($columns_info_query);
			
			$columns_info = array();
			
			while (!$columns_info_result->EOF) {
				$columns_info[$columns_info_result->fields['Field']] = $columns_info_result->fields['Type'];
				
				$columns_info_result->MoveNext();
			}
			
			if (strtolower(str_replace(' ', '', $columns_info['cc_issue'])) != 'varchar(2)') {
				// Unable to add column to table! The database user may not have ALTER TABLE privileges
				return false;
			}
		}
		
		if (!in_array('cc_card_number', $columns)) {
			// Add the new columns for version 4.0.1/4.0.0
			$db->Execute("
				ALTER TABLE
					" . TABLE_CEON_MANUAL_CARD . "
				ADD
					`cc_card_number` VARCHAR(20) default NULL
				AFTER
					`cc_issue`;");
			
			// Verify that the column was added
			$columns_query = 'SHOW COLUMNS FROM ' . TABLE_CEON_MANUAL_CARD . ';';
			$columns_result = $db->Execute($columns_query);
			
			$columns = array();
			
			while (!$columns_result->EOF) {
				$columns[] = $columns_result->fields['Field'];
				
				$columns_result->MoveNext();
			}
			
			if (!in_array('cc_card_number', $columns)) {
				// Unable to add column to table! The database user may not have ALTER TABLE privileges
				return false;
			}
			
			// Assume all other ALTER operations will work fine if the first one did
			if (!in_array('cc_cv2', $columns)) {
				$db->Execute("
					ALTER TABLE
						" . TABLE_CEON_MANUAL_CARD . "
					ADD
						`cc_cv2` INT(3) UNSIGNED default NULL
					AFTER
						`cc_card_number`;");
				
				$db->Execute("
					ALTER TABLE
						" . TABLE_CEON_MANUAL_CARD . "
					ADD
						`deleted_by` VARCHAR(32) default NULL
					AFTER
						`cc_cv2`;");
				
				$db->Execute("
					ALTER TABLE
						" . TABLE_CEON_MANUAL_CARD . "
					ADD
						`deleted_datetime` DATETIME default NULL
					AFTER
						`deleted_by`;");
			}
			
			$messageStack->add_session('Ceon Manual Card Database table upgraded successfully', 'success');
		}
		
		// Database table is up to date
		$this->_current_database_version = $this->_version;
		
		$this->_current_database_edition_code = $this->_ceon_model_edition_code;
		
		return true;
	}
	
	// }}}
	
	
	// {{{ _addConfigurationSettings()
	
	/**
	 * Adds the module's configuration settings to the main Zen Cart configuration table.
	 *
	 * @access  protected
	 * @return  none
	 */
	function _addConfigurationSettings()
	{
		global $db;
		
		// General configuration settings //////
		$background_colour = '#d0d0d0';
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b><style type=\"text/css\">fieldset { padding: 0.6em; } fieldset p { margin: 0 0 0.8em 0; } td.infoBoxContent { padding-left: 0.6em; padding-right: 0.6em; }</style><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">General Config</legend><b>Enable Ceon Manual Card Module', 'CEON_MANUAL_CARD_STATUS', 'Yes', 'Should Ceon Manual Card be enabled as a payment option for this store?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Module Operation Mode', 'CEON_MANUAL_CARD_MODE', 'Send CV2 Number and Middle Digits via E-mail (Safest option).', 'Should the <strong>CV2 number</strong> and the <strong>middle digits</strong> of the <strong>card number</strong> be sent by e-mail?<br /><br />Or should the CV2 number and the entire card number be stored alongside the order information?<br /><br />Having some of the the details sent by e-mail means not having the full card details in one central place, making it a safer way to collect card details for manual/offline processing.<br /><br />As such, the e-mail option is HIGHLY RECOMMENDED FOR BETTER SECURITY.', '6', '0', 'zen_cfg_select_option(array(\'Send CV2 Number and Middle Digits via E-mail (Safest option).\', \'Store <strong>all</strong> the card details, including the CV2 Number and the full Card Number, alongside the other order information (Increased security risk).\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-mail Address', 'CEON_MANUAL_CARD_EMAIL', '', 'The E-mail Address to which the CV2 Number and the Middle Digits of the Card Number should be sent.<br /><br />Only simple e-mail addresses in format <code>user@domain.com</code> are supported (i.e. multiple addresses aren\'t supported, nor are addresses in the format <code>&quot;User Name&quot; &lt;user@domain.com&gt;</code>).', '6', '0', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show Delete Button in Order Admin', 'CEON_MANUAL_CARD_ADMIN_SHOW_DELETE_BUTTON', 'Yes', 'Should a button be displayed in the Ceon Manual Card section of the order\'s admin page, allowing the delection of all the card details?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'CEON_MANUAL_CARD_ZONE', '0', 'If a zone is selected, this module will only be enabled for the selected zone.<br /><br />Leave set to \"--none--\" if Ceon Manual Card should be used for all customers, regardless of what zone their billing address is in.', '6', '0', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'CEON_MANUAL_CARD_ORDER_STATUS_ID', '0', 'Orders paid for using this module will have their order status set to this value.', '6', '0', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");
		
		
		// Security configuration settings //////
		$background_colour = '#eee';
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Security Options</legend><b>Store Sensitive Card Details Temporarily in Session?', 'CEON_MANUAL_CARD_STORE_SENSITIVE_DETAILS_IN_SESSION', 'Yes', 'Should the customer\'s sensitive card details - the Card Number and the Card CV2 Number - be stored temporarily in the session? (They\'ll be cleared from the session when the order is completed).<br /><br />As standard, if a customer leaves the payment details page to go back to the shipping page or the shopping cart, or if they make a mistake when entering their card details, the module will restore most of the details entered, so the customer doesn\'t have to re-enter them when they come back to the payment page.<br /><br />When this option is enabled, the Card Number and the Card CV2 Number will also be stored temporarily, encrypted in the session using the Blowfish algorithm.<br /><br />If this option is disabled, neither the Card Number nor the Card CV2 Number are stored in the session. Customers will have to re-enter their Card Number and their Card CV2 Number in full any time they come back to the payment page (i.e. if they don\'t go straight from the payment page to order completion).', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Encryption Keyphrase', 'CEON_MANUAL_CARD_BLOWFISH_ENCRYPTION_KEYPHRASE', '" . microtime() . md5(time()) . "', 'The keyphrase to be used to encrypt the Card details if they are to be (temporarily) stored in the session.<br /><br />This keyphrase can be <strong>any</strong> random text string, just make one up.', '6', '0', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Ask for CV2 Number', 'CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER', 'Yes', 'Should a field be displayed for the customer to enter a card CV2 number?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow cards with no CV2 Number', 'CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER', 'Yes', 'A small minority of cards have no CV2 number. If a customer has filled in all card details except for the CV2 number should they be given the option to indicate that their card has no CV2 number? (This is necessary for Laser cards as many of them tend not to have CV2 numbers. American Express Corporate Purchasing Cards don\'t have them either.)', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Disable Autocomplete for Card Number field', 'CEON_MANUAL_CARD_DISABLE_CARD_NUMBER_AUTOCOMPLETE', 'Yes', 'Should the autocomplete functionality of certain browsers be disabled for the Card Number field? (This prevents the browser from automatically entering the customer\'s Card Number).', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Disable Autocomplete for CV2 field (if CV2 being asked for)', 'CEON_MANUAL_CARD_DISABLE_CV2_NUMBER_AUTOCOMPLETE', 'Yes', 'Should the autocomplete functionality of certain browsers be disabled for the CV2 field? (This prevents the browser from automatically entering the customer\'s CV2 Number).', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		
		
		// Card Options configuration settings //////
		$background_colour = '#d0d0d0';
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Card Options</legend><b>Ask for Start Date', 'CEON_MANUAL_CARD_ASK_FOR_START_DATE', 'Yes', 'Should month and year select gadgets be displayed for the customer to select a start date for the card?<br /><br />Please note: When this option is enabled, the start date select gadgets will only be shown if one or more card types which can have start dates (currently Maestro and/or AmEx) are enabled in the Card Type Config section below.', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		
		
		// Card Types Enabled configuration settings //////
		$background_colour = '#eee';
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Card Types Enabled</legend><b>Visa Card Payments', 'CEON_MANUAL_CARD_ACCEPT_VISA', 'Yes', 'Does the store accept Visa Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('MasterCard Card Payments', 'CEON_MANUAL_CARD_ACCEPT_MASTERCARD', 'Yes', 'Does the store accept MasterCard Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Visa Debit Card Payments', 'CEON_MANUAL_CARD_ACCEPT_VISA_DEBIT', 'Yes', 'Does the store accept Visa Debit Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('MasterCard Debit Card Payments', 'CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT', 'Yes', 'Does the store accept MasterCard Debit Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Maestro Card Payments', 'CEON_MANUAL_CARD_ACCEPT_MAESTRO', 'Yes', 'Does the store accept Maestro Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Visa Electron (UKE) Card Payments', 'CEON_MANUAL_CARD_ACCEPT_VISA_ELECTRON', 'Yes', 'Does the store accept Visa Electron Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('American Express Card Payments', 'CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS', 'No', 'Does the store accept American Express Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Diners Club Card Payments', 'CEON_MANUAL_CARD_ACCEPT_DINERS_CLUB', 'No', 'Does the store accept Diners Club Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('JCB Card Payments', 'CEON_MANUAL_CARD_ACCEPT_JCB', 'No', 'Does the store accept JCB Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Laser Card Payments', 'CEON_MANUAL_CARD_ACCEPT_LASER', 'No', 'Does the store accept Laser Card payments? (Please note that Laser cards can only be used when the customer is checking out in Euros).', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Discover Card Payments', 'CEON_MANUAL_CARD_ACCEPT_DISCOVER', 'No', 'Does the store accept Discover Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		
		
		// Display configuration settings //////
		$background_colour = '#d0d0d0';
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Display Options</legend><b>Show icons of Cards Accepted', 'CEON_MANUAL_CARD_SHOW_CARDS_ACCEPTED', 'Yes', 'Should icons be shown for each Credit/Debit Card accepted?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Start/Expiry Month Format', 'CEON_MANUAL_CARD_SELECT_MONTH_FORMAT', '%m - %B', 'A valid strftime format code should be entered here, to be used within the Start and Expiry Date Month Selection gadgets.', '6', '0', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Start/Expiry Year Format', 'CEON_MANUAL_CARD_SELECT_YEAR_FORMAT', '%Y', 'A valid strftime format code should be entered here, to be used within the Start and Expiry Date Year Selection gadgets.', '6', '0', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show &ldquo;Powered By Ceon&rdquo; Graphic in Cards Accepted Sidebox', 'CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_SHOW_POWERED_BY_CEON_GRAPHIC', 'Yes', 'Should the graphic showing that the site uses software by Ceon be shown in the Cards Accepted Sidebox? (This reassuring badge for the store gives customers confidence that the store uses reliable, quality software when handling their sensitive card details).', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order of Display.', 'CEON_MANUAL_CARD_SORT_ORDER', '0', 'The Sort Order of Display determines what order the installed payment modules are displayed in. The module with the lowest Sort Order is displayed first (towards the top). No two payment modules can have the same sort order, unless all are using \'0\'.', '6', '0', now())");
		
		
		// Surcharges/Discounts configuration settings //////
		$this->_addSurchargesDiscountsConfigurationSettings();
		
		
		// Miscellaneous settings //////
		$background_colour = '#d0d0d0';
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Misc. Options</legend><b>Enable Debugging Output', 'CEON_MANUAL_CARD_DEBUGGING_ENABLED', 'No', 'When enabled, this option will cause the Cart to stop after the user has submitted their Card Details. The debug information will be output instead of the Checkout Success or Failure (Payment) page.<br /><br />DON\'T ENABLE UNLESS YOU KNOW WHAT YOU ARE DOING!', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Version Check', 'CEON_MANUAL_CARD_VERSION_CHECK', 'Automatic', 'Use Automatic or Manual Version Checking?', '6', '0', 'zen_cfg_select_option(array(\'Automatic\', \'Manual\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b></fieldset><a href=\"http://ceon.net/software/business/zen-cart\" target=\"_blank\"><img src=\"" . DIR_WS_IMAGES . "ceon-button-logo.png\" alt=\"Made by Ceon. &copy; " . $this->copyright_start_year . '-' . (date('Y') > 2012 ? date('Y') : 2012) . " Ceon\" align=\"right\" style=\"margin: 1em 0.2em; border: none;\" /></a><br />Module &copy; " . $this->copyright_start_year . '-' . (date('Y') > 2012 ? date('Y') : 2012) . " <a href=\"http://ceon.net/software/business/zen-cart\" target=\"_blank\">Ceon</a><p style=\"display: none\">', 'CEON_MANUAL_CARD_MADE_BY_CEON', '" . $this->version . "', '', '6', '0', 'zen_draw_hidden_field(\'made-by-ceon\' . ', now())");
	}
	
	// }}}
	
	
	// {{{ _addSurchargesDiscountsConfigurationSettings()
	
	/**
	 * Adds the configuration settings for the Surcharges/Discounts functionality.
	 *
	 * @access  protected
	 * @return  none
	 */
	function _addSurchargesDiscountsConfigurationSettings()
	{
		global $db;
		
		$languages = zen_get_languages();
		
		$background_colour = '#eee';
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Card Type Surcharges/Discounts</legend><b>Enable Surcharge/Discount Functionality', 'CEON_MANUAL_CARD_ENABLE_SURCHARGES_DISCOUNTS', 'No', 'If enabled, this option will allow a Single Rate or Tables of Rates to be specified for any of the enabled card types, to be used in conjunction with the Ceon Payments/Surcharges Discounts Order Total module.<br /><br />This will apply either a surcharge or discount for a card type, dependant on the currency in use and/or the delivery country and/or the value of the order.<br /><br />The Rates can be either Specific Values (E.g. <code>2.00</code> or <code>-3.50</code>) or Percentages (E.g. <code>4%</code> or <code>-0.5%</code>) or, <strong>for surcharges only</strong>, a Percentage plus a Specific Value (E.g. <code>3.4%+0.20</code>).<br /><br /><em>For example</em>: A Single Rate which applies to all Order Values could be specified as <code>2.5%&rdquo;</code> or <code>1.50</code>.<br /><br />The Tables of Rates are comma-separated lists of Limits/Rate pairs. Each Limits/Rate pair consists of an Order Value Range and a Rate, separated by a colon. <br /><br /><em>For example</em>: <code>1000:2.00, 3000:1.50, *:0</code><br /><br />In the above example, orders with a Total Value less than 1000 would have a surcharge of 2.00, those from 1000 up to 3000 would have a surcharge of 1.50 and orders of 3000 and above would have no surcharge applied).<br /><br />Notes: An asterix (*) is a wildcard which matches any value; Lower Limits for ranges can be specified by preceding the Upper Limit with a dash (E.g. <code>300-500</code>).<br /><br />The Tables of Rates can be applied on a currency and/or country basis. More info can be found in the module\'s documentation. A simple example is:<br /><br /><code>GB|IE[1.95%], US[0-1000:2.45%, *:2.85%], *[2.95%]</code><br /><br />Should Surcharges/Discounts be enabled?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>Visa Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA', '', 'If there are surcharge(s) or discount(s) for Visa Card payments, a Rate or any Table(s) of Rates should be entered here.', '6', '0', now())");
		
		// Language text for Visa Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;2% Surcharge&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;Visa Card Surcharge @ 2%&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>MasterCard Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD', '', 'If there are surcharge(s) or discount(s) for MasterCard Card payments, a Rate or any Table(s) of Rates should be entered here.', '6', '0', now())");
		
		// Language text for MasterCard Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;2% Surcharge&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;MasterCard Card Surcharge @ 2%&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>Visa Debit Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_DEBIT', '', 'If there are surcharge(s) or discount(s) for Visa Debit Card payments, a Rate or any Table(s) of Rates should be entered here.<br /><br />Please Note: Most policies forbid surcharges for debit cards!', '6', '0', now())");
		
		// Language text for Visa Debit Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_DEBIT_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;&pound;0.50 Discount&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_DEBIT_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;Visa Debit Card Discount&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b><hr /><b>MasterCard Debit Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_DEBIT', '', 'If there are surcharge(s) or discount(s) for MasterCard Debit Card payments, a Rate or any Table(s) of Rates should be entered here.', '6', '0', 'zen_cfg_textarea_small(', now())");
		
		// Language text for MasterCard Debit Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_DEBIT_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;2% Surcharge&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', 'zen_cfg_textarea(', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_DEBIT_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;MasterCard Debit Card Surcharge @ 2%&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', 'zen_cfg_textarea(', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>Maestro Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MAESTRO', '', 'If there are surcharge(s) or discount(s) for Maestro Card payments, a Rate or any Table(s) of Rates should be entered here.<br /><br />Please Note: Most policies forbid surcharges for debit cards!', '6', '0', now())");
		
		// Language text for Maestro Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MAESTRO_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;&pound;0.50 Discount&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MAESTRO_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;Maestro Card Discount&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>Visa Electron Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_ELECTRON', '', 'If there are surcharge(s) or discount(s) for Visa Electron Card payments, a Rate or any Table(s) of Rates should be entered here.<br /><br />Please Note: Most policies forbid surcharges for debit cards!', '6', '0', now())");
		
		// Language text for Visa Electron Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_ELECTRON_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;&pound;0.50 Discount&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_ELECTRON_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;Visa Electron Card Discount&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>American Express Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_AMERICAN_EXPRESS', '', 'If there are surcharge(s) or discount(s) for American Express Card payments, a Rate or any Table(s) of Rates should be entered here.', '6', '0', now())");
		
		// Language text for American Express Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_AMERICAN_EXPRESS_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;4% Surcharge&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_AMERICAN_EXPRESS_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;American Express Card Surcharge @ 4%&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>Diners Club Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DINERS_CLUB', '', 'If there are surcharge(s) or discount(s) for Diners Club Card payments, a Rate or any Table(s) of Rates should be entered here.', '6', '0', now())");
		
		// Language text for Diners Club Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DINERS_CLUB_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;2% Surcharge&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DINERS_CLUB_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;Diners Club Card Surcharge @ 2%&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>JCB Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_JCB', '', 'If there are surcharge(s) or discount(s) for JCB Card payments, a Rate or any Table(s) of Rates should be entered here.', '6', '0', now())");
		
		// Language text for JCB Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_JCB_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;2% Surcharge&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_JCB_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;JCB Card Surcharge @ 2%&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>Laser Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_LASER', '', 'If there are surcharge(s) or discount(s) for Laser Card payments, a Rate or any Table(s) of Rates should be entered here.<br /><br />Please Note: Most policies forbid surcharges for debit cards!', '6', '0', now())");
		
		// Language text for Laser Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_LASER_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;&pound;0.50 Discount&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_LASER_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;Laser Card Discount&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('</b><hr /><b>Discover Card Surcharges/Discounts', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DISCOVER', '', 'If there are surcharge(s) or discount(s) for Discover Card payments, a Rate or any Table(s) of Rates should be entered here.', '6', '0', now())");
		
		// Language text for Discover Surcharges/Discounts
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Short Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DISCOVER_SHORT_" . strtoupper($languages[$i]['code']) . "', '', 'Short Descriptive Title to be added after card\'s title in the Card Type selection gadget (E.g. &ldquo;2% Surcharge&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . "&nbsp;Long Surcharges/Discounts Title(s)', 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DISCOVER_LONG_" . strtoupper($languages[$i]['code']) . "', '', 'Longer Descriptive Title for Order Total Summary Line (E.g. &ldquo;Discover Card Card Surcharge @ 2%&rdquo;). Table(s) of Titles use the same format as Table(s) of Rates but with a title instead of a rate.', '6', '0', now())");
		}
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show Message about Surcharges/Discounts', 'CEON_MANUAL_CARD_ENABLE_CUSTOM_SURCHARGES_DISCOUNTS_MESSAGE', 'Yes', 'If using the Surcharges/Discounts functionality, it may prove beneficial to give the customer a bit of information about the store\'s policy.<br /><br />If this option is enabled then the message defined in the Languages Definition file will be displayed immediately above the Card Type selection gadget.<br /><br />Should this message be displayed?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
	}
	
	// }}}
	
	
	// {{{ _upgradeConfiguration()
	
	/**
	 * Adds new configuration settings to the main Zen Cart configuration table, updates any changed configuration
	 * settings and copies across configuration values from any previously installed version.
	 *
	 * @access  protected
	 * @return  boolean   Returns false if there was a problem with the upgrade.
	 */
	function _upgradeConfiguration()
	{
		global $db, $messageStack;
		
		$this->_action_performed = false;
		
		if ($this->_current_config_version == '3.0.0' || substr($this->_current_config_version, 0, 3) == '2.2') {
			// Add new configuration options and update those that have changed
			
			if (substr($this->_current_config_version, 0, 3) == '2.2') {
				// Versions 2.2.0 to 2.2.2 didn't have the surcharge/discounts settings, the setting for Mastercard
				// Debit cards or the setting for the disabling of the display of the start date's fields. Some
				// keys have changed name since then and one's title has also changed
				$this->_convertFromLaterVersion2Config();
			}
			
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show &ldquo;Powered By Ceon&rdquo; Graphic in Cards Accepted Sidebox', 'CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_SHOW_POWERED_BY_CEON_GRAPHIC', 'Yes', 'Should the graphic showing that the site uses software by Ceon be shown in the Cards Accepted Sidebox? (This reassuring badge for the store gives customers confidence that the store uses reliable, quality software when handling their sensitive card details).', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
			
			$background_colour = '#d0d0d0';
			
			$db->Execute("
				UPDATE
					" . TABLE_CONFIGURATION . "
				SET
					configuration_title = '</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Card Options</legend><b>Ask for Start Date',
					configuration_description = 'Should month and year select gadgets be displayed for the customer to select a start date for the card?<br /><br />Please note: When this option is enabled, the start date select gadgets will only be shown if one or more card types which can have start dates (currently Maestro and/or AmEx) are enabled in the Card Type Config section below.',
					last_modified = NOW()
				WHERE
					configuration_key = 'CEON_MANUAL_CARD_ASK_FOR_START_DATE';");
			
			$background_colour = '#eee';
			
			$db->Execute("
				UPDATE
					" . TABLE_CONFIGURATION . "
				SET
					configuration_title = '</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Card Types Enabled</legend><b>Visa Card Payments',
					last_modified = NOW()
				WHERE
					configuration_key = 'CEON_MANUAL_CARD_ACCEPT_VISA';");
			
			$background_colour = '#d0d0d0';
			
			$db->Execute("
				UPDATE
					" . TABLE_CONFIGURATION . "
				SET
					configuration_title = '</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Display Options</legend><b>Show icons of Cards Accepted',
					last_modified = NOW()
				WHERE
					configuration_key = 'CEON_MANUAL_CARD_SHOW_CARDS_ACCEPTED';");
			
			$background_colour = '#eee';
			
			$db->Execute("
				UPDATE
					" . TABLE_CONFIGURATION . "
				SET
					configuration_title = '</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Card Type Surcharges/Discounts</legend><b>Enable Surcharge/Discount Functionality',
					last_modified = NOW()
				WHERE
					configuration_key = 'CEON_MANUAL_CARD_ENABLE_SURCHARGES_DISCOUNTS';");
			
			$background_colour = '#d0d0d0';
			
			$db->Execute("
				UPDATE
					" . TABLE_CONFIGURATION . "
				SET
					configuration_title = '</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Misc. Options</legend><b>Enable Debugging Output',
					last_modified = NOW()
				WHERE
					configuration_key = 'CEON_MANUAL_CARD_DEBUGGING_ENABLED';");
			
			$this->_action_performed = true;
			
		} else if ($this->_current_config_version == '3.0.1' ||
				substr($this->_current_config_version, 0, 1) == '4') {
			// No specific changes to be made
		} else {
			// Version before 2.2.0 is installed. All settings are missing as configuration key namespace has
			// changed
			$this->_addConfigurationSettings();
			
			$this->_copyOldVersionsConfigurationAcross();
			
			$this->_action_performed = true;
		}
		
		if (!defined('CEON_MANUAL_CARD_MODE')) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Module Operation Mode', 'CEON_MANUAL_CARD_MODE', 'Send CV2 Number and Middle Digits via E-mail (Safest option).', 'Should the <strong>CV2 number</strong> and the <strong>middle digits</strong> of the <strong>card number</strong> be sent by e-mail?<br /><br />Or should the CV2 number and the entire card number be stored alongside the order information?<br /><br />Having some of the the details sent by e-mail means not having the full card details in one central place, making it a safer way to collect card details for manual/offline processing.<br /><br />As such, the e-mail option is HIGHLY RECOMMENDED FOR BETTER SECURITY.', '6', '0', 'zen_cfg_select_option(array(\'Send CV2 Number and Middle Digits via E-mail (Safest option).\', \'Store <strong>all</strong> the card details, including the CV2 Number and the full Card Number, alongside the other order information (Increased security risk).\'), ', now())");
			
			$this->_action_performed = true;
		}
		
		if (!defined('CEON_MANUAL_CARD_ADMIN_SHOW_DELETE_BUTTON')) {
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show Delete Button in Order Admin', 'CEON_MANUAL_CARD_ADMIN_SHOW_DELETE_BUTTON', 'Yes', 'Should a button be displayed in the Ceon Manual Card section of the order\'s admin page, allowing the delection of all the card details?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
			
			$this->_action_performed = true;
		}
		
		// All updates are assumed to have succeeded, set the database version in the configuration to the module's
		// version
		$version_being_updated = (($this->_current_config_version != $this->_version) ? true : false);
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_value = '" . $this->version . "',
				configuration_title = '</b></fieldset><a href=\"http://ceon.net/software/business/zen-cart\" target=\"_blank\"><img src=\"" . DIR_WS_IMAGES . "ceon-button-logo.png\" alt=\"Made by Ceon. &copy; " . $this->copyright_start_year . '-' . (date('Y') > 2012 ? date('Y') : 2012) . " Ceon\" align=\"right\" style=\"margin: 1em 0.2em; border: none;\" /></a><br />Module &copy; " . $this->copyright_start_year . '-' . (date('Y') > 2012 ? date('Y') : 2012) . " <a href=\"http://ceon.net/software/business/zen-cart\" target=\"_blank\">Ceon</a><p style=\"display: none\">'
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_MADE_BY_CEON';");
		
		// Reset the version check status as it may have changed!
		if (isset($_SESSION[$this->_ceon_base_model_code . '_vc_response'])) {
			unset($_SESSION[$this->_ceon_base_model_code . '_vc_response']);
		}
		
		if (!$version_being_updated && !$this->_action_performed) {
			// No need to notify user as nothing done
			return true;
		}
		
		if ($version_being_updated) {
			// Version was updated so let user know that they should reload the module
			$this->_action_performed = true;
		}
		
		$messageStack->add_session(sprintf(
			'Ceon Manual Card\'s configuration updated from version %s to %s',
			$this->_current_config_version . (!is_null($this->_current_config_edition_code) ? ' ' .
			$this->_getEditionTitle($this->_current_config_edition_code) : ''),
			$this->_version . (!is_null($this->_ceon_model_edition_code) ? ' ' .
			$this->_getEditionTitle($this->_ceon_model_edition_code) : '')), 'success');
		
		return true;
	}
	
	// }}}
	
	
	// {{{ _copyOldVersionsConfigurationAcross()
	
	/**
	 * Copies any old version's configuration across.
	 *
	 * @access  protected
	 * @return  none
	 */
	function _copyOldVersionsConfigurationAcross()
	{
		global $db;
		
		$old_version_base_key_part = 'MODULE_PAYMENT_CEON_MANUAL_CARD';
		
		$old_version_config_result = $db->Execute("
			SELECT
				configuration_key,
				configuration_value
			FROM
				" . TABLE_CONFIGURATION . "
			WHERE
				configuration_key LIKE '" . $old_version_base_key_part . "%';");
		
		$accept_mastercard = '';
		
		while (!$old_version_config_result->EOF) {
			$old_version_config_key_main_part = str_replace($old_version_base_key_part, '',
				$old_version_config_result->fields['configuration_key']);
			
			// Handle keys which have changed or are no longer used
				
			if ($old_version_config_key_main_part == '_STORE_DETAILS_IN_SESSION') {
				$config_key = 'CEON_MANUAL_CARD' . '_STORE_SENSITIVE_DETAILS_IN_SESSION';
			} else if ($old_version_config_key_main_part == '_ENCRYPTION_KEYPHRASE') {
				$config_key = 'CEON_MANUAL_CARD' . '_BLOWFISH_ENCRYPTION_KEYPHRASE';
			} else if ($old_version_config_key_main_part == '_ALLOW_NO_CVV') {
				$config_key = 'CEON_MANUAL_CARD' . '_ALLOW_NO_CV2_NUMBER';
			} else if ($old_version_config_key_main_part == '_DISABLE_CVV_AUTOCOMPLETE') {
				$config_key = 'CEON_MANUAL_CARD' . '_DISABLE_CV2_NUMBER_AUTOCOMPLETE';
			} else if ($old_version_config_key_main_part == '_ACCEPT_MC') {
				// Need to store MasterCard's value so it can be applied to new MasterCard Debit card configuration
				// value as well
				$config_key = 'CEON_MANUAL_CARD' . '_ACCEPT_MASTERCARD';
				
				$accept_mastercard = $old_version_config_result->fields['configuration_value'];
				
			} else if ($old_version_config_key_main_part == '_ACCEPT_DELTA') {
				$config_key = 'CEON_MANUAL_CARD' . '_ACCEPT_VISA_DEBIT';
			} else if ($old_version_config_key_main_part == '_ACCEPT_SWITCH_MAESTRO') {
				$config_key = 'CEON_MANUAL_CARD' . '_ACCEPT_MAESTRO';
			} else if ($old_version_config_key_main_part == '_ACCEPT_UKE') {
				$config_key = 'CEON_MANUAL_CARD' . '_ACCEPT_VISA_ELECTRON';
			} else if ($old_version_config_key_main_part == '_ACCEPT_AMEX') {
				$config_key = 'CEON_MANUAL_CARD' . '_ACCEPT_AMERICAN_EXPRESS';
			} else if ($old_version_config_key_main_part == '_ACCEPT_DC') {
				$config_key = 'CEON_MANUAL_CARD' . '_ACCEPT_DINERS_CLUB';
			} else if ($old_version_config_key_main_part == '_ACCEPT_SOLO') {
				$old_version_config_result->MoveNext();
				
				continue;
				
			} else if ($old_version_config_key_main_part == '_USE_CVV') {
				$old_version_config_result->MoveNext();
				
				continue;
				
			} else if ($old_version_config_key_main_part == '_MADE_BY_CEON') {
				// Handle version number specifically - it will be updated later
				$old_version_config_result->MoveNext();
				
				continue;
				
			} else {
				$config_key = 'CEON_MANUAL_CARD' . $old_version_config_key_main_part;
			}
			
			$old_version_config_value = $old_version_config_result->fields['configuration_value'];
			
			// Version 1.0.0 used True/False whereas all following versions used Yes/No
			switch ($old_version_config_value) {
				case 'True':
					$old_version_config_value = 'Yes';
					break;
				case 'False':
					$old_version_config_value = 'No';
					break;
			}
			
			$db->Execute("
				UPDATE
					" . TABLE_CONFIGURATION . "
				SET
					configuration_value = '" . zen_db_input($old_version_config_value) . "',
					last_modified = NOW()
				WHERE
					configuration_key = '" . $config_key . "';");
			
			$old_version_config_result->MoveNext();
		}
		
		// Handle new MasterCard Debit configuration option
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_value = '" . zen_db_input($accept_mastercard) . "',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT';");
		
		// Remove outdated configuration options
		$db->Execute("
			DELETE FROM
				" . TABLE_CONFIGURATION . "
			WHERE
				configuration_key LIKE '" . $old_version_base_key_part . "%';");
	}
	
	// }}}
	
	
	// {{{ _convertFromLaterVersion2Config()
	
	/**
	 * Adds in the new surcharge/discounts settings, the new setting for Mastercard Debit cards, the new setting
	 * for the disabling of the display of the start date's fields and the new setting for the version check
	 * functionality. Also renames keys that have changed name between versions 2.2.0 - 2.2.2 and the current
	 * version, updates the title of a key to add the admin styling code (for IE really as it lays things out
	 * poorly) and removes outdated settings.
	 *
	 * @access  protected
	 * @return  none
	 */
	function _convertFromLaterVersion2Config()
	{
		global $db;
		
		// Add the new Mastercard Debit configuration setting
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('MasterCard Debit Card Payments', 'CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT', 'Yes', 'Does the store accept MasterCard Debit Card payments?', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		
		// Add the configuration settings for the individual cards' surcharges/discounts
		$this->_addSurchargesDiscountsConfigurationSettings();
		
		// Add the configuration setting for the disabling of the display of the start date's fields
		$background_colour = '#d0d0d0';
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</b></fieldset><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">Card Options</legend><b>Ask for Start Date', 'CEON_MANUAL_CARD_ASK_FOR_START_DATE', 'Yes', 'Should month and year select gadgets be displayed for the customer to select a start date for the card?<br /><br />Please note: When this option is enabled, the start date select gadgets will only be shown if one or more card types which can have start dates (currently Maestro and/or AmEx) are enabled in the Card Type Config section below.', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
		
		// Add the version check setting
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Version Check', 'CEON_MANUAL_CARD_VERSION_CHECK', 'Automatic', 'Use Automatic or Manual Version Checking?', '6', '0', 'zen_cfg_select_option(array(\'Automatic\', \'Manual\'), ', now())");
		
		// Update keys which have changed name or have had their titles and/or descriptions modified (e.g. to
		// remove old section dividers for the card types)
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_description = 'Should the customer\'s sensitive card details - the Card Number and the Card CV2 Number - be stored temporarily in the session? (They\'ll be cleared from the session when the order is completed).<br /><br />As standard, if a customer leaves the payment details page to go back to the shipping page or the shopping cart, or if they make a mistake when entering their card details, the module will restore most of the details entered, so the customer doesn\'t have to re-enter them when they come back to the payment page.<br /><br />When this option is enabled, the Card Number and the Card CV2 Number will also be stored temporarily, encrypted in the session using the Blowfish algorithm.<br /><br />If this option is disabled, neither the Card Number nor the Card CV2 Number are stored in the session. Customers will have to re-enter their Card Number and their Card CV2 Number in full any time they come back to the payment page (i.e. if they don\'t go straight from the payment page to order completion).',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_STORE_SENSITIVE_DETAILS_IN_SESSION';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_key = 'CEON_MANUAL_CARD_BLOWFISH_ENCRYPTION_KEYPHRASE',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ENCRYPTION_KEYPHRASE';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'Ask for CV2 Number',
				configuration_key = 'CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER',
				configuration_description = 'Should a field be displayed for the customer to enter a card CV2 number?',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ASK_FOR_CVV_NUMBER';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'Allow cards with no CV2 Number',
				configuration_key = 'CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER',
				configuration_description = 'A small minority of cards have no CV2 number. If a customer has filled in all card details except for the CV2 number should they be given the option to indicate that their card has no CV2 number? (This is necessary for Laser cards as many of them tend not to have CV2 numbers. American Express Corporate Purchasing Cards don\'t have them either.)',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ALLOW_NO_CVV_NUMBER';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'MasterCard Card Payments',
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_MASTERCARD',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_MC';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'Visa Debit Card Payments',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_VISA_DEBIT';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'Maestro Card Payments',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_MAESTRO';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'Visa Electron (UKE) Payments',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_VISA_ELECTRON';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'American Express Card Payments',
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_AMEX';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'Diners Club Card Payments',
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_DINERS_CLUB',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_DC';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'JCB Card Payments',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_JCB';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'Laser Card Payments',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_LASER';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'Discover Card Payments',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_DISCOVER';");
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = 'Disable Autocomplete for CV2 field (if CV2 being asked for)',
				configuration_key = 'CEON_MANUAL_CARD_DISABLE_CV2_NUMBER_AUTOCOMPLETE',
				configuration_description = 'Should the autocomplete functionality of certain browsers be disabled for the CV2 field? (This prevents the browser from automatically entering the customer\'s CV2 Number).',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_DISABLE_CVV_AUTOCOMPLETE';");
		
		// Set the value of the MasterCard Debit card setting to the same as the MasterCard setting
		$accept_mastercard_result = $db->Execute("
			SELECT
				configuration_value
			FROM
				" . TABLE_CONFIGURATION . "
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_MASTERCARD';");
		
		$accept_mastercard = $accept_mastercard_result->fields['configuration_value'];
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_value = '" . zen_db_input($accept_mastercard) . "',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT';");
		
		// Add styling to configuration table (for IE really as it lays things out poorly)
		$background_colour = '#d0d0d0';
		
		$db->Execute("
			UPDATE
				" . TABLE_CONFIGURATION . "
			SET
				configuration_title = '</b><style type=\"text/css\">fieldset { padding: 0.6em; } fieldset p { margin: 0 0 0.8em 0; } td.infoBoxContent { padding-left: 0.6em; padding-right: 0.6em; }</style><fieldset style=\"background: " . $background_colour . "; margin-bottom: 1.5em;\"><legend style=\"font-size: 1.4em; font-weight: bold\">General Config</legend><b>Enable Ceon Manual Card Module',
				last_modified = NOW()
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_STATUS';");
		
		// Remove outdated configuration options
		$db->Execute("
			DELETE FROM
				" . TABLE_CONFIGURATION . "
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_USE_BLOWFISH';
			");
		
		$db->Execute("
			DELETE FROM
				" . TABLE_CONFIGURATION . "
			WHERE
				configuration_key = 'CEON_MANUAL_CARD_ACCEPT_SOLO';
			");
	}
	
	// }}}
	
	
	// {{{ _getEditionTitle()
	
	/**
	 * Returns the title for edition of the software for the specified code.
	 * 
	 * @access  protected
	 * @param   string    $edition_code   The code for the edition.
	 * @return  string    The localised text for the edition of the software.
	 */
	function _getEditionTitle($edition_code)
	{
		return '';
	}
	
	// }}}
	
	
	// {{{ check()
	
	/**
	 * Simply looks up whether or not the module is installed.
	 *
	 * @access  public
	 * @return  boolean   True if the module is installed, false otherwise.
	 */
	function check()
	{
		global $db;
		
		if (!isset($this->_check)) {
			$check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION .
				" where configuration_key = 'CEON_MANUAL_CARD_STATUS'");
			
			$this->_check = $check_query->RecordCount();
		}
		
		return $this->_check;
	}
	
	// }}}
	
	
	// {{{ remove()
	
	/**
	 * Simply removes all this module's configuration settings from Zen Cart's configuration database table.
	 *
	 * @access  public
	 * @return  none
	 */
	function remove()
	{
		global $db;
		
		$db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" .
			implode("', '", $this->keys()) . "')");
	}
	
	// }}}
	
	
	// {{{ keys()
	
	/**
	 * Returns the keys for this module's configuration settings, ordered acording to how they are to be displayed
	 * in the module's admin configuration panel.
	 *
	 * @access  public
	 * @return  array     An array of the configuration settings' keys.
	 */
	function keys()
	{
		$languages = zen_get_languages();
		
		$keys = array(
			'CEON_MANUAL_CARD_STATUS',
			'CEON_MANUAL_CARD_MODE',
			'CEON_MANUAL_CARD_EMAIL',
			'CEON_MANUAL_CARD_ADMIN_SHOW_DELETE_BUTTON',
			'CEON_MANUAL_CARD_ZONE',
			'CEON_MANUAL_CARD_ORDER_STATUS_ID',
			'CEON_MANUAL_CARD_STORE_SENSITIVE_DETAILS_IN_SESSION',
			'CEON_MANUAL_CARD_BLOWFISH_ENCRYPTION_KEYPHRASE',
			'CEON_MANUAL_CARD_ASK_FOR_CV2_NUMBER',
			'CEON_MANUAL_CARD_ALLOW_NO_CV2_NUMBER',
			'CEON_MANUAL_CARD_DISABLE_CARD_NUMBER_AUTOCOMPLETE',
			'CEON_MANUAL_CARD_DISABLE_CV2_NUMBER_AUTOCOMPLETE',
			'CEON_MANUAL_CARD_ASK_FOR_START_DATE',
			'CEON_MANUAL_CARD_ACCEPT_VISA',
			'CEON_MANUAL_CARD_ACCEPT_MASTERCARD',
			'CEON_MANUAL_CARD_ACCEPT_VISA_DEBIT',
			'CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT',
			'CEON_MANUAL_CARD_ACCEPT_MAESTRO',
			'CEON_MANUAL_CARD_ACCEPT_VISA_ELECTRON',
			'CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS',
			'CEON_MANUAL_CARD_ACCEPT_DINERS_CLUB',
			'CEON_MANUAL_CARD_ACCEPT_JCB',
			'CEON_MANUAL_CARD_ACCEPT_LASER',
			'CEON_MANUAL_CARD_ACCEPT_DISCOVER',
			'CEON_MANUAL_CARD_SHOW_CARDS_ACCEPTED',
			'CEON_MANUAL_CARD_SELECT_MONTH_FORMAT',
			'CEON_MANUAL_CARD_SELECT_YEAR_FORMAT',
			'CEON_MANUAL_CARD_ENABLE_CUSTOM_SURCHARGES_DISCOUNTS_MESSAGE',
			'CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_SHOW_POWERED_BY_CEON_GRAPHIC',
			'CEON_MANUAL_CARD_SORT_ORDER',
			'CEON_MANUAL_CARD_ENABLE_SURCHARGES_DISCOUNTS'
			);
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_SHORT_' . strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_LONG_' . strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_SHORT_' .
				strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_LONG_' .
				strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_DEBIT';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_DEBIT_SHORT_' .
				strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_DEBIT_LONG_' .
				strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_DEBIT';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_DEBIT_SHORT_' .
				strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MASTERCARD_DEBIT_LONG_' .
				strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MAESTRO';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MAESTRO_SHORT_' . strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_MAESTRO_LONG_' . strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_ELECTRON';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_ELECTRON_SHORT_' .
				strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_VISA_ELECTRON_LONG_' .
				strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_AMERICAN_EXPRESS';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_AMERICAN_EXPRESS_SHORT_' .
				strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_AMERICAN_EXPRESS_LONG_' .
				strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DINERS_CLUB';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DINERS_CLUB_SHORT_' .
				strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DINERS_CLUB_LONG_' .
				strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_JCB';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_JCB_SHORT_' . strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_JCB_LONG_' . strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_LASER';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_LASER_SHORT_' . strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_LASER_LONG_' . strtoupper($languages[$i]['code']);
		}
		
		$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DISCOVER';
		
		for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DISCOVER_SHORT_' . strtoupper($languages[$i]['code']);
			$keys[] = 'CEON_MANUAL_CARD_SURCHARGES_DISCOUNTS_DISCOVER_LONG_' . strtoupper($languages[$i]['code']);
		}
		
		$remaining_keys = array(
			'CEON_MANUAL_CARD_DEBUGGING_ENABLED',
			'CEON_MANUAL_CARD_VERSION_CHECK',
			'CEON_MANUAL_CARD_MADE_BY_CEON'
			);
		
		$keys = array_merge($keys, $remaining_keys);
		
		return $keys;
	}
	
	// }}}
}

// }}}
