<?php
/**
 * @package admin
 * @copyright Copyright 2003-2008 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: notes_extras_dhtml.php v0.98 2009-04-01 Paul Mathot $
 */

  require('includes/application_top.php');
// bof config
define('TABLE_NOTES', DB_PREFIX .'notes');
define('TABLE_NOTES_CATEGORIES', DB_PREFIX .'notes_categories');  
define('FILENAME_NOTES', 'notes.php'); 
define('NOTES_IS_PUBLIC_DEFAULT', 0);

// language
define('HEADING_TITLE', 'Admin Notes');
define('TABLE_HEADING_NOTES_ID','id');
define('TABLE_HEADING_NOTES_TITLE','Title');
define('TABLE_HEADING_NOTES_DATE_CREATED','Created');
define('TABLE_HEADING_NOTES_START_DATE','Starts');
define('TABLE_HEADING_NOTES_END_DATE','Ends');
define('TABLE_HEADING_NOTES_CATEGORIES_NAME','Category');
define('TABLE_HEADING_NOTE_CATEGORIES_ID','Categories ID (products)');
define('TABLE_HEADING_NOTE_PRODUCTS_ID','Products ID');
define('TABLE_HEADING_NOTE_CUSTOMERS_ID','Customers ID');
define('TABLE_HEADING_NOTE_ORDERS_ID','Orders ID');
define('TABLE_HEADING_NOTES_STATUS','Active');
define('TABLE_HEADING_NOTES_IS_SPECIAL_STATUS','Special');
define('TABLE_HEADING_NOTES_ACTION','Action');

define('TEXT_INFO_DATE_CREATED','Created: ');
define('TEXT_INFO_NOTE_NOTES_CATEGORY','Notes Category: ');

define('TEXT_INFO_NOTE_ORDER','Edit Order');
define('TEXT_INFO_NOTE_CUSTOMER','Edit Customer');
define('TEXT_INFO_NOTE_PRODUCT','Edit Product');
define('TEXT_INFO_NOTE_CATEGORY','Edit Category');

define('TEXT_INFO_NOTE_TEXT', 'Text');
define('TEXT_INFO_NOTE_TITLE', 'Title');

define('TEXT_INFO_NOTE_START_DATE', 'Starts');
define('TEXT_INFO_NOTE_END_DATE', 'Ends');
define('TEXT_INFO_NOTE_DATE_MODIFIED', 'Last modified:&nbsp;');

//define('TEXT_INFO_NOTE_PRIORITY', 'Priority (not in use yet)');
define('TEXT_INFO_NOTE_IS_SPECIAL', 'Note is special status');
define('TEXT_INFO_NOTE_STATUS', 'Active');

define('TABLE_HEADING_NOTES_CATEGORIES_ID', 'id');
define('TEXT_INFO_NOTE_CATEGORY_TITLE', 'Enter note category name ');
define('DISPLAY_NOTES_CATEGORY','Filter by notes category');
define('TEXT_DISPLAY_NUMBER_OF_NOTES', 'Number of notes: <b>%d</b> to <b>%d</b> (of <b>%d</b>)');

define('TEXT_INFO_HEADING_DELETE_NOTE', 'Delete note!');
define('TEXT_INFO_DELETE_NOTE_INTRO', 'Do you really want to delete this note?');

define('TEXT_NOTES_HELP', '<strong>Active</strong> notes will trigger the Zen Cart notification system. Active <strong>Special</strong> notes will only be show when you are viewing the customer, product, order or category it is attached too. Normal (active) customer, product, order and category notes will show a link to the customer, product, order and category.');

define('TEXT_INFO_START_DATE', 'Starts at:&nbsp;');
define('TEXT_INFO_END_DATE', 'Ends at:&nbsp;');

define('TABLE_HEADING_NOTES_ADMIN_ID', 'Created by Admin');
define('TABLE_HEADING_NOTES_IS_PUBLIC', 'Public');
define('TEXT_INFO_NOTE_IS_PUBLIC','Public (for admins)');

define('TEXT_INFO_NEW_PUBLIC_NOTE', 'New Public note');
define('TEXT_INFO_NEW_PRIVATE_NOTE', 'New Private note'); 
if(NOTES_IS_PUBLIC_DEFAULT == 1) {
  define('TEXT_INFO_NEW_NOTE', TEXT_INFO_NEW_PUBLIC_NOTE);
}else{
  define('TEXT_INFO_NEW_NOTE', TEXT_INFO_NEW_PRIVATE_NOTE);
}
// eof config

