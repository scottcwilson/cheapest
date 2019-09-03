<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2018 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Scott C Wilson Mon Apr 23 08:06:02 2018 -0400 Modified in v1.5.6 $
 */

define ('EMAIL_LOGO_FILENAME', 'header.jpg');  //-File is present in /email folder
define ('EMAIL_LOGO_WIDTH', '550');
define ('EMAIL_LOGO_HEIGHT', '110');
define ('EMAIL_LOGO_ALT_TITLE_TEXT', 'Zen Cart! The Art of E-commerce');

// -----
// If you want to include some extra information in each email's header information (like perhaps the store address and/or phone number),
// set this value to contain the full HTML content to be copied, e.g. '<div id="extra-stuff">Extra stuff for header</div>'.
//
define ('EMAIL_EXTRA_HEADER_INFO', '');

// office use only
define('OFFICE_FROM','<strong>From:</strong>');
define('OFFICE_EMAIL','<strong>Email:</strong>');

define('OFFICE_SENT_TO','<strong>Sent To:</strong>');
define('OFFICE_EMAIL_TO','<strong>To Email:</strong>');

define('OFFICE_USE','<strong>Office Use Only:</strong>');
define('OFFICE_LOGIN_NAME','<strong>Login Name:</strong>');
define('OFFICE_LOGIN_EMAIL','<strong>Login Email:</strong>');
define('OFFICE_LOGIN_PHONE','<strong>Telephone:</strong>');
define('OFFICE_LOGIN_FAX','<strong>Fax:</strong>');
define('OFFICE_IP_ADDRESS','<strong>IP Address:</strong>');
define('OFFICE_HOST_ADDRESS','<strong>Host Address:</strong>');
define('OFFICE_DATE_TIME','<strong>Date and Time:</strong>');
if (!defined('OFFICE_IP_TO_HOST_ADDRESS')) define('OFFICE_IP_TO_HOST_ADDRESS', 'OFF');

define('EMAIL_TEXT_TELEPHONE', 'Telephone: ');

// email disclaimer
define('EMAIL_DISCLAIMER', 'Thank you for choosing Cheapest Dinar / Banknote Pros as your preferred source for collectible world banknotes and banknote collecting supplies.&nbsp; Buy with confidence knowing that each purchase is protected by our IRON CLAD  - "Guaranteed Authentic, Or We Will Buy It Back" Guarantee! ');
define('EMAIL_SPAM_DISCLAIMER','This email is sent in accordance with U.S. CAN-SPAM Law, in effect since 01/01/2004.&nbsp; We honor all requests to be removed from our email mailing list.&nbsp; However, by making a purchase through our website, you automatically consent to receive email communication specifically related to and regarding your purchase.&nbsp; If you wish to stop receiving all other email notifications and newsletters, send your removal request to:&nbsp;&nbsp; <a href="mailto:Support@cheapstdinar.com?subject=Unsubscribe Request">Removal Requests</a>&nbsp; Please allow up to 72 hours to be removed.');

// Define a message you'd like to add to an order confirmation email
define('EMAIL_ORDER_MESSAGE',''); 
define('EMAIL_FOOTER_COPYRIGHT','Copyright (c) ' . date('Y') . ' <a href="http://www.cheapestdinar.com" target="_blank">Cheapest Dinar / Guardian Services</a>.&nbsp;  Powered by <a href="http://www.cheapestdinar.com" target="_blank">Banknote Pros</a>');

define('TEXT_UNSUBSCRIBE', "\n\nTo unsubscribe from future newsletter and promotional mailings, simply click on the following link: \n");

// email advisory for all emails customer generate - tell-a-friend and GV send
define('EMAIL_ADVISORY', '-----' . "\n" . '<strong>IMPORTANT:</strong> For your protection and to prevent malicious use, all emails sent via this web site are logged and the contents recorded and available to the store owner. If you feel that you have received this email in error, please send an email to ' . STORE_OWNER_EMAIL_ADDRESS . "\n\n");

// email advisory included warning for all emails customer generate - tell-a-friend and GV send
define('EMAIL_ADVISORY_INCLUDED_WARNING', '<strong>This message is included with all emails sent from this site:</strong>');


// Admin additional email subjects
define('SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO_SUBJECT','[CREATE ACCOUNT]');
define('SEND_EXTRA_GV_CUSTOMER_EMAILS_TO_SUBJECT','[GV CUSTOMER SENT]');
define('SEND_EXTRA_NEW_ORDERS_EMAILS_TO_SUBJECT','[NEW ORDER]');
define('SEND_EXTRA_CC_EMAILS_TO_SUBJECT','[EXTRA CC ORDER info] #');

// Low Stock Emails
define('EMAIL_TEXT_SUBJECT_LOWSTOCK','Warning: Low Stock');
define('SEND_EXTRA_LOW_STOCK_EMAIL_TITLE','Low Stock Report: ');
