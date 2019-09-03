<?php

/**
 * Ceon Manual Card Language Definitions.
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

// HTML is allowed in the following message!
define('CEON_MANUAL_CARD_CUSTOM_SURCHARGES_DISCOUNTS_MESSAGE', '');


/**
 * Default (fallback) definitions for information about card surcharges/discounts. The "SHORT" version is added
 * after the card's title in the Card Type selection gadget. The "LONG" version is used as the title for the Order
 * Total Summary Line in the Ceon Payment Surcharges Discounts Order Total module.
 *
 * These are only used if no text was specified for a Card Type which is making use of the surcharge/discount
 * functionality.
 */
define('CEON_MANUAL_CARD_TEXT_SURCHARGE_SHORT', 'Surcharge');
define('CEON_MANUAL_CARD_TEXT_SURCHARGE_LONG', 'Card Surcharge');
define('CEON_MANUAL_CARD_TEXT_DISCOUNT_SHORT', 'Discount');
define('CEON_MANUAL_CARD_TEXT_DISCOUNT_LONG', 'Card Discount');


// The remaining definitions should rarely require changing but feel free if you like! //////

/**
 * Payment option title as displayed to the customer.
 */
define('CEON_MANUAL_CARD_TEXT_CATALOG_TITLE', 'Credit / Debit Card');

/**
 * The labels for the card fields.
 */
define('CEON_MANUAL_CARD_TEXT_CARDS_ACCEPTED', '<br><br>Cards Accepted:');
define('CEON_MANUAL_CARD_TEXT_CARD_TYPE', 'Card Type:&nbsp;');
define('CEON_MANUAL_CARD_TEXT_CARD_HOLDER', 'Card Holder Name: &nbsp;');
define('CEON_MANUAL_CARD_TEXT_CARD_NUMBER', 'Card Number:&nbsp;');
define('CEON_MANUAL_CARD_TEXT_CARD_EXPIRY_DATE', 'Card Expire Date:&nbsp;');
define('CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER', '<br><br>Card CV2 Number: ');
define('CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER_WITH_POPUP_LINK', 'CV2 Number (<a href="javascript:popupWindow(\'' . zen_href_link(FILENAME_POPUP_CVV_HELP) . '\')">' . 'More Info' . '</a>):&nbsp;');
define('CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER_TICK_NOT_PRESENT', 'Click here if card has no CV2 number.');
define('CEON_MANUAL_CARD_TEXT_CARD_CV2_NUMBER_NOT_PRESENT', 'Not present');
define('CEON_MANUAL_CARD_TEXT_CARD_START_DATE_IF_ON_CARD', 'Card Start Date (If on card):');
define('CEON_MANUAL_CARD_TEXT_CARD_START_DATE', 'Card Start Date:');
define('CEON_MANUAL_CARD_TEXT_CARD_ISSUE_NUMBER_IF_ON_CARD', 'Card Issue No. (If on card):');
define('CEON_MANUAL_CARD_TEXT_CARD_ISSUE_NUMBER', 'Card Issue No.:');

define('CEON_MANUAL_CARD_TEXT_SELECT_CARD_TYPE', 'Select Card Type');

define('CEON_MANUAL_CARD_TEXT_SELECT_MONTH', 'Month');
define('CEON_MANUAL_CARD_TEXT_SELECT_YEAR', 'Year');

define('CEON_MANUAL_CARD_TEXT_VISA', 'Visa');
define('CEON_MANUAL_CARD_TEXT_MASTERCARD', 'MasterCard');
define('CEON_MANUAL_CARD_TEXT_VISA_DEBIT', 'Visa Debit');
define('CEON_MANUAL_CARD_TEXT_MASTERCARD_DEBIT', 'MasterCard Debit');
define('CEON_MANUAL_CARD_TEXT_MAESTRO', 'Maestro');
define('CEON_MANUAL_CARD_TEXT_VISA_ELECTRON', 'Visa Electron (UKE)');
define('CEON_MANUAL_CARD_TEXT_AMERICAN_EXPRESS', 'American Express');
define('CEON_MANUAL_CARD_TEXT_DINERS_CLUB', 'Diners Club');
define('CEON_MANUAL_CARD_TEXT_JCB', 'JCB');
define('CEON_MANUAL_CARD_TEXT_LASER', 'Laser');
define('CEON_MANUAL_CARD_TEXT_DISCOVER', 'Discover');

