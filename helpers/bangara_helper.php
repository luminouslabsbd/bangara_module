<?php 

defined('BASEPATH') or exit('No direct script access allowed');

    if (!function_exists('insert')) 
    {
        function insert($table_name, $insert_data)
        {
            $CI =& get_instance();
            return $CI->db->insert($table_name, $insert_data);
        }
    }

    if (!function_exists('bangara_api_data_get')) 
    {
        function bangara_api_data_get(){
            $CI        = &get_instance();
            $CI->load->database();
            $CI->db->where('status', 1);
            $query = $CI->db->get(db_prefix().'bangara_api');
            $bangara = $query->row();
            
            if($bangara !== null){
                return $bangara ;
            }else{
                return null ;
            }
        }
    }

    

