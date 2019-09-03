<?php
/**
 * init_includes/init_notes_notifier.php
 *
 * @package notes
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 *
 * @version: 0.97 Paul Mathot
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
// config
define('TABLE_NOTES', DB_PREFIX .'notes');
define('TABLE_NOTES_CATEGORIES', DB_PREFIX .'notes_categories');  
define('FILENAME_NOTES', 'notes.php'); 
define('MODULE_NOTES_STATUS','True');

function zen_active_notes(){
  global $db, $messageStack;
  // BOF list active notes

  $order_by = 'ORDER BY notes_categories_name';
  $notes_categories_query = ("select * FROM " . TABLE_NOTES_CATEGORIES . " " . $order_by);
  $notes_categories_results = $db->Execute($notes_categories_query);  
  
  while(!$notes_categories_results->EOF){
      
      $order_by = 'ORDER BY notes_start_date DESC';
      // use CURDATE ?
      //$notes_active_query = ("select * FROM " . TABLE_NOTES . " n LEFT JOIN " . TABLE_NOTES_CATEGORIES . " nc ON (n.notes_categories_id = nc.notes_categories_id) " . " WHERE (`notes_status` = '1') AND (((`notes_start_date` <= NOW()) AND (`notes_start_date` >= '0001-01-01')) AND  ((`notes_end_date` >= NOW()) OR (`notes_end_date` <= '0001-01-01')))" . $order_by);
      $notes_active_query = ("select * FROM " . TABLE_NOTES . " WHERE ((`notes_is_public` = '1' || `admin_id` = '" . (int)$_SESSION['admin_id'] . "') AND ((`notes_categories_id` = '" . $notes_categories_results->fields['notes_categories_id'] . "') AND ((`notes_status` = '1') AND (((`notes_start_date` <= NOW()) AND (`notes_start_date` >= '0001-01-01')) AND  ((`notes_end_date` >= NOW()) OR (`notes_end_date` <= '0001-01-01'))))))" . $order_by);  	  	  
      $notes_active_results = $db->Execute($notes_active_query);
      
      if(count($notes_active_results) > 0){
        if(isset($_GET['oID']) && ($_GET['oID'] > 0)){
          // lookup the customer id of the order
          // so we can show the customer notes when viewing orders ($order is not available yet at this point, we need a query)
          $notes_customer_query = ("select customers_id FROM " . TABLE_ORDERS . " WHERE (`orders_id` = " . (int)$_GET['oID'] . ") LIMIT 1");
          $notes_customer_result = $db->Execute($notes_customer_query);
        }
      }
              
     $messageStackArray = array();	  
      while(!$notes_active_results->EOF){
        /*
        echo '<pre>';
        print_r($notes_active_results->fields);
        echo '</pre>';
        */
        $note_link = zen_href_link('notes', 'nID=' . $notes_active_results->fields['notes_id']);
        $notes_active_array = $notes_active_results->fields;
        $notes_active = new objectInfo($notes_active_array);
        //print_r($notes_active);
        
        switch(true){
          
          case($notes_active->notes_is_special_status == 1):
            // special products, customers, orders or categories note
            if(basename($_SERVER['PHP_SELF']) != FILENAME_NOTES){
              // do not show on notes page itself
              if($notes_active->customers_id > 0 || $notes_active->orders_id > 0 ||$notes_active->categories_id > 0 ||$notes_active->products_id > 0){
                 // these notes must only be shown when the customer/order/category/product is viewed by the admin 
     
                // customers note
                if(isset($_GET['cID']) && ($_GET['cID'] == $notes_active->customers_id) ||(($notes_customer_result->fields['customers_id'] > 0) && ($notes_customer_result->fields['customers_id'] == $notes_active->customers_id)))    
                  $messageStackArray[] = '<a href="' . $note_link . '&action=view' . '">' . $notes_active_results->fields['notes_title'] . '</a> (Special Customers note)';
                // orders note   
                if(isset($_GET['oID']) && ($_GET['oID'] == $notes_active->orders_id))              
                  $messageStackArray[] = '<a href="' . $note_link . '">' . $notes_active_results->fields['notes_title'] . '</a> (Special Orders note)';
                // category note       
                if(isset($_GET['cPath']) && ($_GET['cPath'] != '') && ($_GET['cPath'] == $notes_active->categories_id))     
                  $messageStackArray[] = '<a href="' . $note_link . '">' . $notes_active_results->fields['notes_title'] . '</a> (Special Categories note)';            
                // products note      
                if(isset($_GET['pID']) && ($_GET['pID'] == $notes_active->products_id)) 
                  $messageStackArray[] = '<a href="' . $note_link . '">' . $notes_active_results->fields['notes_title'] . '</a> (Special Products note)';      
              }else{
                // note is not attached yet  
                if(isset($_GET['pID']) || isset($_GET['cPath']) || isset($_GET['oID']) || isset($_GET['cID'])){
                  $parameters = '';
                  $parameters = ((isset($_GET['cPath'])) ? '&cPath=' . $_GET['cPath'] : '') . ((isset($_GET['pID'])) ? '&pID=' . (int)$_GET['pID'] : '') . ((isset($_GET['oID'])) ? '&oID=' . (int)$_GET['oID'] : '') .((isset($_GET['cID'])) ? '&cID=' . (int)$_GET['cID'] : '') ; 
                  $messageStackArray[] = 'Attach this ' . ' note now? ' . '<a href="' . $note_link . '&action=edit' . $parameters . '">' . $notes_active_results->fields['notes_title'] . '</a>';
                } 
              }
            }
          break;
          
          case(($notes_active->notes_is_special_status != 1) && ($notes_active->customers_id > 0 || $notes_active->orders_id > 0 ||$notes_active->categories_id > 0 ||$notes_active->products_id > 0)):
            // non special products, customers, orders or categories note        
          
            // customers note
            if($notes_active->customers_id > 0)
              $messageStackArray[] = '<a href="' . $note_link . '">' . $notes_active_results->fields['notes_title'] . '</a>' .
              '| <a href="' . FILENAME_CUSTOMERS . '.php' . '?cID=' . $notes_active->customers_id . '&action=edit' . '">' . 'Edit customer' . '</a>';
            // orders note   
            if($notes_active->orders_id > 0)
              $messageStackArray[] = '<a href="' . $note_link . '">' . $notes_active_results->fields['notes_title'] . '</a>' .
              ' | <a href="' . FILENAME_ORDERS . '.php' . '?oID=' . $notes_active->orders_id . '&action=edit' . '">' . 'Edit order' . '</a>';
              
            // category note)       
            if(zen_not_null($notes_active->categories_id))
              $messageStackArray[] = '<a href="' . $note_link . '">' . $notes_active_results->fields['notes_title'] . '</a>' .
              ' | <a href="' . FILENAME_CATEGORIES . '.php' . '?cPath=' . $notes_active->categories_id . '">' . 'Edit category' . '</a>';
              
            // products note
            if($notes_active->products_id > 0){
              //&product_type=1&cPath=1_4&pID=1&action=new_product;
              $messageStackArray[] = '<a href="' . $note_link . '">' . $notes_active_results->fields['notes_title'] . '</a>' .
              ' | <a href="' . FILENAME_PRODUCT . '.php' . '?action=new_product&pID=' . $notes_active->products_id . '&product_type=' . zen_get_products_type($notes_active->products_id) . '&cPath=' . zen_get_products_category_id($notes_active->products_id) . '">' . 'Edit product' . '</a>';
            }       
          
          break;      
          
          default:
            // general notes
            $messageStackArray[] = '<a href="' . $note_link . '">' . $notes_active_results->fields['notes_title'] . '</a>';
          break;      
          
        }
        
      
        if(($notes_active->notes_is_special_status == 1) && (basename($_SERVER['PHP_SELF']) != FILENAME_NOTES)){
          $show_special = true;
        }   
        
        if($notes_active->customers_id > 0 || $notes_active->orders_id > 0 ||$notes_active->categories_id > 0 ||$notes_active->products_id > 0){   
         
          
               
        }elseif($special){
          // ask to attach
          //exit('special attach ' . $notes_active->notes_id);

        }elseif($notes_active->notes_is_special_status != 1){
       }

        $notes_active_results->MoveNext();
      }
  
      if(count($messageStackArray) > 0){	
        $messageStackNotes = implode('</li><li>', $messageStackArray);
        $messageStack->add($notes_categories_results->fields['notes_categories_name'] . ' notes:<ul id="notesNotifierList" style="margin: 0.1em;"><li>'. $messageStackNotes. '</li></ul>', 'warning');		
        
      }	  
      
  
    $notes_categories_results->MoveNext();
  }
  
  

  
  // EOF list active notes   
}
if((basename($_SERVER['PHP_SELF']) != 'login.php') && (MODULE_NOTES_STATUS == 'True')){
  // xxxxx  check if installed first!!!
  zen_active_notes();
  
}
?>