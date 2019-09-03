<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: login.php 14280 2009-08-29 01:33:18Z drbyte $
 */

define('NAVBAR_TITLE', 'Login');
define('HEADING_TITLE', 'Welcome, Please Sign In');

define('HEADING_NEW_CUSTOMER', '<br><br><font size="4"><span style="color:#0000FF;"><b>Not Yet A Member?</span></font></b><br><font size="3"> Create Your Free Account By Providing The Information Below.<br><b>NOTICE:</b>&nbsp;&nbsp; We Are Not Currently Accepting New Members At This Time.</font><br><br><font size="4"><b>We Will <u>NOT</u> Approve Member Accounts From The Following Locations:</b></font><br><font size="3"> Arkansas, Illinois, Florida, Texas, Utah, Washington, Washington State, or any location outside the USA.</font>');
define('HEADING_NEW_CUSTOMER_SPLIT', 'New Customers');

define('TEXT_NEW_CUSTOMER_INTRODUCTION', '<font size="2">Create your FREE membership account with <strong>' . STORE_NAME . '.</strong>&nbsp;  As a Member you will have access to our entire online product catalog, including prices.&nbsp;  You will also be able to review your previous order history, and receive shipping updates about current orders.&nbsp; Your information is protected by our secure SSL Encryption Technology, and we will NEVER share, or sell your information with anyone!</font><br><br><font size="2"> <b>*</b> We do NOT ship to Arkansas, Illinois, Florida, Texas, Utah, Washington, Washington State.<br> <b>*</b> We will NOT ship to ANY location outside of the USA.<br> <b>*</b> We will ship ONLY to the address on file at the time of purchase!<br> <b>*</b> Absolutely NO PO Boxes, or Suite Addresses.<br> ');
define('TEXT_NEW_CUSTOMER_INTRODUCTION_SPLIT', 'Have a PayPal account? Want to pay quickly with a credit card? Use the PayPal button below to use the Express Checkout option.');
define('TEXT_NEW_CUSTOMER_POST_INTRODUCTION_DIVIDER', '<span class="larger">Or</span><br />');
define('TEXT_NEW_CUSTOMER_POST_INTRODUCTION_SPLIT', 'Create a Member Profile with <strong>' . STORE_NAME . '</strong> which allows you to shop faster, track the status of your current orders, review your previous orders and take advantage of our other member\'s benefits.');

define('HEADING_RETURNING_CUSTOMER', ' <font size="2">Due to recent security updates, some members are occasionally required to reset their account passwords.&nbsp; If your old password does not work, please click the green "Click Here To Reset Your password" button below.&nbsp; A temporary password will be emailed to you within 10-15 minutes.&nbsp; Please click this button only once, your reset link will arrive shortly.&nbsp; Resetting your password regularly can help keep your account secure, we recommend resetting your password every three to six months for maximum security.<br><span style="color:#0000ff;"><b>To Create A New Account,&nbsp; Scroll Down To The Next Section.</b></span></font><br><br><font size="4">Returning Members,&nbsp; Log In Here:</font><br>');
define('HEADING_RETURNING_CUSTOMER_SPLIT', 'Returning Customers');

define('TEXT_RETURNING_CUSTOMER_SPLIT', 'In order to continue, please login to your <strong>' . STORE_NAME . '</strong> account.');

define('TEXT_PASSWORD_FORGOTTEN', 'Click Here To Reset Your Password');

define('TEXT_LOGIN_ERROR', 'Error: Sorry, there is no match for that email address and/or password.');
define('TEXT_VISITORS_CART', '<strong>Note:</strong> If you have shopped with us before and left something in your cart, for your convenience, the contents will be available if you log back in. <a href="javascript:session_win();">[More Info]</a>');

define('TABLE_HEADING_PRIVACY_CONDITIONS', '<span class="privacyconditions">Privacy Statement</span>');
define('TEXT_PRIVACY_CONDITIONS_DESCRIPTION', '<span class="privacydescription">Please acknowledge you agree with our privacy statement by clicking the box shown. <br> The privacy statement can be read</span><a href="' . zen_href_link(FILENAME_PRIVACY, '', 'SSL') . '"><span class="pseudolink">Click Here</span></a>.');
define('TEXT_PRIVACY_CONDITIONS_CONFIRM', '<span class="privacyagree">I have read and agreed to your Terms of Service, Conditions of Use, and Membership policies.</span>');

define('ERROR_SECURITY_ERROR', 'There was a security error when trying to login.');

define('TEXT_LOGIN_BANNED', 'Error: Access denied.');
