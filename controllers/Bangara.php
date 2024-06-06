<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
// require __DIR__.'/REST_Controller.php';
/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Bangara extends ClientsController {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Bangara_model');
        $this->load->model('clients_model');
        $this->load->model('projects_model');
    }

    // Invocie Create , Exist , Customer Create and add  Subcriber
    public function create_invoice(){
        
        if($this->input->method() != "post"){
            $message = array(
                'code'  => 400,
                'status' => FALSE,
                'message' => 'Method Error'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }
        
        // Token Check
        $this->tokenPermissionCheck();

        $rawData = file_get_contents("php://input");
        // Decode the raw input if it's in JSON format
        $data = json_decode($rawData, true);
        // Send For Data Format Validation 
        $requiredKeys = ['email', 'firstname', 'lastname', 'phonenumber', 'debt_amount','campaign', 'company'];
        // $requiredKeys = ['email', 'firstname', 'lastname', 'phonenumber', 'debt_amount', 'invoice_number', 'campaign', 'company'];
        $isValidation =   $this->formValidation($data,$requiredKeys);

        if($isValidation){

            // This Invoice Number To To Check
            // $isInvoice = $this->Bangara_model->isInvoiceCheck($data['invoice_number']);
            $invoiceNumber = $this->Bangara_model->newInvoiceIdCreate();

            // Get Project list_uid From Mail Platform
            $projectId = $this->Bangara_model->getProjectData($data['campaign']);

            // if($isInvoice == 'exists'){
                
            //     $message = array(
            //         'code'  => 200,
            //         'status' => TRUE,
            //         'message' => 'This invoice already exists.'
            //     );
            //      // Send JSON response
            //     header('Content-Type: application/json');
            //     echo json_encode($message);
            //     exit();
            //     // $this->response($message, REST_Controller::HTTP_OK);
            // }
            
            $isExists = $this->Bangara_model->isClintCheck($data['email']);
            
            if($isExists == null){
            
                $finalArray = [
                  'company' => $data['company'],
                  'phonenumber' =>$data['phonenumber'],
                ];

                $id = $this->clients_model->add($finalArray);
                $this->Bangara_model->createListSubscriber($data,$projectId);
                
                if($id){
                    $assign['customer_admins']   = [];
                    $assign['customer_admins'][] = get_staff_user_id() ?? 0 ;
                    $this->clients_model->assign_admins($assign, $id);
                    $this->form_contact_data($data ,$id, $contact_id = '');
                    
                    if($data['phonenumber'] && $data['debt_amount']){
                        $invoiceData = $this->invoiceData($data,$id,$invoiceNumber);
                        $itemMeta = $this->itemMeta($data);
                        $this->Bangara_model->invoice_create($invoiceData,$itemMeta);
                    }
                    
                    $message = array(
                        'status' => TRUE,
                        'message' => 'Invoice added successful.',
                        'invoice_number' => $invoiceNumber,
                    );
                    header('Content-Type: application/json');
                    echo json_encode($message);
                    exit();
                    // $this->response($message, REST_Controller::HTTP_OK);
                }else{
                    $message = array(
                        'status' => FALSE,
                        'error' => 'clients create error',
                        'message' => 'Clients create error & something wrong'
                    );
                    header('Content-Type: application/json');
                    echo json_encode($message);
                    exit();
                    // $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
                }
                
            }else{
                
                if($data['phonenumber'] && $data['debt_amount']){
                    
                    $invoiceData = $this->invoiceData($data,$isExists,$invoiceNumber);
                    $itemMeta = $this->itemMeta($data);
                    $this->Bangara_model->invoice_create($invoiceData,$itemMeta);
                    $this->Bangara_model->createListSubscriber($data,$projectId);

                    // $this->Bangara_model->send_call_to_voicebot($data['firstname'],$data['company'],$data['debt_amount'],$data['phonenumber']);

                    $message = array(
                        'status' => TRUE,
                        'message' => 'Invoice add successful.',
                        'invoice_number' => $invoiceNumber,
                    );
                    header('Content-Type: application/json');
                    echo json_encode($message);
                    exit();
                    // $this->response($message, REST_Controller::HTTP_OK);
                }
                
            }
        }
        
    }

    // API Data Validation 
    public function formValidation($data ,  $requiredKeys){
        
        $this->load->library('form_validation');
        // Define an array of required keys
        // $requiredKeys = ['email', 'firstname', 'lastname', 'phonenumber', 'debt_amount', 'invoice_number', 'campaign', 'company'];
        $missingKeys = array_diff($requiredKeys, array_keys($data));
        if (!empty($missingKeys)) {
            $message = array(
                'status' => FALSE,
                'error' => 'Missing required keys: ' . implode(', ', $missingKeys),
                'message' => 'Missing required keys: ' . implode(', ', $missingKeys)
            );
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
            // $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
        return true;
    }
    
    // Make Invoice Insert Data Array 
    public function invoiceData($data , $clientid,$invoiceNumber){
    
        $invoiceData = array(
            'sent' => 0,
            'datesend' => null,
            'clientid' => $clientid ,
            'deleted_customer_name' => null,
            'prefix' => "INV-",
            'number_format' => 1,
            'datecreated' => date('Y-m-d H:i:s'),
            'date' => date('Y-m-d'),
            'duedate' => date('Y-m-d') ,
            'currency' => 1,
            'subtotal' => $data['debt_amount'],
            'total_tax' => 0,
            'total' => $data['debt_amount'],
            'adjustment' =>  0,
            'addedfrom' => 9,
            'hash' => substr(md5( $invoiceNumber ), 0, 30) ?? '' ,
            'status' => 1,
            'last_overdue_reminder' => null,
            'last_due_reminder' => null,
            'cancel_overdue_reminders' => 0,
            'allowed_payment_modes' => serialize(["1"]),
            'token' => null,
            'discount_percent' => 0,
            'discount_total' => 0,
            'recurring' => 0,
            'recurring_type' => null ,
            'custom_recurring' => 0,
            'cycles' => 0,
            'total_cycles' => 0,
            'sale_agent' => 1 ,
            'billing_country' => null,
            'include_shipping' => 0 ,
            'show_shipping_on_invoice' => 1,
            'show_quantity_as' => 1,
            'project_id' => 0,
            'subscription_id' => 0,
            'number' => $invoiceNumber
        );
        return $invoiceData ;
    }
    
    // Array Prepare For Item Table 
    public function itemMeta($data){
        
        $itemMeta = array(
            'rel_type' => 'invoice',
            'description' => $data['campaign'],
            'long_description' => $data['campaign'],
            'qty' => 1,
            'rate' => $data['debt_amount'],
            'unit' => intval(1),
            'item_order' => 1,
        );
        return $itemMeta ;
    }
    
    // Perfex System Compnay and User Create 
    public function form_contact_data($dataSet ,$customer_id, $contact_id = '')
    {
        if ($dataSet) {
            
            $data['userid'] = $customer_id;
            $data['contactid']   = $contact_id;
            $data['firstname'] = $dataSet['firstname'];
            $data['lastname'] = $dataSet['lastname'];
            $data['email'] = $dataSet['email'];
            $data['phonenumber'] = $dataSet['phonenumber'];
            $data['is_primary'] = 'on';
            $data['password'] = 12345678;
            $data['permissions'] = [1,2,3,4,5,6];
            $data['invoice_emails'] = 'invoice_emails';
            $data['estimate_emails'] = 'estimate_emails';
            $data['credit_note_emails'] = 'credit_note_emails';
            $data['project_emails'] = 'project_emails';
            $data['ticket_emails'] = 'ticket_emails';
            $data['task_emails'] = 'task_emails';
            $data['contract_emails'] = 'contract_emails';
            
            unset($data['contactid']);

            $id      = $this->clients_model->add_contact($data, $customer_id);
            
            $original_contact = $this->clients_model->get_contact($contact_id);
            $success          = $this->clients_model->update_contact($data, $contact_id);
            $message          = '';
            $proposal_warning = false;
            $original_email   = '';
            $updated          = false;
            
            if (is_array($success)) {
                if (isset($success['set_password_email_sent'])) {
                    $message = _l('set_password_email_sent_to_client');
                } elseif (isset($success['set_password_email_sent_and_profile_updated'])) {
                    $updated = true;
                    $message = _l('set_password_email_sent_to_client_and_profile_updated');
                }
            }
           return true; 
        }
    }
    
    // Invoice Payment System Update API 
    public function invoice_update(){
        
        if($this->input->method() != "post"){
            $message = array(
                'code'  => 400,
                'status' => FALSE,
                'message' => 'Method Error'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }
        $this->tokenPermissionCheck();
        $rawData = file_get_contents("php://input");
        // Decode the raw input if it's in JSON format
        $data = json_decode($rawData, true);
        $isValidation =   $this->formValidationInvoice($data);

        if($isValidation){
            
            $invoicePaymeny = array(
               //'invoiceid' => $data['invoice_id'],
               'amount' => $data['amount'],
               'paymentmode' => 1,
               'paymentmethod' => 'cash',
               'date' => date('Y-m-d'),
               'note' => "payment done by api",
               'daterecorded' => date('Y-m-d H:i:s'),
               'transactionid' => $data['trxid'],
            );
           $this->Bangara_model->updateInvoiceData($invoicePaymeny,$data['invoice_id']);
           $message = array(
                    'status' => TRUE,
                    'message' => 'Invoice Payment update successful'
            );
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
            // $this->response($message, REST_Controller::HTTP_OK);
        }else{
            $message = array(
                'status' => FALSE,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors() 
            );
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
            // $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }  
    }
    
    // Invoice Payment System Update Form Validation 
    public function formValidationInvoice($data){
        
        $this->load->library('form_validation');
        // Define an array of required keys
        $requiredKeys = ['invoice_id', 'email', 'trxid', 'amount' ];
        $missingKeys = array_diff($requiredKeys, array_keys($data));
        if (!empty($missingKeys)) {
            // Some required keys are missing, return an error
            $message = array(
                'status' => FALSE,
                'error' => 'Missing required keys: ' . implode(', ', $missingKeys),
                'message' => 'Missing required keys: ' . implode(', ', $missingKeys)
            );
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
            // $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
        return true;
        
    }

    public function tokenPermissionCheck(){

        $headers = $this->input->request_headers();
        $postData = $this->input->post();

        if( isset($headers['Authtoken']) && $headers['Authtoken'] == 'from_system'){
            $auth = true ;
        }elseif(isset($headers['Authtoken'])){
            $auth = $this->Bangara_model->authTokenCheck($headers['Authtoken']);
        }else{
            $message = array(
                'code'  => 200,
                'status' => FALSE,
                'message' => 'Header data missing'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }
        if(!$auth){
            $message = array(
                'code'  => 200,
                'status' => FALSE,
                'message' => 'Token are mismatch'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }
    }

    public function create_customer_project_data(){
     
        if($this->input->method() != "post"){
            $message = array(
                'code'  => 400,
                'status' => FALSE,
                'message' => 'Method Error'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }

        $rawData = file_get_contents("php://input");

        $data = json_decode($rawData, true);

        $requiredKey = ['customer_id','project_id','debt_number','channel','event'];

        $isValidation =   $this->formValidation($data,$requiredKey);

        $headers = $this->input->request_headers();

        if(isset($headers['Authtoken'])){
            $auth = $this->Bangara_model->authTokenCheck($headers['Authtoken']);
        }else{
            $message = array(
                'code'  => 200,
                'status' => FALSE,
                'message' => 'Header data missing'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }

        if(!$auth){
            $message = array(
                'code'  => 200,
                'status' => FALSE,
                'message' => 'Token are mismatch'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }

        if( $isValidation){

            $id = $this->Bangara_model->create_customer_project($data);

            if($id){
                $message = array(
                    'status' => TRUE,
                    'message' => 'Customer project Added successful'
                );
                header('Content-Type: application/json');
                echo json_encode($message);
                exit();
            }else{
                $message = array(
                    'status' => FALSE,
                    'error' => 'Customer project error',
                    'message' => 'Customer project error & something wrong'
                );
                header('Content-Type: application/json');
                echo json_encode($message);
                exit();

            }
            
           

        }

    }

    public function create_loyality_customer(){

        if($this->input->method() != "post"){
            $message = array(
                'code'  => 400,
                'status' => FALSE,
                'message' => 'Method Error'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }
        
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        $requiredKeys = ['email','phonenumber', 'company'];
        $isValidation =   $this->formValidation($data,$requiredKeys);

        if(!$isValidation){

            $message = array(
                'code'  => 202,
                'status' => FALSE,
                'message' => 'Validation Error'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
            
        }

        $isExists = $this->Bangara_model->isClintCheck($data['email']);
        
        if(!$isExists ){

            $finalArray = [
                'company' => $data['company'],
                'phonenumber' =>$data['phonenumber'],
            ];

            $email_parts = explode('@',$data['email']);

            // Access the first part of the email address
            $email_username = $email_parts[0] ?? "Null";
            $data['firstname'] = $email_username;
            $data['lastname'] = $email_username;

            $id = $this->clients_model->add($finalArray);

            if( $id){

                $assign['customer_admins']   = [];
                $assign['customer_admins'][] = get_staff_user_id() ?? 0 ;
                $this->clients_model->assign_admins($assign, $id);
                $this->form_contact_data($data ,$id, $contact_id = '');
            }

            $message = array(
                'code'  => 200,
                'status' => true,
                'crm_customer_id' => $id
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
    
        }else{

            $message = array(
                'code'  => 200,
                'status' => true,
                'crm_customer_id' => (int) $isExists
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }
    }

    // Check User Domain Is Exists or Not
    public function check_domain_is_exists(){

        if($this->input->method() != "get"){
            $message = array(
                'code'  => 400,
                'status' => FALSE,
                'message' => 'Method Error'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }
        
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        $requiredKeys = ['domain'];
        $isValidation =   $this->formValidation($data,$requiredKeys);

        if(!$isValidation){
            $message = array(
                'code'  => 202,
                'status' => FALSE,
                'message' => 'Validation Error'
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }

        $isExists = $this->Bangara_model->domain_check($data['domain']);
        
        if($isExists == false ){
            $message = array(
                'code'  => 202,
                'status' => false,
                'domain' => false,
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }else{
            $message = array(
                'code'  => 200,
                'status' => true,
                'domain' => $data['domain']
            );
             // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($message);
            exit();
        }

    }

    

    
 
}
