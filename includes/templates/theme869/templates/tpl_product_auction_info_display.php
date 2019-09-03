<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=product_info.<br />
 * Displays details of a typical product
 *
 * @package templateSystem
 * @copyright Copyright 2003-2014 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id:  $
 */
 //require(DIR_WS_MODULES . '/debug_blocks/product_info_prices.php');
?>


<?php
session_start();
$secret=md5(uniqid(rand(), true));
$_SESSION['FORM_SECRET'] = $secret;
?>

<div class="centerColumn" id="productAuction">
  <?php require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_CATEGORY_ICON_DISPLAY)); ?>
  <div class="wrapper bot-border"> 
    <!--bof Prev/Next bottom position -->
    <?php if (PRODUCT_INFO_PREVIOUS_NEXT == 2 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
    <?php
    /**
     * display the product previous/next helper
     */
     require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
    <?php } ?>
  </div>
  <div class="tie">
    <div class="tie-indent">
      <div class="page-content"> 
      <!--bof Form start--> 
        <?php echo zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('action')) . 'action=add_product&mod=auction'), 'post', 'enctype="multipart/form-data"'); ?>
<!--eof Form start-->
          
<?php if ($messageStack->size('place_bid') > 0) echo $messageStack->output('place_bid'); ?>


<input type="hidden" name="form_secret" id="form_secret" value="<?php echo $_SESSION['FORM_SECRET'];?>" />
        
        <!--bof Category Icon -->
          
        <?php /*
<?php if ($module_show_categories != 0) {?>
<?php

 // display the category icons
require($template->get_template_dir('/tpl_modules_category_icon_display.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_category_icon_display.php'); } 
<!--eof Category Icon -->
   */ ?>
          
        
      <div class="row">
        <div class="main-image col-xs-12 col-sm-6">
          <div id="fb-root"></div>
          <!--bof Main Product Image -->
          <?php
			  if (zen_not_null($products_image)) {
			?>
          <?php
			/**
			 * display the main product image
			 */
          require($template->get_template_dir('/tpl_modules_main_product_image.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_main_product_image.php'); ?>
          <?php
          }
      ?>
            
          <!--eof Main Product Image--> 
          <!--bof Additional Product Images -->
          <?php

			  require(DIR_WS_MODULES . zen_get_module_directory('additional_images.php'));
			 ?>
          <?php
		      if ($flag_show_product_info_additional_images != 0 && $num_images > 0) {
		      ?>
          <ul id="productAdditionalImages">
            <?php
          require($template->get_template_dir('tpl_columnar_display_li.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_columnar_display_li.php'); ?>
          </ul>
          <?php
			     }
			     ?>
		<!--eof Additional Product Images -->
          <div class="video_desc">
            <div class="row">
            <!--bof  -->
                <?php echo (($flag_show_product_info_youtube == 1 && $products_youtube !='') ? 
                '<div id="productYouTube" class="col-xs-12 col-sm-12">
                <iframe src="http://www.youtube-nocookie.com/embed/' . $products_youtube . '?rel=0&amp;showinfo=0&amp;fs=0" allowfullscreen></iframe></div>' : '') . "\n"; ?>
            <!--eof YouTube -->
<!-- bof winning bid thingy -->
<?php if ($difference == '0') { ?> 

<!--beginning of Product Display code-->
<div class="productAuction AuctionBold"><?php echo AUCTION_BIDDING_OVER; ?></div>
 <?php 
 if ($products_reserve_price <= $auction_current_bid && $customer_high_bidder != 'x') {
         if (AUCTION_INFO_DISPLAY_WIN_BID_NAME == '1') { ?>

<div class="productAuction biggerText"><?php echo AUCTION_WINNER . '&nbsp;&nbsp;' . TEXT_AUCTION_BIDDER_ID . ':&nbsp;' . $last_bid->fields['customers_id']; ?></div>

   <?php }
         if (AUCTION_INFO_DISPLAY_WIN_BID_BID == '1') { ?>

<div class="productAuction"><?php echo AUCTION_WIN_BID . '&nbsp;' . $auction_current_bid_with_currency; ?></div>

   <?php }  
         if ($customer_high_bidder == $_SESSION['customer_id']) { 
          if (AUCTION_INFO_DISPLAY_PAY_NOW == 1) { 
             $the_button = '<input type="hidden" name="cart_quantity" value="1" />' . zen_draw_hidden_field('price', $auction_current_bid) . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_AUCTION_PAY_NOW, BUTTON_AUCTION_PAY_NOW_ALT);
             } else {
             $the_button = USER_AUCTION_WINNER . '<br />' . WINNER_CHECKOUT;
			 }  ?>
<div class="productAuction biggerText"><?php echo $the_button;	?></div>
   <?php } }  else if ($products_reserve_price > $auction_current_bid) {?>
<div class="productAuction biggerText"><?php echo AUCTION_RESERVE; ?></div>
<!-- eof winning bid thingy -->  
<?php }} else { ?> 
<!-- bof still open for biding! -->
<!--beginning of Product Display code-->
<!-- bof countdown--> 

<div class="productAuction  biggerText" id="expire">
<script type="text/javascript">window.onload = user_time();</script>


<br /><br /> 

<?php
if (AUCTION_INFO_DISPLAY_PREVIOUS_BIDS_COUNT == '1')
	if ($previous_bids->RecordCount() == 1) {
		echo $previous_bids->RecordCount() . '&nbsp;' . AUCTION_BID;
	} else {
		echo $previous_bids->RecordCount() . '&nbsp;' . AUCTION_BIDS; 
	} ?>
<br /><br />

<?php if ($auction_clock == 1 ) { //run the clock ?>
<div class="countdown" id="remain">
<?php echo '$remainingDay days, $remainingHour hours, $remainingMinutes minutes, $remainingSeconds seconds';?></div>

<!--eof countdown -->
<?php } ?>
</div>
<div class="productAuction AuctionBold">
<?php if ($auction_start == 1) {

if ($products_reserve_price > $auction_current_bid) { 
//	echo '<font color="#000">'. RESERVE_FRONT_TEXT . $products_reserve_price_with_currency . RESERVE_BACK_TEXT . '</font><br /><br />';

	$one_time = AUCTION_RESERVE_NOT_MET . '<br /><br />';
		//echo 'me testing reserve';
   if (AUCTION_INFO_DISPLAY_RESERVE_NOT_MET == '1') {
//    echo $one_time;
	echo '<font color="#FF0000">'. RESERVE_FRONT_TEXT . RESERVE_BACK_TEXT . '</font><br /><br />';

	}
}
}

	if ($previous_bids->RecordCount() > 0) {
	echo CURRENT_BID_TEXT . $auction_current_bid_with_currency;
		} else {
	echo STARTING_BID_TEXT . $auction_current_bid_with_currency;
	$bid_minimum_increase = 0;
		}

    ?>
</div>

<!--bof Product details list  -->
    <div class="productAuction biggerText"><?php if ($customer_high_bidder == $_SESSION['customer_id']) { echo USER_CURRENT_HIGH_BIDDER; } ?></div>

    <div class="productAuction  biggerText">
    <?php echo ((SHOW_PRODUCT_AUCTION_INFO_MODEL == '1' & $products_model != '') ? TEXT_PRODUCT_MODEL . $products_model : '&nbsp;'); ?><br/>
	<?php echo ((SHOW_PRODUCT_AUCTION_INFO_WEIGHT == '1' and $products_weight !=0) ? TEXT_PRODUCT_WEIGHT .  $products_weight . TEXT_PRODUCT_WEIGHT_UNIT : '&nbsp;'); ?><br/>
    <?php echo ((SHOW_PRODUCT_AUCTION_INFO_QUANTITY == '1') ? $products_quantity . TEXT_PRODUCT_QUANTITY : '&nbsp;'); ?><br/><br/>

<?php if ($previous_bids->RecordCount() > 0) {
     echo '<b>'. AUCTION_MINIMUM_INCREASE . ' ' . $bid_currency_minimum_increase . '</b>';
  }
?>
    </div>
<!--eof Product details list -->


    <div class="productAuction"><?php if (!$_SESSION['customer_id']) {  ?>
	<?php $_SESSION['navigation']->set_snapshot(); echo '<a href="' . zen_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_AUCTION_LOG_IN, 'Sign In to Bid!') . '</a>';  ?>	   
	<?php   } ?>
	</div>
<br class="clearBoth" />

<?php
if (CUSTOMERS_APPROVAL == '2' or TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM == '' or CUSTOMERS_APPROVAL == '3') {
   //echo 'Login!!'; // Log in section do nothing
} else {  
//echo 'Not Logged In!!'; // not login section
?>
<?php
if (($products_buynow_price > $auction_current_bid) && ($products_buynow_price > 0)) {
  if (AUCTION_INFO_DISPLAY_BUY_NOW == '1') { ?>	  

           <div class="productAuction biggerText"><?php echo BUY_NOW_PRICE; ?>&nbsp;&nbsp;<?php echo $currencies->display_price($products_buynow_price,
                      zen_get_tax_rate($product_info->fields['products_tax_class_id'])); ?>&nbsp;
             <?php echo ((SHOW_PRODUCT_INFO_IN_CART_QTY == '1' and $_SESSION['cart']->in_cart($_GET['products_id'])) ? PRODUCTS_ORDER_QTY_TEXT_IN_CART . $_SESSION['cart']->get_quantity($_GET['products_id']) : '&nbsp;'); ?>

            <?php
            if ($products_qty_box_status == '0' or $products_quantity_order_max== '1') {
              // hide the quantity box and default to 1
              $the_button = '<input type="hidden" name="cart_quantity" value="1" />' . zen_draw_hidden_field('price', $products_buynow_price) . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_AUCTION_BUY_NOW, BUTTON_AUCTION_BUY_NOW_ALT);
            } else {
              // show the quantity box
              $the_button = PRODUCTS_QUANTITY_TO_BUY_NOW . '<input type="text" name="cart_quantity" value="' . (zen_get_buy_now_qty($_GET['products_id'])) . '" maxlength="6" size="4" /><br>' . zen_draw_hidden_field('price', $products_buynow_price) . zen_get_products_quantity_min_units_display((int)$_GET['products_id']) . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_AUCTION_BUY_NOW, BUTTON_AUCTION_BUY_NOW_ALT) . '<br/>';
            }
            echo zen_get_buy_now_button_auction($_GET['products_id'], $the_button);
            ?>
           </div>

  <?php } } ?></form>
 <!-- if customer is loged in, show this else not -->
  <?php if ($_SESSION['customer_id']) { ?>
  <?php echo zen_draw_form('place_bid', zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('action')) . 'action=place_bid',$request_type), 'post', 'onsubmit="return SubmitOrderButton();"') . zen_draw_hidden_field('action', 'place_bid') . zen_draw_hidden_field('form_secret', $_SESSION['FORM_SECRET'], 'id="form_secret"'); ?>
  <?php echo zen_draw_hidden_field('auction_price', $products_auction_price); ?>
  <?php echo zen_draw_hidden_field('auction_current_bid', $auction_current_bid); ?>
  <?php if ($customer_high_bidder != $_SESSION['customer_id']) { ?> 
		
	<div id="submitmain" align="center" class="productGeneral biggerText">
		<?php echo CURRENT_BID . ' ';
		if ($previous_bids->RecordCount() == 0) {
			echo $auction_current_bid_with_currency . '<br/>';
		} else {
			$auction_minimum_bid = $auction_current_bid+$bid_minimum_increase;
			$auction_minimum_bid_currency = $currencies->display_price($auction_minimum_bid);
			echo $auction_minimum_bid_currency . '<br/>';
		}
	?>


		<?php echo AUCTION_BID_AMOUNT; ?><br/>
		  <?php echo zen_draw_input_field('auction_bid_amount', '', zen_set_field_length(TABLE_PRODUCTS_AUCTION, 'customers_bid', '30')); ?><br/>
		  <?php echo zen_image_submit(BUTTON_IMAGE_SUBMIT_AUCTION_BID, BUTTON_SUBMIT_AUCTION_BID_ALT); ?></div>
<div id="pleasewait" class="buttonRow forward" style="display:none"><?php echo zen_image(DIR_WS_TEMPLATE_IMAGES .'hammer.gif', 'Please Wait') . '<br /><strong>Please wait your bid is processing</strong>';?></div>
  <?php }  ?>
  <?php }  ?>
