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

}