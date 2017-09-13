<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  @Author : Seema Tankariya
  @Desc   : Model For Opportunity Module
  @Input 	:
  @Output	:
  @Date   : 12/01/2016
 */

class Lead_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function exportCsvData($dbSearch) {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = random_string() . "lead.csv";
        $join_tables = array(LEAD_CONTACTS_TRAN . ' as pc' => 'pm.lead_id=pc.lead_id');
        $params['join_type'] = 'left';
        $table = LEAD_MASTER . ' as pm';
        $group_by = 'pm.lead_id';
        $fields = array("pm.prospect_name,pm.prospect_auto_id,count(pc.lead_id) as 'number of contact',
          pc.contact_name,pm.creation_date as 'client since',CASE WHEN pm.status_type =2 THEN ' Lead' END as status");
        //$fields = array('prospect_id'=>'pm.prospect_id','prospect_name'=>'pm.prospect_name','prospect_auto_id'=>'pm.prospect_auto_id','status'=>'pm.status','status_type'=>'pm.status_type','contact_name'=>'pc.contact_name','count(pm.prospect_id) as opp_count','count(pc.prospect_id) as contact_count');
        $data['sortField'] = 'pm.lead_id';
        $data['sortOrder'] = 'desc';
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join(LEAD_CONTACTS_TRAN . ' as pc', 'pm.lead_id=pc.lead_id', 'left');
        $this->db->where($dbSearch, '', false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->group_by($group_by);

        $dataarr = $this->db->get();
       
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
        force_download($filename, $data1);
    }
    
    function exportLeads()
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = time()."-".date('m-d-Y') . "_Lead.csv";
        $table = LEAD_MASTER . ' as l';
        $group_by = 'l.lead_id';
        $fields = array("l.prospect_auto_id as 'Lead Auto Gen ID',l.prospect_name as 'Lead Title',"
            . "cm.company_name as 'Company Name',"
            . "cm.email_id as 'Company Email',"
            . "cm.phone_no as 'Company Phone',cr.campaign_name as 'Campaign Name',"
            . "l.address1 as 'Address1',"
            . "l.address2 as 'Address2',"
            . "l.creation_date as 'Create Date',"
            . "l.postal_code as 'Postal Code',"
            . "l.city as 'City',"
            . "l.state as 'State',"
            . "c.country_name as 'Country',"
            . "CASE l.number_type1 WHEN 1 THEN 'Home' WHEN 2 THEN 'Mobile' WHEN 3 THEN 'Office'  ELSE '' END as 'Number Type 1',"
            . "l.phone_no as 'Phone Number 1',"
            . "CASE l.number_type2 WHEN 1 THEN 'Home' WHEN 2 THEN 'Mobile' WHEN 3 THEN 'Office'  ELSE '' END as 'Number Type 2',"
            . "l.phone_no2 as 'Phone Number 2',"
           // . "CASE l.language_id WHEN l.language_id = 1 THEN 'English' WHEN l.language_id = 2 THEN 'Spanish' ELSE '' END as 'Language',"
            . "lg.language_name as 'Language',"
            . "bm.branch_name as 'Branch Name',"
            . "GROUP_CONCAT(pm.product_name) as 'Intrested Product',"
            . "l.description as 'Description',l.contact_date as 'Contact Date'");

        //,CONCAT(lg.firstname,' ',lg.lastname) as 'Created By'
        $data['sortField'] = 'l.created_date';
        $data['sortOrder'] = 'desc';
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join(COUNTRIES . ' as c', 'c.country_id=l.country_id', 'left');
        $this->db->join(COMPANY_MASTER . ' as cm', 'cm.company_id=l.company_id', 'left');
        $this->db->join(BRANCH_MASTER . ' as bm', 'bm.branch_id=l.branch_id', 'left');
        $this->db->join(CAMPAIGN_MASTER . ' as cr', 'cr.campaign_id=l.campaign_id', 'left');
        $this->db->join(LEAD_PRODUCTS_TRAN . ' as lp', 'lp.lead_id=l.lead_id', 'left');
        $this->db->join(PRODUCT_MASTER . ' as pm', 'pm.product_id=lp.product_id', 'left');
        $this->db->join(LANGUAGE_MASTER . ' as lg', 'lg.language_id=l.language_id', 'left');
        //$this->db->join(LOGIN . ' as lg', 'lg.login_id=l.created_by', 'left');
        $this->db->where('l.status', '1', false);
        $this->db->where('l.is_delete', '0', false);
        //$this->db->where('l.created_by', $this->session->userdata('LOGGED_IN')['ID'], false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->group_by($group_by);
        $dataarr = $this->db->get();

     
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
        force_download($filename, $data1);
    }

    
}