<?php
namespace SimpleShop\Utils;

class Template {
    public static function load($template_name, $args = []) {
        if ($args) {
            extract($args);
        }

        $template_path = SIMPLE_SHOP_PLUGIN_DIR . 'templates/' . $template_name . '.php';
        
        if (file_exists($template_path)) {
            ob_start();
            include $template_path;
            return ob_get_clean();
        }

        return '';
    }

    public static function get_template_part($slug, $name = null) {
        $template = '';

        if ($name) {
            $template = self::load("{$slug}-{$name}");
        }

        if (!$template) {
            $template = self::load($slug);
        }

        return $template;
    }
}