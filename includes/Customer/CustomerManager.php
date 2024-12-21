<?php
namespace SimpleShop\Customer;

class CustomerManager {
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('show_user_profile', [$this, 'add_customer_fields']);
        add_action('edit_user_profile', [$this, 'add_customer_fields']);
    }

    public function init() {
        // Implementation
    }

    public function add_customer_fields($user) {
        // Implementation
    }
}