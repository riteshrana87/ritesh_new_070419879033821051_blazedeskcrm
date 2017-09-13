<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        if(checkPermission('Contact','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->viewname = $this->router->fetch_module();
        $this->load->model('Contact_model');
        $this->user_info = $this->session->userdata ('LOGGED_IN');
        $this->load->library('form_validation');
        
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Contact Model Index Page
      @Input 	:
      @Output	:
      @Date     : 22/02/2016
     */

    public function index() 
    {
        
        $this->breadcrumbs->push(lang('crm'),'/');
        //$this->breadcrumbs->push(lang('sales_overview'), 'SalesOverview');
        $this->breadcrumbs->push(lang('contacts'),' ');
        
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('contact_data');
        }

        $searchsort_session = $this->session->userdata('contact_data');
        
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
                $sortfield = 'contact_id';
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
            $segment_array = $this->uri->segment_array();
            $uri_segment = end($segment_array);
            $config['uri_segment'] = key(array_slice($segment_array, -1, 1, TRUE));
        }
     
//Query
//Get Records From CONTACT_MASTER Table for search
        $table_contact_search = CONTACT_MASTER . ' as cm';
        $match_contact_search = " cm.is_delete=0 ";
        $fields_contact_search = array("cm.contact_id,cm.contact_name");
        $data['owner'] = $this->common_model->get_records($table_contact_search, $fields_contact_search, '', '', $match_contact_search);
//Get Records From BRANCH_MASTER Table       
        $table_branch_master = BRANCH_MASTER . ' as bm';
        $match_branch_master = " bm.status=1 ";
        $fields_branch_master = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table_branch_master, $fields_branch_master, '', '', $match_branch_master);
//Get Records From COMPANY_MASTER Table       
        $table_company_master = COMPANY_MASTER . ' as cmp';
        $match_company_master = " cmp.status=1 and cmp.is_delete=0 ";
        $fields_company_master = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table_company_master, $fields_company_master, '', '', $match_company_master);

        $data['company_owner']  = $this->common_model->getSystemUserData();
//Join CONTACT_MASTER with COMPANY_MASTER for get all related records
      //  $params['join_tables'] = array(COMPANY_MASTER . ' as com' => 'com.company_id=cm.company_id',TBL_CRM_JOB_TITLE . ' as jt' => 'jt.job_title_id=cm.job_title');
          $params['join_tables'] = array(COMPANY_MASTER . ' as com' => 'com.company_id=cm.company_id');
        $params['join_type'] = 'left';
        $table_join_contact_master = CONTACT_MASTER . ' as cm';
        $fields_join_contact_master = array("cm.contact_id,cm.contact_name,cm.company_id,cm.job_title,cm.email,cm.contact_for,cm.status,com.company_name");
        $where = "cm.is_delete=0 ";
        
//If search data are there take post value and update query
        $data['company_show_id'] = "";
        if($this->input->post('search_company_id')!="")
        {
            $data['company_show_id']=$this->input->post('search_company_id');
            $where.=' and cm.company_id='.$data['company_show_id'];
        }
        
        $data['contact_show_id'] = "";
        if($this->input->post('search_contact_id')!="")
        {
            $data['contact_show_id']=$this->input->post('search_contact_id');
            $where.=' and cm.contact_id='.$data['contact_show_id'];
        }
        
        $data['contact_owner_id'] = "";
        if($this->input->post('search_ownwer_id')!="")
        {
            $data['contact_owner_id']=$this->input->post('search_ownwer_id');
            $where.=' and cm.created_by='.$data['contact_owner_id'];
        }
        
        $data['status_show'] = "";
        if($this->input->post('search_status')!="")
        {
            $data['status_show']=$this->input->post('search_status');
            $where.=' and cm.status='.$data['status_show'];
        }
        else{
             $where.=' and cm.status=1';
        } 
        
       $data['search_creation_date_show'] = "";
        if($this->input->post('search_creation_date')!="")
        {
            $data['search_creation_date_show']=date_format(date_create($this->input->post('search_creation_date')), 'Y-m-d');
            $where.=' and cm.created_date>="'.$data['search_creation_date_show'].'"';
        }
        $data['creation_end_date_show'] = "";
        if($this->input->post('creation_end_date')!="")
        {
            $data['creation_end_date_show']=date_format(date_create($this->input->post('creation_end_date')), 'Y-m-d');
            $where.=' and cm.created_date<="'.$data['creation_end_date_show'].'"';
        }
        
