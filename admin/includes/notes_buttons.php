<div id="box_notes_buttons">
<?php 
//define('FILENAME_NOTES', 'notes.php'); 
//define('FILENAME_NOTES', 'notes.php'); 
define('BUTTON_ADD_CUSTOMERS_NOTE', 'Add Customers note');
define('BUTTON_ADD_ORDERS_NOTE', 'Add Orders note');
define('BUTTON_ADD_PRODUCTS_NOTE', 'Add Products note');
define('BUTTON_ADD_CATEGORIES_NOTE', 'Add Categories note');
define('BUTTON_ADD_NOTE', 'Add note');

if(isset($_GET['cID']) && (int)$_GET['cID'] > 0){
  echo '<a target="_blank" href="' . zen_href_link(FILENAME_NOTES) . '?action=insert_new&cID=' . (int)$_GET['cID'] . '">' . zen_image_button('button_add_customers_note.gif', BUTTON_ADD_CUSTOMERS_NOTE) . '</a>';
}

if(isset($_GET['oID']) && (int)$_GET['oID'] > 0){
  echo '<a target="_blank" href="' . zen_href_link(FILENAME_NOTES) . '?action=insert_new&oID=' . (int)$_GET['oID'] . '">' . zen_image_button('button_add_orders_note.gif', BUTTON_ADD_ORDERS_NOTE) . '</a>';
}

if(isset($_GET['pID']) && (int)$_GET['pID'] > 0){
  echo '<a target="_blank" href="' . zen_href_link(FILENAME_NOTES) . '?action=insert_new&pID=' . (int)$_GET['pID'] . '">' . zen_image_button('button_add_products_note.gif', BUTTON_ADD_PRODUCTS_NOTE) . '</a>';
}

if(isset($_GET['cPath']) && (int)$_GET['cPath'] > 0){
  echo '<a target="_blank" href="' . zen_href_link(FILENAME_NOTES) . '?action=insert_new&cPath=' . zen_db_input($_GET['cPath']) . '">' . zen_image_button('button_add_categories_note.gif', BUTTON_ADD_CATEGORIES_NOTE) . '</a>';
}

//if(!(isset($_GET['cPath']) || isset($_GET['pID']) || isset($_GET['oID']) || isset($_GET['cID']))){
  echo '<a target="_blank" href="' . zen_href_link(FILENAME_NOTES) . '?action=insert_new">' . zen_image_button('button_add_note.gif', BUTTON_ADD_NOTE) . '</a>';
//}
?>
</div>