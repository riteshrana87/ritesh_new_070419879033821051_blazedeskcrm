<?php
/*
@Author : Madhuri Chotaliya
@Desc   : Campaign Group Create/Update
@Date   : 21/01/2016
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Campaigngroup extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->prefix = $this->db->dbprefix;
	    $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation','Session'));
        $this->user_info = $this->session->userdata ('LOGGED_IN');
    }

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
            $this->session->unset_userdata('campaign_group_data');
        }

        $searchsort_session = $this->session->userdata('campaign_group_data');
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
                $sortfield = 'campaign_group_id';
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

        $table            = CAMPAIGN_GROUP_MASTER.' as cg';
        $fields = array("cg.campaign_group_id,cg.group_name");
        $where = array("cg.status" => "1");
        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((cg.campaign_group_id LIKE "%'.$searchtext.'%" OR cg.group_name LIKE "%'.$searchtext.'%") AND cg.status = "1")';
            $data['campaign_group_info']  = $this->common_model->get_records($table,$fields,'','','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,'','','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
        }
        else
        {
        $data['campaign_group_info']      = $this->common_model->get_records($table,$fields,'','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,'','','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
//pr($data['campaign_group_info']);exit;
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
        $this->session->set_userdata('campaign_group_data', $sortsearchpage_data);

        $data['group_view'] = $this->viewname;
        $data['header'] = array('menu_module'=>'crm');
        //pr($data['pagination']);exit;
        $data['drag']=true;
        //$data['popup'] = $this->load->view($this->viewname.'/Add',$data);
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname.'/ajax_list',$data);
        } else {
            $data['main_content'] = '/'.$this->viewname;
            //$this->parser->parse('layouts/DashboardTemplate', $data);
            $this->parser->parse('layouts/CampaignTemplate', $data);
        }

    }
    public function add_record() {
        $form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
        if($form_secret != "") {
            $data['modal_title'] = $this->lang->line('CREATE_CAMPAIGN_GROUP');
            // $this->lang->line('UPDATE_CAMPAIGN_GROUP');

            $cg_param = array();
            $cg_param['table'] = CAMPAIGN_GROUP_MASTER . ' as cg';
            $cg_param['fields'] = array("cg.campaign_group_id,cg.group_name");
            $cg_param['where_in'] = array("cg.status" => "1");
            $data['campaign_group_info'] = $this->common_model->get_records_array($cg_param);

            //total contacts count: Prospect: start
            $fieldsConPros = array("oppo_req_con.prospect_id, COUNT(oppo_req_con.requirement_contacts_id) AS 'pros_contacts'");
            $tableConPros = PROSPECT_MASTER . " AS pros_mstr";
            $paramsConPros['join_tables'] = array(OPPORTUNITY_REQUIREMENT_CONTACTS . " AS oppo_req_con" => "pros_mstr.prospect_id = oppo_req_con.prospect_id",
                CONTACT_MASTER . " AS con_mstr" => "con_mstr.contact_id = oppo_req_con.contact_id"
            );

            $whereConPros = array("pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "oppo_req_con.status" => 1, "con_mstr.status" => 1, "con_mstr.is_delete" => 0);
            $groupByConPros = "pros_mstr.prospect_id";
            $data['prospect_contact_info'] = $this->common_model->get_records($tableConPros, $fieldsConPros, $paramsConPros['join_tables'], "left", $whereConPros, "", "", "", "", "", $groupByConPros);
            //total contacts count: Prospect: end

            $table = LOGIN . ' as us';
            $where = array("us.is_delete" => 0);
            $fields = array("us.*");
            $data['employee_owner'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            //pr($this->db->last_query());exit;
            $pm_param = array();
            $pm_param['table'] = PRODUCT_MASTER . ' as pm';
            $pm_param['fields'] = array("pm.product_id,pm.product_name");
            $pm_param['where_in'] = array("pm.status" => 1, "is_delete" => "0");
            $data['product_info'] = $this->common_model->get_records_array($pm_param);

            $br_param = array();
            $br_param['table'] = BRANCH_MASTER . ' as br';
            $br_param['fields'] = array("br.branch_id,br.branch_name");
            $br_param['where_in'] = array("br.status" => "1");
            $data['branch_info'] = $this->common_model->get_records_array($br_param);

            $ca_param = array();
            $ca_param['table'] = CAMPAIGN_MASTER . ' as ca';
            $ca_param['fields'] = array("ca.campaign_id,ca.campaign_name");
            $ca_param['where_in'] = array("ca.status" => "1");
            $data['campaign_info'] = $this->common_model->get_records_array($ca_param);

            $comp_param = array();
            $comp_param['table'] = COMPANY_MASTER . ' as comp';
            $comp_param['fields'] = array("comp.company_id,comp.company_name");
            $comp_param['where_in'] = array("comp.status" => 1, "comp.is_delete" => 0);
            $data['campany_info'] = $this->common_model->get_records_array($comp_param);

            /*  $p_param=array();
              $p_param['table'] = PROSPECT_MASTER.' as p';
              $p_param['fields'] = array("p.prospect_id,p.company_id,p.branch_id,p.status_type");
              $p_param['where']=array("p.status" => "1");
              $data['prospect_info']  = $this->common_model->get_records_array($p_param);
      */

            $p_param_table = PROSPECT_MASTER . ' as p';
            $p_param_where = array("p.status" => 1, "p.is_delete" => 0);
            $pro_wherein = array("p.status_type" => array(1, 3));
            $p_param_fields = array("p.prospect_id,p.company_id,p.branch_id,p.status_type,cm.company_name");
            $p_param_join_tables = array('blzdsk_company_master as cm' => 'cm.company_id = p.company_id');

            $data['prospect_info'] = $this->common_model->get_records($p_param_table, $p_param_fields, $p_param_join_tables, 'left', '', '', '', '', '', '', '', $p_param_where, '', $pro_wherein);

            $p_param_table = LEAD_MASTER . ' as p';
            $p_param_where = array("p.status" => 1, "p.is_delete" => 0);
            $p_param_fields = array("p.lead_id,p.company_id,p.branch_id,p.status_type,cm.company_name");
            $p_param_join_tables = array('blzdsk_company_master as cm' => 'cm.company_id = p.company_id');

            $data['lead_info'] = $this->common_model->get_records($p_param_table, $p_param_fields, $p_param_join_tables, 'left', '', '', '', '', '', '', '', $p_param_where);


//        $pc_param=array();
//        $pc_param['table'] = LEAD_CONTACTS_TRAN.' as pc';
//        $pc_param['fields'] = array("pc.contact_id as pro_contact_id,pc.lead_id");
//        $pc_param['where_in']=array("pc.status" => 1);
//        $data['lead_contact_info']  = $this->common_model->get_records_array($pc_param);

            //total contacts count: Lead :start
            $tableLead = LEAD_MASTER . " AS lead_mstr";
            $paramsConLead['join_tables'] = array(LEAD_CONTACTS_TRAN . " AS lead_con_tran" => "lead_mstr.lead_id = lead_con_tran.lead_id",
                CONTACT_MASTER . " AS con_mstr" => "con_mstr.contact_id = lead_con_tran.contact_id"
            );
            $fieldsConLead = array("lead_con_tran.lead_id, COUNT(lead_con_tran.pro_contact_id) AS 'lead_contacts'");
            $groupByConLead = "lead_mstr.lead_id";
            $whereConLead = array("lead_mstr.is_delete" => 0, "lead_mstr.status" => 1, "lead_con_tran.status" => 1, "con_mstr.status" => 1, "con_mstr.is_delete" => 0);

            $data['lead_contact_info'] = $this->common_model->get_records($tableLead, $fieldsConLead, $paramsConLead['join_tables'], "left", $whereConLead, "", "", "", "", "", $groupByConLead);
            //total contacts count: Lead :end

            $pp_param = array();
            $pp_param['table'] = LEAD_PRODUCTS_TRAN . ' as pp';
            $pp_param['fields'] = array("pp.lead_id,GROUP_CONCAT(pm.product_name) AS 'lead_products'");
            $pp_param['join_tables'] = array(PRODUCT_MASTER . " AS pm" => "pm.product_id = pp.product_id");
            $pp_param['join_type'] = "right";
            $pp_param['group_by'] = array("pp.lead_id");
            $pp_param['where_in'] = array("pp.status" => 1, "pm.status" => 1, "pm.is_delete" => "0");
            $data['lead_product_info'] = $this->common_model->get_records_array($pp_param);

            /*$pc_param=array();
            $pc_param['table'] = PROSPECT_CONTACTS_TRAN.' as pc';
            $pc_param['fields'] = array("pc.pro_contact_id,pc.prospect_id");
            $pc_param['where_in']=array("pc.status" => 1);
            $data['prospect_contact_info']  = $this->common_model->get_records_array($pc_param);
    */
