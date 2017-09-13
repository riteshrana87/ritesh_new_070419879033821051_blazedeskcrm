<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->module = 'Projectmanagement';
        $this->viewname  = $this->router->fetch_class();
        $this->user_info = $this->session->userdata('LOGGED_IN');  //Current Login information
        $this->project_id = $this->session->userdata('PROJECT_ID');
    }

    /*
      @Author : Niral Patel
      @Desc   : Messages index
      @Input  :
      @Output :
      @Date   : 27/01/2016
     */

    public function index($page='') {
        /*$master_user_id = $this->config->item('master_user_id');
        //$master_user_id = $data['user_info']['ID'];

        $table = SETUP_MASTER . ' as ct';
        $where_setup_data = array("ct.login_id" => $master_user_id);
        $fields = array("ct.*");
        $check_user_menu = $this->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where_setup_data);
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
        $cur_uri= explode('/',$_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        check_project();
        $data['header'] = array('menu_module' => 'Projectmanagement');
        $data['main_content'] = '/' . $this->viewname . '/Messages';
        $data['messages_view'] = $this->module . '/' . $this->viewname;
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
            $this->session->unset_userdata('messagepaging_data');
        }
        $searchsort_session = $this->session->userdata('messagepaging_data');
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
                $sortfield = 'message_id';
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
            $searchFields = array('l.firstname',
                'l.lastname',
                'message');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr($dbSearch, 0, -3) . ')';
        }
        $table = PROJECT_MESSAGE . ' as pa';
        $fields = array('pa.*,l.login_id,concat(l.firstname," ",l.lastname) as username');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('project_id' => $this->project_id);
        $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', $sortfield, $sortby, '', $dbSearch, '', '', '1');
        $data['message_data'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $dbSearch);
        //pr($data['message_data']);exit;
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
        $this->session->set_userdata('messagepaging_data', $sortsearchpage_data);
        //Get Records From Login Table 
        $where = array('status' => 1);
        $data['res_user'] = $this->common_model->get_records(LOGIN, '', '', '', $where, '');
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('project_ajax')) {
                $this->load->view('/' . $this->viewname . '/Messages', $data);
            } else {
                $this->load->view('/' . $this->viewname . '/MessagesAjaxList', $data);
            }
        } else {
            $data['main_content'] = '/Messages/' . $this->viewname;
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

    public function get_message() {
        if ($this->project_id == '') {
            echo json_encode(array());
            die;
        }
        $time = $_REQUEST["time"];
        $table = PROJECT_MESSAGE . ' as pa';
        $fields = array('pa.message,pa.time,l.login_id,concat(l.firstname," ",l.lastname) as username');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('project_id' => $this->project_id,
            'time >' => $time);
        $timesheet_data = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', 10, '', 'time', 'DESC', '');
        //echo $this->db->last_query();exit;
        $message_data = array();
        $cls = '';
        foreach ($timesheet_data as $row) {
            if ($this->user_info['ID'] == $row['login_id']) {
                $row['username'] = 'me';
                $cls = 'bd-chat-box-right';
            } else {
                $cls = 'bd-chat-box-left';
            }
            $results[] = array('<li class="' . $cls . '"><b>' . ucfirst($row['username']) . ' </b> ' . $row['message'] . '</li>',
                $row['time']);
        }
        if (!empty($results)) {
            $message_data = array_reverse($results);
        }

        echo json_encode($message_data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert/update data
      @Input  : Post data/Update id
      @Output : Insert/update data
      @Date   : 27/01/2016
     */

    public function insert_message() {
        check_project();
        $insert_data['user_id'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
        $insert_data['message'] = !empty($_REQUEST["message"]) ? $_REQUEST["message"] : '';
        $insert_data['project_id'] = $this->project_id;
        $insert_data['time'] = time();
        $insert_data['created_date'] = datetimeformat();
        $this->common_model->insert(PROJECT_MESSAGE, $insert_data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Delete recoed
      @Input  : Delete id
      @Output : Delete record
      @Date   : 27/01/2016
     */

    public function delete_record($id='') {
        check_project();
        //Delete Record From Database
        if (!empty($id)) {

            $where = array('message_id' => $id);
            $this->common_model->delete(PROJECT_MESSAGE, $where);
            unset($id);
            $msg = $this->lang->line('messages_delete_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        redirect($this->module . '/Messages'); //Redirect On Listing page
    }

}