// If have any search than give post data in db
        if (!empty($searchtext)) 
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $where_search = '(cm.contact_name LIKE "%' . $searchtext . '%" OR com.company_name LIKE "%' . $searchtext . '%" OR cm.job_title LIKE "%' . $searchtext . '%"  OR cm.email LIKE "%' . $searchtext . '%" OR cm.contact_for LIKE "%' . $searchtext . '%")';
            $data['contact_info'] = $this->common_model->get_records($table_join_contact_master, $fields_join_contact_master, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_search);
            $config['total_rows'] = $this->common_model->get_records($table_join_contact_master, $fields_join_contact_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby,'', $where, '', '', '1','','',$where_search);
            $totalContact = $this->common_model->get_records($table_join_contact_master, $fields_join_contact_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby,'', $where, '', '', '1','','',$where_search);
        } else {
            $data['contact_info'] = $this->common_model->get_records($table_join_contact_master, $fields_join_contact_master, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table_join_contact_master, $fields_join_contact_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            $totalContact = $this->common_model->get_records($table_join_contact_master, $fields_join_contact_master, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
        
        //check  total account > 0 otherwise set total account = 0
    if($totalContact)
        {
             $data['total_contact'] = $totalContact;
        }
        else{
            $data['total_contact']='0';
        }
        
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
        $this->session->set_userdata('contact_data', $sortsearchpage_data);
        $data['drag']=true;
        $data['sales_view'] = $this->viewname;
        $data['contact_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'crm');

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = '/' . $this->viewname;
            $this->parser->parse('layouts/DashboardTemplate', $data);
        }
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Display add contact page
      @Input 	:
      @Output	: display modal
      @Date     : 22/02/2016
     */

    public function addrecord() 
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            //Get Records From COMPANY_MASTER Table       
            $table_company_master = COMPANY_MASTER . ' as cmp';
            $match_company_master = " cmp.status=1 and cmp.is_delete=0 ";
            $fields_company_master = array("cmp.company_id,cmp.company_name");
            $data['company_data'] = $this->common_model->get_records($table_company_master, $fields_company_master, '', '', $match_company_master);

            //Get Records From JOb Title Table       
            $table_job_title = TBL_CRM_JOB_TITLE . ' as jt';
            $match_job_title = " jt.status=1 AND jt.is_delete = 0 ";
            $fields_job_title = array("jt.job_title_id,jt.job_title_name");
            $order_by =  'jt.job_title_name';
            $order = 'ASC';
            $data['job_title_data'] = $this->common_model->get_records($table_job_title, $fields_job_title, '', '', $match_job_title,'','','',$order_by,$order);

            //Get Records From BRANCH_MASTER Table       
            $table_branch_master = BRANCH_MASTER . ' as bm';
            $match_branch_master = " bm.status=1 ";
            $fields_branch_master = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($table_branch_master, $fields_branch_master, '', '', $match_branch_master);

            //Get Records From LANGUAGE_MASTER Table
            $table1 = LANGUAGE_MASTER . ' as lan';
            $match1 = "";
            $fields1 = array("lan.language_id,lan.language_name");
            $data['language_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
            $table = COUNTRIES . ' as cm';
            $fields = array("cm.country_name,cm.country_id, cm.country_code");

            $data['country_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', '');

            //Added by sanket for getting Newsletter Lists
            $newsletterType = get_newsletter_type();
            $data['newsletterLists'] = getNewsletterListsByType($newsletterType);
            $data['newsletterSeleectedLists'] = [];
            $data['modal_title'] = $this->lang->line('create_new_contact');
            $data['submit_button_title'] = $this->lang->line('create_contact');
            $data['sales_view'] = $this->viewname;
            $data['drag']= true;
            $data['main_content'] = '/Add';
           // $data['js_content'] = '/loadJsFiles';
            $this->load->view('/Add', $data);
        }
        
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Display related id data and can edit
      @Input 	: get id
      @Output	: display edit data
      @Date     : 22/02/2016
     */

    public function edit() {
        
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $id = $this->uri->segment('3');
            $data['crnt_view'] = $this->viewname;

            $data['modal_title'] = $this->lang->line('update_contact');
            $data['submit_button_title'] = $this->lang->line('update_contact');
            $data['sales_view'] = $this->viewname;

            //Get Records From COMPANY_MASTER Table       
            $table_company_master = COMPANY_MASTER . ' as cmp';
            $match_company_master = " cmp.status=1 and cmp.is_delete=0 ";
            $fields_company_master = array("cmp.company_id,cmp.company_name");
            $data['company_data'] = $this->common_model->get_records($table_company_master, $fields_company_master, '', '', $match_company_master);

            //Get Records From BRANCH_MASTER Table       
            $table_branch_master = BRANCH_MASTER . ' as bm';
            $match_branch_master = " bm.status=1 ";
            $fields_branch_master = array("bm.branch_id,bm.branch_name");
            $data['branch_data'] = $this->common_model->get_records($table_branch_master, $fields_branch_master, '', '', $match_branch_master);

             //Get Records From JOb Title Table       
            $table_job_title = TBL_CRM_JOB_TITLE . ' as jt';
            $match_job_title = " jt.status=1 AND jt.is_delete = 0 ";
            $fields_job_title = array("jt.job_title_id,jt.job_title_name");
            $order_by =  'jt.job_title_name';
            $order = 'ASC';
            $data['job_title_data'] = $this->common_model->get_records($table_job_title, $fields_job_title, '', '', $match_job_title,'','','',$order_by,$order);


            //Get Records From LANGUAGE_MASTER Table
            $table1 = LANGUAGE_MASTER . ' as lan';
            $match1 = "";
            $fields1 = array("lan.language_id,lan.language_name");
            $data['language_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
            $table_countries = COUNTRIES . ' as cm';
            $fields_countries = array("cm.country_name,cm.country_id,cm.country_code");
            //$where=array("cm.status" => "1");
            $data['country_data'] = $this->common_model->get_records($table_countries, $fields_countries, '', '', '', '', '', '', '', '', '', '');

            $params['join_tables'] = array(COMPANY_MASTER . ' as c' => 'c.company_id=cm.company_id');
            $params['join_type'] = 'left';
            $match = "cm.contact_id = '$id'";
            $table_contact_master = CONTACT_MASTER . ' as cm';

            //Added by sanket for getting Newsletter Lists
            $newsletterType = get_newsletter_type();
            $data['newsletterLists'] = getNewsletterListsByType($newsletterType);


            $data['newsletterSeleectedLists'] = $this->getContactNewsletterLists($id,$newsletterType);

          //  $where = array("cm.contact_id" => $id);
            $fields_contact_master = array("cm.*,c.logo_img as company_logo");
            $data['editRecord'] = $this->common_model->get_records($table_contact_master, $fields_contact_master, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', '', '');
            $data['drag']= true;
            $data['id'] = $id;
            $data['main_content'] = '/Add';
           // $data['js_content'] = '/loadJsFiles';
            $this->load->view('/Add', $data);
        }
        
    }

    function cfilter() {
        $cname = $_REQUEST['cname'];
        $cm_param = array();
        $cm_param['table'] = CONTACT_MASTER . ' as cm';
        $cm_param['fields'] = array("cm.contact_id,cm.contact_name,cm.company_name,cm.job_title,cm.email,cm.contact_for,cm.status");
        $cm_param['where_in'] = array("cm.status" => "1", "cm.company_name" => $cname);
        $data2['contact2_info'] = $this->common_model->get_records_array($cm_param);
        echo json_encode($data2['contact2_info']);
        //$this->load->view($this->viewname,$data2);
        //$this->session->set_flashdata('contact_info',$data2['contact2_info']);
        //redirect($this->viewname);
    }   
    /*
      @Author   : Seema Tankariya
      @Desc     : Insert data in database
      @Input 	: input post data
      @Output	: display message if successfully inserted else display error
      @Date     : 22/02/2016
     */

    public function insertdata() 
    {
       
        $redirect_link = $this->input->post('redirect_link');
        if (!validateFormSecret()) {

//            $msg = $this->lang->line('contact_add_msg');
//            $this->session->set_flashdata('msg', $msg);

            redirect($redirect_link); //Redirect On Listing page
        }

        if ($this->input->post('is_newsletter')) {
            $is_newsletter = $this->input->post('is_newsletter');
        }else
        {
           $is_newsletter = ''; 
        }
        if ($this->input->post('contact_name')) {
            $contactdata['contact_name'] = trim($this->input->post('contact_name'));
        }
         $branchName = strip_slashes($this->input->post('branch_id'));
         //Get Branch id From BRANCH_MASTER Table       
        $table22 = BRANCH_MASTER . ' as bm';
        $match22 = "bm.branch_name='" . addslashes($branchName) . "' and status=1 ";
        $fields22 = array("bm.branch_name, bm.branch_id");
        $branchRecord = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        if ($branchRecord) {
            $company_data['branch_id'] = $branchRecord[0]['branch_id'];
        } else {
            $branchData['branch_name'] = $branchName;
        }
        if (count($branchRecord) == 0) {
            //INSERT Branch
            $branchData['created_date'] = datetimeformat();
            $branchData['modified_date'] = datetimeformat();
            $branchData['status'] = 1;
            $branch_id = $this->common_model->insert(BRANCH_MASTER, $branchData);
            $company_data['branch_id'] = $branch_id;
        }

        $company_name = strip_slashes(trim($this->input->post('company_name')));
        $company_data['company_name'] = strip_slashes(trim($this->input->post('company_name')));
        $company_data['email_id'] = $this->input->post('email_id_company');
        $company_data['website'] = $this->input->post('website');
        $company_data['company_id_data'] = $this->input->post('company_id_data');
        $company_data['reg_number'] = $this->input->post('com_reg_number');
        // $company_data['branch_id'] = $contactdata['branch_id'];
        $company_data['address1'] = strip_slashes($this->input->post('address1'));
        $company_data['address2'] = strip_slashes($this->input->post('address2'));
        $company_data['postal_code'] = $this->input->post('postal_code');
        $company_data['city'] = strip_slashes($this->input->post('city'));
        $company_data['state'] = strip_slashes($this->input->post('state'));
        $company_data['country_id'] = $this->input->post('country_id');
        $company_data['status'] = 1;
        $company_data['created_date'] = datetimeformat();
        $company_data['modified_date'] = datetimeformat();
        $company_data['phone_no'] = $this->input->post('phone_no_company');
        $company_data['company_id_data'] = $this->input->post('company_id_data');
        $company_data['reg_number'] = $this->input->post('com_reg_number');
        if(empty($company_name)){
            $contactdata['company_id'] = $this->input->post('company_id');
        }
        else{
            $table23 = COMPANY_MASTER . ' as cm';
            $match23 = "cm.company_name='" . addslashes($company_name) . "' and cm.status=1 ";
            $fields23 = array("cm.*");
            $company_record = $this->common_model->get_records($table23, $fields23, '', '', $match23);
            //If branch name exist in table then get id else insert branch data in BRANCH_MASTER
            if ($company_record) {
                $contactData['company_id'] = $company_record[0]['company_id'];
            } else {
                $companyData['company_name'] = $company_name;
            }
            if (count($company_record) == 0) {
                //If select add new company get company data
              $contactdata['company_id'] = $this->common_model->insert(COMPANY_MASTER, $company_data);

            } else {
                $contactdata['company_id'] = $contactData['company_id'];
            }
        }

        $table_master = COMPANY . ' as cm';
        $match_master = "cm.company_name='" . addslashes($company_name) . "' and cm.status=1 ";
        $fields_master = array("cm.*");
        $company_master = $this->common_model->get_records_data($table_master, $fields_master, '', '', $match_master);

        if (count($company_master) == 0) {
            $this->common_model->insert_data(COMPANY, $company_data);
        }else{
            $where = array('company_id' => $company_master[0]['company_id']);
            $this->common_model->update_data(COMPANY, $company_data,$where);

        }

        //Check primary for this company exist or not
            $table23 = CONTACT_MASTER . ' as cm';
            $match23 = "cm.company_id='" . $contactdata['company_id'] . "' and cm.is_delete=0 and primary_contact=1 and cm.status=1 ";
            $fields23 = array("cm.*");
            $primary_exist = $this->common_model->get_records($table23, $fields23, '', '', $match23);
        
            if($primary_exist)
            {
                $contactdata['primary_contact']=0;
            }
            else{
                 $contactdata['primary_contact']=1;
            }
        if ($this->input->post('job_title')) {
            $contactdata['job_title'] = strip_slashes($this->input->post('job_title'));
        }
        if ($this->input->post('email')) {
            $contactdata['email'] = strip_slashes($this->input->post('email'));
        }
        if ($this->input->post('contact_for')) {
            $contactdata['contact_for'] = strip_slashes($this->input->post('contact_for'));
        }
        if ($this->input->post('phone_number')) {
            $contactdata['phone_number'] = strip_slashes($this->input->post('phone_number'));
        }
        if ($this->input->post('mobile_number')) {
            $contactdata['mobile_number'] = strip_slashes($this->input->post('mobile_number'));
        }
        if ($this->input->post('address1')) {
            $contactdata['address1'] = strip_slashes($this->input->post('address1'));
        }
        if ($this->input->post('address2')) {
            $contactdata['address2'] = strip_slashes($this->input->post('address2'));
        }
        if ($this->input->post('city')) {
            $contactdata['city'] = strip_slashes($this->input->post('city'));
        }
        if ($this->input->post('country_id')) {
            $contactdata['country_id'] = strip_slashes($this->input->post('country_id'));
        }
        if ($this->input->post('state')) {
            $contactdata['state'] = strip_slashes($this->input->post('state'));
        }
        if ($this->input->post('postal_code')) {
            $contactdata['postal_code'] = strip_slashes($this->input->post('postal_code'));
        }
        if ($this->input->post('language_id')) {
            $contactdata['language_id'] = strip_slashes($this->input->post('language_id'));
        }

        $contactdata['fb'] = $this->input->post('url_fb');
        $contactdata['linkdin'] = $this->input->post('url_linkedin');
        $contactdata['twitter'] = $this->input->post('url_twitter');
        $contactdata['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $contactdata['created_date'] = datetimeformat();
        $contactdata['modified_date'] = datetimeformat();
        $contactdata['status'] =  $this->input->post('status');

        //upload image

        if (($_FILES['profile_image']['name']) != NULL) {
            $config = array(
                'upload_path' => FCPATH."uploads/contact/",
               'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
           //     'overwrite' => TRUE,
                'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
          //      'max_height' => "768",
          //      'max_width' => "1024"
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('profile_image')) {
                $file_data = array('upload_data' => $this->upload->data());

              
                foreach ($file_data as $file) {
                    $contactdata['image'] = $file['file_name'];
                }
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
            }
        }
        
        if (($_FILES['logo_image']['name']) != NULL) {
            $config_logo = array(
                'upload_path' => FCPATH."uploads/company/",
                'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
                'max_size' => "2048000", 
            );
            $this->load->library('upload', $config_logo);
            $this->upload->initialize($config_logo);

            if ($this->upload->do_upload('logo_image')) {
                $file_data = array('upload_data' => $this->upload->data());

                foreach ($file_data as $file) {
                    $company_data_logo['logo_img'] = $file['file_name'];
                }
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
            }
            
            $where = array('company_id' => $contactdata['company_id']);
            $this->common_model->update(COMPANY_MASTER, $company_data_logo,$where);
        }
      
     $email_client_success_insert =  $this->Contact_model-> contactSync($this->input->post());  // by brt for adding contact in email client
       if($email_client_success_insert){
        $success_insert = $this->common_model->insert(CONTACT_MASTER, $contactdata);
        
            if ($success_insert) {
                
                $msg = $this->lang->line('contact_add_msg');
                $this->session->set_flashdata('msg', $msg);
                $this->session->set_flashdata('message', $msg);
                
                $newzleter_type = get_newsletter_type();
                // start Added by Sanket For adding contact in newsleeter
                if($is_newsletter == "on")
                {
                    $list_Id = $this->input->post('newsletterLists');

                    $contactdata['is_newsletter'] = 1;
                    $contactdata['configure_with'] = $newzleter_type;

                    if($newzleter_type == '1')
                    {
                        $mailchimp_data = array('contact_id'=>$success_insert,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                        $this->contact_add_to_mailchimp($list_Id,$mailchimp_data);
                        //$this->addTolistsContact($list_Id,$success_insert,'1');
                    }else if($newzleter_type == '2')
                    {
                        $cmonitor_data = array('contact_id'=>$success_insert,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                        $this->contact_add_to_cmonitor($list_Id,$cmonitor_data);
                        //$this->updateContactNewsletter('1',$success_insert,'2');
                    }else if($newzleter_type == '3')
                    {
                        $this->add_contact_moosend($list_Id,$contactdata['contact_name'],$contactdata['email'],$success_insert);
                       
                    }else if($newzleter_type == '4')
                    {
                        $this->add_contact_get_response($list_Id,$contactdata['contact_name'],$contactdata['email'],$success_insert);
                       
                    }
                }
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
 
        redirect($this->viewname); //Redirect On Listing page
    }
	
    /*
      @Author   : Seema Tankariya
      @Desc     : Update data in database
      @Input 	: input post data
      @Output	: display message if successfully updated else display error
      @Date     : 22/02/2016
     */
	
    public function updatedata() 
    {
        $redirect_link = $this->input->post('redirect_link');
        $id = $this->input->post('id');
        if (strpos($redirect_link, 'view') === false) 
        {
            
            $redirect_link = $this->viewname;
        }else
        {
            $redirect_link = base_url()."Contact/view/".$id;
            
        }
        if (!validateFormSecret()) {

            redirect($redirect_link); //Redirect On Listing page
        }
        
        if ($this->input->post('is_newsletter')) {
            $is_newsletter = $this->input->post('is_newsletter');
        }else
        {
            $is_newsletter = ''; 
        }
       
        if ($this->input->post('contact_name')) {
            $contactdata['contact_name'] = strip_slashes(trim($this->input->post('contact_name')));
//Get Contact Information
            $where = array('prospect_id' => $id,'client_type' => 'contact');
            $estimate_data['client_name']= $contactdata['contact_name'];
            $this->common_model->update(ESTIMATE_CLIENT, $estimate_data, $where);
        }
//If Select Add New Company From  Company List
        if ($this->input->post('company_name')) {
            $company_data['company_name'] = strip_slashes(trim($this->input->post('company_name')));
            $company_data['email_id'] = $this->input->post('email_id_company');
            $company_data['website'] = $this->input->post('website');
            $company_data['company_id_data'] = $this->input->post('company_id_data');
            $company_data['reg_number'] = $this->input->post('com_reg_number');
            $company_data['branch_id'] = 2;
            $company_data['status'] = 1;
            $company_data['phone_no'] = $this->input->post('phone_no_company');
            $company_data['created_date'] = datetimeformat();
            $company_data['modified_date'] = datetimeformat();
            $company_data['company_id_data'] = $this->input->post('company_id_data');
            $company_data['reg_number'] = $this->input->post('com_reg_number');
            $contactdata['company_id'] = $this->common_model->insert(COMPANY_MASTER, $company_data);
            

            $table_master = COMPANY . ' as cm';
            $match_master = "cm.company_name='" . addslashes($company_data['company_name']) . "' and cm.status=1 ";
            $fields_master = array("cm.*");
            $company_master = $this->common_model->get_records_data($table_master, $fields_master, '', '', $match_master);
           
            if (count($company_master) == 0) {
                 $this->common_model->insert_data(COMPANY, $company_data);
            }else{
                $where = array('company_id' => $company_master[0]['company_id']);
                $this->common_model->update_data(COMPANY, $company_data,$where);
            }

        }
//Else Select From Company List
        else {
            $contactdata['company_id'] = $this->input->post('company_id');
        }


        if ($this->input->post('job_title')) {
            $contactdata['job_title'] = strip_slashes($this->input->post('job_title'));
        }
        if ($this->input->post('email')) {
            $contactdata['email'] = strip_slashes($this->input->post('email'));
        }
        if ($this->input->post('contact_for')) {
            $contactdata['contact_for'] = strip_slashes($this->input->post('contact_for'));
        }
        if ($this->input->post('phone_number')) {
            $contactdata['phone_number'] = strip_slashes($this->input->post('phone_number'));
        }
        if ($this->input->post('mobile_number')) {
            $contactdata['mobile_number'] = strip_slashes($this->input->post('mobile_number'));
        }
        if ($this->input->post('address1')) {
            $contactdata['address1'] = strip_slashes($this->input->post('address1'));
        }
        if ($this->input->post('address2')) {
            $contactdata['address2'] = strip_slashes($this->input->post('address2'));
        }
        if ($this->input->post('city')) {
            $contactdata['city'] = strip_slashes($this->input->post('city'));
        }
        if ($this->input->post('country_id')) {
            $contactdata['country_id'] = strip_slashes($this->input->post('country_id'));
        }
        if ($this->input->post('state')) {
            $contactdata['state'] = strip_slashes($this->input->post('state'));
        }
        if ($this->input->post('postal_code')) {
            $contactdata['postal_code'] = strip_slashes($this->input->post('postal_code'));
        }
        if ($this->input->post('language_id')) {
            $contactdata['language_id'] = strip_slashes($this->input->post('language_id'));
        }

        // start Added by Sanket For adding contact in newsleeter
        $newzleter_type = get_newsletter_type();
      
        $list_Id = $this->input->post('newsletterLists');
        if($is_newsletter == "on")
        {
            $new_email =  $contactdata['email'] ;
            $new_name =  $contactdata['contact_name'] ;
            $table = CONTACT_MASTER . ' as c';
            $match = "c.contact_id =".$id;
            $fields = array("c.email,c.contact_name");
            $contact_email = $this->common_model->get_records($table, $fields, '', '', $match);
           
            $old_email = $contact_email[0]['email'];
            $old_contact_name = $contact_email[0]['contact_name'];
           
            $contactdata['is_newsletter'] = 1;
            $contactdata['configure_with'] = $newzleter_type;
            
           
            if($newzleter_type == '1')
            {
                if($new_email != $old_email)
                {
                    $mailchimp_data = array('contact_id'=>$id,'old_email_address'=>$old_email,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                    
                    //(As mailchimp doesnot allow to change email addres)first delete the record and than add new
                    $this->delete_contact_from_mailChimp($list_Id,$mailchimp_data);
                    $this->contact_add_to_mailchimp($list_Id,$mailchimp_data);
                }else
                {
                    $mailchimp_data = array('contact_id'=>$id,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                    $this->contact_add_to_mailchimp($list_Id,$mailchimp_data);
                }
                
                if($new_name != $old_contact_name)
                {
                    $mailchimp_data = array('contact_id'=>$id,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                    $this->contact_update_to_mailchimp($list_Id,$mailchimp_data);
                }
                
                if(count($list_Id) > 0)
                {
                    $str_not = implode(',', $list_Id);
                    $tmp_str = "'".str_replace(",", "','", $str_not)."'";
                    
                    $table = TBL_NEWSLETTER_LISTS_CONTACT . ' as c';
                    $match = "c.contact_id =".$id." AND `list_id` NOT IN($tmp_str)";
                    $fields = array("DISTINCT(c.list_id)");
                    $delete_list_id = $this->common_model->get_records($table, $fields, '', '', $match);
                    
                    $tmp_list_arr = [];
                    if(count($delete_list_id) > 0)
                    {
                        foreach ($delete_list_id as $listId)
                        {
                            $tmp_list_arr[] = $listId['list_id'];
                        }
                        $mailchimp_data = array('contact_id'=>$id,'old_email_address'=>$old_email,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                        $this->delete_contact_from_mailChimp($tmp_list_arr,$mailchimp_data);
                    }
                    //quesry is not by common model wise bcoz common model does not suppor not where in function of mysql.
                    
                    //$query_tct = "UPDATE `blzdsk_newsletter_lists_contact` SET `status` = 0 WHERE `contact_id` = $id AND `list_id` NOT IN($tmp_str)";
                    $query_tct = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id AND `list_id` NOT IN($tmp_str)";
                    $query = $this->db->query($query_tct);

                }else
                {
                    $table = TBL_NEWSLETTER_LISTS_CONTACT . ' as c';
                    $match = "c.contact_id =".$id;
                    $fields = array("DISTINCT(c.list_id)");
                    $delete_list_id = $this->common_model->get_records($table, $fields, '', '', $match);
                    
                    
                    $tmp_list_arr = [];
                    if(count($delete_list_id) > 0)
                    {
                        foreach ($delete_list_id as $listId)
                        {
                            $tmp_list_arr[] = $listId['list_id'];
                        }
                        $mailchimp_data = array('contact_id'=>$id,'old_email_address'=>$old_email,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                        $this->delete_contact_from_mailChimp($tmp_list_arr,$mailchimp_data);
                    }
                    
                    $queryD = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id";
                    $query = $this->db->query($queryD);
                    
                    $this->updateContactNewsletter('0',trim($id),'1');
                    $contactdata['is_newsletter'] = 0;
                    
                }
                
            }else if($newzleter_type == '2')
            {
                if($new_email != $old_email)
                {
                    $mailchimp_data = array('contact_id'=>$id,'old_email_address'=>$old_email,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                    $this->contact_update_to_cmonitor($list_Id,$mailchimp_data);
                }else
                {
                    $mailchimp_data = array('contact_id'=>$id,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                    $this->contact_add_to_cmonitor($list_Id,$mailchimp_data);
                }
                
                if($new_name != $old_contact_name)
                {
                    //for updating name
                    $mailchimp_data = array('contact_id'=>$id,'old_email_address'=>$old_email,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                    $this->contact_update_to_cmonitor($list_Id,$mailchimp_data);
                }
                
                
                if(count($list_Id) > 0)
                {
                    $str_not = implode(',', $list_Id);
                    $tmp_str = "'".str_replace(",", "','", $str_not)."'";
                    
                    $table = TBL_NEWSLETTER_LISTS_CONTACT . ' as c';
                    $match = "c.contact_id =".$id." AND `list_id` NOT IN($tmp_str)";
                    $fields = array("DISTINCT(c.list_id)");
                    $delete_list_id = $this->common_model->get_records($table, $fields, '', '', $match);
                    
                    $tmp_list_arr = [];
                    if(count($delete_list_id) > 0)
                    {
                        foreach ($delete_list_id as $listId)
                        {
                            $tmp_list_arr[] = $listId['list_id'];
                        }
                        
                        
                        $mailchimp_data = array('contact_id'=>$id,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                        $this->delete_from_cmonitor($tmp_list_arr,$mailchimp_data);
                    }
                    //quesry is not by common model wise bcoz common model does not suppor not where in function of mysql.
                    
                    //$query_tct = "UPDATE `blzdsk_newsletter_lists_contact` SET `status` = 0 WHERE `contact_id` = $id AND `list_id` NOT IN($tmp_str)";
                    $query_tct = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id AND `list_id` NOT IN($tmp_str)";
                    $query = $this->db->query($query_tct);
                }else
                {
                    $table = TBL_NEWSLETTER_LISTS_CONTACT . ' as c';
                    $match = "c.contact_id =".$id;
                    $fields = array("DISTINCT(c.list_id)");
                    $delete_list_id = $this->common_model->get_records($table, $fields, '', '', $match);
                    
                    
                    $tmp_list_arr = [];
                    if(count($delete_list_id) > 0)
                    {
                        foreach ($delete_list_id as $listId)
                        {
                            $tmp_list_arr[] = $listId['list_id'];
                        }
                        $mailchimp_data = array('contact_id'=>$id,'old_email_address'=>$old_email,'email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                        $this->delete_from_cmonitor($tmp_list_arr,$mailchimp_data);
                    }
                    
                    $queryD = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id";
                    $query = $this->db->query($queryD);
                    
                    $this->updateContactNewsletter('0',trim($id),'2');
                    $contactdata['is_newsletter'] = 0;
                }
            }else if($newzleter_type == '3')
            {
                if($new_email != $old_email)
                {
                    $this->update_contact_moosend($list_Id,$new_name,$new_email,$old_email,$id);
                }else
                {
                    $this->add_contact_moosend($list_Id,$contactdata['contact_name'],$contactdata['email'],$id);
                }
                
                if($new_name != $old_contact_name)
                {
                    $this->update_contact_moosend($list_Id,$new_name,$new_email,$old_email,$id);
                }
               
                if(count($list_Id) > 0)
                {
                    $str_not = implode(',', $list_Id);
                    $tmp_str = "'".str_replace(",", "','", $str_not)."'";
                    
                    $table = TBL_NEWSLETTER_LISTS_CONTACT . ' as c';
                    $match = "c.contact_id =".$id." AND `list_id` NOT IN($tmp_str)";
                    $fields = array("DISTINCT(c.list_id)");
                    $delete_list_id = $this->common_model->get_records($table, $fields, '', '', $match);
                    
                    $tmp_list_arr = [];
                    if(count($delete_list_id) > 0)
                    {
                        foreach ($delete_list_id as $listId)
                        {
                            $tmp_list_arr[] = $listId['list_id'];
                        }
                        $this->delete_contact_moosend($tmp_list_arr,$contactdata['email']);
                    }
                    //quesry is not by common model wise bcoz common model does not suppor not where in function of mysql.
                    
                    //$query_tct = "UPDATE `blzdsk_newsletter_lists_contact` SET `status` = 0 WHERE `contact_id` = $id AND `list_id` NOT IN($tmp_str)";
                    $query_tct = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id AND `list_id` NOT IN($tmp_str)";
                    $query = $this->db->query($query_tct);
                }else
                {
                    $table = TBL_NEWSLETTER_LISTS_CONTACT . ' as c';
                    $match = "c.contact_id =".$id;
                    $fields = array("DISTINCT(c.list_id)");
                    $delete_list_id = $this->common_model->get_records($table, $fields, '', '', $match);
                    
                    
                    $tmp_list_arr = [];
                    if(count($delete_list_id) > 0)
                    {
                        foreach ($delete_list_id as $listId)
                        {
                            $tmp_list_arr[] = $listId['list_id'];
                        }
                        $this->delete_contact_moosend($tmp_list_arr,$contactdata['email']);
                    }
                    
                    $queryD = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id";
                    $query = $this->db->query($queryD);
                    
                    $this->updateContactNewsletter('0',trim($id),'3');
                    $contactdata['is_newsletter'] = 0;
                }
            }
            else if($newzleter_type == '4')
            {
              
                if($new_email != $old_email)
                {
                   
                    //get resposne does not allow update email address of the contact so while updateing the cotnact 
                    //you have delete old contact and add new contact.
                    $this->delete_contact_get_resposne($list_Id,$old_email);
                    $this->add_contact_get_response($list_Id,$contactdata['contact_name'],$new_email,$id);
                }else
                {
                    $this->add_contact_get_response($list_Id,$contactdata['contact_name'],$contactdata['email'],$id);
                }
               
                if($new_name != $old_contact_name)
                {
                    $this->update_contact_get_response($list_Id,$new_email,$new_name);
                }
                
                if(count($list_Id) > 0)
                {
                    $str_not = implode(',', $list_Id);
                    $tmp_str = "'".str_replace(",", "','", $str_not)."'";
                    
                    $table = TBL_NEWSLETTER_LISTS_CONTACT . ' as c';
                    $match = "c.contact_id =".$id." AND `list_id` NOT IN($tmp_str)";
                    $fields = array("DISTINCT(c.list_id)");
                    $delete_list_id = $this->common_model->get_records($table, $fields, '', '', $match);
                    
                    $tmp_list_arr = [];
                    if(count($delete_list_id) > 0)
                    {
                        foreach ($delete_list_id as $listId)
                        {
                            $tmp_list_arr[] = $listId['list_id'];
                        }
                        $this->delete_contact_get_resposne($tmp_list_arr,$contactdata['email']);
                    }
                    //quesry is not by common model wise bcoz common model does not suppor not where in function of mysql.
                    
                    //$query_tct = "UPDATE `blzdsk_newsletter_lists_contact` SET `status` = 0 WHERE `contact_id` = $id AND `list_id` NOT IN($tmp_str)";
                    $query_tct = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id AND `list_id` NOT IN($tmp_str)";
                    $query = $this->db->query($query_tct);
                }else
                {
                    $table = TBL_NEWSLETTER_LISTS_CONTACT . ' as c';
                    $match = "c.contact_id =".$id;
                    $fields = array("DISTINCT(c.list_id)");
                    $delete_list_id = $this->common_model->get_records($table, $fields, '', '', $match);
                    
                    
                    $tmp_list_arr = [];
                    if(count($delete_list_id) > 0)
                    {
                        foreach ($delete_list_id as $listId)
                        {
                            $tmp_list_arr[] = $listId['list_id'];
                        }
                        $this->delete_contact_get_resposne($tmp_list_arr,$contactdata['email']);
                    }
                    
                    $queryD = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id";
                    $query = $this->db->query($queryD);
                    
                    $this->updateContactNewsletter('0',trim($id),'4');
                    $contactdata['is_newsletter'] = 0;
                }
            }
        }else
        {
            $contactdata['is_newsletter'] = 0;
            $contactdata['configure_with'] = 0;
            
            //for unsubscribe or delete contact while checkbox is off.
            if($newzleter_type == '1')
            {
                $mailchimp_data = array('email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                $this->unsubscribe_contact_from_mailChimp($list_Id,$mailchimp_data);
                $this->updateTolistsContact($id,$newzleter_type);
            }else if($newzleter_type == '2')
            {
                $mailchimp_data = array('email_address'=>$contactdata['email'],'contact_name'=>$contactdata['contact_name']);
                $this->cmonitor_make_unsubscribe($list_Id,$mailchimp_data);
                $this->updateTolistsContact($id,$newzleter_type);
            }else if($newzleter_type == '3')
            {
                $this->delete_contact_moosend($list_Id,$contactdata['email']);
                $this->updateTolistsContact($id,$newzleter_type);
            }
            else if($newzleter_type == '4')
            {
                $this->delete_contact_get_resposne($list_Id,$contactdata['email']);
                $this->updateTolistsContact($id,$newzleter_type);
            }
           
        }
        // End Added by Sanket For adding contact in newsleeter
//upload image 
        if (($_FILES['profile_image']['name']) != NULL) {
            $config = array(
                'upload_path' => FCPATH."uploads/contact/",
                'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
                'max_size' => "2048000",
            );
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('profile_image')) {
                $file_data = array('upload_data' => $this->upload->data());

                foreach ($file_data as $file) {
                    $contactdata['image'] = $file['file_name'];
                }
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
            }
        }
        if (($_FILES['logo_image']['name']) != NULL) {
            $config_logo = array(
                'upload_path' => FCPATH."uploads/company/",
                'allowed_types' => "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG",
                'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            );
                $this->load->library('upload', $config_logo);
                $this->upload->initialize($config_logo);
            if ($this->upload->do_upload('logo_image')) {
                $file_data = array('upload_data' => $this->upload->data());

                foreach ($file_data as $file) {
                    $company_data_logo['logo_img'] = $file['file_name'];
                }
            } else {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
            }
            
            $where = array('company_id' => $contactdata['company_id']);
            $this->common_model->update(COMPANY_MASTER, $company_data_logo,$where);
            
        }
        $contactdata['fb'] = $this->input->post('url_fb');
        $contactdata['linkdin'] = $this->input->post('url_linkedin');
        $contactdata['twitter'] = $this->input->post('url_twitter');
        $contactdata['modified_date'] = datetimeformat();
        $contactdata['status'] =  $this->input->post('status');
//Update Record in Database
        $where = array('contact_id' => $id);
        $success_update = $this->common_model->update(CONTACT_MASTER, $contactdata, $where);

        if ($success_update) {
            $msg = $this->lang->line('contact_update_msg');
            $this->session->set_flashdata('msg', $msg);
             $this->session->set_flashdata('message', $msg);
        } else {
            $msg = $this->lang->line('error');
            $this->session->set_flashdata('error', $msg);
        }

        redirect($redirect_link); //Redirect On Listing page
    }

    /*
      @Author   : Seema Tankariya
      @Desc     : Delete data in database
      @Input 	: get id
      @Output	: display message if successfully deleted else display error
      @Date     : 22/02/2016
     */
    public function deletedata() {
        
        $id = $this->input->get('id');
        $redirect_link=$this->input->get('link');
      
        
        
       
        //Set is_delete is 1 In Database
        if (!empty($id)) {

            $where = array('contact_id' => $id);
            $contact_data['is_delete'] = 1;
            $success_delete = $this->common_model->update(CONTACT_MASTER, $contact_data, $where);
            
        //Get company id for this contact
            $table23 = CONTACT_MASTER . ' as cm';
            $match23 = "cm.contact_id='" . $id . "'";
            $fields23 = array("cm.company_id");
            $companyData = $this->common_model->get_records($table23, $fields23, '', '', $match23);
            
            
        //Check primary for this company exist or not
            $table23 = CONTACT_MASTER . ' as cm';
            $match23 = "cm.company_id='" . $companyData[0]['company_id'] . "' and cm.is_delete=0 and primary_contact=1  and cm.status=1 ";
            $fields23 = array("cm.contact_id");
            $primaryExist = $this->common_model->get_records($table23, $fields23, '', '', $match23);
          
            if(empty($primaryExist)){
        //get contact for this company 
                $table23 = CONTACT_MASTER . ' as cm';
                $match23 = "cm.company_id='" . $companyData[0]['company_id'] . "' and cm.is_delete=0  and cm.status=1 ";
                $fields23 = array("cm.contact_id");
                $changePrimary = $this->common_model->get_records($table23, $fields23, '', '', $match23);
                
                if($changePrimary){
                    $where = array('contact_id' => $changePrimary[0]['contact_id']);
                    $contactData['primary_contact'] = 1;
                    $this->common_model->update(CONTACT_MASTER, $contactData, $where);
                }
                
            }
            
            if ($success_delete) 
            {
                $this->session->set_flashdata('msg', lang('contact_del_msg'));
                
                $newzleter_type = get_newsletter_type();
                
                if($newzleter_type == '1')
                {
                    $listArr = $this->getContactNewsletterLists($id,'1');
        
                    $table_contact = CONTACT_MASTER . ' as bm';
                    $match_contact = "bm.contact_id=".$id;
                    $fields_contact = array("bm.email");
                    $contactEmailArr = $this->common_model->get_records($table_contact, $fields_contact, '', '', $match_contact);
                    $mailchimp_data = array('contact_id'=>$id,'email_address'=>$contactEmailArr[0]['email']);
                    $this->delete_contact_from_mailChimp($listArr,$mailchimp_data);
                    
                    $query_tct = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id";
                    $query = $this->db->query($query_tct);
                    $this->updateContactNewsletter('0',$id,'1');
                }else if($newzleter_type == '2')
                {
                    $campaignMonitorlistArr = $this->getContactNewsletterLists($id,'2');
        
                    $table_contact = CONTACT_MASTER . ' as bm';
                    $match_contact = "bm.contact_id=".$id;
                    $fields_contact = array("bm.email");
                    $contactEmailArr = $this->common_model->get_records($table_contact, $fields_contact, '', '', $match_contact);
                    $mailchimp_data = array('contact_id'=>$id,'email_address'=>$contactEmailArr[0]['email']);
                    $this->delete_from_cmonitor($campaignMonitorlistArr,$mailchimp_data);
                    
                    $query_tct = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id";
                    $query = $this->db->query($query_tct);
                    $this->updateContactNewsletter('0',$id,'2');
                }else if($newzleter_type == '3')
                {
                    $moosendlistArr = $this->getContactNewsletterLists($id,'3');
        
                    $table_contact = CONTACT_MASTER . ' as bm';
                    $match_contact = "bm.contact_id=".$id;
                    $fields_contact = array("bm.email");
                    $contactEmailArr = $this->common_model->get_records($table_contact, $fields_contact, '', '', $match_contact);
                    $mailchimp_data = array('contact_id'=>$id,'email_address'=>$contactEmailArr[0]['email']);
                    
                    $this->delete_contact_moosend($moosendlistArr,$contactEmailArr[0]['email']);
                    $query_tct = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id";
                    $query = $this->db->query($query_tct);
                    $this->updateContactNewsletter('0',$id,'3');
                }else if($newzleter_type == '4')
                {
                    $getResposneArr = $this->getContactNewsletterLists($id,'4');
        
                    $table_contact = CONTACT_MASTER . ' as bm';
                    $match_contact = "bm.contact_id=".$id;
                    $fields_contact = array("bm.email");
                    $contactEmailArr = $this->common_model->get_records($table_contact, $fields_contact, '', '', $match_contact);
                    $mailchimp_data = array('contact_id'=>$id,'email_address'=>$contactEmailArr[0]['email']);
                    
                    $this->delete_contact_get_resposne($getResposneArr,$contactEmailArr[0]['email']);
                    $query_tct = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE `contact_id` = $id";
                    $query = $this->db->query($query_tct);
                    $this->updateContactNewsletter('0',$id,'4');
                }
                
            }else 
            {
                $this->session->set_flashdata('error', lang('error'));
            }
        }
        unset($id);
        redirect($redirect_link);
    }

    /*
      @Author : RJ(Rupesh Jorkar)
      @Desc   : Show contact information
      @Input  :
      @Output :
      @Date   : 22/02/2016
     */

    public function view($id = NULL)
    {
       
        if($id > 0)
        {
            $this->breadcrumbs->push(lang('crm'),'/');
            $this->breadcrumbs->push(lang('contacts'),'Contact');
            $_SESSION['current_related_id'] = $id;
            $data['drag']=true;
            $data = array();
            $data['id'] = $id;      //Pass Current id 
            $data['ctr_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'crm');
            
            //Get Records From JOb Title Table       
            $table_job_title = TBL_CRM_JOB_TITLE . ' as jt';
            $match_job_title = " jt.status=1 AND jt.is_delete = 0 ";
            $fields_job_title = array("jt.job_title_id,jt.job_title_name");
            $order_by =  'jt.job_title_name';
            $order = 'ASC';
            $data['job_title_data'] = $this->common_model->get_records($table_job_title, $fields_job_title, '', '', $match_job_title,'','','',$order_by,$order);
       
            $params['join_tables'] = array(TBL_CRM_JOB_TITLE . ' as jt' => 'jt.job_title_id=cm.job_title',COMPANY_MASTER . ' as co' => 'co.company_id=cm.company_id',COUNTRIES . ' as c' => 'co.country_id = c.country_id');
            $params['join_type'] = 'left';
            //Get Records From CONTACT_MASTER Table       
            $table5 = CONTACT_MASTER . ' as cm';
            $match5 = " cm.is_delete= 0 AND cm.contact_id = " . $id;
            $fields5 = array("cm.contact_id,cm.created_by,cm.contact_name,cm.company_name,cm.job_title,jt.job_title_name as jobTitle,
                             cm.image,cm.phone_number,cm.mobile_number,cm.logo_img,co.address1,co.address2,
                             co.postal_code,co.city,co.state,c.country_name,cm.language_id,cm.email,cm.contact_for,
                             cm.created_date,cm.modified_date,cm.status,cm.fb,cm.linkdin,cm.twitter,co.logo_img as company_logo");
            //$where = array("cm.country_id"=>"c.country_id");
            $data['all_records'] = $this->common_model->get_records($table5, $fields5,$params['join_tables'], $params['join_type'] , $match5);
           
            if(empty($data['all_records']))
            {
                redirect('Contact');
            }
            
            $searchtext = @$this->session->userdata('searchtext');
            if(!empty($searchtext))
            {
               $data['searchtext'] = $searchtext ;
            }
            $data['prospect_owner']  = $this->common_model->getSystemUserData();
            $data['drag']=true;
            $this->breadcrumbs->push($data['all_records'][0]['contact_name'],' ');
            $data['main_content'] = '/view';
            
            $this->parser->parse('layouts/DashboardTemplate', $data);
            
        }else
        {
            redirect('Contact');
        }
        
    }
    
    /*
      @Author   : Seema Tankariya
      @Desc     : Export csv file
      @Input 	: search post data
      @Output	: download csv
      @Date     : 22/02/2016
     */

    function exportToCSV() {

        $dbsearch = " pm.status=1 and is_delete=0 ";
        if ($this->input->post('search_branch_id') != '') {
            $dbsearch .= " and pm.branch_id=" . $this->input->post('search_branch_id');
        }
        if ($this->input->post('search_prospect_owner_id') != '') {
            $dbsearch .= " and pm.prospect_owner_id=" . $this->input->post('search_prospect_owner_id');
        }
        if ($this->input->post('search_company_id') != '') {
            $dbsearch .= " and pm.company_id=" . $this->input->post('search_company_id');
        }
        if ($this->input->post('start_value') != '' && $this->input->post('end_value') != '') {
            $dbsearch .= " and pm.estimate_prospect_worth>=" . $this->input->post('start_value') . ' and pm.estimate_prospect_worth<=' . $this->input->post('end_value');
        }
        if ($this->input->post('search_creation_date') != '' && $this->input->post('creation_end_date') != '') {
            $dbsearch .= " and pm.created_date>=" . $this->input->post('search_creation_date') . ' and pm.created_date<=' . $this->input->post('creation_end_date');
        }

        if ($this->input->post('search_contact_date') != '' && $this->input->post('contact_end_date') != '') {
            $dbsearch .= " and pm.contact_date>=" . $this->input->post('search_contact_date') . ' and pm.contact_date<=' . $this->input->post('contact_end_date');
        }
        $data['prospect_data'] = $this->Contact_model->exportCsvData($dbsearch);
    }
    
    /*
     @Author   : Sanket Jayani
     @Desc     : Display Import CSV File popup
     @Input 	: search post data
     @Output	: open popup
     @Date     : 07/03/2016
    */
    function importContact()
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['modal_title'] = $this->lang->line('import_contact');
            $data['submit_button_title'] = $this->lang->line('import_contact');
            $data['sales_view'] = $this->viewname;

            $data['main_content'] = '/importContact';
           // $data['js_content'] = '/loadJsFiles';
            $this->load->view('/importContact', $data);

        }
        
        
    }
    
    /*
     @Author   : Sanket Jayani
     @Desc     : Import CSV OR Excel File to import contact
     @Input    : search post data
     @Output   : import data to contact_master table
     @Date     : 07/03/2016
    */
    function importContactdata()
    {
         
        $config['upload_path'] = FCPATH.'uploads/csv_contact';
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
            $file_path =  './uploads/csv_contact/'.$new_name; 
            
            $this->load->library('excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file_path);
            
            $cell_collection = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
          
            $key_contact_name = array_search('Contact Name', $cell_collection[1]);
            $key_company_name = array_search('Company Name', $cell_collection[1]);
            $key_company_email = array_search('Company Email', $cell_collection[1]);
            $key_company_phone = array_search('Company Phone', $cell_collection[1]);
            $key_company_branch = array_search('Company Branch', $cell_collection[1]);
            $key_job_title = array_search('Job Title', $cell_collection[1]);
            $key_phone_number = array_search('Phone Number', $cell_collection[1]);
            $key_mobile_number = array_search('Mobile  Number', $cell_collection[1]);
            $key_address_1 = array_search('Address1', $cell_collection[1]);
            $key_address_2 = array_search('Address2', $cell_collection[1]);
            $key_postal_code = array_search('Postal Code', $cell_collection[1]);
            $key_city = array_search('City', $cell_collection[1]);
            $key_state = array_search('State', $cell_collection[1]);
            $key_country = array_search('Country', $cell_collection[1]);
            $key_language = array_search('Language', $cell_collection[1]);
            $key_email= array_search('Email', $cell_collection[1]);
            $key_contact_for = array_search('Contact For', $cell_collection[1]);
            $key_fb = array_search('FB', $cell_collection[1]);
            $key_linkedin = array_search('Linkedin', $cell_collection[1]);
            $key_twitter = array_search('Twitter', $cell_collection[1]);
            
           
            $chk_file_column = array('Contact Name','Company Name','Company Email', 'Company Phone','Company Branch','Job Title','Phone Number','Mobile  Number','Address1','Address2','Postal Code','City','State','Country','Language','Email','Contact For','FB','Linkedin','Twitter');
           
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
            
            foreach ($cell_collection as $cell)
            {
                
                $value_contact_name = trim($cell[$key_contact_name]);
                $value_company_name = trim(strtolower($cell[$key_company_name]));
                $value_company_email = trim($cell[$key_company_email]);
                $value_company_phone = trim($cell[$key_company_phone]);
                $value_company_branch = trim($cell[$key_company_branch]);
                $value_job_title = trim($cell[$key_job_title]);
                $value_phone_number = trim($cell[$key_phone_number]);
                $value_mobile_number = trim($cell[$key_mobile_number]);
                $value_address_1 = $cell[$key_address_1];
                $value_address_2 = $cell[$key_address_2];
                $value_postal_code = $cell[$key_postal_code];
                $value_city = trim($cell[$key_city]);
                $value_state = trim($cell[$key_state]);
                $value_country = trim($cell[$key_country]);
                $value_language = trim($cell[$key_language]);
                $value_email= trim($cell[$key_email]);
                $value_contact_for = trim($cell[$key_contact_for]);
                $value_fb = $cell[$key_fb];
                $value_linkedin = $cell[$key_linkedin];
                $value_twitter = $cell[$key_twitter];
                
                
                $tmp_company_id = $this->common_model->getComapnyIdByName($value_company_name);
                $company_id = $this->common_model->getComapnyIdByName($value_company_name);
                $country_id = $this->common_model->getCountryIdByName($value_country);
               
                $language_arr = getLanguages();
                $language_id = 0 ;
                
                if($value_language != '')
                {
                    foreach ($language_arr as $language)
                    {
                        if(trim(strtolower($value_language)) == $language['language_name'])
                        {
                            $language_id = $language['language_id'];
                            break;
                        }
                    }
                    
                }
               
                
                if ($tmp_company_id == 0 && filter_var($value_company_email, FILTER_VALIDATE_EMAIL) && $value_company_email != '') {
                   
                    $tmp_branch_id = $this->common_model->getComapnyBranchIdByName($value_company_branch);
                    
                    if($tmp_branch_id == 0 && $value_company_branch != '')
                    {
                        $branch_data['branch_name'] = $value_company_branch;
                        $branch_data['created_date'] = datetimeformat();
                        $branch_data['modified_date'] = datetimeformat();
                        $branch_data['status'] = 1;
                        $branch_id = $this->common_model->insert(BRANCH_MASTER, $branch_data);
                    }else
                    {
                        $branch_id = $tmp_branch_id;
                    }
                    
                    $company_data['company_name'] = $value_company_name;
                    $company_data['country_id'] = $country_id;
                    $company_data['branch_id'] = $branch_id;
                    $company_data['email_id'] = $value_company_email;
                    $company_data['phone_no'] = $value_company_phone;
                    $company_data['status'] = 1;
                    $company_data['is_delete'] = 0;
                    $company_data['created_date'] = datetimeformat();
                    $company_data['modified_date'] = datetimeformat();
                    $company_id = $this->common_model->insert(COMPANY_MASTER, $company_data);
                } else {
                    $company_id = $tmp_company_id;
                }
               
                $data['contact_name'] = $value_contact_name;
                $data['company_id'] = $company_id;
                $data['job_title'] = $value_job_title;
                $data['phone_number'] = $value_phone_number;
                $data['mobile_number'] = $value_mobile_number;
                $data['address1'] = str_replace(';',',',$value_address_1);
                $data['address2'] = str_replace(';',',',$value_address_2);
                $data['postal_code'] = $value_postal_code;
                $data['city'] = $value_city;
                $data['state'] = $value_state;
                $data['country_id'] = $country_id;
                $data['language_id'] = $language_id;
                $data['email'] = $value_email;
                $data['contact_for'] = $value_contact_for;
                
                $data['fb'] = $value_fb;
                $data['linkdin'] = $value_linkedin;
                $data['twitter'] = $value_twitter;
                $data['is_delete'] = 0;
                $data['created_date'] = datetimeformat();
                $data['status'] = 1;
                
                
                $table_grp = CONTACT_MASTER;
                $fields_grp = array("contact_id");
                $match_grp = array('is_delete' => '0', 'status' => '1','email'=>$data['email']);
                $contact_data_arr = $this->common_model->get_records($table_grp,$fields_grp, '', '', $match_grp);
               
                if( empty($contact_data_arr) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) && $data['contact_name'] !='')
                {
                  
                    $flg_record  = $this->common_model->insert(CONTACT_MASTER, $data);
                  
                    if($flg_record )
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
               
            }
            
           $msg = lang('SUCCESSFULLY_IMPORTED').' '. lang('TOTAL_RECORD').' :'. $total_record.','. lang('SUCCESSFULLY_IMPORTED').' :'. $count_success.', '. lang('FAIL_RECORD').' :'. $count_fail ;
            $this->session->set_flashdata('msg', $msg);
        }
        
        redirect($this->viewname); 
    }
    
    function addNote($id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data['note_related_id'] = $id;
            $data['modal_title'] = $this->lang->line('ADD_NOTES');
            $data['submit_button_title'] = $this->lang->line('ADD_NOTES');
            $data['sales_view'] = $this->viewname;

            $data['main_content'] = '/AddNote';
            $this->load->view('/AddNote', $data);
        }
        
    }
    
    function view_add_campaign($id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['contact_id'] = $id;
            $data['modal_title'] = $this->lang->line('ADD_TO_CAMPAIGN');
            $data['submit_button_title'] = $this->lang->line('ADD_TO_CAMPAIGN');
            $data['sales_view'] = $this->viewname;
            $data['campaign_data'] = $this->common_model->getAllCampaignData();

            $redirect_link = $_SERVER['HTTP_REFERER'];
            if (strpos($redirect_link, 'Lead/viewdata') !== false) {
                $campaign_status=$campaign_data['campaign_status']  = 3;
            }
            elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
                $campaign_status=$campaign_data['campaign_status']  = 4;
            }
            elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
               $campaign_status=$campaign_data['campaign_status']  = 2;
            }
            elseif(strpos($redirect_link, 'Contact') !== false) {
                $campaign_status=$campaign_data['campaign_status'] = 1;
            }

            $table1 = TBL_CAMPAIGN_CONTACT . ' as cm';
            $match1 = "cm.status=1 AND cm.campaign_related_id = $id AND campaign_status = $campaign_status";
            $fields1 = array("cm.campaign_id");
            $campaign_contact = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            $tmp_arr = [];
            if(is_array($campaign_contact) && count($campaign_contact) > 0)
            {

                foreach ($campaign_contact as $id_arr)
                {
                    $tmp_arr[] =     $id_arr['campaign_id'];
                }
            }

            if(is_array($tmp_arr) && count($tmp_arr) > 0)
            {
                $i=0;
                $cam_arr = [] ; 
                foreach ($data['campaign_data'] as $abc_data)
                {
                    if(!in_array($abc_data['campaign_id'], $tmp_arr))
                    {    
                    $cam_arr[$i]['campaign_name'] = $abc_data['campaign_name'];
                    $cam_arr[$i]['campaign_id'] = $abc_data['campaign_id'];
                    $i++;
                    }
                }
                $data['campaign_data'] = $cam_arr;
            }


            $data['main_content'] = '/AddCampaign';
            $this->load->view('/AddCampaign', $data);

        }
        
    }
    
    function insertNote()
    {
        
        if($this->input->post('note_related_id'))
        {
            $contact_id = $this->input->post('note_related_id');
        }else
        {
            $contact_id = $this->input->post('contact_id');
        }
        $add_comm = $this->input->post('add_to_communication');
        
        if($add_comm == 'on')
        {
            $add_to_communication = 1;
        }else
        {
            $add_to_communication = 0;
        }
        $redirect_link = $this->input->post('redirect_link');
        
        if (!validateFormSecret()) {
            
            redirect($redirect_link); //Redirect On Listing page
        }
        
        $data['notes_related_id'] = $contact_id;
        $data['note_subject'] = $this->input->post('note_subject');
        $data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $data['note_description'] = $this->input->post('note_description');
        $data['add_to_communication'] =$add_to_communication;
        $data['created_date'] = datetimeformat();
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
            $data['note_status'] = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $data['note_status'] = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
            $data['note_status'] = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false || strpos($redirect_link, 'SalesOverview') !== false) {
            $data['note_status'] = 1;
        }else
        {
            $data['note_status'] = 1;
        }
        
        $flg_record  = $this->common_model->insert(TBL_NOTE, $data);
        
        //start code for if add to c0mmunication checked
        if($flg_record && $add_to_communication == 1)
        {
            $comm_data['comm_date'] = date('Y-m-d', strtotime(datetimeformat()));
            $comm_data['comm_sender'] = $this->session->userdata('LOGGED_IN')['ID'];
            $comm_data['comm_receiver'] = $contact_id;
            $comm_data['comm_subject'] = $data['note_subject'];
            $comm_data['comm_content'] = $data['note_description'];
            $comm_data['comm_type'] = 3;
            $comm_data['note_id'] = $flg_record;
            $comm_data['comm_related_id'] = $contact_id;
            $comm_data['is_delete'] = 0;
            $comm_data['created_date'] =datetimeformat();
            $this->common_model->insert(TBL_EMAIL_COMMUNICATION, $comm_data);
        }
                
        if($flg_record)
        {
            if (strpos($redirect_link, '/SalesOverview') !== false) 
            {
                $msg = lang('SEND_MESSAGE_SUCCESS');
            }else
            {
                $msg = lang('ADD_NOTE_SUCCESS_MSG');
            }
            $this->session->set_flashdata('message', $msg);
        }else
        {
            $msg = lang('error_msg');
            $this->session->set_flashdata('error', $msg);
        }
        $sess_array = array('setting_current_tab' => 'Notes');
	$this->session->set_userdata($sess_array);
        redirect($redirect_link); 
    }
    
    function insertContactToCampaign()
    {
        $redirect_link = $this->input->post('redirect_link');
        if (!validateFormSecret()) {
          //  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect($redirect_link); //Redirect On Listing page
        }
        $contact_id = $this->input->post('contact_id');
        $campaign_id_arr = $this->input->post('campaign_id');
        
        
        $campaign_data['campaign_related_id'] = $contact_id;
        $campaign_data['campaign_status'] = 1;
        $campaign_data['is_delete'] = 0;
        $campaign_data['status'] = 1;
        
      
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
            $campaign_status=$campaign_data['campaign_status']  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $campaign_status=$campaign_data['campaign_status']  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $campaign_status=$campaign_data['campaign_status']  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $campaign_status=$campaign_data['campaign_status'] = 1;
        }
        
        foreach ($campaign_id_arr as $campaign_id)
        {
            $campaign_data['campaign_id'] = $campaign_id;
            
            $table1 = TBL_CAMPAIGN_CONTACT . ' as cm';
            $match1 = "cm.status=1 AND cm.campaign_id = $campaign_id AND cm.campaign_related_id = $contact_id AND campaign_status = $campaign_status";
            $fields1 = array("cm.campaign_contact_id");
            $company_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
            
            if(count($company_data) > 0)
            {
                $this->session->set_flashdata('error', lang('CONTACT_ALREADY_EXIST'));
                
            }else
            {
                $flg_record  = $this->common_model->insert(TBL_CAMPAIGN_CONTACT, $campaign_data);

                if($flg_record)
                {
                    $this->session->set_flashdata('message', lang('SUCCESS_CAMPAIGN_ADDED'));
                }else
                {
                    $this->session->set_flashdata('error', lang('error_msg'));
                }
            }
        }
        
        $sess_array = array('setting_current_tab' => 'Campaign');
	$this->session->set_userdata($sess_array);
        redirect($redirect_link); 
    }
    
    function updateNote($id)
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data['modal_title'] = $this->lang->line('UPDATE_NOTES');
            $data['note_id'] = $id;
            $previewProducts['fields'] = ['*'];
            $previewProducts['table'] = TBL_NOTE . ' as CN';
            $previewProducts['match_and'] = 'CN.note_id=' . $id;
            $data['submit_button_title'] = $this->lang->line('UPDATE_NOTES');
            $data['editRecord'] = $this->common_model->get_records_array($previewProducts);

            $data['contact_id'] = $data['editRecord'][0]['notes_related_id'];
            $data['main_content'] = '/AddNote';
            $this->load->view('/AddNote', $data);
        }
        
    }
    
    function delete_note()
    {
        
        $note_id = $this->input->get('note_id');
        $redirect_url = $this->input->get('link');
        if (!empty($note_id)) 
        {
            $data['is_delete'] = '1';
            $where = array('note_id' => $note_id);
            $success_delete = $this->common_model->update(TBL_NOTE,$data,$where);
            $this->common_model->update(TBL_EMAIL_COMMUNICATION,$data,$where);
            
            if($success_delete)
            {
                $this->session->set_flashdata('message', lang('MSG_NOTE_DELETE_SUCCESSFULLY'));
            }else
            {
                $this->session->set_flashdata('error', lang('error_msg'));
            }
        
        }
        $sess_array = array('setting_current_tab' => 'Notes');
        $this->session->set_userdata($sess_array);
        redirect($redirect_url);
    }
    
    function delete_campaign()
    {
        $redirect_url=$_SERVER['HTTP_REFERER'];
        $campaign_contact_id = $this->input->get('campaign_contact_id');
        if (!empty($campaign_contact_id)) 
        {
            
            
            
            $data['is_delete'] = '1';
            $data['status'] = '0';
            $where = array('campaign_contact_id' => $campaign_contact_id);
            $success_delete = $this->common_model->update(TBL_CAMPAIGN_CONTACT,$data,$where);
           
            $table = TBL_CAMPAIGN_CONTACT . ' as cc';
            $match = "cc.campaign_contact_id = " . $campaign_contact_id;
            $fields = array("cc.campaign_id,cc.campaign_related_id");
            $data['campaign_contact'] = $this->common_model->get_records($table, $fields, '', '', $match);
            
            $campaign_id = $data['campaign_contact'][0]['campaign_id'];
            $campaign_related_id = $data['campaign_contact'][0]['campaign_related_id'];
            
            $where_trans = array('campaign_id' => $campaign_id,'contact_id'=>$campaign_related_id);
            $this->common_model->delete(CAMPAIGN_RECEIPIENT_TRAN,$where_trans);
            
            if($success_delete)
            {
                $msg = lang('SUCCESS_CAMPAIGN_DELETED');
                $this->session->set_flashdata('message', $msg);
            }else
            {
                $msg = lang('error_msg');
                $this->session->set_flashdata('error', $msg);
            }
        
        }
        $sess_array = array('setting_current_tab' => 'Campaign');
	$this->session->set_userdata($sess_array);
        redirect($redirect_url);
    }
    function deleteLeadCampaign()
    {
        $redirect_url=$_SERVER['HTTP_REFERER'];
        $lead_id = $this->input->get('lead_id');
        if (!empty($lead_id)) 
        {
            $where = array('lead_id' => $lead_id);
            $lead_data['campaign_id']=0;
            $success_delete = $this->common_model->update(LEAD_MASTER, $lead_data, $where);
            if($success_delete)
            {
                $msg = lang('SUCCESS_CAMPAIGN_DELETED');
                $this->session->set_flashdata('message', $msg);
            }else
            {
                $msg = lang('error_msg');
                $this->session->set_flashdata('error', $msg);
            }
        
        }
        $sess_array = array('setting_current_tab' => 'Campaign');
	$this->session->set_userdata($sess_array);
        redirect($redirect_url);
    }
    function deleteProspectCampaign()
    {
        $redirect_url=$_SERVER['HTTP_REFERER'];
        $prospect_id = $this->input->get('prospect_id');
        if (!empty($prospect_id)) 
        {
            $where = array('prospect_id' => $prospect_id);
            $prospect_data['campaign_id']=0;
            $success_delete = $this->common_model->update(LEAD_MASTER, $prospect_data, $where);
            if($success_delete)
            {
                $msg = lang('SUCCESS_CAMPAIGN_DELETED');
                $this->session->set_flashdata('message', $msg);
            }else
            {
                $msg = lang('error_msg');
                $this->session->set_flashdata('error', $msg);
            }
        
        }
        $sess_array = array('setting_current_tab' => 'Campaign');
	$this->session->set_userdata($sess_array);
        redirect($redirect_url);
    }
    
    function viewNote($id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['note_related_id'] = $id;
            $data['modal_title'] = $this->lang->line('VIEW_NOTES');

            $params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=td.created_by');
            $params['join_type'] = 'left';
            $fields = array("td.*,(SELECT contact_name FROM blzdsk_contact_master as cm WHERE cm.contact_id=td.notes_related_id) as contact_name, CONCAT(l.firstname,' ',l.lastname) as login_user_name");
            $where = array("td.is_delete" => "0","td.note_id"=>$id);

            $data['note_data'] = $this->common_model->get_records(TBL_NOTE . ' as td', $fields, $params['join_tables'],$params['join_type'],'', '', '', '', '', '', '', $where,'','','','','','');
            $data['main_content'] = '/ViewNote';
            $this->load->view('/ViewNote', $data);
        }
        
    }
    
    function updateNoteRecord()
    {
        $add_comm = $this->input->post('add_to_communication');
        
        if($add_comm == 'on')
        {
            $add_to_communication = 1;
        }else
        {
            $add_to_communication = 0;
        }
	
        $note_data['note_description'] = $this->input->post('note_description');
        $note_data['note_subject'] = $this->input->post('note_subject');
        $note_data['add_to_communication'] =$add_to_communication;
        $note_id = $this->input->post('note_id');
        $redirect_link = $this->input->post('redirect_link');
        $where = array('note_id' => $note_id);
        
        $success_delete = $this->common_model->update(TBL_NOTE, $note_data, $where);
        
        $table1 = TBL_NOTE . ' as n';
        $match1 = "n.note_id = ".$note_id;
        $fields1 = array("n.notes_related_id");
        $note_related_id = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        $contact_id = $note_related_id[0]['notes_related_id'];
       
        if($add_to_communication == 1)
        {
            $table1 = TBL_EMAIL_COMMUNICATION . ' as ec';
            $match1 = "ec.note_id = ".$note_id;
            $fields1 = array("ec.comm_id");
            $exists_note_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
           
            $comm_data['comm_subject'] = $note_data['note_subject'];
            $comm_data['comm_content'] = $note_data['note_description'];
             $comm_data['is_delete'] = 0;
            if(empty($exists_note_data))
            {
                $comm_data['comm_date'] = date('Y-m-d', strtotime(datetimeformat()));
                $comm_data['comm_type'] = 3;
                $comm_data['comm_related_id'] = $contact_id;
               
                $comm_data['comm_sender'] = $this->session->userdata('LOGGED_IN')['ID'];
                $comm_data['comm_receiver'] = $contact_id;
                $comm_data['note_id'] = $note_id;
                $comm_data['created_date'] =datetimeformat();
                $this->common_model->insert(TBL_EMAIL_COMMUNICATION, $comm_data);
            }else
            {
               
                $where = array('note_id' => $note_id);
                $this->common_model->update(TBL_EMAIL_COMMUNICATION, $comm_data, $where);
            }
            
            
        }else
        {
           
            $comm_del_data['is_delete'] = 1;
            $where = array('note_id' => $note_id);
            $this->common_model->update(TBL_EMAIL_COMMUNICATION, $comm_del_data, $where);
          
        }
      
        if($success_delete)
        {
            
            $msg = lang('MSG_NOTE_UPDATE_SUCCESSFULLY');
            $this->session->set_flashdata('message', $msg);
        }else
        {
            $msg = lang('error_msg');
            $this->session->set_flashdata('error', $msg);
        }
        
        $sess_array = array('setting_current_tab' => 'Notes');
	$this->session->set_userdata($sess_array);
        redirect($redirect_link); 
    }
    
    public function inserttask() 
    {
        if ($this->input->post('task_id')) {
            $id = $this->input->post('task_id');
        }
        
        if ($this->input->post('redirect')) {
            $redirect_link = $this->input->post('redirect');
        }
        if (!validateFormSecret()) {
            if ($id) {
                $msg = $this->lang->line('task_update_msg');
                $this->session->set_flashdata('message', $msg);
            }
            else{
                $msg = $this->lang->line('task_add_msg');
                $this->session->set_flashdata('message', $msg);
            }
            redirect($redirect_link); //Redirect On Listing page
        }
        $data = array();
        $data['task_view'] = $this->viewname;

        $task_data['contact_id'] = $this->input->post('contact_id');
        $task_data['task_name'] = trim($this->input->post('task_name'));
        $task_data['importance'] = $this->input->post('importance');
        $task_reminder = '0';
        if ($this->input->post('reminder') == 'on') {
            $task_reminder = '1';
        }
        $task_data['remember'] = $task_reminder;
        $task_data['before_status'] = $this->input->post('before_after');
        $task_data['repeat'] = $this->input->post('repeat');
        $task_data['time'] = date('h:i:s a', strtotime($this->input->post('remind_time')));

        $task_data['time'] = $this->input->post('remind_time');
        $task_data['remind_before_min'] = $this->input->post('remind_day');
        $task_data['task_description'] = $this->input->post('task_description');
        $task_data['start_date'] = date_format(date_create($this->input->post('start_date')), 'Y-m-d');
        $task_data['end_date'] = date_format(date_create($this->input->post('end_date')), 'Y-m-d');
        $task_data['status'] = 1;

        $task_data['modified_date'] = datetimeformat();
        //Insert Record in Database
        if ($id) {
            $where = array('task_id' => $id);
            $success_update = $this->common_model->update(TBL_CONTACT_TASK_MASTER, $task_data, $where);
            if ($success_update) {
                $msg = $this->lang->line('task_update_msg');
                $this->session->set_flashdata('msg', $msg);
            }
            else{
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('msg', $msg);
            }
        } else {
            $task_data['created_date'] = datetimeformat();
            $success = $this->common_model->insert(TBL_CONTACT_TASK_MASTER, $task_data);
            if ($success) {
                $msg = $this->lang->line('task_add_msg');
                $this->session->set_flashdata('msg', $msg);
            }
            else{
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('msg', $msg);
            }
        }
        $sess_array = array('setting_current_tab' => 'Task');
	$this->session->set_userdata($sess_array);
        redirect($redirect_link);
    }
    
    public function edittask($id) 
    {
        
        $table = TBL_CONTACT_TASK_MASTER . ' as tm';
        $match = "tm.task_id = " . $id;
        $fields = array("tm.task_id,tm.contact_id,tm.task_name,tm.importance,tm.remember,tm.before_status,tm.repeat,tm.time,tm.remind_before_min,tm.task_description,tm.start_date,"
            . "tm.end_date");
        $data['edit_record'] = $this->common_model->get_records($table, $fields, '', '', $match);
        
        $data['task_id'] = $id;
        $data['contact_id'] = $data['edit_record'][0]['contact_id'];
        $data['id'] = $id;
        $data['task_view'] = 'Task';
        $data['modal_title'] = $this->lang->line('update_task');
        $data['submit_button_title'] = $this->lang->line('update_task');
        
        $data['main_content'] = '/Task';
        $this->load->view('AddTask',$data);
        
    }
    
    public function delete_task() {
        
        $id = $this->input->get('task_id');
        $redirect_url = $this->input->get('link');
        if ($id) 
        {
            $where = array('task_id' => $id);
            
            $task_data['is_delete']=1;
            $task_data['status']=0;
            $delete_suceess = $this->common_model->update(TASK_MASTER, $task_data, $where);
            
            if($delete_suceess)
            {
                $msg = lang('MSG_TASK_DELETE_SUCCESSFULLY');
                $this->session->set_flashdata('message', $msg);
            }else
            {
                $msg = lang('error_msg');
                $this->session->set_flashdata('error', $msg);
            }
        }
        $sess_array = array('setting_current_tab' => 'Task');
	$this->session->set_userdata($sess_array);
        redirect($redirect_url);
    }
    
    function viewContactData()
    {
       
        $contact_related_id=$this->input->post('contact_related_id');
        if($contact_related_id!="")
        {
            $contact_id = $contact_related_id;
        }
        else{
            $contact_id = $_SESSION['current_related_id'];
        }
        $data['contact_id'] = $contact_id;
        $contact_status=  $this->input->post('contact_status');
        
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {
            
           $where_search = '(cm.contact_name  LIKE "%' . $searchtext . '%" OR cm.email LIKE "%' . $searchtext . '%"OR cm.mobile_number LIKE "%' . $searchtext . '%")';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(cm.contact_name  LIKE "%' . $searchtext1 . '%" OR cm.email LIKE "%' . $searchtext1 . '%"OR cm.mobile_number LIKE "%' . $searchtext1 . '%")';
        }else
        {
            $this->session->set_userdata('searchtext','');
        }
     
        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        
        //config variable for the pagination
        $config['base_url'] = site_url($data['project_view'] . '/viewContactData');
        
        $data['contatcSortField'] = 'contact_name';
        $data['contactSortOrder'] = 'desc';
        //Only get Contact for view Contact tab
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
            $params['join_tables'] = array(
            LEAD_CONTACTS_TRAN . ' as lct' => 'lct.lead_id=lm.lead_id',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=lct.contact_id');
            $params['join_type'] = 'left';
            $match = "lm.lead_id = " . $contact_id . " and cm.is_delete=0 and cm.status=1 and lm.is_delete=0 and lm.status_type=2 and lm.status=1 ";
            $table = LEAD_MASTER . ' as lm';
            $fields = array("lm.lead_id as prospect_id,cm.contact_id,cm.contact_name as name,cm.mobile_number as number,cm.email as email,lct.contact_id,lct.primary_contact");
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $params['join_tables'] = array(
            OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $contact_id . " and cm.is_delete=0 and cm.status=1 and pm.is_delete=0 and pm.status_type=1 and pm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';
            $fields = array("pm.prospect_id,cm.contact_id,cm.contact_name as name,cm.mobile_number as number,cm.email as email,orc.contact_id,orc.primary_contact");
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
            $params['join_tables'] = array(
            OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id');
            $params['join_type'] = 'left';
            $match = "pm.prospect_id = " . $contact_id . " and cm.is_delete=0 and cm.status=1 and pm.is_delete=0 and pm.status_type=3 and pm.status=1 ";
            $table = PROSPECT_MASTER . ' as pm';
            $fields = array("pm.prospect_id,cm.contact_id,cm.contact_name as name,cm.mobile_number as number,cm.email as email,orc.contact_id,orc.primary_contact");
        }
        
      
        
        $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', '', '', '', '', '1', '', '', $where_search);
        $config['per_page'] =5;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['contatcSortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['contactSortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['contact_info'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', $config['per_page'], $data['page'], $data['contatcSortField'], $data['contactSortOrder'], '', '', '', '', '', '', '', $where_search);
//        $data['note_data'] = $this->common_model->get_records(TBL_NOTE . ' as td', $fields, '', '', '', '', $config['per_page'], $data['page'], $data['contatcSortField'], $data['contactSortOrder'], '', $where,'','','','','',$where_search);
       
        $data['config'] = $config;
         $page_url = $config['base_url'] . '/' . $data['page'];
        //$this->pagination->initialize($config);
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        $data['main_content'] = '/AjaxContacts';
        $this->load->view('/AjaxContacts', $data);
    }
    
    function viewNoteData()
    {
        
        //$contact_id = $_SESSION['current_related_id'];
        $note_related_id=$this->input->post('note_related_id');
        if($note_related_id!="")
        {
            $contact_id = $note_related_id;
        }
        else{
            $contact_id = $_SESSION['current_related_id'];
        }
        $data['contact_id'] = $contact_id;
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $note_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $note_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $note_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $note_status = 1;
        }
        
        //commented by sanket on 30-03-2016
        //$note_status=  $this->input->post('note_status');
        
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {
            
           $where_search = '(td.note_subject LIKE "%' . $searchtext . '%" OR td.note_description LIKE "%' . $searchtext . '%")';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(td.note_subject LIKE "%' . $searchtext1 . '%" OR td.note_description LIKE "%' . $searchtext1 . '%")';
        }else
        {
            $this->session->set_userdata('searchtext','');
        }
     
        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        
        //config variable for the pagination
        $config['base_url'] = site_url($data['project_view'] . '/viewNoteData');
        
        $params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=td.created_by');
        $params['join_type'] = 'left';
        
        $data['tasksortField'] = 'created_date';
        $data['tasksortOrder'] = 'desc';
        $fields = array("td.note_id,td.notes_related_id,td.note_subject,td.created_by,td.note_description,td.is_delete,td.created_date,CONCAT(l.firstname,' ',l.lastname) as login_user_name");
        $where = array("td.is_delete" => "0","td.notes_related_id"=>$contact_id,"note_status"=>$note_status);
         
        $config['total_rows'] = count($this->common_model->get_records(TBL_NOTE . ' as td', $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search));
        $config['per_page'] =5;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['note_data'] = $this->common_model->get_records(TBL_NOTE . ' as td', $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search);
       
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        //$this->pagination->initialize($config);
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        
        $data['main_content'] = '/AjaxNotes';
        $this->load->view('/AjaxNotes', $data);
    }
    
    public function viewTaskData()
    {
        
        $task_related_id=$this->input->post('task_related_id');
        if($task_related_id!="")
        {
            $contact_id = $task_related_id;
        }
        else{
            $contact_id = $_SESSION['current_related_id'];
        }
        
        $data['contact_id'] = $contact_id;
        
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $task_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $task_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $task_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $task_status = 1;
        }
        
        
        //$task_status=  $this->input->post('task_status');
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
                $searchtext = '';
                $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {

           $where_search = '(td.task_name LIKE "%' . $searchtext . '%" )';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
                $searchtext1 = $this->session->userdata('searchtext');
                $where_search = '(td.task_name LIKE "%' . $searchtext1 . '%" )';
        }else
        {
                $this->session->set_userdata('searchtext','');
        }
        
        
        
        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        
        $config['base_url'] = site_url($data['project_view'] . '/viewTaskData');
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $data['tasksortField'] = 'task_id';
        $data['tasksortOrder'] = 'desc';
        $fields = array("td.task_id,td.task_assign,td.task_related_id,td.task_name,td.importance,td.remember,td.reminder_date,td.reminder_time,td.task_description,td.start_date,td.end_date,td.status,td.is_delete,td.created_date");
        $where = array("td.is_delete" => "0","td.status" =>"1","td.task_related_id"=>$contact_id,"td.task_assign"=>$user_id,"task_status"=>$task_status);
         
        $config['total_rows'] = count($this->common_model->get_records(TASK_MASTER . ' as td', $fields, '', '', '', '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search));
        $config['per_page'] =5;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }
       
        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['task_data'] = $this->common_model->get_records(TASK_MASTER . ' as td', $fields, '', '', '', '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search);
        
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        $data['main_content'] = '/AjaxTasks';
        $this->load->view('/AjaxTasks', $data);
    }
    
    public function viewDealsData()
    {
        
       $deals_related_id=$this->input->post('deals_related_id');
        if($deals_related_id!="")
        {
            $contact_id = $deals_related_id;
        }
        else{
            $contact_id = $_SESSION['current_related_id'];
        }
        
        $data['contact_id'] = $contact_id;
        
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $deal_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $deal_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $deal_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $deal_status = 1;
        }
        
        //commented by sanket on 30-03-2016
        //$deal_status=  $this->input->post('deal_status');
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
                $searchtext = '';
                $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {

           $where_search = '(pm.prospect_name LIKE "%' . $searchtext . '%" )';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
                $searchtext1 = $this->session->userdata('searchtext');
                $where_search = '(pm.prospect_name LIKE "%' . $searchtext1 . '%" )';
        }else
        {
                $this->session->set_userdata('searchtext','');
        }
        
        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        
         $params['join_tables'] = array(
            OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
            CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id ');
        $params['join_type'] = 'left';
        
        $config['base_url'] = site_url($data['project_view'] . '/viewDealsData');
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $data['tasksortField'] = 'prospect_id';
        $data['tasksortOrder'] = 'desc';
        /*$fields = array("pm.*"); */
        $fields = array("pm.prospect_id,pm.prospect_assign,pm.prospect_related_id,pm.prospect_name,pm.prospect_auto_id, pm.status,pm.status_type,pm.creation_date,count(orc.prospect_id) as contact_count,cm.contact_name");
        $where = array("pm.is_delete" => "0","pm.status" =>"1","pm.status_type" => "1","pm.prospect_related_id"=>$contact_id,"pm.prospect_assign"=>$user_id,"pm.deal_status"=> $deal_status);
        $group_by = 'pm.prospect_id';
        $config['total_rows'] = count($this->common_model->get_records(PROSPECT_MASTER . ' as pm', $fields, $params['join_tables'],$params['join_type'], '', '', '', '', $data['tasksortField'], $data['tasksortOrder'],$group_by, $where,'','','','','',$where_search));
        $config['per_page'] =5;
        $choice = $config["total_rows"] / $config["per_page"];
        
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }
       
        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['prospect_data'] = $this->common_model->get_records(PROSPECT_MASTER . ' as pm', $fields, $params['join_tables'],$params['join_type'], '', '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], $group_by, $where,'','','','','',$where_search);
       
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        $data['main_content'] = '/AjaxDeals';
        $this->load->view('/AjaxDeals', $data);
    }
    
    public function viewCampaignData()
    {
        $campaign_related_id=$this->input->post('campaign_related_id');
        if($campaign_related_id!="")
        {
            $contact_id = $campaign_related_id;
        }
        else{
            $contact_id = $_SESSION['current_related_id'];
        }
        
        //commented by sanket  on 30-03-2016
        //Comment deleted by seema on 31-03-2016
       
         $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $campaign_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $campaign_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $campaign_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $campaign_status = 1;
        }
        
        $data['campaign_related_id'] = $contact_id;
        
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
                $searchtext = '';
                $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {

           $where_search = '(pc.campaign_name LIKE "%' . $searchtext . '%" )';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
                $searchtext1 = $this->session->userdata('searchtext');
                $where_search = '(pc.campaign_name LIKE "%' . $searchtext1 . '%" )';
        }else
        {
                $this->session->set_userdata('searchtext','');
        }

        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        
        $params['join_tables'] = array(CAMPAIGN_MASTER . ' as pc' => 'ct.campaign_id=pc.campaign_id',CAMPAIGN_TYPE_MASTER . ' as cm' => 'cm.camp_type_id=pc.campaign_type_id');
        $params['join_type'] = 'left';
        
        $config['base_url'] = site_url($data['project_view'] . '/viewCampaignData');
        //$user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $data['tasksortField'] = 'campaign_contact_id';
        $data['tasksortOrder'] = 'desc';
        
        $table = TBL_CAMPAIGN_CONTACT.' as ct';
        $where = array("ct.is_delete" => "0","ct.campaign_related_id"=>$contact_id,"ct.campaign_status"=>$campaign_status);
        $fields = array("ct.campaign_contact_id,pc.campaign_name,cm.camp_type_name,ct.campaign_id,ct.campaign_related_id,ct.is_delete,ct.status");
        
        $config['total_rows']  = $this->common_model->get_records($table,$fields,$params['join_tables'],$params['join_type'],'','','','','','','',$where,'','','1','','',$where_search);
        $config['per_page'] =5;
        $choice = $config["total_rows"] / $config["per_page"];
        
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }
       
        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['campaign_info'] = $this->common_model->get_records($table,$fields,$params['join_tables'],$params['join_type'],'','',$config['per_page'],$data['page'],$data['tasksortField'],$data['tasksortOrder'],'',$where,'','','','','',$where_search);
        
//        if($campaign_status==3){
//            $params['join_tables'] = array(CAMPAIGN_MASTER . ' as pc' => 'ct.campaign_id=pc.campaign_id',CAMPAIGN_TYPE_MASTER . ' as cm' => 'cm.camp_type_id=pc.campaign_type_id');
//        $params['join_type'] = 'left';
//        
//        $config['base_url'] = site_url($data['project_view'] . '/viewCampaignData');
//        //$user_id =  $this->session->userdata('LOGGED_IN')['ID'];
//        
//        $table = LEAD_MASTER.' as ct';
//        $match="ct.campaign_id!=0";
//        $where = array("ct.is_delete" => "0","ct.lead_id"=>$contact_id);
//        $fields = array("pc.campaign_name,cm.camp_type_name,ct.campaign_id,ct.lead_id");
//        $data['lead_campaign_info'] = $this->common_model->get_records($table,$fields,$params['join_tables'],$params['join_type'],$match,'',$config['per_page'],$data['page'],'','','',$where,'','','','','',$where_search);
//        $data['flag']=1;
//        
//        }
//        if($campaign_status==2 || $campaign_status==4){
//            $params['join_tables'] = array(CAMPAIGN_MASTER . ' as pc' => 'ct.campaign_id=pc.campaign_id',CAMPAIGN_TYPE_MASTER . ' as cm' => 'cm.camp_type_id=pc.campaign_type_id');
//        $params['join_type'] = 'left';
//        
//        $config['base_url'] = site_url($data['project_view'] . '/viewCampaignData');
//        //$user_id =  $this->session->userdata('LOGGED_IN')['ID'];
//        
//        $table = PROSPECT_MASTER.' as ct';
//        $match="ct.campaign_id!=0";
//        $where = array("ct.is_delete" => "0","ct.prospect_id"=>$contact_id);
//        $fields = array("pc.campaign_name,cm.camp_type_name,ct.campaign_id,ct.prospect_id");
//        $data['lead_campaign_info'] = $this->common_model->get_records($table,$fields,$params['join_tables'],$params['join_type'],$match,'',$config['per_page'],$data['page'],'','','',$where,'','','','','',$where_search);
//        $data['flag']=2;
//        
//        }
       
        
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        $data['main_content'] = '/AjaxCampaign';
        $this->load->view('/AjaxCampaign', $data);
    }
    
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

        //$config['first_link'] = '&lt;&lt;';
       // $config['last_link'] = '&gt;&gt;';
        $config['num_links'] = 2;
       
        $this->pagination->cur_page = 4;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
    
    function send_email_view($contact_id = NULL)
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $table = CONTACT_MASTER . ' as cm';
            $match = "cm.contact_id = " . $contact_id;
            $fields = array("cm.contact_id,cm.company_id,cm.contact_name,cm.company_id");
            $data['contact_record'] = $this->common_model->get_records($table, $fields, '', '', $match);

            $table_prospect = PROSPECT_MASTER . ' as pm';
            $prospect_match = "pm.status_type = 1 AND pm.is_delete=0 AND pm.status=1 AND pm.company_id=".$data['contact_record'][0]['company_id'];
            $prospect_fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name");
            $data['prospect_data'] = $this->common_model->get_records($table_prospect, $prospect_fields, '', '', $prospect_match);
            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");

            $data['user_data'] = $this->common_model->getSystemUserData();
            $data['email_template_data'] = $this->common_model->getEmailTemplateData();
            $data['comapny_contact_data'] = $this->Contact_model->getAllContactByCompany($data['contact_record'][0]['company_id']);
            $data['company_data'] = $this->common_model->getAllCompanyData();

            $data['modal_title'] = lang('SEND_EMAIL_OCNTACT');
            $data['main_content'] = '/SendEmail';
            $this->load->view('/SendEmail', $data);
        }
        
    }
    
    private function set_upload_options()
    {   
        //upload an image options
        $config = array();
        $config['upload_path'] = EMAIL_PROSPECT_ATTACH_PATH;
        $config['allowed_types'] = '*';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;

        return $config;
    }

    function sendProspectEmail()
    {
        
        // $prospect_id = $this->input->post('prospect_id');
       // $prospect_data = $this->Contact_model->getProspectDataById($prospect_id);
        //  $prospect_auto_id = $prospect_data[0]['prospect_auto_id'];
        //   $prospect_owner_id = $this->input->post('hdn_contact_id');
        $prospect_company_id = $this->input->post('hdn_company_id');
        $redirect_link = $this->input->post('redirect_link');
       // $prospect_owner_arr = $this->Contact_model->getContactEmailByContactId($prospect_owner_id);
        $prospect_owner_email =  $_SESSION['LOGGED_IN']['EMAIL'];
        $prospect_owner_name =  $_SESSION['LOGGED_IN']['FIRSTNAME']." ".$_SESSION['LOGGED_IN']['LASTNAME'];
        
        if (!validateFormSecret()) {
           
            redirect($redirect_link); //Redirect On Listing page
        }
        $cc_employee_id = $this->input->post('cc_employee');
        if($cc_employee_id != '')
        {
             $cc_email_address =  $this->Contact_model->getMultipleLoginUserEmail($cc_employee_id);
        }
       
        $arr_receipent_email = $this->input->post('company_contact');
        $arr_receipent_email[] = $this->input->post('hdn_contact_id');
       
        $contact_receipent_email = $this->Contact_model->getContactEmailbyId($arr_receipent_email);
      
        $email_subject = $this->input->post('email_subject');
        $email_contect = $this->input->post('email_content',false);
        
        $hdn_mark_as_important = $this->input->post('hdn_mark_as_important');
        
        //$email_data['prospect_auto_id'] = $prospect_auto_id;
        //  $email_data['prospect_id'] = $prospect_id;
        $email_data['company_id'] = $prospect_company_id;
       // $email_data['prospect_owner_id'] = $prospect_owner_id;
        $email_data['subject'] = $email_subject;
        $email_data['email_description'] = $email_contect;
        $email_data['send_to'] = implode(',',$arr_receipent_email);
        $email_data['created_date'] = datetimeformat();
        $email_data['modified_date'] = datetimeformat();
        $email_data['status'] = 1;
        
        $id = $last_email_prospect_id =  $this->common_model->insert(TBL_EMAIL_PROSPECT, $email_data);
        
       
         /* custom uplod code starts
         * 
         */
        $file_name = array();
        $file_array1 = $this->input->post('file_data');

        $file_name = $_FILES['cost_files']['name'];
        if (count($file_name) > 0 && count($file_array1) > 0) {
            $differentedImage = array_diff($file_name, $file_array1);
            foreach ($file_name as $file) {
                if (in_array($file, $differentedImage)) {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if (!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['cost_files']['name'][$key]);
                    unset($_FILES['cost_files']['type'][$key]);
                    unset($_FILES['cost_files']['tmp_name'][$key]);
                    unset($_FILES['cost_files']['error'][$key]);
                    unset($_FILES['cost_files']['size'][$key]);
                }
            }
        }
        $_FILES['cost_files'] = $arr = array_map('array_values', $_FILES['cost_files']);
        /* ends
         *
         */
       
        $tmp_url = base_url()."Contact";
        $uploadData = uploadImage('cost_files',EMAIL_PROSPECT_ATTACH_PATH,$tmp_url);

        /* ritesh code */
//
        $Marketingfiles = array();

        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $file_data = implode(",", $file2);
        } else {
            $file_data = '';
        }
        $compaigndata['file_name'] = $file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }

        /* end
         * 
         */
        $costFiles = array();
        $attach_arr_gallary = array();
        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $cost_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $attach_arr_gallary[] = FCPATH.$gallery_path[$i]."/".$cost_files[$i];
                    $costFiles[] = ['file_name' => $cost_files[$i], 'file_path' => $gallery_path[$i]."/", 'email_prospect_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
        $attach_arr_browse = array();
        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $attach_arr_browse[] = FCPATH."uploads/attach_email_prospect/".$files['file_name'];
                $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => EMAIL_PROSPECT_ATTACH_PATH, 'email_prospect_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }
        
        $email_atached_arr = array_merge($attach_arr_gallary,$attach_arr_browse);
        
        
        if (count($costFiles) > 0) {
            $where = array('email_prospect_id' => $id);
            //  $this->common_model->delete(COST_FILES, $where);
           
            if (!$this->common_model->insert_batch(TBL_EMAIL_PROSPECT_FILE_MASTER, $costFiles)) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                
               
                redirect($this->module . '/Contact/'); //Redirect On Listing page
            }
        }
        
        if($last_email_prospect_id)
        {
              
            $CI = & get_instance();
            $configs = getMailConfig();
           
            $config['protocol'] = $configs['email_protocol'];
            $config['smtp_host'] = $configs['smtp_host'];
            $config['smtp_port'] = $configs['smtp_port'];
            $config['smtp_user'] = $configs['smtp_user'];
            $config['smtp_pass'] = $configs['smtp_pass'];
            
            if($hdn_mark_as_important == "1")
            {
                $config['priority'] = 1;
            }
           
            $config['mailtype'] = 'html';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n"; 

            $CI->load->library('email', $config); 
            $CI->email->set_header('MIME-Version', '1.0\r\n');
            $CI->email->set_header('Disposition-Notification-To',$prospect_owner_email);
            $CI->email->from($prospect_owner_email, $prospect_owner_name);
            $CI->email->to($contact_receipent_email);
            
            if(isset($cc_email_address) && $cc_email_address != '')
            {
                $this->email->cc($cc_email_address);
            }
            foreach ($email_atached_arr as $attach)
            {
                $this->email->attach($attach);
            }
           
            $CI->email->subject($email_subject);
            $CI->email->message($email_contect);

            if($CI->email->send())
            {
                $msg = $this->lang->line('SENT_PROSPECT_EMAIL');
                $this->session->set_flashdata('message', $msg);
                
                foreach ($arr_receipent_email as $recepient)
                {
                    $email_communication['comm_date']  = date('Y-m-d');
                    $email_communication['email_prospect_id']  = $id;
                    $email_communication['comm_sender']  = $_SESSION['LOGGED_IN']['ID'];
                    $email_communication['comm_receiver']  =implode(',',$arr_receipent_email); 
                    $email_communication['comm_subject']  =$email_subject; 
                    $email_communication['comm_content']  =$email_contect; 
                    $email_communication['comm_type']  =2; 
                    $email_communication['is_delete']  =0; 
                    $email_communication['comm_related_id']  =$recepient; 
                    $email_communication['created_date']  =  datetimeformat(); 
                    $this->common_model->insert(TBL_EMAIL_COMMUNICATION, $email_communication);
                }
                
            }else
            {
                $msg = $this->lang->line('FAIL_WITH_SENDING_EMAIL');
                $this->session->set_flashdata('error', $msg);
            }

        }else
        {
            $msg = $this->lang->line('error');
            $this->session->set_flashdata('error', $msg);
        }
        unset($email_atached_arr);
        
        redirect($redirect_link);
    }
    
    function getProspectDataById()
    {
        $prospect_id = $this->input->post('prospect_id');
        
        if(isset($prospect_id) && $prospect_id !='')
        {
            $table = PROSPECT_MASTER . ' as pm';
            $match = "pm.prospect_id = " . $prospect_id;
            $fields = array("pm.prospect_id,pm.prospect_auto_id,pm.prospect_name,prospect_owner_id,company_id");
            $data['prospect_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            if(count($data['prospect_data'][0]) > 0)
            {
                echo  $data['prospect_data'][0]['prospect_auto_id']."/".$data['prospect_data'][0]['prospect_owner_id']."/".$data['prospect_data'][0]['company_id'];
            }else
            {
                echo NULL;
            }
        }else
        {
            echo NULL;
        }
        
        
    }
    
    function getEmailTemplateDataById()
    {
        $template_id = $this->input->post('template_id');
        if($template_id != '')
        {
            $table = EMAIL_TEMPLATE_MASTER . ' as tm';
            $match = "tm.status = 1 AND tm.template_id=".$template_id;
            $fields = array("tm.subject,tm.body,tm.template_id");
            $data['template_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            echo  $data['template_data'][0]['subject']."||".$data['template_data'][0]['body'];
            
        }else
        {
            echo NULL;
        }
    }
    
    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('Email_prospect_img_url') . '/' . $filename, $str);
    }
    
    function createDuplicate()
    {
        $contact_id = $this->input->get('id');
        
        $table5 = CONTACT_MASTER . ' as cm';
        $match5 = "cm.is_delete= 0 AND cm.contact_id = " . $contact_id;
        $fields5 = array("cm.*");
        $data['all_records'] = $this->common_model->get_records($table5, $fields5, '', '', $match5);
        $contact_data =  $data['all_records'][0];
        $contact_data['duplicate_contact_id'] = $contact_data['contact_id'];
        
        unset($contact_data['contact_id']);
       
        $success = $this->common_model->insert(CONTACT_MASTER, $contact_data);
        if ($success) 
        {
            $this->session->set_flashdata('msg', lang('SUCCESS_DUPLICATE_CONTACT'));
        }
        else{
            $this->session->set_flashdata('msg', lang('error'));
        }
        
        redirect($this->viewname);
    }
    
    function createDuplicateOpportunity()
    {
        $prospect_id = $this->input->get('id');
        $redirect_link=$_SERVER['HTTP_REFERER'];
        $table5 = PROSPECT_MASTER . ' as pm';
        $match5 = "pm.status=1 AND pm.is_delete= 0 AND pm.prospect_id = " . $prospect_id;
        $fields5 = array("pm.prospect_id,pm.prospect_name,pm.company_id,pm.status_type,"
                . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.city,pm.state,pm.country_id,pm.number_type1,pm.phone_no,pm.number_type2,pm.phone_no2,pm.prospect_owner_id,pm.language_id,"
                . "pm.branch_id,pm.estimate_prospect_worth,pm.prospect_generate,pm.campaign_id,pm.description,"
                . "pm.file,pm.contact_date,pm.status");
        $data['all_records'] = $this->common_model->get_records($table5, $fields5, '', '', $match5);
        $prospect_data =  $data['all_records'][0];
        $prospect_data['prospect_auto_id']=$this->common_model->opportunity_auto_gen_Id();
        $prospect_data['duplicate_opportunity_id'] = $prospect_data['prospect_id'];
        $prospect_data['created_by']=$this->session->userdata('LOGGED_IN')['ID'];
        unset($prospect_data['prospect_id']);
        $prospect_return_id = $this->common_model->insert(PROSPECT_MASTER, $prospect_data);
        if ($prospect_return_id) 
        {
            $this->session->set_flashdata('message', lang('success_duplicate_opportunity'));
        }
        else{
            $this->session->set_flashdata('error', lang('error'));
        }
        
        $prospectDescData = $this->common_model->get_records(OPPORTUNITY_REQUIREMENT, '', '', '', 'prospect_id=' . $prospect_id);
        $duplicateDescription['requirement_description']=$prospectDescData[0]['requirement_description'];
        $duplicateDescription['prospect_id']=$prospect_return_id;
        $duplicateDescription['created_date']=datetimeformat();
        $duplicateDescription['modified_date']= datetimeformat();
        $duplicateDescription['status']= 1;
        $opportunityId=$this->common_model->insert(OPPORTUNITY_REQUIREMENT, $duplicateDescription); 
        
         $table2 = OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc';
            $match2 = "orc.prospect_id = " . $prospect_id;
            $fields2 = array("orc.primary_contact,orc.contact_id");
            $opportunity_contact_data = $this->common_model->get_records($table2, $fields2, '', '', $match2);

            $OreqContacts = array();
            foreach ($opportunity_contact_data as $lead_contact_data) { // loop over results
                $OreqContacts['prospect_id'] = $prospect_return_id;
                $OreqContacts['requirement_id'] = $opportunityId;
                $OreqContacts['primary_contact'] = $lead_contact_data['primary_contact'];
                $OreqContacts['contact_id'] = $lead_contact_data['contact_id'];
                $OreqContacts['created_date'] = datetimeformat();
                $OreqContacts['modified_date'] = datetimeformat();
                $OreqContacts['status'] = 1;
                $this->common_model->insert(OPPORTUNITY_REQUIREMENT_CONTACTS, $OreqContacts); // insert each row to PROSPECT_CONTACTS_TRAN table
            }
        
             $table3 = PROSPECT_PRODUCTS_TRAN . ' as ppt';
            $match3 = "ppt.prospect_id = " . $prospect_id;
            $fields3 = array("ppt.product_id");
            $prospect_product_data = $this->common_model->get_records($table3, $fields3, '', '', $match3);
            $prosProdData = array();
            foreach ($prospect_product_data as $lead_product_data) {
                // loop over results
                $prosProdData['prospect_id'] = $prospect_return_id;
                $prosProdData['product_id'] = $lead_product_data['product_id'];
                $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $prosProdData); // insert each row to PROSPECT table
            }
        
            
            $table3 = FILES_SALES_MASTER . ' as flm';
            $match3 = "flm.prospect_id= " . $prospect_id;
            $fields3 = array("flm.file_name,flm.file_path,flm.upload_status,flm.type");
            $prospect_file_data = $this->common_model->get_records($table3, $fields3, '', '', $match3);
            $filesArr = array();
            foreach ($prospect_file_data as $lead_file_data) {
                // loop over results
                $filesArr['prospect_id'] = $prospect_return_id;
                $filesArr['file_name'] = $lead_file_data['file_name'];
                $filesArr['file_path'] = $lead_file_data['file_path'];
                $filesArr['upload_status'] = $lead_file_data['upload_status'];
                $this->common_model->insert(FILES_SALES_MASTER, $filesArr); // insert each row to PROSPECT table
            }
       
        redirect($redirect_link);
    }
    function viewMergeContact($contact_id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['contact_id'] = $contact_id;
            $data['modal_title'] = $this->lang->line('CONTACT_MERGE_DUPLICATE');
            $data['submit_button_title'] = $this->lang->line('CONTACT_MERGE_DUPLICATE');

            $table5 = CONTACT_MASTER . ' as cm';
            //change by sanket on 22-03-2016 for every contact can be merge
            //$match5 = "cm.is_delete= 0 AND is_merge=0 AND cm.duplicate_contact_id = " . $contact_id;
            $order_by = 'contact_name';
            $order = 'ASC';
            $match5 = "cm.is_delete= 0 AND is_merge=0";
            $fields5 = array("cm.contact_id,cm.contact_name");
            $data['contact_data'] = $this->common_model->get_records($table5, $fields5, '','', $match5,'','','',$order_by,$order);

            $data['main_content'] = '/MergeContact';
            $this->load->view('/MergeContact', $data);
        }
        
    }
    
    function merge_contact()
    {
        $contact_id = $this->input->post('contact_id');
        $id_merge_to_contact = $this->input->post('id_merge_to_contact');
        $redirect_link = $this->input->post('redirect_link');
        
        if (!validateFormSecret()) {
            redirect($redirect_link); //Redirect On Listing page
        }
        if($contact_id == $id_merge_to_contact)
        {
            $this->session->set_flashdata('error',lang('FAIL_MERGE_CONTACT'));
            redirect($redirect_link);
        }
        $contact_data['is_merge'] = 1;
        $contact_data['is_delete'] = 1;
        $contact_data['merge_with'] = $contact_id;
        
        $where = array('contact_id' => $id_merge_to_contact);
        
        $success_delete = $this->common_model->update(CONTACT_MASTER, $contact_data, $where);
        
        if($success_delete)
        {
            //merge all notes to contact
            $flg_merge_notes = $this->merge_notes_to_contact($contact_id,$id_merge_to_contact);
            
            if($flg_merge_notes)
            {
                $flg_merge_task = $this->merge_task_to_contact($contact_id,$id_merge_to_contact);
                
                if($flg_merge_task)
                {
                    $flg_merge_Deals = $this->merge_Deals_to_contact($contact_id,$id_merge_to_contact);
                    
                    if($flg_merge_Deals)
                    {
                        $flg_merge_Deals = $this->merge_Campaign_to_contact($contact_id,$id_merge_to_contact);
                        
                        if($flg_merge_Deals)
                        {
                            $flg_merge_contact_trans = $this->merge_contact_trans_to_contact($contact_id,$id_merge_to_contact);
                            
                            if($flg_merge_contact_trans)
                            {
                                $flg_merge_opp_req_trans = $this->merge_opportunity_req_to_contact($contact_id,$id_merge_to_contact);
                                
                                if($flg_merge_opp_req_trans)
                                {
                                    $flg_merge_attach_file = $this->merge_attach_file_contact($contact_id,$id_merge_to_contact);
                                    if($flg_merge_attach_file)
                                    {
                                        $flg_merge_cases = $this->merge_cases_to_contact($contact_id,$id_merge_to_contact);
                                        
                                        if($flg_merge_cases)
                                        {
                                            $flg_merge_events = $this->merge_event_to_contact($contact_id,$id_merge_to_contact);
                                            
                                            if($flg_merge_events)
                                            {
                                                $flg_merge_meeting = $this->merge_meeting_to_contact($contact_id,$id_merge_to_contact);
                                                
                                                if($flg_merge_meeting)
                                                {
                                                    $this->session->set_flashdata('message', lang('SUCCESS_MERGE_SUCCESS'));
                                                }else
                                                {
                                                    $this->session->set_flashdata('error',lang('FAIL_ATTACH_MEETING'));
                                                }
                                                
                                            }else
                                            {
                                                $this->session->set_flashdata('error',lang('FAIL_ATTACH_EVENTS'));
                                            }
                                        }else
                                        {
                                            $this->session->set_flashdata('error',lang('FAIL_ATTACH_CASES'));
                                        }
                                        
                                    }else
                                    {
                                        $this->session->set_flashdata('error',lang('FAIL_ATTACH_CONTACT_FILE'));
                                    }
                                    
                                }else
                                {
                                    $this->session->set_flashdata('error',lang('FAIL_CONTACT_MERGE_OPPORTUNITY_REQUIREMENT'));
                                }
                                
                            }else
                            {
                                $this->session->set_flashdata('error',lang('FAIL_CONTACT_MERGE_CONTACT_TRANS'));
                            }
                            
                        }else
                        {
                            $this->session->set_flashdata('error',lang('FAIL_CAMPAIGN_MERGE_CONTACT'));
                        }
                    }else
                    {
                        $this->session->set_flashdata('error', lang('FAIL_DEALS_MERGE_CONTACT'));
                    }
                }else 
                {
                    $this->session->set_flashdata('error', lang('FAIL_TASK_MERGE_CONTACT'));
                }
            }else
            {
                $this->session->set_flashdata('error', lang('FAIL_NOTES_MERGE_CONTACT'));
            }
        }else
        {
            $this->session->set_flashdata('error',lang('FAIL_CONTACT_MERGE_CONTACT'));
        }
       
        redirect($redirect_link);
    }
    function viewMergeOpportunity($prospect_id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data['prospect_id'] = $prospect_id;
            $data['modal_title'] = $this->lang->line('CONTACT_MERGE_DUPLICATE');
            $data['submit_button_title'] = $this->lang->line('CONTACT_MERGE_DUPLICATE');

            $table5 = PROSPECT_MASTER . ' as pm';
            //change by sanket on 22-03-2016 for every contact can be merge
            //$match5 = "cm.is_delete= 0 AND is_merge=0 AND cm.duplicate_contact_id = " . $contact_id;
            $order_by = 'prospect_name';
            $order = 'ASC';
            $match5 = "pm.is_delete= 0 AND pm.status=1 AND pm.is_merge=0 AND pm.status_type=1 ";
            $fields5 = array("pm.prospect_id,pm.prospect_name");
            $data['prospect_data'] = $this->common_model->get_records($table5, $fields5, '','', $match5,'','','',$order_by,$order);
            $data['main_content'] = '/MergeOpportunity';
            $this->load->view('/MergeOpportunity', $data);
        }
        
    }
    function mergeOpportunity()
    {
        $prospect_id = $this->input->post('prospect_id');
        $id_merge_to_contact = $this->input->post('id_merge_to_contact');
        $redirect_link = $this->input->post('redirect_link');
        
        if($prospect_id == $id_merge_to_contact)
        {
            $this->session->set_flashdata('error',lang('fail_merge_opportunity'));
            redirect($redirect_link);
        }
        $prospect_data['is_merge'] = 1;
        $prospect_data['is_delete'] = 1;
        $prospect_data['merge_with'] = $prospect_id;
        
        $where = array('prospect_id' => $id_merge_to_contact);
        $prospect_update['prospect_id']=$prospect_id;
        $primay_contact['primary_contact']=0;
        $this->common_model->update(OPPORTUNITY_REQUIREMENT_CONTACTS, $primay_contact, $where);
        $this->common_model->update(OPPORTUNITY_REQUIREMENT_CONTACTS, $prospect_update, $where);
        $this->common_model->update(PROSPECT_PRODUCTS_TRAN, $prospect_update, $where);
        $this->common_model->update(FILES_SALES_MASTER, $prospect_update, $where);
        
        $success_update = $this->common_model->update(PROSPECT_MASTER, $prospect_data, $where);
        
        if($success_update)
        {
            //merge all notes to contact
            $flg_merge_notes = $this->merge_notes_to_contact($prospect_id,$id_merge_to_contact);
            
            if($flg_merge_notes)
            {
                $flg_merge_task = $this->merge_task_to_contact($prospect_id,$id_merge_to_contact);
                
                if($flg_merge_task)
                {
                    $flg_merge_Deals = $this->merge_Deals_to_contact($prospect_id,$id_merge_to_contact);
                    
                    if($flg_merge_Deals)
                    {
                        $flg_camp_merge_Deals = $this->merge_Campaign_to_contact($prospect_id,$id_merge_to_contact);
                        
                                    if($flg_camp_merge_Deals)
                                    {
                                        $flg_merge_cases = $this->merge_cases_to_contact($prospect_id,$id_merge_to_contact);
                                        
                                        if($flg_merge_cases)
                                        {
                                            $flg_merge_events = $this->merge_event_to_contact($prospect_id,$id_merge_to_contact);
                                            
                                            if($flg_merge_events)
                                            {
                                                $flg_merge_meeting = $this->merge_meeting_to_contact($prospect_id,$id_merge_to_contact);
                                                
                                                if($flg_merge_meeting)
                                                {
                                                    $this->session->set_flashdata('message', lang('SUCCESS_MERGE_SUCCESS'));
                                                }else
                                                {
                                                    $this->session->set_flashdata('error',lang('FAIL_ATTACH_MEETING'));
                                                }
                                            }else
                                            {
                                                $this->session->set_flashdata('error',lang('FAIL_ATTACH_EVENTS'));
                                            }
                                        }else
                                        {
                                            $this->session->set_flashdata('error',lang('FAIL_ATTACH_CASES'));
                                        }
                                        
                                    }else
                                    {
                                        $this->session->set_flashdata('error',lang('FAIL_CAMPAIGN_MERGE_CONTACT'));
                                    }
                    }else
                    {
                        $this->session->set_flashdata('error', lang('FAIL_DEALS_MERGE_CONTACT'));
                    }
                }else 
                {
                    $this->session->set_flashdata('error', lang('FAIL_TASK_MERGE_CONTACT'));
                }
            }else
            {
                $this->session->set_flashdata('error', lang('FAIL_NOTES_MERGE_CONTACT'));
            }
        }else
        {
            $this->session->set_flashdata('error',lang('FAIL_CONTACT_MERGE_CONTACT'));
        }
       
        redirect($redirect_link);
    }
    
    function merge_notes_to_contact($contact_id,$id_merge_to_contact)
    {
        $note_data['notes_related_id'] = $contact_id;
        $redirect_link=$_SERVER['HTTP_REFERER'];
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $note_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $note_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $note_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $note_status = 1;
        }
        $where = array('notes_related_id' => $id_merge_to_contact,'note_status'=>$note_status);
        $success_delete = $this->common_model->update(TBL_NOTE, $note_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function merge_task_to_contact($contact_id,$id_merge_to_contact)
    {
        $task_data['task_related_id'] = $contact_id;
        $redirect_link=$_SERVER['HTTP_REFERER'];
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $task_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $task_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $task_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $task_status = 1;
        }
        $where = array('task_related_id' => $id_merge_to_contact,'task_status'=>1,'task_status'=>$task_status);
        $success_delete = $this->common_model->update(TASK_MASTER, $task_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function merge_Deals_to_contact($contact_id,$id_merge_to_contact)
    {
        $task_data['prospect_related_id'] = $contact_id;
        $redirect_link=$_SERVER['HTTP_REFERER'];
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $deal_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $deal_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $deal_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $deal_status = 1;
        }
        $where = array('prospect_related_id' => $id_merge_to_contact,'status_type'=> '1','deal_status'=>$deal_status);
        $success_delete = $this->common_model->update(PROSPECT_MASTER, $task_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
        
    }
    
    function merge_Campaign_to_contact($contact_id,$id_merge_to_contact)
    {
        $contact_data['campaign_related_id'] = $contact_id;
        $redirect_link=$_SERVER['HTTP_REFERER'];
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $campaign_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $campaign_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $campaign_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $campaign_status = 1;
        }
        $where = array('campaign_related_id' => $id_merge_to_contact,'campaign_status'=>$campaign_status);
        $success_delete = $this->common_model->update(TBL_CAMPAIGN_CONTACT, $contact_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function merge_contact_trans_to_contact($contact_id,$id_merge_to_contact)
    {
        $contact_data['contact_id'] = $contact_id;
        $where = array('contact_id' => $id_merge_to_contact);
        $success_delete = $this->common_model->update(LEAD_CONTACTS_TRAN, $contact_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function merge_opportunity_req_to_contact($contact_id,$id_merge_to_contact)
    {
        $contact_data['contact_id'] = $contact_id;
        $where = array('contact_id' => $id_merge_to_contact);
        $success_delete = $this->common_model->update(OPPORTUNITY_REQUIREMENT_CONTACTS, $contact_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function merge_attach_file_contact($contact_id,$id_merge_to_contact)
    {
        $contact_data['contact_id'] = $contact_id;
        $where = array('contact_id' => $id_merge_to_contact);
        $success_delete = $this->common_model->update(TBL_CONTACT_FILE_MASTER, $contact_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    
    function merge_cases_to_contact($contact_id,$id_merge_to_contact)
    {
        $contact_data['cases_related_id'] = $contact_id;
        $where = array('cases_related_id' => $id_merge_to_contact);
        $success_delete = $this->common_model->update(CRM_CASES, $contact_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function merge_event_to_contact($contact_id,$id_merge_to_contact)
    {
        $contact_data['event_related_id'] = $contact_id;
        $where = array('event_related_id' => $id_merge_to_contact);
        $success_delete = $this->common_model->update(TBL_EVENTS, $contact_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function merge_meeting_to_contact($contact_id,$id_merge_to_contact)
    {
        $contact_data['meet_related_id'] = $contact_id;
        $contact_data['meet_contact_id'] = $contact_id;
        $where = array('meet_related_id' => $id_merge_to_contact,'meet_contact_id'=>$id_merge_to_contact);
        $success_delete = $this->common_model->update(TBL_SCHDEULE_MEETING, $contact_data, $where);
        
        if($success_delete)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function view_add_file($id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['contact_id'] = $id;
            $data['modal_title'] = $this->lang->line('ADD_FILE_TO_CONTACT');
            $data['submit_button_title'] = $this->lang->line('ADD_FILE_TO_CONTACT');
            $data['sales_view'] = $this->viewname;
            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['drag']=true;
            $data['main_content'] = '/AddFile';
           // $data['js_content'] = '/loadJsFiles';
            $this->load->view('/AddFile', $data);
        }
        
    }
    function viewLeadFile($id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data['id'] = $id;
            $data['modal_title'] = $this->lang->line('ADD_FILE_TO_CONTACT');
            $data['submit_button_title'] = $this->lang->line('ADD_FILE_TO_CONTACT');
            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['sales_view'] = $this->viewname;
            $data['drag']=true;
            $data['main_content'] = '/AddLeadFile';
            $this->load->view('/AddLeadFile', $data);
        }
        
    }
    function addLeadFile()
    {
        $id = $this->input->post('id');
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (!validateFormSecret()) {
            
            redirect($redirect_link); //Redirect On Listing page
        }
        
        //upload file
        $file_name = array();
        $file_array1 = $this->input->post('file_data');

        $file_name = $_FILES['prospect_files']['name'];
        if (count($file_name) > 0 && count($file_array1) > 0) {
            $differentedImage = array_diff($file_name, $file_array1);
            foreach ($file_name as $file) {
                if (in_array($file, $differentedImage)) {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if (!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['prospect_files']['name'][$key]);
                    unset($_FILES['prospect_files']['type'][$key]);
                    unset($_FILES['prospect_files']['tmp_name'][$key]);
                    unset($_FILES['prospect_files']['error'][$key]);
                    unset($_FILES['prospect_files']['size'][$key]);
                }
            }
        }
        $_FILES['prospect_files'] = $arr = array_map('array_values', $_FILES['prospect_files']);

        $upload_data = uploadImage('prospect_files', prospect_upload_path, 'Lead');
        $Marketingfiles = array();  
        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $file_data = implode(",", $file2);
        } else {
            $file_data = '';
        }
        
        $compaigndata['file_name'] = $file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($upload_data, array('file_name' => $img));
            }
        }

        $prospect_files = array();

        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
            if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $gallery_files_data = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                        $prospect_files[] = ['file_name' => $gallery_files_data[$i], 'file_path' => $gallery_path[$i], 'lead_id' => $id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
            if (count($upload_data) > 0) {
            foreach ($upload_data as $files) {
                
                    $prospect_files[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'lead_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                
            }
        }
        if (count($prospect_files) > 0) {
            if (!$this->common_model->insert_batch(FILES_LEAD_MASTER, $prospect_files)) {
                
            }
        }
                $msg = $this->lang->line('file_add_message');
                $this->session->set_flashdata('message', $msg);
        }
        if (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $gallery_files_data = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {  
                        $prospect_files[] = ['file_name' => $gallery_files_data[$i], 'file_path' => $gallery_path[$i], 'prospect_id' => $id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
            if (count($upload_data) > 0) {
            foreach ($upload_data as $files) {
                
                    $prospect_files[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'prospect_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                
            }
        }
        if (count($prospect_files) > 0) {
            if (!$this->common_model->insert_batch(FILES_SALES_MASTER, $prospect_files)) {
            }
        }
                $msg = $this->lang->line('file_add_message');
                $this->session->set_flashdata('message', $msg);
        }
        if (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
            if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $gallery_files_data = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {     
                        $prospect_files[] = ['file_name' => $gallery_files_data[$i], 'file_path' => $gallery_path[$i], 'prospect_id' => $id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
            if (count($upload_data) > 0) {
            foreach ($upload_data as $files) {
                
                    $prospect_files[] = ['file_name' => $files['file_name'], 'file_path' => prospect_upload_path, 'prospect_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                
            }
        }
        if (count($prospect_files) > 0) {
            if (!$this->common_model->insert_batch(FILES_SALES_MASTER, $prospect_files)) {
               
            }
        }
                $msg = $this->lang->line('file_add_message');
                $this->session->set_flashdata('message', $msg);
        }
        $sess_array = array('setting_current_tab' => 'Documents');
	$this->session->set_userdata($sess_array);
        redirect($redirect_link);
    }
          
    function add_contact_file()
    {
        $contact_id = $this->input->post('hdn_contact_id');
        $redirect_link = $this->input->post('redirect_link');
        
        if (!validateFormSecret()) {
           
            redirect($redirect_link); //Redirect On Listing page
        }
        //$contact_file_uplload_path = CONTACT_FILE_ATTACH_PATH.$contact_id;
        $contact_file_uplload_path = CONTACT_FILE_ATTACH_PATH;
        
        /* change by sanket on 22-03-2016
        if(!is_dir($contact_file_uplload_path))
        {
            mkdir($contact_file_uplload_path, 0777, true);
            chmod($contact_file_uplload_path, 0777);
        }
        */
        $file_name = array();
        $file_array1 = $this->input->post('file_data');

        $file_name = $_FILES['cost_files']['name'];
        if (count($file_name) > 0 && count($file_array1) > 0) {
            $differentedImage = array_diff($file_name, $file_array1);
            foreach ($file_name as $file) {
                if (in_array($file, $differentedImage)) {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if (!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['cost_files']['name'][$key]);
                    unset($_FILES['cost_files']['type'][$key]);
                    unset($_FILES['cost_files']['tmp_name'][$key]);
                    unset($_FILES['cost_files']['error'][$key]);
                    unset($_FILES['cost_files']['size'][$key]);
                }
            }
        }
        $_FILES['cost_files'] = $arr = array_map('array_values', $_FILES['cost_files']);
        /* ends
         *
         */
       
        $tmp_url = base_url()."Contact";
        $uploadData = uploadImage('cost_files',$contact_file_uplload_path,$tmp_url);

        /* ritesh code */
//
        $Marketingfiles = array();

        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $file_data = implode(",", $file2);
        } else {
            $file_data = '';
        }
        $compaigndata['file_name'] = $file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }

        /* end
         * 
         */
        $costFiles = array();
        $attach_arr_gallary = array();
        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $cost_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $attach_arr_gallary[] = FCPATH.$gallery_path[$i].$cost_files[$i];
                    $costFiles[] = ['file_name' => $cost_files[$i], 'file_path' => $gallery_path[$i], 'contact_id' => $contact_id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
        $attach_arr_browse = array();
        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $attach_arr_browse[] = FCPATH."uploads/contact_attach/".$files['file_name'];
                $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => $contact_file_uplload_path."/", 'contact_id' => $contact_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }
        
        $email_atached_arr = array_merge($attach_arr_gallary,$attach_arr_browse);
        
        if (count($costFiles) > 0) {
            $where = array('contact_id' => $contact_id);
            //  $this->common_model->delete(COST_FILES, $where);
           
            if (!$this->common_model->insert_batch(TBL_CONTACT_FILE_MASTER, $costFiles)) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($this->module . '/Contact/'); //Redirect On Listing page
            }
        }
        
        $this->session->set_flashdata('message', lang('SUCCESS_FILE_ADDED_MSG'));
        
        $sess_array = array('setting_current_tab' => 'Document');
	$this->session->set_userdata($sess_array);
        redirect($redirect_link);
    }
    
    function getContactFile()
    {
        $contact_id = $_SESSION['current_related_id'];
        
        $data['contact_id'] = $contact_id;
        
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
                $searchtext = '';
                $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {

           $where_search = '(ct.file_name LIKE "%' . $searchtext . '%" )';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
                $searchtext1 = $this->session->userdata('searchtext');
                $where_search = '(ct.file_name LIKE "%' . $searchtext1 . '%" )';
        }else
        {
                $this->session->set_userdata('searchtext','');
        }
        
        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        $data['drag']=true;
        $config['base_url'] = site_url($data['project_view'] . '/getContactFile');
        $data['tasksortField'] = 'file_id';
        $data['tasksortOrder'] = 'desc';
        
        $table = TBL_CONTACT_FILE_MASTER.' as ct';
        $where = array("ct.is_delete" => "0","ct.contact_id"=>$contact_id);
        $fields = array("ct.*");
        
        $config['total_rows']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where,'','','1','','',$where_search);
        $config['per_page'] =20;
        $choice = $config["total_rows"] / $config["per_page"];
        
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }
       
        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['file_data'] = $this->common_model->get_records($table,$fields,'','','','',$config['per_page'],$data['page'],$data['tasksortField'],$data['tasksortOrder'],'',$where,'','','','','',$where_search);
       
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        $data['main_content'] = '/AjaxContactFile';
        $this->load->view('/AjaxContactFile', $data);
    }
     function getLeadFile()
    {
        $redirect_link=$_SERVER['HTTP_REFERER'];
        $file_related_id=$this->input->post('file_related_id');
        if($file_related_id!="")
        {
            $contact_id = $file_related_id;
        }
        else{
            $contact_id = $_SESSION['current_related_id'];
        }
        
        $data['contact_id'] = $contact_id;
        
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
                $searchtext = '';
                $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {

           $where_search = '(flm.file_name LIKE "%' . $searchtext . '%" )';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
                $searchtext1 = $this->session->userdata('searchtext');
                $where_search = '(flm.file_name LIKE "%' . $searchtext1 . '%" )';
        }else
        {
                $this->session->set_userdata('searchtext','');
        }
        
        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        
        $config['base_url'] = site_url($data['project_view'] . '/getLeadFile');
        $data['tasksortField'] = 'file_id';
        $data['tasksortOrder'] = 'desc';
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
        $table = FILES_LEAD_MASTER.' as flm';
        $where = array("flm.lead_id"=>$contact_id);
        $fields = array("flm.lead_id,flm.file_id,flm.file_name,flm.file_path,flm.type");
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
        $table = FILES_SALES_MASTER.' as flm';
        $where = array("flm.prospect_id"=>$contact_id);
        $fields = array("flm.prospect_id,flm.file_id,flm.file_name,flm.file_path,flm.type");
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false)  {
        $table = FILES_SALES_MASTER.' as flm';
        $where = array("flm.prospect_id"=>$contact_id);
        $fields = array("flm.prospect_id,flm.file_id,flm.file_name,flm.file_path,flm.type");
        }
        $config['total_rows']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where,'','','1','','',$where_search);
        $config['per_page'] =20;
        $choice = $config["total_rows"] / $config["per_page"];
        
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }
       
        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['file_data'] = $this->common_model->get_records($table,$fields,'','','','',$config['per_page'],$data['page'],$data['tasksortField'],$data['tasksortOrder'],'',$where,'','','','','',$where_search);
       
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        $data['main_content'] = '/AjaxContactFile';
        $this->load->view('/AjaxContactFile', $data);
    }
    public function delete_contact_attach() {
        
        $id = $this->input->get('file_id');
        $redirect_link = $this->input->get('redirect_link');
        if ($id) 
        {
            $url= $this->input->get('file_path');
            $where = array('file_id' => $id);
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
            $delete_suceess =$this->common_model->delete(FILES_LEAD_MASTER, $where);
            unlink(BASEPATH . '../' . $url);
            if($delete_suceess)
            {
                return true;
                
            }else
            {
               return false;
            }
        }
        if (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $delete_suceess =$this->common_model->delete(FILES_SALES_MASTER, $where);
            unlink(BASEPATH . '../' . $url);
            if($delete_suceess)
            {
                return true;
                
            }else
            {
               return false;
            }
        }
        if (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $delete_suceess =$this->common_model->delete(FILES_SALES_MASTER,$where );
           unlink(BASEPATH . '../' . $url);
           if($delete_suceess)
            {
                return true;
                
            }else
            {
               return false;
            }
        }
        if(strpos($redirect_link, 'Contact') !== false) {
            $file_data['is_delete']=1;
            $delete_suceess = $this->common_model->update(TBL_CONTACT_FILE_MASTER, $file_data, $where);
            
            if($delete_suceess)
            {
                return true;
                
            }else
            {
               return false;
            }
        }
            
        }
        
    }
    
    public function getContactCases()
    {
        $redirect_link=$_SERVER['HTTP_REFERER'];
        $cases_related_id=$this->input->post('cases_related_id');
        if($cases_related_id!="")
        {
            $contact_id = $cases_related_id;
        }
        else{
            $contact_id = $_SESSION['current_related_id'];
        }
        
        $data['contact_id'] = $contact_id;
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $cases_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $cases_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $cases_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $cases_status = 1;
        }
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
                $searchtext = '';
                $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {

           $where_search = '(cc.title LIKE "%' . $searchtext . '%" )';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
                $searchtext1 = $this->session->userdata('searchtext');
                $where_search = '(cc.title LIKE "%' . $searchtext1 . '%" )';
        }else
        {
                $this->session->set_userdata('searchtext','');
        }

        $this->load->library('pagination');
        $data['contact_view'] = $this->viewname;
        $config['base_url'] = site_url($data['contact_view'] . '/getContactCases');
        //$user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $group_by='cases_id';
        $data['tasksortField'] = 'cases_id';
        $data['tasksortOrder'] = 'desc';
        
        
        $table                = CRM_CASES . ' as cc';
        $fields               = array('cc.*,cf.file_id,cf.file_name,cf.file_path,CONCAT(l.firstname,"   ",l.lastname) as responsible_name,ct.cases_type_name as incident_type_name');
        $join_table           = array(CRM_CASES_FILES_MASTER . ' as cf' => 'cf.cases_related_id=cc.cases_related_id and cf.cases_status=cc.cases_status',CONTACT_MASTER . ' as cm' => 'cc.responsible=cm.contact_id',LOGIN . ' as l' => 'l.login_id=cc.responsible',TBL_CRM_CASES_TYPE . ' as ct' => 'ct.cases_type_id = cc.cases_type_id');
        $where                = array('cc.is_delete' => 0,'cc.status' => 1,'cc.cases_related_id'=>$contact_id,'cc.cases_status'=>$cases_status);
        $config['total_rows'] = $this->common_model->get_records ($table, $fields, $join_table, 'left', $where, '', '', '', $data['tasksortField'], $data['tasksortOrder'], $group_by, $where_search, '', '', '1');
       
        $config['per_page'] =5;
        $choice = $config["total_rows"] / $config["per_page"];
        
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }
       
        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['cases_data'] = $this->common_model->get_records ($table, $fields, $join_table, 'left', $where, '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], $group_by, $where_search);
        
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $data['main_content'] = '/AjaxCases';
        $this->load->view('/AjaxCases', $data);
    }
    
    function exportContact()
    {
        $this->Contact_model->exportContact();
        redirect('Contact');
    }
    
    function view_event($id = NULL)
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
             $data['event_related_id'] = $id;
            $data['modal_title'] = $this->lang->line('ADD_EVENTS');
            $data['submit_button_title'] = $this->lang->line('ADD_EVENTS');
            $data['sales_view'] = $this->viewname;

            $data['main_content'] = '/AddEditEvent';
            $this->load->view('/AddEditEvent', $data);
        }
       
        
    }
    
    function update_event($event_id)
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {

            $data['modal_title'] = $this->lang->line('UPDATE_EVENTS');
            $data['submit_button_title'] = $this->lang->line('UPDATE_EVENTS');

            $previewProducts['fields'] = ['*'];
            $previewProducts['table'] = TBL_EVENTS . ' as CN';
            $previewProducts['match_and'] = 'CN.event_id=' . $event_id;
            $data['editRecord'] = $this->common_model->get_records_array($previewProducts);


            $data['event_id'] = $data['editRecord'][0]['event_id'];
            $data['main_content'] = '/AddEditEvent';
            $this->load->view('/AddEditEvent', $data);
        }
        
        
    }
    
    function insertEvent()
    {
       
        $redirect_link = $this->input->post('redirect_link');
        if (!validateFormSecret()) {
          //  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect($redirect_link); //Redirect On Listing page
        }
        $new_name = '';
        if($_FILES["import_file"]['name']  != '')
        {
            $tmp_name_arr = explode('.', $_FILES['import_file']['name']);
            $tmp_file_name = $tmp_file_name.$tmp_name_arr[1];
            
            $tmp_new_name = time()."_event_logo.".end($tmp_name_arr);
            $config['upload_path'] = FCPATH.'uploads/events';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG';
            $config['max_size'] = 40480;
            
            $config['file_name'] = $tmp_new_name;
            $this->load->library('upload', $config);
            $this->upload->initialize($config); 
            if ( !$this->upload->do_upload('import_file'))
            {
                $msg = $this->upload->display_errors();
                $this->session->set_flashdata('error', $msg);
            }else
            {
                 $new_name = $tmp_new_name;
            }
        }
        
       
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $event_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $event_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $event_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $event_status = 1;
        }
        
        $event_reminder = '0';
        if ($this->input->post('event_reminder') == 'on') {
            $event_reminder = '1';
        }
        
        
        $data['event_related_id'] = $this->input->post('event_related_id');
        $data['event_title'] = $this->input->post('event_title');
        $data['event_date'] = date('Y-m-d',  strtotime($this->input->post('event_date')));
        $data['event_time'] = $this->input->post('event_time');
        $data['event_remember'] = $event_reminder;
        
        if($event_reminder == '1')
        {
            $data['reminder_date'] = date('Y-m-d',  strtotime($this->input->post('reminder_date')));
            $data['reminder_time'] = $this->input->post('reminder_time');
        }
//        //start for event reminder
//        $data['before_status'] = $this->input->post ('before_after');
//        $data['repeat'] = $this->input->post ('repeat');
//        $data['remind_time'] = $this->input->post ('remind_time');
//        $data['remind_before_min'] = $this->input->post ('remind_day');
//        //End for event reminder
        
        $data['event_note'] = $this->input->post('note_description');
        $data['event_place'] = $this->input->post('event_place');
        $data['event_image'] = $new_name;
        $data['event_status'] = $event_status;
        $data['is_delete'] = 0;
        $data['created_date'] = datetimeformat();
       
        
        $flg_record  = $this->common_model->insert(TBL_EVENTS, $data);
                
        if($flg_record)
        {
            $msg = lang('ADD_EVENTS_SUCCESS_MSG');
            $this->session->set_flashdata('message', $msg);
        }else
        {
            $msg = lang('error_msg');
            $this->session->set_flashdata('error', $msg);
        }
        
        $sess_array = array('setting_current_tab' => 'Events');
	$this->session->set_userdata($sess_array);
        redirect($redirect_link); 
        
    }
    
    public function updateEventRecord()
    {
        
        if($_FILES["import_file"]['name']  != '')
        {
            
            $tmp_name_arr = explode('.', $_FILES['import_file']['name']);
            $tmp_file_name = $tmp_file_name.$tmp_name_arr[1];
            
            $tmp_new_name = time()."_event_logo.".end($tmp_name_arr);
            $config['upload_path'] = FCPATH.'uploads/events';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG';
            $config['max_size'] = 40480;
            
            $config['file_name'] = $tmp_new_name;
            $this->load->library('upload', $config);
            $this->upload->initialize($config); 
            if ( !$this->upload->do_upload('import_file'))
            {
                $msg = $this->upload->display_errors();
                $this->session->set_flashdata('error', $msg);
            }else
            {
                $data['event_image'] = $tmp_new_name;
            }
        }
        
        $redirect_link = $this->input->post('redirect_link');
        
        $event_reminder = '0';
        if ($this->input->post('event_reminder') == 'on') {
            $event_reminder = '1';
        }
        $event_id = $this->input->post('event_id');
        
        $data['event_title'] = $this->input->post('event_title');
        $data['event_date'] = date('Y-m-d',  strtotime($this->input->post('event_date')));
        $data['event_time'] = $this->input->post('event_time');
        $data['event_remember'] = $event_reminder;
        
        if($event_reminder == '1')
        {
            $data['reminder_date'] = date('Y-m-d',  strtotime($this->input->post('reminder_date')));
            $data['reminder_time'] = $this->input->post('reminder_time');
        }

        
        $data['event_note'] = $this->input->post('note_description');
        $data['event_place'] = $this->input->post('event_place');
        $data['is_delete'] = 0;
        
        $where = array('event_id' => $event_id);
        
        $flg_record  = $this->common_model->update(TBL_EVENTS, $data,$where);
                
        if($flg_record)
        {
            $msg = lang('UPDATE_EVENTS_SUCCESS_MSG');
            $this->session->set_flashdata('message', $msg);
        }else
        {
            $msg = lang('error_msg');
            $this->session->set_flashdata('error', $msg);
        }
        
        $sess_array = array('setting_current_tab' => 'Events');
	$this->session->set_userdata($sess_array);
        redirect($redirect_link); 
        
    }
    public function getContactEvents()
    {
      
        //$contact_id = $_SESSION['current_related_id'];
        $event_related_id=$this->input->post('event_related_id');
        if($event_related_id!="")
        {
            $contact_id = $event_related_id;
        }
        else{
            $contact_id = $_SESSION['current_related_id'];
        }
        $data['contact_id'] = $contact_id;
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $event_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $event_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $event_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $event_status = 1;
        }
        
        //commented by sanket on 30-03-2016
        //$note_status=  $this->input->post('note_status');
        
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {
            
           $where_search = '(td.event_title LIKE "%' . $searchtext . '%" )';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(td.event_title LIKE "%' . $searchtext1 . '%" )';
        }else
        {
            $this->session->set_userdata('searchtext','');
        }
     
        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        
        //config variable for the pagination
        $config['base_url'] = site_url($data['project_view'] . '/getContactEvents');
        
        $data['tasksortField'] = 'event_date';
        $data['tasksortOrder'] = 'ASC';
        $fields = array("td.*");
        $where = array("td.is_delete" => "0","td.event_related_id"=>$contact_id,"td.event_status"=>$event_status);
        $match  = " td.event_date >='".date('Y-m-d')."'";
        $config['total_rows'] = count($this->common_model->get_records(TBL_EVENTS . ' as td', $fields, '', '',$match, '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search));
        $config['per_page'] =5;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['event_data'] = $this->common_model->get_records(TBL_EVENTS . ' as td', $fields, '', '', $match, '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search);
        
        //echo $this->db->last_query();
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        //$this->pagination->initialize($config);
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        
        $data['main_content'] = '/AjaxEvents';
        $this->load->view('/AjaxEvents', $data);
    }
    
    function viewEvents($id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['event_related_id'] = $id;
            $data['modal_title'] = $this->lang->line('VIEW_EVENTS');

            $previewEvents['fields'] = ['CN.*'];
            $previewEvents['table'] = TBL_EVENTS . ' as CN';
            $previewEvents['match_and'] = 'CN.event_id=' . $id;


            $data['event_data'] = $this->common_model->get_records_array($previewEvents);
            $data['main_content'] = '/ViewEvents';
            $this->load->view('/ViewEvents', $data);

        }
        
    }
    
    function delete_event()
    {
        $event_id = $this->input->get('event_id');
        $redirect_url = $this->input->get('link');
        if (!empty($event_id)) 
        {
            $data['is_delete'] = '1';
           
            $where = array('event_id' => $event_id);
            $success_delete = $this->common_model->update(TBL_EVENTS,$data,$where);
           
            if($success_delete)
            {
                $msg = lang('SUCCESS_EVENT_DELETED');
                $this->session->set_flashdata('message', $msg);
            }else
            {
                $msg = lang('error_msg');
                $this->session->set_flashdata('error', $msg);
            }
        
        }
        $sess_array = array('setting_current_tab' => 'Events');
	$this->session->set_userdata($sess_array);
        redirect($redirect_url);
    }
    
    function event_send_email($id = NULL)
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $table = CONTACT_MASTER . ' as cm';
            $match = "cm.is_delete = 0";
            $fields = array("cm.contact_id,cm.contact_name");
            $data['contact_record'] = $this->common_model->get_records($table, $fields, '', '', $match);

            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");

            $data['email_template_data'] = $this->common_model->getEmailTemplateData();
            $data['contact_id'] = $id;

            $data['modal_title'] = lang('SEND_EMAIL_EVENT');
            $data['main_content'] = '/EventSendEmail';
            $this->load->view('/EventSendEmail', $data);

        }
        
    }
    
    function sendEventEmail()
    {
        $hdn_contact_id = $this->input->post('hdn_contact_id');
        $redirect_link = $this->input->post('redirect_link');
        $from_email =  $_SESSION['LOGGED_IN']['EMAIL'];
        $from_name =  $_SESSION['LOGGED_IN']['FIRSTNAME']. " ". $_SESSION['LOGGED_IN']['LASTNAME'];
        
        if (!validateFormSecret()) {
           
            redirect($redirect_link); //Redirect On Listing page
        }
        
        $arr_receipent_email = $this->input->post('receipent_email');
       
        $contact_receipent_email = $this->Contact_model->getContactEmailbyId($arr_receipent_email);
        $email_subject = $this->input->post('email_subject');
        $email_contect = $this->input->post('email_content');
        
        $hdn_mark_as_important = $this->input->post('hdn_mark_as_important');
        
        
        //capture data in email communication
        $email_communication['comm_date']  = date('Y-m-d');
        $email_communication['comm_sender']  = $_SESSION['LOGGED_IN']['ID'];
        $email_communication['comm_receiver']  =implode(',',$arr_receipent_email); 
        $email_communication['comm_subject']  =$email_subject; 
        $email_communication['comm_content']  =$email_contect; 
        $email_communication['comm_type']  =1; 
        $email_communication['is_delete']  =0; 
        $email_communication['comm_related_id']  =$hdn_contact_id;
        $email_communication['created_date']  =  datetimeformat(); 
        $id = $this->common_model->insert(TBL_EMAIL_COMMUNICATION, $email_communication);
            
        $file_name = array();
        $file_array1 = $this->input->post('file_data');

        $file_name = $_FILES['cost_files']['name'];
        if (count($file_name) > 0 && count($file_array1) > 0) {
            $differentedImage = array_diff($file_name, $file_array1);
            foreach ($file_name as $file) {
                if (in_array($file, $differentedImage)) {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if (!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['cost_files']['name'][$key]);
                    unset($_FILES['cost_files']['type'][$key]);
                    unset($_FILES['cost_files']['tmp_name'][$key]);
                    unset($_FILES['cost_files']['error'][$key]);
                    unset($_FILES['cost_files']['size'][$key]);
                }
            }
        }
        $_FILES['cost_files'] = $arr = array_map('array_values', $_FILES['cost_files']);
        /* ends
         *
         */
       
        $tmp_url = base_url()."Contact";
        $uploadData = uploadImage('cost_files',EMAIL_EVENT_ATTACH_PATH,$tmp_url);

        /* ritesh code */
//
        $Marketingfiles = array();

        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $file_data = implode(",", $file2);
        } else {
            $file_data = '';
        }
        $compaigndata['file_name'] = $file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }

        /* end
         * 
         */
        $costFiles = array();
        $attach_arr_gallary = array();
        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $cost_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $attach_arr_gallary[] = FCPATH.$gallery_path[$i].$cost_files[$i];
                    $costFiles[] = ['file_name' => $cost_files[$i], 'file_path' => $gallery_path[$i], 'email_communication_id' => $id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
        $attach_arr_browse = array();
        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $attach_arr_browse[] = FCPATH."uploads/event_email_attach/".$files['file_name'];
                $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => EMAIL_PROSPECT_ATTACH_PATH, 'email_communication_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }
        
        $email_atached_arr = array_merge($attach_arr_gallary,$attach_arr_browse);
        
        if (count($costFiles) > 0) {
            $where = array('email_prospect_id' => $id);
            
           
            if (!$this->common_model->insert_batch(TBL_EMAIL_COMMUNICATION_FILE_MASTER, $costFiles)) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($this->module . '/Contact/'); //Redirect On Listing page
            }
        }
        
        if($id)
        {
            $CI = & get_instance();
            $configs = getMailConfig();

            $config['protocol'] = $configs['email_protocol'];
            $config['smtp_host'] = $configs['smtp_host']; //change this
            $config['smtp_port'] = $configs['smtp_port'];
            $config['smtp_user'] = $configs['smtp_user']; //change this
            $config['smtp_pass'] = $configs['smtp_pass']; //change this

            if($hdn_mark_as_important == "1")
            {
                $config['priority'] = 1;
            }

            $config['mailtype'] = 'html';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n"; 

            $CI->load->library('email', $config); 
          
            $CI->email->set_header('MIME-Version', '1.0\r\n');
            $CI->email->set_header('Disposition-Notification-To', $from_email);
          


            $CI->email->from($from_email, $from_name);
            $CI->email->to($contact_receipent_email);
           
            if(count($email_atached_arr) > 0)
            {
                foreach ($email_atached_arr as $attach)
                {
                    $this->email->attach($attach);
                }
            }


            $CI->email->subject($email_subject);
            $CI->email->message($email_contect);

            if($CI->email->send())
            {
                $msg = $this->lang->line('EVENT_MAIL_SUUCESSFULLY_SENT');
                $this->session->set_flashdata('message', $msg);
                $this->session->set_flashdata('msg', $msg);


            }else
            {
                $msg = $this->lang->line('FAIL_WITH_SENDING_EMAIL');
                $this->session->set_flashdata('error', $msg);
            }
            
        }else
        {
            $msg = $this->lang->line('error');
            $this->session->set_flashdata('error', $msg);
        }
        
        $sess_array = array('setting_current_tab' => 'Events');
        $this->session->set_userdata($sess_array);
        unset($email_atached_arr);
        redirect($redirect_link);
        
    }
    public function AddCases($id){
        
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['cases_related_id'] = $id;
            $data['modal_title'] = $this->lang->line('create_new_cases');
            $data['submit_button_title'] = $this->lang->line('create_cases');

            $match5='';
            $fields5 = array("ct.*");
            $data['cases_type_data'] = $this->common_model->get_records('blzdsk_crm_cases_type as ct', $fields5, '','', $match5);

            $prospectOwner[]=array('responsible' =>$this->session->userdata('LOGGED_IN')['ID']);
            $data['edit_record']=$prospectOwner;

            $data['contact_data']  = $this->common_model->getSystemUserData();
            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['contact_view'] = $this->viewname;
            $data['main_content'] = '/AddCases';

            $this->load->view('/AddCases', $data);

        }
        
    }
    public function delete_incident_file($id) {
        if (!empty($id)) {
            $match = "incident_file_id = " . $id;
            $res   = $this->common_model->get_records (CRM_CASES_FILES_MASTER, array('file_id','file_name','file_path','upload_status'), '', '', $match);
            
           $upload_dir = $this->config->item('prospect_upload_path').'Project0'.$this->project_id.'/'.$this->config->item('project_incidents_folder').'/';
            if (empty($res[0]['upload_status']) && !empty($res[0]['file_name']) && !empty($res[0]['file_path'])) {
                if (file_exists($upload_dir. $res[0]['file_name'])) {
                    unlink($res[0]['file_path'] . '/' . $res[0]['file_name']);
                }
            }
            $where = array('file_id' => $id);
            if ($this->common_model->delete (CRM_CASES_FILES_MASTER, $where)) {
                echo json_encode (array('status' => 1,
                                        'error'  => ''));
                die;
            } else {
                echo json_encode (array('status' => 0,
                                        'error'  => 'Someting went wrong!'));
                die;
            }
            unset($id);
        }
    }
     function download_incident_file($id) {
         $redirect_link=$_SERVER['HTTP_REFERER'];
        if ($id > 0) {
            $params['fields']    = ['*'];
            $params['table']     = CRM_CASES_FILES_MASTER . ' as cf';
            $params['match_and'] = 'cf.file_id=' . $id . '';
            $task_files          = $this->common_model->get_records_array ($params);
            if (count ($task_files) > 0) {
                $pth = file_get_contents (base_url ($task_files[0]['file_path'] . '/' . $task_files[0]['file_name']));
                $this->load->helper ('download');
                force_download ($task_files[0]['file_name'], $pth);
            }
            redirect ($redirect_link);
        }
    }
    public function insertCases() {
       
        if ($this->input->post ('cases_id')) {
            $id = $this->input->post ('cases_id');
        }
        //$display = $this->input->post ('display');
        
        $redirect_link = $this->input->post ('redirect_link');
        
        if (!validateFormSecret()) {
            redirect($redirect_link); //Redirect On Listing page
        }
        $insert_data['title']       = ucfirst ($this->input->post ('title'));
        $insert_data['cases_type_id']     = $this->input->post ('type_id');
        $insert_data['business_cases']     = $this->input->post ('business_cases');
        $insert_data['business_subject']     = $this->input->post ('business_subject');
        $insert_data['responsible']     = $this->input->post ('responsible');
        $insert_data['deadline']     = date_format(date_create($this->input->post('deadline')), 'Y-m-d');
        $insert_data['incident_status']     = $this->input->post('incident_status');
        $insert_data['cases_related_id']=$this->input->post('cases_related_id');
        $insert_data['description'] = $this->input->post ('description', FALSE);
        $insert_data['status']      = 1;
         if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $cases_status=$insert_data['cases_status']  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $cases_status=$insert_data['cases_status']  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $cases_status=$insert_data['cases_status'] = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $cases_status=$insert_data['cases_status']= 1;
        }
        
        //Insert Record in Database
        if (!empty($id)) //update
        {
            $insert_data['modified_by']   = !empty( $this->session->userdata('LOGGED_IN')['ID']) ?  $this->session->userdata('LOGGED_IN')['ID'] : '';
            $insert_data['modified_date'] = datetimeformat ();
            $where                        = array('cases_id' => $id);
            $success_update               = $this->common_model->update (CRM_CASES, $insert_data, $where);
            $msg                          = $this->lang->line ('crm_cases_update_msg'); 
                $sess_array = array('setting_current_tab' => 'Cases');
                $this->session->set_userdata($sess_array);
                $this->session->set_flashdata('message', $msg);
            
            
        } else //insert
        {
            $insert_data['created_by']   = !empty( $this->session->userdata('LOGGED_IN')['ID']) ?  $this->session->userdata('LOGGED_IN')['ID'] : '';
            $insert_data['created_date'] = datetimeformat ();
            $returnId                          = $this->common_model->insert (CRM_CASES, $insert_data);
            $msg                         = $this->lang->line ('crm_cases_add_msg');
            
            
                $sess_array = array('setting_current_tab' => 'Cases');
                $this->session->set_userdata($sess_array);
                $this->session->set_flashdata('message', $msg);
            
        }
        //upload file
        $file_name = array();
        $file_array1 = $this->input->post('file_data');

        $file_name = $_FILES['prospect_files']['name'];
        if (count($file_name) > 0 && count($file_array1) > 0) {
            $differentedImage = array_diff($file_name, $file_array1);
            foreach ($file_name as $file) {
                if (in_array($file, $differentedImage)) {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if (!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['prospect_files']['name'][$key]);
                    unset($_FILES['prospect_files']['type'][$key]);
                    unset($_FILES['prospect_files']['tmp_name'][$key]);
                    unset($_FILES['prospect_files']['error'][$key]);
                    unset($_FILES['prospect_files']['size'][$key]);
                }
            }
        }
        $_FILES['prospect_files'] = $arr = array_map('array_values', $_FILES['prospect_files']);

        $upload_data = uploadImage('prospect_files', cases_upload_path, $data['cases_view']);
        $Marketingfiles = array();  
        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $file_data = implode(",", $file2);
        } else {
            $file_data = '';
        }
        
        $compaigndata['file_name'] = $file_data;
        if ($compaigndata['file_name'] != '') {
            $explodedData = explode(',', $compaigndata['file_name']);

            foreach ($explodedData as $img) {
                array_push($upload_data, array('file_name' => $img));
            }
        }

        $prospect_files = array();

        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $gallery_files_data = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    if ($id) {
                        $prospect_files[] = ['file_name' => $gallery_files_data[$i], 'file_path' => $gallery_path[$i], 'cases_related_id' => $id,'cases_status' => $cases_status, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    } else {
                        $prospect_files[] = ['file_name' => $gallery_files_data[$i], 'file_path' => $gallery_path[$i], 'cases_related_id' => $returnId,'cases_status' => $cases_status, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    }
                }
            }
        }
        
        if (count($upload_data) > 0) {
            foreach ($upload_data as $files) {
                if ($id) {
                    $prospect_files[] = ['file_name' => $files['file_name'], 'file_path' => cases_upload_path, 'cases_related_id' => $id, 'cases_status' => $cases_status, 'upload_status' => 0, 'created_date' => datetimeformat()];
                } else {
                    $prospect_files[] = ['file_name' => $files['file_name'], 'file_path' => cases_upload_path, 'cases_related_id' => $returnId, 'cases_status' => $cases_status, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }

        if (count($prospect_files) > 0) {
            if ($id) {
                $where = array('cases_related_id' => $id);
            } else {
                $where = array('cases_related_id' => $returnId);
            }

            if (!$this->common_model->insert_batch(CRM_CASES_FILES_MASTER, $prospect_files)) {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
        }
        /**
         * SOFT DELETION CODE STARTS FOR IMAGE DELETE
         */
        $softDeleteImagesArr = $this->input->post('softDeletedImages');
        $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
        if (count($softDeleteImagesUrlsArr) > 0) {
            foreach ($softDeleteImagesUrlsArr as $urls) {
                unlink(BASEPATH . '../' . $urls);
            }
        }

        if (count($softDeleteImagesUrlsArr) > 0) {
            $dlStr = implode(',', $softDeleteImagesArr);
            $this->common_model->delete(CRM_CASES_FILES_MASTER, 'file_id IN(' . $dlStr . ')');
        }
        //end upload
        
        //redirect ($this->module . '/ProjectIncidents');
        
        //updated by sanket on 23-03-2016
        redirect ($redirect_link);
    }
    public function viewCasesRecord($id='') {
        
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
             //Get Records From PROSPECT_MASTER Table
            if (!empty($id)) {
                $match = "cases_id = " . $id;
                $table       = CRM_CASES . ' as cc';
                $fields      = array('cc.*,CONCAT(l.firstname,"   ",l.lastname) as responsible_name,er.cases_type_name as incident_type_name');
                $join_table  = array( 'blzdsk_crm_cases_type as ct' => 'cc.cases_type_id=ct.cases_type_id',CONTACT_MASTER . ' as cm' => 'cc.responsible=cm.contact_id',LOGIN . ' as l' => 'l.login_id=cc.responsible', TBL_CRM_CASES_TYPE . ' as er' => 'er.cases_type_id=cc.cases_type_id');
                $edit_record = $this->common_model->get_records ($table, $fields, $join_table, 'left', $match);
                //Get project incidet file
                $match                    = "cases_id = " . $id;
                $table       = CRM_CASES . ' as cc';
                $field                    = array('cc.*,cf.*,CONCAT(l.firstname,"   ",l.lastname) as responsible_name,er.cases_type_name as incident_type_name');
                $join_table  = array( CRM_CASES_FILES_MASTER . ' as cf' => 'cf.cases_related_id=cc.cases_id and cf.cases_status=cc.cases_status',LOGIN . ' as l' => 'l.login_id=cc.responsible', TBL_CRM_CASES_TYPE . ' as er' => 'er.cases_type_id=cc.cases_type_id');
                $data['cases_files'] = $this->common_model->get_records ($table, $field, $join_table, 'left', $match);
                $data['id']          = $id;
                $data['edit_record'] = $edit_record;
                $data['modal_title'] =  lang('VIEW_PROJECT_CASES');

            }

            $data['cases_view'] = $this->viewname;
            $this->load->view ('Contact/ViewCases', $data);

        }
       
    }
    public function editCases($id='') {
        
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            //Get Records From PROSPECT_MASTER Table
            if (!empty($id)) {
                $table = CRM_CASES;
                $match = "cases_id = " . $id;
                $edit_record = $this->common_model->get_records ($table, '', '', '', $match);

                $data['id']          = $id;
                $data['edit_record'] = $edit_record;
                //Get project incidents file
                $match5='';
                $fields5 = array("ct.*");
                $data['cases_type_data'] = $this->common_model->get_records('blzdsk_crm_cases_type as ct', $fields5, '','', $match5);
                $table5 = CONTACT_MASTER . ' as cm';
                $match5 = " cm.is_delete= 0 and cm.status=1 ";
                $fields5 = array("cm.contact_id,cm.contact_name");
                $data['contact_data'] = $this->common_model->get_records($table5, $fields5, '','', $match5);
                 //Get project incidet file
                $match                    = "cases_id = " . $id;
                $table       = CRM_CASES . ' as cc';
                $field                    = array('file_id,cc.cases_related_id,upload_status,file_name,file_path');
                $join_table  = array( CRM_CASES_FILES_MASTER . ' as cf' => 'cf.cases_related_id=cc.cases_id and cf.cases_status=cc.cases_status');
                $data['cases_files'] = $this->common_model->get_records ($table, $field, $join_table, 'left', $match);

                $data['contact_data']  = $this->common_model->getSystemUserData();

                $data['modal_title']         = $this->lang->line('update_cases');
                $data['submit_button_title'] = $this->lang->line('update_cases');
            }
                $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
                $data['contact_view'] = $this->viewname;
                $this->load->view ('Contact/AddCases', $data);

        }
        
    }
     public function deleteCases() {
       
        //$id = $this->uri->segment ('4');
        $id = $this->input->get('cases_id');
        $redirect_link = $_SERVER['HTTP_REFERER'];
        if (!empty($id)) 
        {
            
            //Is delete record
            $update_data['is_delete']      = 1;
            $where                         = array('cases_id' => $id);
            $this->common_model->update (CRM_CASES, $update_data, $where);
           
            unset($id);
                $msg = $this->lang->line ('crm_cases_delete_msg');
                $sess_array = array('setting_current_tab' => 'Cases');
                $this->session->set_userdata($sess_array);
                $this->session->set_flashdata('message', $msg);
            
        }
        
        //redirect ($this->module . '/ProjectIncidents'); //Redirect On Listing page
        redirect($redirect_link);
    }
    
    function jobTitle()
    {
        if(checkPermission('Contact','view') == false)
        {
            redirect('/Dashboard');
        }
        $data['header']         = array('menu_module' => 'CRM');
        $searchtext             = '';
        $perpage                = '';
        $where                  = '';
        $searchtext             = $this->input->post ('searchtext');
        $sortfield              = $this->input->post ('sortfield');
        $sortby                 = $this->input->post ('sortby');
        $perpage                = RECORD_PER_PAGE;
        $data['groupFieldName'] = $groupFieldName = $this->input->post ('groupFieldName');
        $data['groupFieldData'] = $groupFieldData = $this->input->post ('groupFieldData');

        $allflag = $this->input->post ('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata ('jobtitlepaging_data');
        }
        $searchsort_session = $this->session->userdata ('jobtitlepaging_data');
        //Sorting
        
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby']    = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby']    = $searchsort_session['sortby'];
                $sortfield         = $searchsort_session['sortfield'];
                $sortby            = $searchsort_session['sortby'];
            } else {
                $sortfield         = 'job_title_id';
                $sortby            = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby']    = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext         = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage']    = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage']    = trim ($searchsort_session['perpage']);
                $config['per_page'] = trim ($searchsort_session['perpage']);
            } else {
                $config['per_page'] = RECORD_PER_PAGE;
                $data['perpage']    = RECORD_PER_PAGE;
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url']   = base_url ('Contact/jobTitle');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
           
            $config['uri_segment'] = 0;
            $uri_segment           = 0;
        } else {
           
            $config['uri_segment'] = 3;
            $uri_segment           = $this->uri->segment (3);
        }
        
       
       // $data['project_incidenttype_view'] = $this->module . '/' . $this->viewname;
         $data['project_incidenttype_view'] = base_url ('Contact/jobTitle');
        
        //Get Records From PROJECT_MASTER Table  
        
        $dbSearch = "";
        if (!empty($searchtext)) {
            $searchFields = array('job_title_name');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr ($dbSearch, 0, -3) . ')';
        }
        $table                = TBL_CRM_JOB_TITLE . ' as pa';
        $fields               = array('job_title_id,job_title_name,created_date');
        $where                = array('is_delete'   => 0); 
        $config['total_rows'] = $this->common_model->get_records (TBL_CRM_JOB_TITLE, $fields, '', '', $where, '', '', '', 'job_title_id', 'desc', '', $dbSearch, '', '', '1');
        //Get Records From MILESTONE_MASTER Table   
        $data['project_incidenttype_data'] = $this->common_model->get_records ($table, $fields, '', '', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $dbSearch);
       
       
        $this->ajax_pagination->initialize ($config);
        $data['pagination']  = $this->ajax_pagination->create_links ();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'      => $data['sortfield'],
            'sortby'         => $data['sortby'],
            'searchtext'     => $data['searchtext'],
            'perpage'        => trim ($data['perpage']),
            'uri_segment'    => $uri_segment,
            'groupFieldName' => $this->input->post ('groupFieldName'),
            'groupFieldData' => $this->input->post ('groupFieldData'),
            'total_rows'     => $config['total_rows']);
        $this->session->set_userdata ('jobtitlepaging_data', $sortsearchpage_data);
       
        if ($this->input->is_ajax_request ()) {
       
            if ($this->input->post ('project_ajax')) {
                $this->load->view ('/JobTitleType', $data);
            } else {
                $this->load->view ('/JobTitleTypeAjaxList', $data);
            }
        } else {
            
            $data['main_content'] = 'JobTitleType';
            $data['js_content']   = '/loadJsFilesJobTitle';
            $this->parser->parse ('layouts/DashboardTemplate', $data);
        }
    }
    
    public function add_job_title() {

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else
        {
            $data['modal_title']         = lang('CREATE_NEW_JOB_TITLE');
            $data['submit_button_title'] = lang('CREATE_NEW_JOB_TITLE');

            //url for filemanager
            $data['project_incidenttype_view'] = '/Contact';
            $this->load->view ('Add_JobTitle', $data);
        }
        
    }
    
     public function insert_job_title() {
         
        $this->form_validation->set_rules('job_title_name','Job Title','trim|required|xss_clean');
        
        if($this->form_validation->run() == false){
            $msg = "Job title required";
            $this->session->set_flashdata('error', $msg);
            redirect ('Contact/jobTitle');
        }
        if ($this->input->post ('job_title_id')) {
            $id = $this->input->post ('job_title_id');
        }
        
        if (!validateFormSecret()) {
            redirect ('Contact/jobTitle');
        }
        
        $display = $this->input->post ('display');
        
        $insert_data['job_title_name'] = ucfirst ($this->input->post ('job_title_name'));
        $insert_data['status']             = 1;
        $insert_data['modified_date'] = datetimeformat ();
        $insert_data['modified_by']   = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
        //Insert Record in Database
        if (!empty($id)) //update
        {
            $where                        = array('job_title_id' => $id);
            $success_update               = $this->common_model->update (TBL_CRM_JOB_TITLE, $insert_data, $where);
            $msg                          = $this->lang->line ('JOB_TITLE_UPDATES_MSG');
            $this->session->set_flashdata ('msg', $msg);
            
        } else //insert
        {
            
            $insert_data['created_by']   = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['created_date'] = datetimeformat ();
            $id                          = $this->common_model->insert (TBL_CRM_JOB_TITLE, $insert_data);
            $msg                         = $this->lang->line ('JOB_TITLE_ADD_MSG');
            $this->session->set_flashdata ('msg',$msg);

        }
        
        redirect ('Contact/jobTitle');
    }
    
    public function edit_job_title($id= NULL) {
        
        //$id = $this->uri->segment ('4');
        //Get Records From PROSPECT_MASTER Table
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{
            if (!empty($id)) {
                $table = TBL_CRM_JOB_TITLE;
                $match = "job_title_id = " . $id;

                $edit_record = $this->common_model->get_records ($table, '', '', '', $match);

                $data['id']          = $id;
                $data['edit_record'] = $edit_record;

                $data['modal_title']         = lang('UPDATE_JOB_TITLE');
                $data['submit_button_title'] = lang('UPDATE_JOB_TITLE');
            }

            $data['project_incidenttype_view'] = 'Contact';
            $this->load->view ('Add_JobTitle', $data);
        }
        
    }
    
    public function delete_job_title($id = null) {
        //$id = $this->uri->segment ('4');
        if (!empty($id)) {
            
            //Is delete record
            $update_data['is_delete']      = 1;
            $where                         = array('job_title_id' => $id);
            $this->common_model->update (TBL_CRM_JOB_TITLE, $update_data, $where);
           
            unset($id);
            $msg = $this->lang->line ('DELETE_JOB_TITLE_MSG');
            $this->session->set_flashdata ('msg', $msg);
            
        }
        redirect ('Contact/jobTitle'); //Redirect On Listing page
    }
    
    public function getContactCommunication()
    {
        $event_related_id=$this->input->post('contact_related_id');
        if($event_related_id!="")
        {
           
            $contact_id = $event_related_id;
        }
        else{
           
            $contact_id = $_SESSION['current_related_id'];
        }
        $data['contact_id'] = $contact_id;
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $note_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $note_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $note_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $note_status = 1;
        }
        
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {
            
           $where_search = '(td.comm_subject LIKE "%' . $searchtext . '%" )';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(td.comm_subject LIKE "%' . $searchtext1 . '%" )';
        }else
        {
            $this->session->set_userdata('searchtext','');
        }
     
        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        
        //config variable for the pagination
        $config['base_url'] = base_url('Contact'). '/getContactCommunication';
        
        $data['tasksortField'] = 'comm_id';
        $data['tasksortOrder'] = 'DESC';
       // $fields = array("td.*,(SELECT GROUP_CONCAT(cm.contact_name) FROM blzdsk_contact_master as cm WHERE FIND_IN_SET(cm.contact_id, td.comm_related_id) > 0) as sender_name,(SELECT GROUP_CONCAT(cm.contact_name) FROM blzdsk_contact_master as cm WHERE FIND_IN_SET(cm.contact_id, td.comm_receiver) > 0) as receiver_name");
         $fields = array("td.*,(SELECT CONCAT(l.firstname,' ',l.lastname) FROM  blzdsk_login as l WHERE l.login_id=td.comm_sender) as sender_name,(SELECT GROUP_CONCAT(cm.contact_name) FROM blzdsk_contact_master as cm WHERE FIND_IN_SET(cm.contact_id, td.comm_receiver) > 0) as receiver_name");
        $where = array("td.is_delete" => "0");
        $match  = " FIND_IN_SET($contact_id,td.comm_related_id) > 0 ";
        $config['total_rows'] = count($this->common_model->get_records(TBL_EMAIL_COMMUNICATION . ' as td', $fields, '', '',$match, '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search));
        $config['per_page'] = RECORD_PER_PAGE;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['communication_data'] = $this->common_model->get_records(TBL_EMAIL_COMMUNICATION . ' as td', $fields, '', '', $match, '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search);
        
        //echo $this->db->last_query();
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        //$this->pagination->initialize($config);
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        
        $data['main_content'] = '/AjaxCommunication';
        $this->load->view('/AjaxCommunication', $data);
    }
    
    function viewCommunication($id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data['comm_id'] = $id;
            $data['modal_title'] = $this->lang->line('VIEW_EMAIL_CONTENT');

            $previewEvents['fields'] = ['td.*,(SELECT GROUP_CONCAT(cm.contact_name) FROM blzdsk_contact_master as cm WHERE FIND_IN_SET(cm.contact_id, td.comm_related_id) > 0) as sender_name,(SELECT GROUP_CONCAT(cm.contact_name) FROM blzdsk_contact_master as cm WHERE FIND_IN_SET(cm.contact_id, td.comm_receiver) > 0) as receiver_name'];
            $previewEvents['table'] = TBL_EMAIL_COMMUNICATION . ' as td';
            $previewEvents['match_and'] = 'td.comm_id=' . $id;


            $data['comm_data'] = $this->common_model->get_records_array($previewEvents);


            //if comm_type 1 than get attach file from event blzdsk_email_communication_files_master
            if($data['comm_data'][0]['comm_type'] == "1" || $data['comm_data'][0]['comm_type'] == "3")
            {
                $event_file_data['fields'] = ['ec.file_name,ec.file_path'];
                $event_file_data['table'] = TBL_EMAIL_COMMUNICATION_FILE_MASTER . ' as ec';
                $event_file_data['match_and'] = 'ec.email_communication_id=' . $data['comm_data'][0]['comm_id'] ;
                $attach_file_data = $this->common_model->get_records_array($event_file_data);

            }else
            {
                $email_prospect_data['fields'] = ['ep.file_name,ep.file_path'];
                $email_prospect_data['table'] = TBL_EMAIL_PROSPECT_FILE_MASTER . ' as ep';
                $email_prospect_data['match_and'] = 'ep.email_prospect_id=' . $data['comm_data'][0]['email_prospect_id'] ;
                $attach_file_data = $this->common_model->get_records_array($email_prospect_data);

            }
            $data['attach_file_data'] = $attach_file_data;

            $data['main_content'] = '/ViewCommunication';
            $this->load->view('/ViewCommunication', $data);
        }
        
    }
    
    function scheduleMeeting($id) 
    {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $redirect_link = $_SERVER['HTTP_REFERER'];
            $edited_id = [];
            if(strpos($redirect_link, 'Contact') !== false) 
            {
                $type_id = 2;
                $edited_id[] = $id."/2";

                $table_receipent = CONTACT_MASTER . ' as cm';
                $match_receipent = " cm.contact_id = ".$id;
                $fields_receipent = array("cm.company_id");
                $arr_company_prospect_id=  $this->common_model->get_records($table_receipent, $fields_receipent, '', '', $match_receipent);
                $company_id = $arr_company_prospect_id[0]['company_id'];
                $edited_id[] = $company_id."/3";

            }else
            {


                if (strpos($redirect_link, 'Opportunity/viewdata') !== false || strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) 
                {
                    $table_receipent = PROSPECT_MASTER . ' as pm';
                    $match_receipent = " pm.prospect_id = ".$id;
                    $fields_receipent = array("pm.company_id");
                    $arr_company_prospect_id=  $this->common_model->get_records($table_receipent, $fields_receipent, '', '', $match_receipent);
                    $company_id = $arr_company_prospect_id[0]['company_id'];
                    $edited_id[] = $company_id."/3";
                    $type_id = 4;
                    if(strpos($redirect_link, 'Account/viewdata') !== false)
                    {
                        $type_id = 6;
                    }

                    if(strpos($redirect_link, 'Account/viewLostClient'))
                    {
                        $type_id = 7;
                    }

                }else if(strpos($redirect_link, 'Lead/viewdata') !== false)
                {
                    $table_receipent = LEAD_MASTER . ' as l';
                    $match_receipent = " l.lead_id = ".$id;
                    $fields_receipent = array("l.company_id");
                    $arr_company_prospect_id=  $this->common_model->get_records($table_receipent, $fields_receipent, '', '', $match_receipent);
                    $company_id = $arr_company_prospect_id[0]['company_id'];
                    $edited_id[] = $company_id."/3";
                    $type_id = 5;
                }
            }
            $data['company_id'] =  $company_id;
            $data['edited_id'] = $edited_id;
            $data['meeting_related_id']=$id;
            $data['contact_id'] = $id."/".$type_id;
            $data['modal_title'] = $this->lang->line('SCHEDULE_MEETING');
            $data['submit_button_title'] = $this->lang->line('SCHEDULE_MEETING_AND_INVITE');
            $data['sales_view'] = $this->viewname;

            $table1 = CONTACT_MASTER . ' as cm';
            $match1 = " cm.is_delete=0 and cm.status=1 ";
            $fields1 = array("cm.contact_id,cm.contact_name");
            $contact_participants = $data['contact_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);


            $participants_arr = [] ;
            foreach ($contact_participants as $con_participants)
            {
                $participants_arr[] = array('user_id'=>$con_participants['contact_id'],'user_name'=>$con_participants['contact_name'],'user_type'=>'2');
            }
            $employee_participants = $data['system_employee']  = $this->common_model->getSystemUserData();

            $logged_in_user_id = $this->session->userdata('LOGGED_IN')['ID'];
            foreach ($employee_participants as $emp_participants)
            {
                if($logged_in_user_id != $emp_participants['login_id'])
                {
                    $emp_name = $emp_participants['firstname']." ".$emp_participants['lastname'];
                    $participants_arr[] = array('user_id'=>$emp_participants['login_id'],'user_name'=>$emp_name,'user_type'=>'1');
                }
            }

            $table_company = COMPANY_MASTER . ' as c';
            $match_company = " c.is_delete=0 and c.status=1 ";
            $fields_company = array("c.company_id,c.company_name");
            $data['company_data'] =  $company_participants =  $this->common_model->get_records($table_company, $fields_company, '', '', $match_company);

             foreach ($company_participants as $company_participants)
            {
                $participants_arr[] = array('user_id'=>$company_participants['company_id'],'user_name'=>$company_participants['company_name'],'user_type'=>'3');
            }



            $data['edit_participants'] =[];
            $data['meeting_particiapnts'] = $participants_arr;
            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['drag'] = true;
            $data['form_action'] = 'addUpdateMeeting';
            $data['display_from'] = 'contact';
            $data['main_content'] = '/AddEditMeetingNew';
            $this->load->view('/AddEditMeetingNew', $data);
        }
        
    }
    
    function chk_meeting_participants($user_id,$user_type,$meeting_master_id)
    {
            $table1 = TBL_SCHEDULE_MEETING_RECEIPENT . ' as mr';
            //$match1 = " mr.is_delete=0 and mr.meeting_master_id=".$meeting_master_id.' and mr.user_id='.$user_id.' and mr.user_type='.$user_type;
            $match1 = "mr.meeting_master_id=".$meeting_master_id.' and mr.user_id='.$user_id.' and mr.user_type='.$user_type;
            $fields1 = array("mr.*");
            $get_from_receipent = $this->common_model->get_records($table1, $fields1, '', '', $match1);
           
            if(empty($get_from_receipent))
            {
               
                return false;
            }else 
            {
                return true;
            }
          
    }
    
    
    function addUpdateMeeting()
    {

        $contact_id_array = $this->input->post('contact_id');
        $hdn_contact_id= $this->input->post('hdn_contact_id');
        $hdn_company_id= $this->input->post('hdn_company_id');
        
        if(isset($hdn_contact_id) && $hdn_contact_id != '')
        {
            $contact_id_array[] =$hdn_contact_id;
        }
        if(isset($hdn_company_id) && $hdn_company_id != '')
        {
            $contact_id_array[] =$hdn_company_id;
        }
        
       
        $redirect_link = $this->input->post ('redirect_link');

        if (!validateFormSecret()) {
            redirect($redirect_link); 
        }
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $meet_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $meet_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $meet_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $meet_status = 1;
        }else
        {
            $meet_status = 1;
        }
        
        if ($this->input->post('meeting_id') != '') {
            
            $meeting_id= $this->input->post('meeting_id');
        }
        

        
        $meeting_master['meet_user_id']  = $this->user_info['ID'];
        $meeting_master['meet_title'] = ucfirst ($this->input->post('meeting_subject'));
        $meeting_master['meeting_description'] = ucfirst ($this->input->post('meeting_description'));
        $meeting_master['additiona_receipent_email'] = ucfirst ($this->input->post ('additional_receipent'));
        $meeting_master['meeting_date'] = date('Y-m-d',  strtotime($this->input->post ('meeting_date')));
        $meeting_master['meeting_time'] = $this->input->post ('meeting_time');
        $meeting_master['meeting_end_time'] = $this->input->post ('meeting_end_time');
        $m_meeting_reminder = $this->input->post ('event_reminder');
        
       
        
        
        $meeting_master['meeting_location'] = $this->input->post ('meeting_location');
        $m_is_private = $this->input->post ('private_visible');
        $m_is_public = $this->input->post ('public_visible');
        $m_is_event = $this->input->post ('create_event');
        $m_is_recurring = $this->input->post ('recurring_meeting');
        $meeting_master['recurring_repeat'] = $this->input->post ('recurring_repeat');
        $meeting_master['recurring_end_date'] = $this->input->post ('recurring_end_date');
        $is_another_location = $this->input->post('is_another_location');
        $company_id_location = $this->input->post('company_id_location');
        
        if(isset($company_id_location) && $company_id_location != '')
        {
            $meeting_master['company_id_location'] =$company_id_location;
        }else
        {
            $meeting_master['company_id_location'] = '';
        }
        
        if(isset($m_meeting_reminder) && $m_meeting_reminder == 'on')
        {
            $meeting_master['meeting_reminder'] = 1;
        }else
        {
            $meeting_master['meeting_reminder'] = 0;
        }
        
        if(isset($m_is_private) && $m_is_private == 'on')
        {
            $meeting_master['is_private'] = 1;
        }else
        {
            $meeting_master['is_private'] = 0;
        }
        
        if(isset($m_is_public) && $m_is_public == 'on')
        {
            $meeting_master['is_public'] = 1;
        }else
        {
            $meeting_master['is_public'] = 0;
        }
        
        if(isset($m_is_event) && $m_is_event == 'on')
        {
            $meeting_master['is_event'] = 1;
        }else
        {
            $meeting_master['is_event'] = 0;
        }
        
        if(isset($m_is_recurring) && $m_is_recurring == 'on')
        {
            $meeting_master['is_recurring'] = 1;
        }else
        {
            $meeting_master['is_recurring'] = 0;
        }
        
        if(isset($is_another_location) && $is_another_location == 'on')
        {
            $meeting_master['is_another_location'] = 1;
        }else
        {
            $meeting_master['is_another_location'] = 0;
        }
        
        if($meeting_master['meeting_reminder'] = 1)
        {
            $meeting_master['reminder_date'] = $this->input->post('reminder_date');
            $meeting_master['reminder_time'] = $this->input->post('reminder_time');
        }
        $sess_array = array('setting_current_tab' => 'Meeting');
       $this->session->set_userdata($sess_array);
        
     
       //Set Validation for can not schedule meeting before and after five min.
        //$before_five_min = date('H:i:s', strtotime(''.$meeting_master['meeting_time'].' - 5 minutes'));
        //$after_five_min = date('H:i:s', strtotime(''.$meeting_master['meeting_time'].' + 5 minutes') );
       
        $meeting_start_time = $meeting_master['meeting_time'];
        $meeting_end_time = $meeting_master['meeting_end_time'];
        
        $table_sm = TBL_SCHEDULE_MEETING_MASTER . ' as sm';
       // $match_sm = 'sm.meet_user_id = "'.$meeting_master['meet_user_id'].'" and sm.meeting_date = "'.$meeting_master['meeting_date'].'" and sm.meeting_time = "'.$meeting_master['meeting_time'].'" and sm.is_delete = 0' ;
         $match_sm = 'sm.meet_user_id = "'.$meeting_master['meet_user_id'].'" and sm.meeting_date = "'.$meeting_master['meeting_date'].'" and sm.meeting_time  BETWEEN "'.$meeting_start_time.'" AND "'.$meeting_end_time.'" and sm.is_delete = 0' ;
        $fields_sm = array("sm.meeting_master_id");
        $chk_exist_meeting_arr = $this->common_model->get_records($table_sm, $fields_sm, '', '', $match_sm);
       
        //Insert Record in Database
        if (@$meeting_id != '') 
        {
            $cur_met_id = [];
            foreach ($chk_exist_meeting_arr as $meet_id)
            {
                if($meet_id['meeting_master_id'] != $meeting_id)
                {
                    $cur_met_id[] = $meet_id['meeting_master_id'];
                }
            }
           
            if(is_array($cur_met_id) && count($cur_met_id) > 0)
            {
           
                $msg = lang('MSG_ALREADY_SCHEDULE_MEETING');
                $this->session->set_flashdata ('message',$msg);
                redirect($redirect_link);
            }
            //update
            $meeting_master_id = $meeting_id;
            $meeting_master['modified_date'] = datetimeformat();
            $where = array('meeting_master_id' => $meeting_master_id);
            $update_meeting_master = $this->common_model->update (TBL_SCHEDULE_MEETING_MASTER, $meeting_master, $where);
            
            $table1 = TBL_SCHEDULE_MEETING_RECEIPENT . ' as mr';
            $match1 = " mr.is_delete=0 and mr.meeting_master_id=".$meeting_master_id;
            $fields1 = array("mr.*");
            $get_from_receipent = $this->common_model->get_records($table1, $fields1, '', '', $match1);
            
            $rec_exist = [];
            $exclude_contact = []; 
            
            foreach ($get_from_receipent as $rec)
            {
                $rec_exist[] = $rec['user_id']."/".$rec['user_type'];
                if($rec['user_type'] == '4'  || $rec['user_type'] == '5' || $rec['user_type'] == '6')
                {
                    $exclude_contact[] = $rec['user_id']."/".$rec['user_type'];
                }
            }
            
            
           
            foreach ($rec_exist as $res1)
            {
                if(!in_array($res1, $contact_id_array))
                {
                   
                    $particiapants_arr  = explode('/',$res1);
                    $participant_id =  $particiapants_arr[0];
                    $participant_type =  $particiapants_arr[1];
                    
                    if($participant_type == '1'  || $participant_type == '2' || $participant_type == '3' )
                    {
                        $update_recepennt['is_delete'] = 1; 
                        $where_receipent = array('meeting_master_id'=>$meeting_master_id,'user_id'=>$participant_id,'user_type'=>$participant_type);
                        $this->common_model->update (TBL_SCHEDULE_MEETING_RECEIPENT, $update_recepennt, $where_receipent);
                
                    }
                    
                    if($participant_type == '2')
                    {
                        $update_meeting['is_delete'] = 1;
                        $where_meeting = array('meeting_master_id'=>$meeting_master_id,'meet_related_id'=>$participant_id,'meet_contact_id'=>$participant_id);
                        $this->common_model->update (TBL_SCHDEULE_MEETING, $update_meeting,$where_meeting);
                        
                        $update_event ['is_delete'] = 1;
                        $where_event = array('meeting_master_id'=>$meeting_master_id,'event_related_id'=>$participant_id);
                        $this->common_model->update (TBL_EVENTS, $update_event,$where_event);
                    }
                }
                
            }
            
            if(count($exclude_contact) > 0)
            {
                $contact_id_array = array_merge($contact_id_array,$exclude_contact);
            }
            
            foreach ($contact_id_array as $contact_id)
            {
                $particiapants_arr  = explode('/',$contact_id);
                $participant_id =  $particiapants_arr[0];
                $participant_type =  $particiapants_arr[1];
                
                $chk_exist = $this->chk_meeting_participants($participant_id,$participant_type,$meeting_master_id);
                if($chk_exist)
                {
                    
                   
                    $update_recepennt['is_delete'] = 0; 
                    $where_receipent = array('meeting_master_id'=>$meeting_master_id,'user_id'=>$participant_id,'user_type'=>$participant_type);
                    $this->common_model->update (TBL_SCHEDULE_MEETING_RECEIPENT, $update_recepennt, $where_receipent);
                
                    $update_recepennt_rec['is_delete'] = 0; 
                    $where_receipent_rec = array('meeting_master_id'=>$meeting_master_id,'meet_related_id'=>$participant_id);
                    $this->common_model->update (TBL_SCHDEULE_MEETING, $update_recepennt_rec, $where_receipent_rec);
                
                    if(($meeting_master['is_event'] == '1') && ($participant_type == '2'  || $participant_type == '4' || $participant_type == '5' || $participant_type == '6'))
                    {
                        
                        $table1 = TBL_EVENTS . ' as e';
                        $match1 = "e.meeting_master_id=".$meeting_master_id.' and e.event_related_id='.$participant_id;
                        $fields1 = array("e.event_id");
                        $exits_event = $this->common_model->get_records($table1, $fields1, '', '', $match1);
                        
                       
                        if($participant_type == '2')
                        {
                                $contact_particiapnt_id[] = $participant_id;
                                $meet_status_new = 1;
                        }else if($participant_type == '4')
                        {
                                 $meet_status_new = 4;
                                 
                        }else if($participant_type == '6')
                        {
                                 $meet_status_new = 2;
                                 
                        }else if($participant_type == '5')
                        {
                                 $meet_status_new = 3;
                        }
                        $event_data['event_related_id'] = $participant_id;
                        $event_data['meeting_master_id'] = $meeting_master_id;
                        $event_data['event_title'] = $meeting_master['meet_title'];
                        $event_data['event_date'] = $meeting_master['meeting_date']." ".$meeting_master['meeting_time'];
                        $event_data['event_remember'] = $meeting_master['meeting_reminder'];
                        $event_data['event_note'] = $meeting_master['meeting_description'];
                        $event_data['event_place'] = $meeting_master['meeting_location'];
                        $event_data['event_status'] = $meet_status_new;
                        $event_data ['is_delete'] = 0;
                    
                        if(!empty($exits_event))
                        {
                            $where = array('meeting_master_id' => $meeting_master_id,'event_related_id'=>$participant_id);
                            $this->common_model->update (TBL_EVENTS, $event_data, $where); 
                        }  else 
                        {
                          
                            $event_data ['created_date'] = datetimeformat();
                            $this->common_model->insert (TBL_EVENTS, $event_data);
                        }
                       
                       
                    }
                    
                    if($meeting_master['is_event'] == '0')
                    {
                        $update_event ['is_delete'] = 1;
                        //$where_event = array('meeting_master_id'=>$meeting_master_id,'event_related_id'=>$participant_id);
                        $where_event = array('meeting_master_id'=>$meeting_master_id);
                        $this->common_model->update (TBL_EVENTS, $update_event,$where_event);
                    }
                  
                }else
                {
                    
                    //insert into meeting reciepent and also in schedule meeting if contact 
                    $meeting_receipents['meeting_master_id'] = $meeting_master_id;
                    $meeting_receipents['user_id'] = $participant_id;
                    $meeting_receipents['user_type'] = $participant_type;
                    $meeting_receipents['is_delete'] = 0;
                    $meeting_receipents['created_date'] = datetimeformat();
                    $this->common_model->insert (TBL_SCHEDULE_MEETING_RECEIPENT, $meeting_receipents);
                    
                    if($participant_type == '2' || $participant_type == '4' || $participant_type == '5' || $participant_type == '6')
                    {
                        if($participant_type == '2')
                        {
                                $contact_particiapnt_id[] = $participant_id;
                                $meet_status_new = 1;
                        }else if($participant_type == '4')
                        {
                                 $meet_status_new = 4;
                        }else if($participant_type == '6')
                        {
                                 $meet_status_new = 2;
                        }else if($participant_type == '5')
                        {
                                 $meet_status_new = 3;
                        }
                       // $contact_particiapnt_id[] = $participant_id;
                        $schedule_meeting['meeting_master_id'] = $meeting_master_id;
                        $schedule_meeting['meet_related_id'] = $participant_id;
                        $schedule_meeting['meet_status'] = $meet_status_new;
                        $schedule_meeting['meet_user_id'] = $this->user_info['ID'];
                        $schedule_meeting['meet_contact_id'] = $participant_id;
                        $schedule_meeting['is_delete'] = 0;
                        $schedule_meeting['created_date'] = datetimeformat();
                        
                        $this->common_model->insert (TBL_SCHDEULE_MEETING, $schedule_meeting);
                        
                        if(($meeting_master['is_event'] == '1') && ($participant_type == '2' || $participant_type == '4' || $participant_type == '5' || $participant_type == '6'))
                        {
                            $event_data['event_related_id'] = $participant_id;
                            $event_data['meeting_master_id'] = $meeting_master_id;
                            $event_data['event_title'] = $meeting_master['meet_title'];
                            $event_data['event_date'] = $meeting_master['meeting_date']." ".$meeting_master['meeting_time'];
                            $event_data['event_remember'] = $meeting_master['meeting_reminder'];
                            $event_data['event_note'] = $meeting_master['meeting_description'];
                            $event_data['event_place'] = $meeting_master['meeting_location'];
                            $event_data['event_status'] = $meet_status_new;
                            $event_data['is_delete'] = 0;
                            $event_data['created_date'] = datetimeformat();
                            $this->common_model->insert (TBL_EVENTS, $event_data);
                          
                        }
                    }
                }
            }
            
            //start code for file uploading
            $file_name = array();
            $file_array1 = $this->input->post('file_data');

            $file_name = $_FILES['cost_files']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['cost_files']['name'][$key]);
                        unset($_FILES['cost_files']['type'][$key]);
                        unset($_FILES['cost_files']['tmp_name'][$key]);
                        unset($_FILES['cost_files']['error'][$key]);
                        unset($_FILES['cost_files']['size'][$key]);
                    }
                }
            }
            $_FILES['cost_files'] = $arr = array_map('array_values', $_FILES['cost_files']);
           

            $tmp_url = base_url()."Contact";
            $uploadData = uploadImage('cost_files',SCHEDULE_MEETING_ATTACH_PATH,$tmp_url);

            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }
            $compaigndata['file_name'] = $file_data;
            if ($compaigndata['file_name'] != '') {
                $explodedData = explode(',', $compaigndata['file_name']);

                foreach ($explodedData as $img) {
                    array_push($uploadData, array('file_name' => $img));
                }
            }

            $costFiles = array();
            $attach_arr_gallary = array();
            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $cost_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $attach_arr_gallary[] = FCPATH.$gallery_path[$i].$cost_files[$i];
                        $costFiles[] = ['file_name' => $cost_files[$i], 'file_path' => $gallery_path[$i], 'meeting_master_id' => $meeting_id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    }
                }
            }
            $attach_arr_browse = array();
            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $attach_arr_browse[] = FCPATH."uploads/schedule_meeting_attach/".$files['file_name'];
                    $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => SCHEDULE_MEETING_ATTACH_PATH, 'meeting_master_id' => $meeting_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }

            $email_atached_arr = array_merge($attach_arr_gallary,$attach_arr_browse);

            if (count($costFiles) > 0) 
            {
                $where = array('meeting_master_id' => $meeting_master_id);


                if (!$this->common_model->insert_batch(TBL_SCHEDULE_MEETING_FILE_MASTER, $costFiles)) {
                    $this->session->set_flashdata('message', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                    redirect($redirect_link); //Redirect On Listing page
                }
            }
            
            /**
            * SOFT DELETION CODE STARTS Sanket Jayani
            */
           $softDeleteImagesArr = $this->input->post('softDeletedImages');
           $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
           if (count($softDeleteImagesUrlsArr) > 0) {
               foreach ($softDeleteImagesUrlsArr as $urls) {
                   unlink(BASEPATH . '../' . $urls);
               }
           }

           if (count($softDeleteImagesUrlsArr) > 0) {
               $dlStr = implode(',', $softDeleteImagesArr);
               $this->common_model->delete(TBL_SCHEDULE_MEETING_FILE_MASTER, 'file_id IN(' . $dlStr . ')');
           }
           /*
            * SOFT DELETION CODE ENDS
            */
           //Start Code for Sending Mail
            $contact_particiapnt_id_edit = [];
            $employee_participants_id_edit = [];
            $company_participants_id = [];
            
            foreach ($contact_id_array as $contact)
            {
                $particiapants_arr  = explode('/',$contact);
                $participant_id =  $particiapants_arr[0];
                $participant_type =  $particiapants_arr[1];
                if($participant_type == '2')
                {
                    $contact_particiapnt_id_edit[] = $participant_id;
                }else  if($participant_type == '3')
                {
                    $company_participants_id[] = $participant_id;
                }else if($participant_type == '1')
                {
                    $employee_participants_id_edit[] = $participant_id;
                }
            }
            
            $contact_email_str = ''; 
            $employee_email_str = '';
            $company_email_str = '';
            if(count($contact_particiapnt_id_edit) > 0)
            {
                $contact_email_str = $this->Contact_model->getContactEmailbyId($contact_particiapnt_id_edit);
            }
            
            if(count($employee_participants_id_edit) > 0)
            {
                $employee_email_str = $this->Contact_model->getMultipleLoginUserEmail($employee_participants_id_edit);
            }
            
            if(count($company_participants_id) > 0)
            {
                $company_email_str = $this->Contact_model->getMultipleCompanyUserEmail($company_participants_id);
            }
            
            $email_meeting_receipent = $contact_email_str.",".$employee_email_str.",".$company_email_str.",".$meeting_master['additiona_receipent_email']; 
            
            $email_send_to = $email_meeting_receipent;
            
            $from_email =  $this->user_info['EMAIL'];
            $from_name =  $this->user_info['FIRSTNAME']." ".$this->user_info['LASTNAME'];

            $template =  systemTemplateDataBySlug(TEMPLATE_SCHEDULE_MEETING);
            
            $search  = array('{MEETING_TITLE}', '{MEETING_DATE}', '{MEETING_TIME}','{MEETING_END_TIME}','{MEETING_DESCRIPTION}','{MEETING_LOCATION}','{FROM_NAME}');
            $replace = array( ucfirst($meeting_master['meet_title']), configDateTime($meeting_master['meeting_date']),convertTimeTo12HourFormat($meeting_master['meeting_time']),convertTimeTo12HourFormat($meeting_master['meeting_end_time']),$meeting_master['meeting_description'],$meeting_master['meeting_location'],$from_name);
            $body1 = str_replace($search,$replace, $template[0]['body']);
            
            $subject = "BLAZEDESK :: UPDATE SCHEDULE MEETING    " . ucwords($template[0]['subject']);
            $headers = array('MIME-Version'=>'1.0\r\n','Disposition-Notification-To'=>$from_email);
            
            $sent = send_mail1($email_send_to, $subject, $body1, $email_atached_arr,$from_email,$from_name, '','',$headers,'') ;
            if($sent)
            {
                $msg = lang('SCHEDULE_MEETING_UPDATES_MSG');
                $this->session->set_flashdata('message', $msg);
            }else
            {
                $msg = lang('FAIL_WITH_SENDING_EMAIL');
                $this->session->set_flashdata('error', $msg);
            }
            //End Code for Seding Mail
           
            
        } else //insert
        {
            //inser data into schedule meeting master
            $meeting_master['created_date'] = datetimeformat();
            $meeting_master['modified_date'] = datetimeformat();
            $meeting_master['is_delete'] = 0;
            
            //start code for validation for can not schedule another meeting on same time 
 
            if(is_array($chk_exist_meeting_arr) && count($chk_exist_meeting_arr) > 0)
            {
                $msg = lang('MSG_ALREADY_SCHEDULE_MEETING');
                $this->session->set_flashdata ('message', $msg);
                redirect($redirect_link);
            }
            //End code for validation for can not schedule another meeting on same time 
            
            
            $meeting_master_id  = $this->common_model->insert (TBL_SCHEDULE_MEETING_MASTER, $meeting_master);
           
            //start code for file uploading
            $file_name = array();
            $file_array1 = $this->input->post('file_data');

            $file_name = $_FILES['cost_files']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['cost_files']['name'][$key]);
                        unset($_FILES['cost_files']['type'][$key]);
                        unset($_FILES['cost_files']['tmp_name'][$key]);
                        unset($_FILES['cost_files']['error'][$key]);
                        unset($_FILES['cost_files']['size'][$key]);
                    }
                }
            }
            $_FILES['cost_files'] = $arr = array_map('array_values', $_FILES['cost_files']);
           

            $tmp_url = base_url()."Contact";
            $uploadData = uploadImage('cost_files',SCHEDULE_MEETING_ATTACH_PATH,$tmp_url);

            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }
            $compaigndata['file_name'] = $file_data;
            if ($compaigndata['file_name'] != '') {
                $explodedData = explode(',', $compaigndata['file_name']);

                foreach ($explodedData as $img) {
                    array_push($uploadData, array('file_name' => $img));
                }
            }

            $costFiles = array();
            $attach_arr_gallary = array();
            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $cost_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $attach_arr_gallary[] = FCPATH.$gallery_path[$i].$cost_files[$i];
                        $costFiles[] = ['file_name' => $cost_files[$i], 'file_path' => $gallery_path[$i], 'meeting_master_id' => $meeting_master_id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                    }
                }
            }
            $attach_arr_browse = array();
            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $attach_arr_browse[] = FCPATH."uploads/schedule_meeting_attach/".$files['file_name'];
                    $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => SCHEDULE_MEETING_ATTACH_PATH, 'meeting_master_id' => $meeting_master_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }

            $email_atached_arr = array_merge($attach_arr_gallary,$attach_arr_browse);

            if (count($costFiles) > 0) 
            {
                $where = array('meeting_master_id' => $meeting_master_id);


                if (!$this->common_model->insert_batch(TBL_SCHEDULE_MEETING_FILE_MASTER, $costFiles)) {
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                    redirect($this->module . '/Contact/'); //Redirect On Listing page
                }
            }
           
            
            //start code for file uploading
            if($meeting_master_id)
            {
                $contact_particiapnt_id = [];
                $employee_participants_id = [];
                
                foreach ($contact_id_array as $contact_id)
                {
                    //$participant_type=    1 => EMPLOYEE 
                                    //AND   2= CONTACT,3- Comapny,4-Opportunity,5-Lead,6-Account
                    $particiapants_arr  = explode('/',$contact_id);
                    $participant_id =  $particiapants_arr[0];
                    $participant_type =  $particiapants_arr[1];
                    
                    $meeting_receipents['meeting_master_id'] = $meeting_master_id;
                    $meeting_receipents['user_id'] = $participant_id;
                    $meeting_receipents['user_type'] = $participant_type;
                    $meeting_receipents['is_delete'] = 0;
                    $meeting_receipents['created_date'] = datetimeformat();
                    
                    $this->common_model->insert (TBL_SCHEDULE_MEETING_RECEIPENT, $meeting_receipents);
                    if($participant_type == '2' || $participant_type == '4' || $participant_type == '5' || $participant_type == '6')
                    {
                        if($participant_type == '2')
                        {
                            $contact_particiapnt_id[] = $participant_id;
                            $meet_status_new = 1;
                        }else if($participant_type == '4')
                        {
                             $meet_status_new = 4;
                        }else if($participant_type == '6')
                        {
                             $meet_status_new = 2;
                        }else if($participant_type == '5')
                        {
                             $meet_status_new = 3;
                        }
                        
                        $schedule_meeting['meeting_master_id'] = $meeting_master_id;
                        $schedule_meeting['meet_related_id'] = $participant_id;
                        $schedule_meeting['meet_status'] = $meet_status_new;
                        $schedule_meeting['meet_user_id'] = $this->user_info['ID'];
                        $schedule_meeting['meet_contact_id'] = $participant_id;
                        $schedule_meeting['is_delete'] = 0;
                        $schedule_meeting['created_date'] = datetimeformat();
                        
                        $this->common_model->insert (TBL_SCHDEULE_MEETING, $schedule_meeting);
                        
                        if($meeting_master['is_event'] == '1')
                        {
                            $event_data['event_related_id'] = $participant_id;
                            $event_data['meeting_master_id'] = $meeting_master_id;
                            $event_data['event_title'] = $meeting_master['meet_title'];
                            $event_data['event_date'] = $meeting_master['meeting_date']." ".$meeting_master['meeting_time'];
                            $event_data['event_remember'] = $meeting_master['meeting_reminder'];
                            $event_data['event_note'] = $meeting_master['meeting_description'];
                            $event_data['event_place'] = $meeting_master['meeting_location'];
                            $event_data['event_status'] = $meet_status_new;
                            $event_data['is_delete'] = 0;
                            $event_data['created_date'] = datetimeformat();
                            $this->common_model->insert (TBL_EVENTS, $event_data);
                          
                        }
                    }else  if($participant_type == '1')
                    {
                        $employee_participants_id[] = $participant_id;
                        
                    }else  if($participant_type == '3')
                    {
                        $company_participants_id[] = $participant_id;
                        
                    }
                }
              
            }
            
            $contact_email_str = ''; 
            $employee_email_str = '';
            $company_email_str = '';
            if(count($contact_particiapnt_id) > 0)
            {
                $contact_email_str = $this->Contact_model->getContactEmailbyId($contact_particiapnt_id);
            }
            
            if(count($employee_participants_id) > 0)
            {
                $employee_email_str = $this->Contact_model->getMultipleLoginUserEmail($employee_participants_id);
            }
            
            if(count($company_participants_id) > 0)
            {
                $company_email_str = $this->Contact_model->getMultipleCompanyUserEmail($company_participants_id);
            }
            
            $email_meeting_receipent = $contact_email_str.",".$employee_email_str.",".$company_email_str.",".$meeting_master['additiona_receipent_email']; 
            
            
            $email_send_to = $email_meeting_receipent;
            
            $from_email =  $this->user_info['EMAIL'];
            $from_name =  $this->user_info['FIRSTNAME']." ".$this->user_info['LASTNAME'];
            //get email template
            $template =  systemTemplateDataBySlug(TEMPLATE_SCHEDULE_MEETING);
           
            $search  = array('{MEETING_TITLE}', '{MEETING_DATE}', '{MEETING_TIME}','{MEETING_END_TIME}','{MEETING_DESCRIPTION}','{MEETING_LOCATION}','{FROM_NAME}');
            $replace = array( ucfirst($meeting_master['meet_title']), configDateTime($meeting_master['meeting_date']),convertTimeTo12HourFormat($meeting_master['meeting_time']),convertTimeTo12HourFormat($meeting_master['meeting_end_time']),$meeting_master['meeting_description'],$meeting_master['meeting_location'],$from_name);
            $body1 = str_replace($search,$replace, $template[0]['body']);
            
            $subject = "BLAZEDESK ::  " . ucwords($template[0]['subject']);
            $headers = array('MIME-Version'=>'1.0\r\n','Disposition-Notification-To'=>$from_email);
            
            $sent = send_mail1($email_send_to, $subject, $body1, $email_atached_arr,$from_email,$from_name, '','',$headers,'') ;
            if($sent)
            {
                $msg = lang('SCHEDULE_MEETING_ADD_MSG');
                $this->session->set_flashdata ('message', $msg);
            }else
            {
                $msg = lang('FAIL_WITH_SENDING_EMAIL');
                $this->session->set_flashdata('error', $msg);
            }
            
            $msg = lang('SCHEDULE_MEETING_ADD_MSG');
            $this->session->set_flashdata ('message', $msg);
            
        }
      
        redirect ($redirect_link);
        
    }
    
    
    public function getContactMeeting()
    {
        //$contact_id = $_SESSION['current_related_id'];
        $meeting_related_id=$this->input->post('meeting_related_id');
        if($meeting_related_id!="")
        {
           
            $contact_id = $meeting_related_id;
        }
        else{
           
            $contact_id = $_SESSION['current_related_id'];
        }
        $data['contact_id'] = $contact_id;
        $redirect_link = $_SERVER['HTTP_REFERER'];
        
        if (strpos($redirect_link, 'Lead/viewdata') !== false) {
           $meeting_status  = 3;
        }
        elseif (strpos($redirect_link, 'Opportunity/viewdata') !== false) {
            $meeting_status  = 4;
        }
        elseif (strpos($redirect_link, 'Account/viewdata') !== false || strpos($redirect_link, 'Account/viewLostClient') !== false) {
           $meeting_status  = 2;
        }
        elseif(strpos($redirect_link, 'Contact') !== false) {
            $meeting_status = 1;
        }
        
        //commented by sanket on 30-03-2016
        //$note_status=  $this->input->post('note_status');
        
        $where_search = '';
        $searchtext = '';
        $searchtext = $this->input->post('searchtext');
        $session_searchtext = @$this->session->userdata('searchtext');
        if($searchtext == 'clearData')
        {
            $searchtext = '';
            $this->session->set_userdata('searchtext', '');
        }
        if (!empty($searchtext)) 
        {
            
           $where_search = '(mm.meet_title LIKE "%' . $searchtext . '%" )';
           $this->session->set_userdata('searchtext', $searchtext);
        }else if(!empty($session_searchtext))
        {
            $searchtext1 = $this->session->userdata('searchtext');
            $where_search = '(mm.meet_title LIKE "%' . $searchtext1 . '%" )';
        }else
        {
            $this->session->set_userdata('searchtext','');
        }
     
        $this->load->library('pagination');
        $data['project_view'] = $this->viewname;
        
        //config variable for the pagination
        $config['base_url'] = base_url('Contact') . '/getContactMeeting';
        
        $params['join_tables'] = array(CONTACT_MASTER . ' as cm' => 'cm.contact_id=td.meet_contact_id',LOGIN . ' as l' => 'l.login_id=td.meet_user_id',TBL_SCHEDULE_MEETING_MASTER . ' as mm' => 'mm.meeting_master_id=td.meeting_master_id');
        $params['join_type'] = 'left';
        
        $data['tasksortField'] = 'mm.meeting_date';
        $data['tasksortOrder'] = 'ASC';
        $fields = array("td.*,mm.*,cm.contact_name as meeting_contact_name,CONCAT(l.firstname,' ',l.lastname) as login_user_name");
        $where = array("td.is_delete" => "0","td.meet_related_id"=>$contact_id,"td.meet_status"=>$meeting_status);
        $match  = " mm.meeting_date >='".date('Y-m-d')."'";
        $config['total_rows'] = count($this->common_model->get_records(TBL_SCHDEULE_MEETING . ' as td', $fields, $params['join_tables'],$params['join_type'],$match, '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search));
        $config['per_page'] =5;
        $choice = $config["total_rows"] / $config["per_page"];
        //$config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['notePage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['meeting_data'] = $this->common_model->get_records(TBL_SCHDEULE_MEETING . ' as td', $fields, $params['join_tables'], $params['join_type'], $match, '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where,'','','','','',$where_search);
       
        $data['config'] = $config;
        $page_url = $config['base_url'] . '/' . $data['page'];
        //$this->pagination->initialize($config);
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        
        
        $data['main_content'] = '/AjaxMeeting';
        $this->load->view('/AjaxMeeting', $data);
    }
    
    function update_meeting($meeting_id)
    {
        $data['modal_title'] = $this->lang->line('UPDATE_SCHDEDULE_MEETING');
        $data['submit_button_title'] = $this->lang->line('UPDATE_SCHDEDULE_MEETING');
        
        $params['join_tables'] = array(TBL_SCHEDULE_MEETING_ADDITIONAL_RECEIPENT . ' as r' => 'r.meeting_master_id=td.meeting_master_id');
        $params['join_type'] = 'left';
        
        $fields = array("td.*,r.additiona_receipent_email");
        $where = array("td.is_delete" => "0","td.meeting_master_id"=>$meeting_id);
        
        $data['editRecord'] = $this->common_model->get_records(TBL_SCHEDULE_MEETING_MASTER . ' as td', $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $where,'','','','','','');
       
        
        $data['meeting_id'] = $data['editRecord'][0]['meeting_master_id'];
        $data['main_content'] = '/AddEditMeeting';
        $this->load->view('/AddEditMeeting', $data);
    }
    
    function delete_meeting()
    {
        $meeting_id = $this->input->get('meeting_id');
        $redirect_url = $this->input->get('link');
        if (!empty($meeting_id)) 
        {
            $data['is_delete'] = '1';
           
            $where = array('meeting_id' => $meeting_id);
            $success_delete = $this->common_model->update(TBL_SCHDEULE_MEETING,$data,$where);
            
            if($success_delete)
            {
                $msg = lang('MEETING_DELETED_SUCCESS');
                $this->session->set_flashdata('message', $msg);
            }else
            {
                $msg = lang('error_msg');
                $this->session->set_flashdata('error', $msg);
            }
        
        }
        $sess_array = array('setting_current_tab' => 'Meeting');
	$this->session->set_userdata($sess_array);
        redirect($redirect_url);
    }
    
    function getCompanyAddressById()
    {
        $company_id = $this->input->post('company_id');
        $previewProducts['fields'] = ['cm.phone_no,cm.address1,cm.address2,cm.city,cm.state,cm.country_id,cm.postal_code,cm.logo_img'];
        $previewProducts['table'] = COMPANY_MASTER . ' as cm';
        $previewProducts['match_and'] = 'cm.company_id=' . $company_id;
        $data['company_data'] = $this->common_model->get_records_array($previewProducts);
        
        if($data['company_data'][0]['logo_img'] != '')
        {
            $c_log = FCPATH."uploads/company/".$data['company_data'][0]['logo_img'];
           
            $tmp_logo="";
            if(file_exists($c_log))
            {
                $tmp_logo = base_url()."uploads/company/".$data['company_data'][0]['logo_img'];
                
            }
            else{
                $tmp_logo=base_url()."uploads/company/noimage.jpg";
            }
            $data['company_data'][0]['logo_img'] = $tmp_logo ;
        }
      
        echo json_encode($data['company_data'][0]);
    }
    
    function view_meeting($meeting_id)
    {
        $data['meeting_id'] = $meeting_id;
        $data['modal_title'] = $this->lang->line('VIEW_MEETING');
        
        $params['join_tables'] = array(CONTACT_MASTER . ' as cm' => 'cm.contact_id=td.meet_contact_id',LOGIN . ' as l' => 'l.login_id=td.meet_user_id');
        $params['join_type'] = 'left';
        
        $fields = array("td.*,cm.contact_name as meeting_contact_name,CONCAT(l.firstname,' ',l.lastname) as login_user_name");
        $where = array("td.is_delete" => "0","td.meeting_id"=>$meeting_id);
        
        $data['meeting_data'] = $this->common_model->get_records(TBL_SCHDEULE_MEETING . ' as td', $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', '', $where,'','','','','','');
        
        $data['main_content'] = '/ViewMeeting';
        $this->load->view('/ViewMeeting', $data);
    }
    
    function uploadFromEditor()
    {
        
        $allowed = array('png', 'jpg', 'gif','zip');

        if(isset($_FILES['file']) && $_FILES['file']['error'] == 0)
        {

            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if(!in_array(strtolower($extension), $allowed))
            {
                echo '{"status":"error"}';
                exit;
            }

            if(move_uploaded_file($_FILES['file']['tmp_name'],EMAIL_PROSPECT_ATTACH_PATH.time()."_".$_FILES['file']['name'])){
            $tmp=EMAIL_PROSPECT_ATTACH_PATH.$_FILES['file']['name'];
            
            echo base_url().EMAIL_PROSPECT_ATTACH_PATH.time()."_".$_FILES['file']['name'];
            
            }
        }else
        {
            echo '{"status":"error"}';
            exit;
            
        }

        
    }
    
    function getCompany_location()
    {
        $company_id = $this->input->post('company_id');
        
        if($company_id != '')
        {
            $table = COMPANY_MASTER . ' as c, '.COUNTRIES.' as co';
            $match = "c.company_id =".$company_id." and c.country_id=co.country_id";
            $fields = array("c.address1,c.address2,c.city,c.state,co.country_name,c.postal_code");
            $company_location = $this->common_model->get_records($table, $fields, '', '', $match);

            if(count($company_location) > 0)
            {
                $str_location = $company_location[0]['address1'].", ".$company_location[0]['address2'].", ".$company_location[0]['city'].", ".$company_location[0]['state'].", ".$company_location[0]['country_name']." - ".$company_location[0]['postal_code'];
            }else
            {
                $str_location = '';
            }
        }else
        {
             $str_location = '';
        }
        

       echo    $str_location;

    }
    /*
      @Author   : Seema Tankariya
      @Desc     : get address
      @Input 	: inoput id
      @Output	: get address and show in map
      @Date     : 14/06/2016
     */
    function navigation($id=null) 
    {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
             if ($id > 0) 
                {
            
                    $_SESSION['current_related_id'] = $id;

                    //Get Records From PROSPECT_MASTER Table with JOIN
                    $data = array();
                    $params['join_tables'] = array(
                        COMPANY_MASTER . ' as cm' => 'cm.company_id=pm.company_id',
                        COUNTRIES . ' as c' => 'c.country_id=pm.country_id');
                    $params['join_type'] = 'left';
                    $match = "pm.contact_id = " . $id;
                    $table = CONTACT_MASTER . ' as pm';
                    $groupBy = 'pm.contact_id';
                    $fields = array("pm.contact_id,pm.company_id,"
                        . "pm.address1,pm.address2,pm.city,pm.state,c.country_name");
                    $edit_record = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $groupBy);

                                $companyId=$edit_record[0]['company_id'];

                                $params['join_tables'] = array(
                                         COUNTRIES . ' as c' => 'c.country_id=cm.country_id');
                    $params['join_type'] = 'left';
                                $table13 = COMPANY_MASTER . ' as cm';
                                $match13 = "cm.company_id = " . $companyId;
                                $fields13 = array("cm.company_id,cm.address1,cm.address2,cm.city,cm.state,c.country_name");
                                $data['company_record'] = $this->common_model->get_records($table13, $fields13, $params['join_tables'], $params['join_type'], $match13);

                    $data['id'] = $id;
                    $data['all_records'] = $edit_record;

                    if (count($data['all_records'][0]) < 4) {
                        show_404();
                    }
                    //for breadcrumbs
                    $searchtext = @$this->session->userdata('searchtext');
                    if (!empty($searchtext)) {
                        $data['searchtext'] = $searchtext;
                    }

                   // $this->breadcrumbs->push($editRecord[0]['prospect_name'], ' ');
                    $data['header'] = array('menu_module' => 'crm');
                    $data['modal_title'] = $this->lang->line('view_on_map');
                    $data['opportunity_view'] = $this->viewname;
                    $data['drag'] = true;
                    //$data['main_content'] = '/ViewOpportunity';
                    $this->load->view('Opportunity/navigation',$data);
                } else {
                    show_404();
                }

        }
		
         
    }
    
    /*
      @Author   : sanket jayani
      @Desc     : Newsletter API 
      @Input 	: inoput id
      @Output	: get address and show in map
      @Date     : 14/06/2016
    */
    function contact_add_to_mailchimp($list_Ids,$mailchimp_data)
    {
        $data = [
	'email_address'  =>$mailchimp_data['email_address'],'status'    => 'subscribed',
        'merge_fields'  => [
                'FNAME'     => $mailchimp_data['contact_name'],
                'LNAME'     => ''
        ]];
        $this->load->library('MailChimp');
        
        
        foreach ($list_Ids as $lIds)
        {
            $subscriberHash = $this->mailchimp->subscriberHash($data['email_address']);
            $chkMemberExists = $this->mailchimp->get("lists/$lIds/members/$subscriberHash");
            
            if(isset($chkMemberExists['status']) && $chkMemberExists['status'] == '404')
            {
                $result = $this->mailchimp->post("lists/$lIds/members", $data);
            
                if(isset($result['title']) && ($result['status'] == '400' || $result['title'] == 'Member Exists'))
                {
                    $this->subscribe_contact_from_mailChimp($lIds,$mailchimp_data);
                    $this->updateContactNewsletter('0',$mailchimp_data['contact_id'],'1');
                    $this->updateTolistsContact($mailchimp_data['contact_id'],'1');
                    $msg = "Newsletter Error : ".$result['detail'];
                    $this->session->set_flashdata('error',$msg);
                }else
                {
                    $tmp_list = array($lIds);
                    $this->updateContactNewsletter('1',$mailchimp_data['contact_id'],'1');
                    $this->addTolistsContact($tmp_list,$mailchimp_data['contact_id'],'1');
                }
            }
        }
    }
    
    function contact_update_to_mailchimp($list_Id,$mailchimp_data)
    {
        
        $data = [
	'email_address'  =>$mailchimp_data['email_address'],
        'status'    => 'subscribed',
        'merge_fields'  => [
                'FNAME'     => $mailchimp_data['contact_name'],
                'LNAME'     => ''
        ]
        ];
        
        $this->load->library('MailChimp');
        $subscriber_hash = $this->mailchimp->subscriberHash($mailchimp_data['email_address']);
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $update_mailchimp = $this->mailchimp->patch("lists/$ids/members/$subscriber_hash", $data);
            }
        }
        
    }
    
    function delete_contact_from_mailChimp($list_Id,$mailchimp_data)
    {
        $this->load->library('MailChimp');
        $subscriber_hash = $this->mailchimp->subscriberHash($mailchimp_data['email_address']);
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $lists)
            {
                $result = $this->mailchimp->delete("lists/$lists/members/$subscriber_hash");
            }
        }
        
    }
    
    function unsubscribe_contact_from_mailChimp($list_Id,$mailchimp_data)
    {
        $this->load->library('MailChimp');
        $subscriber_hash = $this->mailchimp->subscriberHash($mailchimp_data['email_address']);
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                 $result = $this->mailchimp->patch("lists/$ids/members/$subscriber_hash", [
                'status' => 'unsubscribed']);
            }
        }
       
        
        if($result)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function subscribe_contact_from_mailChimp($list_Id,$mailchimp_data)
    {
        //$list_id = config_list_id();
        $this->load->library('MailChimp');
        $subscriber_hash = $this->mailchimp->subscriberHash($mailchimp_data['email_address']);
        $result = $this->mailchimp->patch("lists/$list_Id/members/$subscriber_hash", [
                'status' => 'subscribed']);
        
        if($result)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    function contact_add_to_cmonitor($list_Id,$cmonitor_data)
    {
        $this->load->library('CampaignMonitor');
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $cmonitor_data['listId'] = $ids;
                $result = $this->campaignmonitor->add_subscriber($cmonitor_data);
                
                if(isset($result) && !empty($result))
                {
                    $tmp_list = array($ids);
                    $this->addTolistsContact($tmp_list,$cmonitor_data['contact_id'],'2');
                    $this->updateContactNewsletter('1',$cmonitor_data['contact_id'],'2');
                }
                
            }
        }
        if($result)
        {
            return true;
        }else
        {
            return false;
        }
        
    }
    
    function contact_update_to_cmonitor($list_Id,$cmonitor_data)
    {
        
        $this->load->library('CampaignMonitor');
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $cmonitor_data['listID'] = $ids;
                $result = $this->campaignmonitor->update_subscriber($cmonitor_data);
            }
        }
        if($result)
        {
            return true;
        }else
        {
            return false;
        }
        
    }
    
    function cmonitor_make_unsubscribe($list_Id,$cmonitor_data)
    {
        $this->load->library('CampaignMonitor');
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $cmonitor_data['listID'] = $ids;
                $result = $this->campaignmonitor->make_unsubscribe($cmonitor_data);
            }
        }
        
      
        if ($result == '') {
            
           return true;
        } else {
        
            return false;
        }
    }
    
    private function add_contact_get_response($list_Id,$name,$email,$contact_id)
    {
        $this->load->library('GetResponse');
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $add_contact = $this->getresponse->addContact($ids,$name,$email);
                
                if($add_contact->queued == '1')
                {
                    $tmp_list = array($ids);
                    $this->addTolistsContact($tmp_list,$contact_id,'4');
                    $this->updateContactNewsletter('1',$contact_id,'4');
                }
               
               
            }
        }
    }
    
    private function delete_contact_get_resposne($list_Id,$contact_email)
    {
        $this->load->library('GetResponse');
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $cotnact_detail = (array) $this->getresponse->getContactsByEmail($contact_email,$ids);
                $key_arr =  array_keys($cotnact_detail);
                $result = $this->getresponse->deleteContact($key_arr[0]);
                
                pr($result);
            }
        }
        
        if ($result->deleted == '1') 
        {
            return true;
        } else {
            return false;
        }
        
    }
    
    
    
    private function update_contact_get_response($list_Id,$contact_email,$new_name)
    {
        $this->load->library('GetResponse');
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $cotnact_detail = (array) $this->getresponse->getContactsByEmail($contact_email,$ids);
                $key_arr =  array_keys($cotnact_detail);
                $result = $this->getresponse->setContactName($key_arr[0],$new_name);
                
               
            }
        }
        
    }
    
    private function add_contact_moosend($list_Id,$name,$email,$cotnact_id)
    {
        $this->load->library('Moosend');
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $add_contact = $this->moosend->add_subscriber($ids,$name,$email);
                if($add_contact->Code == '0' && $add_contact->Error == null)
                {
                    $tmp_list = array($ids);
                    $this->addTolistsContact($tmp_list,$cotnact_id,'3');
                    $this->updateContactNewsletter('1',$cotnact_id,'3');
                }
                
                
            }
        }
    }
    
    private function update_contact_moosend($list_Id,$name,$email,$old_email,$contact_id)
    {
        $this->load->library('Moosend');
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $email_detail = $this->moosend->get_subscriber_by_email($ids,$old_email);
                if($email_detail->Code =='300')
                {
                    $add_contact = $this->add_contact_moosend($ids,$name,$email,$contact_id);
                }else
                {
                    $subscriber_id = $email_detail->Context->ID;
                    $add_contact = $update_subscriber = $this->moosend->update_subscriber($ids,$name,$email,$subscriber_id);
                }
            }
        }
        
       
        if($add_contact->Code == '0')
        {
            return true;
        }  else {
            return false;
        }
    }
    
    private function delete_contact_moosend($list_Id,$email)
    {
        $this->load->library('Moosend');
        
        if(!empty($list_Id) && count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $delete_contact = $this->moosend->remove_subscribers($ids,$email);
            }
        }
        
        if($delete_contact->Code == "0")
        {
            return true;
        }else{
            return false;
        }
    }
    
    private function addTolistsContact($list_Id,$success_insert,$newsletterType)
    {
       
        if(count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                $contactData['contact_id'] = $success_insert;
                $contactData['list_id'] = $ids;
                $contactData['list_type'] = $newsletterType;
                $contactData['status'] = 1;
                $contactData['created_date'] = date('Y-m-d');
                $this->common_model->insert(TBL_NEWSLETTER_LISTS_CONTACT, $contactData);
            }
        }
        
    }
    
    private function updateTolistsContact($success_insert,$newsletterType)
    {
        $contactData['status'] = 0;
        $where = array('contact_id'=>$success_insert,'list_type'=>$newsletterType);
        $this->common_model->update(TBL_NEWSLETTER_LISTS_CONTACT, $contactData,$where);
        
    }
    
    private function getContactNewsletterLists($id,$newsletterType)
    {
        $table_newsletter = TBL_NEWSLETTER_LISTS_CONTACT . ' as bm';
        $match_newsletter = "bm.status=1 AND bm.list_type=$newsletterType AND bm.contact_id=".$id;
        $fields_newsletter = array("bm.list_id");
        $listIdArr = $this->common_model->get_records($table_newsletter, $fields_newsletter, '', '', $match_newsletter);
        
        $tmp_list_arr = [];
        if(count($listIdArr) > 0)
        {
            
            foreach ($listIdArr as $listId)
            {
                $tmp_list_arr[] = $listId['list_id'];
            }
        }
        
        return array_unique($tmp_list_arr);
    }
    
    private function delete_from_cmonitor($tmp_list_arr,$data)
    {
        $this->load->library('CampaignMonitor');
        if(!empty($tmp_list_arr) && count($tmp_list_arr) > 0)
        {
            foreach ($tmp_list_arr as $list)
            {
                $data['listID'] = $list;
                $this->campaignmonitor->make_subscribe($data);
                $result = $this->campaignmonitor->delete_subscriber($data);
            }
        }
    }
    private function updateContactNewsletter($is_newsletter,$contactId,$newsletterType)
    {
        $nData['is_newsletter'] = $is_newsletter;
        $nData['configure_with'] = $newsletterType;
        $where = array('contact_id' => $contactId);
        $this->common_model->update(CONTACT_MASTER, $nData,$where);
    }
}
?>