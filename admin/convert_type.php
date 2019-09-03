<?php
/*
 * @package admin
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @Version $id: Product Typ Auction 18 2010-06-20 10:30:40Z davewest $
*/

  require('includes/application_top.php');
  $sql = "SELECT * FROM " . TABLE_PRODUCT_TYPES . " WHERE type_name = 'Product - Auction'";
  $result = $db->execute($sql);
  $typeID = $result->fields['type_id'];

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (isset($_POST['change'])) {
    if (isset($_POST['prodID']) && sizeof($_POST['prodID']) > 0) {
      foreach ($_POST['prodID'] as $key=>$value) {
        $productID = $key;
        $sql = "UPDATE " . TABLE_PRODUCTS . " SET products_type = " . (int)$typeID . " WHERE products_id = " . (int)$key;
        echo $sql;
        $db->execute($sql);
        $sql = "INSERT INTO " . TABLE_PRODUCT_AUCTION_EXTRA ." (products_id) 
                VALUES (" . (int)$key . ")";
        echo $sql;
        $db->execute($sql);
      }
    }
   }

  if (isset($_POST['filterCategory'])) {
    $filterCategory = $_POST['filterCategory'];
  } else {
    $filterCategory = 0;    
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>
</head>
<body onload="init()">
<?php
 $sql = "SELECT p.products_id, pd.products_name FROM "
          . TABLE_PRODUCTS . " p, " 
		  . TABLE_PRODUCTS_DESCRIPTION . " pd 
         WHERE master_categories_id = " . (int)$filterCategory . " 
         AND p.products_id = pd.products_id 
         AND pd.language_id = " . (int)$_SESSION['languages_id'] . " 
         AND p.products_type != " . $typeID;
 
 $result = $db->execute($sql);
 $products = array();
 while (!$result->EOF) {
   $products[$result->fields['products_id']] = array('text' => $result->fields['products_name']);
   $result->moveNext();
 }
?>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php echo zen_draw_form('filter', FILENAME_CONVERT_TYPE, '', 'post'); ?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
         <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right">
            </td>
          </tr>
        </table></td>
        <td><?php echo zen_draw_pull_down_menu('filterCategory', zen_get_categories(), '', 'onChange="this.form.submit();"'); ?></td>
      </tr>
    </table></td>
<?php
  if (sizeof($products) > 0) {
    foreach ($products as $key => $value) {
?>
   <tr>
   <td>
   <input type="checkbox" name="prodID[<?php echo $key; ?>]">&nbsp;
   <?php echo $key; ?>&nbsp;<?php echo $value['text']; ?>
   </td>
   </tr>
<?php 
    }   
  }
?>
<!-- body_text_eof //-->
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
  <td><input type="submit" name="change" value="Change to Auction" /></td>
  </tr>
</table>
</form>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>