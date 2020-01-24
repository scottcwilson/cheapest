<?php

/*
*
*
*     Author: Femi Ademosu
*      Email: femi@coderxo.com
*        Web: http://www.coderxo.com
*    Details: AcceptChecksToday IPN Handler for Zencart
*    FileName acceptcheckstoday_ipn.php
*
*
* Please direct bug reports,suggestions or feedback to the http://www.coderxo.com
*                                                                          
* This is a commercial software. Any distribution is strictly prohibited.
*
*/


require('includes/application_top.php');

if(  $_POST['xml'] )
{



			
    $xml = simplexml_load_string($_POST['xml']);		
		
		if( strtolower( sprintf("%s", $xml->status) ) == "approved" ) {
		
		   $oid = sprintf("%d",$xml->referenceID);
			 
			 $tid = sprintf("%s", $xml->transid);
			 
			 $db->Execute("update " . TABLE_ORDERS  . "

                    											 set orders_status = " . MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_APPROVED . "

                    											 where orders_id = '" . $oid . "'");

																					 
																					 																			 
																					 

   		 $comments = 'PAYMENT APPROVED for #$oid. Transaction Id: '.$tid;



  	   $sql_data_array = array('orders_id' => $oid,

                          'orders_status_id' => MODULE_PAYMENT_ACCEPTCHECKSTODAY_ORDER_STATUS_ID_APPROVED ,

                          'date_added' => 'now()',

                          'comments' => zen_db_input($comments),

                          'customer_notified' => false

  												);
													
													
  		 zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
			 
			 
		
		}

}

print function_exists('simplexml_load_string');
