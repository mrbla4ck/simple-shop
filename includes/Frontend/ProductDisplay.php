<?php
namespace SimpleShop\Frontend;

class ProductDisplay {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_filter('simple_shop_product_card_html', [$this, 'render_3d_product_card'], 10, 2);
    }

    public function enqueue_styles() {
        wp_enqueue_style('simple-shop-3d', SIMPLE_SHOP_PLUGIN_URL . 'assets/css/3d-design.css', [], SIMPLE_SHOP_VERSION);
        wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
    }

    public function render_3d_product_card($product_id) {
        $product = get_post($product_id);
        $price = get_post_meta($product_id, '_simple_shop_price', true);
        $image = get_the_post_thumbnail_url($product_id, 'large');

        ob_start();
        ?>
        <div class="simple-shop-product-card">
            <div class="simple-shop-product-image">
                <?php if ($image): ?>
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($product->post_title); ?>">
                <?php endif; ?>
            </div>
            <div class="simple-shop-product-details">
                <h2><?php echo esc_html($product->post_title); ?></h2>
                <div class="simple-shop-product-price">
                    <?php echo \SimpleShop\Utils\Money::format($price); ?>
                </div>
                <button class="simple-shop-button-3d add-to-cart" data-product-id="<?php echo esc_attr($product_id); ?>">
                    <?php _e('Add to Cart', 'simple-shop'); ?>
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}