<?php
namespace SimpleShop\Shipping;

abstract class Method {
    abstract public function calculate_rate($package);
    abstract public function get_title();
    
    public function is_available($package) {
        return true;
    }

    public function get_settings_fields() {
        return [];
    }
}