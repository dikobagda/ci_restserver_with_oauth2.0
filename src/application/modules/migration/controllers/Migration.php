<?php 

class Migration extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
    }
    
    function index() {
        $this->load->library('migration');
        if (! $this->migration->current()) 
        {
            echo "Error".$this->migration->error_string();
        } else {
            echo "Migration successfully";
        }
    }
}
?>