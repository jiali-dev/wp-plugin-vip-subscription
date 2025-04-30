<?php

/**
 * Plugin Name: Jiali VIP Subscription
 * Plugin URI: https://mahyarerad.com
 * Description: Enable VIP subscriptions for your WordPress site. Users can subscribe to VIP content and access exclusive features.
 * Version: 1.0.0
 * Author: Mahyar Rad
 * Author URI: https://mahyarerad.com/
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: jiali-vip-subscription
 * Domain Path: /languages
*/

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

Class Core {

    private static $instance = null;

    public function __construct() {
        $this->jialivs_define_constants();
        $this->jialivs_init();
    }

    // Prevent object cloning
    private function __clone() {}

    // Public method to access the instance
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function jialivs_define_constants() {
        define('JIALIVS_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
        define('JIALIVS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
    }

    private function jialivs_init() {
        register_activation_hook( __FILE__, [$this, 'jialivs_vip_activation'] );
        add_action( 'wp_enqueue_scripts', [$this, 'jialivs_register_assets'] );
        add_action( 'admin_enqueue_scripts', [$this, 'jialivs_admin_register_assets'] );

    }

    private function jialivs_vip_activation() {}

    public function jialivs_register_assets() {
        
        // Register styles
        wp_register_style('jialivs-styles', JIALIVS_PLUGIN_URL . '/assets/css/front/styles.css' , array(), '1.0.0', 'all');
        wp_enqueue_style('jialivs-styles');
        
        // Register scripts
        wp_register_script('jialivs-script', JIALIVS_PLUGIN_URL . '/assets/js/front/main.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('jialivs-script');
    }

    public function jialivs_admin_register_assets() {
       
        // Register styles
        wp_register_style('jialivs-uikit', 'https://cdn.jsdelivr.net/npm/uikit@3.23.6/dist/css/uikit.min.css' , array(), '1.0.0', 'all');
        wp_enqueue_style('jialivs-uikit');

        wp_register_style('jialivs-admin-styles', JIALIVS_PLUGIN_URL . '/assets/css/admin/styles.css' , array(), '1.0.0', 'all');
        wp_enqueue_style('jialivs-admin-styles');
    
        // Register scripts
        wp_register_script('jialivs-admin-script', JIALIVS_PLUGIN_URL . '/assets/js/admin/main.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('jialivs-admin-script');

        wp_register_script('jialivs-uikit', 'https://cdn.jsdelivr.net/npm/uikit@3.23.6/dist/js/uikit.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('jialivs-uikit');

        wp_register_script('jialivs-uikit-icon', 'https://cdn.jsdelivr.net/npm/uikit@3.23.6/dist/js/uikit-icons.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('jialivs-uikit-icon');
    
    }


}

Core::get_instance();
