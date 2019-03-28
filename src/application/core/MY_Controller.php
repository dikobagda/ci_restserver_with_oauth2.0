<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class MY_Controller extends REST_Controller {
    private $couchbase_username = 'steam';
    private $couchbase_password = 'steam1234';
    private $couchbase_host = 'http://127.0.0.1:8091';

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
    
    function checkToken($access_token){
        $oauthdb = $this->load->database('oauth', TRUE);
        $arr = explode(" ", $access_token);
        if ($arr[0] == 'Bearer') {
            
            $token = $oauthdb->get_where('oauth_access_tokens', array('access_token'=>$arr[1]));
            if ($token->num_rows() > 0){
                
                $isExpired = $oauthdb->get_where('oauth_access_tokens', array('access_token'=>$arr[1], 'expires > '=> date('Y-m-d H:i:s')));
                if ($isExpired->num_rows() > 0){
                    return true;
                } else {
                    $this->response([
                        'status' => FALSE,
                        'status_code' => 400,
                        'message' => 'Token has been expired!'
                    ], REST_Controller::HTTP_BAD_REQUEST); 
                }

            } else {
                $this->response([
                    'status' => FALSE,
                    'status_code' => 404,
                    'message' => 'Authorization failed!'
                ], REST_Controller::HTTP_NOT_FOUND); 
            }

        } else {
            $this->response([
                'status' => FALSE,
                'status_code' => 400,
                'message' => 'Bad Request, Authorization type is incorrect!'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }

    function couchbaseOpenConn(){
        // Establish username and password for bucket-access
        $authenticator = new \Couchbase\PasswordAuthenticator();
        $authenticator->username($this->couchbase_username)->password($this->couchbase_password);

        // Connect to Couchbase Server
        $cluster = new CouchbaseCluster($this->couchbase_host);

        // Authenticate, then open bucket
        $cluster->authenticate($authenticator);
        return $cluster;

    }
    
}

?>