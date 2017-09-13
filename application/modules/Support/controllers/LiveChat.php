<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LiveChat extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->knowledgeBase = $this->uri->segment(2);
        $this->listofmaincategory_data = $this->uri->segment(3);
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : view of Live chat page
      @Input  :
      @Output :
      @Date   : 016/03/2016
     */

    public function index() {
		
        /**
         * tasks pagination Function called before data variable
         */
		$searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
		//echo $sortfield;die($sortfield);
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $data['header'] = array('menu_module'=>'support');
        //$data['main_content'] = $this->module . '/LiveChat/LiveChat';
        $data['project_view'] = $this->module . '/LiveChat/' . $this->viewname;
        $data['js_content'] = 'loadJsFiles';
        $data['project_view'] = $this->viewname;
        $data['activities_total'] = 0;
        $data['activities']       = 0;
		$searchsort_session = $this->session->userdata('chat_data');
		if(!empty($sortfield) && !empty($sortby))
        {
            
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
		 else
        {
		
		if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'chat_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }
		
		 if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url']    = base_url().$this->viewname.'/index';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
		
        //Sear
		/*live chat listing start*/
        $user_id=$this->session->userdata['LOGGED_IN']['ID'];
        $match1 = "lc.to_id=".$user_id;
		$groupby="lc.to_id,lc.from_id";
        $table1 = LIVE_CHAT . ' as lc';

        $fields1 = array("lc.time,lc.chat_id,lc.to_id,lc.from_id,lc.msg,lc.status,(select concat(firstname,' ',lastname) from blzdsk_login where login_id=lc.to_id) as toname,(select concat(firstname,' ',lastname) from blzdsk_login where login_id=lc.from_id) as fromname");
        $data['chat_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1, '', '', '', $sortfield,$sortby, $groupby, '');
		/*pr($data['chat_data']);
		die('here');*/
		/*live chat listing end*/
		if($this->input->post('result_type') == 'ajax'){
           
            $this->load->view('LiveChat/AjaxChatList',$data);
        } else {
			
             $data['main_content'] = $this->module . '/LiveChat/LiveChat';
            $this->parser->parse('layouts/SupportTemplate', $data);
            //$this->parser->parse('layouts/CampaignTemplate', $data);
        }
        
        
        
        

    }
	 /*
      @Author : Ghelani Nikunj
      @Desc   : view of add main catedory page
      @Input  :
      @Output :
      @Date   : 02/03/2016
     */
	 public function chat() {
		 $fid='';
		 $myid=$this->session->userdata['LOGGED_IN']['ID'];
		 if(isset($_GET['to_id'])){
			 
			 $fid=$_GET['to_id'];
			 
		 }
		$data = array();
        $data['project_view'] = $this->viewname;
		
         $redirect_link=$this->input->post('redirect_link');

         $table = LOGIN.' as l';
         $where = array("l.status" => "1");
         $fields = array("l.*");
         $data['user']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
			
		 $data['fid']=$fid;
		 $data['myid']=$myid;
        $this->load->view('LiveChat/chat',$data);
//        $this->parser->parse('layouts/DashboardTemplate', $data);
    }
	 public function chatM() {
		
		 $json = '';
		
		 if(isset($_GET['rq'])){
			 
			 switch($_GET['rq']){
				  
				 case 'new':
					
					$chat['msg'] = $_POST['msg'];
					$chat['to_id'] = $_POST['mid'];
					$chat['from_id'] = $_POST['fid'];
					$chat['status']='1';
					$chat['time']   = datetimeformat();
					if(empty($chat['msg'])){
						//$json = array('status' => 0, 'msg'=> 'Enter your message!.');
					}else{
						$qur=$this->common_model->insert(LIVE_CHAT,$chat);
						
					if($qur){
						$table1 = LIVE_CHAT . ' as lc';
						$match1 = "lc.chat_id=".$qur;
						$fields1 = array("lc.time,lc.chat_id,lc.to_id,lc.from_id,lc.msg,lc.status,(select concat(firstname,' ',lastname) from blzdsk_login where login_id=lc.to_id) as toname,(select concat(firstname,' ',lastname) from blzdsk_login where login_id=lc.from_id) as fromname");
						$data['live_chat'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
						//$qurGet = mysql_query("select * from msg where id='".mysql_insert_id()."'");
						foreach($data['live_chat'] as $row){
							
							$json = array('toname'=>$row['toname'],'fromname'=>$row['fromname'],'status' => 1, 'msg' => $row['msg'], 'lid' => $qur, 'time' => $row['time'],'to'=>$row['to_id'],'from'=>$row['from_id']);
						}
						
						}else{
							$json = array('toname'=>$row['toname'],'fromname'=>$row['fromname'],'status' => 0, 'msg'=> 'Unable to process request.');
						}
					}
				break;
				case 'msg':
				
					$myid = $_POST['mid'];
					$fid = $_POST['fid'];
					$lid = $_POST['lid'];
					if(empty($myid)){
						
					}else{
						
					
						$table2 = LIVE_CHAT . ' as lc';
						$match2 = "lc.to_id=".$myid." AND lc.from_id=".$fid." AND lc.status=1";
						$fields2 = array("lc.time,lc.chat_id,lc.to_id,lc.from_id,lc.msg,lc.status,(select concat(firstname,' ',lastname) from blzdsk_login where login_id=lc.to_id) as toname,(select concat(firstname,' ',lastname) from blzdsk_login where login_id=lc.from_id) as fromname");
						$data['live_chat'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
					
						$to_name=array();
						foreach($data['live_chat'] as $toname_value){
							//pr($toname_value);
							$to_name['toname']=$toname_value['toname'];
							$to_name['fromname']=$toname_value['fromname'];
							
						}
						
						if(count($data['live_chat']) > 0){
							//die('aa');
							
							$json = array('status' => 1,'toname'=>$to_name['toname'],'fromname'=>$to_name['fromname']);
							
						}else{
						
							
							$json = array('status' => 0);
						}
					}
				break;
				case 'NewMsg':
					//pr($_POST);die();
					$myid = $_POST['mid'];
					$fid = $_POST['fid'];
					$table3 = LIVE_CHAT . ' as lc';
					$match3 = "lc.to_id=".$myid." AND lc.from_id=".$fid." AND lc.status = 1";
					$sortfield='lc.chat_id';
					$sortby='desc';
					$fields3 = array("lc.time,lc.chat_id,lc.to_id,lc.from_id,lc.msg,lc.status,(select concat(firstname,' ',lastname) from blzdsk_login where login_id=lc.to_id) as toname,(select concat(firstname,' ',lastname) from blzdsk_login where login_id=lc.from_id) as fromname");
					//$data['live_chat'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);
					$data['live_chat'] = $this->common_model->get_records($table3, $fields3, '', '', $match3, '', '1', '', $sortfield, $sortby);	
					//echo "<pre>";print_r($data['live_chat']);die();
					/*$qur = mysql_query("select * from msg where `to`='$myid' && `from`='$fid' && `status`=1 order by id desc limit 1");
					
					while($rw = mysql_fetch_array($qur)){
						$json = array('toname'=>$rw['toname'],'fromname'=>$rw['fromname'],'status' => 1, 'msg' => '<div>'.$rw['msg'].'</div>', 'lid' => $rw['id'], 'time'=> $rw['time'],'to'=>$rw['to'],'from'=>$rw['from']);
					}*/
					foreach($data['live_chat'] as $rw){
							$json = array('toname'=>$rw['toname'],'fromname'=>$rw['fromname'],'status' => 1, 'msg' => '<div>'.$rw['msg'].'</div>', 'lid' => $rw['chat_id'], 'time'=> $rw['time'],'to'=>$rw['to_id'],'from'=>$rw['from_id']);
					}
					// update status
					
					
					$chat_data['status']='0';
					$where = array('to_id' => $myid,'from_id'=>$fid);
					$up = $this->common_model->update(LIVE_CHAT, $chat_data, $where);
					//$up = mysql_query("UPDATE `msg` SET  `status` = '0' WHERE `to`='$myid' && `from`='$fid'");
				break;
			 }
			 
		 }
		 header('Content-type: application/json');
		 echo json_encode($json);
		 
		
		 
	 }
	/*
      @Author : Ghelani Nikunj
      @Desc   : add main category
      @Input  :
      @Output :
      @Date   : 02/03/2016
     */
	
	public function saveMainCategory()
    {

        $product_data = $this->input->post('product_id');
        $product = implode("," ,$product_data);

        $knowledgebasecat['category_name'] = $this->input->post('category_name');
        $client_visible_data = '0';
        if($this->input->post('client_visible') == 'on'){
            $client_visible_data = 1;
        }
        $knowledgebasecat['client_visible'] = $client_visible_data;

        $product_related_data = '0';
        if($this->input->post('product_related')=='on')
        {
            $product_related_data = '1';
        }

        $knowledgebasecat['product_related'] = $product_related_data;
        $knowledgebasecat['article_owner'] = $this->input->post('article_owner');
        $knowledgebasecat['product_id'] = $product;
        //Transfering data to Model
        $this->common_model->insert(KNOWLEDGEBASE_MAIN_CATEGORY,$knowledgebasecat);
         //echo $this->db->last_query();exit;
        $data['message'] = $this->lang->line('category_add_msg');
        //$msg = $this->lang->line('category_add_msg');
        //$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        //Loading View
         redirect($this->viewname . '/KnowledgeBase/ListofMainCategory',$data);
    }


    /*
      @Author : Ghelani Nikunj
      @Desc   : view of add sub catedory page
      @Input  :
      @Output :
      @Date   : 03/03/2016
     */
	 
	 public function AddSubCategory() {
		 
        $data = array();
        $data['project_view'] = $this->viewname;
		
         $redirect_link=$this->input->post('redirect_link');
          $fields = array('main_category_id','category_name');
        $data['main_category'] = $this->common_model->get_records('blzdsk_knowledgebase_main_category',$fields);

         $table = LOGIN.' as l';
         $where = array("l.status" => "1");
         $fields = array("l.*");
         $data['user']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

         $pm_params=array();
         $pm_params['table'] = PRODUCT_MASTER.' as pm';
         $pm_params['fields'] = array("pm.product_id,pm.product_name");
         $pm_params['where_in']=array("pm.status" => "1");
         $data['product_info']  = $this->common_model->get_records_array($pm_params);


        $this->load->view('AddSubCategory',$data);
//        $this->parser->parse('layouts/DashboardTemplate', $data);
    } 
    /*
      @Author : Ghelani Nikunj
      @Desc   : add sub category
      @Input  :
      @Output :
      @Date   : 03/03/2016
     */
	
	public function saveSubCategory()
    {

        $product_data = $this->input->post('product_id');
        $product = implode("," ,$product_data);
        
        $sub_cat_data = '0';
        if($this->input->post('sub_cat') == 'on'){
            $sub_cat_data = 1;
        }
        $client_visible_data = '0';
        if($this->input->post('client_visible') == 'on'){
            $client_visible_data = 1;
        }
               $product_related_data = '0';
        if($this->input->post('product_related')=='on')
        {
            $product_related_data = '1';
        }

     $knowledgebasesubcat['sub_category_name'] = $this->input->post('sub_category_name');
     $knowledgebasesubcat['sub_cat'] = $sub_cat_data;
     $knowledgebasesubcat['main_category_id'] = $this->input->post('main_category_id');
     $knowledgebasesubcat['client_visible'] = $client_visible_data;
     $knowledgebasesubcat['article_owner'] = $this->input->post('article_owner');
     $knowledgebasesubcat['product_related'] = $product_related_data;
     $knowledgebasesubcat['product_id'] = $product;

        //Transfering data to Model
        $this->common_model->insert(knowledgebase_sub_category,$knowledgebasesubcat);
        //echo $this->db->last_query();exit;
        $data['message'] = $this->lang->line('category_add_msg');
        //$msg = $this->lang->line('category_add_msg');
        //$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        //Loading View
         redirect($this->viewname . '/KnowledgeBase/ListofSubCategory',$data);

    }
    
    /*
      @Author : Ghelani Nikunj
      @Desc   : view of add sub catedory page
      @Input  :
      @Output :
      @Date   : 03/03/2016
     */
	 
	 public function AddArticle() {
		 
        $data = array();
        $data['project_view'] = $this->viewname;
		
         $redirect_link=$this->input->post('redirect_link');
        $fields = array('main_category_id','category_name');
        $data['main_category'] = $this->common_model->get_records('blzdsk_knowledgebase_main_category',$fields);
         $fields1 = array('sub_category_id','sub_category_name');
        $data['sub_category'] = $this->common_model->get_records('blzdsk_knowledgebase_sub_category',$fields1);
         $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
         $data['article_view'] = $this->viewname;
         $table = LOGIN.' as l';
         $where = array("l.status" => "1");
         $fields = array("l.*");
         $data['user']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

         $pm_params=array();
         $pm_params['table'] = PRODUCT_MASTER.' as pm';
         $pm_params['fields'] = array("pm.product_id,pm.product_name");
         $pm_params['where_in']=array("pm.status" => "1");
         $data['product_info']  = $this->common_model->get_records_array($pm_params);

         
        $this->load->view('AddArticle',$data);
//        $this->parser->parse('layouts/DashboardTemplate', $data);
    } 
    
    /*
      @Author : Ghelani Nikunj
      @Desc   : add sub category
      @Input  :
      @Output :
      @Date   : 03/03/2016
     */
	
	public function saveArticle()
    {
         
        $id = '';
        $opportunity_requirement_id = '';
        //Get input post prospect_id if have
        if ($this->input->post('article_id')) {
            $article_id = $this->input->post('article_id');
        }
        $product_data = $this->input->post('product_id');
        $product = implode("," ,$product_data); 
         $client_visible_data = '0';
        if($this->input->post('client_visible') == 'on'){
            $client_visible_data = 1;
        }
        

        $product_related_data = '0';
        if($this->input->post('product_related')=='on')
        {
            $product_related_data = '1';
        }

        
        $knowledgebasearticle['article_name'] = $this->input->post('article_name');
        $knowledgebasearticle['main_category_id'] = $this->input->post('main_category_id');
        $knowledgebasearticle['sub_category_id'] = $this->input->post('sub_category_id');
        $knowledgebasearticle['article_description'] = $this->input->post('article_description');
        $knowledgebasearticle['client_visible'] = $client_visible_data;
        $knowledgebasearticle['article_owner'] = $this->input->post('article_owner');
        $knowledgebasearticle['product_related'] = $product_related_data;
        $knowledgebasearticle['product_id'] = $product;

//Transfering data to Model
//Insert Record in Database
        if (!empty($article_id)) {
            $where = array('article_id' => $article_id);
            $success_update = $this->common_model->update(KNOWLEDGEBASE_KNOWLEDGE_ARTICLE, $knowledgebasearticle, $where);
            if ($success_update) {
                $msg = $this->lang->line('account_update_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        } //Update Record in Database
        else {

           $article_new_id = $this->common_model->insert(KNOWLEDGEBASE_KNOWLEDGE_ARTICLE,$knowledgebasearticle);

            if ($article_new_id) {
                $msg = $this->lang->line('account_add_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }

        //upload file
        $data['article_view'] = $this->viewname;
        $file_name=array();
        $file_array1=$this->input->post('file_data');

        $file_name=$_FILES['article_files']['name'];
        if(count($file_name)>0 && count($file_array1)>0){
            $differentedImage=array_diff($file_name,$file_array1);
            foreach($file_name as $file)
            {
                if(in_array($file,$differentedImage))
                {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if(!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['article_files']['name'][$key]);
                    unset($_FILES['article_files']['type'][$key]);
                    unset($_FILES['article_files']['tmp_name'][$key]);
                    unset($_FILES['article_files']['error'][$key]);
                    unset($_FILES['article_files']['size'][$key]);
                }
            }

        }
        $_FILES['article_files']=$arr = array_map('array_values', $_FILES['article_files']);
        $upload_data = uploadImage('article_files', knowledge_upload_path, $data['article_view']);
        $knowledgefiles = array();
        foreach($upload_data as $dataname){
            $knowledgefiles[] =$dataname['file_name'];
        }
        $knowledge_file_str = implode(",",$knowledgefiles);

        $file2 = $this->input->post('fileToUpload');
        if(!(empty($file2))){
            $file_data = implode("," ,$file2);
        }else{
            $file_data = '';
        }
        if(!empty($knowledge_file_str) && !empty($file_data)){
            $compaigndata['file'] = $knowledge_file_str.','.$file_data;
        }else if(!empty($knowledge_file_str)){
            $compaigndata['file'] = $knowledge_file_str;
        }else{
            $compaigndata['file'] = $file_data;
        }
        $compaigndata['file_name']=$file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($upload_data, array('file_name' => $img));
            }
        }
        $article_files_data = array();

        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $prospect_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    if ($article_new_id) {
                        $article_files_data[] = ['file_name' => $prospect_files[$i], 'file_path' => $gallery_path[$i], 'article_id' => $article_new_id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    } else {
                        $article_files_data[] = ['file_name' => $prospect_files[$i], 'file_path' => $gallery_path[$i], 'article_id' => $article_id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    }
                }
            }
        }
        if (count($upload_data) > 0) {
            foreach ($upload_data as $files) {
                if ($article_id) {
                    $article_files_data[] = ['file_name' => $files['file_name'], 'file_path' => knowledge_upload_path, 'article_id' => $article_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                } else {
                    $article_files_data[] = ['file_name' => $files['file_name'], 'file_path' => knowledge_upload_path, 'article_id' => $article_new_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }

        if (count($article_files_data) > 0) {
            if ($article_id) {
                $where = array('article_id' => $article_id);
            } else {
                $where = array('article_id' => $article_new_id);
            }
        if (!$this->common_model->insert_batch(KNOWLEDGEBASE_FILE_MASTER, $article_files_data)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }

    $data['message'] = $this->lang->line('category_add_msg');
    //$msg = $this->lang->line('category_add_msg');
    //$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
    //Loading View
    redirect($this->viewname . '/KnowledgeBase/ListofArticle',$data);
    }

    public function upload_file($fileext=''){
        $str = file_get_contents('php://input');
        echo $filename = time().uniqid().".".$fileext;
        file_put_contents($this->config->item('knowledge_img_url').'/'.$filename,$str);
    }

    public function ListofMainCategory(){
        $data['header'] = array('menu_module'=>'support');
        // $data['js_content'] = '/loadJsFiles';
        
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('list_of_main_category_data');
        }

        $searchsort_session = $this->session->userdata('list_of_main_category_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'main_category_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url']    = base_url().$this->viewname.'/index';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(2).'/'.$this->uri->segment(3);
        }

        //Query

        $table = KNOWLEDGEBASE_MAIN_CATEGORY.' as kcm';
        $where = "kcm.is_delete=0";
        //$where = "kcm.is_delete=0";
        $fields = array("kcm.main_category_id,kcm.category_name,kcm.client_visible,kcm.article_owner,kcm.product_related,kcm.product_id,kcm.status,u.firstname,u.lastname,pm.product_id,pm.product_name");
        $join_tables   =  array('blzdsk_login as u' =>'u.login_id= kcm.article_owner ','blzdsk_product_master as pm' =>'pm.product_id= kcm.product_id ');

        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim($searchtext));
            $match=array('kcm.main_category_id'=>$searchtext,'kcm.category_name'=>$searchtext,'u.firstname'=>$searchtext);
            $data['main_category_info']  = $this->common_model->get_records($table,$fields,$join_tables,'lift','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where,'','','1');
        }
        else
        {
            $data['main_category_info']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('list_of_main_category_data', $sortsearchpage_data);

        $data['sales_view'] = $this->viewname;
        $data['header'] = array('menu_module'=>'crm');
        //pr($searchsort_session);exit;
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname.'/ajax_list_main_category',$data);
        } else {
            $data['main_content'] = '/'.$this->listofmaincategory_data;
            $this->parser->parse('layouts/CampaignTemplate', $data);
        }

    }
     public function ListofSubCategory(){
        // $data['js_content'] = '/loadJsFiles';
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('list_of_sub_category_data');
        }

        $searchsort_session = $this->session->userdata('list_of_sub_category_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'sub_category_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url']    = base_url().$this->viewname.'/index';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(2).'/'.$this->uri->segment(3);
        }

        //Query

        $table = KNOWLEDGEBASE_SUB_CATEGORY.' as ksc';
        //$where = array("ksc.status" => "1");
        $where = "ksc.is_delete=0";
        $fields = array("ksc.sub_category_id,ksc.sub_category_name,ksc.sub_cat,ksc.main_category_id,ksc.client_visible,ksc.article_owner,ksc.product_related,ksc.product_id,ksc.status,u.firstname,u.lastname,pm.product_id,pm.product_name,kmc.main_category_id,kmc.category_name");
        $join_tables   =  array('blzdsk_login as u' =>'u.login_id= ksc.article_owner','blzdsk_product_master as pm' =>'pm.product_id= ksc.product_id','blzdsk_knowledgebase_main_category as kmc' =>'kmc.main_category_id= ksc.main_category_id');
     

        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim($searchtext));
            $match=array('ksc.sub_category_id'=>$searchtext,'ksc.category_name'=>$searchtext,'u.firstname'=>$searchtext,'m.category_name'=>$searchtext);
            $data['sub_category_info']  = $this->common_model->get_records($table,$fields,$join_tables,'lift','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where,'','','1');
        }
        else
        {
            $data['sub_category_info']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('list_of_sub_category_data', $sortsearchpage_data);

        $data['sales_view'] = $this->viewname;
        $data['header'] = array('menu_module'=>'crm');
        //pr($searchsort_session);exit;
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname.'/ajax_list_sub_category',$data);
        } else {
            $data['main_content'] = '/'.$this->listofmaincategory_data;
            $this->parser->parse('layouts/CampaignTemplate', $data);
        }

    }
        public function deletedata($id) {
		
//       $id = $this->input->get('id');
//		die($id);
    
        //Delete Record From Database
         $redirect_link=$this->input->get('link');
         $data['main_category'] = $this->viewname;
        if (!empty($id)) {
            $where = array('main_category_id' => $id);
  
            $main_category_data['is_delete']=1;
        
            $delete_suceess = $this->common_model->update(blzdsk_knowledgebase_main_category, $main_category_data, $where);
          
            if ($delete_suceess) {
                $msg = $this->lang->line('category_del_msg');
                $this->session->set_flashdata('msg', $msg);
            }
            else {
            $msg = $this->lang->line('error');
            $this->session->set_flashdata('error', $msg);
        }
            unset($id);
        }
//             echo $this->db->last_query();exit;
        redirect($this->viewname . '/KnowledgeBase/ListofMainCategory'); //Redirect On Listing page
    }
    
   //Edit Category 
    	 public function edit($id) {
        //$id = $this->uri->segment('3');
        $data['project_view'] = $this->viewname;
          
        $redirect_link=$this->input->post('redirect_link');
        
        $fields = array('kmc.*','pm.product_name','login.login_id','login.firstname','login.lastname');
        $where = array("kmc.main_category_id" => $id);
        $main_table = array("knowledgebase_main_category kmc");
        
        $join_tables = array('product_master pm'=>'pm.product_id=kmc.product_id','login'=>'login.login_id=kmc.article_owner');
        
        $data['edit_record'] = $this->common_model->get_records($main_table,$fields,$join_tables,'LEFT','','','','','','','','','',$where);
        
         $table = LOGIN.' as l';
         $where = array("l.status" => "1");
         $fields = array("l.*");
         $data['user']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

         $pm_params=array();
         $pm_params['table'] = PRODUCT_MASTER.' as pm';
         $pm_params['fields'] = array("pm.product_id,pm.product_name");
         $pm_params['where_in']=array("pm.status" => "1");
         $data['product_info']  = $this->common_model->get_records_array($pm_params);
         
         $product_info=array();
        foreach($data['edit_record'] as $row){
            $product_info[] = $row['product_id'];
        }
        $product=$product_info;
        $data['product_data'] = explode(",",$product[0]);
         // echo $this->db->last_query();exit;
        //pr($data['editRecord']);exit;
        $this->load->view('/EditMainCategory',$data);     
    }
    
    
     public function updatedata() {
       $id = $id= $this->input->post('m_id');

        $data['project_view'] = $this->viewname;
          
        $redirect_link=$this->input->post('redirect_link');
         $product_data = $this->input->post('product_id');
        $product = implode("," ,$product_data);
        $updatecat['category_name'] = $this->input->post('category_name');
        $client_visible_data = '0';
        if($this->input->post('client_visible') == 'on'){
            $client_visible_data = 1;
        }
        $updatecat['client_visible'] = $client_visible_data;

        $product_related_data = '0';
        if($this->input->post('product_related')=='on')
        {
            $product_related_data = '1';
        }

        $updatecat['product_related'] = $product_related_data;
        $updatecat['article_owner'] = $this->input->post('article_owner');
        $updatecat['product_id'] = $product;
        $table = KNOWLEDGEBASE_MAIN_CATEGORY.' as kmc';
        $where = "main_category_id = $id ";
        
        $data['update_record'] = $this->db->where($where)->update($table, $updatecat);
         //echo $this->db->last_query();exit;
        
         redirect($this->viewname . '/KnowledgeBase/ListofMainCategory');
         //$this->load->view('ListofMainCategory',$data);
        
     }
    
public function deletedatasub($id) {
		
//       $id = $this->input->get('id');
//		die($id);
    
        //Delete Record From Database
         $redirect_link=$this->input->get('link');
         $data['sub_category'] = $this->viewname;
        if (!empty($id)) {
            $where = array('sub_category_id' => $id);
  
            $sub_category_data['is_delete']=1;
        
            $delete_suceess = $this->common_model->update(blzdsk_knowledgebase_sub_category, $sub_category_data, $where);
          
            if ($delete_suceess) {
                $msg = $this->lang->line('sub_category_del_msg');
                $this->session->set_flashdata('msg', $msg);
            }
            else {
            $msg = $this->lang->line('error');
            $this->session->set_flashdata('error', $msg);
        }
            unset($id);
        }
//             echo $this->db->last_query();exit;
        redirect($this->viewname . '/KnowledgeBase/ListofSubCategory'); //Redirect On Listing page
    }
    
    
    //edit subcategory
    
    	 public function editsubcat($id) {
        //$id = $this->uri->segment('3');
        $data['project_view'] = $this->viewname;
          
        $redirect_link=$this->input->post('redirect_link');
        
        $fields = array('ksc.*','pm.product_name','login.login_id','login.firstname','login.lastname','kmc.main_category_id','kmc.category_name');
        $where = array("ksc.sub_category_id" => $id);
        $main_table = array("knowledgebase_sub_category ksc");
        
        $join_tables = array('product_master pm'=>'pm.product_id=ksc.product_id','login'=>'login.login_id=ksc.article_owner','knowledgebase_main_category kmc'=>'kmc.main_category_id=ksc.main_category_id');
        
        $data['edit_record'] = $this->common_model->get_records($main_table,$fields,$join_tables,'LEFT','','','','','','','','','',$where);
        //echo $this->db->last_query();exit;
        $table = KNOWLEDGEBASE_MAIN_CATEGORY.' as kmc';
         $where = array("kmc.status" => "1");
         $fields = array("kmc.*");
         $data['main_category']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        
         $table = LOGIN.' as l';
         $where = array("l.status" => "1");
         $fields = array("l.*");
         $data['user'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

         $pm_params=array();
         $pm_params['table'] = PRODUCT_MASTER.' as pm';
         $pm_params['fields'] = array("pm.product_id,pm.product_name");
         $pm_params['where_in']=array("pm.status" => "1");
         $data['product_info']  = $this->common_model->get_records_array($pm_params);
         
         $product_info=array();
        foreach($data['edit_record'] as $row){
            $product_info[] = $row['product_id'];
        }
        $product=$product_info;
        $data['product_data'] = explode(",",$product[0]);
          
        //pr($data['editRecord']);exit;
        $this->load->view('/EditSubCategory',$data);     
    }
      public function updatesubcat() {
       $id = $id= $this->input->post('s_id');

        $data['project_view'] = $this->viewname;
          
        $redirect_link=$this->input->post('redirect_link');
        $product_data = $this->input->post('product_id');
        $product = implode("," ,$product_data);
        $updatesubcat['sub_category_name'] = $this->input->post('sub_category_name');
        $sub_cat_data = '0';
        if($this->input->post('sub_cat') == 'on'){
            $sub_cat_data = 1;
        }
        $updatesubcat['sub_cat'] = $sub_cat_data;
        $client_visible_data = '0';
        if($this->input->post('client_visible') == 'on'){
            $client_visible_data = 1;
        }
        $updatesubcat['client_visible'] = $client_visible_data;

        $product_related_data = '0';
        if($this->input->post('product_related')=='on')
        {
            $product_related_data = '1';
        }

        $updatesubcat['product_related'] = $product_related_data;
         $updatesubcat['main_category_id'] = $this->input->post('main_category_id');
        $updatesubcat['article_owner'] = $this->input->post('article_owner');
        $updatesubcat['product_id'] = $product;
        $table = KNOWLEDGEBASE_SUB_CATEGORY.' as kmc';
        $where = "sub_category_id = $id ";
        
        $data['update_record'] = $this->db->where($where)->update($table, $updatesubcat);
         //echo $this->db->last_query();exit;
        
         redirect($this->viewname . '/KnowledgeBase/ListofSubCategory');
         //$this->load->view('ListofMainCategory',$data);
        
     }
     
      public function viewdata($id) {
        //$id = $this->uri->segment('3');
         // pr($data['editRecord']);exit;
        $data['project_view'] = $this->viewname;
          
        $redirect_link=$this->input->post('redirect_link');
        
        $fields = array('kmc.*','pm.product_id','pm.product_name','login.login_id','login.firstname','login.lastname');
        $where = array("kmc.main_category_id" => $id);
        $main_table = array("knowledgebase_main_category kmc");
        
        $join_tables = array('product_master pm'=>'pm.product_id=kmc.product_id','login'=>'login.login_id=kmc.article_owner');
        
        $data['edit_record'] = $this->common_model->get_records($main_table,$fields,$join_tables,'LEFT','','','','','','','','','',$where);
        
         $table = LOGIN.' as l';
         $where = array("l.status" => "1");
         $fields = array("l.*");
         $data['user']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

         $pm_params=array();
         $pm_params['table'] = PRODUCT_MASTER.' as pm';
         $pm_params['fields'] = array("pm.product_id,pm.product_name");
         $pm_params['where_in']=array("pm.status" => "1");
         $data['product_info']  = $this->common_model->get_records_array($pm_params);
         
        $product_info=array();
        foreach($data['edit_record'] as $row){
            $product_info[] = $row['product_id'];
        }
        $product=$product_info;
        $data['product_data'] = explode(",",$product[0]);
        
        //pr($data['product_data']);exit;
        $this->load->view('ViewMainCategory',$data);     
        
    }
    
    public function view($id) {
       //$id = $this->uri->segment('3');
        $data['project_view'] = $this->viewname;
          
        $redirect_link=$this->input->post('redirect_link');
        
        $fields = array('ksc.*','pm.product_id','pm.product_name','login.login_id','login.firstname','login.lastname','kmc.*');
        $where = array("ksc.sub_category_id" => $id);
        $main_table = array("knowledgebase_sub_category ksc");
        
        $join_tables = array('product_master pm'=>'pm.product_id=ksc.product_id','login'=>'login.login_id=ksc.article_owner','knowledgebase_main_category kmc'=>'kmc.main_category_id=ksc.main_category_id');
        
        $data['edit_record'] = $this->common_model->get_records($main_table,$fields,$join_tables,'LEFT','','','','','','','','','',$where);
       //echo $this->db->last_query();exit;
        $table = KNOWLEDGEBASE_MAIN_CATEGORY.' as kmc';
         $where = array("kmc.status" => "1");
         $fields = array("kmc.*");
         $data['main_category']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        
         $table = LOGIN.' as l';
         $where = array("l.status" => "1");
         $fields = array("l.*");
         $data['user'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

         $pm_params=array();
         $pm_params['table'] = PRODUCT_MASTER.' as pm';
         $pm_params['fields'] = array("pm.product_id,pm.product_name");
         $pm_params['where_in']=array("pm.status" => "1");
         $data['product_info']  = $this->common_model->get_records_array($pm_params);
         
         $product_info=array();
        foreach($data['edit_record'] as $row){
            $product_info[] = $row['product_id'];
        }
        $product=$product_info;
        $data['product_data'] = explode(",",$product[0]);
          
        //pr($data['editRecord']);exit;
        $this->load->view('/ViewSubCategory',$data);     
        
    }
    
         public function ListofArticle(){
        $data['header'] = array('menu_module'=>'support');
        // $data['js_content'] = '/loadJsFiles';
        
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('list_of_article_data');
        }

        $searchsort_session = $this->session->userdata('list_of_article_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'article_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url']    = base_url().$this->viewname.'/index';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(2).'/'.$this->uri->segment(3);
        }

        //Query

        $table = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE.' as ka';
        $where = "ka.is_delete=0";
        $fields = array("ka.article_id,ka.article_name,pm.product_name,u.login_id,u.firstname,u.lastname,kmc.main_category_id,kmc.category_name");
        $join_tables   =  array('blzdsk_login as u' =>'u.login_id= ka.article_owner','blzdsk_knowledgebase_main_category as kmc' =>'kmc.main_category_id = ka.main_category_id','blzdsk_product_master as pm' =>'pm.product_id= ka.product_id');
       
        //echo $this->db->last_query();exit;
        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim($searchtext));
            $match=array('ka.article_id'=>$searchtext,'ka.article_name'=>$searchtext);
            $data['article_info']  = $this->common_model->get_records($table,$fields,$join_tables,'lift','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where,'','','1');
        }
        else
        {
            $data['article_info']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('list_of_article_data', $sortsearchpage_data);

        $data['sales_view'] = $this->viewname;
        $data['header'] = array('menu_module'=>'support');
        //pr($searchsort_session);exit;
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname.'/ajax_list_article',$data);
        } else {
            $data['main_content'] = '/'.$this->listofmaincategory_data;
            $this->parser->parse('layouts/CampaignTemplate', $data);
        }

    }
     public function editarticle($id) {
        //$id = $this->uri->segment('3');
        $data['project_view'] = $this->viewname;

        $redirect_link = $this->input->post('redirect_link');

         $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $fields = array('ka.*','pm.product_name','login.login_id','login.firstname','login.lastname','kmc.main_category_id','kmc.category_name','kfm.*','ksc.sub_category_id','ksc.sub_category_name');
        $where = array("ka.article_id" => $id);
        $main_table = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE.' as ka';

        
        $join_tables = array('product_master pm'=>'pm.product_id = ka.product_id','login'=>'login.login_id=ka.article_owner','knowledgebase_main_category kmc'=>'kmc.main_category_id=ka.main_category_id','knowledge_file_master kfm'=>'kfm.article_id=ka.article_id','knowledgebase_sub_category ksc'=>'ksc.sub_category_id=ka.sub_category_id');
        $data['edit_record'] = $this->common_model->get_records($main_table,$fields,$join_tables,'LEFT','','','','','','','','','',$where);
// pr($data['edit_record']);exit;
        //echo $this->db->last_query();exit;
        $table = KNOWLEDGEBASE_MAIN_CATEGORY.' as kmc';
         $where = array("kmc.status" => "1");
         $fields = array("kmc.*");
         $data['main_category']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
         
          $table = KNOWLEDGEBASE_SUB_CATEGORY.' as ksc';
         $where = array("ksc.status" => "1");
         $fields = array("ksc.*");
         $data['sub_category']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        
         $table = LOGIN.' as l';
         $where = array("l.status" => "1");
         $fields = array("l.*");
         $data['user'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);



         $table = KNOWLEDGEBASE_FILE_MASTER.' as art';
         $where = array("art.article_id" => $id);
         $fields = array("art.*");
         $data['image_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);


         $pm_params=array();
         $pm_params['table'] = PRODUCT_MASTER.' as pm';
         $pm_params['fields'] = array("pm.product_id,pm.product_name");
         $pm_params['where_in']=array("pm.status" => "1");
         $data['product_info']  = $this->common_model->get_records_array($pm_params);
         
         $product_info=array();
        foreach($data['edit_record'] as $row){
            $product_info[] = $row['product_id'];
        }

        $product=$product_info;
        $data['product_data'] = explode(",",$product[0]);

        //pr($data['editRecord']);exit;
        $this->load->view('/EditArticle',$data);     
    }
    
    public function deletearticle($id) {
//       $id = $this->input->get('id');
//		die($id);
    
        //Delete Record From Database
         $redirect_link=$this->input->get('link');
         $data['article'] = $this->viewname;
        if (!empty($id)) {
            $where = array('article_id' => $id);
  
            $article_data['is_delete']=1;
        
            $delete_suceess = $this->common_model->update(blzdsk_knowledgebase_knowledge_article, $article_data, $where);
          
            if ($delete_suceess) {
                $msg = $this->lang->line('article_del_msg');
                $this->session->set_flashdata('msg', $msg);
            }
            else {
            $msg = $this->lang->line('error');
            $this->session->set_flashdata('error', $msg);
        }
            unset($id);
        }
//             echo $this->db->last_query();exit;
        redirect($this->viewname . '/KnowledgeBase/ListofArticle'); //Redirect On Listing page
    }
    public function updatearticle() {

       $id = $id= $this->input->post('s_id');

        $data['project_view'] = $this->viewname;
          
        $redirect_link=$this->input->post('redirect_link');
        $product_data = $this->input->post('product_id');
        $product = implode("," ,$product_data);
        $updatearticle['article_name'] = $this->input->post('article_name');
       
        $client_visible_data = '0';
        if($this->input->post('client_visible') == 'on'){
            $client_visible_data = 1;
        }
        $updatearticle['client_visible'] = $client_visible_data;

        $product_related_data = '0';
        if($this->input->post('product_related')=='on')
        {
            $product_related_data = '1';
        }

        $updatearticle['product_related'] = $product_related_data;
        $updatearticle['main_category_id'] = $this->input->post('main_category_id');
        $updatearticle['sub_category_id'] = $this->input->post('sub_category_id');
        $updatearticle['article_description'] = $this->input->post('article_description');
        $updatearticle['article_owner'] = $this->input->post('article_owner');
        $updatearticle['product_id'] = $product;
        $table = KNOWLEDGEBASE_KNOWLEDGE_ARTICLE.' as ka';
        $where = "article_id = $id ";
        
        $data['update_record'] = $this->db->where($where)->update($table, $updatearticle);
         //echo $this->db->last_query();exit;


        //upload file
        $data['article_view'] = $this->viewname;
        $file_name=array();
        $file_array1=$this->input->post('file_data');

        $file_name=$_FILES['article_files']['name'];
        if(count($file_name)>0 && count($file_array1)>0){
            $differentedImage=array_diff($file_name,$file_array1);
            foreach($file_name as $file)
            {
                if(in_array($file,$differentedImage))
                {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if(!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['article_files']['name'][$key]);
                    unset($_FILES['article_files']['type'][$key]);
                    unset($_FILES['article_files']['tmp_name'][$key]);
                    unset($_FILES['article_files']['error'][$key]);
                    unset($_FILES['article_files']['size'][$key]);
                }
            }

        }
        $_FILES['article_files']=$arr = array_map('array_values', $_FILES['article_files']);
        $upload_data = uploadImage('article_files', knowledge_upload_path, $data['article_view']);

        $knowledgefiles = array();
        foreach($upload_data as $dataname){
            $knowledgefiles[] =$dataname['file_name'];
        }
        $knowledge_file_str = implode(",",$knowledgefiles);

        $file2 = $this->input->post('fileToUpload');
        if(!(empty($file2))){
            $file_data = implode("," ,$file2);
        }else{
            $file_data = '';
        }
        if(!empty($knowledge_file_str) && !empty($file_data)){
            $compaigndata['file'] = $knowledge_file_str.','.$file_data;
        }else if(!empty($knowledge_file_str)){
            $compaigndata['file'] = $knowledge_file_str;
        }else{
            $compaigndata['file'] = $file_data;
        }
        $compaigndata['file_name']=$file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($upload_data, array('file_name' => $img));
            }
        }
        $article_files_data = array();

        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $prospect_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $article_files_data[] = ['file_name' => $prospect_files[$i], 'file_path' => $gallery_path[$i], 'article_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];

                }
            }
        }
        if (count($upload_data) > 0) {
            foreach ($upload_data as $files) {

            $article_files_data[] = ['file_name' => $files['file_name'], 'file_path' => knowledge_upload_path, 'article_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }

        if (count($article_files_data) > 0) {
                $where = array('article_id' => $id);

            if (!$this->common_model->insert_batch(KNOWLEDGEBASE_FILE_MASTER, $article_files_data)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }




        redirect($this->viewname . '/KnowledgeBase/ListofArticle');
         //$this->load->view('ListofMainCategory',$data);
        
     }
     
    public function viewarticle($id) {
       //$id = $this->uri->segment('3');
        $data['project_view'] = $this->viewname;
           $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $redirect_link=$this->input->post('redirect_link');
        
        $fields = array('ka.*','pm.product_id','pm.product_name','login.login_id','login.firstname','login.lastname','kmc.*');
        $where = array("ka.article_id" => $id);
        $main_table = array("knowledgebase_knowledge_article ka");
        
        $join_tables = array('product_master pm'=>'pm.product_id=ka.product_id','login'=>'login.login_id=ka.article_owner','knowledgebase_main_category kmc'=>'kmc.main_category_id=ka.main_category_id');
        
        $data['update_record'] = $this->common_model->get_records($main_table,$fields,$join_tables,'LEFT','','','','','','','','','',$where);
       //echo $this->db->last_query();exit;
        $table = KNOWLEDGEBASE_MAIN_CATEGORY.' as kmc';
         $where = array("kmc.status" => "1");
         $fields = array("kmc.*");
         $data['main_category']      = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        
         $table = LOGIN.' as l';
         $where = array("l.status" => "1");
         $fields = array("l.*");
         $data['user'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

         $pm_params=array();
         $pm_params['table'] = PRODUCT_MASTER.' as pm';
         $pm_params['fields'] = array("pm.product_id,pm.product_name");
         $pm_params['where_in']=array("pm.status" => "1");
         $data['product_info']  = $this->common_model->get_records_array($pm_params);
         
         $product_info=array();
        foreach($data['update_record'] as $row){
            $product_info[] = $row['product_id'];
        }
        $product=$product_info;
        $data['product_data'] = explode(",",$product[0]);
          
        //pr($data['editRecord']);exit;
        $this->load->view('/ViewArticle',$data);     
        
    }

    function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = KNOWLEDGEBASE_FILE_MASTER . ' as CM';
            $params['match_and'] = 'CM.file_id=' . $id . '';
            $cost_files = $this->common_model->get_records_array($params);
            if (count($cost_files) > 0) {
                $pth = file_get_contents(base_url($cost_files[0]['file_path'] . '/' . $cost_files[0]['file_name']));
                $this->load->helper('download');
                force_download($cost_files[0]['file_name'], $pth);
            }
            redirect($this->module);
        }
    }

    public function deleteImage($id) {
        //Delete Record From Database
        if (!empty($id)) {
            $match = array("file_id"=>$id);
            $fields = array("file_name");
            $image_name     = $this->common_model->get_records(KNOWLEDGEBASE_FILE_MASTER,$fields,'','',$match);

            if(file_exists($this->config->item('knowledge_img_base_url').$image_name[0]['file_name']))
            {

                unlink($this->config->item('knowledge_img_base_url').$image_name[0]['file_name']);
            }
            $where = array('file_id' => $id);
            if ($this->common_model->delete(KNOWLEDGEBASE_FILE_MASTER, $where)) {
                echo json_encode(array('status' => 1, 'error' => ''));
                die;
            } else {
                echo json_encode(array('status' => 0, 'error' => 'Someting went wrong!'));
                die;
            }

            unset($id);
        }
    }

}

