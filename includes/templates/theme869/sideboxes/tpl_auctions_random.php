<?php
/**
 * Product Auction Side Box
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_auction_random.php 2.0 2010-02-18  davewest $
 */
  $content = "";
  $auction_box_counter = 0;
  while (!$random_auction_product->EOF) {
    $auction_box_counter++;
    
    $content .= '<div class="sideBoxContent centeredContent">';
	//display image ?
	if (PRODUCT_AUCTION_SIDEBOX_LIST_IMAGE != '0') $content .=  '<a href="' . zen_href_link(zen_get_info_page($random_auction_product->fields["products_id"]), 'cPath=' . zen_get_generated_category_path_rev($random_auction_product->fields["master_categories_id"]) . '&products_id=' . $random_auction_product->fields["products_id"]) . '">' . zen_image(DIR_WS_IMAGES . $random_auction_product->fields['products_image'], $random_auction_product->fields['products_name'], IMAGE_AUCTION_SIDEBOX_PRODUCTS_LISTING_WIDTH, IMAGE_AUCTION_SIDEBOX_PRODUCTS_LISTING_HEIGHT); 
    //display name ?
	if (PRODUCT_AUCTION_SIDEBOX_NAME != '0') $content .= '<br />' . $random_auction_product->fields['products_name'] . '</a>';
   //display product description?	 
        $disp_text = zen_get_products_description($random_auction_product->fields['products_id']);
        $disp_text = zen_clean_html($disp_text);
        $display_products_description = stripslashes(zen_trunc_string($disp_text, PRODUCT_AUCTION_SIDEBOX_DESCRIPTION, '<a href="' . zen_href_link(zen_get_info_page($random_auction_product->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($random_auction_product->fields['master_categories_id']) . '&products_id=' . $random_auction_product->fields['products_id']) . '"> ' . MORE_INFO_TEXT . '</a>'));
    if (PRODUCT_AUCTION_SIDEBOX_DESCRIPTION > '0') $content .= '<div>' . $display_products_description . '</div>';
  //display product price?
		$auction_current_bid = $random_auction_product->fields['products_price'];
	
	if ($random_auction_product->fields['auction_closed'] != "Y"){
	    $lc_price = BOX_AUCTION_BID . $currencies->display_price($auction_current_bid, zen_get_tax_rate($random_auction_product->fields['products_tax_class_id'])) . '<br />';
	} else {
	    $lc_price = BOX_AUCTION_BID_WIN . $currencies->display_price($auction_current_bid, zen_get_tax_rate($random_auction_product->fields['products_tax_class_id'])) . '<br />';
	}
	  
	  if ($random_auction_product->fields['auction_closed'] != "N"){
           $lc_price .= '<b><font color="#FF0000">' . BOX_AUCTION_CLOSED . '</font></b>';   //off
		}else{
		  $lc_price .= '<b><font color="#008C00">' . BOX_AUCTION_OPEN . '</font></b>';  //on
		}    
	if (PRODUCT_AUCTION_SIDEBOX_PRICE <> 0) $content .= '<div>' . $lc_price . '</div>'; //display price?
  //date ending?
     $bid_expire_date = $random_auction_product->fields['bid_expire_date'];


//	 $bid_expire_hour = $random_auction_product->fields['bid_expire_hour'];
// Define your target date here
//	$targetYear  = substr($bid_expire_date,0,4);
//	$targetMonth = substr($bid_expire_date,5,2);
//	$targetDay   = substr($bid_expire_date,8,2);
//	$targetHour  = substr($bid_expire_hour,0,2);
//	$targetMinute= substr($bid_expire_date,12,2);
//	$targetSecond= substr($bid_expire_date,15,2);
// Define date format
//    $sb_dateFormat = "m/d/Y";

$sb_targetDateDisplay = substr($bid_expire_date,5,2) . "/" . substr($bid_expire_date,8,2) . "/" . substr($bid_expire_date,0,4);


//    $targetDate = mktime($targetHour,$targetMinute,$targetSecond,$targetMonth,$targetDay,$targetYear);
//    $sb_targetDateDisplay = date($sb_dateFormat,$targetdate);

    if ($random_auction_product->fields['auction_closed'] != "Y"){
	if (PRODUCT_AUCTION_SIDEBOX_DATE_ADDED <> 0) $content .= '<div>Ending on: ' . $sb_targetDateDisplay . '</div>'; //display date?
    }
		
	$random_auction_product->MoveNextRandom();	
	
//	if (!$random_auction_product->EOF) $content .= '<hr />'; //add hr but not on last product
    $content .= '</div>';
	

		
  }
?>