//        $pc_param=array();
//        $pc_param['table'] = OPPORTUNITY_REQUIREMENT_CONTACTS.' as pc';
//        $pc_param['fields'] = array("pc.contact_id as pro_contact_id,pc.prospect_id");
//        $pc_param['where_in']=array("pc.status" => 1);
//        $data['prospect_contact_info']  = $this->common_model->get_records_array($pc_param);


            $pp_param = array();
            $pp_param['table'] = PROSPECT_PRODUCTS_TRAN . ' as pp';
            $pp_param['fields'] = array("pp.prospect_id,GROUP_CONCAT(pm.product_name) AS 'pros_products'");
            $pp_param['join_tables'] = array(PRODUCT_MASTER . " AS pm" => "pm.product_id = pp.product_id");
            $pp_param['join_type'] = "right";
            $pp_param['group_by'] = array("pp.prospect_id");
            $pp_param['where_in'] = array("pp.status" => 1, "pm.status" => 1, "is_delete" => "0");
            $data['prospect_product_info'] = $this->common_model->get_records_array($pp_param);


            $data['sales_view'] = $this->viewname;
            $data['drag'] = true;
            $data['main_content'] = '/Add';
            $data['js_content'] = '/loadJsFiles';
            $this->load->view('/Add', $data);
        }else{
            exit('No Direct scripts are allowed');
        }
    }

    public function edit($id)
    {
        $form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
        if($form_secret != "") {
        $data['crnt_view'] = $this->viewname;
        $data['modal_title'] = $this->lang->line('UPDATE_CAMPAIGN_GROUP');

        $cg_param=array();
        $cg_param['table'] = CAMPAIGN_GROUP_MASTER.' as cg';
        $cg_param['fields'] = array("cg.campaign_group_id,cg.group_name, cg.emp_owner_id, cg.previous_campaign_id");
        $cg_param['where_in']=array("cg.status" => "1", "cg.campaign_group_id"=> $id);
        $data['campaign_group_info']  = $this->common_model->get_records_array($cg_param);
//        pr($data['campaign_group_info'][0]['emp_owner_id']);
//        pr($data['campaign_group_info'][0]['previous_campaign_id']);

        $cm_param=array();
        $cm_param['table'] = CONTACT_MASTER.' as cm';
        $cm_param['fields'] = array("cm.contact_id,cm.contact_name");
        $cm_param['where_in']=array("cm.status" => 1,"cm.is_delete"=>"0");

        $data['contact_info']  = $this->common_model->get_records_array($cm_param);

        $table = LOGIN.' as us';
        $where = array("us.is_delete" => 0);
        $fields = array("us.*");
        $data['employee_owner']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        //pr($this->db->last_query());exit;
        $pm_param=array();
        $pm_param['table'] = PRODUCT_MASTER.' as pm';
        $pm_param['fields'] = array("pm.product_id,pm.product_name");
        $pm_param['where_in']=array("pm.status" => 1,"is_delete" => "0");
        $data['product_info']  = $this->common_model->get_records_array($pm_param);


        $br_param=array();
        $br_param['table'] = BRANCH_MASTER.' as br';
        $br_param['fields'] = array("br.branch_id,br.branch_name");
        $br_param['where_in']=array("br.status" => "1");
        $data['branch_info']  = $this->common_model->get_records_array($br_param);

        $table22 = BRANCH_MASTER . ' as bm';
        $match22 = "bm.branch_id=(SELECT pm.branch_id from " . $this->prefix . CAMPAIGN_GROUP_MASTER . " as pm WHERE pm.campaign_group_id = " . $id . ") and bm.status=1";
        $fields22 = array("bm.branch_id,bm.branch_name");
        $data['branch_info1'] = $this->common_model->get_records($table22, $fields22, '', '', $match22);

        $ca_param=array();
        $ca_param['table'] = CAMPAIGN_MASTER.' as ca';
        $ca_param['fields'] = array("ca.campaign_id,ca.campaign_name");
        $ca_param['where_in']=array("ca.status" => "1");
        $data['campaign_info']  = $this->common_model->get_records_array($ca_param);

        $comp_param=array();
        $comp_param['table'] = COMPANY_MASTER.' as comp';
        $comp_param['fields'] = array("comp.company_id,comp.company_name");
        $comp_param['where_in']=array("comp.status" => 1,"comp.is_delete"=>0);
        $data['campany_info'] = $this->common_model->get_records_array($comp_param);

        $p_param_table = PROSPECT_MASTER.' as p';
        //$p_param_where =array("p.status" => 1,"p.is_delete"=>0, "p.created_by"=> $data['campaign_group_info'][0]['emp_owner_id']);
        if($data['campaign_group_info'][0]['previous_campaign_id'] != 0){
            $p_param_where = "p.status = 1 AND p.is_delete = 0 AND p.created_by = ".$data['campaign_group_info'][0]['emp_owner_id']." AND p.campaign_id = ".$data['campaign_group_info'][0]['previous_campaign_id']." AND (p.status_type = 1 OR p.status_type = 3)";
        }else{
            $p_param_where = "p.status = 1 AND p.is_delete = 0 AND p.created_by = ".$data['campaign_group_info'][0]['emp_owner_id']." AND (p.status_type = 1 OR p.status_type = 3)";
        }
        //$p_wherein= array("p.status_type"=>array(1,3));
        $p_param_fields = array("p.prospect_id,p.company_id,p.branch_id,p.status_type,cm.company_name");
        $p_param_join_tables   =  array(COMPANY_MASTER.' as cm' =>'cm.company_id = p.company_id');

        $data['prospect_info']      = $this->common_model->get_records($p_param_table,$p_param_fields,$p_param_join_tables,'left','','','','','','','',$p_param_where,'','');

        $p_param_table = LEAD_MASTER.' as p';
        if($data['campaign_group_info'][0]['previous_campaign_id'] != 0){
            $p_param_where =array("p.status" => 1,"p.is_delete"=>0, "p.created_by"=> $data['campaign_group_info'][0]['emp_owner_id'], "p.status_type"=> 2, "p.campaign_id"=> $data['campaign_group_info'][0]['previous_campaign_id']);
        }
        else{
            $p_param_where =array("p.status" => 1,"p.is_delete"=>0, "p.created_by"=> $data['campaign_group_info'][0]['emp_owner_id'], "p.status_type"=> 2);
        }
        $p_param_fields = array("p.lead_id,p.company_id,p.branch_id,p.status_type,cm.company_name");
        $p_param_join_tables   =  array('blzdsk_company_master as cm' =>'cm.company_id = p.company_id');

        $data['lead_info']      = $this->common_model->get_records($p_param_table,$p_param_fields,$p_param_join_tables,'left','','','','','','','',$p_param_where);

//        $pc_param=array();
//        $pc_param['table'] = LEAD_CONTACTS_TRAN.' as pc';
//        $pc_param['fields'] = array("pc.contact_id as pro_contact_id,pc.lead_id");
//        $pc_param['where_in']=array("pc.status" => 1);
//        $data['lead_contact_info']  = $this->common_model->get_records_array($pc_param);
        
        //total contacts count: Lead :start
        $tableLead = LEAD_MASTER. " AS lead_mstr";
        $paramsConLead['join_tables'] = array(LEAD_CONTACTS_TRAN." AS lead_con_tran" => "lead_mstr.lead_id = lead_con_tran.lead_id",
                                            CONTACT_MASTER. " AS con_mstr" => "con_mstr.contact_id = lead_con_tran.contact_id"
            );
        $fieldsConLead = array("lead_con_tran.lead_id, COUNT(lead_con_tran.pro_contact_id) AS 'lead_contacts'");
        $groupByConLead = "lead_mstr.lead_id";
            $whereConLead = array("lead_mstr.is_delete" => 0, "lead_mstr.status" => 1, "lead_con_tran.status" => 1, "con_mstr.status" => 1, "con_mstr.is_delete"=> 0);
            
        $data['lead_contact_info']  = $this->common_model->get_records($tableLead, $fieldsConLead, $paramsConLead['join_tables'], "left", $whereConLead, "", "", "", "", "", $groupByConLead);
        //total contacts count: Lead :end

        $pp_param=array();
        $pp_param['table'] = LEAD_PRODUCTS_TRAN.' as pp';
        $pp_param['fields'] = array("pp.lead_id,GROUP_CONCAT(pm.product_name) AS 'lead_products'");
        $pp_param['join_tables'] = array(PRODUCT_MASTER. " AS pm" => "pm.product_id = pp.product_id");
        $pp_param['join_type'] = "right";
        $pp_param['group_by']=array("pp.lead_id");
        $pp_param['where_in']=array("pp.status" => 1, "pm.status" => 1, "pm.is_delete" => "0");
        $data['lead_product_info']  = $this->common_model->get_records_array($pp_param);

//        $pc_param=array();
//        $pc_param['table'] = OPPORTUNITY_REQUIREMENT_CONTACTS.' as pc';
//        $pc_param['fields'] = array("pc.contact_id as pro_contact_id,pc.prospect_id");
//        $pc_param['where_in']=array("pc.status" => 1);
//        $data['prospect_contact_info']  = $this->common_model->get_records_array($pc_param);
        
        //total contacts count: Prospect: start
        $fieldsConPros = array("oppo_req_con.prospect_id, COUNT(oppo_req_con.requirement_contacts_id) AS 'pros_contacts'");
        $tableConPros = PROSPECT_MASTER . " AS pros_mstr";
        $paramsConPros['join_tables'] = array(OPPORTUNITY_REQUIREMENT_CONTACTS. " AS oppo_req_con" => "pros_mstr.prospect_id = oppo_req_con.prospect_id",
                                            CONTACT_MASTER. " AS con_mstr" => "con_mstr.contact_id = oppo_req_con.contact_id"
            );
        
        $whereConPros = array("pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "oppo_req_con.status" => 1, "con_mstr.status"=> 1, "con_mstr.is_delete"=> 0);
        $groupByConPros = "pros_mstr.prospect_id";
        $data['prospect_contact_info'] = $this->common_model->get_records($tableConPros, $fieldsConPros, $paramsConPros['join_tables'], "left", $whereConPros, "", "", "", "", "", $groupByConPros);
        //total contacts count: Prospect: end

        $pp_param=array();
        $pp_param['table'] = PROSPECT_PRODUCTS_TRAN.' as pp';
        $pp_param['fields'] = array("pp.prospect_id,GROUP_CONCAT(pm.product_name) AS 'pros_products'");
        $pp_param['join_tables'] = array(PRODUCT_MASTER. " AS pm" => "pm.product_id = pp.product_id");
        $pp_param['join_type'] = "right";
        $pp_param['group_by']=array("pp.prospect_id");
        $pp_param['where_in']=array("pp.status" => "1", "pm.status" => 1, "pm.is_delete" => "0");
        $data['prospect_product_info']  = $this->common_model->get_records_array($pp_param);
//        $pp_param=array();
//        $pp_param['table'] = LEAD_PRODUCTS_TRAN.' as pp';
//        $pp_param['fields'] = array("pp.lead_id,GROUP_CONCAT(pm.product_name) AS 'lead_products'");
//        $pp_param['join_tables'] = array(PRODUCT_MASTER. " AS pm" => "pm.product_id = pp.product_id");
//        $pp_param['join_type'] = "right";
//        $pp_param['group_by']=array("pp.lead_id");
//        $pp_param['where_in']=array("pp.status" => 1, "pm.status" => 1, "pm.is_delete" => "0");
//        $data['lead_product_info']  = $this->common_model->get_records_array($pp_param);


        $sales_table = CAMPAIGN_GROUP_SALES_MASTER.' as s';
        $sales_fields = array("s.prospect_id","s.status_type");
        $sales_where =array("s.campaign_group_id" => $id,"s.status"=>'1');

        $data['group_sales']      = $this->common_model->get_records($sales_table,$sales_fields,'','left','','','','','','','',$sales_where);

        $group_sales_info=array();
        $lead_sales_info=array();
        $status = array(1,3);
        foreach($data['group_sales'] as $group_sales){
            if (!empty($group_sales) && in_array($group_sales['status_type'],$status)) {
                $group_sales_info[] = $group_sales['prospect_id'];
        }else{
                $lead_sales_info[] = $group_sales['prospect_id'];
            }
        }
        $data['group_sales_info']=$group_sales_info;
        $data['lead_sales_info']=$lead_sales_info;

        //Get Records From CAMPAIGN_MASTER Table
        $table= CAMPAIGN_GROUP_MASTER.' as cm';
        $where=array("cm.campaign_group_id" => $id);
        $fields = array("cm.campaign_group_id, cm.group_name,cm.group_owner_id, cm.group_description, cm.branch_id,cm.product_id,cm.status_id,cm.value_start,cm.value_end,cm.emp_owner_id,cm.previous_campaign_id,cm.related_campaign,cm.campaign_id");
   $data['editRecord'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

        //pr($data['editRecord']);exit;

        $data['id'] = $id;
        $data['sales_view'] = $this->viewname;
        $data['drag']=true;
        $data['id'] = $id;
        $data['main_content'] = '/Add';
        $data['js_content'] = '/loadJsFiles';
        $this->load->view('/Add',$data);
        //echo json_encode($data['editRecord']);
        }else{
            exit('No Direct scripts are allowed');
        }
    }

    public function view_page($id)
    {
        $form_secret = ($this->input->get('token')) ? $this->input->get('token') : '';
        if($form_secret != "") {
        $data['crnt_view'] = $this->viewname;
        $data['modal_title'] = $this->lang->line('UPDATE_CAMPAIGN_GROUP');

        $cg_param=array();
        $cg_param['table'] = CAMPAIGN_GROUP_MASTER.' as cg';
        $cg_param['fields'] = array("cg.campaign_group_id,cg.group_name");
        $cg_param['where_in']=array("cg.status" => "1");
        $data['campaign_group_info']  = $this->common_model->get_records_array($cg_param);

        $cm_param=array();
        $cm_param['table'] = CONTACT_MASTER.' as cm';
        $cm_param['fields'] = array("cm.contact_id,cm.contact_name");
        $cm_param['where_in']=array("cm.status" => 1,"cm.is_delete"=>"0");

        $data['contact_info']  = $this->common_model->get_records_array($cm_param);

        $table = LOGIN.' as us';
        $where = array("us.is_delete" => 0);
        $fields = array("us.*");
        $data['employee_owner']  = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        //pr($this->db->last_query());exit;
        $pm_param=array();
        $pm_param['table'] = PRODUCT_MASTER.' as pm';
        $pm_param['fields'] = array("pm.product_id,pm.product_name");
        $pm_param['where_in']=array("pm.status" => 1,"is_delete" => "0");
        $data['product_info']  = $this->common_model->get_records_array($pm_param);


        $br_param=array();
        $br_param['table'] = BRANCH_MASTER.' as br';
        $br_param['fields'] = array("br.branch_id,br.branch_name");
        $br_param['where_in']=array("br.status" => "1");
        $data['branch_info']  = $this->common_model->get_records_array($br_param);

        $table22 = BRANCH_MASTER . ' as bm';
        $match22 = "bm.branch_id=(SELECT pm.branch_id from " . $this->prefix . CAMPAIGN_GROUP_MASTER . " as pm WHERE pm.campaign_group_id = " . $id . ") and bm.status=1";
        $fields22 = array("bm.branch_id,bm.branch_name");
        $data['branch_info1'] = $this->common_model->get_records($table22, $fields22, '', '', $match22);

        $ca_param=array();
        $ca_param['table'] = CAMPAIGN_MASTER.' as ca';
        $ca_param['fields'] = array("ca.campaign_id,ca.campaign_name");
        $ca_param['where_in']=array("ca.status" => "1");
        $data['campaign_info']  = $this->common_model->get_records_array($ca_param);

        $comp_param=array();
        $comp_param['table'] = COMPANY_MASTER.' as comp';
        $comp_param['fields'] = array("comp.company_id,comp.company_name");
        $comp_param['where_in']=array("comp.status" => 1,"comp.is_delete"=>0);
        $data['campany_info'] = $this->common_model->get_records_array($comp_param);

        $p_param_table = PROSPECT_MASTER.' as p';
        $wherein= array("p.status_type"=>array(1,3));
        $p_param_where =array("p.status" => 1,"p.is_delete"=>0);

        $p_param_fields = array("p.prospect_id,p.company_id,p.branch_id,p.status_type,cm.company_name");
        $p_param_join_tables   =  array('blzdsk_company_master as cm' =>'cm.company_id = p.company_id');

        $data['prospect_info']      = $this->common_model->get_records($p_param_table,$p_param_fields,$p_param_join_tables,'left','','','','','','','',$p_param_where,'',$wherein);

        /*$pc_param=array();
        $pc_param['table'] = PROSPECT_CONTACTS_TRAN.' as pc';
        $pc_param['fields'] = array("pc.pro_contact_id,pc.prospect_id");
        $pc_param['where_in']=array("pc.status" => "1");
        $data['prospect_contact_info']  = $this->common_model->get_records_array($pc_param);
*/
        $p_param_table = LEAD_MASTER.' as p';
        $p_param_where =array("p.status" => 1,"p.is_delete"=>0);
        $p_param_fields = array("p.lead_id,p.company_id,p.branch_id,p.status_type,cm.company_name");
        $p_param_join_tables   =  array('blzdsk_company_master as cm' =>'cm.company_id = p.company_id');

        $data['lead_info']      = $this->common_model->get_records($p_param_table,$p_param_fields,$p_param_join_tables,'left','','','','','','','',$p_param_where);

        $pc_param=array();
        $pc_param['table'] = LEAD_CONTACTS_TRAN.' as pc';
        $pc_param['fields'] = array("pc.contact_id as pro_contact_id,pc.lead_id");
        $pc_param['where_in']=array("pc.status" => 1);
        $data['lead_contact_info']  = $this->common_model->get_records_array($pc_param);

//        $pp_param=array();
//        $pp_param['table'] = LEAD_PRODUCTS_TRAN.' as pp';
//        $pp_param['fields'] = array("pp.pro_product_id,pp.lead_id,pp.product_id");
//        $pp_param['where_in']=array("pp.status" => 1);
//        $data['lead_product_info']  = $this->common_model->get_records_array($pp_param);
        $pp_param=array();
        $pp_param['table'] = LEAD_PRODUCTS_TRAN.' as pp';
        $pp_param['fields'] = array("pp.lead_id,GROUP_CONCAT(pm.product_name) AS 'lead_products'");
        $pp_param['join_tables'] = array(PRODUCT_MASTER. " AS pm" => "pm.product_id = pp.product_id");
        $pp_param['join_type'] = "right";
        $pp_param['group_by']=array("pp.lead_id");
        $pp_param['where_in']=array("pp.status" => 1, "pm.status" => 1, "pm.is_delete" => "0");
        $data['lead_product_info']  = $this->common_model->get_records_array($pp_param);


        $pc_param=array();
        $pc_param['table'] = OPPORTUNITY_REQUIREMENT_CONTACTS.' as pc';
        $pc_param['fields'] = array("pc.contact_id as pro_contact_id,pc.prospect_id");
        $pc_param['where_in']=array("pc.status" => 1);
        $data['prospect_contact_info']  = $this->common_model->get_records_array($pc_param);

//        $pp_param=array();
//        $pp_param['table'] = PROSPECT_PRODUCTS_TRAN.' as pp';
//        $pp_param['fields'] = array("pp.pro_product_id,pp.prospect_id,pp.product_id");
//        $pp_param['where_in']=array("pp.status" => "1");
//        $data['prospect_product_info']  = $this->common_model->get_records_array($pp_param);
        $pp_param=array();
        $pp_param['table'] = PROSPECT_PRODUCTS_TRAN.' as pp';
        $pp_param['fields'] = array("pp.prospect_id,GROUP_CONCAT(pm.product_name) AS 'pros_products'");
        $pp_param['join_tables'] = array(PRODUCT_MASTER. " AS pm" => "pm.product_id = pp.product_id");
        $pp_param['join_type'] = "right";
        $pp_param['group_by']=array("pp.prospect_id");
        $pp_param['where_in']=array("pp.status" => 1, "pm.status" => 1, "is_delete" => "0");
        $data['prospect_product_info']  = $this->common_model->get_records_array($pp_param);


        $sales_table = CAMPAIGN_GROUP_SALES_MASTER.' as s';
        $sales_fields = array("s.prospect_id","s.status_type");
        $sales_where =array("s.campaign_group_id" => $id);

        $data['group_sales']      = $this->common_model->get_records($sales_table,$sales_fields,'','left','','','','','','','',$sales_where);



        $group_sales_info=array();
        $lead_sales_info=array();
        $status = array(1,3);
        foreach($data['group_sales'] as $group_sales){
            if (!empty($group_sales) && in_array($group_sales['status_type'],$status)) {
                $group_sales_info[] = $group_sales['prospect_id'];
            }else{
                $lead_sales_info[] = $group_sales['prospect_id'];
            }
        }
        $data['group_sales_info']=$group_sales_info;
        $data['lead_sales_info']=$lead_sales_info;
        //Get Records From CAMPAIGN_MASTER Table
        $table= CAMPAIGN_GROUP_MASTER.' as cm';
        $where=array("cm.campaign_group_id" => $id);
        $fields = array("cm.campaign_group_id, cm.group_name,cm.group_owner_id, cm.group_description, cm.branch_id,cm.product_id,cm.status_id,cm.value_start,cm.value_end,cm.emp_owner_id,cm.previous_campaign_id,cm.related_campaign,cm.campaign_id");
        $data['editRecord'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

        //pr($data['editRecord']);exit;

        $data['id'] = $id;
        $data['sales_view'] = $this->viewname;

        $data['view'] = true;
        $data['drag']=true;
        $data['id'] = $id;
        $data['main_content'] = '/View_page';
        $data['js_content'] = '/loadJsFiles';
        $this->load->view('/View_page',$data);
        //echo json_encode($data['editRecord']);
    }else{
        exit('No Direct scripts are allowed');
    }
    }


    public function index_data()
    {
	    $data['main_content'] = '/Campaigngroup';
        $data['group_view'] = $this->viewname;
        
       /* $param=array();
        $param['table'] = CAMPAIGN_TYPE_MASTER.' as ct';
        $param['fields'] = array("ct.camp_type_id,ct.camp_type_name");
        $param['where_in']=array("ct.status" => "1");
	$data['campaign_type_info']  = $this->common_model->get_records_array($param);
        */

       $cg_param=array();
        $cg_param['table'] = CAMPAIGN_GROUP_MASTER.' as cg';
	$cg_param['fields'] = array("cg.campaign_group_id,cg.group_name");
        $cg_param['where_in']=array("cg.status" => "1");
        $data['campaign_group_info']  = $this->common_model->get_records_array($cg_param);


        $cm_param=array();
        $cm_param['table'] = CONTACT_MASTER.' as cm';
	$cm_param['fields'] = array("cm.contact_id,cm.contact_name");
        $cm_param['where_in']=array("cm.status" => 1,"cm.is_delete"=>"0");
	$data['contact_info']  = $this->common_model->get_records_array($cm_param);
        //pr($this->db->last_query());exit;
        $pm_param=array();
        $pm_param['table'] = PRODUCT_MASTER.' as pm';
        $pm_param['fields'] = array("pm.product_id,pm.product_name");
        $pm_param['where_in']=array("pm.status" => 1,"is_delete" => "0");
	$data['product_info']  = $this->common_model->get_records_array($pm_param);
        
        $br_param=array();
        $br_param['table'] = BRANCH_MASTER.' as br';
        $br_param['fields'] = array("br.branch_id,br.branch_name");
        $br_param['where_in']=array("br.status" => "1");
	$data['branch_info']  = $this->common_model->get_records_array($br_param);
        
        $ca_param=array();
        $ca_param['table'] = CAMPAIGN_MASTER.' as ca';
        $ca_param['fields'] = array("ca.campaign_id,ca.campaign_name");
        $ca_param['where_in']=array("ca.status" => "1");
	$data['campaign_info']  = $this->common_model->get_records_array($ca_param);
        
        $comp_param=array();
        $comp_param['table'] = COMPANY_MASTER.' as comp';
        $comp_param['fields'] = array("comp.company_id,comp.company_name");
        $comp_param['where_in']=array("comp.status" => 1,"comp.is_delete"=>0);
	$data['campany_info'] = $this->common_model->get_records_array($comp_param);
        
      /*  $p_param=array();
        $p_param['table'] = PROSPECT_MASTER.' as p';
        $p_param['fields'] = array("p.prospect_id,p.company_id,p.branch_id,p.status_type");
        $p_param['where']=array("p.status" => "1");
        $data['prospect_info']  = $this->common_model->get_records_array($p_param);
*/

        $p_param_table = PROSPECT_MASTER.' as p';
        $p_param_where =array("p.status" => 1,"p.is_delete"=>0);
        $p_wherein= array("p.status_type"=>array(1,3));
        $p_param_fields = array("p.prospect_id,p.company_id,p.branch_id,p.status_type,cm.company_name");
        $p_param_join_tables   =  array('blzdsk_company_master as cm' =>'cm.company_id = p.company_id');

        $data['prospect_info']      = $this->common_model->get_records($p_param_table,$p_param_fields,$p_param_join_tables,'left','','','','','','','',$p_param_where,'',$p_wherein);

        //pr($data['prospect_info']);exit;


                
        $pc_param=array();
        $pc_param['table'] = PROSPECT_CONTACTS_TRAN.' as pc';
        $pc_param['fields'] = array("pc.pro_contact_id,pc.prospect_id");
        $pc_param['where_in']=array("pc.status" => "1");
  	$data['prospect_contact_info']  = $this->common_model->get_records_array($pc_param);
        
        $pp_param=array();
        $pp_param['table'] = PROSPECT_PRODUCTS_TRAN.' as pp';
        $pp_param['fields'] = array("pp.pro_product_id,pp.prospect_id,pp.product_id");
        $pp_param['where_in']=array("pp.status" => "1");
  	$data['prospect_product_info']  = $this->common_model->get_records_array($pp_param);


        $this->parser->parse('layouts/CampaignGroupTemplate', $data);
        $this->load->view('Add');
        $this->load->view('loadJsFiles');


    }
    
      /*
    @Desc   : Insert data in database
    @Date   : 22/01/2016
    */
    public function insertdata()
    {
        if($this->input->post('group_name'))
		    {
                    $compaigndata['group_name'] = strip_slashes($this->input->post('group_name'));
		}
        if (!validateFormSecret()) {
            $msg = $this->lang->line('campign_group_add_msg');
            $this->session->set_flashdata('msg', $msg);
            redirect($this->viewname);	//Redirect On Listing page
        }

        $compaigndata['group_owner_id'] = $this->input->post('group_owner_id');
        $compaigndata['group_description'] = $this->input->post('group_description', FALSE);
        $compaigndata['product_id'] = $this->input->post('product_id');
        $compaigndata['status_id'] = $this->input->post('status_id');
        $compaigndata['value_start'] = $this->input->post('value_start');
        $compaigndata['value_end'] = $this->input->post('value_end');
        $compaigndata['emp_owner_id'] = $this->input->post('emp_owner_id');

        $branch_name = $this->input->post('branch_id');
        //Get Branch id From BRANCH_MASTER Table
        $table22 = BRANCH_MASTER . ' as bm';
        $match22 = "bm.branch_name='" . addslashes($branch_name) . "' and bm.status=1 ";
        $fields22 = array("bm.branch_name, bm.branch_id");
        $branch_record = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        if ($branch_record) {
            $compaigndata['branch_id'] = $branch_record[0]['branch_id'];
        } else {
            $branch_data['branch_name'] = $branch_name;
        }
        if (count($branch_record) == 0) {
            //INSERT Branch
            $branch_data['status'] = 1;
            $branch_data['created_date'] = datetimeformat();
            $branch_data['modified_date'] = datetimeformat();
            $branch_data_list = Array
            (
               'branch_name' => $branch_data['branch_name'],
               'status' => $branch_data['status'],
               'created_date' => $branch_data['created_date'],
               'modified_date' => $branch_data['modified_date'],
            );

            $branch_id = $this->common_model->insert(BRANCH_MASTER, $branch_data_list);
            $compaigndata['branch_id'] = $branch_id;
        }

                if($this->input->post('previous_campaign_id'))
                {    
                    $compaigndata['previous_campaign_id'] = $this->input->post('previous_campaign_id');
                }
                
                $related_campaign='0';
                
                if($this->input->post('related_campaign')=='on')
                {
                    $related_campaign='1';
                } 
               
                $compaigndata['related_campaign'] = $related_campaign;
                if($this->input->post('campaign_id'))
                {    
                $compaigndata['campaign_id'] = $this->input->post('campaign_id');
                }
                
                $compaigndata['status'] 	= '1';
		$compaigndata['created_date'] 	= datetimeformat();
		$compaigndata['modified_date'] 	= datetimeformat();
        	//Insert Record in Database
		$this->common_model->insert(CAMPAIGN_GROUP_MASTER,$compaigndata);
                $insert_id = $this->db->insert_id();
                
                //print_r($this->input->post('prospect_id'));
        $prospect_id=array();
        $prospect_id=$this->input->post('add_to_group');
        for($i=0;$i<count($prospect_id);$i++)
        {
            $prospect = explode('-',$prospect_id[$i]);
            $campaign_sales['campaign_group_id']=$insert_id;
            $campaign_sales['prospect_id']=$prospect[1];
            $campaign_sales['status_type']=$prospect[0];
            $this->common_model->insert(CAMPAIGN_GROUP_SALES_MASTER,$campaign_sales);
            /*echo $this->db->last_query();
            echo "<br>";
            */
        }

                //exit;
                //exit;
                $contact_id=$this->input->post('contact_id');
                for($i=0;$i<count($contact_id);$i++)
                {
                    $campaign_receipents_tran['campaign_id']=$insert_id;
                    $campaign_receipents_tran['contact_id']=$contact_id[$i]; 
                    $campaign_receipents_tran['created_date']=datetimeformat();
                    $campaign_receipents_tran['modified_date']=datetimeformat();
                    $campaign_receipents_tran['status']='1';
                    $this->common_model->insert(CAMPAIGN_RECEIPIENT_TRAN,$campaign_receipents_tran);
                }
        if ($insert_id) 
        {
            $msg = $this->lang->line('campign_group_add_msg');
            $this->session->set_flashdata('msg', $msg);
            
            //start sanket added for newsletter
            $newsletter_type = get_newsletter_type();

            if($newsletter_type == '1' || $newsletter_type == '2'  ||  $newsletter_type == '3' || $newsletter_type == '4')
            {

                if($newsletter_type == '1')
                {
                    //creating lists to the mailchimp (api)
                    $mailchimpLists = $this->insertMailchimpListName($compaigndata['group_name']);
                    
                    if(isset($mailchimpLists['id']) && $mailchimpLists['id'] != '')
                    {
                        $listsMasterData['campaign_id'] = $insert_id;
                        $listsMasterData['lists_id'] = $mailchimpLists['id'];
                        $listsMasterData['lists_name'] = $mailchimpLists['name'];
                        $listsMasterData['lists_type'] = 1;
                        
                        $insertToListsMaster = $this->addToNewsletterMaster($listsMasterData);
                        $cGroupContact = $this->getContactByCampaignGroup($insert_id);
                        
                       
                        //add contact to the mailchimp list (api)
                        $this->addMultipleContactToMailChimp($mailchimpLists['id'],$cGroupContact,$mailchimpLists['name']);
                        
                    }
                }else if($newsletter_type == '2')
                {
                    $this->load->library('CampaignMonitor');
                    
                    $data = array('Title'=>$compaigndata['group_name'],'UnsubscribePage'=>'http://www.example.com/unsubscribed.html',
				'UnsubscribeSetting'=>'AllClientLists','ConfirmedOptIn'=>'false','ConfirmationSuccessPage'=>'http://www.example.com/joined.html');
                    $list_id = $this->campaignmonitor->create_lists($data);
                    
                    if(isset($list_id) && $list_id != '')
                    {
                        $listsMasterData['campaign_id'] = $insert_id;
                        $listsMasterData['lists_id'] = $list_id;
                        $listsMasterData['lists_name'] = $compaigndata['group_name'];
                        $listsMasterData['lists_type'] = 2;
                        
                        $this->addToNewsletterMaster($listsMasterData);
                        $cGroupContact = $this->getContactByCampaignGroup($insert_id);
                        
                        if(count($cGroupContact) > 0)
                        {
                            foreach ($cGroupContact as $contact)
                            {
                                $cmonitor_data = array('email_address'=>$contact['email'],'contact_name'=>$contact['contact_name'],'listID'=>$list_id);
                                $this->campaignmonitor->addSubscribersByLists($cmonitor_data);
                                
                                $tmp_list = array($list_id);
                                $this->addTolistsContact($tmp_list,$contact['contact_id'],'2');
                                $this->updateContactNewsletter('1',$contact['contact_id'],'2');
                            }
                            
                        }
                    }
                   
                }else if($newsletter_type == '3')
                {
                    $this->load->library('Moosend');
                    $list_id = $this->moosend->createLists($compaigndata['group_name']);
                    
                    if(isset($list_id) && $list_id != '')
                    {
                        $listsMasterData['campaign_id'] = $insert_id;
                        $listsMasterData['lists_id'] = $list_id;
                        $listsMasterData['lists_name'] = $compaigndata['group_name'];
                        $listsMasterData['lists_type'] = 3;
                        
                        $insertToListsMaster = $this->addToNewsletterMaster($listsMasterData);
                        $cGroupContact = $this->getContactByCampaignGroup($insert_id);
                        if(count($cGroupContact) > 0)
                        {
                            $tmp_arr = [];
                            foreach ($cGroupContact as $contact)
                            {
                                $tmp_arr[] = array('Email'=>$contact['email'],'Name'=>$contact['contact_name']);
                                
                                $tmp_list = array($list_id);
                                $this->addTolistsContact($tmp_list,$contact['contact_id'],'3');
                                $this->updateContactNewsletter('1',$contact['contact_id'],'3');
                            }
                            $this->moosend->addSubscribersMany($list_id,$tmp_arr);
                            
                        }
                    }
                    
                }else if($newsletter_type == '4')
                {
                    $this->load->library('GetResponse');
                    $list_id = $this->getresponse->create_campaign(strtolower($compaigndata['group_name']));
                    
                    if(isset($list_id) && isset($list_id->CAMPAIGN_ID))
                    {
                        $campaignId_token = $list_id->CAMPAIGN_ID;
                        $listsMasterData['campaign_id'] = $insert_id;
                        $listsMasterData['lists_id'] = $campaignId_token;
                        $listsMasterData['lists_name'] = $compaigndata['group_name'];
                        $listsMasterData['lists_type'] = 4;
                        
                        $insertToListsMaster = $this->addToNewsletterMaster($listsMasterData);
                        $cGroupContact = $this->getContactByCampaignGroup($insert_id);
                        
                        foreach ($cGroupContact as $contact)
                        {
                            $this->getresponse->addContact($campaignId_token,$contact['contact_name'],$contact['email']);
                            
                            $tmp_list = array($campaignId_token);
                            $this->addTolistsContact($tmp_list,$contact['contact_id'],'4');
                            $this->updateContactNewsletter('1',$contact['contact_id'],'4');
                        }
                    }else
                    {
                        $this->session->set_flashdata('error',lang('GETRESPONSE_VALID_CAMPAIGN_NAME'));
                    }
                }
                

            }
            
            //end sanket added for newsletter
        }
       
        redirect($this->viewname);	//Redirect On Listing page
    }
    

    
    public function updatedata()
    {
        //pr($_POST);exit;
        $id = $this->input->post('id');
        
        $table = CAMPAIGN_GROUP_MASTER . ' as c';
        $match = "c.campaign_group_id =".$id;
        $fields = array("c.group_name");
        $data_campaign_group = $this->common_model->get_records($table, $fields, '', '', $match);
        $old_campaign_name = $data_campaign_group[0]['group_name'];
        
       
        if($this->input->post('group_name'))
        {
            $compaigndata['group_name'] = strip_slashes($this->input->post('group_name'));
        }
        if (!validateFormSecret()) {
            if ($id) {
                $msg = $this->lang->line('campign_group_update_msg');
                $this->session->set_flashdata('msg', $msg);
                redirect($this->viewname);
            }
        }
        $compaigndata['group_owner_id'] = $this->input->post('group_owner_id');
        $compaigndata['group_description'] = $this->input->post('group_description', FALSE);
        $compaigndata['product_id'] = $this->input->post('product_id');
        $compaigndata['status_id'] = $this->input->post('status_id');
        $compaigndata['value_start'] = $this->input->post('value_start');
        $compaigndata['value_end'] = $this->input->post('value_end');
        $compaigndata['emp_owner_id'] = $this->input->post('emp_owner_id');


        $branch_name = $this->input->post('branch_id');
        //Get Branch id From BRANCH_MASTER Table
        $table22 = BRANCH_MASTER . ' as bm';
        $match22 = "bm.branch_name='" . addslashes($branch_name) . "' and bm.status=1 ";
        $fields22 = array("bm.branch_name, bm.branch_id");
        $branch_record = $this->common_model->get_records($table22, $fields22, '', '', $match22);
        if ($branch_record) {
            $compaigndata['branch_id'] = $branch_record[0]['branch_id'];
        } else {
            $branch_data['branch_name'] = $branch_name;
        }
        if (count($branch_record) == 0) {
            //INSERT Branch
            $branch_data['status'] = 1;
            $branch_data['created_date'] = datetimeformat();
            $branch_data['modified_date'] = datetimeformat();

            $branch_data_list = Array
            (
                'branch_name' => $branch_data['branch_name'],
                'status' => $branch_data['status'],
                'created_date' => $branch_data['created_date'],
                'modified_date' => $branch_data['modified_date'],
            );

            $branch_id = $this->common_model->insert(BRANCH_MASTER, $branch_data_list);
            $compaigndata['branch_id'] = $branch_id;
        }
        $compaigndata['previous_campaign_id'] = $this->input->post('previous_campaign_id');
        $related_campaign='0';
        if($this->input->post('related_campaign')=='on' || $this->input->post('related_campaign')=='1')
        {
            $related_campaign='1';
        }
        $compaigndata['related_campaign'] = $related_campaign;
        $compaigndata['campaign_id'] = $this->input->post('campaign_id');
        $compaigndata['modified_date'] 	= datetimeformat();
        	//Insert Record in Database
        //pr($compaigndata);exit;
        $where = array('campaign_group_id' => $id);
        $success_update = $this->common_model->update(CAMPAIGN_GROUP_MASTER, $compaigndata, $where);

        //start commented by sanket for deletting and updating record
        /*
        $where_camp = array('campaign_group_id' => $id);
        $this->common_model->delete(CAMPAIGN_GROUP_SALES_MASTER,$where_camp);

        $prospect_id=$this->input->post('add_to_group');
        for($i=0;$i<count($prospect_id);$i++)
        {
            $prospect = explode('-',$prospect_id[$i]);
            $campaign_sales['campaign_group_id']=$id;
            $campaign_sales['prospect_id']=$prospect[1];
            $campaign_sales['status_type']=$prospect[0];
            $this->common_model->insert(CAMPAIGN_GROUP_SALES_MASTER,$campaign_sales);
            
        } */
        //end commented by sanket for deletting and updating record
        
        $prospect_id=$this->input->post('add_to_group');
        $deleted_arr = [];
       
        for($i=0;$i<count($prospect_id);$i++)
        {
            $prospect = explode('-',$prospect_id[$i]);
            
            $groupMaster =CAMPAIGN_GROUP_SALES_MASTER.' as cg';
            $matchGmaster= "cg.campaign_group_id=$id AND cg.status_type='".$prospect[0]."' AND cg.prospect_id='".$prospect[1]."'";
            $fieldsGmaster = array("cg.*");
            $dataGroupSalesMaster = $this->common_model->get_records($groupMaster, $fieldsGmaster, '', '', $matchGmaster);
        
            if(count($dataGroupSalesMaster) > 0)
            {
                $deleted_arr[] = $dataGroupSalesMaster[0]['camp_group_sales_id'];
                $update_status['status'] = 1;
                $where_s = array('camp_group_sales_id' => $dataGroupSalesMaster[0]['camp_group_sales_id']);
                $success_update = $this->common_model->update(CAMPAIGN_GROUP_SALES_MASTER, $update_status, $where_s);
            }else
            {
                $campaign_sales['campaign_group_id']=$id;
                $campaign_sales['prospect_id']=$prospect[1];
                $campaign_sales['status_type']=$prospect[0];
                $last_inserted_id = $this->common_model->insert(CAMPAIGN_GROUP_SALES_MASTER,$campaign_sales);
                $deleted_arr[] = $last_inserted_id;
            }
            
        } 
       
        
        if(count($deleted_arr) > 0)
        {
            //quesry is not by common model wise bcoz common model does not suppor not where in function of mysql.
            $str_not = implode(',', $deleted_arr);
            $query_tct = "UPDATE `blzdsk_campaign_group_sales_master` SET `status` = 0 WHERE `campaign_group_id` = $id AND `camp_group_sales_id` NOT IN($str_not)";
            $query = $this->db->query($query_tct);
            
        }
        
        if(empty($prospect_id))
        {
            $query_tct = "UPDATE `blzdsk_campaign_group_sales_master` SET `status` = 0 WHERE `campaign_group_id` = $id";
            $query = $this->db->query($query_tct);
        }
            
        if ($success_update) 
        {
            $msg = $this->lang->line('campign_group_update_msg');
            $this->session->set_flashdata('msg', $msg);
           
            $newsletter_type = get_newsletter_type();
            $listName = $this->input->post('group_name');

           
            if($newsletter_type == '1' || $newsletter_type == '2'  ||  $newsletter_type == '3' || $newsletter_type == '4')
            {

                if($newsletter_type == '1')
                {
                    $this->load->library('MailChimp');
                    $list_id = $this->listIdByCampaign($id);
                    
                    if($old_campaign_name != $listName)
                    {
                        $this->updateMailchimpListsName($listName,$list_id);
                        $this->updateListsNameInMaster($id,$listName);
                    }
                    
                    //for adding new contact to mailchimp
                    $cGroupContact = $this->getContactByCampaignGroup($id);
                    
                    $this->addMultipleContactToMailChimp($list_id,$cGroupContact,$listName);
                    
                    //for deleting contact from the mailchimo
                    $deleted_member  = $this->getDeletedContactByCampaignGroup($id);
                        
                  
                    if(count($deleted_member) > 0)
                    {
                        foreach ($deleted_member as $delete_contact)
                        {
                            $subscriber_hash = $this->mailchimp->subscriberHash($delete_contact['email']);
                            $this->mailchimp->delete("lists/$list_id/members/$subscriber_hash");
                            $this->updateTolistsContact($list_id,$delete_contact['contact_id'],$newsletter_type);
                            $this->updateContactNewsletter('0',$delete_contact['contact_id'],'1');
                        }
                  
                    }
                    
                    if(empty($prospect_id))
                    {
                        $where_camp = array('list_id' => $list_id);
                        $this->common_model->delete(TBL_NEWSLETTER_LISTS_CONTACT,$where_camp);
                    }
                    
                }else if($newsletter_type == '2')
                {
                    $this->load->library('CampaignMonitor');
                    
                    $cMonitorListId = $this->listIdByCampaign($id);
                    if($old_campaign_name != $listName)
                    {
                        $cMonitorData = array('listId'=>$cMonitorListId,'Title'=>$compaigndata['group_name'],'UnsubscribePage'=>'http://www.example.com/unsubscribed.html',
				'UnsubscribeSetting'=>'AllClientLists','ConfirmedOptIn'=>'false','ConfirmationSuccessPage'=>'http://www.example.com/joined.html');
                        $this->campaignmonitor->update_lists($cMonitorData);
                        $this->updateListsNameInMaster($id,$listName);
                    }
                    
                    //for adding contact to the newsletter
                    $cGroupContact = $this->getContactByCampaignGroup($id);
                    
                   
                    if(count($cGroupContact) > 0)
                    {
                        
                        foreach ($cGroupContact as $contact)
                        {
                            $cmonitor_data = array('email_address'=>$contact['email'],'contact_name'=>$contact['contact_name'],'listID'=>$cMonitorListId);
                            $this->campaignmonitor->addSubscribersByLists($cmonitor_data);
                            
                            $tmp_list = array($cMonitorListId);
                            $this->addTolistsContact($tmp_list,$contact['contact_id'],'2');
                            $this->updateContactNewsletter('1',$contact['contact_id'],'2');
                        }
                    }
                    
                    //for deleting contact from the newsletter
                    $deleted_member  = $this->getDeletedContactByCampaignGroup($id);
                    
                    if(count($deleted_member) > 0)
                    {
                        foreach ($deleted_member as $delete_contact)
                        {
                            $deleteContactArr = array('listID'=>$cMonitorListId,'email_address'=>$delete_contact['email']);
                            $this->campaignmonitor->deleteSubscribersByLists($deleteContactArr);
                            
                            $this->updateTolistsContact($cMonitorListId,$delete_contact['contact_id'],$newsletter_type);
                            $this->updateContactNewsletter('0',$delete_contact['contact_id'],'2');
                        }
                    }
                    
                    if(empty($prospect_id))
                    {
                        $where_camp = array('list_id' => $cMonitorListId);
                        $this->common_model->delete(TBL_NEWSLETTER_LISTS_CONTACT,$where_camp);
                    }
                }else if($newsletter_type == '3')
                {
                    $this->load->library('Moosend');
                    
                    
                    $moosendListId = $this->listIdByCampaign($id);
                    if($old_campaign_name != $listName)
                    {
                        $this->moosend->updateListsById($moosendListId,$compaigndata['group_name']);
                        $this->updateListsNameInMaster($id,$listName);
                    }
                    
                    //for adding contact to the newsletter
                    $cGroupContact = $this->getContactByCampaignGroup($id);
                    if(count($cGroupContact) > 0)
                    {
                        $tmp_arr = [];
                        foreach ($cGroupContact as $contact)
                        {
                            $tmp_arr[] = array('Email'=>$contact['email'],'Name'=>$contact['contact_name']);
                            
                            $tmp_list = array($moosendListId);
                            $this->addTolistsContact($tmp_list,$contact['contact_id'],'2');
                            $this->updateContactNewsletter('1',$contact['contact_id'],'2');
                        }
                        $this->moosend->addSubscribersMany($moosendListId,$tmp_arr);
                        
                    }
                    
                    //for removing cotnact from the moosedn
                    $deleted_member  = $this->getDeletedContactByCampaignGroup($id);
                    if(count($deleted_member) > 0)
                    {
                        $tmp_arr ='';
                        foreach ($deleted_member as $delete_contact)
                        {
                            $tmp_arr.=$delete_contact['email'].",";
                            
                            $this->updateTolistsContact($moosendListId,$delete_contact['contact_id'],$newsletter_type);
                            $this->updateContactNewsletter('0',$delete_contact['contact_id'],'3');
                        }
                        $tmpEmailStr = rtrim($tmp_arr,',');
                        $this->moosend->removeSubscibersMany($moosendListId,$tmpEmailStr);
                    }
                    
                    if(empty($prospect_id))
                    {
                        $where_camp = array('list_id' => $moosendListId);
                        $this->common_model->delete(TBL_NEWSLETTER_LISTS_CONTACT,$where_camp);
                    }
                }else if($newsletter_type == '4')
                {
                    $this->load->library('GetResponse');
                    $campaignId = $this->listIdByCampaign($id);
                    
                    /*
                    if($old_campaign_name != $listName)
                    {
                        $this->moosend->updateListsById($moosendListId,$compaigndata['group_name']);
                     * $this->updateListsNameInMaster($id,$listName);
                    }
                     * 
                     */
                    
                    //for adding contact to the newsletter
                    $cGroupContact = $this->getContactByCampaignGroup($id);
                    
                    if(count($cGroupContact) > 0)
                    {
                        $tmp_arr = [];
                        foreach ($cGroupContact as $contact)
                        {
                            $this->getresponse->addContact($campaignId,$contact['contact_name'],$contact['email']);
                            $tmp_list =array($campaignId);
                            $this->addTolistsContact($tmp_list,$contact['contact_id'],'4');
                            $this->updateContactNewsletter('1',$contact['contact_id'],'4');
                        }
                       
                    }
                    
                    $deleted_member  = $this->getDeletedContactByCampaignGroup($id);
                    if(count($deleted_member) > 0)
                    {
                        $tmp_arr ='';
                        foreach ($deleted_member as $delete_contact)
                        {
                            $cotnact_detail = (array) $this->getresponse->getContactsByEmail($delete_contact['email'],$campaignId);
                            
                            if(!empty($cotnact_detail))
                            {
                                $key_arr =  array_keys($cotnact_detail);
                                $result = $this->getresponse->deleteContact($key_arr[0]);
                            }
                            $this->updateTolistsContact($campaignId,$delete_contact['contact_id'],$newsletter_type);
                            $this->updateContactNewsletter('0',$delete_contact['contact_id'],'4');
                        }
                    }
                    
                    if(empty($prospect_id))
                    {
                        $where_camp = array('list_id' => $campaignId);
                        $this->common_model->delete(TBL_NEWSLETTER_LISTS_CONTACT,$where_camp);
                    }
                }
            }
        }
        redirect($this->viewname);	//Redirect On Listing page
    }
   
    /*
    @Desc   : Get current group data from database and send to js
    @Date   : 25/01/2016
    */
    public function get_options_data()
    {
        $branch_id = $_REQUEST['branch_id'];
        $product_id = $_REQUEST['product_id'];
        $group_owner_id = $_REQUEST['group_owner_id'];
        $previous_campaign_id=$_REQUEST['previous_campaign_id'];
        $campaign_id=$_REQUEST['campaign_id'];
        $emp_owner_id=$_REQUEST['emp_owner_id'];
        $id=$_REQUEST['id'];
        
        if($group_owner_id!='')
        {
            $cont_param=array();
            $cont_param['table'] = CONTACT_MASTER.' as cm';
            $cont_param['fields'] = array("cm.contact_id,cm.contact_name");
            $cont_param['where_in']=array("cm.status" => 1,"cm.is_delete"=>"0");
            $data2['contact_info']  = $this->common_model->get_records_array($cont_param);



            if(!empty($data2['contact_info']))
            {    
                foreach($data2['contact_info'] as $row)
                {
                    $group_owner_info[$row['contact_id']]=$row['contact_name'];
                }
            }
        }
        
        if($branch_id!='')
        {
            $branch_param=array();
            $branch_param['table'] = BRANCH_MASTER.' as br';
            $branch_param['fields'] = array("br.branch_id,br.branch_name");
            $branch_param['where_in']=array("br.status" => "1");
            $data2['branch_info']  = $this->common_model->get_records_array($branch_param);
            if(!empty($data2['branch_info']))
            {    
                foreach($data2['branch_info'] as $row)
                {
                    $branch_info[$row['branch_id']]=$row['branch_name'];
                }
            }
        }
        
        if($product_id!='')
        {
            $prod_param=array();
            $prod_param['table'] = PRODUCT_MASTER.' as pm';
            $prod_param['fields'] = array("pm.product_id,pm.product_name");
            $prod_param['where_in']=array("pm.status" => 1,"is_delete" => "0");
            $data2['product_info']  = $this->common_model->get_records_array($prod_param);
            if(!empty($data2['product_info']))
            {    
                foreach($data2['product_info'] as $row)
                {
                    $product_info[$row['product_id']]=$row['product_name'];
                }
            }
        }
        
        if($emp_owner_id!='')
        {
            $emp_param=array();
            $emp_param['table'] = CONTACT_MASTER.' as cm';
            $emp_param['fields'] = array("cm.contact_id,cm.contact_name");
            $emp_param['where_in']=array("cm.status" => 1,"cm.is_delete"=>"0");
            $empdata['contact_info']  = $this->common_model->get_records_array($emp_param);
            if(!empty($empdata['contact_info']))
            {    
                foreach($empdata['contact_info'] as $row)
                {
                    $emp_owner_info[$row['contact_id']]=$row['contact_name'];
                }
            }
        }
        
        if($previous_campaign_id!='')
        {
            $ca_param=array();
            $ca_param['table'] = CAMPAIGN_MASTER.' as ca';
            $ca_param['fields'] = array("ca.campaign_id,ca.campaign_name");
            $ca_param['where_in']=array("ca.status" => "1");
            $data2['campaign_info']  = $this->common_model->get_records_array($ca_param);
            if(!empty($data2['campaign_info']))
            {    
                foreach($data2['campaign_info'] as $row)
                {
                    $previous_campaign_info[$row['campaign_id']]=$row['campaign_name'];
                }
            }
        }
        
        if($campaign_id!='')
        {
            $camp_param=array();
            $camp_param['table'] = CAMPAIGN_MASTER.' as ca';
            $camp_param['fields'] = array("ca.campaign_id,ca.campaign_name");
            $camp_param['where_in']=array("ca.status" => "1");
            $data3['campaign_info']  = $this->common_model->get_records_array($camp_param);
            if(!empty($data3['campaign_info']))
            {    
                foreach($data3['campaign_info'] as $row)
                {
                    $campaign_info[$row['campaign_id']]=$row['campaign_name'];
                }
            }
        }
        
        $sales_param=array();
        $sales_param['table'] = CAMPAIGN_GROUP_SALES_MASTER.' as s';
        $sales_param['fields'] = array("s.prospect_id");
        $sales_param['where_in']=array("s.campaign_group_id" => $id);
  	$data2['group_sales_info']  = $this->common_model->get_records_array($sales_param);
        
        if($group_owner_info)
        {
            $sel_data[0]=array($group_owner_info);
        }  
        if($branch_info)
        {
            $sel_data[1]=array($branch_info);
        }
        if($product_info)
        {
            $sel_data[2]=array($product_info);
        }
        if($emp_owner_info)
        {
            $sel_data[3]=array($emp_owner_info);
        }
        if($previous_campaign_info)
        {
            $sel_data[4]=array($previous_campaign_info);
        }
        if($campaign_info)
        {
            $sel_data[5]=array($campaign_info);
        }
        if(!empty($data2['group_sales_info']))
        {
            $sel_data[6]=array($data2['group_sales_info']);
        }
        echo json_encode($sel_data);           
                        
    }
    
    /*
    @Desc   : delete data from database
    @Date   : 25/01/2016
    */
    public function deletedata()
    {
            $id = $this->input->get('id');
            //Delete Record From Database
            if(!empty($id))
            {
                $compaigndata['status']= 0;

                $trans_where = array('campaign_group_id' => $id);
                $this->common_model->update(CAMPAIGN_GROUP_SALES_MASTER, $compaigndata, $trans_where);

                $where = array('campaign_group_id' => $id);
                $success_delete = $this->common_model->update(CAMPAIGN_GROUP_MASTER, $compaigndata, $where);

               
                if ($success_delete)
                {
                    $msg = $this->lang->line('campign_del_msg');
                    $this->session->set_flashdata('msg', $msg);
                    
                    $newsletter_type = get_newsletter_type();
                    
                    if($newsletter_type == '1' || $newsletter_type == '2'  ||  $newsletter_type == '3' || $newsletter_type == '4')
                    {
                        $list_id = $this->listIdByCampaign($id);
                        
                        if($newsletter_type == '1')
                        {
                            $this->load->library('MailChimp');
                            $this->mailchimp->delete("lists/$list_id");
                        }else if($newsletter_type == '2')
                        {
                            $this->load->library('CampaignMonitor');
                            $this->campaignmonitor->deleteLists($list_id);
                        }else if($newsletter_type == '3')
                        {
                            $this->load->library('Moosend');
                            $this->moosend->deleteLists($list_id);
                        }else if($newsletter_type == '4')
                        {
                            //can not allow delete campaing in get resposne.
                            //delete from get response
                        }
                        
                        $whereNewsletter = array('campaign_id' => $id);
                        $newsLetter['status']= 0;
                        $success_delete = $this->common_model->update(TBL_NEWSLETTER_LISTS_MASTER, $newsLetter, $whereNewsletter);
                        $deleteContactBylists = $this->deleteFromListsContact($list_id);
                        
                    }
                }
            }
            
            unset($id);
            redirect($this->viewname);	//Redirect On Listing page
    }
    
    public function campaignMonitotLists()
    {
        $this->load->library('CampaignMonitor');
        $listsArr  = $this->campaignmonitor->getAllLists();
        
        $lists_data = $listsArr->response;
        
        $tmp_lists = [];
        if(count($lists_data) > 0)
        {
            
            foreach ($lists_data as $lists)
            {
                $tmp_lists[$lists->ListID] = $lists->Name;
            }
            
        }
        return $tmp_lists;
    }
    
    public function moosendLists()
    {
        $this->load->library('Moosend');
        $lists_res = $this->moosend->get_active_mailing_list();
        
        $lists_data = $lists_res->Context->MailingLists;
        
        $tmp_lists = [];
        if(count($lists_data) > 0)
        {
            
            foreach ($lists_data as $lists)
            {
                $tmp_lists[$lists->ID] = $lists->Name;
            }
            
        }
        return $tmp_lists;
    }
    
    public function getResponseCampaignLists()
    {
        $this->load->library('GetResponse');
        $campaignRes = $this->getresponse->getCampaigns();
        $totalCampaign =  count((array)$campaignRes);
        
        $tmp_lists = [];
        if($totalCampaign > 0)
        {
            foreach ($campaignRes as $cKey => $cVal)
            {
                $tmp_lists[$cKey] = $cVal->name;
            }
        }
        return $tmp_lists;
    }
    
    private function getContactByCampaignGroup($campaign_id)
    {
        $groupMaster =CAMPAIGN_GROUP_SALES_MASTER.' as cg';
        $matchGmaster= "cg.campaign_group_id=$campaign_id AND cg.status='1'";
        $fieldsGmaster = array("cg.*");
        $dataGroupSalesMaster = $this->common_model->get_records($groupMaster, $fieldsGmaster, '', '', $matchGmaster);
        
        $contact_data_arr = [];
        if(count($dataGroupSalesMaster) > 0)
        {
            foreach ($dataGroupSalesMaster as $groupSales)
            {
                if($groupSales['status_type'] == '1' || $groupSales['status_type'] == '3')
                {
                    $prospect_id = $groupSales['prospect_id'];
                    $tableCg = CONTACT_MASTER . ' as c,'.OPPORTUNITY_REQUIREMENT_CONTACTS.' as op';
                    $matchCg = "op.prospect_id=$prospect_id AND op.status='1' AND op.contact_id=c.contact_id";
                    $fieldsCg = array("c.contact_id,c.contact_name,c.email");
                    $contact_data = $this->common_model->get_records($tableCg, $fieldsCg, '', '', $matchCg);
                    
                    if(count($contact_data) > 0)
                    {
                        foreach ($contact_data as $contact)
                        {
                           $contact_data_arr[] = $contact;
                        }
                    }
                    
                }else if($groupSales['status_type'] == '2')
                {
                    $lead_id = $groupSales['prospect_id'];
                    $tableCg = CONTACT_MASTER . ' as c,'.LEAD_CONTACTS_TRAN.' as l';
                    $matchCg = "l.lead_id=$lead_id AND l.status='1' AND l.contact_id=c.contact_id";
                    $fieldsCg = array("c.contact_id,c.contact_name,c.email");
                    $contact_data = $this->common_model->get_records($tableCg, $fieldsCg, '', '', $matchCg);
                    
                    if(count($contact_data) > 0)
                    {
                        foreach ($contact_data as $contact)
                        {
                            $contact_data_arr[] = $contact;
                        }
                    }
                }
            }
        }
        
        return $contact_data_arr;
    }
    
    private function getDeletedContactByCampaignGroup($campaign_id)
    {
        $groupMaster =CAMPAIGN_GROUP_SALES_MASTER.' as cg';
        $matchGmaster= "cg.campaign_group_id=$campaign_id AND cg.status='0'";
        $fieldsGmaster = array("cg.*");
        $dataGroupSalesMaster = $this->common_model->get_records($groupMaster, $fieldsGmaster, '', '', $matchGmaster);
        
        $contact_data_arr = [];
        if(count($dataGroupSalesMaster) > 0)
        {
            foreach ($dataGroupSalesMaster as $groupSales)
            {
                if($groupSales['status_type'] == '1' || $groupSales['status_type'] == '3')
                {
                    $prospect_id = $groupSales['prospect_id'];
                    $tableCg = CONTACT_MASTER . ' as c,'.OPPORTUNITY_REQUIREMENT_CONTACTS.' as op';
                    $matchCg = "op.prospect_id=$prospect_id AND op.status='1' AND op.contact_id=c.contact_id";
                    $fieldsCg = array("c.contact_id,c.contact_name,c.email");
                    $contact_data = $this->common_model->get_records($tableCg, $fieldsCg, '', '', $matchCg);
                    
                    if(count($contact_data) > 0)
                    {
                        foreach ($contact_data as $contact)
                        {
                           $contact_data_arr[] = $contact;
                        }
                    }
                    
                }else if($groupSales['status_type'] == '2')
                {
                    $lead_id = $groupSales['prospect_id'];
                    $tableCg = CONTACT_MASTER . ' as c,'.LEAD_CONTACTS_TRAN.' as l';
                    $matchCg = "l.lead_id=$lead_id AND l.status='1' AND l.contact_id=c.contact_id";
                    $fieldsCg = array("c.contact_id,c.contact_name,c.email");
                    $contact_data = $this->common_model->get_records($tableCg, $fieldsCg, '', '', $matchCg);
                    
                    if(count($contact_data) > 0)
                    {
                        foreach ($contact_data as $contact)
                        {
                            $contact_data_arr[] = $contact;
                        }
                    }
                }
                
                $where_camp = array('camp_group_sales_id' => $groupSales['camp_group_sales_id']);
                $this->common_model->delete(CAMPAIGN_GROUP_SALES_MASTER,$where_camp);
            }
        }
        
        return $contact_data_arr;
    }
    
    
    public function addToNewsletterMaster($listMasterData)
    {
        $listMasterData['created_date'] = date('Y-m-d');
        $listMasterData['updated_date'] = date('Y-m-d');
        $listMasterData['status'] = 1;
        $this->common_model->insert(TBL_NEWSLETTER_LISTS_MASTER, $listMasterData);
    }
    
    public function listIdByCampaign($campaignId)
    {
        $table = TBL_NEWSLETTER_LISTS_MASTER . ' as c';
        $match = "c.campaign_id =".$campaignId." and c.status=1";
        $fields = array("c.lists_id");
        $list_id = $this->common_model->get_records($table, $fields, '', '', $match);
        
        return $list_id[0]['lists_id'];
    }
    
    public function updateMailchimpListsName($listName,$list_id)
    {
        $companyInfo = configGeneralSettgins();
        $countryNameArr = getCountryName($companyInfo->country_id);
        $mailchimpData = array('name'=>$listName,
            'permission_reminder'=>'You signed up for updates',
            'email_type_option'=>false,
            'contact'=>array('company'=>$companyInfo->company_name,'address1'=>$companyInfo->address1,'address2'=>$companyInfo->address2,'city'=>$companyInfo->city,'state'=>$companyInfo->state,'zip'=>$companyInfo->pincode,'country'=>$countryNameArr[0]['country_name'],'phone'=>$companyInfo->telephone1),
            'campaign_defaults'=>array('from_name'=>$this->user_info['FIRSTNAME']." ".$this->user_info['LASTNAME'],'from_email'=>$this->user_info['EMAIL'],'subject'=>$this->input->post('group_name'),'language'=>'US'));

        $result =  $this->mailchimp->patch("lists/$list_id", $mailchimpData);
    }
    
    public function insertMailchimpListName($listsName)
    {
        $this->load->library('MailChimp');
        $companyInfo = configGeneralSettgins();
        $countryNameArr = getCountryName($companyInfo->country_id);
        $mailchimpData = array('name'=>$listsName,
            'permission_reminder'=>'You signed up for updates',
            'email_type_option'=>false,
            'contact'=>array('company'=>$companyInfo->company_name,'address1'=>$companyInfo->address1,'address2'=>$companyInfo->address2,'city'=>$companyInfo->city,'state'=>$companyInfo->state,'zip'=>$companyInfo->pincode,'country'=>$countryNameArr[0]['country_name'],'phone'=>$companyInfo->telephone1),
            'campaign_defaults'=>array('from_name'=>$this->user_info['FIRSTNAME']." ".$this->user_info['LASTNAME'],'from_email'=>$this->user_info['EMAIL'],'subject'=>$this->input->post('group_name'),'language'=>'US'));
                    
        return $this->mailchimp->post('lists', $mailchimpData);
    }
    
    public function addMultipleContactToMailChimp($list_id,$cGroupContact,$listsName)
    {
        $this->load->library('MailChimp');
        $Batch  = $this->mailchimp->new_batch();
        
        if(count($cGroupContact) > 0)
        {
            $i = 0;
            foreach ($cGroupContact as $contact)
            {
                $Batch->post("op_$i", "lists/$list_id/members", [
                        'email_address' => $contact['email'],
                        'status'        => 'subscribed',
                        'merge_fields'  => [
                            'FNAME'     => $contact['contact_name'],
                            'LNAME'     => ''
                                        ]
                    ]);
                $i++;
                
                $tmp_list =array($list_id);
                $this->addTolistsContact($tmp_list,$contact['contact_id'],'1');
                $this->updateContactNewsletter('1',$contact['contact_id'],'1');
            }

            $batchResult = $Batch->execute();
            $this->load->helper('file');
            $data  = json_encode(array('id'=>$batchResult['id'],'list_id'=>$list_id,'list_name'=>$listsName,'status'=>$batchResult['status'],'date'=>date('Y-m-d h:m:s'),'total_operations'=>$batchResult['total_operations'])). "\r\n";
            write_file(FCPATH .'uploads/mailchimp_batch_log.txt', $data,'a');
            
            return $batchResult;
        }
    }
    
    public function updateListsNameInMaster($campaignId,$listName)
    {
        $data['lists_name'] = $listName;
        $where = array('campaign_id' => $campaignId);
        $success_update = $this->common_model->update(TBL_NEWSLETTER_LISTS_MASTER, $data, $where);
        
        if($success_update)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    private function addTolistsContact($list_Id,$success_insert,$newsletterType)
    {
        if(count($list_Id) > 0)
        {
            foreach ($list_Id as $ids)
            {
                
                $table = TBL_NEWSLETTER_LISTS_CONTACT . ' as c';
                $match = "c.contact_id =".$success_insert." AND c.status=1 AND c.list_id='".$ids."'";
                $fields = array("c.list_id");
                $getRecord = $this->common_model->get_records($table, $fields, '', '', $match);
                
                if(!empty($getRecord) && count($getRecord) > 0)
                {
                    $queryDel = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE contact_id=$success_insert AND list_id='$ids'";
                    $this->db->query($queryDel);
                }
        
                $contactData['contact_id'] = $success_insert;
                $contactData['list_id'] = $ids;
                $contactData['list_type'] = $newsletterType;
                $contactData['status'] = 1;
                $contactData['created_date'] = date('Y-m-d');
                $this->common_model->insert(TBL_NEWSLETTER_LISTS_CONTACT, $contactData);
            }
        }
        
    }
    
    private function updateTolistsContact($list_id,$success_insert,$newsletterType)
    {
        $contactData['status'] = 0;
        $where = array('contact_id'=>$success_insert,'list_type'=>$newsletterType,'list_id'=>$list_id);
        $this->common_model->update(TBL_NEWSLETTER_LISTS_CONTACT, $contactData,$where);
        
    }
    
    private function updateContactNewsletter($is_newsletter,$contactId,$newsletterType)
    {
        $nData['is_newsletter'] = $is_newsletter;
        $nData['configure_with'] = $newsletterType;
        $where = array('contact_id' => $contactId);
        $this->common_model->update(CONTACT_MASTER, $nData,$where);
    }
    
    
    public function getCompanyByCampaign()
    {
        //Get CampaignId from Post Method
        $campaignId = $this->input->post('campaignId');
        $campaignGroupId = $this->input->post('campaignGroupId');
        $ownerId = $this->input->post('ownerId');
        
        $fieldsPros = array("pros_mstr.prospect_id, com_mstr.company_name AS 'pros_company_name', brnch_mstr.branch_name AS 'pros_branch_name', pros_mstr.status_type as 'pros_status_type', GROUP_CONCAT(prod_mstr.product_name) AS 'pros_products'");
        $tablePros = PROSPECT_MASTER . " AS pros_mstr";
        $paramsPros['join_tables'] = array(COMPANY_MASTER. " AS com_mstr" => "pros_mstr.company_id = com_mstr.company_id",
                                        BRANCH_MASTER. " AS brnch_mstr" => "pros_mstr.branch_id = brnch_mstr.branch_id",
                                        PROSPECT_PRODUCTS_TRAN. " AS pros_prod_tran" => "pros_mstr.prospect_id = pros_prod_tran.prospect_id",
                                        PRODUCT_MASTER. " AS prod_mstr" => "prod_mstr.product_id = pros_prod_tran.product_id"
            );
        $groupByPros= "pros_mstr.prospect_id";
        
        if(isset($campaignId) && $campaignId != "")
        {
            if(isset($ownerId) && $ownerId != "")
            {
                $wherePros = array("pros_mstr.campaign_id"=> $campaignId, "pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "pros_mstr.status_type!="=> '', "pros_mstr.status_type!=" => 2, "pros_mstr.created_by" => $ownerId);
            }else{
                $wherePros = array("pros_mstr.campaign_id"=> $campaignId, "pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "pros_mstr.status_type!="=> '', "pros_mstr.status_type!=" => 2);
            }
            // Get Records for campaignId and ownerId if selected
            $data['prospect_data'] = $this->common_model->get_records($tablePros, $fieldsPros, $paramsPros['join_tables'], "left", $wherePros, "", "", "", "", "", $groupByPros);
        }
        else
        {
            if(isset($ownerId) && $ownerId != "")
            {
                $wherePros = array("pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "pros_mstr.status_type!="=> '', "pros_mstr.status_type!=" => 2, "pros_mstr.created_by" => $ownerId);
            }else{
                $wherePros = array("pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "pros_mstr.status_type!="=> '', "pros_mstr.status_type!=" => 2);
            }
            // Get Records for campaignId and ownerId if selected
            $data['prospect_data'] = $this->common_model->get_records($tablePros, $fieldsPros, $paramsPros['join_tables'], "left", $wherePros, "", "", "", "", "", $groupByPros);
        }
        
        //total contacts count: Prospect
        $fieldsConPros = array("oppo_req_con.prospect_id, COUNT(oppo_req_con.requirement_contacts_id) AS 'pros_contacts'");
        $tableConPros = PROSPECT_MASTER . " AS pros_mstr";
        $paramsConPros['join_tables'] = array(OPPORTUNITY_REQUIREMENT_CONTACTS. " AS oppo_req_con" => "pros_mstr.prospect_id = oppo_req_con.prospect_id",
                                            CONTACT_MASTER. " AS con_mstr" => "con_mstr.contact_id = oppo_req_con.contact_id"
            );
        
        if(isset($campaignId) && $campaignId != "")
        {
            $whereConPros = array("pros_mstr.campaign_id"=> $campaignId, "pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "oppo_req_con.status" => 1, "con_mstr.status"=> 1, "con_mstr.is_delete"=> 0);
        }
        else
        {
            $whereConPros = array("pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "oppo_req_con.status" => 1, "con_mstr.status"=> 1, "con_mstr.is_delete"=> 0);
        }
        $groupByConPros = "pros_mstr.prospect_id";
        $data['prospect_contact_info'] = $this->common_model->get_records($tableConPros, $fieldsConPros, $paramsConPros['join_tables'], "left", $whereConPros, "", "", "", "", "", $groupByConPros);
        
        //Selected Data
        if($campaignGroupId != ''){
            $tableSales    = CAMPAIGN_GROUP_SALES_MASTER.' as camp_grp_sales_mstr';
            $fieldsSales   = array("camp_grp_sales_mstr.prospect_id", "camp_grp_sales_mstr.status_type");
            $whereSales    = array("camp_grp_sales_mstr.campaign_group_id" => $campaignGroupId);

            $data['group_sales'] = $this->common_model->get_records($tableSales,$fieldsSales,'','','','','','','','','',$whereSales);

            $group_sales_info=array();
            $lead_sales_info=array();
            $status = array(1,3);
            foreach($data['group_sales'] as $group_sales){
                if (!empty($group_sales) && in_array($group_sales['status_type'],$status)) {
                    $group_sales_info[] = $group_sales['prospect_id'];
            }else{
                    $lead_sales_info[] = $group_sales['prospect_id'];
                }
            }
            $data['group_sales_info']=$group_sales_info;
            $data['lead_sales_info']=$lead_sales_info;
        }
        
        /**
         * Lead Data
         */
        $tableLead = LEAD_MASTER . " AS lead_mstr";
        $fieldsLead = array("lead_mstr.lead_id, com_mstr.company_name AS 'lead_company_name', lead_mstr.status_type AS 'lead_status_type', brnch_mstr.branch_name AS 'lead_branch_name', GROUP_CONCAT(prod_mstr.product_name) AS 'lead_products'");
        
        $paramsLead['join_tables'] = array(COMPANY_MASTER. ' AS com_mstr' => 'lead_mstr.company_id = com_mstr.company_id',
                                            BRANCH_MASTER. " AS brnch_mstr" => "lead_mstr.branch_id = brnch_mstr.branch_id",
                                            LEAD_PRODUCTS_TRAN. " AS lead_prod_tran" => "lead_prod_tran.lead_id = lead_mstr.lead_id",
                                            PRODUCT_MASTER. " AS prod_mstr" => "prod_mstr.product_id = lead_prod_tran.product_id"
            );
        $groupByLead = "lead_mstr.lead_id";
        if(isset($campaignId) && $campaignId != "")
        {
            if(isset($ownerId) && $ownerId != "")
            {
                $whereLead = array("lead_mstr.campaign_id" => $campaignId, "lead_mstr.is_delete" => 0, "lead_mstr.status" => 1, "lead_mstr.created_by" => $ownerId);
            }
            else
            {
                $whereLead = array("lead_mstr.campaign_id" => $campaignId, "lead_mstr.is_delete" => 0, "lead_mstr.status" => 1);
            }
        }
        else
        {
            if(isset($ownerId) && $ownerId != "")
            {
                $whereLead = array("lead_mstr.is_delete" => 0, "lead_mstr.status" => 1, "lead_mstr.created_by" => $ownerId);
            }
            else
            {
                $whereLead = array("lead_mstr.is_delete" => 0, "lead_mstr.status" => 1);
            }
        }
        
        $data['lead_data'] = $this->common_model->get_records($tableLead, $fieldsLead, $paramsLead['join_tables'], "left", $whereLead, "", "", "", "", "", $groupByLead);
        
        //total contacts count: Lead
        $paramsConLead['join_tables'] = array(LEAD_CONTACTS_TRAN." AS lead_con_tran" => "lead_mstr.lead_id = lead_con_tran.lead_id",
                                            CONTACT_MASTER. " AS con_mstr" => "con_mstr.contact_id = lead_con_tran.contact_id"
            );
        $fieldsConLead = array("lead_con_tran.lead_id, COUNT(lead_con_tran.pro_contact_id) AS 'lead_contacts'");
        $groupByConLead = "lead_mstr.lead_id";
        
        if(isset($campaignId) && $campaignId != "")
        {
            $whereConLead = array("lead_mstr.campaign_id" => $campaignId, "lead_mstr.is_delete" => 0, "lead_mstr.status" => 1, "lead_con_tran.status" => 1, "con_mstr.status" => 1, "con_mstr.is_delete"=> 0);
        }
        else
        {
            $whereConLead = array("lead_mstr.is_delete" => 0, "lead_mstr.status" => 1, "lead_con_tran.status" => 1, "con_mstr.status" => 1, "con_mstr.is_delete"=> 0);
        }
        $data['lead_contact_info']  = $this->common_model->get_records($tableLead, $fieldsConLead, $paramsConLead['join_tables'], "left", $whereConLead, "", "", "", "", "", $groupByConLead);
        
        echo json_encode($data);
        exit;
    }
    
    /**
    @Author : Disha Patel
    @Desc   : Get Data by ownerId
    @Date   : 21/06/2016
    */
    public function getCompanyByOwner()
    {
        //Get OwnerId from Post Method
        $ownerId = $this->input->post('ownerId');
        $campaignGroupId = $this->input->post('campaignGroupId');
        $campaignId = $this->input->post('campaignId');
        
        /*
         * For prospect Data: company, branch, products and status
         */
        $fieldsPros = array("pros_mstr.prospect_id, com_mstr.company_name AS 'pros_company_name', brnch_mstr.branch_name AS 'pros_branch_name', pros_mstr.status_type as 'pros_status_type', GROUP_CONCAT(prod_mstr.product_name) AS 'pros_products'");
        $tablePros = PROSPECT_MASTER . " AS pros_mstr";
        $paramsPros['join_tables'] = array(COMPANY_MASTER. " AS com_mstr" => "com_mstr.company_id = pros_mstr.company_id",
                                            BRANCH_MASTER. " AS brnch_mstr" => "brnch_mstr.branch_id = pros_mstr.branch_id",
                                            PROSPECT_PRODUCTS_TRAN. " AS pros_prod_tran" => "pros_mstr.prospect_id = pros_prod_tran.prospect_id",
                                            PRODUCT_MASTER. " AS prod_mstr" => "prod_mstr.product_id = pros_prod_tran.product_id",
                                            CAMPAIGN_GROUP_SALES_MASTER. " AS camp_grp_sales_mstr" => "camp_grp_sales_mstr.prospect_id = pros_mstr.prospect_id"
            );
        //$where_pros = array("pros_mstr.created_by" => $ownerId, "pros_mstr.is_delete" => 0, "pros_mstr.status_type" => "1");
        $groupByPros= "pros_mstr.prospect_id";
        
        if(isset($ownerId) && $ownerId != "")
        {
            if(isset($campaignId) && $campaignId != "")
            {
                $wherePros = "pros_mstr.created_by = ".$ownerId." AND pros_mstr.is_delete = 0 AND pros_mstr.status = 1 AND pros_mstr.campaign_id = ".$campaignId." ";
                $wherePros .= " AND (pros_mstr.status_type = '3' OR pros_mstr.status_type = '1')";
            }
            else
            {
                $wherePros = "pros_mstr.created_by = ".$ownerId." AND pros_mstr.is_delete = 0 AND pros_mstr.status = 1";
                $wherePros .= " AND (pros_mstr.status_type = '3' OR pros_mstr.status_type = '1')";
            }
        }
        else
        {
            if(isset($campaignId) && $campaignId != "")
            {
                $wherePros = "pros_mstr.is_delete = 0 AND pros_mstr.status = 1 AND pros_mstr.campaign_id = ".$campaignId." ";
                $wherePros .= " AND (pros_mstr.status_type = '3' OR pros_mstr.status_type = '1')";
            }
            else
            {
                $wherePros = "pros_mstr.is_delete = 0 AND pros_mstr.status = 1";
                $wherePros .= " AND (pros_mstr.status_type = '3' OR pros_mstr.status_type = '1')";
            }
        }
        
        $data['prospect_data'] = $this->common_model->get_records($tablePros, $fieldsPros, $paramsPros['join_tables'], "left", $wherePros, "", "", "", "", "", $groupByPros);
        
        //total contacts count: Prospect
        $fieldsConPros = array("oppo_req_con.prospect_id, COUNT(oppo_req_con.requirement_contacts_id) AS 'pros_contacts'");
        $tableConPros = PROSPECT_MASTER . " AS pros_mstr";
        $paramsConPros['join_tables'] = array(OPPORTUNITY_REQUIREMENT_CONTACTS. " AS oppo_req_con" => "pros_mstr.prospect_id = oppo_req_con.prospect_id",
                                            CONTACT_MASTER. " AS con_mstr" => "con_mstr.contact_id = oppo_req_con.contact_id"
            );
        
        if(isset($ownerId) && $ownerId != "")
        {
            $whereConPros = array("pros_mstr.created_by"=> $ownerId, "pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "oppo_req_con.status" => 1, "con_mstr.status"=> 1, "con_mstr.is_delete"=> 0);
        }
        else
        {
            $whereConPros = array("pros_mstr.is_delete" => 0, "pros_mstr.status" => 1, "oppo_req_con.status" => 1, "con_mstr.status"=> 1, "con_mstr.is_delete"=> 0);
        }
        $groupByConPros = "pros_mstr.prospect_id";
        $data['prospect_contact_info'] = $this->common_model->get_records($tableConPros, $fieldsConPros, $paramsConPros['join_tables'], "left", $whereConPros, "", "", "", "", "", $groupByConPros);
        
        //Selected Data
        if($campaignGroupId != ''){
            $tableSales    = CAMPAIGN_GROUP_SALES_MASTER.' as camp_grp_sales_mstr';
            $fieldsSales   = array("camp_grp_sales_mstr.prospect_id", "camp_grp_sales_mstr.status_type");
            $whereSales    = array("camp_grp_sales_mstr.campaign_group_id" => $campaignGroupId);

            $data['group_sales'] = $this->common_model->get_records($tableSales,$fieldsSales,'','','','','','','','','',$whereSales);

            $group_sales_info=array();
            $lead_sales_info=array();
            $status = array(1,3);
            foreach($data['group_sales'] as $group_sales){
                if (!empty($group_sales) && in_array($group_sales['status_type'],$status)) {
                    $group_sales_info[] = $group_sales['prospect_id'];
            }else{
                    $lead_sales_info[] = $group_sales['prospect_id'];
                }
            }
            $data['group_sales_info']=$group_sales_info;
            $data['lead_sales_info']=$lead_sales_info;
        }
        
        /*
         * For lead data: company, branch, products and status
         */
        $fieldsLead = array("lead_mstr.lead_id, com_mstr.company_name AS 'lead_company_name', lead_mstr.status_type as 'lead_status_type', brnch_mstr.branch_name AS 'lead_branch_name', GROUP_CONCAT(prod_mstr.product_name) AS 'lead_products'");
        $tableLead = LEAD_MASTER . " AS lead_mstr";
        $paramsLead['join_tables'] = array(COMPANY_MASTER. " AS com_mstr" => "com_mstr.company_id = lead_mstr.company_id",
                                            BRANCH_MASTER. " AS brnch_mstr" => "brnch_mstr.branch_id = lead_mstr.branch_id",
                                            LEAD_PRODUCTS_TRAN. " AS lead_prod_tran" => "lead_prod_tran.lead_id = lead_mstr.lead_id",
                                            PRODUCT_MASTER. " AS prod_mstr" => "prod_mstr.product_id = lead_prod_tran.product_id"
            );
        //$where_pros = array("pros_mstr.created_by" => $ownerId, "pros_mstr.is_delete" => 0, "pros_mstr.status_type" => "1");
        if(isset($ownerId) && $ownerId != "")
        {
            if(isset($campaignId) && $campaignId != "")
            {
                $whereLead = "lead_mstr.created_by = ".$ownerId." AND lead_mstr.is_delete = 0 AND lead_mstr.status = 1 AND lead_mstr.campaign_id = ".$campaignId." ";
                $whereLead .= " AND (lead_mstr.status_type = '2')";
            }
            else
            {
                $whereLead = "lead_mstr.created_by = ".$ownerId." AND lead_mstr.is_delete = 0 AND lead_mstr.status = 1";
                $whereLead .= " AND (lead_mstr.status_type = '2')";
            }
        }
        else
        {
            if(isset($campaignId) && $campaignId != "")
            {
                $whereLead = "lead_mstr.is_delete = 0 AND lead_mstr.status = 1 AND lead_mstr.campaign_id = ".$campaignId." ";
                $whereLead .= " AND (lead_mstr.status_type = '2')";
            }
            else
            {
                $whereLead = "lead_mstr.is_delete = 0 AND lead_mstr.status = 1";
                $whereLead .= " AND (lead_mstr.status_type = '2')";
            }
        }
        
        $groupByLead = "lead_mstr.lead_id";
        $data['lead_data'] = $this->common_model->get_records($tableLead, $fieldsLead, $paramsLead['join_tables'], "left", $whereLead, "", "", "", "", "", $groupByLead);
        
        //total contacts count: Lead
        $paramsConLead['join_tables'] = array(LEAD_CONTACTS_TRAN." AS lead_con_tran" => "lead_mstr.lead_id = lead_con_tran.lead_id",
                                            CONTACT_MASTER. " AS con_mstr" => "con_mstr.contact_id = lead_con_tran.contact_id"
            );
        $fieldsConLead = array("lead_con_tran.lead_id, COUNT(lead_con_tran.pro_contact_id) AS 'lead_contacts'");
        $groupByConLead = "lead_mstr.lead_id";
        
        if(isset($ownerId) && $ownerId != "")
        {
            $whereConLead = array("lead_mstr.created_by" => $ownerId, "lead_mstr.is_delete" => 0, "lead_mstr.status" => 1, "lead_con_tran.status" => 1, "con_mstr.status" => 1, "con_mstr.is_delete"=> 0);
        }
        else
        {
            $whereConLead = array("lead_mstr.is_delete" => 0, "lead_mstr.status" => 1, "lead_con_tran.status" => 1, "con_mstr.status" => 1, "con_mstr.is_delete"=> 0);
        }
        $data['lead_contact_info']  = $this->common_model->get_records($tableLead, $fieldsConLead, $paramsConLead['join_tables'], "left", $whereConLead, "", "", "", "", "", $groupByConLead);
        
        echo json_encode($data);
        exit;
    }
    
    function deleteFromListsContact($listId)
    {
        //$queryDel = "DELETE FROM ".TBL_NEWSLETTER_LISTS_CONTACT." WHERE list_id='$listId'";
        //$this->db->query($queryDel);
        $where_camp = array('list_id' => $listId);
        $this->common_model->delete(TBL_NEWSLETTER_LISTS_CONTACT,$where_camp);
    }

public function CheckCampaignGroup(){


    $group_name = strip_slashes($this->input->post('group_name'));

    $table = CAMPAIGN_GROUP_MASTER . ' as c';
    $match = 'c.group_name ="'.$group_name.'" and c.status=1';
    $fields = array("c.campaign_group_id");
    $campaignGroup = $this->common_model->get_records($table, $fields, '', '', $match);
    
    
    if(is_array($campaignGroup) && !empty($campaignGroup) && count($campaignGroup) > 0)
    {
         echo json_encode(array('status' => 1));
    }else
    {
        echo json_encode(array('status' => 0));
    }
   die;
    /*
    foreach($chick_campaign_group as $chick_campaign){
        if($chick_campaign['group_name'] == $group_name)
        {
            $msg = $this->lang->line('Campign_group_is_already_exists');
            $error .= $msg;
        }
    }
    if ($error) {
        echo json_encode(array('error' => $error));
        die();
    }
    */

}




}
?>