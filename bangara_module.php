<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');
// require(__DIR__ . '/vendor/autoload.php');
/*
Module Name: Bangara API
Description: Sample module description.
Version: 1.0.0
Requires at least: 2.3.*
*/

    define('Bangara_MODULE_NAME', 'bangara_module');

    $CI = &get_instance();

    register_activation_hook(Bangara_MODULE_NAME, 'bangara_module_activation_hook');

    function bangara_module_activation_hook()
    {
        $CI = &get_instance();
        require_once(__DIR__ . '/install.php'); 
    }

    $CI = &get_instance();
    $CI->load->helper(Bangara_MODULE_NAME . '/bangara');


    hooks()->add_action('admin_init', 'bangara_module_admin_init_menu_item');

    function bangara_module_admin_init_menu_item()
    {
    /**
        * If the logged in user is administrator, add custom menu in main menu
        */

        if (is_admin()) {
            $CI = &get_instance();

            if ( is_admin() ) {

                $CI->app_menu->add_sidebar_menu_item('bangara', [
                    'name'     => "Bangara",
                    'icon'     => 'fa fa-crosshairs',                
                    'position' => 29,
                ]);
                $CI->app_menu->add_sidebar_children_item('bangara', [
                    'slug'     => 'bangara_api',
                    'name'     => "Bangara Api",
                    'href'     => admin_url('bangara_module/bangara_api/create'),
                    'position' => 1,
                ]);

                $CI->app_menu->add_sidebar_children_item('bangara', [
                    'slug'     => 'api_request_send',
                    'name'     => "Invoice Create API",
                    'href'     => admin_url('bangara_module/bangara_api/api_request_form'),
                    'position' => 2,
                ]);

                $CI->app_menu->add_sidebar_children_item('bangara', [
                    'slug'     => 'bangara_api_request',
                    'name'     => "Customer Project API",
                    'href'     => admin_url('bangara_module/bangara_api/customer_project_data_list'),
                    'position' => 2,
                ]);
                
                $CI->app_menu->add_sidebar_children_item('bangara', [
                    'slug'     => 'campaign_create_request',
                    'name'     => "Campaign Manager",
                    'href'     => admin_url('bangara_module/bangara_api/create_campaign'),
                    'position' => 3,
                ]);     
            }
        }
    }

    hooks()->add_action('after_add_project', 'wbhk_project_added_hook_ll');

    function wbhk_project_added_hook_ll($projectId)
    {
        $CI        = &get_instance();
        $tableData = $CI->projects_model->get($projectId);
        $dataArray = data_modify($tableData);
        $api = bangara_api_data_get();

        if($api != null){
            call_webhook_ll_curl($dataArray, $api->api_key, $api->url,$projectId);
        }

    }

    function data_modify($tableData){

        $CI        = &get_instance();
        $CI->load->model('bangara_module/bangara_model');
        $clintEmail = $CI->bangara_model->clinetEmailGet($tableData->clientid);

        $array = [
            'general[name]' => $tableData->name,
            'general[description]' => isset($tableData->description) && $tableData->description != null  ? $tableData->description : "No Description Have",
            'defaults[from_name]' =>  $tableData->client_data->company,
            'defaults[from_email]' => $clintEmail,
            'defaults[reply_to]' => $clintEmail,
            'defaults[subject]' => 'Hello',
            'company[name]' => $tableData->client_data->company && $tableData->client_data->company != null  ? $tableData->client_data->company : "Demo",
            'company[country]' => 'Defult',
            'company[zone]' => $tableData->client_data->state  && $tableData->client_data->state != null ? $tableData->client_data->state :  'Demo',
            'company[address_1]' => $tableData->client_data->address && $tableData->client_data->address != null ? $tableData->client_data->address: 'Demo',
            'company[country_id]' => $tableData->client_data->country && $tableData->client_data->country != null ? $tableData->client_data->country : 1,
            'company[country_id]' => $tableData->client_data->country && $tableData->client_data->country != null ? $tableData->client_data->country : 1,
            'company[city]' => $tableData->client_data->city &&  $tableData->client_data->city != null ?  $tableData->client_data->city : 'Demo',
            'company[zip_code]' => $tableData->client_data->zip &&  $tableData->client_data->zip != null ?  $tableData->client_data->zip: 34656,
        ];

        return $array ;
    }

    function call_webhook_ll_curl($dataArray, $apiKey, $apiUrl,$projectId) {

        $mainUrl = $apiUrl.'/lists';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $mainUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $dataArray,
            CURLOPT_HTTPHEADER => array(
                'X-Api-Key: ' . $apiKey,
                'apiUrl: ' . $apiUrl
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $responseData = json_decode($response, true);

        if($responseData && $responseData['status'] == 'success'){
            $CI        = &get_instance();
            $CI->load->model('bangara_module/bangara_model');
            $llField = $CI->bangara_model->getCustomFieldCheck();
            if($llField != null){
                $ll_custom_fields['projects'][$llField->id] = $responseData['list_uid'];
                handle_custom_fields_post($projectId, $ll_custom_fields);
            }
        }
        return true ;
    }
