<?php

/**
 * Ceon Manual Card Encrypted Card Details Session Cleaner Auto Loader.
 *
 * @package     ceon_manual_card
 * @author      Conor Kerr <zen-cart.ceon-manual-card@ceon.net>
 * @copyright   Copyright 2006-2012 Ceon
 * @copyright   Portions Copyright 2003-2006 Zen Cart Development Team
 * @link        http://ceon.net/software/business/zen-cart/ceon-manual-card
 * @license     http://www.gnu.org/copyleft/gpl.html   GNU Public License V2.0
 * @version     $Id: config.ceon_manual_cardSessionCleaner.php 1036 2012-07-29 11:28:33Z conor $
 */

$autoLoadConfig[200][] = array(
	'autoType' => 'class',
	'loadFile' => 'observers/class.ceon_manual_cardSessionCleaner.php'
	);
	
$autoLoadConfig[200][] = array(
	'autoType' => 'classInstantiate',
	'className' => 'ceon_manual_cardSessionCleaner',
	'objectName' => 'ceon_manual_card_session_cleaner'
	);
