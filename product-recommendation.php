<?php
/**
 * Plugin Name: Product Recommendation System
 * Plugin URI: https://github.com/nasserhaji/product-recommendation
 * Description: A comprehensive product recommendation system for WooCommerce that helps customers find the perfect products based on their preferences and characteristics.
 * Version: 1.0.0
 * Author: Mohammad Nasser Haji Hashemabad
 * Author URI: https://mohammadnasser.com/
 * Text Domain: product-recommendation
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * @package ProductRecommendation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Prevent duplicate plugin loading
if (defined('PRODUCT_RECOMMENDATION_VERSION')) {
    return;
}

// Define plugin constants
if (!defined('PRODUCT_RECOMMENDATION_VERSION')) {
    define('PRODUCT_RECOMMENDATION_VERSION', '1.0.0');
}

if (!defined('PRODUCT_RECOMMENDATION_PLUGIN_DIR')) {
    define('PRODUCT_RECOMMENDATION_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('PRODUCT_RECOMMENDATION_PLUGIN_URL')) {
    define('PRODUCT_RECOMMENDATION_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('PRODUCT_RECOMMENDATION_PLUGIN_BASENAME')) {
    define('PRODUCT_RECOMMENDATION_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

// Autoloader for plugin classes
spl_autoload_register(function ($class) {
    $prefix = 'ProductRecommendation\\';
    $base_dir = PRODUCT_RECOMMENDATION_PLUGIN_DIR . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Include required files
$required_files = [
    'includes/class-product-form.php',
    'includes/class-product-database.php',
    'includes/class-product-recommendation.php',
    'includes/class-product-recommendation-admin.php',
    'includes/class-product-recommendation-frontend.php',
    'includes/class-product-recommendation-ajax.php',
    'includes/class-product-recommendation-activator.php',
    'includes/class-product-recommendation-deactivator.php',
    'includes/class-product-recommendation-product.php',
    'includes/class-product-recommendation-settings.php'
];

foreach ($required_files as $file) {
    if (!file_exists(PRODUCT_RECOMMENDATION_PLUGIN_DIR . $file)) {
        // File not found - continue silently
        continue;
    }
    require_once PRODUCT_RECOMMENDATION_PLUGIN_DIR . $file;
}

// Load plugin textdomain for translations
if (!function_exists('product_recommendation_load_textdomain')) {
    function product_recommendation_load_textdomain() {
        $domain = 'product-recommendation';
        $mofile = WP_PLUGIN_DIR . '/product-recommendation/languages/' . $domain . '-' . get_locale() . '.mo';
        
        if (file_exists($mofile)) {
            load_textdomain($domain, $mofile);
        }
    }
}
add_action('plugins_loaded', 'product_recommendation_load_textdomain');

// Add hook to reload text domain when language changes
if (!function_exists('product_recommendation_reload_textdomain')) {
    function product_recommendation_reload_textdomain() {
        $current_locale = get_locale();
        static $last_locale = null;
        
        if ($last_locale !== $current_locale) {
            $last_locale = $current_locale;
            $domain = 'product-recommendation';
            $mofile = WP_PLUGIN_DIR . '/product-recommendation/languages/' . $domain . '-' . $current_locale . '.mo';
            
            if (file_exists($mofile)) {
                load_textdomain($domain, $mofile);
            }
        }
    }
}
add_action('init', 'product_recommendation_reload_textdomain');
add_action('admin_init', 'product_recommendation_reload_textdomain');
add_action('wp_loaded', 'product_recommendation_reload_textdomain');

// Initialize the plugin
if (!function_exists('product_recommendation_init')) {
    function product_recommendation_init() {
        // Initialize main plugin class
        $plugin = new Product_Recommendation();
        $plugin->init();
    }
}

// Hook into WordPress
add_action('plugins_loaded', 'product_recommendation_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    Product_Recommendation_Activator::activate();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    Product_Recommendation_Deactivator::deactivate();
});

// Redirect after activation
add_action('admin_init', function() {
    if (get_option('product_recommendation_do_activation_redirect', false)) {
        delete_option('product_recommendation_do_activation_redirect');
        wp_redirect(admin_url('admin.php?page=product-recommendation-settings'));
        exit;
    }
}); 

add_action('plugins_loaded', function() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>' . __('Product Recommendation System requires WooCommerce to be installed and activated.', 'product-recommendation') . '</p></div>';
        });
    }
}); 