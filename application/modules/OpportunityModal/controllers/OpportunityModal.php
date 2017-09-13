<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Opportunity extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->uri->segment(1);

        $this->load->library(array('form_validation', 'Session'));
        $this->current_module = $this->router->fetch_module();
        $this->viewname = $this->current_module;
        $this->load->library('pagination');
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 13/01/2016
     */

    function index() {
        $this->pagination();
    }

    public function pagination($sortfield = 'prospect_id', $order = 'desc', $page = '1') {

        $data['main_content'] = '/Opportunity';
        $data['js_content'] = '/LoadJsFileOpportunity';
        $config['base_url'] = site_url('Opportunity/pagination');
        $data['opportunity_view'] = $this->viewname;
        $params['join_tables'] = array(PROSPECT_CONTACTS_TRAN . ' as pc' => 'pm.prospect_id=pc.prospect_id');
        $params['join_type'] = 'inner';
        $match = "pm.status_type=1";
        $table = PROSPECT_MASTER . ' as pm';
        $group_by = 'pm.prospect_id';
        $fields = array("pm.prospect_id,count(pm.prospect_id) as opp_count,pm.prospect_name,pm.prospect_auto_id, pm.status_type,count(pc.prospect_id) as contact_count,pc.contact_name");
        $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);
        //Get Records From CONTACT_MASTER Table
        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "";
        $fields1 = array("cm.contact_id,cm.contact_name");
        $data['prospect_owner'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        //Get Records From BRANCH_MASTER Table       
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = "";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
        //Get Records From CAMPAIGN_MASTER Table       
        $table3 = CAMPAIGN_MASTER . ' as cam';
        $match3 = "";
        $fields3 = array("cam.campaign_id,cam.campaign_name");
        $data['campaign'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);
        //Get Records From PRODUCT_MASTER Table       
        $table4 = PRODUCT_MASTER . ' as prm';
        $match4 = "";
        $fields4 = array("prm.product_id,prm.product_name");
        $data['product_data'] = $this->common_model->get_records($table4, $fields4, '', '', $match4);
        //Get Records From PROSPECT_MASTER Table       
        $table6 = PROSPECT_MASTER . ' as pm';
        $match6 = "pm.status_type=1";
        $fields6 = array("count(pm.prospect_id) as total_opportunity");
        $total_opportunity = $this->common_model->get_records($table6, $fields6, '', '', $match6);
        $data['total_opportunity'] = $total_opportunity[0]['total_opportunity'];
        //Get Records From COUNTRIES Table       
        $table7 = COUNTRIES . ' as c';
        $match7 = "";
        $fields7 = array("c.country_id,c.country_name");
        $data['country_data'] = $this->common_model->get_records($table7, $fields7, '', '', $match7);
        //Get Records From COMPANY_MASTER Table       
        $table8 = COMPANY_MASTER . ' as cmp';
        $match8 = "";
        $fields8 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table8, $fields8, '', '', $match8);
        //Pass ALL TABLE Record In View

        $this->parser->parse('layouts/ProspectTemplate', $data);
        //$this->load->view('AddEditOpportunity');
        //$this->load->view('LoadJsFileOpportunity');
    }

    /*
      @Date   : 13/01/2016
     */

    public function add() {
        $data = array();
        $data['opportunity_view'] = $this->viewname;
        $data['main_content'] = '/Opportunity';
        $this->parser->parse('layouts/ProspectTemplate', $data);
    }

    public function insertdata() {

        $id = '';
        if ($this->input->post('prospect_id')) {
            $id = $this->input->post('prospect_id');
        }

        $data = array();
        $data['opportunity_view'] = $this->viewname;


        $prospect_data['prospect_name'] = $this->input->post('prospect_name');
        $prospect_data['company_id'] = $this->input->post('company_id');
        $prospect_data['prospect_owner_id'] = $this->input->post('prospect_owner_id');
        $prospect_data['address1'] = $this->input->post('address1');
        $prospect_data['address2'] = $this->input->post('address2');
        $prospect_data['creation_date'] = date_format(date_create($this->input->post('creation_date')), 'Y-m-d');
        $prospect_data['postal_code'] = $this->input->post('postal_code');
        $prospect_data['state'] = $this->input->post('state');
        $prospect_data['language_id'] = $this->input->post('language_id');
        $prospect_data['branch_id'] = $this->input->post('branch_id');
        $prospect_data['country_id'] = $this->input->post('country_id');
        $prospect_data['estimate_prospect_worth'] = $this->input->post('estimate_prospect_worth');
        $prospect_generate = '0';
        if ($this->input->post('prospect_generate') == 'on') {
            $prospect_generate = '1';
        }

        $prospect_data['prospect_generate'] = $prospect_generate;
        if ($this->input->post('campaign_id')) {
            $prospect_data['campaign_id'] = $this->input->post('campaign_id');
        } else {
            $prospect_data['campaign_id'] = '';
        }
        $prospect_data['description'] = $this->input->post('description');


        //upload file
        if ($_FILES['userfile']['name'] != NULL) {
            $config = array(
                'upload_path' => "./uploads/prospect_upload/",
                'allowed_types' => "gif|jpg|png|jpeg|pdf",
                'overwrite' => TRUE,
                'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                'max_height' => "768",
                'max_width' => "1024"
            );
            $this->load->library('upload', $config);
            if ($this->upload->do_upload()) {
                $file_data = array('upload_data' => $this->upload->data());

                foreach ($file_data as $file) {
                    $prospect_data['file'] = $file['file_name'];
                }
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
            }
        }
        if($this->input->post('contact_date'))
        {
        $prospect_data['contact_date'] = date_format(date_create($this->input->post('contact_date')), 'Y-m-d');
        }
        else{
            $prospect_data['contact_date']='';
        }
        $prospect_data['status'] = 1;
        //status_type=1 for Opportunity
        $prospect_data['status_type'] = 1;
        $prospect_data['modified_date'] = datetimeformat();


        //Insert Record in Database

        if ($id) {
            $where = array('prospect_id' => $id);
            $success_update = $this->common_model->update(PROSPECT_MASTER, $prospect_data, $where);
            if ($success_update) {
                $msg = $this->lang->line('opp_update_msg');
                $this->session->set_flashdata('msg',$msg);
            }
        } else {
            $prospect_data['prospect_auto_id'] = $this->input->post('prospect_auto_id');

            $prospect_data['created_date'] = datetimeformat();
            $success_insert = $prospect_contacts_tran['prospect_id'] = $this->common_model->insert(PROSPECT_MASTER, $prospect_data);

            if ($success_insert) {
                $msg = $this->lang->line('opp_add_msg');
                $this->session->set_flashdata('msg',$msg);
            }
        }
        $prospect_contacts_name = array();
        $prospect_contacts_name = $this->input->post('contact_name');
        $contacts_email_id = array();
        $prospect_contacts_email_id = $this->input->post('email_id');
        $prospect_contacts_phone_no = array();
        $prospect_contacts_phone_no = $this->input->post('phone_no');
        $prospect_contacts_tran['status'] = 1;
        $prospect_contacts_tran['created_date'] = datetimeformat();
        $prospect_contacts_tran['modified_date'] = datetimeformat();

        //Insert Record in Database
        if ($id) {


            $where = array('prospect_id' => $id);
            $this->common_model->delete(PROSPECT_CONTACTS_TRAN, $where);
            $prospect_contacts_tran['prospect_id'] = $id;
            for ($i = 0; $i < count($prospect_contacts_name); $i++) {
                $prospect_contacts_tran['contact_name'] = $prospect_contacts_name[$i];
                $prospect_contacts_tran['email_id'] = $prospect_contacts_email_id[$i];
                $prospect_contacts_tran['phone_no'] = $prospect_contacts_phone_no[$i];
                $this->common_model->insert(PROSPECT_CONTACTS_TRAN, $prospect_contacts_tran);
            }
        } else {
            $prospect_contacts_tran['prospect_id'] = $prospect_contacts_tran['prospect_id'];
            for ($i = 0; $i < count($prospect_contacts_name); $i++) {
                $prospect_contacts_tran['contact_name'] = $prospect_contacts_name[$i];
                $prospect_contacts_tran['email_id'] = $prospect_contacts_email_id[$i];
                $prospect_contacts_tran['phone_no'] = $prospect_contacts_phone_no[$i];
                $this->common_model->insert(PROSPECT_CONTACTS_TRAN, $prospect_contacts_tran);
            }
            //$this->common_model->insert(PROSPECT_CONTACTS_TRAN,$prospect_contacts_tran);
        }

        if ($this->input->post('interested_products')) {
            $product_data['status'] = 1;
            $product_data['created_date'] = datetimeformat();
            $product_data['modified_date'] = datetimeformat();
            //Delete Record in Database
            if ($id) {

                $where = array('prospect_id' => $id);
                $this->common_model->delete(PROSPECT_PRODUCTS_TRAN, $where);
            }
            $selected_products = $this->input->post('interested_products');
            $nProducts = count($selected_products);

            for ($i = 0; $i < $nProducts; $i++) {
                $product_data['product_id'] = $selected_products[$i];
                //Insert Record in Database
                if ($id) {
                    $product_data['prospect_id'] = $id;
                    $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $product_data);
                } else {
                    $product_data['prospect_id'] = $prospect_contacts_tran['prospect_id'];
                    //Insert Record in Database
                    $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $product_data);
                }
            }
        }

        redirect($this->viewname); //Redirect On Listing page
    }

    /*
      @Date   : 18/01/2016
     */

    public function edit() {

        $id = $this->input->post('prospect_id');
        //$id=3;
        //Get Records From PROSPECT_MASTER Table
        $table = PROSPECT_MASTER . ' as pm';
        $match = "pm.prospect_id = " . $id;
        $fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
            . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.state,pm.country_id,pm.prospect_owner_id,pm.language_id,"
            . "pm.branch_id,pm.estimate_prospect_worth,pm.prospect_generate,pm.campaign_id,pm.description,"
            . "pm.file,pm.contact_date,pm.created_date");
        $prospect_info = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['prospect_id'] = $prospect_info[0]['prospect_id'];
        $data['prospect_auto_id'] = $prospect_info[0]['prospect_auto_id'];
        $data['prospect_name'] = $prospect_info[0]['prospect_name'];
        $data['status_type'] = $prospect_info[0]['status_type'];
        $data['company_id'] = $prospect_info[0]['company_id'];
        $data['address1'] = $prospect_info[0]['address1'];
        $data['address2'] = $prospect_info[0]['address2'];
        $data['postal_code'] = $prospect_info[0]['postal_code'];
        $data['state'] = $prospect_info[0]['state'];
        $data['country_id'] = $prospect_info[0]['country_id'];
        $data['prospect_owner_id'] = $prospect_info[0]['prospect_owner_id'];
        $data['language_id'] = $prospect_info[0]['language_id'];
        $data['branch_id'] = $prospect_info[0]['branch_id'];
        $data['estimate_prospect_worth'] = $prospect_info[0]['estimate_prospect_worth'];
        $data['prospect_generate'] = $prospect_info[0]['prospect_generate'];
        $data['campaign_id'] = $prospect_info[0]['campaign_id'];
        $data['description'] = $prospect_info[0]['description'];
        $data['uploaded_file_name'] = $prospect_info[0]['file'];
        $data['contact_date'] = $prospect_info[0]['contact_date'];
        $data['creation_date'] = $prospect_info[0]['creation_date'];


        //Get Records From PROSPECT_CONTACTS_TRAN Table
        $table2 = PROSPECT_CONTACTS_TRAN . ' as pcm';
        $match2 = "pcm.prospect_id = " . $id;
        $fields2 = array("pcm.contact_name,pcm.email_id,pcm.phone_no");
        $contact_info = $this->common_model->get_records($table2, $fields2, '', '', $match2);

        if ($contact_info) {
            for ($i = 0; $i < count($contact_info); $i++) {
                $data['contact_name'][$i] = $contact_info[$i]['contact_name'];
                $data['email_id'][$i] = $contact_info[$i]['email_id'];
                $data['phone_no'][$i] = $contact_info[$i]['phone_no'];
            }
        }

        //Get Records From PROSPECT_PRODUCTS_TRAN Table
        $table1 = PROSPECT_PRODUCTS_TRAN . ' as pt';
        $match1 = "pt.prospect_id = " . $id;
        $fields1 = array("pt.product_id");
        $product_info = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        if ($product_info) {
            for ($i = 0; $i < count($product_info); $i++) {
                $data['product_id'][$i] = $product_info[$i]['product_id'];
            }
        }

        $data['id'] = $id;
        $data['opportunity_view'] = $this->viewname;
        $data['main_content'] = '/Opportunity';
        //Pass TABLE Record In View
        echo json_encode($data);
    }

    public function deletedata() {
        $id = $this->input->get('id');
        //Delete Record From Database
        if (!empty($id)) {
            $where = array('prospect_id' => $id);
            $this->common_model->delete(PROSPECT_CONTACTS_TRAN, $where);
            $this->common_model->delete(CAMPAIGN_GROUP_SALES_MASTER, $where);
            $delete_suceess = $this->common_model->delete(PROSPECT_MASTER, $where);
            $this->common_model->delete(PROSPECT_PRODUCTS_TRAN, $where);
            if ($delete_suceess) {
                $msg = $this->lang->line('opp_del_msg');
                $this->session->set_flashdata('msg',$msg);
            }

            unset($id);
        }
        redirect($this->viewname); //Redirect On Listing page
    }

    function loadAjaxOpportunityList() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct script Allowed");
        }
        $this->load->model('Opportunity_model');
        $data['opportunity_view'] = $this->viewname;
        $match[] = "pm.status_type =1";
        if ($this->input->post('search_branch_id') != '') {
            $branchId = $this->input->post('search_branch_id');
            $match[] = "pm.branch_id ='$branchId'";
        }
        if ($this->input->post('search_prospect_owner_id') != '') {
            $ownerId = $this->input->post('search_prospect_owner_id');
            $match[] = "pm.prospect_owner_id ='$ownerId'";
        }
        if ($this->input->post('status') != '') {
            $status = $this->input->post('status');
            $match[] = "pm.status ='$status'";
        }

        if ($this->input->post('end_value') != '' && $this->input->post('end_value') != '') {
            $startVal = $this->input->post('start_value');
            $endVal = $this->input->post('end_value');
            $match[] = "pm.estimate_prospect_worth >='$startVal' and pm.estimate_prospect_worth >='$endVal'  ";
        }
        if ($this->input->post('search_creation_date') != '' && $this->input->post('creation_end_date') != '') {
            $startVal = $this->input->post('search_creation_date');
            $endVal = $this->input->post('creation_end_date');
            $match[] = "pm.created_date >='$search_creation_date' and pm.created_date <='$creation_end_date'  ";
        }
        if ($this->input->post('search_contact_date') != '' && $this->input->post('contact_end_date') != '') {
            $startVal = $this->input->post('search_contact_date');
            $endVal = $this->input->post('contact_end_date');
            $match[] = "pm.contact_date >='$search_creation_date' and pm.contact_date <='$creation_end_date'";
        }

        $table = PROSPECT_MASTER . ' as pm';

        $fields = "pm.prospect_id,count(pm.prospect_id) as opp_count,pm.prospect_name,pm.prospect_auto_id, pm.status_type,count(pc.prospect_id) as contact_count,pc.contact_name";

        $order = 'pm.prospect_id DESC';
        $data['prospect_data'] = $this->Opportunity_model->loadAjaxOpportunityList($fields, $match, $order);
        $view = $this->load->view('ajaxOpportunity', $data, true);
        if (count($data['prospect_data']) > 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        echo json_encode(array('data' => $view, 'status' => $status));
    }

}
