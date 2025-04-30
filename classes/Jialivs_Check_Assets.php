<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Jialivs_Check_Assets {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'check_bootstrap_enqueue'], 100);
        add_action('wp_enqueue_scripts', [$this, 'check_bootstrap_js_enqueue'], 100);
    }
 
    public static function check_bootstrap_enqueue() {
        global $wp_styles;
    
        foreach ($wp_styles->queue as $handle) {
            if (strpos($handle, 'bootstrap') !== false) {
                // You can store this info in a variable or class property
                return true;
            }
        }
    
        return false;
    }

    public static function check_bootstrap_js_enqueue() {
        global $wp_scripts;
    
        foreach ($wp_scripts->queue as $handle) {
            if (strpos($handle, 'bootstrap') !== false) {
                return true;
            }
        }
    
        return false;
    }
}