<?php
// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
$option_names = [
    'product_recommendation_enabled',
    'product_recommendation_custom_message',
    'product_recommendation_primary_color',
    'product_recommendation_font_color',
    'product_recommendation_button_color',
    'product_recommendation_button_text_color',
    'product_recommendation_base_questions',
];
foreach ($option_names as $option) {
    delete_option($option);
    delete_site_option($option);
}

// Delete all product meta for characteristics
$meta_keys = [
    '_product_characteristics',
    '_product_category',
    '_product_price_range',
    '_product_for_whom',
    '_product_style',
    '_product_usage',
    '_product_special_feature',
];
if (function_exists('get_posts')) {
    $products = get_posts([
        'post_type' => 'product',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields' => 'ids',
    ]);
    foreach ($products as $product_id) {
        foreach ($meta_keys as $meta_key) {
            delete_post_meta($product_id, $meta_key);
        }
    }
} 