/**
 * Definitions used when generating the e-mail.
 */
define('CEON_MANUAL_CARD_TEXT_EMAIL_SUBJECT', 'Extra Card Information for Order #');
define('CEON_MANUAL_CARD_TEXT_EMAIL' , "Here are the middle digits of the card number for order #%s:\n\nMiddle Digits: %s\n\nAnd here is the CV2 number:\n\nCV2 Number: %s\n\nYOU MUST NOT STORE THE CV2 NUMBER... DELETE THIS E-MAIL ONCE YOU'VE CHARGED THE CARD!\n\n");
define('CEON_MANUAL_CARD_TEXT_EMAIL_CV2_NUMBER_NOT_PRESENT' , "Here are the middle digits of the card number for order #%s:\n\nMiddle Digits: %s\n\nThe customer indicated that their card has no CV2 number.\n\nYOU SHOULD NOT STORE THE CARD NUMBER... DELETE THIS E-MAIL ONCE YOU'VE CHARGED THE CARD!\n\n");
define('CEON_MANUAL_CARD_TEXT_EMAIL_CV2_NUMBER_NOT_REQUESTED' , "Here are the middle digits of the card number for order #%s:\n\nMiddle Digits: %s\n\n...YOU SHOULD NOT STORE THE CARD NUMBER... DELETE THIS E-MAIL ONCE YOU'VE CHARGED THE CARD!\n\n");

/**
 * Default definitions for the error messages to be displayed when a card details form field's value is missing or
 * wrong. HTML can be used.
 */
define('CEON_MANUAL_CARD_ERROR_CARD_HOLDER_REQUIRED', '<span class="ErrorInfo">You must enter the card holder\'s name.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_HOLDER_MIN_LENGTH', '<span class="ErrorInfo">The card holder\'s name is too short.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_TYPE', '<span class="ErrorInfo">You must select the type of credit/debit card being used.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_NUMBER_REQUIRED', '<span class="ErrorInfo">You must enter the card number.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_NUMBER_INVALID', '<span class="ErrorInfo">The card number entered is invalid. Please check the number and try again, try another card or <a href="' . zen_href_link(FILENAME_CONTACT_US, '', 'SSL') . '">contact us</a> for further assistance.</span>');
define('CEON_MANUAL_CARD_ERROR_MASTERCARD_CREDIT_NOT_ACCEPTED', '<span class="ErrorInfo">You have entered a MasterCard Credit Card number but we don\'t accept MasterCard Credit cards.</span>');
define('CEON_MANUAL_CARD_ERROR_MASTERCARD_DEBIT_NOT_ACCEPTED', '<span class="ErrorInfo">You have entered a MasterCard Debit Card number but we don\'t accept MasterCard Debit cards.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_IS_MASTERCARD_DEBIT_NOT_CREDIT', '<span class="ErrorInfo">You have selected MasterCard Credit Card but entered the number of a MasterCard Debit Card.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_IS_MASTERCARD_CREDIT_NOT_DEBIT', '<span class="ErrorInfo">You have selected MasterCard Debit Card but entered the number of a MasterCard Credit Card.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_EXPIRY_DATE_INVALID', '<span class="ErrorInfo">The expiry date selected is invalid.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_REQUIRED_NOT_AMERICAN_EXPRESS', '<span class="ErrorInfo">You must enter the 3 digit CV2 number from the back of the card.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_MISSING_INDICATE_NOT_AMERICAN_EXPRESS', '<span class="ErrorInfo">A CV2 number has not been entered. Please enter the 3 digit CV2 number from the back of the card, or indicate if the card has no CV2 number.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_INVALID_NOT_AMERICAN_EXPRESS', '<span class="ErrorInfo">The CV2 number entered is invalid. Please enter the 3 digit CV2 number from the back of the card.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_REQUIRED_AMERICAN_EXPRESS', '<span class="ErrorInfo">You must enter the CV2 number. On American Express cards this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_MISSING_INDICATE_AMERICAN_EXPRESS', '<span class="ErrorInfo">A CV2 number has not been entered. <br />On American Express cards this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number. <br />Please enter the CV2 number, or indicate if the card has no CV2 number.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_INVALID_AMERICAN_EXPRESS', '<span class="ErrorInfo">The CV2 number entered is invalid. On American Express cards this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_REQUIRED_POSS_AMERICAN_EXPRESS', '<span class="ErrorInfo">You must enter the CV2 number for the card. <br/>On most cards this is a 3 digit number printed on the back of the card. <br />On American Express cards this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_MISSING_INDICATE_POSS_AMERICAN_EXPRESS', '<span class="ErrorInfo">A CV2 number has not been entered. <br />On most cards this is a 3 digit number printed on the back of the card. <br />On American Express cards this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number. <br />Please enter the CV2 number, or indicate if the card has no CV2 number.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_CV2_NUMBER_INVALID_POSS_AMERICAN_EXPRESS', '<span class="ErrorInfo">The CV2 number entered is invalid. <br />On most cards this is a 3 digit number printed on the back of the card. <br />On American Express cards, this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number.</span>');
define('CEON_MANUAL_CARD_ERROR_CARD_START_DATE_INVALID', '<span class="ErrorInfo">The start date selected is invalid. If the card doesn\'t have a start date, please change the selection to Month - Year.</span>');

