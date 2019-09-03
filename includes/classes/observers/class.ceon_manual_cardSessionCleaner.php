<?php

/**
 * Ceon Manual Card Encrypted Card Details Session Cleaner.
 *
 * @package     ceon_manual_card
 * @author      Conor Kerr <zen-cart.ceon-manual-card@ceon.net>
 * @copyright   Copyright 2006-2012 Ceon
 * @copyright   Portions Copyright 2003-2006 Zen Cart Development Team
 * @link        http://ceon.net/software/business/zen-cart/ceon-manual-card
 * @license     http://www.gnu.org/copyleft/gpl.html   GNU Public License V2.0
 * @version     $Id: class.ceon_manual_cardSessionCleaner.php 1036 2012-07-29 11:28:33Z conor $
 */

// {{{ class ceon_manual_cardSessionCleaner

/**
 * Determines if customer has arrived at one of the registered pages from a page outside of the checkout process.
 * If so, any previously entered card information is cleared.
 * 
 * Rationale: Many people are uneasy when they see their card details entered automatically for them, unless they
 * are already in the checkout process, whereupon they prefer to not have to re-type their details when moving
 * between different parts of the process!
 *
 * @package     ceon_manual_card
 * @author      Conor Kerr <zen-cart.ceon-manual-card@ceon.net>
 * @copyright   Copyright 2006-2012 Ceon
 * @copyright   Portions Copyright 2003-2006 Zen Cart Development Team
 * @link        http://ceon.net/software/business/zen-cart/ceon-manual-card
 * @license     http://www.gnu.org/copyleft/gpl.html   GNU Public License V2.0
 */
class ceon_manual_cardSessionCleaner extends base
{
	
	function ceon_manual_cardSessionCleaner()
	{
		global $zco_notifier;
		
		$zco_notifier->attach($this,
			array(
				'NOTIFY_HEADER_START_SHOPPING_CART',
				'NOTIFY_HEADER_START_CHECKOUT_SHIPPING',
				'NOTIFY_HEADER_START_CHECKOUT_SUCCESS'
				)
			);
	}
	
	function update($callingClass, $notifier, $paramsArray)
	{
		if ($notifier == 'NOTIFY_HEADER_START_CHECKOUT_SUCCESS' ||
				!$this->_referredFromCheckoutProcessURI()) {
			// Customer has completed the checkout process so details shouldn't be stored any longer
			if (isset($_SESSION['ceon_manual_card_data_entered'])) {
				// Previous sensitive card details exist. Remove them!
				unset($_SESSION['ceon_manual_card_data_entered']);
			}
			
			if (isset($_SESSION['ceon_manual_card_card_type'])) {
				// Previous card details exist. Remove the item used as a flag to indicate that data exists in the
				// session
				unset($_SESSION['ceon_manual_card_card_type']);
			}
		}
	}
	
	
	// {{{ _referredFromCheckoutProcessURI()

	/**
	 * Checks if Ceon Manual Card is being used on a page that the user has arrived at from another page in the
	 * checkout process.
	 *
	 * @access  protected
	 * @return  boolean   Whether or not the referring URI is from the checkout process.
	 */
	function _referredFromCheckoutProcessURI()
	{
		$referring_uri = getenv('HTTP_REFERER');
		
		if ($referring_uri !== false) {
			$referring_uri = strtolower($referring_uri);
			$referring_uri = str_replace('&amp;', '&', $referring_uri);
			
			// Build a list of the standard Zen Cart checkout process URIs
			$checkout_page_uris = array(
				zen_href_link(FILENAME_SHOPPING_CART, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL', false),
				zen_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false)
				);
			
			// Add checkout URIs for installed third party modules to the list
			if (defined('FILENAME_CHECKOUT')) {
				$checkout_page_uris[] = zen_href_link(FILENAME_CHECKOUT, '', 'SSL', false);
			}
			
			if (defined('FILENAME_FEC_CONFIRMATION')) {
				$checkout_page_uris[] = zen_href_link(FILENAME_FEC_CONFIRMATION, '', 'SSL', false);
			}
			
			if (defined('FILENAME_QUICK_CHECKOUT')) {
				$checkout_page_uris[] = zen_href_link(FILENAME_QUICK_CHECKOUT, '', 'SSL', false);
			}
			
			foreach ($checkout_page_uris as $checkout_page_uri) {
				// Format URI to be tested to the same format the referring URI is in (lowercase and no encoded
				// ampersands)
				$checkout_page_uri = strtolower($checkout_page_uri);
				$checkout_page_uri = str_replace('&amp;', '&', $checkout_page_uri);
				
				// Only concerned with main part of URI
				$checkout_page_uri = preg_replace('|https?://[^/]+|', '', $checkout_page_uri);
				
				if (strpos($referring_uri, $checkout_page_uri) !== false) {
					// Referring URI is a valid checkout URI
					return true;
				}
			}
		}
		
		return false;
	}
	
	// }}}
}

// }}}
 
