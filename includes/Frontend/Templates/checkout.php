<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');
?>

<div class="simple-shop-checkout">
    <div class="checkout-container">
        <?php if (!SimpleShop\Cart::is_empty()) : ?>
            <form id="simple-shop-checkout-form" class="checkout-form">
                <div class="form-row">
                    <div class="billing-details">
                        <h3><?php _e('Billing Details', 'simple-shop'); ?></h3>
                        
                        <div class="form-group">
                            <label for="billing_first_name"><?php _e('First Name', 'simple-shop'); ?> *</label>
                            <input type="text" id="billing_first_name" name="billing_first_name" required>
                        </div>

                        <div class="form-group">
                            <label for="billing_last_name"><?php _e('Last Name', 'simple-shop'); ?> *</label>
                            <input type="text" id="billing_last_name" name="billing_last_name" required>
                        </div>

                        <div class="form-group">
                            <label for="billing_email"><?php _e('Email', 'simple-shop'); ?> *</label>
                            <input type="email" id="billing_email" name="billing_email" required>
                        </div>

                        <div class="form-group">
                            <label for="billing_phone"><?php _e('Phone', 'simple-shop'); ?> *</label>
                            <input type="tel" id="billing_phone" name="billing_phone" required>
                        </div>

                        <div class="form-group">
                            <label for="billing_country"><?php _e('Country', 'simple-shop'); ?> *</label>
                            <?php
                            $countries = \SimpleShop\Utils\Countries::get_countries();
                            echo '<select id="billing_country" name="billing_country" required>';
                            foreach ($countries as $code => $name) {
                                echo '<option value="' . esc_attr($code) . '">' . esc_html($name) . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </div>

                        <div class="form-group">
                            <label for="billing_address_1"><?php _e('Street Address', 'simple-shop'); ?> *</label>
                            <input type="text" id="billing_address_1" name="billing_address_1" required>
                        </div>

                        <div class="form-group">
                            <label for="billing_city"><?php _e('Town / City', 'simple-shop'); ?> *</label>
                            <input type="text" id="billing_city" name="billing_city" required>
                        </div>

                        <div class="form-group">
                            <label for="billing_state"><?php _e('State', 'simple-shop'); ?> *</label>
                            <input type="text" id="billing_state" name="billing_state" required>
                        </div>

                        <div class="form-group">
                            <label for="billing_postcode"><?php _e('Postcode / ZIP', 'simple-shop'); ?> *</label>
                            <input type="text" id="billing_postcode" name="billing_postcode" required>
                        </div>
                    </div>

                    <div class="shipping-details">
                        <h3>
                            <?php _e('Ship to a different address?', 'simple-shop'); ?>
                            <input type="checkbox" id="ship_to_different" name="ship_to_different">
                        </h3>
                        
                        <div id="shipping_fields" style="display: none;">
                            <!-- Shipping fields similar to billing fields -->
                        </div>
                    </div>
                </div>

                <div class="order-review">
                    <h3><?php _e('Your Order', 'simple-shop'); ?></h3>
                    
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th><?php _e('Product', 'simple-shop'); ?></th>
                                <th><?php _e('Total', 'simple-shop'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cart = SimpleShop\Cart::get_cart();
                            foreach ($cart as $cart_item) {
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        echo esc_html($cart_item['data']->get_name());
                                        echo ' × ' . esc_html($cart_item['quantity']);
                                        ?>
                                    </td>
                                    <td><?php echo SimpleShop\Utils\Money::format($cart_item['line_total']); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="cart-subtotal">
                                <th><?php _e('Subtotal', 'simple-shop'); ?></th>
                                <td><?php echo SimpleShop\Utils\Money::format(SimpleShop\Cart::get_subtotal()); ?></td>
                            </tr>
                            
                            <?php if (SimpleShop\Cart::get_tax_total() > 0) : ?>
                                <tr class="tax-total">
                                    <th><?php _e('Tax', 'simple-shop'); ?></th>
                                    <td><?php echo SimpleShop\Utils\Money::format(SimpleShop\Cart::get_tax_total()); ?></td>
                                </tr>
                            <?php endif; ?>

                            <?php if (SimpleShop\Cart::get_shipping_total() > 0) : ?>
                                <tr class="shipping-total">
                                    <th><?php _e('Shipping', 'simple-shop'); ?></th>
                                    <td><?php echo SimpleShop\Utils\Money::format(SimpleShop\Cart::get_shipping_total()); ?></td>
                                </tr>
                            <?php endif; ?>

                            <tr class="order-total">
                                <th><?php _e('Total', 'simple-shop'); ?></th>
                                <td><?php echo SimpleShop\Utils\Money::format(SimpleShop\Cart::get_total()); ?></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="payment-methods">
                        <h3><?php _e('Payment Method', 'simple-shop'); ?></h3>
                        <?php
                        $available_gateways = SimpleShop\Payment\Gateways::get_available_gateways();
                        foreach ($available_gateways as $gateway_id => $gateway) {
                            ?>
                            <div class="payment-method">
                                <input type="radio" 
                                       id="payment_method_<?php echo esc_attr($gateway_id); ?>" 
                                       name="payment_method" 
                                       value="<?php echo esc_attr($gateway_id); ?>"
                                       <?php checked($gateway_id, SimpleShop\Payment\Gateways::get_default_gateway()); ?>>
                                <label for="payment_method_<?php echo esc_attr($gateway_id); ?>">
                                    <?php echo esc_html($gateway->get_title()); ?>
                                    <?php if ($gateway->get_icon()) : ?>
                                        <img src="<?php echo esc_url($gateway->get_icon()); ?>" alt="<?php echo esc_attr($gateway->get_title()); ?>">
                                    <?php endif; ?>
                                </label>
                                <?php if ($gateway->has_fields()) : ?>
                                    <div class="payment-method-fields" id="payment_method_fields_<?php echo esc_attr($gateway_id); ?>">
                                        <?php $gateway->payment_fields(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="place-order">
                        <button type="submit" class="simple-shop-button-3d checkout-button">
                            <?php _e('Place Order', 'simple-shop'); ?>
                        </button>
                    </div>
                </div>
            </form>
        <?php else : ?>
            <p class="cart-empty">
                <?php _e('Your cart is currently empty.', 'simple-shop'); ?>
            </p>
            <p class="return-to-shop">
                <a class="simple-shop-button-3d" href="<?php echo esc_url(get_permalink(get_option('simple_shop_shop_page_id'))); ?>">
                    <?php _e('Return to shop', 'simple-shop'); ?>
                </a>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer('shop');
?>