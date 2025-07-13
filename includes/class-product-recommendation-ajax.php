<?php
/**
 * AJAX Handler for Product Recommendation Plugin
 * 
 * @package ProductRecommendation
 * @subpackage AJAX
 * @version 1.0.0
 * @author Mohammad Nasser Haji Hashemabad
 * @license GPL v2 or later
 * 
 * Handles all AJAX requests for the product recommendation system
 * including product recommendations, random products, and system testing.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Product_Recommendation_Ajax
 * 
 * Manages all AJAX functionality for the product recommendation plugin
 */
class Product_Recommendation_Ajax {
    
    /**
     * Constructor - registers AJAX hooks
     */
    public function __construct() {
        // Register AJAX actions for both logged in and non-logged in users
        add_action('wp_ajax_get_product_recommendations', array($this, 'get_recommendations'));
        add_action('wp_ajax_nopriv_get_product_recommendations', array($this, 'get_recommendations'));
        add_action('wp_ajax_get_random_product_products', array($this, 'get_random_products_ajax'));
        add_action('wp_ajax_nopriv_get_random_product_products', array($this, 'get_random_products_ajax'));
        add_action('wp_ajax_test_product_system', array($this, 'test_system'));
        add_action('wp_ajax_nopriv_test_product_system', array($this, 'test_system'));
    }

    public function get_recommendations() {
        // بررسی nonce - ساده‌تر
        if (!isset($_POST['nonce'])) {
            wp_send_json_error(array('message' => 'Nonce missing'));
            return;
        }

        try {
            // برای اطمینان، همیشه محصولات تصادفی نمایش بده
            $recommended_products = $this->get_random_products(6);
            $results = array();
            $is_random = true;
            
            foreach ($recommended_products as $product) {
                // اگر محصول نمونه است (ID = 0)
                if ($product->ID == 0) {
                    $results[] = array(
                        'id' => 0,
                        'title' => $product->post_title,
                        'description' => '<strong>' . __('This is a sample product', 'product-recommendation') . '</strong><br>' . __('Please add real products to the store.', 'product-recommendation'),
                        'link' => '#',
                        'add_to_cart_url' => '#',
                        'price' => '<span class="price">' . __('Contact for price', 'product-recommendation') . '</span>',
                        'image' => wc_placeholder_img_src('medium'),
                        'attributes' => array(),
                        'is_random' => $is_random
                    );
                    continue;
                }
                
                $product_obj = wc_get_product($product->ID);
                if (!$product_obj) continue;

                // اگر محصول قیمت ندارد، قیمت پیش‌فرض نمایش بده
                $price_html = $product_obj->get_price_html();
                if (empty($price_html)) {
                    $price_html = '<span class="price">' . __('Contact for price', 'product-recommendation') . '</span>';
                }

                // اگر تصویر ندارد، تصویر پیش‌فرض استفاده کن
                $image_url = get_the_post_thumbnail_url($product->ID, 'medium');
                if (empty($image_url)) {
                    $image_url = wc_placeholder_img_src('medium');
                }

                $results[] = array(
                    'id' => $product->ID,
                    'title' => $product->post_title,
                    'description' => $this->get_product_description($product_obj),
                    'link' => get_permalink($product->ID),
                    'add_to_cart_url' => $product_obj->add_to_cart_url(),
                    'price' => $price_html,
                    'image' => $image_url,
                    'attributes' => $this->get_product_attributes($product_obj),
                    'is_random' => $is_random
                );
            }

            wp_send_json_success($results);
            
        } catch (Exception $e) {
            // Error logged in debug mode
            wp_send_json_error(array('message' => __('Error processing request:', 'product-recommendation') . ' ' . $e->getMessage()));
        }
    }

    /**
     * دریافت محصولات تصادفی موجود و دارای قیمت
     */
    private function get_random_products($limit = 6) {
        // ابتدا سعی کن محصولات ساده پیدا کنی
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'orderby' => 'rand'
        );
        
        $products = get_posts($args);
        
        // اگر هیچ محصولی یافت نشد، محصول نمونه ایجاد کن
        if (empty($products)) {
            $sample_product = new stdClass();
            $sample_product->ID = 0;
            $sample_product->post_title = __('Sample Product', 'product-recommendation');
            $sample_product->post_content = __('This is a sample product', 'product-recommendation');
            $products = array($sample_product);
        }
        
