<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Currencysettings extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		if(checkPermission('Currencysettings','view') == false)
                {
                    redirect('/Dashboard');
                }
		$this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form','url'));
		$this->load->library(array('form_validation','Session', 'breadcrumbs'));
		//$this->perPage = 5;
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Currency Settings Index Page
	 @Input 	:
	 @Output	:
	 @Date   : 17/02/2016
	 */
	public function index()
	{
		$this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push('Settings', 'Currencysettings');
        $this->breadcrumbs->push('Currency Settings', ' ');
		$this->currencylist();

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : country insertdata
	 @Input 	:
	 @Output	:
	 @Date   : 17/02/2016
	 */
	public function insertdata()
	{
               
            $sess_array = array('setting_current_tab' => 'settings_currency');
            $this->session->set_userdata($sess_array);

               
		$data['crnt_view'] = $this->viewname;
		//insert the currency add details into database
		$data = array(
				'currency_name' => $this->input->post('currency_name'),
				'currency_code' => $this->input->post('currency_code'),
				'currency_symbol' => $this->input->post('currency_symbol'),				
				'use_status' => $this->input->post('use_status'),
				'country_status' => $this->input->post('country_status'),
				'is_delete_currency' => '0' 
		);

		$where = array('country_id' => $this->input->post('country_id'));
		//Insert Record in Database

		if ($this->common_model->update(COUNTRIES, $data, $where))
		{
			$msg = $this->lang->line('currency_settings_update_add_msg');
			$this->session->set_flashdata('msg',$msg);

			//redirect($this->viewname.'/currencylist');
                        $redirect_link = $this->viewname.'/currencylist';

		}
		else
		{
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg',$msg);

			//redirect($this->viewname);
                        $redirect_link = $this->viewname;
		}
                
                 redirect('Settings');
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Add Currency view Page
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
				
			$table1 = COUNTRIES . ' as c';
			$match1 = "c.use_status IS NULL OR is_delete_currency = 1 ";
			$fields1 = array("c.country_id,c.country_name");
			$data['country_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
				
			//Pass Login Table Record In View
			$data['main_content'] = '/add';
			$this->load->view('add',$data);
			//$this->parser->parse('layouts/CMSTemplate', $data);
		}

	}


	/*
	 @Author : Mehul Patel
	 @Desc   : currencylist view Page
	 @Input 	:
	 @Output	:
	 @Date   : 17/02/2016
	 */
	public function currencylist()
	{
		$dbSearch = "";

		$data['crnt_view'] = $this->viewname;
		$this->load->library('pagination'); // Load Pagination library
		//Get Records From Login Table

		if ($this->input->get('search') != '') {
			$data['search'] = $term = $this->input->get('search');

			if($data['search'] == "active" || $data['search'] == "Active"){
				$term = 1;
				$dbSearch .= "c.country_status =".$term. " AND  c.is_delete_currency = 0 AND c.use_status IS NOT NULL";
			}elseif($data['search'] == "Inactive" || $data['search'] == "InActive" || $data['search'] == "inactive"){
				$term = 0;
				$dbSearch .= "c.country_status =".$term. " AND  c.is_delete_currency = 0 AND c.use_status IS NOT NULL ";
			}elseif($data['search'] == "Yes" || $data['search'] == "yes" || $data['search'] == "YES"){
				$term = 1;
				$dbSearch .= "c.use_status =".$term. " AND  c.is_delete_currency = 0 ";			
			}elseif($data['search'] == "No" || $data['search'] == "no" || $data['search'] == "NO"){
				$term = 0;
				$dbSearch .= "c.use_status =".$term. " AND  c.is_delete_currency = 0 AND c.use_status IS NOT NULL ";
			}else{		
				$searchFields = array("c.country_name", "c.currency_name", "c.currency_code", "c.use_status","c.country_status");
				foreach ($searchFields as $fields):
				$dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
				endforeach;
				$dbSearch = substr($dbSearch, 0, -3);
				$dbSearch = "( ".$dbSearch." ) AND  c.is_delete_currency = 0 AND c.use_status IS NOT NULL";
			}
		}else{
			$dbSearch .= " c.is_delete_currency = '0' AND c.use_status IS NOT NULL ";
		}
	
		$fields = array("c.country_id,c.country_name, c.currency_name, c.currency_code, c.currency_symbol, c.use_status, c.country_status");

		$config['total_rows'] = count($this->common_model->get_records(COUNTRIES . ' as c', $fields, '', '', $dbSearch, ''));
		$config['base_url'] = site_url($data['crnt_view'] . '/index');
		$config['per_page'] = RECORD_PER_PAGE;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = floor($choice);
		$data['sortField'] = 'country_id';
		$data['sortOrder'] = 'desc';
		if ($this->input->get('orderField') != '') {
			$data['sortField'] = $this->input->get('orderField');
		}
		if ($this->input->get('sortOrder') != '') {
			$data['sortOrder'] = $this->input->get('sortOrder');
		}
		$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['information'] = $this->common_model->get_records(COUNTRIES . ' as c', $fields, '', '', $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
		// echo "Query :".$this->db->last_query();
		
		$page_url = $config['base_url'] . '/' . $data['page'];
			
		$data['pagination'] = $this->pagingConfig($config, $page_url);
		$data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
		$data['drag'] = false;
		$data['main_content'] = '/currencylist';
		$data['header'] = array('menu_module'=>'crm');
		// $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
		 $gensettingWhere = "config_key='general_settings'";
         $defaultDashboard1 = $this->common_model->get_records(CONFIG, array('value'), '', '', $gensettingWhere);
		 $generalSettings = (array) json_decode($defaultDashboard1[0]['value']);

		 if(isset($generalSettings['default_currency']) && !empty($generalSettings['default_currency'])){
         	$data['default_currency'] = $generalSettings['default_currency'];
         }else{
         	$data['default_currency'] = 0;
         }
        if ($this->input->is_ajax_request()) {
			$this->load->view($this->viewname . '/ajaxlist', $data);
		} else {
			$this->parser->parse('layouts/CMSTemplate', $data);
		}
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Currency Edit Page
	 @Input  :
	 @Output :
	 @Date   : 17/02/2016
	 */
	public function edit($id)
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$table	= COUNTRIES.' as c';
			$match 	= "c.country_id = ".$id;
			$fields = array("c.country_id,c.country_name, c.currency_name, c.currency_code, c.currency_symbol, c.use_status, c.country_status");
			$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
			$data['id'] = $id;

			$table1 = COUNTRIES . ' as c';
			$match1 = "";
			$fields1 = array("c.country_id,c.country_name");
			$data['country_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/add';
			$this->load->view('add', $data);
			// $this->parser->parse('layouts/CMSTemplate', $data);
		}


	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Currency Edit Page
	 @Input  :
	 @Output :
	 @Date   : 17/02/2016
	 */
	public function view($id)
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$table	= COUNTRIES.' as c';
			$match 	= "c.country_id = ".$id;
			$fields = array("c.country_id,c.country_name, c.currency_name, c.currency_code, c.currency_symbol, c.use_status, c.country_status");
			$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
			$data['readonly']  = array("disabled"=>"disabled");
			$data['id'] = $id;

			$table1 = COUNTRIES . ' as c';
			$match1 = "";
			$fields1 = array("c.country_id,c.country_name");
			$data['country_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/add';
			$this->load->view('add', $data);
			// $this->parser->parse('layouts/CMSTemplate', $data);
		}

	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Currency Update Query
	 @Input  : Post record from userlist
	 @Output : Update data in database and redirect
	 @Date   : 17/02/2016
	 */
	public function updatedata()
	{
            $sess_array = array('setting_current_tab' => 'settings_currency');
            $this->session->set_userdata($sess_array);
           
            
		$data['crnt_view'] = $this->viewname;
		$id = $this->input->post('countryId');

		//Get Records From Login c';
		$table = COUNTRIES . ' as c';
		$match 	= "c.country_id = ".$id;
		$fields = array("c.country_id,c.country_name, c.currency_name, c.currency_code, c.currency_symbol, c.use_status, c.country_status");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;

		$data = array(
				'currency_name' => $this->input->post('currency_name'),
				'currency_code' => $this->input->post('currency_code'),
				'currency_symbol' => $this->input->post('currency_symbol'),				
				'use_status' => $this->input->post('use_status'),
				'country_status' => $this->input->post('country_status')
		);

		//Update Record in Database
		$where = array('country_id' => $id);

		// Update form data into database
		if ($this->common_model->update(COUNTRIES, $data, $where))
		{
			
			$msg = $this->lang->line('currency_settings_update_update_msg');
			$this->session->set_flashdata('msg',$msg);
			
                        $redirect_link = $this->viewname.'/currencylist';

		}
		else
		{
			
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg',$msg);
			
                        // $redirect_link = $this->viewname;
                         redirect($this->viewname);
		}

                //Redirect On Listing page
                redirect('Settings');
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Currency list Delete Query
	 @Input  : Post id from List page
	 @Output : Delete data from database and redirect
	 @Date   : 17/02/2016
	 */
	public function deletedata($id)
	{
            
            $sess_array = array('setting_current_tab' => 'settings_currency');
            $this->session->set_userdata($sess_array);
            
            $data['crnt_view'] = $this->viewname;
            //	$id = $this->input->post('countryId');

            //Get Records From Login c';
            $table = COUNTRIES . ' as c';
            $match 	= "c.country_id = ".$id;
            $fields = array("c.country_id,c.country_name, c.currency_name, c.currency_code, c.currency_symbol, c.use_status, c.country_status");
            $data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
            $data['id'] = $id;

            $data = array('is_delete_currency' => '1');

            //Update Record in Database
            $where = array('country_id' => $id);

            // Update form data into database
            if ($this->common_model->update(COUNTRIES, $data, $where))
            {
                $msg = $this->lang->line('currency_settings_update_delete_msg');
                $this->session->set_flashdata('msg',$msg);
            }
            else
            {
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg',$msg);
            }

            redirect('Settings');			
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
