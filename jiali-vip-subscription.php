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

if( session_status() == 1 )
    session_start();

// Include Core class
require_once plugin_dir_path(__FILE__) . 'Core.php';

// Start plugin
Core::get_instance();

// Register activation hook
register_activation_hook(__FILE__, ['Core', 'vip_activation']);