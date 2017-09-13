

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TeleMarketing extends CI_Controller {

    public $viewname;

    function __construct() {
		
        parent::__construct();
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation', 'Session'));
        $this->load->model('Tele_model');
		date_default_timezone_set($this->session->userdata('LOGGED_IN')['TIMEZONE']);
        //$this->load->model('Lead_model');
    }
    
     /*
      @Author : Ghelani Nikunj
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
    
   public function index() {
		
		$id=$this->session->userdata('LOGGED_IN')['ID'];
        $data['role_type']=$this->session->userdata('LOGGED_IN')['ROLE_TYPE'];
        $this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('TeleMarketing'), 'TeleMarketing');
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');

        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('telemarketing_data');
        }

        $searchsort_session = $this->session->userdata('telemarketing_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {

            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {
                $sortfield = 'tele_id';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->viewname . '/index';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {

            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
        /*for left side count total telemarketing*/
        $table6 = TELE_MARKETING . ' as tm';
        $match6 = "tm.is_delete=0";
        $fields6 = array("count(tm.tele_id) as total_tele");
        $total_ticket = $this->common_model->get_records($table6, $fields6, '', '',$match6);
        $data['total_tele'] = $total_ticket[0]['total_tele'];
		
		$where1='';
		if($data['role_type']=='39'){
			$where1 = "tm.is_delete=0";
			}
			else{
				$where1 = "tm.is_delete=0 AND tm.user_id=".$id;
				}
		
        $params['join_tables'] = array(
        LOGIN . ' as lg' => 'tm.user_id=lg.login_id');
        $params['join_type'] = 'left';
        $table = TELE_MARKETING . ' as tm';
        $group_by = 'tm.tele_id';
        $fields = array("lg.firstname,lg.lastname,tm.tele_id,tm.status,tm.tele_name,tm.created_date,tm.phone_no,tm.remark,tm.company_name");
        $where = $where1;
		/* search from status */
        $data['select_status'] = "";
        if ($this->input->post('select_status') != "") {
            $data['select_status'] = $this->input->post('select_status');
            $where.=' AND tm.status=' . $data['select_status'];
        }
    
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim($searchtext));
            $where_search = '(tm.tele_name LIKE "%' . $searchtext . '%")';
            //$match=array('pm.prospect_name'=>$searchtext,'pm.prospect_auto_id'=>$searchtext,"pm.status_type"=>$searchtext,"pm.creation_date"=>$searchtext,"pc.contact_name"=>$searchtext);
            $data['tele_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $where_search);
            //echo $this->db->last_query(); exit;

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);
        
           
        } else {

            $data['tele_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where);
			//echo $this->db->last_query(); exit;
			/*pr($data['tele_data']);
			die();*/
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
        }
        

        $data['header'] = array('menu_module' => 'crm');
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('telemarketing_data', $sortsearchpage_data);

        $data['lead_view'] = $this->viewname;

        $data['sales_view'] = $this->viewname;
        $data['drag'] = true;
        if ($this->input->post('result_type') == 'ajax') {

            $this->load->view($this->viewname . '/AjaxTelemarketing', $data);
        } else {

            $data['main_content'] = $this->viewname . '/Telemarketingview';
            $this->parser->parse('layouts/DashboardTemplate', $data);
           
        }
    }
    
      /*
      @Author : Ghelani Nikunj
      @Desc   : For add page data
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
    
    public function add() { 
		$data = array();
        $data['project_view'] = $this->viewname;
        $redirect_link = $this->input->post('redirect_link');
        
        //Get Records From COMPANY_MASTER Table       
        $table1 = COMPANY_MASTER . ' as cmp';
        $match1 = " cmp.status=1 and cmp.is_delete=0 ";
        $fields1 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        
        $data['sales_view'] = $this->viewname;
        $data['drag'] = true;
        $this->load->view('AddFinal', $data);
		
		
		}
		
      /*
      @Author : Ghelani Nikunj
      @Desc   : For update page data
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
	public function edit_record($id) {
		
		$data = array();
        $data['id'] = $id;
        $data['project_view'] = 'TeleMarketing/updateTelemarketingData';

        //Get Records From COMPANY_MASTER Table       
        $table1 = COMPANY_MASTER . ' as cmp';
        $match1 = " cmp.status=1 and cmp.is_delete=0 ";
        $fields1 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        /*get record id wise*/
        $table = TELE_MARKETING . ' as tm';
        $match = "tm.tele_id = " . $id;
        $fields = array("tm.tele_id,tm.status,tm.tele_name,tm.created_date,tm.phone_no,tm.remark,tm.company_name,tm.user_id");
        $data['tele_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

		/*pr($data['tele_data']);
		die('here');*/
        $data['drag'] = true;
        $data['sales_view'] = $this->viewname;
        $this->load->view('AddFinal', $data);
		
		}
		 /*
      @Author : Ghelani Nikunj
      @Desc   : For view page data
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
	public function view_record($id) {
		
		$data = array();
        $data['readonly'] = array("disabled" => "disabled");
        $data['project_view'] = 'viewdata';	
        
          //Get Records From COMPANY_MASTER Table       
        $table1 = COMPANY_MASTER . ' as cmp';
        $match1 = " cmp.status=1 and cmp.is_delete=0 ";
        $fields1 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);


        /*get record id wise*/
        $table = TELE_MARKETING . ' as tm';
        $match = "tm.tele_id = " . $id;
        $fields = array("tm.tele_id,tm.status,tm.tele_name,tm.created_date,tm.phone_no,tm.remark,tm.company_name,tm.user_id");
        $data['tele_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
 
        $data['drag'] = true;
        $this->load->view('view_tele', $data);
			
		}
		
		 /*
      @Author : Ghelani Nikunj
      @Desc   : For insert data in to database
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
		
	public function saveTelemarketingData() {
			/*pr($_POST);
			die();*/
			$this->form_validation->set_rules('tele_name', 'Company Tele Name', 'required');
			$tele_data['user_id'] = $this->session->userdata('LOGGED_IN')['ID'];
			$tele_data['tele_name'] = $this->input->post('tele_name');
			$tele_data['company_name'] = $this->input->post('company_name');
			$tele_data['phone_no'] = $this->input->post('phone_no');
			$tele_data['status'] = $this->input->post('status');
			$tele_data['remark'] = $this->input->post('remark',false);
			$tele_data['created_date'] = datetimeformat();
			//Insert Record in Database
			$success_insert = $this->common_model->insert(TELE_MARKETING, $tele_data);
			if ($success_insert) {
				$msg = $this->lang->line('tele_add_msg');
				$this->session->set_flashdata('msg', $msg);
			}
			redirect($this->viewname);
        
	}
	/*
      @Author : Ghelani Nikunj
      @Desc   : For update data in to database
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
	public function updateTelemarketingData() {
			/*pr($_POST);
			die();*/
			$id = $this->input->post('update_id');
			$this->form_validation->set_rules('tele_name', 'Company Tele Name', 'required');
			$tele_data['tele_name'] = $this->input->post('tele_name');
			$tele_data['user_id'] = $this->input->post('user_id');
			$tele_data['company_name'] = $this->input->post('company_name');
			$tele_data['phone_no'] = $this->input->post('phone_no');
			$tele_data['status'] = $this->input->post('status');
			$tele_data['remark'] = $this->input->post('remark',false);
			$tele_data['created_date'] = datetimeformat();
			//Insert Record in Database
			$where = array('tele_id' => $id);
			$success_insert = $this->common_model->update(TELE_MARKETING, $tele_data,$where);
			if ($success_insert) {
				$msg = $this->lang->line('tele_edit_msg');
				$this->session->set_flashdata('msg', $msg);
			}
			redirect($this->viewname);
        
	}
	/*
      @Author : Ghelani Nikunj
      @Desc   : For delete data 
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
	public function deletedata($id) {
        $redirect_link = $this->input->get('link');
        $data['lead_view'] = $this->viewname;
        if (!empty($id)) {
            $where = array('tele_id' => $id);
            $tele_data['is_delete'] = 1;
            $delete_suceess = $this->common_model->update(TELE_MARKETING, $tele_data, $where);
            if ($delete_suceess) {
                $msg = $this->lang->line('ticket_del_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            unset($id);
        }
		redirect($this->viewname); //Redirect On Listing page
		
        
    }
    
    /*
      @Author : Ghelani Nikunj
      @Desc   : For export data 
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
    
    function exportTeleMarketing() 
    {
        $this->Tele_model->exportProduct();
        
        redirect('Product');
    }
    /*
      @Author : Ghelani Nikunj
      @Desc   : For import data in to database
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
     function importContact()
    {
        $data['modal_title'] = $this->lang->line('import_contact');
        $data['submit_button_title'] = $this->lang->line('import_contact');
        $data['sales_view'] = $this->viewname;

        $data['main_content'] = '/importContact';
       // $data['js_content'] = '/loadJsFiles';
        $this->load->view('/importContact', $data);
        
    }
    /*
      @Author : Ghelani Nikunj
      @Desc   : For import data save in to database
      @Input 	:
      @Output	:
      @Date   : 26/06/2016
     */
    
    function importContactdata()
    {
         
        $config['upload_path'] = './uploads/csv_tele_marketing';
        $config['allowed_types'] = '*';
        $config['max_size'] = 40480;
        $new_name = time()."_".str_replace(' ','_', $_FILES["import_file"]['name']);
        $config['file_name'] = $new_name;
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config); 
        if ( !$this->upload->do_upload('import_file'))
        {
            $msg = $this->upload->display_errors();
            $this->session->set_flashdata('msg', $msg);
        }
        else
        {
            $file_path =  './uploads/csv_tele_marketing/'.$new_name; 
            
            $this->load->library('excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file_path);
            
            $cell_collection = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			
            $key_contact_name = array_search('Contact Name', $cell_collection[1]);
            $key_company_name = array_search('Company Name', $cell_collection[1]);
            $key_phone_no = array_search('Phone no', $cell_collection[1]);
            $key_remarks = array_search('Remarks', $cell_collection[1]);
            $key_status = array_search('status', $cell_collection[1]);
			$key_firstname = array_search('Employee firstname', $cell_collection[1]);
			$key_lastname = array_search('Employee lastname', $cell_collection[1]);            
			$key_email = array_search('Employee email', $cell_collection[1]);            
           
            $chk_file_column = array('Contact Name','Company Name','Phone no','Remarks','status','Employee firstname','Employee lastname','Employee email');
           
            $diff_array = array_diff($chk_file_column, $cell_collection[1]);
           
            if(!empty($diff_array))
            {
                $this->session->set_flashdata('msg', lang('WRONG_FILE_FOMRMAT'));
                redirect($this->viewname); 
            }
                
            unset($cell_collection[1]);
           
            $count_success = 0;
            $count_fail = 0;
            $total_record = count($cell_collection);
            $status_id='';
            $user_id='';
          
            foreach ($cell_collection as $cell)
            {
				
                
                if(strtolower($cell[$key_status])=='Positive information requested'){
					
					$status_id=1;
					
					}
					if(strtolower($cell[$key_status])=='Positive Demo Schedule'){
					
					$status_id=2;
					
					}
					if(strtolower($cell[$key_status])=='Positive become client'){
					
					$status_id=3;
					
					}
					if(strtolower($cell[$key_status])=='Negative not interested'){
					
					$status_id=4;
					
					}
					if(strtolower($cell[$key_status])=='voicemail'){
					
					$status_id='5';
					
					}
					if($cell[$key_status]=='Call back request'){
					
					$status_id=6;
					
					}
					if($cell[$key_status]=='Not Called'){
					
					$status_id=0;
					
					}
					
					
                $value_contact_name = trim($cell[$key_contact_name]);
                $value_company_name = trim(strtolower($cell[$key_company_name]));
                $value_phone_no = trim($cell[$key_phone_no]);
                $value_remarks = trim($cell[$key_remarks]);
                $value_status = trim($cell[$key_status]);
                $value_firstname = trim($cell[$key_firstname]);
                $value_lastname = trim($cell[$key_lastname]);
                $value_email = trim($cell[$key_email]);
            
            /*for empname*/
             $table1 = LOGIN . ' as l';
			 $match1 = "l.firstname='".$value_firstname."' and l.lastname='".$value_lastname."' and l.email='".$value_email."'";
			 $fields1 = array("l.firstname,l.login_id,l.email");
			 $data['empdata'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
			 /*pr($data['empdata']);
			 die('here');*/
                if($data['empdata'][0]['email']==$value_email){
					
					$user_id=$data['empdata'][0]['login_id'];
					
					}
					
                 if (!preg_match("/^([^0-9]*)$/", $value_contact_name)) {
							// valid mobile number
							//echo $value_contact_name;
							$msg = "Invalid Contact Name";
							$this->session->set_flashdata('msg', $msg);
							break;
						}
				   if (!preg_match("/^([^0-9]*)$/", $value_company_name)) {
							// valid mobile number
							//echo $value_contact_name;
							$msg = "Invalid Company Name";
							$this->session->set_flashdata('msg', $msg);
							break;
						}		
						
					if (preg_match("^\(\d{3}\) \d{3}-\d{4}$", $value_phone_no)) {
							// valid mobile number
							//echo $value_contact_name;
							$msg = "Invalid Phone No";
							$this->session->set_flashdata('msg', $msg);
							break;
						}					
                    
                    $tele_data['tele_name'] = $value_contact_name;
                    $tele_data['company_name'] = $value_company_name;
                    $tele_data['phone_no'] = $value_phone_no;
                    $tele_data['remark'] = $value_remarks;
                    $tele_data['status'] = $status_id;
                    $tele_data['is_delete'] = 0;
                    $tele_data['created_date'] = datetimeformat();
                    $tele_data['user_id'] = $user_id;
                    //$tele_data['user_id'] = $this->session->userdata('LOGGED_IN')['ID'];
                /*
                $table_grp = CONTACT_MASTER;
                $fields_grp = array("contact_id");
                $match_grp = array('is_delete' => '0', 'status' => '1','email'=>$data['email']);
                $contact_data_arr = $this->common_model->get_records($table_grp,$fields_grp, '', '', $match_grp);
                empty($contact_data_arr) && 
                */
                if($tele_data['tele_name']!='')
                {
                  
                    $company_id = $this->common_model->insert(TELE_MARKETING, $tele_data);
                    
                  
                    if($company_id )
                    {
                        $count_success++;
                    }else
                    {
                        $count_fail++;
                    }
                }else
                {
                  
                    $count_fail++;
                }
                $msg = "Succesfully Imported ! Total Record : $total_record, Successfully Imported : $count_success, Fail Record : $count_fail ";
					$this->session->set_flashdata('msg', $msg);
               
            }
            
            //$msg = "Succesfully Imported ! Total Record : $total_record, Successfully Imported : $count_success, Fail Record : $count_fail ";
            //$this->session->set_flashdata('msg', $msg);
        }
        
        
        redirect($this->viewname); 
    }
    
    
}

