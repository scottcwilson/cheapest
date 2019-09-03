<?php
/**
 * @package admin
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Author: DrByte  Wed Oct 28 13:08:12 2015 -0400 Modified in v1.5.5 $
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
  define('OFFICE_FROM','From:');
  define('OFFICE_EMAIL','E-mail:');

  define('OFFICE_SENT_TO','Sent To:');
  define('OFFICE_EMAIL_TO','E-mail:');
  define('OFFICE_USE','Office Use Only:');
  define('OFFICE_LOGIN_NAME','Login Name:');
  define('OFFICE_LOGIN_EMAIL','Login e-mail:');
  define('OFFICE_LOGIN_PHONE','<strong>Telephone:</strong>');
  define('OFFICE_IP_ADDRESS','IP Address:');
  define('OFFICE_HOST_ADDRESS','Host Address:');
  define('OFFICE_DATE_TIME','Date and Time:');

// email disclaimer
  define('EMAIL_DISCLAIMER', 'Thank you for choosing Cheapest Dinar / Banknote Pros as your preferred source for collectible world banknotes and numismatic supplies.&nbsp; Buy with confidence knowing that each purchase is protected by our IRON CLAD  - "Guaranteed Authentic, Or We Will Buy It Back" Guarantee! ');
  define('EMAIL_SPAM_DISCLAIMER','This email is sent in accordance with U.S. CAN-SPAM Law, in effect since 01/01/2004.&nbsp; We honor all requests to be removed from our mailing list.&nbsp; However, by making a purchase through our website, you automatically consent to receive email communication specifically related to and regarding your purchase.&nbsp; If you wish to stop receiving all other email notifications, send your removal request to:&nbsp;&nbsp; <a href="mailto:Support@cheapstdinar.com?subject=Unsubscribe Request">Removal Requests</a>');
  define('EMAIL_FOOTER_COPYRIGHT','Copyright (c) ' . date('Y') . ' <a href="http://www.cheapestdinar.com" target="_blank">Cheapest Dinar / Guardian Services</a>.&nbsp;  Powered by <a href="http://www.cheapestdinar.com" target="_blank">Banknote Pros</a>');
  define('SEND_EXTRA_GV_ADMIN_EMAILS_TO_SUBJECT','[GV ADMIN SENT]');
  define('SEND_EXTRA_DISCOUNT_COUPON_ADMIN_EMAILS_TO_SUBJECT','[DISCOUNT COUPONS]');
  define('SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO_SUBJECT','[ORDERS STATUS]');
  define('TEXT_UNSUBSCRIBE', "\n\nTo unsubscribe from future newsletter and promotional mailings, simply click on the following link: \n");

// for whos_online when gethost is off
  define('OFFICE_IP_TO_HOST_ADDRESS', 'Disabled');

define('TEXT_EMAIL_SUBJECT_ADMIN_USER_ADDED', 'Admin Alert: New admin user added.');
define('TEXT_EMAIL_MESSAGE_ADMIN_USER_ADDED', 'Administrative alert: A new admin user (%s) has been ADDED to your store by %s.' . "\n\n" . 'If you or an authorized administrator did not initiate this change, it is advised that you verify your site security immediately.');
define('TEXT_EMAIL_SUBJECT_ADMIN_USER_DELETED', 'Admin Alert: An admin user has been deleted.');
define('TEXT_EMAIL_MESSAGE_ADMIN_USER_DELETED', 'Administrative alert: An admin user (%s) has been DELETED from your store by %s.' . "\n\n" . 'If you or an authorized administrator did not initiate this change, it is advised that you verify your site security immediately.');
define('TEXT_EMAIL_SUBJECT_ADMIN_USER_CHANGED', 'Admin Alert: Admin user details have been changed.');
define('TEXT_EMAIL_ALERT_ADM_EMAIL_CHANGED', 'Admin alert: Admin user (%s) email address has been changed from (%s) to (%s) by (%s)');
define('TEXT_EMAIL_ALERT_ADM_NAME_CHANGED', 'Admin alert: Admin user (%s) username has been changed from (%s) to (%s) by (%s)');
define('TEXT_EMAIL_ALERT_ADM_PROFILE_CHANGED', 'Admin alert: Admin user (%s) security profile has been changed from (%s) to (%s) by (%s)');
