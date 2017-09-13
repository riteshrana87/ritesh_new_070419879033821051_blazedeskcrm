<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends CI_Controller {

	public $viewname;

	function __construct() {
		parent::__construct();
		$this->viewname = $this->uri->segment(1);
		$this->load->library(array('form_validation', 'Session'));
		//$this->load->model('Lead_model');
	}


	/*
	 @Author : Nikunj Ghelani
	 @Desc   : add function for help
	 @Input 	:
	 @Output	:
	 @Date   : 1/03/2016
	 */

	public function add() {

		$data = array();
		$data['project_view'] = $this->viewname;
		$redirect_link=$this->input->post('redirect_link');
		$data['main_content'] = '/Lead';
		$data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
		$data['modal_title'] = $this->lang->line('create_new_lead');
		$data['submit_button_title'] = $this->lang->line('create_lead');
			
		$data['sales_view'] =$this->viewname;
		$this->load->view('AddFinal',$data);
		//$this->parser->parse('layouts/DashboardTemplate', $data);
	}

	/*
	 @Author : Nikunj Ghelani
	 @Desc   : Add Help to database
	 @Input 	:
	 @Output	:
	 @Date   : 26/02/2016
	 */

	public function saveHelpData()
	{
		/*pr($_POST);
		 die('her');*/
		$this->form_validation->set_rules('name', 'Name', 'required');
		$contact_implode='';
		$team_member_implode='';
		$redirect_link = $_SERVER['HTTP_REFERER'];
		if($this->input->post('help_desc'))
		{

			$helpdata['description'] = strip_slashes($this->input->post('help_desc'));
		}

		$helpdata['firstname'] = $this->input->post('firstname');
		$helpdata['lastname'] = $this->input->post('lastname');
		$helpdata['email'] = $this->input->post('email');
		$helpdata['platform_name'] = $this->input->post('platform_name');
		$helpdata['subject'] = $this->input->post('subject');

		$helpdata['type'] = $this->input->post('type');

		//$compaigndata['created_date']   = datetimeformat();
		//Insert Record in Database
		$success_insert=$this->common_model->insert(HELP,$helpdata);

		/*for email body template */
			
		$table1 = EMAIL_TEMPLATE_MASTER . ' as em';
		$match1 = "em.template_id=53";
		$fields1 = array("em.subject,em.body");
		$data['template'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
			
		/*end */
		$to='support@blazedesk.us';
		$from_email=$this->input->post('email');
		
		$from_name='Support Help';
		$message=strip_slashes($this->input->post('help_desc'));
		//$subject=$data['template'][0]['subject'];
		$subject=$this->input->post('email');
		
		$attach='';
		$mail=send_mail1($to, $subject, $message, $attach = NULL,$from_email,$from_name);
	
		$table2 = EMAIL_TEMPLATE_MASTER . ' as em';
		$match2 = "em.template_id=31";
		$fields2 = array("em.subject,em.body");
		$data['template'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
			
		$to=$this->input->post('email');
		$from_email='support@blazedesk.us';
		$message=$data['template'][0]['body'];
		//$subject=$data['template'][0]['subject'];
		$subject=$this->input->post('email');
		$attach='';
		$mail=send_mail1($to, $subject, $message, $attach = NULL,$from_email,$from_name);
		
		$insert_id = $this->db->insert_id();

		echo json_encode(array('status'=>1));

	}

// Added by Sanket Jayani

	function getNotifiedMessage()
	{
			
		$login_user_id =  $from_email = $this->session->userdata('LOGGED_IN')['ID'];
		$table       = TBL_MESSAGE_MASTER . ' as mm';
		$limit = 1;
		$order_field = 'message_id';
		$order_by = 'ASC';
		$match       = "mm.id_send_to = " . $login_user_id. " AND mm.is_notified = 0 ";
		$fields      = array('mm.message_id,mm.message_subject,(SELECT CONCAT(l.firstname," ",l.lastname) FROM blzdsk_login as l WHERE l.login_id = mm.id_send_from) as from_name');
		$edit_record = $this->common_model->get_records ($table, $fields, '', '', $match,'',$limit,'',$order_field,$order_by);
			
			
		$i=0;
		$notify_contain = [];
		foreach ($edit_record as $edit)
		{
			$update_data['is_notified']      = 1;
			$where                          = array('message_id' => $edit['message_id']);
			$this->common_model->update (TBL_MESSAGE_MASTER, $update_data, $where);
			$notify_contain[$i] = array('message_subject'=>$edit['message_subject'],'from_name'=>$edit['from_name'],'url'=>  base_url()."Message");
			$i++;
		}
			
		echo json_encode($notify_contain);

	}
	
// This is temporary  code once added by Mehul Patel
	public function updateLoginTable(){
		$msg = "";
		$flagToupdate = 0;
		$roleIds = array();
		$table_user = LOGIN . ' as lg';
		$where_user = array("lg.is_delete" => 0, "lg.status" =>1);
		$fields_user = array("lg.user_type,lg.is_crm_user,lg.is_pm_user,lg.is_support_user");
		$getListofUsers = $this->common_model->get_records($table_user, $fields_user, '', '', '', '', '', '', '', '', '', $where_user);
			
		foreach ($getListofUsers as $getListofUser){
			if($getListofUser['is_crm_user'] == 0 && $getListofUser['is_pm_user'] == 0  && $getListofUser['is_support_user'] == 0  ){
				$roleIds[] = $getListofUser['user_type'];
			}
		}
		
		if(!empty($roleIds)){
			$roleIds = array_unique($roleIds);

			foreach ($roleIds as $k=>$v){
				$havingModuleAssign =  getListsofSelectedModules($v);
				if(!empty($havingModuleAssign)){
					$updateData = $this->getData($havingModuleAssign);					
					if(!empty($updateData)){
						$where = array('user_type' => $v,'is_delete' => 0);
						if($this->common_model->update(LOGIN, $updateData, $where)){
							$msg = "SuccessFully Done";
						}else{
							$msg = "SuccessFully not Done pls check Again";
						}

					}
				}
			}
			
		}else{
			$msg = "Login Table Already updated";
		}
		echo $msg;
	}
// This is temporary  code once added by Mehul Patel
	public function getData($havingModuleAssign){
		$data = array();
		foreach ($havingModuleAssign as $ky=>$vl){
			if($ky=="crm_user" && $vl == 1){
				$data['is_crm_user'] = $vl;
			}elseif ($ky=="pm_user" && $vl == 1){
				$data['is_pm_user'] = $vl;
			}elseif ($ky=="support_user" && $vl == 1){
				$data['is_support_user'] = $vl;
			}
		}
		return $data;

	}

}
