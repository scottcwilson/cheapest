<?php
require('includes/application_top.php');

if (isset($_GET['action'])) { 
	switch($_GET['action']) {
		case 'suggestion.search':
			if (isset($_GET['keyword'])) {
				$keywords = trim($_GET['keyword']);
				$sql = "SELECT DISTINCT p.products_image, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_price_sorter, p.master_categories_id,
				               p.products_model
						  FROM ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd
						 WHERE p.products_status = 1
                           AND p.products_id = pd.products_id
                           AND pd.language_id = ".(int)$_SESSION['languages_id'];
				$sql .= " AND (";
				zen_parse_search_string(stripslashes($_GET['keyword']), $search_keywords);
			    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
				  switch ($search_keywords[$i]) {
					case '(':
					case ')':
					case 'and':
					case 'or':
					$sql .= " " . $search_keywords[$i] . " ";
					break;
					default:
					$where_str = "(pd.products_name LIKE '%:keywords%' OR p.products_model LIKE '%:keywords%'";
			
					$sql .= $db->bindVars($where_str, ':keywords', $search_keywords[$i], 'noquotestring');
			
					$sql .= ')';
					break;
				  }
				}
				$sql .= " ) ORDER BY p.products_sort_order, pd.products_name";
				
				$result = new splitPageResults($sql, MAX_DISPLAY_SUGGESTION, 'p.products_id', 'page');
				
				if ($result->number_of_rows > 0) {
					$listing = $db->Execute($result->sql_query);				
					$show_divider = false;
					while(!$listing->EOF) {
						$image = zen_image(DIR_WS_IMAGES . $listing->fields['products_image'], $listing->fields['products_name'], SUGGESTION_IMAGE_WIDTH, SUGGESTION_IMAGE_HEIGHT, 'class="suggestionImage"');
						$name = $listing->fields['products_name'];
						$model = $listing->fields['products_model'];
						$brand = zen_get_products_manufacturers_name($listing->fields['products_id']);
						$price = zen_get_products_display_price($listing->fields['products_id']);
						$link = zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($listing->fields['master_categories_id']) . '&products_id=' . $listing->fields['products_id']);
						if ($show_divider) { ?><hr /><?php }
						include('search_api_response.php');
						$listing->MoveNext();
						$show_divider = true;
					}
					$nextPage = (int)$result->number_of_pages != (int)$result->current_page_number;
					$prevPage = (int)$result->number_of_pages > 1 && (int)$result->current_page_number != 1;
					?>
					<?php
				}
			}
			break;
	}
}
require('includes/application_bottom.php');?>
