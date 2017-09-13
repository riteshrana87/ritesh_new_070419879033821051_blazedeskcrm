<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct() {
		
		parent::__construct();
		if(checkPermission('User','view') == false)
           {
               redirect('/Dashboard');
           }
		$this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'Session'));
		
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Registration Index Page
	 @Input 	:
	 @Output	:
	 @Date   : 18/01/2016
	 */

	public function index() {
		
		
		$this->userlist();
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Registration insertdata
	 @Input 	:
	 @Output	:
	 @Date   : 18/01/2016
	 */

	public function insertdata() {

		$checkAvalibility = "";

		$main_user_data = $this->session->userdata('LOGGED_IN');
		$is_crm_user = 0;
		$is_pm_user = 0;
		$is_support_user = 0;
		$selectedUserType = "";

		$sess_array = array('setting_current_tab' => 'setting_user');
		$this->session->set_userdata($sess_array);

		if (!validateFormSecret()) {
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
			redirect($this->viewname . '/userlist'); //Redirect On Listing page
		}

		$duplicateName = $this->checkDuplicateEmail($this->input->post('email'));
		$getcount = count($duplicateName);

		if (isset($duplicateName) && empty($duplicateName) && $getcount == 0) {

			//$users = implode(",", $this->input->post('usertype'));


			$data['crnt_view'] = $this->viewname;
			//insert the user registration details into database
			//check User of CRM , PM, Support

			if ($this->input->post('user_of')) {

				$selectedUserType = $this->input->post('user_of');

				if (!empty($selectedUserType) && in_array("is_CRM_user", $selectedUserType)) {
					$is_crm_user = 1;
				}
				if (!empty($selectedUserType) && in_array("is_PM_user", $selectedUserType)) {
					$is_pm_user = 1;
				}
				if (!empty($selectedUserType) && in_array("is_Support_user", $selectedUserType)) {
					$is_support_user = 1;
				}
			}

			$data = array(
                'salution_prefix' => $this->input->post('salutions_prefix'),
                'firstname' => trim($this->input->post('fname')),
                'lastname' => trim($this->input->post('lname')),
                'email' => $this->input->post('email'),
                'password' => md5($this->input->post('password')),
                'address' => $this->input->post('address'),
                'address_1' => $this->input->post('address_1'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'pincode' => $this->input->post('pincode'),
                'country' => $this->input->post('country'),
                'telephone1' => $this->input->post('telephone1'),
                'telephone2' => $this->input->post('telephone2'),
                'user_type' => $this->input->post('usertype'),
                'parent_id' => $this->input->post('parent_id'),
                'is_crm_user' => $is_crm_user,
                'is_pm_user' => $is_pm_user,
                'is_support_user' => $is_support_user,
			//'user_type' => $users,
                'created_date' => datetimeformat(),
                'status' => $this->input->post('status')
			);

			if ($_FILES['profile_photo']['name'] != '' && !empty($_FILES)) {

				$tmp_file_name = "";
				$tmp_name_arr = explode('.', $_FILES['profile_photo']['name']);
				$tmp_file_name = $tmp_file_name . $tmp_name_arr[1];
				$profile_pic_new_name = time() . "_profile_photo." . end($tmp_name_arr);
				//$profile_pic_new_name = time() . "_" . $_FILES['profile_photo']['name'];
				$config['upload_path'] = PROFILE_PIC_UPLOAD_PATH;
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['file_name'] = $profile_pic_new_name;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('profile_photo', FALSE)) {
					$this->form_validation->set_message('checkdoc', $data['error'] = $this->upload->display_errors());

					if ($_FILES['profile_photo']['error'] != 4) {
						$msg = $this->upload->display_errors();
						$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
						return false;
					}
				} else {
					$upload = array('upload_data' => $this->upload->data());
					$thumbnail_img_name = $this->common_model->create_thumnail($upload, PROFILE_PIC_HEIGHT, PROFILE_PIC_WIDTH); //Create thumbnail


					$data['profile_photo'] = $profile_pic_new_name;
				}
			}

			// Check User created based on purchased limit is completed or not
			$checkAvalibility = $this->checkUserCreateLimit();
				
			//Insert Record in Database
			if($checkAvalibility == "false"){
				
				if ($this->common_model->insert(LOGIN, $data)) {
					// Update User Flag based on Role selected
					if($this->updateUserCreationCount($this->input->post('usertype'),$this->input->post('email'))){

						$table = EMAIL_TEMPLATE_MASTER . ' as et';
						$match = "et.template_id =52";
						$fields = array("et.subject,et.body");
						$template = $this->common_model->get_records($table, $fields, '', '', $match);

						$userName = $this->input->post('fname') . " " . $this->input->post('lname');
						$login_link = "<a href='" . base_url() . "/Masteradmin/login '>" . "Click Here" . "</a>";

						$body1 = str_replace("{LOGIN_URL}", $login_link, $template[0]['body']);

						$body2 = str_replace("{EMAIL}", $this->input->post('email'), $body1);

						$body3 = str_replace("{PASSWORD}", $this->input->post('password'), $body2);

						$to = $this->input->post('email');
						$body = str_replace("{USER_NAME}", $userName, $body3);
						$subject = $template[0]['subject'];

						send_mail($to, $subject, $body);

						$msg = $this->lang->line('user_add_msg');
						$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

					}
					//  redirect($this->viewname . '/userlist');
				} else {
					// error
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

					// redirect($this->viewname);
				}
				
			}else{
				$msg = $this->lang->line('user_limit_over');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

			}
				
		} else {
			$msg = $this->lang->line('duplicate_user_error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

			//redirect($this->viewname . '/userlist');
		}

			
		redirect('Settings/userSettings');
			
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : User list view Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */

	public function registration() {

		$form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
		if($form_secret != ""){
			$purchasedDetails = getListOfPurchasedModule();

			$data['crnt_view'] = $this->viewname;
			//Get Records From Login Table
			$table = LOGIN . ' as l';
			$match = "";
			$fields = array("l.login_id,l.salution_prefix, l.firstname, l.lastname, l.email, l.password, l.address,l.address_1,l.city,l.state,l.pincode,l.country, l.telephone1, l.telephone2, l.user_type, l.created_date, l.status");
			$data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);
			$data['userType'] = getUserType();
			//Pass Login Table Record In View
			$table1 = COUNTRIES . ' as cm';
			$fields1 = array("cm.country_name,cm.country_id");
			$data['country_data'] = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', '');
			$data['salution_list'] = $this->common_model->salution_list();
			$data['checkUserCreateLimit'] = $this->checkUserCreateLimit();
			$data['purchasedDetails'] = getListOfPurchasedModule();
			$data['main_content'] = '/registration';

			$this->load->view('registration', $data);
		} else {
				//Invalid secret key
				exit('No Direct scripts are allowed');
			}
		
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : User list view Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */

	public function userlist() {
		$addSearch = "";
		$moduleType = "";
		$data['crnt_view'] = $this->viewname;
		$this->load->library('pagination'); // Load Pagination library

		$addSearch = getSelectedModule($this->session->userdata['LOGGED_IN']['ID']);

		$configRole = $this->config->item('super_admin_role_id');
		if (isset($addSearch[0]['user_type']) && $configRole != $addSearch[0]['user_type']) {

			if (isset($addSearch[0]['is_crm_user']) && $addSearch[0]['is_crm_user'] == 1) {
				$moduleType .= " AND is_crm_user = 1 ";
			}
			if (isset($addSearch[0]['is_pm_user']) && $addSearch[0]['is_pm_user'] == 1) {
				$moduleType .= " AND is_pm_user = 1 ";
			}
			if (isset($addSearch[0]['is_support_user']) && $addSearch[0]['is_support_user'] == 1) {
				$moduleType .= " AND is_support_user = 1 ";
			}
		}

		//Get Records From Login Table
		$dbSearch = "";
		if ($this->input->get('search') != '') {

			if ($addSearch[0]['user_type']) {
				$data['search'] = $term = $this->input->get('search');

				if ($data['search'] == "active" || $data['search'] == "Active") {
					$term = 1;
					$dbSearch .= "l.status =" . $term . " AND  l.is_delete = 0" . $moduleType;
				} elseif ($data['search'] == "Inactive" || $data['search'] == "InActive" || $data['search'] == "inactive") {
					$term = 0;
					$dbSearch .= "l.status =" . $term . " AND  l.is_delete = 0" . $moduleType;
				} else {
					$searchFields = array("CONCAT(`firstname`,' ', `lastname`)", "l.firstname", "l.lastname", "l.email", "l.telephone1", "rm.role_name", "l.status");
					foreach ($searchFields as $fields):
					$dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
					endforeach;
					$dbSearch = substr($dbSearch, 0, -3);
					$dbSearch = "( " . $dbSearch . " ) AND  l.is_delete = 0" . $moduleType;
					
				}
			}else {
				$data['search'] = $term = $this->input->get('search');

				if ($data['search'] == "active" || $data['search'] == "Active") {
					$term = 1;
					$dbSearch .= "l.status =" . $term . " AND  l.is_delete = 0";
				} elseif ($data['search'] == "Inactive" || $data['search'] == "InActive" || $data['search'] == "inactive") {
					$term = 0;
					$dbSearch .= "l.status =" . $term . " AND  l.is_delete = 0";
				} else {
					$searchFields = array("CONCAT(`firstname`,' ', `lastname`)", "l.firstname", "l.lastname", "l.email", "l.telephone1", "rm.role_name", "l.status");
					foreach ($searchFields as $fields):
					$dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
					endforeach;
					$dbSearch = substr($dbSearch, 0, -3);
					$dbSearch = "( " . $dbSearch . " ) AND  l.is_delete = 0";
					
				}
			}
		}else {

			if (isset($addSearch[0]['user_type'])&& $addSearch[0]['user_type']) {
				$dbSearch = " l.is_delete = 0 " . $moduleType;
			} else {
				$dbSearch = " l.is_delete = 0";
			}
		}

		$params['join_tables'] = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
		$params['join_type'] = 'left';
		$fields = array("l.login_id,l.salution_prefix, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, l.email, l.password, l.address,l.address_1,l.city,l.state,l.pincode,l.country, l.telephone1, l.telephone2, rm.role_name As user_type, l.created_date, l.status,l.user_type as role_type");

		$config['total_rows'] = count($this->common_model->get_records(LOGIN . ' as l', $fields, $params['join_tables'], $params['join_type'], $dbSearch, ''));
		$config['base_url'] = site_url($data['crnt_view'] . '/index');
		$config['per_page'] = RECORD_PER_PAGE;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = floor($choice);
		$data['sortField'] = 'login_id';
		$data['sortOrder'] = 'desc';
		if ($this->input->get('orderField') != '') {
			$data['sortField'] = $this->input->get('orderField');
		}
		if ($this->input->get('sortOrder') != '') {
			$data['sortOrder'] = $this->input->get('sortOrder');
		}
		$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['information'] = $this->common_model->get_records(LOGIN . ' as l', $fields, $params['join_tables'], $params['join_type'], $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
		$page_url = $config['base_url'] . '/' . $data['page'];
		$data['drag'] = false;
		$data['pagination'] = $this->pagingConfig($config, $page_url);
		$data['status'] = array('1' => 'Paid', '0' => 'Unpaid');


		$data['main_content'] = '/userlist';

		$data['header'] = array('menu_module' => 'user');

		if ($this->input->is_ajax_request()) {
			$this->load->view($this->viewname . '/ajaxlist', $data);
		} else {
			$this->parser->parse('layouts/CMSTemplate', $data);
		}
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : UserList Edit Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */
	public function edit($id) {
		
		//Get Records From Login Table
		
		$form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
		$data['roleName']="";

		if($form_secret != ""){
				// Put your form submission code here after processing the form data, unset the secret key from the session /
				$this->session->unset_userdata('FORM_SECRET', '');
				$table = LOGIN . ' as l';
				$match = "l.login_id = " . $id;
				$fields = array("l.login_id,l.salution_prefix, l.firstname, l.lastname, l.email, l.password, l.address,l.address_1,l.city,l.state,l.pincode,l.country, l.telephone1, l.telephone2, l.user_type, l.created_date, l.status,l.profile_photo,l.is_crm_user,l.is_pm_user,l.is_support_user");
				$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
				$data['id'] = $id;
				$data['userType'] = getUserType();
				$table1 = COUNTRIES . ' as cm';
				$fields1 = array("cm.country_name,cm.country_id");
				$data['country_data'] = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', '');
				$data['salution_list'] = $this->common_model->salution_list();
				$data['purchasedDetails'] = getListOfPurchasedModule();
				$data['crnt_view'] = $this->viewname;
				$data['main_content'] = '/registration';
				if (isset($data['editRecord'][0]['user_type'])) {
					$roleName = getRoleName($data['editRecord'][0]['user_type']);
					$data['roleName'] = $roleName[0]['role_name'];
				}
				$this->load->view('registration', $data);
				
				
			} else {
				//Invalid secret key
				exit('No Direct scripts are allowed');
			}

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : User List Update Query
	 @Input 	: Post record from userlist
	 @Output	: Update data in database and redirect
	 @Date   : 12/01/2016
	 */

	public function updatedata() {
		$status = "";
		$usertype = "";
		
		
		if($this->input->post('status') == ""){
			$status = $this->input->post('selected_status');
		}else{
			$status = $this->input->post('status');
		}
		
		if($this->input->post('usertype') == ""){			
			$usertype = $this->input->post('role_selected_id'); 
		}else{
			$usertype =$this->input->post('usertype');			
		}
		

		$is_crm_user = 0;
		$is_pm_user = 0;
		$is_support_user = 0;
		$id = $this->input->post('login_id');
		$selectedUserType = "";
	
		//Get Records From Login Table
		$table = LOGIN . ' as l';
		$match = "l.login_id = " . $id;
		$fields = array("l.login_id,l.salution_prefix, l.firstname, l.lastname, l.email, l.password, l.address,l.address_1,l.city,l.state,l.pincode,l.country, l.telephone1, l.telephone2, l.user_type, l.created_date, l.status,l.profile_photo,l.is_crm_user,l.is_pm_user,l.is_support_user");
		$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
		$data['id'] = $id;
		$data['userType'] = getUserType();
		$data['crnt_view'] = $this->viewname;

		$sess_array = array('setting_current_tab' => 'setting_user');
		$this->session->set_userdata($sess_array);

		if ($this->input->post('user_of')) {

			$selectedUserType = $this->input->post('user_of');

			if (!empty($selectedUserType) && in_array("is_CRM_user", $selectedUserType)) {
				$is_crm_user = 1;
			}
			if (!empty($selectedUserType) && in_array("is_PM_user", $selectedUserType)) {
				$is_pm_user = 1;
			}
			if (!empty($selectedUserType) && in_array("is_Support_user", $selectedUserType)) {
				$is_support_user = 1;
			}
		}
		$data = array(
            'salution_prefix' => $this->input->post('salutions_prefix'),
            'firstname' => trim($this->input->post('fname')),
            'lastname' => trim($this->input->post('lname')),
            'email' => $this->input->post('email'),
		// 'password' => md5($this->input->post('password')),
            'address' => $this->input->post('address'),
            'address_1' => $this->input->post('address_1'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'pincode' => $this->input->post('pincode'),
            'country' => $this->input->post('country'),
            'telephone1' => $this->input->post('telephone1'),
            'telephone2' => $this->input->post('telephone2'),
            'user_type' => $usertype,
            'is_crm_user' => $is_crm_user,
            'is_pm_user' => $is_pm_user,
            'is_support_user' => $is_support_user,
            'updated_by' => $this->session->userdata['LOGGED_IN']['ID'],
            'created_date' => datetimeformat(),
            'status' => $status
		);
		
		if ($this->input->post('password') != "") {
			$data['password'] = md5($this->input->post('password'));
		}

		if ($_FILES['profile_photo']['name'] != '' && !empty($_FILES)) {
			$tmp_file_name = "";
			$tmp_name_arr = explode('.', $_FILES['profile_photo']['name']);
			$tmp_file_name = $tmp_file_name . $tmp_name_arr[1];
			$profile_pic_new_name = time() . "_profile_photo." . end($tmp_name_arr);
			//  $profile_pic_new_name = time()."_".$_FILES['profile_photo']['name'];
			$config['upload_path'] = PROFILE_PIC_UPLOAD_PATH;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['file_name'] = $profile_pic_new_name;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('profile_photo', FALSE)) {
				
				$this->form_validation->set_message('checkdoc', $data['error'] = $this->upload->display_errors());

				if ($_FILES['profile_photo']['error'] != 4) {
					$msg = $this->upload->display_errors();
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
					return false;
				}
			} else {
				$upload = array('upload_data' => $this->upload->data());
				$thumbnail_img_name = $this->common_model->create_thumnail($upload, PROFILE_PIC_HEIGHT, PROFILE_PIC_WIDTH); //Create thumbnail

				if ($_SESSION['LOGGED_IN']['ID'] == $id) {
					$_SESSION['LOGGED_IN']['PROFILE_PHOTO'] = $profile_pic_new_name;
				}

				// $this->session->set_userdata('LOGGED_IN', $sess_array);
				$data['profile_photo'] = $profile_pic_new_name;
			}
		}
	
		//Update Record in Database
		$where = array('login_id' => $id);

		// Update form data into database
		if ($this->common_model->update(LOGIN, $data, $where)) {

			if($this->updateUserCreationCount($usertype,$this->input->post('email'))){
				if($_SESSION['LOGGED_IN']['ID'] == $id)
				{
					$_SESSION['LOGGED_IN']['FIRSTNAME'] = $data['firstname'];
					$_SESSION['LOGGED_IN']['LASTNAME'] = $data['lastname'];
				}
				$msg = $this->lang->line('user_update_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
			}

			// redirect($this->viewname . '/userlist');
		} else {    // error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

			// redirect($this->viewname);
		}

		redirect('Settings/userSettings'); //Redirect On Listing page

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : User List Delete Query
	 @Input 	: Post id from List page
	 @Output	: Delete data from database and redirect
	 @Date   : 12/01/2016
	 */

	public function deletedata($id) {
	
		$sess_array = array('setting_current_tab' => 'setting_user');
		$this->session->set_userdata($sess_array);
		//Delete Record From Database
		if($this->session->userdata['LOGGED_IN']['ID'] != $id){ // Login User Should not been delete
			if (!empty($id)) {

				$data = array('is_delete' => 1);

				$where = array('login_id' => $id);

				if ($this->common_model->update(LOGIN, $data, $where)) {
					$msg = $this->lang->line('user_delete_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
					unset($id);
					//redirect($this->viewname . '/userlist'); //Redirect On Listing page
				} else {
					// error
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
						
					// redirect($this->viewname);
					// redirect($this->viewname . '/userlist'); //Redirect On Listing page
				}
			}
		}else{			
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			
		}
		
		redirect('Settings/userSettings');
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
	 @Desc   : check Duplicate name
	 @Input 	:
	 @Output	:
	 @Date   : 16/02/2016
	 */

	public function checkDuplicateEmail($email, $user_id = null) {

		$table = LOGIN . ' as l';
		if (NULL !== $user_id) {
			$match = "l.is_delete=0 AND l.email = '" . $email . "' and l.login_id <> '" . $user_id . "'";
		} else {
			$match = "l.email = '" . $email . "' AND l.is_delete=0 ";
		}
		$fields = array("l.login_id,l.status");
		$data['duplicateEmail'] = $this->common_model->get_records($table, $fields, '', '', $match);
		return $data['duplicateEmail'];
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : UserList Edit Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */

	public function view($id) {

		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
		
			//Get Records From Login Table
			$table = LOGIN . ' as l';
			$match = "l.login_id = " . $id;
			//$fields = array("l.login_id, l.firstname, l.lastname, l.email, l.password, l.address, l.telephone1, l.telephone2, l.user_type, l.created_date, l.status,l.profile_photo");
			$fields = array("l.login_id,l.salution_prefix, l.firstname, l.lastname, l.email, l.password, l.address,l.address_1,l.city,l.state,l.pincode,l.country, l.telephone1, l.telephone2, l.user_type, l.created_date, l.status,l.profile_photo,l.is_crm_user,l.is_pm_user,l.is_support_user");
			$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);

			if (isset($data['editRecord'][0]['salution_prefix'])) {
				$salution_data = $this->common_model->salution_name($data['editRecord'][0]['salution_prefix']);
			}
			$data['salution_prefix_name'] = $salution_data[0]['s_name'];
			$countryName = "";
			$data['countryName'] = "";
			if (isset($data['editRecord'][0]['country'])) {

				$countryName = getCountryName($data['editRecord'][0]['country']);
				if (isset($countryName[0]['country_name'])) {
					$data['countryName'] = $countryName[0]['country_name'];
				}

				if ($data['countryName'] != NULL) {
					$data['countryName'] = $data['countryName'];
				}
			}

			if (isset($data['editRecord'][0]['user_type'])) {
				$roleName = getRoleName($data['editRecord'][0]['user_type']);
				$data['roleName'] = $roleName[0]['role_name'];
			}
			
			$data['readonly'] = array("disabled" => "disabled");
			$data['id'] = $id;
			$table1 = COUNTRIES . ' as cm';
			$fields1 = array("cm.country_name,cm.country_id");
			$data['country_data'] = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', '');
			$data['salution_list'] = $this->common_model->salution_list();
			$data['userType'] = getUserType();
			$data['purchasedDetails'] = getListOfPurchasedModule();
			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/registration';
			$this->load->view('registration', $data);
			
		}

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : UserList Edit Page
	 @Input  :
	 @Output :
	 @Date   : 19/01/2016
	 */

	public function checkUserCreateLimit() {

		
		// Added by Ritesh Rana
		$main_user_id = $this->config->item('master_user_id');
		$count_user = "";
		$add = "";
		$modules = "";
		// Added by Ritesh Rana
		$table_user = LOGIN . ' as lg';
		$where_user = array("lg.login_id" => $main_user_id, "lg.is_delete" => 0, "lg.status" =>1);
		$fields_user = array("lg.login_id,lg.email,lg.parent_id");
		$check_user_data = $this->common_model->get_records($table_user, $fields_user, '', '', '', '', '', '', '', '', '', $where_user);

		// Added by Ritesh Rana
		$table = SETUP_MASTER . ' as ct';
	
		$where = "ct.login_id = " . $main_user_id . " AND ct.email = '" . $check_user_data[0]['email'] . "'";
		$fields = array("ct.total_user,ct.login_id,ct.support_user,ct.crm_user,ct.pm_user");
		$check_setup_user = $this->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where);

		//Add by Mehul Patel

		if(!empty($check_setup_user) && $check_setup_user[0]['support_user'] != 0 && $check_setup_user[0]['crm_user'] != 0 && $check_setup_user[0]['pm_user'] != 0){ // CRM+PM+Support
			$add = 3;
			$modules = "(sum(is_crm_user)+ sum(is_pm_user)+ sum(is_support_user))";
		}elseif (!empty($check_setup_user) && $check_setup_user[0]['crm_user'] != 0 && $check_setup_user[0]['pm_user'] == 0 && $check_setup_user[0]['support_user'] == 0){ //CRM
			$add = 1;
			$modules = "(sum(is_crm_user))";
		}elseif (!empty($check_setup_user) && $check_setup_user[0]['crm_user'] == 0 && $check_setup_user[0]['pm_user'] != 0 && $check_setup_user[0]['support_user'] == 0){ //PM
			$add = 1;
			$modules = "(sum(is_pm_user))";
		}elseif (!empty($check_setup_user) && $check_setup_user[0]['crm_user'] == 0 && $check_setup_user[0]['pm_user'] == 0 && $check_setup_user[0]['support_user'] != 0){ //Support
			$add = 1;
			$modules = "(sum(is_support_user))";
		}elseif (!empty($check_setup_user) && $check_setup_user[0]['crm_user'] != 0 && $check_setup_user[0]['pm_user']!= 0 && $check_setup_user[0]['support_user'] == 0){ //CRM+PM
			$add = 2;
			$modules = "(sum(is_crm_user)+ sum(is_pm_user))";
		}elseif (!empty($check_setup_user) && $check_setup_user[0]['crm_user'] == 0 && $check_setup_user[0]['pm_user']!= 0 && $check_setup_user[0]['support_user'] != 0){ //PM+SUPPORT
			$add = 2;
			$modules = "(sum(is_pm_user)+ sum(is_support_user))";
		}elseif (!empty($check_setup_user) && $check_setup_user[0]['crm_user'] != 0 && $check_setup_user[0]['pm_user']== 0 && $check_setup_user[0]['support_user'] != 0){ //CRM+SUPPORT
			$add = 2;
			$modules = "(sum(is_crm_user) + sum(is_support_user))";
		}

		// Added by Ritesh Rana
		$table = LOGIN . ' as lg';
		$where = array("lg.parent_id" => $main_user_id, "lg.is_delete" => 0, "lg.status" =>1);
		$fields = array("count(*) as createdUser");
		$check_user = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

		// Added by Mehul
		$table1 = LOGIN . ' as lg';
		$where1 = array("lg.parent_id" => $main_user_id, "lg.is_delete" => 0, "lg.status" =>1);
		$fields1 = array(" $modules AS total");
		$check_user1 = $this->common_model->get_records($table1, $fields1, '', '', '', '', '', '', '', '', '', $where1);

		if (isset($check_user[0]['createdUser'])) {
			$count_user = $check_user[0]['createdUser'] + 1;
		}

		// Added by Ritesh Rana
		if (isset($check_setup_user[0]['total_user'])) {
			if ($count_user >= (int) $check_setup_user[0]['total_user']) {
				$result = "true";
				return $result;

			}elseif (isset($check_user1[0]['total']) && (int) $check_setup_user[0]['total_user'] == $check_user1[0]['total']+$add){
				$result = "true";
				return $result;
					
			}elseif (isset($check_user1[0]['total']) && $check_user1[0]['total']+$add >= (int) $check_setup_user[0]['total_user']){
				$result = "true";
				return $result;
			}else {

				$result = "false";
				return $result;
			}
		}
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : check Duplicate name
	 @Input 	:
	 @Output	:
	 @Date   : 16/02/2016
	 */

	public function isDuplicateEmail() {
		$email = $this->input->post('emailID');
		$user_id = $this->input->post('userID');
		$table = LOGIN . ' as l';
		if (NULL !== $user_id && $user_id != "") {
			$match = "l.is_delete=0 AND l.email = '" . $email . "' and l.login_id <> '" . $user_id . "'";
		} else {
			$match = "l.email = '" . $email . "' AND l.is_delete=0 ";
		}

		$fields = array("l.login_id,l.status");
		$duplicateEmail = $this->common_model->get_records($table, $fields, '', '', $match);
		$count = count($duplicateEmail);
		if (isset($duplicateEmail) && empty($duplicateEmail) && $count == 0) {
			echo "true";
		} else {
			echo "false";
		}
		
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : checkUserCreateLimites
	 @Input  :
	 @Output :
	 @Date   : 19/01/2016
	 */

	public function checkCRMUserCreateLimites($is_user_of = NULL) {

		$getCountofCRMuser = "";

		$main_user_id = $this->config->item('master_user_id');
		$purchasedDetails = getListOfPurchasedModule();
		//	pr($purchasedDetails);
		$table = LOGIN . ' as lg';
		$where = array("lg.parent_id" => $main_user_id, "lg.is_delete" => 0, "lg.is_crm_user" => 1);
		$fields = array("count(*) as createdUser");
		$getCountofCRMuser = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

		if (isset($purchasedDetails[0]['is_crm']) && $purchasedDetails[0]['is_crm'] == 1 && $purchasedDetails[0]['crm_user'] > 0) {
			if ($getCountofCRMuser[0]['createdUser'] >= $purchasedDetails[0]['crm_user']) {
				echo lang('CRM_user_limit_over');
			} else {
				echo "true";
			}
		}
	}

	public function checkPMUserCreateLimites($is_user_of = NULL) {

		$getCountofPMuser = "";

		$main_user_id = $this->config->item('master_user_id');
		$purchasedDetails = getListOfPurchasedModule();

		$table = LOGIN . ' as lg';
		$where = array("lg.parent_id" => $main_user_id, "lg.is_delete" => 0, "lg.is_pm_user" => 1);
		$fields = array("count(*) as createdUser");
		$getCountofPMuser = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);


		if (isset($purchasedDetails[0]['is_pm']) && $purchasedDetails[0]['is_pm'] == 1 && $purchasedDetails[0]['pm_user'] > 0) {
			if ($getCountofPMuser[0]['createdUser'] >= $purchasedDetails[0]['pm_user']) {
				echo lang('PM_user_limit_over');
			} else {
				echo "true";
			}
		}
	}

	public function checkSupportUserCreateLimites($is_user_of = NULL) {


		$getCountofSupportuser = "";
		$main_user_id = $this->config->item('master_user_id');
		$purchasedDetails = getListOfPurchasedModule();

		$table = LOGIN . ' as lg';
		$where = array("lg.parent_id" => $main_user_id, "lg.is_delete" => 0, "lg.is_support_user" => 1);
		$fields = array("count(*) as createdUser");
		$getCountofSupportuser = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

		if (isset($purchasedDetails[0]['is_pm']) && $purchasedDetails[0]['is_pm'] == 1 && isset($purchasedDetails[0]['support_user']) && $purchasedDetails[0]['support_user'] > 0) {
			if ($getCountofSupportuser[0]['createdUser'] >= $purchasedDetails[0]['support_user']) {
				echo lang('Support_user_limit_over');
				exit;
			} else {
				echo "true";
			}
		}
	}

	public function testmail() {


		$configs = getMailConfig(); // Get Email configs from Email settigs page

		if (!empty($configs)) {

			$config['protocol'] = $configs['email_protocol'];
			$config['smtp_host'] = $configs['smtp_host']; //change this
			$config['smtp_port'] = $configs['smtp_port'];
			$config['smtp_user'] = $configs['smtp_user']; //change this
			$config['smtp_pass'] = $configs['smtp_pass']; //change this
			$config['mailtype'] = 'html';
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
			$config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
			$this->load->library('email', $config); // load email library
			$this->email->from($configs['smtp_user'], "CMS TEST");
			$this->email->to($to);
			$this->email->subject("Test Mail");
			$this->email->message("This is Test Mail please ignore the same");
			if (isset($attach) && $attach != "") {
				$this->email->attach($attach); // attach file /path/to/file1.png
			}
			if ($this->email->send()) {
				echo "Mail Sent";
				exit;
			} else {
				echo $this->email->print_debugger();
				exit;
			}
		} else {

			$where = "config_key='email'";
			$fromEmail = $this->common_model->get_records(CONFIG, array('value'), '', '', $where);
			if (isset($fromEmail[0]['value']) && !empty($fromEmail[0]['value'])) {
				$from_Email = $fromEmail[0]['value'];
			}
			$where1 = "config_key='project_name'";
			$projectName = $this->common_model->get_records(CONFIG, array('value'), '', '', $where1);
			if (isset($projectName[0]['value']) && !empty($projectName[0]['value'])) {
				$project_Name = $projectName[0]['value'];
			}

			$this->load->library('email');
			$config['protocol'] = 'sendmail';
			$config['mailpath'] = '/usr/sbin/sendmail';
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
			$this->email->initialize($config);
			$config['mailtype'] = "html";
			$this->email->initialize($config);
			$this->email->set_newline("\r\n");
			$this->email->from($from_Email, $project_Name);
			//$list = array('mehul.patel@c-metric.com');
			$this->email->to("mehul.patel@c-metric.com");
			$this->email->subject("Test Mail");
			$this->email->message("This is test Mail please ignore the same");
			if (isset($attach) && $attach != "") {
				$this->email->attach($attach); // attach file /path/to/file1.png
			}
			if ($this->email->send()) {
				echo "Mail sent";
				exit;
			} else {
				echo $this->email->print_debugger();
				exit;
			}
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
	/*
	 @Author : Mehul Patel
	 @Desc   : updateUserCreationCount
	 @Input  : Role,Email ID
	 @Output :
	 @Date   : 06/06/2016
	 */
	public function updateUserCreationCount($role_id,$email_id){

		$is_crm_user = 0;
		$is_pm_user = 0;
		$is_support_user = 0;
		$getModuleSelect = "";
		//$data = array();
		$getModuleSelect = $this->assignModuleCount($role_id);

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

		$where = array('email' => $email_id,'is_delete' => 0);

		if ($this->common_model->update(LOGIN, $data, $where)) {
			return true;
		}else{
			return false;
		}

	}

	// get Total count of create user for crm,PM,support

	public function getCountofCreatedUser(){
		
		if($_REQUEST['login_id']>0){ // Edit Time Check
			$this->checkUserCreateLimits();			
		}else{	// Add Time check
			$this->InsertTimeCheckUserCounts();
		}
	}
	// Get User Assigned module count with Purchased Module
	public function checkUserCounts(){		
		$this->checkUserCreateLimits();		
	}

	public function checkUserCreateLimits(){
		
		$status = $this->input->post('selected_status');
		//$flag = 0;
		$diff = array();
		// Selected RoleID
		$role_id = $this->input->post('role_id');

		//Preselected RoleID
		$selected_role_id = $this->input->post('selected_role_id');
		// Get Total Purchased User count
		$getPurchasedModuleCounts = getPurchasedModuleCounts();

		// Get User's Count
		$table1 = LOGIN ;
		$match1 = "is_delete = 0 ";
		$fields1 = array("count(*) CreatedUser");
		$getDefaultUserCount = $this->common_model->get_records($table1, $fields1, '', '', $match1);
	
		// Get User's Count
		$table = LOGIN ;
		$match = "is_delete = 0 AND status=1 ";
		$fields = array("sum(is_crm_user) AS crm_user ,sum(is_pm_user) AS pm_user,sum(is_support_user) AS support_user ");
		$getTotalUserCreated = $this->common_model->get_records($table, $fields, '', '', $match);
		
		$havingModuleAssign =  getListsofSelectedModules($role_id);

		//Preselected Role having Assigned Modules list
		$selected_Roles_having_module = getListsofSelectedModules($selected_role_id);

		foreach ($getPurchasedModuleCounts[0] as $keye => $vale){
			foreach ($getTotalUserCreated[0] as $keys=>$vals){
				if($keye === $keys){
					$diff[$keys] = (int)$vale - (int)$vals;
				}
			}
		}
		if(isset($getDefaultUserCount[0]['CreatedUser']) && $getDefaultUserCount[0]['CreatedUser'] !=1 ){
			// Check Availability
			if($status ==1){
				$aheadFlag = $this->updateTimecheckAvailability($getPurchasedModuleCounts,$getTotalUserCreated,$diff,$selected_Roles_having_module,$havingModuleAssign);
			}else{
				$aheadFlag = $this->insertTimecheckAvailability($getPurchasedModuleCounts,$getTotalUserCreated,$diff,$havingModuleAssign);
			}
			
			if($aheadFlag){
				echo "true";
				die();
			}else{
				echo lang('user_create_limit_over');
				die();
			}

		}elseif(isset($getDefaultUserCount[0]['CreatedUser']) && $getDefaultUserCount[0]['CreatedUser'] ==1 ){
			
			echo lang('super_admin_cannot_update');
			die();
				
		}
	}

	public function updateTimecheckAvailability($getPurchasedModuleCounts,$getTotalUserCreated,$diff,$selected_Roles_having_module,$havingModuleAssign){

		$goAhead = 0;
		$getDiff = array();
		$checkForCompare = array();		
		
		//$status = $this->input->post('selected_status');
				
		foreach ($getTotalUserCreated[0] as $k=>$v){
			foreach ($selected_Roles_having_module as $key=>$val){
				if($k === $key){
					$getDiff[$k] = (int)$v - (int)$val; // GetDiff = Total created - Pre existed 
				}
			}
		}		
		$getDiff = array_merge($getTotalUserCreated[0],$getDiff);
		foreach ($getDiff as $kys=>$vls){
			foreach ($havingModuleAssign as $i=>$j){
					
				if($kys == $i){
					$checkForCompare[$kys]=(int)$vls + (int)$j; // CheckForCompare = GetDiff + NewRole 
				}
			}
		}		
		$checkForCompare = array_merge($getDiff,$checkForCompare);	

		// Check here New Assigning Role should not Greater then Purchased Limit
		foreach ($getPurchasedModuleCounts[0] as $a=>$b){
			foreach ($checkForCompare as $c=>$d){
				if($a == $c){
					if($b >= $d){
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
		
		
		return $goAhead;
	}
	
	public function InsertTimeCheckUserCounts(){
		
		// Get Total Purchased User count
		$getPurchasedModuleCounts = getPurchasedModuleCounts();
		
		// Selected RoleID
		$role_id = $this->input->post('role_id');

		//get List of Selected Role Having Assigned Modules
		$havingModuleAssign =  getListsofSelectedModules($role_id);
	
		// Get User's Count
		$table = LOGIN ;
		$match = "is_delete = 0 AND status=1 ";
		$fields = array("sum(is_crm_user) AS crm_user ,sum(is_pm_user) AS pm_user,sum(is_support_user) AS support_user ");
		$getTotalUserCreated = $this->common_model->get_records($table, $fields, '', '', $match);

		foreach ($getPurchasedModuleCounts[0] as $keye => $vale){
			foreach ($getTotalUserCreated[0] as $keys=>$vals){
				if($keye === $keys){
					$diff[$keys] = (int)$vale - (int)$vals;
				}
			}
		}

		// Check Availablity
		$aheadFlag = $this->insertTimecheckAvailability($getPurchasedModuleCounts,$getTotalUserCreated,$diff,$havingModuleAssign);

		if($aheadFlag){
			echo "true";
			die();
		}else{
			echo lang('user_create_limit_over');
			die();
		}
	}
	
	public function insertTimecheckAvailability($getPurchasedModuleCounts,$getTotalUserCreated,$diff,$havingModuleAssign){
				
		$goAhead = 0;
		$checkForCompare = array();		
		foreach ($getTotalUserCreated[0] as $kys=>$vls){
			foreach ($havingModuleAssign as $i=>$j){
					
				if($kys == $i){
					$checkForCompare[$kys]=(int)$vls + (int)$j; // CheckForCompare = GetDiff + NewRole 
				}
			}
		}
		$checkForCompare = array_merge($getTotalUserCreated[0],$checkForCompare);	
		
		// Check here New Assigning Role should not Greater then Purchased Limit
		foreach ($getPurchasedModuleCounts[0] as $a=>$b){
			foreach ($checkForCompare as $c=>$d){
				if($a == $c){
					if($b >= $d){						
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
		return $goAhead;
				
	}
	
	
}
