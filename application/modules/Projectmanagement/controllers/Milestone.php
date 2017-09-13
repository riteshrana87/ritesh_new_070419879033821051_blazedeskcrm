<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Milestone extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_project();
        $this->module = 'Projectmanagement';
        $this->viewname  = $this->router->fetch_class();
        $this->user_info = $this->session->userdata('LOGGED_IN');  //Current Login information
        $this->project_id = $this->session->userdata('PROJECT_ID');
    }

    /*
      @Author : Niral Patel
      @Desc   : Milestone index
      @Input  :
      @Output :
      @Date   : 27/01/2016
     */

    public function index($page='') {
     /*   $master_user_id = $this->config->item('master_user_id');
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
        }*/

        //Get uri segment for pagination
        $cur_uri= explode('/',$_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $data['header'] = array('menu_module' => 'Projectmanagement');
        $data['milestone_view'] = $this->module . '/' . $this->viewname;
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
            $this->session->unset_userdata('milestonepaging_data');
        }
        $data['status'] = array('1' => 'Paid',
            '0' => 'Unpaid');
        $searchsort_session = $this->session->userdata('milestonepaging_data');
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
                $sortfield = 'milestone_id';
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
                $config['per_page'] = RECORD_PER_PAGE;
                $data['perpage'] = RECORD_PER_PAGE;
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url($this->module . '/' . $this->viewname . '/index');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment           = 0;
        } else {
            $config['uri_segment'] = $cur_uri_segment;
            $uri_segment           = $page;
        }

        $dbSearch = "";
        if (!empty($searchtext)) {

            $searchFields = array('milestone_code',
                'milestone_name',
                'description');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr($dbSearch, 0, -3) . ')';
        }
        $where = array('is_delete' => 0, 'project_id' => $this->project_id);
        $config['total_rows'] = $this->common_model->get_records(MILESTONE_MASTER, '', '', '', $where, '', '', '', 'milestone_id', 'desc', '', $dbSearch, '', '', '1');
        //Get Records From MILESTONE_MASTER Table    
        $data['milestone_data'] = $this->common_model->get_records(MILESTONE_MASTER, '', '', '', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $dbSearch);

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
        $this->session->set_userdata('milestonepaging_data', $sortsearchpage_data);
        //Get Records From Login Table 
        $where = array('status' => 1);
        $data['res_user'] = $this->common_model->get_records(LOGIN, '', '', '', $where, '');
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('project_ajax')) {
                $this->load->view('/' . $this->viewname . '/Milestone', $data);
            } else {
                $this->load->view('/' . $this->viewname . '/MilestoneAjaxList', $data);
            }
        } else {
            $data['drag']=true;
            $data['main_content'] = '/Milestone/' . $this->viewname;
            $data['js_content'] = '/loadJsFiles';
            $this->parser->parse('layouts/ProjectmanagementTemplate', $data);
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
        //pr($_FILES);exit;
        if (!validateFormSecret()) {
            redirect($this->module . '/Milestone');//Redirect On Listing page
        }
        if ($this->input->post('milestone_id')) {
            $id = $milestone_id = $this->input->post('milestone_id');
        }
        $display = $this->input->post('display');

        $insert_data['project_id'] = $this->project_id;
        $insert_data['milestone_name'] = ucfirst($this->input->post('milestone_name'));
        $insert_data['res_user'] = $this->input->post('res_user');
        $insert_data['description'] = $this->input->post('description', FALSE);
        $insert_data['status'] = 1;
        $start_date = $this->input->post('start_date');
        $due_date = $this->input->post('due_date');
        $insert_data['start_date'] = '';
        $insert_data['due_date'] = '';
        if (!empty($start_date)) {
            $insert_data['start_date'] = date("Y-m-d", strtotime($start_date));
        }
        if (!empty($due_date)) {
            $insert_data['due_date'] = date("Y-m-d", strtotime($due_date));
        }

        //Insert Record in Database

        if (!empty($id)) { //update
            $insert_data['modified_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['modified_date'] = datetimeformat();
            $where = array('milestone_id' => $id);
            $success_update = $this->common_model->update(MILESTONE_MASTER, $insert_data, $where);
            //update assign user
            $team_member = $this->input->post('team_member');
            if (!empty($team_member)) {
                $where1 = array('milestone_id' => $id);
                $assign_teammember = $this->common_model->get_records(MILESTONE_ASSIGN_MASTER, array('user_id'), '', '', $where1, '');

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
                    $this->common_model->delete_where_in(MILESTONE_ASSIGN_MASTER, $where1, 'user_id', $delete_teammember);
                }
                //insert_teammember
                $insert_teammember = array_diff($team_member, $old_teammember);
                if (!empty($insert_teammember)) {
                    foreach ($insert_teammember as $insert_teammember) {
                        $team_assign_data['milestone_id'] = $id;
                        $team_assign_data['user_id'] = $insert_teammember;
                        $team_assign_data['created_date'] = datetimeformat();
                        $this->common_model->insert(MILESTONE_ASSIGN_MASTER, $team_assign_data);
                    }
                }
            }
            //assign task
            $task_id = $this->input->post('task_id');
            if (!empty($task_id)) {
                $where1 = array('milestone_id' => $id);
                $milestone_task = $this->common_model->get_records(MILESTONE_TASK_TRANS, array('task_id'), '', '', $where1, '');

                $old_task = array();
                if (!empty($milestone_task)) {
                    foreach ($milestone_task as $row) {
                        $old_task[] = $row['task_id'];
                    }
                }
                //delete task
                $delete_task = array_diff($old_task, $task_id);
                if (!empty($delete_task)) {
                    $this->common_model->delete_where_in(MILESTONE_TASK_TRANS, $where1, 'task_id', $delete_task);
                }
                //insert_task
                $insert_task = array_diff($task_id, $old_task);
                if (!empty($insert_task)) {
                    foreach ($insert_task as $insert_task) {
                        $task_assign_data['milestone_id'] = $id;
                        $task_assign_data['task_id'] = $insert_task;
                        $task_assign_data['created_date'] = datetimeformat();
                        $this->common_model->insert(MILESTONE_TASK_TRANS, $task_assign_data);
                    }
                }
            }

            if ($success_update) {
                $activity['activity'] = 'edited a milestone - ' . $insert_data['milestone_name'];
                $this->log_activity($activity);
                $msg = $this->lang->line('milestone_update_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        } else { //insert
            $insert_data['milestone_code'] = $this->input->post('milestone_code');
            $insert_data['created_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['created_date'] = datetimeformat();
            $id = $this->common_model->insert(MILESTONE_MASTER, $insert_data);
            $team_member = $this->input->post('team_member');
            if (!empty($team_member)) {
                foreach ($team_member as $team_member) {
                    $project_assign_data['milestone_id'] = $id;
                    $project_assign_data['user_id'] = $team_member;
                    $project_assign_data['created_date'] = datetimeformat();
                    $this->common_model->insert(MILESTONE_ASSIGN_MASTER, $project_assign_data);
                }
            }
            //Insert task
            $task_id = $this->input->post('task_id');
            if (!empty($task_id)) {
                foreach ($task_id as $task_id) {
                    $task_assign_data['milestone_id'] = $id;
                    $task_assign_data['task_id'] = $task_id;
                    $task_assign_data['created_date'] = datetimeformat();
                    $this->common_model->insert(MILESTONE_TASK_TRANS, $task_assign_data);
                }
            }
            if ($id) {
                $activity['activity'] = 'added new milestone - ' . $insert_data['milestone_name'];
                $this->log_activity($activity);
                $msg = $this->lang->line('milestone_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        }
        /* Upload code */
        $project_upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id;
        if (!is_dir($project_upload_dir)) {
            //create directory
            mkdir($project_upload_dir, 0777, TRUE);
        }
        $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('milestone_folder');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            //Upload data
            $file_name = array();
            $file_array1 = $this->input->post('file_data');
            $file_name = $_FILES['addfile']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['addfile']['name'][$key]);
                        unset($_FILES['addfile']['type'][$key]);
                        unset($_FILES['addfile']['tmp_name'][$key]);
                        unset($_FILES['addfile']['error'][$key]);
                        unset($_FILES['addfile']['size'][$key]);
                    }
                }
            }
            $_FILES['addfile'] = $arr = array_map('array_values', $_FILES['addfile']);
            $file_path = $this->config->item('project_upload_path') . 'Project0' . $this->project_id . '/' . $this->config->item('milestone_folder').'/';
            $data['file_view'] = $this->module . '/Milestone';

            $upload_data = uploadImage('addfile', $file_path, $data['file_view']);

            $ticketfiles = array();
            foreach ($upload_data as $dataname) {
                $ticketfiles[] = $dataname['file_name'];
            }
            $ticket_file_str = implode(",", $ticketfiles);

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
                    array_push($upload_data, array('file_name' => $img));
                }
            }
            $upload_file = array();
            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $add_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $upload_file[] = ['file_name' => $add_files[$i],
                            'file_path' => $gallery_path[$i],
                            'milestone_id' => $id,
                            'upload_status' => 0,
                            'created_date' => datetimeformat(),
                            'upload_status' => 1];
                    }
                }
            }
            if (count($upload_data) > 0) {
                foreach ($upload_data as $files) {
                    $upload_file[] = ['file_name' => $files['file_name'],
                        'file_path' => $file_path,
                        'milestone_id' => $id,
                        'upload_status' => 0,
                        'created_date' => datetimeformat()];
                }
            }

            if (count($upload_file) > 0) {
                $this->common_model->insert_batch(MILESTONE_FILES, $upload_file);
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
            $this->common_model->delete(MILESTONE_FILES, 'milestone_file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        //end upload
        if (!empty($display)) {
            redirect($this->module . '/ProjectTask');
        } else {
            redirect($this->module . '/Milestone');
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Add record
      @Input  : Add id
      @Output : Give record
      @Date   : 18/01/2016
     */

    public function add_record($view='') {
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        if (!empty($view)) {
            $data['home'] = '1';
        } else {
            $data['home'] = '';
        }
        $data['modal_title'] = $this->lang->line('create_milestone');
        $data['submit_button_title'] = $this->lang->line('create_milestone');

        $data['sales_view'] = $this->viewname;
        //Get user
        /* $where = array('status' => 1);
          $data['res_user']      = $this->common_model->get_records(LOGIN,'','','',$where,''); */
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('l.is_delete' => 0, 'project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');

        //Get milestone
        $where = array('sub_task_id' => 0,
            'is_delete' => 0,
            'project_id' => $this->project_id);
        //$wherestring            = '(status = 1 or status = 2)';
        $data['task_data'] = $this->common_model->get_records(PROJECT_TASK_MASTER, '', '', '', $where, '', '', '', 'task_id', 'desc', '', '', '', '', '');
        $data['milestone_code'] = 'M' . rand();
        //project detail
        $where_pro = array('project_id' => $this->project_id);
        $data['project_detail'] = $this->common_model->get_records(PROJECT_MASTER, array('project_id',
            'project_name',
            'status',
            'start_date',
            'due_date'), '', '', $where_pro, '');

        //url for filemanager
        $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");
        $data['milestone_view'] = $this->module . '/Milestone';
        $this->load->view('/ProjectTask/Add_milestone', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function edit_record($id='') {
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = MILESTONE_MASTER;
            $match = "milestone_id = " . $id;

            $edit_record = $this->common_model->get_records($table, '', '', '', $match);

            //Get assign user
            $table = MILESTONE_ASSIGN_MASTER;
            $match = "milestone_id = " . $id;
            $field = array('user_id');
            $assign_user = $this->common_model->get_records($table, $field, '', '', $match);
            $user_id = array();
            if (!empty($assign_user)) {
                foreach ($assign_user as $assign_user) {
                    $user_id[] = $assign_user['user_id'];
                }
            }
            $data['user_id'] = $user_id;
            //Get assign task
            $match = "milestone_id = " . $id;
            $field = array('task_id');
            $assign_task = $this->common_model->get_records(MILESTONE_TASK_TRANS, $field, '', '', $match);
            $task_id = array();
            if (!empty($assign_task)) {
                foreach ($assign_task as $assign_task) {
                    $task_id[] = $assign_task['task_id'];
                }
            }
            $data['task_id'] = $task_id;
            $data['id'] = $id;
            $data['edit_record'] = $edit_record;
            //Get project task file
            $match = "milestone_id = " . $id;
            $field = array('milestone_file_id,milestone_id,upload_status,file_name,file_path');
            $data['milestone_files'] = $this->common_model->get_records(MILESTONE_FILES, $field, '', '', $match);

            $data['modal_title'] = $this->lang->line('update_milestone');
            $data['submit_button_title'] = $this->lang->line('update_milestone');
        } else {
            $data['modal_title'] = $this->lang->line('create_milestone');
            $data['submit_button_title'] = $this->lang->line('create_milestone');
        }
        //Get user
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('l.is_delete' => 0, 'project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');

        //Get Task
        $where = array('sub_task_id' => 0,
            'is_delete' => 0,
            'project_id' => $this->project_id);
        $wherestring = '(status = 1 or status = 2)';
        $data['task_data'] = $this->common_model->get_records(PROJECT_TASK_MASTER, '', '', '', $where, '', '', '', 'task_id', 'desc', '', $wherestring, '', '', '');
        //project detail
        $where_pro = array('project_id' => $this->project_id);
        $data['project_detail'] = $this->common_model->get_records(PROJECT_MASTER, array('project_id',
            'project_name',
            'status',
            'start_date',
            'due_date'), '', '', $where_pro, '');
        //url for filemanager
        $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");
        $data['milestone_view'] = $this->module . '/Milestone';
        $this->load->view('/ProjectTask/Add_milestone', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function view_record($id='') {
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = MILESTONE_MASTER . ' as m';
            $fields = array('m.*,group_concat(l.firstname," ",l.lastname) as res_user');
            $join_table = array(LOGIN . ' as l' => 'm.res_user=l.login_id');
            $where = array('milestone_id' => $id);
            $edit_record = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
            //Get assign user
            $table = MILESTONE_ASSIGN_MASTER . ' as pt';
            $match = "pt.milestone_id = " . $id;
            $field = array('pt.user_id',
                'group_concat(l.firstname," ",l.lastname) as user_name');
            $join_table = array(LOGIN . ' as l' => 'l.login_id=pt.user_id');
            $group_by = 'pt.milestone_id';
            $assign_user = $this->common_model->get_records($table, $field, $join_table, 'left', $match, '', '', '', '', '', $group_by);
            $edit_record[0]['user_name'] = !empty($assign_user[0]['user_name']) ? $assign_user[0]['user_name'] : '';

            //Get assign task
            $table = MILESTONE_TASK_TRANS . ' as mt';
            $match = "mt.milestone_id = " . $id;
            $field = array('group_concat(pt.task_name) as task_name');
            $join_table = array(PROJECT_TASK_MASTER . ' as pt' => 'pt.task_id=mt.task_id');
            $group_by = 'mt.milestone_id';
            $assign_task = $this->common_model->get_records($table, $field, $join_table, 'left', $match, '', '', '', '', '', $group_by);
            $edit_record[0]['assign_task'] = !empty($assign_task[0]['task_name']) ? $assign_task[0]['task_name'] : '';

            $data['id'] = $id;
            $data['edit_record'] = $edit_record;
            //Get project task file
            $match = "milestone_id = " . $id;
            $field = array('milestone_file_id,milestone_id,upload_status,file_name,file_path');
            $data['milestone_files'] = $this->common_model->get_records(MILESTONE_FILES, $field, '', '', $match);
            //pr($data['milestone_files']);exit;
            $data['modal_title'] = !empty($data['edit_record'][0]['milestone_name']) ? $data['edit_record'][0]['milestone_name'] : '';
        }
        //url for filemanager
        $data['milestone_view'] = $this->module . '/Milestone';
        $this->load->view('/Milestone/View_milestone', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Update is delete
      @Input  : Edit id
      @Output : Update is delete
      @Date   : 18/01/2016
     */

    public function delete_record($id='') {
        if (!empty($id)) {

            //Is delete record
            $update_data['is_delete'] = 1;
            $where = array('milestone_id' => $id);
            $this->common_model->update(MILESTONE_MASTER, $update_data, $where);
            //task delete
            $update_data['is_delete'] = 1;
            $where = array('milestone_id' => $id);
            $this->common_model->update(PROJECT_TASK_MASTER, $update_data, $where);
            $match = "milestone_id = " . $id;
            $tasks = $this->common_model->get_records(PROJECT_TASK_MASTER, array('task_id'), '', '', $match);

            foreach ($tasks as $tasks) {
                $update_data['is_delete'] = 1;
                $where = array('sub_task_id' => $tasks['task_id']);
                $this->common_model->update(PROJECT_TASK_MASTER, $update_data, $where);
            }

            unset($id);
            $msg = $this->lang->line('milestone_delete_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        redirect($this->module . '/Milestone'); //Redirect On Listing page
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
            $match = "milestone_file_id = " . $id;
            $res = $this->common_model->get_records(MILESTONE_FILES, array('milestone_file_id', 'file_name', 'file_path', 'upload_status'), '', '', $match);

            $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('milestone_folder') . '/';
            if (empty($res[0]['upload_status']) && !empty($res[0]['file_name']) && !empty($res[0]['file_path'])) {
                if (file_exists($upload_dir . $res[0]['file_name'])) {
                    unlink($res[0]['file_path'] . '/' . $res[0]['file_name']);
                }
            }
            $where = array('milestone_file_id' => $id);
            if ($this->common_model->delete(MILESTONE_FILES, $where)) {
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
            $params['fields'] = ['*'];
            $params['table'] = MILESTONE_FILES . ' as MF';
            $params['match_and'] = 'MF.milestone_file_id=' . $id . '';
            $task_files = $this->common_model->get_records_array($params);
            if (count($task_files) > 0) {
                $pth = file_get_contents(base_url($task_files[0]['file_path'] . '/' . $task_files[0]['file_name']));
                $this->load->helper('download');
                force_download($task_files[0]['file_name'], $pth);
            }
            redirect($this->module . '/ProjectTask');
        }
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
        $config['allowed_types'] = 'gif|jpg|png|pdf';
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
      @Author : Niral Patel
      @Desc   : Insert module activities
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    function log_activity($activity) {
        $activity['user_id'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
        $activity['module'] = 'milestone';
        $activity['project_id'] = $this->project_id;
        $activity['activity_date'] = datetimeformat();
        $this->common_model->insert(PROJECT_ACTIVITIES, $activity);
    }

    /*
      @Author : Niral Patel
      @Desc   : ajax upload file
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    public function file_upload($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        //project folder check
        $project_upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id;
        if (!is_dir($project_upload_dir)) {
            //create directory
            mkdir($project_upload_dir, 0777, TRUE);
        }

        $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('milestone_folder');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            file_put_contents($upload_dir . '/' . $filename, $str);
        }
    }

}
