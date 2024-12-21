<?php
namespace SimpleShop\Admin;

class Settings {
    public function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings() {
        register_setting('simple_shop_options', 'simple_shop_currency');
        register_setting('simple_shop_options', 'simple_shop_tax_rate');
        register_setting('simple_shop_options', 'simple_shop_payment_methods');

        add_settings_section(
            'simple_shop_general',
            __('General Settings', 'simple-shop'),
            [$this, 'render_general_section'],
            'simple_shop_settings'
        );

        add_settings_field(
            'simple_shop_currency',
            __('Currency', 'simple-shop'),
            [$this, 'render_currency_field'],
            'simple_shop_settings',
            'simple_shop_general'
        );

        add_settings_field(
            'simple_shop_tax_rate',
            __('Tax Rate (%)', 'simple-shop'),
            [$this, 'render_tax_rate_field'],
            'simple_shop_settings',
            'simple_shop_general'
        );
    }

    public function render_general_section() {
        echo '<p>' . __('Configure your store settings below.', 'simple-shop') . '</p>';
    }

    public function render_currency_field() {
        $currency = get_option('simple_shop_currency', 'USD');
        ?>
        <select name="simple_shop_currency">
            <option value="USD" <?php selected($currency, 'USD'); ?>>USD</option>
            <option value="EUR" <?php selected($currency, 'EUR'); ?>>EUR</option>
            <option value="GBP" <?php selected($currency, 'GBP'); ?>>GBP</option>
        </select>
        <?php
    }

    public function render_tax_rate_field() {
        $tax_rate = get_option('simple_shop_tax_rate', '0');
        ?>
        <input type="number" step="0.01" name="simple_shop_tax_rate" value="<?php echo esc_attr($tax_rate); ?>">
        <?php
    }
}