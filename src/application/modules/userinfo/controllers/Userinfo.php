<?php 
class Userinfo extends MY_Controller {
    
    function __construct(){
        @session_start();
        parent::__construct();
        $this->load->library("Server", "server");
        $this->server->require_scope("userinfo");
    }
    
    function index_post() {
        echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));
    }
}
   