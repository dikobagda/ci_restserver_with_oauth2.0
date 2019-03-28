<?php 
class Userinfo extends MY_Controller {
    
    function __construct(){
        @session_start();
        parent::__construct();
        $this->load->library("Server", "server");
        $this->access_token = $this->input->get_request_header('Authorization');
    }
    
    function index_get() {
        if ($this->checkToken($this->access_token)) {
            $users = [
                ['id' => 1, 'name' => $this->access_token, 'email' => 'john@example.com', 'fact' => 'Loves coding'],
                ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
                ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
            ];
            $id = $this->get('id');
            // If the id parameter doesn't exist return all the users
            if ($id === NULL)
            {
                // Check if the users data store contains users (in case the database result returns NULL)
                if ($users)
                {
                    // Set the response and exit
                    $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
                else
                {
                    // Set the response and exit
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No users were found'
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
            // Find and return a single record for a particular user.
            $id = (int) $id;
            // Validate the id.
            if ($id <= 0)
            {
                // Invalid id, set the response and exit.
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }
            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.
            $user = NULL;
            if (!empty($users))
            {
                foreach ($users as $key => $value)
                {
                    if (isset($value['id']) && $value['id'] === $id)
                    {
                        $user = $value;
                    }
                }
            }
            if (!empty($user))
            {
                $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'User could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
        
    }

    
}
   