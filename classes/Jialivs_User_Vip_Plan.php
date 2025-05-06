<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Jialivs_User_Vip_Plan {

    private $db;
    private $table;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $this->db->prefix.'jialivs_user_vip_plan';
    }

    public function find( $user_id ) {
        
        $stmt = $this->db->get_row( $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = %d", $user_id ) );
        
        if( $stmt )
            return $stmt;

        return false;
    }

    public function is_user_vip( $user_id ) {
        
        $res = self::find( $user_id );
        
        if( $res && $res->expiration_date >= date('Y-m-d') )
        {
            return $res;
        }

        return false;
    }

    public function update_user_vip_plan( $plan_type, $user_id ) {
        
        $is_user_exist = self::find( $user_id );

        if( $is_user_exist && $is_user_exist->expiration_date >= date('Y-m-d') ) {

            switch( $plan_type ) {
                case '1':
                    $expiration_date = date( 'Y-m-d', strtotime($is_user_exist->expiration_date . '+3 months') );
                    break;
                case '2':
                    $expiration_date = date( 'Y-m-d', strtotime($is_user_exist->expiration_date . '+2 months') );
                    break;
                case '3':
                    $expiration_date = date( 'Y-m-d', strtotime($is_user_exist->expiration_date . '+1 months') );
                    break;
            }

        } else {

            switch( $plan_type ) {
                case '1':
                    $expiration_date = date( 'Y-m-d', strtotime('+3 months') );
                    break;
                case '2':
                    $expiration_date = date( 'Y-m-d', strtotime('+2 months') );
                    break;
                case '3':
                    $expiration_date = date( 'Y-m-d', strtotime('+1 months') );
                    break;
            }

        }

        if( $is_user_exist ) {

            $data = [
                'plan_type' => $plan_type,
                'expiration_date' => $expiration_date
            ];

            $format = [ '%d', '%d', '%s' ];

            $where = [
                'user_id' => $user_id
            ];

            $where_format = [ '%d' ];

            return $this->db->update( $this->table, $data, $where, $format, $where_format );

        } else {

            $data = [
                'user_id' => $user_id,
                'plan_type' => $plan_type,
                'expiration_date' => $expiration_date
            ];
    
            $format = [ '%d', '%d', '%d', '%s'];
    
            return $this->db->insert( $this->table, $data, $format );
        }

    }

}