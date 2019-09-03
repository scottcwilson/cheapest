<?php

/**
 * Ceon Manual Card Configuration Check Warning.
 *
 * @package     ceon_manual_card
 * @author      Conor Kerr <zen-cart.ceon-manual-card@ceon.net>
 * @copyright   Copyright 2006-2012 Ceon
 * @copyright   Portions Copyright 2003-2006 Zen Cart Development Team
 * @link        http://ceon.net/software/business/zen-cart/ceon-manual-card
 * @license     http://www.gnu.org/copyleft/gpl.html   GNU Public License V2.0
 * @version     $Id: class.ceon_manual_cardConfigCheck.php 1036 2012-07-29 11:28:33Z conor $
 */

// {{{ class ceon_manual_cardConfigCheck

/**
 * If the Ceon Manual Card module is installed and enabled, checks if debug mode is enabled, so that the user can
 * be alerted.
 *
 * @package     ceon_manual_card
 * @author      Conor Kerr <zen-cart.ceon-manual-card@ceon.net>
 * @copyright   Copyright 2006-2012 Ceon
 * @copyright   Portions Copyright 2003-2006 Zen Cart Development Team
 * @link        http://ceon.net/software/business/zen-cart/ceon-manual-card
 * @license     http://www.gnu.org/copyleft/gpl.html   GNU Public License V2.0
 */
class ceon_manual_cardConfigCheck extends base
{
	function ceon_manual_cardConfigCheck()
	{
		global $messageStack;
		
		if (defined('CEON_MANUAL_CARD_STATUS') &&
				strtolower(CEON_MANUAL_CARD_STATUS) == 'yes') {
			
			if (defined('CEON_MANUAL_CARD_DEBUGGING_ENABLED') &&
					strtolower(CEON_MANUAL_CARD_DEBUGGING_ENABLED) == 'yes') {
				$messageStack->add('header', 'CEON MANUAL CARD IS IN DEBUG MODE', 'warning');
			}
		}
	}
}

// }}}
 