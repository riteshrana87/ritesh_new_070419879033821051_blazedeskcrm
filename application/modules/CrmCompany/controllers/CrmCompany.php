<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CrmCompany extends CI_Controller {

    function __construct() {
        parent::__construct();
        if(checkPermission('CrmCompany','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->prefix = $this->db->dbprefix;
        $this->module = $this->router->fetch_class();
        $this->viewname = $this->router->fetch_class();
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : CrmCompany Model Index Page
      @Input 	:
      @Output	:
      @Date     : 06/05/2016
     */

    //view of indexpage
    public function index($page = '') {

        $data['main_content'] = '/CrmCompany';
        $data['header'] = array('menu_module' => 'crm');
        $data['company_view'] = $this->viewname;

        //Get uri segment for pagination
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('company_data');
        }

        $searchsort_session = $this->session->userdata('company_data');
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
                $sortfield = 'company_id';
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
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = $cur_uri_segment;
            $uri_segment = $page;
        }
//Get Records From country_master Table
        $table_country_master = COUNTRIES . ' as c';
        $fields_country_master = array("c.*");
        $data['country_master'] = $this->common_model->get_records($table_country_master, $fields_country_master);
//Get Records From BRANCH_MASTER Table       
        $table_branch_master = BRANCH_MASTER . ' as bm';
        $match_branch_master = " bm.status=1 ";
        $fields_branch_master = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table_branch_master, $fields_branch_master, '', '', $match_branch_master);
//Join table
        $join_tables = array('countries c' => 'c.country_id=cm.country_id');
        $params['join_type'] = 'left';
        $table = COMPANY_MASTER . ' as cm';
        $group_by = 'cm.company_id';
        $fields = array("cm.company_id,cm.company_name,cm.email_id, cm.website,cm.phone_no,cm.country_id,cm.created_date,c.country_name,cm.status");
        $where = "cm.is_delete=0";

        //left filter search records
        $leftSearch = array();
        $data['branch_show_id'] = "";
        if ($this->input->post('search_branch_id') != "") {
            $data['branch_show_id'] = $this->input->post('search_branch_id');
            $where.=' and cm.branch_id=' . $data['branch_show_id'];
            $leftSearch['branch_show_id'] = $this->input->post('search_branch_id');
        }
        $data['country_show_id'] = "";
        if ($this->input->post('search_country_id') != "") {
            $data['country_show_id'] = $this->input->post('search_country_id');
            $where.=' and cm.country_id=' . $data['country_show_id'];
            $leftSearch['country_show_id'] = $this->input->post('country_show_id');
        }
        $data['status_show'] = "";
        if ($this->input->post('search_status') != "") {
            $data['status_show'] = $this->input->post('search_status');
            $where.=' and cm.status=' . $data['status_show'];
            $leftSearch['status_show'] = $this->input->post('search_status');
        }

        $data['search_creation_date_show'] = "";
        if ($this->input->post('search_creation_date') != "") {
            $data['search_creation_date_show'] = date_format(date_create($this->input->post('search_creation_date')), 'Y-m-d');
            $where.=' and cm.created_date>="' . $data['search_creation_date_show'] . '"';
            $leftSearch['search_creation_date_show'] = date_format(date_create($this->input->post('search_creation_date')), 'Y-m-d');
        }
        $data['creation_end_date_show'] = "";
        if ($this->input->post('creation_end_date') != "") {
            $data['creation_end_date_show'] = date_format(date_create($this->input->post('creation_end_date')), 'Y-m-d');
            $where.=' and cm.created_date<="' . $data['creation_end_date_show'] . '"';
            $leftSearch['creation_end_date_show'] = date_format(date_create($this->input->post('creation_end_date')), 'Y-m-d');
        }

        if (!empty($searchtext)) {
//If have any search text
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $where_search = '(cm.company_name LIKE "%' . $searchtext . '%" OR cm.email_id LIKE "%' . $searchtext . '%" OR cm.website LIKE "%' . $searchtext . '%" OR cm.phone_no LIKE "%' . $searchtext . '%"  OR c.country_name LIKE "%' . $searchtext . '%")';
            $data['company_data'] = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);

            //get total company from query
            $totalCompany = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);
        } else {
//Not have any search input
            $data['company_data'] = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');

            //get total company from query
            $totalCompany = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
        }

        //check  total company > 0 otherwise set total company = 0
        if ($totalCompany) {
            $data['total_company'] = $totalCompany;
        } else {
            $data['total_company'] = '0';
        }


        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['drag'] = true;
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('company_data', $sortsearchpage_data);

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view('AjaxCompanyList', $data);
        } else {
            $data['main_content'] = '/' . $this->viewname;
            $this->parser->parse('layouts/DashboardTemplate', $data);
        }
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model Add Page
      @Input 	:
      @Output	: Show add company modal
      @Date     : 06/05/2016
     */

    // view of add company data page 
    public function Add() {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data = array();
            $data['company_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'crm');
            $data['main_content'] = '/CrmCompany';
            $data['drag'] = true;
            $table_branch_master = BRANCH_MASTER . ' as bm';
            $match_branch_master = "bm.status=1";
            $fields_branch_master = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($table_branch_master, $fields_branch_master, '', '', $match_branch_master);
            $table1 = COUNTRIES . ' as c';
            $fields1 = array("c.country_id", "c.country_name", "c.country_code");
            $data['country'] = $this->common_model->get_records($table1, $fields1);

            $redirect_link = $this->input->post('redirect_link');
            $this->load->view('AddEditCompany', $data);
        }
        
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model Insertdata
      @Input 	: Input post data
      @Output	: Insert data in database
      @Date     : 06/05/2016
     */

    //save company data
    public function insertData() {
        if (!validateFormSecret()) {
            redirect($this->viewname); //Redirect On Listing page
        }
        $data = array();
        $data['company_view'] = $this->viewname;
        $company_data['company_name'] = strip_slashes(trim($this->input->post('company_name')));
        $company_data['email_id'] = $this->input->post('email_id');
        $company_data['phone_no'] = $this->input->post('phone_no');
        $company_data['website'] = $this->input->post('website');
        $company_data['company_id_data'] = $this->input->post('company_id_data');
        $company_data['reg_number'] = $this->input->post('com_reg_number');
        $company_data['address1'] = strip_slashes($this->input->post('address1'));
        $company_data['address2'] = strip_slashes($this->input->post('address2'));
        $company_data['city'] = strip_slashes($this->input->post('city'));
        $company_data['state'] = strip_slashes($this->input->post('state'));
        $company_data['country_id'] = $this->input->post('country_id');
        $company_data['postal_code'] = $this->input->post('postal_code');
        $company_data['status'] = $this->input->post('status');
        $company_data['company_id_data'] = $this->input->post('company_id_data');
        $company_data['reg_number'] = $this->input->post('com_reg_number');


        $company_data['modified_date'] = datetimeformat();
        $branch_name = strip_slashes($this->input->post('branch_id'));
        $table = BRANCH_MASTER . ' as bm';
        $match = "bm.branch_name='" . addslashes($branch_name) . "' and status=1 ";
        $fields = array("bm.branch_name, bm.branch_id");
        $branch_record = $this->common_model->get_records($table, $fields, '', '', $match);
        if ($branch_record) {
            $company_data['branch_id'] = $branch_record[0]['branch_id'];
        } else {
            $branch_data['branch_name'] = $branch_name;
        }
        if (count($branch_record) == 0) {
            //INSERT Branch
            $branch_data['created_date'] = datetimeformat();
            $branch_data['modified_date'] = datetimeformat();
            $branch_data['status'] = 1;
            $branch_id = $this->common_model->insert(BRANCH_MASTER, $branch_data);
            $company_data['branch_id'] = $branch_id;
        }

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
                    $company_data['logo_img'] = $file['file_name'];
                }
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
            }
        }

        //Insert Record in Database
        $table = COMPANY_MASTER . ' as cm';
        $match = "cm.company_name='" . addslashes($company_data['company_name']) . "' and cm.branch_id ='" . $company_data['branch_id'] . "'  and status = 1 ";
        $fields = array("cm.company_name, cm.company_id");
        $company_record = $this->common_model->get_records($table, $fields, '', '', $match);

        if ($company_record) {
            $where = array('company_name' => $company_data['company_name']);
            $success_update = $this->common_model->update(COMPANY_MASTER, $company_data, $where);
            if ($success_update) {
                $msg = $this->lang->line('company_update_msg');
                $this->session->set_flashdata('msg', $msg);
            }
        } else {
            $company_data['created_date'] = datetimeformat();
            $success_insert = $prospect_contacts_tran['prospect_id'] = $this->common_model->insert(COMPANY_MASTER, $company_data);

            if ($success_insert) {
                $msg = $this->lang->line('company_add_msg');
                $this->session->set_flashdata('msg', $msg);
            }
        }
        $table_master = COMPANY . ' as cm';
        $match_master = "cm.company_name='" . addslashes($company_data['company_name']) . "' and cm.status=1 ";
        $fields_master = array("cm.*");
        $company_master = $this->common_model->get_records_data($table_master, $fields_master, '', '', $match_master);

        if (count($company_master) == 0) {
            $contactdata['company_id'] = $this->common_model->insert_data(COMPANY, $company_data);
        } else {
            $where = array('company_id' => $company_master[0]['company_id']);
            $this->common_model->update_data(COMPANY, $company_data, $where);
        }

        redirect($this->viewname); //Redirect On Listing page
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model Edit Page
      @Input 	: get id
      @Output	: Show related id data in modal
      @Date     : 06/05/2016
     */

    //view of edit company page
    public function edit($id) {

        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['company_view'] = $this->viewname;
            $data['drag'] = true;
            $redirect_link = $this->input->post('redirect_link');

            $fields = array('c.*', 'cm.*');
            $where = array("cm.company_id" => $id);
            $main_table = COMPANY_MASTER . ' as cm';
            $join_tables = array('countries c' => 'c.country_id=cm.country_id');

            $data['editRecord'] = $this->common_model->get_records($main_table, $fields, $join_tables, 'LEFT', '', '', '', '', '', '', '', '', '', $where);


            if ($data['editRecord'][0]['logo_img'] != '') {
                $c_log = FCPATH . "uploads/company/" . $data['editRecord'][0]['logo_img'];

                $tmp_logo = "";
                if (file_exists($c_log)) {
                    $tmp_logo = $data['editRecord'][0]['logo_img'];
                } else {
                    $tmp_logo = "noimage.jpg";
                }
                $data['editRecord'][0]['logo_img'] = $tmp_logo;
            }


            $table1 = COUNTRIES . ' as c';
            $fields1 = array("c.country_id", "c.country_name", "c.country_code");
            $data['country'] = $this->common_model->get_records($table1, $fields1);

            $table_branch_master = BRANCH_MASTER . ' as bm';
            $match_branch_master = " bm.status=1 ";
            $fields_branch_master = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($table_branch_master, $fields_branch_master, '', '', $match_branch_master);

            $table2 = BRANCH_MASTER . ' as bm';
            $match2 = "bm.branch_id=(SELECT cm.branch_id from blzdsk_company_master as cm WHERE cm.company_id = " . $id . ") and bm.status=1 ";
            $fields2 = array("bm.branch_id,bm.branch_name");
            $data['branch_data1'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

            $this->load->view('AddEditCompany', $data);

        }
        
    }

    //delete company data
    public function deleteData($id) {


        //Delete Record From Database
        if (!empty($id)) {
            $where = array('company_id' => $id);
            $company_data['is_delete'] = 1;
            $delete_suceess = $this->common_model->update(COMPANY_MASTER, $company_data, $where);
            if ($delete_suceess) {
                $msg = $this->lang->line('company_del_msg');
                $this->session->set_flashdata('msg', $msg);
            }

            unset($id);
        }
        redirect($this->viewname); //Redirect On Listing page
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model Update  Page
      @Input 	: Input post data
      @Output	: Update data in database
      @Date     : 06/05/2016
     */

    //update company data
    public function updateData() {

        $id = $id = $this->input->post('company_id');

        $data['company_view'] = $this->viewname;

        $redirect_link = $this->input->post('redirect_link');
        $update['company_name'] = strip_slashes(trim($this->input->post('company_name')));
        $update['email_id'] = $this->input->post('email_id');
        $update['phone_no'] = $this->input->post('phone_no');
        $update['website'] = $this->input->post('website');
        $update['company_id_data'] = $this->input->post('company_id_data');
        $update['reg_number'] = $this->input->post('com_reg_number');
        $update['address1'] = strip_slashes($this->input->post('address1'));
        $update['address2'] = strip_slashes($this->input->post('address2'));
        $update['city'] = strip_slashes($this->input->post('city'));
        $update['state'] = strip_slashes($this->input->post('state'));
        $update['country_id'] = $this->input->post('country_id');
        $update['postal_code'] = $this->input->post('postal_code');
        $update['status'] = $this->input->post('status');


        $branch_name = strip_slashes($this->input->post('branch_id'));
        $table = BRANCH_MASTER . ' as bm';
        $match = "bm.branch_name='" . addslashes($branch_name) . "' and status=1 ";
        $fields = array("bm.branch_name, bm.branch_id");
        $branch_record = $this->common_model->get_records($table, $fields, '', '', $match);
        if ($branch_record) {
            $update['branch_id'] = $branch_record[0]['branch_id'];
        } else {
            $branch_data['branch_name'] = $branch_name;
        }
        if (count($branch_record) == 0) {
            //INSERT Branch
            $branch_data['created_date'] = datetimeformat();
            $branch_data['modified_date'] = datetimeformat();
            $branch_data['status'] = 1;
            $branch_id = $this->common_model->insert(BRANCH_MASTER, $branch_data);
            $update['branch_id'] = $branch_id;
        }
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
                    $update['logo_img'] = $file['file_name'];
                }
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
            }
        }

        $table = COMPANY_MASTER . ' as c';
        $where = "company_id = $id ";

        $data['update_record'] = $this->db->where($where)->update($table, $update);

        $table_master = COMPANY . ' as cm';
        $match_master = "cm.company_name='" . addslashes($update['company_name']) . "' and cm.status=1 ";
        $fields_master = array("cm.*");
        $company_master = $this->common_model->get_records_data($table_master, $fields_master, '', '', $match_master);

        if (count($company_master) == 0) {
            $contactdata['company_id'] = $this->common_model->insert_data(COMPANY, $update);
        } else {
            $where = array('company_id' => $company_master[0]['company_id']);
            $this->common_model->update_data(COMPANY, $update, $where);
        }


        $msg = $this->lang->line('company_update_msg');
        $this->session->set_flashdata('msg', $msg);

        //update company data also in prospect master table
        $whereProspect = "company_id = $id and status_type=2";
        $prospectData['prospect_name'] = strip_slashes(trim($this->input->post('company_name')));
        $prospectData['branch_id'] = $update['branch_id'];
        $prospectData['address1'] = strip_slashes($this->input->post('address1'));
        $prospectData['address2'] = strip_slashes($this->input->post('address2'));
        $prospectData['city'] = strip_slashes($this->input->post('city'));
        $prospectData['state'] = strip_slashes($this->input->post('state'));
        $prospectData['country_id'] = $this->input->post('country_id');
        $prospectData['postal_code'] = $this->input->post('postal_code');
        $prospectData['status'] = $this->input->post('status');
        $this->common_model->update(PROSPECT_MASTER, $prospectData, $whereProspect);

        redirect($this->viewname);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model View Page
      @Input 	: Get id
      @Output	: View id related data
      @Date     : 06/05/2016
     */

    //view page
    public function view($id) {
        if ($id > 0) {
            $_SESSION['current_company_id'] = $id;
            //Get Records From PROSPECT_MASTER Table with JOIN
            $_SESSION['current_related_id'] = $id;
            $data = array();
            $params['join_tables'] = array(
                BRANCH_MASTER . ' as bm' => 'bm.branch_id=cm.branch_id',
                //    LANGUAGE_MASTER . ' as lan' => 'lan.language_id=cm.language_id',
                COUNTRIES . ' as c' => 'c.country_id=cm.country_id');
            $params['join_type'] = 'left';
            $match = " cm.is_delete=0 and cm.company_id = " . $id;
            $table = COMPANY_MASTER . ' as cm';
            $group_by = 'cm.company_id';
            $fields = array("cm.company_id,cm.address1,cm.address2,cm.postal_code,cm.city,cm.state,cm.country_id,"
                . "cm.phone_no as phone_number,bm.branch_name,cm.created_date,"
                . "cm.company_name,cm.logo_img,cm.email_id as company_email,c.country_name");
            $editRecord = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);

//Get Records From BRANCH_MASTER Table       
            $tableBranchMaster = BRANCH_MASTER . ' as bm';
            $matchBranchMaster = " bm.status=1 ";
            $fieldsBranchMaster = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($tableBranchMaster, $fieldsBranchMaster, '', '', $matchBranchMaster);

//Get Selected Records From BRANCH_MASTER Table       
            $table2 = BRANCH_MASTER . ' as bm';
            $match2 = "bm.branch_id=(SELECT cm.branch_id from " . $this->prefix . COMPANY_MASTER . " as cm WHERE cm.company_id = " . $id . ") and bm.status=1 ";
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
            $fieldsCountries = array("c.country_id,c.country_name");
            $data['country_data'] = $this->common_model->get_records($tableCountries, $fieldsCountries, '', '', $matchCountries);

            //get estimate_prospect_worth value for view account
            if($editRecord){
                 $params['join_tables'] = array(
                ESTIMATE_MASTER . ' as em' => 'em.estimate_id=pm.estimate_prospect_worth');
                $params['join_type'] = 'left';
                $match = "pm.company_id = " . $editRecord[0]['company_id'] . " and pm.is_delete=0 and pm.status=1 ";
                $table = PROSPECT_MASTER . ' as pm';
                $fields = array("em.value,pm.prospect_id,pm.prospect_auto_id,pm.status_type,pm.is_estimate_sent,pm.prospect_name,pm.creation_date");
                $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match);
            }
           

            $data['prospect_owner'] = $this->common_model->getSystemUserData();
            $data['id'] = $id;
            $data['all_records'] = $editRecord;

            if (count($data['all_records'][0]) < 1) {
                redirect('CrmCompany');
            }

            $searchtext = @$this->session->userdata('searchtext');
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            }
            $data['drag'] = true;
            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['header'] = array('menu_module' => 'crm');
            $data['modal_title'] = $this->lang->line('view_company');
            $data['account_view'] = $this->viewname;
            $data['main_content'] = '/view';
            $this->parser->parse('layouts/DashboardTemplate', $data);
        } else {
            redirect('CrmCompany');
        }
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Account Model validateCompanyUniqueness
      @Input 	: get email id
      @Output	: if match in database send status 1
      @Date     : 06/05/2016
     */

    public function validateCompanyUniqueness() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts allowed");
        }
        $company_email = $this->input->post('email');


        $table1 = COMPANY_MASTER . ' as cm';
        $match1 = " cm.is_delete=0 and cm.status=1 and email_id='" . $company_email . "'";


        $fields1 = array("cm.company_id,cm.company_name");
        $companyData = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', $match1);

        if (count($companyData) > 0) {
            echo json_encode(array('status' => 1));
        } else {
            echo json_encode(array('status' => 0));
        }
        die;
    }

}

?>