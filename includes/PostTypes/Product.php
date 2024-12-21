<?php
namespace SimpleShop\PostTypes;

class Product {
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
    }

    public function register_post_type() {
        register_post_type('simple_shop_product', [
            'labels' => [
                'name' => __('Products', 'simple-shop'),
                'singular_name' => __('Product', 'simple-shop'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'menu_icon' => 'dashicons-products',
        ]);
    }

    public function register_taxonomies() {
        register_taxonomy('simple_shop_category', 'simple_shop_product', [
            'labels' => [
                'name' => __('Categories', 'simple-shop'),
                'singular_name' => __('Category', 'simple-shop'),
            ],
            'hierarchical' => true,
            'show_admin_column' => true,
        ]);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'simple_shop_product_data',
            __('Product Data', 'simple-shop'),
            [$this, 'render_meta_box'],
            'simple_shop_product'
        );
    }

    public function render_meta_box($post) {
        $price = get_post_meta($post->ID, '_simple_shop_price', true);
        $sku = get_post_meta($post->ID, '_simple_shop_sku', true);
        
        wp_nonce_field('simple_shop_product_meta_box', 'simple_shop_product_meta_box_nonce');
        ?>
        <p>
            <label for="simple_shop_price"><?php _e('Price', 'simple-shop'); ?></label>
            <input type="number" step="0.01" id="simple_shop_price" name="simple_shop_price" value="<?php echo esc_attr($price); ?>">
        </p>
        <p>
            <label for="simple_shop_sku"><?php _e('SKU', 'simple-shop'); ?></label>
            <input type="text" id="simple_shop_sku" name="simple_shop_sku" value="<?php echo esc_attr($sku); ?>">
        </p>
        <?php
    }

    public function save_meta_boxes($post_id) {
        if (!isset($_POST['simple_shop_product_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['simple_shop_product_meta_box_nonce'], 'simple_shop_product_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['simple_shop_price'])) {
            update_post_meta($post_id, '_simple_shop_price', sanitize_text_field($_POST['simple_shop_price']));
        }

        if (isset($_POST['simple_shop_sku'])) {
            update_post_meta($post_id, '_simple_shop_sku', sanitize_text_field($_POST['simple_shop_sku']));
        }
    }
}