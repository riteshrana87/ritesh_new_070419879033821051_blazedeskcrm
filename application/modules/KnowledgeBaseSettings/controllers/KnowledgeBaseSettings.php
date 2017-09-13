<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class KnowledgeBaseSettings extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		if(checkPermission('KnowledgeBaseSettings','view') == false)
           {
               redirect('/Dashboard');
           }
		$this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form','url'));
		$this->load->library(array('form_validation','Session', 'breadcrumbs'));
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : knowledgeBaseSettings Index Page
	 @Input 	:
	 @Output	:
	 @Date   : 16/03/2016
	 */
	public function index()
	{	$this->breadcrumbs->push(lang('support'), 'Support');
        $this->breadcrumbs->push('Settings', 'KnowledgeBaseSettings');
        $this->breadcrumbs->push('KnowledgeBase Settings', ' ');
		$this->knowledgeBaseSettingsList();
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : knowledgeBaseSettings insertdata
	 @Input 	:
	 @Output	:
	 @Date   : 16/03/2016
	 */
	public function insertdata()
	{

		$data['crnt_view'] = $this->viewname;
		//insert the Support Settings add details into database

			
		$data_priority = array(
				'type' => $this->input->post('type'),
				'status' => $this->input->post('status')
		);

		//Insert Record in Database

		if ($this->common_model->insert(KNOWLEDGEBASE_BASE_SETTINGS_TYPE,$data_priority))
		{
			$msg = $this->lang->line('knowledgeBaseSettings_add_msg');
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
	 @Desc   : View knowledgeBaseSettingsList
	 @Input  :
	 @Output :
	 @Date   : 27/01/2016
	 */

	public function view($id) {

		$table = KNOWLEDGEBASE_BASE_SETTINGS_TYPE . ' as kbs';
		$match 	= "kbs.type_id = ".$id;
		$fields = array("kbs.type_id,kbs.type,kbs.status");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;
		$data['readonly']  = array("disabled"=>"disabled");
		//$data['id'] = $id;

		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/knowledgeBaseSettings';
		$this->load->view('knowledgeBaseSettings', $data);
		// $this->parser->parse('layouts/CMSTemplate', $data);
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : EstimateSettings add  Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */
	public function add()
	{
		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/knowledgeBaseSettings';
		$this->load->view('knowledgeBaseSettings',$data);
	}


	/*
	 @Author : Mehul Patel
	 @Desc   : knowledgeBaseSettingsList view Page
	 @Input  :
	 @Output :
	 @Date   : 16/02/2016
	 */
	public function knowledgeBaseSettingsList()
	{
		$data['crnt_view'] = $this->viewname;
		$this->load->library('pagination'); // Load Pagination library 
		//Get Records From Login Table
		$dbSearch = "";
		if ($this->input->get('search') != '') {
			$data['search'] = $term = $this->input->get('search');	

			if($data['search'] == "active" || $data['search'] == "Active"){
				$term = 1;
				$dbSearch .= "kbs.status =".$term. " AND  kbs.is_delete = 0";
			}elseif($data['search'] == "Inactive" || $data['search'] == "InActive" || $data['search'] == "inactive"){
				$term = 0;
				$dbSearch .= "kbs.status =".$term. " AND  kbs.is_delete = 0";
			}else{
				$searchFields = array("kbs.type","kbs.status");
				foreach ($searchFields as $fields):
				$dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
				endforeach;
				$dbSearch = substr($dbSearch, 0, -3);
				$dbSearch = "( ".$dbSearch." ) AND  kbs.is_delete = 0";
			}
		}else{
			
			$dbSearch = " kbs.is_delete = 0";
		}
		
	    $fields = array("kbs.type_id","kbs.type,kbs.status");
		$config['total_rows'] = count($this->common_model->get_records(KNOWLEDGEBASE_BASE_SETTINGS_TYPE . ' as kbs', $fields,'', '', $dbSearch, ''));
        $config['base_url'] = site_url($data['crnt_view'] . '/index');
        $config['per_page'] = RECORD_PER_PAGE;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        $data['sortField'] = 'type_id';
        $data['sortOrder'] = 'desc';
        if ($this->input->get('orderField') != '') {
            $data['sortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['sortOrder'] = $this->input->get('sortOrder');
        }
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['information'] = $this->common_model->get_records(KNOWLEDGEBASE_BASE_SETTINGS_TYPE . ' as kbs', $fields, '', '', $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
        $page_url = $config['base_url'] . '/' . $data['page'];
     
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        $data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
		$data['drag'] = false;
        $data['main_content'] = '/knowledgeBaseSettingslist';        
		$data['header'] = array('menu_module'=>'support');
        if ($this->input->is_ajax_request()) {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
           $this->parser->parse('layouts/SupportTemplate', $data);
        }
		
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : knowledgeBaseSettingsList Edit Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */
	public function edit($id)
	{
		$table = KNOWLEDGEBASE_BASE_SETTINGS_TYPE . ' as kbs';
		$match 	= "kbs.type_id = ".$id;
		$fields = array("kbs.type_id,kbs.type,kbs.status");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;
		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/knowledgeBaseSettings';
		$this->load->view('knowledgeBaseSettings', $data);

		// $this->parser->parse('layouts/CMSTemplate', $data);
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : knowledgeBaseSettingsList List Update Query
	 @Input 	: Post record from templatelist
	 @Output	: Update data in database and redirect
	 @Date   : 12/01/2016
	 */
	public function updatedata()
	{
		$data['crnt_view'] = $this->viewname;
		$id = $this->input->post('type_id');

		$table = KNOWLEDGEBASE_BASE_SETTINGS_TYPE . ' as kbs';
		$match 	= "kbs.type_id = ".$id;
		$fields = array("kbs.type_id,kbs.type,kbs.status");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;

		$where = array('type_id' => $id);

		$data_priority = array(
				'type' => $this->input->post('type'),
			 	'status' => $this->input->post('status')
		);
	
		// Update form data into database
		if ($this->common_model->update(KNOWLEDGEBASE_BASE_SETTINGS_TYPE,$data_priority, $where))
		{
			//$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Updated! </div>');
			$msg = $this->lang->line('knowledgeBaseSettings_update_msg');
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

		//redirect($this->viewname);	//Redirect On Listing page
		//}
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : knowledgeBaseSettingsList List Delete Query
	 @Input 	: Post id from List page
	 @Output	: Delete data from database and redirect
	 @Date   : 12/01/2016
	 */
	public function deletedata($id)
	{

		$data['crnt_view'] = $this->viewname;

		if(!empty($id))
		{

			$data_priority = array('is_delete'=>1);

			$where = array('type_id' => $id);
			//  $this->common_model->delete(LOGIN,$where);


			if($this->common_model->update(KNOWLEDGEBASE_BASE_SETTINGS_TYPE,$data_priority,$where)){

				$msg = $this->lang->line('knowledgeBaseSettings_update_delete_msg');
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
	
	

}
