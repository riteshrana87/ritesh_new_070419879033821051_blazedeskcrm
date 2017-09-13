<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SupportSettings extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		if(checkPermission('SupportSettings','view') == false)
           {
               redirect('/Dashboard');
           }
		$this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form','url'));
		$this->load->library(array('form_validation','Session', 'breadcrumbs'));

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings Index Page
	 @Input 	:
	 @Output	:
	 @Date   : 08/03/2016
	 */
	public function index()
	{	$this->breadcrumbs->push(lang('support'), 'Support');
     //   $this->breadcrumbs->push('Settings', 'SupportSettings');
        $this->breadcrumbs->push(lang('settings_support'), ' ');
		
		
		// Support Priority List
		$dataPriority = $this->settingslist();
	
		//Support Type List
		$dataType = $this->settingslistType();

		//Support Status List
		$dataStatus = $this->settingslistStatus();
			
			
		$data = array_merge($dataPriority, $dataType, $dataStatus);
		$data['crnt_view'] = $this->viewname;
		$data['header'] = array('menu_module'=>'support');
		$data['drag'] = false;
		/**
		 * tasks pagination starts
		 */
		$page_url = $data['config']['base_url'] . '/' . $data['page'];

		$page_url_type= $data['config1']['base_url'] . '/' . $data['typePage'];

		$page_url_status= $data['config1']['base_url'] . '/' . $data['statusPage'];

		$data['pagination'] = $this->pagingConfig($data['config'], $page_url);
		$data['paginationSales'] = $this->pagingConfigType($data['config1'], $page_url_type);
		$data['paginationStatus'] = $this->pagingConfigType($data['config2'], $page_url_status);
		$this->parser->parse('layouts/SupportTemplate', $data);

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings insertdata
	 @Input 	:
	 @Output	:
	 @Date   : 08/03/2016
	 */
	public function insertdata()
	{

		$data['crnt_view'] = $this->viewname;
		//insert the Support Settings add details into database
		if (!validateFormSecret()) {
		   $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
		   redirect($this->viewname); //Redirect On Listing page
		  }
		
			
		$data_priority = array(
				'priority' => $this->input->post('priority'),
				'status' => $this->input->post('status')
		);

		//Insert Record in Database

		if ($this->common_model->insert(SUPPORT_PRIORITY,$data_priority))
		{
			$msg = $this->lang->line('support_settings_update_add_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
			redirect($this->viewname);

		}
		else
		{
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");

			redirect($this->viewname);
		}

	}


	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings insertdata
	 @Input 	:
	 @Output	:
	 @Date   : 08/03/2016
	 */
	public function insertdataType()
	{

		$data['crnt_view'] = $this->viewname;
		//insert the Support Settings add details into database
		 if (!validateFormSecret()) {
		   $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
		   redirect($this->viewname); //Redirect On Listing page
		  }
			
		$data_priority = array(
				'type' => $this->input->post('type'),
				'status' => $this->input->post('status')
		);

		//Insert Record in Database

		if ($this->common_model->insert(SUPPORT_TYPE,$data_priority))
		{
			$msg = $this->lang->line('support_settings_update_add_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
			redirect($this->viewname);

		}
		else
		{
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");

			redirect($this->viewname);
		}

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings status
	 @Input 	:
	 @Output	:
	 @Date   : 10/03/2016
	 */
	public function insertdataStatus(){

		$data['crnt_view'] = $this->viewname;
		if (!validateFormSecret()) {
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
			redirect($this->viewname); //Redirect On Listing page
		}
		$data_status['font_awesome_data']   = $this->get_font_awesome_icon();
		$data_status = array(
				'status_name' => ucfirst($this->input->post ('status_name')),
				'status_color'=>$this->input->post ('status_color'),
				'status_font_icon' =>$this->input->post ('status_font_icon'),
				'created_date'=>datetimeformat()			
		);
		// Insert Record into Database
		if ($this->common_model->insert(SUPPORT_STATUS,$data_status))
		{
			$msg = $this->lang->line('support_settings_update_add_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
			redirect($this->viewname);

		}
	}


	/*
	 @Author : Mehul Patel
	 @Desc   : Add Support Priority view Page
	 @Input 	:
	 @Output	:
	 @Date   : 17/02/2016
	 */
	public function add()
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$data['crnt_view'] = $this->viewname;
			//Get Records From COUNTRIES Table
				
			$data['main_content'] = '/add';
			$this->load->view('add',$data);
			//$this->parser->parse('layouts/CMSTemplate', $data);
		}

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Add Support Priority view Page
	 @Input 	:
	 @Output	:
	 @Date   : 17/02/2016
	 */
	public function addType()	
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$data['crnt_view'] = $this->viewname;

			$data['main_content'] = '/addType';
			$this->load->view('addType',$data);
			//$this->parser->parse('layouts/CMSTemplate', $data);
		}

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Add Support Priority view Page
	 @Input 	:
	 @Output	:
	 @Date   : 17/02/2016
	 */
	public function addStatus(){

		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
				
			$data['crnt_view'] = $this->viewname;
			//Get Records From COUNTRIES Table

			$data['modal_title']         = 'Create New Status';
			$data['submit_button_title'] = 'Create Status';
			//get font awesome
			$data['font_awesome_data']   = $this->get_font_awesome_icon();
			$data['main_content'] = '/addStatus';
			$this->load->view('addStatus',$data);
		}

	}


	/*
	 @Author : Mehul Patel
	 @Desc   : Support Priority view Page
	 @Input 	:
	 @Output	:
	 @Date   : 08/03/2016
	 */
	public function settingslist()
	{
		$this->load->library('pagination');

		$data['crnt_view'] = $this->viewname;
		//Get Records From Login Table
		$dbSearch = " sp.is_delete = 0";

		$fields = array("sp.support_priority_id,sp.priority,sp.status");
		$config['total_rows'] = count($this->common_model->get_records(SUPPORT_PRIORITY . ' as sp', $fields,'', '', $dbSearch, ''));
		$config['base_url'] = site_url($data['crnt_view'] . '/taskAjaxList');
		$config['per_page'] = RECORD_PER_PAGE;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = floor($choice);
		$data['sortField'] = 'support_priority_id';
		$data['sortOrder'] = 'desc';
		if ($this->input->get('orderField') != '') {
			$data['sortField'] = $this->input->get('orderField');
		}
		if ($this->input->get('sortOrder') != '') {
			$data['sortOrder'] = $this->input->get('sortOrder');
		}
		$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['information'] = $this->common_model->get_records(SUPPORT_PRIORITY . ' as sp', $fields, '', '', $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
		$page_url = $config['base_url'] . '/' . $data['page'];
			
		$data['pagination'] = $this->pagingConfig($config, $page_url);
		$data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
		$data['main_content'] = '/settingslist';
		$data['header'] = array('menu_module'=>'support');
		
		$data['config'] = $config;
		return $data;
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Support Type view Page
	 @Input 	:
	 @Output	:
	 @Date   : 08/03/2016
	 */
	public function settingslistType()
	{

		$data['crnt_view'] = $this->viewname;
		$this->load->library('pagination'); // Load Pagination library
		//Get Records From Login Table
		$dbSearch = " st.is_delete = 0";

		$fields = array("st.support_type_id	,st.type,st.status");
		$config1['total_rows'] = count($this->common_model->get_records(SUPPORT_TYPE . ' as st', $fields,'', '', $dbSearch, ''));
		$config1['base_url'] = site_url($data['crnt_view'] . '/typeAjaxList');
		$config1['per_page'] = RECORD_PER_PAGE;
		$choice = $config1["total_rows"] / $config1["per_page"];
		$config1["num_links"] = floor($choice);
		$data['typesortField'] = 'support_type_id';
		$data['typesortOrder'] = 'desc';
		if ($this->input->get('orderType') != '') {
			$data['typesortField'] = $this->input->get('orderType');
		}
		if ($this->input->get('sortOrder') != '') {
			$data['typesortOrder'] = $this->input->get('sortOrder');
		}
		$data['typePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['information_type'] = $this->common_model->get_records(SUPPORT_TYPE . ' as st', $fields, '', '', $dbSearch, '', $config1['per_page'], $data['typePage'], $data['typesortField'], $data['typesortOrder']);
		 
		$page_url = $config1['base_url'] . '/' . $data['typePage'];
			
		$data['pagination'] = $this->pagingConfigType($config1, $page_url);
		$data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
		$data['main_content'] = '/settingslist';
		$data['header'] = array('menu_module'=>'support');
		$data['config1'] = $config1;

		return $data;

	}


	/*
	 @Author : Mehul Patel
	 @Desc   : Support status view Page
	 @Input 	:
	 @Output	:
	 @Date   : 10/03/2016
	 */
	public function settingslistStatus()
	{

		$data['crnt_view'] = $this->viewname;
		$this->load->library('pagination'); // Load Pagination library
		//Get Records From Login Table
		$dbSearch = " ss.is_delete = 0";

		$fields = array("ss.status_id,ss.status_name,ss.status_color,ss.status_font_icon");
		$config2['total_rows'] = count($this->common_model->get_records(SUPPORT_STATUS . ' as ss', $fields,'', '', $dbSearch, ''));
		$config2['base_url'] = site_url($data['crnt_view'] . '/statusAjaxList');
		$config2['per_page'] = RECORD_PER_PAGE;
		$choice = $config2["total_rows"] / $config2["per_page"];
		$config2["num_links"] = floor($choice);
		$data['statussortField'] = 'status_id';
		$data['statussortOrder'] = 'desc';
		if ($this->input->get('orderStatus') != '') {
			$data['statussortField'] = $this->input->get('orderStatus');
		}
		if ($this->input->get('sortOrder') != '') {
			$data['statussortOrder'] = $this->input->get('sortOrder');
		}
		$data['statusPage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['information_status'] = $this->common_model->get_records(SUPPORT_STATUS . ' as ss', $fields, '', '', $dbSearch, '', $config2['per_page'], $data['statusPage'], $data['statussortField'], $data['statussortOrder']);
		 
		$page_url = $config2['base_url'] . '/' . $data['statusPage'];
			
		$data['pagination'] = $this->pagingConfigType($config2, $page_url);
		$data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
		$data['main_content'] = '/settingslist';
		$data['header'] = array('menu_module'=>'support');
		$data['config2'] = $config2;

		return $data;

	}


	/*
	 @Author : Mehul Patel
	 @Desc   :Support Settings priority Edit Page
	 @Input  :
	 @Output :
	 @Date   : 08/03/2016
	 */
	public function edit($id)
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$table = SUPPORT_PRIORITY . ' as sp';
			$match 	= "sp.support_priority_id = ".$id;
			$fields = array("sp.support_priority_id,sp.priority,sp.status");
			$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
			$data['id'] = $id;
			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/add';
			$this->load->view('add', $data);
		}


		// $this->parser->parse('layouts/CMSTemplate', $data);
	}
	/*
	 @Author : Mehul Patel
	 @Desc   :Support Settings type Edit Page
	 @Input  :
	 @Output :
	 @Date   : 08/03/2016
	 */
	public function editType($id)
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$table = SUPPORT_TYPE . ' as st';
			$match 	= "st.support_type_id = ".$id;
			$fields = array("st.support_type_id,st.type,st.status");
			$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
			$data['id'] = $id;
			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/addType';
			$this->load->view('addType', $data);
		}



		// $this->parser->parse('layouts/CMSTemplate', $data);
	}
	/*
	 @Author : Mehul Patel
	 @Desc   :Support Settings status Edit Page
	 @Input  :
	 @Output :
	 @Date   : 10/03/2016
	 */
	public function editStatus($id)
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$table = SUPPORT_STATUS . ' as ss';
			$match 	= "ss.status_id = ".$id;
			$fields = array("ss.status_id,ss.status_name,ss.status_color,ss.status_font_icon");
			$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
			$data['font_awesome_data']   = $this->get_font_awesome_icon();
			$data['id'] = $id;
			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/addStatus';
			$this->load->view('addStatus', $data);
		}

	}


	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings View Page
	 @Input  :
	 @Output :
	 @Date   : 08/03/2016
	 */
	public function view($id)
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$table = SUPPORT_PRIORITY . ' as sp';
			$match 	= "sp.support_priority_id = ".$id;
			$fields = array("sp.support_priority_id,sp.priority,sp.status");
			$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
			$data['id'] = $id;
			$data['readonly']  = array("disabled"=>"disabled");
			//$data['id'] = $id;

			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/add';
			$this->load->view('add', $data);
			// $this->parser->parse('layouts/CMSTemplate', $data);
		}

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings type View Page
	 @Input  :
	 @Output :
	 @Date   : 08/03/2016
	 */
	public function viewType($id)
	{

		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$table = SUPPORT_TYPE . ' as st';
			$match 	= "st.support_type_id = ".$id;
			$fields = array("st.support_type_id,st.type,st.status");
			$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
			$data['id'] = $id;
			$data['readonly']  = array("disabled"=>"disabled");
			//$data['id'] = $id;

			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/addType';
			$this->load->view('addType', $data);
			// $this->parser->parse('layouts/CMSTemplate', $data);
		}

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings status View Page
	 @Input  :
	 @Output :
	 @Date   : 08/03/2016
	 */
	public function viewStatus($id)
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$table = SUPPORT_STATUS . ' as ss';
		$match 	= "ss.status_id = ".$id;
		$fields = array("ss.status_id,ss.status_name,ss.status_color,ss.status_font_icon");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;
		$data['readonly']  = array("disabled"=>"disabled");
		//$data['id'] = $id;
		 $data['font_awesome_data']   = $this->get_font_awesome_icon();
		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/addStatus';
		$this->load->view('addStatus', $data);
		// $this->parser->parse('layouts/CMSTemplate', $data);
		}
		
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings Update Query
	 @Input  : Post record from userlist
	 @Output : Update data in database and redirect
	 @Date   : 17/02/2016
	 */
	public function updatedata()
	{
		$data['crnt_view'] = $this->viewname;
		$id = $this->input->post('support_priority_id');

		$table = SUPPORT_TYPE . ' as st';
		$match 	= "st.support_type_id = ".$id;
		$fields = array("st.support_type_id,st.type,st.status");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;
		
		$where = array('support_priority_id' => $id);

		$data_priority = array(
				'priority' => $this->input->post('priority'),
			 	'status' => $this->input->post('status')
		);

		// Update form data into database
		if ($this->common_model->update(SUPPORT_PRIORITY,$data_priority, $where))
		{
			//$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Updated! </div>');
			$msg = $this->lang->line('support_settings_update_update_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
			redirect($this->viewname);

		}
		else
		{
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
			//redirect('user/register');
			redirect($this->viewname);
		}

		redirect($this->viewname);	//Redirect On Listing page
		//}
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings Update Query
	 @Input  : Post record from userlist
	 @Output : Update data in database and redirect
	 @Date   : 17/02/2016
	 */

	public function updatedataType()
	{
		$data['crnt_view'] = $this->viewname;
		$id = $this->input->post('support_type_id');

		$table = SUPPORT_TYPE . ' as st';
		$match 	= "st.support_type_id = ".$id;
		$fields = array("st.support_type_id,st.type,st.status");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;

		$where = array('support_type_id' => $id);

		$data_priority = array(
				'type' => $this->input->post('type'),
			 	'status' => $this->input->post('status')
		);

		// Update form data into database
		if ($this->common_model->update(SUPPORT_TYPE,$data_priority, $where))
		{
			//$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Updated! </div>');
			$msg = $this->lang->line('support_settings_update_update_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
			redirect($this->viewname);

		}
		else
		{
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
			//redirect('user/register');
			redirect($this->viewname);
		}

		redirect($this->viewname);	//Redirect On Listing page
		//}
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings status Update Query
	 @Input  : Post record from settinglist
	 @Output : Update data in database and redirect
	 @Date   : 10/03/2016
	 */

	public function updatedataStatus()
	{
		$data['crnt_view'] = $this->viewname;
		$id = $this->input->post('status_id');

		$table = SUPPORT_STATUS . ' as ss';
		$match 	= "ss.status_id = ".$id;
		$fields = array("ss.status_id,ss.status_name,ss.status_color,ss.status_font_icon");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;

		$where = array('status_id' => $id);

		$data_status = array(
				'status_name' => ucfirst($this->input->post ('status_name')),
				'status_color'=>$this->input->post ('status_color'),
				'status_font_icon' =>$this->input->post ('status_font_icon'),
				'modified_date'=>datetimeformat()			
		);

		// Update form data into database
		if ($this->common_model->update(SUPPORT_STATUS,$data_status, $where))
		{
			//$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Updated! </div>');
			$msg = $this->lang->line('support_settings_update_update_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
			redirect($this->viewname);

		}
		else
		{
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
			//redirect('user/register');
			redirect($this->viewname);
		}

		redirect($this->viewname);	//Redirect On Listing page
		//}
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings list Delete Query
	 @Input  : Post id from List page
	 @Output : Delete data from database and redirect
	 @Date   : 08/03/2016
	 */
	public function deletedataPriority($id)
	{
		$data['crnt_view'] = $this->viewname;
		$checkAssinedPriority = $this->checkPriorityIsAssigned($id);
		if($checkAssinedPriority['priority_assigned']==0){
			if(!empty($id))
			{

				$data_priority = array('is_delete'=>1);

				$where = array('support_priority_id' => $id);
				//  $this->common_model->delete(LOGIN,$where);


				if($this->common_model->update(SUPPORT_PRIORITY,$data_priority,$where)){

					$msg = $this->lang->line('support_settings_update_delete_msg');
					$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
					unset($id);
					redirect($this->viewname);	//Redirect On Listing page

				}else{
					// error
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
					//redirect('user/register');
					redirect($this->viewname);
					redirect($this->viewname.'/settingslist');	//Redirect On Listing page
				}


			}

		}else{
			// error
			$msg = $this->lang->line('assigned_Priority_delete');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
			//redirect('user/register');
			redirect($this->viewname);
			//	redirect($this->viewname.'/settingslist');	//Redirect On Listing page
		}

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings list Delete Query
	 @Input  : Post id from List page
	 @Output : Delete data from database and redirect
	 @Date   : 08/03/2016
	 */
	public function deletedataType($id)
	{
		$data['crnt_view'] = $this->viewname;
		$checkTypeIsAssigned = $this->checkTypeIsAssigned($id);
		
		if($checkTypeIsAssigned['type_assigned']==0){
			if(!empty($id))
			{

				$data_priority = array('is_delete'=>1);

				$where = array('support_type_id' => $id);
				//  $this->common_model->delete(LOGIN,$where);


				if($this->common_model->update(SUPPORT_TYPE,$data_priority,$where)){

					$msg = $this->lang->line('support_settings_update_delete_msg');
					$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
					unset($id);
					redirect($this->viewname);	//Redirect On Listing page

				}else{
					// error
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
					//redirect('user/register');
					redirect($this->viewname);
					//	redirect($this->viewname.'/settingslist');	//Redirect On Listing page
				}


			}
		}else{
			
				$msg = $this->lang->line('assigned_type_delete');
				$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
				redirect($this->viewname);
			
		}
		

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Support Settings list Delete Query
	 @Input  : Post id from List page
	 @Output : Delete data from database and redirect
	 @Date   : 08/03/2016
	 */
	public function deletedataStatus($id)
	{

		$data['crnt_view'] = $this->viewname;

		$checkStatusIsAssigned = $this->checkStatusIsAssigned($id);
	
		if($checkStatusIsAssigned['status_assigned']==0 ){

			if(!empty($id))
			{

				$data_status = array('is_delete'=>1);

				$where = array('status_id' => $id);
				//  $this->common_model->delete(LOGIN,$where);


				if($this->common_model->update(SUPPORT_STATUS,$data_status,$where)){

					$msg = $this->lang->line('support_settings_update_delete_msg');
					$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
					unset($id);
					redirect($this->viewname);	//Redirect On Listing page

				}else{
					// error
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
					//redirect('user/register');
					redirect($this->viewname);
					//	redirect($this->viewname.'/settingslist');	//Redirect On Listing page
				}


			}

		}else{
			
				$msg = $this->lang->line('assigned_status_delete');
				$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
					//redirect('user/register');
				redirect($this->viewname);
			
		}


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
	 @Desc   : Common pagination initialization
	 @Input 	:
	 @Output	:
	 @Date   : 01/02/2016
	 */
	private function pagingConfigType($config, $page_url) {

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
	 @Desc   : Priority list
	 @Input  :
	 @Output :
	 @Date   : 10/03/2016
	 */
	public function taskAjaxList() {
			
		$data = $this->settingslist();
		$data['crnt_view'] = $this->viewname;
			
		$page_url = $data['config']['base_url'] . '/' . $data['page'];
		$data['pagination'] = $this->pagingConfig($data['config'], $page_url);

		$this->load->view('ajaxlist', $data);
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Settings Type list
	 @Input  :
	 @Output :
	 @Date   : 10/03/2016
	 */
	public function typeAjaxList() {
			
		$data = $this->settingslistType();
		$data['crnt_view'] = $this->viewname;
			
		$page_url = $data['config1']['base_url'] . '/' . $data['typePage'];
		$data['paginationSales'] = $this->pagingConfig($data['config1'], $page_url);

		$this->load->view('type_ajaxlist', $data);
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Settings Status list
	 @Input  :
	 @Output :
	 @Date   : 10/03/2016
	 */
	public function statusAjaxList() {
			
		$data = $this->settingslistStatus();
		$data['crnt_view'] = $this->viewname;
			
		$page_url = $data['config2']['base_url'] . '/' . $data['statusPage'];
		$data['paginationStatus'] = $this->pagingConfig($data['config2'], $page_url);

		$this->load->view('status_ajaxlist', $data);
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : checkStatusIsAssigned
	 @Input  :
	 @Output :
	 @Date   : 31/03/2016
	 */
	public function checkStatusIsAssigned($id) {
		$table = TICKET_MASTER . ' as tm';
		$match = "tm.status = " . $id." AND tm.is_delete = 0";
		$fields = array(" COUNT(tm.status) AS status_assigned ");
		$status = $this->common_model->get_records($table, $fields, '', '', $match);		
		return $status[0];
	}
/*
	 @Author : Mehul Patel
	 @Desc   : checkTypeIsAssigned
	 @Input  :
	 @Output :
	 @Date   : 31/03/2016
	 */
	public function checkTypeIsAssigned($id) {
		$table = TICKET_MASTER . ' as tm';
		$match = "tm.type = " . $id." AND tm.is_delete = 0";
		$fields = array(" COUNT(tm.type) AS type_assigned ");
		$type = $this->common_model->get_records($table, $fields, '', '', $match);		
		return $type[0];
	}
/*
	 @Author : Mehul Patel
	 @Desc   : checkPriorityIsAssigned
	 @Input  :
	 @Output :
	 @Date   : 31/03/2016
	 */
	public function checkPriorityIsAssigned($id) {
		$table = TICKET_MASTER . ' as tm';
		$match = "tm.priority = " . $id." AND tm.is_delete = 0";
		$fields = array(" COUNT(tm.priority) AS priority_assigned ");
		$type = $this->common_model->get_records($table, $fields, '', '', $match);		
		return $type[0];
	}
 /*
      @Author : Niral Patel
      @Desc   : get font awesome icon
      @Input  : file
      @Output : 
      @Date   : 17/03/2016
     */
    function get_font_awesome_icon()
    {
        $str = file_get_contents($this->config->item('project_upload_base_url').'ProjectStatus/'.'font-awesome.json');
      
        return $json = json_decode($str, true); // decode the JSON into an associative array
    }
}
