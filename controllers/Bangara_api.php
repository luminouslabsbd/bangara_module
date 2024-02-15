<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bangara_api extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('app_modules');
        $this->load->model('Bangara_model');
    }

    public function create($id = '')
    {

        if ($this->input->post()) {
            $data      = $this->input->post();

            if($data['id'] == null){
                $this->api_data_insert($data);
            }else{
                $this->api_data_update($data);
            }
        }

        if($this->input->get()){
            $bangara = $this->api_data_take();
            \modules\api\core\Apiinit::the_da_vinci_code('api');
            $data['title'] = "Bangara API Setup";
            $data['api_data'] = $bangara ;
            $this->load->view('create', $data);
        }

        $bangara = $this->api_data_take();
        $data['title'] = "Bangara API Setup";
        $data['api_data'] = $bangara ;
        $this->load->view('create', $data);
        
    }

    public function api_data_take(){
        $this->load->database();
        $query = $this->db->get(db_prefix().'bangara_api');
        $bangara = $query->row();
        if($bangara !== null){
            return $bangara ;
        }else{
            return null ;
        }
    }

    public function api_data_insert($dataArray){
        $data = [
            'url' => rtrim($dataArray['url'], '/') ,
            'api_key' => $dataArray['api_key'],
            'status'  => $dataArray['status']
        ];
        $this->load->database();
        $this->db->insert(db_prefix() . 'bangara_api', $data);
        $last_insert_id = $this->db->insert_id();
        if($last_insert_id){
            return true;
        }
        return false;
    }

    public function api_data_update($dataArray){

        $this->load->database();
        $this->db->select('id');
        $this->db->where('id', $dataArray['id']);
        $getId = $this->db->get(db_prefix() . 'bangara_api')->row();
        // Here Update the API  Data
        if ($getId) {
            $this->db->where('id', $getId->id);
            $this->db->update(db_prefix() . 'bangara_api', [ 
               'url' => rtrim($dataArray['url'], '/') ,
               'api_key' => $dataArray['api_key'],
               'status'  => $dataArray['status']
            ]);
            return true;
        }
        return false; 

    }

    public function customer_project_data_list(){
           
        $data['title'] = "Bangara API Customer Project Data List";
        $this->load->view('customer_project_list', $data);

    }

    public function c_p_table(){

        $row=array();
        $output=array();
        $this->load->database();
        $data = $this->db->get(db_prefix() . 'customer_project_data')->result_array();

        foreach ($data as $value) {

            $row = [];
            $row[] =$value['date'];
            $row[] =$value['customer_id'];
            $row[] =$value['project_id'];
            $row[] =$value['debt_number'];
            $row[] =$value['channel'];
            $row[] =$value['event'];
            
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
        die();

        $this->load->view('manage_leader' , $output['aaData'][]);

    }

    // public function api_request_form($id=''){

    //     $this->load->helper('url');
    //     $data['title'] = "API Request Form";

    //     if($this->input->post()){

    //         $formData = $this->input->post();
    //         $url = base_url('index.php/admin/bangara_module/bangara/create_customer_project_data');
    //         $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiRGFuaWVsIiwibmFtZSI6IkFQSSBKc29uIiwiQVBJX1RJTUUiOjE2OTg5NDcxMDB9.4ZZKC6CH5vqWG_8FrQ8dAEeKgi6BzlTW890TwytXSck";
            
    //         $dataArray = array(
    //             'customer_id' => $formData['customer_id'],
    //             'project_id' => $formData['project_id'],
    //             'debt_number' => $formData['debt_number'],
    //             'channel' => $formData['channel'], 
    //         );
    //         // Convert array to JSON string
    //         $jsonString = json_encode($dataArray, JSON_PRETTY_PRINT);

    //         $curl = curl_init();
    //         curl_setopt_array($curl, array(
    //             CURLOPT_URL => $url,
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => '',
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 0,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             // CURLOPT_SSL_VERIFYPEER => false,
    //             CURLOPT_CUSTOMREQUEST => "POST",
    //             CURLOPT_POSTFIELDS => $jsonString ,
    //             CURLOPT_HTTPHEADER => array(
    //                 'Authtoken: ' . $token,
    //                 'Content-Type: application/json',
    //               ),
    //         ));

    //         $response = curl_exec($curl);
    //         $data['title'] = "API Request Result";
    //         $data['response'] = $response;
            
    //         $this->session->set_userdata('api_request_result', $response);
    //         redirect('bangara_module/bangara_api/api_request_result');
    //     }

    //     $this->load->view('api_request_form', $data);
    
    // }

    public function api_request_form($id=''){

        $this->load->helper('url');
        $data['title'] = "API Request Form";

        if($this->input->post()){

            $formData = $this->input->post();

           
            if(isset($formData['from_system'])){
                $token  = 'from_system';
            }

            if(isset($formData['body_data'])) {
                // Split the string by comma to get each key-value pair
                $lines = explode(',', $formData['body_data']);
                $dataArray = array();
            
                foreach ($lines as $line) {
                    // Trim whitespace and remove surrounding double quotes
                    $line = trim($line);
                    $line = trim($line, '"');
                    
                    // Split each line by colon (:) to get key and value
                    $pair = explode(':', $line);
            
                    // Ensure the pair is in the correct format
                    if (count($pair) == 2) {
                        // Trim whitespace and remove surrounding double quotes from key and value
                        $key = trim($pair[0]);
                        $value = trim($pair[1], '"');
            
                        // Assign key-value pair to the dataArray
                        $dataArray[$key] = $value;
                    } else {
                        // Handle unexpected format
                        // For example, log an error or display a message
                        $dataArray = "Error: Unexpected format in line: $line";
                    }
                }
            }
            

            $url = base_url('admin/bangara_module/bangara/create_invoice');
            
            // $dataArray = array(
            //     'email' => $formData['email'],
            //     'firstname' => $formData['firstname'],
            //     'lastname' => $formData['lastname'],
            //     'phonenumber' => $formData['phonenumber'], 
            //     'debt_amount' => $formData['debt_amount'],
            //     // 'invoice_number' => $formData['invoice_number'],
            //     'campaign' => $formData['campaign'],
            //     'company' => $formData['company'],
            // );

            // Convert array to JSON string
            $jsonString = json_encode($dataArray, JSON_PRETTY_PRINT);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                // CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonString ,
                CURLOPT_HTTPHEADER => array(
                    'Authtoken: ' . $token,
                    'Content-Type: application/json',
                  ),
            ));

            $response = curl_exec($curl);

            $data['title'] = "API Request Result";
            $data['response'] = $response;
            
            $this->session->set_userdata('api_request_result', $response);
            redirect('bangara_module/bangara_api/api_request_result');
        }

        $this->load->view('api_request_form', $data);
        
    }

    public function api_request_result(){

        $response = $this->session->userdata('api_request_result');
        if (!$response) {
            // Handle the case where there's no response in session
            redirect('bangara_module/bangara_api/api_request_form');
        }
        $data['title'] = "API Result";
        $data['response'] = $response;

        // Clear the session data after retrieving it
        $this->session->unset_userdata('api_request_result');
        $this->load->view('api_request_result', $data);

    }

    public function formValidation($data ,  $requiredKeys){
        
        $this->load->library('form_validation');
        // Define an array of required keys
        // $missingKeys = array_diff($requiredKeys, array_keys($data));
        $missingKeys = array_diff($requiredKeys, $data);

        if (!empty($missingKeys)) {
            // Some required keys are missing
            $message = array(
                'status' => FALSE,
                'error' => 'Missing required keys: ' . implode(', ', $missingKeys),
                'message' => 'Missing required keys: ' . implode(', ', $missingKeys)
            );
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }
        return true;
    }

    public function rightFormatData($formData){

        // Iterate over both arrays simultaneously
        for ($i = 0; $i < count($formData['fields_name']); $i++) {
            $key = $formData['fields_name'][$i];
            $value = $formData['fields_value'][$i];

            // Perform validation based on the key
            switch ($key) {
                case 'email':
                    // Validate email format
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $message = "Invalid email format for key $key";
                        return $message;
                    }
                    break;
                case 'PurchaseValue':
                case 'CampaignID':
                case 'ProductID':
                case 'TenantID':
                case 'OrderID':
                    if (!is_numeric($value)) {
                        $message = "Invalid value for key $key, must be numerical";
                        return $message;
                    }
                    break;
                case 'is_login':
                    if (!in_array($value, ['true', 'false'], true)) {
                        $message = "Invalid value for key $key, must be 'true' or 'false'";
                        return $message;
                    }
                    break;
                default:
                    $final_array = array();
                    return $final_array[$key] = $value;
                    break;
            }
        }
    }

    public function create_campaign_api_setting($id=''){

        if($this->input->post()){
            $dataArray =  $this->input->post();
            $this->create_campaign_api_setting_insert($dataArray);
        }
        redirect(admin_url('bangara_module/bangara_api/create_campaign'));


    }

    public function get_api_data(){
        
        $this->load->database();
        $query = $this->db->get(db_prefix().'campaign_api_settings');
        $data = $query->row();
        if($data !== null){
            echo json_encode($data);
        } else {
            echo json_encode(false);
        }
    }

    public function create_campaign_api_setting_insert($dataArray) {
        $this->load->database();
    
        $tableName = db_prefix() . 'campaign_api_settings';
    
        // Check if there is existing data
        $query = $this->db->get($tableName);
        $data = $query->row();
    
        $ArrayDataSet = [
            'url' => rtrim($dataArray['url'], '/'),
            'api_key' => $dataArray['api_key'],
        ];
    
        // If there is no existing data, insert new record
        if ($data == null) {
            $this->db->insert($tableName, $ArrayDataSet);
            $last_insert_id = $this->db->insert_id();
        } else {
            // If there is existing data, update it
            $this->db->where('id', $data->id);
            $this->db->update($tableName, $ArrayDataSet);
        }
    
        return true;
    }

    public function create_campaign($id=''){

        $this->load->helper('url');
        $data['title'] = "Campaign Create";

        if($this->input->post()){

            $formData = $this->input->post();

            $this->load->database();
            $query = $this->db->get(db_prefix().'campaign_api_settings');
            $campaign_api_settings = $query->row();

            if($campaign_api_settings != null){
                $url = $campaign_api_settings->url ;
            }else{
                $url = null;
            }
            

            if(isset($formData['fields_name']) && $formData['fields_value'] ){

                $requiredKeys = ['TenantID', 'CampaignID', 'ProductID', 'PurchaseValue', 'email','is_login','OrderID'];
                $isValidation =   $this->formValidation($formData['fields_name'],$requiredKeys);
                $rightFormatData = $this->rightFormatData($formData);

                if($isValidation && is_array($rightFormatData)){

                    $final_array = array();
                    for ($i = 0; $i < count($formData['fields_name']); $i++) {
                        $final_array[$formData['fields_name'][$i]] = $formData['fields_value'][$i];
                    }

                    $jsonString = json_encode($final_array, JSON_PRETTY_PRINT);

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $jsonString ,
                    ));

                    $response = curl_exec($curl);

                    $data['title'] = "API Request Result";
                    $data['response'] = $response;
                    $this->session->set_userdata('api_request_result', $response);
                }elseif(!is_array($rightFormatData)){
                    $data['title'] = "API Request Result";
                    $data['response'] = $rightFormatData;
                    $this->session->set_userdata('api_request_result', $rightFormatData);
                }elseif($url == null){
                    $data['title'] = "API Request Result";
                    $data['response'] = "Please add the api end point";
                    $this->session->set_userdata('api_request_result', $data);
                }else{
                    $data['title'] = "API Request Result";
                    $data['response'] = $isValidation;
                    $this->session->set_userdata('api_request_result', $isValidation);
                }
                redirect('bangara_module/bangara_api/campaign_api_request_result');
            }
        }else{
            // $data['api_data'] = $this->getApiData();
            $this->load->view('campaign_create', $data);
        }
        
    }


}