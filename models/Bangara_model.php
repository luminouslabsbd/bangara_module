<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bangara_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function isClintCheck($email){
        
        $this->load->database();
        $this->db->where('email', $email);
        $query = $this->db->get(db_prefix().'contacts');
        $contact = $query->row();
        if($contact !== null){
            return $contact->userid ;
        }else{
            return false ;
        }
    }
    
    public function getProjectData($projectname){
        
        $this->load->database();
        $this->db->select('id');
        $this->db->where('name', $projectname);
        $query = $this->db->get(db_prefix().'projects');
        $project = $query->row();

        if($project !== null){
            $getCustomFieldValue = get_custom_field_value($project->id, 'projects_mail_platform_list_id', 'projects', $format = true);
            return $getCustomFieldValue;
        }else{
            return false ;
        } 
    
    }
    
    // public function getProjectCustom
    public function isInvoiceCheck($invoiceId){
        
        $this->load->database();
        $this->db->where('number', $invoiceId);
        $query = $this->db->get(db_prefix().'invoices');
        $invoice = $query->row();
        
        if($invoice == null){
            return null;
        }else{
            return "exists" ;
        }
        
    }
    
    public function invoice_create($invoiceData,$itemMeta )
    {
        $this->load->database();
        $this->db->insert( db_prefix() . 'invoices', $invoiceData);
        // Get the last inserted ID
        $last_insert_id = $this->db->insert_id();
        // Data Inser To Item meta
        $itemMeta['rel_id'] = $last_insert_id ;
        
        $this->db->insert( db_prefix() . 'itemable', $itemMeta);
        
        // Check for Success
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function updateInvoiceData($invoicePaymeny,$invoiceId)
    {   
        // 1 = unpaid  4 = overdue
        $desired_statuses = array(1, 4);
        $this->load->database();
        $this->db->select('id');
        $this->db->where('number',$invoiceId);
        $this->db->where_in('status',$desired_statuses);
        
        $getId = $this->db->get(db_prefix() . 'invoices')->row();
        
        if ($getId) {
            // Update Invoice Payment Staus
            $this->db->where('id', $getId->id);
            $this->db->update(db_prefix() . 'invoices', [
                'status' => 2,
            ]);
            $invoicePaymeny['invoiceid'] = $getId->id;
            // Insert Invoice Payment Records
            $this->db->insert( db_prefix() . 'invoicepaymentrecords', $invoicePaymeny);
            // Check for Success
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function createListSubscriber($data,$projectId){

        $data = [
            'EMAIL' => $data['email'],
            'FNAME' => $data['firstname'],
            'LNAME' => $data['lastname'],
            'NUMBER' => $data['phonenumber'],
            'COMPANY' => $data['company'],
            'DEBT' => $data['debt_amount'],
        ];

        $api = bangara_api_data_get();

        $this->call_carl_to_create_subscriber($data, $api->api_key , $api->url,$projectId);
    }

    public function call_carl_to_create_subscriber($data, $apiKey, $apiUrl,$projectId) {

        $mainUrl = $apiUrl.'/lists/'.$projectId.'/subscribers';
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
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'X-Api-Key: ' . $apiKey,
                'apiUrl: ' . $apiUrl
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $responseData = json_decode($response, true);

        if($responseData && $responseData['status'] == 'success'){
            return true ;
        }
        return false ;
    }

    public function authTokenCheck($token){
        
        $this->load->database();
        $this->db->where('token', $token);
        $this->db->where('permission_enable', 0);
        $query = $this->db->get(db_prefix().'user_api');
        $isToken = $query->row();
        if($isToken !== null){
            return true ;
        }else{
            return false ;
        }
    }

    public function create_customer_project($insertedArray){

        $insertedArray['date'] = date('Y-m-d');

        $this->load->database();
        $this->db->insert( db_prefix() . 'customer_project_data', $insertedArray);
        // Get the last inserted ID
        $last_insert_id = $this->db->insert_id();
        if( $last_insert_id){
            return true ;
        }
        return false;
    }

    public function getCustomFieldCheck(){

        $this->load->database();
        $this->db->select('id,name,slug');
        $this->db->where('fieldto','projects');
        $this->db->where('active',1);
        $this->db->where('name','mail_platform_list_id');
        $get = $this->db->get(db_prefix() . 'customfields')->row();
        if($get != null ){
            return $get;
        }
        return null;
    }

    public function clinetEmailGet($id){
        
        $this->load->database();
        $this->db->where('userid', $id);
        $query = $this->db->get(db_prefix().'contacts');
        $contact = $query->row();
        if($contact !== null){
            return $contact->email ;
        }else{
            return false ;
        }
    }

    public function send_call_to_voicebot($name,$company_name,$amount,$number){

        $url = "https://voicebot.keoscx.com/make-call?name=" . urlencode($name) . "&company_name=" . urlencode($company_name) . "&amount=" . $amount . "&number=" . $number ;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);

        curl_close($curl);
    }

    // public function Genarate Invoice ID
    public function newInvoiceIdCreate(){
        
        $this->load->database();
        $query = $this->db->select_max('number')->get(db_prefix().'invoices');
        $invoice_id = $query->row()->number;

        if ($invoice_id == null) {
            return null;     
        } else {
            return $invoice_id+1;
        }
        
    }

    public function getStaffEmail($id){
        
        $this->load->database();
        $this->db->where('staffid', $id);
        $query = $this->db->get(db_prefix().'staff');
        $staff = $query->row();
        if($staff != null){
            return $staff->email ;
        }else{
            return false ;
        }
    }

    public function domain_check($domain){

        $this->load->database();
        $this->db->where('domain', $domain);
        $query = $this->db->get(db_prefix().'saas_companies');
        $domain = $query->row();
        if($domain != null){
            return $domain->domain ;
        }else{
            return false ;
        }

    }

    
}
