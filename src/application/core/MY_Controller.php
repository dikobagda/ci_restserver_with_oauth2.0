<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class MY_Controller extends REST_Controller {
    
    public function _get_post_data()
    {
        if (isset($_POST))
        {
            $val_submit = array();
            foreach($_POST as $idx => $val){
                if(is_array($val)){
                    foreach($val as $vidx=>$vval)
                        $val_submit[$idx][$vidx] = (string)$val[$vidx];
                }
                else
                    $val_submit[$idx] = $this->input->post($idx);
                    
                    /*	if($val_submit[$idx]==''){
                     $error = array(
                     "$idx" => "$idx"
                     );
                     $this->_flashMsg($error);
                     redirect('members/register');
                     }
                     */
            }
            return $val_submit;
        }
        
        return false;
    }
    
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    
}

?>