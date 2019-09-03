<?php

/*
*
*
*     Author: Femi Ademosu
*      Email: femi@coderxo.com
*        Web: http://www.coderxo.com
*    Details: AcceptChecksToday Module for Zencart
*    FileName acceptcheckstoday.php
*
*
* Please direct bug reports,suggestions or feedback to the http://www.coderxo.com
*                                                                          
* This is a commercial software. Any distribution is strictly prohibited.
*
*/


  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_TITLE', 'AcceptChecksToday');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_PUBLIC_TITLE', 'Electronic Check (USA Only)<br>');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_DESCRIPTION', '<strong>AcceptChecksToday E-Check Module</strong><br><br>Be sure to put your API Security Key in the field below!!');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ACCOUNTNUMBER', 'Account Number <br>(Full Number, incl. Zeros):<br><br>');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ROUTINGNUMBER', 'ABA/Routing Number:');

  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_JS_CK_ACCOUNT_NUMBER', '* The account number is required\n');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_JS_CK_ROUTING_NUMBER', '* The routing number must be at least 9 digits.\n');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ERROR_CK_ACCOUNT_NUMBER', 'The account number must be populated.');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ERROR_CK_ROUTING_NUMBER', 'The routing number must be populated.');

  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ERROR_MESSAGE', 'There has been an error processing your e-check transaction. Please try again.');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_DECLINED_MESSAGE', 'Your e-check transaction was declined. Please try another form of payment or contact your bank for more info.');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ERROR', 'E-Check Error!');
	define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_NOCOMM' , 'Could not communicate with gateway');
  define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_DECLINED_MESSAGE', 'Your eCheck was declined');
	
	define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_PLEASE_SIGN', '<br><br><br><center> <span style="color: rgb(255, 0, 0);"><font size = "3">For Security, <u>ALL</u> Payment Types Now REQUIRE Your Digital Signature In The Yellow Box On The eCheck Form Below. </span></center><br>  Using the mouse for your computer hold down the left click button to provide your digital signature. &nbsp; It will be messy, thats perfectly ok. </font><br><br>You hereby authorize a $20 fee for eChecks declined for payment!<br><br>
	<ul>
	<li>Clearing Time: 7 Business Day for eCheck payments.</li>
	<li>Clearing Time: 1-2 Business Day for Credit/Debit card payments.</li>
        <li>Clearing Time: 2-3 Business Day for Money Order payments.</li>
        <li>Returned eChecks that remain unpaid will be subject to collections</li>
        <li>Some eCheck payments may not show in your account, enter each transaction 1 time. &nbsp; We will email your transaction details to you.</li>
</ul>

');
	define('MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_JS_PLEASE_SIGN', 'Please sign your echeck');
 ?>
