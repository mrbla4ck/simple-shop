<?php
namespace SimpleShop\Core;

class Setup {
    public static function init() {
        self::define_constants();
        self::check_environment();
        self::init_hooks();
    }

    private static function define_constants() {
        $constants = [
            'SIMPLE_SHOP_VERSION' => '1.0.0',
            'SIMPLE_SHOP_DB_VERSION' => '1.0.0',
            'SIMPLE_SHOP_PLUGIN_FILE' => dirname(dirname(dirname(__FILE__))) . '/simple-shop.php',
            'SIMPLE_SHOP_PLUGIN_BASENAME' => plugin_basename(SIMPLE_SHOP_PLUGIN_FILE),
            'SIMPLE_SHOP_PLUGIN_DIR' => plugin_dir_path(SIMPLE_SHOP_PLUGIN_FILE),
            'SIMPLE_SHOP_PLUGIN_URL' => plugin_dir_url(SIMPLE_SHOP_PLUGIN_FILE),
            'SIMPLE_SHOP_TEMPLATE_DEBUG_MODE' => false
        ];

        foreach ($constants as $name => $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }

    private static function check_environment() {
        if (version_compare(PHP_VERSION, '7.2', '<')) {
            add_action('admin_notices', [__CLASS__, 'php_version_notice']);
            return false;
        }

        if (version_compare($GLOBALS['wp_version'], '5.6', '<')) {
            add_action('admin_notices', [__CLASS__, 'wp_version_notice']);
            return false;
        }

        return true;
    }

    private static function init_hooks() {
        add_action('init', [__CLASS__, 'load_plugin_textdomain']);
        add_action('plugins_loaded', [__CLASS__, 'init_plugin']);
    }

    public static function load_plugin_textdomain() {
        load_plugin_textdomain('simple-shop', false, dirname(plugin_basename(SIMPLE_SHOP_PLUGIN_FILE)) . '/languages/');
    }

    public static function init_plugin() {
        if (self::check_environment()) {
            Plugin::instance();
        }
    }

    public static function php_version_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('SimpleShop requires PHP version 7.2 or higher.', 'simple-shop'); ?></p>
        </div>
        <?php
    }

    public static function wp_version_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('SimpleShop requires WordPress version 5.6 or higher.', 'simple-shop'); ?></p>
        </div>
        <?php
    }
}