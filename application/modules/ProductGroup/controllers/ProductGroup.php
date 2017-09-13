<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductGroup extends CI_Controller 
{
    function __construct()
    {
            parent::__construct();
            if(checkPermission('ProductGroup','view') == false)
            {
                redirect('/Dashboard');
            }
            $this->viewname = $this->uri->segment(1);
            $this->load->helper(array('form','url'));
            $this->load->library(array('form_validation','Session'));
    }
    /*
     @Author : Disha Patel
     @Desc   : ProductGroup Index Page
     @Input  :
     @Output :
     @Date   : 12/02/2016
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
            $this->session->unset_userdata('product_group_data');
        }

        $searchsort_session = $this->session->userdata('product_group_data');
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
                $sortfield = 'product_group_id';
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
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('product_group_data');
        }

        $searchsort_session = $this->session->userdata('product_group_data');
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
                $sortfield = 'product_group_id';
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
        
        $table = PRODUCT_GROUP_MASTER;
                        
        //Get Records From PRODUCT_GROUP_MASTER Table
        $fields = array("product_group_id, product_group_name, product_group_description, status");
        $where = array('is_delete' => '0');
        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim($searchtext));
            $match=array('product_group_name'=>$searchtext);
            $config['total_rows'] = count($this->common_model->get_records($table, $fields, '', '', $where, $match));
        
            $data['product_group_info'] = $this->common_model->get_records($table. ' AS pg', $fields, '', '', $where, $match, $config['per_page'], $uri_segment,$sortfield,$sortby,'','','','','');
        
        } else {
            $config['total_rows'] = count($this->common_model->get_records($table, $fields, '', '', $where, '','','','','','1'));
            $data['product_group_info'] = $this->common_model->get_records($table. ' AS pg', $fields, '', '', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby,'','','','','');
//            echo $this->db->last_query();exit;
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
        $this->session->set_userdata('product_group_data', $sortsearchpage_data);
        
        //Pass Records In View         	
	if($this->input->post('result_type') == 'ajax'){
            $this->load->view('ProductGroupajax',$data);
        } else {
            $data['main_content'] = '/'.$this->viewname;
            $this->parser->parse('layouts/CMSTemplate', $data);
        }
    }

    /*
     @Author : Disha Patel
     @Desc   : ProductGroup insertdata
     @Input  :
     @Output : Insert data in database and redirect
     @Date   : 12/02/2016
    */
    public function insertdata()
    {
        if(isset($_POST) && !empty($_POST)) {
            $table = PRODUCT_GROUP_MASTER;
            $data['crnt_view'] = $this->viewname;

            //insert the ProductGroup details into database
            $data = array(
                'product_group_name' => trim($this->input->post('prod_grp_name')),
                'product_group_description' => trim($this->input->post('prod_grp_desc', false)),
                'product_group_total_amt' => trim($this->input->post('total_amt')),
                'product_group_discounted_amt' => trim($this->input->post('discounted_amt')),
                'product_group_tax_amt' => trim($this->input->post('taxed_amt')),
                'status' => $this->input->post('status'),
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat()
            );

            //Duplicate Entry check in PRODUCT_GROUP_MASTER table
            $table1 = PRODUCT_GROUP_MASTER;
            $fields = array("product_group_name");
            $where = array('product_group_name' => $this->input->post('prod_grp_name'), 'is_delete' => '0');
            $prod_grp_name = $this->common_model->get_records($table1. ' AS pg', $fields,'','',$where);

            //Insert ProductGroup details if no Duplicate Entry else Show Error
            if(count($prod_grp_name) == 0){
                //Insert
                $insert_grp_prod = $this->common_model->insert($table,$data);
            }else {
                //error
                $msg = $this->lang->line('duplicate_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
                redirect($this->viewname);
            }

            //Insert Multiple products Records into PRODUCT_GROUP_RELATION table
            $table_rel = PRODUCT_GROUP_RELATION;
            //$products_count = count($this->input->post('products'));

            foreach ($this->input->post('products') as $products) {

                $data1 = array('product_id' => $products , 
                                'product_group_id' => $insert_grp_prod,
                                'product_discount' => $this->input->post('prod_discount_'.$products),
                                'discount_option' => $this->input->post('discount_Opt_'.$products),
                                'product_qty' => $this->input->post('prod_qty_'.$products),
                                'product_total' => $this->input->post('prod_total_'.$products),
                                'product_tax_id' => $this->input->post('tax_id'.$products),
                                'product_group_status' => $this->input->post('product_group_status_'.$products)
                        );
                $data['product_grp_rel_info'] = $this->common_model->insert($table_rel,$data1);
            }
                $table11 = PRODUCT_MASTER ;
                $fields11 = array("pm.product_name");
                $params11['join_tables'] = array(PRODUCT_GROUP_RELATION . ' as pgr' => 'pm.product_id = pgr.product_id');
                $params11['join_type'] = 'left';
                $where11 = array('pgr.product_group_id' => $insert_grp_prod);
                $data['product_name'] = $this->common_model->get_records($table11. ' AS pm', $fields11,$params11['join_tables'],$params11['join_type'],$where11);

            //Insert Record in Database
            if ($insert_grp_prod || $data['product_grp_rel_info'])
            {
                $msg = $this->lang->line('group_add_msg');
    //            $count = 0;
    //            foreach ($data['product_name'] as $product_name){
    //                $count++;
    //                $msg .= $product_name['product_name'];
    //                if($count != count($data['product_name'])){ $msg .= ", "; }
    //                
    //                	
    //            }
                $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
                redirect($this->viewname);

            }
            else
            {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
                redirect($this->viewname);
            }
        }
        else {
            exit('No direct script access allowed');
        }
    }
    /*
     @Author : Disha Patel
     @Desc   : ProductGroupList view Page
     @Input  :
     @Output : 
     @Date   : 19/01/2016
    */
    public function AddEditProductGroup()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{
            $data['crnt_view'] = $this->viewname;

            // Get product name
            $table	= PRODUCT_MASTER . ' as pm';
            $fields = array("pm.product_id, pm.product_name, pm.sales_price_unit");
            $where = array('pm.is_delete' => '0', 'pm.status'=> '1');
            $data['product_info'] = $this->common_model->get_records($table,$fields, '', '', $where);

            $data['currencyInfo'] = getDefaultCurrencyInfo();

            //Get Tax name from PRODUCT_TAX_MASTER
            $table1 = PRODUCT_TAX_MASTER . " AS ptm";
            $fields1 = array("ptm.tax_id, ptm.tax_name, ptm.tax_percentage");
            $where1 = array('ptm.is_delete' => '0');
            $data['tax_info'] = $this->common_model->get_records($table1,$fields1, '', '', $where1);

            //Pass Table:PRODUCT_GROUP_MASTER Record In View
            $data['main_content'] = '/AddEditProductGroup';
            $this->load->view('AddEditProductGroup',$data);
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
            //Get Default Currency of System
            $data['currencyInfo'] = getDefaultCurrencyInfo();

            // Get All Products name
            $table1	= PRODUCT_MASTER . ' as pm';
            $fields1 = array("pm.product_id, pm.product_name, pm.sales_price_unit");
            $where1 = array("pm.status"=> '1', "pm.is_delete"=> '0');
            $data['product_info'] = $this->common_model->get_records($table1,$fields1, '', '', $where1);

            //Get Tax name from PRODUCT_TAX_MASTER
            $table3 = PRODUCT_TAX_MASTER . " AS ptm";
            $fields3 = array("ptm.tax_id, ptm.tax_name");
            $where3 = array('ptm.is_delete' => '0');
            $data['tax_info'] = $this->common_model->get_records($table3,$fields3, '', '', $where3);

            // For selected products in dropdown
            $fields2 = array(" pgr.product_group_id, pgr.product_group_status, pm.product_id, pm.product_name, pgr.product_group_status, pgr.product_tax_id, pgr.product_discount, pgr.discount_option, pgr.product_qty, pgr.product_total, ptm.tax_percentage");
            $params2['join_tables'] = array(PRODUCT_MASTER . ' as pm' => 'pm.product_id = pgr.product_id',
                                           PRODUCT_TAX_MASTER . ' AS ptm' => 'ptm.tax_id = pgr.product_tax_id' 
                                        );
            $params2['join_type'] = 'left';
            $match2 = "pgr.product_group_id = ".$id;
            $match2 = array("pgr.product_group_id" => $id, "pgr.is_delete !=" => '1');
            $data['product_grp_rel_info'] = $this->common_model->get_records(PRODUCT_GROUP_RELATION. ' AS pgr',$fields2, $params2['join_tables'], $params2['join_type'], $match2);

            //Get Records From PRODUCT_GROUP_MASTER Table
            $table	= PRODUCT_GROUP_MASTER;
            $match 	= "product_group_id = ".$id;
            $fields = array("product_group_id, product_group_name, product_group_description, product_group_total_amt,product_group_discounted_amt, product_group_tax_amt, status");
            $data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);

            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/AddEditProductGroup';
            $this->load->view('AddEditProductGroup', $data);
        }
    }
    /*
     @Author : Disha Patel
     @Desc   : ProductList Update Query
     @Input  : 
     @Output : Update data in database and redirect
     @Date   : 12/01/2016
     */
    public function updatedata()
    {
        if(isset($_POST) && !empty($_POST)) {
            $id = $this->input->post('prod_grp_id');

            // delete products in dropdown and insert again : start
            $data_rel = array('is_delete' => '1');
            $where_rel = array('product_group_id' => $id);
            $this->common_model->update(PRODUCT_GROUP_RELATION, $data_rel, $where_rel);

            foreach ($this->input->post('products') as $products){
                $data1 = array('product_id' => $products, 
                                'product_group_id' => $id,
                                'product_discount' => $this->input->post('prod_discount_'.$products),
                                'discount_option' => $this->input->post('discount_Opt_'.$products),
                                'product_qty' => $this->input->post('prod_qty_'.$products),
                                'product_total' => $this->input->post('prod_total_'.$products),
                                'product_tax_id' => $this->input->post('tax_id'.$products),
                                'product_group_status' => $this->input->post('product_group_status_'.$products)
                        );                    

                $this->common_model->insert(PRODUCT_GROUP_RELATION, $data1);  
            }
            // end

            //Get Records From PRODUCT_GROUP_MASTER Table
            $table	= PRODUCT_GROUP_MASTER;
            $match 	= "product_group_id = ".$id;
            $fields = array("product_group_id, product_group_name, product_group_description, product_group_total_amt, product_group_discounted_amt, product_group_tax_amt, status");
            $where = array('product_group_id' => $id);
            $data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;

            $data = array(
                        'product_group_name' => trim($this->input->post('prod_grp_name')),
                        'product_group_description' => trim($this->input->post('prod_grp_desc', false)),
                        'product_group_total_amt' => trim($this->input->post('total_amt')),
                        'product_group_discounted_amt' => trim($this->input->post('discounted_amt')),
                        'product_group_tax_amt' => trim($this->input->post('taxed_amt')),
                        'status' => $this->input->post('status'),
                        'modified_date' => datetimeformat()
            );

            //Duplicate Entry check in PRODUCT_GROUP_MASTER table
            $table_dupl = PRODUCT_GROUP_MASTER;
            $fields_dupl = array("product_group_name");
            $where_dupl = array('product_group_name' => $this->input->post('prod_grp_name'), 'product_group_id<>' => $id);
            $prod_grp_name = $this->common_model->get_records($table_dupl. ' AS pg', $fields_dupl,'','',$where_dupl);

            //Update ProductGroup details if no Duplicate Entry else Show Error
            if(count($prod_grp_name) == 0){
                //Update
                $this->common_model->update($table, $data, $where);
                $msg = $this->lang->line('group_update_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
                redirect($this->viewname);
            }else {
                //error
                $msg = $this->lang->line('duplicate_msg');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
                redirect($this->viewname);
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
            $table	= PRODUCT_GROUP_MASTER;
            if(!empty($id))
            {
                $data = array('is_delete' => '1');
                $where = array('product_group_id' => $id);

                $del_prod_grp = $this->common_model->update($table, $data, $where);

                $del_prod_grp_rel = $this->common_model->update(PRODUCT_GROUP_RELATION, $data, $where);
                if($del_prod_grp || $del_prod_grp_rel){

                    $msg = $this->lang->line('group_delete_msg');
                    $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
                    unset($id);
                    redirect($this->viewname);	//Redirect On Listing page

                }else{
                // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
                    redirect($this->viewname);	//Redirect On Listing page

                }
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
            //Default Currency
            $data['currencyInfo'] = getDefaultCurrencyInfo();

            // Get product name
            $table1	= PRODUCT_MASTER . ' as pm';
            $fields1 = array("pm.product_id, pm.product_name");

            $data['product_info'] = $this->common_model->get_records($table1,$fields1, '', '');

            //Get Tax name from PRODUCT_TAX_MASTER
            $table3 = PRODUCT_TAX_MASTER . " AS ptm";
            $fields3 = array("ptm.tax_id, ptm.tax_name");
            $where3 = array('ptm.is_delete' => '0');
            $data['tax_info'] = $this->common_model->get_records($table3,$fields3, '', '', $where3);

            // for selected products in dropdown
            $fields2 = array(" pgr.product_group_id, pgr.product_group_status, pgr.product_discount, pgr.discount_option, pgr.product_qty, pgr.product_total, pm.product_id, pm.product_name, pm.sales_price_unit, pgr.product_tax_id, ptm.tax_name, ptm.tax_percentage");
            $params['join_tables'] = array(PRODUCT_MASTER . ' as pm' => 'pm.product_id=pgr.product_id',
                                            PRODUCT_TAX_MASTER . ' AS ptm' => 'ptm.tax_id = pgr.product_tax_id' 
                );
            $params['join_type'] = 'left';
            //$match = "pgr.product_group_id = ".$id;
            $match = array("pm.status" => '1', "pm.is_delete" => '0', "pgr.product_group_id" => $id, "pgr.is_delete != "=> '1');
            $data['product_grp_rel_info'] = $this->common_model->get_records(PRODUCT_GROUP_RELATION. ' AS pgr',$fields2, $params['join_tables'], $params['join_type'], $match);

            //Get Records From PRODUCT_GROUP_MASTER Table
            $data['view'] = true;
            $table	= PRODUCT_GROUP_MASTER;
            $match 	= "product_group_id = ".$id;

            $fields = array("product_group_id, product_group_name, product_group_description, product_group_total_amt, product_group_discounted_amt, product_group_tax_amt, status");
            $data['editRecord']  = $this->common_model->get_records($table,$fields,'','',$match);
            $data['id'] = $id;
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/ViewProductGroup';
            $this->load->view('ViewProductGroup', $data);
        }
    }
    
    /*
      @Author : Disha Patel
      @Desc   : View Display Data Page
      @Input  :
      @Output :
      @Date   : 11/02/2016
     */
    function GetTaxPercent(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }else{
            $tax_id = $this->input->post('taxId');
            $fields = array("tax_percentage");
            $where = array('tax_id' => $tax_id);
            $data['tax_val']  = $this->common_model->get_records(PRODUCT_TAX_MASTER, $fields, '', '', $where);
            //pr($data['tax_val'][0]['tax_percentage']);exit;
            if(count($data['tax_val'])>0)
            {

                echo $data['tax_val'][0]['tax_percentage'];
            }
            else
            {
                echo 0;
            }
        }
    }
    
}
