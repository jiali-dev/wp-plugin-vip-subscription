<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Jialivs_Plan {

    private $db;
    private $vipTable;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->vipTable = $this->db->prefix.'jialivs_vip_plans';
    }

    public function find() {
        $stmt = $this->db->get_results("SELECT * FROM {$this->vipTable} ORDER BY id DESC");
        return $stmt;
    }

    public function find_by_id($plan_id) {
        $stmt = $this->db->get_row($this->db->prepare("SELECT * FROM {$this->vipTable} WHERE id = %d", $plan_id));
        return $stmt;
    }

    public static function get_plan_title($plan_id) {
        $vip_plan_title = '';
        switch($plan_id) {
            case 1:
                $vip_plan_title = 'پکیج طلایی';
                break;
            case 2:
                $vip_plan_title = 'پکیج نقره ای';
                break;
            case 3:
                $vip_plan_title = 'پکیج برنزی';
                break;
        }
        return $vip_plan_title;
    }

    public static function get_plan_icon($plan_id) {
        $vip_plan_icon = 'lni-layers';
        switch($plan_id) {
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

}