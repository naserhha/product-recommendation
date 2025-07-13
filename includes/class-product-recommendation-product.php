<?php
class Product_Recommendation_Product {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        
        // اضافه کردن ویژگی‌های سفارشی به محصولات
        add_action('init', array($this, 'register_product_attributes'));
        
        // اضافه کردن فیلدهای جستجو به محصولات
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_search_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'save_search_fields'));
        
        // اضافه کردن فیلتر جستجو
        add_filter('posts_where', array($this, 'custom_search_query'), 10, 2);
    }

    public function register_product_attributes() {
        // ثبت ویژگی‌های سفارشی برای محصولات
        $attributes = array(
            'pa_product_category' => __('Product Category', 'product-recommendation'),
            'pa_product_price_range' => __('Price Range', 'product-recommendation'),
            'pa_product_for_whom' => __('Product Target', 'product-recommendation'),
            'pa_product_style' => __('Style and Color', 'product-recommendation'),
            'pa_product_usage' => __('Product Usage', 'product-recommendation'),
            'pa_product_special_feature' => __('Special Feature', 'product-recommendation'),
            'product_notes' => __('Product Notes', 'product-recommendation'),
            'product_occasion' => __('Occasion', 'product-recommendation'),
            'product_season' => __('Season', 'product-recommendation'),
            'product_brand' => __('Brand', 'product-recommendation'),
            'product_quality' => __('Quality', 'product-recommendation')
        );

        foreach ($attributes as $attribute_name => $attribute_label) {
            register_taxonomy(
                $attribute_name,
                'product',
                array(
                    'label' => $attribute_label,
                    'hierarchical' => true,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'query_var' => true,
                    'rewrite' => array('slug' => $attribute_name),
                    'show_admin_column' => true,
                    'labels' => array(
                        'name' => $attribute_label,
                        'singular_name' => $attribute_label,
                        'menu_name' => $attribute_label,
                        'all_items' => sprintf(__('All %s', 'product-recommendation'), $attribute_label),
                        'edit_item' => sprintf(__('Edit %s', 'product-recommendation'), $attribute_label),
                        'view_item' => sprintf(__('View %s', 'product-recommendation'), $attribute_label),
                        'update_item' => sprintf(__('Update %s', 'product-recommendation'), $attribute_label),
                        'add_new_item' => sprintf(__('Add New %s', 'product-recommendation'), $attribute_label),
                        'new_item_name' => sprintf(__('New %s Name', 'product-recommendation'), $attribute_label),
                        'parent_item' => sprintf(__('Parent %s', 'product-recommendation'), $attribute_label),
                        'parent_item_colon' => sprintf(__('Parent %s:', 'product-recommendation'), $attribute_label),
                        'search_items' => sprintf(__('Search %s', 'product-recommendation'), $attribute_label),
                        'popular_items' => sprintf(__('Popular %s', 'product-recommendation'), $attribute_label),
                        'separate_items_with_commas' => sprintf(__('Separate %s with commas', 'product-recommendation'), $attribute_label),
                        'add_or_remove_items' => sprintf(__('Add or remove %s', 'product-recommendation'), $attribute_label),
                        'choose_from_most_used' => sprintf(__('Choose from the most used %s', 'product-recommendation'), $attribute_label),
                        'not_found' => sprintf(__('No %s found', 'product-recommendation'), $attribute_label)
                    )
                )
            );
        }
    }

    public function add_search_fields() {
        global $woocommerce, $post;
        
        echo '<div class="options_group">';
        echo '<h4>' . __('Product Search Information', 'product-recommendation') . '</h4>';
        
        // فیلدهای جستجوی پیشرفته
        woocommerce_wp_textarea_input(array(
            'id' => '_product_search_keywords',
            'label' => __('Search Keywords', 'product-recommendation'),
            'description' => __('Enter keywords related to this product (comma separated)', 'product-recommendation')
        ));
        
        woocommerce_wp_textarea_input(array(
            'id' => '_product_search_description',
            'label' => __('Search Description', 'product-recommendation'),
            'description' => __('Additional description to improve search', 'product-recommendation')
        ));
        
        echo '</div>';
    }

    public function save_search_fields($post_id) {
        $fields = array(
            '_product_search_keywords',
            '_product_search_description'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_textarea_field($_POST[$field]));
            }
        }
    }

    public function custom_search_query($where, $wp_query) {
        global $wpdb;

        if (!is_admin() && $wp_query->is_main_query() && $wp_query->is_search()) {
            $search_term = $wp_query->query_vars['s'];
            
            // جستجو در متادیتای محصولات
            $where .= " OR (
                {$wpdb->posts}.ID IN (
                    SELECT post_id 
                    FROM {$wpdb->postmeta} 
                    WHERE meta_key IN ('_product_search_keywords', '_product_search_description')
                    AND meta_value LIKE '%" . esc_sql($search_term) . "%'
                )
            )";
            
            // جستجو در ویژگی‌های محصول
            $where .= " OR (
                {$wpdb->posts}.ID IN (
                    SELECT object_id 
                    FROM {$wpdb->term_relationships} tr
                    INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
                    WHERE tt.taxonomy LIKE 'product_%'
                    AND t.name LIKE '%" . esc_sql($search_term) . "%'
                )
            )";
        }

        return $where;
    }

    public function get_recommended_products($criteria) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_product_category',
                    'value' => $criteria['category'],
                    'compare' => '='
                ),
                array(
                    'key' => '_product_price_range',
                    'value' => $criteria['price_range'],
                    'compare' => '='
                ),
                array(
                    'key' => '_product_for_whom',
                    'value' => $criteria['for_whom'],
                    'compare' => '='
                ),
                array(
                    'key' => '_product_style',
                    'value' => $criteria['style'],
                    'compare' => '='
                ),
                array(
                    'key' => '_product_usage',
                    'value' => $criteria['usage'],
                    'compare' => '='
                ),
                array(
                    'key' => '_product_special_feature',
                    'value' => $criteria['special_feature'],
                    'compare' => '='
                )
            )
        );

        $products = new WP_Query($args);
        return $products->posts;
    }

    public function get_recommended_products_dynamic($criteria) {
        // اگر معیارهای خالی ارسال شده، محصولات تصادفی برگردان
        if (empty($criteria)) {
            return array();
        }
        
        $tax_query = array('relation' => 'AND');
        foreach ($criteria as $taxonomy => $term_slug) {
            if (!empty($term_slug)) {
                $tax_query[] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $term_slug,
                );
            }
        }
        
        // اگر هیچ معیار معتبری وجود ندارد، آرایه خالی برگردان
        if (count($tax_query) <= 1) {
            return array();
        }
        
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 12,
            'tax_query' => $tax_query,
            'meta_query' => array(
                array(
                    'key' => '_visibility',
                    'value' => array('catalog', 'visible'),
                    'compare' => 'IN'
                )
            )
        );

        $products = new WP_Query($args);
        return $products->posts;
    }
} 