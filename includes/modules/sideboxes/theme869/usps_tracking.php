<?php
/**
 * order_history sidebox - if enabled, shows customers' most recent orders
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: order_history.php 4822 2006-10-23 11:11:36Z drbyte $
 */


      require($template->get_template_dir('tpl_usps_tracking.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_usps_tracking.php');
      $title =  SIDEBOX_USPS_TRACKING_TITLE;
      $title_link = FILENAME_USPS_TRACKING;
      require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);
 
