<?php
namespace SimpleShop\Admin\Settings;

class Advanced {
    public function get_settings() {
        return [
            'performance' => [
                'title' => __('Performance', 'simple-shop'),
                'fields' => [
                    [
                        'id' => 'enable_caching',
                        'type' => 'checkbox',
                        'title' => __('Enable Caching', 'simple-shop'),
                        'default' => 'yes'
                    ],
                    [
                        'id' => 'cache_lifetime',
                        'type' => 'number',
                        'title' => __('Cache Lifetime (hours)', 'simple-shop'),
                        'default' => '24'
                    ],
                    [
                        'id' => 'image_optimization',
                        'type' => 'group',
                        'title' => __('Image Optimization', 'simple-shop'),
                        'fields' => [
                            [
                                'id' => 'enable_lazy_loading',
                                'type' => 'checkbox',
                                'title' => __('Enable Lazy Loading', 'simple-shop'),
                                'default' => 'yes'
                            ],
                            [
                                'id' => 'image_quality',
                                'type' => 'range',
                                'title' => __('Image Quality', 'simple-shop'),
                                'min' => 60,
                                'max' => 100,
                                'default' => 85
                            ]
                        ]
                    ]
                ]
            ],
            'security' => [
                'title' => __('Security', 'simple-shop'),
                'fields' => [
                    [
                        'id' => 'enable_captcha',
                        'type' => 'checkbox',
                        'title' => __('Enable CAPTCHA', 'simple-shop'),
                        'default' => 'yes'
                    ],
                    [
                        'id' => 'login_attempts',
                        'type' => 'number',
                        'title' => __('Max Login Attempts', 'simple-shop'),
                        'default' => '5'
                    ],
                    [
                        'id' => 'secure_checkout',
                        'type' => 'group',
                        'title' => __('Secure Checkout', 'simple-shop'),
                        'fields' => [
                            [
                                'id' => 'force_ssl',
                                'type' => 'checkbox',
                                'title' => __('Force SSL', 'simple-shop'),
                                'default' => 'yes'
                            ],
                            [
                                'id' => 'fraud_detection',
                                'type' => 'checkbox',
                                'title' => __('Enable Fraud Detection', 'simple-shop'),
                                'default' => 'yes'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}