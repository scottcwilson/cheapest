<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2012 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Sat Jul 21 16:05:31 2012 -0400 Modified in v1.5.1 $
 */

define('NAVBAR_TITLE', 'Create an Account');

define('HEADING_TITLE', 'My Account Information');

define('TEXT_ORIGIN_LOGIN', '<strong class="note">NOTE:</strong> If you already have an account with us, please <a href="%s"><b>Login Here</b></a>.');

define('ERROR_CREATE_ACCOUNT_SPAM_DETECTED', 'Thank you, your account request has been submitted for review.&nbsp;  Once we review your account for membership, we will notify you by email if your membership is approved.&nbsp; All accounts are reviewed strictly in the order in which they are received.&nbsp;  This process may take between 2-3 Business weeks as we review on average 3 to 5 accounts each month.');


// greeting salutation
define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear Mr. %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Dear Ms. %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Dear %s' . "\n\n");

// First line of the greeting
define('EMAIL_WELCOME', 'We wish to welcome you to <strong>' . STORE_NAME . '</strong>.');
define('EMAIL_SEPARATOR', '--------------------');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations! To make your next visit to our online shop a more rewarding experience, listed below are details for a Discount Coupon created just for you!' . "\n\n");
// your Discount Coupon Description will be inserted before this next define
define('EMAIL_COUPON_REDEEM', 'To use the Discount Coupon, enter the ' . TEXT_GV_REDEEM . ' code during checkout:  <strong>%s</strong>' . "\n\n");
define('TEXT_COUPON_HELP_DATE', '<p>The coupon is valid between %s and %s</p>');

define('EMAIL_GV_INCENTIVE_HEADER', 'Just for stopping by today, we have sent you a ' . TEXT_GV_NAME . ' for %s!' . "\n");
define('EMAIL_GV_REDEEM', 'The ' . TEXT_GV_NAME . ' ' . TEXT_GV_REDEEM . ' is: %s ' . "\n\n" . 'You can enter the ' . TEXT_GV_REDEEM . ' during Checkout, after making your selections in the store. ');
define('EMAIL_GV_LINK', ' Or, you may redeem it now by following this link: ' . "\n");
// GV link will automatically be included before this line

define('EMAIL_GV_LINK_OTHER','Once you have added the ' . TEXT_GV_NAME . ' to your account, you may use the ' . TEXT_GV_NAME . ' for yourself, or send it to a friend!' . "\n\n");

define('EMAIL_TEXT', '<br><br> Thank you for applying for a free membership account with Cheapest Dinar, we sincerely appreciate your interest in becoming a part of our team.&nbsp;  As notated on our website, So that we may comfortably meet the needs of our current members, we are not accepting new members at this time.&nbsp;  Unfortunately there is no set or projected time that this will remain in effect, but we will update our website once we again reviewing new membership accounts.<br><br><br> <b>PLEASE NOTE:</b>&nbsp;  Your account membership will need to be reviewed prior to approval, therefore your account is not yet activated.&nbsp;  We manually review all accounts for membership, and approve accounts strictly in the order in which they are received.&nbsp;  During busy shopping times with our current members, we will occasionally postpone new membership approvals so that we can first meet the needs of our existing members.&nbsp;  We do this so that we can provide our current members with the undivided attention they deserve, and have come to expect from us.' . "\n\n" . 'As notated on our website, So that we may comfortably meet the needs of our current members, we are not accepting new members at this time.&nbsp;  Unfortunately there is no set or projected time that this will remain in effect, but we will update our website once we again reviewing new membership accounts.'  . "\n\n" . ' Once we begin accepting new members,  Any membership applications which were submitted before you had submitted yours, determine the time it takes to review your account.&nbsp; We will review only 3 to 5 new memberships each month, and all memberships are reviewed strictly in the order in which they were received.&nbsp;  We understand your interest in getting started and making your first purchase, and we promise that we will review your membership at our earliest possible convenience. - Thank you for your patience during this time.' . "\n\n" . 'Once we review your account information, we will send you an email informing you on our decision of your account status.&nbsp;   If your account is approved, the email you receive will have detailed information on our policies and Membership Agreement.&nbsp;  You <b><u>MUST</u></b> agree to this Membership agreement and policies to continue onto the activation of your account.&nbsp;  If you agree to the Terms and Policies in your Membership Activation email, you will be asked to follow the link provided to activate your account.&nbsp;  Once you follow that link, your account will be activated, and you will be authorized to begin purchasing immediately logging into your account through that activation link.&nbsp; ' . "\n\n" . 'If you do not agree to our Terms Of Service, Membership Agreement, or any other posted Policies, you may NOT use or purchase from our website, and you must not continue onto the activation link posted in your activation email.&nbsp;  Activating your account and making a purchase from our website will signify your willful agreement to be bound by our posted TOS, Policies, Conditions Of Use, Membership agreement policies, and purchasing Requirements.&nbsp;  Therefore, please be sure that you indeed agree to be bound by such before making your first purchase. ' . "\n\n" . '<b>PLEASE NOTE:</b>   We Do <u>NOT</u> Approve Membership accounts for residents of  Arkansas, Texas, Utah, Washington, or Washington State.&nbsp;  Additionally, we do NOT approve membership accounts for residents who reside anywhere outside the United States.&nbsp;   If you reside in any of these prohibited locations, your account membership will be denied.&nbsp;  There are absolutely NO exceptions for these location restrictions.' . "\n\n");
define('EMAIL_CONTACT', 'For help with any of our online services, please email the store-owner: <a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">'. STORE_OWNER_EMAIL_ADDRESS ." </a>\n\n");
define('EMAIL_GV_CLOSURE', "\n" . 'Sincerely,' . "\n\n" . STORE_OWNER . "\nStore Owner\n\n". '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'.HTTP_SERVER . DIR_WS_CATALOG ."</a>\n\n");

// email disclaimer - this disclaimer is separate from all other email disclaimers
define('EMAIL_DISCLAIMER_NEW_CUSTOMER', 'This email address was given to us by you or by one of our customers who had chosen to refer you to our website, or by yourself when creating your online account.&nbsp;  If you did not signup for an account, or feel that you have received this email in error, please send an email to %s ');
