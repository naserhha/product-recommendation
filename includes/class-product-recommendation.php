<?php

class Product_Recommendation {
    private $form;
    private $database;
    private $ajax;
    private $settings;

    public function __construct() {
        // فقط اگر ووکامرس فعال باشد، کلاس‌های وابسته را بارگذاری کن
        if (class_exists('WooCommerce')) {
            $this->form = new Product_Form();
            $this->database = new Product_Database();
            $this->ajax = new Product_Recommendation_Ajax();
        }
        $this->settings = new Product_Recommendation_Settings();
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('product-recommendation', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Add hooks for language change detection
        add_action('init', array($this, 'handle_language_change'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add admin menu (always available)
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Fix language mismatch if needed
        add_action('admin_init', array($this, 'fix_language_mismatch'));
        
        // فقط اگر ووکامرس فعال باشد، قابلیت‌های مربوطه را اضافه کن
        if (class_exists('WooCommerce')) {
            // Enqueue frontend assets
            add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
            
            // Add product meta box
            add_action('add_meta_boxes', array($this, 'add_product_meta_box'));
            
            // Save product characteristics
            add_action('save_post', array($this, 'save_product_characteristics'));
            
            // Add shortcode
            add_shortcode('product_recommendation_form', array($this, 'render_recommendation_form'));
            
            // Add AJAX handlers
            add_action('wp_ajax_get_product_recommendations', array($this, 'get_product_recommendations'));
            add_action('wp_ajax_nopriv_get_product_recommendations', array($this, 'get_product_recommendations'));
            
            // Add admin notice
            add_action('admin_notices', array($this, 'admin_notice'));
        }
    }
    
    /**
     * Handle language change detection
     */
    public function handle_language_change() {
        // Force reload of text domain when language changes
        $current_locale = get_locale();
        static $last_locale = null;
        
        if ($last_locale !== $current_locale) {
            $last_locale = $current_locale;
            load_plugin_textdomain('product-recommendation', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }
    }
    
    /**
     * Fix language mismatch by updating plugin default language to match WordPress
     */
    public function fix_language_mismatch() {
        // Only run once per session to avoid constant updates
        static $fixed = false;
        if ($fixed) {
            return;
        }
        
        $current_locale = get_locale();
        $current_default = get_option('product_recommendation_default_language', 'en');
        
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
            'ar_PS' => 'ar',
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
        
        $expected_lang_code = isset($language_map[$current_locale]) ? $language_map[$current_locale] : 'en';
        
        // If there's a mismatch, update the plugin's default language
        if ($current_default !== $expected_lang_code) {
            update_option('product_recommendation_default_language', $expected_lang_code);
            $fixed = true;
        }
    }

    public function register_settings() {
        register_setting('product_recommendation_settings_group', 'product_recommendation_enabled');
        register_setting('product_recommendation_settings_group', 'product_recommendation_custom_message');
        register_setting('product_recommendation_settings_group', 'product_recommendation_primary_color');
        register_setting('product_recommendation_settings_group', 'product_recommendation_font_color');
        register_setting('product_recommendation_settings_group', 'product_recommendation_button_color');
        register_setting('product_recommendation_settings_group', 'product_recommendation_button_text_color');
    }

    public function enqueue_frontend_assets() {
        // فقط اگر ووکامرس فعال باشد، assets را بارگذاری کن
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        // Load CSS
        wp_enqueue_style(
            'product-recommendation-style',
            PRODUCT_RECOMMENDATION_PLUGIN_URL . 'assets/css/style.css',
            array(),
            PRODUCT_RECOMMENDATION_VERSION,
            'all'
        );
        
        // Load JavaScript
        wp_enqueue_script(
            'product-recommendation-script',
            PRODUCT_RECOMMENDATION_PLUGIN_URL . 'assets/js/script.js',
            array('jquery'),
            PRODUCT_RECOMMENDATION_VERSION,
            true
        );

        // Localize script
        wp_localize_script('product-recommendation-script', 'productAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('product_recommendation_nonce'),
            'loading_text' => __('Loading...', 'product-recommendation'),
            'error_text' => __('An error occurred. Please try again.', 'product-recommendation'),
            'no_results_text' => __('No products found matching your criteria.', 'product-recommendation'),
            'view_details_text' => __('View Details', 'product-recommendation'),
            'add_to_cart_text' => __('Add to Cart', 'product-recommendation'),
            'placeholder_image' => wc_placeholder_img_src('medium')
        ));
    }

    public function add_admin_menu() {
        // منوی اصلی
        add_menu_page(
            __('Product Recommendation System', 'product-recommendation'),
            __('Product Recommendation System', 'product-recommendation'),
            'manage_options',
            'product-recommendation',
            array($this, 'render_admin_page'),
            'dashicons-smiley',
            30
        );
        
        // زیرمنوی تنظیمات
        add_submenu_page(
            'product-recommendation',
            __('Settings', 'product-recommendation'),
            __('Settings', 'product-recommendation'),
            'manage_options',
            'product-recommendation-settings',
            array($this->settings, 'render_settings_page')
        );
        
        // زیرمنوی راهنما
        add_submenu_page(
            'product-recommendation',
            __('Guide', 'product-recommendation'),
            __('Guide', 'product-recommendation'),
            'manage_options',
            'product-recommendation-guide',
            array($this, 'render_guide_page')
        );
        
        // اگر ووکامرس فعال نیست، پیام هشدار اضافه کن
        if (!class_exists('WooCommerce')) {
            add_submenu_page(
                'product-recommendation',
                __('⚠️ WooCommerce Required', 'product-recommendation'),
                __('⚠️ WooCommerce Required', 'product-recommendation'),
                'manage_options',
                'product-recommendation-woocommerce-warning',
                array($this, 'render_woocommerce_warning_page')
            );
        }
    }

    public function add_product_meta_box() {
        // فقط اگر ووکامرس فعال باشد، meta box اضافه کن
        if (class_exists('WooCommerce')) {
            add_meta_box(
                'product-characteristics',
                __('Product Characteristics', 'product-recommendation'),
                array($this, 'render_meta_box'),
                'product',
                'normal',
                'high'
            );
        }
    }

    public function render_meta_box($post) {
        wp_nonce_field('product_characteristics_nonce', 'product_characteristics_nonce');
        // دریافت سوالات پایه از تنظیمات
        $base = $this->get_base_questions();
        $characteristics = get_post_meta($post->ID, '_product_characteristics', true);
        if (!is_array($characteristics)) {
            $characteristics = array();
        }
        echo '<div class="product-characteristics">';
        foreach ($base as $q) {
            $attr = $q['attribute'];
            $val = isset($characteristics[$attr]) ? $characteristics[$attr] : '';
            echo '<p>';
            echo '<label for="' . esc_attr($attr) . '">' . esc_html($q['title']) . '</label>';
            echo '<select name="product_characteristics[' . esc_attr($attr) . ']" id="' . esc_attr($attr) . '">';
            foreach ($q['options'] as $opt) {
                echo '<option value="' . esc_attr($opt) . '"' . selected($val, $opt, false) . '>' . esc_html($opt) . '</option>';
            }
            echo '</select>';
            echo '</p>';
        }
        echo '</div>';
    }

    public function save_product_characteristics($post_id) {
        if (!isset($_POST['product_characteristics_nonce']) || 
            !wp_verify_nonce($_POST['product_characteristics_nonce'], 'product_characteristics_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['product_characteristics'])) {
            $characteristics = array_map('sanitize_text_field', $_POST['product_characteristics']);
            update_post_meta($post_id, '_product_characteristics', $characteristics);
            // ذخیره ویژگی به عنوان term ووکامرس
            foreach ($characteristics as $attr => $val) {
                $taxonomy = 'pa_' . $attr;
                if (taxonomy_exists($taxonomy)) {
                    $terms = is_array($val) ? $val : array($val);
                    wp_set_object_terms($post_id, $terms, $taxonomy);
                }
            }
            // Update the database table
            $this->database->add_product(array_merge(
                array('product_id' => $post_id),
                $characteristics
            ));
        }
    }

    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Product Recommendation System', 'product-recommendation'); ?></h1>
            
            <?php if (!class_exists('WooCommerce')): ?>
                <div class="notice notice-error">
                    <h3><?php _e('⚠️ WooCommerce Required', 'product-recommendation'); ?></h3>
                    <p><?php _e('This plugin requires WooCommerce to be installed and activated. Please install WooCommerce first.', 'product-recommendation'); ?></p>
                    <p><a href="<?php echo admin_url('admin.php?page=product-recommendation-woocommerce-warning'); ?>" class="button button-primary"><?php _e('Learn How to Install WooCommerce', 'product-recommendation'); ?></a></p>
                </div>
            <?php else: ?>
                <div class="card">
                    <h2><?php _e('Usage Guide', 'product-recommendation'); ?></h2>
                    <p><?php _e('To add a product recommendation form to any page or post, use the following shortcode:', 'product-recommendation'); ?></p>
                    <code>[product_recommendation_form]</code>
                    <h3><?php _e('How to configure products for recommendations', 'product-recommendation'); ?></h3>
                    <p><?php _e('To enable a product in the product recommendation system:', 'product-recommendation'); ?></p>
                    <ol>
                        <li><?php _e('Create a new product in WooCommerce.', 'product-recommendation'); ?></li>
                        <li><?php _e('In the Product Characteristics section, select appropriate values.', 'product-recommendation'); ?></li>
                        <li><?php _e('Publish the product.', 'product-recommendation'); ?></li>
                    </ol>
                    <p><?php _e('Form questions and their options are editable from the plugin settings section.', 'product-recommendation'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function admin_notice() {
        // فقط اگر ووکامرس فعال باشد، notice نمایش بده
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        $screen = get_current_screen();
        if ($screen->id === 'product') {
            ?>
            <div class="notice notice-info">
                <p><?php _e('Don\'t forget to set the product characteristics for this product to enable it in the recommendation system.', 'product-recommendation'); ?></p>
            </div>
            <?php
        }
    }





    public function render_guide_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Product Recommendation System Guide', 'product-recommendation'); ?></h1>
            <div class="card">
                <h2><?php _e('How to Use the System', 'product-recommendation'); ?></h2>
                <p><?php _e('The product recommendation system allows you to suggest appropriate products based on user characteristics.', 'product-recommendation'); ?></p>
                
                <h3><?php _e('Setup Steps:', 'product-recommendation'); ?></h3>
                <ol>
                    <li><strong><?php _e('Enable Plugin:', 'product-recommendation'); ?></strong> <?php _e('Activate the plugin in WordPress.', 'product-recommendation'); ?></li>
                    <li><strong><?php _e('Configure Products:', 'product-recommendation'); ?></strong> <?php _e('For each product, set appropriate characteristics in the \'Product Characteristics\' section.', 'product-recommendation'); ?></li>
                    <li><strong><?php _e('Add Form:', 'product-recommendation'); ?></strong> <?php _e('Place the shortcode [product_recommendation_form] on the desired page or post.', 'product-recommendation'); ?></li>
                    <li><strong><?php _e('Customize:', 'product-recommendation'); ?></strong> <?php _e('Personalize the form appearance from the settings section.', 'product-recommendation'); ?></li>
                </ol>
                
                <h3><?php _e('Configure Products:', 'product-recommendation'); ?></h3>
                <p><?php _e('To enable a product in the recommendation system:', 'product-recommendation'); ?></p>
                <ol>
                    <li><?php _e('Go to the WooCommerce products section.', 'product-recommendation'); ?></li>
                    <li><?php _e('Edit the desired product.', 'product-recommendation'); ?></li>
                    <li><?php _e('In the \'Product Characteristics\' section, select appropriate values.', 'product-recommendation'); ?></li>
                    <li><?php _e('Publish the product.', 'product-recommendation'); ?></li>
                </ol>
                
                <h3><?php _e('Shortcodes:', 'product-recommendation'); ?></h3>
                <ul>
                    <li><code>[product_recommendation_form]</code> - <?php _e('Product recommendation form', 'product-recommendation'); ?></li>
                </ul>
                
                <h3><?php _e('Support:', 'product-recommendation'); ?></h3>
                <p><?php _e('For support or to report issues, contact the development team.', 'product-recommendation'); ?></p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render WooCommerce warning page
     */
    public function render_woocommerce_warning_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('⚠️ WooCommerce Required', 'product-recommendation'); ?></h1>
            
            <div class="notice notice-error">
                <h3><?php _e('WooCommerce is Required', 'product-recommendation'); ?></h3>
                <p><?php _e('The Product Recommendation System requires WooCommerce to be installed and activated to function properly.', 'product-recommendation'); ?></p>
            </div>
            
            <div class="card">
                <h2><?php _e('How to Install WooCommerce', 'product-recommendation'); ?></h2>
                <ol>
                    <li><?php _e('Go to Plugins > Add New in your WordPress admin.', 'product-recommendation'); ?></li>
                    <li><?php _e('Search for "WooCommerce".', 'product-recommendation'); ?></li>
                    <li><?php _e('Click "Install Now" and then "Activate".', 'product-recommendation'); ?></li>
                    <li><?php _e('Follow the WooCommerce setup wizard.', 'product-recommendation'); ?></li>
                    <li><?php _e('Once WooCommerce is active, this plugin will work properly.', 'product-recommendation'); ?></li>
                </ol>
                
                <h3><?php _e('Alternative Installation', 'product-recommendation'); ?></h3>
                <p><?php _e('You can also download WooCommerce from', 'product-recommendation'); ?> <a href="https://woocommerce.com/" target="_blank">woocommerce.com</a> <?php _e('and install it manually.', 'product-recommendation'); ?></p>
                
                <h3><?php _e('After Installing WooCommerce', 'product-recommendation'); ?></h3>
                <ul>
                    <li><?php _e('The warning will disappear automatically.', 'product-recommendation'); ?></li>
                    <li><?php _e('You can access all plugin features from the main menu.', 'product-recommendation'); ?></li>
                    <li><?php _e('Configure your products with characteristics.', 'product-recommendation'); ?></li>
                    <li><?php _e('Use the shortcode [product_recommendation_form] on your pages.', 'product-recommendation'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render recommendation form shortcode
     */
    public function render_recommendation_form($atts = array()) {
        // فقط اگر ووکامرس فعال باشد، فرم را نمایش بده
        if (!class_exists('WooCommerce')) {
            return '<div class="notice notice-error"><p>' . __('WooCommerce is required for this form to work.', 'product-recommendation') . '</p></div>';
        }
        
        // دریافت سوالات پایه بر اساس زبان فعلی وردپرس
        $current_locale = get_locale();
        $questions = $this->get_base_questions($current_locale);
        
        ob_start();
        ?>
        <div class="product-recommendation-form">
            <div class="product-recommendation-header">
                <h2><?php _e('Product Recommendation', 'product-recommendation'); ?></h2>
                <p><?php _e('Please answer a few short questions so we can recommend the right product for you', 'product-recommendation'); ?></p>
            </div>

            <form id="product-recommendation-form">
                <!-- Progress Bar -->
                <div class="product-recommendation-progress">
                    <div class="product-recommendation-progress-bar"></div>
                </div>

                <!-- Step Labels -->
                <div class="product-recommendation-step-labels">
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="product-recommendation-step-label <?php echo $index === 0 ? 'active' : ''; ?>" data-step="<?php echo $index; ?>">
                            <?php 
                            // استفاده از برچسب‌های کوتاه برای step labels
                            $step_label = '';
                            switch ($question['attribute']) {
                                case 'pa_product_category':
                                    $step_label = __('Category', 'product-recommendation');
                                    break;
                                case 'pa_product_price_range':
                                    $step_label = __('Price', 'product-recommendation');
                                    break;
                                case 'pa_product_for_whom':
                                    $step_label = __('Target', 'product-recommendation');
                                    break;
                                case 'pa_product_style':
                                    $step_label = __('Style', 'product-recommendation');
                                    break;
                                case 'pa_product_usage':
                                    $step_label = __('Usage', 'product-recommendation');
                                    break;
                                case 'pa_product_special_feature':
                                    $step_label = __('Feature', 'product-recommendation');
                                    break;
                                default:
                                    // اگر attribute شناخته شده نیست، از عنوان کوتاه استفاده کن
                                    $short_title = $question['title'];
                                    if (strlen($short_title) > 12) {
                                        $step_label = mb_substr($short_title, 0, 12) . '...';
                                    } else {
                                        $step_label = $short_title;
                                    }
                                    break;
                            }
                            echo esc_html($step_label);
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($questions as $index => $question): ?>
                    <!-- Step <?php echo $index + 1; ?>: <?php echo esc_html($question['title']); ?> -->
                    <div class="product-recommendation-step <?php echo $index === 0 ? 'active' : ''; ?>" data-step="<?php echo $index; ?>">
                        <h3><?php echo esc_html($question['title']); ?></h3>
                        <div class="product-recommendation-options">
                            <?php foreach ($question['options'] as $option): ?>
                                <div class="product-recommendation-option" data-value="<?php echo esc_attr($option); ?>">
                                    <div class="product-recommendation-option-content">
                                        <span class="product-recommendation-option-text"><?php echo esc_html($option); ?></span>
                                        <div class="product-recommendation-checkmark">✓</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="question_<?php echo $index; ?>" value="">
                    </div>
                <?php endforeach; ?>

                <!-- Navigation Buttons -->
                <div class="product-recommendation-navigation">
                    <button type="button" class="product-recommendation-prev" style="display: none;"><?php _e('Previous', 'product-recommendation'); ?></button>
                    <button type="button" class="product-recommendation-next"><?php _e('Next', 'product-recommendation'); ?></button>
                    <button type="submit" class="product-recommendation-submit" style="display: none;"><?php _e('Get Recommendations', 'product-recommendation'); ?></button>
                </div>
            </form>

            <!-- Loading Spinner -->
            <div class="product-recommendation-loading" style="display: none;">
                <div class="product-recommendation-spinner"></div>
                <p><?php _e('Processing...', 'product-recommendation'); ?></p>
            </div>

            <!-- Results Container -->
            <div class="product-recommendation-results" style="display: none;"></div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Handle AJAX request for product recommendations
     */
    public function get_product_recommendations() {
        // فقط اگر ووکامرس فعال باشد، درخواست را پردازش کن
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(array('message' => 'WooCommerce is required'));
            return;
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'product_recommendation_nonce')) {
            wp_die('Security check failed');
        }
        
        // Get form data
        $answers = $_POST['answers'] ?? array();
        
        if (empty($answers)) {
            wp_send_json_error(array('message' => __('No answers provided.', 'product-recommendation')));
        }
        
        // Get recommended products
        $recommended_products = $this->get_recommended_products($answers);
        
        if (empty($recommended_products)) {
            wp_send_json_error(array('message' => __('No products found matching your criteria.', 'product-recommendation')));
        }
        
        wp_send_json_success(array(
            'products' => $recommended_products,
            'message' => __('Here are your recommended products:', 'product-recommendation')
        ));
    }
    
    /**
     * Get recommended products based on answers
     */
    private function get_recommended_products($answers) {
        // فقط اگر ووکامرس فعال باشد، محصولات را جستجو کن
        if (!class_exists('WooCommerce')) {
            return array();
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'product_recommendation_products';
        
        // Build WHERE clause based on answers
        $where_conditions = array();
        $where_values = array();
        
        foreach ($answers as $attribute => $value) {
            if (!empty($value)) {
                $where_conditions[] = $attribute . ' = %s';
                $where_values[] = $value;
            }
        }
        
        if (empty($where_conditions)) {
            return array();
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        $query = $wpdb->prepare(
            "SELECT product_id FROM $table_name WHERE $where_clause",
            $where_values
        );
        
        $product_ids = $wpdb->get_col($query);
        
        if (empty($product_ids)) {
            return array();
        }
        
        // Get product details
        $products = array();
        foreach ($product_ids as $product_id) {
            $product = wc_get_product($product_id);
            if ($product && $product->is_visible()) {
                $products[] = array(
                    'id' => $product_id,
                    'name' => $product->get_name(),
                    'price' => $product->get_price_html(),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
                    'url' => get_permalink($product_id)
                );
            }
        }
        
        return $products;
    }
    
    /**
     * Get the option key for base questions based on current language
     */
    private function get_base_questions_option_key($locale = null) {
        if (!$locale) {
            $locale = get_locale();
        }
        
        // Map locale to language code
        $language_map = [
            'fa_IR' => 'fa',
            'en_US' => 'en', 
            'en_GB' => 'en',
            'ru_RU' => 'ru',
            'zh_CN' => 'zh',
            'ar' => 'ar'
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
} 