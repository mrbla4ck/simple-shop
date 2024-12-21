<?php
namespace SimpleShop\Order;

class Status {
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
    const REFUNDED = 'refunded';
    const ON_HOLD = 'on-hold';
    const FAILED = 'failed';

    public static function get_statuses() {
        return [
            self::PENDING => __('Pending payment', 'simple-shop'),
            self::PROCESSING => __('Processing', 'simple-shop'),
            self::COMPLETED => __('Completed', 'simple-shop'),
            self::CANCELLED => __('Cancelled', 'simple-shop'),
            self::REFUNDED => __('Refunded', 'simple-shop'),
            self::ON_HOLD => __('On hold', 'simple-shop'),
            self::FAILED => __('Failed', 'simple-shop'),
        ];
    }

    public static function update_status($order_id, $new_status) {
        $old_status = get_post_meta($order_id, '_status', true);
        
        if ($old_status !== $new_status) {
            update_post_meta($order_id, '_status', $new_status);
            do_action('simple_shop_order_status_' . $new_status, $order_id);
            do_action('simple_shop_order_status_changed', $order_id, $old_status, $new_status);
        }
    }
}