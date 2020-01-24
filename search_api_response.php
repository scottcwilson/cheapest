<div class="suggestion">
	<a href="<?php echo zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($listing->fields['master_categories_id']) . '&products_id=' . $listing->fields['products_id']); ?>">
		<?php echo zen_image(DIR_WS_IMAGES . $listing->fields['products_image'], $listing->fields['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="suggestionImage"'); ?>
    	<div class="search-info">
			<span class="product-name"><?php echo $listing->fields['products_name']; ?></span>
			<span class="model"><?php echo $listing->fields['products_model']; ?></span>
			<span class="brand"><?php echo zen_get_products_manufacturers_name($listing->fields['products_id']); ?></span>
            <span class="content_price"><?php echo zen_get_products_display_price($listing->fields['products_id']); ?></span>
		</div>
	    <br style="clear:both;" />
    </a>
</div>
