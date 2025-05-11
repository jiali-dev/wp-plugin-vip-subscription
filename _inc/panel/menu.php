<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

function jialivs_register_vip_menu() {
    // Main menu
    add_menu_page(
        'پنل VIP',               // Page title
        'پنل VIP',               // Menu title
        'manage_options',                // Capability
        'jialivs_vip_panel',             // Menu slug
        'jialivs_vip_dashboard_page',    // Function for content
        'dashicons-star-filled',         // Icon
        25                               // Position
    );

    // Submenu 1: کاربران VIP
    add_submenu_page(
        'jialivs_vip_panel',
        'کاربران VIP',
        'کاربران VIP',
        'manage_options',
        'jialivs_vip_users',
        'jialivs_vip_users_page'
    );

    // Submenu 2: پلن‌های VIP
    add_submenu_page(
        'jialivs_vip_panel',
        'پلن‌های VIP',
        'پلن‌های VIP',
        'manage_options',
        'jialivs_vip_plans',
        'jialivs_vip_plans_page'
    );

    // Submenu 3: لیست تراکنش‌ها
    add_submenu_page(
        'jialivs_vip_panel',
        'لیست تراکنش‌ها',
        'لیست تراکنش‌ها',
        'manage_options',
        'jialivs_vip_transactions',
        'jialivs_vip_transactions_page'
    );

    // Submenu 3: لیست تراکنش‌ها
    add_submenu_page(
        'jialivs_vip_panel',
        'تنظیمات',
        'تنظیمات',
        'manage_options',
        'jialivs_vip_settings',
        'jialivs_vip_settings_page'
    );
}
add_action('admin_menu', 'jialivs_register_vip_menu');

// Main Dashboard Page
function jialivs_vip_dashboard_page() {
    echo '<div class="wrap"><h1>پنل تنظیمات VIP</h1><p>به پنل تنظیمات VIP خوش آمدید.</p></div>';
}

// Submenu Page 1: کاربران VIP
function jialivs_vip_users_page() {
    include_once( JIALIVS_PLUGIN_PATH.'/view/admin/vip-user-list.php' );
}

// Submenu Page 2: پلن‌های VIP
function jialivs_vip_plans_page() {
    include_once( JIALIVS_PLUGIN_PATH.'/view/admin/vip-plans-list.php' );
}

// Submenu Page 3: لیست تراکنش‌ها
function jialivs_vip_transactions_page() {
    include_once( JIALIVS_PLUGIN_PATH.'/view/admin/vip-users-transactions.php' );
}

// Submenu Page 3: تنظیمات
function jialivs_vip_settings_page() {
    echo '<div class="wrap"><h2>تنظیمات</h2><p>در این بخش تنظیمات VIP را مشاهده می‌کنید.</p></div>';
}