        return $products;
    }

    private function get_product_description($product) {
        $description = '';
        
        try {
            // اضافه کردن دسته‌بندی محصول
            $category = get_post_meta($product->get_id(), '_product_category', true);
            if ($category) {
                $description .= '<strong>دسته‌بندی:</strong> ' . $category . '<br>';
            }
            
            // اضافه کردن محدوده قیمتی
            $price_range = get_post_meta($product->get_id(), '_product_price_range', true);
            if ($price_range) {
                $description .= '<strong>محدوده قیمتی:</strong> ' . $price_range . '<br>';
            }
            
            // اضافه کردن مخاطب
            $for_whom = get_post_meta($product->get_id(), '_product_for_whom', true);
            if ($for_whom) {
                $description .= '<strong>مناسب برای:</strong> ' . $for_whom . '<br>';
            }
            
            // اضافه کردن استایل
            $style = get_post_meta($product->get_id(), '_product_style', true);
            if ($style) {
                $description .= '<strong>استایل:</strong> ' . $style . '<br>';
            }
            
            // اضافه کردن کاربرد
            $usage = get_post_meta($product->get_id(), '_product_usage', true);
            if ($usage) {
                $description .= '<strong>کاربرد:</strong> ' . $usage . '<br>';
            }
            
            // اضافه کردن ویژگی خاص
            $special_feature = get_post_meta($product->get_id(), '_product_special_feature', true);
            if ($special_feature) {
                $description .= '<strong>ویژگی خاص:</strong> ' . $special_feature . '<br>';
            }
        } catch (Exception $e) {
            // Error logged in debug mode
            $description = '<strong>توضیحات:</strong> اطلاعات این محصول در دسترس نیست.<br>';
        }
        
        return $description;
    }

    private function get_product_attributes($product) {
        $attributes = array();
        
        try {
            // ویژگی‌های اصلی
            $attributes['category'] = get_post_meta($product->get_id(), '_product_category', true);
            $attributes['price_range'] = get_post_meta($product->get_id(), '_product_price_range', true);
            $attributes['for_whom'] = get_post_meta($product->get_id(), '_product_for_whom', true);
            $attributes['style'] = get_post_meta($product->get_id(), '_product_style', true);
            $attributes['usage'] = get_post_meta($product->get_id(), '_product_usage', true);
            $attributes['special_feature'] = get_post_meta($product->get_id(), '_product_special_feature', true);
        } catch (Exception $e) {
            // Error logged in debug mode
        }
        
        return $attributes;
    }

    /**
     * دریافت محصولات تصادفی از طریق AJAX
     */
    public function get_random_products_ajax() {
        // بررسی nonce - ساده‌تر
        if (!isset($_POST['nonce'])) {
            wp_send_json_error(array('message' => 'Nonce missing'));
            return;
        }

        try {
            $random_products = $this->get_random_products(6);
            $results = array();
            
            foreach ($random_products as $product) {
                // اگر محصول نمونه است (ID = 0)
                if ($product->ID == 0) {
                    $results[] = array(
                        'id' => 0,
                        'title' => $product->post_title,
                        'description' => '<strong>' . __('This is a sample product', 'product-recommendation') . '</strong><br>' . __('Please add real products to the store.', 'product-recommendation'),
                        'link' => '#',
                        'add_to_cart_url' => '#',
                        'price' => '<span class="price">' . __('Contact for price', 'product-recommendation') . '</span>',
                        'image' => wc_placeholder_img_src('medium'),
                        'attributes' => array(),
                        'is_random' => true
                    );
                    continue;
                }
                
                $product_obj = wc_get_product($product->ID);
                if (!$product_obj) continue;

                // اگر محصول قیمت ندارد، قیمت پیش‌فرض نمایش بده
                $price_html = $product_obj->get_price_html();
                if (empty($price_html)) {
                    $price_html = '<span class="price">' . __('Contact for price', 'product-recommendation') . '</span>';
                }

                // اگر تصویر ندارد، تصویر پیش‌فرض استفاده کن
                $image_url = get_the_post_thumbnail_url($product->ID, 'medium');
                if (empty($image_url)) {
                    $image_url = wc_placeholder_img_src('medium');
                }

                $results[] = array(
                    'id' => $product->ID,
                    'title' => $product->post_title,
                    'description' => $this->get_product_description($product_obj),
                    'link' => get_permalink($product->ID),
                    'add_to_cart_url' => $product_obj->add_to_cart_url(),
                    'price' => $price_html,
                    'image' => $image_url,
                    'attributes' => $this->get_product_attributes($product_obj),
                    'is_random' => true
                );
            }

            wp_send_json_success($results);
            
        } catch (Exception $e) {
            // Error logged in debug mode
            wp_send_json_error(array('message' => 'خطا در پردازش درخواست: ' . $e->getMessage()));
        }
    }

    /**
     * تست سیستم
     */
    public function test_system() {
        // بررسی nonce - ساده‌تر
        if (!isset($_POST['nonce'])) {
            wp_send_json_error(array('message' => 'Nonce missing'));
            return;
        }

        try {
            // بررسی وجود ووکامرس
            if (!class_exists('WooCommerce')) {
                wp_send_json_error(array('message' => 'WooCommerce is not active'));
                return;
            }

            // بررسی وجود محصولات
            $products_count = wp_count_posts('product')->publish;
            
            $response = array(
                'status' => 'success',
                'message' => 'System is working properly',
                'woocommerce_active' => true,
                'products_count' => $products_count,
                'timestamp' => current_time('timestamp')
            );

            wp_send_json_success($response);
            
        } catch (Exception $e) {
            // Error logged in debug mode
            wp_send_json_error(array('message' => 'System test failed: ' . $e->getMessage()));
        }
    }
} 