// bof functions
////
// new note
function zen_insert_new_note(){
  global $db;
  $notes_is_special_status = 1;

  $customers_id = 0;
  $orders_id = 0;
  $products_id = 0;
  $categories_id = '';
  $admin_id = (int)$_SESSION['admin_id'];
  $notes_is_public = (int)NOTES_IS_PUBLIC_DEFAULT;
  
  switch(TRUE){
     case(isset($_GET['cID']) && (int)$_GET['cID'] > 0):
      $customers_id = (int)$_GET['cID'];        
      break;
  
    case(isset($_GET['oID']) && (int)$_GET['oID'] > 0):
      $orders_id = (int)$_GET['oID'];        
      break;
      
    case(isset($_GET['pID']) && (int)$_GET['pID'] > 0):
      $products_id = (int)$_GET['pID'];        
      break;

    case(isset($_GET['cPath']) && !empty($_GET['cPath'])):
      $categories_id = zen_db_input($_GET['cPath']);        
      break;
    
    default:
      $notes_is_special_status = 0;
  }   
  $db->Execute("INSERT INTO " . TABLE_NOTES . "
                    (`notes_title`, `notes_date_created`, `notes_start_date`, `notes_end_date`, `notes_categories_id`, `notes_status`, `notes_is_special_status`, `customers_id`, `orders_id`, `products_id`, `categories_id`, `admin_id`, `notes_is_public`)
                    VALUES('" . TEXT_INFO_NEW_NOTE . "',NOW(), NOW(), '0001-01-01','1','1', '" . $notes_is_special_status . "','" . $customers_id . "','" . $orders_id . "','" . $products_id . "','" . $categories_id . "','" . $admin_id . "','" . $notes_is_public . "')");
  $insert_id = $db->Insert_ID();
  zen_redirect(zen_href_link(FILENAME_NOTES, 'action=edit&nID=' . $insert_id, 'NONSSL'));  
}
// Sets the status of a note
 // zen_set_notes_status($_GET['nID'], $_GET['flag']);
function zen_set_notes_status($id, $status){
     global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_NOTES . "
                           set notes_status = 1
                           where notes_id = '" . (int)$id . "'");

    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_NOTES . "
                           set notes_status = 0
                           where notes_id = '" . (int)$id . "'");

    } else {
      return -1;
    } 
}

// Sets the public status of a note
function zen_set_notes_is_public($id, $status){
     global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_NOTES . "
                           set notes_is_public = 1
                           where notes_id = '" . (int)$id . "'");

    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_NOTES . "
                           set notes_is_public = 0
                           where notes_id = '" . (int)$id . "'");

    } else {
      return -1;
    } 
}


// Sets the is special status of a note
 // zen_set_notes_status($_GET['nID'], $_GET['flag']);
function zen_set_is_special_notes_status($id, $status){
     global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_NOTES . "
                           set notes_is_special_status = 1
                           where notes_id = '" . (int)$id . "'");

    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_NOTES . "
                           set notes_is_special_status = 0
                           where notes_id = '" . (int)$id . "'");

    } else {
      return -1;
    } 
}

function zen_get_notes_categories_array($all = false){
  global $db;
  
  $categories = $db->Execute("SELECT * FROM " . TABLE_NOTES_CATEGORIES . " ORDER BY notes_categories_name");
  if($all == true){
    $categories_array[] =  array(id => 0, text => 'All'); 
  }
  while(!$categories->EOF){
   $categories_array[] = array(id=> $categories->fields['notes_categories_id'], text => $categories->fields['notes_categories_name']); 
   $categories->MoveNext(); 
  }
  return $categories_array;
   
}
// eof functions

// bof logic

$notes_categories_array = zen_get_notes_categories_array();

$current_notes_category_id = $_GET['notes_cat'];

$notes_categories_filter = '';
if(isset($_GET['notes_cat']) && ($_GET['notes_cat'] > 0)){
  $notes_categories_filter = " AND nc.notes_categories_id =" . (int)$_GET['notes_cat'] . " ";
  
}

// get note data for detailed note view and edit 
if(isset($_GET['nID']) && ($_GET['nID'] > 0)){  
  $notes_query_raw = ("select * FROM " . TABLE_NOTES . " n LEFT JOIN " . TABLE_NOTES_CATEGORIES . " nc ON (n.notes_categories_id = nc.notes_categories_id) WHERE notes_id = " . (int)$_GET['nID'] . " LIMIT 1");
  $note = $db->Execute($notes_query_raw);
  //print_r($note);
}
// get note category data for edit 
if(isset($_GET['cID']) && ($_GET['cID'] > 0)){  
  $notes_categories_query_raw = ("select * FROM " . TABLE_NOTES_CATEGORIES . " WHERE notes_categories_id = " . (int)$_GET['cID'] . " LIMIT 1");
  $note_cat = $db->Execute($notes_categories_query_raw);
  //print_r($note);
}

// create search filter
    $search = '';
    if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
      $keywords = zen_db_input(zen_db_prepare_input($_GET['search']));
      $search = " AND n.notes_title LIKE '%" . $keywords . "%' OR n.notes_text LIKE '%" . $keywords . "%'";
    }

    if ($status_filter !='' && $status_filter >0) $search .= " and n.status=" . ((int)$status_filter-1) . " ";
    
