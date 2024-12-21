<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');

while (have_posts()) :
    the_post();
    global $product;
    ?>
    <div class="simple-shop-single-product">
        <div class="product-gallery">
            <?php
            if (has_post_thumbnail()) {
                $image_id = get_post_thumbnail_id();
                $full_size_image = wp_get_attachment_image_src($image_id, 'full');
                $thumbnail = wp_get_attachment_image_src($image_id, 'shop_thumbnail');
                
                echo '<div class="product-gallery-main">';
                echo '<div class="simple-shop-product-image">';
                echo get_the_post_thumbnail($post->ID, 'shop_single', array(
                    'title' => get_post_field('post_title', $image_id),
                    'data-large_image' => $full_size_image[0],
                    'data-large_image_width' => $full_size_image[1],
                    'data-large_image_height' => $full_size_image[2],
                ));
                echo '</div>';
                echo '</div>';

                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids) {
                    echo '<div class="product-gallery-thumbs">';
                    foreach ($attachment_ids as $attachment_id) {
                        echo wp_get_attachment_image($attachment_id, 'shop_thumbnail');
                    }
                    echo '</div>';
                }
            }
            ?>
        </div>

        <div class="product-details">
            <h1 class="product-title"><?php the_title(); ?></h1>
            
            <div class="product-price">
                <?php echo $product->get_price_html(); ?>
            </div>

            <div class="product-description">
                <?php the_content(); ?>
            </div>

            <?php if ($product->is_in_stock()) : ?>
                <form class="cart" method="post" enctype="multipart/form-data">
                    <?php
                    if ($product->is_type('variable')) {
                        woocommerce_variable_add_to_cart();
                    } else {
                        echo '<div class="quantity">';
                        echo '<label for="quantity">' . __('Quantity', 'simple-shop') . '</label>';
                        echo '<input type="number" id="quantity" name="quantity" value="1" min="1" max="' . $product->get_stock_quantity() . '">';
                        echo '</div>';
                    }
                    ?>
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="simple-shop-button-3d add-to-cart">
                        <?php echo esc_html($product->single_add_to_cart_text()); ?>
                    </button>
                </form>
            <?php else : ?>
                <p class="stock out-of-stock"><?php _e('Out of stock', 'simple-shop'); ?></p>
            <?php endif; ?>

            <div class="product-meta">
                <?php
                $categories = get_the_terms($post->ID, 'product_cat');
                if ($categories) {
                    echo '<span class="posted_in">' . __('Category:', 'simple-shop') . ' ';
                    $cat_links = array();
                    foreach ($categories as $category) {
                        $cat_links[] = '<a href="' . get_term_link($category) . '">' . $category->name . '</a>';
                    }
                    echo implode(', ', $cat_links) . '</span>';
                }
                ?>
            </div>
        </div>

        <div class="product-tabs">
            <ul class="tabs">
                <li class="active" data-tab="description"><?php _e('Description', 'simple-shop'); ?></li>
                <li data-tab="additional"><?php _e('Additional Information', 'simple-shop'); ?></li>
                <li data-tab="reviews"><?php _e('Reviews', 'simple-shop'); ?></li>
            </ul>

            <div class="tab-content">
                <div id="tab-description" class="tab-panel active">
                    <?php the_content(); ?>
                </div>

                <div id="tab-additional" class="tab-panel">
                    <?php do_action('simple_shop_product_additional_info'); ?>
                </div>

                <div id="tab-reviews" class="tab-panel">
                    <?php
                    if (comments_open()) {
                        comments_template();
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php
        $related_products = \SimpleShop\Product\Related::get_related_products($product->get_id(), 4);
        if ($related_products) {
            echo '<div class="related-products">';
            echo '<h2>' . __('Related Products', 'simple-shop') . '</h2>';
            echo '<div class="products">';
            foreach ($related_products as $related_product) {
                \SimpleShop\Frontend\ProductDisplay::render_product_card($related_product);
            }
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
<?php
endwhile;

get_footer('shop');
?>