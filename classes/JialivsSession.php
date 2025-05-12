<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialivsSession {

    public static function has( $key ) {
        return array_key_exists( $key, $_SESSION );
    }

    public static function set( $key, $value ) {
        $_SESSION[$key] = $value;
    }

    public static function get( $key ) {
        if( self::has($key) )
            return $_SESSION[$key];
    }

    public static function unset( $key ) {
        if( self::has($key) )
            unset($_SESSION[$key]);
    }

}