/**
 * Default definitions for the error messages to be displayed using JavaScript when a card details form field's
 * value is missing or wrong.
 */
define('CEON_MANUAL_CARD_ERROR_JS_CARD_HOLDER_MIN_LENGTH', '* The card holder\'s name must be at least ' . (is_numeric(CC_OWNER_MIN_LENGTH) ? CC_OWNER_MIN_LENGTH : 2) . ' characters.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_TYPE', '* You must select the type of credit/debit card being used.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_NUMBER_MIN_LENGTH', '* The card number must be at least ' . (is_numeric(CC_NUMBER_MIN_LENGTH) ?  CC_NUMBER_MIN_LENGTH : 16) . ' digits in length.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_EXPIRY_DATE_INVALID', '* The expiry date selected is invalid.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_NOT_AMERICAN_EXPRESS', '* A valid CV2 number has not been entered.\n--> Please enter the 3 digit CV2 number from the back of the card.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_INDICATE_NOT_AMERICAN_EXPRESS', '* A valid CV2 number has not been entered.\n--> Please enter the 3 digit CV2 number from the back of the card, or indicate if the card has no CV2 number.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_AMERICAN_EXPRESS', '* A valid CV2 number has not been entered.\n--> On American Express cards this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number.\n--> Please enter the 4 digit CV2 number.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_INDICATE_AMERICAN_EXPRESS', '* A valid CV2 number has not been entered.\n--> On American Express cards this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number.\n--> Please enter the CV2 number, or indicate if the card has no CV2 number.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_POSS_AMERICAN_EXPRESS', '* A valid CV2 number has not been entered.\n--> On most cards this is a 3 digit number printed on the back of the card.\n--> On American Express cards this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number.\n--> Please enter the CV2 number.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_CV2_NUMBER_INVALID_INDICATE_POSS_AMERICAN_EXPRESS', '* A valid CV2 number has not been entered.\n--> On most cards this is a 3 digit number printed on the back of the card.\n--> On American Express cards this is a 4 digit number (unique card code), displayed on the front of the card, separately from the main card number.\n--> Please enter the CV2 number, or indicate if the card has no CV2 number.\n');
define('CEON_MANUAL_CARD_ERROR_JS_CARD_START_DATE_INVALID', '* The start date selected is invalid.\n--> If the card doesn\'t have a start date, please change the selection to \"Month\" - \"Year\".\n');

