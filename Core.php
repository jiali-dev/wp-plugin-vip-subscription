<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Core {

    private static $instance = null;

    public function __construct() {
        $this->define_constants();
        $this->register_autoload();
        $this->init();
    }

    private function __clone() {}

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function define_constants() {
        define('JIALIVS_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('JIALIVS_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('JIALIVS_CLASSES_PATH', JIALIVS_PLUGIN_PATH . 'classes/');
    }

    private function register_autoload() {
        spl_autoload_register(function ($class_name) {
            // Only autoload classes starting with "Jialivs_"
            if (strpos($class_name, 'Jialivs_') === 0) {
                $file = JIALIVS_CLASSES_PATH . $class_name . '.php';
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        });
    }

    private function init() {
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        add_action('admin_enqueue_scripts', [$this, 'admin_register_assets']);
        include_once(JIALIVS_PLUGIN_PATH.'_lib/jdf.php');
        new Jialivs_Shortcodes();
        include_once( ABSPATH.'wp-includes/pluggable.php'); // For gettin wp_get_current_user and etc. 
        include_once( JIALIVS_PLUGIN_PATH.'_inc/vip-metabox.php'); 
        include_once( JIALIVS_PLUGIN_PATH.'_inc/filter-vip-content.php'); 
        include_once( JIALIVS_PLUGIN_PATH.'_inc/panel/menu.php'); 
        // Initialize Settings
        if( get_option( '_vip_settings', '__not_set__' ) === '__not_set__' ) {
            $default_settings = [
                'merchant_id' => '',
                'gateway_slug' => 'vip-gateway',
                'checkout_slug' => 'vip-plans-checkout',
                'payment_result_slug' => 'payment-result',
            ];
            update_option( '_vip_settings', $default_settings );
        }

    }

    public static function vip_activation() {
        // Activation logic here
    }

    public function register_assets() {
        if (Jialivs_Check_Assets::check_bootstrap_enqueue()) {
            wp_enqueue_style('jialivs-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css', [], '5.3.0-alpha1');
        }

        wp_enqueue_style('jialivs-styles', JIALIVS_PLUGIN_URL . '/assets/css/front/styles.css', [], '1.0.0');

        wp_enqueue_script('jialivs-script', JIALIVS_PLUGIN_URL . '/assets/js/front/main.js', ['jquery'], '1.0.0', true);

        if (Jialivs_Check_Assets::check_bootstrap_js_enqueue()) {
            wp_enqueue_script('jialivs-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js', ['jquery'], '5.3.0-alpha1', true);
        }
    }

    public function admin_register_assets() {
        wp_enqueue_style('jialivs-uikit', 'https://cdn.jsdelivr.net/npm/uikit@3.23.6/dist/css/uikit.min.css', [], '3.23.6');
        
        if( is_rtl(  ) ) {
            wp_enqueue_style('jialivs-uikit', JIALIVS_PLUGIN_URL . '/assets/plugins/uikit/uikit-rtl.min.css', [], '3.23.6');
        } 
        
        wp_enqueue_style('jialivs-admin-styles', JIALIVS_PLUGIN_URL . '/assets/css/admin/styles.css', [], '1.0.0');
        wp_enqueue_style('jialivs-date-picker', 'https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css', [], '1.0.0');

        wp_enqueue_script('jialivs-admin-script', JIALIVS_PLUGIN_URL . '/assets/js/admin/main.js', ['jquery'], '1.0.0', true);
        wp_enqueue_script('jialivs-uikit', 'https://cdn.jsdelivr.net/npm/uikit@3.23.6/dist/js/uikit.min.js', ['jquery'], '3.23.6', true);
        wp_enqueue_script('jialivs-uikit-icon', 'https://cdn.jsdelivr.net/npm/uikit@3.23.6/dist/js/uikit-icons.min.js', ['jquery'], '3.23.6', true);
        wp_enqueue_script('jialivs-date-picker', 'https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js', ['jquery'], '3.23.6', true);
    }
}