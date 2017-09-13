<?php
/*
@Author : Ritesh rana
@Desc   : Marketing Campaign Create/Update
@Date   : 13/01/2016
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveCampaign extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation','Session'));
    }
    /*
@Author : Ritesh rana
@Desc   : Common Model Index Page
@Input 	:
@Output	:
@Date   : 12/01/2016
*/

    public function index()
    {

        // $data['js_content'] = '/loadJsFiles';
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('archivecampaign_data');
        }

        $searchsort_session = $this->session->userdata('archivecampaign_data');
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
                $sortfield = 'campaign_id';
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

        $table = CAMPAING_ARCHIVE.' as ct';
        $where = array("ct.status" => "1");
        $fields = array("ct.campaign_id,ct.campaign_name,ct.start_date,ct.end_date,ct.campaign_auto_id,ct.campaign_type_id,ct.responsible_employee_id,ctm.camp_type_name");
        $join_tables   =  array('blzdsk_campaign_type_master as ctm' =>'ctm.camp_type_id = ct.campaign_type_id');

        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((ct.campaign_name LIKE "%'.$searchtext.'%" OR ct.campaign_auto_id LIKE "%'.$searchtext.'%" OR ctm.camp_type_name LIKE "%'.$searchtext.'%" OR ct.start_date LIKE "%'.date("y-m-d", strtotime($searchtext)).'%" OR ct.end_date LIKE "%'.date("y-m-d", strtotime($searchtext)).'%") AND ct.status = "1")';

            $data['campaign_info']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
            //echo $this->db->last_query();exit;
        }
        else
        {

            $data['campaign_info']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
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
        $this->session->set_userdata('archivecampaign_data', $sortsearchpage_data);
        
        $data['sales_view'] = $this->viewname;
        $data['header'] = array('menu_module'=>'crm');
        $data['drag']=true;
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname.'/ajax_list',$data);
        } else {
            //$data['main_content'] = '/SalesCampaign';
            $data['main_content'] = '/'.$this->viewname;

            //$this->parser->parse('layouts/CampaignTemplate', $data);
            $this->parser->parse('layouts/CampaignTemplate', $data);
            // $this->parser->parse('layouts/ProspectTemplate', $data);
        }

    }

    public function edit($id)
    {
        $form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
        if($form_secret != ""){
        /* updated by sanket on 12/03/2016*/

        $data['crnt_view'] = $this->viewname;

        $data['modal_title'] = $this->lang->line('edit_archive_campaign');

        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $ct_params=array();
        $ct_params['table'] = CAMPAIGN_TYPE_MASTER.' as ct';
        $ct_params['fields'] = array("ct.camp_type_id,ct.camp_type_name");
        $ct_params['where_in']=array("ct.status" => "1");
        $data['campaign_type_info']  = $this->common_model->get_records_array($ct_params);

        //Get Client Information
        $table = PROSPECT_MASTER . ' as pro';
        $match = "pro.status_type = 3 AND pro.status = 1";
        $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Company Information
        $table = COMPANY_MASTER . ' as com_mst';
        $match = "com_mst.status = 1";
        $fields = array("com_mst.company_id, com_mst.company_name");
        $data['company_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

        $cm_params=array();
        $cm_params['table'] = CONTACT_MASTER.' as cm';
        $cm_params['fields'] = array("cm.contact_id,cm.contact_name");
        $cm_params['where_in']=array("cm.status" => 1,"cm.is_delete"=>"0");
        $data['contact_info']  = $this->common_model->get_records_array($cm_params);

        $table = LOGIN.' as us';
        $where = array("us.is_delete" => 0);
        $fields = array("us.*");
        $data['responsible_employee_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

        $sm_params=array();
        $sm_params['table'] = SUPPLIER_MASTER.' as sm';
        $sm_params['fields'] = array("sm.supplier_id,sm.supplier_name");
        $sm_params['where_in']=array("sm.status" => "0","sm.is_delete"=>"0");
        $data['supplier_info']  = $this->common_model->get_records_array($sm_params);

        $cg_params=array();
        $cg_params['table'] = CAMPAIGN_GROUP_MASTER.' as cg';
        $cg_params['fields'] = array("cg.campaign_group_id,cg.group_name");
        $cg_params['where_in']=array("cg.status" => "1");
        $data['campaign_group_info']  = $this->common_model->get_records_array($cg_params);

        $pm_params=array();
        $pm_params['table'] = PRODUCT_MASTER.' as pm';
        $pm_params['fields'] = array("pm.product_id,pm.product_name");
        $pm_params['where_in']=array("pm.status" => 1,"pm.is_delete" => "0");
        $data['product_info']  = $this->common_model->get_records_array($pm_params);

        $data['sales_view'] = $this->viewname;

        $table = CAMPAING_ARCHIVE.' as cm';
        $where = array("cm.campaign_id" => $id);
        $fields = array("cm.campaign_id, cm.campaign_name,cm.campaign_description, cm.campaign_auto_id, cm.campaign_type_id,cm.responsible_employee_id,cm.start_date,cm.end_date,cm.budget_requirement,cm.budget_ammount,cm.campaign_supplier,cm.supplier_id,cm.revenue_goal,cm.revenue_amount,cm.related_product,cm.product_id,cm.campaign_group_id,cm.file,cmc.login_id,cmc.firstname,cmc.lastname,ctm.camp_type_name");
        $join_tables   =  array('blzdsk_campaign_receipents_tran as crt' =>'crt.campaign_id = cm.campaign_id','blzdsk_login as cmc' =>'cmc.login_id = crt.contact_id','blzdsk_campaign_type_master as ctm' =>'ctm.camp_type_id = cm.campaign_type_id');

        $data['editRecord']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);



        $table = CAMPAIGN_FILE_MASTER.' as cfm';
        $where = array("cfm.campaign_id" => $id);
        $fields = array("cfm.*");
        $data['image_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
 
        //pr($data['editRecord']);exit;

        $contact_info=array();
        $product_info=array();
        foreach($data['editRecord'] as $row){
            //$contact_info[] = $row['login_id'];
            $product_info[] = $row['product_id'];
        }

        $receip_table 	= CAMPAIGN_RECEIPIENT_TRAN . ' as receipient';
        $receip_match 	= "receipient.campaign_id = " . $id;
        $receip_fields 	= array("receipient.campaign_id, receipient.contact_id, receipient.recipient_type, ");
        $receiptArray 	= $this->common_model->get_records($receip_table, $receip_fields, '', '', $receip_match);
        $RecipientBlnkArray = array();
        $ClientBlnkArra = "";

        if (count($receiptArray) > 0) {
            foreach ($receiptArray as $receipientID) {
                $RecipientBlnkArray[] = $receipientID['recipient_type'] . '_' . $receipientID['contact_id'];
            }
        }
        $data['EstRecipientArray'] 	= $RecipientBlnkArray;



        $table = CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN.' as ct';
        $where = array("ct.status" => "1","campaign_id" => $id);
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




        $data['content_data']=$contact_info;
        $product=$product_info;
        $data['product_data'] = explode(",",$product[0]);

        //pr($data['editRecord']);exit;
        $data['id'] = $id;
        $data['main_content'] = '/Add';
        $data['js_content'] = '/loadJsFiles';
        $this->load->view('/Add',$data);

        //echo json_encode($data['editRecord']);
        } else {
            exit('No Direct scripts are allowed');
        }
    }

    public function view_page($id)
    {
        $form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
        if($form_secret != ""){
        /* updated by sanket on 12/03/2016 */
        //$id = $this->uri->segment('3');
        $data['crnt_view'] = $this->viewname;

        $data['modal_title'] = $this->lang->line('edit_archive_campaign');


        $ct_params=array();
        $ct_params['table'] = CAMPAIGN_TYPE_MASTER.' as ct';
        $ct_params['fields'] = array("ct.camp_type_id,ct.camp_type_name");
        $ct_params['where_in']=array("ct.status" => "1");
        $data['campaign_type_info']  = $this->common_model->get_records_array($ct_params);

        //Get Client Information
        $table = PROSPECT_MASTER . ' as pro';
        $match = "pro.status_type = 3 AND pro.status = 1";
        $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Company Information
        $table = COMPANY_MASTER . ' as com_mst';
        $match = "com_mst.status = 1";
        $fields = array("com_mst.company_id, com_mst.company_name");
        $data['company_info'] = $this->common_model->get_records($table, $fields, '', '', $match);


        $cm_params=array();
        $cm_params['table'] = CONTACT_MASTER.' as cm';
        $cm_params['fields'] = array("cm.contact_id,cm.contact_name");
        $cm_params['where_in']=array("cm.status" => 1,"cm.is_delete"=>"0");
        $data['contact_info']  = $this->common_model->get_records_array($cm_params);

        $table = LOGIN.' as us';
        $where = array("us.is_delete" => 0);
        $fields = array("us.*");
        $data['responsible_employee_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

        $sm_params=array();
        $sm_params['table'] = SUPPLIER_MASTER.' as sm';
        $sm_params['fields'] = array("sm.supplier_id,sm.supplier_name");
        $sm_params['where_in']=array("sm.status" => "0","sm.is_delete"=>"0");
        $data['supplier_info']  = $this->common_model->get_records_array($sm_params);

        $cg_params=array();
        $cg_params['table'] = CAMPAIGN_GROUP_MASTER.' as cg';
        $cg_params['fields'] = array("cg.campaign_group_id,cg.group_name");
        $cg_params['where_in']=array("cg.status" => "1");
        $data['campaign_group_info']  = $this->common_model->get_records_array($cg_params);

        $pm_params=array();
        $pm_params['table'] = PRODUCT_MASTER.' as pm';
        $pm_params['fields'] = array("pm.product_id,pm.product_name");
        $pm_params['where_in']=array("pm.status" => 1,"pm.is_delete" => "0");
        $data['product_info']  = $this->common_model->get_records_array($pm_params);

        $data['sales_view'] = $this->viewname;

        $table = CAMPAING_ARCHIVE.' as cm';
        $where = array("cm.campaign_id" => $id);
        $fields = array("cm.campaign_id, cm.campaign_name,cm.campaign_description, cm.campaign_auto_id, cm.campaign_type_id,cm.responsible_employee_id,cm.start_date,cm.end_date,cm.budget_requirement,cm.budget_ammount,cm.campaign_supplier,cm.supplier_id,cm.revenue_goal,cm.revenue_amount,cm.related_product,cm.product_id,cm.campaign_group_id,cm.file,cmc.login_id,cmc.firstname,cmc.lastname,ctm.camp_type_name");
        $join_tables   =  array('blzdsk_campaign_receipents_tran as crt' =>'crt.campaign_id = cm.campaign_id','blzdsk_login as cmc' =>'cmc.login_id = crt.contact_id','blzdsk_campaign_type_master as ctm' =>'ctm.camp_type_id = cm.campaign_type_id');
        $data['editRecord']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','','',$where);

        $table = CAMPAIGN_FILE_MASTER.' as cfm';
        $where = array("cfm.campaign_id" => $id);
        $fields = array("cfm.*");
        $data['image_data']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

        //pr($data['editRecord']);exit;
        $contact_info=array();
        $product_info=array();
        foreach($data['editRecord'] as $row){
            //$contact_info[] = $row['login_id'];
            $product_info[] = $row['product_id'];
        }

        $receip_table 	= CAMPAIGN_RECEIPIENT_TRAN . ' as receipient';
        $receip_match 	= "receipient.campaign_id = " . $id;
        $receip_fields 	= array("receipient.campaign_id, receipient.contact_id, receipient.recipient_type, ");
        $receiptArray 	= $this->common_model->get_records($receip_table, $receip_fields, '', '', $receip_match);
        $RecipientBlnkArray = array();
        $ClientBlnkArra = "";

        if (count($receiptArray) > 0) {
            foreach ($receiptArray as $receipientID) {
                $RecipientBlnkArray[] = $receipientID['recipient_type'] . '_' . $receipientID['contact_id'];
            }
        }
        $data['EstRecipientArray'] 	= $RecipientBlnkArray;
        //$data['content_data']=$contact_info;

        $table = CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN.' as ct';
        $where = array("ct.status" => "1","campaign_id" => $id);
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


        $product=$product_info;
        $data['product_data'] = explode(",",$product[0]);

        $data['view']= true;
        //pr($data['editRecord']);exit;
        $data['id'] = $id;
        $data['main_content'] = '/View_page';
        $data['js_content'] = '/loadJsFiles';
        $this->load->view('/View_page',$data);
        } else {
            exit('No Direct scripts are allowed');
        }

    }

    public function updatedata()
    {
        $product_data = $this->input->post('product_id');
        $product = implode("," ,$product_data);

        $id = $this->input->post('id');
        $redirect_url = $this->input->post('redirect');
        if (!validateFormSecret()) {
            if ($id) {
            $msg = $this->lang->line('archive_campign_update_msg');
            $this->session->set_flashdata('msg', $msg);
            redirect($redirect_url); //Redirect On Listing page
        }
        }
        $compaigndata['campaign_auto_id'] = $this->input->post('campaign_auto_id');
        $compaigndata['campaign_name']= trim(strip_slashes($this->input->post('campaign_name')));
        $compaigndata['campaign_description']= trim(strip_slashes($this->input->post('campaign_description',FALSE)));
        $compaigndata['revenue_amount']= $this->input->post('revenue_amount');
        $compaigndata['start_date'] = date_format(date_create($this->input->post('start_date')), 'Y-m-d');
        $compaigndata['end_date'] = date_format(date_create($this->input->post('end_date')), 'Y-m-d');
        $budget_requirement = '0';
        //pr($this->input->post('budget_requirement'));exit;
        if($this->input->post('budget_requirement')=='on' || $this->input->post('budget_requirement')=='1')
        {
            $budget_requirement='1';
        }
        
        if (strpos($redirect_url, 'Contact/view') !== false) {
            $sess_array = array('setting_current_tab' => 'Campaign');
            $this->session->set_userdata($sess_array);
        }
        
        $compaigndata['budget_requirement'] = $budget_requirement;
        $compaigndata['budget_ammount']= $this->input->post('budget_ammount');
        $campaign_supplier='0';
        //pr($this->input->post('campaign_supplier'));exit;
        if($this->input->post('campaign_supplier')=='on' || $this->input->post('campaign_supplier')=='1')
        {
            $campaign_supplier='1';
        }
        $compaigndata['campaign_supplier'] = $campaign_supplier;
        //pr($compaigndata['campaign_supplier']);exit;
        $revenue_goal='0';
        if($this->input->post('revenue_goal')=='on' || $this->input->post('revenue_goal')=='1')
        {
            $revenue_goal='1';
        }
        $compaigndata['revenue_goal'] = $revenue_goal;
        $compaigndata['revenue_amount'] = $this->input->post('revenue_amount');
        $related_product='0';
        if($this->input->post('related_product')=='on' || $this->input->post('related_product')=='1')
        {
            $related_product='1';
        }
        $compaigndata['related_product'] = $related_product;
        $compaigndata['supplier_id'] = $this->input->post('supplier_id');

        $compaigndata['product_id'] = $product;

        $compaigndata['campaign_group_id'] = $this->input->post('campaign_group_id');

        $compaigndata['modified_date'] 	= datetimeformat();
        //pr($compaigndata);exit;
        //Update Record in Database

        $campaign_type = $this->input->post('campaign_type_id');
        //Get Branch id From BRANCH_MASTER Table
        $table22 = CAMPAIGN_TYPE_MASTER . ' as bm';
        $match22 = "bm.camp_type_name='" . addslashes($campaign_type) . "' and status=1 ";
        $fields22 = array("bm.camp_type_name, bm.camp_type_id");
        $campaign_type_record = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        if ($campaign_type_record) {
            $compaigndata['campaign_type_id'] = $campaign_type_record[0]['camp_type_id'];
        } else {
            $camp_type_data['camp_type_name'] = $campaign_type;
        }
        if (count($campaign_type_record) == 0) {
            //INSERT Branch
            $camp_type_data['created_date'] = datetimeformat();
            $camp_type_data['modified_date'] = datetimeformat();
            $camp_type_data['status'] = 1;
            $camp_type_data_list = array(
                'camp_type_name' => $camp_type_data['camp_type_name'],
                'created_date' => $camp_type_data['created_date'],
                'modified_date' => $camp_type_data['modified_date'],
                'status' => $camp_type_data['status'],
            );
            $camp_id = $this->common_model->insert(CAMPAIGN_TYPE_MASTER, $camp_type_data_list);
            $compaigndata['campaign_type_id'] = $camp_id;
        }


        $where = array('campaign_id' => $id);
        $success_update = $this->common_model->update(CAMPAING_ARCHIVE, $compaigndata, $where);


        /* image upload code*/
        $file_name=array();
        $file_array1=$this->input->post('file_data');

        $file_name=$_FILES['fileUpload']['name'];
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
                    unset($_FILES['fileUpload']['name'][$key]);
                    unset($_FILES['fileUpload']['type'][$key]);
                    unset($_FILES['fileUpload']['tmp_name'][$key]);
                    unset($_FILES['fileUpload']['error'][$key]);
                    unset($_FILES['fileUpload']['size'][$key]);
                }
            }

        }

        $_FILES['fileUpload']=$arr = array_map('array_values', $_FILES['fileUpload']);
        $data['lead_view'] = $this->viewname;
        $uploadData = uploadImage('fileUpload', campaign_upload_path, $data['lead_view']);
        //pr($uploadData);exit;
        $Marketingfiles = array();
        foreach($uploadData as $dataname){
            $Marketingfiles[] =$dataname['file_name'];
        }
        $marketing_file_str = implode(",",$Marketingfiles);

        $file2 = $this->input->post('fileToUpload');
        if(!(empty($file2))){
            $file_data = implode("," ,$file2);
        }else{
            $file_data = '';
        }
        if(!empty($marketing_file_str) && !empty($file_data)){
            $marketingdata['file'] = $marketing_file_str.','.$file_data;
        }else if(!empty($marketing_file_str)){
            $marketingdata['file'] = $marketing_file_str;
        }else{
            $marketingdata['file'] = $file_data;
        }
        $marketingdata['file_name']=$file_data;
        if ($marketingdata['file_name'] != '') {
            $explodedData = explode(',', $marketingdata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }

    //pr($uploadData);exit;

        $estFIles = array();

        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $est_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $estFIles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'campaign_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }

    if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => campaign_upload_path, 'campaign_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }
        //pr($estFIles);exit;
        if (count($estFIles) > 0) {
            $where = array('campaign_id' => $id);
            if (!$this->common_model->insert_batch(CAMPAIGN_FILE_MASTER, $estFIles)) {
                $this->session->set_flashdata('msg', lang('error'));
                redirect($this->module); //Redirect On Listing page
            }
        }
        $where = array('campaign_id' => $id);
        $this->common_model->delete(CAMPAIGN_RECEIPIENT_TRAN,$where);

        $estRecipientArray = $this->input->post('contact_id');
        if (count($estRecipientArray) > 0) {
            foreach ($estRecipientArray as $estRecipientId) {
                $estRecipientIdArray = explode("_", $estRecipientId);  //Create Array of Recipient
                $campaign_receipents_tran['campaign_id']=$id;
                $campaign_receipents_tran['contact_id']=$estRecipientIdArray[1];
                $campaign_receipents_tran['recipient_type'] = $estRecipientIdArray[0];
                $campaign_receipents_tran['created_date']=datetimeformat();
                $campaign_receipents_tran['modified_date']=datetimeformat();
                $campaign_receipents_tran['status']='1';
                //Insert Client Query
                $this->common_model->insert(CAMPAIGN_RECEIPIENT_TRAN,$campaign_receipents_tran);
            }

            $where_contact = array('campaign_id' => $id);
            $this->common_model->delete(TBL_CAMPAIGN_CONTACT,$where_contact);
            foreach ($estRecipientArray as $estRecipientIdContact) {
                $estContactIdArray = explode("_", $estRecipientIdContact);  //Create Array of Recipient
                $campaign_contact['campaign_id']=$id;
                $campaign_contact['campaign_related_id']=$estContactIdArray[1];
                $campaign_contact['campaign_status'] = 1;
                $campaign_contact['is_delete']=0;
                $campaign_contact['status']='1';
                //Insert Client Query
                $this->common_model->insert(TBL_CAMPAIGN_CONTACT,$campaign_contact);
            }

        }



        $where = array('campaign_id' => $id);
        $this->common_model->delete(CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN,$where);
        $compaigndata_user = $this->input->post('responsible_employee_id');

        if (count($compaigndata_user) > 0) {
            foreach ($compaigndata_user as $compaigndata_user_id) {
                $campaign_responsible_employee['campaign_id']=$id;
                $campaign_responsible_employee['user_id']=$compaigndata_user_id;
                $campaign_responsible_employee['created_date']=datetimeformat();
                $campaign_responsible_employee['modified_date']=datetimeformat();
                $campaign_responsible_employee['status']='1';
                //Insert Client Query
                $this->common_model->insert(CAMPAIGN_RESPONSIBLE_EMPLOYEE_TRAN,$campaign_responsible_employee);

            }
        }

        if ($success_update) {
            $msg = $this->lang->line('archive_campign_update_msg');
            $this->session->set_flashdata('msg', $msg);
        }

        /**
         * SOFT DELETION CODE STARTS MAULIK SUTHAR
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
            $this->common_model->delete(CAMPAIGN_FILE_MASTER, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */

        //redirect($this->viewname);	//Redirect On Listing page
        redirect($redirect_url);  //Redirect On Listing page
    }

    /*
@Author : Ritesh Rana
@Desc   : Marketing campaign Delete Query
@Input 	: Post id from List page
@Output	: Delete data from database and redirect
@Date   : 18/01/2016
*/

    public function deletedata()
    {
        $id  = $this->input->post('single_remove_id');
        if(!empty($id))
        {
            $compaigndata['status']= 0;
            $trans_where = array('campaign_id' => $id);
            $this->common_model->update(CAMPAIGN_RECEIPIENT_TRAN, $compaigndata, $trans_where);

            $where = array('campaign_id' => $id);
            $success_delete = $this->common_model->update(CAMPAING_ARCHIVE, $compaigndata, $where);

            unset($id);
        }
        $delete_all_flag = 0;$cnt = 0;
        //pagingation
        $searchsort_session = $this->session->userdata('archivecampaign_data');
        //pr($searchsort_session);exit;
        if(!empty($searchsort_session['uri_segment']))
            $pagingid = $searchsort_session['uri_segment'];
        else
        $pagingid = 0;
        $perpage = !empty($searchsort_session['perpage'])?$searchsort_session['perpage']:'10';
        $total_rows = $searchsort_session['total_rows'];
        if($delete_all_flag == 1)
        {
            $total_rows -= $cnt;
            $pagingid*$perpage;
            if($pagingid*$perpage > $total_rows) {
                if($total_rows % $perpage == 0) // if all record delete
                {
                    $pagingid -= $perpage;
                }
            }
        } else {
            if($total_rows % $perpage == 1)
                $pagingid -= $perpage;
        }

        if($pagingid < 0)
            $pagingid = 0;
        echo $pagingid;

    }
    public function upload_file($fileext=''){
        $str = file_get_contents('php://input');
        echo $filename = time().uniqid().".".$fileext;
        file_put_contents($this->config->item('Campaign_img_url').'/'.$filename,$str);
    }

    /*
        @Author : Sanket Jayani
        @Desc   : Get facebook page count
        @Input 	: facebook page URL
        @Output	: Page like Count
        @Date   : 29/02/2016
    */

    function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = CAMPAIGN_FILE_MASTER . ' as CM';
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
            $image_name     = $this->common_model->get_records(CAMPAIGN_FILE_MASTER,$fields,'','',$match);

            if(file_exists($this->config->item('Campaign_img_url').$image_name[0]['file_name']))
            {

                unlink($this->config->item('Campaign_img_url').$image_name[0]['file_name']);
            }
            $where = array('file_id' => $id);
            if ($this->common_model->delete(CAMPAIGN_FILE_MASTER, $where)) {
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

