<?php
namespace SimpleShop\UI;

class Alert {
    private static $initialized = false;

    public static function init() {
        if (self::$initialized) {
            return;
        }

        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_scripts']);
        self::$initialized = true;
    }

    public static function enqueue_scripts() {
        wp_enqueue_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', [], '11.0.0', true);
        wp_enqueue_script('simple-shop-alerts', SIMPLE_SHOP_PLUGIN_URL . 'assets/js/alerts.js', ['sweetalert2'], SIMPLE_SHOP_VERSION, true);
        
        wp_localize_script('simple-shop-alerts', 'simpleShopAlerts', [
            'defaultIcon' => 'success',
            'defaultTitle' => __('Success', 'simple-shop'),
            'defaultPosition' => 'center',
            'defaultShowConfirmButton' => true,
            'defaultTimer' => null
        ]);
    }

    public static function show($args) {
        if (is_string($args)) {
            $args = ['text' => $args];
        }

        $data = wp_parse_args($args, [
            'title' => null,
            'text' => '',
            'icon' => 'success',
            'position' => 'center',
            'timer' => null,
            'showConfirmButton' => true,
            'confirmButtonText' => __('OK', 'simple-shop'),
            'showCancelButton' => false,
            'cancelButtonText' => __('Cancel', 'simple-shop'),
            'customClass' => [
                'popup' => 'simple-shop-alert-popup',
                'title' => 'simple-shop-alert-title',
                'content' => 'simple-shop-alert-content',
                'confirmButton' => 'simple-shop-alert-confirm',
                'cancelButton' => 'simple-shop-alert-cancel'
            ]
        ]);

        return sprintf(
            '<script>SimpleShopAlert.show(%s);</script>',
            wp_json_encode($data)
        );
    }
}