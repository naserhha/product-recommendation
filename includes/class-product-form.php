<?php

class Product_Form {
    
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

    public function __construct() {
        add_shortcode('product_recommendation_form', array($this, 'render_form'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_product_recommendation_submit', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_product_recommendation_submit', array($this, 'handle_form_submission'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style(
            'product-recommendation-style',
            PRODUCT_RECOMMENDATION_PLUGIN_URL . 'assets/css/style.css',
            array(),
            PRODUCT_RECOMMENDATION_VERSION
        );

        wp_enqueue_script(
            'product-recommendation-script',
            PRODUCT_RECOMMENDATION_PLUGIN_URL . 'assets/js/script.js',
            array('jquery'),
            PRODUCT_RECOMMENDATION_VERSION,
            true
        );

        wp_localize_script('product-recommendation-script', 'productAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('product_recommendation_nonce')
        ));
    }

    public function render_form() {
        $primary_color = get_option('product_recommendation_primary_color', 'rgba(140,1,1,1)');
        $font_color = get_option('product_recommendation_font_color', '#fff');
        $button_color = get_option('product_recommendation_button_color', '#fff');
        $button_text_color = get_option('product_recommendation_button_text_color', $primary_color);
        echo "<style>
.stepper-form, .product-recommendation-form {
    background: {$primary_color} !important;
    color: {$font_color} !important;
    border-radius: 12px;
    box-shadow: 0 4px 24px {$primary_color};
}
.stepper-form h3, .product-recommendation-form h3,
.stepper-form label, .product-recommendation-form label,
.stepper-form .step-label, .product-recommendation-form .step-label {
    color: {$font_color} !important;
}
.stepper-form .step-label.active {
    background: #fff;
    color: {$primary_color};
}
.button, .stepper-form .button, .product-recommendation-form .button {
    background: {$button_color} !important;
    color: {$button_text_color} !important;
}
.button:hover, .stepper-form .button:hover, .product-recommendation-form .button:hover {
    background: #ffb3b3 !important;
    color: #fff !important;
}
.option-card.selected, .option-card:focus {
    border: 2px solid #fff;
    background: #fff;
    color: {$primary_color};
}
.product-recommendation-form .product-recommendation-grid .button,
.product-recommendation-form .product-recommendation-grid .product-action-btn {
    display: inline-block;
    padding: 0.7em 1.4em;
    margin: 0.3em 0.2em;
    border: none;
    border-radius: 10px;
    background: #8c0101;
    color: #fff;
    font-size: 1em;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(140,1,1,0.08);
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.1s;
    cursor: pointer;
    outline: none;
}
.product-recommendation-form .product-recommendation-grid .button:hover,
.product-recommendation-form .product-recommendation-grid .product-action-btn:hover {
    background: #fff;
    color: #8c0101;
    box-shadow: 0 4px 16px rgba(140,1,1,0.18);
    transform: translateY(-2px) scale(1.04);
    border: 1.5px solid #8c0101;
}
@media (max-width: 600px) {
    .product-recommendation-form .product-recommendation-grid .button,
    .product-recommendation-form .product-recommendation-grid .product-action-btn {
        width: 100%;
        font-size: 0.98em;
        padding: 0.8em 0;
        margin: 0.2em 0;
    }
}
</style>";
        // دریافت سوالات پایه
        $base = $this->get_base_questions();
        $questions = $base;
        if (empty($questions)) {
            return '<div class="product-recommendation-form"><p>سوالی برای فرم تعریف نشده است.</p></div>';
        }
        ob_start();
        ?>
        <div class="product-recommendation-form stepper-form">
            <div class="step-progress">
                <div class="progress-bar"><div class="progress"></div></div>
                <div class="step-labels product-recommendation-step-labels">
                    <?php foreach ($questions as $i => $q): ?>
                        <div class="product-recommendation-step-label<?php echo $i === 0 ? ' active' : ''; ?>" data-step="<?php echo $i; ?>"><?php echo esc_html($q['title']); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <form id="product-recommendation-form" method="post">
                <?php foreach ($questions as $i => $q):
                    $attribute = isset($q['attribute']) ? $q['attribute'] : '';
                    $options = array();
                    $default_options = isset($q['options']) && is_array($q['options']) && count($q['options']) > 0 ? $q['options'] : array();
                    // اگر attribute وجود دارد، termهای ووکامرس را بخوان
                    if ($attribute) {
                        $terms = get_terms(array('taxonomy' => $attribute, 'hide_empty' => false));
                        if (!empty($terms) && !is_wp_error($terms)) {
                            foreach ($terms as $term) {
                                $options[] = array('slug' => $term->slug, 'name' => $term->name);
                            }
                        } elseif (!empty($default_options)) {
                            // اگر term نبود، گزینه‌های پیش‌فرض را استفاده کن
                            foreach ($default_options as $opt) {
                                $options[] = array('slug' => sanitize_title($opt), 'name' => $opt);
                            }
                        }
                    } elseif (!empty($default_options)) {
                        foreach ($default_options as $opt) {
                            $options[] = array('slug' => sanitize_title($opt), 'name' => $opt);
                        }
                    }
                ?>
                <div class="step step-<?php echo ($i+1); ?><?php echo $i === 0 ? ' active' : ''; ?>">
                    <h3><?php echo esc_html($q['title']); ?></h3>
                    <div class="option-cards">
                        <?php if (empty($options)): ?>
                            <div style="color:#fff; background:#c00; padding:1em; border-radius:8px;"><?php _e('No options defined for this question. Please add options from WooCommerce attributes or plugin settings.', 'product-recommendation'); ?></div>
                        <?php else: ?>
                            <?php foreach ($options as $opt): ?>
                                <button type="button" class="option-card" data-value="<?php echo esc_attr($opt['slug']); ?>"><?php echo esc_html($opt['name']); ?></button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="question_<?php echo $i; ?>" id="question_<?php echo $i; ?>" required />
                    <?php if ($i > 0): ?>
                        <button type="button" class="prev-step button"><?php _e('Previous', 'product-recommendation'); ?></button>
                    <?php endif; ?>
                    <?php if ($i < count($questions)-1): ?>
                        <button type="button" class="next-step button"><?php _e('Next', 'product-recommendation'); ?></button>
                    <?php else: ?>
                        <button type="submit" class="button"><?php _e('Get Recommendations', 'product-recommendation'); ?></button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </form>
            <div id="product-recommendations" class="recommendations-container" style="display: none;"></div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.step');
            const progress = document.querySelector('.progress');
            const stepLabels = document.querySelectorAll('.step-label');
            let currentStep = 0;
            function showStep(index) {
                steps.forEach((step, i) => {
                    step.classList.toggle('active', i === index);
                    stepLabels[i].classList.toggle('active', i === index);
                });
                progress.style.width = ((index) / (steps.length - 1)) * 100 + '%';
            }
            document.querySelectorAll('.next-step').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });
            document.querySelectorAll('.prev-step').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });
            document.querySelectorAll('.option-card').forEach(card => {
                card.addEventListener('click', function() {
                    const input = this.closest('.step').querySelector('input[type="hidden"]');
                    input.value = this.getAttribute('data-value');
                    this.parentElement.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });
            showStep(currentStep);
        });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_form_submission() {
        // Check nonce
        if (!wp_verify_nonce($_POST['nonce'], 'product_recommendation_nonce')) {
            wp_die('Security check failed');
        }

        // Get form data
        $form_data = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $form_data[] = sanitize_text_field($value);
            }
        }

        // Get recommendations
        $database = new Product_Database();
        $characteristics = array(
            'gender' => $form_data[0] ?? '',
            'temperature' => $form_data[1] ?? '',
            'age_range' => $form_data[2] ?? '',
            'smoker_friendly' => $form_data[3] === 'بله' ? 1 : 0,
            'skin_tone' => $form_data[4] ?? '',
            'personality' => $form_data[5] ?? ''
        );

        $recommended_products = $database->get_recommended_products($characteristics);

        if (empty($recommended_products)) {
            // If no specific recommendations, get random products
            $recommended_products = $this->get_random_products(6);
        }

        // Render results
        $html = '<div class="product-recommendation-results">';
        $html .= '<h3>' . __('Recommended products for you:', 'product-recommendation') . '</h3>';
        $html .= '<div class="product-recommendation-grid">';
        
        foreach ($recommended_products as $product) {
            $html .= $this->render_product_card($product);
        }
        
        $html .= '</div></div>';

        wp_send_json_success(array(
            'html' => $html,
            'products' => $recommended_products
        ));
    }

    private function get_random_products($limit = 6) {
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'orderby' => 'rand'
        );
        
        $query = new WP_Query($args);
        return $query->posts;
    }

    private function render_product_card($product, $is_row = false) {
        $product_obj = wc_get_product($product->ID);
        if (!$product_obj) return '';

        $image_url = get_the_post_thumbnail_url($product->ID, 'medium');
        $price_html = $product_obj->get_price_html();
        $product_url = get_permalink($product->ID);
        $add_to_cart_url = $product_obj->add_to_cart_url();

        $card_class = $is_row ? 'product-card-row' : 'product-card';
        
        $html = '<div class="' . $card_class . '">';
        $html .= '<div class="product-image">';
        if ($image_url) {
            $html .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->post_title) . '">';
        }
        $html .= '</div>';
        $html .= '<div class="product-content">';
        $html .= '<h4>' . esc_html($product->post_title) . '</h4>';
        $html .= '<div class="product-price">' . $price_html . '</div>';
        $html .= '<div class="product-actions">';
        $html .= '<a href="' . esc_url($product_url) . '" class="button">' . __('View Details', 'product-recommendation') . '</a>';
        $html .= '<a href="' . esc_url($add_to_cart_url) . '" class="button">' . __('Add to Cart', 'product-recommendation') . '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
} 