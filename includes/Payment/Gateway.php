<?php
namespace SimpleShop\Payment;

abstract class Gateway {
    abstract public function process_payment($order_id);
    abstract public function get_title();
    abstract public function get_description();
    
    public function validate_fields() {
        return true;
    }

    public function has_fields() {
        return false;
    }

    public function get_icon() {
        return '';
    }
}