<?php
namespace SimpleShop\Admin\Settings;

class General {
    public function get_settings() {
        return [
            'shop_settings' => [
                'title' => __('Shop Settings', 'simple-shop'),
                'fields' => [
                    [
                        'id' => 'simple_shop_currency',
                        'type' => 'select',
                        'title' => __('Currency', 'simple-shop'),
                        'options' => \SimpleShop\Utils\Currency::get_currencies(),
                        'default' => 'USD'
                    ],
                    [
                        'id' => 'simple_shop_tax_options',
                        'type' => 'group',
                        'title' => __('Tax Options', 'simple-shop'),
                        'fields' => [
                            [
                                'id' => 'enable_tax',
                                'type' => 'checkbox',
                                'title' => __('Enable Tax', 'simple-shop'),
                                'default' => 'yes'
                            ],
                            [
                                'id' => 'tax_based_on',
                                'type' => 'select',
                                'title' => __('Tax Based On', 'simple-shop'),
                                'options' => [
                                    'shipping' => __('Shipping Address', 'simple-shop'),
                                    'billing' => __('Billing Address', 'simple-shop'),
                                    'base' => __('Shop Base Address', 'simple-shop')
                                ],
                                'default' => 'shipping'
                            ],
                            [
                                'id' => 'tax_display',
                                'type' => 'select',
                                'title' => __('Display Prices', 'simple-shop'),
                                'options' => [
                                    'incl' => __('Including tax', 'simple-shop'),
                                    'excl' => __('Excluding tax', 'simple-shop')
                                ],
                                'default' => 'excl'
                            ]
                        ]
                    ],
                    [
                        'id' => 'simple_shop_inventory',
                        'type' => 'group',
                        'title' => __('Inventory', 'simple-shop'),
                        'fields' => [
                            [
                                'id' => 'manage_stock',
                                'type' => 'checkbox',
                                'title' => __('Manage Stock', 'simple-shop'),
                                'default' => 'yes'
                            ],
                            [
                                'id' => 'low_stock_threshold',
                                'type' => 'number',
                                'title' => __('Low Stock Threshold', 'simple-shop'),
                                'default' => '5'
                            ],
                            [
                                'id' => 'out_of_stock_visibility',
                                'type' => 'select',
                                'title' => __('Out of Stock Products', 'simple-shop'),
                                'options' => [
                                    'show' => __('Show in catalog', 'simple-shop'),
                                    'hide' => __('Hide from catalog', 'simple-shop')
                                ],
                                'default' => 'show'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}