switch($_GET['action']){
  
  case 'update_category':
    $db->Execute("update " . TABLE_NOTES_CATEGORIES . "
                           SET notes_categories_name ='" . zen_db_input(zen_db_prepare_input($_POST['notes_categories_name'])) . "'
                           WHERE notes_categories_id = '" . (int)$_GET['cID'] . "'");
  
    zen_redirect(zen_href_link(FILENAME_NOTES, 'action=categories', 'NONSSL'));  
  break;
  
  case 'categories':
    $notes_cats_query_raw = ("select * FROM " . TABLE_NOTES_CATEGORIES . " ORDER BY notes_categories_name ASC, notes_categories_id DESC");
    $notes_cats = $db->Execute($notes_cats_query_raw);
  break;
  
  case 'setflag':
    zen_set_notes_status($_GET['nID'], $_GET['flag']);
    zen_redirect(zen_href_link(FILENAME_NOTES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'nID=' . (int)$_GET['nID'], 'NONSSL'));
  break;
    
  case 'setspecialflag':
    zen_set_is_special_notes_status($_GET['nID'], $_GET['flag']);
    zen_redirect(zen_href_link(FILENAME_NOTES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'nID=' . (int)$_GET['nID'], 'NONSSL'));
  break;
      
  case 'setpublicflag':
    zen_set_notes_is_public($_GET['nID'], $_GET['flag']);
    zen_redirect(zen_href_link(FILENAME_NOTES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'nID=' . (int)$_GET['nID'], 'NONSSL'));
  break;

  case 'update':

   $db->Execute("update " . TABLE_NOTES . "
                           SET
                          `notes_status` = '" . zen_db_input(zen_db_prepare_input($_POST['notes_status'])) . "',
                          `notes_title` = '" . zen_db_input(zen_db_prepare_input($_POST['notes_title'])) . "',
                          `notes_text` =  '" . zen_db_input(zen_db_prepare_input($_POST['notes_text'])) . "',
 
                          `notes_start_date` =  '" . zen_db_input(zen_db_prepare_input($_POST['notes_start_date'])) . "',                          
                          `notes_end_date` =  '" . zen_db_input(zen_db_prepare_input($_POST['notes_end_date'])) . "',
                                                                             
                          `notes_date_modified` = NOW(), 
                          `notes_categories_id` =  '" . (int)$_POST['notes_categories_id'] . "',
                          `notes_is_special_status` =  '" . (int)$_POST['notes_is_special_status'] . "',                              
                          `customers_id` =  '" . (int)$_POST['customers_id'] . "',
                          `orders_id` =  '" . (int)$_POST['orders_id'] . "', 
                          `products_id` =  '" . (int)$_POST['products_id'] . "',
                          `categories_id` =  '" . zen_db_input(zen_db_prepare_input($_POST['categories_id'])) . "',
                          `notes_is_public` =  '" . (int)$_POST['notes_is_public'] . "'                         

                           WHERE notes_id = '" . (int)$_POST['notes_id'] . "'");
   //xxx message_stack
   zen_redirect(zen_href_link(FILENAME_NOTES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'nID=' . (int)$_GET['nID'], 'NONSSL'));

  break;
  
  case 'deleteconfirm':
    
    //exit(zen_get_all_get_params());
    $update_succes = $db->Execute("DELETE FROM " . TABLE_NOTES . " WHERE notes_id = '" . (int)$_GET['nID'] . "'");
    // add messagestack message
    zen_redirect(zen_href_link(FILENAME_NOTES, zen_get_all_get_params(array('action', 'nID')), 'NONSSL'));

  break;
  case 'insert_new_category':
  
     $db->Execute("INSERT INTO " . TABLE_NOTES_CATEGORIES . " (`notes_categories_name`) VALUES('!New category')");
     $insert_id = $db->Insert_ID();
     zen_redirect(zen_href_link(FILENAME_NOTES, 'action=edit_category&cID=' . $insert_id, 'NONSSL'));

  break;
                            
  case 'insert_new':
    zen_insert_new_note();                    
                                               
  break;
                 
  /*
     case 'copy_to_new':               
         $db->Execute("INSERT INTO " . TABLE_NOTES . " 
         (notes_title, notes_text, notes_date_created, notes_date_modified, notes_start_date, notes_end_date, notes_snooze_date,
  notes_categories_id, notes_priority, notes_status, notes_is_special_status, customers_id, orders_id, products_id, categories_id)
         SELECT FROM " . TABLE_NOTES . " (notes_title, notes_text, notes_date_created, notes_date_modified, notes_start_date, notes_end_date, notes_snooze_date,
  notes_categories_id, notes_priority, notes_status, notes_is_special_status, customers_id, orders_id, products_id, categories_id)
  WHERE notes_id = '2'");
      break; 
  */
                                                            
}
// eof logic
                
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
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<?php

// bof search form
?>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
         <tr><?php echo zen_draw_form('search', FILENAME_NOTES, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right">
<?php

  if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
    $keywords = zen_db_input(zen_db_prepare_input($_GET['search']));
    echo '<br/ >' . TEXT_INFO_SEARCH_DETAIL_FILTER . '<strong>' . $keywords . '</strong>';
    // show reset search    
    echo '<br/ >' .'<a href="' . zen_href_link(FILENAME_NOTES) . '">' . zen_image_button('button_reset.gif', IMAGE_RESET) . '</a>&nbsp;&nbsp;';

  }else{
    echo HEADING_TITLE_SEARCH_DETAIL . ' ' . zen_draw_input_field('search') . zen_hide_session_id();    
  }
?>
            </td>
          </form>
          </tr>
          </table>
<?php
// eof search form


switch($_GET['action']){
   
  case 'view':
?>
           
<h4><?php echo $note->fields['notes_title']; ?></h4>
<ul>
  <li><?php echo TEXT_INFO_START_DATE . zen_date_long($note->fields['notes_start_date']); ?></li>
  <?php if($note->fields['notes_end_date'] != '0001-01-01') echo '<li>' . TEXT_INFO_END_DATE . zen_date_long($note->fields['notes_end_date']) . '</li>'; ?>
  <li><?php echo TEXT_INFO_NOTE_NOTES_CATEGORY . $note->fields['notes_categories_name']; ?></li> 
<?php if($note->fields['customers_id'] > 0) echo '<li><a href="' . FILENAME_CUSTOMERS . '.php' . '?cID=' . $note->fields['customers_id'] . '&action=edit' . '"><strong>' . TEXT_INFO_NOTE_CUSTOMER . '</strong></a></li>'; ?>
<?php if($note->fields['orders_id'] > 0) echo '<a href="' . FILENAME_ORDERS . '.php' . '?oID=' . $note->fields['orders_id'] . '&action=edit' . '"><strong>' . TEXT_INFO_NOTE_ORDER . '</strong></a>'; ?>
<?php if(zen_not_null($note->fields['categories_id'])) echo '<a href="' . FILENAME_CATEGORIES . '.php' . '?cPath=' . $note->fields['categories_id'] . '"><strong>' . TEXT_INFO_NOTE_CATEGORY . '</strong></a>'; ?>
<?php if($note->fields['products_id'] > 0) echo '<a href="' . FILENAME_PRODUCT . '.php' . '?action=new_product&pID=' . $note->fields['products_id'] . '&product_type=' . zen_get_products_type($note->fields['products_id']) . '&cPath=' . zen_get_products_category_id($note->fields['products_id']) . '"><strong>' . TEXT_INFO_NOTE_PRODUCT . '</strong></a>'; ?>
  <li><?php echo TEXT_INFO_DATE_CREATED . zen_date_long($note->fields['notes_date_created']); ?></li>
 <?php if(zen_not_null($note->fields['notes_date_modified'])) echo '<li>' . TEXT_INFO_NOTE_DATE_MODIFIED . zen_date_long($note->fields['notes_date_modified']) . '</li>'; ?> 
</ul>
<div style="border: 3px double blue; padding: 1em; width: 80%; margin: auto; line-height: 1.5em;"><?php echo nl2br(stripslashes($note->fields['notes_text'])); ?></div>    
     
<?php echo '<a href="' . zen_href_link(FILENAME_NOTES, zen_get_all_get_params(array('action'))) . '">' . zen_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>

<?php
  break;  
  
  case 'edit':
    // $parameters = 
    // ((isset($_GET['cPath'])) ? $_GET['cPath'] : $note->fields['categories_id']) 
    // ((isset($_GET['pID'])) ? '<strong class="notesCheckValue">*</strong>' : '') 
    // ((isset($_GET['oID'])) ? '<strong class="notesCheckValue">*</strong>' : '')
    // ((isset($_GET['cID'])) ? '<strong class="notesCheckValue">*</strong>' : '')

?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
  var startDate = new ctlSpiffyCalendarBox("startDate", "edit_note", "notes_start_date","btnDate1","<?php echo $note->fields['notes_start_date']; ?>",scBTNMODE_CUSTOMBLUE);
  var endDate = new ctlSpiffyCalendarBox("endDate", "edit_note", "notes_end_date","btnDate2","<?php echo $note->fields['notes_end_date']; ?>",scBTNMODE_CUSTOMBLUE);
</script>
<?php echo zen_draw_form('edit_note', FILENAME_NOTES, zen_get_all_get_params(array('action')) . '&action=update'); ?>
<table>
  <tr><th><?php echo TEXT_INFO_NOTE_STATUS; ?></th><td><?php echo zen_draw_checkbox_field('notes_status', 1, $note->fields['notes_status']); ?></td></tr>  
  <tr><th><?php echo TEXT_INFO_NOTE_IS_PUBLIC; ?></th><td><?php echo zen_draw_checkbox_field('notes_is_public', 1, $note->fields['notes_is_public']); ?></td></tr>  
  <tr><th><?php echo TEXT_INFO_NOTE_TITLE; ?></th><td><?php echo zen_draw_input_field('notes_title', $note->fields['notes_title']); ?></td></tr>
  <tr><th><?php echo TEXT_INFO_NOTE_NOTES_CATEGORY; ?></th><td><?php echo zen_draw_pull_down_menu('notes_categories_id', $notes_categories_array, $note->fields['notes_categories_id']); ?></td></tr>
  <tr><th><?php echo TEXT_INFO_NOTE_START_DATE; ?></th><td class="main"><script language="javascript">startDate.writeControl(); startDate.dateFormat="yyyy-MM-dd";</script></td></tr>
  <tr><th><?php echo TEXT_INFO_NOTE_END_DATE; ?></th><td class="main"><script language="javascript">endDate.writeControl(); endDate.dateFormat="yyyy-MM-dd";</script></td></tr>

<!--  
  <tr><th><?php echo TEXT_INFO_NOTE_PRIORITY; ?></th><td><?php echo zen_draw_input_field('notes_priority', $note->fields['notes_priority']); ?></td></tr>
-->
  <tr><th><?php echo TEXT_INFO_NOTE_IS_SPECIAL; ?></th><td><?php echo zen_draw_checkbox_field('notes_is_special_status', 1, $note->fields['notes_is_special_status']); ?></td></tr>  
  <tr><th><?php echo TEXT_INFO_NOTE_PRODUCT; ?></th><td><?php echo zen_draw_input_field('products_id', ((isset($_GET['pID'])) ? (int)$_GET['pID'] : $note->fields['products_id'])); ?><?php echo ((isset($_GET['pID'])) ? '<strong class="notesCheckValue">*</strong>' : '');?></td></tr>
  <tr><th><?php echo TEXT_INFO_NOTE_ORDER; ?></th><td><?php echo zen_draw_input_field('orders_id', ((isset($_GET['oID'])) ? (int)$_GET['oID'] : $note->fields['orders_id'])); ?><?php echo ((isset($_GET['oID'])) ? '<strong class="notesCheckValue">*</strong>' : '');?></td></tr>
  <tr><th><?php echo TEXT_INFO_NOTE_CATEGORY; ?></th><td><?php echo zen_draw_input_field('categories_id', ((isset($_GET['cPath'])) ? zen_db_input($_GET['cPath']) : $note->fields['categories_id'])); ?><?php echo ((isset($_GET['cPath'])) ? '<strong class="notesCheckValue">*</strong>' : '');?></td></tr>
  <tr><th><?php echo TEXT_INFO_NOTE_CUSTOMER; ?></th><td><?php echo zen_draw_input_field('customers_id', ((isset($_GET['cID'])) ? (int)$_GET['cID'] : $note->fields['customers_id'])); ?><?php echo ((isset($_GET['cID'])) ? '<strong class="notesCheckValue">*</strong>' : '');?></td></tr>        
  <!-- todo  xxx     
  <tr>
            <td class="main">Available Date:&nbsp;</td>

            <td class="main"><script language="javascript">StartDate.writeControl(); StartDate.dateFormat="MM/dd/yyyy";</script><input class="cal-TextBox" name="start" onchange="calMgr.validateDate(document.new_special.start,StartDate.required);" onblur="calMgr.formatDate(document.new_special.start,StartDate.dateFormat);" size="12" value="" type="text"><a class="so-BtnLink" onmouseover="calMgr.swapImg(StartDate,'.imgOver',false);" onmouseout="calMgr.swapImg(StartDate,'.imgUp',false);" onclick="calMgr.swapImg(StartDate,'.imgDown',true);StartDate.show();"><img name="btnDate1" src="http://localhost/admin/includes/javascript/spiffyCal/images/btn_date1_up.gif" width="22" align="absmiddle" border="0" height="17"></a></td>
          </tr>
          <tr>
            <td class="main">Expiry Date:&nbsp;</td>
            <td class="main"><script language="javascript">EndDate.writeControl(); EndDate.dateFormat="MM/dd/yyyy";</script><input class="cal-TextBox" name="end" onchange="calMgr.validateDate(document.new_special.end,EndDate.required);" onblur="calMgr.formatDate(document.new_special.end,EndDate.dateFormat);" size="12" value="" type="text"><a class="so-BtnLink" onmouseover="calMgr.swapImg(EndDate,'.imgOver',false);" onmouseout="calMgr.swapImg(EndDate,'.imgUp',false);" onclick="calMgr.swapImg(EndDate,'.imgDown',true);EndDate.show();"><img name="btnDate2" src="http://localhost/admin/includes/javascript/spiffyCal/images/btn_date1_up.gif" width="22" align="absmiddle" border="0" height="17"></a></td>
          </tr>
  -->
  </table> 
      <?php echo TEXT_INFO_NOTE_TEXT . '<p>' .zen_draw_textarea_field('notes_text', 'soft', '70', '15', stripslashes($note->fields['notes_text'])) . '</p>'; ?></td>
      <?php echo zen_draw_hidden_field('notes_id', $note->fields['notes_id']) . zen_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . zen_href_link(FILENAME_NOTES, zen_get_all_get_params(array('action'))) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
      </form>
<?php
  break;
  
  case 'edit_category':
    echo TEXT_INFO_NOTE_CATEGORY_TITLE . '(id=' . $note_cat->fields['notes_categories_id'] . '):<br />';
    echo zen_draw_form('notes_category_edit', FILENAME_NOTES, 'cID=' . $_GET['cID'] . '&action=update_category');
    echo zen_draw_input_field('notes_categories_name', $note_cat->fields['notes_categories_name']) . '<br /><br />';
    echo zen_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . zen_href_link(FILENAME_NOTES, 'action=categories') . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
 
?>
</form>
<?php

  break;
  
  case 'categories':
    echo '<p>';
    echo '<a href="' . zen_href_link(FILENAME_NOTES, '', 'NONSSL') . '">' . 'Back to notes list' . '</a>';
    echo '&nbsp;|&nbsp;';
    echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=insert_new_category', 'NONSSL') . '">' . 'Insert new category' . '</a>';
    echo '</p>';
?>
    <!-- bof categories listing -->
    <table border="0" width="50em" cellspacing="0" cellpadding="2">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NOTES_CATEGORIES_ID; ?></td>
        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NOTES_CATEGORIES_NAME; ?></td>
        <td class="dataTableHeadingContent">&nbsp;</td>        
      </tr>
<?php
    while(!$notes_cats->EOF){
?>
      <tr>
        <td class="dataTableContent" align="right"><?php echo $notes_cats->fields['notes_categories_id']; ?></td>                                 
        <td class="dataTableContent" align="right"><?php echo $notes_cats->fields['notes_categories_name']; ?></td>
        <td class="dataTableContent" align="right"><?php echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=edit_category&cID=' . $notes_cats->fields['notes_categories_id'], 'NONSSL') . '">' . 'EDIT' . '</a>'; ?></td>
      </tr>
<?php

      $notes_cats->MoveNext();
    }
?>
    </table>
    <!-- eof categories listing -->
         
<?php  
    if($_GET['edit_category']='true'){
      //echo zen_draw_hidden_field('notes_id', $note->fields['notes_id']) . zen_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . zen_href_link(FILENAME_NOTES, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';

    }
  break; 
    
  default:

    echo zen_draw_form('categorie', FILENAME_NOTES, zen_get_all_get_params(array('page', 'nID')), 'get') . DISPLAY_NOTES_CATEGORY . '&nbsp;&nbsp;' . zen_draw_pull_down_menu('notes_cat', zen_get_notes_categories_array(true), $current_notes_category_id, 'onChange="this.form.submit();"');
    //echo zen_image_button('button_reset.gif', 'Reset', 'onClick="this.form.submit();"') . '';
    echo '</form>';
    if (isset($current_notes_category_id) && ($current_notes_category_id > 0)) {
      echo '<a href="' . zen_href_link(FILENAME_NOTES) . '">' . zen_image_button('button_reset.gif', IMAGE_RESET) . '</a>&nbsp;&nbsp;';
    }   
    
    echo '<p>';
    if(isset($_GET['nID'])){
      echo '<a href="' . zen_href_link(FILENAME_NOTES, '', 'NONSSL') . '">' . 'Notes list' . '</a>';
      echo '&nbsp;|&nbsp;';
    }    
    echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=categories', 'NONSSL') . '">' . 'Note categories' . '</a>';
    echo '&nbsp;|&nbsp;';
    echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=insert_new', 'NONSSL') . '">' . 'Insert new note' . '</a>';
    echo '</p>';    
    ?>
        <!-- bof notes listing -->
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NOTES_ID; ?></td>
                <td class="dataTableHeadingContent">&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NOTES_TITLE; ?></td>
<!--                
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NOTES_DATE_CREATED; ?></td>
-->                
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NOTES_START_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NOTES_END_DATE; ?></td>                
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_NOTES_CATEGORIES_NAME; ?></td>
                
<!--
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_NOTE_CUSTOMERS_ID; ?></td>                
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_NOTE_ORDERS_ID; ?></td>                
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_NOTE_CATEGORIES_ID; ?></td> 
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_NOTE_PRODUCTS_ID; ?></td>
-->              
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NOTES_ADMIN_ID; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NOTES_IS_PUBLIC; ?></td> 
                
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NOTES_IS_SPECIAL_STATUS; ?></td>                             
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NOTES_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NOTES_ACTION; ?></td>               
                
              </tr>
<?php

    if((!($_GET['page'] > 0)) && (isset($_GET['nID']) && ((int)$_GET['nID'] > 0))){
      // fix for active notes notifier link issue: note not showing when nID is set but page is not (and the note is not on page 1).
      $notes_query_conditions = " WHERE n.notes_id = " . (int)$_GET['nID'];      
      //$notes_query_raw = ("select * FROM " . TABLE_NOTES . " n LEFT JOIN " . TABLE_NOTES_CATEGORIES . " nc ON (n.notes_categories_id = nc.notes_categories_id) " . " WHERE n.notes_id = " . (int)$_GET['nID']);
    }else{  
      $order_by = " ORDER BY notes_id DESC";
      $notes_query_conditions =  " WHERE 1 = 1 " . $search . $notes_categories_filter;
      //$notes_query_raw = ("select * FROM " . TABLE_NOTES . " n LEFT JOIN " . TABLE_NOTES_CATEGORIES . " nc ON (n.notes_categories_id = nc.notes_categories_id) " . " WHERE 1 = 1 " . $search . $notes_categories_filter . $order_by);
    }
    $notes_query_conditions .= " AND (n.notes_is_public = '1' || n.admin_id = '" . (int)$_SESSION['admin_id'] . "')";      
    $notes_query_raw = ("select * FROM " . TABLE_NOTES . " n LEFT JOIN " . TABLE_NOTES_CATEGORIES . " nc ON (n.notes_categories_id = nc.notes_categories_id) " . $notes_query_conditions . $order_by);
    
    $notes_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $notes_query_raw, $notes_query_numrows);
    $notes = $db->Execute($notes_query_raw);
    while (!$notes->EOF) {
      if ((!isset($_GET['nID']) || (isset($_GET['nID']) && ($_GET['nID'] == $notes->fields['notes_id']))) && !isset($nInfo)) {
/*
        $products_image = $db->Execute("select products_image, products_model
                                        from " . TABLE_PRODUCTS . "
                                        where products_id = '" . (int)$notes->fields['products_id'] . "'");


        $products_name = $db->Execute("select products_name
                                       from " . TABLE_PRODUCTS_DESCRIPTION . "
                                       where products_id = '" . (int)$notes->fields['products_id'] . "'
                                       and language_id = '" . (int)$_SESSION['languages_id'] . "'");
*/

        $nInfo_array = $notes->fields;
        $nInfo = new objectInfo($nInfo_array);

      }

      if (isset($nInfo) && is_object($nInfo) && ($notes->fields['notes_id'] == $nInfo->notes_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_NOTES, zen_get_all_get_params(array('nID', 'action')) . 'nID=' . $nInfo->notes_id . '&action=view') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_NOTES, zen_get_all_get_params(array('nID')) . 'nID=' . $notes->fields['notes_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $notes->fields['notes_id']; ?></td>
                <td class="dataTableContent"><?php echo '<a href="' . zen_href_link(FILENAME_NOTES, 'page=' . $_GET['page'] . '&nID=' . $notes->fields['notes_id'] . '&action=view') . '">' . zen_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>'; ?></td>
                <td class="dataTableContent"><?php echo $notes->fields['notes_title']; ?></td>
<!--                
                <td class="dataTableContent" align="right"><?php echo $notes->fields['notes_date_created']; ?></td>
-->                
                <td class="dataTableContent" align="right"><?php echo zen_date_short($notes->fields['notes_start_date']); ?></td>
                <td class="dataTableContent" align="right"><?php 
                  if($notes->fields['notes_end_date'] == '0001-01-01'){
                    echo '--';                    
                  }else{
                    echo zen_date_short($notes->fields['notes_end_date']); 
                  }
                  
                  ?></td>                                 
                <td class="dataTableContent" align="right"><?php echo $notes->fields['notes_categories_name']; ?></td>
<!--                
                <td class="dataTableContent" align="right"><?php echo $notes->fields['customers_id']; ?></td>                
                <td class="dataTableContent" align="right"><?php echo $notes->fields['orders_id']; ?></td>
                <td class="dataTableContent" align="right"><?php echo $notes->fields['categories_id']; ?></td>                                
                <td class="dataTableContent" align="right"><?php echo $notes->fields['products_id']; ?></td>                
-->
                <td class="dataTableContent" align="right"><?php echo $notes->fields['admin_id']; ?></td>
                
                <td  class="dataTableContent" align="center">
<?php
      if ($notes->fields['notes_is_public'] == '1') {
        echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=setpublicflag&flag=0&nID=' . $notes->fields['notes_id'], 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_green_on.gif', IMAGE_ICON_STATUS_ON) . '</a>';
      } else {
        echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=setpublicflag&flag=1&nID=' . $notes->fields['notes_id'], 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_green_off.gif', IMAGE_ICON_STATUS_OFF) . '</a>';
      }
?>
                </td>
                
                <td  class="dataTableContent" align="center">
<?php
      if ($notes->fields['notes_is_special_status'] == '1') {
        echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=setspecialflag&flag=0&nID=' . $notes->fields['notes_id'], 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_yellow_on.gif', IMAGE_ICON_STATUS_ON) . '</a>';
      } else {
        echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=setspecialflag&flag=1&nID=' . $notes->fields['notes_id'], 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_yellow_off.gif', IMAGE_ICON_STATUS_OFF) . '</a>';
      }
?>
                </td>                
                <td  class="dataTableContent" align="center">
<?php
      if ($notes->fields['notes_status'] == '1') {
        echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=setflag&flag=0&nID=' . $notes->fields['notes_id'], 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_green_on.gif', IMAGE_ICON_STATUS_ON) . '</a>';
      } else {
        echo '<a href="' . zen_href_link(FILENAME_NOTES, 'action=setflag&flag=1&nID=' . $notes->fields['notes_id'], 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_red_on.gif', IMAGE_ICON_STATUS_OFF) . '</a>';
      }
?>
                </td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($nInfo)) && ($notes->fields['notes_id'] == $nInfo->notes_id) ) { echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . zen_href_link(FILENAME_NOTES, 'page=' . $_GET['page'] . '&nID=' . $notes->fields['notes_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      $notes->MoveNext();
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $notes_split->display_count($notes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_NOTES); ?></td>
                    <td class="smallText" align="right"><?php echo $notes_split->display_links($notes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], zen_get_all_get_params(array('action', 'nID','page', 'x', 'y'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();

    switch($_GET['action']) {
      case 'delete':
        // show delete confirmation (note preview sidebox) 
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_NOTE . '</b>');

        $contents = array('form' => zen_draw_form('notes', FILENAME_NOTES, zen_get_all_get_params(array('action', 'nID')) . 'nID=' . $nInfo->notes_id . '&action=deleteconfirm'));
    
        $contents[] = array('text' => TEXT_INFO_DELETE_NOTE_INTRO);
        $contents[] = array('text' => '<br /><b>' . $nInfo->notes_title . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . zen_href_link(FILENAME_NOTES, zen_get_all_get_params(array('action', 'nID')) . 'nID=' . $nInfo->notes_id) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
        
      default:
      
        if (isset($nInfo) && is_object($nInfo)) {
        // note preview sidebox
        
        $heading[] = array('text' => '<b>' . $nInfo->notes_title . '</b>'); 
    
        $contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_NOTES, zen_get_all_get_params(array('action','nID')) . 'nID=' . $nInfo->notes_id . '&action=edit') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . zen_href_link(FILENAME_NOTES, zen_get_all_get_params(array('action','nID')) . 'nID=' . $nInfo->notes_id . '&action=delete') . '">' . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a> ' . '<a href="' . zen_href_link(FILENAME_NOTES, zen_get_all_get_params(array('action','nID')) . 'nID=' . $nInfo->notes_id . '&action=view') . '">' . zen_image_button('button_details.gif', IMAGE_DETAILS) . '</a>');
        //$contents[] = array('text' => '<br />' . TEXT_INFO_NOTE_TITLE . $nInfo->notes_title);
        $contents[] = array('text' => '<br />' .TEXT_INFO_START_DATE . zen_date_long($nInfo->notes_start_date));
        
        if($nInfo->notes_end_date != '0001-01-01')
        $contents[] = array('text' => TEXT_INFO_END_DATE . zen_date_long($nInfo->notes_end_date));
                
        $contents[] = array('text' => '<br />' . TEXT_INFO_NOTE_NOTES_CATEGORY . $nInfo->notes_categories_name . '<br />');
        
        // bof note links
        // customers note
        if($nInfo->customers_id > 0)
          $contents[] = array('text' => '<a href="' . FILENAME_CUSTOMERS . '.php' . '?cID=' . $nInfo->customers_id . '&action=edit' . '"><strong>' . TEXT_INFO_NOTE_CUSTOMER . '</strong></a>');
        
        // orders note   
        if($nInfo->orders_id > 0)
          $contents[] = array('text' => '<a href="' . FILENAME_ORDERS . '.php' . '?oID=' . $nInfo->orders_id . '&action=edit' . '"><strong>' . TEXT_INFO_NOTE_ORDER . '</strong></a>');
        
        if(zen_not_null($nInfo->categories_id))
          $contents[] = array('text' => '<a href="' . FILENAME_CATEGORIES . '.php' . '?cPath=' . $nInfo->categories_id . '"><strong>' . TEXT_INFO_NOTE_CATEGORY . '</strong></a>');
        
        // products note
        if($nInfo->products_id > 0)
          $contents[] = array('text' => '<a href="' . FILENAME_PRODUCT . '.php' . '?action=new_product&pID=' . $nInfo->products_id . '&product_type=' . zen_get_products_type($nInfo->products_id) . '&cPath=' . zen_get_products_category_id($nInfo->products_id) . '"><strong>' . TEXT_INFO_NOTE_PRODUCT . '</strong></a>');                                
        // eof note links
         
        $contents[] = array('text' => '<br />' . TEXT_INFO_NOTE_TEXT . '<p style="font-weight: bold; ">' . nl2br(stripslashes($nInfo->notes_text)) . '</p>');
        
         $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_CREATED . zen_date_long($nInfo->notes_date_created));
        if (zen_not_null($nInfo->notes_date_modified)) $contents[] = array('text' => TEXT_INFO_NOTE_DATE_MODIFIED . ': ' . zen_date_long($nInfo->notes_date_modified));

      }
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
        </table>
        <!-- eof notes listing -->
       
<?php
  echo '<p>' . TEXT_NOTES_HELP . '</p>';
} // eof switch($action)

    
?>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>