<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emailtemplate extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		 if(checkPermission('Emailtemplate','view') == false)
                {
                    redirect('/Dashboard');
                }
		$this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form','url'));
		$this->load->library(array('form_validation','Session'));
		if(checkPermission('Emailtemplate','view') == false)
        {
            redirect('/Dashboard');
        }
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : emailtemplate Index Page
	 @Input 	:
	 @Output	:
	 @Date   : 18/01/2016
	 */
	public function index()
	{
				
	
		$this->templatelist();
		
				
		
		
		/*$data['crnt_view'] = $this->viewname;
		//Get Records From blzdsk_email_template_maste Table

		$table = EMAIL_TEMPLATE_MASTER . ' as et';
		$match = "";
		$fields = array("et.template_id,et.subject,et.body,et.status");
		$data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);

		//Pass EMAIL_TEMPLATE_MASTER Table Record In View
		$data['main_content'] = '/templatelist';

		$this->parser->parse('layouts/CMSTemplate', $data);*/
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : emailtemplate insertdata
	 @Input 	:
	 @Output	:
	 @Date   : 18/01/2016
	 */
	public function insertdata()
	{
	  if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
           redirect($this->viewname.'/templatelist'); //Redirect On Listing page
        }

		$data['crnt_view'] = $this->viewname;
		//insert the user emailtemplate details into database
		$data = array(			
				'subject' => $this->input->post('emailTemplate_sub'),
				'body' => $this->input->post('emailTemplate_body',false),
				//'system_template' =>$this->input->post('system_template'),		
				'created_date' => datetimeformat(),
				'status' => $this->input->post('status')
		);

		//Insert Record in Database

		if ($this->common_model->insert(EMAIL_TEMPLATE_MASTER,$data))
		{
			$msg = $this->lang->line('emailTemplate_add_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");

			redirect($this->viewname.'/templatelist');

		}
		else
		{
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");

			redirect($this->viewname);
		}
		//}
	}

	/*
	 @Author : Mehul Patel
	 @Desc   : View Email Template
	 @Input  :
	 @Output :
	 @Date   : 27/01/2016
	 */

	public function viewEmailTemplate($id = null) {

		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$data['crnt_view'] = $this->viewname;
			//Get Records From AAUTH_PERMS Table
			$table = EMAIL_TEMPLATE_MASTER . ' as et';
			$match = "et.template_id=". $id;
			$fields = array("et.template_id,et.subject,et.body,et.status");
			$data['viewEmailTemplate'] = $this->common_model->get_records($table, $fields, '', '', $match);

			//Pass Role Master Table Record In View
			$data['main_content'] = '/viewEmailTemplate';
			$this->load->view("viewEmailTemplate", $data);
			//$this->parser->parse('layouts/CMSTemplate', $data);
		}

	}

	/*
	 @Author : Mehul Patel
	 @Desc   : Email template add view Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */
	public function add()
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			$data['crnt_view'] = $this->viewname;

			$table = EMAIL_TEMPLATE_MASTER . ' as et';
			$match = "";
			$fields = array("et.template_id,et.subject,et.body,et.status,et.system_template");
			$data['information']  = $this->common_model->get_records($table,$fields,'','',$match);
			$data['userType'] = getUserType();
			//Pass Login Table Record In View
			$data['main_content'] = '/emailtemplate';
			$this->load->view('emailtemplate',$data);
			//$this->parser->parse('layouts/CMSTemplate', $data);
		}

	}


	/*
	 @Author : Mehul Patel
	 @Desc   : Email list view Page
	 @Input 	:
	 @Output	:
	 @Date   : 19/01/2016
	 */
	public function templatelist()
	{

		$data['crnt_view'] = $this->viewname;
		$this->load->library('pagination'); // Load Pagination library 
		//Get Records From Login Table
		$dbSearch = "";
		if ($this->input->get('search') != '') {
			$data['search'] = $term = $this->input->get('search');

			if($data['search'] == "active" || $data['search'] == "Active"){
				$term = 1;
				$dbSearch .= "et.status =".$term." AND  et.is_delete = 0 AND et.system_template = 0 ";
			}elseif($data['search'] == "Inactive" || $data['search'] == "InActive" || $data['search'] == "inactive"){
				$term = 0;
				$dbSearch .= "et.status =".$term." AND  et.is_delete = 0 AND et.system_template = 0 ";
			}else{
				$searchFields = array("et.subject","et.status");
					
				foreach ($searchFields as $fields):
				$dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
				endforeach;
				$dbSearch = substr($dbSearch, 0, -3);
				$dbSearch = "( ".$dbSearch." ) AND  et.is_delete = 0 AND et.system_template = 0 ";
			}
		}else{
			
			$dbSearch = " et.is_delete = 0 AND et.system_template = 0 ";
		}
	    $fields = array("et.template_id","et.subject,et.body,et.status,et.system_template");
		$config['total_rows'] = count($this->common_model->get_records(EMAIL_TEMPLATE_MASTER . ' as et', $fields,'', '', $dbSearch, ''));
        $config['base_url'] = site_url($data['crnt_view'] . '/index');
        $config['per_page'] = RECORD_PER_PAGE;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        $data['sortField'] = 'template_id';
        $data['sortOrder'] = 'desc';
        if ($this->input->get('orderField') != '') {
            $data['sortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['sortOrder'] = $this->input->get('sortOrder');
        }
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['information'] = $this->common_model->get_records(EMAIL_TEMPLATE_MASTER . ' as et', $fields, '', '', $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
        $page_url = $config['base_url'] . '/' . $data['page'];
       
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        $data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
        $data['drag'] = false;
        $data['main_content'] = '/templatelist';        
		$data['header'] = array('menu_module'=>'crm');
        if ($this->input->is_ajax_request()) {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
           $this->parser->parse('layouts/CMSTemplate', $data);
        }
		
		
		/*
		$data['crnt_view'] = $this->viewname;
		$table = EMAIL_TEMPLATE_MASTER . ' as et';
		$match = "";
		$fields = array("et.template_id,et.subject,et.body,et.status");
		$data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);

		//Pass Login Table Record In View
		$data['main_content'] = '/templatelist';
		$this->parser->parse('layouts/CMSTemplate', $data);*/
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
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}else
		{
			//$id = $this->input->get('id');
			//Get Records From Login Table
			$table = EMAIL_TEMPLATE_MASTER . ' as et';
			$match 	= "et.template_id = ".$id;
			$fields = array("et.template_id, et.subject,et.body,et.status,et.system_template,et.variable");
			$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
			$data['id'] = $id;
			$data['userType'] = getUserType();
			$data['crnt_view'] = $this->viewname;
			$data['main_content'] = '/emailtemplate';
			$this->load->view('emailtemplate', $data);
			// $this->parser->parse('layouts/CMSTemplate', $data);
				
		}

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

		$id = $this->input->post('template_id');

		//Get Records From EMAIL_TEMPLATE_MASTER Table
		$table = EMAIL_TEMPLATE_MASTER . ' as et';
		$match 	= "et.template_id = ".$id;
		$fields = array("et.template_id, et.subject,et.body,et.status,et.system_template,et.variable");
		$data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
		$data['id'] = $id;
		$data['userType'] = getUserType();
		$data['crnt_view'] = $this->viewname;

		$data = array(				
				'subject' => $this->input->post('emailTemplate_sub'),
				'body' => $this->input->post('emailTemplate_body',false),
				//'system_template' =>$this->input->post('system_template'),						
				'modified_date' => datetimeformat(),
				'status' => $this->input->post('status')
		);
			
		$where = array('template_id' => $id);

		// Update form data into database
		if ($this->common_model->update(EMAIL_TEMPLATE_MASTER, $data, $where))
		{
			//$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Updated! </div>');
			$msg = $this->lang->line('emailTemplate_update_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
			redirect($this->viewname.'/templatelist');

		}
		else
		{
			// error
			$msg = $this->lang->line('error_msg');
			$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");

			redirect($this->viewname);
		}

		redirect($this->viewname);	//Redirect On Listing page
		//}
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
		//$id = $this->input->post('login_id');
		//Delete Record From Database
		if(!empty($id)){	
			
			$data = array('is_delete'=>1);
			$where = array('template_id' => $id);
			if($this->common_model->update(EMAIL_TEMPLATE_MASTER,$data,$where)){
			//if($this->common_model->delete(EMAIL_TEMPLATE_MASTER,$where)){
				$msg = $this->lang->line('emailTemplate_delete_msg');
				$this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
				unset($id);
				redirect($this->viewname.'/templatelist');	//Redirect On Listing page
			}else{
				// error
				$msg = $this->lang->line('error_msg');
				$this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
				//redirect('user/register');
				//redirect($this->viewname);
				redirect($this->viewname.'/templatelist');	//Redirect On Listing page

			}

		}
		//	redirect($this->viewname.'/userlist');	//Redirect On Listing page
	}
	
	public function sendmail() {
		
        $config['protocol'] = 'mail';
        $config['smtp_host'] = 'ssl://smtp.gmail.com'; //change this
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'cmswtest101@gmail.com'; //change this
        $config['smtp_pass'] = 'inf0city'; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        $this->load->library('email', $config); // load email library
        $this->email->from('cmswtest101@gmail.com', 'CMS TEST');
        $this->email->to('mehul.patel@c-metric.com');
        $this->email->subject('This is test mail');
        $this->email->message('max test');
        //$this->email->attach('/path/to/file1.png'); // attach file
      //  $this->email->attach('/path/to/file2.pdf');
        if ($this->email->send()){
        	echo "Mail Sent!";
        }else{
        	echo $this->email->print_debugger();
            echo "There is error in sending mail!";
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
	
    public function systemTemplateList(){

    	$data['crnt_view'] = $this->viewname;
    	$this->load->library('pagination'); // Load Pagination library
    	//Get Records From Login Table
    	$dbSearch = "";
    	if ($this->input->get('search') != '') {
    		$data['search'] = $term = $this->input->get('search');

    		if($data['search'] == "active" || $data['search'] == "Active"){
    			$term = 1;
    			$dbSearch .= "et.status =".$term." AND  et.is_delete = 0";
    		}elseif($data['search'] == "Inactive" || $data['search'] == "InActive" || $data['search'] == "inactive"){
    			$term = 0;
    			$dbSearch .= "et.status =".$term." AND  et.is_delete = 0";
    		}else{
    			$searchFields = array("et.subject","et.status");
    				
    			foreach ($searchFields as $fields):
    			$dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
    			endforeach;
    			$dbSearch = substr($dbSearch, 0, -3);
    			$dbSearch = "( ".$dbSearch." ) AND  et.is_delete = 0";
    		}
    	}else{
    			
    			$dbSearch = " et.is_delete = 0 AND et.system_template = 1 ";
    	}
    	 $fields = array("et.template_id","et.subject,et.body,et.status,et.system_template");
    	$config['total_rows'] = count($this->common_model->get_records(EMAIL_TEMPLATE_MASTER . ' as et', $fields,'', '', $dbSearch, ''));
    	$config['base_url'] = site_url($data['crnt_view'] . '/systemTemplateList');
    	$config['per_page'] = RECORD_PER_PAGE;
    	$choice = $config["total_rows"] / $config["per_page"];
    	$config["num_links"] = floor($choice);
    	$data['sortField'] = 'template_id';
    	$data['sortOrder'] = 'desc';
    	if ($this->input->get('orderField') != '') {
    		$data['sortField'] = $this->input->get('orderField');
    	}
    	if ($this->input->get('sortOrder') != '') {
    		$data['sortOrder'] = $this->input->get('sortOrder');
    	}
    	$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    	$data['information'] = $this->common_model->get_records(EMAIL_TEMPLATE_MASTER . ' as et', $fields, '', '', $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
    	$page_url = $config['base_url'] . '/' . $data['page'];
    	 
    	$data['pagination'] = $this->pagingConfig($config, $page_url);
    	$data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
    	$data['drag'] = false;
    	$data['main_content'] = '/systemtemplatelist';
    	$data['header'] = array('menu_module'=>'crm');
    	if ($this->input->is_ajax_request()) {
    		$this->load->view($this->viewname . '/systemtemplateajaxlist', $data);
    	} else {
    		$this->parser->parse('layouts/CMSTemplate', $data);
    	}

    }

}
