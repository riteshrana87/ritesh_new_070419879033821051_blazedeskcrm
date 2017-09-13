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

}
