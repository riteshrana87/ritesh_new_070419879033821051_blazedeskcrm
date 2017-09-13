<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rolemaster extends CI_Controller {
	//public $data;
	function __construct() {
		parent::__construct();
		if(checkPermission('Rolemaster','view') == false)
           {
               redirect('/Dashboard');
           }
		$this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'Session'));
		$this->load->model('role_model');
		$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : RoleMaster list View
	 @Input  :
	 @Output :
	 @Date   : 21/01/2016
	 */

	public function index() {

		//$datas = $this->getPurchasedModulesCounts(27);

		$dbSearch = "";
		$data['crnt_view'] = $this->viewname;
		$this->load->library('pagination'); // Load Pagination library

		if ($this->input->get('search') != '') {
			$data['search'] = $term = $this->input->get('search');

			if($data['search'] == "active" || $data['search'] == "Active"){
				$term = 1;
				$dbSearch .= "rm.status =".$term." AND  rm.is_delete = 0";
			}elseif($data['search'] == "Inactive" || $data['search'] == "InActive" || $data['search'] == "inactive"){
				$term = 0;
				$dbSearch .= "rm.status =".$term." AND  rm.is_delete = 0";
			}else{
				$searchFields = array("rm.role_name", "rm.status");
				foreach ($searchFields as $fields):
				$dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
				endforeach;
				$dbSearch = substr($dbSearch, 0, -3);
				$dbSearch = "( ".$dbSearch." ) AND  rm.is_delete = 0";
			}

		}else{

			$dbSearch = " rm.is_delete = 0";
		}

		$fields = array("rm.role_id, rm.role_name, rm.status");

		$config['total_rows'] = count($this->common_model->get_records(ROLE_MASTER . ' as rm', $fields, '', '', $dbSearch, ''));
		$config['base_url'] = site_url($data['crnt_view'] . '/index');
		$config['per_page'] = RECORD_PER_PAGE;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = floor($choice);
		$data['sortField'] = 'role_id';
		$data['sortOrder'] = 'desc';
		if ($this->input->get('orderField') != '') {
			$data['sortField'] = $this->input->get('orderField');
		}
		if ($this->input->get('sortOrder') != '') {
			$data['sortOrder'] = $this->input->get('sortOrder');
		}
		$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['information'] = $this->common_model->get_records(ROLE_MASTER . ' as rm', $fields, '', '', $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
		$page_url = $config['base_url'] . '/' . $data['page'];
			
		$data['pagination'] = $this->pagingConfig($config, $page_url);
		$data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
		$data['main_content'] = '/role_list';
		$data['header'] = array('menu_module'=>'crm');
		// $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
		if ($this->input->is_ajax_request()) {
			echo $this->load->view($this->viewname . '/ajaxlist', $data, true);
			die();
		}

		/*$table = ROLE_MASTER . ' as rm';
		 $match = "";
		 $fields = array("rm.role_id, rm.role_name, rm.status");
		 $data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);
		 */

		//Get Records From MODULE_MASTER Table

		$getModuleData = array();
		$getModuleData['table'] = MODULE_MASTER . ' as mm';
		$getModuleData['fields'] = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");
		$data['module_data_list'] = $this->common_model->get_records_array($getModuleData);

		//Get Records From AAUTH_PERMS Table

		$getPermsData = array();
		$getPermsData['table'] = AAUTH_PERMS . ' as ap';
		$getPermsData['fields'] = array("ap.id,ap.name,ap.defination");

		$data['perms_list'] = $this->common_model->get_records_array($getPermsData);
		//Get Records From AAUTH_PERMS_TO_ROLE Table
			
		$data['perms_to_role_list'] = $this->role_model->permsToRoleList();

		// $sql = "select * From blzdsk_role_master WHERE role_id NOT IN ( select role_id from blzdsk_aauth_perm_to_group GROUP BY role_id )";
		//$data['perms_to_role_list']  = $this->common_model->customQuery($sql);
		//Pass Role Master Table Record In View
		$data['drag'] = false;
		$data['header'] = array('menu_module'=>'settings');
		$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
		//$data['main_content'] = '/role_list';

		$this->parser->parse('layouts/RolemasterTemplate', $data);
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Role insertdata
	 @Input  :
	 @Output :
	 @Date   : 21/01/2016
	 */

	public function insertdata() {

	 if (!validateFormSecret()) {
	 	$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
	 	redirect('Rolemaster/index'); //Redirect On Listing page
	 }
		$data['crnt_view'] = $this->viewname;
		$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
		$data['header'] = array('menu_module'=>'settings');
		$duplicateRole= $this->checkDuplicateRole($this->input->post('role_name'));
		$getcount = count($duplicateRole);

		if( isset($duplicateRole) && empty($duplicateRole) && $getcount == 0){

			//insert the Role details into database
			$data = array(
                'role_name' => $this->input->post('role_name'),
                'created_date' => datetimeformat(),
                'status' => $this->input->post('status')
			);

			//Insert Record in Database
			$roleId = $this->common_model->insert(ROLE_MASTER, $data);
			if ($roleId) {
				//if ($this->common_model->insert(ROLE_MASTER, $data)) {
				$this->assignPermission($roleId);
			//	$msg = $this->lang->line('role_add_msg');
				//$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");


				//redirect('Rolemaster/index');
			} else {
				// error
				$msg = $this->lang->line('error_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

				redirect($this->viewname);
			}

		}else{

			$msg = "Role ". $this->input->post('role_name') ." has already been entered";
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			echo "<script>window.location.href=window.location.href</script>";
			//redirect($this->viewname);

		}

	}

	function checkDuplicateRole($role_name,$role_id=null){
		//Get Records From Role Master Table
		$table = ROLE_MASTER . ' as rm';
		if(NULL!==$role_id)
		{
			$match = "rm.role_name = '" . addslashes($role_name)."' and rm.role_id <> '".$role_id."'"." AND rm.is_delete = 0 ";
		}
		else
		{
			$match = "rm.role_name = '" . addslashes($role_name)."' AND rm.is_delete = 0";
		}
			
		$fields = array("rm.role_id,rm.status");
		$data['duplicateRole'] = $this->common_model->get_records($table, $fields, '', '', $match);
			
		return $data['duplicateRole'];
			
	}


	/*
	 @Author : Mehul Patel
	 @Desc   : Add Role View Page
	 @Input  :
	 @Output :
	 @Date   : 21/01/2016
	 */

	public function add() {
		$data['crnt_view'] = $this->viewname;
		//Get Records From Role Master Table
		$table = ROLE_MASTER . ' as rm';
		$match = "";
		$fields = array("rm.role_id, rm.role_name, rm.status");
		$data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);
		$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
		//Pass Role Master Table Record In View
		$data['main_content'] = '/add_role';
		$this->load->view('add_role',$data);
		//$this->parser->parse('layouts/CMSTemplate', $data);
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Role list view Page
	 @Input  :
	 @Output :
	 @Date   : 19/01/2016
	 */

	public function role_list() {
			
		$data['crnt_view'] = $this->viewname;
		//Get Records From Role Master Table
		$table = ROLE_MASTER . ' as rm';
		$match = "";
		$fields = array("rm.role_id, rm.role_name, rm.status");
		$data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);

		//Get Records From MODULE_MASTER Table


		$getModuleData = array();
		$getModuleData['table'] = MODULE_MASTER . ' as mm';
		$getModuleData['fields'] = array("mm.module_id, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");
		$data['module_data_list'] = $this->common_model->get_records_array($getModuleData);

		//Get Records From AAUTH_PERMS Table

		$getPermsData = array();
		$getPermsData['table'] = AAUTH_PERMS . ' as ap';
		$getPermsData['fields'] = array("ap.id,ap.name,ap.defination");

		$data['perms_list'] = $this->common_model->get_records_array($getPermsData);
		//  $sql = "select * from blzdsk_role_master as RM INNER join blzdsk_aauth_perm_to_group as APG on RM.role_id=APG.role_id GROUP BY RM.role_id";
		// $data['perms_to_role_list'] = $this->common_model->customQuery($sql);
		$data['perms_to_role_list'] = $this->role_model->permsToRoleList();
		$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
		//Pass Role Master Table Record In View
		$data['header'] = array('menu_module'=>'settings');
		$data['main_content'] = '/role_list';
		$this->load->view('role_list', $data);
		//$this->parser->parse('layouts/CMSTemplate', $data);
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Role List Edit Page
	 @Input  :
	 @Output :
	 @Date   : 19/01/2016
	 */
	public function edit($id) {

		//Get Records From Role Master Table
		$table = ROLE_MASTER . ' as rm';
		$match = "rm.role_id = " . $id;
		$fields = array("rm.role_id, rm.role_name, rm.status");
		$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
		$data['id'] = $id;
		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/add_role';
		$this->load->view('add_role',$data);

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Role List Update Query
	 @Input  : Post record from userlist
	 @Output : Update data in database and redirect
	 @Date   : 12/01/2016
	 */

	public function updatedata()
	{

		$sess_array = array('setting_current_tab' => 'setting_role_permission');
		$this->session->set_userdata($sess_array);


		$id = $this->input->get('id');
		$assignedRole = $this->checkRoleAssignedToUserStatus($id);

		if($this->input->post('status')){
			$roleStatus = $this->input->post('status');
		}
		if(!empty($assignedRole))
		{ // Role is assigned to User or not
			if($assignedRole[0]['roleStatus'] != $roleStatus){ // if Role is assigned then you don't allow to change the status
				$msg = $this->lang->line('change_role_status');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				echo "<script>window.location.href=window.location.href</script>";
				redirect('Settings/userSettings');
			}else{ // Allow to update the Role
				$table = ROLE_MASTER . ' as rm';
				$match = "rm.role_id = " . $id;
				$fields = array("rm.role_name, rm.status");
				$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
				$data['id'] = $id;
				$data['crnt_view'] = $this->viewname;

				$duplicateRole= $this->checkDuplicateRole($this->input->post('role_name'),$id);
				//print_r($duplicateRole); exit;
				$getcount = count($duplicateRole);

				if( isset($duplicateRole) && empty($duplicateRole) && $getcount == 0){
					$data = array(
                				'role_name' => $this->input->post('role_name'),
								'updated_by'=>$this->session->userdata['LOGGED_IN']['ID'],
                				'status' => $this->input->post('status')
					);
					//$id =  $this->input->post('role_id');
					//Update Record in Database
					$where = array('role_id' => $id);

					// Update form data into database
					if ($this->common_model->update(ROLE_MASTER, $data, $where)) {
						$msg = $this->lang->line('role_update_msg');
						$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

						//	redirect('Rolemaster/index');
					} else {
						// error
						$msg = $this->lang->line('error_msg');
						$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
						//redirect($this->viewname);
					}

					//redirect($this->viewname); //Redirect On Listing page
					redirect('Settings/userSettings');
				}else{
					$msg = "Role ". $this->input->post('role_name') ." has already been entered";
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
					echo "<script>window.location.href=window.location.href</script>";
					//redirect($this->viewname);

				}
			}
		}else{ // Allow to update the Role

			$table = ROLE_MASTER . ' as rm';
			$match = "rm.role_id = " . $id;
			$fields = array("rm.role_name, rm.status");
			$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
			$data['id'] = $id;
			$data['crnt_view'] = $this->viewname;

			$duplicateRole= $this->checkDuplicateRole($this->input->post('role_name'),$id);
			//print_r($duplicateRole); exit;
			$getcount = count($duplicateRole);

			if( isset($duplicateRole) && empty($duplicateRole) && $getcount == 0){
				$data = array(
	                'role_name' => $this->input->post('role_name'),
					'updated_by'=>$this->session->userdata['LOGGED_IN']['ID'],
	                'status' => $this->input->post('status')
				);
				//$id =  $this->input->post('role_id');
				//Update Record in Database
				$where = array('role_id' => $id);

				// Update form data into database
				if ($this->common_model->update(ROLE_MASTER, $data, $where)) {
					$msg = $this->lang->line('role_update_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

					//	redirect('Rolemaster/index');
				} else {
					// error
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
					//redirect($this->viewname);
				}

				//redirect($this->viewname); //Redirect On Listing page
			}else{
				$msg = "Role ". $this->input->post('role_name') ." has already been entered";
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				echo "<script>window.location.href=window.location.href</script>";
				//redirect($this->viewname);

			}

		}
		redirect('Settings/userSettings');
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : User List Delete Query
	 @Input 	: Post id from List page
	 @Output	: Delete data from database and redirect
	 @Date   : 12/01/2016
	 */

	public function deletedata($id) {

		$role_id =  $this->config->item('super_admin_role_id');

		if($role_id != $id){
			$sess_array = array('setting_current_tab' => 'setting_role_permission');
			$this->session->set_userdata($sess_array);

			$status = $this->checkRoleStatus($id);
			$assignedRole = $this->checkRoleAssignedToUser($id);

			if(!empty($assignedRole)){
				$msg = $this->lang->line('assign_role_del_error_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				//	redirect($this->viewname);
				redirect('Settings/userSettings');
				//redirect('Rolemaster/index'); //Redirect On Listing page
			}else{
					
				//if($status['status'] !=1){
				//Delete Record From Database
				if (!empty($id)) {

					$data = array('is_delete'=>1);
					$where = array('role_id' => $id);

					//	if ($this->common_model->delete(ROLE_MASTER, $where)) {
					if ($this->common_model->update(ROLE_MASTER,$data, $where)) {
						$msg = $this->lang->line('role_delete_msg');
						$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
						unset($id);
						//redirect('Rolemaster/index'); //Redirect On Listing page
						//redirect('Rolemaster/index');
					} else {
						// error
						$msg = $this->lang->line('error_msg');
						$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
						//redirect($this->viewname);
						redirect('Settings/userSettings');
						//redirect($this->viewname . '/role_list'); //Redirect On Listing page
						//redirect('Rolemaster/index');
					}
				}
				/*}else{
				 // error
				 $msg = $this->lang->line('role_del_error_msg');
				 $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				 redirect($this->viewname);
				 //redirect($this->viewname . '/role_list'); //Redirect On Listing page
				 redirect('Rolemaster/index');
				 } */
			}
		}else{
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			redirect('Settings/userSettings');
		}
		redirect('Settings/userSettings');


	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Add permission View Page
	 @Input  :
	 @Output :
	 @Date   : 21/01/2016
	 */

	public function addPermission() {
		$data['crnt_view'] = $this->viewname;
		//Get Records From AAUTH_PERMS Table
		$table = AAUTH_PERMS . ' as ap';
		$match = "";
		$fields = array("ap.id, ap.name, ap.defination");
		$data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);

		//Pass Role Master Table Record In View
		$data['main_content'] = '/perms_add';
		$this->parser->parse('layouts/RolemasterTemplate', $data);
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Insert New Permission
	 @Input  :
	 @Output :
	 @Date   : 25/01/2016
	 */

	public function insertPerms() {

	 if (!validateFormSecret()) {
	 	$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
	 	//redirect($this->viewname . '/role_list'); //Redirect On Listing page
	 	redirect('Rolemaster/index');
	 }

		$data['crnt_view'] = $this->viewname;
		//insert the Role details into database
		$data = array(
            'name' => $this->input->post('perms_name'),
            'defination' => $this->input->post('perms_defination')
		);

		//Insert Record in Database

		if ($this->common_model->insert(AAUTH_PERMS, $data)) {
			$msg = $this->lang->line('perms_add_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
			redirect('Rolemaster/index');
			//redirect($this->viewname . '/role_list');
		} else {
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

			redirect($this->viewname);
		}
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Role List Edit Page
	 @Input  :
	 @Output :
	 @Date   : 19/01/2016
	 */

	public function editPerms() {

		$id = $this->input->get('id');

		//Get Records From AAUTH_PERMS Table
		$table = AAUTH_PERMS . ' as ap';
		$match = "ap.id = " . $id;
		$fields = array("ap.id, ap.name, ap.defination");
		$data['perms_list'] = $this->common_model->get_records($table, $fields, '', '', $match);

		//Get Records From Permission Table
		$data['id'] = $id;
		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/perms_add';

		$this->parser->parse('layouts/RolemasterTemplate', $data);
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Permission Update Query
	 @Input  : Post record from Permission List
	 @Output : Update data in database and redirect
	 @Date   : 25/01/2016
	 */

	public function updatePerms() {

		$id = $this->input->get('id');

		//Get Records From AAUTH_PERMS Table

		$getPermsData = array();
		$getPermsData['table'] = AAUTH_PERMS . ' as ap';
		$getPermsData['match'] = "ap.id = " . $id;
		$getPermsData['fields'] = array("ap.id,ap.name,ap.defination");

		$data['perms_list'] = $this->common_model->get_records_array($getPermsData);

		//Get Records From Permission Table
		$data['id'] = $id;
		$data['crnt_view'] = $this->viewname;

		$data = array(
            'name' => $this->input->post('perms_name'),
            'defination' => $this->input->post('perms_defination')
		);

		//$id =  $this->input->post('role_id');
		//Update Record in Database
		$where = array('id' => $id);

		// Update form data into database
		if ($this->common_model->update(AAUTH_PERMS, $data, $where)) {
			$msg = $this->lang->line('perms_update_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

			redirect('Rolemaster/index');
		} else {
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			redirect($this->viewname);
		}

		redirect($this->viewname); //Redirect On Listing page
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Delete Permission Query
	 @Input 	: Post id from List page
	 @Output	: Delete data from database and redirect
	 @Date   : 26/01/2016
	 */

	public function deletePerms() {
		$id = $this->input->get('id');
		//Delete Record From Database
		if (!empty($id)) {
			$where = array('id' => $id);
			//  $this->common_model->delete(LOGIN,$where);
			if ($this->common_model->delete(AAUTH_PERMS, $where)) {

				$msg = $this->lang->line('perms_delete_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
				unset($id);
				redirect('Rolemaster/index'); //Redirect On Listing page
			} else {
				// error
				$msg = $this->lang->line('error_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				redirect($this->viewname);
				redirect('Rolemaster/index'); //Redirect On Listing page

			}
		}
		//	redirect($this->viewname.'/userlist');	//Redirect On Listing page
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Assign Permission
	 @Input  :
	 @Output :
	 @Date   : 21/01/2016
	 */

	public function assignPermission($roleId) {
			
		if($this->input->post("moduleName") != ""){
			$moduleComponent = $this->input->post("moduleName");
		}else{
			$moduleComponent = "CRM";
		}
		//   $sql = "select * From blzdsk_role_master WHERE role_id NOT IN ( select role_id from blzdsk_aauth_perm_to_group GROUP BY role_id )";
		//  $getRoles = $this->common_model->customQuery($sql);
		$getRoles = $this->role_model->getRoles();

		if(empty($getRoles)){
			// error
			$msg = $this->lang->line('assign_role_error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			echo "<script>window.location.href=window.location.href</script>";
			//redirect($this->viewname);
			//redirect($this->viewname . '/role_list'); //Redirect On Listing page
		}else{
			$data['crnt_view'] = $this->viewname;
			//Get Records From AAUTH_PERMS Table
			$table = AAUTH_PERMS_TO_ROLE . ' as apTOr';
			$match = "";
			$fields = array("apTOr.perm_id, apTOr.role_id");
			$data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);
			$data['userType'] = getUserTypeList();

			$data['perms_list'] = array();
			$data['module_list'] = getModuleList();
			//pr($data['module_list']);
			$data['CRM_module_list'] = getCRMModuleList();
			$data['PM_module_list'] = getPMModuleList();
			$data['Finance_module_list'] = getFinanceModuleList();
			$data['Support_module_list'] = getSupportModuleList();
			$data['HR_module_list'] = getHRModuleList();
			$data['User_module_list'] = getUserModuleList();
			$data['settings_module_list'] = getsettingsModuleList();
			$data['roleId'] = $roleId;
			$data['getPermList'] = getPermsList();
			$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
			//Pass Role Master Table Record In View
			$master_user_id =  $this->config->item('master_user_id');
			$sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
			$table = SETUP_MASTER . ' as ct';
			//$where_setup_data = array("ct.login_id" => $master_user_id);
			$where_setup_data = "ct.domain_name = '".$sub_domain."'";
			
			$fields = array("ct.*");
			$data['hasPermission'] = $this->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where_setup_data);
		
			// Check Purchased module limit is completed or not
			$data['getModuleCounts'] = $this->getPurchasedModulesCounts($master_user_id);

			//getAssigned Module to Role
			$data['assignedModule'] = getAssignedModuleCounts($roleId);

			$data['main_content'] = '/assign_perms';
			$this->load->view('assign_perms',$data);
			//$this->parser->parse('layouts/CMSTemplate', $data);
		}

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Edit Assign Permission
	 @Input  :
	 @Output :
	 @Date   : 21/01/2016
	 */

	public function editPermission($id = null) {

		
		$frmSession = $this->session->userdata('FORM_SECRET_DATA');
		$form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
		//echo "Form Secreat :".$form_secret;
		
		//if (isset($frmSession)) {
			//if (strcasecmp($form_secret, $frmSession) === 0) {
			if($form_secret != ""){

				$role_id =  $this->config->item('super_admin_role_id');

				if($role_id != $id){

					if ($id != NULL) {
						// Get Role List
						$table1 = ROLE_MASTER . ' as rm';
						$fields1 = array("rm.role_id, rm.role_name");
						//$where = array('rm.status' => '1');
						$match1 = "";
						$data['crnt_view'] = $this->viewname;
						$table = AAUTH_PERMS_TO_ROLE . ' as apTOr';
						$match = "apTOr.role_id = " . $id;
						$fields = array("apTOr.perm_id, apTOr.role_id,apTOr.module_id");
						$permList = $data['view_perms_to_role_list'] = $this->common_model->get_records($table, $fields, '', '', $match);

						$data['userType'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);;
						$data['perms_list'] = array();
						//	$data['module_list'] = getModuleList();
						$data['getPermList'] = getPermsList();
						$data['CRM_module_list'] = getCRMModuleList();
						$data['PM_module_list'] = getPMModuleList();
						$data['Finance_module_list'] = getFinanceModuleList();
						$data['Support_module_list'] = getSupportModuleList();
						$data['HR_module_list'] = getHRModuleList();
						$data['User_module_list'] = getUserModuleList();
						$data['settings_module_list'] = getsettingsModuleList();
						$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
						//Pass Role Master Table Record In View


						$master_user_id =  $this->config->item('master_user_id');
						$sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));

						$table = SETUP_MASTER . ' as ct';
						//$where_setup_data = array("ct.login_id" => $master_user_id);
						$where_setup_data = "ct.domain_name = '".$sub_domain."'";
						$fields = array("ct.*");
						$data['hasPermission'] = $this->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where_setup_data);

						// Check Purchased module limit is completed or not
						$data['getModuleCounts'] = $this->editTimeCheckPurchasedUserLimit($id);

						//getAssigned Module to Role
						$data['assignedModule'] = getAssignedModuleCounts($id);

						$data['main_content'] = '/edit_perms';
						$this->load->view('edit_perms',$data);
						//$this->parser->parse('layouts/CMSTemplate', $data);
					} else {
						$msg = $this->lang->line('perms_assign_update_msg');
						$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
						redirect($this->viewname);
					}

				}else{
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
					redirect('Settings/userSettings');
				}

			} else {
				//Invalid secret key
				exit('No Direct scripts are allowed');
			}
			
		/*} else {
			//Secret key missing
			exit('No Direct scripts are allowed');
		}*/

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Insert New Permission
	 @Input  :
	 @Output :
	 @Date   : 25/01/2016
	 */

	public function insertAssginPerms() {

		$userCheckLimit1 = array();

		if($this->input->post('id') !=""){

			// $userCheckLimit1 =  $this->updateTimeCheckUserAvailbility($this->input->post('id'));

			//if($userCheckLimit1){

				$sess_array = array('setting_current_tab' => 'setting_role_permission');
				$this->session->set_userdata($sess_array);


				$exp1 = $exp3 = $exp4 = $exp5 = $expdata = array();
				$data['crnt_view'] = $this->viewname;
				$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
				//insert the Role details into database
				$postData = $exploded = array();
				if (count($this->input->post()) > 0) {
					$postData = $this->input->post();

					//unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id']);
					unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id'],$postData['editPerm'],$postData['submit_btn']);
					foreach ($postData as $key => $val) {
						$exp1 = explode('checkbox', $key);

						if(isset($exp1[1])){
							$expdata = $exp1[1];
						}
						$expdata = explode('_', $expdata);
						if(isset($expdata[0])){
							$exp3 = $expdata[0];
						}
						if(isset($expdata[1])){
							$exp4 = $expdata[1];
						}
						if(isset($expdata[2])){
							$exp5 = $expdata[2];
						}
						if( $exp3 != NULL && $exp4 != NULL && $exp5 != ""){
							$exploded[] = array('perm_id' => $exp4, 'role_id' => $this->input->post('usertype'), 'module_id' => $exp3, 'component_name' => $exp5);

						}
					}
				}


				//Insert Record in Database
				if(empty($exploded)){
					$msg = $this->lang->line('perms_Assign_error');
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
					redirect('Settings/userSettings');
				}


				if ($this->input->post('id')) {
					$this->common_model->delete(AAUTH_PERMS_TO_ROLE, array('role_id' => $this->input->post('id')));
				}


				if ($this->common_model->insert_batch(AAUTH_PERMS_TO_ROLE, $exploded)) {

					if($this->input->post('editPerm') != "" ){
						$msg = $this->lang->line('perms_update_msg');
					}else{
						$msg = $this->lang->line('perms_assign_add_msg');
					}
					// Update the Assigned module to User based on selected Role

					$hasUser = getUserList($this->input->post('id'));
					if(isset($hasUser[0]['login_id']) && $hasUser[0]['login_id'] !=""){
						$this->updateRolebasedUserCreationCount($this->input->post('id'));
					}


					$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

					//redirect('Rolemaster/index');
				} else {
					// error
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

					//redirect($this->viewname);
				}
				redirect('Settings/userSettings');
					
					
			/*}else{
				
				$msg = $this->lang->line('user_limit_over');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				redirect('Settings/userSettings');

			}*/

		}else{


			$sess_array = array('setting_current_tab' => 'setting_role_permission');
			$this->session->set_userdata($sess_array);


			$exp1 = $exp3 = $exp4 = $exp5 = $expdata = array();
			$data['crnt_view'] = $this->viewname;
			$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
			//insert the Role details into database
			$postData = $exploded = array();
			if (count($this->input->post()) > 0) {
				$postData = $this->input->post();

				//unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id']);
				unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id'],$postData['editPerm'],$postData['submit_btn']);
				foreach ($postData as $key => $val) {
					$exp1 = explode('checkbox', $key);

					if(isset($exp1[1])){
						$expdata = $exp1[1];
					}
					$expdata = explode('_', $expdata);
					if(isset($expdata[0])){
						$exp3 = $expdata[0];
					}
					if(isset($expdata[1])){
						$exp4 = $expdata[1];
					}
					if(isset($expdata[2])){
						$exp5 = $expdata[2];
					}


					if( $exp3 != NULL && $exp4 != NULL && $exp5 != ""){
						$exploded[] = array('perm_id' => $exp4, 'role_id' => $this->input->post('usertype'), 'module_id' => $exp3, 'component_name' => $exp5);

					}
				}
			}


			//Insert Record in Database
			if(empty($exploded)){
				$msg = $this->lang->line('perms_Assign_error');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				redirect('Settings/userSettings');
			}


			if ($this->input->post('id')) {
				$this->common_model->delete(AAUTH_PERMS_TO_ROLE, array('role_id' => $this->input->post('id')));
			}


			if ($this->common_model->insert_batch(AAUTH_PERMS_TO_ROLE, $exploded)) {

				if($this->input->post('editPerm') != "" ){
					$msg = $this->lang->line('perms_update_msg');
				}else{
					$msg = $this->lang->line('perms_assign_add_msg');
				}
				// Update the Assigned module to User based on selected Role

				$hasUser = getUserList($this->input->post('id'));
				if(isset($hasUser[0]['login_id']) && $hasUser[0]['login_id'] !=""){
					$this->updateRolebasedUserCreationCount($this->input->post('id'));
				}


				$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

				//redirect('Rolemaster/index');
			} else {
				// error
				$msg = $this->lang->line('error_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

				//redirect($this->viewname);
			}
			redirect('Settings/userSettings');


		}



			
		/*


		if($userCheckLimit1){
		$sess_array = array('setting_current_tab' => 'setting_role_permission');
		$this->session->set_userdata($sess_array);


		$exp1 = $exp3 = $exp4 = $exp5 = $expdata = array();
		$data['crnt_view'] = $this->viewname;
		$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
		//insert the Role details into database
		$postData = $exploded = array();
		if (count($this->input->post()) > 0) {
		$postData = $this->input->post();

		unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id']);

		foreach ($postData as $key => $val) {
		$exp1 = explode('checkbox', $key);

		if(isset($exp1[1])){
		$expdata = $exp1[1];
		}
		$expdata = explode('_', $expdata);
		if(isset($expdata[0])){
		$exp3 = $expdata[0];
		}
		if(isset($expdata[1])){
		$exp4 = $expdata[1];
		}
		if(isset($expdata[2])){
		$exp5 = $expdata[2];
		}


		if( $exp3 != NULL && $exp4 != NULL && $exp5 != ""){
		$exploded[] = array('perm_id' => $exp4, 'role_id' => $this->input->post('usertype'), 'module_id' => $exp3, 'component_name' => $exp5);

		}
		}
		}


		//Insert Record in Database
		if(empty($exploded)){
		$msg = $this->lang->line('perms_Assign_error');
		$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
		redirect('Settings/userSettings');
		}


		if ($this->input->post('id')) {
		$this->common_model->delete(AAUTH_PERMS_TO_ROLE, array('role_id' => $this->input->post('id')));
		}


		if ($this->common_model->insert_batch(AAUTH_PERMS_TO_ROLE, $exploded)) {

		if($this->input->post('editPerm') != "" ){
		$msg = $this->lang->line('perms_update_msg');
		}else{
		$msg = $this->lang->line('perms_assign_add_msg');
		}
		// Update the Assigned module to User based on selected Role

		$hasUser = getUserList($this->input->post('id'));
		if(isset($hasUser[0]['login_id']) && $hasUser[0]['login_id'] !=""){
		$this->updateRolebasedUserCreationCount($this->input->post('id'));
		}


		$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

		//redirect('Rolemaster/index');
		} else {
		// error
		$msg = $this->lang->line('error_msg');
		$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

		//redirect($this->viewname);
		}
		redirect('Settings/userSettings');
		}elseif($this->input->post('id') == ""){

		$sess_array = array('setting_current_tab' => 'setting_role_permission');
		$this->session->set_userdata($sess_array);


		$exp1 = $exp3 = $exp4 = $exp5 = $expdata = array();
		$data['crnt_view'] = $this->viewname;
		$data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
		//insert the Role details into database
		$postData = $exploded = array();
		if (count($this->input->post()) > 0) {
		$postData = $this->input->post();

		unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id']);

		foreach ($postData as $key => $val) {
		$exp1 = explode('checkbox', $key);

		if(isset($exp1[1])){
		$expdata = $exp1[1];
		}
		$expdata = explode('_', $expdata);
		if(isset($expdata[0])){
		$exp3 = $expdata[0];
		}
		if(isset($expdata[1])){
		$exp4 = $expdata[1];
		}
		if(isset($expdata[2])){
		$exp5 = $expdata[2];
		}


		if( $exp3 != NULL && $exp4 != NULL && $exp5 != ""){
		$exploded[] = array('perm_id' => $exp4, 'role_id' => $this->input->post('usertype'), 'module_id' => $exp3, 'component_name' => $exp5);

		}
		}
		}


		//Insert Record in Database
		if(empty($exploded)){
		$msg = $this->lang->line('perms_Assign_error');
		$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
		redirect('Settings/userSettings');
		}


		if ($this->input->post('id')) {
		$this->common_model->delete(AAUTH_PERMS_TO_ROLE, array('role_id' => $this->input->post('id')));
		}


		if ($this->common_model->insert_batch(AAUTH_PERMS_TO_ROLE, $exploded)) {

		if($this->input->post('editPerm') != "" ){
		$msg = $this->lang->line('perms_update_msg');
		}else{
		$msg = $this->lang->line('perms_assign_add_msg');
		}
		// Update the Assigned module to User based on selected Role

		$hasUser = getUserList($this->input->post('id'));
		if(isset($hasUser[0]['login_id']) && $hasUser[0]['login_id'] !=""){
		$this->updateRolebasedUserCreationCount($this->input->post('id'));
		}


		$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

		//redirect('Rolemaster/index');
		} else {
		// error
		$msg = $this->lang->line('error_msg');
		$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

		//redirect($this->viewname);
		}
		redirect('Settings/userSettings');



		}else{
		$msg = $this->lang->line('user_limit_over');
		$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
		redirect('Settings/userSettings');

		}
		*/

		//}
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : View Permission
	 @Input  :
	 @Output :
	 @Date   : 27/01/2016
	 */

	public function view_perms_to_role_list($id = null) {
		// Get Role List
		$table1 = ROLE_MASTER . ' as rm';
		$fields1 = array("rm.role_id, rm.role_name");
		//$where = array('rm.status' => '1');
		$match1 = "";
		// $data['role_option'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
			
		//$data['crnt_view'] = $this->viewname;
		//Get Records From AAUTH_PERMS Table
		$table = AAUTH_PERMS_TO_ROLE . ' as apTOr';
		$match = "apTOr.role_id = " . $id;
		$fields = array("apTOr.perm_id, apTOr.role_id,apTOr.module_id");
		$permList = $data['view_perms_to_role_list'] = $this->common_model->get_records($table, $fields, '', '', $match);

		$data['userType'] =  $this->common_model->get_records($table1, $fields1, '', '', $match1);;
		$data['perms_list'] = array();
		//$data['module_list'] = getModuleList();
		$data['CRM_module_list'] = getCRMModuleList();
		$data['PM_module_list'] = getPMModuleList();
		$data['Finance_module_list'] = getFinanceModuleList();
		$data['Support_module_list'] = getSupportModuleList();
		$data['HR_module_list'] = getHRModuleList();
		$data['User_module_list'] = getUserModuleList();
		$data['settings_module_list'] = getsettingsModuleList();

		$data['getPermList'] = getPermsList();

		$master_user_id =  $this->config->item('master_user_id');
		$sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));
		$table = SETUP_MASTER . ' as ct';
		//$where_setup_data = array("ct.login_id" => $master_user_id);
		$where_setup_data = "ct.domain_name = '".$sub_domain."'";
		$fields = array("ct.*");
		$data['hasPermission'] = $this->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where_setup_data);
		
		$data['getModuleCounts'] = $this->getPurchasedModulesCounts($master_user_id);

		//Pass Role Master Table Record In View
		$data['main_content'] = '/veiw_assign_perms';
		$this->load->view("veiw_assign_perms", $data);
		//$this->parser->parse('layouts/CMSTemplate', $data);
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Delete Assigned Permission
	 @Input  : Post id from List page
	 @Output : Delete data from database and redirect
	 @Date   : 27/01/2016
	 */

	public function deleteAssignperms($id) {

		//$id = $this->input->get('id');
		//Delete Record From Database
		if (!empty($id)) {
			$where = array('role_id' => $id);
			//  $this->common_model->delete(LOGIN,$where);
			if ($this->common_model->delete(AAUTH_PERMS_TO_ROLE, $where)) {

				$msg = $this->lang->line('perms_assign_delete_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
				unset($id);
				redirect('Rolemaster/index'); //Redirect On Listing page
			} else {
				// error
				$msg = $this->lang->line('error_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				redirect('Settings/userSettings'); //Redirect On Listing page
			}
		}
		// redirect($this->viewname.'/userlist'); //Redirect On Listing page
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Check Role Status Active / Inactive
	 @Input  : Post id from List page
	 @Output : return Status Array
	 @Date   : 27/01/2016
	 */
	public function checkRoleStatus($id){
			
		$table = ROLE_MASTER . ' as rm';
		$match = "rm.role_id = " . $id;
		$fields = array("rm.role_id,rm.status");
		$roleStatus = $this->common_model->get_records($table, $fields, '', '', $match);

		return $roleStatus[0];
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : checkRoleAssignedToUser
	 @Input  : Post id from List page
	 @Output : return Status Array
	 @Date   : 27/01/2016
	 */
	public function checkRoleAssignedToUser($id){
			
		$table = LOGIN . ' as l';
		$match = "l.user_type = " . $id." AND l.is_delete = 0";
		$fields = array("l.user_type");
		$roleStatus = $this->common_model->get_records($table, $fields, '', '', $match);
		return $roleStatus[0];
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : checkRoleAssignedToUser
	 @Input  : Post id from List page
	 @Output : return Status Array
	 @Date   : 27/01/2016
	 */
	public function checkRoleAssignedToUserStatus($id){
			
		$roleStatus = $this->role_model->checkRoleAssigne($id);

		return $roleStatus;
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Add New Module
	 @Input  :
	 @Output :
	 @Date   : 01/02/2016
	 */
	public function addModule(){
		$data['crnt_view'] = $this->viewname;
		//Get Records From AAUTH_PERMS Table
		$table = MODULE_MASTER . ' as mm';
		$match = "";
		$fields = array("mm.module_id, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");
		$data['moduleInformation'] = $this->common_model->get_records($table, $fields, '', '', $match);

		//Pass Role Master Table Record In View
		$data['main_content'] = '/add_module';
		$this->load->view('add_module',$data);
		//$this->parser->parse('layouts/CMSTemplate', $data);
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Insert New Module Details
	 @Input  :
	 @Output :
	 @Date   : 01/02/2016
	 */

	public function insertModule() {

	 if (!validateFormSecret()) {
	 	$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
	 	redirect('Rolemaster/index'); //Redirect On Listing page
	 }

		$data['crnt_view'] = $this->viewname;
			
		$data = array(
			'component_name' => $this->input->post('component_name'),
            'module_name' => $this->input->post('module_name'),
            'module_unique_name' => $this->input->post('module_name'),
    	  	'controller_name' => $this->input->post('controller_name'),
			'created_date'=> datetimeformat(),
    		'status'=>$this->input->post('module_status')
		);

		//Insert Record in Database
		if ($this->common_model->insert(MODULE_MASTER, $data)) {
			$msg = $this->lang->line('module_add_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
			redirect('Rolemaster/index');
		} else {
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			redirect($this->viewname);
		}

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Module Master Edit Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */
	public function editModule($id)
	{

		//$id = $this->input->get('id');
		//Get Records From Login Table
		$table	= MODULE_MASTER.' as mm';
		$match 	= "mm.module_id = ".$id;
		$fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");
		$data['editModuleRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;
		$data['module_status'] = getModuleStatus();
		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/add_module';
		$this->load->view('add_module',$data);
		//$this->parser->parse('layouts/CMSTemplate', $data);
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Module Master update Query
	 @Input  : Post record from Module Master List
	 @Output : Update data in database and redirect
	 @Date   : 01/02/2016
	 */

	public function updateModule() {

		$id = $this->input->get('id');

		//Get Records From AAUTH_PERMS Table

		$getModuleData = array();
		$getModuleData['table'] = MODULE_MASTER . ' as mm';
		$getModuleData['match'] = "mm.module_id = " . $id;
		$getModuleData['fields'] = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");

		$data['module_data_list'] = $this->common_model->get_records_array($getModuleData);

		//Get Records From Module Master Table
		$data['module_id'] = $id;
		$data['crnt_view'] = $this->viewname;

		$data = array(
		 'component_name' => $this->input->post('component_name'),
            'module_name' => $this->input->post('module_name'),
            'module_unique_name' => $this->input->post('module_name'),
    	  	'controller_name' => $this->input->post('controller_name'),			
    		'status'=>$this->input->post('module_status')
		);

		//$id =  $this->input->post('role_id');
		//Update Record in Database
		$where = array('module_id' => $id);

		// Update form data into database
		if ($this->common_model->update(MODULE_MASTER, $data, $where)) {
			$msg = $this->lang->line('module_update_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

			redirect('Rolemaster/index');
		} else {
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			redirect($this->viewname);
		}

		redirect($this->viewname); //Redirect On Listing page
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Delete Module Master Query
	 @Input 	: Post id from List page
	 @Output	: Delete data from database and redirect
	 @Date   : 01/02/2016
	 */

	public function deleteModuleData($id) {
		//	$id = $this->input->get('id');
		//Delete Record From Database
		if (!empty($id)) {
			$where = array('module_id' => $id);
			//  $this->common_model->delete(LOGIN,$where);
			if ($this->common_model->delete(MODULE_MASTER, $where)) {

				$msg = $this->lang->line('module_delete_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
				unset($id);
				redirect('Rolemaster/index'); //Redirect On Listing page
			} else {
				// error
				$msg = $this->lang->line('error_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				redirect($this->viewname);
				redirect('Rolemaster/index'); //Redirect On Listing page
			}
		}
		//	redirect($this->viewname.'/userlist');	//Redirect On Listing page
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Common pagination initialization
	 @Input 	:
	 @Output	:
	 @Date   : 01/02/2016
	 */
	private function pagingConfig($config, $page_url) {
		$config["uri_segment"] = 3;
		$choice = $config["total_rows"] / $config["per_page"];
		$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01 pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="' . $page_url . '">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['first_link'] = '&lt;&lt;';
		$config['last_link'] = '&gt;&gt;';

		$this->pagination->cur_page = 3;

		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : getPurchasedModulesCounts
	 @Input 	: master_user_id as parent_id
	 @Output	:
	 @Date   : 07/06/2016
	 */

	public function getPurchasedModulesCounts($parent_id){
			
		$userCount= array();
		$getTotalUserCreated = "";
		$getPurchasedModuleCounts = "";

		/*
		 $table = LOGIN ;
		 $match = "is_delete = 0 AND status=1  AND  parent_id = " . $parent_id;
		 $fields = array("sum(is_crm_user) AS crm_user ,sum(is_pm_user) AS pm_user,sum(is_support_user) AS support_user ");
		 $getTotalUserCreated = $this->common_model->get_records($table, $fields, '', '', $match);
		 */

		$table = LOGIN ;
		$match = "is_delete = 0 AND status=1 ";
		$fields = array("sum(is_crm_user) AS crm_user ,sum(is_pm_user) AS pm_user,sum(is_support_user) AS support_user ");
		$getTotalUserCreated = $this->common_model->get_records($table, $fields, '', '', $match);

		$getPurchasedModuleCounts = getPurchasedModuleCounts();

		foreach($getPurchasedModuleCounts as $k=>$v){
			$userCount = $v;
		}
		/*
		 foreach ($getTotalUserCreated as $key=>$val){
			$val['crm_user'] = $val['crm_user'] + 1 ;
			$val['pm_user'] = $val['pm_user'] + 1 ;
			$val['support_user'] = $val['support_user'] + 1 ;

			}*/

		if(isset($getTotalUserCreated[0])){
			$result=array_intersect_assoc($v,$getTotalUserCreated[0]);
		}


		if(!empty($result)){
			return $result;
		}else{
			return $result;
		}

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : getPurchasedModulesCounts
	 @Input 	: master_user_id as parent_id
	 @Output	:
	 @Date   : 07/06/2016
	 */

	public function editTimeCheckPurchasedUserLimit($role_id){
			
		$userCount= array();
		$getTotalUserCreated = "";
		$getPurchasedModuleCounts = "";
		$result = array();
		$checkUserCreate = array();
		$havingModuleAssign = array();
		$diff = array();
		$k = array();
		$j=0;
		$result_array = array();
		$inArr = array();
		// Get List of Module for the Selected Role having Assigned
		$havingModuleAssign =  getListsofSelectedModules($role_id);

		// Get Total Count of User having Assigned selected Role
		$checkUserCreate =  isUserCreate($role_id);

		// Get List of Purchased User  with Assigned Modules
		$getPurchasedModuleCounts = getPurchasedModuleCounts();
			
		$table = LOGIN ;
		$match = "is_delete = 0 AND status=1 ";
		$fields = array("sum(is_crm_user) AS crm_user ,sum(is_pm_user) AS pm_user,sum(is_support_user) AS support_user ");
		$getTotalUserCreated = $this->common_model->get_records($table, $fields, '', '', $match);



		$inArr = in_array('0', $checkUserCreate);

		if(!empty($checkUserCreate) && !empty($havingModuleAssign) && $inArr == false){
			foreach($getPurchasedModuleCounts as $k=>$v){
				$userCount = $v;
			}
			if(isset($getTotalUserCreated[0])){
				$result=array_intersect_assoc($v,$getTotalUserCreated[0]);
			}

		}elseif(!empty($checkUserCreate) && empty($havingModuleAssign) && $inArr == false){
				
			if(isset($getPurchasedModuleCounts[0])){

				$diff = array_diff($getTotalUserCreated[0],$getPurchasedModuleCounts[0]);
			}

			$k = array_keys($diff);
				
			$arrt2=array_diff_key($getTotalUserCreated[0],$diff);

			$arrt=(array_diff_key($checkUserCreate,$diff));

			if(count($arrt)>0)
			{
				foreach($arrt as $elm=>$v1){
					unset($checkUserCreate[$elm]);
				}

					
			}else
			{
				$arrt=$checkUserCreate;
			}
			if(count($arrt2)>0)
			{
				foreach($arrt2 as $elm1=>$y){
					unset($getTotalUserCreated[0][$elm1]);
				}

					
			}else
			{
				$arrt2=$getTotalUserCreated[0];
			}
				
			$result_array = array("crm_user"=>1,"pm_user" =>1,"support_user" =>1);

			for($i=0; $i<count($k); $i++){
				foreach ($checkUserCreate as $key=>$val){
					foreach ($getTotalUserCreated[0] as $ky=>$vl ){
							
						if($key==$ky){
								
							if($val < $vl){
								$result[$key] = 0;
							}else{
								$result[$key] = 1;
							}
						}

					}

				}
				$j++;
			}

			$result = array_merge($result_array,$result);

		}else{


			if(isset($getPurchasedModuleCounts[0])){

				$diff = array_diff($getTotalUserCreated[0],$getPurchasedModuleCounts[0]);
			}


			$k = array_keys($diff);

			//$result_array = array("crm_user"=>1,"pm_user" =>1,"support_user" =>1);

			for($i=0; $i<count($k); $i++){
				foreach ($checkUserCreate as $key=>$val){
					foreach ($getTotalUserCreated[0] as $ky=>$vl ){
						if($key==$k[$j]){
							if($val < $vl){
								$result[$k[$j]] = 0;
							}else{
								$result[$k[$j]] = 1;
							}
						}

					}

				}
				$j++;
			}
			//pr($result);
			//$result = array_merge($result_array,$result);

		}

		if(!empty($result)){
			return $result;
		}else{
			return $result;
		}

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : updateRolebasedUserCreationCount
	 @Input 	: Roleid
	 @Output	:
	 @Date   : 07/06/2016
	 */
	public function updateRolebasedUserCreationCount($roleId){


		$is_crm_user = 0;
		$is_pm_user = 0;
		$is_support_user = 0;
		$getModuleSelect = "";
		//$data = array();

		$getModuleSelect = $this->assignModuleCount($roleId);

		if(isset($getModuleSelect['CRM'])&& isset($getModuleSelect['PM'])&& isset($getModuleSelect['Support'])){// CRM+PM+SUPPORT

			$is_crm_user = 1;
			$is_pm_user = 1;
			$is_support_user = 1;

			$data = array(
				'is_crm_user' => $is_crm_user,
            	'is_pm_user' => $is_pm_user,
            	'is_support_user' => $is_support_user
			);

		}elseif (isset($getModuleSelect['CRM']) && !isset($getModuleSelect['PM'])  && !isset($getModuleSelect['Support'])){ //CRM
			$is_crm_user = 1;
			$is_pm_user = 0;
			$is_support_user = 0;

			$data = array(
				'is_crm_user' => $is_crm_user,
            	'is_pm_user' => $is_pm_user,
            	'is_support_user' => $is_support_user
			);
		}elseif (isset($getModuleSelect['PM']) && !isset($getModuleSelect['CRM'])  && !isset($getModuleSelect['Support']) ){ //PM
			$is_crm_user = 0;
			$is_pm_user = 1;
			$is_support_user = 0;

			$data = array(
				'is_crm_user' => $is_crm_user,
            	'is_pm_user' => $is_pm_user,
            	'is_support_user' => $is_support_user
			);
		}elseif (isset($getModuleSelect['Support']) && !isset($getModuleSelect['PM'])  && !isset($getModuleSelect['CRM'])  ){ //Support
			$is_crm_user = 0;
			$is_pm_user = 0;
			$is_support_user = 1;

			$data = array(
				'is_crm_user' => $is_crm_user,
            	'is_pm_user' => $is_pm_user,
            	'is_support_user' => $is_support_user
			);
		}elseif(isset($getModuleSelect['CRM'])&& isset($getModuleSelect['PM']) && !isset($getModuleSelect['Support'])){// CRM+PM
			$is_crm_user = 1;
			$is_pm_user = 1;
			$is_support_user = 0;

			$data = array(
				'is_crm_user' => $is_crm_user,
            	'is_pm_user' => $is_pm_user,
            	'is_support_user' => $is_support_user
			);


		}elseif (isset($getModuleSelect['CRM'])&& isset($getModuleSelect['Support']) && !isset($getModuleSelect['PM'])){//CRM+Support
			$is_crm_user = 1;
			$is_pm_user = 0;
			$is_support_user = 1;

			$data = array(
				'is_crm_user' => $is_crm_user,
            	'is_pm_user' => $is_pm_user,
            	'is_support_user' => $is_support_user
			);

		}elseif (isset($getModuleSelect['PM'])&& isset($getModuleSelect['Support']) && !isset($getModuleSelect['CRM'])){ //PM+Support
			$is_crm_user = 0;
			$is_pm_user = 1;
			$is_support_user = 1;

			$data = array(
				'is_crm_user' => $is_crm_user,
            	'is_pm_user' => $is_pm_user,
            	'is_support_user' => $is_support_user
			);
		}elseif (!isset($getModuleSelect['CRM'])&& !isset($getModuleSelect['PM'])&& !isset($getModuleSelect['Support'])){
			$is_crm_user = 0;
			$is_pm_user = 0;
			$is_support_user = 0;
			$data = array(
				'is_crm_user' => $is_crm_user,
            	'is_pm_user' => $is_pm_user,
            	'is_support_user' => $is_support_user
			);

		}elseif (empty($getModuleSelect)){
			$is_crm_user = 0;
			$is_pm_user = 0;
			$is_support_user = 0;
			$data = array(
				'is_crm_user' => $is_crm_user,
            	'is_pm_user' => $is_pm_user,
            	'is_support_user' => $is_support_user
			);
		}

		$where = array('user_type' => $roleId,'is_delete' => 0);

		if ($this->common_model->update(LOGIN, $data, $where)) {
			return true;
		}else{
			return false;
		}

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : assignModuleCount
	 @Input  :
	 @Output :
	 @Date   : 19/01/2016
	 */
	public function assignModuleCount($role_id){

		$dataArr=array();
		$this->db->select('component_name,COUNT(*) AS ASSIGNED')->from(AAUTH_PERMS_TO_ROLE);
		$this->db->where('role_id =',$role_id);
		$this->db->group_by('component_name');
		$query = $this->db->get();
		$data = $query->result_array();

		foreach ($data as $dataz){

			$dataArr[$dataz['component_name']]=$dataz['ASSIGNED'];
		}
		return  $dataArr;
		//return  json_encode($dataArr);

	}

	public function updateTimeCheckUserAvailbility($role_id){

		$goAhead = 0;
		$checkFlag = array();
		$inArr = array();
		$diff = array();
		$getDiff = array();
		$getListOfAssignedModule = array();
		$checkForCompare = array();
		// Get List of Purchased User  with Assigned Modules
		$getPurchasedModuleCounts = getPurchasedModuleCounts();

		// Get Total Created User
		$table = LOGIN ;
		$match = "is_delete = 0 AND status=1 ";
		$fields = array("sum(is_crm_user) AS crm_user ,sum(is_pm_user) AS pm_user,sum(is_support_user) AS support_user ");
		$getTotalUserCreated = $this->common_model->get_records($table, $fields, '', '', $match);

		//	echo "Total Created :";
		//	pr($getTotalUserCreated);
		// Get Difference Diff = Purchased - Totalcreated
		foreach ($getPurchasedModuleCounts[0] as $a=>$b){
			foreach ($getTotalUserCreated[0] as $c=>$d){
				if($a==$c){
					if($d<=$b){
						$diff[$a] = (int)$b - (int)$d;
					}
				}
			}
		}
		//	echo "Difference :";
		//	pr($diff);
		// get List CheckSelected Role Assigned to User
		$getListOfAssignedModule = getListsofSelectedModules($role_id);
		//echo "Get List of Assigned Module";
		//pr($getListOfAssignedModule);
		if(!empty($getListOfAssignedModule)){
			// Role Edit time check

			// if Role has assigned User then get list of Assigned Module
			$havingModuleAssign =  getSelectedModules($role_id);
			//pr($getTotalUserCreated[0]);
			// get difference = total create - having
			foreach ($getTotalUserCreated[0] as $g=>$f){
				foreach ($havingModuleAssign as $i=>$t){
					if($g==$i){
						$getDiff[$g]= (int)$f - (int)$t;
					}
				}
			}
			$getDiff = array_merge($getTotalUserCreated[0],$getDiff);

			// checkforCompare = difference - having module lists
			foreach ($getDiff as $kys=>$vls){
				foreach ($havingModuleAssign as $m=>$j){
					if($kys == $m){
						$checkForCompare[$kys]=(int)$vls + (int)$j; // CheckForCompare = GetDiff + NewRole
					}
				}
			}
			$checkForCompare = array_merge($getDiff,$checkForCompare);

			foreach ($getPurchasedModuleCounts[0] as $o=>$p){
				foreach ($checkForCompare as $q=>$r){
					if($o == $q){
						if($p >= $r){
							$goAhead = 1;
						}else{
							$goAhead = 0;
							break;
						}
					}
				}
				if($goAhead == 0){
					break;
				}
			}
		}elseif(empty($getListOfAssignedModule)){

		 $arr = array("crm_user"=>1,"pm_user" =>1,"support_user" =>1);

		 foreach ($getTotalUserCreated[0] as $g=>$h){
		 	foreach ($arr as $e=>$f){
		 		if($g == $e){
		 			$checkForCompare[$e]=(int)$h + (int)$f;
		 		}
		 	}
		 }
		 $checkForCompare = array_merge($getTotalUserCreated[0],$checkForCompare);

		 foreach ($getPurchasedModuleCounts[0] as $x=>$y){
		 	foreach ($checkForCompare as $ke =>$vl){
		 		if($x == $ke){
		 			if($y >= $vl){
		 				$goAhead = 1;
		 			}else{
		 				$goAhead = 0;
		 				break;
		 			}
		 		}
		 	}
		 	if($goAhead == 0){
		 		break;
		 	}
		 }

		}

		return $goAhead;
	}

}
