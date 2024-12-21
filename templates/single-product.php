<?php
get_header('shop');

while (have_posts()) :
    the_post();
    global $product;
?>
<div class="simple-shop-single-product bg-gray-100 dark:bg-gray-900 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="simple-shop-product-card bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 flex flex-wrap md:flex-nowrap gap-8">
            <div class="product-gallery w-full md:w-1/2">
                <div class="simple-shop-product-image">
                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('full', ['class' => 'rounded-lg transform hover:scale-105 transition-transform duration-300']); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="product-details w-full md:w-1/2">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4"><?php the_title(); ?></h1>
                
                <div class="price text-2xl font-bold text-blue-600 dark:text-blue-400 mb-6">
                    <?php echo $product->get_price_html(); ?>
                </div>

                <div class="description prose dark:prose-invert mb-6">
                    <?php the_content(); ?>
                </div>

                <?php if ($product->is_in_stock()): ?>
                    <form class="cart" method="post">
                        <div class="flex gap-4 mb-6">
                            <input type="number" 
                                   name="quantity" 
                                   value="1" 
                                   min="1" 
                                   class="w-20 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <button type="submit" 
                                    name="add-to-cart" 
                                    value="<?php echo esc_attr($product->get_id()); ?>" 
                                    class="simple-shop-button-3d bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-all">
                                <?php esc_html_e('Add to Cart', 'simple-shop'); ?>
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-red-600 dark:text-red-400"><?php esc_html_e('Out of stock', 'simple-shop'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
endwhile;

get_footer('shop');
?>