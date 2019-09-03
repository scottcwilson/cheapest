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

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

	require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  if (zen_not_null($action)) {
    switch ($action) {
	  case 'setflag':
		$aID = zen_db_prepare_input($_GET['iID']);
        $db->Execute("update " . TABLE_ADDITIONAL_IMAGES . " set status = '" .(int)$_GET['flag']. "' where additional_images_id = '" . (int)$aID . "'");
         zen_redirect(zen_href_link(FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'products_id='.(int)$_GET['products_id']));
        break;
      case 'insert':
	  	$additional_image = new upload('additional_image');
          $additional_image->set_destination(DIR_FS_CATALOG_IMAGES . 'additional/');
          if ($additional_image->parse() && $additional_image->save($_POST['overwrite'])) {
            $additional_image_name = 'additional/' . $additional_image->filename;
          }
	  
	  
        $db->Execute("insert into " . TABLE_ADDITIONAL_IMAGES . "
                    (products_id, additional_image, status) 
                    values ('" . zen_db_input((int)$_GET['products_id']) . "',
                            '" . $additional_image_name . "',
                            '1')");

        zen_redirect(zen_href_link(FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'products_id='.(int)$_GET['products_id']));
        break;
      case 'deleteconfirm':
        $related_products_id = zen_db_prepare_input($_GET['iID']);

        $db->Execute("delete from " . TABLE_ADDITIONAL_IMAGES . "
                      where additional_images_id = '" . (int)$related_products_id . "'");

        zen_redirect(zen_href_link(FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'products_id='.(int)$_GET['products_id']));
        break;
    }
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
<body onLoad="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE . zen_get_products_name((int)$_GET['products_id'], (int)$_SESSION['languages_id']); ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="right"><?php echo 'ID#'; ?>&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_IMAGE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $additional_query_raw = "select additional_images_id, additional_image, status from " . TABLE_ADDITIONAL_IMAGES . "  where products_id = '" .(int)$_GET['products_id']. "'";
  //$additional_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $additional_query_raw);
  $additional = $db->Execute($additional_query_raw);
  while (!$additional->EOF) {
    if ((!isset($_GET['iID']) || (isset($_GET['iID']) && ($_GET['iID'] == $additional->fields['additional_products_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cInfo = new objectInfo($additional->fields);
    }
?>
                
                
                <td  class="dataTableContent" align="right"><?php echo $additional->fields['additional_images_id']; ?>&nbsp;</td>
                <td  class="dataTableContent"><?php echo zen_image(DIR_WS_CATALOG_IMAGES . $additional->fields['additional_image'], '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></td>
                <td  class="dataTableContent" align="center">
<?php
      if ($additional->fields['status'] == '1') {
        echo '<a href="' . zen_href_link(FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'action=setflag&flag=0&iID=' . $additional->fields['additional_images_id'].'&products_id='.(int)$_GET['products_id'], 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_green_on.gif', IMAGE_ICON_STATUS_ON) . '</a>';
      } else {
        echo '<a href="' . zen_href_link(FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'action=setflag&flag=1&iID=' . $additional->fields['additional_images_id'].'&products_id='.(int)$_GET['products_id'], 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_red_on.gif', IMAGE_ICON_STATUS_OFF) . '</a>';
      }
?>
                </td>
                <td class="dataTableContent" align="right">
				          <?php echo '<a href="' . zen_href_link(FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'iID=' . $additional->fields['additional_images_id'] . '&action=delete&products_id='.(int)$_GET['products_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_delete.gif', ICON_DELETE) . '</a>'; ?>
                  </td>
      </tr>
      
      
      
      
      
<?php
    $additional->MoveNext();
  }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . zen_href_link(FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'page=' . $_GET['page'] . '&action=new&products_id='.(int)$_GET['products_id']) . '">' . zen_image_button('button_additional_images.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>
                    <?php echo '<a href="' . zen_href_link(FILENAME_ADDITIONAL_IMAGES) . '">' . zen_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ADDITIONAL_IMAGES . '</b>');
      $contents = array('form' => zen_draw_form('additional', FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'products_id='.(int)$_GET['products_id'] . '&action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => '<br>' . zen_draw_file_field('additional_image'));
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . zen_href_link(FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'products_id='.(int)$_GET['products_id']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ADDITIONAL_IMAGES . '</b>');
      $contents = array('form' => zen_draw_form('additional', FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'products_id='.(int)$_GET['products_id'] . '&iID=' . (int)$_GET['iID'] . '&action=deleteconfirm'));
      $contents[] = array('text' => '<br><b>' . $cInfo->products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . zen_href_link(FILENAME_ADDITIONAL_IMAGES_UPLOAD, 'products_id='.(int)$_GET['products_id']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
  }

  if ( (zen_not_null($heading)) && (zen_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>