<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_order_totals.php 2993 2006-02-08 07:14:52Z birdbrain $
 */
 ?>
<?php 
/**
 * Displays order-totals modules' output
 */
  for ($i=0; $i<$size; $i++) { ?>
<?php
    $title = $GLOBALS[$class]->output[$i]['title'];
// strip off br's
// I could NOT find where they were added - scw 06/05/19
   $title = strip_tags($title); 
?>
<div id="<?php echo str_replace('_', '', $GLOBALS[$class]->code); ?>">
    <div class="totalBox larger forward"><?php echo $GLOBALS[$class]->output[$i]['text']; ?></div>
    <div class="lineTitle larger forward"><?php echo $title; ?></div>
</div>
<div class="clearfix"></div>
<?php } ?>
