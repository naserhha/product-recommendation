<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Product_Recommendation_Activator {
    public static function activate() {
        try {
            // Create default options
            if (!get_option('product_recommendation_enabled')) {
                add_option('product_recommendation_enabled', '1');
            }
            
            if (!get_option('product_recommendation_primary_color')) {
                add_option('product_recommendation_primary_color', '#8c0101');
            }
            
            if (!get_option('product_recommendation_font_color')) {
                add_option('product_recommendation_font_color', '#fff');
            }
            
            if (!get_option('product_recommendation_button_color')) {
                add_option('product_recommendation_button_color', '#fff');
            }
            
            if (!get_option('product_recommendation_button_text_color')) {
                add_option('product_recommendation_button_text_color', '#8c0101');
            }
            
            if (!get_option('product_recommendation_custom_message')) {
                add_option('product_recommendation_custom_message', __('Product Recommendation Form', 'product-recommendation'));
            }
            
            // Get current WordPress language
            $current_locale = get_locale();
            
            // Map locale to language code
            $language_map = [
                // Persian/Farsi variants
                'fa_IR' => 'fa',
                'fa_AF' => 'fa',
                'fa_TJ' => 'fa',
                'fa' => 'fa',
                
                // English variants
                'en_US' => 'en', 
                'en_GB' => 'en',
                'en_CA' => 'en',
                'en_AU' => 'en',
                'en_NZ' => 'en',
                'en_ZA' => 'en',
                'en_IE' => 'en',
                'en_IN' => 'en',
                'en_PK' => 'en',
                'en_NG' => 'en',
                'en_KE' => 'en',
                'en_UG' => 'en',
                'en_TZ' => 'en',
                'en_ZW' => 'en',
                'en_PH' => 'en',
                'en_MY' => 'en',
                'en_SG' => 'en',
                'en_HK' => 'en',
                'en' => 'en',
                
                // Russian variants
                'ru_RU' => 'ru',
                'ru_UA' => 'ru',
                'ru_BY' => 'ru',
                'ru_KZ' => 'ru',
                'ru_KG' => 'ru',
                'ru_TJ' => 'ru',
                'ru_UZ' => 'ru',
                'ru_TM' => 'ru',
                'ru_MD' => 'ru',
                'ru_AM' => 'ru',
                'ru_AZ' => 'ru',
                'ru_GE' => 'ru',
                'ru' => 'ru',
                
                // Chinese variants
                'zh_CN' => 'zh',
                'zh_TW' => 'zh',
                'zh_HK' => 'zh',
                'zh_SG' => 'zh',
                'zh_MO' => 'zh',
                'zh' => 'zh',
                
                // Arabic variants
                'ar' => 'ar',
                'ar_SA' => 'ar',
                'ar_EG' => 'ar',
                'ar_AE' => 'ar',
                'ar_JO' => 'ar',
                'ar_SY' => 'ar',
                'ar_LB' => 'ar',
                'ar_IQ' => 'ar',
                'ar_KW' => 'ar',
                'ar_QA' => 'ar',
                'ar_BH' => 'ar',
                'ar_OM' => 'ar',
                'ar_YE' => 'ar',
                'ar_ER' => 'ar',
                'ar_ET' => 'ar',
                'ar_SS' => 'ar'
            ];
            
            // Set up base questions for each supported language
            $supported_languages = ['fa', 'en', 'ru', 'zh', 'ar'];
            
            // Default questions using translation functions (will be translated based on current locale)
            $default_questions = [
                [
                    'title' => __('What type of product are you looking for?', 'product-recommendation'),
                    'attribute' => 'pa_product_category',
                    'options' => [
                        __('Clothing', 'product-recommendation'),
                        __('Digital Accessories', 'product-recommendation'),
                        __('Home Appliances', 'product-recommendation'),
                        __('Beauty & Health', 'product-recommendation'),
                        __('Books', 'product-recommendation'),
                        __('Other', 'product-recommendation')
                    ]
                ],
                [
                    'title' => __('What is your preferred price range?', 'product-recommendation'),
                    'attribute' => 'pa_product_price_range',
                    'options' => [
                        __('Less than 500 thousand Tomans', 'product-recommendation'),
                        __('500 thousand to 2 million Tomans', 'product-recommendation'),
                        __('More than 2 million Tomans', 'product-recommendation')
                    ]
                ],
                [
                    'title' => __('Who is this product for?', 'product-recommendation'),
                    'attribute' => 'pa_product_for_whom',
                    'options' => [
                        __('Myself', 'product-recommendation'),
                        __('Family members', 'product-recommendation'),
                        __('Gift for friend', 'product-recommendation'),
                        __('Child', 'product-recommendation'),
                        __('Teenager', 'product-recommendation'),
                        __('Adult', 'product-recommendation')
                    ]
                ],
                [
                    'title' => __('What style and color do you prefer?', 'product-recommendation'),
                    'attribute' => 'pa_product_style',
                    'options' => [
                        __('Light colors', 'product-recommendation'),
                        __('Dark colors', 'product-recommendation'),
                        __('Classic', 'product-recommendation'),
                        __('Modern', 'product-recommendation'),
                        __('Sporty', 'product-recommendation')
                    ]
                ],
                [
                    'title' => __('What is this product for?', 'product-recommendation'),
                    'attribute' => 'pa_product_usage',
                    'options' => [
                        __('Daily use', 'product-recommendation'),
                        __('Party', 'product-recommendation'),
                        __('Workplace', 'product-recommendation'),
                        __('Travel', 'product-recommendation'),
                        __('Sports', 'product-recommendation')
                    ]
                ],
                [
                    'title' => __('What special feature are you looking for?', 'product-recommendation'),
                    'attribute' => 'pa_product_special_feature',
                    'options' => [
                        __('High build quality', 'product-recommendation'),
                        __('Reputable brand', 'product-recommendation'),
                        __('Low energy consumption', 'product-recommendation'),
                        __('Portability', 'product-recommendation'),
                        __('Multi-functionality', 'product-recommendation')
                    ]
                ]
            ];
            
            // Create base questions for each language
            foreach ($supported_languages as $lang) {
                $option_key = 'product_recommendation_base_questions_' . $lang;
                if (!get_option($option_key)) {
                    add_option($option_key, $default_questions);
                }
            }
            
            // Set the default language based on current WordPress language
            $current_lang_code = isset($language_map[$current_locale]) ? $language_map[$current_locale] : 'en';
            update_option('product_recommendation_default_language', $current_lang_code);
            
            // Create taxonomies
            $taxonomies = [
                'pa_product_category' => __('Product Category', 'product-recommendation'),
                'pa_product_price_range' => __('Price Range', 'product-recommendation'),
                'pa_product_for_whom' => __('Product Target', 'product-recommendation'),
                'pa_product_style' => __('Style and Color', 'product-recommendation'),
                'pa_product_usage' => __('Product Usage', 'product-recommendation'),
                'pa_product_special_feature' => __('Special Feature', 'product-recommendation')
            ];
            
            foreach ($taxonomies as $taxonomy => $label) {
                if (!taxonomy_exists($taxonomy)) {
                    register_taxonomy($taxonomy, 'product', [
                        'label' => $label,
                        'public' => false,
                        'show_ui' => false,
                        'hierarchical' => false,
                    ]);
                }
            }
            
            // Add terms to taxonomies using translated options
            foreach ($default_questions as $question) {
                $taxonomy = $question['attribute'];
                if (taxonomy_exists($taxonomy)) {
                    foreach ($question['options'] as $option) {
                        if (!term_exists($option, $taxonomy)) {
                            wp_insert_term($option, $taxonomy);
                        }
                    }
                }
            }
            
            // Create database table
            global $wpdb;
            $table_name = $wpdb->prefix . 'product_recommendation_products';
            
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                product_id bigint(20) NOT NULL,
                pa_product_category varchar(100),
                pa_product_price_range varchar(100),
                pa_product_for_whom varchar(100),
                pa_product_style varchar(100),
                pa_product_usage varchar(100),
                pa_product_special_feature varchar(100),
                created_at datetime DEFAULT CURRENT_TIMESTAMP,
                updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY  (id),
                UNIQUE KEY product_id (product_id)
            ) $charset_collate;";
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            // Set activation flag
            add_option('product_recommendation_do_activation_redirect', true);
            
            // Flush rewrite rules
            flush_rewrite_rules();
            
        } catch (Exception $e) {
            // Error logged in debug mode
        }
    }
}
