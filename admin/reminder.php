<?php 
// Common code for reminder 
   require 'includes/languages/english/reminder.php'; 
   $reminder_info = $db->Execute("SELECT reminder_delay, reminder_sent, reminder_email, reminder_sent_date FROM " . TABLE_ORDERS . " WHERE orders_id = '" . zen_db_input($oID) . "'"); 
   $reminder_options = array(0,1,25,40,55,85,115); 
   // Testing only 
   //$reminder_options = array_merge(array(1), $reminder_options);
   //$reminder_options = array_unique($reminder_options); 

   $today = date("Y-m-d",time());
   foreach ($reminder_options as $option) {
     $check_time = strtotime($order->info['date_purchased']) + 24*60*60*$option;
     $check_date = date("Y-m-d", $check_time);
     if ($option > 1) { // always allow 0 and 1 in the list 
        if ($today > $check_date) {
          // too late to send unless it's the current value
          if ($option != $reminder_info->fields['reminder_delay']) {
            continue;
          }
        }
     }
     if ($option == 0) {
       $option_text = "Do not send"; 
     } else {
       $option_text = $option; 
     }
     $reminder_delay[] = array('id' => $option, 'text' => $option_text); 
   }
   echo  '<strong>'. TABLE_HEADING_REMINDER_CONTENT . '</strong>';
   echo  '<br /><br />';  
   $prior_reminder = $db->Execute("SELECT reminder_sent_date FROM " . TABLE_ORDERS . " WHERE customers_id = " . (int)$order->customer['id'] . " AND reminder_sent=1 ORDER BY reminder_sent_date DESC"); 
   if ($prior_reminder->EOF) {
     echo NO_REMINDER_SENT; 
   } else {
     echo LAST_REMINDER_SENT . $prior_reminder->fields['reminder_sent_date']; 
   }
   echo  '<br /><br />';  
   if ($reminder_info->fields['reminder_sent'] == 0) { 
?>
        <div class="row noprint">
          <div class="formArea">
              <?php echo zen_draw_form('update_reminder', FILENAME_ORDERS, zen_get_all_get_params(array('action')) . 'action=update_reminder', 'post', 'class="form-horizontal"', true); ?>
            <div class="form-group">
                <?php echo zen_draw_label(ENTRY_REMINDER_CONTENT, 'reminder_email', 'class="col-sm-3 control-label"'); ?>
              <div class="col-sm-9">
                  <?php echo zen_draw_textarea_field('reminder_email', 'soft', '60', '5', $reminder_info->fields['reminder_email'], 'class="form-control"'); ?>
              </div>
                <?php echo zen_draw_label(ENTRY_REMINDER_DELAY, 'reminder_delay', 'class="col-sm-3 control-label"'); ?>
              <div class="col-sm-9">
                  <?php echo zen_draw_pull_down_menu('reminder_delay', $reminder_delay, $reminder_info->fields['reminder_delay'], 'class="form-control"'); ?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-3">
                <br />
                <button type="submit" class="btn btn-info"><?php echo IMAGE_UPDATE; ?></button>
              </div>
            </div>
            </form> 
<?php 
   } else { 
?>
      <div>
<?php echo REMINDER_EMAIL_SENT_DATE . $reminder_info->fields['reminder_sent_date'] . "."; ?>
      <br />
      <div style="border:1px solid black; width:80%; margin: 2px; padding: 5px;">
<?php echo $reminder_info->fields['reminder_email']; ?>
      </div>
      <br /><br />
<?php echo '<a href="' . zen_href_link(FILENAME_ORDERS, zen_get_all_get_params(array('action')) . 'action=reset_update_reminder', 'NONSSL') . '">Reset Reminder Email</a>'; ?>
      </div>
<?php 
   }
?>
           </div>
         </div>
