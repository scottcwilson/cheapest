<?php

/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_order_history.php 18695 2011-05-04 05:24:19Z drbyte $
 */
// BOF begin sidebox
$content = "";
$content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">' . "\n";
// EOF begin sidebox
$content .= zen_draw_form('sidebox_tracking_form', '', 'get', 'id="sidebox_tracking_form"');
$content .= 'Tracking Number';
$content .= zen_draw_input_field('sidebox_tracking_field', '', 'id="sidebox_tracking_field"');
$content .= zen_image_submit('submit.gif', 'Submit');
$content .= '</form>';
$content .=  '<br/>';
$content .=  '<div id="sideboxTrackingInformation"></div>';
$content .=  '<span id="hiddenTracking" style="display:none;"></span>';

// BOF end sidebox
$content .= '</div>';
// EOF end sidebox