<?php
namespace SimpleShop\Utils;

class Logger {
    private static $log_dir;

    public static function init() {
        self::$log_dir = SIMPLE_SHOP_PLUGIN_DIR . 'logs/';
        if (!file_exists(self::$log_dir)) {
            wp_mkdir_p(self::$log_dir);
        }
    }

    public static function log($message, $level = 'info') {
        $timestamp = current_time('mysql');
        $log_entry = sprintf("[%s] [%s] %s\n", $timestamp, strtoupper($level), $message);
        error_log($log_entry, 3, self::$log_dir . 'simple-shop-' . date('Y-m-d') . '.log');
    }
}