<?php
/**
 * @package cart
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @Version $id: Product Typ Auction 18 2010-06-20 10:30:40Z davewest $
*/

define('TEXT_AUCTION_TAG_LINE', '');
define('TEXT_PRODUCT_NOT_FOUND', 'Sorry, the product was not found.');
define('TEXT_CURRENT_REVIEWS', 'Current Reviews:');
define('TEXT_MORE_INFORMATION', 'For more information, please visit this product\'s <a href="%s" target="_blank">webpage</a>.');
define('TEXT_DATE_ADDED', 'This product was added to our catalog on %s.');
define('TEXT_DATE_AVAILABLE', 'Auction ends on ');
define('TEXT_ALSO_PURCHASED_PRODUCTS', 'Customers who bought this product also purchased...');
define('TEXT_PRODUCT_OPTIONS', '<strong>Please Choose:</strong>');
define('TEXT_PRODUCT_MANUFACTURER', 'Manufactured by: ');
define('TEXT_PRODUCT_WEIGHT', 'Shipping Weight: ');
define('TEXT_PRODUCT_WEIGHT_UNIT', ' lbs');
define('TEXT_PRODUCT_QUANTITY', ' Unit(s) in Stock');
define('TEXT_PRODUCT_MODEL', 'Item ID: ');
define('LOG_IN_TO_BID', 'Log In Here To Place Bid');
define('AUCTION_HIGH_BID', 'Current Highest Bid: ');
define('AUCTION_BID_AMOUNT', 'Bid Amount: ');
define('BUTTON_SUBMIT_BID_ALT', 'Place Bid');
define('CURRENT_BID', 'Minimum Bid Required:');
define('ENTRY_BID_TOO_LOW', 'Your Bid Is Too Low');
define('AUCTION_RESERVE_NOT_MET', '<font color="#ff0000">Reserve not yet met</font>');
define('USER_CURRENT_HIGH_BIDDER', '<font color="#ff0000"><strong>You are the current high bidder!</strong></font>');
define('AUCTION_MINIMUM_INCREASE', 'The minimum bid increase is');
define('BUY_NOW_PRICE', 'Buy Now Price');
define('PRODUCTS_QUANTITY_TO_BUY_NOW', 'Quantity');
define('AUCTION_PREVIOUS_BIDS', 'Previous Bids');
define('AUCTION_BID', 'Bid'); 
define('AUCTION_BIDS', 'Bids');
define('USER_AUCTION_WINNER', '<font color="#ff0000">Congratulations, you are the winning bidder!</font>');
define('WINNER_CHECKOUT', '<font color="#ff0000">Item was added to your Shopping Cart. Please complete the checkout process to close this Auction!</font>');
define('BUTTON_AUCTION_BUY_NOW_ALT', 'Buy It Now!');
define('PREV_NEXT_PRODUCT', 'Product ');
define('PREV_NEXT_FROM', ' from ');
define('IMAGE_BUTTON_PREVIOUS','Previous Item');
define('IMAGE_BUTTON_NEXT','Next Item');
define('IMAGE_BUTTON_RETURN_TO_PRODUCT_LIST','Back to Product List');
define('AUCTION_WINNER','Auction Winner:');
define('AUCTION_RESERVE','No Winner, Reserve was not reached!');
define('AUCTION_WIN_BID','Winning Bid:');
define('AUCTION_BIDDING_OVER','Auction Has Ended');
define('BUTTON_SUBMIT_AUCTION_BID_ALT','Submit Bid');
define('BUTTON_AUCTION_BUY_NOW_ALT','Buy It Now');
define('BUTTON_AUCTION_PAY_NOW_ALT','Pay Now');
define('BUTTON_IMAGE_AUCTION_LOG_IN_ALT', 'Login to Bid for this item!');

define('TEXT_ATTRIBUTES_PRICE_WAS',' [was: ');
define('TEXT_ATTRIBUTE_IS_FREE',' now is: Free]');
define('TEXT_ONETIME_CHARGE_SYMBOL', ' *');
define('TEXT_ONETIME_CHARGE_DESCRIPTION', ' One time charges may apply');
define('TEXT_ATTRIBUTES_QTY_PRICE_HELP_LINK','Quantity Discounts Available');
define('ATTRIBUTES_QTY_PRICE_SYMBOL', zen_image(DIR_WS_TEMPLATE_ICONS . 'icon_status_green.gif', TEXT_ATTRIBUTES_QTY_PRICE_HELP_LINK, 10, 10) . '&nbsp;');

define('EMAIL_SUBJECT', 'You have been outbid on ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear Mr. %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Dear Ms. %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Dear %s' . "\n\n");
// First line of the greeting
define('EMAIL_OUTBID', 'You have been outbid on an auction item in our store. ');
define('EMAIL_SEPARATOR', '--------------------');
define('EMAIL_TEXT', '');
define('EMAIL_TEXT_LINK', 'To view the product and current bid, click on the link:' . "\n" . '%s');


define('EMAIL_CONTACT', 'For help with any of our online services, please email us at: <a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">'. STORE_OWNER_EMAIL_ADDRESS ." </a>\n\n");
define('EMAIL_GV_CLOSURE',"\n" . 'Yours truly,' . "\n" . STORE_OWNER . "\n\n". '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'.HTTP_SERVER ."</a>\n\n");
define('EMAIL_CLOSURE',"\n" . 'Yours truly,' . "\n" . STORE_OWNER . "\n\n". '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'.HTTP_SERVER ."</a>\n\n");
define('TEXT_AUCTION_BIDDER_ID','Bidder ID:');

//help
define('TEXT_AUCTION_HELP_LINK', '&nbsp;Help&nbsp;[?]');
define('HEADING_AUCTION_HELP', 'Auction Help');
define('TEXT_AUCTION_HELP', '<div><b>Reserve Price:</b> Minimum price that the product may sale for. May be the same as Starting Price or not used.</div><br /><div><b>Buy Now Price: </b>Buy Now Price, which allows you to buy an item when you want it, at a known set price.</div><br /><div><b>Minimum Bid Increase:</b> The minimum bid incress for a product. </div><br /><div><b>Start Price:</b> The starting price may be set for the auction. This may be less or the same as a reserve price. </div><br /><div><b>Auction Ends:</b> The date and time the Auction ends. After auction ends, E-mail notifications will be sent to all current bidders. </div><br /><div><b>Current Highest Bid:</b> The current highest bid on the product. If your loged in, and you are the current highest bidder, it will say <strong>You are the current high bidder!</strong></div><br /><div><b>How to bid:</b> Once you find an item you\'re interested in, it\'s easy to place a bid.<ol><li>Carefully review the product listing, and click the Login to Place Bid button on the product page.</li><li>Enter the current bid plus your  bid. If a minimum bid increase is active, you must enter the current bid and at lest the minimum amount.</li><li>Click the Submit button to place bid. </li></ol><p>You\'ll get an email if you have been out bid for a product. And when the auction ends, you\'ll receive another email indicating whether you\'ve won the product or not, with an explanation of what you can do next.</p></div>');
define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');
?>
