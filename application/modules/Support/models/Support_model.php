<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  @Author : Ishani Dave
  @Desc   : Model For Support Report

 */

class Support_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function ExportCSV($ticket_id) {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Support_Report.csv";

        //Get Records to Export CSV
        
        $this->db->select("tm.ticket_subject AS Ticket Subject,tm.ticket_desc AS Ticket Description,pm.prospect_name AS Ticket Holder,l.firstname AS Created By,s.status_name AS Status,st.type AS Type,tm.created_date AS Created On,tm.due_date AS Due Date");
        $this->db->from('blzdsk_ticket_master tm');
        $this->db->join('blzdsk_login as l', 'l.login_id = tm.user_id', 'left');
        $this->db->join('blzdsk_contact_master as cm', 'cm.contact_id = tm.contact_id', 'left');
        $this->db->join('blzdsk_prospect_master as pm', 'pm.prospect_id = tm.client_id', 'left');
        $this->db->join('blzdsk_support_type as st', 'st.support_type_id = tm.type', 'left');
        $this->db->join('blzdsk_support_status as s', 's.status_id = tm.status', 'left');
        $this->db->where('tm.is_delete', '0');
        $this->db->order_by("tm.ticket_id", "asc");
        $result = $this->db->get();
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function ExportPDF() {

        $this->db->select('tm.*,l.firstname,l.lastname,cm.contact_name,pm.prospect_name,st.type,s.status_name,tu.support_user,u.firstname as fname, u.lastname as lname');
        $this->db->from('blzdsk_ticket_master tm');
        $this->db->join('blzdsk_login as l', 'l.login_id = tm.user_id', 'left');
        $this->db->join('blzdsk_contact_master as cm', 'cm.contact_id = tm.contact_id', 'left');
        $this->db->join('blzdsk_prospect_master as pm', 'pm.prospect_id = tm.client_id', 'left');
        $this->db->join('blzdsk_support_type as st', 'st.support_type_id = tm.type', 'left');
        $this->db->join('blzdsk_support_status as s', 's.status_id = tm.status', 'left');
        $this->db->join('blzdsk_ticket_support_user as tu', 'tu.ticket_id = tm.ticket_id', 'left');
        $this->db->join('blzdsk_login as u', 'u.login_id = tu.support_user', 'left');
        $this->db->where('tm.is_delete', '0');
        $query = $this->db->get();
        $status_name = $query->result_array();

        return $query->result();
    }

    function get_data() {
        $this->db->distinct();
        $this->db->select('MONTH(tm.created_date) AS "month",tm.ticket_id,s.status_name');
        $this->db->from('blzdsk_ticket_master tm');
        $this->db->where('tm.is_delete !=', '1');
        $this->db->join('blzdsk_support_status as s', 's.status_id = tm.status', 'left');
        $query = $this->db->get();
        $status_name = $query->result_array();

        return $status_name;
    }

}
