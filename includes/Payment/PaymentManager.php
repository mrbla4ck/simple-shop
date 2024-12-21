<?php
namespace SimpleShop\Payment;

class PaymentManager {
    private $available_gateways = [];

    public function __construct() {
        add_action('init', [$this, 'load_gateways']);
        add_filter('simple_shop_payment_gateways', [$this, 'register_default_gateways']);
    }

    public function load_gateways() {
        $gateways = apply_filters('simple_shop_payment_gateways', []);
        foreach ($gateways as $gateway) {
            if (class_exists($gateway)) {
                $this->available_gateways[] = new $gateway();
            }
        }
    }

    public function register_default_gateways($gateways) {
        return array_merge($gateways, [
            \SimpleShop\Payment\Gateways\Stripe::class,
            \SimpleShop\Payment\Gateways\PayPal::class,
            \SimpleShop\Payment\Gateways\BankTransfer::class,
            \SimpleShop\Payment\Gateways\CashOnDelivery::class,
        ]);
    }

    public function get_available_gateways() {
        return array_filter($this->available_gateways, function($gateway) {
            return $gateway->is_available();
        });
    }

    public function process_payment($order_id, $gateway_id) {
        $gateway = $this->get_gateway($gateway_id);
        if (!$gateway) {
            throw new \Exception(__('Invalid payment method.', 'simple-shop'));
        }

        return $gateway->process_payment($order_id);
    }

    private function get_gateway($gateway_id) {
        foreach ($this->available_gateways as $gateway) {
            if ($gateway->get_id() === $gateway_id) {
                return $gateway;
            }
        }
        return null;
    }
}