<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SalesTargetSettings extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
                if(checkPermission('SalesTargetSettings','view') == false)
                {
                    redirect('/Dashboard');
                }
		$this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form','url'));
		$this->load->library(array('form_validation','Session', 'breadcrumbs'));
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : SalesTargetSettings Index Page
	 @Input 	:
	 @Output	:
	 @Date   : 14/03/2016
	 */
	public function index()
	{	$this->breadcrumbs->push(lang('crm'), '/');
	$this->breadcrumbs->push(lang('settings'), 'SalesTargetSettings');
	$this->breadcrumbs->push(lang('sales_target_settings'), ' ');
	$this->salesTargetlist();
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : SalesTargetSettings insertdata
	 @Input 	:
	 @Output	:
	 @Date   : 18/01/2016
	 */
	public function insertdata()
	{
		if (!validateFormSecret()) {
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
			redirect($this->viewname.'/salesTargetlist'); //Redirect On Listing page
		}

		$data['crnt_view'] = $this->viewname;
		//insert the user emailtemplate details into database
		$data = array(
				'login_id' => $this->input->post('employee_name'),
				'country_id' => $this->input->post('currency_symbol'),
				'currency_symbol' => $this->input->post('currency_symbol'),
				'month' => $this->input->post('month'),
				'target' => $this->input->post('target'),	
				'created_date' => datetimeformat(),	
		  		'status' => $this->input->post('status')
		);

		//Insert Record in Database
		$duplicateTarget = $this->checkDuplicateTarget($this->input->post('employee_name'),  $this->input->post('month'));
		
		
		if($duplicateTarget=="yes"){
			if ($this->common_model->insert(SALES_TARGET_SETTINGS,$data))
			{
				$msg = $this->lang->line('sales_targer_add_success');
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
		}else{
			// error
			$msg = $this->lang->line('duplicate_sales_targer');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");

			redirect($this->viewname);

		}

		//}



	}

	/*
	 @Author : Mehul Patel
	 @Desc   : View EstimateSettings
	 @Input  :
	 @Output :
	 @Date   : 27/01/2016
	 */

	public function view($id) {

                if (!$this->input->is_ajax_request()) 
                {
                    exit('No direct script access allowed');
                }else
                {
                    // Get Employee List
                    $table = LOGIN . ' as l';
                    $match = "l.status=1 AND l.is_delete=0";
                    $fields = array("l.login_id,CONCAT(`firstname`,' ', `lastname`) as name, rm.role_name As user_type ");
                    $params['join_tables'] = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
                    $params['join_type'] = 'left';
                    $data['employee_name_list']=$this->common_model->get_records(LOGIN . ' as l', $fields, $params['join_tables'], $params['join_type'], $match, '');


                    //pr($data['employee_name_list']); exit;

                    // Month list
                    $data['months'] = array('January' => 'January', 'February' => 'February','March' => 'March','April' => 'April','May' => 'May','June' => 'June','July' => 'July','August' => 'August', 'September' => 'September','October' => 'October','November' => 'November','December' => 'December');

                    // get Currency Symbols
                    $table = COUNTRIES . ' as c';
                    $match = "c.use_status=1 AND c.is_delete_currency=0";
                    $fields = array("c.country_id,c.currency_symbol,c.currency_code");
                    $data['currency_symbol']  =  $this->common_model->get_records($table,$fields,'','',$match);

                    $fields = array("sts.target_id,sts.target,CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, rm.role_name As user_type, sts.status,sts.month AS month,c.currency_symbol,c.currency_code,c.country_id,l.login_id");
                    $params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=sts.login_id',COUNTRIES . ' as c' => 'c.country_id=sts.country_id',ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
                    $params['join_type'] = 'left';
                    $match 	= "sts.target_id = ".$id;
                    $data['editRecord']  = $this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $match, '');
                    if(isset($data['editRecord'][0]['firstname'])){
                            $data['employee_name']=$data['editRecord'][0]['name']." ( ".$data['editRecord'][0]['user_type']." ) ";
                    }		
                    $data['id'] = $id;

                    $data['readonly']  = array("disabled"=>"disabled");
                    $data['crnt_view'] = $this->viewname;
                    $data['main_content'] = '/salesTarget';
                    $this->load->view('salesTarget', $data);
                }
		
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Sales Target add  Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */
	public function add()
	{
            if (!$this->input->is_ajax_request()) 
            {
                exit('No direct script access allowed');
            } else {
                $data['crnt_view'] = $this->viewname;
		// Get Employee List
		$table = LOGIN . ' as l';
		$match = "l.status=1 AND l.is_delete=0";
		$fields = array("l.login_id,CONCAT(`firstname`,' ', `lastname`) as name, rm.role_name As user_type ");
		$params['join_tables'] = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
		$params['join_type'] = 'left';
		$data['employee_name_list']=$this->common_model->get_records(LOGIN . ' as l', $fields, $params['join_tables'], $params['join_type'], $match, '');
		//	pr($data['employee_name_list']); exit;

		// Month list
		$data['months'] = array('January' => 'January', 'February' => 'February','March' => 'March','April' => 'April','May' => 'May','June' => 'June','July' => 'July','August' => 'August', 'September' => 'September','October' => 'October','November' => 'November','December' => 'December');

		// get Currency Symbols
		$table = COUNTRIES . ' as c';
		$match = "c.use_status=1 AND c.is_delete_currency=0";
		$fields = array("c.country_id,c.currency_symbol,c.currency_code");
		$data['currency_symbol']  =  $this->common_model->get_records($table,$fields,'','',$match);

		$data['main_content'] = '/salesTarget';
		$this->load->view('salesTarget',$data);
		//$this->parser->parse('layouts/CMSTemplate', $data);
            
                
            }
                
            
		
	}


	/*
	 @Author : Mehul Patel
	 @Desc   : salesTargetlist view Page
	 @Input  :
	 @Output :
	 @Date   : 13/02/2016
	 */
	public function salesTargetlist()
	{
		$data['crnt_view'] = $this->viewname;
		$this->load->library('pagination'); // Load Pagination library
		//Get Records From Login Table
		$dbSearch = "";
		if ($this->input->get('search') != '') {
			$data['search'] = $term = $this->input->get('search');

			if($data['search'] == "active" || $data['search'] == "Active"){
				$term = 1;
				$dbSearch .= "sts.status =".$term." AND  sts.is_delete = 0";
			}elseif($data['search'] == "Inactive" || $data['search'] == "InActive" || $data['search'] == "inactive"){
				$term = 0;
				$dbSearch .= "sts.status =".$term." AND  sts.is_delete = 0";
			}else{
				$searchFields = array("CONCAT(`firstname`,' ', `lastname`)","l.firstname", "l.lastname","rm.role_name","sts.month","sts.target","sts.status");
				//$searchFields = array("es.name","es.terms","es.status");
				foreach ($searchFields as $fields):
				$dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
				endforeach;
				$dbSearch = substr($dbSearch, 0, -3);
				$dbSearch = "( ".$dbSearch." ) AND  sts.is_delete = 0";
			}
		}else{
				
			$dbSearch = " sts.is_delete = 0";
		}

		$params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=sts.login_id',COUNTRIES . ' as c' => 'c.country_id=sts.country_id',ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
		$params['join_type'] = 'left';
		$fields = array("sts.target_id,sts.target,CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, rm.role_name As user_type, sts.created_date, sts.status,sts.month AS month,c.currency_symbol,c.currency_code,c.country_id,l.login_id");
		//$fields = array("l.login_id,l.salution_prefix, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, l.email, l.password, l.address,l.address_1,l.city,l.state,l.pincode,l.country, l.telephone1, l.telephone2, rm.role_name As user_type, l.created_date, l.status");

		$config['total_rows'] = count($this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $dbSearch, ''));
		$config['base_url'] = site_url($data['crnt_view'] . '/index');
		$config['per_page'] = RECORD_PER_PAGE;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = floor($choice);
		$data['sortField'] = 'target_id';
		$data['sortOrder'] = 'desc';
		if ($this->input->get('orderField') != '') {
			$data['sortField'] = $this->input->get('orderField');
		}
		if ($this->input->get('sortOrder') != '') {
			$data['sortOrder'] = $this->input->get('sortOrder');
		}
		$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['information'] = $this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
		$page_url = $config['base_url'] . '/' . $data['page'];
		//$data['drag'] = false;
		$data['pagination'] = $this->pagingConfig($config, $page_url);
		$data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
		 
		$data['drag'] = false;
		$data['main_content'] = '/salesTargetlist';
		$data['header'] = array('menu_module'=>'crm');
		if ($this->input->is_ajax_request()) {
			$this->load->view($this->viewname . '/ajaxlist', $data);
		} else {
			$this->parser->parse('layouts/CMSTemplate', $data);
		}

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Edit Sales target Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */
	public function edit($id)
	{
            if (!$this->input->is_ajax_request()) 
            {
                exit('No direct script access allowed');
            }else
            {
                // Get Employee List
		$table = LOGIN . ' as l';
		$match = "l.status=1 AND l.is_delete=0 ";
		$fields = array("l.login_id,CONCAT(`firstname`,' ', `lastname`) as name, rm.role_name As user_type ");
		$params['join_tables'] = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
		$params['join_type'] = 'left';
		$data['employee_name_list']=$this->common_model->get_records(LOGIN . ' as l', $fields, $params['join_tables'], $params['join_type'], $match, '');
		// Month list
		$data['months'] = array('January' => 'January', 'February' => 'February','March' => 'March','April' => 'April','May' => 'May','June' => 'June','July' => 'July','August' => 'August', 'September' => 'September','October' => 'October','November' => 'November','December' => 'December');
		
		// get Currency Symbols
		$table = COUNTRIES . ' as c';
		$match = "c.use_status=1 AND c.is_delete_currency=0";
		$fields = array("c.country_id,c.currency_symbol,c.currency_code");
		$data['currency_symbol']  =  $this->common_model->get_records($table,$fields,'','',$match);

		$fields = array("sts.target_id,sts.target,CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, rm.role_name As user_type, sts.status,sts.month AS month,c.currency_symbol,c.currency_code,c.country_id,l.login_id");
		$params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=sts.login_id',COUNTRIES . ' as c' => 'c.country_id=sts.country_id',ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
		$params['join_type'] = 'left';
		$match 	= "sts.target_id = ".$id;
		$data['editRecord']  = $this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $match, '');
		$data['id'] = $id;
		$data['crnt_view'] = $this->viewname;
		$data['main_content'] = '/salesTarget';
		$this->load->view('salesTarget', $data);
		// $this->parser->parse('layouts/CMSTemplate', $data);
            }
		
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : Sales Target List Update Query
	 @Input 	: Post record from templatelist
	 @Output	: Update data in database and redirect
	 @Date   : 12/01/2016
	 */
	public function updatedata()
	{



		$id = $this->input->post('target_id');

		// Get Employee List
		$table = LOGIN . ' as l';
		$match = "l.status=1 AND l.is_delete=0 AND l.login_id NOT IN ( select login_id from blzdsk_sales_target_settings )";
		$fields = array("l.login_id,CONCAT(`firstname`,' ', `lastname`) as name, rm.role_name As user_type ");
		$params['join_tables'] = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
		$params['join_type'] = 'left';
		$data['employee_name_list']=$this->common_model->get_records(LOGIN . ' as l', $fields, $params['join_tables'], $params['join_type'], $match, '');
		// Month list
		$data['months'] = array('January' => 'January', 'February' => 'February','March' => 'March','April' => 'April','May' => 'May','June' => 'June','July' => 'July','August' => 'August', 'September' => 'September','October' => 'October','November' => 'November','December' => 'December');

		// get Currency Symbols
		$table = COUNTRIES . ' as c';
		$match = "c.use_status=1 AND c.is_delete_currency=0";
		$fields = array("c.country_id,c.currency_symbol,c.currency_code");
		$data['currency_symbol']  =  $this->common_model->get_records($table,$fields,'','',$match);

		$fields = array("sts.target_id,sts.target,CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, rm.role_name As user_type, l.created_date, l.status,sts.month AS month,c.currency_symbol,c.currency_code,c.country_id,l.login_id");
		$params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=sts.login_id',COUNTRIES . ' as c' => 'c.country_id=sts.country_id',ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
		$params['join_type'] = 'left';
		$match 	= "sts.target_id = ".$id;
		$data['editRecord']  = $this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $match, '');
		$data['id'] = $id;

		$data['crnt_view'] = $this->viewname;

		$data = array(
				'login_id' => $this->input->post('employee_name'),
				'country_id' => $this->input->post('currency_symbol'),
				'currency_symbol' => $this->input->post('currency_symbol'),
				'month' => $this->input->post('month'),
				'target' => $this->input->post('target'),
				'modified_date' => datetimeformat(),		
		  		'status' => $this->input->post('status')
		);

			
		$where = array('target_id' => $id);

		$duplicateTarget = $this->checkDuplicateTarget($this->input->post('employee_name'),  $this->input->post('month'),$id);
		
		if($duplicateTarget=="yes"){
			// Update form data into database
			if ($this->common_model->update(SALES_TARGET_SETTINGS, $data, $where))
			{
				//$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Updated! </div>');
				$msg = $this->lang->line('sales_targer_update_success');
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
		}else{
			// error
			$msg = $this->lang->line('duplicate_sales_targer');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");

			redirect($this->viewname);

		}


		redirect($this->viewname);	//Redirect On Listing page
		//}
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : salesTarget List Delete Query
	 @Input 	: Post id from List page
	 @Output	: Delete data from database and redirect
	 @Date   : 12/01/2016
	 */
	public function deletedata($id)
	{
		//$id = $this->input->post('login_id');
		//Delete Record From Database
		if(!empty($id)){
				
			$data = array('is_delete'=>1);
			$where = array('target_id' => $id);
			if($this->common_model->update(SALES_TARGET_SETTINGS,$data,$where)){
				//if($this->common_model->delete(ESTIMATE_SETTINGS,$where)){
				$msg = $this->lang->line('sales_targer_delete_success');
				$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
				unset($id);
				redirect($this->viewname);	//Redirect On Listing page
			}else{
				// error
				$msg = $this->lang->line('error_msg');
				$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
				//redirect('user/register');
				redirect($this->viewname);
				//redirect($this->viewname.'/estimateSettingslist');	//Redirect On Listing page

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
	 @Desc   : checkDuplicateTarget
	 @Input 	:
	 @Output	:
	 @Date   : 01/02/2016
	 */

	public function checkDuplicateTarget($user_id,$month,$target_id=null){
			
			
		$table = SALES_TARGET_SETTINGS . ' as sts';
		if(NULL!==$target_id)
		{
			$match = " sts.login_id = ".$user_id." and sts.month = '" . $month."' and sts.target_id <> '".$target_id."'"." AND sts.is_delete = 0 ";
			$fields = array(" COUNT(sts.target_id) AS target_id, sts.is_delete");
			$status = $this->common_model->get_records($table, $fields, '', '', $match);
			//echo  $this->db->last_query();  exit;
			if(isset($status[0]['target_id'] ) && $status[0]['target_id'] ==0 && $status[0]['is_delete']==1  ){
				return "yes";	
			}elseif($status[0]['target_id'] == 0 && $status[0]['is_delete'] == NULL){
				
					return "yes";
			
			}else{
				return "no";	
			}
			
		}
		else
		{
			$match1 = "sts.login_id =" .$user_id. " AND sts.month ='" .$month."'";
			$table1 = SALES_TARGET_SETTINGS . ' as sts';
			$fields1 = array(" COUNT(sts.target_id) AS target_id, sts.is_delete");
			$status1 = $this->common_model->get_records($table, $fields1, '', '', $match1);
			//	echo $this->db->last_query(); exit;
			if($status1[0]['target_id']>0 && $status1[0]['is_delete']==1){
				$match2 = "is_delete = 0 AND sts.login_id =" .$user_id. " AND sts.month ='" .$month."'";
				$table2 = SALES_TARGET_SETTINGS . ' as sts';
				$fields2 = array(" COUNT(sts.target_id) AS target_id, sts.is_delete");
				$status2 = $this->common_model->get_records($table, $fields1, '', '', $match1);
					
				if($status2[0]['target_id'] > 1){
					
					return "no";
				}else{
					return "yes";
				}
						
			}elseif($status1[0]['target_id'] == 0 && $status1[0]['is_delete'] == NULL){
				
					return "yes";
			
			}else{
				return "no";
			}
				
			//$match = "sts.login_id =" .$user_id. " AND sts.month ='" .$month."'";
		}

		
			
	}


}
