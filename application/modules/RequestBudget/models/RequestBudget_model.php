<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
@Author : Sanket Jayani
@Desc   : Model For Request Budget Module
@Input 	: 
@Output	: 
@Date   : 12/01/2016
*/
class RequestBudget_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_compaign_master()
    {
        $this->db->select('campaign_id, campaign_name');
        $this->db->where('status','1');
        $compaign_master_list = $this->db->get('blzdsk_campaign_master')->result_array();
        
        return $compaign_master_list;
    }
    
    function get_type_compaign()
    {
        $this->db->select('camp_type_id, camp_type_name');
        $type_master_list = $this->db->get('blzdsk_campaign_type_master')->result_array();
        
        return $type_master_list;
    }
    
    function get_employee_list()
    {
        $this->db->select('contact_id, contact_name');
        $employee_list = $this->db->get('blzdsk_contact_master')->result_array();
        
        return $employee_list;
    }
    
    function get_product_list()
    {
        $this->db->select('product_id, product_name');
        $product_list = $this->db->get('blzdsk_product_master')->result_array();
        
        return $product_list;
    }
    
    function get_supplier_list()
    {
        $this->db->select('supplier_id, supplier_name');
        $supplier_list = $this->db->get('blzdsk_supplier_master')->result_array();
        
        return $supplier_list;
    }
    
    function getCampaignDataById($campiagn_id)
    {
        $this->db->select('*');
        $this->db->where('campaign_id',$campiagn_id);
        return $this->db->get('blzdsk_campaign_master')->result_array();
    }
    
    function delete_request($campaign_id)
    {
        $data = array('status' => '2');
        $this->db->where('budget_campaign_id', $campaign_id);
        $update_flg = $this->db->update('blzdsk_budget_campaign_master', $data); 
        
        if($update_flg)
        {
            return true;
        }else
        {
            return false;
        }
    }
    function getBudgetCampiagnMasterData($budget_campaign_id)
    {
        $this->db->select('*');
        $this->db->where('budget_campaign_id',$budget_campaign_id);
        return $this->db->get('blzdsk_budget_campaign_master')->result_array();
    }
    
    function total_budget_request()
    {
        $this->db->where('status !=','2');
        $this->db->from('blzdsk_budget_campaign_master');
        $total_request = $this->db->count_all_results();
        return $total_request;
    }
    function get_request_budget_list($limit, $start)
    {
        
        $this->db->select('cm.campaign_name,bc.*,ct.camp_type_name, conm.contact_name, pr.product_name');
        $this->db->from('blzdsk_budget_campaign_master as bc');
        $this->db->join('blzdsk_campaign_master as cm','bc.campaign_id = cm.campaign_id');
        $this->db->join('blzdsk_campaign_type_master as ct','ct.camp_type_id = bc.campaign_type_id');
        $this->db->join('blzdsk_contact_master as conm','conm.contact_id = bc.employee_id');
        $this->db->join('blzdsk_product_master as pr','pr.product_id = bc.product_id');
        $this->db->where('bc.status !=','2');
        $this->db->order_by("bc.budget_campaign_id", "DESC"); 
        $this->db->limit($limit, $start);
        $query  = $this->db->get();
        
        /*
        $sql = 'select cm.campaign_name,bc.*,ct.camp_type_name, conm.contact_name, pr.product_name from '
                . 'blzdsk_budget_campaign_master as bc, '
                . 'blzdsk_campaign_master as cm, '
                . 'blzdsk_campaign_type_master as ct, blzdsk_contact_master as conm, blzdsk_product_master as pr'
                . ' where pr.product_id = bc.product_id AND conm.contact_id = bc.employee_id AND ct.camp_type_id = bc.campaign_type_id AND bc.campaign_id = cm.campaign_id AND bc.status != 2 ORDER BY bc.budget_campaign_id DESC  limit ' . $start . ', ' . $limit;
        
        $query = $this->db->query($sql);*/
        return $query->result();
    }
    
    function update_campaign_budget_master()
    {

        extract($_POST);
        $budget_campaign_product_id=$budget_campaign;
        $data['campaign_id'] = $hdn_campaign_id;
       // $data['campaign_auto_id'] = $hdn_auto_gen_id;
        //$data['employee_id'] = $responsible_employee_id;
        $data['start_date'] = date("Y-m-d", strtotime($start_date));
        $data['end_date'] = date("Y-m-d", strtotime($end_date));
        $data['campaign_description'] = $this->input->post('campaign_description', FALSE);
        $data['budget_ammount'] = $budget_ammount;
        $data['revenue_goal'] = $revenue_goal;

        $campaign_type = $campaign_type_id;
        //Get Branch id From BRANCH_MASTER Table
        $table22 = CAMPAIGN_TYPE_MASTER . ' as bm';
        $match22 = "bm.camp_type_name='" . addslashes($campaign_type) . "' and status=1 ";
        $fields22 = array("bm.camp_type_name, bm.camp_type_id");
        $campaign_type_record = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        if ($campaign_type_record) {
            $data['campaign_type_id'] = $campaign_type_record[0]['camp_type_id'];
        } else {
            $camp_type_data['camp_type_name'] = $campaign_type;
        }
        if (count($campaign_type_record) == 0) {
            //INSERT Branch
            $camp_type_data['created_date'] = datetimeformat();
            $camp_type_data['modified_date'] = datetimeformat();
            $camp_type_data['status'] = 1;
            $camp_type_data_list = array(
                'camp_type_name' => $camp_type_data['camp_type_name'],
                'created_date' => $camp_type_data['created_date'],
                'modified_date' => $camp_type_data['modified_date'],
                'status' => $camp_type_data['status'],
            );
            $camp_id = $this->common_model->insert(CAMPAIGN_TYPE_MASTER, $camp_type_data_list);
            $data['campaign_type_id'] = $camp_id;
        }

        if ($budget_for_product) {
            $product_data['status'] = 1;
            $product_data['created_date'] = datetimeformat();
            $product_data['modified_date'] = datetimeformat();
            //Delete Record in Database
            if($budget_campaign_product_id){
                    $where = array('budget_campaign_id' => $budget_campaign_product_id);
                    $this->common_model->delete(BUDGET_CAMPAIGN_PRODUCT_TRAN, $where);
            }
            $selected_products = $budget_for_product;
            
            $nProducts = count($selected_products);

            for ($products_count = 0; $products_count < $nProducts; $products_count++) {
                $product_data['product_id'] = $selected_products[$products_count];
                //Insert Record in Database
                
                    $product_data['budget_campaign_id'] = $budget_campaign_product_id;
                    $this->common_model->insert(BUDGET_CAMPAIGN_PRODUCT_TRAN, $product_data);
               
            }
        }

        $where = array('budget_campaign_id' => $budget_campaign_product_id);
        $this->common_model->delete(BUDGET_CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN,$where);

        $compaigndata_user = $responsible_employee_id;
        if (count($compaigndata_user) > 0) {
            foreach ($compaigndata_user as $compaigndata_user_id) {
                $campaign_responsible_employee['budget_campaign_id']=$budget_campaign_product_id;
                $campaign_responsible_employee['employee_id']=$compaigndata_user_id;
                $campaign_responsible_employee['created_date']=datetimeformat();
                $campaign_responsible_employee['modified_date']=datetimeformat();
                $campaign_responsible_employee['status']='1';
                //Insert Client Query
                $this->common_model->insert(BUDGET_CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN,$campaign_responsible_employee);

            }
        }

        
        $data['supplier_id'] = $supplier_id;
       // $data['campaign_group_id'] = '1';
        $data['aditional_notes'] = $aditional_notes;
       //echo  "<pre>";print_r($data);exit;
        $this->db->where('budget_campaign_id',$hdn_budget_campaign_id);
        $insert_flg = $this->db->update('blzdsk_budget_campaign_master', $data);

        /* image upload code*/
        $file_name=array();
        $file_array1=$this->input->post('file_data');

        $file_name=$_FILES['fileUpload']['name'];
        if(count($file_name)>0 && count($file_array1)>0){
            $differentedImage=array_diff($file_name,$file_array1);
            foreach($file_name as $file)
            {
                if(in_array($file,$differentedImage))
                {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if(!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['fileUpload']['name'][$key]);
                    unset($_FILES['fileUpload']['type'][$key]);
                    unset($_FILES['fileUpload']['tmp_name'][$key]);
                    unset($_FILES['fileUpload']['error'][$key]);
                    unset($_FILES['fileUpload']['size'][$key]);
                }
            }
        }

        $_FILES['fileUpload']=$arr = array_map('array_values', $_FILES['fileUpload']);
        $data['lead_view'] = $this->viewname;
        $uploadData = uploadImage('fileUpload', budget_upload_path, $data['lead_view']);

        $Marketingfiles = array();
        foreach($uploadData as $dataname){
            $Marketingfiles[] =$dataname['file_name'];
        }
        $marketing_file_str = implode(",",$Marketingfiles);

        $file2 = $this->input->post('fileToUpload');
        if(!(empty($file2))){
            $file_data = implode("," ,$file2);
        }else{
            $file_data = '';
        }
        if(!empty($marketing_file_str) && !empty($file_data)){
            $marketingdata['file'] = $marketing_file_str.','.$file_data;
        }else if(!empty($marketing_file_str)){
            $marketingdata['file'] = $marketing_file_str;
        }else{
            $marketingdata['file'] = $file_data;
        }
        $marketingdata['file_name']=$file_data;
        if ($marketingdata['file_name'] != '') {
            $explodedData = explode(',', $marketingdata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }

        $estFIles = array();

        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $est_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $estFIles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'budget_campaign_id' => $hdn_budget_campaign_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }
//pr($uploadData);exit;
        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => budget_upload_path, 'budget_campaign_id' => $hdn_budget_campaign_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }
        //pr($estFIles);exit;
        if (count($estFIles) > 0) {
            $where = array('budget_campaign_id' => $hdn_budget_campaign_id);
            if (!$this->common_model->insert_batch(REQUEST_BUDGET_FILES, $estFIles)) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($this->module); //Redirect On Listing page
            }
        }

        /**
         * SOFT DELETION CODE STARTS MAULIK SUTHAR
         */
        $softDeleteImagesArr = $this->input->post('softDeletedImages');
        $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
        if (count($softDeleteImagesUrlsArr) > 0) {
            foreach ($softDeleteImagesUrlsArr as $urls) {
                unlink(BASEPATH . '../' . $urls);
            }
        }

        if (count($softDeleteImagesUrlsArr) > 0) {
            $dlStr = implode(',', $softDeleteImagesArr);
            $this->common_model->delete(REQUEST_BUDGET_FILES, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */



        if ( $insert_flg == false)
        {
            return false;
        }else
        {
            return true;
        }
    }
    function save_campaign_budget_master()
    {
        //pr($_FILES);exit;
        extract($_POST);
        $data['campaign_id'] = $master_compaign;
        $data['campaign_auto_id'] = $hdn_auto_gen_id;
        //$data['employee_id'] = $responsible_employee_id;
        $data['start_date'] = date("Y-m-d", strtotime($start_date));
        $data['end_date'] = date("Y-m-d", strtotime($end_date));;
        $data['campaign_description'] = $this->input->post('campaign_description', FALSE);
        $data['budget_ammount'] = $budget_ammount;
        $data['revenue_goal'] = $revenue_goal;
        //$data['product_id'] = $budget_for_product;
        $data['supplier_id'] = $supplier_id;
       // $data['campaign_group_id'] = '1';
        $data['aditional_notes'] = $aditional_notes;

        $campaign_type = $campaign_type_id;
        //Get Branch id From BRANCH_MASTER Table
        $table22 = CAMPAIGN_TYPE_MASTER . ' as bm';
        $match22 = "bm.camp_type_name='" . addslashes($campaign_type) . "' and status=1 ";
        $fields22 = array("bm.camp_type_name, bm.camp_type_id");
        $campaign_type_record = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        if ($campaign_type_record) {
            $data['campaign_type_id'] = $campaign_type_record[0]['camp_type_id'];
        } else {
            $camp_type_data['camp_type_name'] = $campaign_type;
        }
        if (count($campaign_type_record) == 0) {
            //INSERT Branch
            $camp_type_data['created_date'] = datetimeformat();
            $camp_type_data['modified_date'] = datetimeformat();
            $camp_type_data['status'] = 1;

            $camp_type_data_list = array(
                'camp_type_name' => $camp_type_data['camp_type_name'],
                'created_date' => $camp_type_data['created_date'],
                'modified_date' => $camp_type_data['modified_date'],
                'status' => $camp_type_data['status'],
            );
            $camp_id = $this->common_model->insert(CAMPAIGN_TYPE_MASTER, $camp_type_data_list);
            $data['campaign_type_id'] = $camp_id;
        }
            $data_list = Array(
                    'campaign_id' => $data['campaign_id'],
                    'campaign_auto_id' => $data['campaign_auto_id'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'campaign_description' => $data['campaign_description'],
                    'budget_ammount' => $data['budget_ammount'],
                    'revenue_goal' => $data['revenue_goal'],
                    'supplier_id' => $data['supplier_id'],
                    'aditional_notes' => $data['aditional_notes'],
                    'campaign_type_id' => $data['campaign_type_id'],
                );

        $insert_flg = $this->db->insert('budget_campaign_master', $data_list);
        $budget_campaign_id = $this->db->insert_id();           // return last inserted id


        $compaigndata_user = $responsible_employee_id;
        if (count($compaigndata_user) > 0) {
            foreach ($compaigndata_user as $compaigndata_user_id) {
                $campaign_responsible_employee['budget_campaign_id']=$budget_campaign_id;
                $campaign_responsible_employee['employee_id']=$compaigndata_user_id;
                $campaign_responsible_employee['created_date']=datetimeformat();
                $campaign_responsible_employee['modified_date']=datetimeformat();
                $campaign_responsible_employee['status']='1';
                //Insert Client Query
                $this->common_model->insert(BUDGET_CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN,$campaign_responsible_employee);

            }
        }

        /* image upload code*/
        $file_name=array();
        $file_array1=$this->input->post('file_data');

        $file_name=$_FILES['fileUpload']['name'];
        if(count($file_name)>0 && count($file_array1)>0){
            $differentedImage=array_diff($file_name,$file_array1);
            foreach($file_name as $file)
            {
                if(in_array($file,$differentedImage))
                {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if(!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['fileUpload']['name'][$key]);
                    unset($_FILES['fileUpload']['type'][$key]);
                    unset($_FILES['fileUpload']['tmp_name'][$key]);
                    unset($_FILES['fileUpload']['error'][$key]);
                    unset($_FILES['fileUpload']['size'][$key]);
                }
            }
        }

        $_FILES['fileUpload']=$arr = array_map('array_values', $_FILES['fileUpload']);
        $data['lead_view'] = $this->viewname;
        $uploadData = uploadImage('fileUpload', budget_upload_path, $data['lead_view']);

        $Marketingfiles = array();
        foreach($uploadData as $dataname){
            $Marketingfiles[] =$dataname['file_name'];
        }
        $marketing_file_str = implode(",",$Marketingfiles);

        $file2 = $this->input->post('fileToUpload');
        if(!(empty($file2))){
            $file_data = implode("," ,$file2);
        }else{
            $file_data = '';
        }
        if(!empty($marketing_file_str) && !empty($file_data)){
            $marketingdata['file'] = $marketing_file_str.','.$file_data;
        }else if(!empty($marketing_file_str)){
            $marketingdata['file'] = $marketing_file_str;
        }else{
            $marketingdata['file'] = $file_data;
        }
        $marketingdata['file_name']=$file_data;
        if ($marketingdata['file_name'] != '') {
            $explodedData = explode(',', $marketingdata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }

        $estFIles = array();

        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $est_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $estFIles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'budget_campaign_id' => $budget_campaign_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }


//pr($uploadData);exit;
        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => budget_upload_path, 'budget_campaign_id' => $budget_campaign_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }
        //pr($estFIles);exit;
        if (count($estFIles) > 0) {
            $where = array('budget_campaign_id' => $budget_campaign_id);
            if (!$this->common_model->insert_batch(REQUEST_BUDGET_FILES, $estFIles)) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($this->module); //Redirect On Listing page
            }
        }

        /**
         * SOFT DELETION CODE STARTS MAULIK SUTHAR
         */
        $softDeleteImagesArr = $this->input->post('softDeletedImages');
        $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
        if (count($softDeleteImagesUrlsArr) > 0) {
            foreach ($softDeleteImagesUrlsArr as $urls) {
                unlink(BASEPATH . '../' . $urls);
            }
        }

        if (count($softDeleteImagesUrlsArr) > 0) {
            $dlStr = implode(',', $softDeleteImagesArr);
            $this->common_model->delete(REQUEST_BUDGET_FILES, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */


        if ( $insert_flg == false)
        {
            return false;
        }else
        {
            return $budget_campaign_id;
        }
        
    }
    
    
}