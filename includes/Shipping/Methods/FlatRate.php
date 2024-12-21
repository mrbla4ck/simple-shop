<?php
namespace SimpleShop\Shipping\Methods;

use SimpleShop\Shipping\Method;

class FlatRate extends Method {
    public function calculate_rate($package) {
        $base_rate = floatval(get_option('simple_shop_flat_rate_cost', '5.00'));
        return $base_rate;
    }

    public function get_title() {
        return __('Flat Rate', 'simple-shop');
    }

    public function get_settings_fields() {
        return [
            [
                'id' => 'simple_shop_flat_rate_cost',
                'title' => __('Shipping Cost', 'simple-shop'),
                'type' => 'number',
                'default' => '5.00'
            ]
        ];
    }
}