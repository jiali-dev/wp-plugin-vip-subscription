<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialivsPlan {

    private $db;
    private $table;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $this->db->prefix.'jialivs_vip_plans';
    }

    public function find() {
        $stmt = $this->db->get_results("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt;
    }

    public function delete( $plan_id ) {
        $stmt = $this->db->delete( $this->table, [ 'id' => $plan_id ], [ '%d' ] );
        return $stmt;
    }

    public function findByID($plan_id) {
        $stmt = $this->db->get_row($this->db->prepare("SELECT * FROM {$this->table} WHERE id = %d", $plan_id));
        return $stmt;
    }

    public static function getPlanTitle($plan_type) {
        $vip_plan_title = '';
        switch($plan_type) {
            case 1:
                $vip_plan_title = __('Golden package', 'jialivs');
                break;
            case 2:
                $vip_plan_title = __('Silver package', 'jialivs');
                break;
            case 3:
                $vip_plan_title = __('Bronze package', 'jialivs');
                break;
        }
        return $vip_plan_title;
    }

    public static function getPlanIcon($plan_type) {
        $vip_plan_icon = 'lni-layers';
        switch($plan_type) {
            case 1:
                $vip_plan_icon = 'lni-layers';
                break;
            case 2:
                $vip_plan_icon = 'lni-diamond';
                break;
            case 3:
                $vip_plan_icon = 'lni-invention';
                break;
        }
        return $vip_plan_icon;
    }

    public function editVipPlan(  $id, $price, $recommended, $status, $benefits ) {

        $data = [
            'price' => $price,
            'recommended' => $recommended,
            'status' => $status,
            'benefits' => $benefits
        ];

        $format = [ '%s', '%d', '%d', '%s' ];

        $where = [
            'id' => $id
        ];

        $where_format = [ '%d' ];

        return $this->db->update( $this->table, $data, $where, $format, $where_format );

    }

}