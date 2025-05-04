<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Jialivs_Helper {
    public static function orderNumber( ) {
        return jdate("Ymd") . time();
    }
}