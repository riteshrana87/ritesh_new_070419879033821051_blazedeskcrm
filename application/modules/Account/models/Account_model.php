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

class Account_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function exportCsvData($dbSearch) {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = time() ."-". date('m-d-Y') . "_Client.csv";
        $join_tables = array(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc' => 'pm.prospect_id=pc.prospect_id');
        $params['join_type'] = 'left';
        $table = PROSPECT_MASTER . ' as pm';
        $group_by = 'pm.prospect_id';
        $fields = array("pm.prospect_name,pm.prospect_auto_id,count(pc.prospect_id) as 'number of contact',pc.contact_name,
            pm.creation_date as 'client since',CASE WHEN pm.status_type =3 THEN ' Account' END as status");
        //$fields = array('prospect_id'=>'pm.prospect_id','prospect_name'=>'pm.prospect_name','prospect_auto_id'=>'pm.prospect_auto_id','status'=>'pm.status','status_type'=>'pm.status_type','contact_name'=>'pc.contact_name','count(pm.prospect_id) as opp_count','count(pc.prospect_id) as contact_count');
        $data['sortField'] = 'pm.prospect_id';
        $data['sortOrder'] = 'desc';
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc', 'pm.prospect_id=pc.prospect_id', 'left');
        $this->db->where($dbSearch, '', false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->group_by($group_by);

        $dataarr = $this->db->get();

        //pr($dataarr);

        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
        force_download($filename, $data1);
    }

    function getComapnyIdByName($company_name) {

        $this->db->select('company_id');
        $this->db->from(COMPANY_MASTER);
        $where_array = array('company_name' => $company_name, 'status' => '1');
        $this->db->where($where_array);
        $dataarr = $this->db->get()->result();

        //echo $this->db->last_query();
        //pr($dataarr);
        if (is_array($dataarr) && !empty($dataarr)) {
            return $dataarr[0]->company_id;
        } else {
            return 0;
        }
    }

    function getCountryIdByName($country_name) {
        $this->db->select('country_id');
        $this->db->from(COUNTRIES);
        $this->db->where('country_name', $country_name);
        $dataarr = $this->db->get()->result();


        //pr($dataarr);
        if (is_array($dataarr) && !empty($dataarr)) {

            return $dataarr[0]->country_id;
        } else {
            return 0;
        }
    }

    function getBranchIdByName($branch_name) {
        $this->db->select('branch_id');
        $this->db->from(BRANCH_MASTER);
        $this->db->where('branch_name', $branch_name);
        $dataarr = $this->db->get()->result();
        if (is_array($dataarr) && !empty($dataarr)) {
            return $dataarr[0]->branch_id;
        } else {
            return 0;
        }
    }

    function getProductIdByName($product_name) {


        $this->db->select('product_id');
        $this->db->from(PRODUCT_MASTER);
        $this->db->where('product_name', $product_name);
        $dataarr = $this->db->get()->result();

        if (is_array($dataarr) && !empty($dataarr)) {
            return $dataarr[0]->product_id;
        } else {
            return 0;
        }
    }

}
