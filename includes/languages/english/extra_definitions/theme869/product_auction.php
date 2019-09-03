<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 The zen-cart developers                           |
// |                                                                      |   
// | http://www.zen-cart.com/index.php                                    |   
// |                                                                      |   
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: 
//
define('PAE_EMAIL_GREET_NONE', 'Dear %s' . "\n\n");
define('PAE_EMAIL_LOST_AUCTION', 'You recently bid on our auction product - %s. The auction for that product is now closed, and unfortunately you were not successful in your bidding.' . "\n\n");
define('PAE_EMAIL_LOST_AUCTION2', 'We thank you for your interest, and hope to see you back at our store soon.' . "\n\n");
define('PAE_EMAIL_CONTACT', 'For help with any of our online services, please email us at: <a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">'. STORE_OWNER_EMAIL_ADDRESS ." </a>\n\n");
define('EMAIL_CLOSURE',"\n" . 'Yours truly,' . "\n" . STORE_OWNER . "\n\n". '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'.HTTP_SERVER ."</a>\n\n");
define('PAE_EMAIL_LOST_AUCTION_RESERVE', 'Please check our auctions frequently for a re-listing of this and other items. ');
define('EMAIL_TEXT_LINK', 'To view the product and current bid, click on the link:' . "\n" . '%s');
// email disclaimer - this disclaimer is seperate from all other email disclaimers
define('PAE_EMAIL_DISCLAIMER_NEW_CUSTOMER', 'This email address was given to us by you or by one of our customers. If you did not place a bid on ' . STORE_NAME . ', or feel that you have received this email in error, please send an email to %s '); 
define('PAE_EMAIL_WON_AUCTION', 'The auction has now closed, and you are the winning bidder - congratulations! Please login and view the product %s in your Shopping Cart. Then complete the purchase of your item.  For this auction item, you have 14 days to complete the Check Out process. Should you not complete the Check Out process within the 14 day period, your bid will be considered null and void and the item will be placed back up for auction.' . "\n\n");
define('PAE_EMAIL_RESERVE_AUCTION', 'You recently bid on our auction product - %s. The auction for that product is now closed. Unfortunately the reserve was not reached and the auction was closed without any winners.  This item maybe re-auctioned in our store soon.' . "\n\n");
define('PAE_EMAIL_SUBJECT', 'Your Auction Bid at ' . STORE_NAME);
define('BUTTON_IMAGE_AUCTION_HELP', 'button_help.png');  
define('BUTTON_AUCTION_HELP_ALT', 'Auction Help');
define('FILENAME_PRODUCT_AUCTION', 'product_auction'); 
define('CURRENT_BID_TEXT', 'Current Bid! ');
define('STARTING_BID_TEXT', 'Starting Bid! ');
define('RESERVE_FRONT_TEXT', 'The item\'s reserve price ');
define('RESERVE_BACK_TEXT', 'has not been met.');
define('AUCTION_TITLE', 'Auction Information');
define('TABLE_HEADING_NEW_AUCTIONS', 'New Auctions');
define('TABLE_HEADING_AUCTION_INDEX', 'Current Auctions for %s');
?>
