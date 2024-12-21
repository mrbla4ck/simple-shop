<?php
namespace SimpleShop\Discount;

class Coupon {
    private $id;
    private $code;
    private $type;
    private $amount;
    private $expiry_date;
    private $minimum_spend;
    private $maximum_spend;
    private $usage_limit;
    private $usage_count;

    public function __construct($coupon_id) {
        $this->id = $coupon_id;
        $this->load();
    }

    private function load() {
        $post = get_post($this->id);
        if (!$post || $post->post_type !== 'simple_shop_coupon') {
            return;
        }

        $this->code = $post->post_title;
        $this->type = get_post_meta($this->id, '_coupon_type', true);
        $this->amount = get_post_meta($this->id, '_coupon_amount', true);
        $this->expiry_date = get_post_meta($this->id, '_coupon_expiry', true);
        $this->minimum_spend = get_post_meta($this->id, '_coupon_min_spend', true);
        $this->maximum_spend = get_post_meta($this->id, '_coupon_max_spend', true);
        $this->usage_limit = get_post_meta($this->id, '_coupon_usage_limit', true);
        $this->usage_count = get_post_meta($this->id, '_coupon_usage_count', true);
    }

    public function is_valid($cart_total = 0) {
        if (!$this->id) {
            return false;
        }

        if ($this->is_expired()) {
            return false;
        }

        if ($this->has_reached_usage_limit()) {
            return false;
        }

        if (!$this->meets_spend_requirements($cart_total)) {
            return false;
        }

        return true;
    }

    public function apply_discount($amount) {
        if ($this->type === 'percentage') {
            return $amount * ($this->amount / 100);
        }
        return min($amount, $this->amount);
    }

    private function is_expired() {
        if (!$this->expiry_date) {
            return false;
        }
        return strtotime($this->expiry_date) < current_time('timestamp');
    }

    private function has_reached_usage_limit() {
        if (!$this->usage_limit) {
            return false;
        }
        return $this->usage_count >= $this->usage_limit;
    }

    private function meets_spend_requirements($cart_total) {
        if ($this->minimum_spend && $cart_total < $this->minimum_spend) {
            return false;
        }
        if ($this->maximum_spend && $cart_total > $this->maximum_spend) {
            return false;
        }
        return true;
    }

    public function increment_usage() {
        $this->usage_count++;
        update_post_meta($this->id, '_coupon_usage_count', $this->usage_count);
    }
}