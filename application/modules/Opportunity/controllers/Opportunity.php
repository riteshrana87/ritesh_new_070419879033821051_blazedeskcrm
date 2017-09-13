<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Opportunity extends CI_Controller {

    function __construct() {
        
        parent::__construct();
         if(checkPermission('Opportunity','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->prefix = $this->db->dbprefix;
        $this->viewname = $this->uri->segment(1);
        $this->current_module = $this->router->fetch_module();
        $this->load->model('Opportunity_model');
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
        $this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('sales_overview'), 'SalesOverview');
        $this->breadcrumbs->push(lang('opportunities'), ' ');
        $this->pagination();
    }

    function navigation($id = null) {
        
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            if ($id > 0) {

                $_SESSION['current_related_id'] = $id;

    //Get Records From PROSPECT_MASTER Table with JOIN
                $data = array();
                $params['join_tables'] = array(
                    COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                    COUNTRIES . ' as c' => 'c.country_id=pm.country_id');
                $params['join_type'] = 'left';
                $match = "pm.prospect_id = " . $id;
                $table = PROSPECT_MASTER . ' as pm';
                $groupBy = 'pm.prospect_id';
                $fields = array("pm.prospect_id,pm.company_id,"
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
                $data['modal_title'] = $this->lang->line('view_on_map');
                $data['opportunity_view'] = $this->viewname;
                $data['drag'] = true;
                //$data['main_content'] = '/ViewOpportunity';
                $this->load->view('navigation', $data);
            } else {
                show_404();
            }

        }
        
    }

    public function pagination() {

        //$data['main_content'] = '/Opportunity';
        //$data['js_content'] = '/LoadJsFileOpportunity';
        //$config['base_url'] = site_url('Opportunity/pagination');
        $data['opportunity_view'] = $this->viewname;


        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('opportunity_data');
        }

        $searchsort_session = $this->session->userdata('opportunity_data');
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
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('opportunity_data');
        }

        $searchsort_session = $this->session->userdata('opportunity_data');
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

        $data['sales_view'] = $this->viewname;

//Get owner data from LOGIN table
        $data['prospect_owner'] = $this->common_model->getSystemUserData();

//Get Records From BRANCH_MASTER Table       
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = " bm.status=1 ";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

//Get data from PROSPECT_MASTER with join other table for get contact data for listing
        $params['join_tables'] = array(
            OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
            ESTIMATE_MASTER . ' as em' => 'em.estimate_id=pm.estimate_prospect_worth',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id ');
        $params['join_type'] = 'left';
        $table = PROSPECT_MASTER . ' as pm';
        $groupBy = 'pm.prospect_id';
        $fields = array("pm.prospect_id,pm.prospect_name,pm.prospect_auto_id, pm.status,pm.status_type,pm.creation_date,(select count(orc.prospect_id) from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . OPPORTUNITY_REQUIREMENT_CONTACTS . " as orc on cm.contact_id=orc.contact_id where cm.is_delete=0 and cm.status=1 and orc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_count,(select cm.contact_name from " . $this->prefix . CONTACT_MASTER . " as cm inner join " . $this->prefix . OPPORTUNITY_REQUIREMENT_CONTACTS . " as orc on cm.contact_id=orc.contact_id where orc.primary_contact=1 and cm.is_delete=0 and cm.status=1 and orc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_name");
        $where = "pm.is_delete=0 and pm.status_type=1 and pm.status=1";

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
          
            $opportunitySince = date('Y-m-d', strtotime($searchtext));
            $whereSearch = '(pm.prospect_name LIKE "%' . $searchtext . '%" OR pm.prospect_auto_id LIKE "%' . $searchtext . '%" OR pm.status_type LIKE "%' . $searchtext . '%" OR pm.creation_date LIKE "%' . $opportunitySince . '%"  OR cm.contact_name LIKE "%' . $searchtext . '%")';

            $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $groupBy, $where, '', '', '', '', '', $whereSearch);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $groupBy, $where, '', '', '1', '', '', $whereSearch);

            //Get total Records From PROSPECT_MASTER Table  
            $totalOpportunity = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $groupBy, $where, '', '', '1', '', '', $whereSearch);
        } else {
//Not have any search input
            $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $groupBy, $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $groupBy, $where, '', '', '1');
            
            //Get total Records From PROSPECT_MASTER Table  
            $totalOpportunity = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $groupBy, $where, '', '', '1');
        }

        //check  total opportunity > 0 otherwise set total opportunity = 0
        if ($totalOpportunity) {
            $data['total_opportunity'] = $totalOpportunity;
        } else {
            $data['total_opportunity'] = '0';
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
        $this->session->set_userdata('opportunity_data', $sortsearchpage_data);
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $data['header'] = array('menu_module' => 'crm');
        $data['drag'] = true;

        //Pass ALL TABLE Record In View
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view('AjaxOpportunityList', $data);
        } else {
            $data['main_content'] = '/' . $this->viewname;
            $this->parser->parse('layouts/DashboardTemplate', $data);
        }
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Opportunity Model Add Page
      @Input    :
      @Output	: Show Modal For Add Account
      @Date     : 13/01/2016
     */

    public function add($contactId = null, $redirect = '') {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data = array();
            $data['contact_id'] = $contactId;
            if ($data['contact_id'] != '') {
                $redirectLink = $_SERVER['HTTP_REFERER'];

                //Add Deal from view lead
                if (strpos($redirectLink, 'Lead') !== false) {
                    $table1 = LEAD_MASTER . ' as lm';
                    $match1 = " lm.is_delete=0 and lm.lead_id=" . $data['contact_id'];
                    $fields1 = array("lm.company_id,lm.address1,lm.address2,lm.postal_code,lm.city,lm.state,lm.country_id,lm.language_id,lm.branch_id,lm.description");
                    $companyData = $this->common_model->get_records($table1, $fields1, '', '', $match1);

                    $data['company_id'] = $companyData[0]['company_id'];
                    $companyData['company_id'] = $companyData[0]['company_id'];
                    $companyData['address1'] = $companyData[0]['address1'];
                    $companyData['address2'] = $companyData[0]['address2'];
                    $companyData['postal_code'] = $companyData[0]['postal_code'];
                    $companyData['city'] = $companyData[0]['city'];
                    $companyData['state'] = $companyData[0]['state'];
                    $companyData['country_id'] = $companyData[0]['country_id'];
                    $companyData['language_id'] = $companyData[0]['language_id'];
                    $data['opportunity_requirement'][0]['requirement_description'] = $companyData[0]['description'];
                    $data['edit_record'][] = $companyData;
                }
                //Add Deal from view Opportunity
                elseif (strpos($redirectLink, 'Opportunity') !== false) {
                    $table1 = PROSPECT_MASTER . ' as pm';
                    $match1 = " pm.is_delete=0 and pm.prospect_id=" . $data['contact_id'];
                    $fields1 = array("pm.company_id,pm.address1,pm.address2,pm.postal_code,pm.city,pm.state,pm.country_id,pm.language_id,pm.branch_id,pm.description");
                    $companyData = $this->common_model->get_records($table1, $fields1, '', '', $match1);

                    $table13 = OPPORTUNITY_REQUIREMENT . ' as or';
                    $match13 = "or.prospect_id = " . $data['contact_id'];
                    $fields13 = array("or.requirement_id,or.requirement_description");
                    $opportunityRequirement = $this->common_model->get_records($table13, $fields13, '', '', $match13);

                    $data['company_id'] = $companyData[0]['company_id'];
                    $companyData['company_id'] = $companyData[0]['company_id'];
                    $companyData['address1'] = $companyData[0]['address1'];
                    $companyData['address2'] = $companyData[0]['address2'];
                    $companyData['postal_code'] = $companyData[0]['postal_code'];
                    $companyData['city'] = $companyData[0]['city'];
                    $companyData['state'] = $companyData[0]['state'];
                    $companyData['country_id'] = $companyData[0]['country_id'];
                    $companyData['language_id'] = $companyData[0]['language_id'];
                    $data['opportunity_requirement'][0]['requirement_description'] = $opportunityRequirement[0]['requirement_description'];
                    $data['edit_record'][] = $companyData;
                }
                //Add Deal from view Client
                elseif (strpos($redirectLink, 'Account') !== false) {
                    $table1 = PROSPECT_MASTER . ' as pm';
                    $match1 = " pm.is_delete=0 and pm.prospect_id=" . $data['contact_id'];
                    $fields1 = array("pm.company_id,pm.address1,pm.address2,pm.postal_code,pm.city,pm.state,pm.country_id,pm.language_id,pm.branch_id,pm.description");
                    $companyData = $this->common_model->get_records($table1, $fields1, '', '', $match1);

                    $table13 = OPPORTUNITY_REQUIREMENT . ' as or';
                    $match13 = "or.prospect_id = " . $data['contact_id'];
                    $fields13 = array("or.requirement_id,or.requirement_description");
                    $opportunityRequirement = $this->common_model->get_records($table13, $fields13, '', '', $match13);

                    $data['company_id'] = $companyData[0]['company_id'];
                    $companyData['company_id'] = $companyData[0]['company_id'];
                    $companyData['address1'] = $companyData[0]['address1'];
                    $companyData['address2'] = $companyData[0]['address2'];
                    $companyData['postal_code'] = $companyData[0]['postal_code'];
                    $companyData['city'] = $companyData[0]['city'];
                    $companyData['state'] = $companyData[0]['state'];
                    $companyData['country_id'] = $companyData[0]['country_id'];
                    $companyData['language_id'] = $companyData[0]['language_id'];
                    $data['opportunity_requirement'][0]['requirement_description'] = $opportunityRequirement[0]['requirement_description'];
                    $data['edit_record'][] = $companyData;
                }
                //Add Deal from view Contact
                elseif (strpos($redirectLink, 'Contact') !== false) {
                    $companyData = $this->common_model->getCompanyidFromContactid($data['contact_id']);

                    $data['company_id'] = $companyData['company_id'];
                    $companyData['company_id'] = $companyData['company_id'];
                    $companyData['address1'] = $companyData['address1'];
                    $companyData['address2'] = $companyData['address2'];
                    $companyData['postal_code'] = $companyData['postal_code'];
                    $companyData['city'] = $companyData['city'];
                    $companyData['state'] = $companyData['state'];
                    $companyData['country_id'] = $companyData['country_id'];
                    $companyData['language_id'] = $companyData['language_id'];
                    $companyData['prospect_owner_id'] = $data['contact_id'];

                    $data['edit_record'][] = $companyData;
                }
            }
            $data['opportunity_view'] = $this->viewname;
            $data['main_content'] = '/Opportunity';
            $redirectLink = $this->input->post('redirect_link');
            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['modal_title'] = $this->lang->line('create_new_opportunity');
            $data['submit_button_title'] = $this->lang->line('create_opportunity');


    //By default owner selected as login user
            $prospectOwner[] = array('prospect_owner_id' => $this->session->userdata('LOGGED_IN')['ID']);
            $data['edit_record'] = $prospectOwner;
    //Generate Prospect auto id 
            $data['pros_auto_id'] = $this->common_model->opportunity_auto_gen_Id();

    //Get Estimate Master data
            $table1 = ESTIMATE_MASTER . ' as ESTM';
            $match1 = " ESTM.status=1 ";
            $fields1 = array("ESTM.subject,ESTM.estimate_id");
            $data['EstimateArray'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

    //Get owner data from LOGIN table
            $data['prospect_owner'] = $this->common_model->getSystemUserData();

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

    //Get Records From LANGUAGE_MASTER Table
            $table1 = LANGUAGE_MASTER . ' as lan';
            $match1 = "";
            $fields1 = array("lan.language_id,lan.language_name");
            $data['language_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

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

            $data['drag'] = true;
            $this->load->view('AddEditOpportunity', $data);

        }
        
    }

    /*     * ************************************************************************************************

     * Check if have id then update data otherwise insertdata

      1)  When click on Create Opportunity or Update Opportunity this function insert data in related table.
      2)  Get input post data .
      3)  If input post branch name exist in BRANCH_MASTER then take branch id.
      4)  If input post branch name not exist in BRANCH_MASTER then insert that branch in BRANCH_MASTER
      and get inserted branch id.
      5)  If input post company select from list then get company_id.
      6)  If input post company is Add New Comapny then get company data and insert in COMPANY_MASTER
      and get inserted id.
      7) 	If company not have an account create account with status type=2t
      8)  If have prospect owner then get detail and send email to that prospect.
      9)  If update prospect name also update in ESTIMATE_CLIENT table.
      10) If have input post campaign then insert  in TBL_CAMPAIGN_CONTACT and update then update it.
      11) Insert all files and documents in FILES_SALES_MASTER.
      12) If have any deleted file or document then delete form FILES_SALES_MASTER and unlink.
      13) Insert description in OPPORTUNITY_REQUIREMENT table
      14) If have deleted contact then delete from CONTACT_MASTER
      15) Insert contact data in CONTACT_MASTER and  relational table is OPPORTUNITY_REQUIREMENT_CONTACTS
      16) If have input post produts then insert in PROSPECT_PRODUCTS_TRAN table

     * ************************************************************************************************* */

    public function insertdata() {

        $prospectId = '';
        $dealStatus = '';
        $opportunityRequirementId = '';
        if ($this->input->post('prospect_id')) {
            $prospectId = $this->input->post('prospect_id');
        }

        if ($this->input->post('requirement_id')) {
            $opportunityRequirementId = $this->input->post('requirement_id');
        }
        $redirectLink = $this->input->post('redirect_link');
        if (!validateFormSecret()) {

            redirect($redirectLink); //Redirect On Listing page
        }
        if (strpos($redirectLink, 'Contact/view') !== false) {
            $sessArray = array('setting_current_tab' => 'Deals');
            $this->session->set_userdata($sessArray);
            $this->session->set_flashdata('message', $msg);
            $userId = $this->session->userdata('LOGGED_IN')['ID'];

            if ($this->input->post('hdn_contact_id')) {
                $prospectData['prospect_related_id'] = $this->input->post('hdn_contact_id');
            }

            $prospectData['prospect_assign'] = $userId;
            //$prospectData['deal_status'] = 1;
            $dealStatus = 1;
        } elseif (strpos($redirectLink, 'Lead/viewdata') !== false) {
            $sessArray = array('setting_current_tab' => 'Deals');
            $this->session->set_userdata($sessArray);
            $this->session->set_flashdata('message', $msg);
            $userId = $this->session->userdata('LOGGED_IN')['ID'];

            if ($this->input->post('hdn_contact_id')) {
                $prospectData['prospect_related_id'] = $this->input->post('hdn_contact_id');
            }

            $prospectData['prospect_assign'] = $userId;
            // $prospectData['deal_status'] = 3;
            $dealStatus = 3;
        } elseif (strpos($redirectLink, 'Opportunity/viewdata') !== false) {
            $sessArray = array('setting_current_tab' => 'Deals');
            $this->session->set_userdata($sessArray);
            $this->session->set_flashdata('message', $msg);
            $userId = $this->session->userdata('LOGGED_IN')['ID'];

            if ($this->input->post('hdn_contact_id')) {
                $prospectData['prospect_related_id'] = $this->input->post('hdn_contact_id');
            }
            $prospectData['prospect_assign'] = $userId;
            // $prospectData['deal_status'] = 4;
            $dealStatus = 4;
        } elseif (strpos($redirectLink, 'Account/viewdata' || strpos($redirectLink, 'Account/viewLostClient') !== false) !== false) {
            $sessArray = array('setting_current_tab' => 'Deals');
            $this->session->set_userdata($sessArray);
            $this->session->set_flashdata('message', $msg);
            $userId = $this->session->userdata('LOGGED_IN')['ID'];

            if ($this->input->post('hdn_contact_id')) {
                $prospectData['prospect_related_id'] = $this->input->post('hdn_contact_id');
            }

            $prospectData['prospect_assign'] = $userId;
            //$prospectData['deal_status']['deal_status'] = 2;
            $dealStatus = 2;
        }

        $data = array();
        $data['opportunity_view'] = $this->viewname;
        $prospectData['prospect_name'] = strip_slashes(trim($this->input->post('prospect_name')));
        $prospectData['address1'] = strip_slashes($this->input->post('address1'));
        $prospectData['address2'] = strip_slashes($this->input->post('address2'));
        $prospectData['creation_date'] = date_format(date_create($this->input->post('creation_date')), 'Y-m-d');
        $prospectData['postal_code'] = strip_slashes($this->input->post('postal_code'));
        $prospectData['city'] = strip_slashes($this->input->post('city'));
        $prospectData['state'] = strip_slashes($this->input->post('state'));
        $prospectData['number_type1'] = $this->input->post('number_type1');
        $prospectData['phone_no'] = $this->input->post('phone_no1_opportunity');
        $prospectData['number_type2'] = $this->input->post('number_type2');
        $prospectData['phone_no2'] = $this->input->post('phone_no2_opportunity');
        $prospectData['language_id'] = $this->input->post('language_id');
        $prospectData['fb'] = $this->input->post('fb');
        $prospectData['twitter'] = $this->input->post('twitter');
        $prospectData['linkedin'] = $this->input->post('linkedin');
        $prospectData['credit_limit'] = $this->input->post('credit_limit');
        $prospectOwneId = $this->input->post('prospect_owner_id');
        $prospectData['prospect_owner_id'] = $prospectOwneId;
        $branchName = strip_slashes($this->input->post('branch_id'));
//Get Branch id From BRANCH_MASTER Table       
        $table22 = BRANCH_MASTER . ' as bm';
        $match22 = "bm.branch_name='" . addslashes($branchName) . "' and bm.status=1";
        $fields22 = array("bm.branch_name, bm.branch_id");
        $branchRecord = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        if ($branchRecord) {
            $prospectData['branch_id'] = $branchRecord[0]['branch_id'];
        } else {
            $branchData['branch_name'] = $branchName;
        }



//Check for Duplicate Branch Name: Start
        if (count($branchRecord) == 0) {
            //INSERT Branch
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
                $companyData['company_name'] = strip_slashes(trim($this->input->post('company_name')));
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

                //upload company logo image if add new company
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
        $description = $this->input->post('description', FALSE);
        $companyId = $prospectData['company_id'];
        //check this company account exist or not
        $table = PROSPECT_MASTER . ' as pm';
        $match = "pm.company_id = " . $prospectData['company_id'] . " and status_type=2 and pm.is_delete=0";
        $fields = array("pm.company_id");
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

            $opportunityRequirementAccount['requirement_description'] = $description;
            $opportunityRequirementAccount['modified_date'] = datetimeformat();
            $opportunityRequirementAccount['prospect_id'] = $CompanyAccountId;
            $opportunityRequirementAccount['created_date'] = datetimeformat();
            $opportunityRequirementAccount['status'] = 1;
            $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $opportunityRequirementAccount);
        }

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

        if ($this->input->post('contact_date')) {
            $prospectData['contact_date'] = date_format(date_create($this->input->post('contact_date')), 'Y-m-d');
        } else {
            $prospectData['contact_date'] = '';
        }
        $prospectData['status'] = 1;

        $prospectData['modified_date'] = datetimeformat();
        $prospectData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        //Insert Record in Database

        if ($prospectId) {
            /*
             * gets current data of current opportunity 
             */
            $params['join_tables'] = array(
                COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                LANGUAGE_MASTER . ' as lan' => 'lan.language_id=pm.language_id',
                COUNTRIES . ' as c' => 'c.country_id=pm.country_id',
                CONTACT_MASTER . ' as ct' => 'pm.prospect_owner_id=ct.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $prospectId;
            $table = PROSPECT_MASTER . ' as pm';
            $groupBy = 'pm.prospect_id';
            $fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
                . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.city,pm.state,pm.country_id,pm.number_type1,"
                . "pm.phone_no as phone_number,pm.phone_no2,pm.prospect_owner_id,lan.language_name,"
                . "pm.branch_id,pm.estimate_prospect_worth,pm.prospect_generate,pm.campaign_id,pm.description,"
                . "cm.company_name,cm.logo_img,c.country_name,ct.contact_name");
            $currentProspectData = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $groupBy);
            if (count($currentProspectData) > 0) {
                $prospectOwnerOldId = $currentProspectData[0]['prospect_owner_id'];
            }

            /*
             * will sends email if prospect owner gets changed
             */
            if ($prospectOwnerOldId != $prospectOwneId) {

                /*
                 * fetches new prospect owner data
                 */
                $umatch = "login_id =" . $prospectOwneId;
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
                        $url = base_url('Opportunity/viewdata/' . $prospectId);
                        $replace = array(
                            'USER' => $prospectOwnerName,
                            'OPPORTUNITY_NAME' => $prospectName,
                            'LINK' => "<a href='" . $url . "' title='view opportunity'>View</a>",
                            'TYPE' => ' Prospect'
                        );
                        $format = $template[0]['body'];
                        $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
                        $subject = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $template[0]['subject']))));

                        send_mail($prospectOwnerEmail, $subject, $body);
                    }
                }
            }

            $where = array('prospect_id' => $prospectId);
            $successUpdate = $this->common_model->update(PROSPECT_MASTER, $prospectData, $where);
            if ($this->input->post('campaign_id')) {
                $table = TBL_CAMPAIGN_CONTACT . ' as cc';
                $fields = 'campaign_related_id';
                $match = 'campaign_related_id=' . $prospectId . ' and campaign_status=4 and is_delete=0 ';
                $getCampaignData = $this->common_model->get_records($table, $fields, '', '', $match);
                if (!empty($getCampaignData)) {
                    $where = array('campaign_related_id' => $prospectId, 'campaign_status' => 4);
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $where);
                } else {
                    $campaignData['campaign_id'] = $this->input->post('campaign_id');
                    $campaignData['campaign_related_id'] = $prospectId;
                    $campaignData['campaign_status'] = 4;
                    $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
                }
            }
            if ($successUpdate) {
                $msg = $this->lang->line('opp_update_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            if (strpos($redirectLink, 'Contact/view') !== false) {
                $sessArray = array('setting_current_tab' => 'Contacts');
                $this->session->set_userdata($sessArray);
                $this->session->set_flashdata('message', $msg);
            } elseif (strpos($redirectLink, 'Lead/viewdata') !== false) {
                $sessArray = array('setting_current_tab' => 'Contacts');
                $this->session->set_userdata($sessArray);
                $this->session->set_flashdata('message', $msg);
            } elseif (strpos($redirectLink, 'Opportunity/viewdata') !== false) {
                $sessArray = array('setting_current_tab' => 'Contacts');
                $this->session->set_userdata($sessArray);
                $this->session->set_flashdata('message', $msg);
            } elseif (strpos($redirectLink, 'Account/viewdata' || strpos($redirectLink, 'Account/viewLostClient') !== false) !== false) {
                $sessArray = array('setting_current_tab' => 'Contacts');
                $this->session->set_userdata($sessArray);
                $this->session->set_flashdata('message', $msg);
            }
        } else {

            $prospectData['deal_status'] = $dealStatus;
            //status_type=1 for Opportunity
            $prospectData['status_type'] = 1;
            $prospectData['prospect_auto_id'] = $this->input->post('prospect_auto_id');
            $prospectData['created_date'] = datetimeformat();
            $returnProspectId = $this->common_model->insert(PROSPECT_MASTER, $prospectData);

            //insert campaign also in campaign_contact
            if ($this->input->post('campaign_id')) {
                $campaignData['campaign_id'] = $this->input->post('campaign_id');
                $campaignData['campaign_related_id'] = $returnProspectId;
                $campaignData['campaign_status'] = 4;
                $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaignData);
            }
            if ($returnProspectId) {
                $msg = $this->lang->line('opp_add_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
        if (strpos($redirectLink, 'Contact/view') !== false) {
            $this->session->set_flashdata('message', $msg);
        } elseif (strpos($redirectLink, 'Lead/viewdata') !== false) {
            $this->session->set_flashdata('message', $msg);
        } elseif (strpos($redirectLink, 'Opportunity/viewdata') !== false) {
            $this->session->set_flashdata('message', $msg);
        } elseif (strpos($redirectLink, 'Account/viewdata' || strpos($redirectLink, 'Account/viewLostClient') !== false) !== false) {
            $this->session->set_flashdata('message', $msg);
        }

        //Data in COMPANY_ACCOUNTS_TRAN Insert
        if ($prospectId) {
            //   Update lead data in COMPANY_ACCOUNTS_TRAN  
            $where = array('client_id' => $prospectId, 'status_type' => 2);
            $companyAccountdata['company_id'] = $prospectData['company_id'];
            $this->common_model->update(COMPANY_ACCOUNTS_TRAN, $companyAccountdata, $where);
        } else {
            //   Insert lead data in COMPANY_ACCOUNTS_TRAN      
            $companyAccountdata['company_id'] = $prospectData['company_id'];
            $companyAccountdata['client_id'] = $returnProspectId;
            $companyAccountdata['status_type'] = $prospectData['status_type'];
            $companyAccountdata['created_date'] = datetimeformat();
            $companyAccountdata['modified_date'] = datetimeformat();
            $companyAccountdata['status'] = 1;
            $this->common_model->insert(COMPANY_ACCOUNTS_TRAN, $companyAccountdata);
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
        $uploadData = uploadImage('prospect_files', prospect_upload_path, $data['opportunity_view']);

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
                    if ($prospectId) {
                        $prospectFiles[] = ['file_name' => $galleryFilesData[$i], 'file_path' => $galleryPath[$i], 'prospect_id' => $prospectId, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    } else {
                        $prospectFiles[] = ['file_name' => $galleryFilesData[$i], 'file_path' => $galleryPath[$i], 'prospect_id' => $returnProspectId, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    }
                }
            }
        }

        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                if ($prospectId) {
                    $prospectFiles[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'prospect_id' => $prospectId, 'upload_status' => 0, 'created_date' => datetimeformat()];
                } else {
                    $prospectFiles[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'prospect_id' => $returnProspectId, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }

        if (count($prospectFiles) > 0) {
            if ($prospectId) {
                $where = array('prospect_id' => $prospectId);
            } else {
                $where = array('prospect_id' => $returnProspectId);
            }

            if (!$this->common_model->insert_batch(FILES_SALES_MASTER, $prospectFiles)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }

        /**
         * SOFT DELETION CODE STARTS 
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

        $opportunityRequirement['requirement_description'] = $description;

        $opportunityRequirement['modified_date'] = datetimeformat();
        if ($prospectId) {
            $where = array('prospect_id' => $prospectId);
            $successUpdate = $this->common_model->update(OPPORTUNITY_REQUIREMENT, $opportunityRequirement, $where);
        } else {
            $opportunityRequirement['prospect_id'] = $returnProspectId;
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
        $opportunityRequirementContactsName = array();
        $opportunityRequirementContactsName = $this->input->post('contact_name');
        $opportunityRequirementContactsEmailId = array();
        $opportunityRequirementContactsEmailId = $this->input->post('email_id');
        $opportunityRequirementContactsPhoneNo = array();
        $opportunityRequirementContactsPhoneNo = $this->input->post('phone_no');

        //Update Record in Database
        if ($prospectId) {

            $prospectContactsTran['primary_contact'] = "0";
            for ($prospectContactsCount = 0; $prospectContactsCount < count($opportunityRequirementContactsName); $prospectContactsCount++) {
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
                $contactMasterData['contact_name'] = $opportunityRequirementContactsName[$prospectContactsCount];
                $contactMasterData['email'] = $opportunityRequirementContactsEmailId[$prospectContactsCount];
                $contactMasterData['mobile_number'] = $opportunityRequirementContactsPhoneNo[$prospectContactsCount];
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
                    $prospectContactsTran['prospect_id'] = $prospectId;
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
        } else {

            $prospectContactsTran['primary_contact'] = "0";
            for ($prospectContactsCount = 0; $prospectContactsCount < count($opportunityRequirementContactsName); $prospectContactsCount++) {


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
                $contactMasterData['contact_name'] = $opportunityRequirementContactsName[$prospectContactsCount];
                $contactMasterData['email'] = $opportunityRequirementContactsEmailId[$prospectContactsCount];
                $contactMasterData['mobile_number'] = $opportunityRequirementContactsPhoneNo[$prospectContactsCount];
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
                $prospectContactsTran['prospect_id'] = $returnProspectId;
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
            if ($prospectId) {

                $where = array('prospect_id' => $prospectId);
                $this->common_model->delete(PROSPECT_PRODUCTS_TRAN, $where);
            }
            $selectedProducts = $this->input->post('interested_products');
            $nProducts = count($selectedProducts);

            for ($productsCount = 0; $productsCount < $nProducts; $productsCount++) {
                $productData['product_id'] = $selectedProducts[$productsCount];
                //Insert Record in Database for product
                if ($prospectId) {
                    $productData['prospect_id'] = $prospectId;
                    $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $productData);
                } else {
                    $productData['prospect_id'] = $returnProspectId;
                    //Insert Record in Database
                    $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $productData);
                }
            }
        }
        redirect($redirectLink); //Redirect On Listing page
    }

    public function deletedata() {
    
        $prospectId = $this->input->get('id');

        $redirectLink = $_SERVER['HTTP_REFERER'];
        
        $params['join_tables'] = array(
            OPPORTUNITY_REQUIREMENT_CONTACTS . ' as lct' => 'lct.prospect_id=lm.prospect_id',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=lct.contact_id');
        $params['join_type'] = 'left';
        $match = "lm.prospect_id = " . $prospectId . " and cm.is_delete=0 and cm.status=1 ";
        $table = PROSPECT_MASTER . ' as lm';

        $fields = array("lct.contact_id");
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
                    $this->common_model->update(CONTACT_MASTER, $contactData, $wherePrimary);
                }
                
            }
        
        //Delete Record From Database
        if (!empty($prospectId)) {
            $where = array('prospect_id' => $prospectId);
            $prospectData['is_delete'] = 1;
            $deleteSuceess = $this->common_model->update(PROSPECT_MASTER, $prospectData, $where);

           // $this->common_model->delete(OPPORTUNITY_REQUIREMENT, $where);
            
            $this->common_model->delete(OPPORTUNITY_REQUIREMENT_CONTACTS, $where);
            
            $this->common_model->delete(FILES_SALES_MASTER, $where);
            
            $this->common_model->delete(PROSPECT_PRODUCTS_TRAN, $where);
            
            $campaignwhere = array('campaign_related_id' => $delete_id['prospect_id']);
            $this->common_model->delete(TBL_CAMPAIGN_CONTACT, $campaignwhere);
            
            $accwhere = array('client_id' => $delete_id['prospect_id']);
            $this->common_model->delete(COMPANY_ACCOUNTS_TRAN, $accwhere);
            
            
            if ($deleteSuceess) {
                $msg = $this->lang->line('opp_del_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            unset($prospectId);
        }

        if (strpos($redirectLink, 'Contact/view') !== false) {
            $sessArray = array('setting_current_tab' => 'Deals');
            $this->session->set_userdata($sessArray);
            $this->session->set_flashdata('message', lang('opp_del_msg'));
            redirect($redirectLink);
        } elseif (strpos($redirectLink, 'Lead/viewdata') !== false) {
            $sessArray = array('setting_current_tab' => 'Deals');
            $this->session->set_userdata($sessArray);
            $this->session->set_flashdata('message', lang('opp_del_msg'));
            redirect($redirectLink);
        } elseif (strpos($redirectLink, 'Account/viewdata') !== false || strpos($redirectLink, 'Account/viewLostClient') !== false)  {
            $sessArray = array('setting_current_tab' => 'Deals');
            $this->session->set_userdata($sessArray);
            $this->session->set_flashdata('message', lang('opp_del_msg'));
            redirect($redirectLink);
        }
        else{
             redirect('Opportunity'); //Redirect On Listing page
        }
       
    }

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
            $table22 = BRANCH_MASTER . ' as bm';
            $match22 = " bm.status=1 ";
            $fields22 = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($table22, $fields22, '', '', $match22);

            //Get Selected Records From BRANCH_MASTER Table       
            $table2 = BRANCH_MASTER . ' as bm';
            $match2 = "bm.branch_id=(SELECT pm.branch_id from " . $this->prefix . PROSPECT_MASTER . " as pm WHERE pm.prospect_id = " . $id . ") and bm.status=1";
            $fields2 = array("bm.branch_id,bm.branch_name");
            $data['branch_data1'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

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

            //Get Records From LANGUAGE_MASTER Table
            $table1 = LANGUAGE_MASTER . ' as lan';
            $match1 = "";
            $fields1 = array("lan.language_id,lan.language_name");
            $data['language_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

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

            //Get Records From PROSPECT_MASTER Table
            $table_join_prospect = PROSPECT_MASTER . ' as pm';
            $match_join_prospect = "pm.prospect_id = " . $id;
            $fields_join_prospect = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
                . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.city,pm.state,pm.country_id,pm.number_type1,"
                . "pm.phone_no,pm.number_type2,pm.phone_no2,pm.prospect_owner_id,pm.language_id,"
                . "pm.branch_id,pm.estimate_prospect_worth,pm.prospect_generate,pm.campaign_id,pm.description,"
                . "pm.file,pm.contact_date,pm.fb,pm.twitter,pm.linkedin,pm.credit_limit,pm.created_date");
            $data['edit_record'] = $this->common_model->get_records($table_join_prospect, $fields_join_prospect, '', '', $match_join_prospect);

            //Get Records From LEAD_MASTER Table with join to get contact detail
            $params['join_tables'] = array(
                OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
                CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $id . " and cm.is_delete=0 and cm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';
            $fields = array("pm.prospect_id,cm.contact_id,cm.contact_name,cm.mobile_number,cm.email,orc.contact_id,orc.primary_contact");
            $data['contact_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);

            //Get record from PROSPECT_PRODUCTS_TRAN
            $table12 = PROSPECT_PRODUCTS_TRAN . ' as pt';
            $match12 = "pt.prospect_id = " . $id;
            $fields12 = array("pt.product_id");
            $dataopportunity_product_data = $this->common_model->get_records($table12, $fields12, '', '', $match12);
            $product_id = array();
            if (!empty($dataopportunity_product_data)) {
                foreach ($dataopportunity_product_data as $dataopportunity_product_data) {
                    $product_id[] = $dataopportunity_product_data['product_id'];
                }
            }
            $data['opportunity_product_data'] = $product_id;

            //Get  description from OPPORTUNITY_REQUIREMENT      
            $table13 = OPPORTUNITY_REQUIREMENT . ' as or';
            $match13 = "or.prospect_id = " . $id;
            $fields13 = array("or.requirement_id,or.requirement_description");
            $data['opportunity_requirement'] = $this->common_model->get_records($table13, $fields13, '', '', $match13);

            //Get  files and documents from FILES_SALES_MASTER      
            $fields14 = array("*");
            $table14 = FILES_SALES_MASTER . ' as lf';
            $match14 = 'lf.prospect_id=' . $id . '';
            $data['prospect_files'] = $this->common_model->get_records($table14, $fields14, '', '', $match14);

            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['drag'] = true;

            if ($data['edit_record'][0]['status_type'] == 1) {
                $data['modal_title'] = $this->lang->line('update_opportunity');
                $data['submit_button_title'] = $this->lang->line('update_opportunity');
            } elseif ($data['edit_record'][0]['status_type'] == 4) {
                $data['modal_title'] = $this->lang->line('update_client');
                $data['submit_button_title'] = $this->lang->line('update_client');
            } else {
                $data['modal_title'] = $this->lang->line('update_client');
                $data['submit_button_title'] = $this->lang->line('update_client');
            }

            $data['opportunity_view'] = $this->viewname;
            $this->load->view('AddEditOpportunity', $data);

        }
       
    }

    /*
      @Author   : Rupesh Jorkar(RJ)
      @Desc     : Display Import CSV File popup
      @Input 	: search post data
      @Output	: open popup
      @Date     : 16/04/2016
     */

    function importOpportunity() {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data['modal_title'] = $this->lang->line('import_opportunity');
            $data['submit_button_title'] = $this->lang->line('import_opportunity');
            $data['account_view'] = $this->viewname;
            $data['main_content'] = '/ImportOpportunity';
            $data['js_content'] = '/loadJsFiles';
            $this->load->view('/ImportOpportunity', $data);

        }
        
    }

    /*
      @Author   : Rupesh Jorkar(RJ)
      @Desc     : Import CSV OR Excel File to import Account
      @Input    : search post data
      @Output   : import data to prospect_master table
      @Date     : 16/04/2016
     */

    function importOpportunitydata() {
        $config['upload_path'] = FCPATH . 'uploads/csv_opportunity';
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
            $filePath = './uploads/csv_opportunity/' . $new_name;

            // $this->load->library('excel');
            // $objPHPExcel = PHPExcel_IOFactory::load($filePath);
            // $cell_collection = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

            $file = fopen($filePath, 'r');
            $i = 1;
            while (($line = fgetcsv($file)) !== FALSE) {
                if ($i == 1) {
                    $cell_collection[$i] = $line;
                } else if ($line[1] != '') {
                    $cell_collection[$i] = $line;
                }
                $i++;
            }
            $key_account_name = array_search('Opportunity Name', $cell_collection[1]);
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

            $chk_file_column = array('Opportunity Name', 'Company Name', 'Company Email ID', 'Website', 'Company Phone No', 'Address1', 'Address2', 'Language', 'Postal Code', 'City', 'State', 'Country', 'Branch', 'Estimate Prospect Worth', 'NumberType1', 'PhoneNumber1', 'NumberType2', 'PhoneNumber2', 'Interested Products', 'Description');

            $diff_array = array_diff($chk_file_column, $cell_collection[1]);


            if (!empty($diff_array)) {

                $this->session->set_flashdata('msg', lang('WRONG_FILE_FOMRMAT'));
                redirect($this->viewname);
            }

            unset($cell_collection[1]);

            $count_success 	= 0;
            $count_fail 	= 0;
            $total_record 	= count($cell_collection);
            $type1 			= "";
            $type2 			= "";
            $get_branch_id 	= "";
            //$productArr 	= array();
			$errorArraySet 	= array(); 
			
			foreach ($cell_collection as $lineKey => $cell) {
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
                
                
                $branchId = $this->Opportunity_model->getBranchIdByName($value_branch);
                $companyId = $this->Opportunity_model->getComapnyIdByName($value_company_name);
                $country_id = $this->Opportunity_model->getCountryIdByName($value_country);
                $auto_generate_id = $this->common_model->opportunity_auto_gen_Id();
				$branchValid = 1;

                // Check Branch is exist or not if not then insert new branch into branch Master
                if ($branchId == "") {
					//If Condition Description - If Branch name is not blank
					 if(isset($value_branch) && $value_branch != "") {
						//Branch Master Data
							$data_branch['branch_name'] = $value_branch;
							$data_branch['created_date'] = datetimeformat();
                            $data_branch['modified_date'] = datetimeformat();
							$data_branch['status'] = 1;
							$get_branch_id = $this->common_model->insert(BRANCH_MASTER, $data_branch);
						}
                    
                    if ($get_branch_id != "") {
                        $branchId = $get_branch_id;
                    }
                } else {
                    $branchId = $branchId;
                }

                // Check Company is exist or not if not then insert new company into Company Master
                if ($companyId == "") {
					//if company name is not blank - rj 
                    if ($value_company_website == NULL) {
                        $value_company_website = "";
                    }
                    if ($value_company_name != "") {
						
                        // Company Master Data
                        $data_company['company_name'] 	= $value_company_name;
						//Insert Country ID 
						$data_company['country_id'] 	= $country_id;
                        $data_company['email_id'] 		= $value_company_email_id;
                        $data_company['branch_id'] 		= $branchId;
                        $data_company['website'] 		= $value_company_website;
                        $data_company['phone_no'] 		= $value_company_phone_no;
                        $data_company['created_date'] 	= datetimeformat();
                        $data_company['status'] 		= 1;
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
                            $companyId = $get_company_id;
                        }
                    }
                } else {
                    $companyId = $companyId;
                    if($value_branch == '')
                    {
                        $table_grp = COMPANY_MASTER;
                        $fields_grp = array("branch_id");
                        $match_grp = array('company_id' => $companyId);
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
				//If Condition for Company ID
               
                 if ($value_account_name != '' && $companyId != ''  && $country_id != 0) {
               // if ($companyId != "" && $value_account_name != "") {
                   
					//Create Account Code - check this company account exist or not otherwise make an account
					$table 			= PROSPECT_MASTER . ' as pm';
					$match 			= "pm.company_id = " . $companyId . " and status_type=2 and pm.is_delete=0";
					$fields 		= array("pm.company_id,pm.prospect_id");
					$companyExist 	= $this->common_model->get_records($table, $fields, '', '', $match);
					if (empty($companyExist)) {
						//get company data from company id
						$table 		= COMPANY_MASTER . ' as cm';
						$match 		= "cm.company_id = " . $companyId;
						$fields 	= array("cm.company_id,cm.company_name as prospect_name,cm.branch_id,cm.address1,cm.address2,cm.city,
										cm.state,cm.country_id,cm.postal_code");
						$companyData = $this->common_model->get_records($table, $fields, '', '', $match);
						$companyData[0]['prospect_auto_id'] = $this->common_model->opportunity_auto_gen_Id();
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

                //Prospect Master Data
                    $data_prospect['prospect_assign'] = 0;
                    $data_prospect['prospect_related_id'] = 0;
                    $data_prospect['prospect_auto_id'] = $auto_generate_id;
                    $data_prospect['prospect_name'] = $value_account_name;
                    $data_prospect['status_type'] = 1;
                    $data_prospect['company_id'] = $companyId;
                    $data_prospect['address1'] = str_replace(';',',',$value_address_1);
                    $data_prospect['address2'] = str_replace(';',',',$value_address_2);
                    $data_prospect['creation_date'] = datetimeformat();
                    $data_prospect['language_id'] = $language_id;
                    $data_prospect['postal_code'] = $value_postal_code;
                    $data_prospect['city'] = $value_city;
                    $data_prospect['state'] = $value_state;
                    $data_prospect['country_id'] = $country_id;
                    $data_prospect['branch_id'] = $branchId;
                    $data_prospect['estimate_prospect_worth'] = $value_esimate_prospect_worth;
                    $data_prospect['prospect_generate'] = 0;
                    $data_prospect['campaign_id'] = 0;
                    $data_prospect['number_type1'] = $type1;
                    $data_prospect['number_type2'] = $type2;
                    $data_prospect['phone_no'] = $value_phone_number1;
                    $data_prospect['phone_no2'] = $value_phone_number2;
                    $data_prospect['prospect_owner_id'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $data_prospect['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $data_prospect['creation_date'] =  datetimeformat();
                    $data_prospect['created_date'] = datetimeformat();
                    $data_prospect['modified_date'] =  datetimeformat();
                    $data_prospect['status'] = 1;
                    $data_prospect['is_delete'] = 0;

                    if ($value_account_name != '') {
                        $prospectId = $this->common_model->insert(PROSPECT_MASTER, $data_prospect);

                        if ($prospectId) {
                            $data_opportunity_requirement['requirement_description'] = $value_description;
                            $data_opportunity_requirement['prospect_id'] = $prospectId;
                            $data_opportunity_requirement['created_date'] = datetimeformat();
                            $data_opportunity_requirement['modified_date'] = datetimeformat();
                            $data_opportunity_requirement['status'] = 1;

                            $opportunity_req_id = $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $data_opportunity_requirement);

                            if ($opportunity_req_id) {

                                $arry_products = explode(",", $value_inserted_products_services);

                                foreach ($arry_products as $k => $v) {
                                    $product_id = $this->Opportunity_model->getProductIdByName($v);
                                    if ($product_id != "" && $prospectId != "") {
                                        $data_prospect_product_tran['product_id'] = $product_id;
                                        $data_prospect_product_tran['prospect_id'] = $prospectId;
                                        $data_prospect_product_tran['created_date'] = datetimeformat();
                                        $data_prospect_product_tran['modified_date'] = datetimeformat();
                                        $data_prospect_product_tran['status'] = 1;
                                        $prospect_pro_trns_id = $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $data_prospect_product_tran);
                                    }
                                }
                            }
							//$errorArraySet[] = $value_account_name;
							$errorArraySet[] = $lineKey.' - '.$prospectId;
                            $count_success++;
							$prospectId = "";
                        } else {
                            $count_fail++;
                        }
                    } else {
                        $count_fail++;
                    }
                } else {
                    $count_fail++;
                }
			}
			$errorFilePath = fopen(FCPATH."/uploads/error_debug/errorCsvDebug.txt","w");
			foreach($errorArraySet as $addedFile)
			{
				fwrite($errorFilePath, $addedFile.""."\r\n");
			}
			fclose($errorFilePath);
            $msg = "Succesfully Imported ! Total Record : $total_record, Successfully Imported : $count_success, Fail Record : $count_fail ";
            $this->session->set_flashdata('msg', $msg);
        }
		redirect($this->viewname);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Export Opportunity
      Input  :
      Output :
      Date   : 16/04/2016
     */

    function exportOpportunitys() {
        $this->Opportunity_model->exportOpportunity();
        redirect('Opportunity');
    }

    /*
      @Author    : Seema Tankariya
      @Desc      : Opportunity Model viewdata Page
      @Input     : Get id
      @Output    : View all data related to id
      @Date      : 13/01/2016
     */

    public function viewdata($id = NULL) {
        if ($id > 0) {
            $this->breadcrumbs->push(lang('crm'), '/');
            $this->breadcrumbs->push(lang('sales_overview'), 'SalesOverview');
            $this->breadcrumbs->push(lang('opportunities'), 'Opportunity');

            $_SESSION['current_related_id'] = $id;
            //Get Records From PROSPECT_MASTER Table with JOIN
            $data = array();
            $params['join_tables'] = array(
                COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                LANGUAGE_MASTER . ' as lan' => 'lan.language_id=pm.language_id',
                COUNTRIES . ' as c' => 'c.country_id=cm.country_id',
                CONTACT_MASTER . ' as ct' => 'pm.prospect_owner_id=ct.contact_id');
            $params['join_type'] = 'left';
            $match = " pm.is_delete=0 and pm.prospect_id = " . $id;
            $table = PROSPECT_MASTER . ' as pm';
            $groupBy = 'pm.prospect_id';
            $fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,pm.status_type,pm.company_id,"
                . "cm.address1,cm.address2,pm.creation_date,cm.postal_code,cm.city,cm.state,cm.country_id,pm.number_type1,"
                . "cm.company_name,pm.phone_no as phone_number,pm.phone_no2,pm.prospect_owner_id,lan.language_name,"
                . "pm.branch_id,pm.prospect_generate,pm.campaign_id,pm.description,pm.company_id,"
                . "cm.logo_img,cm.email_id as company_email,c.country_name,pm.fb,pm.twitter,pm.linkedin,ct.contact_name");
            $editRecord = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $groupBy);

            //get company account
            $table1 = PROSPECT_MASTER . ' as pm';
            $match1 = " pm.is_delete=0 and pm.status_type=2 and pm.company_id=" . $editRecord[0]['company_id'];
            $fields1 = array("pm.prospect_id");
            $data['companyData'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

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

            //Get owner data from LOGIN table
            $data['prospect_owner'] = $this->common_model->getSystemUserData();

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

            if (count($data['all_records'][0]) < 4) {
                redirect('Opportunity');
            }

            $searchtext = @$this->session->userdata('searchtext');
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            }
            //for breadcrumbs
            $this->breadcrumbs->push($editRecord[0]['prospect_name'], ' ');
            $data['header'] = array('menu_module' => 'crm');
            $data['modal_title'] = $this->lang->line('view_account');
            $data['opportunity_view'] = $this->viewname;
            $data['drag'] = true;
            $data['main_content'] = '/ViewOpportunity';
            $this->parser->parse('layouts/DashboardTemplate', $data);
        } else {
            redirect('Opportunity');
        }
    }

    public function uploadImage($input, $path, $redirect, $fileName = null, $file_ext_tolower = false, $encrypt_name = false, $remove_spaces = false, $detect_mime = true) {
        $files = $_FILES;
        $FileDataArr = array();
        $config['upload_path'] = $path;
        $config['allowed_types'] = '*';
        $config['max_size'] = 20480;
//        $config['max_width'] = 1024;
//        $config['max_height'] = 768;
        $config['file_ext_tolower'] = $file_ext_tolower;
        $config['encrypt_name'] = $encrypt_name;
        $config['remove_spaces'] = $remove_spaces;
        $config['detect_mime'] = $detect_mime;
        if ($fileName != null) {
            $config['file_name'] = $fileName;
        }
        $tmpFile = count($_FILES[$input]['name']);

        if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
            for ($i = 0; $i < $tmpFile; $i++) {
                $_FILES[$input]['name'] = $files[$input]['name'][$i];
                $_FILES[$input]['type'] = $files[$input]['type'][$i];
                $_FILES[$input]['tmp_name'] = $files[$input]['tmp_name'][$i];
                $_FILES[$input]['error'] = $files[$input]['error'][$i];
                $_FILES[$input]['size'] = $files[$input]['size'][$i];

                $content = file_get_contents($_FILES[$input]['tmp_name']);
                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = 'Invalid File';
                    echo json_encode($json);
                    die;
                }
                $this->load->library('upload', $config);
                if ($this->upload->do_upload($input)) {
                    $FileDataArr[] = $this->upload->data();
                } else {
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . $this->upload->display_errors() . "</div>");
                    redirect($redirect);
                    die;
                }
            }
        }
        return $FileDataArr;
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
      @Author : Maulik Suthar
      @Desc   : Exports the csv
      @Input 	:
      @Output	:
      @Date   : 19/02/2016
     */

    function exportToCSV() {

        $dbSearch = " pm.status_type=1 and pm.status=1 and is_delete=0 ";
        if ($this->input->post('search_branch_id') != '') {
            $dbSearch .= " and pm.branch_id=" . $this->input->post('search_branch_id');
        }
        if ($this->input->post('search_prospect_owner_id') != '') {
            $dbSearch .= " and pm.prospect_owner_id=" . $this->input->post('search_prospect_owner_id');
        }
        if ($this->input->post('status') != '') {
            $dbSearch .= " and pm.status=" . $this->input->post('status');
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
        $data['prospect_data'] = $this->Opportunity_model->exportCsvData($dbSearch);
    }

    /*
      @Author    : Seema Tankariya
      @Desc      : Opportunity Model convertWinAccount
      @Input     : Get input post id
      @Output    : convert id to win account by change status
      @Date      : 13/01/2016
     */

    public function convertWinAccount() {
        $prospectId = $this->input->post('prospect_id');


        $table = OPPORTUNITY_REQUIREMENT . ' as pm';
        $match = "pm.prospect_id = " . $prospectId;
        $fields = array("pm.requirement_description");
        $descriptionData = $this->common_model->get_records($table, $fields, '', '', $match);


        $table = PROSPECT_MASTER . ' as pm';
        $match = "pm.prospect_id = " . $prospectId;
        $fields = array("pm.prospect_name,pm.prospect_owner_id,pm.company_id");
        $prospectData = $this->common_model->get_records($table, $fields, '', '', $match);

        /*
         * will sends email if convert opportunity to win client
         */
        $umatch = "login_id =" . $prospectData[0]['prospect_owner_id'];
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $newProspectOwnerData = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);

        if (count($newProspectOwnerData) > 0) {
            $prospectOwnerEmail = $newProspectOwnerData[0]['email'];
            $prospectOwnerName = $newProspectOwnerData[0]['fullname'];
            $prospectName = $prospectData[0]['prospect_name'];
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
                $url = base_url('Account/');
                $replace = array(
                    'USER' => $prospectOwnerName,
                    'OPPORTUNITY_NAME' => $prospectName,
                    'LINK' => "<a href='" . $url . "' title='view opportunity'>View</a>",
                    'TYPE' => ' Prospect'
                );
                $format = $template[0]['body'];
                $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
                $subject = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $template[0]['subject']))));

                send_mail($prospectOwnerEmail, $subject, $body);
            }
        }
        $where = array('prospect_id' => $prospectId);
        $prospectMasterData['status_type'] = 3;
        $prospectMasterData['won_lost_date'] = datetimeformat();
        $successUpdate = $this->common_model->update(PROSPECT_MASTER, $prospectMasterData, $where);

        //If company not have account then create new company account with status_type=2
        $companyId = $prospectData[0]['company_id'];
        //check this company account exist or not
        $table = PROSPECT_MASTER . ' as pm';
        $match = "pm.company_id = " . $companyId . " and status_type=2 and pm.is_delete=0";
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

            $opportunityRequirementAccount['requirement_description'] = $descriptionData[0]['requirement_description'];
            $opportunityRequirementAccount['modified_date'] = datetimeformat();
            $opportunityRequirementAccount['prospect_id'] = $CompanyAccountId;
            $opportunityRequirementAccount['created_date'] = datetimeformat();
            $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $opportunityRequirementAccount);
        }


        //COMPANY_ACCOUNTS_TRAN data update 
        $where = array('client_id' => $prospectId, 'status_type' => 1);
        $companyAccountdata['status_type'] = 3;
        $this->common_model->update(COMPANY_ACCOUNTS_TRAN, $companyAccountdata, $where);

        //change lead id to prosepect id in notes table
        $whereNote = array('notes_related_id' => $prospectId, 'note_status' => 4);
        $noteData['note_status'] = 2;
        $this->common_model->update(TBL_NOTE, $noteData, $whereNote);

        //change lead id to prosepect id in Task_master table
        $whereTask = array('task_related_id' => $prospectId, 'task_status' => 4);
        $taskData['task_status'] = 2;
        $this->common_model->update(TASK_MASTER, $taskData, $whereTask);

        //change lead id to prosepect id in Prospect_master table
        $whereDeal = array('prospect_related_id' => $prospectId, 'deal_status' => 4);
        $dealData['deal_status'] = 2;
        $this->common_model->update(PROSPECT_MASTER, $dealData, $whereDeal);

        //change lead id to prosepect id in Campaign_contact table
        $whereCampaign = array('campaign_related_id' => $prospectId, 'campaign_status' => 4);
        $campaignData['campaign_status'] = 2;
        $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $whereCampaign);

        //change lead id to prosepect id in Cases table
        $whereCases = array('cases_related_id' => $prospectId, 'cases_status' => 4);
        $casesData['cases_status'] = 2;
        $this->common_model->update(CRM_CASES, $casesData, $whereCases);

        //change lead id to prosepect id in Events table
        $whereEvents = array('event_related_id' => $prospectId, 'event_status' => 4);
        $eventData['event_status'] = 2;
        $this->common_model->update(TBL_EVENTS, $eventData, $whereEvents);

        //change lead id to prosepect id in Schedule Meeting table
        $whereMeeting = array('meet_related_id' => $prospectId, 'meet_status' => 4);
        $meetingData['meet_status'] = 2;
        $this->common_model->update(TBL_SCHDEULE_MEETING, $meetingData, $whereMeeting);

        unset($prospectId);
        $deleteAllFlag = 0;
        $cnt = 0;
        //pagingation
        $searchsort_session = $this->session->userdata('opportunity_data');

        if (!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
            $pagingid = 0;
        $perpage = !empty($searchsort_session['perpage']) ? $searchsort_session['perpage'] : '10';
        $total_rows = $searchsort_session['total_rows'];
        if ($deleteAllFlag == 1) {
            $total_rows -= $cnt;
            $pagingid * $perpage;
            if ($pagingid * $perpage > $total_rows) {
                if ($total_rows % $perpage == 0) { // if all record delete
                    $pagingid -= $perpage;
                }
            }
        } else {
            if ($total_rows % $perpage == 1)
                $pagingid -= $perpage;
        }

        if ($pagingid < 0)
            $pagingid = 0;
        echo $pagingid;
    }

    public function convertLostAccount() {
        $prospectId = $this->input->post('prospect_id');

        $table = PROSPECT_MASTER . ' as pm';
        $match = "pm.prospect_id = " . $prospectId;
        $fields = array("pm.prospect_name,pm.prospect_owner_id");
        $prospectData = $this->common_model->get_records($table, $fields, '', '', $match);

        /*
         * will sends email if convert opportunity to lost client
         */
        $umatch = "login_id =" . $prospectData[0]['prospect_owner_id'];
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $newProspectOwnerData = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);

        if (count($newProspectOwnerData) > 0) {
            $prospectOwnerEmail = $newProspectOwnerData[0]['email'];
            $prospectOwnerName = $newProspectOwnerData[0]['fullname'];
            $prospectName = $prospectData[0]['prospect_name'];
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
                $url = base_url('Account/');
                $replace = array(
                    'USER' => $prospectOwnerName,
                    'OPPORTUNITY_NAME' => $prospectName,
                    'LINK' => "<a href='" . $url . "' title='view opportunity'>View</a>",
                    'TYPE' => ' Prospect'
                );
                $format = $template[0]['body'];
                $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
                $subject = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $template[0]['subject']))));

                send_mail($prospectOwnerEmail, $subject, $body);
            }
        }

        $where = array('prospect_id' => $prospectId);
        $prospectMasterData['status_type'] = 4;
        $prospectMasterData['won_lost_date'] = datetimeformat();
        $this->common_model->update(PROSPECT_MASTER, $prospectMasterData, $where);



        //COMPANY_ACCOUNTS_TRAN data update 
        $where = array('client_id' => $prospectId, 'status_type' => 1);
        $companyAccountdata['status_type'] = 4;
        $this->common_model->update(COMPANY_ACCOUNTS_TRAN, $companyAccountdata, $where);

        //change lead id to prosepect id in notes table
        $whereNote = array('notes_related_id' => $prospectId, 'note_status' => 4);
        $noteData['note_status'] = 2;
        $this->common_model->update(TBL_NOTE, $noteData, $whereNote);

        //change lead id to prosepect id in Task_master table
        $whereTask = array('task_related_id' => $prospectId, 'task_status' => 4);
        $taskData['task_status'] = 2;
        $this->common_model->update(TASK_MASTER, $taskData, $whereTask);

        //change lead id to prosepect id in Prospect_master table
        $whereDeal = array('prospect_related_id' => $prospectId, 'deal_status' => 4);
        $dealData['deal_status'] = 2;
        $this->common_model->update(PROSPECT_MASTER, $dealData, $whereDeal);

        //change lead id to prosepect id in Campaign_contact table
        $whereCampaign = array('campaign_related_id' => $prospectId, 'campaign_status' => 4);
        $campaignData['campaign_status'] = 2;
        $this->common_model->update(TBL_CAMPAIGN_CONTACT, $campaignData, $whereCampaign);

        //change lead id to prosepect id in Cases table
        $whereCases = array('cases_related_id' => $prospectId, 'cases_status' => 4);
        $casesData['cases_status'] = 2;
        $this->common_model->update(CRM_CASES, $casesData, $whereCases);

        //change lead id to prosepect id in Events table
        $whereEvents = array('event_related_id' => $prospectId, 'event_status' => 4);
        $eventData['event_status'] = 2;
        $this->common_model->update(TBL_EVENTS, $eventData, $whereEvents);

        //change lead id to prosepect id in Schedule Meeting table
        $whereMeeting = array('meet_related_id' => $prospectId, 'meet_status' => 4);
        $meetingData['meet_status'] = 2;
        $this->common_model->update(TBL_SCHDEULE_MEETING, $meetingData, $whereMeeting);

        unset($prospectId);
    }

    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('Prospect_img_url') . '/' . $filename, $str);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Opportunity Model delete_contact_master Page
      @Input 	: input post contact id data
      @Output	: delete from CONTACT_MASTER
      @Date   : 18/02/2016
     */

    public function delete_contact_master() {
        $redirectLink = $_SERVER['HTTP_REFERER'];
        $id = $this->input->get('id');

        //Delete Record From Database
        //$redirectLink=$this->input->get('link');
        if ($id) {

            $where = array('contact_id' => $id);
            $contact_data['is_delete'] = 1;
            $successDelete = $this->common_model->update(CONTACT_MASTER, $contact_data, $where);
            if ($successDelete) {
                $msg = $this->lang->line('contact_del_msg');
                $this->session->set_flashdata('message', $msg);
            }
        }
        redirect($redirectLink);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Opportunity Model deletedataSalesOverview
      @Input 	: input post  id
      @Output	: delete data for opportunity from dashboard
      @Date   : 18/02/2016
     */

    public function deletedataSalesOverview() {

        $prospectId = $this->input->get('id');

        //Delete Record From Database
        if (!empty($prospectId)) {
            $where = array('prospect_id' => $prospectId);
            $prospectData['is_delete'] = 1;
            $deleteSuceess = $this->common_model->update(PROSPECT_MASTER, $prospectData, $where);

            if ($deleteSuceess) {
                $msg = $this->lang->line('opp_del_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            unset($prospectId);
        }

        if (strpos($redirectLink, 'Contact/view') !== false) {
            $sessArray = array('setting_current_tab' => 'Deals');
            $this->session->set_userdata($sessArray);
            $this->session->set_flashdata('message', lang('opp_del_msg'));
        }


        redirect('SalesOverview'); //Redirect On Listing page
    }

    /*
      @Author   : Disha Patel
      @Desc     : Opportunity Model getCompanyDataById
      @Input 	: input post  id
      @Output	: get data for post company id and autifill
      @Date   : 18/02/2016
     */

    function getCompanyDataById() {
        $companyId = $this->input->post('company_id');
        $previewProducts['fields'] = ['cm.phone_no,cm.branch_id,bm.branch_name,cm.address1,cm.address2,cm.city,cm.state,cm.country_id,cm.postal_code,cm.logo_img'];
        $previewProducts['table'] = COMPANY_MASTER . ' as cm';
        $previewProducts['join_tables'] = array(BRANCH_MASTER . ' AS bm' => 'bm.branch_id=cm.branch_id',);
        $previewProducts['join_type'] = "left";
        $previewProducts['match_and'] = 'cm.company_id=' . $companyId;
        $data['company_data'] = $this->common_model->get_records_array($previewProducts);
        echo json_encode($data['company_data'][0]);
    }

    /*
     * Maulik Suthar
     * function is used to validating  contact uniquess by email
     */

    function validateContactUniqueness() {

        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts allowed");
        }
        $contact_email = $this->input->post('email');
        $id = $this->input->post('id');
        $companyId = $this->input->post('company_id');

        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = " cm.is_delete=0 and cm.status=1 and email='" . $contact_email . "'";
        if ($companyId != null && $companyId != '') {
            $match1.=" and company_id=" . $companyId;
        }
//        if ($id > 0) {
//            $match1.=" and contact_id=" . $id;
//        }
        $fields1 = array("cm.contact_id,cm.contact_name");
        $contactData = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', $match1);
        if (count($contactData) > 0) {
            echo json_encode(array('status' => 1));
        } else {
            echo json_encode(array('status' => 0));
        }
        die;
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : get post company id and view all contact data
      @Input    : get id
      @Output   : view data
      @Date     : 20/05/2016
     */

    function updateStatus() {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $currtype = $this->input->post('currtype');
        $login_id = $this->session->userdata['LOGGED_IN']['ID'];
        $dataType = $this->input->post('dataType');
        $prospecting = lang('prospecting');
        $proposal = lang('proposal');
        $won = lang('won');
        $lost = lang('lost');

        if ($id > 0 && $id != '') {
            if ($type == $proposal && $currtype == $prospecting) {
                $updateData['is_estimate_sent'] = 1;
                $success = $this->common_model->update(PROSPECT_MASTER, $updateData, array('prospect_id' => $id));
                echo "done";
            } else if ($type == $won && $currtype == $prospecting) {
                $updateData['status_type'] = 3;
                $this->common_model->update(PROSPECT_MASTER, $updateData, array('prospect_id' => $id));
                echo "done";
            } else if ($type == 'Lost' && $currtype == $prospecting) {
                $updateData['status_type'] = 4;
                $this->common_model->update(PROSPECT_MASTER, $updateData, array('prospect_id' => $id));
                echo "done";
            } else if ($type == $prospecting && $currtype == $proposal) {
                $updateData['is_estimate_sent'] = 0;
                $this->common_model->update(PROSPECT_MASTER, $updateData, array('prospect_id' => $id));
                echo "done";
            } else if ($type == $won && $currtype == $proposal) {

                $updateData['status_type'] = 3;
                $this->common_model->update(PROSPECT_MASTER, $updateData, array('prospect_id' => $id));
                echo "done";
            } else if ($type == $lost && $currtype == $proposal) {
                $updateData['status_type'] = 4;
                $this->common_model->update(PROSPECT_MASTER, $updateData, array('prospect_id' => $id));
                echo "done";
            } else if ($type == $lost && $currtype == $won) {
                $updateData['status_type'] = 4;
                $this->common_model->update(PROSPECT_MASTER, $updateData, array('prospect_id' => $id));
                echo "done";
            } else if ($type == $won && $currtype == $lost) {
                $updateData['status_type'] = 3;
                $this->common_model->update(PROSPECT_MASTER, $updateData, array('prospect_id' => $id));
                echo "done";
            }
        }
    }

}
