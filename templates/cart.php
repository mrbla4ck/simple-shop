<?php
get_header('shop');
?>

<div class="simple-shop-cart bg-gray-100 dark:bg-gray-900 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="simple-shop-card">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8"><?php esc_html_e('Shopping Cart', 'simple-shop'); ?></h1>

            <?php if (!SimpleShop\Cart::is_empty()): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="text-left py-4"><?php esc_html_e('Product', 'simple-shop'); ?></th>
                                <th class="text-left py-4"><?php esc_html_e('Price', 'simple-shop'); ?></th>
                                <th class="text-left py-4"><?php esc_html_e('Quantity', 'simple-shop'); ?></th>
                                <th class="text-left py-4"><?php esc_html_e('Total', 'simple-shop'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (SimpleShop\Cart::get_cart() as $cart_item): ?>
                                <tr class="border-b dark:border-gray-700">
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <?php echo get_the_post_thumbnail($cart_item['product_id'], 'thumbnail', ['class' => 'w-16 h-16 rounded']); ?>
                                            <div class="ml-4">
                                                <h3 class="font-medium dark:text-white"><?php echo get_the_title($cart_item['product_id']); ?></h3>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4"><?php echo SimpleShop\Utils\Money::format($cart_item['price']); ?></td>
                                    <td class="py-4">
                                        <input type="number" 
                                               value="<?php echo esc_attr($cart_item['quantity']); ?>" 
                                               min="1" 
                                               class="w-20 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                               data-product-id="<?php echo esc_attr($cart_item['product_id']); ?>">
                                    </td>
                                    <td class="py-4"><?php echo SimpleShop\Utils\Money::format($cart_item['total']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex justify-end">
                    <div class="w-full max-w-md">
                        <div class="simple-shop-card">
                            <h2 class="text-xl font-bold mb-4 dark:text-white"><?php esc_html_e('Cart Totals', 'simple-shop'); ?></h2>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="dark:text-gray-300"><?php esc_html_e('Subtotal', 'simple-shop'); ?></span>
                                    <span class="font-medium dark:text-white"><?php echo SimpleShop\Cart::get_subtotal_formatted(); ?></span>
                                </div>
                                <?php if (SimpleShop\Cart::get_tax_total() > 0): ?>
                                    <div class="flex justify-between">
                                        <span class="dark:text-gray-300"><?php esc_html_e('Tax', 'simple-shop'); ?></span>
                                        <span class="font-medium dark:text-white"><?php echo SimpleShop\Cart::get_tax_total_formatted(); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="flex justify-between border-t dark:border-gray-700 pt-2">
                                    <span class="font-bold dark:text-white"><?php esc_html_e('Total', 'simple-shop'); ?></span>
                                    <span class="font-bold dark:text-white"><?php echo SimpleShop\Cart::get_total_formatted(); ?></span>
                                </div>
                            </div>
                            <a href="<?php echo esc_url(SimpleShop\Checkout::get_checkout_url()); ?>" 
                               class="simple-shop-button-3d block text-center mt-6">
                                <?php esc_html_e('Proceed to Checkout', 'simple-shop'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <p class="text-xl dark:text-white mb-6"><?php esc_html_e('Your cart is empty.', 'simple-shop'); ?></p>
                    <a href="<?php echo esc_url(get_permalink(get_option('simple_shop_shop_page_id'))); ?>" 
                       class="simple-shop-button-3d inline-block">
                        <?php esc_html_e('Return to Shop', 'simple-shop'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
get_footer('shop');
?>