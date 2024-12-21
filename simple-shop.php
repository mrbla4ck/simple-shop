<?php
/**
 * Plugin Name: SimpleShop
 * Plugin URI: https://example.com/simple-shop
 * Description: A lightweight and extensible e-commerce solution for WordPress
 * Version: 1.0.0
 * Requires at least: 5.6
 * Requires PHP: 7.2
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: simple-shop
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

// Autoloader
require_once __DIR__ . '/includes/class-simple-shop-autoloader.php';
SimpleShop\Autoloader::register();

// Initialize the plugin
add_action('plugins_loaded', function() {
    SimpleShop\Core\Bootstrap::instance();
});

// Activation hook
register_activation_hook(__FILE__, function() {
    SimpleShop\Install\Installer::activate();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    SimpleShop\Install\Installer::deactivate();
});