<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        if(checkPermission('Product','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->viewname = $this->uri->segment(1);
        $this->load->helper(array('form','url'));
        $this->load->library(array('form_validation','Session'));
        $this->load->model('Product_model');
    }
    
    /*
     @Author : Disha Patel
     @Desc   : Product Index Page
     @Input  :
     @Output :
     @Date   : 10/02/2016
     */
    public function index()
    {
        $data['crnt_view'] = $this->viewname;
        
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('products_data');
        }

        $searchsort_session = $this->session->userdata('products_data');
        
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
                $sortfield = 'product_id';
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
        $data['sales_view']=$this->viewname;
        
        $fields = array("pm.product_id, pm.product_name, pm.product_type, pm.product_description, pm.purchase_price_unit, pm.sales_price_unit, pm.gross_margin, pm.currency_symbol, cm.currency_symbol, pm.status, pgm.product_group_name");
        $params1['join_type'] = 'left';
        $params1['join_tables'] = array(PRODUCT_GROUP_RELATION. ' as pgr' => 'pgr.product_id = pm.product_id',                                               PRODUCT_GROUP_MASTER . ' as pgm' => 'pgr.product_group_id = pgm.product_group_id',
                                        COUNTRIES . ' AS cm' => 'cm.country_id = pm.currency_symbol'
            );
        $group_by = 'pm.product_name';
        
        //Search
        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            //$match=array('pm.product_name'=>$searchtext,'pm.product_type'=>$searchtext);
            $match = "";
            $match_where = '((pm.product_name LIKE "%'.$searchtext.'%"ESCAPE "!" OR pm.product_type LIKE "%'.$searchtext.'%" OR pm.product_description LIKE "%'.$searchtext.'%" OR pm.sales_price_unit LIKE "%'.$searchtext.'%"ESCAPE "!" OR pm.purchase_price_unit LIKE "%'.$searchtext.'%"ESCAPE "!" OR pm.gross_margin LIKE "%'.$searchtext.'%"ESCAPE "!") AND (pm.is_delete = "0"))';
            
            //Get count of rows From PRODUCT_MASTER Table
            if($this->input->post('sortfield')!='product_group_name'){
                $config['total_rows'] = count($this->common_model->get_records(PRODUCT_MASTER . ' as pm', $fields, $params1['join_tables'], $params1['join_type'], '', $match, '', '', $sortfield, $sortby, $group_by, $match_where,'','',''));
            } else {
                $config['total_rows'] = count($this->common_model->get_records(PRODUCT_MASTER . ' as pm', $fields, $params1['join_tables'], $params1['join_type'], '', $match, '', '',$sortfield, $sortby, $group_by, $match_where,'','',''));
            }
            
            //Get Records From PRODUCT_MASTER Table
            $product_data = $this->common_model->get_records(PRODUCT_MASTER . ' as pm', $fields, $params1['join_tables'],$params1['join_type'], '', $match, $config['per_page'], $uri_segment,$sortfield,$sortby, $group_by, $match_where,'','','');
            
            //Join With PRODUCT_GROUP_MASTER and PRODUCT_GROUP_RELATION to get product name
            $product_info = array();
            foreach($product_data as $product)
            {
                //Get Product Group name
                $params['join_tables'] = array(PRODUCT_GROUP_MASTER . ' AS pgm' => 'pgr.product_group_id = pgm.product_group_id');
                $params['join_type'] = 'left';
                $where = array("product_id" => $product['product_id'], "pgm.is_delete" => "0", "pgr.is_delete"=>"0");
                $group_data  = $this->common_model->get_records(PRODUCT_GROUP_RELATION .' as pgr', array("pgm.product_group_name"),$params['join_tables'],$params['join_type'],$where);
                $product['group_name'] = $group_data; 
                $product_info[] = $product;
            }
            $data['product_data'] = $product_info;
        } else {
            //Without Search
            $where = array("pm.is_delete" => "'0'");
            
            //Get count of rows
            if($this->input->post('sortfield')!='product_group_name'){
            $config['total_rows'] = count($this->common_model->get_records(PRODUCT_MASTER . ' as pm', $fields, $params1['join_tables'],$params1['join_type'], '', '', '', '',$sortfield, $sortby, $group_by, $where,'','',''));
            } else {
                $config['total_rows'] = count($this->common_model->get_records(PRODUCT_MASTER . ' as pm', $fields, $params1['join_tables'],$params1['join_type'], '', '', '', '',$sortfield, $sortby, $group_by, $where,'','',''));
            }
            //Get Records From PRODUCT_MASTER Table
            $product_data = $this->common_model->get_records(PRODUCT_MASTER . ' as pm', $fields,$params1['join_tables'],$params1['join_type'], '', '', $config['per_page'],$uri_segment, $sortfield, $sortby, $group_by, $where,'','','');
            
            //Join With PRODUCT_GROUP_MASTER and PRODUCT_GROUP_RELATION to get product name
            $product_info = array();
            foreach($product_data as $product)
            {
                //Start Query for get Gourp name
                $params['join_tables'] = array(PRODUCT_GROUP_MASTER . ' as pgm' => 'pgr.product_group_id = pgm.product_group_id');
                $params['join_type'] = 'left';
                $where = array("product_id" => $product['product_id'], "pgm.is_delete" => "0", "pgm.status" => "1", "pgr.is_delete"=>"0");
                //$where = "product_id = ".$product['product_id']."";
                $group_data  = $this->common_model->get_records(PRODUCT_GROUP_RELATION .' as pgr', array("pgm.product_group_name, pgm.product_group_id"),$params['join_tables'],$params['join_type'],$where);
                
                $product['group_name'] = $group_data; 
                $product_info[] = $product;
            }
            $data['product_data'] = $product_info;
            $data['sales_view']=$this->viewname;
            
        }
        $data['header'] = array('menu_module'=>'crm');
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
        $this->session->set_userdata('products_data', $sortsearchpage_data);
        
        //Pass Records In View         	
	if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname.'/ajaxlist',$data);
        } else {
            $data['main_content'] = '/'.$this->viewname;
            $this->parser->parse('layouts/CMSTemplate', $data);
        }
    }

    /*
     @Author : Disha Patel
     @Desc   : Product insertdata
     @Input  :
     @Output : Insert data in database and redirect
     @Date   : 10/02/2016
    */
    public function insertdata()
    {
        if(isset($_POST) && !empty($_POST)) {
            $data['crnt_view'] = $this->viewname;

            //insert the Product details into database
            $data = array(
                'product_name' => trim($this->input->post('prod_name')),
                'product_type' => trim($this->input->post('prod_type')),
                'product_description' => trim($this->input->post('prod_desc', false)),
                'purchase_price_unit' => trim($this->input->post('prod_ppu')),
                'sales_price_unit' => trim($this->input->post('prod_spu')),
                'gross_margin' => trim($this->input->post('prod_gm')),
                'currency_symbol' => $this->input->post('currency_id'),
                'product_tax_id' => $this->input->post('tax_id'),
                'status' => $this->input->post('status'),
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat()
            );

            //Check Duplicate Entry in PRODUCT_MASTER table
            $table_dupl = PRODUCT_MASTER;
            $fields_dupl = array("product_name");
            $where_dupl = array('product_name' => $this->input->post('prod_name'), 'is_delete'=> '0');
            $prod_name = $this->common_model->get_records($table_dupl. ' AS pm', $fields_dupl,'','',$where_dupl);

            //Insert Product details if no Duplicate Entry else Show Error
            if(count($prod_name) == 0){
                //Insert
                $no_insert = true;
                $insert_prod = $this->common_model->insert(PRODUCT_MASTER,$data);
            }else {
                //error
                $msg = $this->lang->line('duplicate_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
                redirect($this->viewname);
            }

            if($no_insert == true){
                //Insert Product Groups Records in PRODUCT_GROUP_RELATION table
                $table_rel = PRODUCT_GROUP_RELATION;
                $products_grp_count = count($this->input->post('prod_group'));

                if($products_grp_count>0){
                    for($i=0; $i<$products_grp_count; $i++){
                        $data_rel = array(
                            'product_group_id' => $this->input->post('prod_group')[$i], 
                            'product_id' => $insert_prod,
                            'discount_option' => 'prsnt',
                            'product_qty' => 1,
                            'product_total' => $this->input->post('prod_spu')*1,
                            'product_tax_id' => $this->input->post('tax_id'),
                            'product_group_status' => '1'
                            );
                        $insert_grp_prod_rel = $this->common_model->insert($table_rel,$data_rel);

                        $fields = array("pgm.product_group_total_amt, pgm.product_group_discounted_amt, pgm.product_group_tax_amt, ptm.tax_percentage");
                        $params['join_tables'] = array(PRODUCT_GROUP_RELATION . ' AS pgr' => 'pgr.product_group_id = pgm.product_group_id', PRODUCT_TAX_MASTER . ' AS ptm' => 'pgr.product_tax_id = ptm.tax_id');
                        $params['join_type'] = 'left';
                        $match = array('pgm.product_group_id'=> $this->input->post('prod_group')[$i]);
                        $grp_data = $this->common_model->get_records(PRODUCT_GROUP_MASTER . ' AS pgm', $fields, $params['join_tables'], $params['join_type'], $match);

                        $total_amt = $grp_data[0]['product_group_total_amt'] + $this->input->post('prod_spu');
                        $discounted_amt = $grp_data[0]['product_group_discounted_amt'] + $this->input->post('prod_spu');
                        $total_tax_amt = $grp_data[0]['product_group_tax_amt'] + ($this->input->post('prod_spu')* 1 * $grp_data[0]['tax_percentage']/100);
                        $data_grp = array('product_group_total_amt'=> $total_amt, 'product_group_discounted_amt' => $discounted_amt, 'product_group_tax_amt'=> $total_tax_amt);
                        $where_grp = array('product_group_id' => $this->input->post('prod_group')[$i]);
                        $this->common_model->update(PRODUCT_GROUP_MASTER, $data_grp, $where_grp);

                    }
                }
            }

            if ($insert_prod || $insert_grp_prod_rel)
            {
                //success
                $msg = $this->lang->line('add_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            }
            else
            {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect($this->viewname);
        }
        else {
            exit('No direct script access allowed');
        }
    }
    
    /*
     @Author : Disha Patel
     @Desc   : Product AddEdit Page
     @Input  :
     @Output : 
     @Date   : 19/01/2016
    */
    public function AddEditProduct()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{
            $data['crnt_view'] = $this->viewname;

            //Get Records From PRODUCT_MASTER Table
            $table	= PRODUCT_MASTER;
            $match 	= "";
            $fields = array("product_id, product_name, product_type, product_description, purchase_price_unit, sales_price_unit, gross_margin, currency_symbol, product_tax_id, status");
            $data['information']  = $this->common_model->get_records($table,$fields,'','',$match);

            //Get ProductGroup Info from Product Group Master table for dropdown: Edit
            $table_grp = PRODUCT_GROUP_MASTER;
            $fields_grp = array("product_group_id, product_group_name");
            $match_grp = array('is_delete' => '0', 'status' => '1');
            $data['product_group_info'] = $this->common_model->get_records($table_grp,$fields_grp, '', '', $match_grp);

            //Get Tax name from PRODUCT_TAX_MASTER
            $table_tax = PRODUCT_TAX_MASTER . " AS ptm";
            $fields_tax = array("ptm.tax_id, ptm.tax_name");
            $where_tax = array('ptm.is_delete' => '0');
            $data['tax_info'] = $this->common_model->get_records($table_tax, $fields_tax, '', '', $where_tax);

            //Get Currency symbol from COUNTRIES table for dropdown
            $table_cur = COUNTRIES . ' AS cm';
            $fields_cur = array("cm.currency_symbol, cm.country_id");
            $where_cur = array('use_status'=> '1', 'country_status'=> '1', 'cm.is_delete_currency' => '0');
            $data['currency_info'] = $this->common_model->get_records($table_cur,$fields_cur, '', '', $where_cur);

            //Pass Table:PRODUCT_MASTER Record In View
            $data['main_content'] = '/AddEditProduct';
            $this->load->view('AddEditProduct',$data);
        }
    }

    /*
     @Author : Disha Patel
     @Desc   : ProductList Edit Page
     @Input 	:
     @Output	:
     @Date   : 19/01/2016
     */
    public function edit($id)
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{
        //Get ProductGroup Info
        $table_grp	= PRODUCT_GROUP_MASTER;
        $fields_grp     = array("product_group_id, product_group_name");
        $where          = array("is_delete" => "0", "status" => "1");
        $data['product_group_info'] = $this->common_model->get_records($table_grp,$fields_grp,'', '', $where);
        
        //Get Tax name from PRODUCT_TAX_MASTER
        $table_tax     = PRODUCT_TAX_MASTER . " AS ptm";
        $fields_tax    = array("ptm.tax_id, ptm.tax_name");
        $where_tax     = array('ptm.is_delete' => '0');
        $data['tax_info'] = $this->common_model->get_records($table_tax, $fields_tax, '', '', $where_tax);
        
        //Get All Currency symbol from COUNTRIES table for dropdown
        $table_cur     = COUNTRIES . ' AS cm';
        $fields_cur    = array("cm.currency_symbol, cm.country_id");
        $where_cur     = array('use_status'=> '1', 'country_status'=> '1', 'cm.is_delete_currency' => '0');
        $data['currency_info'] = $this->common_model->get_records($table_cur, $fields_cur, '', '', $where_cur);
        
        //Get selected currency in dropdown
        $table_pro_cur     = PRODUCT_MASTER . ' AS pm';
        $fields_pro_cur    = array("pm.currency_symbol as 'product_currency_id', cm.currency_symbol, cm.country_id");
        $where_pro_cur     = array('pm.product_id' => $id, 'use_status'=> '1', 'country_status'=> '1', 'cm.is_delete_currency' => '0');
        $params_pro_cur['join_tables'] = array(COUNTRIES . ' AS cm' => 'pm.currency_symbol = cm.country_id');
	$params_pro_cur['join_type'] = 'left';
        $data['prod_currency_info'] = $this->common_model->get_records($table_pro_cur, $fields_pro_cur, $params_pro_cur['join_tables'], $params_pro_cur['join_type'], $where_pro_cur);
        
        //Get selected tax in dropdown
        $table_pro_tax     = PRODUCT_MASTER . ' AS pm';
        $fields_pro_tax    = array("pm.product_tax_id, ptm.tax_id, ptm.tax_name");
        $where_pro_tax     = array('pm.product_id' => $id, 'ptm.is_delete' => '0');
        $params_pro_tax['join_tables'] = array(PRODUCT_TAX_MASTER . ' AS ptm' => 'pm.product_tax_id = ptm.tax_id');
	$params_pro_tax['join_type'] = 'left';
        $data['prod_tax_info'] = $this->common_model->get_records($table_pro_tax,$fields_pro_tax, $params_pro_tax['join_tables'], $params_pro_tax['join_type'], $where_pro_tax);
        
        //Get selected product groups in dropdown
        $table2     = PRODUCT_GROUP_RELATION . ' AS pgr';
        $fields2    = array("pgr.product_group_id, pgm.product_group_name");
        $params['join_tables'] = array(PRODUCT_GROUP_MASTER . ' as pgm' => 'pgm.product_group_id=pgr.product_group_id');
	$params['join_type'] = 'left';
        $match      = array("pgr.product_id" => $id, "pgr.is_delete" => '0');
        $data['product_rel_info'] = $this->common_model->get_records($table2,$fields2, $params['join_tables'], $params['join_type'], $match);
        //echo $this->db->last_query();exit;
        
        //Get Records From PRODUCT_MASTER Table
        $table	= PRODUCT_MASTER . ' AS pm';
        $match 	= "pm.product_id = ".$id;
        $fields = array("pm.product_id, pm.product_name, pm.product_type, pm.product_description, pm.purchase_price_unit, pm.sales_price_unit, pm.gross_margin, pm.status");
        $data['editRecord']  = $this->common_model->get_records($table,$fields, '', '', $match);

        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/AddEditProduct';
        $this->load->view('AddEditProduct', $data);
        }
    }
            
    /*
     @Author : Disha Patel
     @Desc   : Product Updatedata
     @Input  : 
     @Output : Update data in database and redirect
     @Date   : 12/01/2016
     */
    public function updatedata()
    {
//        print_r($_POST);exit;
        if(isset($_POST) && !empty($_POST)) {
            $id = $this->input->post('prod_id');

            //Get group ids based on product id
            $match1  = array("product_id"=> $id, "is_delete"=>"0");
            $fields1 = array("product_group_id, product_total");
            $data['group_ids_total'] = $this->common_model->get_records(PRODUCT_GROUP_RELATION,$fields1,'', '', $match1);

            //if group is selected then do calculation
            if(count($data['group_ids_total'])>0){
                foreach ($data['group_ids_total'] as $group_ids_total){

                    //Get Total amount, Discounted amount, Total tax, tax percentage, spu and qty
                    $match2  = array("pgm.product_group_id"=> $group_ids_total['product_group_id']);
                    $fields2 = array("pgm.product_group_total_amt, pgm.product_group_discounted_amt, pgm.product_group_tax_amt, pm.sales_price_unit, pgr.product_qty, ptm.tax_percentage");
                    $params['join_tables'] = array(PRODUCT_GROUP_RELATION . ' AS pgr' => 'pgr.product_group_id = pgm.product_group_id', 
                                                PRODUCT_MASTER . ' AS pm' => 'pgr.product_id = pm.product_id', 
                                                PRODUCT_TAX_MASTER . ' AS ptm' => 'pgr.product_tax_id = ptm.tax_id');
                    $params['join_type'] = 'left';
                    $group_data = $this->common_model->get_records(PRODUCT_GROUP_MASTER . ' AS pgm', $fields2, $params['join_tables'], $params['join_type'], $match2);

                    //Calculate Total amount and Discounted amount
                    $total = $group_ids_total['product_total'];
                    $total_amt = $group_data[0]['product_group_total_amt'] - $total;
                    $discounted_amt = $group_data[0]['product_group_total_amt'] - $total;
                    $tax_amt = $group_data[0]['product_group_tax_amt'] - ($group_data[0]['sales_price_unit'] * $group_data[0]['product_qty']* $group_data[0]['tax_percentage']/100);

                    //Update Product Group
                    $update_grp = array('product_group_total_amt'=> $total_amt, 'product_group_discounted_amt'=> $discounted_amt, 'product_group_tax_amt'=>$tax_amt);
                    $where_grp = array('product_group_id'=> $group_ids_total['product_group_id']);
                    $this->common_model->update(PRODUCT_GROUP_MASTER, $update_grp, $where_grp);
                }
            }

            //Get Records From PRODUCT_MASTER Table
            $table	= PRODUCT_MASTER;
            $match 	= "product_id = ".$id;
            $fields = array("product_id, product_name, product_type, product_description, purchase_price_unit, sales_price_unit, gross_margin, status");

            $data['editRecord']  = $this->common_model->get_records($table,$fields,'', '', $match);
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;

            $data = array(
                            'product_name' => trim($this->input->post('prod_name')),
                            'product_type' => trim($this->input->post('prod_type')),
                            'product_description' => trim($this->input->post('prod_desc',false)),
                            'purchase_price_unit' => trim($this->input->post('prod_ppu')),
                            'sales_price_unit' => trim($this->input->post('prod_spu')),
                            'gross_margin' => trim($this->input->post('prod_gm')),
                            'currency_symbol' => $this->input->post('currency_id'),
                            'product_tax_id' => $this->input->post('tax_id'),
                            'status' => $this->input->post('status'),
                            'modified_date' => datetimeformat()
            );

            //Check Duplicate Entry in PRODUCT_MASTER table
            $table_dupl = PRODUCT_MASTER;
            $fields_dupl = array("product_name");
            $where_dupl = array('product_name' => $this->input->post('prod_name'), 'is_delete'=> '0', 'product_id!=' => $id);
            $prod_name = $this->common_model->get_records($table_dupl. ' AS pm', $fields_dupl,'','',$where_dupl);

            //Update Product details if no Duplicate Entry else Show Error
            $where = array('product_id' => $id);
            if(count($prod_name) == 0){
                //Insert
                $no_insert = true;
                $update_prod = $this->common_model->update(PRODUCT_MASTER, $data, $where);
            }else {
                //error
                $msg = $this->lang->line('duplicate_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
                redirect($this->viewname);
            }

            if($no_insert == true){
                //Delete product group from PRODUCT_GROUP_RELATION
                $where_rel = array('product_id' => $id);
                $data_rel = array("is_delete"=>'1');
                $this->common_model->update(PRODUCT_GROUP_RELATION, $data_rel, $where_rel);

                //$table_rel = PRODUCT_GROUP_RELATION;
                $products_grp_count = count($this->input->post('prod_group'));

                if($products_grp_count>0){
                    for($i=0; $i<$products_grp_count; $i++){
                        //Insert Data into PRODUCT_GROUP_RELATION table
                        $data_rel = array(
                            'product_group_id' => $this->input->post('prod_group')[$i],
                            'product_id' => $id,
                            'discount_option' => 'prsnt',
                            'product_qty' => 1,
                            'product_total' => $this->input->post('prod_spu')*1,
                            'product_tax_id' => $this->input->post('tax_id'),
                            'product_group_status' => '1'
                        );
                        $this->common_model->insert(PRODUCT_GROUP_RELATION, $data_rel);

                        //Get all data for calculation
                        $fields = array("pgm.product_group_total_amt, pgm.product_group_discounted_amt, pgm.product_group_tax_amt, ptm.tax_percentage");
                        $params['join_tables'] = array(PRODUCT_GROUP_RELATION . ' AS pgr' => 'pgr.product_group_id = pgm.product_group_id', PRODUCT_TAX_MASTER . ' AS ptm' => 'pgr.product_tax_id = ptm.tax_id');
                        $params['join_type'] = 'left';
                        $match = array('pgm.product_group_id'=> $this->input->post('prod_group')[$i]);
                        $grp_data = $this->common_model->get_records(PRODUCT_GROUP_MASTER . ' AS pgm', $fields, $params['join_tables'], $params['join_type'], $match);

                        //Calculate Total amount, Discounted amount, Total tax
                        $total_amt = $grp_data[0]['product_group_total_amt'] + $this->input->post('prod_spu');
                        $discounted_amt = $grp_data[0]['product_group_discounted_amt'] + $this->input->post('prod_spu');
                        $total_tax_amt = $grp_data[0]['product_group_tax_amt'] + ($this->input->post('prod_spu') * $grp_data[0]['tax_percentage']/100);

                        //Update data for PRODUCT_GROUP_MASTER
                        $data_grp = array('product_group_total_amt'=> $total_amt, 'product_group_discounted_amt' => $discounted_amt, 'product_group_tax_amt'=> $total_tax_amt);
                        $where_grp = array('product_group_id' => $this->input->post('prod_group')[$i]);
                        $this->common_model->update(PRODUCT_GROUP_MASTER, $data_grp, $where_grp);
                    }
                }
            }

            //Update Record in Database
            if ($update_prod || $insert_grp_prod_rel)
            {
                //update success
                $msg = $this->lang->line('update_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");

            }else
            {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
            }
            redirect($this->viewname);	//Redirect On Listing page
        }    
        else {
            exit('No direct script access allowed');
        }
		
    }
    /*
     @Author : Disha Patel
     @Desc   : ProductList Delete Data
     @Input  : Post id from ProductList page
     @Output : Delete data from database and redirect
     @Date   : 10/02/2016
     */
    public function deletedata()
    {
        if(isset($_GET) && !empty($_GET)) {
            $id = $this->input->get('id');
            if(!empty($id))
            {
                $fields_rel = array("is_delete");
                $where_rel = array("product_id"=> $id, "is_delete"=>"0");
                $data_rel = $this->common_model->get_records(PRODUCT_GROUP_RELATION, $fields_rel,'','',$where_rel);

                if (!empty($data_rel)) {
                    $msg = $this->lang->line('cannot_delete_product');
                    $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");

                } else{
                    $data = array('is_delete' => '1');
                    $where = array('product_id' => $id);
                    $delete_prod = $this->common_model->update(PRODUCT_MASTER, $data, $where);

                    $del_prod_rel = $this->common_model->update(PRODUCT_GROUP_RELATION, $data,$where);
                     if($delete_prod || $del_prod_rel){
                        //Delete msg
                        $msg = $this->lang->line('delete_msg');
                        $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
                        unset($id);

                    }else{
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
                    }
                }

                redirect($this->viewname);	//Redirect On Listing page
            }
        }
        else {
            exit('No direct script access allowed');
        }
    }
	
    /*
      @Author : Disha Patel
      @Desc   : View Display Data Page
      @Input  :
      @Output :
      @Date   : 11/02/2016
     */
    public function display($id)
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{
            //Get ProductGroup Info
            $table_grp	= PRODUCT_GROUP_MASTER;
            $fields_grp     = array("product_group_id, product_group_name");
            $data['product_group_info'] = $this->common_model->get_records($table_grp,$fields_grp);

            //Get Tax name from PRODUCT_TAX_MASTER
            $table_tax     = PRODUCT_TAX_MASTER . " AS ptm";
            $fields_tax    = array("ptm.tax_id, ptm.tax_name");
            $where_tax     = array('ptm.is_delete' => '0');
            $data['tax_info'] = $this->common_model->get_records($table_tax, $fields_tax, '', '', $where_tax);

            //Get Currency Symbol from COUNTRIES
            $table_cur  = COUNTRIES . " AS cm";
            $fields_cur = array("cm.country_id, cm.currency_symbol");
            $where_cur  = array('cm.use_status' => '1', 'country_status'=>'1', 'cm.is_delete_currency' => '0');
            $data['currency_info'] = $this->common_model->get_records($table_cur,$fields_cur, '', '', $where_cur);

            //Get selected currency in dropdown
            $table_pro_cur     = PRODUCT_MASTER . ' AS pm';
            $fields_pro_cur    = array("pm.currency_symbol as 'product_currency_id', cm.currency_symbol, cm.country_id");
            $where_pro_cur     = array('pm.product_id' => $id, 'use_status'=> '1', 'country_status'=> '1', 'cm.is_delete_currency' => '0');
            $params_pro_cur['join_tables'] = array(COUNTRIES . ' AS cm' => 'pm.currency_symbol = cm.country_id');
            $params_pro_cur['join_type'] = 'left';
            $data['prod_currency_info'] = $this->common_model->get_records($table_pro_cur, $fields_pro_cur, $params_pro_cur['join_tables'], $params_pro_cur['join_type'], $where_pro_cur);

            // for selected product tax in dropdown
            $table_pro_tax     = PRODUCT_MASTER . ' AS pm';
            $fields_pro_tax    = array("pm.product_tax_id, ptm.tax_id, ptm.tax_name");
            $where_pro_tax     = array('pm.product_id' => $id, 'ptm.is_delete' => '0');
            $params_pro_tax['join_tables'] = array(PRODUCT_TAX_MASTER . ' AS ptm' => 'pm.product_tax_id = ptm.tax_id');
            $params_pro_tax['join_type'] = 'left';
            $data['prod_tax_info'] = $this->common_model->get_records($table_pro_tax,$fields_pro_tax, $params_pro_tax['join_tables'], $params_pro_tax['join_type'], $where_pro_tax);

            // for selected product group in dropdown
            $fields2 = array("pgr.product_group_id, GROUP_CONCAT(pgm.product_group_name SEPARATOR ',') AS product_group_name");
            $params['join_tables'] = array(PRODUCT_GROUP_MASTER . ' AS pgm' => 'pgm.product_group_id=pgr.product_group_id');
            $params['join_type'] = 'left';
            $match2 = array("pgr.product_id"=> $id, "pgr.is_delete" => '0', "pgm.status"=>'1');
            $data['product_rel_info'] = $this->common_model->get_records(PRODUCT_GROUP_RELATION. ' AS pgr',$fields2, $params['join_tables'], $params['join_type'], $match2);

            //Get Records From PRODUCT_MASTER Table
            $data['view'] = true;
            $table	= PRODUCT_MASTER;
            $match 	= "product_id = ".$id;
            $fields = array("product_id, product_name, product_type, product_description, purchase_price_unit, sales_price_unit, gross_margin, status");
            $data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);

            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/ViewProduct';
            $this->load->view('ViewProduct', $data);
        }
    }
    
    
    function importProduct()
    {
         
            $data['modal_title'] = $this->lang->line('IMPORT_PRODUCT');
            $data['submit_button_title'] = $this->lang->line('IMPORT_PRODUCT');
            $data['sales_view'] = $this->viewname;
            $data['main_content'] = '/importProduct';
            $this->load->view('/importProduct', $data);
    }
    
    function importProductdata()
    {
        $config['upload_path'] = './uploads/csv_product';
        $config['allowed_types'] = '*';
        $config['max_size'] = 40480;
        $new_name = time()."_".str_replace(' ','_', $_FILES["import_file"]['name']);
        $config['file_name'] = $new_name;
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config); 
        if ( !$this->upload->do_upload('import_file'))
        {
            $msg = $this->upload->display_errors();
            $this->session->set_flashdata('msg', $msg);
        }
        else
        {
            $file_path =  './uploads/csv_product/'.$new_name; 
            
            $this->load->library('excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file_path);
            
            $cell_collection = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            
            $key_product_name = array_search('Product Name', $cell_collection[1]);
            $key_product_type = array_search('Product Type', $cell_collection[1]);
            $key_product_description = array_search('Product Description', $cell_collection[1]);
            $key_purchase_unit_price = array_search('Purchase Price Unit', $cell_collection[1]);
            $key_sales_unit_price = array_search('Sales Price Unit', $cell_collection[1]);
            $key_gross_margin = array_search('Gross Margin', $cell_collection[1]);
            $key_prodcut_tax_name = array_search('Product Tax Name', $cell_collection[1]);
            $key_product_tax_percentage = array_search('Tax Percentage', $cell_collection[1]);
            $key_product_group = array_search('Product Group', $cell_collection[1]);
            $key_currency_symbol = array_search('Currency Symbol', $cell_collection[1]);
            
            $chk_file_column = array('Product Name','Product Type','Product Description','Purchase Price Unit','Sales Price Unit','Gross Margin','Product Tax Name','Tax Percentage','Product Group','Currency Symbol');
           
            $diff_array = array_diff($chk_file_column, $cell_collection[1]);
           
            if(!empty($diff_array))
            {
                $msg = lang('WRONG_FILE_FOMRMAT');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
                
                redirect($this->viewname); 
            }
                
            unset($cell_collection[1]);
           
            $count_success = 0;
            $count_fail = 0;
            $total_record = count($cell_collection);
            
           
            foreach ($cell_collection as $cell)
            {
                
                $value_product_name= trim($cell[$key_product_name]);
                $value_product_type = trim($cell[$key_product_type]);
                $value_prodcut_description = strip_tags($cell[$key_product_description]);
                $value_product_unit_price = trim($cell[$key_purchase_unit_price]);
                $value_sales_unit_price = trim($cell[$key_sales_unit_price]);
                $value_gross_margin = $cell[$key_gross_margin];
                $value_product_tax_name = $cell[$key_prodcut_tax_name];
                $value_product_tax_percentage = $cell[$key_product_tax_percentage];
                $value_product_group = trim($cell[$key_product_group]);
                $value_currency_symbol = trim($cell[$key_currency_symbol]);
               
                $tax_id = $this->Product_model->getTaxIdByName($value_product_tax_name);
                
                $currency_symbol_id = $this->Product_model->getCurrencySymbolIdByName($value_currency_symbol);
                
                if ($tax_id == 0 && !is_null($value_product_tax_percentage))
                { 
                    
                    $data_tax['tax_name'] = $value_product_tax_name;
                    $data_tax['tax_percentage'] = $value_product_tax_percentage;
                    $data_tax['created_date'] = datetimeformat();
                    $data_tax['is_delete'] = 0;
                     
                    $get_tax_id = $this->common_model->insert(PRODUCT_TAX_MASTER, $data_tax);
                    if ($get_tax_id != "") 
                    {
                        $tax_id = $get_tax_id;
                    }
                } else {
                    
                        $tax_id = $tax_id;
                        $get_tax_id = '';
                }
               
                $product_data['product_name'] = $value_product_name;
                $product_data['product_type'] = $value_product_type;
                $product_data['product_description'] = $value_prodcut_description;
                $product_data['purchase_price_unit'] = number_format($value_product_unit_price,2);
                $product_data['sales_price_unit'] = number_format($value_sales_unit_price,2);
                $product_data['gross_margin'] = number_format($value_gross_margin,2);
                $product_data['product_tax_id'] = $tax_id;
                $product_data['currency_symbol'] = $currency_symbol_id;
                $product_data['created_date'] = datetimeformat();
                $product_data['status'] = 1;
                $product_data['is_delete'] = '0';
               
                $table_grp = PRODUCT_MASTER;
                $fields_grp = array("product_id");
                $match_grp = array('is_delete' => '0', 'status' => '1','product_name'=>$value_product_name);
                $product_data_arr = $this->common_model->get_records($table_grp,$fields_grp, '', '', $match_grp);
                
                
                // && $value_product_name !='' && $value_product_type != '' && $value_product_unit_price != '' && $value_sales_unit_price!= ''  && $value_gross_margin != ''  && $tax_id != '' && $currency_symbol_id != ''
                // && $value_product_unit_price >= 0  && $value_sales_unit_price >= 0 && $value_gross_margin >= 0
                if(empty($product_data_arr) && $value_product_name !='' && $value_product_type != '' && $value_product_unit_price != '' && $value_sales_unit_price!= ''&& $value_sales_unit_price >= 0 && $value_product_unit_price >= 0 && $value_gross_margin >= 0 && $value_gross_margin >= 0 && $tax_id != '' && $currency_symbol_id != '')
                {
                    $flg_product_master  = $this->common_model->insert(PRODUCT_MASTER, $product_data);
                    
                    if($flg_product_master)
                    {
                        
                        $product_group_array = explode(',',$value_product_group);
                        foreach ($product_group_array as $product_group)
                        {
                            $product_group_id = $this->Product_model->getProductGroupIdByName($product_group);
                             
                            if ($product_group_id == 0 && $product_group != '')
                            {
                                $data_group['product_group_name'] = $product_group;
                                $data_group['created_date'] = datetimeformat();
                                $data_group['modified_date'] = datetimeformat();
                                $data_group['status'] = '1';
                                $data_group['is_delete'] = '0';
                               
                                
                                $get_group_id = $this->common_model->insert(PRODUCT_GROUP_MASTER, $data_group);
                                if ($get_group_id != "") 
                                {
                                    $group_id = $get_group_id;
                                }
                            } else {
                                $group_id = $product_group_id;
                            }
                           
                            if($group_id == 0)
                            {
                                //  $product_relation['product_tax_id'] = $tax_id;
                               // $product_relation['currency_symbol'] = $currency_symbol_id;
                                $product_relation['product_id'] = $flg_product_master;
                                $product_relation['product_group_id'] = $group_id;
                                $product_relation['product_tax_id'] = $tax_id;
                                $product_relation['is_delete'] = '0';
                                $product_relation['product_group_status'] = '1';
                                
                                $this->common_model->insert(PRODUCT_GROUP_RELATION, $product_relation);
                                
                            }else
                            {
                                $data_rel = array( 
                                'product_id' => $flg_product_master,'product_group_id'=>$group_id,
                                'product_tax_id' => $tax_id,'is_delete'=>'0'
                                );
                                $insert_grp_prod_rel = $this->common_model->insert(PRODUCT_GROUP_RELATION,$data_rel);
                            }
                        }
                        
                        $count_success++;
                    }else
                    {
                        $count_fail++;
                    }
                }else
                {
                   
                    if(isset($get_tax_id) && $get_tax_id != '')
                    {
                   
                        $where_arr = array('tax_id'=>$get_tax_id);
                        $this->common_model->delete(PRODUCT_TAX_MASTER, $where_arr);
                    }
                   
                    $count_fail++;
                }
            }
            
            $msg = "Succesfully Imported ! Total Record : $total_record, Successfully Imported : $count_success, Fail Record : $count_fail ";
            $this->session->set_flashdata("msg","<div class='alert alert-success text-center'>$msg</div>");
        }
        
        redirect($this->viewname); 
    }
    function exportProduct() 
    { 
            $this->Product_model->exportProduct();

            redirect('Product');
    }
    
    function getCurWiseSPUandPPU(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{ 
            $oldCurrencySymbolId = $this->input->post('oldCurrencySymbolId');
            $newCurrencySymbolId = $this->input->post('newCurrencySymbolId');
            $sales_price_unit = $this->input->post('spu');
            $purchase_price_unit = $this->input->post('ppu');
            $oldCurCode = getCourrencyCode($oldCurrencySymbolId);
            $newCurCode = getCourrencyCode($newCurrencySymbolId);
            $salesAmt = helperConvertCurrency($sales_price_unit, $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
            $purchaseAmt = helperConvertCurrency($purchase_price_unit, $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
            echo json_encode(array('purchaseAmt'=> $purchaseAmt, 'salesAmt' => $salesAmt));
        }
    }
    
    function getPPUandSPUById()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{ 
            $id = $this->input->post('productId');
            //Get SPU, PPU By Id
            $table  = PRODUCT_MASTER;
            $fields = array("sales_price_unit, purchase_price_unit");
            $where  = array('product_id' => $id);
            $data['ppuspu_info'] = $this->common_model->get_records($table,$fields, '', '', $where);
            $spu = $data['ppuspu_info'][0]['sales_price_unit'];
            $ppu = $data['ppuspu_info'][0]['purchase_price_unit'];
            echo json_encode(array('PurchasePriceUnit'=> $ppu, 'SalesPriceUnit' => $spu));
        }
    }
}
