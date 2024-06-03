<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bangara_api extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('app_modules');
        $this->load->model('Bangara_model');
        $this->config =& load_class('Config', 'core');
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
            

            $url = base_url('index.php/admin/bangara_module/bangara/create_invoice');
            
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
        $dataKeys = array_keys($data);
        $missingKeys = array_diff($requiredKeys, $dataKeys);
 
        if (!empty($missingKeys)) {
            // Some required keys are missing
            $message = array(
                'status' => FALSE,
                'error' => 'Missing required keys: ' . implode(', ', $missingKeys),
                'message' => 'Missing required keys: ' . implode(', ', $missingKeys)
            );
            header('Content-Type: application/json');
            return json_encode($message);
            exit();
        }
        
        return true;
    }
    
    public function rightFormatData($formData){

        $dataKeys = array_keys($formData);
        $value = array_values($formData);

        foreach ($dataKeys as $index => $key) {
           
            switch ($key) {
                // case 'email':
                //     // Validate email format
                //     if ( !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                //         $message = "Invalid email format for key $key";
                //         header('Content-Type: application/json');
                //         return json_encode(array('status' => FALSE, 'error' => $message, 'message' => $message));
                //         exit();
                //     }
                //     break;
                case 'PurchaseValue':
                case 'CampaignID':
                case 'ProductID':
                case 'OrderID':
                    if (!is_numeric($value)) {
                        $message = "Invalid value for key $key, must be numerical";
                        header('Content-Type: application/json');
                        return json_encode(array('status' => FALSE, 'error' => $message, 'message' => $message));
                        exit();
                    }
                    break;
                default:
                    $final_array = array();
                    return $final_array[$key] = $value;
                    break;
                }
        }

        // Old Code 
        // Iterate over both arrays simultaneously
        // for ($i = 0; $i < count($formData['fields_name']); $i++) {
        //     $key = $formData['fields_name'][$i];
        //     $value = $formData['fields_value'][$i];
            // Perform validation based on the key
            // switch ($key) {
            //     case 'email':
            //         // Validate email format
            //         if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            //             $message = "Invalid email format for key $key";
            //             return $message;
            //         }
            //         break;
            //     case 'PurchaseValue':
            //     case 'CampaignID':
            //     case 'ProductID':
            //     case 'TenantID':
            //     case 'OrderID':
            //         if (!is_numeric($value)) {
            //             $message = "Invalid value for key $key, must be numerical";
            //             return $message;
            //         }
            //         break;
            //     case 'is_login':
            //         if (!in_array($value, ['true', 'false'], true)) {
            //             $message = "Invalid value for key $key, must be 'true' or 'false'";
            //             return $message;
            //         }
            //         break;
            //     default:
            //         $final_array = array();
            //         return $final_array[$key] = $value;
            //         break;
            // }
        // }

        return $final_array = $value;
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

    public function create_campaignOld($id=''){

        $this->load->helper('url');
        $data['title'] = "User QR Code Manage";

        if($this->input->post()){

            $formData = $this->input->post();
            
            $this->load->database();
            $query = $this->db->get(db_prefix().'campaign_api_settings');
            $campaign_api_settings = $query->row();

            if($campaign_api_settings != null){
                $url = $campaign_api_settings->url ;
            }else{
                $url = null ;
            }
             
            if(isset($formData['fields_name']) && $formData['fields_value'] ){
                
                $requiredKeys = ['TenantID', 'CampaignID', 'ProductID', 'PurchaseValue', 'email','is_login','OrderID','phone'];
                $isValidation =   $this->formValidation($formData['fields_name'],$requiredKeys);
                $rightFormatData = $this->rightFormatData($formData);

                if($isValidation && $rightFormatData){
                  
                    $final_array = array();
                    for ($i = 0; $i < count($formData['fields_name']); $i++) {
                        $final_array[$formData['fields_name'][$i]] = $formData['fields_value'][$i];
                    }

                    $jsonString = json_encode($final_array, JSON_PRETTY_PRINT);
                    // $jsonString = json_encode($final_array);

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_POSTFIELDS => $jsonString ,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json', // Specify content type as JSON
                        ),
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

    public function getLoyalityCampaignList(){

        $url = $this->config->item('loyality_url');
        $userId = get_staff_user_id();
        $email = $this->Bangara_model->getStaffEmail($userId);
        // $email = "exampleparner@loyalty.keoscx.com"; 
        // Build the JSON string
        $postFields = json_encode(array(
            "email" => $email
        ));

        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url.'v1/ll/crm/partner-campaign',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false, // Disable SSL certificate verification
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS => $postFields, // Set the dynamic JSON string here
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        $response = json_decode($response, true);
        return $response ;
        
    }

    public function getLoyalityAllPartnerList(){

        $url = $this->config->item('loyality_url');

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url.'v1/ll/crm/get-partner',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);
        return $response ;
        
    }

    public function create_campaign($id=''){

        $data['all_partner'] = $this->getLoyalityAllPartnerList();
        $data['url'] = $this->config->item('loyality_url'); 

        if(is_staff_logged_in()){
            $data['loyality_api_data'] = $this->getLoyalityCampaignList() ?? null;
        }else{
            $data['all_partner'] = $this->getLoyalityAllPartnerList() ?? null;
        }
        
        $this->load->helper('url');
        $data['title'] = "User QR Code Manage";
        

        if (isset($_SESSION['expire_time']) && time() > $_SESSION['expire_time']) {
            // Unset all session variables
           unset($_SESSION['old_form_data']);
        }

        if($this->input->post()){

            $formData = $this->input->post();

            $this->load->database();
            $query = $this->db->get(db_prefix().'campaign_api_settings');
            $campaign_api_settings = $query->row();

            if($campaign_api_settings != null){
                $url = $campaign_api_settings->url ;
            }else{
                $url = null ;
            }
           
            if( isset($formData['CampaignID']) && isset($formData['ProductID']) && isset($formData['PurchaseValue']) && isset($formData['OrderID']) && isset($formData['phone']) ){
                
                $requiredKeys = ['CampaignID','PurchaseValue','OrderID','phone'];
                $isValidation =   $this->formValidation($formData ,$requiredKeys );

                $rightFormatData = $this->rightFormatData($formData);

                if($isValidation && $rightFormatData){

                    $newData = array(
                        "OrderID" => $formData["OrderID"],
                        "TenantID" => $formData["TenantID"],
                        "CampaignID" => $formData["CampaignID"],
                        "PurchaseValue" => $formData["PurchaseValue"],
                        "email" => isset($formData["email"]) ? $formData["email"] : null,
                        "phone" => $formData["phone"],
                        "products" => array()
                    );

                    $count = count($formData["ProductID"]);
                    for ($i = 0; $i < $count; $i++) {
                        $product = array(
                            "productId" => $formData["ProductID"][$i],
                            "sku" => $formData["sku"][$i]
                        );
                        $newData["products"][] = $product;
                    }

                    $jsonString = json_encode($newData, JSON_PRETTY_PRINT);

                    $_SESSION['old_form_data'] = $jsonString;
                    $_SESSION['expire_time'] = time() + (3 * 60);

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
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json', // Specify content type as JSON
                        ),
                    ));

                    $response = curl_exec($curl);

                    $data['title'] = "API Request Result";
                    $data['response'] = $response;
                    $response = json_decode($response,true);
                    
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

    public function campaign_api_request_result(){

        $response = $this->session->userdata('api_request_result');
        if (!$response) {
            // Handle the case where there's no response in session
            redirect('bangara_module/bangara_api/create_campaign');
        }
        $data['title'] = "API Result";
        $data['response'] = $response;

        // Clear the session data after retrieving it
        $this->session->unset_userdata('api_request_result');
        $this->load->view('campaign_api_request_result', $data);

    }

    public function reset_data(){

        unset($_SESSION['old_form_data']);
        redirect('bangara_module/bangara_api/create_campaign');

    }


}