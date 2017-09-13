 <?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SupportTask extends CI_Controller {

    function __construct() {
        parent::__construct();
       $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
  
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : ishani dave
      @Date   : 03/05/2016
     */

    public function index() {
        
        $data['main_content'] = '/SupportTask';
        $data['task_view'] = $this->viewname;
        $table5 = SUPPORT_TASK_MASTER . ' as td';
        $match5 = "is_delete = 0 AND status = 1";
        $fields5 = array("td.task_id,td.task_name,td.importance,td.remember,td.task_description,td.start_date,"
            . "td.end_date");
        $data['task_data'] = $this->common_model->get_records($table5, $fields5, '', '', $match5);
        //Pass ALL TABLE Record In View
        $data['header'] = array('menu_module' => $this->module);
        $this->parser->parse('layouts/DashboardTemplate', $data);
       
    }
    
    
    public function add($contact_id = null) {
        $data['contact_id'] = $contact_id;
        $data['main_content'] = '/SupportTask';
        $data['modal_title'] = $this->lang->line('create_new_task');
        $data['submit_button_title'] = $this->lang->line('create_task');
        $data['remove_button_title'] = $this->lang->line('remove_task');

        $this->load->view('AddEditTask', $data);
    }
    
   public function inserttask() {
        if ($this->input->post('task_id')) {
            $id = $this->input->post('task_id');
        }

        if ($this->input->post('redirect')) {
            $redirect_link = $this->input->post('redirect');
        }

        if (strpos($redirect_link, 'Lead') !== false) {
            $sess_array = array('setting_current_tab' => 'Task');
            $this->session->set_userdata($sess_array);
            $task_data['task_status'] = 3;
        }
        if (strpos($redirect_link, 'Opportunity') !== false) {
            $sess_array = array('setting_current_tab' => 'Task');
            $this->session->set_userdata($sess_array);
            $task_data['task_status'] = 4;
        }
        if (strpos($redirect_link, 'Account') !== false) {
            $sess_array = array('setting_current_tab' => 'Task');
            $this->session->set_userdata($sess_array);
            $task_data['task_status'] = 2;
        }
        if (strpos($redirect_link, 'Contact') !== false) {
            $sess_array = array('setting_current_tab' => 'Task');
            $this->session->set_userdata($sess_array);
            $task_data['task_status'] = 1;
        }
        if (!validateFormSecret()) {
//            if ($id) {
//                $msg = $this->lang->line('task_update_msg');
//                $this->session->set_flashdata('message', $msg);
//            }
//            else{
//                $msg = $this->lang->line('task_add_msg');
//                $this->session->set_flashdata('message', $msg);
//            }
            redirect($redirect_link); //Redirect On Listing page
        }
        $data = array();
        $data['task_view'] = $this->viewname;

        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        if ($this->input->post('hdn_contact_id')) {
            $task_data['task_related_id'] = $this->input->post('hdn_contact_id');
        }
        $task_data['task_assign'] = $user_id;
        $task_data['task_name'] = trim($this->input->post('task_name'));
        $task_data['importance'] = $this->input->post('importance');
        $task_reminder = '0';
        if ($this->input->post('reminder') == 'on') {
            $task_reminder = '1';
        }
        $task_data['remember'] = $task_reminder;
        $task_data['reminder_date'] = date_format(date_create($this->input->post('reminder_date')), 'Y-m-d');
        $task_data['reminder_time'] = $this->input->post('reminder_time');

        $task_data['task_description'] = $this->input->post('task_description');
        $task_data['start_date'] = date_format(date_create($this->input->post('start_date')), 'Y-m-d');
        $task_data['end_date'] = date_format(date_create($this->input->post('end_date')), 'Y-m-d');
        $task_data['status'] = 1;

        $task_data['modified_date'] = datetimeformat();
        //Insert Record in Database
        if (isset($id) && $id != '') {
            $where = array('task_id' => $id);
            $success_update = $this->common_model->update(SUPPORT_TASK_MASTER, $task_data, $where);
            if ($success_update) {
                $msg = $this->lang->line('task_update_msg');
                $this->session->set_flashdata('message', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        } else {
            $task_data['created_date'] = datetimeformat();
            $task_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $success = $this->common_model->insert(SUPPORT_TASK_MASTER, $task_data);
            if ($success) {
                $msg = $this->lang->line('task_add_msg');
                $this->session->set_flashdata('message', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        }

        redirect($redirect_link);
    }
    public function edittask($id) {
      
        $table = SUPPORT_TASK_MASTER . ' as tm';
        $match = "tm.task_id = " . $id;
        $fields = array("tm.task_id,tm.task_related_id,tm.task_assign,tm.task_name,tm.importance,tm.remember,tm.reminder_date,tm.reminder_time,tm.task_description,tm.start_date,tm.end_date");
        $data['edit_record'] = $this->common_model->get_records($table, $fields, '', '', $match);

        $data['id'] = $id;
        $data['contact_id'] = $data['edit_record'][0]['task_related_id'];
        $data['task_view'] = 'SupportTask';
        $data['modal_title'] = $this->lang->line('update_task');
        $data['submit_button_title'] = $this->lang->line('update_task');
        $data['remove_button_title'] = $this->lang->line('remove_task');
        $data['main_content'] = '/SupportTask';
        $this->load->view('AddEditTask', $data);
    }
    
    public function deletetask() {
        $id = $this->input->get('id');
       
        $redirect_link = $this->input->get('link');
        if ($id) {
            $where = array('task_id' => $id);
     
            $task_data['is_delete'] = 1;
            $delete_suceess = $this->common_model->update(SUPPORT_TASK_MASTER, $task_data, $where);
           
            if ($delete_suceess) {

                $msg = $this->lang->line('task_del_msg');
                $this->session->set_flashdata('message', $msg);
                $link = $_SERVER['HTTP_REFERER'];
            if (strpos($link, 'Lead') !== false || strpos($link, 'Account') !== false || strpos($link, 'Contact') !== false || strpos($link, 'Opportunity') !== false || strpos($link, 'lostClient') !== false) {
                $this->session->set_flashdata('msg', $msg);
            }
            }else {

                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        }


        redirect($redirect_link);
    }
    
    public function viewtask($id) {
//Get Records From TASK_MASTER Table
        $table = SUPPORT_TASK_MASTER . ' as tm';
        $match = " tm.is_delete=0 and tm.task_id = " . $id;
       $fields = array("tm.task_id,tm.status,tm.task_name,tm.importance,tm.remember,tm.reminder_date,tm.reminder_time,tm.task_description,tm.start_date,"
            . "tm.end_date");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['view'] = true;
        $data['id'] = $id;
        $data['task_view'] = $this->viewname;
        $data['main_content'] = '/SupportTask';
        //Pass TABLE Record In View
        $this->load->view('ViewTask', $data);
    }
    
     public function completeTask(){
        $id = $this->input->get('id');
        $redirectLink = $this->input->get('link');
        if ($id) {
            $where = array('task_id' => $id);
            //$success = $this->common_model->delete(TASK_MASTER, $where);
            $taskData['status'] = 2;
            $complete_suceess = $this->common_model->update(SUPPORT_TASK_MASTER, $taskData, $where);
            if ($complete_suceess) {
                $msg = $this->lang->line('complete_task_message');
                $this->session->set_flashdata('message', $msg);
                $link = $_SERVER['HTTP_REFERER'];
            if (strpos($link, 'Lead') !== false || strpos($link, 'Account') !== false || strpos($link, 'Contact') !== false || strpos($link, 'Opportunity') !== false || strpos($link, 'lostClient') !== false) {
                $this->session->set_flashdata('msg', $msg);
            }
            }else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        }
        redirect($redirectLink);
    }
    
         public function reopen(){
        $id = $this->input->get('id');
   
        $redirectLink = $this->input->get('link');
        if ($id) {
            $where = array('task_id' => $id);
            //$success = $this->common_model->delete(TASK_MASTER, $where);
            $taskData['status'] = 1;
            $complete_suceess = $this->common_model->update(SUPPORT_TASK_MASTER, $taskData, $where);
            if ($complete_suceess) {

                $msg = $this->lang->line('task_reopen_message');
                $this->session->set_flashdata('message', $msg);
                $link = $_SERVER['HTTP_REFERER'];
            if (strpos($link, 'Lead') !== false || strpos($link, 'Account') !== false || strpos($link, 'Contact') !== false || strpos($link, 'Opportunity') !== false || strpos($link, 'lostClient') !== false) {
                $this->session->set_flashdata('msg', $msg);
            }
            }else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error_task', $msg);
            }
        }
        redirect($redirectLink);
    }

}
  