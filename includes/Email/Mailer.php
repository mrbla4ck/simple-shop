<?php
namespace SimpleShop\Email;

class Mailer {
    public static function send_order_confirmation($order_id) {
        $order = new \SimpleShop\Order\Order($order_id);
        $customer_email = $order->get_billing_email();
        
        $subject = sprintf(__('Order Confirmation #%s', 'simple-shop'), $order_id);
        $message = \SimpleShop\Utils\Template::load('emails/order-confirmation', [
            'order' => $order
        ]);

        self::send($customer_email, $subject, $message);
    }

    public static function send_low_stock_notification($product_id, $stock_quantity) {
        $admin_email = get_option('admin_email');
        $product = get_post($product_id);
        
        $subject = sprintf(__('Low Stock Alert: %s', 'simple-shop'), $product->post_title);
        $message = \SimpleShop\Utils\Template::load('emails/low-stock', [
            'product' => $product,
            'stock' => $stock_quantity
        ]);

        self::send($admin_email, $subject, $message);
    }

    private static function send($to, $subject, $message) {
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option('blogname') . ' <' . get_option('admin_email') . '>'
        ];

        wp_mail($to, $subject, $message, $headers);
    }
}