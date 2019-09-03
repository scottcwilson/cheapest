<?php
/**
 * @package page template
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Define Generator v0.1 $
 */
?>
<!-- bof tpl_usps_tracking_default.php -->
<div class='centerColumn' id='usps_tracking'>
    <h1 id='usps_tracking-heading'><?php echo HEADING_TITLE; ?></h1>
    <div id='usps_tracking-content' class='content'>
        <?php
        /**
         * require the html_define for the usps_tracking page
         */
        require($define_page);
        echo zen_draw_form('tracking_form', '', 'get', 'id="trackingForm"');
        echo 'Tracking Number';
        echo zen_draw_input_field('tracking_field', '', 'id="tracking_field"');
        echo zen_image_submit('submit.gif', 'Submit');
        echo '</form>'
        ?>
        <br/>
        <div id="trackingInformation">
        </div>
        <span id="hiddenTracking" style="display:none;"></span>
    </div>
</div>
<!-- eof tpl_usps_tracking_default.php -->
