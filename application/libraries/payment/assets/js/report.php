<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Students extends CI_Controller {
	
	var $tablename 	= 'students';
	var $keycolumn  = 'student_first_name';
	var $keypk = 'student_id';
	var $index = 3;
	var $class_name = 'students';
	function __construct()
    {
        parent::__construct();
		/* Location: ./application/config/site_config.php */
		$this->load->library('upload'); 
		$this->load->model('teachers/mod_main');
		$this->load->model('teachers/mod_tech_students');
		$this->load->model('mod_pagenation');
		$this->load->config( 'site_config', true );
    }
	public function index()
	{
		$data = array();$currPg=1;
		$data['session'] = $this->session->all_userdata('memberdata');
		$data['certificate'] = $this->session->all_userdata('certificate');
		$data['session_package'] = $this->session->all_userdata('memberpackage');			
		if(isset($data['session']['memberdata']['logged_in'])){
			if((isset($data['session']['certificate']['certificate_id']))){	
			$data['certificate'] = $this->session->userdata('certificate');					
			$where_in = array();
			$where_in = explode(',',$data['session']['memberdata']['access_menu']);
			$data['menus']  = $this->mod_main->get_menus($where_in);
			$data['title'] 		= ucfirst($data['session']['certificate']['certificate_name']);
			$data['header'] 	= $data['menus'][$this->index]->menu_title;
			$data['header'] 	= $data['menus'][$this->index]->menu_title;
			$data['subtitle'] 	= $data['menus'][$this->index]->menu_note;
			$data['menulink'] 	= $data['menus'][$this->index]->menu_links;
			$data['menuimg'] 	= $data['menus'][$this->index]->menu_img;
			
			$data['add'] 		= $data['menus'][$this->index]->is_add;
			$data['edit'] 		= $data['menus'][$this->index]->is_edit;
			$data['copy'] 		= $data['menus'][$this->index]->is_copy;
			$data['delete'] 	= $data['menus'][$this->index]->is_delete;
			$data['search'] 	= $data['menus'][$this->index]->is_search;
			
			$data['configdata'] = $this->config->item('site_config');	
			
			$difflimit			=	$data['configdata']['record_pre_page'];		
			$where = array();
			$where['s.certificate_id '] 	= $data['session']['certificate']['certificate_id'];
			$where['s.id_added']   		= $data['session']['memberdata']['member_id'];		
			$data['dataset']	= $this->mod_tech_students->get_DataArr($this->tablename, $this->keypk, $this->keycolumn, $where,'','desc',$difflimit);		
			$totalRec			= $this->mod_tech_students->get_DataTotal($this->tablename, $this->keypk, $this->keycolumn, $where,'');
			$pagesdata			= $this->mod_pagenation->getPagerData($totalRec,$difflimit,0);			
			$data['pagination'] = $this->mod_pagenation->createLink($pagesdata);
			$data['pageinfo']	= $this->mod_pagenation->pageText($totalRec,$currPg,$difflimit);	
			$this->load->view('teachers/view_header',$data);
			$this->load->view('teachers/'.$this->class_name.'/view_list_top',$data);
			$this->load->view('teachers/'.$this->class_name.'/view_list_page',$data);
			$this->load->view('teachers/'.$this->class_name.'/view_list_bot',$data);
			$this->load->view('teachers/view_footer',$data);
			}else{
				$this->session->set_flashdata('errormsg', 'Please select or create a new certificate.');
				redirect('teachers/dashboard/', 'refresh');
			}
		}else{
			redirect('teachers/home/login/', 'refresh');
		}
	}
	public function update($id='',$todo='')
	{
		$data = array();
		$data['session'] = $this->session->all_userdata('memberdata');
		$data['certificate'] = $this->session->all_userdata('certificate');
		$data['session_package'] = $this->session->all_userdata('memberpackage');	
		if(isset($data['session']['memberdata']['logged_in'])){	
			if((isset($data['session']['certificate']['certificate_id']))){	
				$data['certificate'] = $this->session->userdata('certificate');		
				$where_in = array();
				$where_in = explode(',',$data['session']['memberdata']['access_menu']);
				$data['menus']  = $this->mod_main->get_menus($where_in);
				$data['title'] 		= ucfirst($data['session']['certificate']['certificate_name']);
				$data['header'] 	= $data['menus'][$this->index]->menu_title;
				$data['header'] 	= $data['menus'][$this->index]->menu_title;
				$data['subtitle'] 	= $data['menus'][$this->index]->menu_note;
				$data['menulink'] 	= $data['menus'][$this->index]->menu_links;
				$data['menuimg'] 	= $data['menus'][$this->index]->menu_img;
				
				$data['add'] 		= $data['menus'][$this->index]->is_add;
				$data['edit'] 		= $data['menus'][$this->index]->is_edit;
				$data['copy'] 		= $data['menus'][$this->index]->is_copy;
				$data['delete'] 	= $data['menus'][$this->index]->is_delete;
				$data['search'] 	= $data['menus'][$this->index]->is_search;
				
				$data['dojob'] 		= 'Update';
				$data['configdata'] = $this->config->item('site_config');						
			if($data['edit']!=1){
					redirect('teachers/'.$this->class_name, 'refresh');
			}
			if($todo=='save'){
				$this->form_validation->set_rules('student_first_name','first name','required');
				$this->form_validation->set_rules('student_last_name','last name','required');
				$this->form_validation->set_rules('student_username','email','required|valid_email');
				if ($this -> form_validation -> run() === FALSE ){
					echo form_error('student_first_name').'@@@'.form_error('student_last_name').'@@@'.form_error('student_username');
				}else{
					$where= array();
					$where['student_id']		=	$this->security->xss_clean($id);				
					$update['student_username']	= $this->security->xss_clean(trim($this->input->post('student_active_email')));
					if(trim($this->input->post('student_password'))!=''){
						$update['student_password ']= md5(md5($this->security->xss_clean(trim($this->input->post('student_password')))));
					}
					$update['student_first_name']= $this->security->xss_clean(trim($this->input->post('student_first_name')));
					$update['student_last_name']= $this->security->xss_clean(trim($this->input->post('student_last_name')));
					//$update['student_username'] = $this->security->xss_clean(trim($this->input->post('student_username')));
					//$update['student_active_email'] = $this->security->xss_clean(trim($this->input->post('student_username')));
					$update['date_edited']   	= 	date('Y-m-d');
					$update['ip_edited']   		= 	$_SERVER['REMOTE_ADDR'];
					$update['id_edited']   		= 	$data['session']['memberdata']['member_id'];
					if($this->mod_tech_students->get_UpdateData($this->tablename, $update, $where)==1){
						$this->session->set_flashdata('message', '<strong>'.$update['student_first_name'].'</strong> - Edited successfully.');
						echo 1;
					}
				}				
			}else{					
				$where= array();
				$where['student_id']=$id;				
				$data['dataset']=$this->mod_tech_students->getSingleData($this->tablename,$where);
				$this->load->view('teachers/view_header',$data);
				$this->load->view('teachers/'.$this->class_name.'/view_form_page',$data);
				$this->load->view('teachers/view_footer',$data);
			}
			}else{
				$this->session->set_flashdata('errormsg', 'Please select or create a new certificate.');
				redirect('teachers/dashboard/', 'refresh');
			}
		}else{
			redirect('teachers/home/login/', 'refresh');
		}
	}	
	public function insert($todo='')
	{
		$data = array();
		$data['session'] = $this->session->all_userdata('memberdata');
		$data['certificate'] = $this->session->all_userdata('certificate');
		$data['session_package'] = $this->session->all_userdata('memberpackage');				
		if(isset($data['session']['memberdata']['logged_in'])){
			if((isset($data['session']['certificate']['certificate_id']))){
				$data['certificate'] = $this->session->userdata('certificate');				
				$where_in = array();
				$where_in = explode(',',$data['session']['memberdata']['access_menu']);
				$data['menus']  = $this->mod_main->get_menus($where_in);
				$data['title'] 		= ucfirst($data['session']['certificate']['certificate_name']);
				$data['header'] 	= $data['menus'][$this->index]->menu_title;
				$data['subtitle'] 	= $data['menus'][$this->index]->menu_note;
				$data['menulink'] 	= $data['menus'][$this->index]->menu_links;
				$data['menuimg'] 	= $data['menus'][$this->index]->menu_img;
				
				$data['add'] 		= $data['menus'][$this->index]->is_add;
				$data['edit'] 		= $data['menus'][$this->index]->is_edit;
				$data['copy'] 		= $data['menus'][$this->index]->is_copy;
				$data['delete'] 	= $data['menus'][$this->index]->is_delete;
				$data['search'] 	= $data['menus'][$this->index]->is_search;
				
				$data['dojob'] 		= 'Insert';
				$data['configdata'] = $this->config->item('site_config');
			
			$where= array();				
				$where['id_added']=$data['session']['memberdata']['member_id'];				
				$dataset=$this->mod_tech_students->getSingleData($this->tablename,$where);
			
			
			if(count($dataset) >= $data['session_package']['memberpackage']['package_details'][2]){	
				$this->session->set_flashdata('errormsg', 'Do not add more than '.$data['session_package']['memberpackage']['package_details'][2].' students.');			
				redirect('teachers/'.$this->class_name, 'refresh');
			}
			if($data['add']!=1){
					redirect('teachers/'.$this->class_name, 'refresh');
			}					
			if($todo=='save'){
				
				$this->form_validation->set_rules('student_first_name','first name','required');
				$this->form_validation->set_rules('student_last_name','last name','required');
				$this->form_validation->set_rules('student_username','email','required|valid_email|is_unique[students.student_username]');
				$this->form_validation->set_rules('student_password','password','required');
				
				if ($this -> form_validation -> run() === FALSE ){
					echo form_error('student_first_name').'@@@'.form_error('student_last_name').'@@@'.form_error('student_username');
				}else{
						$insert['user_group_id'] 		= 3;
						$insert['certificate_id '] 		= $data['session']['certificate']['certificate_id'];
						$insert['student_username']		= $this->security->xss_clean(trim($this->input->post('student_username')));
						$insert['student_password ']	= md5(md5($this->security->xss_clean(trim($this->input->post('student_password')))));
						$insert['student_first_name'] 	= $this->security->xss_clean(trim($this->input->post('student_first_name')));
						$insert['student_last_name'] 	= $this->security->xss_clean(trim($this->input->post('student_last_name')));
						$insert['student_active_email'] = $this->security->xss_clean(trim($this->input->post('student_username')));
						$insert['date_added'] 			= date('Y-m-d');
						$insert['is_active']  			= 1;
						$insert['ip_added']   			= $_SERVER['REMOTE_ADDR'];
						$insert['id_added']   			= $data['session']['memberdata']['member_id'];
						if($this->mod_tech_students->get_InsertData($this->tablename, $insert)==1){
							$this->session->set_flashdata('message', '<strong>'.$insert['student_first_name'].'</strong> - Added successfully.');
							echo 1;
						}
				}
									
			}else{	
				$this->load->view('teachers/view_header',$data);
				$this->load->view('teachers/'.$this->class_name.'/view_form_page',$data);
				$this->load->view('teachers/view_footer',$data);
			}
		}else{
					$this->session->set_flashdata('errormsg', 'Please select or create a new certificate.');
					redirect('teachers/dashboard/', 'refresh');
			}
		}else{
			redirect('teachers/home/login/', 'refresh');
		}
	}
	
	public function todo($jobtype){
		$data = array();
		$data['session'] = $this->session->all_userdata('memberdata');	
		$data['certificate'] = $this->session->userdata('certificate');		
		$data['configdata'] = $this->config->item('site_config');
		$where_in = array();
		$where_in = explode(',',$data['session']['memberdata']['access_menu']);
		$data['menus']  = $this->mod_main->get_menus($where_in);
		$data['title'] 		= ucfirst($data['session']['certificate']['certificate_name']);
		$data['header'] 	= $data['menus'][$this->index]->menu_title;
		$data['header'] 	= $data['menus'][$this->index]->menu_title;
		$data['subtitle'] 	= $data['menus'][$this->index]->menu_note;
		$data['menulink'] 	= $data['menus'][$this->index]->menu_links;
		$data['menuimg'] 	= $data['menus'][$this->index]->menu_img;
				
		$data['add'] 		= $data['menus'][$this->index]->is_add;
		$data['edit'] 		= $data['menus'][$this->index]->is_edit;
		$data['copy'] 		= $data['menus'][$this->index]->is_copy;
		$data['delete'] 	= $data['menus'][$this->index]->is_delete;
		$data['search'] 	= $data['menus'][$this->index]->is_search;
		$difflimit	=	$data['configdata']['record_pre_page'];		
		if(!isset($data['session']['memberdata']['logged_in'])){	
			redirect('teachers/home/login/', 'refresh');
		}else{
				$currPg = $this->input->post('currpage');
				$offset = $this->input->post('offset');
				switch($jobtype)
				{
					case "search":	$keyword 		= trim($this->input->post('keyword'));	
									$where 			= array();
									$where['s.certificate_id '] 	= $data['session']['certificate']['certificate_id'];
									$where['s.id_added']   		= $data['session']['memberdata']['member_id'];																
									$data['dataset']	= $this->mod_tech_students->get_DataArr($this->tablename, $this->keypk, $this->keycolumn, $where,$keyword,'desc',$difflimit, $offset);		
									$totalRec			= $this->mod_tech_students->get_DataTotal($this->tablename, $this->keycolumn, $this->keycolumn, $where,'');
									$pagesdata			= $this->mod_pagenation->getPagerData($totalRec,$difflimit,$currPg);			
									$data['pagination'] = $this->mod_pagenation->createLink($pagesdata);	
									$data['pageinfo']	= $this->mod_pagenation->pageText($totalRec,$currPg,$difflimit);
									$data['keyword']	= $keyword;
									$this->load->view('teachers/'.$this->class_name.'/view_list_page',$data);
									break;
					case "page":	$keyword 		= trim($this->input->post('keyword'));	
									$where 			= array();																	
									$where['s.certificate_id '] 	= $data['session']['certificate']['certificate_id'];
									$where['s.id_added']   		= $data['session']['memberdata']['member_id'];		
									$data['dataset']	= $this->mod_tech_students->get_DataArr($this->tablename, $this->keypk, $this->keycolumn, $where, $keyword, 'desc', $difflimit, $offset);		
									$totalRec			= $this->mod_tech_students->get_DataTotal($this->tablename, $this->keypk, $this->keycolumn, $where,$keyword);
									$pagesdata			= $this->mod_pagenation->getPagerData($totalRec,$difflimit,$currPg);			
									$data['pagination'] = $this->mod_pagenation->createLink($pagesdata);
									$data['pageinfo']	= $this->mod_pagenation->pageText($totalRec,$currPg,$difflimit);	
									$this->load->view('teachers/'.$this->class_name.'/view_list_page',$data);
									break;
					case "publish":	$publishid 		= $this->input->post('recid');
									$field 			= $this->input->post('field');	
									$orderby 		= $this->input->post('orderby');									
									$this->mod_tech_students->setPublish($this->tablename , 'student_id', $publishid);	
									$where 			= array();							
									$where['s.certificate_id '] 	= $data['session']['certificate']['certificate_id'];
									$where['s.id_added']   		= $data['session']['memberdata']['member_id'];		
									$data['dataset']	= $this->mod_tech_students->get_DataArr($this->tablename, $this->keypk, $this->keycolumn, $where, '', 'desc', $difflimit, $offset);			
									$totalRec			= $this->mod_tech_students->get_DataTotal($this->tablename, $this->keypk, $this->keycolumn, $where,'');
									$pagesdata			= $this->mod_pagenation->getPagerData($totalRec,$difflimit,$currPg);			
									$data['pagination'] = $this->mod_pagenation->createLink($pagesdata);
									$data['pageinfo']	= $this->mod_pagenation->pageText($totalRec,$currPg,$difflimit);	
									$this->load->view('teachers/'.$this->class_name.'/view_list_page',$data);
									break;
					case "orderby":	$field 			= $this->input->post('field');	
									$orderby 		= $this->input->post('orderby');									
									$where 			= array();
									$where['is_status'] = 'Y';									
									$totalRec		= $this->mod_ecom_categories->get_CateTotal($this->tablename, $this->keycolumn, $where, '');
									$categorydata	= $this->mod_pagenation->getPagerData($totalRec,$this->difflimit,$currPg);
									$dataset		= $this->mod_ecom_categories->get_CateArr($table, $this->keycolumn, $where, '', $orderby,$categorydata->limit,$currPg);	
									$categories		= $this->mod_pagenation->createLink($categorydata);
									echo $this->ajaxLoadView($dataset,$categories,$currPg);
									break;
					case "delete":	$field 			= $this->input->post('field');	
									$orderby 		= $this->input->post('orderby');	
									$delid 			= $this->input->post('recDelid');								
									$where 			= array();
									$wheredel 			= array();
									$wheredel['student_id'] = $delid;	
									$this->mod_tech_students->get_DeleteData($this->tablename,$wheredel);
									$where['s.certificate_id '] 	= $data['session']['certificate']['student_id'];
									$where['s.id_added']   		= $data['session']['memberdata']['member_id'];		
									$data['dataset']	= $this->mod_tech_students->get_DataArr($this->tablename, $this->keypk, $this->keycolumn, $where, '', 'desc', $difflimit, $offset);				
									$totalRec			= $this->mod_tech_students->get_DataTotal($this->tablename, $this->keypk, $this->keycolumn, $where,'');
									$pagesdata			= $this->mod_pagenation->getPagerData($totalRec,$difflimit,$currPg);			
									$data['pagination'] = $this->mod_pagenation->createLink($pagesdata);	
									$data['pageinfo']	= $this->mod_pagenation->pageText($totalRec,$currPg,$difflimit);
									$this->load->view('teachers/'.$this->class_name.'/view_list_page',$data);
									break;	
				     case "setcertificate": $certificate_id	= $this->input->post('certificate_id');
					 						$where 			= array();
											$where['certificate_id'] = $certificate_id;	
											$certificate = $this->mod_tech_students->get_DataData($this->tablename,$where);
											$certificate_sess = array(
											   'certificate_id'		=> $certificate[0]->certificate_id,
											   'certificate_name'	=> $certificate[0]->certificate_name,						   
											   'certificate_code'  	=> $certificate[0]->certificate_code,
											   'certificate_logo'	=> $certificate[0]->certificate_logo
											 );
					 						$this->session->set_userdata('certificate', $certificate_sess);
					 						break;				
				}
			}		
		}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */