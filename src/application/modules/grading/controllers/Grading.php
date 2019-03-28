<?php 
class Grading extends MY_Controller {

    function __construct(){
        @session_start();
        parent::__construct();
        $this->load->library("Server", "server");
        $this->load->database();
        $this->access_token = $this->input->get_request_header('Authorization');
        $this->bucketName = "grading_bucket";
        $this->tbl_log_grading = "tbl_log_grading";
    }
    
    function index_get($UniqueKey) {
        if ($this->checkToken($this->access_token)) {
            $cluster = $this->couchbaseOpenConn();
            $bucket = $cluster->openBucket($this->bucketName);

            //Retrieve Document
            $result = $bucket->get($UniqueKey);

            if($result->error == NULL) {
                $this->response([
                    'status' => "success",
                    'message' => "Get data grading",
                    'data' => $result->value,
                ], REST_Controller::HTTP_OK); 
            }   
        }
    }

    function index_post() {
        if ($this->checkToken($this->access_token)) {
            $cluster = $this->couchbaseOpenConn();
            $bucket = $cluster->openBucket($this->bucketName);
            
            // Store a document
            $request = json_decode($this->security->xss_clean($this->input->raw_input_stream));

            $result = $bucket->upsert($request->UniqueKey, $request);
            if($result->error == NULL) {
                $log = array (
                    'unique_key' => $request->UniqueKey,
                    'agreement_no' => $request->CustomerId,
                );

                $add = $this->db->insert($this->tbl_log_grading, $log);

                if ($add) {
                    $this->response([
                        'status' => "success",
                        'message' => "Data berhasil di simpan"
                    ], REST_Controller::HTTP_OK); 

                } else {
                    // Coachbase Rollback
                    $this->response([
                        'status' => "failed",
                        'message' => "Data gagal di simpan"
                    ], REST_Controller::HTTP_OK); 
                }
            }   
        }
    }

    
}
   