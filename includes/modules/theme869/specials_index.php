<?php
/**
 * specials_index module
 *
 * @package modules
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: specials_index.php 6424 2007-05-31 05:59:21Z ajeh $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

// initialize vars
$categories_products_id_list = '';
$list_of_products = '';
$specials_index_query = '';
$display_limit = '';

if ( (($manufacturers_id > 0 && $_GET['filter_id'] == 0) || $_GET['music_genre_id'] > 0 || $_GET['record_company_id'] > 0) || (!isset($new_products_category_id) || $new_products_category_id == '0') ) {
  $specials_index_query = "select p.products_id, p.products_image, pd.products_name, p.master_categories_id, pd.products_description
                           from (" . TABLE_PRODUCTS . " p
                           left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id
                           left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                           where p.products_id = s.products_id
                           and p.products_id = pd.products_id
                           and p.products_status = '1' and s.status = 1
                           and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
} else {
  // get all products and cPaths in this subcat tree
  $productsInCategory = zen_get_categories_products_list( (($manufacturers_id > 0 && $_GET['filter_id'] > 0) ? zen_get_generated_category_path_rev($_GET['filter_id']) : $cPath), false, true, 0, $display_limit);

  if (is_array($productsInCategory) && sizeof($productsInCategory) > 0) {
    // build products-list string to insert into SQL query
    foreach($productsInCategory as $key => $value) {
      $list_of_products .= $key . ', ';
    }
    $list_of_products = substr($list_of_products, 0, -2); // remove trailing comma
    $specials_index_query = "select distinct p.products_id, p.products_image, pd.products_name, p.master_categories_id, pd.products_description
                             from (" . TABLE_PRODUCTS . " p
                             left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id
                             left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                             where p.products_id = s.products_id
                             and p.products_id = pd.products_id
                             and p.products_status = '1' and s.status = '1'
                             and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                             and p.products_id in (" . $list_of_products . ")";
  }
}
if ($specials_index_query != '') $specials_index = $db->ExecuteRandomMulti($specials_index_query, MAX_DISPLAY_SPECIAL_PRODUCTS_INDEX);

$row = 0;
$col = 0;
$list_box_contents = array();
$title = '';

$num_products_count = ($specials_index_query == '') ? 0 : $specials_index->RecordCount();

// show only when 1 or more
if ($num_products_count > 0) {
  if ($num_products_count < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS || SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS == 0 ) {
    $col_width = floor(100/$num_products_count);
  } else {
    $col_width = floor(100/SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS);
  }

  $list_box_contents = array();
  while (!$specials_index->EOF) {
  
  
  
    if (!isset($productsInCategory[$specials_index->fields['products_id']])) $productsInCategory[$specials_index->fields['products_id']] = zen_get_generated_category_path_rev($specials_index->fields['master_categories_id']);
	
	
    $products_img = (($specials_index->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<a href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'cPath=' . $productsInCategory[$specials_index->fields['products_id']] . '&products_id=' . (int)$specials_index->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $specials_index->fields['products_image'], $specials_index->fields['products_name'], MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT) . '</a>');
    
	$products_name = '<a class="product-name name" href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'cPath=' . $productsInCategory[$specials_index->fields['products_id']] . '&products_id=' . $specials_index->fields['products_id']) . '">' . substr(strip_tags($specials_index->fields['products_name']), 0, 50) . '</a>';
    
	$products_desc = substr(strip_tags($specials_index->fields['products_description']), 0, 60) . '...';
	
    $products_price = '<strong>' . zen_get_products_display_price($specials_index->fields['products_id']) . '</strong>';
	
	// $products_butt = '<a class="btn products-button" href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'cPath=' . $productsInCategory[$specials_index->fields['products_id']] . '&products_id=' . (int)$specials_index->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a>';
	
	// $products_butt2 = '<a class="btn add-to-cart" href="' . zen_href_link(FILENAME_SPECIALS, zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials_index->fields["products_id"]) . '">' . zen_image_button(BUTTON_IMAGE_ADD_TO_CART, BUTTON_IN_CART_ALT) . '</a>';

	
    $img_col_w = SMALL_IMAGE_WIDTH + 43;

	
	
	
	if (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS > 1 && $num_products_count > 1) {
	
		if ($col > 10 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS) {
			$tm_param = ' i12';		
		
		} elseif ($col > 9 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i11';
				
		} elseif ($col > 8 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i10';

		} elseif ($col > 7 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i9';
	
		} elseif ($col > 6 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i8';
			
		} elseif ($col > 5 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i7';

		} elseif ($col > 4 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i6';

		} elseif ($col > 3 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i5';

		} elseif ($col > 2 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i4';
			
		} elseif ($col > 1 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i3';
			
		} elseif ($col > 0 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS){
			$tm_param = ' i2';
		}			
		 else {
			$tm_param = ' i1';
		}
	
    $list_box_contents[$row][$col] = array('params' =>'class="centerBoxContentsFeatured centeredContent back '. $tm_param . '"' . ' ' . 'style="width:' . $col_width . '%;"' ,
    'text' => 
	
			'<div data-match-height="items-h" class="product-col">
				<div class="img">
					' . $products_img . '
				</div>
				<div class="prod-info">
					<h5>' . $products_name . '</h5>
				
					<div class="text">
						' . $products_desc . '
					</div>
						<div class="price">
							' . str_replace('&nbsp;', '', $products_price) . '
						</div>
						<div class="product-buttons">
							<div class="button">' . $products_butt2 . '</div>
							<div class="button1">' . $products_butt . '</div>
						</div>
				</div>
			</div>'
					
			);
	
	} else {

    $list_box_contents[$row][$col] = array('params' =>'class="item col-xs-4 col-lg-4 col-products"',
    'text' => 
	
			'<div data-match-height="items-h" class="product-col">
				<div class="img">
					' . $products_img . '
				</div>
				<div class="prod-info">
					<h5>' . $products_name . '</h5>
				
					<div class="text">
						' . $products_desc . '
					</div>
						<div class="price">
							' . str_replace('&nbsp;', '', $products_price) . '
						</div>
				</div>
			</div>'
					
			);
	
	}

    $col ++;
    if ($col > (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS - 1)) {
      $col = 0;
      $row ++;
    }
    $specials_index->MoveNextRandom();
  }

  if ($specials_index->RecordCount() > 0) {
    $title = '<h2 class="centerBoxHeading">' . sprintf(TABLE_HEADING_SPECIALS_INDEX, strftime('%B')) . '</h2>';
    $zc_show_specials = true;
  }
}
?>
