<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  @Author : Sanket jayani
  @Desc   : Model For Opportunity Module
  @Input 	:
  @Output	:
  @Date   : 12/01/2016
 */

class Tele_model extends CI_Model {

    function __construct() {
            parent::__construct();
    }

    	
    	 
    	
    function getTaxIdByName($tax_name)
    {
        $this->db->select('tax_id');
        $this->db->from(PRODUCT_TAX_MASTER);
        $this->db->where('tax_name',$tax_name);
        $dataarr = $this->db->get()->result();

        if(is_array($dataarr) && !empty($dataarr))
        {
                return $dataarr[0]->tax_id;
        }else
        {
                return 0;
        }
    }
    
    function getCurrencySymbolIdByName($currency_symbol)
    {
        
        $this->db->select('country_id');
        $this->db->from(COUNTRIES);
        $this->db->where('currency_code',$currency_symbol);
        $this->db->where('use_status','1');
        $this->db->where('country_status','1');
        $this->db->where('is_delete_currency','0');
        $dataarr = $this->db->get()->result();
        
        if(is_array($dataarr) && !empty($dataarr))
        {
            return $dataarr[0]->country_id;
        }else
        {
            $country_code = getDefaultCurrencyInfo();
            return $country_code['country_id'];
        }
    }
    
    function getProductGroupIdByName($group_name)
    {
        $this->db->select('product_group_id');
        $this->db->from(PRODUCT_GROUP_MASTER);
        $this->db->where('product_group_name',$group_name);
        $dataarr = $this->db->get()->result();

        if(is_array($dataarr) && !empty($dataarr))
        {
                return $dataarr[0]->product_group_id;
        }else
        {
                return 0;
        }
    }
    //Function Created by Sanket On 29/04/2016 Pass aaray and generate csv
    
    function export_to_csv($input_array, $output_file_name, $delimiter)
    {
        /** open raw memory as file, no need for temp files, be careful not to run out of memory thought */
        $f = fopen('php://memory', 'w');
        /** loop through array  */
        foreach ($input_array as $line) {
            /** default php csv handler **/
            fputcsv($f, $line, $delimiter);
        }
        /** rewrind the "file" with the csv lines **/
        fseek($f, 0);
        /** modify header to be downloadable csv file **/
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
        /** Send file to browser for download */
        fpassthru($f);
    }
    /** Array to convert to csv */	
    function exportProduct() 
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "TeleMarketing_".datetimeformat(). "_tele.csv";
        $table = TELE_MARKETING . ' as tm';
        $table2 = TELE_MARKETING ;
        
        $data['sortField'] = 'tm.created_date';
        $data['sortOrder'] = 'desc';
        $this->db->select(array("concat(L.firstname,' ',L.lastname) as `Employee Name`",'(select count(user_id) as Number_of_call from blzdsk_'.$table2.' where user_id=L.login_id) as `Number of call`',
        '(select count(user_id) as status1 from blzdsk_'.$table2.' 
        where user_id=L.login_id and status=1) as `Positive Requested information`','(select count(user_id) as status2 from blzdsk_'.$table2.' 
        where user_id=L.login_id and status=2) as `Positive Demo Scheduled`','(select count(user_id) as status3  from blzdsk_'.$table2.' 
        where user_id=L.login_id and status=3) as `Positive Became client`','(select count(user_id) as status4 from blzdsk_'.$table2.'
         where user_id=L.login_id and status=4) as `Negative not interested`','(select count(user_id) as status5 from blzdsk_'.$table2.'
          where user_id=L.login_id and status=5) as `Voicemail`','(select count(user_id) as status6  from blzdsk_'.$table2.'
           where user_id=L.login_id and status=6) as `Call Back request`'),'',false);
       
         $this->db->join(LOGIN . ' as L', 'L.login_id=user_id', 'left');
        
        $del_str = "'0'";
      //  $this->db->where('pm.status', '1', false);
        $this->db->where('tm.is_delete', $del_str, false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->from($table);
        $this->db->group_by('user_id');
        $dataarr = $this->db->get();
        
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
        force_download($filename, $data1);
      
    }
    	

}
