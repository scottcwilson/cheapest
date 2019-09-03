<?php

/**
 * Ceon Manual Card Cards Accepted Sidebox.
 *
 * Displays icons for card types accepted by this store through the Ceon Manual Card payment module.
 *
 * @package     ceon_manual_card
 * @author      Conor Kerr <zen-cart.ceon-manual-card@ceon.net>
 * @copyright   Copyright 2006-2012 Ceon
 * @copyright   Portions Copyright 2003-2006 Zen Cart Development Team
 * @copyright   Portions Copyright 2003 osCommerce
 * @link        http://ceon.net/software/business/zen-cart/ceon-manual-card
 * @license     http://www.gnu.org/copyleft/gpl.html   GNU Public License V2.0
 * @version     $Id: ceon_manual_card_cards_accepted.php 1038 2012-07-29 18:04:54Z conor $
 */

// Only show sidebox if module is installed and enabled (Enabled/disabled status only - checks are not made for a
// valid configuration).
if (defined('CEON_MANUAL_CARD_STATUS') && strtolower(CEON_MANUAL_CARD_STATUS) == 'yes') {
	
	require_once($template->get_template_dir('tpl_ceon_manual_card_cards_accepted.php', DIR_WS_TEMPLATE,
		$current_page_base, 'sideboxes'). '/tpl_ceon_manual_card_cards_accepted.php');
	
	$title = CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_CARDS_ACCEPTED;
	$title_link = false;
	
	require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base, 'common') . '/' .
		$column_box_default);
}
