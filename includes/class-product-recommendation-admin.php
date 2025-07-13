<?php
class Product_Recommendation_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            PRODUCT_RECOMMENDATION_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            $this->version,
            'all'
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            PRODUCT_RECOMMENDATION_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            $this->version,
            true
        );
    }

    public function add_product_meta_box() {
        add_meta_box(
            'product_characteristics',
            __('Product Characteristics', 'product-recommendation'),
            array($this, 'render_product_meta_box'),
            'product',
            'normal',
            'high'
        );
    }

    public function render_product_meta_box($post) {
        wp_nonce_field('product_meta_box', 'product_meta_box_nonce');

        $category = get_post_meta($post->ID, '_product_category', true);
        $price_range = get_post_meta($post->ID, '_product_price_range', true);
        $for_whom = get_post_meta($post->ID, '_product_for_whom', true);
        $style = get_post_meta($post->ID, '_product_style', true);
        $usage = get_post_meta($post->ID, '_product_usage', true);
        $special_feature = get_post_meta($post->ID, '_product_special_feature', true);
        ?>
        <div class="product-meta-box">
            <p><?php _e('Don\'t forget to set the product characteristics for this product to enable it in the recommendation system.', 'product-recommendation'); ?></p>
            
            <div class="form-group">
                <label for="product_category"><?php _e('Product Category', 'product-recommendation'); ?></label>
                <select name="product_category" id="product_category">
                    <option value=""><?php _e('Select', 'product-recommendation'); ?></option>
                    <option value="clothing" <?php selected($category, 'clothing'); ?>><?php _e('Clothing', 'product-recommendation'); ?></option>
                    <option value="digital_accessories" <?php selected($category, 'digital_accessories'); ?>><?php _e('Digital Accessories', 'product-recommendation'); ?></option>
                    <option value="home_appliances" <?php selected($category, 'home_appliances'); ?>><?php _e('Home Appliances', 'product-recommendation'); ?></option>
                    <option value="beauty_health" <?php selected($category, 'beauty_health'); ?>><?php _e('Beauty & Health', 'product-recommendation'); ?></option>
                    <option value="books" <?php selected($category, 'books'); ?>><?php _e('Books', 'product-recommendation'); ?></option>
                    <option value="other" <?php selected($category, 'other'); ?>><?php _e('Other', 'product-recommendation'); ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="product_price_range"><?php _e('Price Range', 'product-recommendation'); ?></label>
                <select name="product_price_range" id="product_price_range">
                    <option value=""><?php _e('Select', 'product-recommendation'); ?></option>
                    <option value="lt_500k" <?php selected($price_range, 'lt_500k'); ?>><?php _e('Less than 500 thousand Tomans', 'product-recommendation'); ?></option>
                    <option value="500k_2m" <?php selected($price_range, '500k_2m'); ?>><?php _e('500 thousand to 2 million Tomans', 'product-recommendation'); ?></option>
                    <option value="gt_2m" <?php selected($price_range, 'gt_2m'); ?>><?php _e('More than 2 million Tomans', 'product-recommendation'); ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="product_for_whom"><?php _e('Target Audience', 'product-recommendation'); ?></label>
                <select name="product_for_whom" id="product_for_whom">
                    <option value=""><?php _e('Select', 'product-recommendation'); ?></option>
                    <option value="myself" <?php selected($for_whom, 'myself'); ?>><?php _e('Myself', 'product-recommendation'); ?></option>
                    <option value="family" <?php selected($for_whom, 'family'); ?>><?php _e('Family members', 'product-recommendation'); ?></option>
                    <option value="friend" <?php selected($for_whom, 'friend'); ?>><?php _e('Gift for friend', 'product-recommendation'); ?></option>
                    <option value="child" <?php selected($for_whom, 'child'); ?>><?php _e('Child', 'product-recommendation'); ?></option>
                    <option value="teenager" <?php selected($for_whom, 'teenager'); ?>><?php _e('Teenager', 'product-recommendation'); ?></option>
                    <option value="adult" <?php selected($for_whom, 'adult'); ?>><?php _e('Adult', 'product-recommendation'); ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="product_style"><?php _e('Style and Color', 'product-recommendation'); ?></label>
                <select name="product_style" id="product_style">
                    <option value=""><?php _e('Select', 'product-recommendation'); ?></option>
                    <option value="light" <?php selected($style, 'light'); ?>><?php _e('Light colors', 'product-recommendation'); ?></option>
                    <option value="dark" <?php selected($style, 'dark'); ?>><?php _e('Dark colors', 'product-recommendation'); ?></option>
                    <option value="classic" <?php selected($style, 'classic'); ?>><?php _e('Classic', 'product-recommendation'); ?></option>
                    <option value="modern" <?php selected($style, 'modern'); ?>><?php _e('Modern', 'product-recommendation'); ?></option>
                    <option value="sporty" <?php selected($style, 'sporty'); ?>><?php _e('Sporty', 'product-recommendation'); ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="product_usage"><?php _e('Product Usage', 'product-recommendation'); ?></label>
                <select name="product_usage" id="product_usage">
                    <option value=""><?php _e('Select', 'product-recommendation'); ?></option>
                    <option value="daily" <?php selected($usage, 'daily'); ?>><?php _e('Daily use', 'product-recommendation'); ?></option>
                    <option value="party" <?php selected($usage, 'party'); ?>><?php _e('Party', 'product-recommendation'); ?></option>
                    <option value="workplace" <?php selected($usage, 'workplace'); ?>><?php _e('Workplace', 'product-recommendation'); ?></option>
                    <option value="travel" <?php selected($usage, 'travel'); ?>><?php _e('Travel', 'product-recommendation'); ?></option>
                    <option value="sports" <?php selected($usage, 'sports'); ?>><?php _e('Sports', 'product-recommendation'); ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="product_special_feature"><?php _e('Special Feature', 'product-recommendation'); ?></label>
                <select name="product_special_feature" id="product_special_feature">
                    <option value=""><?php _e('Select', 'product-recommendation'); ?></option>
                    <option value="high_quality" <?php selected($special_feature, 'high_quality'); ?>><?php _e('High build quality', 'product-recommendation'); ?></option>
                    <option value="brand" <?php selected($special_feature, 'brand'); ?>><?php _e('Reputable brand', 'product-recommendation'); ?></option>
                    <option value="low_energy" <?php selected($special_feature, 'low_energy'); ?>><?php _e('Low energy consumption', 'product-recommendation'); ?></option>
                    <option value="portable" <?php selected($special_feature, 'portable'); ?>><?php _e('Portability', 'product-recommendation'); ?></option>
                    <option value="multi_function" <?php selected($special_feature, 'multi_function'); ?>><?php _e('Multi-functionality', 'product-recommendation'); ?></option>
                </select>
            </div>
        </div>
        <?php
    }

    public function save_product_meta($post_id) {
        if (!isset($_POST['product_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['product_meta_box_nonce'], 'product_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'product_category',
            'product_price_range',
            'product_for_whom',
            'product_style',
            'product_usage',
            'product_special_feature'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
} 