<?php } // CUSTOMERS_APPROVAL == '3' ?>

<br class="clearBoth" />
<?php //if ($_SESSION['customer_id']) {
if (AUCTION_INFO_DISPLAY_PREVIOUS_BIDS == '1') {
 if ($auction_info->RecordCount() >0) {
 ?>

<div><br/><b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo AUCTION_PREVIOUS_BIDS; ?></b></div>

    <?php  while (!$previous_bids->EOF) { ?>

        <div><?php echo $currencies->display_price($previous_bids->fields['customers_bid'], zen_get_tax_rate($product_info->fields['products_tax_class_id'])); ?>&nbsp;-&nbsp;<?php echo TEXT_AUCTION_BIDDER_ID ?> &nbsp;&nbsp;<?php echo $previous_bids->fields['customers_id']; ?></div>

    <?php $previous_bids->MoveNext(); } ?>

<?php } } //} ?>
<br/><br/>

  <?php }  ?>			
			
			
            <!--bof Product description -->
            <?php if ($products_description != '') { ?>
            <div id="productDescription" class="description biggerText col-sm-12 col-xs-12<?php if ($flag_show_product_info_youtube == 1 && $products_youtube !=''){?> col-sm-12 <?php } ?>"><?php echo stripslashes($products_description); ?></div>
 <div class="productGeneral  biggerText"><?php echo TEXT_AUCTION_TAG_LINE; ?></div>
<div class="forward">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo '<a href="javascript:popupWindow(\'' . zen_href_link(FILENAME_POPUP_AUCTION_HELP) . '\')">' . zen_image_button(BUTTON_IMAGE_AUCTION_HELP, BUTTON_AUCTION_HELP_ALT) . '</a>'; ?></div>  

 <?php } ?>
            <!--eof Product description --> 
            </div>
       </div>
		</div>
          <div class="pb-center-column col-xs-12 col-sm-6"> 
            <!--bof free ship icon  -->
            <?php if(zen_get_product_is_always_free_shipping($products_id_current) && $flag_show_product_info_free_shipping) { ?>
            <div id="freeShippingIcon"><?php echo TEXT_PRODUCT_FREE_SHIPPING_ICON; ?></div>
            <?php } ?>
            <!--eof free ship icon  -->
            <h2 class="title_product"><?php echo $products_name; ?></h2>
            <h3 class="sub_title"><?php echo $products_model; ?> </h3>
            <!--bof Product description -->
            <?php if ($products_description != '') { ?>
            <div id="shortDescription" class="description"><?php echo substr(strip_tags($products_description), 0, 300) . '...' .''; ?></div>
            <?php } ?>
            <!--eof Product description -->
            <!--bof Product details list  -->
			<?php if ( (($flag_show_product_info_model == 1 and $products_model != '') or ($flag_show_product_info_weight == 1 and $products_weight !=0) or ($flag_show_product_info_quantity == 1) or ($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name))) ) { ?>
            <ul class="instock">
              <?php echo (($flag_show_product_info_weight == 1 and $products_weight !=0) ? '<li>' . '<strong>'.TEXT_PRODUCT_WEIGHT.'</strong>' .  $products_weight . TEXT_PRODUCT_WEIGHT_UNIT . '</li>'  : '') . "\n"; ?> <?php echo (($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name)) ? '<li>' . '<strong>'.TEXT_PRODUCT_MANUFACTURER.'</strong>' . $manufacturers_name . '</li>' : '') . "\n"; ?>
            </ul>
            <?php
              }
            ?>
            <!--eof Product details list  --> 
            <div class="wrapper atrib"> <span class="quantity_label"><?php echo $products_quantity.TEXT_PRODUCT_QUANTITY; ?></span>
             
            </div>
            <div class="wrapper atrib2"> 
              <!--bof Attributes Module -->
              <?php
                  if ($pr_attr->fields['total'] > 0) {
                ?>
                              <?php
                /**
                 * display the product atributes
                 */
                  require($template->get_template_dir('/tpl_modules_attributes.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_attributes.php'); ?>
                              <?php
                  }
                ?>
              <!--eof Attributes Module --> 
              
            <div class="add_to_cart_block"> 
              <!--bof Add to Cart Box -->
              <?php
	if (CUSTOMERS_APPROVAL == 3 and TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM == '') {
	  // do nothing
	} else {
	?>
              <?php
				$display_qty = (($flag_show_product_info_in_cart_qty == 1 and $_SESSION['cart']->in_cart($_GET['products_id'])) ? '<p>' . PRODUCTS_ORDER_QTY_TEXT_IN_CART . $_SESSION['cart']->get_quantity($_GET['products_id']) . '</p>' : '');
				if ($products_qty_box_status == 0 or $products_quantity_order_max== 1) {
				  // hide the quantity box and default to 1
				  $the_button = '<input type="hidden" name="cart_quantity" value="1" />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . '<span class="fright">'.zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT).'</span>';
				} else {
				  // show the quantity box
		$the_button = '<div class="add_to_cart_row"><strong class="fleft text2"><input type="text" class="form-control" name="cart_quantity" value="' . (zen_get_buy_now_qty($_GET['products_id'])) . '" maxlength="6" size="8" />' . zen_get_products_quantity_min_units_display((int)$_GET['products_id']) . zen_draw_hidden_field('products_id', (int)$_GET['products_id']).'</strong>' . '<span class="buttonRow">'.zen_image_submit('', BUTTON_IN_CART_ALT).'</span></div>';
				}
		$display_button = zen_get_buy_now_button($_GET['products_id'], $the_button);
	  ?>
              <?php if ($display_qty != '' or $display_button != '') { ?>
              <div id="prod-price">
                <?php
        	// base price
        	  if ($show_onetime_charges_description == 'true') {
        		$one_time = '<span >' . TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION . '</span>';
        	  } else {
        		$one_time = '';
        	  }
        	  echo $one_time . ((zen_has_product_attributes_values((int)$_GET['products_id']) and $flag_show_product_info_starting_at == 1) ? '<span class="price-text">'.TEXT_BASE_PRICE.'</span>' : '') . zen_get_products_display_price((int)$_GET['products_id']);
		      ?>
              </div>
              <div class="clearfix"></div>
              
              <?php } // display qty and button ?>
              <?php } // CUSTOMERS_APPROVAL == 3 ?>
              <!--eof Add to Cart Box--> 
            </div>
            </div>
            <div id="button_product">
                <?php 
			  echo $display_button;
		      echo $display_qty;
			  ?>
              </div>
              <!-- bof Social Media Icons -->
              <?php if(TM_SOCIAL_BLOCK_STATUS == 'true') { ?> 
                <ul id="social">
                  <?php if(TM_SOCIAL_BLOCK_STATUS_FB == 'true') { ?> 
                  <li class="facebook"><fb:like send="false" layout="button_count" width="150" show_faces="false"></fb:like></li>
                  <?php } ?>
                  <?php if(TM_SOCIAL_BLOCK_STATUS_PN == 'true') { ?> 
                  <li class="pinterest"><a href="http://pinterest.com/pin/create/button/?url='.  urlencode(zen_href_link(zen_get_info_page($_GET['products_id']), 'cPath=' . $_GET['cPath'] . '&amp;products_id=' . $_GET['products_id']) ).'&amp;media=' .  urlencode(HTTP_SERVER . DIR_WS_CATALOG . $products_img) . '&amp;description=' .  rawurlencode($products_name) . '" class="pin-it-button"><img src="//assets.pinterest.com/images/PinExt.png" title="Pin It" alt=""></a></li>
                  <?php } ?>
                  <?php if(TM_SOCIAL_BLOCK_STATUS_TW == 'true') { ?> 
                  <li class="twitter"><a href="https://twitter.com/share" class="twitter-share-button fleft">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
                  <?php } ?>
                  <?php if(TM_SOCIAL_BLOCK_STATUS_GO == 'true') { ?> 
                  <li class="google"><div class="g-plusone" data-size="medium"></div></li>
                  <?php } ?>
                </ul>
              <?php } ?>
              <!-- eof Social Media Icons -->
          </div>
        </div>
        <!--bof Quantity Discounts table -->
        <?php
  if ($products_discount_type != 0) { ?>
        <?php
/**
 * display the products quantity discount
 */
 require($template->get_template_dir('/tpl_modules_products_quantity_discounts.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_products_quantity_discounts.php'); ?>
        <?php
  }
?>
        <!--eof Quantity Discounts table --> 
        
        <!--bof also related products module-->
        
        <?php require($template->get_template_dir('tpl_modules_related_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_related_products.php');?>
        
        <!--eof also related products module--> 
        <br />
        <div class="text2">
          <p class="reviewCount"><strong><?php echo ($flag_show_product_info_reviews_count == 1 ? TEXT_CURRENT_REVIEWS . ' ' . $reviews->fields['count'] : ''); ?></strong></p>
          
          <!--bof Product date added/available-->
          <?php
  if ($products_date_available > date('Y-m-d H:i:s')) {
    if ($flag_show_product_info_date_available == 1) {
?>
          <p id="productDateAvailable" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_AVAILABLE, zen_date_long($products_date_available)); ?></p>
          <?php
    }
  } else {
    if ($flag_show_product_info_date_added == 1) {
?>
          <p id="productDateAdded" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_ADDED, zen_date_long($products_date_added)); ?></p>
          <?php
    } // $flag_show_product_info_date_added
  }
?>
          <!--eof Product date added/available --> 
          
          <!--bof Product URL -->
          <?php
  if (zen_not_null($products_url)) {
    if ($flag_show_product_info_url == 1) {
?>
          <p id="productInfoLink" class="productGeneral centeredContent"><?php echo sprintf(TEXT_MORE_INFORMATION, zen_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($products_url), 'NONSSL', true, false)); ?></p>
          <?php
    } // $flag_show_product_info_url
  }
?>
          <!--eof Product URL --> 
          
        </div>
       
        <!--bof also purchased products module-->
        <?php require($template->get_template_dir('tpl_modules_also_purchased_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_also_purchased_products.php');?>
        <!--eof also purchased products module--> 
        
        <!--bof Form close-->
        </form>
        <!--bof Form close--> 
        
      </div>
    </div>
  </div>
</div>