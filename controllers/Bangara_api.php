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

            $url = base_url('index.php/admin/bangara_module/bangara/create_invoice');
            
            $dataArray = array(
                'email' => $formData['email'],
                'firstname' => $formData['firstname'],
                'lastname' => $formData['lastname'],
                'phonenumber' => $formData['phonenumber'], 
                'debt_amount' => $formData['debt_amount'],
                'invoice_number' => $formData['invoice_number'],
                'campaign' => $formData['campaign'],
                'company' => $formData['company'],
            );

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


}