<?php
namespace SimpleShop\Admin\Settings;

class Payments {
    public function get_settings() {
        return [
            'payment_gateways' => [
                'title' => __('Payment Gateways', 'simple-shop'),
                'fields' => [
                    [
                        'id' => 'stripe_settings',
                        'type' => 'group',
                        'title' => __('Stripe Settings', 'simple-shop'),
                        'fields' => [
                            [
                                'id' => 'enable_stripe',
                                'type' => 'checkbox',
                                'title' => __('Enable Stripe', 'simple-shop'),
                                'default' => 'yes'
                            ],
                            [
                                'id' => 'stripe_mode',
                                'type' => 'select',
                                'title' => __('Mode', 'simple-shop'),
                                'options' => [
                                    'test' => __('Test Mode', 'simple-shop'),
                                    'live' => __('Live Mode', 'simple-shop')
                                ],
                                'default' => 'test'
                            ],
                            [
                                'id' => 'stripe_test_key',
                                'type' => 'text',
                                'title' => __('Test Public Key', 'simple-shop')
                            ],
                            [
                                'id' => 'stripe_test_secret',
                                'type' => 'password',
                                'title' => __('Test Secret Key', 'simple-shop')
                            ],
                            [
                                'id' => 'stripe_live_key',
                                'type' => 'text',
                                'title' => __('Live Public Key', 'simple-shop')
                            ],
                            [
                                'id' => 'stripe_live_secret',
                                'type' => 'password',
                                'title' => __('Live Secret Key', 'simple-shop')
                            ]
                        ]
                    ],
                    [
                        'id' => 'paypal_settings',
                        'type' => 'group',
                        'title' => __('PayPal Settings', 'simple-shop'),
                        'fields' => [
                            [
                                'id' => 'enable_paypal',
                                'type' => 'checkbox',
                                'title' => __('Enable PayPal', 'simple-shop'),
                                'default' => 'yes'
                            ],
                            [
                                'id' => 'paypal_mode',
                                'type' => 'select',
                                'title' => __('Mode', 'simple-shop'),
                                'options' => [
                                    'sandbox' => __('Sandbox', 'simple-shop'),
                                    'live' => __('Live', 'simple-shop')
                                ],
                                'default' => 'sandbox'
                            ],
                            [
                                'id' => 'paypal_client_id',
                                'type' => 'text',
                                'title' => __('Client ID', 'simple-shop')
                            ],
                            [
                                'id' => 'paypal_secret',
                                'type' => 'password',
                                'title' => __('Secret', 'simple-shop')
                            ]
                        ]
                    ],
                    [
                        'id' => 'crypto_settings',
                        'type' => 'group',
                        'title' => __('Cryptocurrency Settings', 'simple-shop'),
                        'fields' => [
                            [
                                'id' => 'enable_crypto',
                                'type' => 'checkbox',
                                'title' => __('Enable Cryptocurrency Payments', 'simple-shop'),
                                'default' => 'no'
                            ],
                            [
                                'id' => 'accepted_coins',
                                'type' => 'multiselect',
                                'title' => __('Accepted Cryptocurrencies', 'simple-shop'),
                                'options' => [
                                    'btc' => __('Bitcoin', 'simple-shop'),
                                    'eth' => __('Ethereum', 'simple-shop'),
                                    'usdt' => __('USDT', 'simple-shop'),
                                    'bnb' => __('Binance Coin', 'simple-shop')
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}