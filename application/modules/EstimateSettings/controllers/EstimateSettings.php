<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EstimateSettings extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		if(checkPermission('EstimateSettings','view') == false)
           {
               redirect('/Dashboard');
           }	
		$this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form','url'));
		$this->load->library(array('form_validation','Session', 'breadcrumbs'));
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : EstimateSettings Index Page
	 @Input 	:
	 @Output	:
	 @Date   : 14/03/2016
	 */
	public function index()
	{	
		$this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push('Settings', 'EstimateSettings');
        $this->breadcrumbs->push('Terms & Conditions', ' ');
		$this->estimateSettingsList();
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : EstimateSettings insertdata
	 @Input 	:
	 @Output	:
	 @Date   : 18/01/2016
	 */
	public function insertdata()
	{
            if (!validateFormSecret())
            {
                $this->session->set_flashdata('msg', lang('error'));
                redirect($this->viewname.'/estimateSettingslist'); //Redirect On Listing page
            }

            $sess_array = array('setting_current_tab' => 'settings_termsConditions');
            $this->session->set_userdata($sess_array);
            
            $data['crnt_view'] = $this->viewname;
		
            $data = array(			
                        'name' => $this->input->post('name'),
                        'terms' => $this->input->post('terms',false),				
                        //'conditions' => $this->input->post('conditions'),
                        'status' => $this->input->post('status')
            );

            //Insert Record in Database

            if ($this->common_model->insert(ESTIMATE_SETTINGS,$data))
            {
                $msg = $this->lang->line('estimate_settings_add_msg');
                $this->session->set_flashdata('msg',$msg);
            }
            else
            {
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg',$msg);
            }
            redirect('/Settings');
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : View EstimateSettings
	 @Input  :
	 @Output :
	 @Date   : 27/01/2016
	 */

	public function view($id) {
				
		$table = ESTIMATE_SETTINGS . ' as es';
		$match 	= "es.estimate_settings_id = ".$id;
		$fields = array("es.estimate_settings_id,es.name,es.terms,es.status");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;
		$data['readonly']  = array("disabled"=>"disabled");		
		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/estimateSettings';
		$this->load->view('estimateSettings', $data);
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
		
		$table = ESTIMATE_SETTINGS . ' as es';
		$match = "";
		$fields = array("es.estimate_settings_id,es.name,es.terms,es.status");		
		$data['information']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['main_content'] = '/estimateSettings';
		$this->load->view('estimateSettings',$data);
		//$this->parser->parse('layouts/CMSTemplate', $data);
	}


	/*
	 @Author : Mehul Patel
	 @Desc   : estimateSettingsList view Page
	 @Input  :
	 @Output :
	 @Date   : 13/02/2016
	 */
	public function estimateSettingsList()
	{
		$data['crnt_view'] = $this->viewname;
		$this->load->library('pagination'); // Load Pagination library 
		//Get Records From Login Table
		$dbSearch = "";
		if ($this->input->get('search') != '') {
			$data['search'] = $term = $this->input->get('search');	

			if($data['search'] == "active" || $data['search'] == "Active"){
				$term = 1;
				$dbSearch .= "es.status =".$term." AND  es.is_delete = 0";
			}elseif($data['search'] == "Inactive" || $data['search'] == "InActive" || $data['search'] == "inactive"){
				$term = 0;
				$dbSearch .= "es.status =".$term." AND  es.is_delete = 0";
			}else{
				$searchFields = array("es.name","es.terms","es.status");
				foreach ($searchFields as $fields):
				$dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
				endforeach;
				$dbSearch = substr($dbSearch, 0, -3);
				$dbSearch = "( ".$dbSearch." ) AND  es.is_delete = 0";
			}
		}else{
			
			$dbSearch = " es.is_delete = 0";
		}
	    $fields = array("es.estimate_settings_id","es.name,es.terms,es.status");
		$config['total_rows'] = count($this->common_model->get_records(ESTIMATE_SETTINGS . ' as es', $fields,'', '', $dbSearch, ''));
        $config['base_url'] = site_url($data['crnt_view'] . '/index');
        $config['per_page'] = RECORD_PER_PAGE;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        $data['sortField'] = 'estimate_settings_id';
        $data['sortOrder'] = 'desc';
        if ($this->input->get('orderField') != '') {
            $data['sortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['sortOrder'] = $this->input->get('sortOrder');
        }
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['information'] = $this->common_model->get_records(ESTIMATE_SETTINGS . ' as es', $fields, '', '', $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
        $page_url = $config['base_url'] . '/' . $data['page'];
       
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        $data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
		$data['drag'] = false;
        $data['main_content'] = '/estimateSettingslist';        
		$data['header'] = array('menu_module'=>'crm');
        if ($this->input->is_ajax_request()) {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
           $this->parser->parse('layouts/CMSTemplate', $data);
        }
		
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : templatelist Edit Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */
	public function edit($id)
	{

		//$id = $this->input->get('id');
		//Get Records From Login Table
		$table = ESTIMATE_SETTINGS . ' as es';
		$match 	= "es.estimate_settings_id = ".$id;
		$fields = array("es.estimate_settings_id,es.name,es.terms,es.status");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;
		$data['userType'] = getUserType();
		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/estimateSettings';
		$this->load->view('estimateSettings', $data);
		// $this->parser->parse('layouts/CMSTemplate', $data);
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Email Template List Update Query
	 @Input 	: Post record from templatelist
	 @Output	: Update data in database and redirect
	 @Date   : 12/01/2016
	 */
	public function updatedata()
	{
            $sess_array = array('setting_current_tab' => 'settings_termsConditions');
            $this->session->set_userdata($sess_array);
            
            $id = $this->input->post('estimate_settings_id');
            
            $table = ESTIMATE_SETTINGS . ' as es';
            $match 	= "es.estimate_settings_id = ".$id;
            $fields = array("es.estimate_settings_id,es.name,es.terms,es.status");
            $data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
            $data['id'] = $id;
            $data['userType'] = getUserType();
            $data['crnt_view'] = $this->viewname;

            $data = array(			
                    'name' => $this->input->post('name'),
                    'terms' => $this->input->post('terms',false),				
                    //'conditions' => $this->input->post('conditions'),
                    'status' => $this->input->post('status')
            );

            $where = array('estimate_settings_id' => $id);

            // Update form data into database
            if ($this->common_model->update(ESTIMATE_SETTINGS, $data, $where))
            {
                $msg = $this->lang->line('estimate_settings_update_msg');
                $this->session->set_flashdata('msg',$msg);
            }
            else
            {
            
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg',$msg);
            }

            redirect('/Settings');
            
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Emailtemplate List Delete Query
	 @Input 	: Post id from List page
	 @Output	: Delete data from database and redirect
	 @Date   : 12/01/2016
	 */
	public function deletedata($id)
	{
            
             $sess_array = array('setting_current_tab' => 'settings_termsConditions');
            $this->session->set_userdata($sess_array);
		//$id = $this->input->post('login_id');
		//Delete Record From Database
		if(!empty($id)){	
			
			$data = array('is_delete'=>1);
			$where = array('estimate_settings_id' => $id);
			if($this->common_model->update(ESTIMATE_SETTINGS,$data,$where)){
			//if($this->common_model->delete(ESTIMATE_SETTINGS,$where)){
				$msg = $this->lang->line('support_settings_update_delete_msg');
				$this->session->set_flashdata('msg',$msg);
				unset($id);
				//redirect($this->viewname);	//Redirect On Listing page
			}else{
				// error
				$msg = $this->lang->line('error_msg');
				$this->session->set_flashdata('msg',$msg);
				//redirect('user/register');
				//redirect($this->viewname);
				//redirect($this->viewname.'/estimateSettingslist');	//Redirect On Listing page

			}

		}
		redirect('/Settings');
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
