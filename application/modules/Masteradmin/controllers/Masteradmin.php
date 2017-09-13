<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Masteradmin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->uri->segment(1);
        $this->load->helper(array('form'));
        //This method will have the credentials validation
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index() {
        $this->login();
    }

    public function removed_session() {
        $session = $this->input->post('session_id');
        $where = array('id' => $session);
        $this->common_model->delete(CI_SESSION, $where);
        //pr($this->db->last_query());exit;
    }

    public function login() {
        $data['error'] = $this->session->userdata('ERRORMSG');   //Pass Error message
        $data['main_content'] = '/login';      //Pass Content
        $data['session_id'] = session_id();
        $this->parser->parse('layouts/LoginTemplate', $data);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Verify login information
      Input  : Post User Email and password for verify
      Output : If login then redirect on Home page and if not then redirect on login page
      Date   : 18/01/2016
     */

    public function verifylogin() {
        $this->form_validation->set_error_delimiters(ERROR_START_DIV, ERROR_END_DIV);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');

        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            //$error_array = validation_errors();
            $error_msg = ERROR_START_DIV_NEW . lang('ERROR_MSG_LOGIN') . ERROR_END_DIV;
            $this->session->set_userdata('ERRORMSG', $error_msg);
            redirect($this->viewname);
        } else {
            //Login sucessfully done so now redirect on Dashboard page
            //$login_info = $this->session->userdata('LOGGED_IN');
            $data['user_info'] = $this->session->userdata('LOGGED_IN');  //Current Login information

            $master_user_id = $this->config->item('master_user_id');
            //$master_user_id = $data['user_info']['ID'];

            $table = SETUP_MASTER . ' as ct';
            $where_setup_data = array("ct.login_id" => $master_user_id);
            $fields = array("ct.*");
            $check_user_menu = $this->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where_setup_data);
            //pr($check_user_menu);exit;

            if (count($check_user_menu) != 0) {
                if (isset($check_user_menu[0]['is_crm']) && $check_user_menu[0]['is_crm'] == 1) {
                    redirect(base_url()); //Redirect on Dashboard
                } elseif (isset($check_user_menu[0]['is_pm']) && $check_user_menu[0]['is_pm'] == 1) {
                    redirect(base_url('Projectmanagement/Projectdashboard')); //Redirect on Dashboard
                } elseif (isset($check_user_menu[0]['is_support']) && $check_user_menu[0]['is_support'] == 1) {
                    redirect(base_url('Support')); //Redirect on Dashboard
                }
            } else {
                /* $this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>" . lang('ERROR_MSG_LOGIN') . "</div>");
                 */
                redirect(base_url('Dashboard/logout')); //Redirect on Dashboard
            }
        }
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : This function is Call back function
      Input  : $password
      Output : Return false and true
      Date   : 18/01/2016
     */

    function check_database() {



        $browser = $_SERVER['HTTP_USER_AGENT'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $session_id = session_id();
        //PROFILE_PHOTO added by sanket on 29_02_2016
        
        $email = quotes_to_entities($this->input->post('email'));
        $password = quotes_to_entities($this->input->post('password'));
        //timezone added by maulik suthar 15-03-16
        $timezone = $this->input->post('timezone');
        //Compare Email and password from database
        $match = "email = '" . $email . "' && password = '" . md5($password) . "' && status = 1 && is_delete = 0";

        $result = $this->common_model->get_records(LOGIN, array("login_id, firstname, lastname, email, user_type,dashboard_widgets,profile_photo,dashboard_pm_widgets,blazedesk_pm_taskdashboardWidgets,taskdashboardinnerWidgets,dashboard_support_widgets,salesoverview_dashboard_widgets"), '', '', $match);

        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'ID' => $row['login_id'],
                    'EMAIL' => $row['email'],
                    'FIRSTNAME' => $row['firstname'],
                    'LASTNAME' => $row['lastname'],
                    'ROLE_TYPE' => $row['user_type'],
                    'PROFILE_PHOTO' => $row['profile_photo'],
                    'TIMEZONE' => $timezone,
                    'session_id' => $session_id
                );

                $this->session->set_userdata('LOGGED_IN', $sess_array);

                $match = "login_id = '" . $row['login_id'] . "'";
                $log_data = $this->common_model->get_records(LOG_MASTER, array("login_id, ip_address, session_id"), '', '', $match);
                foreach ($log_data as $log_result) {
                    $where = array('id' => $log_result['session_id']);
                    $this->common_model->delete(CI_SESSION, $where);
                }

                $login_id = $row['login_id'];
                $check_login['session_id'] = $session_id;
                $check_login['login_id'] = $login_id;
                $check_login['ip_address'] = $ip_address;
                $check_login['browser'] = $browser;
                $check_login['date'] = datetimeformat();
                //pr($check_login);exit;
                $this->common_model->insert(LOG_MASTER, $check_login);
            }

            if (trim($result[0]['dashboard_widgets']) != '') {
                $this->session->set_userdata('blazedesk_dashboardWidgets', (array) json_decode($result[0]['dashboard_widgets']));
            }
            if (trim($result[0]['dashboard_pm_widgets']) != '') {
                $this->session->set_userdata('dashboard_pm_widgets', (array) json_decode($result[0]['dashboard_pm_widgets']));
            }
            if (trim($result[0]['blazedesk_pm_taskdashboardWidgets']) != '') {
                $this->session->set_userdata('blazedesk_pm_taskdashboardWidgets', (array) json_decode($result[0]['blazedesk_pm_taskdashboardWidgets']));
            }
            if (trim($result[0]['taskdashboardinnerWidgets']) != '') {
                $this->session->set_userdata('taskdashboardinnerWidgets', (array) json_decode($result[0]['taskdashboardinnerWidgets']));
            }
            if (trim($result[0]['dashboard_support_widgets']) != '') {
                $this->session->set_userdata('dashboard_support_widgets', (array) json_decode($result[0]['dashboard_support_widgets']));
            }
            if (trim($result[0]['salesoverview_dashboard_widgets']) != '') {
                $this->session->set_userdata('salesoverview_dashboard_widgets', (array) json_decode($result[0]['salesoverview_dashboard_widgets']));
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', $this->lang->line('ERROR_INVALID_CREDENTIALS'));
            return false;
        }
    }

    /*
      Author : Mehul Patel
      Desc   : Forgotpassword page redirect
      Input  :
      Output :
      Date   : 19/02/2016
     */

    public function forgotpassword() {


        $data['main_content'] = '/forgotpassword';
        $this->parser->parse('layouts/LoginTemplate', $data);
    }

    /*
      Author : Mehul Patel
      Desc   : resetpassword prepare template and sent to requester
      Input  :
      Output :
      Date   : 19/02/2016
     */

    public function resetpassword() {

        $this->form_validation->set_error_delimiters(ERROR_START_DIV, ERROR_END_DIV);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $msg = validation_errors();
            $this->session->set_flashdata('msgs', $msg);
            redirect('Masteradmin/forgotpassword');
        } else {
            $exitEmailId = $this->checkEmailId($this->input->post('email'));
            if (empty($exitEmailId)) {
                // error
                $msg = $this->lang->line('email_id_not_exit');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                //redirect('user/register');
                redirect('Masteradmin/forgotpassword');
            } else {
                if ($this->input->post('email')) {

                    $token = md5($this->input->post('email') . date("Y-m-d H:i:s"));
                    $newpasswordlink = "<a href='" . base_url() . "/Masteradmin/updatepassword?token=" . $token . "'>" . "Click Here" . "</a>";

                    // Get Template from Template Master
                    $table = EMAIL_TEMPLATE_MASTER . ' as et';
                    // $match = "et.subject ='Forgot Password' ";
                    $match = "et.template_id =29";
                    $fields = array("et.subject,et.body");
                    $template = $this->common_model->get_records($table, $fields, '', '', $match);

                    $body1 = str_replace("{PASS_KEY_URL}", $newpasswordlink, $template[0]['body']);

                    $to = $this->input->post('email');
                    $body = str_replace("{SITE_NAME}", base_url(), $body1);
                    $subject = "BLAZEDESK :: " . $template[0]['subject'];

                    $data = array('reset_password_token' => $token, 'modified_date' => datetimeformat());
                    $where = array('email' => $this->input->post('email'));

                    if ($this->common_model->update(LOGIN, $data, $where)) {
                        //send_mail($to, $subject, $body);
                        if (send_mail($to, $subject, $body)) {
                            $msg = $this->lang->line('new_password_sent');
                        } else {

                            $msg = $this->lang->line('FAIL_WITH_SENDING_EMAILS');
                        }

                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                        redirect('Masteradmin/forgotpassword');
                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        //redirect('user/register');
                        redirect('Masteradmin/forgotpassword');
                    }
                }
            }

            redirect('Masteradmin/forgotpassword');
        }
    }

    /*
      Author : Mehul Patel
      Desc   : Update Password Page
      Input  :
      Output :
      Date   : 19/02/2016
     */

    public function updatepassword() {
    	$token_ID = $this->input->get('token');
    	if($token_ID != ""){

    		$table1 = LOGIN . ' as l';
    		$match1 = "l.reset_password_token = '".$token_ID."'";
    		$fields1 = array("l.login_id");
    		$checkTokenexist = $this->common_model->get_records($table1, $fields1, '', '', $match1);
    		if(isset($checkTokenexist[0]['login_id']) && $checkTokenexist[0]['login_id'] !="" ){
    			$data['main_content'] = '/updatepassword';

    			$this->parser->parse('layouts/LoginTemplate', $data);
    		}else{
    			 
    			redirect('Masteradmin');
    		}

    	}else{
			
			redirect('Masteradmin');
    	}

    }

    /*
      Author : Mehul Patel
      Desc   : Update Password to requested person redirect to updatepassword page
      Input  :
      Output :
      Date   : 19/02/2016
     */

    public function updatePasswords() {

        $this->form_validation->set_rules('password', 'New Password', 'trim|required|md5');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|md5|matches[password]');

        if ($this->form_validation->run() == FALSE) {

            $msg = validation_errors();
            $this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");

            $redirect_to = str_replace(base_url(), '', $_SERVER['HTTP_REFERER']);
            redirect($redirect_to);
        } else {

            $tokenID = $this->input->post('tokenID');
            $password = $this->input->post('password');

            if($tokenID !=""){
            	
	           	
            	
            	$data = array('password' => $password, 'modified_date' => datetimeformat());
            	$where = array('reset_password_token' => $tokenID);

            	$affectedrow = $this->common_model->update(LOGIN, $data, $where);
            	//$affectedrow = $this->db->affected_rows();
			
            	if ($affectedrow) {
            		$msg = $this->lang->line('newpasswordupdated');
            		$this->session->set_flashdata('msgs', "<div class='alert alert-success text-center'>$msg</div>");
            		// Once Requester update the password with token then here Token will be remove from db.
            		$data1 = array('reset_password_token' => '', 'modified_date' => datetimeformat());
            		$where1 = array('reset_password_token' => $tokenID);
            		$this->common_model->update(LOGIN, $data1, $where1);

            		redirect('Masteradmin');
            	} else {
            		// error
            		$msg = $this->lang->line('change_password_token_expired');
            		$this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");
            		//redirect('user/register');
            		redirect('Masteradmin/updatepassword');
            	}
            }else{
					// error
            		$msg = $this->lang->line('change_password_token_expired');
            		$this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");
            		redirect('user/register');
            			//redirect('Masteradmin');
			}
            
            
            
        }
    }

    /*
      Author : Mehul Patel
      Desc   : Check Email id is exist into DB or not
      Input  :
      Output :
      Date   : 02/03/2016
     */

    public function checkEmailId($emailID) {
        $table = LOGIN . ' as l';
        $match = "l.email = '" . $emailID . "' AND l.is_delete = 0";
        $fields = array("l.login_id,l.status");
        $data['duplicateEmail'] = $this->common_model->get_records($table, $fields, '', '', $match);
        return $data['duplicateEmail'];
    }

}
