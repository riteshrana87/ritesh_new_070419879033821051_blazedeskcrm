<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lead extends CI_Controller {

    public $viewname;

    function __construct() {
        parent::__construct();
        if(checkPermission('Lead','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->prefix = $this->db->dbprefix;
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation', 'Session'));
        $this->load->model('Lead_model');
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 13/01/2016
     */

    public function index() {

        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('lead_data');
        }

        $searchsortSession = $this->session->userdata('lead_data');
//Sorting
        if (!empty($sortfield) && !empty($sortby)) {

            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsortSession['sortfield'])) {
                $data['sortfield'] = $searchsortSession['sortfield'];
                $data['sortby'] = $searchsortSession['sortby'];
                $sortfield = $searchsortSession['sortfield'];
                $sortby = $searchsortSession['sortby'];
            } else {
                $sortfield = 'creation_date';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
//Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsortSession['searchtext'])) {
                $data['searchtext'] = $searchsortSession['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsortSession['perpage'])) {
                $data['perpage'] = trim($searchsortSession['perpage']);
                $config['per_page'] = trim($searchsortSession['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
//pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->viewname . '/index';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uriSegment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uriSegment = $this->uri->segment(3);
        }

//Get Records From COMPANY_MASTER Table       
        $table8 = COMPANY_MASTER . ' as cmp';
        $match8 = " cmp.status=1 and cmp.is_delete=0 ";
        $fields8 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table8, $fields8, '', '', $match8);

//Get owner Records From LOGIN Table      
        $data['prospect_owner'] = $this->common_model->getSystemUserData();

//Get Records From BRANCH_MASTER Table       
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = " bm.status=1 ";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

//Get Records From LEAD_MASTER Table with join for contact data
        $params['join_tables'] = array(
            LEAD_CONTACTS_TRAN . ' as pc' => 'pc.lead_id=lm.lead_id',
            ESTIMATE_MASTER . ' as em' => 'em.estimate_id=lm.estimate_prospect_worth',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=pc.contact_id ');
        $params['join_type'] = 'left';
        //$match = "lm.status_type=2";
        $table = LEAD_MASTER . ' as lm';
        $group_by = 'lm.lead_id';
        $fields = array("lm.lead_id,lm.prospect_name,lm.prospect_auto_id, lm.status_type,lm.creation_date,(select count(pc.lead_id) from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . LEAD_CONTACTS_TRAN . " as pc on cm.contact_id=pc.contact_id where cm.is_delete=0 and cm.status=1 and pc.lead_id=lm.lead_id group by lm.lead_id) as contact_count,(select cm.contact_name from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . LEAD_CONTACTS_TRAN . " as pc on cm.contact_id=pc.contact_id where pc.primary_contact=1 and cm.is_delete=0 and cm.status=1 and pc.lead_id=lm.lead_id group by lm.lead_id) as contact_name");
        $where = "lm.is_delete=0 and lm.status_type=2 and lm.status=1 ";

//If search data are there take post value and update query
        $data['branch_show_id'] = "";
        if ($this->input->post('search_branch_id') != "") {
            $data['branch_show_id'] = $this->input->post('search_branch_id');
            $where.=' and lm.branch_id=' . $data['branch_show_id'];
        }
        $data['company_show_id'] = "";
        if ($this->input->post('search_company_id') != "") {
            $data['company_show_id'] = $this->input->post('search_company_id');
            //change by sanket for searching  by prospect owner (Login User)
            //$where.=' and lm.company_id=' . $data['company_show_id'];
            $where.=' and lm.prospect_owner_id=' . $data['company_show_id'];
        }
        $data['status_show'] = "";
        if ($this->input->post('search_status') != "") {
            $data['status_show'] = $this->input->post('search_status');
            $where.=' and lm.status=' . $data['status_show'];
        }

        $data['start_value_show'] = "";
        if ($this->input->post('start_value') != "") {
            $data['start_value_show'] = $this->input->post('start_value');
            $where.=' and em.value>=' . $data['start_value_show'];
        }
        $data['end_value_show'] = "";
        if ($this->input->post('end_value') != "") {
            $data['end_value_show'] = $this->input->post('end_value');
            $where.=' and em.value<=' . $data['end_value_show'];
        }
        $data['search_creation_date_show'] = "";
        if ($this->input->post('search_creation_date') != "") {
            $data['search_creation_date_show'] = date_format(date_create($this->input->post('search_creation_date')), 'Y-m-d');
            $where.=' and lm.creation_date>="' . $data['search_creation_date_show'] . '"';
        }
        $data['creation_end_date_show'] = "";
        if ($this->input->post('creation_end_date') != "") {
            $data['creation_end_date_show'] = date_format(date_create($this->input->post('creation_end_date')), 'Y-m-d');
            $where.=' and lm.creation_date<="' . $data['creation_end_date_show'] . '"';
        }
        $data['search_contact_date_show'] = "";
        if ($this->input->post('search_contact_date') != "") {
            $data['search_contact_date_show'] = date_format(date_create($this->input->post('search_contact_date')), 'Y-m-d');
            $where.=' and lm.contact_date>="' . $data['search_contact_date_show'] . '"';
        }
        $data['contact_end_date_show'] = "";
        if ($this->input->post('contact_end_date') != "") {
            $data['contact_end_date_show'] = date_format(date_create($this->input->post('contact_end_date')), 'Y-m-d');
            $where.=' and lm.contact_date<="' . $data['contact_end_date_show'] . '"';
        }


        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $lead_since = date('Y-m-d', strtotime($searchtext));
            $where_search = '(lm.prospect_name LIKE "%' . $searchtext . '%" OR lm.prospect_auto_id LIKE "%' . $searchtext . '%" OR lm.status_type LIKE "%' . $searchtext . '%" OR lm.creation_date LIKE "%' . $lead_since . '%"  OR cm.contact_name LIKE "%' . $searchtext . '%")';
            $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);

            //Get Total Records From LEAD_MASTER Table 
            $total_lead = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);
        } else {
            $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');

            //Get Total Records From LEAD_MASTER Table 
            $total_lead = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
        }
        //check  total lead > 0 otherwise set total lead = 0
        if ($total_lead) {
            $data['total_lead'] = $total_lead;
        } else {
            $data['total_lead'] = '0';
        }

        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uriSegment;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uriSegment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('lead_data', $sortsearchpage_data);
        $data['drag'] = true;
        $data['lead_view'] = $this->viewname;

        $data['header'] = array('menu_module' => 'crm');
        $data['sales_view'] = $this->viewname;

        if ($this->input->post('result_type') == 'ajax') {

            $this->load->view('AjaxLeadList', $data);
        } else {
            $data['main_content'] = '/' . $this->viewname;
            $this->parser->parse('layouts/DashboardTemplate', $data);
        }
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Lead Model Add Page
      @Input 	:
      @Output	: Show Modal For Add Lead
      @Date     : 13/01/2016
     */

    public function add()
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data = array();
            $data['lead_view'] = $this->viewname;
            $redirectLink = $this->input->post('redirect_link');
            $data['main_content'] = '/Lead';

            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['modal_title'] = $this->lang->line('create_new_lead');
            $data['submit_button_title'] = $this->lang->line('create_lead');

            //By default owner selected as login user
            $prospectOwner[] = array('prospect_owner_id' => $this->session->userdata('LOGGED_IN')['ID']);
            $data['edit_record'] = $prospectOwner;
            //Generate Lead auto id 
            $data['pros_auto_id'] = $this->common_model->lead_auto_gen_Id();

            //Get owner data from LOGIN table     
            $data['prospect_owner'] = $this->common_model->getSystemUserData();

            //Get Records From LANGUAGE_MASTER Table
            $table1 = LANGUAGE_MASTER . ' as lan';
            $match1 = "";
            $fields1 = array("lan.language_id,lan.language_name");
            $data['language_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            //Get Records From BRANCH_MASTER Table       
            $table2 = BRANCH_MASTER . ' as bm';
            $match2 = " bm.status=1 ";
            $fields2 = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

            //Get Records From CAMPAIGN_MASTER Table       
            $table3 = CAMPAIGN_MASTER . ' as cam';
            $match3 = " cam.status=1 ";
            $fields3 = array("cam.campaign_id,cam.campaign_name");
            $data['campaign'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);

            //Get Records From PRODUCT_MASTER Table       
            $table4 = PRODUCT_MASTER . ' as prm';
            $match4 = " prm.is_delete='0' and prm.status=1 ";
            $fields4 = array("prm.product_id,prm.product_name");
            $data['product_data'] = $this->common_model->get_records($table4, $fields4, '', '', $match4);

            //Get Records From LEAD_MASTER Table       
            $table6 = LEAD_MASTER . ' as lm';
            $match6 = "lm.status_type=1 and lm.status=1 and lm.is_delete=0 ";
            $fields6 = array("count(lm.lead_id) as total_lead");
            $totalOpportunity = $this->common_model->get_records($table6, $fields6, '', '', $match6);

            $data['total_lead'] = $totalOpportunity[0]['total_lead'];
            //Get Records From COUNTRIES Table       
            $table7 = COUNTRIES . ' as c';
            $match7 = "";
            $fields7 = array("c.country_id,c.country_name,c.country_code");
            $data['country_data'] = $this->common_model->get_records($table7, $fields7, '', '', $match7);

            //Get Records From COMPANY_MASTER Table       
            $table8 = COMPANY_MASTER . ' as cmp';
            $match8 = " cmp.status=1 and cmp.is_delete=0 ";
            $fields8 = array("cmp.company_id,cmp.company_name");
            $data['company_data'] = $this->common_model->get_records($table8, $fields8, '', '', $match8);

            //Get Estimate Master data
            $table1 = ESTIMATE_MASTER . ' as ESTM';
            $match1 = " ESTM.status=1 ";
            $fields1 = array("ESTM.subject,ESTM.estimate_id");
            $data['EstimateArray'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            $data['drag'] = true;
            //Get all data from table and display in AddEditLead
            $this->load->view('AddEditLead', $data);
            
        }
        
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Lead Model insertdata Page
      @Input 	: Input post data
      @Output	: Insert in database
      @Date     : 13/01/2016
     */
    /*     * ************************************************************************************************

     * Check if have id then update data otherwise insertdata

      1)  When click on Create Lead or Update Lead this function insert data in related table.
      2)  Get input post data .
      3)  If input post branch name exist in BRANCH_MASTER then take branch id.
      4)  If input post branch name not exist in BRANCH_MASTER then insert that branch in BRANCH_MASTER
      and get inserted branch id.
      5)  If input post company select from list then get company_id.
      6)  If input post company is Add New Comapny then get company data and insert in COMPANY_MASTER
      and get inserted id.
      7)  If have prospect owner then get detail and send email to that prospect.
      8)  If have input post campaign then insert  in TBL_CAMPAIGN_CONTACT and update then update it.
      9)  Insert all files and documents in FILES_LEAD_MASTER.
      10) If have any deleted file or document then delete form FILES_LEAD_MASTER and unlink.
      11) If have deleted contact then delete from CONTACT_MASTER.
      12) Insert contact data in CONTACT_MASTER and  relational table is LEAD_CONTACTS_TRAN.
      13) If have input post produts then insert in LEAD_PRODUCTS_TRAN table.

     * ************************************************************************************************* */

    public function insertdata() {
        $id = '';
        if ($this->input->post('lead_id')) {
            $id = $this->input->post('lead_id');
        }
        $redirectLink = $this->input->post('redirect_link');
        if (!validateFormSecret()) {

            redirect($redirectLink); //Redirect On Listing page
        }
        $data = array();
        $data['lead_view'] = $this->viewname;

        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");

        $prospectData['prospect_name'] = strip_slashes(trim($this->input->post('lead_name')));
        $prospectData['address1'] = strip_slashes($this->input->post('address1'));
        $prospectData['address2'] = strip_slashes($this->input->post('address2'));
        $prospectData['creation_date'] = date_format(date_create($this->input->post('creation_date')), 'Y-m-d');
        $prospectData['postal_code'] = strip_slashes($this->input->post('postal_code'));
        $prospectData['city'] = strip_slashes($this->input->post('city'));
        $prospectData['state'] = strip_slashes($this->input->post('state'));
        $prospectData['number_type1'] = $this->input->post('number_type1');
        $prospectData['phone_no'] = $this->input->post('phone_no1_lead');
        $prospectData['number_type2'] = $this->input->post('number_type2');
        $prospectData['phone_no2'] = $this->input->post('phone_no2_lead');
        $prospectData['language_id'] = $this->input->post('language_id');
        $prospectData['fb'] = $this->input->post('fb');
        $prospectData['twitter'] = $this->input->post('twitter');
        $prospectData['linkedin'] = $this->input->post('linkedin');
        $prospectOwnerId = $this->input->post('prospect_owner_id');
        $prospectData['prospect_owner_id'] = $prospectOwnerId;
        $branchName = strip_slashes($this->input->post('branch_id'));
    //Get Branch id From BRANCH_MASTER Table for input post branch name    
        $table22 = BRANCH_MASTER . ' as bm';
        $match22 = "bm.branch_name='" . addslashes($branchName) . "' and bm.status=1 ";
        $fields22 = array("bm.branch_name, bm.branch_id");
        $branch_record = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        //If branch name exist in table then get id else insert branch data in BRANCH_MASTER
        if ($branch_record) {
            $prospectData['branch_id'] = $branch_record[0]['branch_id'];
        } else {
            $branchData['branch_name'] = $branchName;
        }
        if (count($branch_record) == 0) {
            //INSERT Branch data in BRANCH_MASTER
            $branchData['status'] = 1;
            $branchData['created_date'] = datetimeformat();
            $branchData['modified_date'] = datetimeformat();
            $branchId = $this->common_model->insert(BRANCH_MASTER, $branchData);
            $prospectData['branch_id'] = $branchId;
        }

        $company_name = $this->input->post('company_name');
        if (empty($company_name)) {
            $prospectData['company_id'] = $this->input->post('company_id');
        } else {

            $table23 = COMPANY_MASTER . ' as cm';
            $match23 = "cm.company_name='" . addslashes($company_name) . "' and cm.status=1 ";
            $fields23 = array("cm.*");
            $company_record = $this->common_model->get_records($table23, $fields23, '', '', $match23);
            //If branch name exist in table then get id else insert branch data in BRANCH_MASTER
            if ($company_record) {
                $prospectData['company_id'] = $company_record[0]['company_id'];
            } else {
                $companyData['company_name'] = $company_name;
            }
            if (count($company_record) == 0) {
                //If select add new company get company data
                $companyData['company_name'] = strip_slashes(trim($this->input->post('company_name')));
                $companyData['email_id'] = $this->input->post('email_id_company');
                $companyData['website'] = $this->input->post('website');
                $companyData['company_id_data'] = $this->input->post('company_id_data');
                $companyData['reg_number'] = $this->input->post('com_reg_number');
                $companyData['branch_id'] = strip_slashes($prospectData['branch_id']);
                $companyData['address1'] = strip_slashes($this->input->post('address1'));
                $companyData['address2'] = strip_slashes($this->input->post('address2'));
                $companyData['postal_code'] = strip_slashes($this->input->post('postal_code'));
                $companyData['city'] = strip_slashes($this->input->post('city'));
                $companyData['state'] = strip_slashes($this->input->post('state'));
                $companyData['country_id'] = $this->input->post('country_id');
                $companyData['status'] = 1;
                $companyData['is_delete'] = 0;
                $companyData['phone_no'] = $this->input->post('phone_no_company');

                //upload logo image for company
                if (($_FILES['logo_image']['name']) != NULL) {
                    $config = array(
                        'upload_path' => FCPATH . "uploads/company/",
                        'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
                        'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    );
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('logo_image')) {
                        $file_data = array('upload_data' => $this->upload->data());
                        foreach ($file_data as $file) {
                            $companyData['logo_img'] = $file['file_name'];
                        }
                    } else {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                    }
                }
                $prospectData['company_id'] = $this->common_model->insert(COMPANY_MASTER, $companyData);
            } else {

                $prospectData['company_id'] = $prospectData['company_id'];
            }
        }
        /* Store company data in master admin */
        $companyData['company_name'] = strip_slashes(trim($this->input->post('company_name')));
        $companyData['email_id'] = $this->input->post('email_id_company');
        $companyData['website'] = $this->input->post('website');
        $companyData['company_id_data'] = $this->input->post('company_id_data');
        $companyData['reg_number'] = $this->input->post('com_reg_number');
        $companyData['branch_id'] = strip_slashes($prospectData['branch_id']);
        $companyData['address1'] = strip_slashes($this->input->post('address1'));
        $companyData['address2'] = strip_slashes($this->input->post('address2'));
        $companyData['postal_code'] = strip_slashes($this->input->post('postal_code'));
        $companyData['city'] = strip_slashes($this->input->post('city'));
        $companyData['state'] = strip_slashes($this->input->post('state'));
        $companyData['country_id'] = $this->input->post('country_id');
        $companyData['status'] = 1;
        $companyData['is_delete'] = 0;
        $companyData['phone_no'] = $this->input->post('phone_no_company');

        $tableMaster = COMPANY . ' as cm';
        $matchMaster = "cm.company_name='" . addslashes($company_name) . "' and cm.status=1 ";
        $fieldsMaster = array("cm.*");
        $companyMaster = $this->common_model->get_records_data($tableMaster, $fieldsMaster, '', '', $matchMaster);

        if (count($companyMaster) == 0) {
            $contactdata['company_id'] = $this->common_model->insert_data(COMPANY, $companyData);
        } else {
            $where = array('company_id' => $companyMaster[0]['company_id']);
            $this->common_model->update_data(COMPANY, $companyData, $where);
        }

        /* end */



        $prospectData['country_id'] = $this->input->post('country_id');
        $prospectData['estimate_prospect_worth'] = $this->input->post('estimate_prospect_worth');
        $prospectGenerate = '0';
        if ($this->input->post('prospect_generate') == 'on') {
            $prospectGenerate = '1';
            if ($this->input->post('campaign_id')) {
                $prospectData['campaign_id'] = $this->input->post('campaign_id');
            }
        } else {
            $prospectData['campaign_id'] = '';
        }
        $prospectData['prospect_generate'] = $prospectGenerate;


        $prospectData['description'] = $this->input->post('description', FALSE);
        if ($this->input->post('contact_date')) {
            $prospectData['contact_date'] = date_format(date_create($this->input->post('contact_date')), 'Y-m-d');
        } else {
            $prospectData['contact_date'] = '';
        }

        $prospectData['status'] = 1;
        //status_type=2 for lead
        $prospectData['status_type'] = 2;
        $prospectData['modified_date'] = datetimeformat();
        $prospectData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];

        //Insert Record in Database

        if ($id) {
            //Check for Duplicate Branch Name: Start

            /*
             * gets current data of current opportunity 
             */
            $table9 = LEAD_MASTER . ' as lm';
            $match9 = "lm.lead_id = " . $id;
            $fields9 = array("lm.lead_id,lm.prospect_auto_id,lm.prospect_name,lm.company_id,"
                . "lm.address1,lm.address2,lm.creation_date,lm.postal_code,lm.city,lm.state,lm.country_id,lm.number_type1,"
                . "lm.phone_no,lm.number_type2,lm.phone_no2,lm.prospect_owner_id,lm.language_id,lm.branch_id,lm.estimate_prospect_worth,lm.prospect_generate,"
                . "lm.campaign_id,lm.description,lm.file,lm.contact_date,lm.status");
            $currentProspectData = $this->common_model->get_records($table9, $fields9, '', '', $match9);

            if (count($currentProspectData) > 0) {
                $prospectOwnerOldId = $currentProspectData[0]['prospect_owner_id'];
            }

            /*
             * will sends email if prospect owner gets changed
             */
            if ($prospectOwnerOldId != $prospectOwnerId) {

                /*
                 * fetches new prospect owner data
                 */

                //user details from id
                $umatch = "login_id =" . $prospectOwnerId;
                $ufields = array("concat(firstname,' ',lastname) as fullname,email");
                $newProspectOwnerData = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);

                if (count($newProspectOwnerData) > 0) {
                    $prospectOwnerEmail = $newProspectOwnerData[0]['email'];
                    $prospectOwnerName = $newProspectOwnerData[0]['fullname'];
                    $prospect_name = $prospectData['prospect_name'];
                    // Get Template from Template Master
                    $etable = EMAIL_TEMPLATE_MASTER . ' as et';
                    $ematch = "et.template_id =55";
                    $efields = array("et.subject,et.body");
                    $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
                    if (count($template) > 0) {

                        $find = array(
                            '{USER}',
                            '{OPPORTUNITY_NAME}',
                            '{LINK}',
                            '{TYPE}'
                        );
                        $url = base_url('Lead/viewdata/' . $id);
                        $replace = array(
                            'USER' => $prospectOwnerName,
                            'OPPORTUNITY_NAME' => $prospect_name,
                            'LINK' => "<a href='" . $url . "' title='view opportunity'>View</a>",
                            'TYPE' => ' Lead'
                        );
                        $format = $template[0]['body'];
                        $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
                        $subject = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $template[0]['subject']))));

                        send_mail($prospectOwnerEmail, $subject, $body);
                    }
                }
            }
            $where = array('lead_id' => $id);

            $successUpdate = $this->common_model->update(LEAD_MASTER, $prospectData, $where);

            if ($this->input->post('campaign_id')) {
                $table = TBL_CAMPAIGN_CONTACT . ' as cc';
                $fields = 'campaign_related_id';
                $match = 'campaign_related_id=' . $id . ' and campaign_status=3 and is_delete=0 ';
                $getCampaignData = $this->common_model->get_records($table, $fields, '', '', $match);
                if (!empty($getCampaignData)) {
                    $where = array('campaign_related_id' => $id, 'campaign_status' => 3);
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $where);
                } else {
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $campaignData['campaign_related_id'] = $id;
                    $campaignData['campaign_status'] = 3;
                    $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                }
            }
            if ($successUpdate) {

                $msg = $this->lang->line('lead_update_msg');
                $this->session->set_flashdata('msg', $msg);
                if (strpos($redirectLink, 'Lead/viewdata') !== false) {
                    $sess_array = array('setting_current_tab' => 'Deals');
                    $this->session->set_userdata($sess_array);
                    $this->session->set_flashdata('message', $msg);
                }
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        } else {
            $prospectData['prospect_auto_id'] = $this->input->post('prospect_auto_id');
            $prospectData['created_date'] = datetimeformat();
            $returnLeadId = $this->common_model->insert(LEAD_MASTER, $prospectData);

            //insert campaign also in campaign_contact
            if ($this->input->post('campaign_id')) {
                $campaignData['campaign_id'] = $this->input->post('campaign_id');
                $campaignData['campaign_related_id'] = $returnLeadId;
                $campaignData['campaign_status'] = 3;
                $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
            }

            if ($returnLeadId) {
                $msg = $this->lang->line('lead_add_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
//ritesh code
//        if ($prospectOwnerId) {
//            $table = LOGIN . ' as us';
//            $where = array("us.is_delete" => 0, "us.login_id" => $prospectOwnerId);
//            $fields = array("us.email");
//            $prospect_owner = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
//            /* send email */
//            $email = $prospect_owner[0]['email'];
//            $subject = 'Lead owner';
//            $message = 'Hello You are Lead owner for ' . $prospectData['prospect_name'] . ' Lead';
//            send_mail($email, $subject, $message);
//        }
        //Data in COMPANY_ACCOUNTS_TRAN Insert
        if ($id) {

            //   Update lead data in COMPANY_ACCOUNTS_TRAN  
            $where = array('client_id' => $id, 'status_type' => 2);
            $companyAccountData['company_id'] = $prospectData['company_id'];
            $this->common_model->update(COMPANY_ACCOUNTS_TRAN, $companyAccountData, $where);
        } else {
            //   Insert lead data in COMPANY_ACCOUNTS_TRAN      
            $companyAccountData['company_id'] = $prospectData['company_id'];
            $companyAccountData['client_id'] = $returnLeadId;
            $companyAccountData['status_type'] = $prospectData['status_type'];
            $companyAccountData['created_date'] = datetimeformat();
            $companyAccountData['modified_date'] = datetimeformat();
            $companyAccountData['status'] = 1;
            $this->common_model->insert(COMPANY_ACCOUNTS_TRAN, $companyAccountData);
        }

        //upload file
        $fileName = array();
        $fileArray1 = $this->input->post('file_data');

        $fileName = $_FILES['prospect_files']['name'];
        if (count($fileName) > 0 && count($fileArray1) > 0) {
            $differentedImage = array_diff($fileName, $fileArray1);
            foreach ($fileName as $file) {
                if (in_array($file, $differentedImage)) {
                    $keyData[] = array_search($file, $fileName); // $key = 2;
                }
            }
            if (!empty($keyData)) {
                foreach ($keyData as $key) {
                    unset($_FILES['prospect_files']['name'][$key]);
                    unset($_FILES['prospect_files']['type'][$key]);
                    unset($_FILES['prospect_files']['tmp_name'][$key]);
                    unset($_FILES['prospect_files']['error'][$key]);
                    unset($_FILES['prospect_files']['size'][$key]);
                }
            }
        }
        $_FILES['prospect_files'] = $arr = array_map('array_values', $_FILES['prospect_files']);

        $uploadData = uploadImage('prospect_files', prospect_upload_path, $data['lead_view']);
        $Marketingfiles = array();
        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $file_data = implode(",", $file2);
        } else {
            $file_data = '';
        }

        $compaigndata['file_name'] = $file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }

        $prospectFiles = array();

        if ($this->input->post('gallery_path')) {
            $galleryPath = $this->input->post('gallery_path');
            $galleryFilesData = $this->input->post('gallery_files');
            if (count($galleryPath) > 0) {
                for ($i = 0; $i < count($galleryPath); $i++) {
                    if ($id) {
                        $prospectFiles[] = ['file_name' => $galleryFilesData[$i], 'file_path' => $galleryPath[$i], 'lead_id' => $id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    } else {
                        $prospectFiles[] = ['file_name' => $galleryFilesData[$i], 'file_path' => $galleryPath[$i], 'lead_id' => $returnLeadId, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    }
                }
            }
        }

        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                if ($id) {
                    $prospectFiles[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'lead_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                } else {
                    $prospectFiles[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'lead_id' => $returnLeadId, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }

        if (count($prospectFiles) > 0) {
            if ($id) {
                $where = array('lead_id' => $id);
            } else {
                $where = array('lead_id' => $returnLeadId);
            }

            if (!$this->common_model->insert_batch(FILES_LEAD_MASTER, $prospectFiles)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
        /**
         * SOFT DELETION CODE STARTS FOR IMAGE DELETE
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
            $this->common_model->delete(FILES_LEAD_MASTER, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        /**
         * SOFT DELETION CODE STARTS FOR CONTACT DELETE
         */
        $softDeleteContactsArr = $this->input->post('softDeletedContacts');
        if (count($softDeleteContactsArr) > 0) {
            $dlStr = implode(',', $softDeleteContactsArr);
            $contactData['is_delete'] = 1;
            $this->common_model->update(CONTACT_MASTER, $contactData, 'contact_id IN(' . $dlStr . ')');
        }
        
        
        /*
         * SOFT DELETION CODE ENDS
         */
        
        //Check primary for this company exist or not
            $table23 = CONTACT_MASTER . ' as cm';
            $match23 = "cm.company_id='" . $prospectData['company_id'] . "' and cm.is_delete=0 and primary_contact=1  and cm.status=1 ";
            $fields23 = array("cm.contact_id");
            $primaryExist = $this->common_model->get_records($table23, $fields23, '', '', $match23);
          
            if(empty($primaryExist)){
        //get contact for this company 
                $table23 = CONTACT_MASTER . ' as cm';
                $match23 = "cm.company_id='" . $prospectData['company_id'] . "' and cm.is_delete=0  and cm.status=1 ";
                $fields23 = array("cm.contact_id");
                $changePrimary = $this->common_model->get_records($table23, $fields23, '', '', $match23);
                
                if($changePrimary){
                    $where = array('contact_id' => $changePrimary[0]['contact_id']);
                    $contactData['primary_contact'] = 1;
                    $this->common_model->update(CONTACT_MASTER, $contactData, $where);
                }
                
            }
        
        $primaryContact = array();
        $primaryContact = $this->input->post('primary_contact');
        $array = array_filter($primaryContact, create_function('$a', 'return $a!=="";'));
        $array = array_merge($array, array());
        $primaryContact = $array;
        $prospectContactsName = array();
        $prospectContactsName = $this->input->post('contact_name');
        $contacts_email_id = array();
        $prospectContactsEmailId = $this->input->post('email_id');
        $prospectContactsPhoneNo = array();
        $prospectContactsPhoneNo = $this->input->post('phone_no');
        $prospectContactsTran['status'] = 1;
        $prospectContactsTran['modified_date'] = datetimeformat();

        //Insert Record in Database
        if ($id) {

            if (!empty($prospectContactsName)) {
                $prospectContactsTran['primary_contact'] = "0";
                for ($prospectContactsCount = 0; $prospectContactsCount < count($prospectContactsName); $prospectContactsCount++) {

                    if ($primaryContact[$prospectContactsCount] > 0) {
                        $prospectContactsTran['primary_contact'] = "1";
                    } else {
                        $prospectContactsTran['primary_contact'] = "0";
                    }

                    $contactMasterData['status'] = 1;
                    $contactMasterData['created_date'] = datetimeformat();
                    $contactMasterData['modified_date'] = datetimeformat();
                    $contactMasterData['company_id'] = $prospectData['company_id'];
                    $contactMasterData['address1'] = strip_slashes($this->input->post('address1'));
                    $contactMasterData['address2'] = strip_slashes($this->input->post('address2'));
                    $contactMasterData['postal_code'] = strip_slashes($this->input->post('postal_code'));
                    $contactMasterData['city'] = strip_slashes($this->input->post('city'));
                    $contactMasterData['state'] = strip_slashes($this->input->post('state'));
                    $contactMasterData['language_id'] = $this->input->post('language_id');
                    $contactMasterData['country_id'] = $this->input->post('country_id');
                    $contactMasterData['contact_name'] = $prospectContactsName[$prospectContactsCount];
                    $contactMasterData['email'] = $prospectContactsEmailId[$prospectContactsCount];
                    $contactMasterData['mobile_number'] = $prospectContactsPhoneNo[$prospectContactsCount];
                    $contactId = $this->input->post('contact_id');
                    if ($contactId[$prospectContactsCount] > 0) {
                        $where = array('contact_id' => $contactId[$prospectContactsCount]);
                        $this->common_model->update(CONTACT_MASTER, $contactMasterData, $where);
                        $this->common_model->update(LEAD_CONTACTS_TRAN, $prospectContactsTran, $where);
                        if ($this->input->post('campaign_id')) {
                            $where = array('campaign_related_id' => $contactId[$prospectContactsCount], 'campaign_status' => 1);
                            $campaignData['campaign_id'] = $this->input->post('campaign_id');
                            $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $where);
                        }
                    } else {
                        $prospectContactsTran['lead_id'] = $id;
                        //Check primary for this company exist or not
                        $table23 = CONTACT_MASTER . ' as cm';
                        $match23 = "cm.company_id='" . $prospectData['company_id'] . "' and cm.is_delete=0 and primary_contact=1  and cm.status=1 ";
                        $fields23 = array("cm.*");
                        $primary_exist = $this->common_model->get_records($table23, $fields23, '', '', $match23);

                        if ($primary_exist) {
                            $contactMasterData['primary_contact'] = 0;
                        } else {
                            $contactMasterData['primary_contact'] = 1;
                        }
                        $contactId = $this->common_model->insert(CONTACT_MASTER, $contactMasterData);
                        $prospectContactsTran['created_date'] = datetimeformat();
                        $prospectContactsTran['contact_id'] = $contactId;
                        $this->common_model->insert(LEAD_CONTACTS_TRAN, $prospectContactsTran);
                        if ($this->input->post('campaign_id')) {
                            $campaignData['campaign_id'] = $this->input->post('campaign_id');
                            $campaignData['campaign_related_id'] = $contactId;
                            $campaignData['campaign_status'] = 1;
                            $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                        }
                    }
                }
            }
        } else {
            $prospectContactsTran['primary_contact'] = "0";
            //For loop for adding contacts to contact master & lead contact transaction table
            for ($prospectContactsCount = 0; $prospectContactsCount < count($prospectContactsName); $prospectContactsCount++) {

                $contactMasterData['status'] = 1;
                $contactMasterData['created_date'] = datetimeformat();
                $contactMasterData['modified_date'] = datetimeformat();
                $contactMasterData['company_id'] = $prospectData['company_id'];
                $contactMasterData['address1'] = strip_slashes($this->input->post('address1'));
                $contactMasterData['address2'] = strip_slashes($this->input->post('address2'));
                $contactMasterData['postal_code'] = strip_slashes($this->input->post('postal_code'));
                $contactMasterData['city'] = strip_slashes($this->input->post('city'));
                $contactMasterData['state'] = strip_slashes($this->input->post('state'));
                $contactMasterData['language_id'] = $this->input->post('language_id');
                $contactMasterData['country_id'] = $this->input->post('country_id');
                $contactMasterData['contact_name'] = $prospectContactsName[$prospectContactsCount];
                $contactMasterData['email'] = $prospectContactsEmailId[$prospectContactsCount];
                $contactMasterData['mobile_number'] = $prospectContactsPhoneNo[$prospectContactsCount];
                //Check primary for this company exist or not
                $table23 = CONTACT_MASTER . ' as cm';
                $match23 = "cm.company_id='" . $prospectData['company_id'] . "' and cm.is_delete=0 and primary_contact=1  and cm.status=1 ";
                $fields23 = array("cm.*");
                $primary_exist = $this->common_model->get_records($table23, $fields23, '', '', $match23);

                if ($primary_exist) {
                    $contactMasterData['primary_contact'] = 0;
                } else {
                    $contactMasterData['primary_contact'] = 1;
                }

                $contactId = $this->common_model->insert(CONTACT_MASTER, $contactMasterData);
                if ($primaryContact[$prospectContactsCount] > 0) {
                    $prospectContactsTran['primary_contact'] = "1";
                } else {
                    $prospectContactsTran['primary_contact'] = "0";
                }
                $prospectContactsTran['contact_id'] = $contactId;
                $prospectContactsTran['lead_id'] = $returnLeadId;
                $prospectContactsTran['status'] = 1;
                $prospectContactsTran['created_date'] = datetimeformat();
                $prospectContactsTran['modified_date'] = datetimeformat();
                $this->common_model->insert(LEAD_CONTACTS_TRAN, $prospectContactsTran);
                if ($this->input->post('campaign_id')) {
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $campaignData['campaign_related_id'] = $contactId;
                    $campaignData['campaign_status'] = 1;
                    $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                }
            }//End for
        }

        if ($this->input->post('interested_products')) {
            $productData['status'] = 1;
            $productData['created_date'] = datetimeformat();
            $productData['modified_date'] = datetimeformat();
            //Delete Record in Database
            if ($id) {

                $where = array('lead_id' => $id);
                $this->common_model->delete(LEAD_PRODUCTS_TRAN, $where);
            }
            $selectedProducts = $this->input->post('interested_products');
            $nProducts = count($selectedProducts);

            for ($i = 0; $i < $nProducts; $i++) {
                $productData['product_id'] = $selectedProducts[$i];
                //Insert Record in Database
                if ($id) {
                    $productData['lead_id'] = $id;
                    $this->common_model->insert(LEAD_PRODUCTS_TRAN, $productData);
                } else {
                    $productData['lead_id'] = $returnLeadId;
                    //Insert Record in Database
                    $this->common_model->insert(LEAD_PRODUCTS_TRAN, $productData);
                }
            }
        }

        redirect($redirectLink);
        //Redirect On Listing page
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Lead Model deletedata Page
      @Input 	: get id
      @Output	: set is_delete=1 and if success display message
      @Date     : 13/01/2016
     */

    public function deletedata() {

        $id = $this->input->get('id');
        //Delete Record From Database

        $params['join_tables'] = array(
            LEAD_CONTACTS_TRAN . ' as lct' => 'lct.lead_id=lm.lead_id',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=lct.contact_id');
        $params['join_type'] = 'left';
        $match = "lm.lead_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
        $table = LEAD_MASTER . ' as lm';

        $fields = array("lct.contact_id,lm.company_id");
        $contactIds = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

        for ($i = 0; $i < count($contactIds); $i++) {
            $where = array('contact_id' => $contactIds[$i]['contact_id']);
            $contactData['is_delete'] = 1;
            $this->common_model->update(CONTACT_MASTER, $contactData, $where);
        }
        
        //Check primary for this company exist or not
            $table23 = CONTACT_MASTER . ' as cm';
            $match23 = "cm.company_id='" . $contactIds[0]['company_id'] . "' and cm.is_delete=0 and primary_contact=1  and cm.status=1 ";
            $fields23 = array("cm.contact_id");
            $primaryExist = $this->common_model->get_records($table23, $fields23, '', '', $match23);
          
            if(empty($primaryExist)){
        //get contact for this company 
                $table23 = CONTACT_MASTER . ' as cm';
                $match23 = "cm.company_id='" . $contactIds[0]['company_id'] . "' and cm.is_delete=0  and cm.status=1 ";
                $fields23 = array("cm.contact_id");
                $changePrimary = $this->common_model->get_records($table23, $fields23, '', '', $match23);
                
                if($changePrimary){
                    $wherePrimary = array('contact_id' => $changePrimary[0]['contact_id']);
                    $contactData['primary_contact'] = 1;
                    $this->common_model->update(LEAD_MASTER, $contactData, $wherePrimary);
                }
                
            }
        
        $data['lead_view'] = $this->viewname;
        if (!empty($id)) {
            $where = array('lead_id' => $id);
            $leadData['is_delete'] = 1;
            $deleteSuceess = $this->common_model->update(LEAD_MASTER, $leadData, $where);
            
            $this->common_model->delete(LEAD_PRODUCTS_TRAN, $where);
            
            $this->common_model->delete(LEAD_CONTACTS_TRAN, $where);
            
            $this->common_model->delete(FILES_LEAD_MASTER, $where);
            
            $campaignwhere = array('campaign_related_id' => $delete_id['lead_id']);
            $this->common_model->delete(TBL_CAMPAIGN_CONTACT, $campaignwhere);
            
            $accwhere = array('client_id' => $delete_id['lead_id']);
            $this->common_model->delete(COMPANY_ACCOUNTS_TRAN, $accwhere);
            
            if ($deleteSuceess) {
                $msg = $this->lang->line('lead_del_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            unset($id);
        }
        redirect('Lead'); //Redirect On Listing page
    }

    public function converToQualified() {
        $leadId = $this->input->post('lead_id');

        if (!empty($leadId)) {

            $table = LEAD_MASTER . ' as lm';
            $match = "lm.lead_id = " . $leadId;
            $fields = array("lm.prospect_auto_id,lm.prospect_name,lm.company_id,"
                . "lm.address1,lm.address2,lm.creation_date,lm.postal_code,lm.city,lm.state,lm.country_id,lm.number_type1,lm.phone_no,lm.number_type2,lm.phone_no2,lm.prospect_owner_id,lm.language_id,"
                . "lm.branch_id,lm.estimate_prospect_worth,lm.prospect_generate,lm.campaign_id,lm.description,"
                . "lm.file,lm.contact_date,lm.status,lm.created_by");
            $leadData = $this->common_model->get_records($table, $fields, '', '', $match);

            $leadData[0]['status_type'] = '1';
            $prospectId = $this->common_model->insert(PROSPECT_MASTER, $leadData[0]); // insert each row to PROSPECT table
            //If company not have account then create new company account with status_type=2
            $companyId = $leadData[0]['company_id'];
            //check this company account exist or not
            $table = PROSPECT_MASTER . ' as pm';
            $match = "pm.company_id = " . $companyId . " and pm.status_type=2 and pm.is_delete=0";
            $fields = array("pm.company_id");
            $companyExist = $this->common_model->get_records($table, $fields, '', '', $match);
            if (empty($companyExist)) {
                //get company data from company id
                $table = COMPANY_MASTER . ' as cm';
                $match = "cm.company_id = " . $companyId;
                $fields = array("cm.company_id,cm.company_name as prospect_name,cm.branch_id,cm.address1,cm.address2,cm.city,
							cm.state,cm.country_id,cm.postal_code");
                $companyData = $this->common_model->get_records($table, $fields, '', '', $match);
                $companyData[0]['prospect_auto_id'] = $this->common_model->client_auto_gen_Id();
                $companyData[0]['status_type'] = 2;
                $companyData[0]['creation_date'] = datetimeformat();
                $companyData[0]['created_date'] = datetimeformat();
                $companyData[0]['modified_date'] = datetimeformat();
                $companyData[0]['status'] = 1;
                $companyData[0]['prospect_owner_id'] = $this->session->userdata('LOGGED_IN')['ID'];
                $CompanyAccountId = $this->common_model->insert(PROSPECT_MASTER, $companyData[0]);

                $opportunityRequirementAccount['requirement_description'] = $leadData[0]['description'];
                $opportunityRequirementAccount['modified_date'] = datetimeformat();
                $opportunityRequirementAccount['prospect_id'] = $CompanyAccountId;
                $opportunityRequirementAccount['created_date'] = datetimeformat();
                $opportunityRequirementAccount['status'] = 1;
                $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $opportunityRequirementAccount);
            }

            /*
             * will sends email if lead qualified
             */
            $umatch = "login_id =" . $leadData[0]['prospect_owner_id'];
            $ufields = array("concat(firstname,' ',lastname) as fullname,email");
            $newProspectOwnerData = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);

            if (count($newProspectOwnerData) > 0) {
                $prospectOwnerEmail = $newProspectOwnerData[0]['email'];
                $prospectOwnerName = $newProspectOwnerData[0]['fullname'];
                $prospect_name = $leadData[0]['prospect_name'];
                // Get Template from Template Master
                $etable = EMAIL_TEMPLATE_MASTER . ' as et';
                $ematch = "et.template_id =55";
                $efields = array("et.subject,et.body");
                $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
                if (count($template) > 0) {

                    $find = array(
                        '{USER}',
                        '{OPPORTUNITY_NAME}',
                        '{LINK}',
                        '{TYPE}'
                    );
                    $url = base_url('Opportunity/');
                    $replace = array(
                        'USER' => $prospectOwnerName,
                        'OPPORTUNITY_NAME' => $prospect_name,
                        'LINK' => "<a href='" . $url . "' title='view opportunity'>View</a>",
                        'TYPE' => ' Prospect'
                    );
                    $format = $template[0]['body'];
                    $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
                    $subject = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $template[0]['subject']))));

                    send_mail($prospectOwnerEmail, $subject, $body);
                }
            }

//COMPANY_ACCOUNTS_TRAN data update 
            $where = array('client_id' => $leadId, 'status_type' => 2);
            $companyAccountData['status_type'] = 1;
            $companyAccountData['client_id'] = $prospectId;
            $this->common_model->update(COMPANY_ACCOUNTS_TRAN, $companyAccountData, $where);

//OPPORTUNITY_REQUIREMENT data
            $opportunityRequirement['prospect_id'] = $prospectId;
            $opportunityRequirement['requirement_description'] = $leadData[0]['description'];
            $opportunityRequirement['created_date'] = datetimeformat();
            $opportunityRequirement['modified_date'] = datetimeformat();
            $opportunityRequirement['status'] = 1;
            $opportunityRequirement_id = $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $opportunityRequirement);
            $where = array('lead_id' => $leadId);
            $this->common_model->delete(LEAD_MASTER, $where);

            $table2 = LEAD_CONTACTS_TRAN . ' as lct';
            $match2 = "lct.lead_id = " . $leadId;
            $fields2 = array("lct.primary_contact,lct.contact_id");
            $leadContactData = $this->common_model->get_records($table2, $fields2, '', '', $match2);


            foreach ($leadContactData as $opportunityContactData) { // loop over results
                $opportunityContactData['prospect_id'] = $prospectId;
                $opportunityContactData['requirement_id'] = $opportunityRequirement_id;
                $opportunityContactData['created_date'] = datetimeformat();
                $opportunityContactData['modified_date'] = datetimeformat();
                $opportunityContactData['status'] = 1;
                $this->common_model->insert(OPPORTUNITY_REQUIREMENT_CONTACTS, $opportunityContactData); // insert each row to PROSPECT_CONTACTS_TRAN table
        
            }

            $where = array('lead_id' => $leadId);
            $this->common_model->delete(LEAD_CONTACTS_TRAN, $where);

            $table3 = LEAD_PRODUCTS_TRAN . ' as lpt';
            $match3 = "lpt.lead_id = " . $leadId;
            $fields3 = array("lpt.product_id");
            $leadProductData = $this->common_model->get_records($table3, $fields3, '', '', $match3);

            foreach ($leadProductData as $opportunityProductData) {
                // loop over results
                $opportunityProductData['prospect_id'] = $prospectId;
                $opportunityProductData['created_date'] = datetimeformat();
                $opportunityProductData['modified_date'] = datetimeformat();
                $opportunityProductData['status'] = 1;
                $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $opportunityProductData); // insert each row to PROSPECT table
            }
            $where = array('lead_id' => $leadId);
            $this->common_model->delete(LEAD_PRODUCTS_TRAN, $where);

            $table3 = FILES_LEAD_MASTER . ' as flm';
            $match3 = "flm.lead_id = " . $leadId;
            $fields3 = array("flm.file_name,flm.file_path,flm.upload_status,flm.type");
            $leadFileData = $this->common_model->get_records($table3, $fields3, '', '', $match3);

            foreach ($leadFileData as $opportunityFileData) {
                // loop over results
                $opportunityFileData['prospect_id'] = $prospectId;
                $this->common_model->insert(FILES_SALES_MASTER, $opportunityFileData); // insert each row to PROSPECT table
            }
            $where = array('lead_id' => $leadId);
            $this->common_model->delete(FILES_LEAD_MASTER, $where);

            //change lead id to prosepect id in notes table
            $whereNote = array('notes_related_id' => $leadId, 'note_status' => 3);
            $noteData['note_status'] = 4;
            $noteData['notes_related_id'] = $prospectId;
            $this->common_model->update(TBL_NOTE, $noteData, $whereNote);

            //change lead id to prosepect id in Task_master table
            $whereTask = array('task_related_id' => $leadId, 'task_status' => 3);
            $taskData['task_status'] = 4;
            $taskData['task_related_id'] = $prospectId;
            $this->common_model->update(TASK_MASTER, $taskData, $whereTask);

            //change lead id to prosepect id in Prospect_master table
            $whereDeal = array('prospect_related_id' => $leadId, 'deal_status' => 3);
            $dealData['deal_status'] = 4;
            $dealData['prospect_related_id'] = $prospectId;
            $this->common_model->update(PROSPECT_MASTER, $dealData, $whereDeal);

            //change lead id to prosepect id in Campaign_contact table
            $whereCampaign = array('campaign_related_id' => $leadId, 'campaign_status' => 3);
            $campaignData['campaign_status'] = 4;
            $campaignData['campaign_related_id'] = $prospectId;
            $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $whereCampaign);

            //change lead id to prosepect id in Cases table
            $whereCases = array('cases_related_id' => $leadId, 'cases_status' => 3);
            $casesData['cases_status'] = 4;
            $casesData['cases_related_id'] = $prospectId;
            $this->common_model->update(CRM_CASES, $casesData, $whereCases);

            //change lead id to prosepect id in Events table
            $whereEvents = array('event_related_id' => $leadId, 'event_status' => 3);
            $eventData['event_status'] = 4;
            $eventData['event_related_id'] = $prospectId;
            $this->common_model->update(TBL_EVENTS, $eventData, $whereEvents);

            //change lead id to prosepect id in Schedule Meeting table
            $whereMeeting = array('meet_related_id' => $leadId, 'meet_status' => 3);
            $meetingData['meet_status'] = 4;
            $meetingData['meet_related_id'] = $prospectId;
            $meetingData['meet_contact_id'] = $prospectId;
            $this->common_model->update(TBL_SCHDEULE_MEETING, $meetingData, $whereMeeting);


            unset($leadId);
            $deleteAllFlag = 0;
            $cnt = 0;
            //pagingation
            $searchsortSession = $this->session->userdata('lead_data');
            if (!empty($searchsortSession['uri_segment']))
                $pagingid = $searchsortSession['uri_segment'];
            else
                $pagingid = 0;
            $perpage = !empty($searchsortSession['perpage']) ? $searchsortSession['perpage'] : '10';
            $totalRows = $searchsortSession['total_rows'];
            if ($deleteAllFlag == 1) {
                $totalRows -= $cnt;
                $pagingid * $perpage;
                if ($pagingid * $perpage > $totalRows) {
                    if ($totalRows % $perpage == 0) { // if all record delete
                        $pagingid -= $perpage;
                    }
                }
            } else {
                if ($totalRows % $perpage == 1)
                    $pagingid -= $perpage;
            }

            if ($pagingid < 0)
                $pagingid = 0;
            echo $pagingid;
        }
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Lead Model editdata Page
      @Input 	: id
      @Output	: Get data from id and display in AddEditLead
      @Date     : 13/01/2016
     */

    public function editdata($id) {

        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            //Get Records From CONTACT_MASTER Table
            $redirectLink = $this->input->post('redirect_link');

            //Get Estimate Master data
            $table1 = ESTIMATE_MASTER . ' as ESTM';
            $match1 = " ESTM.status=1 ";
            $fields1 = array("ESTM.subject,ESTM.estimate_id");
            $data['EstimateArray'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            //Get prospect owner form LOGIN table
            $data['prospect_owner'] = $this->common_model->getSystemUserData();

            //Get Records From BRANCH_MASTER Table       
            $table2 = BRANCH_MASTER . ' as bm';
            $match2 = " bm.status=1 ";
            $fields2 = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

            //Get Records From LANGUAGE_MASTER Table
            $table1 = LANGUAGE_MASTER . ' as lan';
            $match1 = "";
            $fields1 = array("lan.language_id,lan.language_name");
            $data['language_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            //Get Selected Records From BRANCH_MASTER Table
            $table22 = BRANCH_MASTER . ' as bm';
            $match22 = "bm.branch_id=(SELECT pm.branch_id from " . $this->prefix . LEAD_MASTER . " as pm WHERE pm.lead_id = " . $id . ") and bm.status=1";
            $fields22 = array("bm.branch_id,bm.branch_name");
            $data['branch_data1'] = $this->common_model->get_records($table22, $fields22, '', '', $match22);

            //Get Records From CAMPAIGN_MASTER Table       
            $table3 = CAMPAIGN_MASTER . ' as cam';
            $match3 = " cam.status=1 ";
            $fields3 = array("cam.campaign_id,cam.campaign_name");
            $data['campaign'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);

            //Get Records From PRODUCT_MASTER Table       
            $table4 = PRODUCT_MASTER . ' as prm';
            $match4 = " prm.is_delete='0' and prm.status=1 ";
            $fields4 = array("prm.product_id,prm.product_name");
            $data['product_data'] = $this->common_model->get_records($table4, $fields4, '', '', $match4);

            //Get Records From COUNTRIES Table       
            $table7 = COUNTRIES . ' as c';
            $match7 = "";
            $fields7 = array("c.country_id,c.country_name,c.country_code");
            $data['country_data'] = $this->common_model->get_records($table7, $fields7, '', '', $match7);

            //Get Records From COMPANY_MASTER Table       
            $table8 = COMPANY_MASTER . ' as cmp';
            $match8 = " cmp.status=1 and cmp.is_delete=0 ";
            $fields8 = array("cmp.company_id,cmp.company_name");
            $data['company_data'] = $this->common_model->get_records($table8, $fields8, '', '', $match8);


            //Get Records From LEAD_MASTER Table with join to get contact detail
            $params['join_tables'] = array(
                LEAD_CONTACTS_TRAN . ' as lct' => 'lct.lead_id=lm.lead_id',
                CONTACT_MASTER . ' as cm' => 'cm.contact_id=lct.contact_id');
            $params['join_type'] = 'left';
            $match = "lm.lead_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
            $table = LEAD_MASTER . ' as lm';

            $fields = array("lm.lead_id,cm.contact_id,cm.contact_name,cm.mobile_number,cm.email,lct.contact_id,lct.primary_contact");
            $data['contact_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);
            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");

            //Get Records From LEAD_MASTER Table
            $table9 = LEAD_MASTER . ' as lm';
            $match9 = "lm.lead_id = " . $id;
            $fields9 = array("lm.lead_id,lm.prospect_auto_id,lm.prospect_name,lm.company_id,"
                . "lm.address1,lm.address2,lm.creation_date,lm.postal_code,lm.city,lm.state,lm.country_id,lm.number_type1,"
                . "lm.phone_no,lm.number_type2,lm.phone_no2,lm.prospect_owner_id,lm.language_id,lm.branch_id,lm.estimate_prospect_worth,lm.prospect_generate,"
                . "lm.campaign_id,lm.description,lm.file,lm.fb,lm.twitter,lm.linkedin,lm.contact_date,lm.status");
            $data['edit_record'] = $this->common_model->get_records($table9, $fields9, '', '', $match9);

            //Get Records From LEAD_CONTACTS_TRAN Table for primary contact
            $table10 = LEAD_CONTACTS_TRAN . ' as pcm';
            $match10 = "pcm.lead_id = " . $id;
            $fields10 = array("pcm.primary_contact");
            $data['opportunity_contact_data'] = $this->common_model->get_records($table10, $fields10, '', '', $match10);

            //Get product data From LEAD_PRODUCTS_TRAN Table 
            $table11 = LEAD_PRODUCTS_TRAN . ' as pt';
            $match11 = "pt.lead_id = " . $id;
            $fields11 = array("pt.product_id");
            $dataopportunity_product_data = $this->common_model->get_records($table11, $fields11, '', '', $match11);
            $product_id = array();
            if (!empty($dataopportunity_product_data)) {
                foreach ($dataopportunity_product_data as $dataopportunity_product_data) {
                    $product_id[] = $dataopportunity_product_data['product_id'];
                }
            }
            $data['opportunity_product_data'] = $product_id;

            //Get files data from FILES_LEAD_MASTER table
            $fields12 = array("*");
            $table12 = FILES_LEAD_MASTER . ' as lf';
            $match12 = 'lf.lead_id=' . $id . '';
            $data['prospect_files'] = $this->common_model->get_records($table12, $fields12, '', '', $match12);

            $data['drag'] = true;
            $data['modal_title'] = $this->lang->line('update_lead');
            $data['submit_button_title'] = $this->lang->line('update_lead');
            $data['lead_view'] = $this->viewname;
            $this->load->view('AddEditLead', $data);
        }

    }

    /*
      @Author    : Seema Tankariya
      @Desc      : Lead Model viewdata Page
      @Input     : Get id
      @Output    : View all data related to id
      @Date      : 13/01/2016
     */

    public function viewdata($id = NULL) {

//Get Records From PROSPECT_MASTER Table with JOIN for get contact,language,country,company data
        if ($id > 0) {
            $_SESSION['current_related_id'] = $id;
            $params['join_tables'] = array(
                COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                LANGUAGE_MASTER . ' as lan' => 'lan.language_id=pm.language_id',
                COUNTRIES . ' as c' => 'c.country_id=cm.country_id',
                CONTACT_MASTER . ' as ct' => 'pm.prospect_owner_id=ct.contact_id');
            $params['join_type'] = 'left';
            $match = " pm.is_delete=0 and pm.lead_id = " . $id;
            $table = LEAD_MASTER . ' as pm';
            $group_by = 'pm.lead_id';
            $fields = array("pm.lead_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
                . "cm.address1,cm.address2,pm.creation_date,cm.postal_code,cm.city,cm.state,cm.country_id,pm.number_type1,"
                . "cm.company_name,pm.phone_no as phone_number,pm.phone_no2,pm.prospect_owner_id,lan.language_name,"
                . "pm.branch_id,pm.estimate_prospect_worth,pm.prospect_generate,pm.campaign_id,pm.description,"
                . "cm.company_name,cm.logo_img,cm.email_id as company_email,c.country_name,ct.contact_name,pm.fb,pm.twitter,pm.linkedin");
            $editRecord = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);

//Get records for contact data LEAD_MASTER join with other table
            $params['join_tables'] = array(
                LEAD_CONTACTS_TRAN . ' as lct' => 'lct.lead_id=lm.lead_id',
                CONTACT_MASTER . ' as cm' => 'cm.contact_id=lct.contact_id');
            $params['join_type'] = 'left';
            $match = "lm.lead_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
            $table = LEAD_MASTER . ' as lm';

            $fields = array("group_concat(cm.contact_name) as contact_name,group_concat(cm.mobile_number) as phone_no,group_concat(cm.email) as email_id");
            $contact_info = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);
            $editRecord[0]['contact_name'] = !empty($contact_info[0]['contact_name']) ? $contact_info[0]['contact_name'] : '';
            $editRecord[0]['email_id'] = !empty($contact_info[0]['email_id']) ? $contact_info[0]['email_id'] : '';
            $editRecord[0]['phone_no'] = !empty($contact_info[0]['phone_no']) ? $contact_info[0]['phone_no'] : '';

//Only get Contact for view Contact tab
            $params['join_tables'] = array(
                LEAD_CONTACTS_TRAN . ' as lct' => 'lct.lead_id=lm.lead_id',
                CONTACT_MASTER . ' as cm' => 'cm.contact_id=lct.contact_id');
            $params['join_type'] = 'left';
            $match = "lm.lead_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
            $table = LEAD_MASTER . ' as lm';
            $fields = array("cm.contact_id,cm.contact_name as name,cm.mobile_number as number,cm.email as email");
            $data['contact_info'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

//Get owner data from LOGIN table
            $data['prospect_owner'] = $this->common_model->getSystemUserData();

//get estimate_prospect_worth value for view account 
            $params['join_tables'] = array(
                ESTIMATE_MASTER . ' as em' => 'em.estimate_id=lm.estimate_prospect_worth');
            $params['join_type'] = 'left';
            $match = "lm.lead_id = " . $id . " and lm.is_delete=0 and lm.status=1 ";
            $table = LEAD_MASTER . ' as lm';
            $fields = array("em.value");
            $data['estimate_prospect_worth'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

            $data['id'] = $id;
            $data['all_records'] = $editRecord;
            if (count($data['all_records'][0]) < 4) {
                redirect('Lead');
            }
            $searchtext = @$this->session->userdata('searchtext');
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            }
            $data['drag'] = true;
            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['header'] = array('menu_module' => 'crm');
            $data['modal_title'] = $this->lang->line('view_account');
            $data['lead_view'] = $this->viewname;
            $data['main_content'] = '/ViewLead';
            $this->parser->parse('layouts/DashboardTemplate', $data);
        } else {
            redirect('Lead');
        }
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Download image
      @Input 	:
      @Output	:
      @Date   : 18/02/2016
     */

    function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = FILES_LEAD_MASTER . ' as CM';
            $params['match_and'] = 'CM.file_id=' . $id . '';
            $prospectFiles = $this->common_model->get_records_array($params);
            if (count($prospectFiles) > 0) {
                $pth = file_get_contents(base_url($prospectFiles[0]['file_path'] . '/' . $prospectFiles[0]['file_name']));
                $this->load->helper('download');
                force_download($prospectFiles[0]['file_name'], $pth);
            }
            redirect($this->viewname);
        }
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Delete image
      @Input 	:
      @Output	:
      @Date   : 18/02/2016
     */

    public function deleteImage($id) {
        //Delete Record From Database
        if (!empty($id)) {
            $match = array("file_id" => $id);
            $fields = array("file_name");
            $imageName = $this->common_model->get_records(FILES_LEAD_MASTER, $fields, '', '', $match);

            if (file_exists($this->config->item('Prospect_img_url') . $imageName[0]['file_name'])) {

                unlink($this->config->item('Prospect_img_url') . $imageName[0]['file_name']);
            }
            $where = array('file_id' => $id);
            if ($this->common_model->delete(FILES_LEAD_MASTER, $where)) {
                echo json_encode(array('status' => 1, 'error' => ''));
                die;
            } else {
                echo json_encode(array('status' => 0, 'error' => 'Someting went wrong!'));
                die;
            }
            unset($id);
        }
    }

    /*
      function exportToCSV() {

      $dbSearch = " pm.status_type=2 and pm.status=1 and is_delete=0 ";
      if ($this->input->post('search_branch_id') != '') {
      $dbSearch .= " and pm.branch_id=" . $this->input->post('search_branch_id');
      }
      if ($this->input->post('search_prospect_owner_id') != '') {
      $dbSearch .= " and pm.prospect_owner_id=" . $this->input->post('search_prospect_owner_id');
      }
      if ($this->input->post('search_status') != '') {
      $dbSearch .= " and pm.status=" . $this->input->post('search_status');
      }
      if ($this->input->post('start_value') != '' && $this->input->post('end_value') != '') {
      $dbSearch .= " and pm.estimate_prospect_worth>=" . $this->input->post('start_value') . ' and pm.estimate_prospect_worth<=' . $this->input->post('end_value');
      }
      if ($this->input->post('search_creation_date') != '' && $this->input->post('creation_end_date') != '') {
      $dbSearch .= " and pm.created_date>=" . $this->input->post('search_creation_date') . ' and pm.created_date<=' . $this->input->post('creation_end_date');
      }

      if ($this->input->post('search_contact_date') != '' && $this->input->post('contact_end_date') != '') {
      $dbSearch .= " and pm.contact_date>=" . $this->input->post('search_contact_date') . ' and pm.contact_date<=' . $this->input->post('contact_end_date');
      }
      $data['prospect_data'] = $this->Lead_model->exportCsvData($dbSearch);
      }
     */

    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('Prospect_img_url') . '/' . $filename, $str);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Lead Model delete_contact_master Page
      @Input 	: input post contact id data
      @Output	: delete from CONTACT_MASTER
      @Date   : 18/02/2016
     */

    public function delete_contact_master() {
        $id = $this->input->post('contact_id');
        //Delete Record From Database
        //$redirectLink=$this->input->get('link');
        if ($id) {

            $where = array('contact_id' => $id);
            $contactData['is_delete'] = 1;
            $this->common_model->update(CONTACT_MASTER, $contactData, $where);
        }
    }

    //Added  By Sanket jayani on 29-09-2016 for Export Leads

    function exportLeads() {
        $this->Lead_model->exportLeads();
        redirect('Lead');
    }

    function importLeads() {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['modal_title'] = $this->lang->line('IMPORT_LEADS');
            $data['submit_button_title'] = $this->lang->line('IMPORT_LEADS');
            $data['sales_view'] = $this->viewname;

            $data['main_content'] = '/importLeads';
            $data['js_content'] = '/loadJsFiles';
            $this->load->view('/importLeads', $data);
            
        }
        
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : Import CSV OR Excel File to import Leads
      @Input    :
      @Output   : import data to lead_master table
      @Date     : 29-03-2016
     */

    function importLeaddata() {

        set_time_limit(0);
        $config['upload_path'] = FCPATH . 'uploads/csv_leads';
        $config['allowed_types'] = '*';
        $config['max_size'] = 40480;

        //  $new_name = time() . "_" . $_FILES["import_file"]['name'];
        $new_name = time() . "_" . str_replace(' ', '_', $_FILES["import_file"]['name']);
        $config['file_name'] = $new_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('import_file')) {
            $msg = $this->upload->display_errors();
            $this->session->set_flashdata('error', $msg);
        } else {
            $file_path = './uploads/csv_leads/' . $new_name;

            //$this->load->library('excel');
            // $objPHPExcel = PHPExcel_IOFactory::load($file_path);

            $file = fopen($file_path, 'r');
            $i = 1;
            while (($line = fgetcsv($file)) !== FALSE) {
                if ($i == 1) {
                    $cell_collection[$i] = $line;
                } else if ($line[1] != '') {
                    $cell_collection[$i] = $line;
                }
                $i++;
            }

            // $cell_collection = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            // 
            //$key_lead_name = array_search('Lead Auto Gen ID', $cell_collection[1]);
            $key_lead_title = array_search('Lead Title', $cell_collection[1]);
            $key_company_name = array_search('Company Name', $cell_collection[1]);
            $key_company_email = array_search('Company Email', $cell_collection[1]);
            $key_company_phone = array_search('Company Phone', $cell_collection[1]);
            $key_campaign_name = array_search('Campaign Name', $cell_collection[1]);
            $key_address1 = array_search('Address1', $cell_collection[1]);
            $key_address2 = array_search('Address2', $cell_collection[1]);
            $key_create_date = array_search('Create Date', $cell_collection[1]);
            $key_postal_code = array_search('Postal Code', $cell_collection[1]);
            $key_city = array_search('City', $cell_collection[1]);
            $key_state = array_search('State', $cell_collection[1]);
            $key_country = array_search('Country', $cell_collection[1]);
            $key_number_type_1 = array_search('Number Type 1', $cell_collection[1]);
            $key_phone_number_1 = array_search('Phone Number 1', $cell_collection[1]);
            $key_number_type_2 = array_search('Number Type 2', $cell_collection[1]);
            $key_phone_number_2 = array_search('Phone Number 2', $cell_collection[1]);
            $key_language = array_search('Language', $cell_collection[1]);
            $key_branch_name = array_search('Branch Name', $cell_collection[1]);
            $key_intrested_product = array_search('Intrested Product', $cell_collection[1]);
            $key_description = array_search('Description', $cell_collection[1]);
            $key_contact_date = array_search('Contact Date', $cell_collection[1]);

            //'Lead Auto Gen ID',
            $chk_file_column = array( 'Lead Title', 'Company Name', 'Company Email', 'Company Phone', 'Campaign Name', 'Address1', 'Address2', 'Create Date', 'Postal Code', 'City', 'State', 'Country', 'Number Type 1', 'Phone Number 1', 'Number Type 2', 'Phone Number 2', 'Language', 'Branch Name', 'Intrested Product', 'Description', 'Contact Date');

            $diff_array = array_diff($chk_file_column, $cell_collection[1]);

            if (!empty($diff_array)) {

                $this->session->set_flashdata('error', lang('WRONG_FILE_FOMRMAT'));
                redirect($this->viewname);
            }

            unset($cell_collection[1]);

            $count_success = 0;
            $count_fail = 0;
            $total_record = count($cell_collection);

            foreach ($cell_collection as $cell) {
                $value_lead_title = trim($cell[$key_lead_title]);
                $value_company_name = trim($cell[$key_company_name]);
                $value_company_email = trim($cell[$key_company_email]);
                $value_company_phone = trim($cell[$key_company_phone]);
                $value_campaign_name = trim($cell[$key_campaign_name]);
                $value_address1 = trim($cell[$key_address1]);
                $value_address2 = trim($cell[$key_address2]);
                $value_create_date = trim($cell[$key_create_date]);
                $value_postal_code = $cell[$key_postal_code];
                $value_city = $cell[$key_city];
                $value_state = trim($cell[$key_state]);
                $value_country = trim($cell[$key_country]);
                $value_number_type_1 = trim($cell[$key_number_type_1]);
                $value_phone_number_1 = trim($cell[$key_phone_number_1]);
                $value_number_type_2 = trim($cell[$key_number_type_2]);
                $value_phone_number_2 = $cell[$key_phone_number_2];
                $value_language = $cell[$key_language];
                $value_branch_name = $cell[$key_branch_name];
                $value_intrested_product = trim($cell[$key_intrested_product]);
                $value_description = $cell[$key_description];
                $value_contact_date = $cell[$key_contact_date];


                $tmp_company_id = $this->common_model->getComapnyIdByName($value_company_name);
                $tmp_branch_id = $this->common_model->getBranchIdByName($value_branch_name);
                $campaign_id = $this->common_model->getBCampaignIdByName($value_campaign_name);
                $country_id = $this->common_model->getCountryIdByName($value_country);

                if ($tmp_branch_id == 0) {
                    $branchData['branch_name'] = $value_branch_name;
                    $branchData['status'] = 1;
                    $branchData['created_date'] = datetimeformat();
                    $branchData['modified_date'] = datetimeformat();
                    $branchId = $this->common_model->insert(BRANCH_MASTER, $branchData);
                } else {
                    $branchId = $tmp_branch_id;
                }

                //remove on && $value_company_email != '' && $branchId != '' && $country_id !='' on 07/07 
                //&& filter_var($value_company_email, FILTER_VALIDATE_EMAIL)
                if ($tmp_company_id == 0 ) {
                    $companyData['company_name'] = $value_company_name;
                    $companyData['country_id'] = $country_id;
                    $companyData['branch_id'] = $branchId;
                    $companyData['email_id'] = $value_company_email;
                    $companyData['phone_no'] = $value_company_phone;
                    $companyData['status'] = 1;
                    $companyData['is_delete'] = 0;
                    $companyData['created_date'] = datetimeformat();
                    $companyData['modified_date'] = datetimeformat();
                    $company_id = $this->common_model->insert(COMPANY_MASTER, $companyData);
                } else {
                    $company_id = $tmp_company_id;
                    
                    if($value_branch_name == '')
                    {
                        $table_grp = COMPANY_MASTER;
                        $fields_grp = array("branch_id");
                        $match_grp = array('company_id' => $tmp_company_id);
                        $branch_arr = $this->common_model->get_records($table_grp, $fields_grp, '', '', $match_grp);
                        
                        if(!empty($branch_arr))
                        {
                            $branchId = $branch_arr[0]['branch_id'];
                        }else
                        {
                            $branchId = 0;
                        }
                        
                    }
                    
                }
                
                $language_arr = getLanguages();
                $language_id = 0 ;
                
                if($value_language != '')
                {
                    foreach ($language_arr as $language)
                    {
                        if(trim(strtolower($value_language)) == $language['language_name'])
                        {
                            $language_id = $language['language_id'];
                            break;
                        }
                    }
                    
                }
                
                
                if (strtolower($value_number_type_1) == "home") {
                    $num_type_1 = 1;
                } else if (strtolower($value_number_type_1) == "mobile") {
                    $num_type_1 = 2;
                } else if (strtolower($value_number_type_1) == "office") {
                    $num_type_1 = 3;
                }else
                {
                    $num_type_1 = '';
                }

                if (strtolower($value_number_type_2) == "home") {
                    $num_type_2 = 1;
                } else if (strtolower($value_number_type_1) == "mobile") {
                    $num_type_2 = 2;
                } else if (strtolower($value_number_type_1) == "office") {
                    $num_type_2 = 3;
                }else
                {
                    $num_type_2 = '';
                }

                if ($campaign_id != 0) {
                    $data['prospect_generate'] = 1;
                } else {
                    $data['prospect_generate'] = 0;
                }
                $data['prospect_auto_id'] = $this->common_model->lead_auto_gen_Id();
                $data['prospect_name'] = $value_lead_title;
                $data['status_type'] = 2;
                $data['company_id'] = $company_id;
                $data['campaign_id'] = $campaign_id;
                $data['address1'] = str_replace(';',',',$value_address1);
                $data['address2'] = str_replace(';',',',$value_address2);
                
                if($value_create_date != '')
                {
                    $creationDate = date('Y-m-d', strtotime($value_create_date));
                }else
                {
                    $creationDate = date('Y-m-d');
                }
                $data['creation_date'] = $creationDate;
                $data['postal_code'] = $value_postal_code;
                $data['city'] = $value_city;
                $data['state'] = $value_state;
                $data['country_id'] = $country_id;
                $data['language_id'] = $language_id;
                $data['number_type1'] = $num_type_1;
                $data['phone_no'] = $value_phone_number_1;
                $data['number_type2'] = $num_type_2;
                $data['phone_no2'] = $value_phone_number_2;
                $data['branch_id'] = $branchId;
                $data['description'] = $value_description;
                $data['contact_date'] = date('Y-m-d', strtotime($value_contact_date));
                $data['is_delete'] = 0;
                $data['created_date'] = datetimeformat();
                $data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $data['prospect_owner_id'] = $this->session->userdata('LOGGED_IN')['ID'];
                $data['status'] = 1;
                
                $table_grp = LEAD_MASTER;
                $fields_grp = array("lead_id");
                $match_grp = array('is_delete' => '0', 'status' => '1', 'prospect_name' => $value_lead_title);
                $leadData_arr = $this->common_model->get_records($table_grp, $fields_grp, '', '', $match_grp);
               
                //&& $value_description != ''
                if (empty($leadData_arr) && $value_lead_title != '' && $company_id != '' && $country_id != 0) {
                    $last_lead_id = $this->common_model->insert(LEAD_MASTER, $data);
                    if ($last_lead_id) {
                        $intrested_product_arr = explode(',', $value_intrested_product);

                        foreach ($intrested_product_arr as $intrested_product) {
                            $product_id = $this->common_model->getIntrestedProductIdByName($intrested_product);

                            if ($product_id != 0) {
                                $productData['lead_id'] = $last_lead_id;
                                $productData['product_id'] = $product_id;
                                $productData['created_date'] = datetimeformat();
                                $productData['status'] = 1;
                                $this->common_model->insert(LEAD_PRODUCTS_TRAN, $productData);
                            }
                        }
                        $count_success++;
                    } else {
                        $count_fail++;
                    }
                } else {
                    $count_fail++;
                    
                }
            }
            
            $msg = "Succesfully Imported ! Total Record : $total_record, Successfully Imported : $count_success, Fail Record : $count_fail ";
            $this->session->set_flashdata('msg', $msg);
        }
       
        redirect('Lead');
    }

    function deletedataSalesOverview() {
        $id = $this->input->get('id');
        //Delete Record From Database
        $params['join_tables'] = array(
            LEAD_CONTACTS_TRAN . ' as lct' => 'lct.lead_id=lm.lead_id',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=lct.contact_id');
        $params['join_type'] = 'left';
        $match = "lm.lead_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
        $table = LEAD_MASTER . ' as lm';

        $fields = array("lct.contact_id");
        $contactIds = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

        for ($i = 0; $i < count($contactIds); $i++) {
            $where = array('contact_id' => $contactIds[$i]['contact_id']);
            $contactData['is_delete'] = 1;
            $this->common_model->update(CONTACT_MASTER, $contactData, $where);
        }

        $data['lead_view'] = $this->viewname;
        if (!empty($id)) {
            $where = array('lead_id' => $id);
            $leadData['is_delete'] = 1;
            $deleteSuceess = $this->common_model->update(LEAD_MASTER, $leadData, $where);

            if ($deleteSuceess) {
                $msg = $this->lang->line('lead_del_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            unset($id);
        }
        redirect('SalesOverview'); 
    }

    function send_email_view($leadId = NULL) 
    {
        
        if (!$this->input->is_ajax_request()) 
        {
                        exit('No direct script access allowed');
        }else
        {
            $table = LEAD_MASTER . ' as cm';
            $match = "cm.lead_id = " . $leadId;
            $fields = array("cm.lead_id,cm.prospect_auto_id,cm.prospect_name,cm.company_id");
            $data['contact_record'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $company_id = $data['contact_record'][0]['company_id'];

            $table_prospect = PROSPECT_MASTER . ' as pm';
            //$prospect_match = "pm.is_delete=0 AND pm.status=1 AND pm.prospect_id=".$prospectId;
            $prospect_match = "pm.is_delete=0 AND pm.status=1 AND pm.status_type =1";
            $prospect_fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.company_id,pm.status_type");
            $data['prospect_data'] = $this->common_model->get_records($table_prospect, $prospect_fields, '', '', $prospect_match);

            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");

            //$data['contact_record'] = $data['prospect_data'];

            $data['user_data'] = $this->common_model->getSystemUserData();
            $data['email_template_data'] = $this->common_model->getEmailTemplateData();
            //$data['comapny_contact_data'] = $this->getAllContactByCompany($data['contact_record'][0]['company_id']);
            $data['comapny_contact_data'] = $this->getAllContactByCompany();
            $data['company_data'] = $this->common_model->getAllCompanyData();
            //$company_email = $this->common_model->getCompanyEmailFromComapanyid($data['contact_record'][0]['company_id']);
            //for company email
            $prospect_contact_arr = $this->getLeadContact($leadId);
            $prospect_company_email = $this->common_model->getCompanyEmailFromComapanyid($company_id);

            $receipent_arr = [];
            $i = 0;
            foreach ($prospect_contact_arr as $prospect_contact) {
                $receipent_arr[$i]['contact_email'] = $prospect_contact['email'];
                $receipent_arr[$i]['contact_name'] = $prospect_contact['contact_name'];
                $i++;
            }

            //  $company_email[0] = array('contact_email' => $prospect_company_email['email_id'], 'contact_name' => $prospect_company_email['company_name']);
            //$new_receipenet_arr = array_merge($receipent_arr, $company_email);

            $new_receipenet_arr = $prospect_contact_arr;
            //for gettig lead primary contact

            $data['contact_data'] = $prospect_contact_arr;

            if (count($data['contact_data']) > 0) {
                $data['contact_id'] = $data['contact_data'][0]['contact_id'];
            }
            $data['company_email'] = $prospect_company_email['email_id'];
            $data['prospect_receipent_array'] = $new_receipenet_arr;

            $data['modal_title'] = 'Email Prospect';
            $data['main_content'] = '/SendEmail';
            $this->load->view('Account/SendEmail', $data);

        }
        
    }

    function getLeadContact($leadId) {
        $table1 = LEAD_CONTACTS_TRAN . ' as cm,' . CONTACT_MASTER . ' as ad';
        $match1 = "cm.status=1 AND ad.status=1 AND ad.is_delete=0 AND cm.contact_id=ad.contact_id AND cm.lead_id=" . $leadId;
        $fields1 = array("ad.contact_id,ad.contact_name,ad.email");
        $order_by = 'ad.contact_name';
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1, '', '', '', $order_by, 'asc');

        return $user_data;
    }

    function getAllContactByCompany() {
        $table1 = CONTACT_MASTER . ' as cm';
        //$match1 = "cm.status=1 AND cm.is_delete=0 AND cm.company_id=".$company_id;
        $match1 = "cm.status=1 AND cm.is_delete=0 ";
        $fields1 = array("cm.contact_id,cm.contact_name");
        $order_by = 'cm.contact_name';
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1, '', '', '', $order_by, 'asc');

        return $user_data;
    }

    function getCompanyDataById() {
        $company_id = $this->input->post('company_id');
        $previewProducts['fields'] = ['cm.phone_no,cm.branch_id,bm.branch_name,cm.address1,cm.address2,cm.city,cm.state,cm.country_id,cm.postal_code,cm.logo_img'];
        $previewProducts['table'] = COMPANY_MASTER . ' as cm';
        $previewProducts['join_tables'] = array(BRANCH_MASTER . ' AS bm' => 'bm.branch_id=cm.branch_id',);
        $previewProducts['join_type'] = "left";
        $previewProducts['match_and'] = 'cm.company_id=' . $company_id;
        $data['company_data'] = $this->common_model->get_records_array($previewProducts);
        echo json_encode($data['company_data'][0]);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get address
      @Input 	: inoput id
      @Output	: get address and show in map
      @Date     : 14/06/2016
     */

    function navigation($id = null) {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            if ($id > 0)
            {

                $_SESSION['current_related_id'] = $id;

                //Get Records From PROSPECT_MASTER Table with JOIN
                $data = array();
                $params['join_tables'] = array(
                    COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                    COUNTRIES . ' as c' => 'c.country_id=pm.country_id');
                $params['join_type'] = 'left';
                $match = "pm.lead_id = " . $id;
                $table = LEAD_MASTER . ' as pm';
                $groupBy = 'pm.lead_id';
                $fields = array("pm.lead_id,pm.company_id,"
                    . "pm.address1,pm.address2,pm.city,pm.state,c.country_name");
                $edit_record = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $groupBy);

                $companyId = $edit_record[0]['company_id'];

                $params['join_tables'] = array(
                    COUNTRIES . ' as c' => 'c.country_id=cm.country_id');
                $params['join_type'] = 'left';
                $table13 = COMPANY_MASTER . ' as cm';
                $match13 = "cm.company_id = " . $companyId;
                $fields13 = array("cm.company_id,cm.address1,cm.address2,cm.city,cm.state,c.country_name");
                $data['company_record'] = $this->common_model->get_records($table13, $fields13, $params['join_tables'], $params['join_type'], $match13);

                $data['id'] = $id;
                $data['all_records'] = $edit_record;

                if (count($data['all_records'][0]) < 4) {
                    show_404();
                }
                //for breadcrumbs
                $searchtext = @$this->session->userdata('searchtext');
                if (!empty($searchtext)) {
                    $data['searchtext'] = $searchtext;
                }

                // $this->breadcrumbs->push($editRecord[0]['prospect_name'], ' ');
                $data['header'] = array('menu_module' => 'crm');
                $data['modal_title'] =$this->lang->line('view_on_map');
                $data['opportunity_view'] = $this->viewname;
                $data['drag'] = true;
                //$data['main_content'] = '/ViewOpportunity';
                $this->load->view('Opportunity/navigation', $data);
            } else {
                show_404();
            }
        }
        
    }

}
