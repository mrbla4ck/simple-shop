<?php
namespace SimpleShop\PostTypes;

class Order {
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
    }

    public function register_post_type() {
        register_post_type('simple_shop_order', [
            'labels' => [
                'name' => __('Orders', 'simple-shop'),
                'singular_name' => __('Order', 'simple-shop'),
                'menu_name' => __('Orders', 'simple-shop'),
                'add_new' => __('Add New', 'simple-shop'),
                'add_new_item' => __('Add New Order', 'simple-shop'),
                'edit_item' => __('Edit Order', 'simple-shop'),
                'new_item' => __('New Order', 'simple-shop'),
                'view_item' => __('View Order', 'simple-shop'),
                'search_items' => __('Search Orders', 'simple-shop'),
                'not_found' => __('No orders found', 'simple-shop'),
                'not_found_in_trash' => __('No orders found in trash', 'simple-shop'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'simple-shop',
            'capability_type' => 'post',
            'capabilities' => [
                'create_posts' => false,
            ],
            'map_meta_cap' => true,
            'supports' => ['title'],
            'menu_position' => 58,
        ]);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'simple_shop_order_details',
            __('Order Details', 'simple-shop'),
            [$this, 'render_order_details_meta_box'],
            'simple_shop_order'
        );

        add_meta_box(
            'simple_shop_order_items',
            __('Order Items', 'simple-shop'),
            [$this, 'render_order_items_meta_box'],
            'simple_shop_order'
        );

        add_meta_box(
            'simple_shop_order_customer',
            __('Customer Details', 'simple-shop'),
            [$this, 'render_customer_meta_box'],
            'simple_shop_order'
        );
    }

    public function render_order_details_meta_box($post) {
        $order_status = get_post_meta($post->ID, '_status', true);
        $order_total = get_post_meta($post->ID, '_total', true);
        $payment_method = get_post_meta($post->ID, '_payment_method', true);
        
        wp_nonce_field('simple_shop_order_meta_box', 'simple_shop_order_meta_box_nonce');
        ?>
        <p>
            <label for="order_status"><?php _e('Order Status:', 'simple-shop'); ?></label>
            <select id="order_status" name="order_status">
                <?php foreach (\SimpleShop\Order\Status::get_statuses() as $status => $label): ?>
                    <option value="<?php echo esc_attr($status); ?>" <?php selected($order_status, $status); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label><?php _e('Order Total:', 'simple-shop'); ?></label>
            <strong><?php echo \SimpleShop\Utils\Money::format($order_total); ?></strong>
        </p>
        <p>
            <label><?php _e('Payment Method:', 'simple-shop'); ?></label>
            <strong><?php echo esc_html($payment_method); ?></strong>
        </p>
        <?php
    }

    public function render_order_items_meta_box($post) {
        $items = get_post_meta($post->ID, '_items', true);
        if (!empty($items)): ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Product', 'simple-shop'); ?></th>
                        <th><?php _e('Quantity', 'simple-shop'); ?></th>
                        <th><?php _e('Price', 'simple-shop'); ?></th>
                        <th><?php _e('Total', 'simple-shop'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo esc_html(get_the_title($item['product_id'])); ?></td>
                            <td><?php echo esc_html($item['quantity']); ?></td>
                            <td><?php echo \SimpleShop\Utils\Money::format($item['price']); ?></td>
                            <td><?php echo \SimpleShop\Utils\Money::format($item['total']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif;
    }

    public function render_customer_meta_box($post) {
        $billing_name = get_post_meta($post->ID, '_billing_name', true);
        $billing_email = get_post_meta($post->ID, '_billing_email', true);
        $billing_address = get_post_meta($post->ID, '_billing_address', true);
        ?>
        <p>
            <label><?php _e('Name:', 'simple-shop'); ?></label>
            <strong><?php echo esc_html($billing_name); ?></strong>
        </p>
        <p>
            <label><?php _e('Email:', 'simple-shop'); ?></label>
            <strong><?php echo esc_html($billing_email); ?></strong>
        </p>
        <p>
            <label><?php _e('Address:', 'simple-shop'); ?></label>
            <strong><?php echo esc_html($billing_address); ?></strong>
        </p>
        <?php
    }

    public function save_meta_boxes($post_id) {
        if (!isset($_POST['simple_shop_order_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['simple_shop_order_meta_box_nonce'], 'simple_shop_order_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['order_status'])) {
            $old_status = get_post_meta($post_id, '_status', true);
            $new_status = sanitize_text_field($_POST['order_status']);
            
            if ($old_status !== $new_status) {
                update_post_meta($post_id, '_status', $new_status);
                do_action('simple_shop_order_status_changed', $post_id, $old_status, $new_status);
            }
        }
    }
}