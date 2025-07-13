<?php
/**
 * Product Recommendation Engine
 *
 * @package Product_Recommendation
 * @author Mohammad Nasser Haji Hashemabad
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Product_Recommendation_Engine {
    private $database;

    public function __construct() {
        $this->database = new Product_Database();
    }

    public function get_recommendations($characteristics) {
        // Get base recommendations from database
        $recommendations = $this->database->get_recommended_products($characteristics);
        
        // If no exact matches found, try to find similar products
        if (empty($recommendations)) {
            $recommendations = $this->get_similar_products($characteristics);
        }
        
        return $recommendations;
    }

    private function get_similar_products($characteristics) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'product_recommendation_products';
        
        // Build query to find similar products
        $query = $wpdb->prepare(
            "SELECT p.*, pr.* 
            FROM {$wpdb->posts} p 
            JOIN $table_name pr ON p.ID = pr.product_id 
            WHERE p.post_type = 'product' 
            AND p.post_status = 'publish'
            AND (
                pr.gender = %s
                OR pr.temperature = %s
                OR pr.age_range = %s
                OR pr.skin_tone = %s
                OR pr.personality = %s
            )
            LIMIT 6",
            $characteristics['gender'],
            $characteristics['temperature'],
            $characteristics['age_range'],
            $characteristics['skin_tone'],
            $characteristics['personality']
        );

        return $wpdb->get_results($query);
    }

    public function calculate_match_score($product, $characteristics) {
        $score = 0;
        
        // Gender match
        if ($product->gender === $characteristics['gender']) {
            $score += 2;
        }
        // Temperature match
        if ($product->temperature === $characteristics['temperature']) {
            $score += 2;
        }
        // Age range match
        if ($product->age_range === $characteristics['age_range']) {
            $score += 2;
        }
        // Smoker friendly match
        if ($product->smoker_friendly == $characteristics['smoker_friendly']) {
            $score += 1;
        }
        // Skin tone match
        if ($product->skin_tone === $characteristics['skin_tone']) {
            $score += 2;
        }
        // Personality match
        if ($product->personality === $characteristics['personality']) {
            $score += 2;
        }
        return $score;
    }
} 