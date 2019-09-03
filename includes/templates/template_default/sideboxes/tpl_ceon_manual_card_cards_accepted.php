<?php

/**
 * Ceon Manual Card Cards Accepted Side Box Template.
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
 * @version     $Id: tpl_ceon_manual_card_cards_accepted.php 1038 2012-07-29 18:04:54Z conor $
 */

$content = '';

$content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') .
	'" class="sideBoxContent centeredContent CeonManualCardCardsAcceptedSidebox">' . "\n";

if (strtolower(CEON_MANUAL_CARD_ACCEPT_VISA) == 'yes') {
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/visa.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_VISA, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}

if (strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD) == 'yes' ||
		strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT) == 'yes') {
	if (strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD) == 'yes' &&
			strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD_DEBIT) == 'yes') {
		$alt_text = CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_MASTERCARD . ' / ' .
			CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_MASTERCARD_DEBIT;
	} else if (strtolower(CEON_MANUAL_CARD_ACCEPT_MASTERCARD) == 'yes') {
		$alt_text = CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_MASTERCARD;
	} else {
		$alt_text = CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_MASTERCARD_DEBIT;
	}
	
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/mastercard.png', $alt_text, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}

if (strtolower(CEON_MANUAL_CARD_ACCEPT_VISA_DEBIT) == 'yes') {
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/visa-debit.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_VISA_DEBIT, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}

if (strtolower(CEON_MANUAL_CARD_ACCEPT_MAESTRO) == 'yes') {
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/maestro.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_MAESTRO, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}

if (strtolower(CEON_MANUAL_CARD_ACCEPT_VISA_ELECTRON) == 'yes') {
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/visa-electron.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_VISA_ELECTRON, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}

if (strtolower(CEON_MANUAL_CARD_ACCEPT_AMERICAN_EXPRESS) == 'yes') {
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/american-express.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_AMERICAN_EXPRESS, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}

if (strtolower(CEON_MANUAL_CARD_ACCEPT_DINERS_CLUB) == 'yes') {
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/diners-club.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_DINERS_CLUB, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}

if (strtolower(CEON_MANUAL_CARD_ACCEPT_JCB) == 'yes') {
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/jcb.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_JCB, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}

if (strtolower(CEON_MANUAL_CARD_ACCEPT_LASER) == 'yes') {
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/laser.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_LASER, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}

if (strtolower(CEON_MANUAL_CARD_ACCEPT_DISCOVER) == 'yes') {
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'card-icons/discover.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_DISCOVER, '', '',
		'class="CeonManualCardCardsAcceptedSideboxCardIcon"');
}


if (strtolower(CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_SHOW_POWERED_BY_CEON_GRAPHIC) == 'yes') {
	$content .= '<div>&nbsp;</div>' . '<a href="https://ceon.net/powered-by-ceon/card-payments"' .
		' id="ceon-manual-card-cards-accepted-sidebox-powered-by-ceon" target="_blank">';
	$content .= zen_image(DIR_WS_TEMPLATE_IMAGES  . 'ceon-powered-logo-sidebox.png',
		CEON_MANUAL_CARD_CARDS_ACCEPTED_SIDEBOX_POWERED_BY_CEON, '', '', '') . "</a>\n\n";
}

$content .= '</div>';
