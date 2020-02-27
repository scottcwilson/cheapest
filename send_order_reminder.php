<?php
require("includes/application_top.php");
// don't run checks on localhost
if (strpos(HTTP_SERVER, "localhost") === false) { 
   if (getenv('CRON_MODE')) {
     // Call from Cron
   } else if ($_GET['key'] != SEND_REMINDER_CRON_KEY) {
     // Call from web with bad arg - possibly attack
     die("Invalid web"); // norefreshlog
   }
}

$list = $db->Execute("SELECT orders_id, customers_name, customers_email_address, reminder_delay, reminder_email, date_purchased FROM " . TABLE_ORDERS . " WHERE reminder_delay > 0 AND reminder_sent = 0"); 

$today = date("Y-m-d",time());
while (!$list->EOF) {
  $delay = $list->fields['reminder_delay']; 
  if ($delay == 1) $delay = 0; // immediate send 
  $check_time = strtotime($list->fields['date_purchased']) + 24*60*60*$delay;
  $check_date = date("Y-m-d", $check_time);
  if ($today >= $check_date) {
    send_reminder_mail($list->fields['orders_id'],
      $list->fields['customers_name'], 
         $list->fields['customers_email_address'],
         $list->fields['reminder_email']); 
  }
  $list->MoveNext();
}
