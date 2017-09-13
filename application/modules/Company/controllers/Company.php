<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = $this->router->fetch_class();
        $this->viewname = $this->router->fetch_class();
    }

    //view of indexpage
    public function index($page = '') {

        $data['main_content'] = '/Company';
        $data['header'] = array('menu_module' => 'support');
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

//Get Records From 
        $table1 = COMPANY_MASTER . ' as cm';
        $fields1 = array("count(cm.company_id) as total_company");
        $total_company = $this->common_model->get_records($table1, $fields1, '', '', $where);
        $data['total_company'] = $total_company[0]['total_company'];
        if (!empty($searchtext)) {
//If have any search text
            $searchtext = html_entity_decode(trim($searchtext));
            $where_search = '(cm.company_name LIKE "%' . $searchtext . '%" OR cm.email_id LIKE "%' . $searchtext . '%" OR cm.website LIKE "%' . $searchtext . '%" OR cm.phone_no LIKE "%' . $searchtext . '%"  OR c.country_name LIKE "%' . $searchtext . '%")';
            $data['company_data'] = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);
            $total_company=$this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);
            } else {
//Not have any search input
            $data['company_data'] = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
            $total_company=$this->common_model->get_records($table, $fields, $join_tables, $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
            }

             //check  total account > 0 otherwise set total account = 0
    if($total_company)
        {
             $data['total_company'] = $total_company;
        }
        else{
            $data['total_company']='0';
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
            $this->parser->parse('layouts/SupportTemplate', $data);
        }
    }

    // view of add company data page 
    public function Add() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{

        $data = array();
        $data['company_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'support');
        $data['main_content'] = '/Company';
        $data['drag'] = true;
        $table_branch_master = BRANCH_MASTER . ' as bm';
        $match_branch_master = "bm.status=1";
        $fields_branch_master = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table_branch_master, $fields_branch_master, '', '', $match_branch_master);
        $table1 = COUNTRIES . ' as c';
        $fields1 = array("c.country_id", "c.country_name" ,"c.country_code");
        $data['country'] = $this->common_model->get_records($table1, $fields1);

        $redirect_link = $this->input->post('redirect_link');
        $this->load->view('AddEditCompany', $data);
        }
    }

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
        $company_data['postal_code'] = strip_slashes($this->input->post('postal_code'));
        $company_data['status'] = $this->input->post('status');

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
                'upload_path' => "./uploads/company/",
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
        //pr($company_master);exit;
        if (count($company_master) == 0) {
            $contactdata['company_id'] = $this->common_model->insert_data(COMPANY, $company_data);
        }else{
            $where = array('company_id' => $company_master[0]['company_id']);
            $this->common_model->update_data(COMPANY, $company_data,$where);

        }
        redirect($this->viewname); //Redirect On Listing page
    }

    //view of edit company page
    public function edit($id) {
if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{
        $data['company_view'] = $this->viewname;
        $data['drag'] = true;
        $redirect_link = $this->input->post('redirect_link');

        $fields = array('c.*', 'cm.*');
        $where = array("cm.company_id" => $id);
        $main_table = COMPANY_MASTER . ' as cm';
        $join_tables = array('countries c' => 'c.country_id=cm.country_id');

        $data['editRecord'] = $this->common_model->get_records($main_table, $fields, $join_tables, 'LEFT', '', '', '', '', '', '', '', '', '', $where);
        
          if( $data['editRecord'][0]['logo_img'] != '')
        {
            $c_log = FCPATH."uploads/company/".$data['editRecord'][0]['logo_img'];
           
            $tmp_logo="";
            if(file_exists($c_log))
            {
                $tmp_logo = $data['editRecord'][0]['logo_img'];
                
            }
            else{
                $tmp_logo="noimage.jpg";
            }
            $data['editRecord'][0]['logo_img'] = $tmp_logo ;
        }
        $table1 = COUNTRIES . ' as c';
        $fields1 = array("c.country_id", "c.country_name","c.country_code");
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

    //update company data
    public function updateData() {
        $id = $id = $this->input->post('company_id');

        $data['company_view'] = $this->viewname;

        $redirect_link = $this->input->post('redirect_link');
        $update['company_name'] = $this->input->post('company_name');
        $update['email_id'] = $this->input->post('email_id');
        $update['phone_no'] = $this->input->post('phone_no');
        $update['website'] = $this->input->post('website');
        $update['address1'] = $this->input->post('address1');
        $update['address2'] = $this->input->post('address2');
        $update['city'] = $this->input->post('city');
        $update['state'] = $this->input->post('state');
        $update['country_id'] = $this->input->post('country_id');
        $update['postal_code'] = $this->input->post('postal_code');
        $update['status'] = $this->input->post('status');

        $branch_name = $this->input->post('branch_id');
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
                'upload_path' => "./uploads/company/",
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
        //pr($company_master);exit;
        if (count($company_master) == 0) {
            $contactdata['company_id'] = $this->common_model->insert_data(COMPANY, $update);
        }else{
            $where = array('company_id' => $company_master[0]['company_id']);
            $this->common_model->update_data(COMPANY, $update,$where);

        }

        $msg = $this->lang->line('company_update_msg');
        $this->session->set_flashdata('msg', $msg);

        redirect($this->viewname);
    }

    //view page
    public function view($id) {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{
        
        $data['company_view'] = $this->viewname;

        $redirect_link = $this->input->post('redirect_link');

        $fields = array('c.*', 'cm.*');
        $where = array("cm.company_id" => $id);
        $main_table = COMPANY_MASTER . ' as cm';

        $join_tables = array('countries c' => 'c.country_id=cm.country_id');

        $data['editRecord'] = $this->common_model->get_records($main_table, $fields, $join_tables, 'LEFT', '', '', '', '', '', '', '', '', '', $where);

        $table1 = COUNTRIES . ' as c';
        $fields1 = array("c.country_id", "c.country_name", "c.country_code");
        $data['country'] = $this->common_model->get_records($table1, $fields1);

        $table_branch_master = BRANCH_MASTER . ' as bm';
        $match_branch_master = " bm.status=1 ";
        $fields_branch_master = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table_branch_master, $fields_branch_master, '', '', $match_branch_master);
        $data['drag'] = true;
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = "bm.branch_id=(SELECT cm.branch_id from blzdsk_company_master as cm WHERE cm.company_id = " . $id . ") and bm.status=1 ";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data1'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
        $this->load->view('view', $data);
    }
}
}
