<?php

class Product_Recommendation_Settings {
    
    /**
     * Get the option key for base questions based on current language
     */
    private function get_base_questions_option_key($locale = null) {
        if (!$locale) {
            $locale = get_locale();
        }
        
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
            'ar_MA' => 'ar',
            'ar_DZ' => 'ar',
            'ar_TN' => 'ar',
            'ar_LY' => 'ar',
            'ar_SD' => 'ar',
            'ar_TD' => 'ar',
            'ar_MR' => 'ar',
            'ar_DJ' => 'ar',
            'ar_SO' => 'ar',
            'ar_KM' => 'ar',
            'ar_ER' => 'ar',
            'ar_ET' => 'ar',
            'ar_SS' => 'ar'
        ];
        
        $lang_code = isset($language_map[$locale]) ? $language_map[$locale] : 'en';
        return 'product_recommendation_base_questions_' . $lang_code;
    }
    
    /**
     * Get base questions for current language
     */
    private function get_base_questions($locale = null) {
        if (!$locale) {
            $locale = get_locale();
        }
        
        $option_key = $this->get_base_questions_option_key($locale);
        $questions = get_option($option_key, array());
        
        // اگر سوالات برای این زبان وجود ندارد، به صورت خودکار مقدار پیش‌فرض همان زبان را ذخیره کن
        if (empty($questions)) {
            $default_questions = $this->get_default_base_questions($locale);
            update_option($option_key, $default_questions);
            return $default_questions;
        }
        
        return $questions;
    }
    
    /**
     * Get the default language for the plugin
     */
    private function get_default_language() {
        $default_lang = get_option('product_recommendation_default_language', 'en');
        return $default_lang;
    }
    
    /**
     * Get default base questions for specified language
     */
    private function get_default_base_questions($locale = null) {
        if (!$locale) {
            $locale = get_locale();
        }
        
        // Default questions based on current language using translation functions
        $base_questions = [
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
        
        return $base_questions;
    }

    public function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array($this, 'auto_create_base_taxonomies'));
        add_action('admin_init', array($this, 'redirect_taxonomy_pages'));
        add_action('admin_init', array($this, 'handle_language_update'));
        add_action('admin_menu', array($this, 'remove_woocommerce_taxonomy_menus'), 999);
        add_filter('woocommerce_admin_product_columns', array($this, 'remove_product_columns'));
        add_action('admin_head', array($this, 'hide_product_related_links'));
        add_action('admin_head', array($this, 'hide_product_admin_elements'));
        add_action('add_meta_boxes', array($this, 'remove_product_meta_boxes'), 999);
        add_filter('manage_product_posts_columns', array($this, 'remove_product_admin_columns'));
        add_action('admin_footer', array($this, 'hide_product_admin_scripts'));
    }

    public function register_settings() {
        register_setting('product_recommendation_settings_group', 'product_recommendation_enabled');
        register_setting('product_recommendation_settings_group', 'product_recommendation_custom_message');
        register_setting('product_recommendation_settings_group', 'product_recommendation_primary_color');
        register_setting('product_recommendation_settings_group', 'product_recommendation_font_color');
        register_setting('product_recommendation_settings_group', 'product_recommendation_button_color');
        register_setting('product_recommendation_settings_group', 'product_recommendation_button_text_color');
    }

    public function render_settings_page() {
        // Force reload textdomain for this page
        $domain = 'product-recommendation';
        $mofile = WP_PLUGIN_DIR . '/product-recommendation/languages/' . $domain . '-' . get_locale() . '.mo';
        
        if (file_exists($mofile)) {
            load_textdomain($domain, $mofile);
        }
        
        // Debug information removed - Translation Debug tab provides complete information
        
        $primary_color = get_option('product_recommendation_primary_color', '#8c0101');
        $font_color = get_option('product_recommendation_font_color', '#fff');
        $button_color = get_option('product_recommendation_button_color', '#fff');
        $button_text_color = get_option('product_recommendation_button_text_color', '#8c0101');
        
        // نمایش پیام ریدایرکت
        if (isset($_GET['message']) && $_GET['message'] === 'redirected') {
            echo '<div class="notice notice-info is-dismissible"><p>' . __('Plugin feature management is only done through this page. Plugin-related links have been removed from other dashboard sections.', 'product-recommendation') . '</p></div>';
        }
        
        // نمایش پیام موفقیت‌آمیز بودن بروزرسانی زبان
        if (isset($_GET['language_updated']) && $_GET['language_updated'] === '1') {
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Plugin default language has been updated to match the current WordPress language.', 'product-recommendation') . '</p></div>';
        }
        
        // تعیین تب فعال
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'guide';
        ?>
        <style>
        .product-settings-tabs { display: flex; border-bottom: 2px solid #eee; margin-bottom: 2em; }
        .product-settings-tab {
            padding: 1em 2em;
            cursor: pointer;
            background: #fafafa;
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: bold;
            color: #8c0101;
            transition: background 0.2s, border 0.2s;
        }
        .product-settings-tab.active {
            background: #fff;
            border-bottom: 2px solid #8c0101;
            color: #8c0101;
        }
        .product-settings-content { display: none; }
        .product-settings-content.active { display: block; }
        </style>
        <div class="wrap">
            <h1><?php _e('Product Recommendation', 'product-recommendation'); ?></h1>
            <div class="product-settings-tabs">
                <button class="product-settings-tab <?php echo $active_tab === 'guide' ? 'active' : ''; ?>" data-tab="guide"><?php _e('Guide', 'product-recommendation'); ?></button>
                <button class="product-settings-tab <?php echo $active_tab === 'general' ? 'active' : ''; ?>" data-tab="general"><?php _e('General Settings', 'product-recommendation'); ?></button>
                <button class="product-settings-tab <?php echo $active_tab === 'base' ? 'active' : ''; ?>" data-tab="base"><?php _e('Base Questions', 'product-recommendation'); ?></button>
                <button class="product-settings-tab <?php echo $active_tab === 'taxonomies' ? 'active' : ''; ?>" data-tab="taxonomies"><?php _e('Plugin Features', 'product-recommendation'); ?></button>
                <button class="product-settings-tab <?php echo $active_tab === 'languages' ? 'active' : ''; ?>" data-tab="languages"><?php _e('Language Support', 'product-recommendation'); ?></button>
                <button class="product-settings-tab <?php echo $active_tab === 'debug' ? 'active' : ''; ?>" data-tab="debug"><?php _e('Translation Debug', 'product-recommendation'); ?></button>
            </div>
            <div class="product-settings-content <?php echo $active_tab === 'guide' ? 'active' : ''; ?>" id="tab-guide">
                <div class="card">
                    <h2><?php _e('Complete Guide to Using the Product Recommendation Plugin', 'product-recommendation'); ?></h2>
                    
                    <h3>📋 <?php _e('Step 1: Configure Base Questions', 'product-recommendation'); ?></h3>
                    <ol>
                        <li><?php _e('Go to the Base Questions tab', 'product-recommendation'); ?></li>
                        <li><?php _e('Review the default questions (product category, price range, target audience, style, usage, special feature)', 'product-recommendation'); ?></li>
                        <li><?php _e('Edit question titles if needed', 'product-recommendation'); ?></li>
                        <li><?php _e('Enter options for each question in the relevant section (one option per line)', 'product-recommendation'); ?></li>
                        <li><?php _e('To add a new question, use the \'Add New Question\' section', 'product-recommendation'); ?></li>
                        <li><?php _e('Click the Save Base Questions button', 'product-recommendation'); ?></li>
                    </ol>
                    
                    <h3>🎨 <?php _e('Step 2: Customize Form Appearance', 'product-recommendation'); ?></h3>
                    <ol>
                        <li><?php _e('Go to the General Settings tab', 'product-recommendation'); ?></li>
                        <li><?php _e('Check that the plugin is enabled (should be checked)', 'product-recommendation'); ?></li>
                        <li><?php _e('Enter a custom message if needed', 'product-recommendation'); ?></li>
                        <li><?php _e('Set the primary plugin color (example: #8c0101)', 'product-recommendation'); ?></li>
                        <li><?php _e('Choose the form font color (example: #fff for white)', 'product-recommendation'); ?></li>
                        <li><?php _e('Set button colors and button text colors', 'product-recommendation'); ?></li>
                        <li><?php _e('Click the Save General Settings button', 'product-recommendation'); ?></li>
                    </ol>
                    
                    <h3>🏷️ <?php _e('Step 3: Manage Product Characteristics', 'product-recommendation'); ?></h3>
                    <ol>
                        <li><?php _e('Go to the Plugin Features tab', 'product-recommendation'); ?></li>
                        <li><?php _e('View the list of all available characteristics', 'product-recommendation'); ?></li>
                        <li><?php _e('For each characteristic, review the available options', 'product-recommendation'); ?></li>
                        <li><?php _e('Add new options if needed', 'product-recommendation'); ?></li>
                        <li><?php _e('Remove unnecessary options', 'product-recommendation'); ?></li>
                        <li><?php _e('Or use separate submenus to manage each characteristic:', 'product-recommendation'); ?>
                            <ul>
                                <li><strong><?php _e('Product Characteristics:', 'product-recommendation'); ?></strong> <?php _e('to define different product characteristics', 'product-recommendation'); ?></li>
                                <li><strong><?php _e('Suitable For:', 'product-recommendation'); ?></strong> <?php _e('to define occasions', 'product-recommendation'); ?></li>
                                <li><strong><?php _e('Suitable Season:', 'product-recommendation'); ?></strong> <?php _e('to define suitable seasons', 'product-recommendation'); ?></li>
                                <li><strong><?php _e('Gender:', 'product-recommendation'); ?></strong> <?php _e('to define suitable gender', 'product-recommendation'); ?></li>
                                <li><strong><?php _e('Quality:', 'product-recommendation'); ?></strong> <?php _e('to define quality levels', 'product-recommendation'); ?></li>
                                <li><strong><?php _e('Usage:', 'product-recommendation'); ?></strong> <?php _e('to define different usages', 'product-recommendation'); ?></li>
                            </ul>
                        </li>
                    </ol>
                    
                    <h3>📦 <?php _e('Step 4: Configure Products', 'product-recommendation'); ?></h3>
                    <ol>
                        <li><?php _e('Go to Products > All Products', 'product-recommendation'); ?></li>
                        <li><?php _e('Click on the desired product to edit it', 'product-recommendation'); ?></li>
                        <li><?php _e('In the Product Characteristics section (located at the bottom of the product page):', 'product-recommendation'); ?></li>
                        <li><?php _e('For each base question, select the appropriate option:', 'product-recommendation'); ?>
                            <ul>
                                <li><?php _e('Product Category: Clothing, Digital Accessories, Home Appliances, Beauty & Health, Books, Other', 'product-recommendation'); ?></li>
                                <li><?php _e('Price Range: Less than 500 thousand Tomans, 500 thousand to 2 million Tomans, More than 2 million Tomans', 'product-recommendation'); ?></li>
                                <li><?php _e('Target Audience: Myself, Family members, Gift for friend, Child, Teenager, Adult', 'product-recommendation'); ?></li>
                                <li><?php _e('Style: Bright colors, Dark colors, Classic, Modern, Sporty', 'product-recommendation'); ?></li>
                                <li><?php _e('Usage: Daily use, Party, Workplace, Travel, Sports', 'product-recommendation'); ?></li>
                                <li><?php _e('Special Feature: High build quality, Reputable brand, Low energy consumption, Portable, Multi-functional', 'product-recommendation'); ?></li>
                            </ul>
                        </li>
                        <li><?php _e('Also configure other product characteristics (features, occasions, seasons, etc.)', 'product-recommendation'); ?></li>
                        <li><?php _e('Save the product', 'product-recommendation'); ?></li>
                        <li><?php _e('Repeat this process for all products you want to display in the form', 'product-recommendation'); ?></li>
                    </ol>
                    
                    <h3>🌐 <?php _e('Step 5: Place the Form on Your Site', 'product-recommendation'); ?></h3>
                    <ol>
                        <li><?php _e('Go to Pages > Add New Page', 'product-recommendation'); ?></li>
                        <li><?php _e('Enter the page title (example: Product Recommendation)', 'product-recommendation'); ?></li>
                        <li><?php _e('In the page content, place the following shortcode:', 'product-recommendation'); ?></li>
                        <li><code>[product_recommendation_form]</code></li>
                        <li><?php _e('Publish the page', 'product-recommendation'); ?></li>
                        <li><?php _e('Or place the same shortcode in any other page or post where you want the form to appear', 'product-recommendation'); ?></li>
                    </ol>
                    
                    <h3>✅ <?php _e('Step 6: Test Functionality', 'product-recommendation'); ?></h3>
                    <ol>
                        <li><?php _e('Go to the page where you placed the form', 'product-recommendation'); ?></li>
                        <li><?php _e('View the form and make sure questions and options are displayed correctly', 'product-recommendation'); ?></li>
                        <li><?php _e('Fill out the form and try different responses', 'product-recommendation'); ?></li>
                        <li><?php _e('Check that recommended products are displayed', 'product-recommendation'); ?></li>
                        <li><?php _e('If no product is displayed, check that the product characteristics are set correctly', 'product-recommendation'); ?></li>
                    </ol>
                    
                    <h3>🔧 <?php _e('Troubleshooting Common Issues', 'product-recommendation'); ?></h3>
                    <ul>
                        <li><strong><?php _e('Question options are empty:', 'product-recommendation'); ?></strong> <?php _e('Go to the Plugin Features tab and add options', 'product-recommendation'); ?></li>
                        <li><strong><?php _e('No product is displayed:', 'product-recommendation'); ?></strong> <?php _e('Check product characteristics', 'product-recommendation'); ?></li>
                        <li><strong><?php _e('Colors are not correct:', 'product-recommendation'); ?></strong> <?php _e('Check general settings', 'product-recommendation'); ?></li>
                        <li><strong><?php _e('Form is not displayed:', 'product-recommendation'); ?></strong> <?php _e('Check the shortcode', 'product-recommendation'); ?></li>
                    </ul>
                    
                    <h3>💡 <?php _e('Important Notes', 'product-recommendation'); ?></h3>
                    <ul>
                        <li><?php _e('All characteristic management and plugin options are done only through this page', 'product-recommendation'); ?></li>
                        <li><?php _e('Plugin-related links have been removed from other admin sections', 'product-recommendation'); ?></li>
                        <li><?php _e('For best results, configure at least 6 products with different characteristics', 'product-recommendation'); ?></li>
                        <li><?php _e('Product characteristics must match question options', 'product-recommendation'); ?></li>
                        <li><?php _e('The form automatically adapts to your settings', 'product-recommendation'); ?></li>
                    </ul>
                    
                    <h3>🌍 <?php _e('Supported Languages and Countries', 'product-recommendation'); ?></h3>
                    <p><?php _e('This plugin supports multiple languages and automatically adapts to your WordPress language setting. The following languages and countries are fully supported:', 'product-recommendation'); ?></p>
                    
                    <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                        <h4>🇮🇷 <?php _e('Persian/Farsi (فارسی)', 'product-recommendation'); ?></h4>
                        <ul>
                            <li><strong>fa_IR</strong> - <?php _e('Iran', 'product-recommendation'); ?></li>
                            <li><strong>fa</strong> - <?php _e('General Persian', 'product-recommendation'); ?></li>
                        </ul>
                    </div>
                    
                    <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                        <h4>🇺🇸 <?php _e('English', 'product-recommendation'); ?></h4>
                        <ul>
                            <li><strong>en_US</strong> - <?php _e('United States', 'product-recommendation'); ?></li>
                            <li><strong>en_GB</strong> - <?php _e('United Kingdom', 'product-recommendation'); ?></li>
                            <li><strong>en_CA</strong> - <?php _e('Canada', 'product-recommendation'); ?></li>
                            <li><strong>en_AU</strong> - <?php _e('Australia', 'product-recommendation'); ?></li>
                            <li><strong>en_NZ</strong> - <?php _e('New Zealand', 'product-recommendation'); ?></li>
                            <li><strong>en_ZA</strong> - <?php _e('South Africa', 'product-recommendation'); ?></li>
                            <li><strong>en_IE</strong> - <?php _e('Ireland', 'product-recommendation'); ?></li>
                            <li><strong>en</strong> - <?php _e('General English', 'product-recommendation'); ?></li>
                        </ul>
                    </div>
                    
                    <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                        <h4>🇷🇺 <?php _e('Russian (Русский)', 'product-recommendation'); ?></h4>
                        <ul>
                            <li><strong>ru_RU</strong> - <?php _e('Russia', 'product-recommendation'); ?></li>
                            <li><strong>ru_UA</strong> - <?php _e('Ukraine', 'product-recommendation'); ?></li>
                            <li><strong>ru_BY</strong> - <?php _e('Belarus', 'product-recommendation'); ?></li>
                            <li><strong>ru_KZ</strong> - <?php _e('Kazakhstan', 'product-recommendation'); ?></li>
                            <li><strong>ru_KG</strong> - <?php _e('Kyrgyzstan', 'product-recommendation'); ?></li>
                            <li><strong>ru</strong> - <?php _e('General Russian', 'product-recommendation'); ?></li>
                        </ul>
                    </div>
                    
                    <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                        <h4>🇨🇳 <?php _e('Chinese (中文)', 'product-recommendation'); ?></h4>
                        <ul>
                            <li><strong>zh_CN</strong> - <?php _e('China (Simplified)', 'product-recommendation'); ?></li>
                            <li><strong>zh_TW</strong> - <?php _e('Taiwan (Traditional)', 'product-recommendation'); ?></li>
                            <li><strong>zh_HK</strong> - <?php _e('Hong Kong', 'product-recommendation'); ?></li>
                            <li><strong>zh_SG</strong> - <?php _e('Singapore', 'product-recommendation'); ?></li>
                            <li><strong>zh_MO</strong> - <?php _e('Macau', 'product-recommendation'); ?></li>
                            <li><strong>zh</strong> - <?php _e('General Chinese', 'product-recommendation'); ?></li>
                        </ul>
                    </div>
                    
                    <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                        <h4>🇸🇦 <?php _e('Arabic (العربية)', 'product-recommendation'); ?></h4>
                        <ul>
                            <li><strong>ar</strong> - <?php _e('General Arabic', 'product-recommendation'); ?></li>
                            <li><strong>ar_SA</strong> - <?php _e('Saudi Arabia', 'product-recommendation'); ?></li>
                            <li><strong>ar_EG</strong> - <?php _e('Egypt', 'product-recommendation'); ?></li>
                            <li><strong>ar_AE</strong> - <?php _e('United Arab Emirates', 'product-recommendation'); ?></li>
                            <li><strong>ar_JO</strong> - <?php _e('Jordan', 'product-recommendation'); ?></li>
                            <li><strong>ar_SY</strong> - <?php _e('Syria', 'product-recommendation'); ?></li>
                            <li><strong>ar_LB</strong> - <?php _e('Lebanon', 'product-recommendation'); ?></li>
                            <li><strong>ar_IQ</strong> - <?php _e('Iraq', 'product-recommendation'); ?></li>
                            <li><strong>ar_KW</strong> - <?php _e('Kuwait', 'product-recommendation'); ?></li>
                            <li><strong>ar_QA</strong> - <?php _e('Qatar', 'product-recommendation'); ?></li>
                            <li><strong>ar_BH</strong> - <?php _e('Bahrain', 'product-recommendation'); ?></li>
                            <li><strong>ar_OM</strong> - <?php _e('Oman', 'product-recommendation'); ?></li>
                            <li><strong>ar_YE</strong> - <?php _e('Yemen', 'product-recommendation'); ?></li>
                            <li><strong>ar_MA</strong> - <?php _e('Morocco', 'product-recommendation'); ?></li>
                            <li><strong>ar_DZ</strong> - <?php _e('Algeria', 'product-recommendation'); ?></li>
                            <li><strong>ar_TN</strong> - <?php _e('Tunisia', 'product-recommendation'); ?></li>
                            <li><strong>ar_LY</strong> - <?php _e('Libya', 'product-recommendation'); ?></li>
                            <li><strong>ar_SD</strong> - <?php _e('Sudan', 'product-recommendation'); ?></li>
                            <li><strong>ar_TD</strong> - <?php _e('Chad', 'product-recommendation'); ?></li>
                            <li><strong>ar_MR</strong> - <?php _e('Mauritania', 'product-recommendation'); ?></li>
                            <li><strong>ar_DJ</strong> - <?php _e('Djibouti', 'product-recommendation'); ?></li>
                            <li><strong>ar_SO</strong> - <?php _e('Somalia', 'product-recommendation'); ?></li>
                            <li><strong>ar_KM</strong> - <?php _e('Comoros', 'product-recommendation'); ?></li>
                            <li><strong>ar_ER</strong> - <?php _e('Eritrea', 'product-recommendation'); ?></li>
                            <li><strong>ar_ET</strong> - <?php _e('Ethiopia', 'product-recommendation'); ?></li>
                            <li><strong>ar_SS</strong> - <?php _e('South Sudan', 'product-recommendation'); ?></li>
                        </ul>
                    </div>
                    
                    <div style="background: #e8f4fd; padding: 1em; border-radius: 8px; margin: 1em 0;">
                        <h4>🔄 <?php _e('Automatic Language Detection', 'product-recommendation'); ?></h4>
                        <p><?php _e('The plugin automatically detects your WordPress language setting and adapts all interface elements, questions, and options accordingly. If your WordPress language is set to one of the supported languages, the plugin will automatically use that language for all text and interface elements.', 'product-recommendation'); ?></p>
                    </div>
                    
                    <div style="background: #f0f8ff; padding: 1em; border-radius: 8px; margin-top: 2em;">
                        <h4>🎯 <?php _e('Summary of Steps:', 'product-recommendation'); ?></h4>
                        <p><?php _e('1. Configure base questions → 2. Customize form appearance → 3. Manage characteristics → 4. Configure products → 5. Place form on site → 6. Test functionality', 'product-recommendation'); ?></p>
                    </div>
                </div>
            </div>
            <div class="product-settings-content <?php echo $active_tab === 'general' ? 'active' : ''; ?>" id="tab-general">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('product_recommendation_settings_group');
                    do_settings_sections('product_recommendation_settings_group');
                    ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e('Enable Plugin', 'product-recommendation'); ?></th>
                            <td>
                                <input type="checkbox" name="product_recommendation_enabled" value="1" <?php checked(1, get_option('product_recommendation_enabled', 1)); ?> />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Custom Message', 'product-recommendation'); ?></th>
                            <td>
                                <input type="text" name="product_recommendation_custom_message" value="<?php echo esc_attr(get_option('product_recommendation_custom_message', '')); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Primary Color', 'product-recommendation'); ?></th>
                            <td>
                                <input type="text" name="product_recommendation_primary_color" value="<?php echo esc_attr($primary_color); ?>" class="regular-text" placeholder="<?php _e('Example: #8c0101 or rgba(140,1,1,1)', 'product-recommendation'); ?>">
                                <br><small><?php _e('Enter color code in HEX or RGBA format. (Example: #8c0101 or rgba(140,1,1,1))', 'product-recommendation'); ?></small>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Form Font Color', 'product-recommendation'); ?></th>
                            <td>
                                <input type="text" name="product_recommendation_font_color" value="<?php echo esc_attr($font_color); ?>" class="regular-text" placeholder="<?php _e('#fff or #000 or rgba(255,255,255,1)', 'product-recommendation'); ?>">
                                <br><small><?php _e('Enter form font color code.', 'product-recommendation'); ?></small>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Form Button Color', 'product-recommendation'); ?></th>
                            <td>
                                <input type="text" name="product_recommendation_button_color" value="<?php echo esc_attr($button_color); ?>" class="regular-text" placeholder="<?php _e('#fff or #8c0101 or rgba(140,1,1,1)', 'product-recommendation'); ?>">
                                <br><small><?php _e('Enter form button background color code.', 'product-recommendation'); ?></small>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Form Button Text Color', 'product-recommendation'); ?></th>
                            <td>
                                <input type="text" name="product_recommendation_button_text_color" value="<?php echo esc_attr($button_text_color); ?>" class="regular-text" placeholder="<?php _e('#8c0101 or #fff or rgba(140,1,1,1)', 'product-recommendation'); ?>">
                                <br><small><?php _e('Enter form button text color code.', 'product-recommendation'); ?></small>
                            </td>
                        </tr>
                    </table>
                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save General Settings', 'product-recommendation'); ?>"></p>
                </form>
            </div>
            <div class="product-settings-content <?php echo $active_tab === 'base' ? 'active' : ''; ?>" id="tab-base">
                <?php $this->render_base_questions_section(); ?>
            </div>
            <div class="product-settings-content <?php echo $active_tab === 'taxonomies' ? 'active' : ''; ?>" id="tab-taxonomies">
                <?php $this->render_taxonomies_section(); ?>
            </div>
            <div class="product-settings-content <?php echo $active_tab === 'languages' ? 'active' : ''; ?>" id="tab-languages">
                <?php $this->render_languages_section(); ?>
            </div>
            
            <div class="product-settings-content <?php echo $active_tab === 'debug' ? 'active' : ''; ?>" id="tab-debug">
                <?php $this->render_debug_section(); ?>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('.product-settings-tab').on('click', function() {
                const tab = $(this).data('tab');
                
                // حذف کلاس active از همه تب‌ها
                $('.product-settings-tab').removeClass('active');
                $('.product-settings-content').removeClass('active');
                
                // اضافه کردن کلاس active به تب انتخاب شده
                $(this).addClass('active');
                $('#' + 'tab-' + tab).addClass('active');
                
                // بروزرسانی URL بدون reload
                const url = new URL(window.location);
                url.searchParams.set('tab', tab);
                window.history.pushState({}, '', url);
            });
        });
        </script>
        <?php
    }

    public function render_base_questions_section() {
        // تعیین زبان فعلی وردپرس
        $current_locale = get_locale();
        $language_map = [
            'fa_IR' => 'fa',
            'en_US' => 'en', 
            'en_GB' => 'en',
            'ru_RU' => 'ru',
            'zh_CN' => 'zh',
            'ar' => 'ar'
        ];
        $current_lang = isset($language_map[$current_locale]) ? $language_map[$current_locale] : 'en';
        // اگر زبان از URL انتخاب شده، از آن استفاده کن، در غیر این صورت از زبان فعلی وردپرس
        $selected_language = isset($_GET['lang']) ? sanitize_text_field($_GET['lang']) : $current_locale;
        $selected_lang_code = isset($language_map[$selected_language]) ? $language_map[$selected_language] : 'en';

        // --- دکمه بازنشانی سوالات پایه ---
        if (isset($_POST['reset_base_questions_nonce']) && wp_verify_nonce($_POST['reset_base_questions_nonce'], 'reset_base_questions')) {
            $default_questions = $this->get_default_base_questions($selected_language);
            update_option($this->get_base_questions_option_key($selected_language), $default_questions);
            echo '<div class="updated"><p>' . __('Base questions reset to default for this language.', 'product-recommendation') . '</p></div>';
        }

        // دریافت سوالات ذخیره‌شده برای زبان انتخابی
        $questions = $this->get_base_questions($selected_language);
        
        // حذف سوال
        if (isset($_GET['delete_base_question'])) {
            $delete_index = intval($_GET['delete_base_question']);
            if (isset($questions[$delete_index])) {
                unset($questions[$delete_index]);
                $questions = array_values($questions);
                update_option($this->get_base_questions_option_key($selected_language), $questions);
                echo '<div class="updated"><p>' . __('Question deleted.', 'product-recommendation') . '</p></div>';
            }
        }
        // جابجایی سوال به بالا
        if (isset($_GET['move_up_base_question'])) {
            $move_index = intval($_GET['move_up_base_question']);
            if ($move_index > 0 && isset($questions[$move_index]) && isset($questions[$move_index-1])) {
                $tmp = $questions[$move_index-1];
                $questions[$move_index-1] = $questions[$move_index];
                $questions[$move_index] = $tmp;
                update_option($this->get_base_questions_option_key($selected_language), $questions);
                echo '<div class="updated"><p>' . __('Question moved.', 'product-recommendation') . '</p></div>';
            }
        }
        // جابجایی سوال به پایین
        if (isset($_GET['move_down_base_question'])) {
            $move_index = intval($_GET['move_down_base_question']);
            if ($move_index < count($questions) - 1 && isset($questions[$move_index]) && isset($questions[$move_index+1])) {
                $tmp = $questions[$move_index+1];
                $questions[$move_index+1] = $questions[$move_index];
                $questions[$move_index] = $tmp;
                update_option($this->get_base_questions_option_key($selected_language), $questions);
                echo '<div class="updated"><p>' . __('Question moved.', 'product-recommendation') . '</p></div>';
            }
        }
        
        // ذخیره سوالات
        if (isset($_POST['save_base_questions_nonce']) && wp_verify_nonce($_POST['save_base_questions_nonce'], 'save_base_questions')) {
            $updated_questions = array();
            if (isset($_POST['base_question_title']) && is_array($_POST['base_question_title'])) {
                foreach ($_POST['base_question_title'] as $i => $title) {
                    if (!empty($title) && isset($_POST['base_question_attribute'][$i]) && isset($_POST['base_question_options'][$i])) {
                        $options = array_map('trim', explode("\n", $_POST['base_question_options'][$i]));
                        $options = array_filter($options);
                        $updated_questions[] = [
                            'title' => sanitize_text_field($title),
                            'attribute' => sanitize_text_field($_POST['base_question_attribute'][$i]),
                    'options' => $options
                ];
            }
                }
                update_option($this->get_base_questions_option_key($selected_language), $updated_questions);
                $questions = $updated_questions;
            echo '<div class="updated"><p>' . __('Base questions saved.', 'product-recommendation') . '</p></div>';
        }
        }
        
        // افزودن سوال جدید
        if (isset($_POST['add_base_question_nonce']) && wp_verify_nonce($_POST['add_base_question_nonce'], 'add_base_question')) {
            $title = sanitize_text_field($_POST['new_base_question_title']);
            $attribute = sanitize_text_field($_POST['new_base_question_attribute']);
            $options = array_map('sanitize_text_field', explode("\n", $_POST['new_base_question_options']));
            $questions[] = [
                'title' => $title,
                'attribute' => $attribute,
                'options' => $options
            ];
            update_option($this->get_base_questions_option_key($selected_language), $questions);
            echo '<div class="updated"><p>' . __('New question added.', 'product-recommendation') . '</p></div>';
        }
        
        // نمایش انتخاب زبان
        $languages = [
            'fa_IR' => 'فارسی',
            'en_US' => 'English',
            'ru_RU' => 'Русский',
            'zh_CN' => '中文',
            'ar' => 'العربية'
        ];
        
        // Get default language
        $default_language = $this->get_default_language();
        $default_locale = array_search($default_language, $language_map);
        
        ?>
        <div class="language-selector" style="margin-bottom: 20px;">
            <h3><?php _e('Select Language for Base Questions', 'product-recommendation'); ?></h3>
            <p><strong><?php _e('Current WordPress Language:', 'product-recommendation'); ?></strong> <?php echo esc_html($languages[$current_locale] ?? $current_locale); ?></p>
            <p><strong><?php _e('Plugin Default Language:', 'product-recommendation'); ?></strong> <?php echo esc_html($languages[$default_locale] ?? $default_locale); ?></p>
            <select id="language-selector" onchange="changeLanguage(this.value)">
                <?php foreach ($languages as $locale => $name): ?>
                    <option value="<?php echo esc_attr($locale); ?>" <?php selected($selected_language, $locale); ?>>
                        <?php echo esc_html($name); ?>
                        <?php if ($locale === $default_locale): ?> (<?php _e('Default', 'product-recommendation'); ?>)<?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php _e('Select the language to edit base questions for that language. The current WordPress language is automatically selected.', 'product-recommendation'); ?></p>
        </div>
        
        <?php
        // Display language information
        $this->display_language_info();
        ?>
        
        <script>
        function changeLanguage(locale) {
            const url = new URL(window.location);
            url.searchParams.set('lang', locale);
            window.location.href = url.toString();
        }
        </script>
        
        <form method="post">
            <?php wp_nonce_field('save_base_questions', 'save_base_questions_nonce'); ?>
            <table class="form-table">
                <?php foreach ($questions as $i => $q): ?>
                <tr style="border-top:2px solid #eee;">
                    <th><label><?php _e('Question Title', 'product-recommendation'); ?> <?php echo ($i+1); ?></label></th>
                    <td><input type="text" name="base_question_title[<?php echo $i; ?>]" value="<?php echo esc_attr($q['title']); ?>" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label><?php _e('WooCommerce Attribute', 'product-recommendation'); ?></label></th>
                    <td><input type="text" name="base_question_attribute[<?php echo $i; ?>]" value="<?php echo esc_attr($q['attribute']); ?>" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label><?php _e('Options (one per line)', 'product-recommendation'); ?></label></th>
                    <td><textarea name="base_question_options[<?php echo $i; ?>]" rows="3" class="large-text" required><?php echo esc_textarea(implode("\n", $q['options'])); ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="<?php echo esc_url(add_query_arg(['delete_base_question' => $i, 'lang' => $selected_language])); ?>" onclick="return confirm('<?php _e('Delete?', 'product-recommendation'); ?>');" class="button button-danger"><?php _e('Delete', 'product-recommendation'); ?></a>
                        <?php if ($i > 0): ?>
                        <a href="<?php echo esc_url(add_query_arg(['move_up_base_question' => $i, 'lang' => $selected_language])); ?>" class="button">▲ <?php _e('Up', 'product-recommendation'); ?></a>
                        <?php endif; ?>
                        <?php if ($i < count($questions)-1): ?>
                        <a href="<?php echo esc_url(add_query_arg(['move_down_base_question' => $i, 'lang' => $selected_language])); ?>" class="button">▼ <?php _e('Down', 'product-recommendation'); ?></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <input type="submit" class="button button-primary" value="<?php _e('Save Base Questions', 'product-recommendation'); ?>">
        </form>
        <hr>
        <h3><?php _e('Add New Question', 'product-recommendation'); ?></h3>
        <form method="post">
            <?php wp_nonce_field('add_base_question', 'add_base_question_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="new_base_question_title"><?php _e('Question Title', 'product-recommendation'); ?></label></th>
                    <td><input type="text" name="new_base_question_title" id="new_base_question_title" required class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="new_base_question_attribute"><?php _e('WooCommerce Attribute', 'product-recommendation'); ?></label></th>
                    <td><input type="text" name="new_base_question_attribute" id="new_base_question_attribute" required class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="new_base_question_options"><?php _e('Options (one per line)', 'product-recommendation'); ?></label></th>
                    <td><textarea name="new_base_question_options" id="new_base_question_options" rows="3" class="large-text" required></textarea></td>
                </tr>
            </table>
            <input type="submit" class="button button-secondary" value="<?php _e('Add Question', 'product-recommendation'); ?>">
        </form>
        <form method="post" style="margin-bottom: 16px;">
            <?php wp_nonce_field('reset_base_questions', 'reset_base_questions_nonce'); ?>
            <input type="submit" class="button button-secondary" value="<?php _e('Reset Base Questions to Default for This Language', 'product-recommendation'); ?>">
        </form>
        <?php
    }

    /**
     * به صورت خودکار taxonomy و termهای اولیه را برای سوالات پایه ایجاد می‌کند
     */
    public function auto_create_base_taxonomies() {
        // استفاده از سوالات فارسی به عنوان پیش‌فرض برای ایجاد taxonomy
        $base = $this->get_base_questions('fa_IR');
        $questions = $this->get_default_base_questions('fa_IR');
        if (is_array($base) && !empty($base)) {
            $questions = $base;
        }
        $messages = array();
        foreach ($questions as $q) {
            $taxonomy = isset($q['attribute']) ? $q['attribute'] : '';
            $options = isset($q['options']) ? $q['options'] : array();
            if ($taxonomy && taxonomy_exists($taxonomy) === false) {
                $label = isset($q['title']) ? $q['title'] : $taxonomy;
                register_taxonomy($taxonomy, 'product', array(
                    'label' => $label,
                    'public' => false,
                    'show_ui' => false,
                    'hierarchical' => false,
                ));
                $messages[] = sprintf(__("Feature '%s' (taxonomy: %s) created successfully.", 'product-recommendation'), $label, $taxonomy);
            }
            // افزودن termهای اولیه
            if ($taxonomy && !empty($options)) {
                foreach ($options as $term) {
                    if (!term_exists($term, $taxonomy)) {
                        $result = wp_insert_term($term, $taxonomy);
                        if (!is_wp_error($result)) {
                            $messages[] = sprintf(__("Option '%s' added to feature '%s'.", 'product-recommendation'), $term, $taxonomy);
                        } else {
                            $messages[] = sprintf(__("Error adding option '%s' to feature '%s': %s", 'product-recommendation'), $term, $taxonomy, $result->get_error_message());
                        }
                    }
                }
            }
        }
        if (!empty($messages) && is_admin() && isset($_GET['page']) && $_GET['page'] === 'product-recommendation-settings') {
            add_action('admin_notices', function() use ($messages) {
                echo '<div class="notice notice-success is-dismissible"><ul style="margin:0;">';
                foreach ($messages as $msg) {
                    echo '<li>' . esc_html($msg) . '</li>';
                }
                echo '</ul></div>';
            });
        }
    }

    /**
     * نمایش لیست taxonomy و termهای مربوط به افزونه
     */
    public function render_taxonomies_section() {
        // استفاده از سوالات فارسی به عنوان پیش‌فرض برای نمایش
        $base = $this->get_base_questions('fa_IR');
        $questions = $this->get_default_base_questions('fa_IR');
        if (is_array($base) && !empty($base)) {
            $questions = $base;
        }
        echo '<h2>' . __('Plugin Features (Taxonomy)', 'product-recommendation') . '</h2>';
        echo '<p style="color:#666;">' . __('In this section, you can manage the options for each feature (taxonomy). Adding, editing, and deleting options is only possible from this section, and access to WooCommerce\'s default management for these features is disabled.', 'product-recommendation') . '</p>';
        echo '<table class="widefat"><thead><tr><th>' . __('Feature Name (taxonomy)', 'product-recommendation') . '</th><th>' . __('Title', 'product-recommendation') . '</th><th>' . __('Options (terms)', 'product-recommendation') . '</th></tr></thead><tbody>';
        foreach ($questions as $q) {
            $taxonomy = isset($q['attribute']) ? $q['attribute'] : '';
            $label = isset($q['title']) ? $q['title'] : $taxonomy;
            $terms = array();
            if ($taxonomy && taxonomy_exists($taxonomy)) {
                $term_objs = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
                if (!is_wp_error($term_objs)) {
                    foreach ($term_objs as $term) {
                        $terms[] = [
                            'id' => $term->term_id,
                            'name' => $term->name
                        ];
                    }
                }
            }
            echo '<tr>';
            echo '<td><code>' . esc_html($taxonomy) . '</code></td>';
            echo '<td>' . esc_html($label) . '</td>';
            echo '<td>';
            // لیست terms
            if (!empty($terms)) {
                echo '<ul style="margin:0; padding-right:1em;">';
                foreach ($terms as $t) {
                    echo '<li>' . esc_html($t['name']) .
                        ' <a href="' . esc_url(add_query_arg(['edit_term' => $t['id'], 'taxonomy' => $taxonomy])) . '" class="button button-small">' . __('Edit', 'product-recommendation') . '</a>' .
                        ' <a href="' . esc_url(add_query_arg(['delete_term' => $t['id'], 'taxonomy' => $taxonomy])) . '" class="button button-small" onclick="return confirm(\'' . __('Delete?', 'product-recommendation') . '\');">' . __('Delete', 'product-recommendation') . '</a>' .
                        '</li>';
                }
                echo '</ul>';
            } else {
                echo '<span style="color:#888">' . __('No options', 'product-recommendation') . '</span>';
            }
            // فرم افزودن term جدید
            echo '<form method="post" style="margin-top:1em;">';
            wp_nonce_field('add_term_' . $taxonomy, 'add_term_nonce_' . $taxonomy);
            echo '<input type="hidden" name="taxonomy" value="' . esc_attr($taxonomy) . '">';
            echo '<input type="text" name="new_term_name" placeholder="' . __('Add new option...', 'product-recommendation') . '" required class="regular-text"> ';
            echo '<input type="submit" class="button button-secondary" value="' . __('Add', 'product-recommendation') . '">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '<p style="margin-top:1em;color:#666;">' . __('This list only displays features managed by the plugin.', 'product-recommendation') . '</p>';

        // مدیریت افزودن، ویرایش و حذف term
        if (isset($_POST['new_term_name'], $_POST['taxonomy']) && isset($_POST['add_term_nonce_' . $_POST['taxonomy']]) && wp_verify_nonce($_POST['add_term_nonce_' . $_POST['taxonomy']], 'add_term_' . $_POST['taxonomy'])) {
            $taxonomy = sanitize_text_field($_POST['taxonomy']);
            $term_name = sanitize_text_field($_POST['new_term_name']);
            if ($taxonomy && $term_name && taxonomy_exists($taxonomy)) {
                $result = wp_insert_term($term_name, $taxonomy);
                if (!is_wp_error($result)) {
                    echo '<div class="notice notice-success is-dismissible"><p>' . __('New option added.', 'product-recommendation') . '</p></div>';
                } else {
                    echo '<div class="notice notice-error is-dismissible"><p>' . __('Error:', 'product-recommendation') . ' ' . esc_html($result->get_error_message()) . '</p></div>';
                }
            }
        }
        if (isset($_GET['delete_term'], $_GET['taxonomy'])) {
            $taxonomy = sanitize_text_field($_GET['taxonomy']);
            $term_id = intval($_GET['delete_term']);
            if ($taxonomy && taxonomy_exists($taxonomy) && $term_id) {
                $result = wp_delete_term($term_id, $taxonomy);
                if (!is_wp_error($result)) {
                    echo '<div class="notice notice-success is-dismissible"><p>' . __('Option deleted.', 'product-recommendation') . '</p></div>';
                } else {
                    echo '<div class="notice notice-error is-dismissible"><p>' . __('Error:', 'product-recommendation') . ' ' . esc_html($result->get_error_message()) . '</p></div>';
                }
            }
        }
        if (isset($_GET['edit_term'], $_GET['taxonomy'])) {
            $taxonomy = sanitize_text_field($_GET['taxonomy']);
            $term_id = intval($_GET['edit_term']);
            if ($taxonomy && taxonomy_exists($taxonomy) && $term_id) {
                $term = get_term($term_id, $taxonomy);
                if ($term && !is_wp_error($term)) {
                    echo '<div class="notice notice-info is-dismissible"><form method="post" style="margin:1em 0;">';
                    wp_nonce_field('edit_term_' . $taxonomy . '_' . $term_id, 'edit_term_nonce_' . $taxonomy . '_' . $term_id);
                    echo '<input type="hidden" name="taxonomy" value="' . esc_attr($taxonomy) . '">';
                    echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';
                    echo '<input type="text" name="edit_term_name" value="' . esc_attr($term->name) . '" required class="regular-text"> ';
                    echo '<input type="submit" class="button button-primary" value="ذخیره ویرایش">';
                    echo '</form></div>';
                }
            }
        }
        if (isset($_POST['edit_term_name'], $_POST['taxonomy'], $_POST['term_id']) && isset($_POST['edit_term_nonce_' . $_POST['taxonomy'] . '_' . $_POST['term_id']]) && wp_verify_nonce($_POST['edit_term_nonce_' . $_POST['taxonomy'] . '_' . $_POST['term_id']], 'edit_term_' . $_POST['taxonomy'] . '_' . $_POST['term_id'])) {
            $taxonomy = sanitize_text_field($_POST['taxonomy']);
            $term_id = intval($_POST['term_id']);
            $term_name = sanitize_text_field($_POST['edit_term_name']);
            if ($taxonomy && $term_id && $term_name && taxonomy_exists($taxonomy)) {
                $result = wp_update_term($term_id, $taxonomy, array('name' => $term_name));
                if (!is_wp_error($result)) {
                    echo '<div class="notice notice-success is-dismissible"><p>' . __('Option updated successfully.', 'product-recommendation') . '</p></div>';
                } else {
                    echo '<div class="notice notice-error is-dismissible"><p>' . __('Error:', 'product-recommendation') . ' ' . esc_html($result->get_error_message()) . '</p></div>';
                }
            }
        }
    }

    /**
     * حذف منوهای taxonomy از ووکامرس
     */
    public function remove_woocommerce_taxonomy_menus() {
        // حذف تمام taxonomy های مربوط به افزونه
        $product_taxonomies = array(
            'product_category',
            'product_price_range', 
            'product_for_whom',
            'product_style',
            'product_usage',
            'product_special_feature',
            'product_notes',
            'product_occasion',
            'product_season',
            'product_brand',
            'product_quality'
        );
        
        foreach ($product_taxonomies as $taxonomy) {
            remove_submenu_page('edit.php?post_type=product', 'edit-tags.php?taxonomy=' . $taxonomy . '&post_type=product');
        }
        
        // حذف صفحه product-recommendations از منوی محصولات
        remove_submenu_page('edit.php?post_type=product', 'product-recommendations');
    }

    /**
     * مخفی کردن تمام عناصر مربوط به افزونه در پیشخوان
     */
    public function hide_product_admin_elements() {
        // این تابع خالی است - هیچ عنصری مخفی نمی‌شود
    }

    /**
     * مخفی کردن اسکریپت‌های مربوط به افزونه
     */
    public function hide_product_admin_scripts() {
        // این تابع خالی است - هیچ اسکریپتی مخفی نمی‌شود
    }

    /**
     * حذف ستون‌های مربوط به افزونه از لیست محصولات
     */
    public function remove_product_columns($columns) {
        $product_taxonomies = array(
            'product_category',
            'product_price_range',
            'product_for_whom',
            'product_style',
            'product_usage',
            'product_special_feature',
            'product_notes',
            'product_occasion',
            'product_season',
            'product_brand',
            'product_quality'
        );
        
        foreach ($product_taxonomies as $taxonomy) {
            if (isset($columns[$taxonomy])) {
                unset($columns[$taxonomy]);
            }
        }
        
        return $columns;
    }

    /**
     * حذف metabox های مربوط به افزونه
     */
    public function remove_product_meta_boxes() {
        if (!$this->is_product_settings_page()) {
            remove_meta_box('product_characteristics_meta_box', 'product', 'normal');
            remove_meta_box('product-recommendation-meta_box', 'product', 'normal');
            remove_meta_box('product_meta_box', 'product', 'normal');
        }
    }

    /**
     * حذف ستون‌های مربوط به افزونه از لیست محصولات
     */
    public function remove_product_admin_columns($columns) {
        $product_columns = array(
            'product_category',
            'product_price_range',
            'product_for_whom',
            'product_style',
            'product_usage',
            'product_special_feature',
            'product_notes',
            'product_occasion',
            'product_season',
            'product_brand',
            'product_quality'
        );
        
        foreach ($product_columns as $column) {
            if (isset($columns[$column])) {
                unset($columns[$column]);
            }
        }
        
        return $columns;
    }

    /**
     * مخفی کردن لینک‌های مربوط به افزونه
     */
    public function hide_product_related_links() {
        if (!$this->is_product_settings_page()) {
            echo '<style>
            /* مخفی کردن لینک‌های taxonomy در منوی محصولات */
            .taxonomy-product_category,
            .taxonomy-product_price_range,
            .taxonomy-product_for_whom,
            .taxonomy-product_style,
            .taxonomy-product_usage,
            .taxonomy-product_special_feature,
            .taxonomy-product_notes,
            .taxonomy-product_occasion,
            .taxonomy-product_season,
            .taxonomy-product_brand,
            .taxonomy-product_quality {
                display: none !important;
            }
            
            /* مخفی کردن لینک product-recommendations */
            a[href*="product-recommendations"] {
                display: none !important;
            }
            
            /* مخفی کردن لینک‌های taxonomy در breadcrumb */
            .taxonomy-product_category a,
            .taxonomy-product_price_range a,
            .taxonomy-product_for_whom a,
            .taxonomy-product_style a,
            .taxonomy-product_usage a,
            .taxonomy-product_special_feature a,
            .taxonomy-product_notes a,
            .taxonomy-product_occasion a,
            .taxonomy-product_season a,
            .taxonomy-product_brand a,
            .taxonomy-product_quality a {
                display: none !important;
            }
            </style>';
        }
    }

    /**
     * بررسی اینکه آیا در صفحه تنظیمات افزونه هستیم یا نه
     */
    private function is_product_settings_page() {
        return isset($_GET['page']) && (
            strpos($_GET['page'], 'product-recommendation-settings') !== false ||
            strpos($_GET['page'], 'product-notes-management') !== false ||
            strpos($_GET['page'], 'product-occasion-management') !== false ||
            strpos($_GET['page'], 'product-season-management') !== false ||
            strpos($_GET['page'], 'product-brand-management') !== false ||
            strpos($_GET['page'], 'product-quality-management') !== false ||
            strpos($_GET['page'], 'product-category-management') !== false
        );
    }

    /**
     * ریدایرکت صفحات taxonomy به تنظیمات افزونه
     */
    public function redirect_taxonomy_pages() {
        if (!is_admin()) return;
        
        $product_taxonomies = array(
            'product_category' => 'base',
            'product_price_range' => 'base',
            'product_for_whom' => 'base',
            'product_style' => 'base',
            'product_usage' => 'base',
            'product_special_feature' => 'base',
            'product_notes' => 'notes',
            'product_occasion' => 'occasion',
            'product_season' => 'season',
            'product_brand' => 'brand',
            'product_quality' => 'quality'
        );
        
        // ریدایرکت صفحات edit-tags برای taxonomy های افزونه
        if (isset($_GET['taxonomy']) && array_key_exists($_GET['taxonomy'], $product_taxonomies)) {
            $tab = $product_taxonomies[$_GET['taxonomy']];
            wp_redirect(admin_url('admin.php?page=product-recommendation-settings&tab=' . $tab . '&message=redirected'));
            exit;
        }
        
        // ریدایرکت صفحه product-recommendations
        if (isset($_GET['page']) && $_GET['page'] === 'product-recommendations') {
            wp_redirect(admin_url('admin.php?page=product-recommendation-settings&message=redirected'));
            exit;
        }
    }

    // تابع رندر راهنما
    public function render_guide_page() {
        // فقط تب راهنما را نمایش بده
        $active_tab = 'guide';
        ?>
        <div class="wrap">
            <h1>پیشنهاد محصول - راهنما</h1>
            <div class="product-settings-tabs">
                <button class="product-settings-tab active" data-tab="guide">راهنما</button>
            </div>
            <div class="product-settings-content active" id="tab-guide">
                <?php $this->render_settings_page_guide_only(); ?>
            </div>
        </div>
        <?php
    }

    // تابع کمکی برای نمایش فقط راهنما
    private function render_settings_page_guide_only() {
        // کد راهنمای کامل را از render_settings_page کپی کن
        ?>
        <div class="card">
            <h2>راهنمای کامل استفاده از افزونه پیشنهاد محصول</h2>
            <!-- ... (همان محتوای راهنما که در تب راهنما بود) ... -->
        </div>
        <?php
    }

    /**
     * Handle language update request
     */
    public function handle_language_update() {
        if (isset($_GET['update_language']) && $_GET['update_language'] === '1') {
            $new_language = $this->update_default_language();
            
            // Redirect back to the same page with success message
            $redirect_url = remove_query_arg('update_language', $_SERVER['REQUEST_URI']);
            $redirect_url = add_query_arg('language_updated', '1', $redirect_url);
            wp_redirect($redirect_url);
            exit;
        }
    }

    /**
     * Update plugin default language to match current WordPress language
     */
    public function update_default_language() {
        $current_locale = get_locale();
        
        $language_map = [
            'fa_IR' => 'fa',
            'en_US' => 'en', 
            'en_GB' => 'en',
            'ru_RU' => 'ru',
            'zh_CN' => 'zh',
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
            'ar_MA' => 'ar',
            'ar_DZ' => 'ar',
            'ar_TN' => 'ar',
            'ar_LY' => 'ar',
            'ar_SD' => 'ar',
            'ar_TD' => 'ar',
            'ar_MR' => 'ar',
            'ar_DJ' => 'ar',
            'ar_SO' => 'ar',
            'ar_KM' => 'ar',
            'ar_ER' => 'ar',
            'ar_ET' => 'ar',
            'ar_SS' => 'ar'
        ];
        
        $current_lang_code = isset($language_map[$current_locale]) ? $language_map[$current_locale] : 'en';
        update_option('product_recommendation_default_language', $current_lang_code);
        
        return $current_lang_code;
    }

    /**
     * Render languages support section
     */
    public function render_languages_section() {
        $this->display_language_info();
        ?>
        <div class="card">
            <h2><?php _e('Supported Languages and Countries', 'product-recommendation'); ?></h2>
            <p><?php _e('This plugin supports multiple languages and automatically adapts to your WordPress language setting. The following languages and countries are fully supported:', 'product-recommendation'); ?></p>
            
            <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                <h4>🇮🇷 <?php _e('Persian/Farsi (فارسی)', 'product-recommendation'); ?></h4>
                <ul>
                    <li><strong>fa_IR</strong> - <?php _e('Iran', 'product-recommendation'); ?></li>
                    <li><strong>fa_AF</strong> - <?php _e('Afghanistan', 'product-recommendation'); ?></li>
                    <li><strong>fa_TJ</strong> - <?php _e('Tajikistan', 'product-recommendation'); ?></li>
                    <li><strong>fa</strong> - <?php _e('General Persian', 'product-recommendation'); ?></li>
                </ul>
            </div>
            
            <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                <h4>🇺🇸 <?php _e('English', 'product-recommendation'); ?></h4>
                <ul>
                    <li><strong>en_US</strong> - <?php _e('United States', 'product-recommendation'); ?></li>
                    <li><strong>en_GB</strong> - <?php _e('United Kingdom', 'product-recommendation'); ?></li>
                    <li><strong>en_CA</strong> - <?php _e('Canada', 'product-recommendation'); ?></li>
                    <li><strong>en_AU</strong> - <?php _e('Australia', 'product-recommendation'); ?></li>
                    <li><strong>en_NZ</strong> - <?php _e('New Zealand', 'product-recommendation'); ?></li>
                    <li><strong>en_ZA</strong> - <?php _e('South Africa', 'product-recommendation'); ?></li>
                    <li><strong>en_IE</strong> - <?php _e('Ireland', 'product-recommendation'); ?></li>
                    <li><strong>en_IN</strong> - <?php _e('India', 'product-recommendation'); ?></li>
                    <li><strong>en_PK</strong> - <?php _e('Pakistan', 'product-recommendation'); ?></li>
                    <li><strong>en_NG</strong> - <?php _e('Nigeria', 'product-recommendation'); ?></li>
                    <li><strong>en_KE</strong> - <?php _e('Kenya', 'product-recommendation'); ?></li>
                    <li><strong>en_UG</strong> - <?php _e('Uganda', 'product-recommendation'); ?></li>
                    <li><strong>en_TZ</strong> - <?php _e('Tanzania', 'product-recommendation'); ?></li>
                    <li><strong>en_ZW</strong> - <?php _e('Zimbabwe', 'product-recommendation'); ?></li>
                    <li><strong>en_PH</strong> - <?php _e('Philippines', 'product-recommendation'); ?></li>
                    <li><strong>en_MY</strong> - <?php _e('Malaysia', 'product-recommendation'); ?></li>
                    <li><strong>en_SG</strong> - <?php _e('Singapore', 'product-recommendation'); ?></li>
                    <li><strong>en_HK</strong> - <?php _e('Hong Kong', 'product-recommendation'); ?></li>
                    <li><strong>en</strong> - <?php _e('General English', 'product-recommendation'); ?></li>
                </ul>
            </div>
            
            <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                <h4>🇷🇺 <?php _e('Russian (Русский)', 'product-recommendation'); ?></h4>
                <ul>
                    <li><strong>ru_RU</strong> - <?php _e('Russia', 'product-recommendation'); ?></li>
                    <li><strong>ru_UA</strong> - <?php _e('Ukraine', 'product-recommendation'); ?></li>
                    <li><strong>ru_BY</strong> - <?php _e('Belarus', 'product-recommendation'); ?></li>
                    <li><strong>ru_KZ</strong> - <?php _e('Kazakhstan', 'product-recommendation'); ?></li>
                    <li><strong>ru_KG</strong> - <?php _e('Kyrgyzstan', 'product-recommendation'); ?></li>
                    <li><strong>ru</strong> - <?php _e('General Russian', 'product-recommendation'); ?></li>
                </ul>
            </div>
            
            <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                <h4>🇨🇳 <?php _e('Chinese (中文)', 'product-recommendation'); ?></h4>
                <ul>
                    <li><strong>zh_CN</strong> - <?php _e('China (Simplified)', 'product-recommendation'); ?></li>
                    <li><strong>zh_TW</strong> - <?php _e('Taiwan (Traditional)', 'product-recommendation'); ?></li>
                    <li><strong>zh_HK</strong> - <?php _e('Hong Kong', 'product-recommendation'); ?></li>
                    <li><strong>zh_SG</strong> - <?php _e('Singapore', 'product-recommendation'); ?></li>
                    <li><strong>zh_MO</strong> - <?php _e('Macau', 'product-recommendation'); ?></li>
                    <li><strong>zh</strong> - <?php _e('General Chinese', 'product-recommendation'); ?></li>
                </ul>
            </div>
            
            <div style="background: #f9f9f9; padding: 1em; border-radius: 8px; margin: 1em 0;">
                <h4>🇸🇦 <?php _e('Arabic (العربية)', 'product-recommendation'); ?></h4>
                <ul>
                    <li><strong>ar</strong> - <?php _e('General Arabic', 'product-recommendation'); ?></li>
                    <li><strong>ar_SA</strong> - <?php _e('Saudi Arabia', 'product-recommendation'); ?></li>
                    <li><strong>ar_EG</strong> - <?php _e('Egypt', 'product-recommendation'); ?></li>
                    <li><strong>ar_AE</strong> - <?php _e('United Arab Emirates', 'product-recommendation'); ?></li>
                    <li><strong>ar_JO</strong> - <?php _e('Jordan', 'product-recommendation'); ?></li>
                    <li><strong>ar_SY</strong> - <?php _e('Syria', 'product-recommendation'); ?></li>
                    <li><strong>ar_LB</strong> - <?php _e('Lebanon', 'product-recommendation'); ?></li>
                    <li><strong>ar_IQ</strong> - <?php _e('Iraq', 'product-recommendation'); ?></li>
                    <li><strong>ar_KW</strong> - <?php _e('Kuwait', 'product-recommendation'); ?></li>
                    <li><strong>ar_QA</strong> - <?php _e('Qatar', 'product-recommendation'); ?></li>
                    <li><strong>ar_BH</strong> - <?php _e('Bahrain', 'product-recommendation'); ?></li>
                    <li><strong>ar_OM</strong> - <?php _e('Oman', 'product-recommendation'); ?></li>
                    <li><strong>ar_YE</strong> - <?php _e('Yemen', 'product-recommendation'); ?></li>
                    <li><strong>ar_MA</strong> - <?php _e('Morocco', 'product-recommendation'); ?></li>
                    <li><strong>ar_DZ</strong> - <?php _e('Algeria', 'product-recommendation'); ?></li>
                    <li><strong>ar_TN</strong> - <?php _e('Tunisia', 'product-recommendation'); ?></li>
                    <li><strong>ar_LY</strong> - <?php _e('Libya', 'product-recommendation'); ?></li>
                    <li><strong>ar_SD</strong> - <?php _e('Sudan', 'product-recommendation'); ?></li>
                    <li><strong>ar_TD</strong> - <?php _e('Chad', 'product-recommendation'); ?></li>
                    <li><strong>ar_MR</strong> - <?php _e('Mauritania', 'product-recommendation'); ?></li>
                    <li><strong>ar_DJ</strong> - <?php _e('Djibouti', 'product-recommendation'); ?></li>
                    <li><strong>ar_SO</strong> - <?php _e('Somalia', 'product-recommendation'); ?></li>
                    <li><strong>ar_KM</strong> - <?php _e('Comoros', 'product-recommendation'); ?></li>
                    <li><strong>ar_ER</strong> - <?php _e('Eritrea', 'product-recommendation'); ?></li>
                    <li><strong>ar_ET</strong> - <?php _e('Ethiopia', 'product-recommendation'); ?></li>
                    <li><strong>ar_SS</strong> - <?php _e('South Sudan', 'product-recommendation'); ?></li>
                </ul>
            </div>
            
            <div style="background: #e8f4fd; padding: 1em; border-radius: 8px; margin: 1em 0;">
                <h4>🔄 <?php _e('Automatic Language Detection', 'product-recommendation'); ?></h4>
                <p><?php _e('The plugin automatically detects your WordPress language setting and adapts all interface elements, questions, and options accordingly. If your WordPress language is set to one of the supported languages, the plugin will automatically use that language for all text and interface elements.', 'product-recommendation'); ?></p>
            </div>
            
            <div style="background: #f0f8ff; padding: 1em; border-radius: 8px; margin-top: 2em;">
                <h4>🎯 <?php _e('Summary of Steps:', 'product-recommendation'); ?></h4>
                <p><?php _e('1. Configure base questions → 2. Customize form appearance → 3. Manage characteristics → 4. Configure products → 5. Place form on site → 6. Test functionality', 'product-recommendation'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Display language information in admin panel
     */
    public function display_language_info() {
        $current_locale = get_locale();
        $default_language = $this->get_default_language();
        
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
            'ar_MA' => 'ar',
            'ar_DZ' => 'ar',
            'ar_TN' => 'ar',
            'ar_LY' => 'ar',
            'ar_SD' => 'ar',
            'ar_TD' => 'ar',
            'ar_MR' => 'ar',
            'ar_DJ' => 'ar',
            'ar_SO' => 'ar',
            'ar_KM' => 'ar',
            'ar_ER' => 'ar',
            'ar_ET' => 'ar',
            'ar_SS' => 'ar'
        ];
        
        $languages = [
            // Persian/Farsi variants
            'fa_IR' => 'فارسی',
            'fa_AF' => 'فارسی',
            'fa_TJ' => 'فارسی',
            'fa' => 'فارسی',
            
            // English variants
            'en_US' => 'English',
            'en_GB' => 'English',
            'en_CA' => 'English',
            'en_AU' => 'English',
            'en_NZ' => 'English',
            'en_ZA' => 'English',
            'en_IE' => 'English',
            'en_IN' => 'English',
            'en_PK' => 'English',
            'en_NG' => 'English',
            'en_KE' => 'English',
            'en_UG' => 'English',
            'en_TZ' => 'English',
            'en_ZW' => 'English',
            'en_PH' => 'English',
            'en_MY' => 'English',
            'en_SG' => 'English',
            'en_HK' => 'English',
            'en' => 'English',
            
            // Russian variants
            'ru_RU' => 'Русский',
            'ru_UA' => 'Русский',
            'ru_BY' => 'Русский',
            'ru_KZ' => 'Русский',
            'ru_KG' => 'Русский',
            'ru_TJ' => 'Русский',
            'ru_UZ' => 'Русский',
            'ru_TM' => 'Русский',
            'ru_MD' => 'Русский',
            'ru_AM' => 'Русский',
            'ru_AZ' => 'Русский',
            'ru_GE' => 'Русский',
            'ru' => 'Русский',
            
            // Chinese variants
            'zh_CN' => '中文',
            'zh_TW' => '中文',
            'zh_HK' => '中文',
            'zh_SG' => '中文',
            'zh_MO' => '中文',
            'zh' => '中文',
            
            // Arabic variants
            'ar' => 'العربية',
            'ar_SA' => 'العربية',
            'ar_EG' => 'العربية',
            'ar_AE' => 'العربية',
            'ar_JO' => 'العربية',
            'ar_SY' => 'العربية',
            'ar_LB' => 'العربية',
            'ar_IQ' => 'العربية',
            'ar_KW' => 'العربية',
            'ar_QA' => 'العربية',
            'ar_BH' => 'العربية',
            'ar_OM' => 'العربية',
            'ar_YE' => 'العربية',
            'ar_MA' => 'العربية',
            'ar_DZ' => 'العربية',
            'ar_TN' => 'العربية',
            'ar_LY' => 'العربية',
            'ar_SD' => 'العربية',
            'ar_TD' => 'العربية',
            'ar_MR' => 'العربية',
            'ar_DJ' => 'العربية',
            'ar_SO' => 'العربية',
            'ar_KM' => 'العربية',
            'ar_ER' => 'العربية',
            'ar_ET' => 'العربية',
            'ar_SS' => 'العربية'
        ];
        
        $current_lang_code = isset($language_map[$current_locale]) ? $language_map[$current_locale] : 'en';
        $default_locale = array_search($default_language, $language_map);
        
        ?>
        <div class="language-info" style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h4><?php _e('Language Information', 'product-recommendation'); ?></h4>
            <p><strong><?php _e('Current WordPress Language:', 'product-recommendation'); ?></strong> <?php echo esc_html($languages[$current_locale] ?? $current_locale); ?> (<?php echo esc_html($current_lang_code); ?>)</p>
            <p><strong><?php _e('Plugin Default Language:', 'product-recommendation'); ?></strong> <?php echo esc_html($languages[$default_locale] ?? $default_locale); ?> (<?php echo esc_html($default_language); ?>)</p>
            <?php if ($current_lang_code === $default_language): ?>
                <p style="color: green;"><strong><?php _e('✓ Languages match - Plugin will use current WordPress language', 'product-recommendation'); ?></strong></p>
            <?php else: ?>
                <p style="color: orange;"><strong><?php _e('⚠ Languages differ - Plugin will use WordPress language, but default is different', 'product-recommendation'); ?></strong></p>
                <p>
                    <a href="<?php echo esc_url(add_query_arg('update_language', '1', $_SERVER['REQUEST_URI'])); ?>" class="button button-primary">
                        <?php _e('Update Plugin Default Language to Match WordPress', 'product-recommendation'); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }
    
    public function render_debug_section() {
        $locale = get_locale();
        $mo_path = WP_PLUGIN_DIR . '/product-recommendation/languages/product-recommendation-' . $locale . '.mo';
        $plugin_mo_path = plugin_dir_path(__FILE__) . '../languages/product-recommendation-' . $locale . '.mo';
        
        echo '<div class="card">';
        echo '<h2>' . __('Translation Debug Information', 'product-recommendation') . '</h2>';
        echo '<div style="background:#f9f9f9; border:1px solid #ddd; padding:15px; margin:15px 0; border-radius:5px; font-family:monospace; font-size:13px;">';
        echo '<strong>Locale:</strong> ' . esc_html($locale) . '<br>';
        echo '<strong>MO file:</strong> ' . esc_html($mo_path) . ' - ' . (file_exists($mo_path) ? '<span style="color:green">exists</span>' : '<span style="color:red">missing</span>') . '<br>';
        echo '<strong>Test translation:</strong> ' . __('Settings', 'product-recommendation') . '<br>';
        echo '<strong>Text Domain Loaded:</strong> ' . (is_textdomain_loaded('product-recommendation') ? 'Yes' : 'No') . '<br>';
        echo '<strong>WordPress Language:</strong> ' . get_option('WPLANG', 'default') . '<br>';
        echo '<strong>Site Language:</strong> ' . get_bloginfo('language') . '<br>';
        echo '<strong>Plugin MO File:</strong> ' . $plugin_mo_path . ' - ' . (file_exists($plugin_mo_path) ? '<span style="color:green">exists</span>' : '<span style="color:red">missing</span>') . '<br>';
        echo '<strong>Direct MO Path:</strong> ' . WP_PLUGIN_DIR . '/product-recommendation/languages/product-recommendation-' . $locale . '.mo - ' . (file_exists(WP_PLUGIN_DIR . '/product-recommendation/languages/product-recommendation-' . $locale . '.mo') ? '<span style="color:green">exists</span>' : '<span style="color:red">missing</span>') . '<br>';
        echo '<strong>Text Domain Loaded After Force:</strong> ' . (is_textdomain_loaded('product-recommendation') ? 'Yes' : 'No') . '</div>';
        
        echo '<h3>' . __('Available Translation Files', 'product-recommendation') . '</h3>';
        $languages_dir = plugin_dir_path(__FILE__) . '../languages/';
        $mo_files = glob($languages_dir . '*.mo');
        if ($mo_files) {
            echo '<ul>';
            foreach ($mo_files as $file) {
                $filename = basename($file);
                $size = filesize($file);
                echo '<li><strong>' . $filename . '</strong> (' . number_format($size) . ' bytes)</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . __('No .mo files found.', 'product-recommendation') . '</p>';
        }
        
        echo '</div>';
    }
}

// فعال‌سازی تنظیمات هنگام بارگذاری ادمین
if (is_admin()) {
    new Product_Recommendation_Settings();
} 