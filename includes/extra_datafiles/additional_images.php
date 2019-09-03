<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// +----------------------------------------------------------------------+
//  Author: Ravi Gulhane
//
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
/**
 * Database name defines
 *
 */
define('TABLE_ADDITIONAL_IMAGES', DB_PREFIX . 'additional_images');
define('FILENAME_ADDITIONAL_IMAGES', 'additional_images');
define('FILENAME_ADDITIONAL_IMAGES_MODULE','additional_images.php');
define('TABLE_HEADING_ADDITIONAL_IMAGES','Additional Images');
?>