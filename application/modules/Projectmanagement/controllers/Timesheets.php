<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Timesheets extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_project();

        $this->module = 'Projectmanagement';
        $this->viewname = $this->router->fetch_class();
        $this->user_info = $this->session->userdata('LOGGED_IN');  //Current Login information
        $this->project_id = $this->session->userdata('PROJECT_ID');
        date_default_timezone_set($this->session->userdata('LOGGED_IN')['TIMEZONE']);
    }

    /*
      @Author : Niral Patel
      @Desc   : Timesheets index
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
          if(!empty($check_user_data))
          {
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
          } */
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
            $this->session->unset_userdata('timesheetpaging_data');
        }
        $searchsort_session = $this->session->userdata('timesheetpaging_data');
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
                $sortfield = 'timesheet_id';
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
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = $cur_uri_segment;
            $uri_segment = $page;
        }

        $data['timiesheet_view'] = $this->module . '/' . $this->viewname;

        //Get Records From PROJECT_MASTER Table  
        $dbSearch = "";
        if (!empty($searchtext)) {
            $searchFields = array('pa.description',
                'pt.task_name',
                'pa.estimate_time',
                'pa.spent_time');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr($dbSearch, 0, -3) . ')';
        }
        $table = PROJECT_TIMESHEETS . ' as pa';
        $fields = array('pa.*,concat(l.firstname," ",l.lastname) as username,pt.task_name,pa.created_date');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id',
            PROJECT_TASK_MASTER . ' as pt' => 'pa.task_id=pt.task_id');
        $where = array('pa.is_delete' => 0, 'pt.is_delete' => 0, 'l.is_delete' => 0, 'pa.project_id' => $this->project_id);
        $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'timesheet_id', 'desc', '', $dbSearch, '', '', '1');
        //Get Records From MILESTONE_MASTER Table    
        $data['timesheet_data'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $dbSearch);

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
        $this->session->set_userdata('timesheetpaging_data', $sortsearchpage_data);

        if ($this->input->is_ajax_request()) {

            if ($this->input->post('project_ajax')) {
                $this->load->view('/' . $this->viewname . '/Timesheets', $data);
            } else {
                $this->load->view('/' . $this->viewname . '/TimesheetsAjaxList', $data);
            }
        } else {
            $data['main_content'] = '/Timesheets/' . $this->viewname;
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
        if (!validateFormSecret()) {
            redirect($this->module . '/Timesheets'); //Redirect On Listing page
        }
        if ($this->input->post('timesheet_id')) {
            $id = $this->input->post('timesheet_id');
        }
        $display = $this->input->post('display');
        $curr_date = $this->input->post('curr_date');
        //$curr_date = date("Y-m-d H:i:s",strtotime("-1 minutes",strtotime($curr_datetime)));
        //pr($_POST);exit;
        $insert_data['project_id'] = $this->project_id;
        $insert_data['task_id'] = $this->input->post('task_id');
        $insert_data['estimate_time'] = ucfirst($this->input->post('estimate_time'));
        $insert_data['spent_time'] = $this->input->post('spent_time');
        $insert_data['description'] = $this->input->post('description', FALSE);
        $insert_data['user_id'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
        //Insert Record in Database

        if (!empty($id)) { //update
            $table = PROJECT_TIMESHEETS;
            $match = "timesheet_id = " . $id;
            $edit_record = $this->common_model->get_records($table, '', '', '', $match);
            $insert_data['timer_start_flag'] = (($this->input->post('timer_start_flag'))) ? $this->input->post('timer_start_flag') : $this->input->post('timer_start_flag_old');
            $insert_data['timer_end_flag'] = (($this->input->post('timer_end_flag'))) ? $this->input->post('timer_end_flag') : $this->input->post('timer_end_flag_old');
            $paush_timer = $this->input->post('paush_timer');
            $resume_timer = $this->input->post('resume_timer');
            $hours_diff = $this->input->post('hours_diff');

            if ($insert_data['timer_start_flag'] == 1 && $this->input->post('timer_start_flag_old') != 1) {
                $insert_data['timer_start_timestamp'] = strtotime($curr_date);
            }
            //Pause timer
            if (!empty($paush_timer)) {

                $insert_data['timer_pause_flag'] = 1;
                $insert_data['timer_pause_timestamp'] = strtotime($curr_date);
                /* date("Y-m-d H:i:s", $paush_timer);
                  pr($insert_data);exit; */
            }
            //Resume timer
            if (!empty($resume_timer)) {
                if (!empty($hours_diff)) {
                    $hours_diff = strtotime($curr_date);
                    $pause_total_time = $edit_record[0]['timer_pause_timestamp'];
                    $start_date = date("Y-m-d H:i:s", $edit_record[0]['timer_pause_timestamp']);
                    $paush_time = date("Y-m-d H:i:s", $hours_diff);
                    $datetime1 = new DateTime($start_date);
                    $datetime2 = new DateTime($paush_time);
                    $interval = $datetime1->diff($datetime2);
                    $start_timepush1 = $interval->format('%H:%i');
                    if (!empty($edit_record[0]['pause_time'])) {
                        $st = explode(':', $edit_record[0]['pause_time']);
                        $start_timepush = date('H:i', strtotime('+' . $st[0] . ' hour +' . $st[1] . ' minutes', strtotime($start_timepush1)));
                        $insert_data['pause_time'] = $start_timepush;
                    } else {
                        $insert_data['pause_time'] = $start_timepush1;
                    }

                    //pr($insert_data);
                }
                $insert_data['timer_restart_timestamp'] = strtotime($curr_date);
                $insert_data['timer_pause_flag'] = 0;
                $insert_data['timer_pause_timestamp'] = '';
            }
            if ($insert_data['timer_end_flag'] == 1 && $this->input->post('timer_end_flag_old') != 1) {
                $insert_data['timer_end_timestamp'] = strtotime($curr_date);
            }
            if ($insert_data['timer_end_flag'] == 1 && $insert_data['timer_start_flag'] == 1) {


                $data['id'] = $id;
                $data['edit_record'] = $edit_record;

                $diff = strtotime($curr_date) - $edit_record[0]['timer_start_timestamp'];
                $t = gmdate("H.i", $diff + ( $this->input->post('total_spent') * 3600));
                //$insert_data['spent_time'] = $t;
                $insert_data['spent_time'] = $this->input->post('total_spent');

                $insert_data['timer_start_flag'] = '';
                $insert_data['timer_start_timestamp'] = '';
                $insert_data['timer_end_flag'] = '';
                $insert_data['timer_end_timestamp'] = '';
                $insert_data['timer_pause_timestamp'] = '';
                $insert_data['timer_pause_flag'] = '';
                $insert_data['timer_restart_timestamp'] = '';
                $insert_data['pause_time'] = '';
                //pr($insert_data);exit;
            }
            $insert_data['modified_date'] = datetimeformat();
            $where = array('timesheet_id' => $id);

            $success_update = $this->common_model->update(PROJECT_TIMESHEETS, $insert_data, $where);
            $msg = $this->lang->line('timesheet_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else { //insert
            $insert_data['timer_start_flag'] = ($this->input->post('timer_start_flag')) ? $this->input->post('timer_start_flag') : 0;
            $insert_data['timer_end_flag'] = ($this->input->post('timer_end_flag')) ? $this->input->post('timer_end_flag') : 0;
            if ($insert_data['timer_start_flag'] == 1) {
                $insert_data['timer_start_timestamp'] = strtotime($curr_date);
            }
            if ($insert_data['timer_end_flag'] == 1) {
                $insert_data['timer_end_timestamp'] = strtotime($curr_date);
            }
            $insert_data['created_date'] = datetimeformat();
            $id = $this->common_model->insert(PROJECT_TIMESHEETS, $insert_data);
            $msg = $this->lang->line('timesheet_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }

        redirect($this->module . '/Timesheets');
    }

    /*
      @Author : Niral Patel
      @Desc   : Add record
      @Input  : Add id
      @Output : Give record
      @Date   : 18/01/2016
     */

    public function add_record($view = '') {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        if (!empty($view)) {
            $data['home'] = '1';
        } else {
            $data['home'] = '';
        }
        $data['modal_title'] = $this->lang->line('create_timesheet');
        $data['submit_button_title'] = $this->lang->line('create_timesheet');

        //Get milestone
        $where = array('is_delete' => 0, 'project_id' => $this->project_id);
        $wherestring = '';
        $data['task_data'] = $this->common_model->get_records(PROJECT_TASK_MASTER, '', '', '', $where, '', '', '', 'task_id', 'desc', '', $wherestring, '', '', '');

        //url for filemanager
        $data['timiesheet_view'] = $this->module . '/Timesheets';
        $this->load->view('/Timesheets/Add_timesheets', $data);
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

        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = PROJECT_TIMESHEETS;
            $match = "timesheet_id = " . $id;

            $edit_record = $this->common_model->get_records($table, '', '', '', $match);

            $data['id'] = $id;
            $data['edit_record'] = $edit_record;

            if (!empty($edit_record[0]['pause_time'])) {
                $start_date = date("Y-m-d H:i:s", $edit_record[0]['timer_start_timestamp']);
                $st = explode(':', $edit_record[0]['pause_time']);
                $start_timepush = date('Y-m-d H:i:s', strtotime('+' . $st[0] . ' hour +' . $st[1] . ' minutes', strtotime($start_date)));
                $data['timer_updatestart_timestamp'] = strtotime($start_timepush);

                $pause_date = date("Y-m-d H:i:s", $edit_record[0]['timer_pause_timestamp']);
                $pause_timepush = date('Y-m-d H:i:s', strtotime('+' . $st[0] . ' hour +' . $st[1] . ' minutes', strtotime($pause_date)));
                $data['timer_updatepause_timestamp'] = strtotime($pause_timepush);
            } else {
                $data['timer_updatestart_timestamp'] = $edit_record[0]['timer_start_timestamp'];
                $data['timer_updatepause_timestamp'] = $edit_record[0]['timer_pause_timestamp'];
            }
            $data['estimate_time_data'] = $this->common_model->get_records($table, '', '', '', 'task_id=' . $edit_record[0]['task_id'], '', '', '', 'timesheet_id', 'asc');

            $data['modal_title'] = $this->lang->line('update_timesheet');
            $data['submit_button_title'] = $this->lang->line('update_timesheet');
        }
        //Get milestone
        $where = array('is_delete' => 0, 'project_id' => $this->project_id);
        $wherestring = '';
        $data['task_data'] = $this->common_model->get_records(PROJECT_TASK_MASTER, '', '', '', $where, '', '', '', 'task_id', 'desc', '', $wherestring, '', '', '');

        $data['timiesheet_view'] = $this->module . '/Timesheets';
        $this->load->view('/Timesheets/Add_timesheets', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function view_record($id = '') {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = PROJECT_TIMESHEETS;
            $match = "timesheet_id = " . $id;

            $table = PROJECT_TIMESHEETS . ' as pa';
            $fields = array('pa.*,pt.task_name,concat(l.firstname," ",l.lastname) as username');
            $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id',
                PROJECT_TASK_MASTER . ' as pt' => 'pa.task_id=pt.task_id');
            $where = array('pt.is_delete' => 0, 'pa.project_id' => $this->project_id);
            $edit_record = $this->common_model->get_records($table, $fields, $join_table, 'left', $match);

            if (!empty($edit_record[0]['pause_time'])) {
                $start_date = date("Y-m-d H:i:s", $edit_record[0]['timer_start_timestamp']);
                $st = explode(':', $edit_record[0]['pause_time']);
                $start_timepush = date('Y-m-d H:i:s', strtotime('+' . $st[0] . ' hour +' . $st[1] . ' minutes', strtotime($start_date)));
                $data['timer_updatestart_timestamp'] = strtotime($start_timepush);

                $pause_date = date("Y-m-d H:i:s", $edit_record[0]['timer_pause_timestamp']);
                $pause_timepush = date('Y-m-d H:i:s', strtotime('+' . $st[0] . ' hour +' . $st[1] . ' minutes', strtotime($pause_date)));
                $data['timer_updatepause_timestamp'] = strtotime($pause_timepush);
            } else {
                $data['timer_updatestart_timestamp'] = $edit_record[0]['timer_start_timestamp'];
                $data['timer_updatepause_timestamp'] = $edit_record[0]['timer_pause_timestamp'];
            }
            $data['id'] = $id;
            $data['edit_record'] = $edit_record;
            $data['modal_title'] = $this->lang->line('view_timesheet');
        }
        $data['timiesheet_view'] = $this->module . '/Timesheets';
        $this->load->view('/Timesheets/View_timesheets', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Update is delete
      @Input  : Edit id
      @Output : Update is delete
      @Date   : 18/01/2016
     */

    public function delete_record($id = '') {
        if (!empty($id)) {

            //Is delete record
            $update_data['is_delete'] = 1;
            $where = array('timesheet_id' => $id);
            $this->common_model->update(PROJECT_TIMESHEETS, $update_data, $where);

            unset($id);
            $msg = $this->lang->line('timesheet_delete_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        redirect($this->module . '/Timesheets'); //Redirect On Listing page
    }

    public function getEstimateHrs() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        } else {
            $taskId = $this->input->post('task_id');
            $data = $this->common_model->get_records(PROJECT_TIMESHEETS, '', '', '', "task_id=" . $taskId . " and is_delete=0", '', '', '', 'timesheet_id', 'asc');
//            echo $this->db->last_query();
            $spent_sum = 0;
            if (count($data) > 0) {
                foreach ($data as $el):
                    $spent_sum = $spent_sum + $el['spent_time'];
                endforeach;
                echo json_encode(array('estimate_time' => $data[0]['estimate_time'], 'spent_time' => $spent_sum));
            } else {
                echo json_encode(array('estimate_time' => 0));
            }
        }
    }

}
