<?php
/**
 * product_listing module
 *
 * @package modules
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: product_listing.php 18695 2011-05-04 05:24:19Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
$show_submit = zen_run_normal();
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_PRODUCTS_LISTING, 'p.products_id', 'page');
$zco_notifier->notify('NOTIFY_MODULE_PRODUCT_LISTING_RESULTCOUNT', $listing_split->number_of_rows);
$how_many = 0;

$list_box_contents[0] = array('params' => 'class="productListing-rowheading"');

$zc_col_count_description = 0;
$lc_align = '';
for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
  switch ($column_list[$col]) {
    case 'PRODUCT_LIST_MODEL':
    $lc_text = TABLE_HEADING_MODEL;
    $lc_align = '';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_NAME':
    $lc_text = TABLE_HEADING_PRODUCTS;
    $lc_align = '';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_MANUFACTURER':
    $lc_text = TABLE_HEADING_MANUFACTURER;
    $lc_align = '';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_PRICE':
    $lc_text = TABLE_HEADING_PRICE;
    $lc_align = 'right' . (PRODUCTS_LIST_PRICE_WIDTH > 0 ? '" width="' . PRODUCTS_LIST_PRICE_WIDTH : '');
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_QUANTITY':
    $lc_text = TABLE_HEADING_QUANTITY;
    $lc_align = 'right';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_WEIGHT':
    $lc_text = TABLE_HEADING_WEIGHT;
    $lc_align = 'right';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_IMAGE':
    $lc_text = TABLE_HEADING_IMAGE;
    $lc_align = 'center';
    $zc_col_count_description++;
    break;
  }

  if ( ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
    $lc_text = zen_create_sort_heading($_GET['sort'], $col+1, $lc_text);
  }



  $list_box_contents[0][$col] = array('align' => $lc_align,
                                      'params' => 'class="productListing-heading"',
                                      'text' => $lc_text );
}

/////////////  HEADER ROW ABOVE /////////////////////////////////////////////////

$num_products_count = $listing_split->number_of_rows;
if ($listing_split->number_of_rows > 0) {
  $rows = 0;
  $listing = $db->Execute($listing_split->sql_query);
  $extra_row = 0;
  while (!$listing->EOF) {
    $rows++;

    if ((($rows-$extra_row)/2) == floor(($rows-$extra_row)/2)) {
      $list_box_contents[$rows] = array('params' => 'class="productListing-even"');
    } else {
      $list_box_contents[$rows] = array('params' => 'class="productListing-odd"');
    }

    $cur_row = sizeof($list_box_contents) - 1;

    for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
      $lc_align = '';
      switch ($column_list[$col]) {
        case 'PRODUCT_LIST_MODEL':
        $lc_align = '';
        $lc_text = $listing->fields['products_model'];
        break;
        case 'PRODUCT_LIST_NAME':
        $lc_align = '';
        $lc_text = '<h5 itemprop="name">
                        <a class="product-name name" href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id'] > 0) ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id']))) . '&products_id=' . $listing->fields['products_id']) . '">' . $listing->fields['products_name'] . '</a>
                    </h5>
                    <div class="text">
						<span class="list-desc">' . zen_trunc_string(zen_clean_html(stripslashes(zen_get_products_description($listing->fields['products_id'], $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION) . '</span>
						<span class="grid-desc">' . zen_trunc_string(substr(strip_tags(zen_clean_html(stripslashes(zen_get_products_description($listing->fields['products_id'], $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION), 0, 80 ). '...') . '</span>
						</div>';
        break;
        case 'PRODUCT_LIST_MANUFACTURER':
        $lc_align = '';
        $lc_text = '<a href="' . zen_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing->fields['manufacturers_id']) . '">' . $listing->fields['manufacturers_name'] . '</a>';
        break;
        case 'PRODUCT_LIST_PRICE':
        $lc_price = '<span itemprop="price" class="price product-price">'.zen_get_products_display_price($listing->fields['products_id']) . '</span><div class="clearfix"></div>';
	$sql = "select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . "
 
		        where categories_id ='" . (int)$listing->fields['master_categories_id'] . "'";

        $catname = $db->Execute($sql);

		if ($catname->fields['categories_name'] != 'Auctions' ) {

		//show for products

		$lc_price = zen_get_products_display_price($listing->fields['products_id']) . '<br />';

      		$lc_text =  $lc_price;
		}else{

		//show for auction

		$sql2 = "select customers_bid, customers_id from  " . TABLE_PRODUCTS_AUCTION . "

			 where products_id = '" . $listing->fields['products_id'] . "'" . "
			 order by customers_bid DESC";

    $auction_bid = $db->Execute($sql2);
    $customer_high_bid = ($auction_bid->fields['customers_id']);

	
	  $auction_query = "select bid_start_price, auction_closed, auction_start, bid_expire_date from " . TABLE_PRODUCT_AUCTION_EXTRA . "

                            where products_id = '" . $listing->fields['products_id'] . "'";

        $auction = $db->Execute($auction_query);


		$auction_current_bid = ($auction_bid->fields['customers_bid'] >= 1) ? $auction_bid->fields['customers_bid'] : $auction->fields['bid_start_price'];

		$auction_start = ($auction->fields['auction_start']);
		$auction_closed = ($auction->fields['auction_closed']);
		$bid_expire_date = ($auction->fields['bid_expire_date']);

$exp_DateDisplay = substr($bid_expire_date,5,2) . "/" . substr($bid_expire_date,8,2) . "/" . substr($bid_expire_date,0,4);


    if ($auction_closed == "N") {
	if ($auction_start == '1') {
	    $lc_price = 'Current Bid <br />'. $currencies->format($auction_current_bid) . '<br />';

	} else {
	    $lc_price = 'Starting Bid <br />'. $currencies->format($auction_current_bid) . '<br />';

	}

		if ($_SESSION['customer_id'] <> "" &&  $customer_high_bid == $_SESSION['customer_id']) {
		$lc_text = $lc_price . '<b><font color="#008C00">' . "Auction Open!" . '</font></b><br />' . '<b><i><font color="#1462FF">' . "You are the current high bidder" . '</font></i></b>';

		} else {
		$lc_text = $lc_price . '<b><font color="#008C00">' . "Auction Open!" . '</font></b><br />' . "Ending on " . $exp_DateDisplay;
		}

    } else {

  	$lc_price = 'Winning Bid <br />'. $currencies->format($auction_current_bid) . '<br />';

	$lc_text = $lc_price . '<b><font color="#FF0000">' . "Auction Closed!" . '</font></b>';
    }


	}

        $lc_align = 'right';
        $lc_text =  $lc_price;

        // more info in place of buy now

				
        $the_button = '<div class="button"><a class="btn add-to-cart" href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_ADD_TO_CART, BUTTON_IN_CART_ALT) . '</a></div><div class="button1"><a class="btn" href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id'] > 0) ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id']))) . '&products_id=' . $listing->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a></div>';
        
		$products_link = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . ( ($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ? zen_get_generated_category_path_rev($_GET['filter_id']) : $_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id'])) . '&products_id=' . $listing->fields['products_id']) . '">' . MORE_INFO_TEXT . '</a>';
       
	    $lc_text .= '' . zen_get_buy_now_button($listing->fields['products_id'], $the_button, $products_link) . '<br />' . zen_get_products_quantity_min_units_display($listing->fields['products_id']);
       

        break;
        case 'PRODUCT_LIST_QUANTITY':
        $lc_align = 'right';
        $lc_text = $listing->fields['products_quantity'];
        break;
        case 'PRODUCT_LIST_WEIGHT':
        $lc_align = 'right';
        $lc_text = $listing->fields['products_weight'];
        break;
        case 'PRODUCT_LIST_IMAGE':
        $lc_align = 'center';
        if ($listing->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) {
          $lc_text = '';
        } else {
          if (isset($_GET['manufacturers_id'])) {
            $lc_text = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id']))) . '&products_id=' . $listing->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $listing->fields['products_image'], $listing->fields['products_name'], IMAGE_PRODUCT_LISTING_WIDTH, IMAGE_PRODUCT_LISTING_HEIGHT, 'class="listingProductImage"') . '</a>';
          } else {
            $lc_text = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id']))) . '&products_id=' . $listing->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $listing->fields['products_image'], $listing->fields['products_name'], IMAGE_PRODUCT_LISTING_WIDTH, IMAGE_PRODUCT_LISTING_HEIGHT, 'class="listingProductImage"') . '</a>';
          }
        }
        break;
      }

      $list_box_contents[$rows][$col] = array('align' => $lc_align,
                                              'params' => 'class="productListing-data"',
                                              'text'  => $lc_text);
    }

    // add description and match alternating colors
    //if (PRODUCT_LIST_DESCRIPTION > 0) {
    //  $rows++;
    //  if ($extra_row == 1) {
    //    $list_box_description = "productListing-data-description-even";
    //    $extra_row=0;
    //  } else {
    //    $list_box_description = "productListing-data-description-odd";
    //    $extra_row=1;
    //  }
    //  $list_box_contents[$rows][] = array('params' => 'class="' . $list_box_description . '" colspan="' . $zc_col_count_description . '"',
    //  'text' => zen_trunc_string(zen_clean_html(stripslashes(zen_get_products_description($listing->fields['products_id'], $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION));
    //}
    $listing->MoveNext();
  }
  $error_categories = false;
} else {
  $list_box_contents = array();

  $list_box_contents[0] = array('params' => 'class="productListing-odd"');
  $list_box_contents[0][] = array('params' => 'class="productListing-data"',
                                              'text' => TEXT_NO_PRODUCTS);

  $error_categories = true;
}

if (($how_many > 0 and $show_submit == true and $listing_split->number_of_rows > 0) and (PRODUCT_LISTING_MULTIPLE_ADD_TO_CART == 1 or  PRODUCT_LISTING_MULTIPLE_ADD_TO_CART == 3) ) {
  $show_top_submit_button = true;
} else {
  $show_top_submit_button = false;
}
if (($how_many > 0 and $show_submit == true and $listing_split->number_of_rows > 0) and (PRODUCT_LISTING_MULTIPLE_ADD_TO_CART >= 2) ) {
  $show_bottom_submit_button = true;
} else {
  $show_bottom_submit_button = false;
}



  if ($how_many > 0 && PRODUCT_LISTING_MULTIPLE_ADD_TO_CART != 0 and $show_submit == true and $listing_split->number_of_rows > 0) {
  // bof: multiple products
    echo zen_draw_form('multiple_products_cart_quantity', zen_href_link(FILENAME_DEFAULT, zen_get_all_get_params(array('action')) . 'action=multiple_products_add_product'), 'post', 'enctype="multipart/form-data"');
  }

