<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

function jialivs_register_vip_menu() {
    // Main menu
    add_menu_page(
        __('VIP plan', 'jialivs'),               // Page title
        __('VIP plan', 'jialivs'),               // Menu title
        'manage_options',                // Capability
        'jialivs_vip_panel',             // Menu slug
        'jialivs_vip_dashboard_page',    // Function for content
        'dashicons-star-filled',         // Icon
        25                               // Position
    );

    // Submenu 1: VIP users
    add_submenu_page(
        'jialivs_vip_panel',
        __('VIP users', 'jialivs'),
        __('VIP users', 'jialivs'),
        'manage_options',
        'jialivs_vip_users',
        'jialivs_vip_users_page'
    );

    // Submenu 2: VIP plans
    add_submenu_page(
        'jialivs_vip_panel',
       __('VIP plans', 'jialivs'),
       __('VIP plans', 'jialivs'),
        'manage_options',
        'jialivs_vip_plans',
        'jialivs_vip_plans_page'
    );

    // Submenu 3: Transactions
    add_submenu_page(
        'jialivs_vip_panel',
        __('Transactions', 'jialivs'),
        __('Transactions', 'jialivs'),
        'manage_options',
        'jialivs_vip_transactions',
        'jialivs_vip_transactions_page'
    );

    // Submenu 3: Settings
    add_submenu_page(
        'jialivs_vip_panel',
        __('Settings', 'jialivs'),
        __('Settings', 'jialivs'),
        'manage_options',
        'jialivs_vip_settings',
        'jialivs_vip_settings_page'
    );
}
add_action('admin_menu', 'jialivs_register_vip_menu');

// Main Dashboard Page
function jialivs_vip_dashboard_page() {
    echo '<div class="wrap"><h1>'. __('VIP plans settings', 'jialivs') .'</h1><p>'.__('Welcome to VIP setting page', 'jialivs'),.'</p></div>';
}

// Submenu Page 1: VIP users
function jialivs_vip_users_page() {
    include_once( JIALIVS_PLUGIN_PATH.'/view/admin/vip-user-list.php' );
}

// Submenu Page 2: VIP plans
function jialivs_vip_plans_page() {
    include_once( JIALIVS_PLUGIN_PATH.'/view/admin/vip-plans-list.php' );
}

// Submenu Page 3:  Transactions
function jialivs_vip_transactions_page() {
    include_once( JIALIVS_PLUGIN_PATH.'/view/admin/vip-users-transactions.php' );
}

// Submenu Page 3:  Settings
function jialivs_vip_settings_page() {
    include_once( JIALIVS_PLUGIN_PATH.'/view/admin/vip-settings.php' );
}
