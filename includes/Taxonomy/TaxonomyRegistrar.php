<?php
namespace SimpleShop\Taxonomy;

class TaxonomyRegistrar {
    public static function register_taxonomies() {
        register_taxonomy('simple_shop_category', 'simple_shop_product', [
            'labels' => [
                'name' => __('Product Categories', 'simple-shop'),
                'singular_name' => __('Category', 'simple-shop')
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true
        ]);

        register_taxonomy('simple_shop_tag', 'simple_shop_product', [
            'labels' => [
                'name' => __('Product Tags', 'simple-shop'),
                'singular_name' => __('Tag', 'simple-shop')
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true
        ]);
    }
}