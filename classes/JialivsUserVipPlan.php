<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialivsUserVipPlan {

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

    public function isUserVip( $user_id ) {
        
        $res = self::find( $user_id );
        
        if( $res && $res->expiration_date >= date('Y-m-d') )
        {
            return $res;
        }

        return false;
    }

    public function updateUserVipPlan( $user_id, $plan_type ) {
        
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
                'start_date' => date('Y-m-d'),
                'expiration_date' => $expiration_date
            ];

            $format = [ '%d', '%s', '%s' ];

            $where = [
                'user_id' => $user_id
            ];

            $where_format = [ '%d' ];

            return $this->db->update( $this->table, $data, $where, $format, $where_format );

        } else {

            $data = [
                'user_id' => $user_id,
                'plan_type' => $plan_type,
                'start_date' => date('Y-m-d'),
                'expiration_date' => $expiration_date
            ];
    
            $format = [ '%d', '%d', '%s', '%s' ];
    
            return $this->db->insert( $this->table, $data, $format );
        }

    }

    public function getUsersVipPlans() {
        
        $stmt = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->table}" ) );
        
        return $stmt;

    }

    public function deleteUserVipPlan( $user_id ) {
        
        $stmt = $this->db->delete( $this->table, [ 'user_id' => $user_id ], [ '%d' ] );
        
        return $stmt;

    }
    
    public static function calculateRemainingTime( $expiration_date ) {
        
        $current_date = date('Y-m-d');
        $remaining_time = strtotime($expiration_date) - strtotime($current_date);
        
        if( $remaining_time < 0 ) {
            return __('Expired', 'jialivs');
        }

        $days_remaining = floor($remaining_time / (60 * 60 * 24));
        
        return $days_remaining . __('Remaining days', 'jialivs');
    }

    public function editUserVipPlan(  $user_id, $plan_type, $start_date, $expiration_date ) {

        $data = [
            'plan_type' => $plan_type,
            'start_date' => $start_date,
            'expiration_date' => $expiration_date
        ];

        $format = [ '%d', '%s', '%s' ];

        $where = [
            'user_id' => $user_id
        ];

        $where_format = [ '%d' ];

        return $this->db->update( $this->table, $data, $where, $format, $where_format );

    }
}