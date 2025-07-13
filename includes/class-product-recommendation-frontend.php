<?php
class Product_Recommendation_Frontend {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        
        // Add hook to refresh questions when language changes
        add_action('init', array($this, 'refresh_questions_on_language_change'));
    }
    
    /**
     * Refresh questions when language changes
     */
    public function refresh_questions_on_language_change() {
        static $last_locale = null;
        $current_locale = get_locale();
        
        if ($last_locale !== $current_locale) {
            $last_locale = $current_locale;
            // Clear any cached questions to force reload
            wp_cache_delete('product_recommendation_questions_' . $current_locale, 'product_recommendation');
        }
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
        
        // If no questions found for this language, return default questions
        if (empty($questions)) {
            return $this->get_default_base_questions($locale);
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

    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            PRODUCT_RECOMMENDATION_PLUGIN_URL . 'assets/css/style.css',
            array(),
            $this->version,
            'all'
        );
        
        // اضافه کردن متغیر CSS برای رنگ اصلی
        $primary_color = get_option('product_recommendation_primary_color', 'rgba(140,1,1,1)');
        $custom_css = "
            :root {
                --product-primary-color: {$primary_color};
            }
        ";
        wp_add_inline_style($this->plugin_name, $custom_css);
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            PRODUCT_RECOMMENDATION_PLUGIN_URL . 'assets/js/script.js',
            array('jquery'),
            $this->version,
            true
        );

        wp_localize_script($this->plugin_name, 'productAjax', array(
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

    public function render_recommendation_form() {
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
} 