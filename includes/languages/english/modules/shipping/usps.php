<?php
/**
 * USPS Module for Zen Cart v1.3.x thru v1.6
 * USPS RateV4 Intl RateV2 - 2016-01-17 Version K8
 * Prices from: Jan 17, 2016
 * Rates Names: Jan 17, 2016
 * Services Names: Jan 17, 2016
 *
 * @package shippingMethod
 * @copyright Copyright 2003-2016 Zen Cart Development Team

 * @copyright Portions Copyright 2003 osCommerce
 * @copyright Portions adapted from 2012 osCbyJetta
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: usps.php Jan 17, 2016 ajeh Version K8 $
 */

define('MODULE_SHIPPING_USPS_TEXT_TITLE', 'United States Postal Service');
define('MODULE_SHIPPING_USPS_TEXT_SHORT_TITLE', 'USPS');
define('MODULE_SHIPPING_USPS_TEXT_DESCRIPTION', 'United States Postal Service<br /><br />You will need to have registered an account with USPS at https://secure.shippingapis.com/registration/ to use this module<br /><br />USPS expects you to use pounds as weight measure for your products.');

define('MODULE_SHIPPING_USPS_TEXT_TEST_MODE_NOTICE', '<br /><span class="alert">Your account is in TEST MODE. Do not expect to see usable rate quotes until your USPS account is moved to the production server (1-800-344-7779) and you have set the module to production mode in Zen Cart admin.</span>');
define('MODULE_SHIPPING_USPS_TEXT_SERVER_ERROR', 'An error occurred in obtaining USPS shipping quotes.<br />If you prefer to use USPS as your shipping method, please try refreshing this page, or contact the store owner.');
define('MODULE_SHIPPING_USPS_TEXT_ERROR', 'We are unable to find a USPS shipping quote suitable for your mailing address and the shipping methods we typically use.<br />If you prefer to use USPS as your shipping method, please contact us for assistance.<br />(Please check that your Zip Code is entered correctly.)');

define('MODULE_SHIPPING_USPS_TEXT_DAY', 'day');
define('MODULE_SHIPPING_USPS_TEXT_DAYS', 'days');
define('MODULE_SHIPPING_USPS_TEXT_WEEKS', 'weeks');

