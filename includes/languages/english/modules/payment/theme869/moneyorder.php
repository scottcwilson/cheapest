<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: moneyorder.php 1969 2005-09-13 06:57:21Z drbyte Modified in v1.5.6 $
//

define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', 'Bank Check / Money Order');
define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', '<br><b>Please Make Your Check / Money Order Payable To:</b><br>' . MODULE_PAYMENT_MONEYORDER_PAYTO . '<br><br><b>Mail Your Payment To:</b><br>' . nl2br(STORE_NAME_ADDRESS) . '<br /><br />' . '<b>*</b>&nbsp; <span style="color:#FF0000"> Lay-Away orders,  will <u>NOT</u> be validated until we receive cleared payment!</span> <br> <b>*</b>&nbsp; All mail-in payments are due within 7 days of the date of purchase.<br> <b>*</b>&nbsp; We reserve the right to cancel any order if we have not received your payment, or your payment has not yet been processed.');
if (defined('MODULE_PAYMENT_MONEYORDER_STATUS')) {
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "Please make your check or money order payable to:" . "\n\n" . MODULE_PAYMENT_MONEYORDER_PAYTO . "\n\nMail your payment to:\n" . STORE_NAME_ADDRESS . "\n\n" . 'Your order will not be validated until we receive cleared payment.');
}
