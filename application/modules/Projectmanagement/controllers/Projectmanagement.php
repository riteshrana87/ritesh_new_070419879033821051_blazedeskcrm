<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Projectmanagement extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = 'Projectmanagement';
        $this->viewname = $this->router->fetch_class();
        $this->user_info = $this->session->userdata('LOGGED_IN');  //Current Login information
    }

    /*
      @Author : Niral Patel
      @Desc   : Projectmanagement index
      @Input  :
      @Output :
      @Date   : 27/01/2016
     */

    public function index($page = '') {

        /*  $master_user_id = $this->config->item('master_user_id');
          //$master_user_id = $data['user_info']['ID'];
          $table_user = LOGIN.' as lg';
          $where_user = array("lg.login_id" => $master_user_id);
          $fields_user = array("lg.*");
          $check_user_data = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);

          $table = SETUP_MASTER.' as ct';
          //$where = array("ct.login_id" => $main_user_id);
          $where = "ct.login_id = ".$master_user_id." AND ct.email = '".$check_user_data[0]['email']."'";
          $fields = array("ct.*");
          $check_user_menu = $this->common_model->get_records_data($table,$fields,'','','','','','','','','',$where);

          //pr($check_user_menu);exit;
          if (isset($check_user_menu[0]['is_pm']) && $check_user_menu[0]['is_pm'] == 0) {
          if (isset($check_user_menu[0]['is_crm']) && $check_user_menu[0]['is_crm'] == 1) {
          $msg = $this->lang->line('DONT_HAVE_PAGE_PERMISSION');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect(base_url()); //Redirect on Dashboard
          } elseif (isset($check_user_menu[0]['is_support']) && $check_user_menu[0]['is_support'] == 1) {
          $msg = $this->lang->line('DONT_HAVE_PAGE_PERMISSION');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect(base_url('Support')); //Redirect on Dashboard
          }
          }
         */
        //Get uri segment for pagination
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $data['header'] = array('menu_module' => 'Projectmanagement');
        $searchtext = '';
        $perpage = '';
        $where = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = RECORD_PER_PAGE;
        $data['groupFieldName'] = $groupFieldName = $this->input->post('groupFieldName');
        $data['groupFieldData'] = $groupFieldData = $this->input->post('groupFieldData');
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('projectpaging_data');
        }

        $searchsort_session = $this->session->userdata('projectpaging_data');
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
                $sortfield = 'project_id';
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
        //Per page
        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = RECORD_PER_PAGE;
                $data['perpage'] = RECORD_PER_PAGE;
            }
        }
        //urisegment
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = $cur_uri_segment;
            $uri_segment = $page;
        }

        $data['project_view'] = $this->module . '/' . $this->viewname;
        $config['base_url'] = site_url($this->module . '/index');

        //sorting
        $data['sortField'] = 'project_id';
        $data['sortOrder'] = 'desc';
        if ($this->input->get('orderField') != '') {
            $data['sortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['sortOrder'] = $this->input->get('sortOrder');
        }

        //Get Records From PROJECT_MASTER Table   
        $searchFields = array();
        if (!empty($searchtext)) {
            $searchFields = array('project_code' => $searchtext,
                'project_name' => $searchtext,
                'project_desc' => $searchtext,
                'project_budget' => $searchtext,
                'l.firstname' => $searchtext,
                'l.lastname' => $searchtext);
        }
        $dbSearch = "";
        if (!empty($searchtext)) {

            $searchFields = array('prospect_name', 'project_code', 'project_name', 'project_desc', 'project_budget', 'l.firstname', 'l.lastname');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr($dbSearch, 0, -3) . ')';
        }
        if ($this->user_info['ROLE_TYPE'] == '39') {
            $where = array('pt.is_delete' => 0);
            $join_table = array(PROSPECT_MASTER . ' as p' => 'p.prospect_id=pt.client_id ',
                CONTACT_MASTER . ' as ca' => 'ca.contact_id=pt.client_id ',
                COMPANY_MASTER . ' as co' => 'co.company_id=pt.client_id',
                PROJECT_ASSIGN_MASTER . ' as te1' => 'te1.project_id=pt.project_id',
                LOGIN . ' as l' => 'te1.user_id=l.login_id  and l.is_delete = 0',
                PROSPECT_MASTER . ' as p' => 'p.prospect_id=pt.client_id');
        } else {
            $where = array('pt.is_delete' => 0, 'te.user_id' => $this->user_info['ID']);
            $join_table = array(PROSPECT_MASTER . ' as p' => 'p.prospect_id=pt.client_id ',
                CONTACT_MASTER . ' as ca' => 'ca.contact_id=pt.client_id ',
                COMPANY_MASTER . ' as co' => 'co.company_id=pt.client_id',
                PROJECT_ASSIGN_MASTER . ' as te' => 'te.project_id=pt.project_id',
                PROJECT_ASSIGN_MASTER . ' as te1' => 'te1.project_id=pt.project_id',
                LOGIN . ' as l' => 'te1.user_id=l.login_id  and l.is_delete = 0',
                PROSPECT_MASTER . ' as p' => 'p.prospect_id=pt.client_id');
        }

        $table = PROJECT_MASTER . ' as pt';
        $fields = array("CASE 
                        WHEN pt.client_type = 'contact' THEN ca.contact_name
                        WHEN pt.client_type = 'client' THEN p.prospect_name
                        WHEN pt.client_type = 'company' THEN co.company_name END AS prospect_name,pt.project_id,pt.project_code,pt.project_name,pt.start_date,pt.due_date,pt.project_icon,pt.icon_path,pt.status,group_concat(l.firstname,' ',l.lastname) as employee_name");
        $group_by = 'pt.project_id';
        $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'project_id', 'desc', $group_by, $dbSearch, '', '', '1');
        $data['project_details'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $dbSearch);
        if (empty($data['project_details'])) {
            $msg = lang('no_project_assign');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        //echo $this->db->last_query();exit;
        //pagination
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'groupFieldName' => $this->input->post('groupFieldName'),
            'groupFieldData' => $this->input->post('groupFieldData'),
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('projectpaging_data', $sortsearchpage_data);

        $where = array('is_delete' => 0, 'status' => 1, 'is_pm_user' => 1);

        $data['res_user'] = $this->common_model->get_records('login', '', '', '', $where, '');

        if ($this->input->is_ajax_request()) {
            $this->load->view('/' . $this->module . '/ProjectsAjaxList', $data);
        } else {
            $data['main_content'] = '/' . $this->module . '/Projects';
            $data['js_content'] = '/loadJsFiles';
            $this->parser->parse('layouts/ProjectTemplate', $data);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert/update data
      @Input  : Post data/Update id
      @Output : Insert/update data
      @Date   : 27/01/2016
     */

    public function insertdata() {
        if (!validateFormSecret()) {
            redirect($this->module); //Redirect On Listing page
        }
        if ($this->input->post('project_id')) {
            $id = $this->input->post('project_id');
        }
        $this->load->model('TeamMembers_model');
        $project_data['project_name'] = ucfirst($this->input->post('project_name'));
        $project_data['project_desc'] = $this->input->post('project_desc', FALSE);
        $project_data['project_budget'] = $this->input->post('project_budget');
        $project_data['status'] = 1;
        $start_date = $this->input->post('start_date');
        $due_date = $this->input->post('due_date');
        //$project_data['client_id'] = $this->input->post('client_id');
        $prospect_arr = explode("_", $this->input->post('client_id'));
        if (!empty($prospect_arr)) {
            $project_data['client_id'] = $prospect_arr[1];
            $project_data['client_type'] = $prospect_arr[0];
        }
        $project_data['start_date'] = '';
        $project_data['due_date'] = '';
        //pr($project_data);exit;
        if (!empty($start_date)) {
            $project_data['start_date'] = date("Y-m-d", strtotime($start_date));
        }
        if (!empty($due_date)) {
            $project_data['due_date'] = date("Y-m-d", strtotime($due_date));
        }

        //Insert Record in Database

        if (!empty($id)) { //update
            $project_data['modified_date'] = datetimeformat();
            $project_data['modified_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $where = array('project_id' => $id);
            $success_update = $this->common_model->update(PROJECT_MASTER, $project_data, $where);
            //update assign user
            $team_member = $this->input->post('res_user');
            if (!empty($team_member)) {
                $where1 = array('project_id' => $id);
                $assign_teammember = $this->common_model->get_records(PROJECT_ASSIGN_MASTER, array('user_id'), '', '', $where1, '');

                $old_teammember = array();
                if (!empty($assign_teammember)) {
                    foreach ($assign_teammember as $row) {
                        $old_teammember[] = $row['user_id'];
                    }
                }

                //delete task
                $delete_teammember = array_diff($old_teammember, $team_member);

                //pr($delete_teammember);exit;
                if (!empty($delete_teammember)) {
                    $this->common_model->delete_where_in(PROJECT_ASSIGN_MASTER, $where1, 'user_id', $delete_teammember);
                    $this->common_model->delete_where_in(PROJECT_TEAM_MEMBERS, $where1, 'member_id', $delete_teammember);
                    foreach ($delete_teammember as $dtm) {
                        $this->common_model->delete(PROJECT_PM_ASSIGN, 'project_id=' . $id . ' and user_id=' . $dtm);
                        $this->common_model->delete(PROJECT_TEAM_MEMBERS, 'project_id=' . $id . ' and member_id=' . $dtm);
                    }
                }

                $roleUsersPM = array();
                //insert_teammember
                //removal of  pm
                if (!empty($team_member)) {
                    foreach ($team_member as $ptl) {

                        $roleType = $this->TeamMembers_model->getRolesByuser($ptl);
                        if ($roleType[0]['role_name'] == 'Project Manager') {
                            $roleUsersPM[] = $ptl;
                            $this->common_model->delete(PROJECT_PM_ASSIGN, 'project_id=' . $id . ' and user_id=' . $ptl);
                            $this->common_model->delete(PROJECT_ASSIGN_MASTER, 'project_id=' . $id . ' and user_id=' . $ptl);
                        }
                    }
                }

                $insert_teammember = array_diff($team_member, $old_teammember);

                if (!empty($insert_teammember)) {
                    foreach ($insert_teammember as $insert_teammember) {

                        $roleType = $this->TeamMembers_model->getRolesByuser($insert_teammember);
                        if ($roleType[0]['role_name'] == 'Project Manager') {
                            //$roleUsersPM[] = $insert_teammember;
                        } else {
                            $project_assign_data['project_id'] = $id;
                            $project_assign_data['user_id'] = $insert_teammember;
                            $project_assign_data['created_date'] = datetimeformat();
                            $this->common_model->insert(PROJECT_ASSIGN_MASTER, $project_assign_data);
                            $this->common_model->insert(PROJECT_TEAM_MEMBERS, array('team_id' => 0, 'member_id' => $insert_teammember, 'project_id' => $id, 'created_date' => datetimeformat()));
                        }
                    }
                }


                if (count($roleUsersPM) > 0) {
                    $this->common_model->insert(PROJECT_PM_ASSIGN, array('project_id' => $id, 'user_id' => $roleUsersPM[0]));
                    $project_assign_data['project_id'] = $id;
                    $project_assign_data['user_id'] = $roleUsersPM[0];
                    $project_assign_data['created_date'] = datetimeformat();
                    $this->common_model->insert(PROJECT_ASSIGN_MASTER, $project_assign_data);
                }
            }
            if ($success_update) {
                $msg = $this->lang->line('project_update_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        } else { //insert
            $project_data['project_code'] = $this->input->post('project_code');
            $project_data['created_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $project_data['created_date'] = datetimeformat();

            $id = $this->common_model->insert(PROJECT_MASTER, $project_data);
            $res_user = $this->input->post('res_user');
            $q = 1;
            if (!empty($res_user)) {
                foreach ($res_user as $ruser) {
                    $roleType = $this->TeamMembers_model->getRolesByuser($ruser);
                    if (count($roleType) > 0) {
                        if ($roleType[0]['role_name'] == 'Project Manager') {
                            $roleUsersPM[] = $ruser;
                        } else {
                            $project_assign_data['project_id'] = $id;
                            $project_assign_data['user_id'] = $ruser;
                            $project_assign_data['created_date'] = datetimeformat();
                            $this->common_model->insert(PROJECT_ASSIGN_MASTER, $project_assign_data);
                            $this->common_model->insert(PROJECT_TEAM_MEMBERS, array('team_id' => 0, 'member_id' => $ruser, 'project_id' => $id, 'created_date' => datetimeformat()));
                        }
                    }
                }
            }
            if (count($roleUsersPM) > 0) {
                $this->common_model->insert(PROJECT_PM_ASSIGN, array('project_id' => $id, 'user_id' => $roleUsersPM[0]));
                $project_assign_data['project_id'] = $id;
                $project_assign_data['user_id'] = $roleUsersPM[0];
                $project_assign_data['created_date'] = datetimeformat();
                $this->common_model->insert(PROJECT_ASSIGN_MASTER, $project_assign_data);
            }
            if ($id) {
                $msg = $this->lang->line('project_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        }
        /* Upload code */
        $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $id;
        if (!is_dir($upload_dir)) {
            //create directory
            try {
                mkdir($upload_dir, 0777, TRUE);
                mkdir($upload_dir . '/' . $this->config->item('project_folder'), 0777, TRUE);
                mkdir($upload_dir . '/' . $this->config->item('project_task_folder'), 0777, TRUE);
                mkdir($upload_dir . '/' . $this->config->item('milestone_folder'), 0777, TRUE);
                mkdir($upload_dir . '/' . $this->config->item('cost_folder'), 0777, TRUE);
                mkdir($upload_dir . '/' . $this->config->item('project_incidents_folder'), 0777, TRUE);
            } catch (Exception $e) {
                //echo 'Caught exception: ',  $e->getMessage(), "\n";
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . $e->getMessage() . "</div>");
                redirect($this->module);
                die;
            }
        }
        if (is_dir($upload_dir)) {
            //Upload data
            if (!is_dir($upload_dir . '/' . $this->config->item('project_folder'))) {
                mkdir($upload_dir . '/' . $this->config->item('project_folder'), 0777, TRUE);
            }
            $file_path = $this->config->item('project_upload_path') . 'Project0' . $id . '/' . $this->config->item('project_folder') . '/';

            $upload_data = $this->upload_files('addfile', $file_path, $this->module);

            $upload_file = array();

            if (count($upload_data) > 0) {
                foreach ($upload_data as $files) {
                    $upload_file = ['project_icon' => $files['file_name'],
                        'icon_path' => $file_path];
                }
            }
            if (count($upload_file) > 0) {
                $where = array('project_id' => $id);
                //  $this->common_model->delete(COST_FILES, $where);
                $this->common_model->update(PROJECT_MASTER, $upload_file, $where);
            }
        }
        /**
         * SOFT DELETION CODE STARTS MAULIK SUTHAR
         */
        $delete_image = $this->input->post('delete_image');
        if (!empty($delete_image)) {
            unlink($delete_image);

            $where = array('project_id' => $id);
            $data['project_icon'] = '';
            $data['icon_path'] = '';
            $this->common_model->update(PROJECT_MASTER, $data, $where);
        }

        $redirectLink = $_SERVER['HTTP_REFERER'];
        if (strpos($redirectLink, 'Account/viewdata') !== false) {
            $sess_array = array('setting_current_tab' => 'Projects');
            $this->session->set_userdata($sess_array);
            redirect($redirectLink);
        } elseif (strpos($redirectLink, 'CrmCompany/view') !== false) {
            $sess_array = array('setting_current_tab' => 'Projects');
            $this->session->set_userdata($sess_array);
            redirect($redirectLink);
        } else {
            /* end soft delete */
            $display = $this->input->post('display');
            if (!empty($display)) {
                redirect($this->module . '/Projectdashboard');
            } else {
                redirect($this->module); //Redirect On Listing page
            }
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Add record
      @Input  : Add id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function add_record() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        //Get Records From PROSPECT_MASTER Table
        $data['modal_title'] = $this->lang->line('create_project');
        $data['submit_button_title'] = $this->lang->line('create_project');
        //Get assign user
        $where = array('is_delete' => 0, 'status' => 1, 'is_pm_user' => 1);
        $data['res_user'] = $this->common_model->get_records(LOGIN, '', '', '', $where, '');

        //Get client data
        $where = array('is_delete' => 0, 'status_type' => 3);
        $fields = array('prospect_name', 'prospect_id');
        $data['clients_data'] = $this->common_model->get_records(PROSPECT_MASTER, '', '', '', $where, '');
        //Get Company Information
        $table = COMPANY_MASTER . ' as com_mst';
        $match = "com_mst.status = 1 and com_mst.is_delete = 0";
        $fields = array("com_mst.company_id, com_mst.company_name");
        $data['company_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Client Information
        $table = PROSPECT_MASTER . ' as pro';
        $match = "pro.is_delete = 0 and pro.status_type = 3";
        $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

        //Get Contact Information
        $table = CONTACT_MASTER . ' as con_mst';
        $match = "con_mst.is_delete = 0 and con_mst.status = 1";
        $fields = array("con_mst.contact_id, con_mst.contact_name");
        $data['contact_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

        $data['project_view'] = $this->module;
        $data['project_code'] = 'P' . rand();
        $this->load->view('/' . $this->module . '/Add', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function edit_record($id = '') {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        $segment_array = $this->uri->segment_array();
        $redirect = end($segment_array);
        if (!empty($redirect) && $redirect == 'task_page') {
            $data['redirect'] = '1';
        } else {
            $data['redirect'] = '';
        }
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = PROJECT_MASTER;
            $match = "project_id = " . $id;

            $edit_record = $this->common_model->get_records($table, '', '', '', $match);
            //Get assign user
            $table = PROJECT_ASSIGN_MASTER;
            $match = "project_id = " . $id;
            $field = array('user_id');
            $assign_user = $this->common_model->get_records($table, $field, '', '', $match);
            $user_id = array();
            if (!empty($assign_user)) {
                foreach ($assign_user as $assign_user) {
                    $user_id[] = $assign_user['user_id'];
                }
            }
            $data['user_id'] = $user_id;
            $data['id'] = $id;
            $data['edit_record'] = $edit_record;
            $data['modal_title'] = $this->lang->line('update_project');
            $data['submit_button_title'] = $this->lang->line('update_project');
        }
        //Get client data
        $where = array('is_delete' => 0, 'status_type' => 3);
        $fields = array('prospect_name', 'prospect_id');
        $data['clients_data'] = $this->common_model->get_records(PROSPECT_MASTER, '', '', '', $where, '');
        //Get Client Information
        $table = PROSPECT_MASTER . ' as pro';
        $match = "pro.status_type = 3";
        $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Company Information
        $table = COMPANY_MASTER . ' as com_mst';
        $match = "com_mst.status = 1";
        $fields = array("com_mst.company_id, com_mst.company_name");
        $data['company_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Contact Information
        $table = CONTACT_MASTER . ' as con_mst';
        $match = "con_mst.status = 1";
        $fields = array("con_mst.contact_id, con_mst.contact_name");
        $data['contact_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

        //Get assign user
        $where = array('is_delete' => 0, 'status' => 1, 'is_pm_user' => 1);

        $data['res_user'] = $this->common_model->get_records(LOGIN, '', '', '', $where, '');
        $data['project_view'] = $this->module;
        $this->load->view('/' . $this->module . '/Add', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : View record
      @Input  : View id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function view_record($id = '') {
        //$id = $this->uri->segment('3');
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $match = "pt.project_id = " . $id;
            $table = PROJECT_MASTER . ' as pt';
            $fields = array("CASE 
                        WHEN pt.client_type = 'contact' THEN ca.contact_name
                        WHEN pt.client_type = 'client' THEN p.prospect_name
                        WHEN pt.client_type = 'company' THEN co.company_name END AS prospect_name,pt.*");
            $join_table = array(PROJECT_ASSIGN_MASTER . ' as te' => 'te.project_id=pt.project_id',
                PROSPECT_MASTER . ' as p' => 'p.prospect_id=pt.client_id ',
                CONTACT_MASTER . ' as ca' => 'ca.contact_id=pt.client_id ',
                COMPANY_MASTER . ' as co' => 'co.company_id=pt.client_id',);
            $edit_record = $this->common_model->get_records($table, $fields, $join_table, 'left', $match, '');

            //Get assign user
            $table = PROJECT_ASSIGN_MASTER . ' as pt';
            $match = "pt.project_id = " . $id;
            $field = array('pt.user_id',
                'group_concat(l.firstname," ",l.lastname) as user_name');
            $join_table = array(LOGIN . ' as l' => 'l.login_id=pt.user_id');
            $group_by = 'pt.project_id';
            $assign_user = $this->common_model->get_records($table, $field, $join_table, 'left', $match, '', '', '', '', '', $group_by);
            $edit_record[0]['user_name'] = !empty($assign_user[0]['user_name']) ? $assign_user[0]['user_name'] : '';
            $data['id'] = $id;
            $data['edit_record'] = $edit_record;
            $data['modal_title'] = !empty($edit_record[0]['project_name']) ? $edit_record[0]['project_name'] : '';
        }
        $data['project_view'] = $this->module;
        $this->load->view('/' . $this->module . '/View_project', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Update is delete
      @Input  : Edit id
      @Output : Update is delete
      @Date   : 18/01/2016
     */

    public function delete_record($id = '') {
        //$id = $this->uri->segment('3');
        if (!empty($id)) {

            //Is delete record
            $update_data['is_delete'] = 1;
            $where = array('project_id' => $id);
            $this->common_model->update(PROJECT_MASTER, $update_data, $where);
            //task
            $where = array('project_id' => $id);
            $this->common_model->update(PROJECT_TASK_MASTER, $update_data, $where);
            //milestone
            $where = array('project_id' => $id);
            $this->common_model->update(MILESTONE_MASTER, $update_data, $where);
            //project incident
            $where = array('project_id' => $id);
            $this->common_model->update(PROJECT_INCIDENTS, $update_data, $where);
            //timeshhet
            $where = array('project_id' => $id);
            $this->common_model->update(PROJECT_TIMESHEETS, $update_data, $where);
            //timeshhet
            $where = array('project_id' => $id);
            $this->common_model->update(COST_MASTER, $update_data, $where);
            //timeshhet
            $where = array('project_id' => $id);
            $this->common_model->update(PROJECT_TEAM_MASTER, $update_data, $where);
            //timeshhet
            $where = array('project_id' => $id);
            $this->common_model->update(PROJECT_TEAM_MEMBERS, $update_data, $where);
            unset($id);
            // $this->session->unset_userdata('PROJECT_ID');
            $msg = $this->lang->line('project_delete_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        $redirectLink = $_SERVER['HTTP_REFERER'];
        if (strpos($redirectLink, 'Account/viewdata') !== false) {
            $sess_array = array('setting_current_tab' => 'Projects');
            $this->session->set_userdata($sess_array);
            redirect($redirectLink);
        } elseif (strpos($redirectLink, 'CrmCompany/view') !== false) {
            $sess_array = array('setting_current_tab' => 'Projects');
            $this->session->set_userdata($sess_array);
            redirect($redirectLink);
        } else {
            redirect($this->module); //Redirect On Listing page
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : select project
      @Input  : project id
      @Output : Store in session
      @Date   : 18/01/2016
     */

    public function select_project($project_id = '') {
        //$project_id = $this->uri->segment(3);
        $this->session->set_userdata('PROJECT_ID', $project_id);
        redirect($this->module . '/ProjectTask');
    }

    /*
      @Author : Niral Patel
      @Desc   : Common Upload Function
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    public function upload_files($input, $path, $redirect, $file_name = NULL, $file_ext_tolower = FALSE, $encrypt_name = FALSE, $remove_spaces = FALSE, $detect_mime = TRUE) {

        $files = $_FILES;
        $FileDataArr = array();
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'png|gif|jpg|jpeg';
        $config['max_size'] = 10000;
        //$config['max_width'] = 1024;
        //$config['max_height'] = 768;
        $config['file_ext_tolower'] = $file_ext_tolower;
        $config['encrypt_name'] = $encrypt_name;
        $config['remove_spaces'] = $remove_spaces;
        $config['detect_mime'] = $detect_mime;
        if ($file_name != NULL) {
            $config['file_name'] = $file_name;
        }
        $tmpFile = count($_FILES[$input]['name']);

        if ($tmpFile > 0 && $_FILES[$input]['name'] != NULL) {

            $_FILES[$input]['name'] = $files[$input]['name'];
            $_FILES[$input]['type'] = $files[$input]['type'];
            $_FILES[$input]['tmp_name'] = $files[$input]['tmp_name'];
            $_FILES[$input]['error'] = $files[$input]['error'];
            $_FILES[$input]['size'] = $files[$input]['size'];

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
        return $FileDataArr;
    }

    /*
      @Author : Niral Patel
      @Desc   : Delete file
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    public function delete_file($id) {
        if (!empty($id)) {
            $match = "project_id = " . $id;
            $res = $this->common_model->get_records(PROJECT_MASTER, array('project_id',
                'project_icon',
                'icon_path'), '', '', $match);

            if (!empty($res[0]['project_icon']) && !empty($res[0]['icon_path'])) {
                unlink($res[0]['icon_path'] . '/' . $res[0]['project_icon']);
            }
            $where = array('project_id' => $id);
            $data['project_icon'] = '';
            $data['icon_path'] = '';
            if ($this->common_model->update(PROJECT_MASTER, $data, $where)) {
                echo json_encode(array('status' => 1,
                    'error' => ''));
                die;
            } else {
                echo json_encode(array('status' => 0,
                    'error' => 'Someting went wrong!'));
                die;
            }
            unset($id);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : download attachment function
      @Input    :
      @Output   :
      @Date   : 01/02/2016
     */

    function download($id) {
        if ($id > 0) {
            $match = "project_id = " . $id;
            $res = $this->common_model->get_records(PROJECT_MASTER, array('project_id',
                'project_icon',
                'icon_path'), '', '', $match);
            if (count($res) > 0) {
                $pth = file_get_contents(base_url($res[0]['icon_path'] . '/' . $res[0]['project_icon']));
                $this->load->helper('download');
                force_download($res[0]['project_icon'], $pth);
            }
            redirect($this->module);
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Mark Project as Finished Project
      @Input    :
      @Output   :
      @Date   : 22/03/2016
     */

    function finishProject() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct script allowed");
        }
        $id = $this->input->post('id');
        $status = $this->input->post('ptype');
        if ($id > 0) {
            $match = "project_id = " . $id;
            $res = $this->common_model->get_records(PROJECT_MASTER, array('project_name',
                'project_icon',
                'icon_path'), '', '', $match);
            if (count($res) > 0) {
                $projectName = $res[0]['project_name'];
            }
            if ($status == 3) {
                $stat = " Finished";
            } else {
                $stat = " Reopened";
            }
            $activity['activity'] = $stat . ' project <b>' . $projectName . '</b>  ';
            $activity["project_id"] = $id;
            $this->common_model->update(PROJECT_MASTER, array('status' => $status), array('project_id' => $id));
            $this->session->unset_userdata('PROJECT_STATUS', '');
            $this->session->set_userdata('PROJECT_STATUS', $status);
            $this->log_activity($activity);

            echo json_encode(array('status' => 1));
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert module activities
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    function log_activity($activity) {
        $activity['user_id'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
        $activity['module'] = 'Project';
        //   $activity['project_id'] = $this->project_id;
        $activity['activity_date'] = datetimeformat();
        $this->common_model->insert(PROJECT_ACTIVITIES, $activity);
    }

}
