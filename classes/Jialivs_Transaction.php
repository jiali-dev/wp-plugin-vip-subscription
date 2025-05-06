<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Jialivs_Transaction {

    private $db;
    private $table;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $this->db->prefix.'jialivs_transactions';
    }

    public function save($data) {

        $now = current_time('mysql'); // WordPress-safe current timestamp

        $data = [
            'user_id' => $data['user_id'],
            'plan_type' => $data['plan_type'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'price' => $data['price'],
            'order_number' => $data['order_number'],
            'created_at'   => $now,
            'updated_at'   => $now
        ];

        $format = [ '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s'];

        return $this->db->insert( $this->table, $data, $format );

    }

    public function update($ref_id, $order_number) {

        $data = [
            'ref_id' => $ref_id,
        ];

        $format = [ '%s', '%s' ];

        $where = [
            'order_number' => $order_number
        ];

        $where_format = [ '%s' ];

        return $this->db->update( $this->table, $data, $where, $format, $where_format );

    }

    public function find() {
        $stmt = $this->db->get_results("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt;
    }

}