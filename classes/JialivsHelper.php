<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialivsHelper {
    public static function orderNumber( ) {
        return jdate("Ymd") . time();
    }
}