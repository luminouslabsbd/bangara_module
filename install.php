<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table_name = db_prefix() . 'bangara_api';

// Check if the table exists
if (!$CI->db->table_exists($table_name)) {
    $query = 'CREATE TABLE `' . $table_name . "` (
        `id` bigint(11) NOT NULL AUTO_INCREMENT,
        `url` varchar(255) NOT NULL,
        `api_key` varchar(255) NOT NULL,
        `status` INT(11) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';';

    $CI->db->query($query);
}

$customer_project_data_table = db_prefix() . 'customer_project_data';
// Check if the table exists
if (!$CI->db->table_exists($customer_project_data_table)) {

    $events = "'EMAIL_NOT_OPENED', 'CALL_NOT_ANSWERED', 'CALL_REJECTED', 'NEGOTIATION_ACCEPTED', 'PAYMENT_NOT_COMPLETED'";

    $query = 'CREATE TABLE `' . $customer_project_data_table . "` (
        `id` bigint(11) NOT NULL AUTO_INCREMENT,
        `date` date NOT NULL,
        `customer_id` varchar(255) NOT NULL,
        `project_id` varchar(255) NOT NULL,
        `debt_number` varchar(255) NOT NULL,
        `channel` varchar(255) NOT NULL,
        `event` ENUM($events) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';';

    $CI->db->query($query);
}

