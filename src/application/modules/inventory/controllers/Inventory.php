<?php 
class Inventory extends MY_Controller {
    
    function __construct(){
        @session_start();
        parent::__construct();
        $this->load->library("Server", "server");
        $this->load->database();
        $this->access_token = $this->input->get_request_header('Authorization');
        $this->tbl_log_change_status = 'tbl_log_change_status';
        $this->tbl_companies = 'companies';
        $this->tbl_inventories = 'inventories';
    }
    

    /**
        * Object		    Integration : Inventory Status	
        * Module Type		API	
        * Objective		    untuk mengirim data request update status inventory dari data BFI ke SIP	
        * Table Name		tbl_log_change_status	
        * API Consumer		BFI	

        * Paramete Request :
        * CompanyID
        * AgreementNo
        * InventoryDate
        * InventoryAmount
        * UnitStatus
        * UsrUpd
        * DtmUpd
    */

    function update_status_post() {
        if ($this->checkToken($this->access_token)) {
            $data = $this->post();

            $array = array (
                'CompanyID' => $data['CompanyID'],
                'AgreementNo' => $data['AgreementNo'],
                'InventoryDate' => $data['InventoryDate'],
                'InventoryAmount' => $data['InventoryAmount'],
                'UnitStatus' => $data['UnitStatus'],
                'UsrUpd' => $data['UsrUpd'],
                'DtmUpd' => $data['DtmUpd'],
            );

            // Update inventory status
            $arr = array(
                'unit_status' => $array['UnitStatus'],
                'updated_at' => $array['DtmUpd'],
            );
            $this->db->where('agreement_no', $data['AgreementNo']);
            $update_status = $this->db->update($this->tbl_inventories, $arr);

            if ($update_status) {
                // insert to tblLogChangeStatus

                $company = $this->db->get_where($this->tbl_companies, array('company_code'=>$array['CompanyID']))->row_array();

                $log = array (
                    'company_id' => $array['CompanyID'],
                    'agreement_no' => $array['AgreementNo'],
                    'inventory_date' => $array['InventoryDate'],
                    'inventory_amount' => $array['InventoryAmount'],
                    'unit_status' => $array['UnitStatus'],
                    'usr_upd' => $array['UsrUpd'],
                    'dtm_upd' => $array['DtmUpd'],
                );
    
                $this->db->insert($this->tbl_log_change_status, $log);
                
                $this->response([
                    'CompanyID' => $company['name'],
                    'AgreementNo' => $array['AgreementNo'],
                    'ResponseCode' => 99,
                    'ResponseDescription' => 'Update Successfully'
                ], REST_Controller::HTTP_OK);

            } else {
                $this->response([
                    'CompanyID' => $array['CompanyID'],
                    'AgreementNo' => $array['AgreementNo'],
                    'ResponseCode' => 00,
                    'ResponseDescription' => 'Failed to updated'
                ], REST_Controller::HTTP_BAD_REQUEST); 
            }
        }
    }

    
}
   