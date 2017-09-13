<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

    public $viewname;

    function __construct() {
        parent::__construct();
        if(checkPermission('Account','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->prefix = $this->db->dbprefix;
        $this->viewname = $this->uri->segment(1);
        $this->load->model('Account_model');
        $this->load->library('breadcrumbs');
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model Index Page
      @Input 	:
      @Output	:
      @Date     : 13/01/2016
     */

    public function index() {
        $this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('sales_overview'), 'SalesOverview');
        $this->breadcrumbs->push(lang('accounts'), ' ');


        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('prospect_data');
        }

        $searchsort_session = $this->session->userdata('prospect_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
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
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
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
//Get Records  prospect_owner  from LOGIN Table           
        $data['prospect_owner'] = $this->common_model->getSystemUserData();

//Get Records From BRANCH_MASTER Table       
        $tableBranchMaster = BRANCH_MASTER . ' as bm';
        $matchBranchMaster = " bm.status=1 ";
        $fieldsBranchMaster = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($tableBranchMaster, $fieldsBranchMaster, '', '', $matchBranchMaster);

//Join PROSPECT_MASTER with OPPORTUNITY_REQUIREMENT_CONTACTS,OPPORTUNITY_REQUIREMENT for get all related like contact data
        $params['join_tables'] = array(
            OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
            ESTIMATE_MASTER . ' as em' => 'em.estimate_id=pm.estimate_prospect_worth',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id ');
        $params['join_type'] = 'left';
        $tableJoinProspect_master = PROSPECT_MASTER . ' as pm';
        $group_by = 'pm.prospect_id';
        $fieldsJoinProspect_master = array("pm.prospect_id,pm.prospect_name,pm.prospect_auto_id, pm.status,pm.status_type,pm.creation_date,(select count(cm.contact_id) from " . $this->prefix . CONTACT_MASTER . " as cm where cm.is_delete=0 and cm.status=1 and cm.company_id=pm.company_id group by pm.company_id) as contact_count,(select cm.contact_name from " . $this->prefix . CONTACT_MASTER . " as cm  where cm.primary_contact=1 and cm.is_delete=0 and cm.status=1 and cm.company_id=pm.company_id group by pm.prospect_id) as contact_name");
        $where = "pm.is_delete=0 and pm.status_type=2 and pm.status=1 ";

//If search data are there take post value and update query
        $data['branch_show_id'] = "";
        if ($this->input->post('search_branch_id') != "") {
            $data['branch_show_id'] = $this->input->post('search_branch_id');
            $where.=' and pm.branch_id=' . $data['branch_show_id'];
        }
        $data['prospect_show_id'] = "";
        if ($this->input->post('search_prospect_owner_id') != "") {
            $data['prospect_show_id'] = $this->input->post('search_prospect_owner_id');
            $where.=' and pm.prospect_owner_id=' . $data['prospect_show_id'];
        }
        $data['status_show'] = "";
        if ($this->input->post('search_status') != "") {
            $data['status_show'] = $this->input->post('search_status');
            $where.=' and pm.status=' . $data['status_show'];
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
            $where.=' and pm.creation_date>="' . $data['search_creation_date_show'] . '"';
        }
        $data['creation_end_date_show'] = "";
        if ($this->input->post('creation_end_date') != "") {
            $data['creation_end_date_show'] = date_format(date_create($this->input->post('creation_end_date')), 'Y-m-d');
            $where.=' and pm.creation_date<="' . $data['creation_end_date_show'] . '"';
        }
        $data['search_contact_date_show'] = "";
        if ($this->input->post('search_contact_date') != "") {
            $data['search_contact_date_show'] = date_format(date_create($this->input->post('search_contact_date')), 'Y-m-d');
            $where.=' and pm.contact_date>="' . $data['search_contact_date_show'] . '"';
        }
        $data['contact_end_date_show'] = "";
        if ($this->input->post('contact_end_date') != "") {
            $data['contact_end_date_show'] = date_format(date_create($this->input->post('contact_end_date')), 'Y-m-d');
            $where.=' and pm.contact_date<="' . $data['contact_end_date_show'] . '"';
        }



        if (!empty($searchtext)) {
//If have any search text
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $clientSince = date('Y-m-d', strtotime($searchtext));
            $whereSearch = '(pm.prospect_name LIKE "%' . $searchtext . '%" OR pm.prospect_auto_id LIKE "%' . $searchtext . '%" OR pm.status_type LIKE "%' . $searchtext . '%" OR pm.creation_date LIKE "%' . $clientSince . '%"  OR cm.contact_name LIKE "%' . $searchtext . '%")';
            $data['prospect_data'] = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $whereSearch);
           
            $config['total_rows'] = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $whereSearch);
            //Get Records From PROSPECT_MASTER Table total account   

            $totalAccount = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $whereSearch);
        } else {
//Not have any search input
            $data['prospect_data'] = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where);
            $config['total_rows'] = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
            //Get Records From PROSPECT_MASTER Table total account   

            $totalAccount = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
        }

        //check  total account > 0 otherwise set total account = 0
        if ($totalAccount) {
            $data['total_account'] = $totalAccount;
        } else {
            $data['total_account'] = '0';
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
        $this->session->set_userdata('prospect_data', $sortsearchpage_data);
        $data['drag'] = true;
        $data['account_view'] = $this->viewname;
        $data['sales_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'crm');

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view('AjaxAccountList', $data);
        } else {
            $data['main_content'] = '/' . $this->viewname;
            $this->parser->parse('layouts/DashboardTemplate', $data);
        }
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model Add Page
      @Input    :
      @Output	: Show Modal For Add Account
      @Date     : 13/01/2016
     */
    /*     * ************************

      1)When click on add new client take data form related table and pass to AddEditAccount page

     * ************************ */

    public function add() {
        
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data = array();
            $data['account_view'] = $this->viewname;
            $data['main_content'] = '/Account';
            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['modal_title'] = $this->lang->line('create_new_client');
            $data['submit_button_title'] = $this->lang->line('create_client');

            //By default owner selected as login user
            $prospectOwner[] = array('prospect_owner_id' => $this->session->userdata('LOGGED_IN')['ID']);
            $data['edit_record'] = $prospectOwner;
            //Generate Client auto id 
            $data['pros_auto_id'] = $this->common_model->client_auto_gen_Id();

            //Get Records From LOGIN Table
            $data['prospect_owner'] = $this->common_model->getSystemUserData();

            //Get Records From BRANCH_MASTER Table       
            $tableBranchMaster = BRANCH_MASTER . ' as bm';
            $matchBranchMaster = " bm.status=1 ";
            $fieldsBranchMaster = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($tableBranchMaster, $fieldsBranchMaster, '', '', $matchBranchMaster);

            //Get Records From LANGUAGE_MASTER Table
            $table1 = LANGUAGE_MASTER . ' as lan';
            $match1 = "";
            $fields1 = array("lan.language_id,lan.language_name");
            $data['language_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            //Get Records From CAMPAIGN_MASTER Table       
            $tableCampaignMaster = CAMPAIGN_MASTER . ' as cam';
            $matchCampaignMaster = " cam.status=1 ";
            $fieldsCampaignMaster = array("cam.campaign_id,cam.campaign_name");
            $data['campaign'] = $this->common_model->get_records($tableCampaignMaster, $fieldsCampaignMaster, '', '', $matchCampaignMaster);

            //Get Records From PRODUCT_MASTER Table       
            $tableProductMaster = PRODUCT_MASTER . ' as prm';
            $matchProductMaster = " prm.is_delete='0' and prm.status=1 ";
            $fieldsProductMaster = array("prm.product_id,prm.product_name");
            $data['product_data'] = $this->common_model->get_records($tableProductMaster, $fieldsProductMaster, '', '', $matchProductMaster);

            //Get Records From COUNTRIES Table       
            $tableCountries = COUNTRIES . ' as c';
            $matchCountries = "";
            $fieldsCountries = array("c.country_id,c.country_name,c.country_code");
            $data['country_data'] = $this->common_model->get_records($tableCountries, $fieldsCountries, '', '', $matchCountries);

            //Get Records From COMPANY_MASTER Table       
            $tableCompanyMaster = COMPANY_MASTER . ' as cmp';
            $matchCompanyMaster = " cmp.status=1 and cmp.is_delete=0 ";
            $fieldsCompanyMaster = array("cmp.company_id,cmp.company_name");
            $data['company_data'] = $this->common_model->get_records($tableCompanyMaster, $fieldsCompanyMaster, '', '', $matchCompanyMaster);

            //Get Estimate Master data
            $table1 = ESTIMATE_MASTER . ' as ESTM';
            $match1 = " ESTM.status=1 ";
            $fields1 = array("ESTM.subject,ESTM.estimate_id");
            $data['EstimateArray'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            //Get All Data and view in AddEditAccount Page  
            $data['drag'] = true;
            $this->load->view('AddEditAccount', $data);

        }
        
    }

    /*     * ************************************************************************************************

     * Check if have id then update data otherwise insertdata

      1)  When click on Create Client or Update Client this function insert data in related table.
      2)  Get input post data .
      3)  If input post branch name exist in BRANCH_MASTER then take branch id.
      4)  If input post branch name not exist in BRANCH_MASTER then insert that branch in BRANCH_MASTER
      and get inserted branch id.
      5)  If input post company select from list then get company_id.
      6)  If input post company is Add New Comapny then get company data and insert in COMPANY_MASTER
      and get inserted id.
      7)  If company not have an account create account with status type=2
      8)  If have client owner then get detail and send email to that client.
      9)  If update client name also update in ESTIMATE_CLIENT table.
      10) If have input post campaign then insert  in TBL_CAMPAIGN_CONTACT and update then update it.
      11) Insert all files and documents in FILES_SALES_MASTER.
      12) If have any deleted file or document then delete form FILES_SALES_MASTER and unlink.
      13) Insert description in OPPORTUNITY_REQUIREMENT table
      14) If have deleted contact then delete from CONTACT_MASTER
      15) Insert contact data in CONTACT_MASTER and  relational table is OPPORTUNITY_REQUIREMENT_CONTACTS
      16) If have input post produts then insert in PROSPECT_PRODUCTS_TRAN table

     * ************************************************************************************************* */

    public function insertdata() {

        $id = '';
        $opportunityRequirementId = '';
        //Get input post prospect_id if have
        if ($this->input->post('prospect_id')) {
            $id = $this->input->post('prospect_id');
        }
        //Get input post requirement_id if have
        if ($this->input->post('requirement_id')) {
            $opportunityRequirementId = $this->input->post('requirement_id');
        }

        $redirectLink = $this->input->post('redirect_link');
        if (strpos($redirectLink, 'lostClient') !== false) {

            $redirectLink = base_url() . "Account/lostClient";
        } elseif (strpos($redirectLink, 'Account/viewdata') !== false) {
            $redirectLink = $redirectLink;
        } elseif (strpos($redirectLink, 'Account/viewLostClient') !== false) {
            $redirectLink = $redirectLink;
        } else {
            $redirectLink = base_url() . "Account";
        }
        if (!validateFormSecret()) {

            redirect($redirectLink); //Redirect On Listing page
        }

        $data = array();
        $data['account_view'] = $this->viewname;
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $prospectData['prospect_name'] = strip_slashes(trim($this->input->post('account_name')));
        $prospectData['address1'] = strip_slashes($this->input->post('address1'));
        $prospectData['address2'] = strip_slashes($this->input->post('address2'));
        $prospectData['creation_date'] = date_format(date_create($this->input->post('creation_date')), 'Y-m-d');
        $prospectData['postal_code'] = strip_slashes($this->input->post('postal_code'));
        $prospectData['city'] = strip_slashes($this->input->post('city'));
        $prospectData['state'] = strip_slashes($this->input->post('state'));
        $prospectData['number_type1'] = $this->input->post('number_type1');
        $prospectData['phone_no'] = $this->input->post('phone_no1_account');
        $prospectData['number_type2'] = $this->input->post('number_type2');
        $prospectData['phone_no2'] = $this->input->post('phone_no2_account');
        $prospectData['language_id'] = $this->input->post('language_id');
        $prospectData['fb'] = $this->input->post('fb');
        $prospectData['twitter'] = $this->input->post('twitter');
        $prospectData['linkedin'] = $this->input->post('linkedin');
        $prospectData['credit_limit'] = $this->input->post('credit_limit');
        $prospect_owner_id = $this->input->post('prospect_owner_id');
        $prospectData['prospect_owner_id'] = $prospect_owner_id;
        $branchName = strip_slashes($this->input->post('branch_id'));
        
    //Get Branch id From BRANCH_MASTER Table       
        $table22 = BRANCH_MASTER . ' as bm';
        $match22 = "bm.branch_name='" . addslashes($branchName) . "' and status=1 ";
        $fields22 = array("bm.branch_name, bm.branch_id");
        $branchRecord = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        if ($branchRecord) {
            $prospectData['branch_id'] = $branchRecord[0]['branch_id'];
        } else {
            $branchData['branch_name'] = $branchName;
        }
        if (count($branchRecord) == 0) {
            //INSERT Branch
            $branchData['created_date'] = datetimeformat();
            $branchData['modified_date'] = datetimeformat();
            $branchData['status'] = 1;
            $branch_id = $this->common_model->insert(BRANCH_MASTER, $branchData);
            $prospectData['branch_id'] = $branch_id;
        }
        
    //Get input post data for COMPANY_MASTER if want to add new company
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
                $companyData['company_name'] = trim($this->input->post('company_name'));
                $companyData['email_id'] = $this->input->post('email_id_company');
                $companyData['website'] = $this->input->post('website');
                $companyData['company_id_data'] = $this->input->post('company_id_data');
                $companyData['reg_number'] = $this->input->post('com_reg_number');
                $companyData['branch_id'] = $prospectData['branch_id'];
                $companyData['address1'] = strip_slashes($this->input->post('address1'));
                $companyData['address2'] = strip_slashes($this->input->post('address2'));
                $companyData['postal_code'] = strip_slashes($this->input->post('postal_code'));
                $companyData['city'] = strip_slashes($this->input->post('city'));
                $companyData['state'] = strip_slashes($this->input->post('state'));
                $companyData['country_id'] = $this->input->post('country_id');
                $companyData['status'] = 1;
                $companyData['is_delete'] = 0;
                $companyData['phone_no'] = $this->input->post('phone_no_company');
                $companyData['created_date'] = datetimeformat();
                $companyData['modified_date'] = datetimeformat();

                if (($_FILES['logo_image']['name']) != NULL) {
                    $config = array(
                        'upload_path' => FCPATH . "uploads/company/",
                        'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
                        'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    );
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('logo_image')) {
                        $fileData = array('upload_data' => $this->upload->data());
                        foreach ($fileData as $file) {
                            $companyData['logo_img'] = $file['file_name'];
                        }
                    } else {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                    }
                }
                $prospectData['company_id'] = $this->common_model->insert(COMPANY_MASTER, $companyData);
            } //Else Select From Company List
            else {
                $company_id = $this->input->post('company_id');
                $prospectData['company_id'] = $company_id;
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

        $table_master = COMPANY . ' as cm';
        $match_master = "cm.company_name='" . addslashes($company_name) . "' and cm.status=1 ";
        $fields_master = array("cm.*");
        $company_master = $this->common_model->get_records_data($table_master, $fields_master, '', '', $match_master);

        if (count($company_master) == 0) {
            $contactdata['company_id'] = $this->common_model->insert_data(COMPANY, $companyData);
        } else {
            $where = array('company_id' => $company_master[0]['company_id']);
            $this->common_model->update_data(COMPANY, $companyData, $where);
        }

        /* end */


        //check this company account exist or not otherwise make an account
        $table = PROSPECT_MASTER . ' as pm';
        $match = "pm.company_id = " . $prospectData['company_id'] . " and status_type=2 and pm.is_delete=0";
        $fields = array("pm.company_id,pm.prospect_id");
        $companyExist = $this->common_model->get_records($table, $fields, '', '', $match);
        if (empty($companyExist)) {
            //get company data from company id
            $table = COMPANY_MASTER . ' as cm';
            $match = "cm.company_id = " . $prospectData['company_id'];
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

            $opportunityRequirementAccount['requirement_description'] = $this->input->post('description', FALSE);
            $opportunityRequirementAccount['modified_date'] = datetimeformat();
            $opportunityRequirementAccount['prospect_id'] = $CompanyAccountId;
            $opportunityRequirementAccount['created_date'] = datetimeformat();
            $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $opportunityRequirementAccount);
        }
        $prospectData['country_id'] = $this->input->post('country_id');
        $prospectData['estimate_prospect_worth'] = $this->input->post('estimate_prospect_worth');

        $prospect_generate = '0';
        if ($this->input->post('prospect_generate') == 'on') {
            $prospect_generate = '1';
            if ($this->input->post('campaign_id')) {
                $prospectData['campaign_id'] = $this->input->post('campaign_id');
            }
        } else {
            $prospectData['campaign_id'] = '';
        }

        $prospectData['prospect_generate'] = $prospect_generate;


        if ($this->input->post('contact_date')) {
            $prospectData['contact_date'] = date_format(date_create($this->input->post('contact_date')), 'Y-m-d');
        } else {
            $prospectData['contact_date'] = '';
        }
        $prospectData['status'] = 1;
        //status_type=3 for Account status_type=3 for client && status_type=4 for Lost Client

        if (strpos($redirectLink, 'lostClient') !== false) {
            $prospectData['status_type'] = 4;
        } else if (strpos($redirectLink, 'viewLostClient') !== false) {
            $prospectData['status_type'] = 4;
        } else if (strpos($redirectLink, 'viewdata') !== false) {
            $prospectData['status_type'] = 3;
        }
        if ($id) {
            $prospectData['status_type'] = 2;
        } else {
            $prospectData['status_type'] = 3;
        }

        $prospectData['modified_date'] = datetimeformat();
        $prospectData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        //Update Record in Database
        if ($id) {

            /*
             * gets current data of current opportunity 
             */
            $params['join_tables'] = array(
                COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                LANGUAGE_MASTER . ' as lan' => 'lan.language_id=pm.language_id',
                COUNTRIES . ' as c' => 'c.country_id=pm.country_id',
                CONTACT_MASTER . ' as ct' => 'pm.prospect_owner_id=ct.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id;
            $table = PROSPECT_MASTER . ' as pm';
            $group_by = 'pm.prospect_id';
            $fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
                . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.city,pm.state,pm.country_id,pm.number_type1,"
                . "pm.phone_no as phone_number,pm.phone_no2,pm.prospect_owner_id,lan.language_name,"
                . "pm.branch_id,pm.estimate_prospect_worth,pm.prospect_generate,pm.campaign_id,pm.description,"
                . "cm.company_name,cm.logo_img,c.country_name,ct.contact_name");
            $currentProspectData = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);
            if (count($currentProspectData) > 0) {
                $prospectOwnerOldId = $currentProspectData[0]['prospect_owner_id'];
            }

            /*
             * will sends email if prospect owner gets changed
             */
            if ($prospectOwnerOldId != $prospect_owner_id) {


                $umatch = "login_id =" . $prospect_owner_id;
                $ufields = array("concat(firstname,' ',lastname) as fullname,email");
                $newProspectOwnerData = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);

                if (count($newProspectOwnerData) > 0) {
                    $prospectOwnerEmail = $newProspectOwnerData[0]['email'];
                    $prospectOwnerName = $newProspectOwnerData[0]['fullname'];
                    $prospectName = $prospectData['prospect_name'];
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
                        $url = base_url('Account/viewdata/' . $id);
                        $replace = array(
                            'USER' => $prospectOwnerName,
                            'OPPORTUNITY_NAME' => $prospectName,
                            'LINK' => "<a href='" . $url . "' title='view opportunity'>View</a>",
                            'TYPE' => ' Client'
                        );
                        $format = $template[0]['body'];
                        $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
                        $subject = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $template[0]['subject']))));

                        send_mail($prospectOwnerEmail, $subject, $body);
                    }
                }
            }

            $where = array('prospect_id' => $id);
            //Get PROSPECT_MASTER Information client name and update also in estimate client
            $whereEstimate = array('prospect_id' => $id, 'client_type' => 'client');
            $estimateData['client_name'] = $prospectData['prospect_name'];
            $this->common_model->update(ESTIMATE_CLIENT, $estimateData, $whereEstimate);
            $successUpdate = $this->common_model->update(PROSPECT_MASTER, $prospectData, $where);
            if ($this->input->post('campaign_id')) {
                $table = TBL_CAMPAIGN_CONTACT . ' as cc';
                $fields = 'campaign_related_id';
                $match = 'campaign_related_id=' . $id . ' and campaign_status=2 and is_delete=0 ';
                $getCampaignData = $this->common_model->get_records($table, $fields, '', '', $match);
                if (!empty($getCampaignData)) {
                    $where = array('campaign_related_id' => $id, 'campaign_status' => 2);
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $where);
                } else {
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $campaignData['campaign_related_id'] = $id;
                    $campaignData['campaign_status'] = 2;
                    $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                }
            }
            if ($successUpdate) {
                $msg = $this->lang->line('client_update_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
        //Insert Record in Database
        else {
            $prospectData['prospect_auto_id'] = $this->input->post('prospect_auto_id');
            $prospectData['created_date'] = datetimeformat();
            $prospectData['won_lost_date'] = datetimeformat();
            $prospectId = $this->common_model->insert(PROSPECT_MASTER, $prospectData);

            //insert campaign also in campaign_contact
            if ($this->input->post('campaign_id')) {
                $campaignData['campaign_id'] = $this->input->post('campaign_id');
                $campaignData['campaign_related_id'] = $prospectId;
                $campaignData['campaign_status'] = 2;
                $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
            }
            if ($prospectId) {
                $msg = $this->lang->line('client_add_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }


        //Data in COMPANY_ACCOUNTS_TRAN Insert
        if ($id) {
            //   Update lead data in COMPANY_ACCOUNTS_TRAN  
            $where = array('client_id' => $id, 'status_type' => 2);
            $companyAccountData['company_id'] = $prospectData['company_id'];
            $this->common_model->update(COMPANY_ACCOUNTS_TRAN, $companyAccountData, $where);
        } else {
            //   Insert lead data in COMPANY_ACCOUNTS_TRAN      
            $companyAccountData['company_id'] = $prospectData['company_id'];
            $companyAccountData['client_id'] = $prospectId;
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

        $uploadData = uploadImage('prospect_files', prospect_upload_path, $data['account_view']);
        $Marketingfiles = array();

        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $fileData = implode(",", $file2);
        } else {
            $fileData = '';
        }

        $compaigndata['file_name'] = $fileData;

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
                        $prospectFiles[] = ['file_name' => $galleryFilesData[$i], 'file_path' => $galleryPath[$i], 'prospect_id' => $id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    } else {
                        $prospectFiles[] = ['file_name' => $galleryFilesData[$i], 'file_path' => $galleryPath[$i], 'prospect_id' => $prospectId, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    }
                }
            }
        }

        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                if ($id) {
                    $prospectFiles[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'prospect_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                } else {
                    $prospectFiles[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'prospect_id' => $prospectId, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }

        if (count($prospectFiles) > 0) {
            if ($id) {
                $where = array('prospect_id' => $id);
            } else {
                $where = array('prospect_id' => $prospectId);
            }

            if (!$this->common_model->insert_batch(FILES_SALES_MASTER, $prospectFiles)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }

        /**
         * SOFT DELETION CODE STARTS FOR DELETE FILE
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
            $this->common_model->delete(FILES_SALES_MASTER, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        $opportunityRequirement['requirement_description'] = $this->input->post('description', FALSE);
        $opportunityRequirement['modified_date'] = datetimeformat();

        if ($id) {

            $where = array('prospect_id' => $id);
            $successUpdate = $this->common_model->update(OPPORTUNITY_REQUIREMENT, $opportunityRequirement, $where);
        } else {

            $opportunityRequirement['prospect_id'] = $prospectId;
            $opportunityRequirement['created_date'] = datetimeformat();
            $returnOpportunityId = $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $opportunityRequirement);
        }

        /**
         * SOFT DELETION CODE STARTS FOR CONTACT DELETE
         */
        $softDeleteContactsArr = $this->input->post('softDeletedContacts');
        if (count($softDeleteContactsArr) > 0) {
            $dlStr = implode(',', $softDeleteContactsArr);
            $contact_data['is_delete'] = 1;
            $this->common_model->update(CONTACT_MASTER, $contact_data, 'contact_id IN(' . $dlStr . ')');
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
        $prospectContactsEmailId = array();
        $prospectContactsEmailId = $this->input->post('email_id');
        $prospectContactsPhoneNo = array();
        $prospectContactsPhoneNo = $this->input->post('phone_no');

//Update Record in Database
        if ($id) {
            $prospectContactsTran['primary_contact'] = "0";
            for ($prospectContactsCount = 0; $prospectContactsCount < count($prospectContactsName); $prospectContactsCount++) {
                if ($primaryContact[$prospectContactsCount] > 0) {
                    $prospectContactsTran['primary_contact'] = "1";
                } else {
                    $prospectContactsTran['primary_contact'] = "0";
                }

                if ($primaryContact[$prospectContactsCount] > 0) {
                    $contactMasterData['primary_contact'] = "1";
                } else {
                    $contactMasterData['primary_contact'] = "0";
                }
                
                $prospectContactsTran['modified_date'] = datetimeformat();
                $prospectContactsTran['status'] = 1;
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
                    $this->common_model->update(OPPORTUNITY_REQUIREMENT_CONTACTS, $prospectContactsTran, $where);
                    if ($this->input->post('campaign_id')) {
                        $where = array('campaign_related_id' => $contactId[$prospectContactsCount], 'campaign_status' => 1);
                        $campaignData['campaign_id'] = $this->input->post('campaign_id');
                        $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $where);
                    }
                } else {
                    $prospectContactsTran['prospect_id'] = $id;
                //Check primary for this company exist or not
                    $table23 = CONTACT_MASTER . ' as cm';
                    $match23 = "cm.company_id='" . $prospectData['company_id'] . "' and cm.is_delete=0
                        and primary_contact=1 and cm.status=1 ";
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
                    $prospectContactsTran['requirement_id'] = $opportunityRequirementId;
                    $this->common_model->insert(OPPORTUNITY_REQUIREMENT_CONTACTS, $prospectContactsTran);
                    if ($this->input->post('campaign_id')) {
                        $campaignData['campaign_id'] = $this->input->post('campaign_id');
                        $campaignData['campaign_related_id'] = $contactId;
                        $campaignData['campaign_status'] = 1;
                        $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                    }
                }
            }
        }
        //Insert Record in Database
        else {
            $prospectContactsTran['primary_contact'] = "0";
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
                $match23 = "cm.company_id='" . $prospectData['company_id'] . "' and cm.is_delete=0 and primary_contact=1 and cm.status=1 ";
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
                $prospectContactsTran['requirement_id'] = $returnOpportunityId;
                $prospectContactsTran['contact_id'] = $contactId;
                $prospectContactsTran['prospect_id'] = $prospectId;
                $prospectContactsTran['status'] = 1;
                $prospectContactsTran['created_date'] = datetimeformat();
                $prospectContactsTran['modified_date'] = datetimeformat();
                $this->common_model->insert(OPPORTUNITY_REQUIREMENT_CONTACTS, $prospectContactsTran);
                if ($this->input->post('campaign_id')) {
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $campaignData['campaign_related_id'] = $contactId;
                    $campaignData['campaign_status'] = 1;
                    $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                }
            }
        }
        if ($this->input->post('interested_products')) {
            $productData['status'] = 1;
            $productData['created_date'] = datetimeformat();
            $productData['modified_date'] = datetimeformat();
//Delete Record in Database
            if ($id) {

                $where = array('prospect_id' => $id);
                $this->common_model->delete(PROSPECT_PRODUCTS_TRAN, $where);
            } 
            $selectedProducts = $this->input->post('interested_products');
            $nproducts = count($selectedProducts);

            for ($i = 0; $i < $nproducts; $i++) {
                $productData['product_id'] = $selectedProducts[$i];
                //Insert Record in Database
                if ($id) {
                    $productData['prospect_id'] = $id;
                    $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $productData);
                } else {
                    $productData['prospect_id'] = $prospectId;
                    $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $productData);
                }
            }
        }
        redirect($redirectLink); //Redirect On Listing page
    }

    /*
      @Author    : Seema Tankariya
      @Desc      : Account Model deletedata Page
      @Input     : Get id
      @Output    : Set is_delete=1 and Display Message if Success else Error
      @Date      : 13/01/2016
     */

    public function deletedata() {

        $data['account_view'] = $this->viewname;
        $id = $this->input->get('id');

        //Delete Record From Database
        if (!empty($id)) {
            
        //Get data from prospect_master data
            $table23 = PROSPECT_MASTER . ' as pm';
            $match23 = "pm.prospect_id='" . $id . "' and pm.is_delete=0  and pm.status_type=2 ";
            $fields23 = array("pm.company_id");
            $comapnyId = $this->common_model->get_records($table23, $fields23, '', '', $match23);
            
        //Get data from prospect_master data
            $table23 = PROSPECT_MASTER . ' as pm';
            $match23 = "pm.company_id=" . $comapnyId[0]['company_id'];
            $fields23 = array("pm.prospect_id");
            $deleteProspectId = $this->common_model->get_records($table23, $fields23, '', '', $match23);
            
            
            if(!empty($deleteProspectId)){
                foreach($deleteProspectId as $deleteId){
                    $where = array('prospect_id' => $deleteId['prospect_id']);
                    
                    $prospectData['is_delete'] = 1;
                    $deleteSuceess = $this->common_model->update(PROSPECT_MASTER, $prospectData, $where);
                    
                  //  $this->common_model->delete(OPPORTUNITY_REQUIREMENT, $where);
                    
                    $params['join_tables'] = array(
                        OPPORTUNITY_REQUIREMENT_CONTACTS . ' as lct' => 'lct.prospect_id=lm.prospect_id',
                        CONTACT_MASTER . ' as cm' => 'cm.contact_id=lct.contact_id');
                    $params['join_type'] = 'left';
                    $match = "lm.prospect_id = " . $deleteId['prospect_id'] . " and cm.is_delete=0 and cm.status=1 ";
                    $table = PROSPECT_MASTER . ' as lm';
            
                    $fields = array("lct.contact_id");
                    $contactIds = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);
                    
                    if(!empty($contactIds)){
                        for ($i = 0; $i < count($contactIds); $i++) {
                           $Contactwhere = array('contact_id' => $contactIds[$i]['contact_id']);
                           $contactData['is_delete'] = 1;
                           $this->common_model->update(CONTACT_MASTER, $contactData, $Contactwhere);
                       }
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
                                $this->common_model->update(CONTACT_MASTER, $contactData, $wherePrimary);
                            }
                            
                        }   
                       
                               
                    $this->common_model->delete(OPPORTUNITY_REQUIREMENT_CONTACTS, $where);
                    
                    $this->common_model->delete(FILES_SALES_MASTER, $where);
                    
                    $this->common_model->delete(PROSPECT_PRODUCTS_TRAN, $where);
                    
                    
                    $campaignwhere = array('campaign_related_id' => $deleteId['prospect_id']);
                    $this->common_model->delete(TBL_CAMPAIGN_CONTACT, $campaignwhere);
                    
                    $accwhere = array('client_id' => $deleteId['prospect_id']);
                    $this->common_model->delete(COMPANY_ACCOUNTS_TRAN, $accwhere);
                }
            }
            
            
    //Delete data related to account company also from lead master
            
            //Get data from lead data data
            $table23 = LEAD_MASTER . ' as pm';
            $match23 = "pm.company_id=" . $comapnyId[0]['company_id'];
            $fields23 = array("pm.lead_id");
            $deleteLeadId = $this->common_model->get_records($table23, $fields23, '', '', $match23);
            
            
            if(!empty($deleteLeadId)){
                foreach($deleteLeadId as $deleteId){
                    $whereLead = array('lead_id' => $deleteId['lead_id']);
                
                    $leadData['is_delete'] = 1;
                    $deleteSuceess = $this->common_model->update(LEAD_MASTER, $leadData, $whereLead);
                     
                    $params['join_tables'] = array(
                        LEAD_CONTACTS_TRAN . ' as lct' => 'lct.lead_id=lm.lead_id',
                        CONTACT_MASTER . ' as cm' => 'cm.contact_id=lct.contact_id');
                    $params['join_type'] = 'left';
                    $match = "lm.lead_id = " . $deleteId['lead_id'] . " and cm.is_delete=0 and cm.status=1 ";
                    $table = LEAD_MASTER . ' as lm';
            
                    $fields = array("lct.contact_id");
                    $contactIds = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);
                
                    if(!empty($contactIds)){
                        for ($i = 0; $i < count($contactIds); $i++) {
                        $where = array('contact_id' => $contactIds[$i]['contact_id']);
                        $contactData['is_delete'] = 1;
                        $this->common_model->update(CONTACT_MASTER, $contactData, $where);
                         }
                    }
                    
                               
                    $this->common_model->delete(LEAD_CONTACTS_TRAN, $whereLead);
                    
                    $this->common_model->delete(FILES_LEAD_MASTER, $whereLead);
                    
                    $this->common_model->delete(LEAD_PRODUCTS_TRAN, $whereLead);
                    
                    
                    $campaignwhere = array('campaign_related_id' => $deleteId['lead_id']);
                    $this->common_model->delete(TBL_CAMPAIGN_CONTACT, $campaignwhere);
                    
                    $accwhere = array('client_id' => $deleteId['lead_id']);
                    $this->common_model->delete(COMPANY_ACCOUNTS_TRAN, $accwhere);
                }
            }
                
            //end lead master related delete data
            
            $whereProspectId = array('prospect_id' => $id);
            $prospectData['is_delete'] = 1;
            $deleteSuceess = $this->common_model->update(PROSPECT_MASTER, $prospectData, $whereProspectId);
            if ($deleteSuceess) {
                $msg = $this->lang->line('client_del_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            unset($id);
        }
        $redirectLink = $_SERVER['HTTP_REFERER'];
        if (strpos($redirectLink, 'Account/viewdata') !== false )  {
            $sessArray = array('setting_current_tab' => 'Deals');
            $this->session->set_userdata($sessArray);
            redirect($redirectLink);
        }
        else{
            redirect('Account'); //Redirect On Listing page
        }
        
    }

    /*
      @Author    : Seema Tankariya
      @Desc      : Account Model ConvertToClient
      @Input     : Get id
      @Output    : Set status_type=3 that's for client and Display Message if Success
      @Date      : 13/01/2016
     */

    public function ConvertToClient() {


        $prospectId = $this->input->post('prospect_id');

        //Convert back to client
        if (!empty($prospectId)) {
            $where = array('prospect_id' => $prospectId);
            $prospectData['status_type'] = 3;
            $deleteSuceess = $this->common_model->update(PROSPECT_MASTER, $prospectData, $where);
            if ($deleteSuceess) {
                return true;
            } else {
                return false;
            }
            unset($prospectId);
        }
    }

    /*
      @Author    : Seema Tankariya
      @Desc      : Account Model viewdata Page
      @Input     : Get id
      @Output    : View all data related to id
      @Date      : 13/01/2016
     */

    public function viewdata($id = NULL) {
        if ($id > 0) {
            //Get Records From PROSPECT_MASTER Table with JOIN
            $_SESSION['current_related_id'] = $id;
            $data = array();
            $params['join_tables'] = array(
                COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                BRANCH_MASTER . ' as bm' => 'bm.branch_id=pm.branch_id',
                LANGUAGE_MASTER . ' as lan' => 'lan.language_id=pm.language_id',
                COUNTRIES . ' as c' => 'c.country_id=pm.country_id',
                CONTACT_MASTER . ' as ct' => 'pm.prospect_owner_id=ct.contact_id');
            $params['join_type'] = 'left';
            $match = " pm.is_delete=0 and pm.prospect_id = " . $id;
            $table = PROSPECT_MASTER . ' as pm';
            $group_by = 'pm.prospect_id';
            $fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
                . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.city,pm.state,pm.country_id,pm.number_type1,"
                . "pm.phone_no as phone_number,pm.number_type2,pm.phone_no2,pm.prospect_owner_id,lan.language_name,"
                . "pm.branch_id,bm.branch_name,pm.prospect_generate,pm.campaign_id,pm.description,pm.language_id,"
                . "cm.company_name,cm.logo_img,cm.email_id as company_email,c.country_name,pm.fb,pm.twitter,pm.linkedin,ct.contact_name");
            $editRecord = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);
            
            if (empty($editRecord)) {
                redirect('Account');
            }
            else{
                $_SESSION['current_company_id'] = $editRecord[0]['company_id'];
            }
           

            $params['join_tables'] = array(
                OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
                CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';


            $fields = array("group_concat(cm.contact_name) as contact_name,group_concat(cm.mobile_number) as phone_no,group_concat(cm.email) as email_id");
            $contactInfo = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);
            $editRecord[0]['contact_name_account'] = !empty($contactInfo[0]['contact_name']) ? $contactInfo[0]['contact_name'] : '';
            $editRecord[0]['email_id'] = !empty($contactInfo[0]['email_id']) ? $contactInfo[0]['email_id'] : '';
            $editRecord[0]['phone_no'] = !empty($contactInfo[0]['phone_no']) ? $contactInfo[0]['phone_no'] : '';

            //Only get Contact for view Contact tab
            $params['join_tables'] = array(
                OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
                CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';
            $fields = array("cm.contact_id,cm.contact_name as name,cm.mobile_number as number,cm.email as email");
            $data['contact_info'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

            //Get Records From OPPORTUNITY_REQUIREMENT_CONTACTS Table
            $params['join_tables'] = array(
                OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
                CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.company_id = " . $editRecord[0]['company_id'] . " and cm.is_delete=0 and cm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';

            $fields = array("pm.prospect_id,cm.contact_id,cm.contact_name,cm.mobile_number,cm.email,orc.contact_id,orc.primary_contact");
            $data['opportunity_contact_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);
//Get Records From BRANCH_MASTER Table       
            $tableBranchMaster = BRANCH_MASTER . ' as bm';
            $matchBranchMaster = " bm.status=1 ";
            $fieldsBranchMaster = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($tableBranchMaster, $fieldsBranchMaster, '', '', $matchBranchMaster);

//Get Selected Records From BRANCH_MASTER Table       
            $table2 = BRANCH_MASTER . ' as bm';
            $match2 = "bm.branch_id=(SELECT pm.branch_id from " . $this->prefix . PROSPECT_MASTER . " as pm WHERE pm.prospect_id = " . $id . ") and bm.status=1 ";
            $fields2 = array("bm.branch_id,bm.branch_name");
            $data['branch_data1'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

//Get Records From LANGUAGE_MASTER Table
            $table1 = LANGUAGE_MASTER . ' as lan';
            $match1 = "";
            $fields1 = array("lan.language_id,lan.language_name");
            $data['language_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            //Get Records From COUNTRIES Table       
            $tableCountries = COUNTRIES . ' as c';
            $matchCountries = "";
            $fieldsCountries = array("c.country_id,c.country_name,c.country_code");
            $data['country_data'] = $this->common_model->get_records($tableCountries, $fieldsCountries, '', '', $matchCountries);

            //get estimate_prospect_worth value for view account 
            $params['join_tables'] = array(
                ESTIMATE_MASTER . ' as em' => 'em.estimate_id=pm.estimate_prospect_worth');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id . " and pm.is_delete=0 and pm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';
            $fields = array("em.value");
            $data['estimate_prospect_worth'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

            //get estimate_prospect_worth value for view account 
            $params['join_tables'] = array(
                ESTIMATE_MASTER . ' as em' => 'em.estimate_id=pm.estimate_prospect_worth');
            $params['join_type'] = 'left';
            $match = "pm.company_id = " . $editRecord[0]['company_id'] . " and pm.is_delete=0 and pm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';
            $fields = array("em.value,em.country_id_symbol,pm.prospect_id,pm.prospect_auto_id,pm.status_type,pm.is_estimate_sent,pm.prospect_name,pm.creation_date");
            $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

            $data['prospect_owner'] = $this->common_model->getSystemUserData();
            $data['id'] = $id;
            $data['all_records'] = $editRecord;

            
            if (count($data['all_records'][0]) < 4) {
                redirect('Account');
            }

            $searchtext = @$this->session->userdata('searchtext');
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            }
            $data['drag'] = true;
            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['header'] = array('menu_module' => 'crm');
            $data['modal_title'] = $this->lang->line('view_account');
            $data['account_view'] = $this->viewname;
            $data['main_content'] = '/ViewAccount';
            $this->parser->parse('layouts/DashboardTemplate', $data);
        } else {
            redirect('Account');
        }
    }

    /*
      @Author    : Sanket Jayani
      @Desc      : Account Model viewLostClient Page
      @Input     : Get id
      @Output    : View all data related to id
      @Date      : 13/01/2016
     */

    function viewLostClient($id = NULL) {

        if ($id > 0) {
            //Get Records From PROSPECT_MASTER Table with JOIN
            $_SESSION['current_related_id'] = $id;
            $data = array();
            $params['join_tables'] = array(
                COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                LANGUAGE_MASTER . ' as lan' => 'lan.language_id=pm.language_id',
                COUNTRIES . ' as c' => 'c.country_id=pm.country_id',
                CONTACT_MASTER . ' as ct' => 'pm.prospect_owner_id=ct.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id;
            $table = PROSPECT_MASTER . ' as pm';
            $group_by = 'pm.prospect_id';
            $fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
                . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.city,pm.state,pm.country_id,pm.number_type1,"
                . "pm.phone_no as phone_number,pm.phone_no2,pm.prospect_owner_id,lan.language_name,"
                . "pm.branch_id,pm.prospect_generate,pm.campaign_id,pm.description,"
                . "cm.company_name,cm.logo_img,cm.email_id as company_email,c.country_name,pm.fb,pm.twitter,pm.linkedin,ct.contact_name");
            $editRecord = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);
            $params['join_tables'] = array(
                OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
                CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';


            $fields = array("group_concat(cm.contact_name) as contact_name,group_concat(cm.mobile_number) as phone_no,group_concat(cm.email) as email_id");
            $contactInfo = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);
            $editRecord[0]['contact_name_account'] = !empty($contactInfo[0]['contact_name']) ? $contactInfo[0]['contact_name'] : '';
            $editRecord[0]['email_id'] = !empty($contactInfo[0]['email_id']) ? $contactInfo[0]['email_id'] : '';
            $editRecord[0]['phone_no'] = !empty($contactInfo[0]['phone_no']) ? $contactInfo[0]['phone_no'] : '';

            //Only get Contact for view Contact tab
            $params['join_tables'] = array(
                OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
                CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';
            $fields = array("cm.contact_id,cm.contact_name as name,cm.mobile_number as number,cm.email as email");
            $data['contact_info'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

            //get estimate_prospect_worth value for view account 
            $params['join_tables'] = array(
                ESTIMATE_MASTER . ' as em' => 'em.estimate_id=pm.estimate_prospect_worth');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id . " and pm.is_delete=0 and pm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';
            $fields = array("em.value");
            $data['estimate_prospect_worth'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

            $data['id'] = $id;
            $data['all_records'] = $editRecord;
            $data['prospect_owner'] = $this->common_model->getSystemUserData();
            if (count($data['all_records'][0]) < 4) {
                show_404();
            }

            $searchtext = @$this->session->userdata('searchtext');
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            }
            $data['drag'] = true;
            $data['header'] = array('menu_module' => 'crm');
            $data['modal_title'] = $this->lang->line('view_account');
            $data['account_view'] = $this->viewname;
            $data['main_content'] = '/ViewAccountLostClient';
            $this->parser->parse('layouts/DashboardTemplate', $data);
            //$this->load->view('ViewAccount', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author    : Seema Tankariya
      @Desc      : Account Model editdata Page
      @Input     : Get id
      @Output    : Get data from id and display in AddEditAccount
      @Date      : 13/01/2016
     */

    public function editdata($id) 
    {
        
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $redirectLink = $this->input->post('redirect_link');

            //Get Estimate Master data
            $table1 = ESTIMATE_MASTER . ' as ESTM';
            $match1 = " ESTM.status=1 ";
            $fields1 = array("ESTM.subject,ESTM.estimate_id");
            $data['EstimateArray'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            //Get owner data from LOGIN table
            $data['prospect_owner'] = $this->common_model->getSystemUserData();

            //Get Records From BRANCH_MASTER Table       
            $tableBranchMaster = BRANCH_MASTER . ' as bm';
            $matchBranchMaster = " bm.status=1 ";
            $fieldsBranchMaster = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($tableBranchMaster, $fieldsBranchMaster, '', '', $matchBranchMaster);

            //Get Selected Records From BRANCH_MASTER Table       
            $table2 = BRANCH_MASTER . ' as bm';
            $match2 = "bm.branch_id=(SELECT pm.branch_id from " . $this->prefix . PROSPECT_MASTER . " as pm WHERE pm.prospect_id = " . $id . ") and bm.status=1 ";
            $fields2 = array("bm.branch_id,bm.branch_name");
            $data['branch_data1'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

            //Get Records From LANGUAGE_MASTER Table
            $table1 = LANGUAGE_MASTER . ' as lan';
            $match1 = "";
            $fields1 = array("lan.language_id,lan.language_name");
            $data['language_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            //Get Records From CAMPAIGN_MASTER Table       
            $tableCampaignMaster = CAMPAIGN_MASTER . ' as cam';
            $matchCampaignMaster = " cam.status=1 ";
            $fieldsCampaignMaster = array("cam.campaign_id,cam.campaign_name");
            $data['campaign'] = $this->common_model->get_records($tableCampaignMaster, $fieldsCampaignMaster, '', '', $matchCampaignMaster);

            //Get Records From PRODUCT_MASTER Table       
            $tableProductMaster = PRODUCT_MASTER . ' as prm';
            $matchProductMaster = " prm.is_delete='0' and prm.status=1 ";
            $fieldsProductMaster = array("prm.product_id,prm.product_name");
            $data['product_data'] = $this->common_model->get_records($tableProductMaster, $fieldsProductMaster, '', '', $matchProductMaster);

            //Get Records From COUNTRIES Table       
            $tableCountries = COUNTRIES . ' as c';
            $matchCountries = "";
            $fieldsCountries = array("c.country_id,c.country_name,c.country_code");
            $data['country_data'] = $this->common_model->get_records($tableCountries, $fieldsCountries, '', '', $matchCountries);

            //Get Records From COMPANY_MASTER Table       
            $tableCompanyMaster = COMPANY_MASTER . ' as cmp';
            $matchCompanyMaster = " cmp.status=1 and cmp.is_delete=0 ";
            $fieldsCompanyMaster = array("cmp.company_id,cmp.company_name");
            $data['company_data'] = $this->common_model->get_records($tableCompanyMaster, $fieldsCompanyMaster, '', '', $matchCompanyMaster);

            //Get Records From PROSPECT_MASTER Table
            $tableJoinProspect = PROSPECT_MASTER . ' as pm';
            $matchJoinProspect = "pm.prospect_id = " . $id;
            $fieldsJoinProspect = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
                . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.city,pm.state,pm.country_id,pm.number_type1,"
                . "pm.phone_no,pm.number_type2,pm.phone_no2,pm.prospect_owner_id,pm.language_id,"
                . "pm.branch_id,pm.estimate_prospect_worth,pm.prospect_generate,pm.campaign_id,pm.description,"
                . "pm.file,pm.contact_date,pm.fb,pm.twitter,pm.linkedin,pm.credit_limit,pm.created_date");
            $data['edit_record'] = $this->common_model->get_records($tableJoinProspect, $fieldsJoinProspect, '', '', $matchJoinProspect);

            //Get Records From OPPORTUNITY_REQUIREMENT_CONTACTS Table
            //$params['join_tables'] = array(
            //    OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
            //    CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id');
            //$params['join_type'] = 'left';
            $match = "cm.company_id = " . $data['edit_record'][0]['company_id'] . " and cm.is_delete=0 and cm.status=1 ";
            $table = CONTACT_MASTER . ' as cm';

            $fields = array("cm.primary_contact,cm.contact_id,cm.contact_name,cm.mobile_number,cm.email");
            $data['opportunity_contact_data'] = $this->common_model->get_records($table, $fields, '','', $match);


            //Get Records From PROSPECT_PRODUCTS_TRAN Table
            $tableProducts = PROSPECT_PRODUCTS_TRAN . ' as pt';
            $matchProducts = "pt.prospect_id = " . $id;
            $fieldsProducts = array("pt.product_id");
            $dataopportunity_product_data = $this->common_model->get_records($tableProducts, $fieldsProducts, '', '', $matchProducts);
            $productId = array();
            if (!empty($dataopportunity_product_data)) {
                foreach ($dataopportunity_product_data as $dataopportunity_product_data) {
                    $productId[] = $dataopportunity_product_data['product_id'];
                }
            }
            $data['opportunity_product_data'] = $productId;

            //Get description From OPPORTUNITY_REQUIREMENT Table
            $tableRequirement = OPPORTUNITY_REQUIREMENT . ' as or';
            $matchRequirement = "or.prospect_id = " . $id;
            $fieldsRequirement = array("or.requirement_id,or.requirement_description");
            $data['opportunity_requirement'] = $this->common_model->get_records($tableRequirement, $fieldsRequirement, '', '', $matchRequirement);


            //Get files and documents From FILES_SALES_MASTER Table
            $tableFiles = FILES_SALES_MASTER . ' as lf';
            $fieldsFiles = array("*");
            $matchFiles = 'lf.prospect_id=' . $id . '';
            $data['prospect_files'] = $this->common_model->get_records($tableFiles, $fieldsFiles, '', '', $matchFiles);

            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['drag'] = true;
            $data['modal_title'] = $this->lang->line('update_client');
            $data['submit_button_title'] = $this->lang->line('update_client');
            $data['account_view'] = $this->viewname;

            //Get all data and display at AddEditAccount
            $this->load->view('AddEditAccount', $data);

        }
        
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model download Page
      @Input 	: id
      @Output	: Download image
      @Date     : 18/02/2016
     */

    function download($id) {

        if (!empty($id)) {
            $params['fields'] = ['*'];
            $params['table'] = FILES_SALES_MASTER . ' as CM';
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
      @Author   : Seema Tankariya
      @Desc     : Account Model deleteImage Page
      @Input 	: Id
      @Output	: Delete Image
      @Date   : 18/02/2016
     */

    public function deleteImage($id) {
//Delete Record From Database

        if (!empty($id)) {
            $match = array("file_id" => $id);
            $fields = array("file_name");
            $imageName = $this->common_model->get_records(FILES_SALES_MASTER, $fields, '', '', $match);
            if (file_exists($this->config->item('Prospect_img_url') . $imageName[0]['file_name'])) {

                unlink($this->config->item('Prospect_img_url') . $imageName[0]['file_name']);
            }
            $where = array('file_id' => $id);
            if ($this->common_model->delete(FILES_SALES_MASTER, $where)) {
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
      @Author   : Seema Tankariya
      @Desc     : Account Model exportToCSV Page
      @Input 	: input post data
      @Output	: export csv
      @Date   : 18/02/2016
     */

    function exportToCSV() {

        $db_search = " pm.status_type=3 and pm.status=1 and is_delete=0 ";
        if ($this->input->post('search_branch_id') != '') {
            $db_search .= " and pm.branch_id=" . $this->input->post('search_branch_id');
        }
        if ($this->input->post('search_prospect_owner_id') != '') {
            $db_search .= " and pm.prospect_owner_id=" . $this->input->post('search_prospect_owner_id');
        }
        if ($this->input->post('search_status') != '') {
            $db_search .= " and pm.status=" . $this->input->post('search_status');
        }
        if ($this->input->post('start_value') != '' && $this->input->post('end_value') != '') {
            $db_search .= " and pm.estimate_prospect_worth>=" . $this->input->post('start_value') . ' and pm.estimate_prospect_worth<=' . $this->input->post('end_value');
        }
        if ($this->input->post('search_creation_date') != '' && $this->input->post('creation_end_date') != '') {
            $db_search .= " and pm.created_date>=" . $this->input->post('search_creation_date') . ' and pm.created_date<=' . $this->input->post('creation_end_date');
        }

        if ($this->input->post('search_contact_date') != '' && $this->input->post('contact_end_date') != '') {
            $db_search .= " and pm.contact_date>=" . $this->input->post('search_contact_date') . ' and pm.contact_date<=' . $this->input->post('contact_end_date');
        }
        $data['prospect_data'] = $this->Account_model->exportCsvData($db_search);
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : lostclient Index Page
      @Input 	:
      @Output	:
      @Date     : 18/02/2016
     */

    public function lostClient() {
        $this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('sales_overview'), 'SalesOverview');
        $this->breadcrumbs->push(lang('accounts'), 'Account');


        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('prospect_data');
        }

        $searchsort_session = $this->session->userdata('prospect_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {
                $sortfield = 'creation_date';
                //$sortfield = 'prospect_id';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
//Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
//pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->viewname . '/lostClient';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uriSegment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uriSegment = $this->uri->segment(3);
        }

//Get prospect_owner From LOGIN Table
        $data['prospect_owner'] = $this->common_model->getSystemUserData();

//Get Records From BRANCH_MASTER Table       
        $tableBranchMaster = BRANCH_MASTER . ' as bm';
        $matchBranchMaster = " bm.status=1 ";
        $fieldsBranchMaster = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($tableBranchMaster, $fieldsBranchMaster, '', '', $matchBranchMaster);

//Join PROSPECT_MASTER with OPPORTUNITY_REQUIREMENT_CONTACTS,OPPORTUNITY_REQUIREMENT for get all related like contact data
        $params['join_tables'] = array(
            OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id ');
        $params['join_type'] = 'left';
        $tableJoinProspect_master = PROSPECT_MASTER . ' as pm';
        $group_by = 'pm.prospect_id';
        $fieldsJoinProspect_master = array("pm.prospect_id,pm.prospect_name,pm.prospect_auto_id, pm.status,pm.status_type,pm.creation_date,(select count(orc.prospect_id) from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . OPPORTUNITY_REQUIREMENT_CONTACTS . " as orc on cm.contact_id=orc.contact_id where cm.is_delete=0 and cm.status=1 and orc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_count,(select cm.contact_name from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . OPPORTUNITY_REQUIREMENT_CONTACTS . " as orc on cm.contact_id=orc.contact_id where orc.primary_contact=1 and cm.is_delete=0 and cm.status=1 and orc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_name");
        $where = "pm.is_delete=0 and pm.status_type=4 and pm.status=1";

//If search data are there take post value and update query
        $data['branch_show_id'] = "";
        if ($this->input->post('search_branch_id') != "") {
            $data['branch_show_id'] = $this->input->post('search_branch_id');
            $where.=' and pm.branch_id=' . $data['branch_show_id'];
        }
        $data['prospect_show_id'] = "";
        if ($this->input->post('search_prospect_owner_id') != "") {
            $data['prospect_show_id'] = $this->input->post('search_prospect_owner_id');
            $where.=' and pm.prospect_owner_id=' . $data['prospect_show_id'];
        }
        $data['status_show'] = "";
        if ($this->input->post('search_status') != "") {
            $data['status_show'] = $this->input->post('search_status');
            $where.=' and pm.status=' . $data['status_show'];
        }

        $data['start_value_show'] = "";
        if ($this->input->post('start_value') != "") {
            $data['start_value_show'] = $this->input->post('start_value');
            $where.=' and pm.estimate_prospect_worth>=' . $data['start_value_show'];
        }
        $data['end_value_show'] = "";
        if ($this->input->post('end_value') != "") {
            $data['end_value_show'] = $this->input->post('end_value');
            $where.=' and pm.estimate_prospect_worth<=' . $data['end_value_show'];
        }
        $data['search_creation_date_show'] = "";
        if ($this->input->post('search_creation_date') != "") {
            $data['search_creation_date_show'] = date_format(date_create($this->input->post('search_creation_date')), 'Y-m-d');
            $where.=' and pm.creation_date>="' . $data['search_creation_date_show'] . '"';
        }
        $data['creation_end_date_show'] = "";
        if ($this->input->post('creation_end_date') != "") {
            $data['creation_end_date_show'] = date_format(date_create($this->input->post('creation_end_date')), 'Y-m-d');
            $where.=' and pm.creation_date<="' . $data['creation_end_date_show'] . '"';
        }
        $data['search_contact_date_show'] = "";
        if ($this->input->post('search_contact_date') != "") {
            $data['search_contact_date_show'] = date_format(date_create($this->input->post('search_contact_date')), 'Y-m-d');
            $where.=' and pm.contact_date>="' . $data['search_contact_date_show'] . '"';
        }
        $data['contact_end_date_show'] = "";
        if ($this->input->post('contact_end_date') != "") {
            $data['contact_end_date_show'] = date_format(date_create($this->input->post('contact_end_date')), 'Y-m-d');
            $where.=' and pm.contact_date<="' . $data['contact_end_date_show'] . '"';
        }

////Get Records From PROSPECT_MASTER Table total account     
//        $table_prospect_master = PROSPECT_MASTER . ' as pm';
//        //$match_prospect_master = "pm.status_type=2 and pm.is_delete=0 and pm.status=1";
//        $fields_prospect_master = array("count(pm.prospect_id) as total_account");

        if (!empty($searchtext)) {
//If have any search text
            $searchtext = html_entity_decode(trim($searchtext));
            $clientSince = date('Y-m-d', strtotime($searchtext));
            $whereSearch = '(pm.prospect_name LIKE "%' . $searchtext . '%" OR pm.prospect_auto_id LIKE "%' . $searchtext . '%" OR pm.status_type LIKE "%' . $searchtext . '%" OR pm.creation_date LIKE "%' . $clientSince . '%"  OR cm.contact_name LIKE "%' . $searchtext . '%")';
            $data['prospect_data'] = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $whereSearch);
            $config['total_rows'] = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $whereSearch);

            //Get Records From PROSPECT_MASTER Table total account 
            $totalAccount = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $whereSearch);
        } else {
//Not have any search input
            $data['prospect_data'] = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where);
            $config['total_rows'] = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');

            //Get Records From PROSPECT_MASTER Table total account 
            $totalAccount = $this->common_model->get_records($tableJoinProspect_master, $fieldsJoinProspect_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
        }

        //check  total account > 0 otherwise set total opportunity = 0
        if ($totalAccount) {
            $data['total_account'] = $totalAccount;
        } else {
            $data['total_account'] = '0';
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
        $this->session->set_userdata('prospect_data', $sortsearchpage_data);
        $data['drag'] = true;
        $data['account_view'] = $this->viewname;
        $data['sales_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'crm');

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view('AjaxAccountLostClientList', $data);
        } else {
            $data['main_content'] = '/AccountLostClient';
            $this->parser->parse('layouts/DashboardTemplate', $data);
        }
    }

    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('Prospect_img_url') . '/' . $filename, $str);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model delete_contact_master Page
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
            $contact_data['is_delete'] = 1;
            $this->common_model->update(CONTACT_MASTER, $contact_data, $where);
        }
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : Account Model send_email_view Page
      @Input 	: input post prospect id
      @Output	: get and view data in SendEmail
      @Date     : 29/03/2016
     */

    function send_email_view($prospectId = NULL) {

        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $table = PROSPECT_MASTER . ' as cm';
            $match = "cm.prospect_id = " . $prospectId;
            $fields = array("cm.prospect_id,cm.prospect_auto_id,cm.prospect_name,cm.company_id,cm.prospect_owner_id");
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
            $prospect_contact_arr = $this->getProspectContact($prospectId);
            $prospect_company_email = $this->common_model->getCompanyEmailFromComapanyid($company_id);


            $receipent_arr = [];
            $i = 0;
            foreach ($prospect_contact_arr as $prospect_contact) {
                $receipent_arr[$i]['contact_email'] = $prospect_contact['email'];
                $receipent_arr[$i]['contact_name'] = $prospect_contact['contact_name'];
                $i++;
            }


            $new_receipenet_arr = $prospect_contact_arr;

    //for gettig opportunity primary contact
            $table_req = OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc';
            $match_req = "pc.prospect_id = " . $prospectId;
            $fields_req = array("pc.contact_id");
            $data['contact_data'] = $this->common_model->get_records($table_req, $fields_req, '', '', $match_req);

            if (count($data['contact_data']) > 0) {
                $data['contact_id'] = $data['contact_data'][0]['contact_id'];
            }

            $data['company_email'] = $prospect_company_email['email_id'];
            $data['prospect_receipent_array'] = $new_receipenet_arr;
            $data['drag'] = true;
            $data['modal_title'] = lang('SEND_EMAIL_OCNTACT');
            $data['main_content'] = '/SendEmail';
            $this->load->view('SendEmail', $data);

        }
        
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : Account Model getProspectContact Page
      @Input 	: input post prospect id
      @Output	: get and view data
      @Date     : 29/03/2016
     */

    function getProspectContact($prospectId) {

        $table1 = OPPORTUNITY_REQUIREMENT_CONTACTS . ' as cm,' . CONTACT_MASTER . ' as ad';
        $match1 = "cm.status=1  AND ad.status=1 AND ad.is_delete=0 AND cm.contact_id=ad.contact_id AND cm.prospect_id=" . $prospectId;
        $fields1 = array("ad.contact_id,ad.contact_name,ad.email");
        $order_by = 'ad.contact_name';
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1, '', '', '', $order_by, 'asc');

        return $user_data;
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : Account Model getAllContactByCompany Page
      @Input 	:
      @Output	:
      @Date     : 29/03/2016
     */

    function getAllContactByCompany() {
        $table1 = CONTACT_MASTER . ' as cm';
        //$match1 = "cm.status=1 AND cm.is_delete=0 AND cm.company_id=".$company_id;
        $match1 = "cm.status=1 AND cm.is_delete=0 and cm.status=1 ";
        $fields1 = array("cm.contact_id,cm.contact_name");
        $order_by = 'cm.contact_name';
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1, '', '', '', $order_by, 'asc');

        return $user_data;
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : Account Model set_upload_options Page
      @Input 	:
      @Output	:
      @Date     : 29/03/2016
     */

    private function set_upload_options() {
        //upload an image options
        $config = array();
        $config['upload_path'] = EMAIL_PROSPECT_ATTACH_PATH;
        $config['allowed_types'] = '*';
        $config['max_size'] = '0';
        $config['overwrite'] = FALSE;

        return $config;
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : Account Model sendProspectEmail Page
      @Input 	:
      @Output	:
      @Date     : 29/03/2016
     */

    function sendProspectEmail() {


        // $prospectId = $this->input->post('prospect_id');
        // $prospectData = $this->getProspectDataById($prospectId);
        // $prospect_auto_id = $prospectData[0]['prospect_auto_id'];
        // $prospect_owner_id = $this->input->post('hdn_contact_id');
        // $prospect_owner_id = $this->input->post('prospect_owner');
        $prospect_company_id = $this->input->post('hdn_company_id');
        $redirectLink = $this->input->post('redirect_link');
        // $prospect_owner_arr = $this->getContactEmailByContactId($prospect_owner_id);
        $prospect_owner_email = $_SESSION['LOGGED_IN']['EMAIL'];
        ;
        $prospect_owner_name = $_SESSION['LOGGED_IN']['FIRSTNAME'] . " " . $_SESSION['LOGGED_IN']['LASTNAME'];

        $cc_employee_id = $this->input->post('cc_employee');
        if ($cc_employee_id != '') {
            $cc_email_address = $this->getLoginUserEmail($cc_employee_id);
        }

        $arr_receipent_email = $this->input->post('company_contact');
        $hdn_contact_id = $this->input->post('hdn_contact_id');
        $hdn_company_email = $this->input->post('hdn_company_email');
        $recepient_array = [];

        if (isset($hdn_company_email) && $hdn_company_email != '') {
            $recepient_array[] = $hdn_company_email;
        }

        if (!empty($arr_receipent_email)) {
            foreach ($arr_receipent_email as $arr_receipent) {
                $tmp_arr_rec[] = $arr_receipent;
                $recepient_email = $this->getContactEmailbyId($tmp_arr_rec);
                $recepient_array[] = $recepient_email;
            }
        }

        if (isset($hdn_contact_id) && $hdn_contact_id != '') {
            $tmp_arr[] = $hdn_contact_id;
            $recepient_array[] = $this->getContactEmailbyId($tmp_arr);
            $arr_receipent_email[] = $hdn_contact_id;
        }


        $unique_recepent_array = array_unique($recepient_array);

        $contact_receipent_email = implode(',', $unique_recepent_array);

        $email_subject = $this->input->post('email_subject');
        $email_contect = $this->input->post('email_content');

        $hdn_mark_as_important = $this->input->post('hdn_mark_as_important');

        // $email_data['prospect_auto_id'] = $prospect_auto_id;
        // $email_data['prospect_id'] = $prospectId;
        $email_data['company_id'] = $prospect_company_id;
        // $email_data['prospect_owner_id'] = $prospect_owner_id;
        $email_data['subject'] = $email_subject;
        $email_data['email_description'] = $email_contect;
        $email_data['send_to'] = $hdn_contact_id;
        $email_data['created_date'] = datetimeformat();
        $email_data['modified_date'] = datetimeformat();
        $email_data['status'] = 1;

        $id = $last_email_prospect_id = $this->common_model->insert(TBL_EMAIL_PROSPECT, $email_data);


        /* custom uplod code starts
         * 
         */
        $fileName = array();
        $fileArray1 = $this->input->post('file_data');

        $fileName = $_FILES['cost_files']['name'];
        if (count($fileName) > 0 && count($fileArray1) > 0) {
            $differentedImage = array_diff($fileName, $fileArray1);
            foreach ($fileName as $file) {
                if (in_array($file, $differentedImage)) {
                    $keyData[] = array_search($file, $fileName); // $key = 2;
                }
            }
            if (!empty($keyData)) {
                foreach ($keyData as $key) {
                    unset($_FILES['cost_files']['name'][$key]);
                    unset($_FILES['cost_files']['type'][$key]);
                    unset($_FILES['cost_files']['tmp_name'][$key]);
                    unset($_FILES['cost_files']['error'][$key]);
                    unset($_FILES['cost_files']['size'][$key]);
                }
            }
        }
        $_FILES['cost_files'] = $arr = array_map('array_values', $_FILES['cost_files']);
        /* ends
         *
         */

        $tmp_url = base_url() . "Contact";
        $uploadData = uploadImage('cost_files', EMAIL_PROSPECT_ATTACH_PATH, $tmp_url);

        /* ritesh code */
//
        $Marketingfiles = array();

        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $fileData = implode(",", $file2);
        } else {
            $fileData = '';
        }
        $compaigndata['file_name'] = $fileData;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }

        /* end
         * 
         */
        $costFiles = array();
        $attach_arr_gallary = array();
        if ($this->input->post('gallery_path')) {
            $galleryPath = $this->input->post('gallery_path');
            $cost_files = $this->input->post('gallery_files');
            if (count($galleryPath) > 0) {
                for ($i = 0; $i < count($galleryPath); $i++) {
                    $attach_arr_gallary[] = FCPATH . $galleryPath[$i] . $cost_files[$i];
                    $costFiles[] = ['file_name' => $cost_files[$i], 'file_path' => $galleryPath[$i], 'email_prospect_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
        $attach_arr_browse = array();
        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $attach_arr_browse[] = FCPATH . "uploads/attach_email_prospect/" . $files['file_name'];
                $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => EMAIL_PROSPECT_ATTACH_PATH, 'email_prospect_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }

        $email_atached_arr = array_merge($attach_arr_gallary, $attach_arr_browse);

        if (count($costFiles) > 0) {
            $where = array('email_prospect_id' => $id);
            //  $this->common_model->delete(COST_FILES, $where);

            if (!$this->common_model->insert_batch(TBL_EMAIL_PROSPECT_FILE_MASTER, $costFiles)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
                redirect($this->module . '/Contact/'); //Redirect On Listing page
            }
        }

        if ($last_email_prospect_id) {
            $CI = & get_instance();
            $configs = getMailConfig();

            $config['protocol'] = $configs['email_protocol'];
            $config['smtp_host'] = $configs['smtp_host']; //change this
            $config['smtp_port'] = $configs['smtp_port'];
            $config['smtp_user'] = $configs['smtp_user']; //change this
            $config['smtp_pass'] = $configs['smtp_pass']; //change this

            if ($hdn_mark_as_important == "1") {
                $config['priority'] = 1;
            }

            $config['mailtype'] = 'html';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8

            $CI->load->library('email', $config); // load email library
            //$CI->email->from($configs['smtp_user'], $email_subject);
            $CI->email->set_header('MIME-Version', '1.0\r\n');
            $CI->email->set_header('Disposition-Notification-To', $prospect_owner_email);

            $CI->email->from($prospect_owner_email, $prospect_owner_name);
            $CI->email->to($contact_receipent_email);

            if (isset($cc_email_address) && $cc_email_address != '') {
                $this->email->cc($cc_email_address);
            }

            foreach ($email_atached_arr as $attach) {
                $this->email->attach($attach);
            }

            $CI->email->subject($email_subject);
            $CI->email->message($email_contect);

            if ($CI->email->send()) {
                $msg = $this->lang->line('SENT_PROSPECT_EMAIL');
                $this->session->set_flashdata('message', $msg);

                foreach ($arr_receipent_email as $recepient) {
                    $email_communication['comm_date'] = date('Y-m-d');
                    $email_communication['email_prospect_id'] = $id;
                    $email_communication['comm_sender'] = $_SESSION['LOGGED_IN']['ID'];
                    $email_communication['comm_receiver'] = $recepient;
                    $email_communication['comm_subject'] = $email_subject;
                    $email_communication['comm_content'] = $email_contect;
                    $email_communication['comm_type'] = 2;
                    $email_communication['is_delete'] = 0;
                    $email_communication['comm_related_id'] = $recepient;
                    $email_communication['created_date'] = datetimeformat();
                    $this->common_model->insert(TBL_EMAIL_COMMUNICATION, $email_communication);
                }
            } else {
                $msg = $this->lang->line('FAIL_WITH_SENDING_EMAIL');
                $this->session->set_flashdata('error', $msg);
            }
        } else {
            $msg = $this->lang->line('error');
            $this->session->set_flashdata('error', $msg);
        }
        unset($email_atached_arr);
        redirect($redirectLink);
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : Account Model getContactEmailByContactId Page
      @Input 	:
      @Output	:
      @Date     : 29/03/2016
     */

    function getContactEmailByContactId($contactId) {

        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "cm.contact_id=" . $contactId;
        $fields1 = array("cm.email,cm.contact_name");
        $contat_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        return $contat_data[0];
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : Account Model getProspectDataById Page
      @Input 	:
      @Output	:
      @Date     : 29/03/2016
     */

    function getProspectDataById($prospectId) {
        $table_prospect = PROSPECT_MASTER . ' as pm';
        $prospect_match = "pm.status_type = 1 AND pm.is_delete=0 AND pm.status=1 AND pm.prospect_id=" . $prospectId;
        $prospect_fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name");
        $data['prospect_data'] = $this->common_model->get_records($table_prospect, $prospect_fields, '', '', $prospect_match);

        return $data['prospect_data'];
    }

    /*
      @Author   : Sanket Jayani
      @Desc     : Account Model getLoginUserEmail Page
      @Input 	:
      @Output	:
      @Date     : 29/03/2016
     */

    function getLoginUserEmail($user_id) {
        $table1 = LOGIN . ' as l';
        $match1 = "l.login_id=" . $user_id;
        $fields1 = array("l.email");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        return $user_data[0]['email'];
    }

    /*
      @Author   : Mehul Patel
      @Desc     : Display Import CSV File popup
      @Input 	: search post data
      @Output	: open popup
      @Date     : 29/03/2016
     */

    function importAccount() {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data['modal_title'] = $this->lang->line('import_account');
            $data['submit_button_title'] = $this->lang->line('import_account');
            $data['account_view'] = $this->viewname;
            $data['main_content'] = '/ImportAccount';
            $data['js_content'] = '/loadJsFiles';
            $this->load->view('/ImportAccount', $data);
        }
        
    }

    /*
      @Author   : Mehul Patel
      @Desc     : Import CSV OR Excel File to import Account
      @Input    : search post data
      @Output   : import data to prospect_master table
      @Date     : 28/03/2016
     */

    function importAccountdata() {
        $config['upload_path'] = FCPATH . 'uploads/csv_account';
        $config['allowed_types'] = '*';

        //  $new_name = time() . "_" . $_FILES["import_file"]['name'];
        $new_name = time() . "_" . str_replace(' ', '_', $_FILES["import_file"]['name']);
        $config['file_name'] = $new_name;
    
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('import_file')) {
            $msg = $this->upload->display_errors();
            $this->session->set_flashdata('msg', $msg);
        } else {
            $file_path = './uploads/csv_account/' . $new_name;

            // $this->load->library('excel');
            // $objPHPExcel = PHPExcel_IOFactory::load($file_path);
            //  $cell_collection = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

            $file = fopen($file_path, 'r');
            $i = 1;
            while (($line = fgetcsv($file)) !== FALSE) {
                if ($i == 1) {
                    $cell_collection[$i] = $line;
                } else if ($line[0] != '') {
                    $cell_collection[$i] = $line;
                }
                $i++;
            }
            echo array_search('Client Name', $cell_collection[1]);


            $chk_file_column = array('Client Name', 'Company Name', 'Company Email ID', 'Website', 'Company Phone No', 'Address1', 'Address2', 'Language', 'Postal Code', 'City', 'State', 'Country', 'Branch', 'Estimate Prospect Worth', 'NumberType1', 'PhoneNumber1', 'NumberType2', 'PhoneNumber2', 'Interested Products', 'Description');

            $diff_array = array_diff($chk_file_column, $cell_collection[1]);


            if (!empty($diff_array)) {

                $this->session->set_flashdata('msg', lang('WRONG_FILE_FOMRMAT'));
                redirect($this->viewname);
            }

            
            $key_account_name = array_search('Client Name', $cell_collection[1]);
            $key_company_name = array_search('Company Name', $cell_collection[1]);
            $key_company_email_id = array_search('Company Email ID', $cell_collection[1]);
            $key_company_website = array_search('Website', $cell_collection[1]);
            $key_company_phone_no = array_search('Company Phone No', $cell_collection[1]);
            $key_address_1 = array_search('Address1', $cell_collection[1]);
            $key_address_2 = array_search('Address2', $cell_collection[1]);
            $key_language = array_search('Language', $cell_collection[1]);
            $key_postal_code = array_search('Postal Code', $cell_collection[1]);
            $key_city = array_search('City', $cell_collection[1]);
            $key_state = array_search('State', $cell_collection[1]);
            $key_country = array_search('Country', $cell_collection[1]);
            $key_branch = array_search('Branch', $cell_collection[1]);
            $key_esimate_prospect_worth = array_search('Estimate Prospect Worth', $cell_collection[1]);
            $key_number_type1 = array_search('NumberType1', $cell_collection[1]);
            $key_number_type2 = array_search('NumberType2', $cell_collection[1]);
            $key_phone_number1 = array_search('PhoneNumber1', $cell_collection[1]);
            $key_phone_number2 = array_search('PhoneNumber2', $cell_collection[1]);
            $key_inserted_products_services = array_search('Interested Products', $cell_collection[1]);
            $key_description = array_search('Description', $cell_collection[1]);

            $chk_file_column = array('Client Name', 'Company Name', 'Company Email ID', 'Website', 'Company Phone No', 'Address1', 'Address2', 'Language', 'Postal Code', 'City', 'State', 'Country', 'Branch', 'Estimate Prospect Worth', 'NumberType1', 'PhoneNumber1', 'NumberType2', 'PhoneNumber2', 'Interested Products', 'Description');

            $diff_array = array_diff($chk_file_column, $cell_collection[1]);


            if (!empty($diff_array)) {

                $this->session->set_flashdata('msg', lang('WRONG_FILE_FOMRMAT'));
                redirect($this->viewname);
            }

            
            unset($cell_collection[1]);
          
            $count_success = 0;
            $count_fail = 0;
            $total_record = count($cell_collection);
            $type1 = "";
            $type2 = "";
            $get_branch_id = "";
            //$productArr = array();
            foreach ($cell_collection as $cell) {
                $value_account_name = trim($cell[$key_account_name]);
                $value_company_name = trim(strtolower($cell[$key_company_name]));
                $value_company_email_id = trim($cell[$key_company_email_id]);
                $value_company_website = $cell[$key_company_website];
                $value_company_phone_no = trim($cell[$key_company_phone_no]);
                $value_address_1 = $cell[$key_address_1];
                $value_address_2 = $cell[$key_address_2];
                $value_language = trim($cell[$key_language]);
                $value_postal_code = $cell[$key_postal_code];
                $value_city = trim($cell[$key_city]);
                $value_state = trim($cell[$key_state]);
                $value_country = trim($cell[$key_country]);
                $value_branch = trim($cell[$key_branch]);
                $value_esimate_prospect_worth = trim($cell[$key_esimate_prospect_worth]);
                $value_number_type1 = trim($cell[$key_number_type1]);
                $value_number_type2 = trim($cell[$key_number_type2]);
                $value_phone_number1 = trim($cell[$key_phone_number1]);
                $value_phone_number2 = trim($cell[$key_phone_number2]);
                $value_inserted_products_services = trim($cell[$key_inserted_products_services]);
                $value_description = trim($cell[$key_description]);

                if (trim(strtolower($value_number_type1)) == 'home') {
                    $type1 = 1;
                }
                if (trim(strtolower($value_number_type2)) == 'home') {
                    $type2 = 1;
                }
                if (trim(strtolower($value_number_type1)) == 'mobile') {
                    $type1 = 2;
                }
                if (trim(strtolower($value_number_type2)) == 'mobile') {
                    $type2 = 2;
                }
                if (trim(strtolower($value_number_type1)) == 'office') {
                    $type1 = 3;
                }
                if (trim(strtolower($value_number_type2)) == 'office') {
                    $type2 = 3;
                }

                $language_arr = getLanguages();
                $language_id = 0 ;
                
                if($value_language != ''){
                    foreach ($language_arr as $language)
                    {
                        if(trim(strtolower($value_language)) == $language['language_name'])
                        {
                            $language_id = $language['language_id'];
                            break;
                        }
                    }
                }
                

                $branch_id = $this->Account_model->getBranchIdByName($value_branch);
                $company_id = $this->Account_model->getComapnyIdByName($value_company_name);
                $country_id = $this->Account_model->getCountryIdByName($value_country);
                $auto_generate_id = $this->common_model->account_auto_gen_Id();
                // Check Branch is exist or not if not then insert new branch into branch Master
                if ($branch_id == "") {
                    //Branch Master Data
                    $data_branch['branch_name'] = $value_branch;
                    $data_branch['created_date'] = datetimeformat();
                    $data_branch['modified_date'] = datetimeformat();
                    $data_branch['status'] = 1;
                    $get_branch_id = $this->common_model->insert(BRANCH_MASTER, $data_branch);
                    if ($get_branch_id != "") {
                        $branch_id = $get_branch_id;
                    }
                } else {
                    $branch_id = $branch_id;
                }

                // Check Company is exist or not if not then insert new company into Company Master
                if ($company_id == "") {

                    if ($value_company_website == NULL) {
                        $value_company_website = "";
                    }
                    if ($value_company_name != "" ) {
                        $get_company_id=0;
                        // Company Master Data
                        $data_company['country_id'] 	= $country_id;
                        $data_company['company_name'] = $value_company_name;
                        $data_company['email_id'] = $value_company_email_id;
                        $data_company['branch_id'] = $branch_id;
                        $data_company['website'] = $value_company_website;
                        $data_company['phone_no'] = $value_company_phone_no;
                        $data_company['created_date'] = datetimeformat();
                        $data_company['status'] = 1;
                        $data_company['is_delete'] = 0;
                        $data_company['created_date'] = datetimeformat();
                        $data_company['modified_date'] = datetimeformat();
                        if (filter_var($data_company['email_id'], FILTER_VALIDATE_EMAIL)) {
                            //$flg_record  = $this->common_model->insert(CONTACT_MASTER, $data);
                            $get_company_id = $this->common_model->insert(COMPANY_MASTER, $data_company);
                        } else {
                            $count_fail++;
                        }

                        if ($get_company_id != "") {
                            $company_id = $get_company_id;
                        }
                    }
                } else {
                    $company_id = $company_id;
                    if($value_branch == '')
                    {
                        $table_grp = COMPANY_MASTER;
                        $fields_grp = array("branch_id");
                        $match_grp = array('company_id' => $company_id);
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

                if ($value_account_name != '' && $company_id != '' && $value_description != '' && $country_id != 0) {
                    
                    //Create Account Code - check this company account exist or not otherwise make an account
					$table 			= PROSPECT_MASTER . ' as pm';
					$match 			= "pm.company_id = " . $company_id . " and status_type=2 and pm.is_delete=0";
					$fields 		= array("pm.company_id,pm.prospect_id");
					$companyExist 	= $this->common_model->get_records($table, $fields, '', '', $match);
					if (empty($companyExist)) {
						//get company data from company id
						$table 		= COMPANY_MASTER . ' as cm';
						$match 		= "cm.company_id = " . $company_id;
						$fields 	= array("cm.company_id,cm.company_name as prospect_name,cm.branch_id,cm.address1,cm.address2,cm.city,
										cm.state,cm.country_id,cm.postal_code");
						$companyData = $this->common_model->get_records($table, $fields, '', '', $match);
						$companyData[0]['prospect_auto_id'] = $this->common_model->client_auto_gen_Id();
						$companyData[0]['status_type'] 		= 2;
						$companyData[0]['creation_date'] 	= datetimeformat();
						$companyData[0]['created_date'] 	= datetimeformat();
						$companyData[0]['modified_date'] 	= datetimeformat();
						$companyData[0]['status'] 			= 1;
						$companyData[0]['prospect_owner_id'] = $this->session->userdata('LOGGED_IN')['ID'];
						//echo "<br>Create Account<br>";
						$CompanyAccountId = $this->common_model->insert(PROSPECT_MASTER, $companyData[0]);

						$opportunityRequirementAccount['requirement_description'] = $value_description;
						$opportunityRequirementAccount['modified_date'] = datetimeformat();
						$opportunityRequirementAccount['prospect_id'] = $CompanyAccountId;
						$opportunityRequirementAccount['created_date'] = datetimeformat();
                        $opportunityRequirementAccount['status'] = 1;
						$this->common_model->insert(OPPORTUNITY_REQUIREMENT, $opportunityRequirementAccount);
					}
                    
                    
                    if ($value_postal_code == NULL) {
                        $value_postal_code = "";
                    }
                    if ($value_address_2 == NULL) {
                        $value_address_2 = "";
                    }
                    if ($value_address_1 == NULL) {
                        $value_address_1 = "";
                    }

                    // Prospect Master Data

                    $data_prospect['prospect_assign'] = 0;
                    $data_prospect['prospect_related_id'] = 0;
                    $data_prospect['prospect_auto_id'] = $auto_generate_id;
                    $data_prospect['prospect_name'] = $value_account_name;
                    $data_prospect['status_type'] = 3;
                    $data_prospect['company_id'] = $company_id;
                    $data_prospect['address1'] = str_replace(';',',',$value_address_1);
                    $data_prospect['address2'] = str_replace(';',',',$value_address_2);
                    $data_prospect['creation_date'] = datetimeformat();
                    $data_prospect['language_id'] = $language_id;
                    $data_prospect['postal_code'] = $value_postal_code;
                    $data_prospect['city'] = $value_city;
                    $data_prospect['state'] = $value_state;
                    $data_prospect['country_id'] = $country_id;
                    $data_prospect['branch_id'] = $branch_id;
                    $data_prospect['estimate_prospect_worth'] = $value_esimate_prospect_worth;
                    $data_prospect['prospect_generate'] = 0;
                    $data_prospect['campaign_id'] = 0;
                    $data_prospect['number_type1'] = $type1;
                    $data_prospect['number_type2'] = $type2;
                    $data_prospect['phone_no'] = $value_phone_number1;
                    $data_prospect['phone_no2'] = $value_phone_number2;
                    $data_prospect['prospect_owner_id'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $data_prospect['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $data_prospect['creation_date'] = datetimeformat();
                    $data_prospect['created_date'] = datetimeformat();
                    $data_prospect['modified_date'] = datetimeformat();
                    $data_prospect['status'] = 1;
                    $data_prospect['is_delete'] = 0;
                    $prospectId = $this->common_model->insert(PROSPECT_MASTER, $data_prospect);

                    if ($prospectId) {

                        //blzdsk_opportunity_requirement
                        $data_opportunity_requirement['requirement_description'] = $value_description;
                        $data_opportunity_requirement['prospect_id'] = $prospectId;
                        $data_opportunity_requirement['created_date'] = datetimeformat();
                        $data_opportunity_requirement['modified_date'] = datetimeformat();
                        $data_opportunity_requirement['status'] = 1;

                        $opportunity_req_id = $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $data_opportunity_requirement);

                        if ($opportunity_req_id) {

                            $arry_products = explode(",", $value_inserted_products_services);

                            foreach ($arry_products as $k => $v) {
                                $productId = $this->Account_model->getProductIdByName($v);
                                if ($productId != "" && $prospectId != "") {
                                    $data_prospect_product_tran['product_id'] = $productId;
                                    $data_prospect_product_tran['prospect_id'] = $prospectId;
                                    $data_prospect_product_tran['created_date'] = datetimeformat();
                                    $data_prospect_product_tran['modified_date'] = datetimeformat();
                                    $data_prospect_product_tran['status'] = 1;
                                    $prospect_pro_trns_id = $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $data_prospect_product_tran);
                                }
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

        redirect($this->viewname);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : set is_delete =1 for delete data
      @Input    : get id
      @Output   : if success delete get message else error
      @Date     : 28/03/2016
     */

    public function deletedataSalesOverview() {
        $data['account_view'] = $this->viewname;
        $id = $this->input->get('id');
        //Delete Record From Database
        if (!empty($id)) {
            $where = array('prospect_id' => $id);
            $prospectData['is_delete'] = 1;
            $deleteSuceess = $this->common_model->update(PROSPECT_MASTER, $prospectData, $where);
            if ($deleteSuceess) {
                $msg = $this->lang->line('client_del_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            unset($id);
        }
        redirect('SalesOverview');
    }

    //Added  By Mehul Patel on 30/03/2016 for Export Accounts
    function exportAccounts() {
        $login_id = $this->session->userdata('LOGGED_IN')['ID'];
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = time() ."-". date('m-d-Y') . "_Client.csv";
        $table = PROSPECT_MASTER . ' as pm';
        $group_by = 'pm.prospect_id';
        $fields = array("pm.prospect_name as 'Client Name',"
            . "cm.company_name as 'Company Name',"
            . "cm.email_id as 'Company Email ID',"
            . "cm.website as 'Website',"
            . "cm.phone_no as 'Company Phone No',"
            . "pm.address1 as 'Address1',"
            . "pm.address2 as 'Address2',"
           // . "CASE pm.language_id WHEN pm.language_id = 1 THEN 'English' WHEN pm.language_id = 2 THEN 'Spanish' ELSE '' END as 'Language',"
          // updated by seema tankariya 07-07-2016
            . "lg.language_name as 'Language',"
            . "pm.postal_code as 'Postal Code',"
            . "pm.city as 'City',"
            . "pm.state as 'State',"
            . "c.country_name as 'Country',"
            . "bm.branch_name as 'Branch',"
            . "pm.estimate_prospect_worth as 'Estimate Prospect Worth',"
            . "CASE pm.number_type1 WHEN  1 THEN 'Home' WHEN 2 THEN 'Mobile' WHEN  3 THEN 'Office'  ELSE '' END as 'NumberType1',"
            . "pm.phone_no as 'PhoneNumber1',"
            . "CASE pm.number_type2 WHEN 1 THEN 'Home' WHEN  2 THEN 'Mobile' WHEN  3 THEN 'Office'  ELSE '' END as 'NumberType2',"
            . "pm.phone_no2 as 'PhoneNumber2',"
            . "GROUP_CONCAT(pm1.product_name) as 'Interested Products',"
            . "or.requirement_description as 'Description'");

        $data['sortField'] = 'pm.created_date';
        $data['sortOrder'] = 'desc';
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join(COUNTRIES . ' as c', 'c.country_id=pm.country_id', 'left');
        $this->db->join(COMPANY_MASTER . ' as cm', 'cm.company_id=pm.company_id', 'left');
        $this->db->join(BRANCH_MASTER . ' as bm', 'bm.branch_id=pm.branch_id', 'left');
        $this->db->join(PROSPECT_PRODUCTS_TRAN . ' as ppt', 'ppt.prospect_id=pm.prospect_id', 'left');
        $this->db->join(PRODUCT_MASTER . ' as pm1', 'pm1.product_id=ppt.product_id', 'left');
        $this->db->join(OPPORTUNITY_REQUIREMENT . ' as or', 'or.prospect_id=pm.prospect_id', 'left');
         // updated by seema tankariya 07-07-2016
        $this->db->join(LANGUAGE_MASTER . ' as lg', 'lg.language_id=pm.language_id', 'left');
        // end
        $this->db->where('pm.status', '1', false);
        $this->db->where('pm.status_type', '2', false);
        $this->db->where('pm.is_delete', '0', false);
        //$this->db->where('pm.created_by', $login_id, false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->group_by($group_by);
        $dataarr = $this->db->get();
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);

        force_download($filename, $data1);
        redirect('Account');
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

    function getContactEmailbyId($arr_contact_id) {
        $temp_arr_str = implode(",", $arr_contact_id);


        $table1 = CONTACT_MASTER . ' as l';
        $match1 = "l.contact_id IN (" . rtrim($temp_arr_str, ',') . ")";
        $fields1 = array("l.email");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        $str_email = '';
        foreach ($user_data as $user_email) {
            $str_email.= $user_email['email'] . ",";
        }
        $tmp_str_email = rtrim($str_email, ",");
        return $tmp_str_email;
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : save contact data added from view
      @Input    : get id
      @Output   : if success added contact and display in view
      @Date     : 13/05/2016
     */

    function saveContactData() {

        $redirectLink = $_SERVER['HTTP_REFERER'];
        $id = $this->input->post('prospect_id');
        if (!empty($id)) {
            //Get Records From PROSPECT_MASTER Table
            $params['join_tables'] = array(
                OPPORTUNITY_REQUIREMENT . ' as or' => 'or.prospect_id=pm.prospect_id');
            $params['join_type'] = 'left';

            $table1 = PROSPECT_MASTER . ' as pm';
            $match1 = " pm.prospect_id=" . $id;
            $fields1 = array("pm.company_id,pm.address1,pm.address2,pm.postal_code,pm.city,pm.state,pm.language_id,pm.country_id,or.requirement_id");
            $prospectData = $this->common_model->get_records($table1, $fields1, $params['join_tables'], $params['join_type'], $match1);

            $contactMasterData['status'] = 1;
            $contactMasterData['created_date'] = datetimeformat();
            $contactMasterData['modified_date'] = datetimeformat();
            $contactMasterData['company_id'] = $prospectData[0]['company_id'];
            $contactMasterData['address1'] = $prospectData[0]['address1'];
            $contactMasterData['address2'] = $prospectData[0]['address2'];
            $contactMasterData['postal_code'] = $prospectData[0]['postal_code'];
            $contactMasterData['city'] = $prospectData[0]['city'];
            $contactMasterData['state'] = $prospectData[0]['state'];
            $contactMasterData['language_id'] = $prospectData[0]['language_id'];
            $contactMasterData['country_id'] = $prospectData[0]['country_id'];
            $contacEmail = $this->input->post('email_id_account');
            $contactNumber = $this->input->post('phone_no_account');
            $contactName = $this->input->post('contact_name_account');
            $primaryContact = $this->input->post('primary_contact');
            $contactMasterData['email'] = $contacEmail[0];
            $contactMasterData['mobile_number'] = $contactNumber[0];
            $contactMasterData['contact_name'] = $contactName[0];

            if ($primaryContact[1]) {
                $contactMasterData['primary_contact'] = 1;
            } else {
                //Check primary for this company exist or not
                $table23 = CONTACT_MASTER . ' as cm';
                $match23 = "cm.company_id='" . $prospectData[0]['company_id'] . "' and cm.is_delete=0 and primary_contact=1  and cm.status=1 ";
                $fields23 = array("cm.*");
                $primary_exist = $this->common_model->get_records($table23, $fields23, '', '', $match23);

                if ($primary_exist) {
                    $contactMasterData['primary_contact'] = 0;
                } else {
                    $contactMasterData['primary_contact'] = 1;
                }
            }
            $contactId = $this->common_model->insert(CONTACT_MASTER, $contactMasterData);

            if ($primaryContact[1]) {

                $where = array('prospect_id' => $id);
                $contactData['primary_contact'] = 0;
                $this->common_model->update(OPPORTUNITY_REQUIREMENT_CONTACTS, $contactData, $where);
                $prospectContactsTran['primary_contact'] = 1;
            }

            $prospectContactsTran['prospect_id'] = $id;
            $prospectContactsTran['status'] = 1;
            $prospectContactsTran['created_date'] = datetimeformat();
            $prospectContactsTran['modified_date'] = datetimeformat();
            $prospectContactsTran['contact_id'] = $contactId;
            if ($prospectContactsTran['requirement_id']) {
                $prospectContactsTran['requirement_id'] = $prospectData[0]['requirement_id'];
            }
            $this->common_model->insert(OPPORTUNITY_REQUIREMENT_CONTACTS, $prospectContactsTran);
            redirect($redirectLink);
        }
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : chnage primary contact from view account
      @Input    : get id
      @Output   : if success primary display
      @Date     : 13/05/2016
     */

    function changePrimaryStatus() {

        $redirectLink = $_SERVER['HTTP_REFERER'];
        $companytId = $this->input->post('company_id');

        $contactId = $this->input->post('contact_id');
        $where = array('company_id' => $companytId);
        $contactData['primary_contact'] = 0;
        $contactData['modified_date'] = datetimeformat();
        //set primary=0 for all data
        $this->common_model->update(CONTACT_MASTER, $contactData, $where);
        //set primary=1 for select contact id
        $whereStatus = array('contact_id' => $contactId);
        $updateContctStatus['primary_contact'] = 1;
        $updateContctStatus['modified_date'] = datetimeformat();
        $this->common_model->update(CONTACT_MASTER, $updateContctStatus, $whereStatus);

        echo json_encode(array('status' => 1));
        die;
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get post company id and view that's estimate data
      @Input    : get id
      @Output   : view data
      @Date     : 18/05/2016
     */

    function viewEstimateData() {

        $companyId = $this->input->post('company_id');
        if ($companyId) {
            $companyId = $this->input->post('company_id');
        } else {
            $companyId = $_SESSION['current_company_id'];
        }
        
        $table1 = PROSPECT_MASTER . ' as pm';
        $match1 = " pm.company_id=" . $companyId . " and status_type=3";
        $fields1 = array("pm.prospect_id");
        $prospectIdData = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        foreach ($prospectIdData as $prospectID) {
            $prospectIds[] = $prospectID['prospect_id'];
        }

        if (!empty($prospectIds)) {
            $where_in = array("ec.recipient_id" => $prospectIds);
        } else {
            $where_in = array("ec.recipient_id" => '');
        }
        
        $data['company_id'] = $companyId;
        
        $table1 = ESTIMATE_CLIENT . ' as ec';
        $where = array(" ec.recipient_type " => "'client'");
        $fields1 = array("distinct(ec.estimate_id)");
       // $prospectData = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        $prospectData = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', $where, '', $where_in);
        
        foreach ($prospectData as $estimateID) {
            $estimateData[] = $estimateID['estimate_id'];
        }

        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if ($searchtext == 'clearData') {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) {

            $where_search = '(em.estimate_auto_id LIKE "%' . $searchtext . '%" OR em.value LIKE "%' . $searchtext . '%" OR em.created_date LIKE "%' . $searchtext . '%" OR em.due_date LIKE "%' . $searchtext . '%" OR em.send_date LIKE "%' . $searchtext . '%" OR  concat(l.firstname," ",l.lastname) LIKE "%' . $searchtext . '%")';
            $this->session->set_userdata('searchtext', $searchtext);
        } else if (!empty($session_searchtext)) {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(em.estimate_auto_id LIKE "%' . $searchtext . '%" OR em.value LIKE "%' . $searchtext . '%" OR em.created_date LIKE "%' . $searchtext . '%" OR em.due_date LIKE "%' . $searchtext . '%" OR em.send_date LIKE "%' . $searchtext . '%" OR  concat(l.firstname," ",l.lastname) LIKE "%' . $searchtext . '%")';
        } else {
            $this->session->set_userdata('searchtext', '');
        }



        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;

        $config['base_url'] = site_url($data['project_view'] . '/viewEstimateData');
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $data['tasksortField'] = 'estimate_id';
        $data['tasksortOrder'] = 'desc';
        $params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=em.login_id');
        $params['join_type'] = 'left';
        $fieldsEstimate = array('em.estimate_id,em.estimate_auto_id,em.value,em.created_date,em.due_date,em.send_date,em.login_id,l.firstname,l.lastname');
        $where = ' em.status=1 ';

        if (!empty($estimateData)) {
            $where_in = array("em.estimate_id" => $estimateData);
        } else {
            $where_in = array("em.estimate_id" => 0);
        }
        $config['total_rows'] = count($this->common_model->get_records(ESTIMATE_MASTER . ' as em', $fieldsEstimate, $params['join_tables'], $params['join_type'], '', '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where, '', $where_in, '', '', '', $where_search));
        $config['per_page'] = 5;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['estimate_data'] = $this->common_model->get_records(ESTIMATE_MASTER . ' as em', $fieldsEstimate, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where, '', $where_in, '', '', '', $where_search);

        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];

        $data['pagination'] = $this->pagingConfig($config, $page_url);

        $data['main_content'] = '/AjaxEstimates';
        $this->load->view('/AjaxEstimates', $data);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get post company id and view deals data
      @Input    : get id
      @Output   : view data
      @Date     : 18/05/2016
     */

    function viewAllDealsData() {
        $companyId = $this->input->post('company_id');
        if ($companyId != "") {
            $companyId = $this->input->post('company_id');
        } else {
            $companyId = $_SESSION['current_company_id'];
        }

        $data['company_id'] = $companyId;

        $redirect_link = $_SERVER['HTTP_REFERER'];

        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if ($searchtext == 'clearData') {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) {
            $clientSince = date('Y-m-d', strtotime($searchtext));
            $where_search = '(pm.prospect_name LIKE "%' . $searchtext . '%" OR pm.prospect_auto_id LIKE "%' . $searchtext . '%" OR pm.status_type LIKE "%' . $searchtext . '%" OR pm.creation_date LIKE "%' . $clientSince . '%"  OR cm.contact_name LIKE "%' . $searchtext . '%")';
            $this->session->set_userdata('searchtext', $searchtext);
        } else if (!empty($session_searchtext)) {
            $clientSince = date('Y-m-d', strtotime($searchtext));
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(pm.prospect_name LIKE "%' . $searchtext . '%" OR pm.prospect_auto_id LIKE "%' . $searchtext . '%" OR pm.status_type LIKE "%' . $searchtext . '%" OR pm.creation_date LIKE "%' . $clientSince . '%"  OR cm.contact_name LIKE "%' . $searchtext . '%")';
        } else {
            $this->session->set_userdata('searchtext', '');
        }

        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;

        $params['join_tables'] = array(
            OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id ');
        $params['join_type'] = 'left';

        $config['base_url'] = site_url($data['project_view'] . '/viewAllDealsData');
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $data['tasksortField'] = 'prospect_id';
        $data['tasksortOrder'] = 'desc';
        /* $fields = array("pm.*"); */
        $fields = array("pm.prospect_id,pm.prospect_assign,pm.prospect_related_id,pm.prospect_name,pm.prospect_auto_id, pm.status,pm.status_type,pm.creation_date,count(orc.prospect_id) as contact_count,cm.contact_name");
        $where = array("pm.is_delete" => "0", "pm.status" => "1", "pm.company_id" => $companyId);
        $group_by = 'pm.prospect_id';
        $config['total_rows'] = count($this->common_model->get_records(PROSPECT_MASTER . ' as pm', $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $data['tasksortField'], $data['tasksortOrder'], $group_by, $where, '', '', '', '', '', $where_search));

        $config['per_page'] = 5;
        $choice = $config["total_rows"] / $config["per_page"];

        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['prospect_data'] = $this->common_model->get_records(PROSPECT_MASTER . ' as pm', $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], $group_by, $where, '', '', '', '', '', $where_search);

        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];

        $data['pagination'] = $this->pagingConfig($config, $page_url);


        $data['main_content'] = '/AjaxDeals';
        $this->load->view('/AjaxDeals', $data);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get post company id and view communication data
      @Input    : get id
      @Output   : view data
      @Date     : 18/05/2016
     */

    function getCommunicationData() {
        $companyId = $this->input->post('company_id');
        if ($companyId) {
            $companyId = $this->input->post('company_id');
        } else {
            //  $companyId=$this->input->get('company_id');
            $companyId = $_SESSION['current_company_id'];
        }
        $data['company_id'] = $companyId;
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if ($searchtext == 'clearData') {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) {
            $created_date = date('Y-m-d', strtotime($searchtext));
            $where_search = '(ep.subject LIKE "%' . $searchtext . '%" OR ep.created_date LIKE "%' . $created_date . '%" OR  concat(l.firstname," ",l.lastname) LIKE "%' . $searchtext . '%")';
            $this->session->set_userdata('searchtext', $searchtext);
        } else if (!empty($session_searchtext)) {
            $created_date = date('Y-m-d', strtotime($searchtext));
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(ep.subject LIKE "%' . $searchtext1 . '%" OR ep.created_date LIKE "%' . $created_date . '%" OR  concat(l.firstname," ",l.lastname) LIKE "%' . $searchtext . '%")';
        } else {
            $this->session->set_userdata('searchtext', '');
        }

        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;

        //config variable for the pagination
        $config['base_url'] = base_url('Account') . '/getCommunicationData';

        $data['tasksortField'] = 'email_prospect_id';
        $data['tasksortOrder'] = 'DESC';
        $params['join_tables'] = array(TBL_EMAIL_PROSPECT . ' as ep' => 'ep.email_prospect_id = ec.email_prospect_id', LOGIN . ' as l' => 'l.login_id=ec.comm_sender', COMPANY_MASTER . ' as cm' => 'cm.company_id=ep.company_id');
        $params['join_type'] = 'left';
        $fields = array(" ec.comm_id,ec.comm_receiver,ep.email_prospect_id,ep.created_date,ep.subject,ep.prospect_owner_id,cm.company_name,l.firstname,l.lastname,(SELECT GROUP_CONCAT(cm.contact_name) FROM blzdsk_contact_master as cm WHERE FIND_IN_SET(cm.contact_id, ec.comm_receiver) > 0) as receiver_name");
        //$fields = array("td.*,(SELECT CONCAT(l.firstname,' ',l.lastname) FROM  blzdsk_login as l WHERE l.login_id=td.comm_sender) as sender_name,(SELECT GROUP_CONCAT(cm.contact_name) FROM blzdsk_contact_master as cm WHERE FIND_IN_SET(cm.contact_id, td.comm_receiver) > 0) as receiver_name");
        $where = array("ep.status" => "1");
        $match = " ep.company_id=" . $companyId;
        $config['total_rows'] = count($this->common_model->get_records(TBL_EMAIL_COMMUNICATION . ' as ec', $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where, '', '', '', '', '', $where_search));
        $config['per_page'] = 5;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['communication_data'] = $this->common_model->get_records(TBL_EMAIL_COMMUNICATION . ' as ec', $fields, $params['join_tables'], $params['join_type'], $match, '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where, '', '', '', '', '', $where_search);


        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        //$this->pagination->initialize($config);
        $data['pagination'] = $this->pagingConfig($config, $page_url);

        $data['main_content'] = '/AjaxCommunication';
        $this->load->view('/AjaxCommunication', $data);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get post company id and view support data
      @Input    : get id
      @Output   : view data
      @Date     : 19/05/2016
     */

    function viewSupportData() {
        $companyId = $this->input->post('company_id');
        if ($companyId) {
            $companyId = $this->input->post('company_id');
        } else {
            //  $companyId=$this->input->get('company_id');
            $companyId = $_SESSION['current_company_id'];
        }
        $data['company_id'] = $companyId;
        $table1 = PROSPECT_MASTER . ' as pm';
        $match1 = " pm.company_id=" . $companyId . " and status_type=3";
        $fields1 = array("pm.prospect_id");
        $prospectData = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        foreach ($prospectData as $prospectID) {
            $prospectIds[] = $prospectID['prospect_id'];
        }
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if ($searchtext == 'clearData') {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) {
            $created_date = date('Y-m-d', strtotime($searchtext));
            $where_search = '(tm.ticket_id LIKE "%' . $searchtext . '%" OR tm.ticket_subject LIKE "%' . $searchtext . '%" OR  tm.created_date LIKE "%' . $created_date . '%" OR  tm.due_date LIKE "%' . $created_date . '%")';
            $this->session->set_userdata('searchtext', $searchtext);
        } else if (!empty($session_searchtext)) {
            $created_date = date('Y-m-d', strtotime($searchtext));
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(tm.ticket_id "%' . $searchtext . '%" OR tm.ticket_subject LIKE "%' . $searchtext . '%" OR  tm.created_date LIKE "%' . $created_date . '%" OR  tm.due_date LIKE "%' . $created_date . '%")';
        } else {
            $this->session->set_userdata('searchtext', '');
        }

        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;

        //config variable for the pagination
        $config['base_url'] = base_url('Account') . '/viewSupportData';

        $data['tasksortField'] = 'ticket_id';
        $data['tasksortOrder'] = 'DESC';

        $fields = array("tm.ticket_id,tm.client_id,tm.ticket_subject,tm.status,tm.created_date,tm.due_date");
        $where = array("tm.is_delete" => "0");

        if (!empty($prospectIds)) {
            $where_in = array("tm.client_id" => $prospectIds);
        } else {
            $where_in = array("tm.client_id" => '');
        }


        $match = "";
        $config['total_rows'] = count($this->common_model->get_records(TICKET_MASTER . ' as tm', $fields, '', '', $match, '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where, '', $where_in, '', '', '', $where_search));
        $config['per_page'] = 5;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['support_data'] = $this->common_model->get_records(TICKET_MASTER . ' as tm', $fields, '', '', $match, '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where, '', $where_in, '', '', '', $where_search);
       
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        //$this->pagination->initialize($config);
        $data['pagination'] = $this->pagingConfig($config, $page_url);

        $data['main_content'] = '/AjaxSupportTicket';
        $this->load->view('/AjaxSupportTicket', $data);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get post company id and view project data
      @Input    : get id
      @Output   : view data
      @Date     : 19/05/2016
     */

    function viewProjectsData() {
        $companyId = $this->input->post('company_id');
        if ($companyId) {
            $companyId = $this->input->post('company_id');
        } else {
            //  $companyId=$this->input->get('company_id');
            $companyId = $_SESSION['current_company_id'];
        }
        $data['company_id'] = $companyId;

        $table1 = PROSPECT_MASTER . ' as pm';
        $match1 = " pm.company_id=" . $companyId . " and status_type=3";
        $fields1 = array("pm.prospect_id");
        $prospectData = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        foreach ($prospectData as $prospectID) {
            $prospectIds[] = $prospectID['prospect_id'];
        }
        
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if ($searchtext == 'clearData') {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) {
            $created_date = date('Y-m-d', strtotime($searchtext));
            $where_search = '(pr.project_code LIKE "%' . $searchtext . '%" OR pr.project_name LIKE "%' . $searchtext . '%" OR pr.project_budget LIKE "%' . $searchtext . '%" OR pr.start_date LIKE "%' . $created_date . '%" OR pr.due_date LIKE "%' . $created_date . '%" )';
            $this->session->set_userdata('searchtext', $searchtext);
        } else if (!empty($session_searchtext)) {
            $created_date = date('Y-m-d', strtotime($searchtext));
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(ep.subject LIKE "%' . $searchtext1 . '%" OR ep.created_date LIKE "%' . $created_date . '%" OR  concat(l.firstname," ",l.lastname) LIKE "%' . $searchtext . '%")';
        } else {
            $this->session->set_userdata('searchtext', '');
        }

        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;

        //config variable for the pagination
        $config['base_url'] = base_url('Account') . '/viewProjectsData';

        $data['tasksortField'] = 'project_id';
        $data['tasksortOrder'] = 'DESC';
        $fields = array("pr.project_id,pr.project_code,pr.project_name,pr.project_budget,pr.start_date,pr.due_date");
        $where = array("pr.is_delete" => "0", "pr.status" => "1",  "pr.client_type" => "'client'");
       
        if (!empty($prospectIds)) {
            $where_in = array("pr.client_id" => $prospectIds);
        } else {
            $where_in = array("pr.client_id" => '');
        }
       
        $match = "";
        $config['total_rows'] = count($this->common_model->get_records(PROJECT_MASTER . ' as pr', $fields, '', '', $match, '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where, '', $where_in, '', '', '', $where_search));
        $config['per_page'] = 5;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['project_data'] = $this->common_model->get_records(PROJECT_MASTER . ' as pr', $fields, '', '', $match, '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where, '', $where_in, '', '', '', $where_search);

        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        //$this->pagination->initialize($config);
        $data['pagination'] = $this->pagingConfig($config, $page_url);

        $data['main_content'] = '/AjaxProjects';
        $this->load->view('/AjaxProjects', $data);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get post company id and view files data
      @Input    : get id
      @Output   : view data
      @Date     : 19/05/2016
     */

    function getAccountFile() {
        $redirect_link = $_SERVER['HTTP_REFERER'];
        $prospectId = $this->input->post('prospect_id');

        $data['prospect_id'] = $prospectId;

        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if ($searchtext == 'clearData') {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) {

            $where_search = '(flm.file_name LIKE "%' . $searchtext . '%" )';
            $this->session->set_userdata('searchtext', $searchtext);
        } else if (!empty($session_searchtext)) {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(flm.file_name LIKE "%' . $searchtext1 . '%" )';
        } else {
            $this->session->set_userdata('searchtext', '');
        }

        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;

        $config['base_url'] = site_url($data['project_view'] . '/getAccountFile');
        $data['tasksortField'] = 'file_id';
        $data['tasksortOrder'] = 'desc';

        $table = FILES_SALES_MASTER . ' as flm';
        $where = array("flm.prospect_id" => $prospectId);
        $fields = array("flm.prospect_id,flm.file_id,flm.file_name,flm.file_path,flm.type");

        $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', $where_search);
        $config['per_page'] = 20;
        $choice = $config["total_rows"] / $config["per_page"];

        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['file_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where, '', '', '', '', '', $where_search);

        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        $data['pagination'] = $this->pagingConfig($config, $page_url);

        $data['main_content'] = '/AjaxAccountFile';
        $this->load->view('/AjaxAccountFile', $data);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get post file id and delete file
      @Input    : get id
      @Output   : delete data
      @Date     : 19/05/2016
     */

    public function deleteAccountAttach() {

        $id = $this->input->get('file_id');
        $redirect_link = $this->input->get('redirect_link');
        if ($id) {
            $url = $this->input->get('file_path');
            $where = array('file_id' => $id);

            $delete_suceess = $this->common_model->delete(FILES_SALES_MASTER, $where);
            unlink(BASEPATH . '../' . $url);
            if ($delete_suceess) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get post company id and view all contact data
      @Input    : get id
      @Output   : view data
      @Date     : 20/05/2016
     */

    function getContactData() {
        $companyId = $this->input->post('company_id');
        if ($companyId) {
            $companyId = $this->input->post('company_id');
        } else {
            //  $companyId=$this->input->get('company_id');
            $companyId = $_SESSION['current_company_id'];
        }
        $data['company_id'] = $companyId;

        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if ($searchtext == 'clearData') {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) {

            $where_search = '(cm.contact_name  LIKE "%' . $searchtext . '%" OR cm.email LIKE "%' . $searchtext . '%"OR cm.mobile_number LIKE "%' . $searchtext . '%")';
            $this->session->set_userdata('searchtext', $searchtext);
        } else if (!empty($session_searchtext)) {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(cm.contact_name  LIKE "%' . $searchtext . '%" OR cm.email LIKE "%' . $searchtext . '%"OR cm.mobile_number LIKE "%' . $searchtext . '%")';
        } else {
            $this->session->set_userdata('searchtext', '');
        }

        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;

        $config['base_url'] = site_url($data['project_view'] . '/getContactData');
        $data['tasksortField'] = 'cm.contact_id';
        $data['tasksortOrder'] = 'desc';

        //Get Records From CONTACT_MASTER Table

        $where = "cm.company_id = " . $companyId . " and cm.is_delete=0 and cm.status=1 ";
        $table = CONTACT_MASTER . ' as cm';
        $fields = array("cm.contact_id,cm.company_id,cm.contact_name,cm.mobile_number,cm.email,cm.primary_contact");
        $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', $where_search);
        $config['per_page'] = 5;
        $choice = $config["total_rows"] / $config["per_page"];

        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['opportunity_contact_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where, '', '', '', '', '', $where_search);

        //Last primary select
        $primaryFields = array(" cm.contact_id ");
        $group_by = 'cm.contact_id';
        $where.= " and cm.primary_contact=1  ";
        $data['primarysortField'] = "cm.modified_date";
        $data['primarysortOrder'] = "desc";
        $data['primaryData'] = $this->common_model->get_records($table, $primaryFields, '', '', '', '', '', '', $data['primarysortField'], $data['primarysortOrder'], '', $where, '', $group_by, '', '', '', $where_search);
        $config['first_link'] = 'First';
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        $data['pagination'] = $this->pagingConfig($config, $page_url);

        $data['main_content'] = '/AjaxContacts';
        $this->load->view('/AjaxContacts', $data);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : for pagination
      @Input    : total row and total page
      @Output   : create link for pagination
      @Date     : 20/05/2016
     */

    private function pagingConfig($config, $page_url) {
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01 pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . $page_url . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        //$config['first_link'] = '&lt;&lt;';
        //$config['last_link'] = '&gt;&gt;';
        $config['num_links'] = 2;

        $this->pagination->cur_page = 4;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : insert data if not have an id otherwise update data
      @Input    : input post data
      @Output   : inserdata in table
      @Date     : 04/06/2016
     */

    /*     * ************************************************************************************************

     * Check if have id then update data otherwise insertdata

      1)  When click on Create Client or Update Client this function insert data in related table.
      2)  Get input post data .
      3)  If input post branch name exist in BRANCH_MASTER then take branch id.
      4)  If input post branch name not exist in BRANCH_MASTER then insert that branch in BRANCH_MASTER
      and get inserted branch id.
      5)  If input post company select from list then get company_id.
      6)  If input post company is Add New Comapny then get company data and insert in COMPANY_MASTER
      and get inserted id.
      7)  If company not have an account create account with status type=2
      8)  If have client owner then get detail and send email to that client.
      9)  If update client name also update in ESTIMATE_CLIENT table.
      10) If have input post campaign then insert  in TBL_CAMPAIGN_CONTACT and update then update it.
      11) Insert all files and documents in FILES_SALES_MASTER.
      12) If have any deleted file or document then delete form FILES_SALES_MASTER and unlink.
      13) Insert description in OPPORTUNITY_REQUIREMENT table
      14) If have deleted contact then delete from CONTACT_MASTER
      15) Insert contact data in CONTACT_MASTER and  relational table is OPPORTUNITY_REQUIREMENT_CONTACTS
      16) If have input post produts then insert in PROSPECT_PRODUCTS_TRAN table

     * ************************************************************************************************* */

    public function insertLostdata() {
        // $module_url = $this->input->post('redirect_link');


        $id = '';
        $opportunityRequirementId = '';
        //Get input post prospect_id if have
        if ($this->input->post('prospect_id')) {
            $id = $this->input->post('prospect_id');
        }
        //Get input post requirement_id if have
        if ($this->input->post('requirement_id')) {
            $opportunityRequirementId = $this->input->post('requirement_id');
        }

        $redirectLink = $_SERVER['HTTP_REFERER'];
        //$redirectLink = $this->input->post('redirect_link');
        //if (strpos($redirectLink, 'lostClient') !== false) {
        //
        //    $redirectLink = base_url() . "Account/lostClient";
        //} elseif (strpos($redirectLink, 'Account/viewdata') !== false) {
        //    $redirectLink = $redirectLink;
        //} elseif (strpos($redirectLink, 'Account/viewLostClient') !== false) {
        //    $redirectLink = $redirectLink;
        //} else {
        //    $redirectLink = base_url() . "Account";
        //}
        if (!validateFormSecret()) {

            redirect($redirectLink); //Redirect On Listing page
        }

        $data = array();
        $data['account_view'] = $this->viewname;
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $prospectData['prospect_name'] = trim(strip_slashes($this->input->post('prospect_name')));
        $prospectData['address1'] = strip_slashes($this->input->post('address1'));
        $prospectData['address2'] = strip_slashes($this->input->post('address2'));
        $prospectData['creation_date'] = date_format(date_create($this->input->post('creation_date')), 'Y-m-d');
        $prospectData['postal_code'] = strip_slashes($this->input->post('postal_code'));
        $prospectData['city'] = strip_slashes($this->input->post('city'));
        $prospectData['state'] = strip_slashes($this->input->post('state'));
        $prospectData['number_type1'] = $this->input->post('number_type1');
        $prospectData['phone_no'] = $this->input->post('phone_no1_account');
        $prospectData['number_type2'] = $this->input->post('number_type2');
        $prospectData['phone_no2'] = $this->input->post('phone_no2_account');
        $prospectData['language_id'] = $this->input->post('language_id');
        $prospectData['fb'] = $this->input->post('fb');
        $prospectData['twitter'] = $this->input->post('twitter');
        $prospectData['linkedin'] = $this->input->post('linkedin');
        $prospectData['credit_limit'] = $this->input->post('credit_limit');
        $prospect_owner_id = $this->input->post('prospect_owner_id');
        $prospectData['prospect_owner_id'] = $prospect_owner_id;
        $branchName = strip_slashes($this->input->post('branch_id'));
        //Get Branch id From BRANCH_MASTER Table       
        $table22 = BRANCH_MASTER . ' as bm';
        $match22 = "bm.branch_name='" . addslashes($branchName) . "' and status=1 ";
        $fields22 = array("bm.branch_name, bm.branch_id");
        $branchRecord = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        if ($branchRecord) {
            $prospectData['branch_id'] = $branchRecord[0]['branch_id'];
        } else {
            $branchData['branch_name'] = $branchName;
        }
        if (count($branchRecord) == 0) {
            //INSERT Branch
            $branchData['created_date'] = datetimeformat();
            $branchData['modified_date'] = datetimeformat();
            $branchData['status'] = 1;
            $branch_id = $this->common_model->insert(BRANCH_MASTER, $branchData);
            $prospectData['branch_id'] = $branch_id;
        }
        //Get input post data for COMPANY_MASTER if want to add new company

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
                $companyData['company_name'] = trim(strip_slashes($this->input->post('company_name')));
                $companyData['email_id'] = $this->input->post('email_id_company');
                $companyData['website'] = $this->input->post('website');
                $companyData['company_id_data'] = $this->input->post('company_id_data');
                $companyData['reg_number'] = $this->input->post('com_reg_number');
                $companyData['branch_id'] = $prospectData['branch_id'];
                $companyData['address1'] = strip_slashes($this->input->post('address1'));
                $companyData['address2'] = strip_slashes($this->input->post('address2'));
                $companyData['postal_code'] = strip_slashes($this->input->post('postal_code'));
                $companyData['city'] = strip_slashes($this->input->post('city'));
                $companyData['state'] = strip_slashes($this->input->post('state'));
                $companyData['country_id'] = $this->input->post('country_id');
                $companyData['status'] = 1;
                $companyData['is_delete'] = 0;
                $companyData['phone_no'] = $this->input->post('phone_no_company');
                $companyData['created_date'] = datetimeformat();
                $companyData['modified_date'] = datetimeformat();

                if (($_FILES['logo_image']['name']) != NULL) {
                    $config = array(
                        'upload_path' => FCPATH . "uploads/company/",
                        'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
                        'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    );
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('logo_image')) {
                        $fileData = array('upload_data' => $this->upload->data());
                        foreach ($fileData as $file) {
                            $companyData['logo_img'] = $file['file_name'];
                        }
                    } else {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                    }
                }
                $prospectData['company_id'] = $this->common_model->insert(COMPANY_MASTER, $companyData);
            } //Else Select From Company List
            else {
                $company_id = $this->input->post('company_id');
                $prospectData['company_id'] = $company_id;
            }
        }

        /* Store company data in master admin */
        $companyData['company_name'] = trim(strip_slashes($this->input->post('company_name')));
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

        $table_master = COMPANY . ' as cm';
        $match_master = "cm.company_name='" . addslashes($company_name) . "' and cm.status=1 ";
        $fields_master = array("cm.*");
        $company_master = $this->common_model->get_records_data($table_master, $fields_master, '', '', $match_master);

        if (count($company_master) == 0) {
            $contactdata['company_id'] = $this->common_model->insert_data(COMPANY, $companyData);
        } else {
            $where = array('company_id' => $company_master[0]['company_id']);
            $this->common_model->update_data(COMPANY, $companyData, $where);
        }

        /* end */

        $prospectData['country_id'] = $this->input->post('country_id');
        $prospectData['estimate_prospect_worth'] = $this->input->post('estimate_prospect_worth');

        $prospect_generate = '0';
        if ($this->input->post('prospect_generate') == 'on') {
            $prospect_generate = '1';
            if ($this->input->post('campaign_id')) {
                $prospectData['campaign_id'] = $this->input->post('campaign_id');
            }
        } else {
            $prospectData['campaign_id'] = '';
        }

        $prospectData['prospect_generate'] = $prospect_generate;


        if ($this->input->post('contact_date')) {
            $prospectData['contact_date'] = date_format(date_create($this->input->post('contact_date')), 'Y-m-d');
        } else {
            $prospectData['contact_date'] = '';
        }
        $prospectData['status'] = 1;
        //status_type=4 for Lost Client

        $prospectData['status_type'] = 4;

        $prospectData['modified_date'] = datetimeformat();
        $prospectData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        //Update Record in Database
        if ($id) {

            /*
             * gets current data of current opportunity 
             */
            $params['join_tables'] = array(
                COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                LANGUAGE_MASTER . ' as lan' => 'lan.language_id=pm.language_id',
                COUNTRIES . ' as c' => 'c.country_id=pm.country_id',
                CONTACT_MASTER . ' as ct' => 'pm.prospect_owner_id=ct.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id;
            $table = PROSPECT_MASTER . ' as pm';
            $group_by = 'pm.prospect_id';
            $fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
                . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.city,pm.state,pm.country_id,pm.number_type1,"
                . "pm.phone_no as phone_number,pm.phone_no2,pm.prospect_owner_id,lan.language_name,"
                . "pm.branch_id,pm.estimate_prospect_worth,pm.prospect_generate,pm.campaign_id,pm.description,"
                . "cm.company_name,cm.logo_img,c.country_name,ct.contact_name");
            $currentProspectData = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);
            if (count($currentProspectData) > 0) {
                $prospectOwnerOldId = $currentProspectData[0]['prospect_owner_id'];
            }

            /*
             * will sends email if prospect owner gets changed
             */
            if ($prospectOwnerOldId != $prospect_owner_id) {


                $umatch = "login_id =" . $prospect_owner_id;
                $ufields = array("concat(firstname,' ',lastname) as fullname,email");
                $newProspectOwnerData = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);

                if (count($newProspectOwnerData) > 0) {
                    $prospectOwnerEmail = $newProspectOwnerData[0]['email'];
                    $prospectOwnerName = $newProspectOwnerData[0]['fullname'];
                    $prospectName = $prospectData['prospect_name'];
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
                        $url = base_url('Account/viewdata/' . $id);
                        $replace = array(
                            'USER' => $prospectOwnerName,
                            'OPPORTUNITY_NAME' => $prospectName,
                            'LINK' => "<a href='" . $url . "' title='view opportunity'>View</a>",
                            'TYPE' => ' Client'
                        );
                        $format = $template[0]['body'];
                        $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
                        $subject = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $template[0]['subject']))));

                        send_mail($prospectOwnerEmail, $subject, $body);
                    }
                }
            }

            $where = array('prospect_id' => $id);
            //Get PROSPECT_MASTER Information client name and update also in estimate client
            $whereEstimate = array('prospect_id' => $id, 'client_type' => 'client');
            $estimateData['client_name'] = $prospectData['prospect_name'];
            $this->common_model->update(ESTIMATE_CLIENT, $estimateData, $whereEstimate);
            $successUpdate = $this->common_model->update(PROSPECT_MASTER, $prospectData, $where);
            if ($this->input->post('campaign_id')) {
                $table = TBL_CAMPAIGN_CONTACT . ' as cc';
                $fields = 'campaign_related_id';
                $match = 'campaign_related_id=' . $id . ' and campaign_status=2 and is_delete=0 ';
                $getCampaignData = $this->common_model->get_records($table, $fields, '', '', $match);
                if (!empty($getCampaignData)) {
                    $where = array('campaign_related_id' => $id, 'campaign_status' => 2);
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $where);
                } else {
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $campaignData['campaign_related_id'] = $id;
                    $campaignData['campaign_status'] = 2;
                    $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                }
            }
            if ($successUpdate) {
                $msg = $this->lang->line('client_update_msg');
                $this->session->set_flashdata('message', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
        //Insert Record in Database
        else {
            $prospectData['prospect_auto_id'] = $this->input->post('prospect_auto_id');
            $prospectData['created_date'] = datetimeformat();
            $prospectData['won_lost_date'] = datetimeformat();
            $prospectId = $this->common_model->insert(PROSPECT_MASTER, $prospectData);

            //insert campaign also in campaign_contact
            if ($this->input->post('campaign_id')) {
                $campaignData['campaign_id'] = $this->input->post('campaign_id');
                $campaignData['campaign_related_id'] = $prospectId;
                $campaignData['campaign_status'] = 2;
                $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
            }
            if ($prospectId) {
                $msg = $this->lang->line('client_add_msg');
                $this->session->set_flashdata('message', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }


        //Data in COMPANY_ACCOUNTS_TRAN Insert
        if ($id) {
            //   Update lead data in COMPANY_ACCOUNTS_TRAN  
            $where = array('client_id' => $id, 'status_type' => 2);
            $companyAccountData['company_id'] = $prospectData['company_id'];
            $this->common_model->update(COMPANY_ACCOUNTS_TRAN, $companyAccountData, $where);
        } else {
            //   Insert lead data in COMPANY_ACCOUNTS_TRAN      
            $companyAccountData['company_id'] = $prospectData['company_id'];
            $companyAccountData['client_id'] = $prospectId;
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

        $uploadData = uploadImage('prospect_files', prospect_upload_path, $data['account_view']);
        $Marketingfiles = array();

        //$marketing_file_str = implode(",", $Marketingfiles);

        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $fileData = implode(",", $file2);
        } else {
            $fileData = '';
        }

        $compaigndata['file_name'] = $fileData;

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
                        $prospectFiles[] = ['file_name' => $galleryFilesData[$i], 'file_path' => $galleryPath[$i], 'prospect_id' => $id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    } else {
                        $prospectFiles[] = ['file_name' => $galleryFilesData[$i], 'file_path' => $galleryPath[$i], 'prospect_id' => $prospectId, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    }
                }
            }
        }

        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                if ($id) {
                    $prospectFiles[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'prospect_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                } else {
                    $prospectFiles[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'prospect_id' => $prospectId, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }

        if (count($prospectFiles) > 0) {
            if ($id) {
                $where = array('prospect_id' => $id);
            } else {
                $where = array('prospect_id' => $prospectId);
            }

            if (!$this->common_model->insert_batch(FILES_SALES_MASTER, $prospectFiles)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }

        /**
         * SOFT DELETION CODE STARTS FOR DELETE FILE
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
            $this->common_model->delete(FILES_SALES_MASTER, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        $opportunityRequirement['requirement_description'] = $this->input->post('description', FALSE);
        $opportunityRequirement['modified_date'] = datetimeformat();

        if ($id) {

            $where = array('prospect_id' => $id);
            $successUpdate = $this->common_model->update(OPPORTUNITY_REQUIREMENT, $opportunityRequirement, $where);
        } else {

            $opportunityRequirement['prospect_id'] = $prospectId;
            $opportunityRequirement['created_date'] = datetimeformat();
            $returnOpportunityId = $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $opportunityRequirement);
        }

        /**
         * SOFT DELETION CODE STARTS FOR CONTACT DELETE
         */
        $softDeleteContactsArr = $this->input->post('softDeletedContacts');
        if (count($softDeleteContactsArr) > 0) {
            $dlStr = implode(',', $softDeleteContactsArr);
            $contact_data['is_delete'] = 1;
            $this->common_model->update(CONTACT_MASTER, $contact_data, 'contact_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        $primaryContact = array();
        $primaryContact = $this->input->post('primary_contact');
        $array = array_filter($primaryContact, create_function('$a', 'return $a!=="";'));
        $array = array_merge($array, array());
        $primaryContact = $array;
        $prospectContactsName = array();
        $prospectContactsName = $this->input->post('contact_name');
        $prospectContactsEmailId = array();
        $prospectContactsEmailId = $this->input->post('email_id');
        $prospectContactsPhoneNo = array();
        $prospectContactsPhoneNo = $this->input->post('phone_no');

//Update Record in Database
        if ($id) {
            $prospectContactsTran['primary_contact'] = "0";
            for ($prospectContactsCount = 0; $prospectContactsCount < count($prospectContactsName); $prospectContactsCount++) {
                if ($primaryContact[$prospectContactsCount] > 0) {
                    $prospectContactsTran['primary_contact'] = "1";
                } else {
                    $prospectContactsTran['primary_contact'] = "0";
                }

                $prospectContactsTran['modified_date'] = datetimeformat();
                $prospectContactsTran['status'] = 1;
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
                    $this->common_model->update(OPPORTUNITY_REQUIREMENT_CONTACTS, $prospectContactsTran, $where);
                    if ($this->input->post('campaign_id')) {
                        $where = array('campaign_related_id' => $contactId[$prospectContactsCount], 'campaign_status' => 1);
                        $campaignData['campaign_id'] = $this->input->post('campaign_id');
                        $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $where);
                    }
                } else {
                    $prospectContactsTran['prospect_id'] = $id;
                    //Check primary for this company exist or not
                    $table23 = CONTACT_MASTER . ' as cm';
                    $match23 = "cm.company_id='" . $prospectData['company_id'] . "' and cm.is_delete=0
                        and primary_contact=1 and cm.status=1 ";
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
                    $prospectContactsTran['requirement_id'] = $opportunityRequirementId;
                    $this->common_model->insert(OPPORTUNITY_REQUIREMENT_CONTACTS, $prospectContactsTran);
                    if ($this->input->post('campaign_id')) {
                        $campaignData['campaign_id'] = $this->input->post('campaign_id');
                        $campaignData['campaign_related_id'] = $contactId;
                        $campaignData['campaign_status'] = 1;
                        $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                    }
                }
            }
        }
//Insert Record in Database
        else {
            $prospectContactsTran['primary_contact'] = "0";
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
                $prospectContactsTran['requirement_id'] = $returnOpportunityId;
                $prospectContactsTran['contact_id'] = $contactId;
                $prospectContactsTran['prospect_id'] = $prospectId;
                $prospectContactsTran['status'] = 1;
                $prospectContactsTran['created_date'] = datetimeformat();
                $prospectContactsTran['modified_date'] = datetimeformat();
                $this->common_model->insert(OPPORTUNITY_REQUIREMENT_CONTACTS, $prospectContactsTran);
                if ($this->input->post('campaign_id')) {
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $campaignData['campaign_related_id'] = $contactId;
                    $campaignData['campaign_status'] = 1;
                    $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                }
            }
        }
        if ($this->input->post('interested_products')) {
            $productData['status'] = 1;
            $productData['created_date'] = datetimeformat();
            $productData['modified_date'] = datetimeformat();
//Delete Record in Database
            if ($id) {

                $where = array('prospect_id' => $id);
                $this->common_model->delete(PROSPECT_PRODUCTS_TRAN, $where);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            $selectedProducts = $this->input->post('interested_products');
            $nproducts = count($selectedProducts);

            for ($i = 0; $i < $nproducts; $i++) {
                $productData['product_id'] = $selectedProducts[$i];
                //Insert Record in Database
                if ($id) {
                    $productData['prospect_id'] = $id;
                    $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $productData);
                } else {
                    $productData['prospect_id'] = $prospectId;
                    $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $productData);
                }
            }
        }
        redirect($redirectLink); //Redirect On Listing page
    }

}
