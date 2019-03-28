<?php 
class Assetrepo extends MY_Controller {
    
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
    

    /*
        * Object		    Integration : Prerequisites (Get data asset repossess to BFI)
        * Module Type		API
        * Objective		    untuk mengirim data dari Perusahaan penitip asset/BFI ke SIP
        * Table Name		tbl_trx_pre_asset_receive
        * API Consumer		BFI

        CompanyID v
        BranchName v
        AgreementNo v 
        CustomerName v
        AssetDescription v
        AssetCategory v
        ManufacturingYear v
        LicensePlate v
        Colour v
        SerialNo1 v
        SerialNo2 v
        RepossessionDate v
        DaysOverdue v
        LastInstallmentNo v
        Tenor v
        CollectorName v
        FirstUnitStatus x
        StatusDate x 
        Osprincipal x
        AmounttobePaid x 
        Notes x
        InventoryDate v
        InventoryAmount x
        Sentdate x
        UsrUpd v -> updated_at
        DtmUpd v -> created_at
    */

    function index_post() {
        if ($this->checkToken($this->access_token)) {
            $data = $this->post();

            $array = array (
                'company_id' => $data['CompanyID'],
                'branch_name' => $data['BranchName'],
                'agreement_no' => $data['AgreementNo'],
                'customer_name' => $data['CustomerName'],
                'asset_description' => $data['AssetDescription'],
                'asset_type' => $data['AssetCategory'],
                'manufacturing_year' => $data['ManufacturingYear'],
                'license_plate' => $data['LicensePlate'],
                'colour' => $data['Colour'],
                'serial_no_1' => $data['SerialNo1'],
                'serial_no_2' => $data['SerialNo2'],
                'repossession_date' => $data['RepossessionDate'],
                'days_overdue' => $data['DaysOverdue'],
                'last_installment_no' => $data['LastInstallmentNo'],
                'tenor' => $data['Tenor'],
                'collector_name' => $data['CollectorName'],
                'inventory_date' => $data['InventoryDate'],
                'updated_at' => $data['UsrUpd'],
                'created_at' => $data['DtmUpd'],
            );

            $this->db->insert($this->tbl_inventories, $array);
            $this->response([
                'CompanyID' => $array['company_id'],
                'AgreementNo' => $array['agreement_no'],
                'ResponseCode' => 99,
                'ResponseDescription' => 'Asset reposses berhasil di tambahkan'
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

    

   