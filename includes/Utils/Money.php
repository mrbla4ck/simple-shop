<?php
namespace SimpleShop\Utils;

class Money {
    public static function format($amount, $currency = null) {
        if ($currency === null) {
            $currency = get_option('simple_shop_currency', 'USD');
        }

        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£'
        ];

        $symbol = isset($symbols[$currency]) ? $symbols[$currency] : $currency;
        return sprintf('%s%0.2f', $symbol, floatval($amount));
    }

    public static function calculateTax($amount) {
        $tax_rate = floatval(get_option('simple_shop_tax_rate', '0'));
        return $amount * ($tax_rate / 100);
    }
}