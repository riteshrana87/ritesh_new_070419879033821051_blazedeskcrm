<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->uri->segment(1);

        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Task Model Index Page
      @Input    :
      @Output	:
      @Date     : 26/01/2016
     */

    public function index() {
        //$this->config->item('directory_root');
        $data['main_content'] = '/Task';
        //$data['js_content'] = '/LoadJsFileTask';
        $data['task_view'] = $this->viewname;
        $table5 = TASK_MASTER . ' as td';
        $match5 = "";
        $fields5 = array("td.task_id,td.task_name,td.importance,td.remember,td.task_description,td.start_date,"
            . "td.end_date");
        $data['task_data'] = $this->common_model->get_records($table5, $fields5, '', '', $match5);
        //Pass ALL TABLE Record In View
        $data['header'] = array('menu_module' => 'crm');
        $this->parser->parse('layouts/DashboardTemplate', $data);
        //$this->load->view('AddEditTask');
        //$this->load->view('LoadJsFileTask');
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Task Model add Page
      @Input 	:
      @Output	:
      @Date     : 26/01/2016
     */

    public function add($contact_id = null) {

        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            //get contact id if add task from another view page
            $data['contact_id'] = $contact_id;

            $data['task_view'] = 'SalesOverview';
            $data['main_content'] = '/Task';
            $data['modal_title'] = $this->lang->line('create_new_task');
            $data['submit_button_title'] = $this->lang->line('create_task');
            $data['remove_button_title'] = $this->lang->line('remove_task');

            $this->load->view('AddEditTask', $data);
        }
        
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Task Model inserttask Page
      @Input 	: get input post data
      @Output	: insert data in table and id success display message
      @Date     : 26/01/2016
     */

    public function inserttask() {

        if ($this->input->post('task_id')) {
            $id = $this->input->post('task_id');
        }

        if ($this->input->post('redirect')) {
            $redirectLink = $this->input->post('redirect');
        }

        //Add task from view Lead insert status=3
        if (strpos($redirectLink, 'Lead') !== false) {
            $sessArray = array('setting_current_tab' => 'Task');
            $this->session->set_userdata($sessArray);
            $taskData['task_status'] = 3;
        }

        //Add task from view Opportunity insert status=4
        if (strpos($redirectLink, 'Opportunity') !== false) {
            $sessArray = array('setting_current_tab' => 'Task');
            $this->session->set_userdata($sessArray);
            $taskData['task_status'] = 4;
        }

        //Add task from view Account insert status=2
        if (strpos($redirectLink, 'Account') || strpos($redirectLink, 'Account/viewLostClient') !== false) {
            $sessArray = array('setting_current_tab' => 'Task');
            $this->session->set_userdata($sessArray);
            $taskData['task_status'] = 2;
        }

        //Add task from view Contact insert status=1
        if (strpos($redirectLink, 'Contact') !== false) {
            $sessArray = array('setting_current_tab' => 'Task');
            $this->session->set_userdata($sessArray);
            $taskData['task_status'] = 1;
        }
        if (!validateFormSecret()) {

            redirect($redirectLink); //Redirect On Listing page
        }
        $data = array();
        $data['task_view'] = $this->viewname;

        $userId = $this->session->userdata('LOGGED_IN')['ID'];
        if ($this->input->post('hdn_contact_id')) {
            $taskData['task_related_id'] = $this->input->post('hdn_contact_id');
        }
        $taskData['task_assign'] = $userId;
        $taskData['task_name'] = trim($this->input->post('task_name'));
        $taskData['importance'] = $this->input->post('importance');
        $task_reminder = '0';
        if ($this->input->post('reminder') == 'on') {
            $task_reminder = '1';
        }
        $taskData['remember'] = $task_reminder;
        $taskData['reminder_date'] = date_format(date_create($this->input->post('reminder_date')), 'Y-m-d');
        $taskData['reminder_time'] = $this->input->post('reminder_time');
        $taskData['task_description'] = $this->input->post('task_description');
        $taskData['start_date'] = date_format(date_create($this->input->post('start_date')), 'Y-m-d');
        $taskData['end_date'] = date_format(date_create($this->input->post('end_date')), 'Y-m-d');
        $taskData['status'] = 1;

        $taskData['modified_date'] = datetimeformat();
        //Insert Record in Database
        if (isset($id) && $id != '') {
            $where = array('task_id' => $id);
            $successUpdate = $this->common_model->update(TASK_MASTER, $taskData, $where);
            if ($successUpdate) {
                $msg = $this->lang->line('task_update_msg');
                $this->session->set_flashdata('message', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        } else {
            $taskData['created_date'] = datetimeformat();
            $taskData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $success = $this->common_model->insert(TASK_MASTER, $taskData);
            if ($success) {
                $msg = $this->lang->line('task_add_msg');
                $this->session->set_flashdata('message', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        }

        redirect($redirectLink);
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Task Model edittask Page
      @Input 	: get data from id
      @Output	: view data in AddEditTask
      @Date     : 26/01/2016
     */

    public function edittask($id) {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $table = TASK_MASTER . ' as tm';
            $match = "tm.task_id = " . $id;
            $fields = array("tm.task_id,tm.task_related_id,tm.task_assign,tm.task_name,tm.importance,tm.remember,tm.reminder_date,tm.reminder_time,tm.task_description,tm.start_date,tm.end_date");
            $data['edit_record'] = $this->common_model->get_records($table, $fields, '', '', $match);

            $data['id'] = $id;
            $data['contact_id'] = $data['edit_record'][0]['task_related_id'];
            $data['task_view'] = 'Task';
            $data['modal_title'] = $this->lang->line('update_task');
            $data['submit_button_title'] = $this->lang->line('update_task');
            $data['remove_button_title'] = $this->lang->line('remove_task');
            $data['main_content'] = '/Task';
            $this->load->view('AddEditTask', $data);
        }
        
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Task Model deletetask Page
      @Input 	: get id
      @Output	: set is_delete=1 for get id
      @Date     : 26/01/2016
     */

    public function deletetask() {
        $id = $this->input->get('id');
        $redirectLink = $this->input->get('link');
        if ($id) {
            $where = array('task_id' => $id);
            //$success = $this->common_model->delete(TASK_MASTER, $where);
            $taskData['is_delete'] = 1;
            $delete_suceess = $this->common_model->update(TASK_MASTER, $taskData, $where);
            if ($delete_suceess) {

                $msg = $this->lang->line('task_del_msg');
                $this->session->set_flashdata('message', $msg);
                $link = $_SERVER['HTTP_REFERER'];
                if (strpos($link, 'Lead') !== false || strpos($link, 'Account') !== false || strpos($link, 'Contact') !== false || strpos($link, 'Opportunity') !== false || strpos($link, 'lostClient') !== false) {
                    $this->session->set_flashdata('msg', $msg);
                }
            } else {

                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        }


        redirect($redirectLink);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : View for the task
      @Input 	:
      @Output	:
      @Date   : 26/01/2016
     */

    public function viewtask($id) {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            //Get Records From TASK_MASTER Table
            $table = TASK_MASTER . ' as tm';
            $match = " tm.is_delete=0 and tm.task_id = " . $id;
            $fields = array("tm.task_id,tm.status,tm.task_name,tm.importance,tm.remember,tm.reminder_date,tm.reminder_time,tm.task_description,tm.start_date,"
                . "tm.end_date");
            $where = array("tm.is_delete" => "0", "tm.status" => "1");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            if (empty($data['editRecord'])) {
                redirect('Salesoveview');
            }
            $data['view'] = true;
            $data['id'] = $id;
            $data['task_view'] = $this->viewname;
            $data['main_content'] = '/Task';
            //Pass TABLE Record In View
            $this->load->view('ViewTask', $data);
        }

    }

    public function completeTask() {
        $id = $this->input->get('id');
        $redirectLink = $this->input->get('link');
        if ($id) {
            $where = array('task_id' => $id);
            //$success = $this->common_model->delete(TASK_MASTER, $where);
            $taskData['status'] = 2;
            $complete_suceess = $this->common_model->update(TASK_MASTER, $taskData, $where);
            if ($complete_suceess) {
                $msg = $this->lang->line('complete_task_message');
                $this->session->set_flashdata('message', $msg);
                $link = $_SERVER['HTTP_REFERER'];
                if (strpos($link, 'Lead') !== false || strpos($link, 'Account') !== false || strpos($link, 'Contact') !== false || strpos($link, 'Opportunity') !== false || strpos($link, 'lostClient') !== false) {
                    $this->session->set_flashdata('msg', $msg);
                }
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        }
        redirect($redirectLink);
    }

    public function reopen() {
        $id = $this->input->get('id');

        $redirectLink = $this->input->get('link');
        if ($id) {
            $where = array('task_id' => $id);
            //$success = $this->common_model->delete(TASK_MASTER, $where);
            $taskData['status'] = 1;
            $complete_suceess = $this->common_model->update(TASK_MASTER, $taskData, $where);
            if ($complete_suceess) {

                $msg = $this->lang->line('task_reopen_message');
                $this->session->set_flashdata('message', $msg);
                $link = $_SERVER['HTTP_REFERER'];
                if (strpos($link, 'Lead') !== false || strpos($link, 'Account') !== false || strpos($link, 'Contact') !== false || strpos($link, 'Opportunity') !== false || strpos($link, 'lostClient') !== false) {
                    $this->session->set_flashdata('msg', $msg);
                }
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        }
        redirect($redirectLink);
    }

}
