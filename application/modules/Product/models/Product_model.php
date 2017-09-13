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

class Product_model extends CI_Model {

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
        $filename = time()."-".date('m-d-Y'). "_Product.csv";
        $table = PRODUCT_MASTER . ' as pm';
        $group_by = 'pm.product_id';
        $fields = array("pm.product_name as 'Product Name',"
            . "pm.product_type as 'Product Type',"
            . "pm.product_description as 'Product Description',"
            . "pm.purchase_price_unit as 'Purchase Price Unit',"
            . "pm.sales_price_unit as 'Sales Price Unit',"
            . "pm.gross_margin as 'Gross Margin',"
            . "pt.tax_name as 'Product Tax Name',"
            . "pt.tax_percentage as 'Tax Percentage',"
            . "(SELECT group_concat(gm.product_group_name) FROM blzdsk_product_group_master as gm,`blzdsk_product_group_relation` as `pg` WHERE gm.product_group_id=pg.product_group_id AND pg.product_id=pm.product_id) as 'Product Group',"
            . "CONVERT(c.currency_code USING utf8) as 'Currency Symbol'");

        $data['sortField'] = 'pm.created_date';
        $data['sortOrder'] = 'desc';
        $this->db->select($fields);
        $this->db->from($table);
         $this->db->join(PRODUCT_GROUP_RELATION . ' as gr', 'gr.product_id=pm.product_id', 'left');
        $this->db->join(PRODUCT_TAX_MASTER . ' as pt', 'pt.tax_id=pm.product_tax_id', 'left');
        $this->db->join(COUNTRIES . ' as c', 'c.country_id=pm.currency_symbol', 'left');
       
        $del_str = "'0'";
      //  $this->db->where('pm.status', '1', false);
        $this->db->where('pm.is_delete', $del_str, false);
        $this->db->order_by($data['sortField'], $data['sortOrder']);
        $this->db->group_by($group_by);
        $dataarr = $this->db->get();
        
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
        force_download($filename, $data1);
      
    }
    	

}
