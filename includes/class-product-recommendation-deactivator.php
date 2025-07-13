<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Product_Recommendation_Deactivator {
    public static function deactivate() {
        try {
            // Remove activation flag
            delete_option('product_recommendation_do_activation_redirect');
            
            // Flush rewrite rules
            flush_rewrite_rules();
            
        } catch (Exception $e) {
            // Error logged in debug mode
        }
    }
} 