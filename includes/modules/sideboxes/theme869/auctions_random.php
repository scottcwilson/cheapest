<?php
/**
 * auction sidebox - displays a random auction Product
 *
 * @package cart
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @Version $id: Product Typ Auction 18 2010-06-20 10:30:40Z davewest $
 */

// test if box should display
  $show_auction= true;

  if ($show_auction == true) {
    $random_auction_products_query = "select p.products_id, p.products_image, pd.products_name, pd.products_description,
	                        exa.bid_start_price, exa.auction_closed, exa.bid_expire_date,
                            p.master_categories_id, p.products_price, p.products_tax_class_id
                           from (" . TABLE_PRODUCTS . " p
                           left join " . TABLE_PRODUCT_AUCTION_EXTRA . " exa on p.products_id = exa.products_id
                           left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                           where p.products_id = exa.products_id
                           and p.products_id = pd.products_id
                           and p.products_status = 1
                           and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";

    // randomly select ONE auction product from the list retrieved:
    //$random_auction_product = zen_random_select($random_auction_products_query);
    $random_auction_product = $db->ExecuteRandomMulti($random_auction_products_query, MAX_AUCTION_SIDEBOX_PRODUCTS); //
		
    if ($random_auction_product->RecordCount() > 0)  {
      
      $title =  BOX_HEADING_AUCTIONS;
      $title_link = false;
	  require($template->get_template_dir('tpl_auctions_random.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_auctions_random.php');
      require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);
    }
  }
?>