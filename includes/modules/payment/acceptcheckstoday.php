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


  class acceptcheckstoday {

    var $code, $title, $description, $enabled;

    function acceptcheckstoday() {

      global $order;
			
      $this->code = 'acceptcheckstoday';;
      $this->title =  basename($_SERVER['SCRIPT_FILENAME']) == 'modules.php' ? MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_TITLE : MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_PUBLIC_TITLE ;
      $this->description = MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_DESCRIPTION;
      
			$this->sort_order = MODULE_PAYMENT_ACCEPTCHECKSTODAY_SORT_ORDER;
      $this->email_footer = '';
      $this->enabled = ((MODULE_PAYMENT_ACCEPTCHECKSTODAY_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_PENDING > 0)
        $this->order_status = MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_PENDING;

      if (is_object($order)) $this->update_status();
    
		}


    function update_status() {

      global $order, $db;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_ACCEPTCHECKSTODAY_ZONE > 0) ) {

        $check_flag = false;
        $check_query = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_ACCEPTCHECKSTODAY_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");

        while (!$check->EOF) {
          if ($check->fields['zone_id'] < 1) {
            $check_flag = true;
            break;

          } elseif ($check->fields['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
					
          $check->MoveNext();
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }

    }



    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var ck_account_number = document.checkout_payment.acceptcheckstoday_accountnumber.value;' . "\n" .
            '    var ck_routing_number = document.checkout_payment.acceptcheckstoday_routingnumber.value;' . "\n" .
            '    var ck_signature      = document.checkout_payment.signature.value;' . "\n" .						
            '    if (ck_account_number == "") {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_JS_CK_ACCOUNT_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (ck_routing_number == "" || ck_routing_number.length < 9 ) {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_JS_CK_ROUTING_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
						'    }' . "\n\n".						
            '    if (ck_signature== "" || ck_signature.length < 2 ) {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_JS_PLEASE_SIGN . '";' . "\n" .
            '      error = 1;' . "\n" .						
            '    }' . "\n\n}\n\n".
      			"function isNumberKey(evt)\n".
      			"{\n".
         		"var charCode = (evt.which) ? evt.which : event.keyCode\n\n".
         		"if (charCode > 31 && (charCode < 48 || charCode > 57))\n".
            "\treturn false;\n".
						"return true;\n".
      			"}\n";

      return $js;
    } 



    function selection() {

       global $order, $messageStack;

	  	 if ($_GET['error']) 
			 		$messageStack->add('header', 'WARNING: '.$_GET['error'], 'caution'); 

    	 $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(
                                           array('title' => MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ACCOUNTNUMBER,
                                                 'field' => zen_draw_input_field('acceptcheckstoday_accountnumber', $_GET['acceptcheckstoday_accountnumber'] , ' onkeypress="return isNumberKey(event)"' )  ),
																								 
                                           array('title' => MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ROUTINGNUMBER,
                                                 'field' => zen_draw_input_field('acceptcheckstoday_routingnumber', $_GET['acceptcheckstoday_routingnumber'], ' onkeypress="return isNumberKey(event)"' )) , 

						
																					array(  'title' => '' ,
																					   			'field' => "<br/><h3>".MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_PLEASE_SIGN."</h3>\n".
																														 "<link rel=\"stylesheet\" href=\"".DIR_WS_MODULES."payment/acceptcheckstoday/jquery.signaturepad.css\">\n".
    																												 "<!--[if lt IE 9]><script src=\"".DIR_WS_MODULES."payment/acceptcheckstoday/flashcanvas.js\"></script><![endif]-->\n".
    																												 "<br/><script src=\"".DIR_WS_MODULES."payment/acceptcheckstoday/jquery-1.6.4.min.js\"></script>\n".
																														 "<div id=\"sigpaddiv\">\n".
    																												 "<div class=\"sigPad\">\n".
    																												 "<div class=\"sig sigWrapper\">\n".
    																												 "<canvas class=\"pad\" width=\"300\" height=\"60\"></canvas>\n".
    																												 "<input type=\"hidden\" name=\"signature\" class=\"output\" />\n".
																														 "<input type=\"hidden\" name=\"submit\" value=\"1\" />\n".
    																												 "</div>\n".
    																												 "<button class=\"clearSig clearButton\">Clear Signature </button>\n".
    																												 "</div>".
																														 "<script src=\"".DIR_WS_MODULES."payment/acceptcheckstoday/jquery.signaturepad.js\"></script>\n".
    																												 "<script>\n".
    																												 "$(document).ready(function() {\n".
    																												 "$('.sigPad').signaturePad({\n".
    																												 "displayOnly: false,\n".
    																												 "drawOnly:true,\n".
    																												 "bgColour:'#ffff00',\n".
    																												 "validateFields:true,\n".
    																												 "lineTop:60,\n".
    																												 "penWidth:1,\n".
    																												 "lineColour:'#000',\n".
    																												 "lineMargin:0\n".
   																													 "});\n".
    																												 "});\n".
    																												 "</script>\n".
    																												 "<script src=\"".DIR_WS_MODULES."payment/acceptcheckstoday/json2.min.js\"></script><br/>"
																					  )							, 																									 							 
																					
																					 array('title' => '',
                                                 'field' => "<br/><img src='".DIR_WS_MODULES."payment/acceptcheckstoday/check.jpg'><br/>"  )
																			)
										);

       return $selection;

    }


    
		function pre_confirmation_check() {
		
      if(!is_numeric($_POST['acceptcheckstoday_accountnumber'])) 
			   zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, 'acceptcheckstoday_accountnumber='.$_POST['acceptcheckstoday_accountnumber'].'&acceptcheckstoday_routingnumber='.$_POST['acceptcheckstoday_routingnumber'].'&error='.MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_JS_CK_ACCOUNT_NUMBER, 'SSL'));

      if( ereg("[^0-9]+",$_POST['acceptcheckstoday_routingnumber']) )
			   zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, 'acceptcheckstoday_accountnumber='.$_POST['acceptcheckstoday_accountnumber'].'&acceptcheckstoday_routingnumber='.$_POST['acceptcheckstoday_routingnumber'].'&error='.MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_JS_CK_ROUTING_NUMBER, 'SSL'));
		  elseif( strlen($_POST['acceptcheckstoday_routingnumber']) < 9) 
		  	zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT,  'acceptcheckstoday_accountnumber='.$_POST['acceptcheckstoday_accountnumber'].'&acceptcheckstoday_routingnumber='.$_POST['acceptcheckstoday_routingnumber'].'&error=Routing Number must be a minimum of 9 numbers', 'SSL'));

      $_SESSION["OUTPUT"] =  $_POST['signature'];
		
		}


    
		function confirmation() {
      return false;
    }
		

    function process_button() {

      $process_button_string =   zen_draw_hidden_field('acceptcheckstoday_accountnumber', $_POST['acceptcheckstoday_accountnumber']) .
                             		 zen_draw_hidden_field('acceptcheckstoday_routingnumber', $_POST['acceptcheckstoday_routingnumber']);
																 
      return $process_button_string;

    }



    function before_process() {

	  				 global $insert_id, $order,$db , $messageStack;
						 
						 if(!$insert_id)
						 {
    				    // Calculate the next expected order id
    				 		$last_order_id = $db->Execute("select * from " . TABLE_ORDERS . " order by orders_id desc limit 1");
    				 		$new_order_id = $last_order_id->fields['orders_id'];
    				 		$insert_id = ($new_order_id + 1);		
						 }
						 
						 
						 $query = "select * from " . TABLE_ZONES . " z left join " . TABLE_COUNTRIES . " c on (z.zone_country_id = c.countries_id) where zone_id = ".$order->billing['zone_id'];
 
    				 $address = $db->Execute($query);
						 // if($address->fields['countries_iso_code_2'] == 'US' || $address->fields['countries_iso_code_2'] == 'CA' )
						 {
		  			 	  if( $address->fields['zone_code'] && strlen($address->fields['zone_code']) < 3 )
								{
			  				  $order->billing['state'] = $address->fields['zone_code'];
								}
						 }
								 
						 
						 				 
				 
    		 		 $request = "<?xml version='1.0' encoding='UTF-8' ?>\n".
    								 	 "<transaction>\n".
							 				 "<auth>\n".
    					 				 "<userID>".MODULE_PAYMENT_ACCEPTCHECKSTODAY_USERID."</userID>\n".
    					 				 "<userKey>".MODULE_PAYMENT_ACCEPTCHECKSTODAY_KEY."</userKey>\n".
    					 				 "<version>".MODULE_PAYMENT_ACCEPTCHECKSTODAY_VERSION."</version>\n".
    					 				 "<environment>".MODULE_PAYMENT_ACCEPTCHECKSTODAY_ENVIRONMENT."</environment>\n".
    					 				 "</auth>\n".
							 				 "<customer>\n".
    					 				 "<firstName>".$order->billing['firstname']."</firstName>\n".
    					 				 "<lastName>".$order->billing['lastname']."</lastName>\n".
    					 				 "<address>".$order->billing['street_address']."</address>\n".
    					 				 "<address2></address2>\n".
    					 				 "<city>".$order->billing['city']."</city>\n".
    					 				 "<state>".$order->billing['state']."</state>\n".
    					 				 "<zip>".$order->customer['postcode']."</zip>\n".
    					 				 "<country>".$order->billing['country']['iso_code_2']."</country>\n".
    					 				 "<phone>".str_pad(preg_replace("/[^0-9]/","",$order->customer['telephone']),10,"0",STR_PAD_LEFT) ."</phone>\n".
    					 				 "<email>".$order->customer['email_address']."</email>\n".
    					 				// "<ipaddress>174.120.231.150</ipaddress>\n".
										 	 "<ipaddress>".$_SERVER['REMOTE_ADDR']."</ipaddress>\n".	
    					 				 "<account>\n".
    					 				 "<accountNumber>".$_POST['acceptcheckstoday_accountnumber']."</accountNumber>\n".
    					 				 "<routingNumber>".$_POST['acceptcheckstoday_routingnumber']."</routingNumber>\n".
    					 				 "<amount>".number_format($order->info['total'],2,'.','' )."</amount>\n".
    					 				 "<cknum></cknum>\n".
    					 				 "<signature>".$_SESSION["OUTPUT"]."</signature>\n".
    					 				 "</account>\n".
    					 				 "<referenceID>". $_SESSION['customer_id']."</referenceID>\n".
    					 				 "</customer>\n".
  						 				 "<options>\n".
    					 				 "<storeID>".MODULE_PAYMENT_ACCEPTCHECKSTODAY_STOREID."</storeID>\n".
    					 				 "<referenceID>". $insert_id."</referenceID>\n".
    					 				 "<misc1></misc1>\n".
    					 				 "<misc2></misc2>\n".
    					 				 "<misc3></misc3>\n".
    					 				 "<debug>0</debug>\n".
    					 				 "</options>\n".
    					 				 "</transaction>\n";
     unset($_SESSION["OUTPUT"] );
    // important to URLEncode the data
    $xmlstr = urlencode($request); 
     
    // The API URL to send the transaction to
    $postURL = "https://backoffice.acceptcheckstoday.com/api/add/single";
     
    // Initialize CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $postURL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=$xmlstr");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     
    if(($result = curl_exec($ch)) === false) {
		
             $messageStack->add_session('checkout_payment', MODULE_PAYMENT_ACCEPTCHECKSTODAY_NOCOMM, 'error');
      			 zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));
    } else {

    		    $xml = simplexml_load_string($result); // Result is XML format
				    
										
						if( strtolower(sprintf("%s",$xml->status)) == 'approved'  || 
				        strtolower(sprintf("%s",$xml->status)) == 'pending'   ||
								strtolower(sprintf("%s",$xml->status)) == 'entered'   ||
								strtolower(sprintf("%s",$xml->status)) == 'signature'  
				    )
				    {
				 
				       $_SESSION["ACCRES"] = $xml;
				 	     return;
						 
				    }
				    else {
				     $error = sprintf("%s", $xml->error);
						 if( !$error )
						       $error = MODULE_PAYMENT_ACCEPTCHECKSTODAY_DECLINED_MESSAGE;
									 
             $messageStack->add_session('checkout_payment', $error, 'error');
      			 zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', true, false));				 
				 
				    }
       }
    
		}
		
    function after_process() {
	      global $db, $insert_id , $order;
				

	      $message = zen_db_prepare_input('Order #' . $insert_id . "\n\nTransaction Info: ".print_r($_SESSION["ACCRES"],1)."\n\nAccount Holder Info:\n===============\n" . $order->customer['firstname'] . " ".$order->customer['lastname'] ."\n" . $order->customer['street_address'] . "\n" . $order->customer['suburb'] . "\n" . $order->customer['city'] ."\n" .$order->customer['state']."\n".$order->customer['postcode']."\n\n". $order->customer['country']['title']."\n".$order->customer['telephone'] . "\n\nBank Info:\n========\n" . "\n\nAccount Info:\n==========\n" . MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ACCOUNTNUMBER . $HTTP_POST_VARS['acceptcheckstoday_accountnumber'] . "\n" . MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ROUTINGNUMBER . $HTTP_POST_VARS['acceptcheckstoday_routingnumber'] );      
     		zen_mail('STORE_OWNER', MODULE_PAYMENT_ACCEPTCHECKSTODAY_EMAIL, 'AcceptsChecksToday Info for Order #' . $insert_id, $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
				$status = $_SESSION["ACCRES"]->status;
				$tid		= $_SESSION["ACCRES"]->transid;					
        $com = "Transaction Info: Trans ID: $tid\nStatus: $status";
				
				$status = ( strtolower(sprintf("%s",$_SESSION['ACCRES']->status)) == 'approved' ) ? MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_APPROVED : MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_PENDING;
				
        $db->Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (comments, orders_id, orders_status_id, date_added) values ('".zen_db_prepare_input($com)."' , '". (int)$insert_id . "','" . $status. "', now() )");  				
				$db->Execute("update " . TABLE_ORDERS . " set orders_status = $status where orders_id = '" . (int)$insert_id . "'");      
                
				session_unregister('ACCRES');
		
		}		
		

    function get_error() {

      global $HTTP_GET_VARS;
      $error = array('title' => MODULE_PAYMENT_ACCEPTCHECKSTODAY_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));
      return $error;

    }



    function check() {

      global $db;

      if (!isset($this->_check)) {

        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_STATUS'");
        $this->_check = $check_query->RecordCount();

      }

      return $this->_check;

    }



    function install() {

      global $db;

      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable AcceptChecksToday', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_STATUS', 'True', 'Do you want to accept Echeck payments via AcceptChecksToday?', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('User ID', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_USERID', '', 'Enter your UserID here', '6', '0', now())");		      
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Security Key', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_KEY', '', 'Enter your API Security Key here', '6', '0', now())");	
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Version', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_VERSION', '', 'API Version', '6', '0', now())");		      
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Environment', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_ENVIRONMENT', '', 'API Environment', '6', '0', now())");	
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Store ID', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_STOREID', '', 'This should be 1', '6', '0', now())");			
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-Mail Address', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_EMAIL', '', 'Enter your e-mail address where you want to receive AcceptChecksToday Payment information', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_ZONE', '0', 'Enable this payment method for a particular zone. Please note, this payment modual does not work with zones so do not choose one at this time.', '6', '2', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
  		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Pending Order Status', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_PENDING', '0', 'Set the status of orders made with this payment module to this value that are Pending', '6', '0', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");
			$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Approved Order Status', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_APPROVED', '0', 'Set the status of orders made with this payment module to this value that are Approved', '6', '0', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");
    
		//  $db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD (accountholder VARCHAR(64), address VARCHAR(64), address2 VARCHAR(64), phone VARCHAR(32), bank VARCHAR(64), bankcity VARCHAR(64), bankphone VARCHAR(64), checknumber VARCHAR(10), acceptcheckstoday_accountnumber VARCHAR(32), acceptcheckstoday_routingnumber VARCHAR(15))");

    }



    function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key LIKE 'MODULE_PAYMENT_ACCEPTCHECKSTODAY%' ");
    }
		
		function keys() {
		  return array('MODULE_PAYMENT_ACCEPTCHECKSTODAY_STATUS', 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_USERID' , 
			
						 			 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_KEY' , 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_VERSION' , 
									 
									 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_ENVIRONMENT' , 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_STOREID' , 
			             
									 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_EMAIL' , 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_SORT_ORDER'  , 
									 
									 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_ZONE' ,   'MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_PENDING' , 
									 
									 'MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_APPROVED' );
		}


  }

?>