/**
 * Admin text definitions.
 */
define('CEON_MANUAL_CARD_TEXT_ADMIN_TITLE', 'Ceon Manual Card v%s');
define('CEON_MANUAL_CARD_TEXT_DESCRIPTION', '<fieldset style="background: #f7f6f0; margin-bottom: 1.5em"><legend style="font-size: 1.2em; font-weight: bold">Test Card Details</legend>A valid Credit/Debit Card Number must be used (e.g. 4111111111111111).<br /><br />Any future date can be used for the Expiry Date and any 3 or 4 (AMEX) digit number can be used for the CV2 Number.<br /><br />Maestro can optionally use a Start Date and/or Issue Number.<br /><br />American Express cards normally have and require a Start Date (although this module does not enforce its selection).');
define('CEON_MANUAL_CARD_TEXT_NOT_INSTALLED', '<a href="http://ceon.net/software/business/zen-cart" target="_blank"><img src="' . DIR_WS_IMAGES . 'ceon-button-logo.png" alt="Made by Ceon. &copy; 2006-' . (date('Y') > 2012 ? date('Y') : 2012) . ' Ceon" align="right" style="margin: 1em 0.2em; border: none;" /></a><br />Module &copy; 2006-' . (date('Y') > 2012 ? date('Y') : 2012) . ' <a href="http://ceon.net/software/business/zen-cart" target="_blank">Ceon</a>');

define('CEON_MANUAL_CARD_ADMIN_TEXT_TITLE', 'Ceon Manual Card');

define('CEON_MANUAL_CARD_ADMIN_TEXT_DELETE_DETAILS', 'Delete Card Details');
define('CEON_MANUAL_CARD_ADMIN_TEXT_CONFIRM_DELETE_DETAILS', 'Are you sure you want to permanently delete the card details?');
define('CEON_MANUAL_CARD_ADMIN_TEXT_DETAILS_DELETED_CONFIRMED_NOTICE', 'The card details were deleted at {ceon:time} on {ceon:date} by {ceon:admin-user-name}.');
define('CEON_MANUAL_CARD_ADMIN_TEXT_DETAILS_DELETION_JUST_TOOK_PLACE', 'Some details may still be displayed above as any details output above were output before the deletion took place. Rest assured though that the details have been deleted.');

define('CEON_MANUAL_CARD_ADMIN_TEXT_DETAILS_DELETED_NOTICE', 'The card details were deleted at {ceon:time} on {ceon:date} by {ceon:admin-user-name}.');
define('CEON_MANUAL_CARD_ADMIN_TEXT_EMAIL_NOTICE', 'The middle digits of the above card number and any CV2 number for the card have been e-mailed to the address specified in the module configuration.');
define('CEON_MANUAL_CARD_ADMIN_TEXT_NO_CV2_NUMBER', 'No CV2 number was entered.').
define('CEON_MANUAL_CARD_ADMIN_TEXT_NO_START_DATE_OR_ISSUE_NUMBER', 'No start date was selected and no issue number was entered.').
define('CEON_MANUAL_CARD_ADMIN_TEXT_NO_START_DATE', 'No start date was selected.').
define('CEON_MANUAL_CARD_ADMIN_TEXT_NO_ISSUE_NUMBER', 'No issue number was entered.').
define('CEON_MANUAL_CARD_ADMIN_TEXT_CARD_NUMBER', 'Card Number:');
define('CEON_MANUAL_CARD_ADMIN_TEXT_CV2_NUMBER', 'Card CV2 Number:');
define('CEON_MANUAL_CARD_ADMIN_TEXT_START_DATE', 'Card Start Date:');
define('CEON_MANUAL_CARD_ADMIN_TEXT_ISSUE_NUMBER', 'Card Issue Number:');
