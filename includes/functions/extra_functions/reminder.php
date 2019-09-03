<?php
function send_reminder_mail($oID, $name, $email_address, $text) {
   global $db; 
   
   require_once("includes/languages/english/reminder.php");
   $email_html = nl2br($text); 

   $content['EMAIL_MESSAGE_HTML'] = $email_html;
   $content['EMAIL_SUBJECT'] = EMAIL_TEXT_SUBJECT;

   $email_text = strip_tags($text);
   $subject = EMAIL_TEXT_SUBJECT; 
   zen_mail($name, $email_address, $subject, $email_text, STORE_NAME, EMAIL_FROM, $content); 
   echo "Sent order " . $oID . " email reminder to " . $email_address . "<br />"; 
   $today = date("Y-m-d"); 
   $update_reminder_query = "UPDATE " . TABLE_ORDERS . " SET reminder_sent=1, reminder_sent_date='" . $today . "' WHERE orders_id='" . $oID . "'";
   $db->Execute($update_reminder_query);
}
