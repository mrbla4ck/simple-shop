<?php
namespace SimpleShop\Inventory;

class Stock {
    public static function update_stock($product_id, $quantity_change) {
        $current_stock = self::get_stock_quantity($product_id);
        $new_stock = $current_stock + $quantity_change;
        
        update_post_meta($product_id, '_simple_shop_stock', max(0, $new_stock));
        
        if ($new_stock <= get_option('simple_shop_low_stock_threshold', 5)) {
            do_action('simple_shop_low_stock_notification', $product_id, $new_stock);
        }
    }

    public static function get_stock_quantity($product_id) {
        return (int) get_post_meta($product_id, '_simple_shop_stock', true);
    }

    public static function is_in_stock($product_id) {
        return self::get_stock_quantity($product_id) > 0;
    }

    public static function can_reduce_stock($product_id, $quantity) {
        return self::get_stock_quantity($product_id) >= $quantity;
    }
}