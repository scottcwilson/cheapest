<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2017 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Author: DrByte  Modified in v1.5.5 $
 */

define('NAVBAR_TITLE_1', 'Checkout - Step 1');
define('NAVBAR_TITLE_2', 'Payment Method - Step 2');

define('HEADING_TITLE', 'Step 2 of 3 - Payment Information');

define('TABLE_HEADING_BILLING_ADDRESS', 'Billing Address');
define('TEXT_SELECTED_BILLING_DESTINATION', '<br><b>*</b>&nbsp; The billing address shown here should match the address on your Credit/Debit Card billing statement.<br><b>*</b>&nbsp; You can change the billing address by clicking the green "Change Address" button.<br><b>*</b>&nbsp; After reviewing your billing information, proceed to the lower part of this page to select your payment options.<br><br><br><br><br>');
define('TITLE_BILLING_ADDRESS', 'Billing Address:');

define('TABLE_HEADING_PAYMENT_METHOD', 'Payment Method');
define('TEXT_SELECT_PAYMENT_METHOD', '<br><b>*</b> &nbsp; <b>You MUST submit a payment authorization form to pay for currency purchases with a Credit/Debit card.</b><br>&nbsp;&nbsp;&nbsp;&nbsp; <i>otherwise please pay by US Postal Money Order, or Bank Check.</i><br> <b>*</b> &nbsp;<span style="color:#FF0000"> A 4% processing fee will be added to ALL currency purchases paid by credit/debit card.</span>  <br><br><br><b><u>PLEASE SELECT A PAYMENT METHOD FOR THIS ORDER</u>:</b><br>');
define('TITLE_PLEASE_SELECT', 'Please Select');
define('TEXT_ENTER_PAYMENT_INFORMATION', '');
define('TABLE_HEADING_COMMENTS', 'Special Instructions or Order Comments');

define('TITLE_NO_PAYMENT_OPTIONS_AVAILABLE', 'Not Available At This Time');
define('TEXT_NO_PAYMENT_OPTIONS_AVAILABLE','<span class="alert">Sorry, we are not accepting payments from your region at this time.</span><br />Please contact us for alternate arrangements.');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', '<strong>Continue to Step 3</strong>');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', '<br><b>*</b> &nbsp; Press "Continue"  to confirm your order on the next page. <br><b>*</b> &nbsp; You can come back to this page if you need to.');

define('TABLE_HEADING_CONDITIONS', '<span class="termsconditions">Terms and Conditions</span>');
define('TEXT_CONDITIONS_DESCRIPTION', '<span class="termsdescription"><br><br>Please acknowledge the terms and conditions bound to this order by clicking the following box. If you do not agree to our posted policies, Conditions Of Use, and Terms of use policies, then do NOT purchase from this website. By purchasing from this website, you acknowledge your agreement to be legally bound by these Terms, and Conditions of use.<br><br> The terms and conditions can be read <a href="' . zen_href_link(FILENAME_CONDITIONS, '', 'SSL') . '"><span class="pseudolink">Click Here</span></a>.');
define('TEXT_CONDITIONS_CONFIRM', '<span class="termsiagree"> <span style="color:#000000"><b><-- Check this Box</b></span> to signify that you have read, and agree to the terms and conditions bound to this order.</span>');

define('TEXT_CHECKOUT_AMOUNT_DUE', 'Total Amount Due: ');
define('TEXT_YOUR_TOTAL','Your Order Total');
