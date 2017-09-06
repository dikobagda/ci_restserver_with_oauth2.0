<?php 
defined('BASEPATH') or exit('Permission refused');

class Api extends MY_Controller {
    
    function __construct() {
        @session_start();
        parent::__construct();
        $this->load->library("Server", "server");
        
    }
    
    public function password_credential_post() {
        /* Refresh Token 
         * Grant Type : password
         * Data. put in Body  
         * [
         *  {"key":"grant_type","value":"password","description":""},
         *  {"key":"username","value":"user","description":""},
         *  {"key":"password","value":"pass","description":""},
         *  {"key":"client_id","value":"testclient","description":""},
         *  {"key":"client_secret","value":"testpass","description":""},
         *  {"key":"scope","value":"userinfo","description":""}
         * ]
         * 
         */
        $userdata = array("user"=>$this->input->post());
        $this->server->password_credentials($userdata);
    }
    
    public function client_credentials_post() {
        /*  Access Token
         *  Grant Type : client_credentials
         *  Data, put in body
         * {
         *  client_id: 'testclient', 
         *  client_secret:'testpass', 
         *  grant_type:'client_credentials', 
         *  scope:'userinfo cloud file node'
         * }
         */
        
        $this->server->client_credentials();
    }
    
    
   
}
?>