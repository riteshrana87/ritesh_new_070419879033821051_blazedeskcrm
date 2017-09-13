<?php
/*
@Author : Sanket Jayani
@Desc   : Request Campaign Budget
@Date   : 22/01/2016
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class RequestBudget extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        if(checkPermission('RequestBudget','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->load->library(array('form_validation','Session'));
        $this->load->model('RequestBudget_model');
        $this->current_module = $this->router->fetch_module();
        $this->viewname = $this->current_module;
        $this->load->library('pagination');
    }

    public function index()
    {

        // $data['js_content'] = '/loadJsFiles';
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        //pr($searchtext);exit;
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('salescampaign_data');
        }

        $searchsort_session = $this->session->userdata('salescampaign_data');
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
                $sortfield = 'budget_campaign_id';
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
            $uri_segment = $this->uri->segment(3);
        }

        //Query

        $table = BUDGET_COMPAIGN_MASTER.' as bc';
        $where = array("bc.status" => "0");
        $fields = array("cm.campaign_name,bc.*, lu.firstname,lu.lastname, pr.product_name,ctm.camp_type_name");
        $join_tables   =  array('blzdsk_campaign_master as cm' =>'bc.campaign_id = cm.campaign_id','blzdsk_login as lu' =>'lu.login_id = bc.employee_id','blzdsk_product_master as pr' =>'pr.product_id = bc.product_id','blzdsk_campaign_type_master as ctm' =>'ctm.camp_type_id = bc.campaign_type_id');


        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((cm.campaign_name LIKE "%'.$searchtext.'%" OR ctm.camp_type_name LIKE "%'.$searchtext.'%" OR lu.firstname LIKE "%'.$searchtext.'%" OR pr.product_name LIKE "%'.$searchtext.'%" OR bc.budget_ammount LIKE "%'.$searchtext.'%") AND bc.status = "0")';


            $data['budget_request_list']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);
            //echo $this->db->last_query();exit;
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
            //echo $this->db->last_query();exit;
        }
        else
        {

            $data['budget_request_list']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
        //pr($this->db->last_query());exit;
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
        $this->session->set_userdata('salescampaign_data', $sortsearchpage_data);

        $data['sales_view'] = $this->viewname;
        //pr($data['pagination']);exit;
        $data['header'] = array('menu_module'=>'crm');
        //$data['popup'] = $this->load->view($this->viewname.'/Add',$data);
        $data['drag']=true;
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname.'/ajax_list',$data);
        } else {
            //$data['main_content'] = '/SalesCampaign';
            $data['main_content'] = '/'.$this->viewname;

           // $this->parser->parse('layouts/CampaignTemplate', $data);
            $this->parser->parse('layouts/BudgetRequestTemplate', $data);
    }

    }

    function delete_request()
    {
        $budget_request_id = $this->uri->segment(3); 
        if($this->RequestBudget_model->delete_request($budget_request_id))
        {
            $msg = $this->lang->line('DEL_REQUEST_SUC_MSG');
            $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            redirect($this->viewname);
        }else
        {
            $msg = $this->lang->line('DEL_REQUEST_FAIL_MSG');
            $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            redirect($this->viewname);
        }
    }
    
    function getCampaignDataById()
    {
        $campaign_id = $_POST['campaign_id'];
        /*
        $campaign_data = $this->RequestBudget_model->getCampaignDataById($campaign_id);
        */
        $table = CAMPAIGN_MASTER.' as cm';
        $where = array("cm.campaign_id" => $campaign_id);
        $fields = array("cm.campaign_id, cm.campaign_name,cm.campaign_description, cm.campaign_auto_id, cm.campaign_type_id,cm.responsible_employee_id,cm.start_date,cm.end_date,cm.budget_requirement,cm.budget_ammount,cm.campaign_supplier,cm.supplier_id,cm.revenue_goal,cm.revenue_amount,cm.related_product,cm.product_id,cm.campaign_group_id,cm.file,ctm.camp_type_name");
        $join_tables   =  array('blzdsk_campaign_type_master as ctm' =>'ctm.camp_type_id = cm.campaign_type_id');
        $campaign_data  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);

        echo json_encode($campaign_data);
    }

    function show_Campaign_product()
    {
        $product_id = $_POST['product_id'];

        $data['product_list_data'] = explode(",",$product_id);
        $data['product_data'] = $data['product_list_data'];

        $pm_params=array();
        $pm_params['table'] = PRODUCT_MASTER.' as pm';
        $pm_params['fields'] = array("pm.product_id,pm.product_name");
        $pm_params['where_in']=array("pm.status" => 1,"pm.is_delete" => "0");
        $data['product_list']  = $this->common_model->get_records_array($pm_params);

        $this->load->view($this->viewname.'/Add_ajax',$data);
    }


    function show_Campaign_responsible()
    {
        $campaign_id = $_POST['campaign_id'];


        $table = LOGIN.' as us';
        $where = array("us.is_delete" => 0);
        $fields = array("us.*");
        $data['responsible_employee_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

        $table = CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN.' as ct';
        $where = array("ct.status" => "1","campaign_id" => $campaign_id);
        $fields = array("cm.firstname,cm.lastname,cm.login_id,ct.campaign_id");
        $join_tables   =  array('blzdsk_login as cm' =>'cm.login_id = ct.user_id');
        $responsible_info_id      = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);

        $responsible_name = array();
        if(count($responsible_info_id) > 0){
            foreach($responsible_info_id as $responsible_data){
                $responsible_name[] = $responsible_data['login_id'];
            }

        }
        $data['responsible_user_data'] 	= $responsible_name;
        $this->load->view($this->viewname.'/Add_responsible_user',$data);
    }

    
    function update()
    {
        $hdn_campaign_id = $this->input->post('hdn_campaign_id');
        if (!validateFormSecret()) {
            if ($hdn_campaign_id) {
                $msg = $this->lang->line('BUDGET_CAMPAIGN_UPDATE_FAIL_MSG');
                $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
                redirect($this->viewname); //Redirect On Listing page
            }
        }

        $this->RequestBudget_model->update_campaign_budget_master();
        $update_flg = $this->input->post('budget_campaign');

        $selected_products = $this->input->post('budget_for_product');
        $nProducts = count($selected_products);
        if($update_flg){
            $where = array('budget_campaign_id' => $update_flg);
            $this->common_model->delete(BUDGET_CAMPAIGN_PRODUCT_TRAN, $where);
        }
        for ($products_count = 0; $products_count < $nProducts; $products_count++) {
            $product_data['product_id'] = $selected_products[$products_count];
            //Insert Record in Database

            $product_data['budget_campaign_id'] = $update_flg;
            $this->common_model->insert(BUDGET_CAMPAIGN_PRODUCT_TRAN, $product_data);
        }
        if($update_flg)
        {
            $msg = $this->lang->line('BUDGET_CAMPAIGN_UPDATE_MSG');
            $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            redirect($this->viewname); 
        }else
        {
            $msg = $this->lang->line('BUDGET_CAMPAIGN_UPDATE_FAIL_MSG');
            $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            redirect($this->viewname); 
        }
    }
    
    function create()
    {
        if (!validateFormSecret()) {
            $msg = $this->lang->line('BUDGET_CAMPAIGN_SUC_MSG');
            $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            redirect($this->viewname); //Redirect On Listing page
        }
       $create_flg = $this->RequestBudget_model->save_campaign_budget_master();
       if($create_flg){
            $product_data['status'] = 1;
            $product_data['created_date'] = datetimeformat();
            $product_data['modified_date'] = datetimeformat();
            //Delete Record in Database
           //$budget_for_product;
            $selected_products = $this->input->post('budget_for_product');
            $nProducts = count($selected_products);
            if($create_flg){
                    $where = array('budget_campaign_id' => $create_flg);
                    $this->common_model->delete(BUDGET_CAMPAIGN_PRODUCT_TRAN, $where);
                     }
            for ($products_count = 0; $products_count < $nProducts; $products_count++) {
                $product_data['product_id'] = $selected_products[$products_count];
                //Insert Record in Database
                
                    $product_data['budget_campaign_id'] = $create_flg;
                    $this->common_model->insert(BUDGET_CAMPAIGN_PRODUCT_TRAN, $product_data);
            }
        }
        if($create_flg)
        {
            $msg = $this->lang->line('BUDGET_CAMPAIGN_SUC_MSG');
            $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            redirect($this->viewname); 
        }else
        {
            $msg = $this->lang->line('BUDGET_CAMPAIGN_SUC_MSG');
            $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            redirect($this->viewname); 
        }
    }
    
    function get_request_data()
    {
        $budget_campaign_id = $_POST['budget_campaign_id'];
        $campaign_data = $this->RequestBudget_model->getBudgetCampiagnMasterData($budget_campaign_id);
        echo json_encode($campaign_data);
    }
    
    function create_campaign()
    {
        $form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
        if($form_secret != "") {
            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            //data for the add campaign pop window
            $data['compaign_master'] = $this->RequestBudget_model->get_compaign_master();

            $data['submit_btn_val'] = $this->lang->line('ADD_REQUEST_BUDGET');

            $data['type_compaign'] = $this->RequestBudget_model->get_type_compaign();
            //$data['employee_list'] = $this->RequestBudget_model->get_employee_list();

            $cm_params = array();
            $cm_params['table'] = CONTACT_MASTER . ' as cm';
            $cm_params['fields'] = array("cm.contact_id,cm.contact_name");
            $cm_params['where_in'] = array("cm.status" => "1", "cm.is_delete" => "0");
            $data['employee_list'] = $this->common_model->get_records_array($cm_params);


            $table = LOGIN . ' as us';
            $where = array("us.is_delete" => 0);
            $fields = array("us.*");
            $data['responsible_employee_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            $pm_params = array();
            $pm_params['table'] = PRODUCT_MASTER . ' as pm';
            $pm_params['fields'] = array("pm.product_id,pm.product_name");
            $pm_params['where_in'] = array("pm.status" => 1, "pm.is_delete" => "0");
            $data['product_list'] = $this->common_model->get_records_array($pm_params);

            $data['supplier_list'] = $this->RequestBudget_model->get_supplier_list();
            $data['campaign_data'] = '';

            $data['sales_view'] = $this->viewname;
            $data['drag'] = true;
            $this->load->view('create_campaign', $data);
        }else{
            exit('No Direct scripts are allowed');
        }
    }
    
    function edit_record($campaign_budget_id)
    {
        $form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
        if($form_secret != "") {
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        //$campaign_budget_id = $this->uri->segment('3');
        //data for the add campaign pop window
        $data['compaign_master'] = $this->RequestBudget_model->get_compaign_master();
        $data['type_compaign'] = $this->RequestBudget_model->get_type_compaign();

        $data['submit_btn_val'] = $this->lang->line('EDIT_REQUEST_BUDGET');

        //$data['employee_list'] = $this->RequestBudget_model->get_employee_list();
        $cm_params=array();
        $cm_params['table'] = CONTACT_MASTER.' as cm';
        $cm_params['fields'] = array("cm.contact_id,cm.contact_name");
        $cm_params['where_in']=array("cm.status" => "1","cm.is_delete" =>"0");
        $data['employee_list']  = $this->common_model->get_records_array($cm_params);

        $table = LOGIN.' as us';
        $where = array("us.is_delete" => 0);
        $fields = array("us.*");
        $data['responsible_employee_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

        $pm_params=array();
        $pm_params['table'] = PRODUCT_MASTER.' as pm';
        $pm_params['fields'] = array("pm.product_id,pm.product_name");
        $pm_params['where_in']=array("pm.status" => 1,"pm.is_delete" => "0");
        $data['product_list']  = $this->common_model->get_records_array($pm_params);

        $data['supplier_list'] = $this->RequestBudget_model->get_supplier_list();
        //$data['campaign_data'] = $this->RequestBudget_model->getBudgetCampiagnMasterData($campaign_budget_id);

        $table = BUDGET_COMPAIGN_MASTER.' as bc';
        $where = array("bc.status" => "0","bc.budget_campaign_id"=>$campaign_budget_id);
        $fields = array("bc.*,ctm.camp_type_name");
        $join_tables   =  array('blzdsk_campaign_type_master as ctm' =>'ctm.camp_type_id = bc.campaign_type_id');
        $data['campaign_data']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);

        //$budget_campaign_id=$campaign_data[0]['budget_campaign_id'];
        $budget_campaign=  $this->input->post('budget_campaign');
        
        $table12 = BUDGET_CAMPAIGN_PRODUCT_TRAN . ' as pt';
        $match12 = "pt.budget_campaign_id = " . $campaign_budget_id;
        $fields12 = array("pt.product_id");

        $product_data = $this->common_model->get_records($table12, $fields12, '', '', $match12);

        $table = REQUEST_BUDGET_FILES.' as cfm';
        $where = array("cfm.budget_campaign_id" => $campaign_budget_id);
        $fields = array("cfm.*");
        $data['image_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        //pr($data['image_data']);exit;
        $data['sales_view'] = $this->viewname;
        $product_id = array();
        if (!empty($product_data)) {
            foreach ($product_data as $product_data) {
                $product_id[] = $product_data['product_id'];
            }
        }


        $table = BUDGET_CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN.' as ct';
        $where = array("ct.status" => "1","budget_campaign_id" => $campaign_budget_id);
        $fields = array("cm.firstname,cm.lastname,cm.login_id,ct.budget_campaign_id,ct.employee_id");
        $join_tables   =  array('blzdsk_login as cm' =>'cm.login_id = ct.employee_id');
        $responsible_info_id      = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);
//pr($responsible_info_id);exit;

        $responsible_name = array();
        if(count($responsible_info_id) > 0){
            foreach($responsible_info_id as $responsible_data){
                $responsible_name[] = $responsible_data['employee_id'];
            }

        }
        $data['responsible_user_data'] 	= $responsible_name;


        $data['drag']=true;
        $data['campaign_product_data'] = $product_id;
        $this->load->view('create_campaign',$data);
        }else{
            exit('No Direct scripts are allowed');
        }

      }

      function display($campaign_budget_id){
          $form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
          if($form_secret != "") {
        //$campaign_budget_id = $this->uri->segment('3');

        //data for the add campaign pop window
        $data['compaign_master'] = $this->RequestBudget_model->get_compaign_master();
        $data['type_compaign'] = $this->RequestBudget_model->get_type_compaign();
        $cm_params=array();
        $cm_params['table'] = CONTACT_MASTER.' as cm';
        $cm_params['fields'] = array("cm.contact_id,cm.contact_name");
        $cm_params['where_in']=array("cm.status" => "1","cm.is_delete" =>"0");
        $data['employee_list']  = $this->common_model->get_records_array($cm_params);

        $pm_params=array();
        $pm_params['table'] = PRODUCT_MASTER.' as pm';
        $pm_params['fields'] = array("pm.product_id,pm.product_name");
        $pm_params['where_in']=array("pm.status" => 1,"pm.is_delete" => "0");
        $data['product_list']  = $this->common_model->get_records_array($pm_params);

        $data['supplier_list'] = $this->RequestBudget_model->get_supplier_list();
        //$data['campaign_data'] = $this->RequestBudget_model->getBudgetCampiagnMasterData($campaign_budget_id);

          $table = BUDGET_COMPAIGN_MASTER.' as bc';
          $where = array("bc.status" => "0","bc.budget_campaign_id"=>$campaign_budget_id);
          $fields = array("bc.*,ctm.camp_type_name");
          $join_tables   =  array('blzdsk_campaign_type_master as ctm' =>'ctm.camp_type_id = bc.campaign_type_id');
          $data['campaign_data']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);

          $table = LOGIN.' as us';
          $where = array("us.is_delete" => 0);
          $fields = array("us.*");
          $data['responsible_employee_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        
        $table12 = BUDGET_CAMPAIGN_PRODUCT_TRAN . ' as pt';
        $match12 = "pt.budget_campaign_id = " . $campaign_budget_id;
        $fields12 = array("pt.product_id");

        $product_data = $this->common_model->get_records($table12, $fields12, '', '', $match12);

        $table = REQUEST_BUDGET_FILES.' as cfm';
        $where = array("cfm.budget_campaign_id" => $campaign_budget_id);
        $fields = array("cfm.*");
        $data['image_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
          //pr($data['image_data']);exit;

          $table = BUDGET_CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN.' as ct';
          $where = array("ct.status" => "1","budget_campaign_id" => $campaign_budget_id);
          $fields = array("cm.firstname,cm.lastname,cm.login_id,ct.budget_campaign_id,ct.employee_id");
          $join_tables   =  array('blzdsk_login as cm' =>'cm.login_id = ct.employee_id');
          $responsible_info_id      = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);
//pr($responsible_info_id);exit;

          $responsible_name = array();
          if(count($responsible_info_id) > 0){
              foreach($responsible_info_id as $responsible_data){
                  $responsible_name[] = $responsible_data['employee_id'];
              }

          }
          $data['responsible_user_data'] 	= $responsible_name;

        
        $product_id = array();
        if (!empty($product_data)) {
            foreach ($product_data as $product_data) {
                $product_id[] = $product_data['product_id'];
            }
        }
        $data['campaign_product_data'] = $product_id;
        $data['display'] = true;
        $this->load->view('View_page',$data);
      }else{
        exit('No Direct scripts are allowed');
      }
      }

    public function upload_file($fileext=''){
        $str = file_get_contents('php://input');
        echo $filename = time().uniqid().".".$fileext;
        file_put_contents($this->config->item('Request_img_url').'/'.$filename,$str);

    }

    function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = REQUEST_BUDGET_FILES . ' as CM';
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
            $image_name     = $this->common_model->get_records(REQUEST_BUDGET_FILES,$fields,'','',$match);

            if(file_exists($this->config->item('Campaign_img_url').$image_name[0]['file_name']))
            {

                unlink($this->config->item('Campaign_img_url').$image_name[0]['file_name']);
            }
            $where = array('file_id' => $id);
            if ($this->common_model->delete(REQUEST_BUDGET_FILES, $where)) {
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
