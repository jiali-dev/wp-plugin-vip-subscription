<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialivsCheckAssets {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'checkBootstrapEnqueue'], 100);
        add_action('wp_enqueue_scripts', [$this, 'checkBootstrapJsEnqueue'], 100);
    }
 
    public static function checkBootstrapEnqueue() {
        global $wp_styles;
    
        foreach ($wp_styles->queue as $handle) {
            if (strpos($handle, 'bootstrap') !== false) {
                // You can store this info in a variable or class property
                return true;
            }
        }
    
        return false;
    }

    public static function checkBootstrapJsEnqueue() {
        global $wp_scripts;
    
        foreach ($wp_scripts->queue as $handle) {
            if (strpos($handle, 'bootstrap') !== false) {
                return true;
            }
        }
    
        return false;
    }
}