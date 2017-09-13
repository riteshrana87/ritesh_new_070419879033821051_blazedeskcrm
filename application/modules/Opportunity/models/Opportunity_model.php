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

class Opportunity_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_opportunity_list($per_page, $offset, $search_keywords_array, $search_keywords_between_array) {
        $sdata = array();
        $this->db->select('*')->from(PROSPECT_MASTER);
        //print_r($search_keywords_array);
        if (count($search_keywords_array) > 0)
            $this->db->where($search_keywords_array);
        //$this->db->where($search_keywords_between_array);
        //if(!empty($search_orderby_string)) $this->db->order_by($search_orderby_string);

        $this->db->limit($per_page, $offset);
        $query_result = $this->db->get();
        echo $this->db->last_query(); // shows last executed query

        if ($query_result->num_rows() > 0) {
            foreach ($query_result->result_array() as $row) {
                $sdata[] = array('prospect_name' => $row['prospect_name'], 'prospect_auto_id' => $row['prospect_auto_id'], 'branch_id' => $row['branch_id'], 'branch_id' => $row['branch_id'], 'branch_id' => $row['branch_id'], 'prospect_owner_id' => $row['prospect_owner_id'], 'status' => $row['status'], 'creation_date' => $row['creation_date'], 'contact_date' => $row['contact_date']);
            }
        }
        return $sdata;
    }

    public function get_total_opportunity_($search_keywords_array) {
        $this->db->select('*')->from(PROSPECT_MASTER);
        $this->db->where($search_keywords_array);
        //$this->db->where_between($search_keywords_between_array);
        $query_result = $this->db->get();

        return $query_result->num_rows();
    }

    public function searchterm_handler($field, $searchterm) {
        if ($searchterm) {
            $this->session->set_userdata($field, $searchterm);
            return $searchterm;
        } elseif ($this->session->userdata($field)) {
            $searchterm = $this->session->userdata($field);
            return $searchterm;
        } else {
            $searchterm = "";
            return $searchterm;
        }
    }

    function exportCsvData($dbSearch) {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = time() ."-". date('m-d-Y') . "_Opportunity.csv";
        $join_tables = array(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc' => 'pm.prospect_id=pc.prospect_id');
        $params['join_type'] = 'left';
        $table = PROSPECT_MASTER . ' as pm';
        $group_by = 'pm.prospect_id';
        $fields = array("pm.prospect_name,pm.prospect_auto_id,count(pc.prospect_id) as 'number of contact',pc.contact_name,
          pm.creation_date as 'client since',CASE WHEN pm.status_type =1 THEN ' Lead' END as status");
        //$fields = array('prospect_id'=>'pm.prospect_id','prospect_name'=>'pm.prospect_name','prospect_auto_id'=>'pm.prospect_auto_id','status'=>'pm.status','status_type'=>'pm.status_type','contact_name'=>'pc.contact_name','count(pm.prospect_id) as opp_count','count(pc.prospect_id) as contact_count');
        $data['sortField'] = 'pm.prospect_id';
        $data['sortOrder'] = 'desc';
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc', 'pm.prospect_id=pc.prospect_id', 'left');
        $this->db->join(OPPORTUNITY_REQUIREMENT . ' as or', 'pm.prospect_id=or.prospect_id', 'left');
        $this->db->where($dbSearch, '', false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->group_by($group_by);

        $dataarr = $this->db->get();
        //echo $this->db->last_query();
        //pr($dataarr);
        //die;
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
        force_download($filename, $data1);
    }
	/*
      @Author   : Rupesh Jorkar(RJ)
      @Desc     : Following function copy from Account Model.
      @Input    : 
      @Output   : 
      @Date     : 16/04/2016
     */
	function getBranchIdByName($branch_name){
		$this->db->select('branch_id');
		$this->db->from(BRANCH_MASTER);
		$this->db->where('branch_name',$branch_name);
		$dataarr = $this->db->get()->result();
		if(is_array($dataarr) && !empty($dataarr))
		{
			return $dataarr[0]->branch_id;
		}else
		{
			return 0;
		}
	}
	function getComapnyIdByName($company_name)
	{

		$this->db->select('company_id');
		$this->db->from(COMPANY_MASTER);
		$where_array = array('company_name'=>$company_name,'status'=>'1');
		$this->db->where($where_array);
		$dataarr = $this->db->get()->result();

		//echo $this->db->last_query();
		//pr($dataarr);
		if(is_array($dataarr) && !empty($dataarr))
		{
			return $dataarr[0]->company_id;
		}else
		{
			return 0;
		}
	}
    function getCountryIdByName($country_name)
    	{
    		$this->db->select('country_id');
    		$this->db->from(COUNTRIES);
    		$this->db->where('country_name',$country_name);
    		$dataarr = $this->db->get()->result();

    		 
    		//pr($dataarr);
    		if(is_array($dataarr) && !empty($dataarr))
    		{

    			return $dataarr[0]->country_id;
    		}else
    		{
    			return 0;
    		}
    	}	
    function getProductIdByName($product_name){
    			

    		$this->db->select('product_id');
    		$this->db->from(PRODUCT_MASTER);
    		$this->db->where('product_name',$product_name);
    		$dataarr = $this->db->get()->result();
    	   	
    		if(is_array($dataarr) && !empty($dataarr))
    		{
    			return $dataarr[0]->product_id;
    		}else
    		{
    			return 0;
    		}
    	}
        
    function exportOpportunity()
    {
         $login_id = $this->session->userdata('LOGGED_IN')['ID'];
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = time() ."-". date('m-d-Y') . "_Opportunity.csv";
        $table = PROSPECT_MASTER . ' as pm';
        $group_by = 'pm.prospect_id';
        $fields = array("pm.prospect_name as 'Opportunity Name',"
            . "cm.company_name as 'Company Name',"
            . "cm.email_id as 'Company Email ID',"
            . "cm.website as 'Website',"
            . "cm.phone_no as 'Company Phone No',"
            . "pm.address1 as 'Address1',"
            . "pm.address2 as 'Address2',"
            //. "CASE pm.language_id WHEN pm.language_id = 1 THEN 'English' WHEN pm.language_id = 2 THEN 'Spanish' ELSE '' END as 'Language',"
            . "lg.language_name as 'Language',"
		    . "pm.postal_code as 'Postal Code',"
            . "pm.city as 'City',"
            . "pm.state as 'State',"
            . "c.country_name as 'Country',"
            . "bm.branch_name as 'Branch',"
            . "pm.estimate_prospect_worth as 'Estimate Prospect Worth',"
            . "CASE pm.number_type1 WHEN 1 THEN 'Home' WHEN  2 THEN 'Mobile' WHEN 3 THEN 'Office'  ELSE '' END as 'NumberType1',"
            . "pm.phone_no as 'PhoneNumber1',"
            . "CASE pm.number_type2 WHEN 1 THEN 'Home' WHEN 2 THEN 'Mobile' WHEN  3 THEN 'Office'  ELSE '' END as 'NumberType2',"
            . "pm.phone_no2 as 'PhoneNumber2',"
            . "GROUP_CONCAT(pm1.product_name) as 'Interested Products',"
            . "or.requirement_description as 'Description'");

        $data['sortField'] = 'pm.created_date';
        $data['sortOrder'] = 'desc';
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join(COUNTRIES . ' as c', 'c.country_id=pm.country_id', 'left');
        $this->db->join(COMPANY_MASTER . ' as cm', 'cm.company_id=pm.company_id', 'left');
        $this->db->join(BRANCH_MASTER . ' as bm', 'bm.branch_id=pm.branch_id', 'left');
        $this->db->join(PROSPECT_PRODUCTS_TRAN . ' as ppt', 'ppt.prospect_id=pm.prospect_id', 'left');
        $this->db->join(PRODUCT_MASTER . ' as pm1', 'pm1.product_id=ppt.product_id', 'left');
        $this->db->join(OPPORTUNITY_REQUIREMENT . ' as or', 'or.prospect_id=pm.prospect_id', 'left');
		$this->db->join(LANGUAGE_MASTER . ' as lg', 'lg.language_id=pm.language_id', 'left');
        $this->db->where('pm.status', '1', false);
        $this->db->where('pm.status_type', '1', false);
        $this->db->where('pm.is_delete', '0', false);
        //$this->db->where('pm.created_by', $login_id, false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->group_by($group_by);
        $dataarr = $this->db->get();
        // echo $this->db->last_query(); exit;
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);

        force_download($filename, $data1);
    }

}
