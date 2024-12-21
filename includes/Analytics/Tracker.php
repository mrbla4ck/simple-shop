<?php
namespace SimpleShop\Analytics;

class Tracker {
    public static function track_product_view($product_id) {
        $views = (int) get_post_meta($product_id, '_simple_shop_views', true);
        update_post_meta($product_id, '_simple_shop_views', $views + 1);
    }

    public static function track_add_to_cart($product_id) {
        $adds = (int) get_post_meta($product_id, '_simple_shop_cart_adds', true);
        update_post_meta($product_id, '_simple_shop_cart_adds', $adds + 1);
    }

    public static function track_purchase($product_id, $quantity, $total) {
        $purchases = (int) get_post_meta($product_id, '_simple_shop_purchases', true);
        $revenue = (float) get_post_meta($product_id, '_simple_shop_revenue', true);
        
        update_post_meta($product_id, '_simple_shop_purchases', $purchases + $quantity);
        update_post_meta($product_id, '_simple_shop_revenue', $revenue + $total);
    }

    public static function get_product_stats($product_id) {
        return [
            'views' => (int) get_post_meta($product_id, '_simple_shop_views', true),
            'cart_adds' => (int) get_post_meta($product_id, '_simple_shop_cart_adds', true),
            'purchases' => (int) get_post_meta($product_id, '_simple_shop_purchases', true),
            'revenue' => (float) get_post_meta($product_id, '_simple_shop_revenue', true)
        ];
    }
}