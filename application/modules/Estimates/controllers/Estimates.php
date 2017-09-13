<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Estimates extends CI_Controller {
    public $viewname;
    function __construct() {
        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->load->model('EstimateModel');
        $this->load->library(array('form_validation', 'Session', 'm_pdf'));
		if(checkPermission('Estimates','view') == false)
        {
            redirect('/Dashboard');
        }
	}
    /*
      @Author : Ritesh Rana
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 07/02/2016
     */
    /* new index function */
   public function index()
    {
	//Breadcrumb Code
		$this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('estimates'), ' ');
    // $data['js_content'] = '/loadJsFiles';
        $searchtext='';$perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('estimates_data');
        }
		$searchsort_session = $this->session->userdata('estimates_data');
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
                $sortfield = 'estimate_id';
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
        $table = ESTIMATE_MASTER.' as em';
        $where            = 'em.status != 3';
        $where_not_in  	  = array('em.status' => "3");
        $or_where         = '';
        $group_by = array('clm.estimate_id');
        $join_tables = array(ESTIMATE_CLIENT.' as clm' => 'clm.estimate_id = em.estimate_id',);
        $fields = array("em.country_id_symbol, em.created_date, em.estimate_id, em.status, em.prospect_id, em.estimate_auto_id, em.subject, em.value, em.send_date, em.due_date, clm.prospect_id, clm.client_type,clm.client_name");
	//Search Filter Condition start Delete following if condition after done all the things
		/*if ($this->input->post('searchAction') == "rightSideSearch") 
		pr($_REQUEST);
			echo "<br>";
			//$where.='1';
		}*/
	//Search From Right side Box
		$data['search_status'] = "";
        if ($this->input->post('search_status') != "") {
            $data['search_status'] = $this->input->post('search_status');
            $where.=' AND em.status=' . $data['search_status'];
        }
		$data['fstValData'] = "";
        if ($this->input->post('fstValue') != "") {
           $data['fstValData'] = $this->input->post('fstValue');
        }
		$data['sndValData'] = "";
        if ($this->input->post('sndValue') != "") {
           $data['sndValData'] = $this->input->post('sndValue');
        }
		if($data['fstValData'] != "" && $data['sndValData'] && is_numeric($data['fstValData']) && is_numeric($data['sndValData']))
		{
			$where.=' AND em.value>="' . $data['fstValData'] . '"';
			$where.=' AND em.value<="' . $data['sndValData'] . '"';
		}
		$data['prospect_owner_id'] = "";
        if ($this->input->post('prospect_owner_id') != "") {
            $data['prospect_owner_id'] = $this->input->post('prospect_owner_id');
            $where.=' AND em.prospect_owner_id = "' . $data['prospect_owner_id'] . '"';
        }
		$data['search_creation_date_show'] = "";
        if ($this->input->post('search_creation_date') != "") {
            $data['search_creation_date_show'] = date_format(date_create($this->input->post('search_creation_date')), 'Y-m-d');
            $where.=' AND em.created_date>="' . $data['search_creation_date_show'] . '"';
        }
		$data['creation_end_date_show'] = "";
        if ($this->input->post('creation_end_date') != "") {
            $data['creation_end_date_show'] = date_format(date_create($this->input->post('creation_end_date')), 'Y-m-d');
            $where.=' and em.created_date<="' . $data['creation_end_date_show'] . '"';
        }
		//Search by Company, Client
			$contactAndOrString = ' AND ';
			$contactAndOrContact = ' ( ';
			if ($this->input->post('search_company_id') != "") {
				$contactAndOrString = ' OR ';
				$contactAndOrContact = ' ';
			//Place ) if Contact by id Search is available
				if ($this->input->post('search_contact_id') != "") { $contactAndOrCompany = ' ';  } else {	$contactAndOrCompany = ' ) ';	}
				$data['search_company_id'] = explode('_', $this->input->post('search_company_id'));
				$where.=' AND ( clm.client_type IN('.'"'.$data['search_company_id'][0].'"'.') AND clm.prospect_id IN('.$data['search_company_id'][1].') '.$contactAndOrCompany;
			}
		//Search by Contact
			if ($this->input->post('search_contact_id') != "") {
				$data['search_contact_id'] = $this->input->post('search_contact_id');
				$where.= $contactAndOrString .$contactAndOrContact. '  clm.client_type IN("contact") AND clm.prospect_id IN('.$data['search_contact_id'].') )';
			}
		if(!empty($searchtext))
        {	
            $searchtext = html_entity_decode(trim($searchtext));
            $match 		= "";
            $where_search='(em.send_date LIKE "%'.$searchtext.'%" OR em.due_date LIKE "%'.$searchtext.'%" OR clm.client_name LIKE "%'.$searchtext.'%" OR em.subject LIKE "%'.$searchtext.'%" OR em.value LIKE "%'.$searchtext.'%" OR em.estimate_auto_id LIKE "%'.$searchtext.'%")';
			$data['estimate_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by,$where,'','','',$or_where, $where_not_in, $where_search);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,$group_by,$where,'','','1',$or_where, $where_not_in, $where_search);
        }
        else
        {
			//print_r($_REQUEST);
			$data['estimate_data']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,$group_by,$where,'','','',$or_where, $where_not_in);
			//echo $this->db->last_query();
			$config['total_rows']  		= $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,$group_by,$where,'','','1',$or_where, $where_not_in);
			//echo count($data['estimate_data']);
        }
		$data['totalEstimate'] = $config['total_rows'];
		//pr($data['totalEstimate']);
        //pr($this->db->last_query());exit;
	//Get Records From CONTACT_MASTER Table for search
        $table_contact_search = CONTACT_MASTER . ' as cm';
        $match_contact_search = " cm.is_delete=0 ";
        $fields_contact_search = array("cm.contact_id,cm.contact_name");
        $data['owner'] = $this->common_model->get_records($table_contact_search, $fields_contact_search, '', '', $match_contact_search);
	//Get Records From COMPANY_MASTER Table       
        $table_company_master = COMPANY_MASTER . ' as cmp';
        $match_company_master = " cmp.status=1 and cmp.is_delete=0 ";
        $fields_company_master = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table_company_master, $fields_company_master, '', '', $match_company_master);
	//Get Client Information
        $PMClientTable = PROSPECT_MASTER . ' as pro';
        $PMClientMatch = "pro.status_type = 3 AND pro.status = 1 AND pro.is_delete = 0";
		$PMClientFields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['clientArray'] = $this->common_model->get_records($PMClientTable, $PMClientFields, '', '', $PMClientMatch);
	//pr($data['estimate_data']);exit;
        $table1 = ESTIMATE_MASTER . ' as em';
        $match1 = "";
        $fields1 = array("count(em.prospect_id) as total_company");
        $total_company = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        $data['total_company'] = $total_company[0]['total_company'];
	//Get Records From BRANCH_MASTER Table
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = "";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
    //Get Records From CONTACT_MASTER Table
        $table3 = CONTACT_MASTER . ' as cm';
        $match3 = "";
        $fields3 = array("cm.contact_id,cm.contact_name");
        $data['prospect_owner'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);
	//Get Prospect Owner 
		$data['prospect_owner'] = $this->common_model->getSystemUserData();
	//
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
        $this->session->set_userdata('estimates_data', $sortsearchpage_data);
        $data['estimate_view'] = $this->viewname;
        $data['sales_view'] = $this->viewname;
        $data['header'] = array('menu_module'=>'crm');		
        if($this->input->post('result_type') == 'ajax'){
			
            $this->load->view($this->viewname.'/ajax_list',$data);
        } else {
            $data['main_content'] = '/'.$this->viewname;
            $this->parser->parse('layouts/CMSTemplate', $data);
        }
    }

   /* end */

    /*public function index_data_list() {
        //$this->config->item('directory_root');
        $data['main_content'] = '/Estimates';
        $data['js_content'] = '/LoadJsFileEstimates';
        $data['estimate_view'] = $this->viewname;
        //Top side selected Header menu name pass
        $data['header'] = array('menu_module' => 'crm');
        //Get Records From ESTIMATE_MASTER Table
        $table = ESTIMATE_MASTER . ' as em';
        $match = "";
        $fields = array("em.estimate_id, em.status, em.prospect_id, em.estimate_auto_id, em.subject, em.value, em.send_date, em.due_date, bpm.prospect_name");
        $join_tables = array(PROSPECT_MASTER.' as bpm' => 'em.prospect_id = bpm.prospect_id');
        $data['estimate_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

        $table1 = ESTIMATE_MASTER . ' as em';
        $match1 = "";
        $fields1 = array("count(em.prospect_id) as total_company");
        $total_company = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        $data['total_company'] = $total_company[0]['total_company'];

        //Get Records From BRANCH_MASTER Table       
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = "";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
        //Get Records From CONTACT_MASTER Table
        $table3 = CONTACT_MASTER . ' as cm';
        $match3 = "";
        $fields3 = array("cm.contact_id,cm.contact_name");
        $data['prospect_owner'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);
        //Pass ALL TABLE Record In View

        $this->parser->parse('layouts/DashboardTemplate', $data);
        //$this->load->view('AddEditEstimates');
        //$this->load->view('LoadJsFileEstimates');
    }*/
    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 13/01/2016
     */
    public function add() {
	//Breadcrumb Code
		$this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('estimates'), 'Estimates');
        $this->breadcrumbs->push(lang('create_estimate'), ' ');
        $data = array();
        $data['ctr_view'] 	= $this->viewname;
        $data['incId'] 		= 0;
        $data['discount'] 	= 0;
        $data['subtotal'] 	= 0;
        $data['taxes'] 		= 0;
        $data['total'] 		= 0;
        $data['url'] 		= base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        //Get Country Record
        $cnt_table 	= COUNTRIES . ' as cm';
        $cnt_fields = array("cm.country_name,cm.country_id");
        $data['country_data'] = $this->common_model->get_records($cnt_table, $cnt_fields, '', '', '', '', '', '', '', '', '', '');
	//Get Current login Info
		$LoginUserArray = $this->session->userdata('LOGGED_IN');
        $data['login_id'] = $LoginUserArray['ID'];
	//Get Prospect Owner 
		$data['prospect_owner'] = $this->common_model->getSystemUserData();
    //Top side selected Header menu name pass
        $data['header'] = array('menu_module' => 'crm');
    //Get Client Information
        $tableCustClient = PROSPECT_MASTER . ' as pro';
        $matchCustClient = "pro.status_type = 3 AND pro.status = 1 AND pro.is_delete = 0";
		$fieldsCustClient = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($tableCustClient, $fieldsCustClient, '', '', $matchCustClient);
    //Get Company Information
        $tableCustComMast 	= COMPANY_MASTER . ' as com_mst';
        $matchCustComMast 	= "com_mst.status = 1 AND com_mst.is_delete=0";
        $fieldsCustComMast = array("com_mst.company_id, com_mst.company_name");
        $data['company_info'] = $this->common_model->get_records($tableCustComMast, $fieldsCustComMast, '', '', $matchCustComMast);
    //Get Contact Information
        $tableCustConMast  = CONTACT_MASTER . ' as con_mst';
        $matchCustConMast  = "con_mst.status = 1 AND con_mst.is_delete=0";
        $fieldsCustConMast = array("con_mst.contact_id, con_mst.contact_name");
        $data['contact_info'] = $this->common_model->get_records($tableCustConMast, $fieldsCustConMast, '', '', $matchCustConMast);
	//Get Terms and Condition 	
		$TermsSettingData['fields'] 	= ['EstSet.estimate_settings_id, EstSet.name, EstSet.terms, EstSet.conditions, EstSet.status'];
		$TermsSettingData['table'] 		= ESTIMATE_SETTINGS . ' as EstSet';
		$TermsSettingData['match_and'] 	= 'EstSet.status = 1 AND EstSet.is_delete = 0';
		$data['TermsConditionDataArray']= $this->common_model->get_records_array($TermsSettingData);
	//Get Product Information
        $tableCustPrdMst = PRODUCT_MASTER . ' as product';
        $matchCustPrdMst = "";
        $fieldsCustPrdMst = array("product.product_id,product.product_name,product.product_type");
        $data['product_info'] = $this->common_model->get_records($tableCustPrdMst, $fieldsCustPrdMst, '', '', $matchCustPrdMst);
        $fieldsCustPrdTaxMaster = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fieldsCustPrdTaxMaster, '', '', "");

    //Get Estimate Template Information
        $table = ESTIMATE_TEMPLATE . ' as est_temp';
        $match = "";
        $fields = array("est_temp.est_temp_id, est_temp.est_temp_name");
        $data['estimate_temp_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['account_view'] = $this->viewname;

        $data['main_content'] = '/add';
		$data['drag'] = true;	//Set for Drag and Drop functionality Complete with Mehul
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }

    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 13/01/2016
     */

    public function insertdata() {
		$id = '';
		//Stop Multiple Time Insert 
		// RJ remove this comment section after done with Currency
		
		if (!validateFormSecret()) {
		   $this->session->set_flashdata('msg', ERROR_DANGER_DIV . lang('error') . ERROR_END_DIV);
		   redirect($this->viewname . '/edit/' . $id);
		  }
		
		if ($this->input->post('estimate_id')) {
            $id = $this->input->post('estimate_id');
        }
		
        $data = array();
        $data['estimate_view'] 				= $this->viewname;

        $estimate_data['estimate_auto_id'] 	= url_title($this->input->post('estimate_auto_id'), '-');
        $estimate_data['due_date'] 			= date_format(date_create($this->input->post('creation_date')), 'Y-m-d');
        $estimate_data['modified_date'] 		= datetimeformat();
		if($this->input->post('discount_Opt'))
		{
			$estimate_data['discount_option'] 	= $this->input->post('discount_Opt');
			}
		if($this->input->post('prospect_owner_id') != "")
		{
			$estimate_data['prospect_owner_id'] = $this->input->post('prospect_owner_id');
		}
	//Get Estimate Status
        $estimate_data['status'] = $this->input->post('hdn_submit_status');
        if ($this->input->post('est_userdescription_status')) {
			$estimate_data['est_userdescription_status'] = 1;
        } else {
			$estimate_data['est_userdescription_status'] = 0;
        }
		$estimate_data['est_userdescription'] 	= $this->input->post('est_userdescription',false);
        $estimate_data['est_termcondition'] 	= $this->input->post('est_termcondition',false);
        $estimate_data['est_content'] 			= $this->input->post('est_content',false);
        $estimate_data['client_can_accept_online'] = $this->input->post('client_can_accept_online');
	//Pass Currency Symbole for make insert
        $estimate_data['country_id_symbol'] 	= $this->input->post('country_id_symbol');
	    $estimate_data['subject'] 				= htmlentities($this->input->post('subject'));
	/*
	 * stores json encoded text paragraph section
	 */
        if ($this->input->post('text_paragraph')) {
            $estimate_data['text_paragraph'] = json_encode(array('text_paragraph' => $this->input->post('text_paragraph', false)));
        }
        $estimate_data['discount'] = (float)$this->input->post('discount');
	/*
	 * section for signature
	 */
	//Following code for Single insert Autograph functionality
	   $SignatureStatus = "";
	   if($this->input->post('SignatureTypeOn'))
	   {
			$SignatureStatus = $this->input->post('SignatureTypeOn');
	   }
	   if(isset($SignatureStatus) && $SignatureStatus == 'on')
	   {
			if($this->input->post('signature_date'))
			   {
				$SignatureDateFormate = date_format(date_create($this->input->post('signature_date')), 'Y-m-d');
			   } else {
				$SignatureDateFormate = "";
			   }
			$estimate_data['signature_date'] = $SignatureDateFormate; 
			$estimate_data['signature_place'] = $this->input->post('signature_place'); 
			$estimate_data['signature_name'] = $this->input->post('signature_name'); 
			$estimate_data['signature_jobrole'] = $this->input->post('signature_jobrole'); 
			/*if($this->input->post('newDigitalSign') == "1"){
			//Else Take place in Edit mode
			if($this->input->post('signature_date') != "")
			{ $estimate_data['signature_date'] = date_format(date_create($this->input->post('signature_date')), 'Y-m-d'); }
			if($this->input->post('signature_place') != "")
			{ $estimate_data['signature_place'] = $this->input->post('signature_place'); }
			if($this->input->post('signature_name') != "")
			{ $estimate_data['signature_name'] = $this->input->post('signature_name'); }
			if($this->input->post('signature_jobrole') != "")
			{ $estimate_data['signature_jobrole'] = $this->input->post('signature_jobrole'); }
			}*/
			$signatureVariable = "";
			if (strlen($this->input->post('signature_type')) > 0) {
				if ($this->input->post('signature_type') == 1) {
				//file upload
					if ($_FILES['signature-file']['tmp_name'] != '') {
						$dataImg = file_get_contents($_FILES['signature-file']['tmp_name']);
						$type = $_FILES['signature-file']['type'];
						$signatureVariable = 'data:' . $type . ';base64,' . base64_encode($dataImg);
					}
				} else {
				//direct blobsignature-digital_old
					$signatureVariable = $this->input->post('signature-digital', false);
				}
			}
			$estimate_data['signature'] = $signatureVariable;
			if(strlen($this->input->post('signature_type')) != 0)
			{
				$estimate_data['signature_type'] = $this->input->post('signature_type');
			}
	   } else {
			$estimate_data['signature_date'] 	= ""; 
			$estimate_data['signature_place'] 	= ""; 
			$estimate_data['signature_name'] 	= ""; 
			$estimate_data['signature_jobrole'] = ""; 
			$estimate_data['signature_type'] 	= "";
			$estimate_data['signature'] 	= "";
	   }
	   
	   
	//Insert Estimate Total Amount
		if($this->input->post('total'))
		{
			$estimate_data['value'] = $this->input->post('total');
			}
		if ($id) {
			/*Get Old Currency*/
				$curtable 		= ESTIMATE_MASTER . ' as ESTMster';
				$curmatch 		= "ESTMster.estimate_id = " . $id;
				$oldCurrecy = $this->common_model->get_records($curtable, '', '', '', $curmatch);
				$oldCurrAmt = $oldCurrecy[0]['country_id_symbol'];
			/*Done with Old Currency Code*/
        //Update query 
            $data = mysql_real_escape_string($data);
            $where = array('estimate_id' => $id);
			$success_update = $this->common_model->update(ESTIMATE_MASTER, $estimate_data, $where);
            if ($success_update) {
                $data['msg'] = $this->lang->line('estimate_update_msg');
//$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        } else {
		/*
		 * Current Login User ID Insert in Estimate
		 */
			$LoginUserArray = $this->session->userdata('LOGGED_IN');
            $estimate_data['login_id'] = $LoginUserArray['ID'];
		//
			$currencyInfo = getDefaultCurrencyInfo(); 
			$oldCurrAmt = $currencyInfo['country_id'];
		
		//Insert Query
            $estimate_data['estimate_auto_id'] = $this->input->post('estimate_auto_id');
            $estimate_data['send_date'] = datetimeformat();
            $estimate_data['created_date'] = datetimeformat();
            $estimate_data['modified_date'] = datetimeformat();
            $success_insert = $this->common_model->insert(ESTIMATE_MASTER, $estimate_data);

            if ($success_insert) {
                $data['msg'] = $this->lang->line('estimate_add_msg');
//$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        } 
        if ($id == '') {
            $id = $success_insert;
        }

	/*
	 * Following Section for Insert Client And Recipients
	 */
		
        $estProspectId = explode("_", $this->input->post('prospect_id'));

        if (isset($estProspectId) && !empty($estProspectId)) {
            $estRecipientArray = $this->input->post('recipient_id');
            if (count($estRecipientArray) > 0) {
                $where = array('estimate_id' => $id);
                $this->common_model->delete(ESTIMATE_CLIENT, $where);

                foreach ($estRecipientArray as $estRecipientId) {
                    $estRecipientIdArray = explode("_", $estRecipientId);  //Create Array of Recipient 

                    $estClientData['estimate_id'] = $id;
                    $estClientData['recipient_type'] = $estRecipientIdArray[0];
                    $estClientData['recipient_id'] = $estRecipientIdArray[1];
                    $estClientData['client_type'] = $estProspectId[0];
                    $estClientData['client_name'] = $this->input->post('client_name');
                    $estClientData['prospect_id'] = $estProspectId[1];
                    $estClientData['created_date'] = datetimeformat();
                    $estClientData['modified_date'] = datetimeformat();

                    //Insert Client Query 
                    $this->common_model->insert(ESTIMATE_CLIENT, $estClientData);
                }
            }
        }
        /* $estimate_data['estimate_auto_id'] = $this->input->post('estimate_auto_id');
          $estimate_data['created_date'] = datetimeformat();
          $success_insert = $this->common_model->insert(ESTIMATE_MASTER, $estimate_data); */
        /*
         * this section is used for New product Insert and mapping
         */
        $product_new_data 			= array();
        $product_inserted_id 		= array();
        $product_new_name 			= $this->input->post('product_new_name');
        $product_new_description 	= $this->input->post('product_new_description');
        $product_new_qty 			= $this->input->post('product_new_qty');
        $product_new_tax 			= $this->input->post('product_new_tax');
		$product_new_discount 		= $this->input->post('product_new_discount');
		$product_new_disoption 		= $this->input->post('product_new_disOption');
		$product_new_amount 		= $this->input->post('product_new_amount');
        $product_new_amount_sales 	= $this->input->post('product_new_amount_sales');
		
		$estCustomPrdOrder_new 		= $this->input->post('estCustomPrdOrder_new');
		
        if (count($product_new_name) > 0) {
			$currencyInfo = getDefaultCurrencyInfo(); 
            for ($i = 0; $i < count($product_new_name); $i++) {
                //  pr( array('product_name' => $product_new_name[$i], 'product_description' => $product_new_description[$i], 'sales_price_unit' => $product_new_amount_sales[$i], 'created_date' => datetimeformat()));
                $product_inserted_id[] = $this->common_model->insert(PRODUCT_MASTER, array('product_name' => $product_new_name[$i], 'currency_symbol' => $estimate_data['country_id_symbol'], 'product_tax_id' => $product_new_tax[$i], 'product_description' => htmlentities($product_new_description[$i]), 'status' => '1', 'sales_price_unit' => $product_new_amount_sales[$i], 'created_date' => datetimeformat()));
            }
        }
        if (count($product_inserted_id) > 0) {
            for ($i = 0; $i < count($product_inserted_id); $i++) {
                $product_new_data[] = array('estimate_id' => $id, 'product_id' => $product_inserted_id[$i], 'product_qty' => $product_new_qty[$i], 'product_discount' => $product_new_discount[$i], 'product_disoption' => $product_new_disoption[$i], 'product_order' => $estCustomPrdOrder_new[$i], 'product_sales_price' => $product_new_amount_sales[$i], 'product_name' => $product_new_name[$i], 'product_description' => htmlentities($product_new_description[$i]), 'product_amt' => $product_new_amount[$i], 'created_date' => datetimeformat(), 'product_tax' => $product_new_tax[$i]);
            }
        }
//        pr($product_new_data);
//        die;
	/*
	 * this section is used for Existing product mapping
	 */
        $product_id 			= $this->input->post('product_id');
        $product_qty 			= $this->input->post('product_qty');
        $product_tax 			= $this->input->post('product_tax');
        $product_amount 		= $this->input->post('product_amount');
		$product_discount 		= $this->input->post('product_discount');
		$product_disoption 		= $this->input->post('product_disOption');
		$product_amount_sales 	= $this->input->post('product_amount_sales'); 
		//$product_name 		= $this->input->post('product_name'); 
		$product_description 	= $this->input->post('product_description'); 
		$estCustomPrdOrder 		= $this->input->post('estCustomPrdOrder'); 
		 /*
		 * Change Currency 
		 */
			$EstsubmitValueForChangeCurrency = $this->input->post('HdnSubmitBtnVlaue');
			/*echo $oldCurrAmt;echo "<br> New one".$estimate_data['country_id_symbol'];echo "<br>";*/
				$oldCurCode = getCourrencyCode($oldCurrAmt);
				$newCurCode = getCourrencyCode($estimate_data['country_id_symbol']);
			if($EstsubmitValueForChangeCurrency == 'currencyChange')
			{
			//Calculation for convert Amount as per Currency
				//pr($product_amount_sales); Old AMount
				$curFlag = 0;
				foreach($product_amount_sales as $singleProductAmtSales)
				{
					/*echo "<br>";echo "Old amount - ". $singleProductAmtSales.' - ';*/
					$product_amount_sales[$curFlag] = helperConvertCurrency($singleProductAmtSales, $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
					$product_amount[$curFlag] = helperConvertCurrency($product_amount[$curFlag], $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
				//Set Discount amount array
					$amount = $product_discount[$curFlag];
					if($product_disoption[$curFlag] == 'amt')
					{
						$amount = helperConvertCurrency($product_discount[$curFlag], $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
					}
					$product_discount[$curFlag] = $amount;
					$curFlag++;
				}
			}
		$product_data = array();
        if (count($product_id) > 0) {
			for ($i = 0; $i < count($product_id); $i++) {
                $product_data[] = array('estimate_id' => $id, 'product_id' => $product_id[$i], 'product_qty' => $product_qty[$i], 'product_discount' => $product_discount[$i], 'product_disoption' => $product_disoption[$i], 'product_description' => htmlentities($product_description[$i]), 'product_order' => $estCustomPrdOrder[$i], 'product_sales_price' => $product_amount_sales[$i],  'product_amt' => $product_amount[$i], 'created_date' => datetimeformat(), 'product_tax' => $product_tax[$i]);
            }
        }
	
	/*
	 * this section is used for product Group mapping
	 */
		$product_group_id 			= $this->input->post('product_group_id');
		$estCustomPrdOrder_group	= $this->input->post('estCustomPrdOrder_group');
		
		$product_group_name 		= $this->input->post('product_group_name');
		$product_group_description 	= $this->input->post('product_group_description');
		
		$prd_grpQty					= $this->input->post('prd_grpQty');
		$product_group_product_id 	= $this->input->post('product_group_product_id');
        $product_group_qty 			= $this->input->post('product_group_qty');
        $product_group_tax 			= $this->input->post('product_group_tax');
        $product_group_amount 		= $this->input->post('product_group_amount');
		$product_group_amount_sales = $this->input->post('product_group_amount_sales');
		$product_group_discount		= $this->input->post('product_group_discount');
		$product_group_disOption	= $this->input->post('product_group_disOption');
        $product_group_data 		= array();
	//Currency convert amount
		$product_group_tax_amt		= $this->input->post('product_group_tax_amt');
		$product_group_total_amt 	= $this->input->post('product_group_total_amt');
		$product_group_discounted_amt= $this->input->post('product_group_discounted_amt');
		
		//pr($product_group_amount);
		if (count($product_group_id) > 0) {
			$prdFlag = 1;
			$this->common_model->delete(ESTIMATE_PRODUCT_GROUP, $where);
			$i = 0;
			foreach($product_group_product_id as $firstprdArryKey=>$productGrpPrdID){
			//for ($i = 0; $i < count($product_group_id); $i++) {
				//$product_grp_code = random_string('nozero', 12);
				$prdGrpTaxAmount = $product_group_tax_amt[$i];
				$prdGrpTotalAmt  = $product_group_total_amt[$i];
				$prdGrpDiscAmt   = $product_group_discounted_amt[$i];
			/** Change Currency for Product Group */
				if($EstsubmitValueForChangeCurrency == 'currencyChange')
				{
					/*echo "<br>";echo $oldCurCode[0]['currency_code'].' - '.$newCurCode[0]['currency_code'];echo "<br>";*/
					$prdGrpTaxAmount = helperConvertCurrency($product_group_tax_amt[$i], $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
					$prdGrpTotalAmt  = helperConvertCurrency($product_group_total_amt[$i], $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
					$prdGrpDiscAmt   = helperConvertCurrency($product_group_discounted_amt[$i], $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
				}
				/*echo 'Tax Amount - '.$prdGrpTaxAmount;echo "<br>";
				echo 'Total Amount = '.$prdGrpTotalAmt;echo "<br>";
				echo 'Prodcut Group discount amount - '.$prdGrpDiscAmt;echo "<br>";*/
				$prdLoopWiseGrpID = $product_group_id[$i];
				$est_prd_grp_id = $this->common_model->insert(ESTIMATE_PRODUCT_GROUP, array('estimate_id' => $id, 'product_group_id' => $product_group_id[$i], 'product_group_total_amt' => $prdGrpTotalAmt, 'product_group_discounted_amt' => $prdGrpDiscAmt, 'product_group_qty' => $prd_grpQty[$i], 'product_group_tax_amt' => $prdGrpTaxAmount));
				if (count($product_group_product_id[$firstprdArryKey][$product_group_id[$i]]) > 0) {
					$flgInnerPrd = 1; // Remove This line
					$j = 0;
					for ($j = 0; $j < count($product_group_product_id[$firstprdArryKey][$product_group_id[$i]]); $j++) {
						$prdGroupAmount = $product_group_amount[$firstprdArryKey][$product_group_id[$i]][$j];
						$prdGrpSalesAmt = $product_group_amount_sales[$firstprdArryKey][$product_group_id[$i]][$j];
					//Convert Discount as per selected Currency symbol
						$prdGrpDiscountAmt = $product_group_discount[$firstprdArryKey][$product_group_id[$i]][$j];
						$prdGrpDiscountOpt = $product_group_disOption[$firstprdArryKey][$product_group_id[$i]][$j];
						//$prdGroupSalesAmount = $product_group_amount[$firstprdArryKey][$product_group_id[$i]][$j];
							/*
							 * Change Currency for Product Group
							 */
							if($EstsubmitValueForChangeCurrency == 'currencyChange')
								{
									//pr($product_amount_sales); Old AMount
									$prdGroupAmount = helperConvertCurrency($prdGroupAmount, $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
									$prdGrpSalesAmt = helperConvertCurrency($prdGrpSalesAmt, $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
									//Set Discount amount array
									$grpAmount = $prdGrpDiscountAmt;
									if($prdGrpDiscountOpt == 'amt')
									{
										$grpAmount = helperConvertCurrency($prdGrpDiscountAmt, $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
									}
									$prdGrpDiscountAmt = $grpAmount;
								}
							/*End With Currency Convert*/
						$product_group_data[] = array('estimate_id' => $id, 
						'product_group_id' 		=> $product_group_id[$i], 
						'product_order' 		=> $estCustomPrdOrder_group[$i], 
						'product_id' 			=> $product_group_product_id[$firstprdArryKey][$product_group_id[$i]][$j],
						
						'product_name' 			=> $product_group_name[$firstprdArryKey][$product_group_id[$i]][$j],
						'product_description' 	=> $product_group_description[$firstprdArryKey][$product_group_id[$i]][$j],
						
						'product_qty' 			=> $product_group_qty[$firstprdArryKey][$product_group_id[$i]][$j], 
						'product_disoption' 	=> $product_group_disOption[$firstprdArryKey][$product_group_id[$i]][$j], 
						//'product_discount' 		=> $product_group_discount[$firstprdArryKey][$product_group_id[$i]][$j], 
						'product_discount' 		=> $prdGrpDiscountAmt, 
						'product_sales_price' 	=> $prdGrpSalesAmt, 
						'product_amt' 			=> $prdGroupAmount, 
						'created_date'			=> datetimeformat(), 
						'est_prd_grp_id' 		=> $est_prd_grp_id, 
						'product_tax' 			=> $product_group_tax[$firstprdArryKey][$product_group_id[$i]][$j]
						);
                    }
				}
			$prdFlag++;
			$i++;
            }
        } //echo "<br>sdf";pr($product_group_data);exit;
		$dataInsInMapping = array();
        $dataInsInMapping = array_merge($product_data, $product_group_data, $product_new_data);
		if (count($dataInsInMapping) > 0) {
            $where = array('estimate_id' => $id);
            $this->common_model->delete(ESTIMATE_PRODUCT, $where);
            foreach ($dataInsInMapping as $dataMap) {
                $this->common_model->insert(ESTIMATE_PRODUCT, $dataMap);
            }
        }
//new
        /* image upload code*/
        $file_name=array();
        $file_array1=$this->input->post('file_data');

        $file_name=$_FILES['estimate_files']['name'];
        if(count($file_name)>0 && count($file_array1)>0){
            $differentedImage=array_diff($file_name,$file_array1);
            foreach($file_name as $file)
            {
                if(in_array($file,$differentedImage))
                {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if(!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['estimate_files']['name'][$key]);
                    unset($_FILES['estimate_files']['type'][$key]);
                    unset($_FILES['estimate_files']['tmp_name'][$key]);
                    unset($_FILES['estimate_files']['error'][$key]);
                    unset($_FILES['estimate_files']['size'][$key]);
                }
            }

        }

        $_FILES['estimate_files']=$arr = array_map('array_values', $_FILES['estimate_files']);
        $data['estimate_view'] = $this->viewname;
        $uploadData = uploadImage('estimate_files', estimate_upload_path, $data['estimate_view']);
        //pr($uploadData);exit;
        $estimatefiles = array();
        foreach($uploadData as $dataname){
            $estimatefiles[] =$dataname['file_name'];
        }
        $estimate_file_str = implode(",",$estimatefiles);

        $file2 = $this->input->post('fileToUpload');
        if(!(empty($file2))){
            $file_data = implode("," ,$file2);
        }else{
            $file_data = '';
        }
        if(!empty($estimate_file_str) && !empty($file_data)){
            $estimatedata['file'] = $estimate_file_str.','.$file_data;
        }else if(!empty($estimate_file_str)){
            $estimatedata['file'] = $estimate_file_str;
        }else{
            $estimatedata['file'] = $file_data;
        }
        $estimatedata['file_name']=$file_data;
        if ($estimatedata['file_name'] != '') {
            $explodedData = explode(',', $estimatedata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }
        //end

        //$uploadData = uploadImage('estimate_files', estimate_upload_path, $data['estimate_view']);
        $estFIles = array();

        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $est_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $estFIles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'estimate_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => estimate_upload_path, 'estimate_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }

        if (count($estFIles) > 0) {
            $where = array('estimate_id' => $id);
            //  $this->common_model->delete(COST_FILES, $where);
            if (!$this->common_model->insert_batch(ESTIMATE_FILES, $estFIles)) {
                $this->session->set_flashdata('msg', ERROR_DANGER_DIV . lang('error') . ERROR_END_DIV);
                redirect($this->module); //Redirect On Listing page
            }
        }

        /**
         * SOFT DELETION CODE STARTS MAULIK SUTHAR
         */
        $softDeleteImagesArr = $this->input->post('softDeletedImages');
        $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
        if (count($softDeleteImagesUrlsArr) > 0) {
            foreach ($softDeleteImagesUrlsArr as $urls) {
                unlink(BASEPATH . '../' . $urls);
            }
        }

        if (count($softDeleteImagesUrlsArr) > 0) {
            $dlStr = implode(',', $softDeleteImagesArr);
            $this->common_model->delete(ESTIMATE_FILES, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */

    //Preview - 3, Draft - 2, Active - 1, Inactive - 0
		$EstsubmitValue = $this->input->post('HdnSubmitBtnVlaue');
		$ESTActionArray = array();
		if ($EstsubmitValue == 'draft') {
			$RedirectMSG 	= lang('SUCCESS_DRAFT_MSG');
		} elseif ($EstsubmitValue == 'preview') {
			$ESTActionArray = array('EstAction' => 'preview');
			$RedirectMSG 	= lang('SUCCESS_SAVE_MSG');
			//$this->session->set_flashdata('msg', ERROR_SUCCESS_DIV . $RedirectMSG . ERROR_END_DIV);
			//redirect($this->viewname . '/preview/' . $id);
		} elseif ($EstsubmitValue == 'pdf') {
		//Direct Call Generate PDF function and redirect Edit page
			//$this->GeneratePrintPDF($id,'pdf');
			//exit;
			$ESTActionArray = array('EstAction' => 'pdf');
			$RedirectMSG 	= lang('SUCCESS_SAVE_MSG');
		} elseif ($EstsubmitValue == 'print') {
		//Direct Call the Generate Print function and redirect in Edit page
			//echo $this->GeneratePrintPDF($id,'print');
			$ESTActionArray = array('EstAction' => 'print');
			$RedirectMSG 	= lang('SUCCESS_SAVE_MSG');
		} elseif ($EstsubmitValue == 'currencyChange') {
			$ESTActionArray = array('EstAction' => 'currencyChange');
			$RedirectMSG 	= lang('SUCCESS_SAVE_MSG');
		} elseif ($EstsubmitValue == 'sendEstimate') {
		//Call The Send Estimate Function and then redirect on 
			//$this->SendEstimate($id);
			//exit;
			$HdnChangeEmailTmp = $this->input->post('HdnChangeEmailTmp');
			if($HdnChangeEmailTmp == 'yes')
			{
				$this->session->set_userdata('ESTChangeEmailTMP', $HdnChangeEmailTmp);
			}
			$ESTActionArray = array('EstAction' => 'sendEstimate');
			$RedirectMSG 	= lang('SUCCESS_SAVE_MSG');
		} else {
			$RedirectMSG 	= lang('SUCCESS_SAVE_MSG');
		}
			if(!empty($ESTActionArray) && $ESTActionArray != "")
			{
				$this->session->set_userdata('ESTAction', $ESTActionArray);
			}
		    $this->session->set_flashdata('msg', ERROR_SUCCESS_DIV . $RedirectMSG . ERROR_END_DIV);
			redirect($this->viewname . '/edit/' . $id);
            //$this->session->set_flashdata('msg', ERROR_SUCCESS_DIV . lang('error') . ERROR_END_DIV);
        exit;
    }

    /*
      @Author 	: RJ(Rupesh Jorkar)
      @Desc   	: Add Product and Product Group In database
      @Input 	:
      @Output	:
      @Date   	: 18/02/2016
     */

    public function insertTemplate() {
        $estimate_data['est_temp_name'] = $this->input->post('est_temp_name');
        $estimate_data['created_date'] 	= datetimeformat();
        $estimate_data['modified_date'] = datetimeformat();
        $success_insert 				= $this->common_model->insert(ESTIMATE_TEMPLATE, $estimate_data);
        if ($success_insert) {
		/*
		 * this section is used for Existing product mapping
		 */
            $product_id 		= $this->input->post('product_id');
            $product_disoption	= $this->input->post('product_disOption');
            $product_discount	= $this->input->post('product_discount');
            $product_qty 		= $this->input->post('product_qty');
            $product_tax 		= $this->input->post('product_tax');
            $product_amount 	= $this->input->post('product_amount');
            $product_data 		= array();
            if (count($product_id) > 0) {
                for ($i = 0; $i < count($product_id); $i++) {
                    $product_data[] = array('est_temp_id' => $success_insert, 'product_id' => $product_id[$i], 'product_disoption' => $product_disoption[$i], 'product_discount' => $product_discount[$i], 'product_qty' => $product_qty[$i], 'product_amt' => $product_amount[$i], 'created_date' => datetimeformat(), 'modified_date' => datetimeformat(), 'product_tax' => $product_tax[$i]);
                }
            }
		/*
		 * this section is used for product Group mapping
		 */
            $group_product_information = $this->input->post('group_product_information')[0];
            $product_group_data = array();
            if (count($group_product_information) > 0) {
                foreach ($group_product_information as $grp_id => $product_information) {
                    //Group ID = $grp_id;
                    if (count($product_information['product_id']) > 0) {
                        for ($i = 0; $i < count($product_information['product_id']); $i++) {
                            $product_group_data[] = array('est_temp_id' => $success_insert, 'product_group_id' => $grp_id, 'product_id' => $product_information['product_id'][$i], 'product_disOption' => $product_information['product_disOption'][$i], 'product_discount' => $product_information['product_discount'][$i], 'product_qty' => $product_information['product_qty'][$i], 'product_amt' => $product_information['product_amount'][$i], 'created_date' => datetimeformat(), 'modified_date' => datetimeformat(), 'product_tax' => $product_information['product_tax'][$i]);
                        }
                    }
                }
            }
		//Marge Two Group product and existing product array  
            $dataInsInMapping = array();
            $dataInsInMapping = array_merge($product_data, $product_group_data);
            if (count($dataInsInMapping) > 0) {
                foreach ($dataInsInMapping as $dataMap) {
                    $this->common_model->insert(ESTIMATE_TEMPLATE_PRODUCT, $dataMap);
                }
            }
            echo 'done';
        } //Close If Condition for Success message
        else 
		{
            echo 'error';
        }
    }
    /*
      @Author 	: RJ(Rupesh Jorkar)
      @Desc   	: Show Product and Product Group In database
      @Input 	:
      @Output	:
      @Date   	: 01/03/2016
     */
    public function showTemplateProduct() {
        $est_temp_id = $this->input->post('est_temp_id');
        $data['incId'] = 0;
        $data['estSymbolSelected'] = $this->input->post('estSymbolSelected');
        /*
         * Fetch Product information
         */
		$simpleProduct['fields'] = ['*'];
        $simpleProduct['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=estTempprd.product_id', PRODUCT_TAX_MASTER . ' as ptm' => 'PM.product_tax_id = ptm.tax_id');
        $simpleProduct['join_type'] = 'left';
        $simpleProduct['table'] = ESTIMATE_TEMPLATE_PRODUCT . ' as estTempprd';
        $simpleProduct['match_and'] = 'estTempprd.est_temp_id=' . $est_temp_id .' AND PM.is_delete = "0"';
        $data['estimate_product'] = $this->common_model->get_records_array($simpleProduct);
		
		$Taxfields = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $Taxfields, '', '', "tax.is_delete = 0");
        //Fetch Product Information
        $table = PRODUCT_MASTER . ' as product';
        $match = "";
        $fields = array("product.product_id,product.product_name,product.product_type");
        $data['product_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

        $this->load->view('files/estimateProductTemplate', $data);
    }
    /*
      @Author : maulik suthar
      @Desc   : edit function
      @Input 	:
      @Output	:
      @Date   : 18/02/2016
     */
    public function edit($id,$estAction = NULL) {
	//Breadcrumb Code
		$this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('estimates'), 'Estimates');
        $this->breadcrumbs->push(lang('edit_estimate'), ' ');
		$data = array();
		if ($id > 0) {
            $data['ctr_view'] = $this->viewname;
			/*
			 * Edit page Estimate Records
			 */
            $data['main_content'] = '/edit';
            $table = ESTIMATE_MASTER . ' as ESTMster';
            $match = "ESTMster.status != '3' && ESTMster.estimate_id = " . $id;
            $data['editRecord'] = $this->common_model->get_records($table, '', '', '', $match);
		//Page redirect on Estimate listing page if records are delete from estimate
			if(empty($data['editRecord']) && count($data['editRecord']) == 0)
			{
				$this->session->set_flashdata('msg', ERROR_DANGER_DIV . lang('error') . ERROR_END_DIV);
				redirect($this->viewname); //Redirect On Listing page   
				exit;
			}
			$ESTActionInfo = $this->session->userdata('ESTAction');
			$ESTChangeEmailTMP = $this->session->userdata('ESTChangeEmailTMP');
		//Get Prospect Owner 
			$data['prospect_owner'] = $this->common_model->getSystemUserData();
		//Code for Show Change Email Template Popup
			if(isset($ESTChangeEmailTMP) && !empty($ESTChangeEmailTMP) && count($ESTChangeEmailTMP) != 0)
			{
				if($data['editRecord'][0]['client_can_accept_online'] == 1)
				{
					$templateID = '38';
				} else {
					$templateID = '39';
				}
			// Get Template from Template Master
				$EmailTMPtable 			= EMAIL_TEMPLATE_MASTER . ' as et';
				$EmailTMPmatch 			= "et.template_id = ".$templateID;
				$EmailTMPfields 		= array("et.subject,et.body");
				$data['EmailTMPInfo']	= $this->common_model->get_records($EmailTMPtable,$EmailTMPfields,'','',$EmailTMPmatch);
				$ESTChngEmiTMP = $ESTChangeEmailTMP;
				$this->session->unset_userdata('ESTChangeEmailTMP');
			} else {
				$ESTChngEmiTMP = "";
			}
		//Set Edit Action for Download PDF, Print and Send Estimate
			if(isset($ESTActionInfo) && !empty($ESTActionInfo) && count($ESTActionInfo) != 0)
			{
				$estAction = $ESTActionInfo['EstAction'];
				$this->session->unset_userdata('ESTAction');
			} else {
				$estAction = "";
			}
			$data['estAction'] 		= $estAction;
			$data['ESTChngEmiTMP'] 	= $ESTChngEmiTMP;
			$data['incId'] = 0;
            $data['discount'] 	= 0;
            $data['subtotal'] 	= 0;
            $data['taxes'] 		= 0;
            $data['total'] 		= 0;
            $data['url'] 	= base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
		//Top side selected Header menu name pass
            $data['header'] 	= array('menu_module' => 'crm');
		//Get Country Record
            $cnt_table 			= COUNTRIES . ' as cm';
            $cnt_fields 		= array("cm.country_name,cm.country_id");
            $data['country_data'] = $this->common_model->get_records($cnt_table, $cnt_fields, '', '', '', '', '', '', '', '', '', '');
		//Get Client and Recipient Selected
            $EstClient_table 	= ESTIMATE_CLIENT . ' as EstClntTbl';
            $EstClient_match 	= "EstClntTbl.estimate_id = " . $id;
            $EstClient_fields 	= array("EstClntTbl.estimate_id, EstClntTbl.prospect_id,EstClntTbl.client_name, EstClntTbl.client_type, EstClntTbl.recipient_id, EstClntTbl.recipient_type, ");
            $EstClientArray 	= $this->common_model->get_records($EstClient_table, $EstClient_fields, '', '', $EstClient_match);
            $RecipientBlnkArray = array();
            $ClientBlnkArra = "";
            $selectedCondition 		= "";
            $data['client_name'] 	= "";
            if (count($EstClientArray) > 0) {
                //Get Selected Client Id 
                $ClientBlnkArra 	= $EstClientArray[0]['client_type'] . '_' . $EstClientArray[0]['prospect_id'];
                $data['client_name'] = $EstClientArray[0]['client_name'];
                //Create Condition for make Company filter
                if ($EstClientArray[0]['client_type'] == 'company') {
                    $selectedCondition = " AND company_id = " . $EstClientArray[0]['prospect_id'];
                } else {
                    $selectedCondition = "";
                }
                foreach ($EstClientArray as $EstClntID) {
                    $RecipientBlnkArray[] = $EstClntID['recipient_type'] . '_' . $EstClntID['recipient_id'];
                }
            }
			
            $data['EstClntArray'] 		= $ClientBlnkArra;
            $data['EstRecipientArray'] 	= $RecipientBlnkArray;

        //Get Client Information for Recipients
		    $table = PROSPECT_MASTER . ' as pro';
            $match = "pro.status_type = 3 AND pro.status = 1 AND pro.is_delete = 0 " . $selectedCondition;
            $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
            $data['RecipientClientInfo'] = $this->common_model->get_records($table, $fields, '', '', $match);
		//Get Contact Information for Recipients
            $table = CONTACT_MASTER . ' as con_mst';
            $match = "con_mst.status = 1 AND con_mst.is_delete = 0 " . $selectedCondition;
            $fields = array("con_mst.contact_id, con_mst.contact_name");
            $data['RecipientContactInfo'] = $this->common_model->get_records($table, $fields, '', '', $match);
		//Get Company Information For Client Select Box
            $ComInfo_table = COMPANY_MASTER . ' as com_mst';
            $ComInfo_match = "com_mst.status = 1";
            $ComInfo_fields = array("com_mst.company_id, com_mst.company_name");
            $data['company_info'] = $this->common_model->get_records($ComInfo_table, $ComInfo_fields, '', '', $ComInfo_match);
		//Get Client Information for Client Select Box
            $ClntInfo_table = PROSPECT_MASTER . ' as pro';
            $ClntInfo_match = "pro.status_type = 3 AND pro.status = 1 AND pro.is_delete = 0";
			$ClntInfo_fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
            $data['client_info'] = $this->common_model->get_records($ClntInfo_table, $ClntInfo_fields, '', '', $ClntInfo_match);
		//Get Contact Information for Client Select Box
            $ContactInfo_table = CONTACT_MASTER . ' as con_mst';
            $ContactInfo_match = "con_mst.status = 1";
            $ContactInfo_fields = array("con_mst.contact_id, con_mst.contact_name");
            $data['contact_info'] = $this->common_model->get_records($ContactInfo_table, $ContactInfo_fields, '', '', $ContactInfo_match);
		//Get Product Information
            $table = PRODUCT_MASTER . ' as product';
            $match = "";
            $fields = array("product.product_id,product.product_name,product.product_type");
            $data['product_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $fields = array("tax.tax_id,tax.tax_percentage");
            $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "tax.is_delete = 0");
		
		//Get Terms and Condition 	
			$TermsSettingData['fields'] 	= ['EstSet.estimate_settings_id, EstSet.name, EstSet.terms, EstSet.conditions, EstSet.status'];
			$TermsSettingData['table'] 		= ESTIMATE_SETTINGS . ' as EstSet';
			$TermsSettingData['match_and'] 	= 'EstSet.status = 1 AND EstSet.is_delete = 0';
			$data['TermsConditionDataArray']= $this->common_model->get_records_array($TermsSettingData);
		/*
		 * get grouped product data
		 */
            $table = PRODUCT_GROUP_MASTER . ' as PG';
            $match = "PG.is_delete = '0' AND PG.status = '1'";

            $fields = array("PG.product_group_id,PG.product_group_name");
            $data['group_info'] = $this->common_model->get_records($table, $fields, "", "", $match);
		/*
		 * mapping data of products - Edit Time show Selected
		 */
            $mapingProducts['fields'] 		= ['*, EP.product_description as prdDescription'];
            $mapingProducts['join_tables'] 	= array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id', PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=PM.product_tax_id');
            $mapingProducts['join_type'] 	= 'left';
            $mapingProducts['table'] 		= ESTIMATE_PRODUCT . ' as EP';
            $mapingProducts['match_and'] 	= 'EP.estimate_id=' . $id . ' AND PM.is_delete="0" AND EP.product_group_id is null';
			$mapingProducts['orderby'] 		= 'EP.product_order';
            $mapingProducts['sort'] 		= 'ASC';
            $data['estimate_product'] 		= $this->common_model->get_records_array($mapingProducts);
			
		/*
		 * Edit time Get Selected Product Group 
		 */
			$prdGroupArray = array();
			$prdInfoArray = array();
			$mapingGropuedid['fields'] 		= ['*','product_group_id', 'est_prd_grp_id'];
            $mapingGropuedid['table'] 		= ESTIMATE_PRODUCT_GROUP . ' as EPGID';
            $mapingGropuedid['match_and'] 	= 'EPGID.estimate_id=' . $id ;
            $prd_group_array = $this->common_model->get_records_array($mapingGropuedid);
			if(count($prd_group_array) != 0){
				foreach($prd_group_array as $prd_group_id)
				{
				//Get Estimate Product id as per Product Group Auto id
					$estPrdArry['fields'] 		= ['*','est_prd_id', 'product_qty', 'product_id', 'product_group_id', 'est_prd_grp_id'];
					$estPrdArry['table'] 		= ESTIMATE_PRODUCT . ' as ESTPRD';
					$estPrdArry['match_and'] 	= 'ESTPRD.est_prd_grp_id=' . $prd_group_id['est_prd_grp_id'];
					$estPrdIdArry 				= $this->common_model->get_records_array($estPrdArry);
					$tempEstPrdIdArry = array();
					if(count($estPrdIdArry) != 0){
						foreach($estPrdIdArry as $estPrdId)
						{
							$tempEstPrdIdArry[] = $estPrdId['product_id'];
						}
					//Push Product Order in Group Array
						$prd_group_id['product_order']	= $estPrdIdArry[0]['product_order'];
					//Push IsGroup Status in Group Array
						$prd_group_id['isGroup']	= 'yes';
					//Get Product information as per Product id
						$prdGroupArray[] 			= $prd_group_id;
						$group_info_products 		= $this->EstimateModel->getprdListByExistingGroupId($prd_group_id['est_prd_grp_id'],$prd_group_id['product_group_id'], $tempEstPrdIdArry,$id);
						$prdInfoArray[$estPrdIdArry[0]['product_order']] 		= $group_info_products;
					}
				}
			}
			$data['group_ids'] 			= $prdGroupArray; 
			$data['group_info_products']= $prdInfoArray; 
			$wholePrdMergeArray = array_merge ($data['group_ids'], $data['estimate_product']);
		//Make whole product Array in Order Wise 
			$orderWiseAllPrdData = array();
			foreach($wholePrdMergeArray as $prdMergeDetail)
			{
				$orderWiseAllPrdData[$prdMergeDetail['product_order']] = $prdMergeDetail;
			}
			ksort($orderWiseAllPrdData);
		//Set Order Wise Product and Product Group in one Array
			$data['ordWiseAllPrdArray'] = $orderWiseAllPrdData;
			/*pr($data['wholePrdMerge']);
			echo "Individual";
				pr($data['estimate_product']);
			echo "test";
			pr($data['group_ids']);pr($data['group_info_products']);
			*/
		//Get Estimate Template Information
            $table = ESTIMATE_TEMPLATE . ' as est_temp';
            $match = "";
            $fields = array("est_temp.est_temp_id, est_temp.est_temp_name");
            $data['estimate_temp_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
		/**
		 * Images data
		 */
            $params['fields'] = ['*'];
            $params['table'] = ESTIMATE_FILES . ' as EM';
            $params['match_and'] = 'EM.estimate_id=' . $id . '';
            $data['estimate_files'] = $this->common_model->get_records_array($params);
		/*
		 * Below code for Estimate Preview
		 */
		$PreviewClientArray = array();  //Store Estimate Preview Option
            if (count($EstClientArray) > 0) {
                $PreviewClientType = $EstClientArray[0]['client_type'];
                $PreviewClientID = $EstClientArray[0]['prospect_id'];
                if ($PreviewClientType == 'client') {
                //Get Client Information for Recipients
                    $clntPreview_table = PROSPECT_MASTER . ' as pro';
                    $clntPreview_match = "pro.status_type = 3 AND prospect_id = " . $PreviewClientID;
                    $clntPreview_fields = array("pro.*, cnt.country_name");
                    $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=pro.country_id');
                    $GetPreClientData = $this->common_model->get_records($clntPreview_table, $clntPreview_fields, $join_tables, 'left', $clntPreview_match);

                    $PreviewClientArray['name'] = $GetPreClientData[0]['prospect_name'];
                    $PreviewClientArray['address1'] = $GetPreClientData[0]['address1'];
                    $PreviewClientArray['address2'] = $GetPreClientData[0]['address2'];
                    $PreviewClientArray['city'] = $GetPreClientData[0]['city'];
                    $PreviewClientArray['state'] = $GetPreClientData[0]['state'];
                    $PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
                    $PreviewClientArray['postal_code'] = $GetPreClientData[0]['postal_code'];
                    $PreviewClientArray['image'] = ""; //Image Field not available in Prospect Master table
                } else if ($PreviewClientType == 'contact') {
                //Get Contact Information for Recipients
                    $conPreview_table = CONTACT_MASTER . ' as con_mst';
                    $conPreview_match = "con_mst.status = 1 AND contact_id = " . $PreviewClientID;
                    $conPreview_fields = array("con_mst.*, cnt.country_name");
                    $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=con_mst.country_id');
                    $GetPreClientData = $this->common_model->get_records($conPreview_table, $conPreview_fields, $join_tables, 'left', $conPreview_match);

                    $PreviewClientArray['name'] = $GetPreClientData[0]['contact_name'];
                    $PreviewClientArray['address1'] = $GetPreClientData[0]['address1'];
                    $PreviewClientArray['address2'] = $GetPreClientData[0]['address2'];
                    $PreviewClientArray['city'] = $GetPreClientData[0]['city'];
                    $PreviewClientArray['state'] = $GetPreClientData[0]['state'];
                    $PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
                    $PreviewClientArray['postal_code'] = $GetPreClientData[0]['postal_code'];
                    $PreviewClientArray['image'] = $GetPreClientData[0]['image'];
                } else if ($PreviewClientType == 'company') {
                //Get Company Information For Client Select Box
                    $ComPreview_table = COMPANY_MASTER . ' as com_mst';
					$ComPreview_match = "com_mst.status = 1 AND company_id = " . $PreviewClientID;
					$ComPreview_fields = array("cnt.country_name, com_mst.company_id, com_mst.company_name as CompanyName, com_mst.address1, com_mst.address2, com_mst.city, com_mst.state, com_mst.country_id, com_mst.postal_code");
					$join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=com_mst.country_id');
					$GetPreClientData = $this->common_model->get_records($ComPreview_table, $ComPreview_fields, $join_tables, 'left', $ComPreview_match);
					$PreviewClientArray['name'] 		= $GetPreClientData[0]['CompanyName'];
					$PreviewClientArray['address1'] 	= $GetPreClientData[0]['address1'];
					$PreviewClientArray['address2'] 	= $GetPreClientData[0]['address2'];
					$PreviewClientArray['city'] 		= $GetPreClientData[0]['city'];
					$PreviewClientArray['state'] 		= $GetPreClientData[0]['state'];
					$PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
					$PreviewClientArray['postal_code'] 	= $GetPreClientData[0]['postal_code'];
					$PreviewClientArray['CompanyName'] 	= $GetPreClientData[0]['CompanyName'];
                }
            }
            $data['PreviewClientInformation'] = $PreviewClientArray; //Push Client, Contact and Company information 
            //Get All Product information 	
            $previewProducts['fields'] = ['*'];
            $previewProducts['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id', PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=EP.product_tax');
            $previewProducts['join_type'] = 'left';
            $previewProducts['table'] = ESTIMATE_PRODUCT . ' as EP';
            $previewProducts['match_and'] = 'EP.estimate_id=' . $id;
            $previewProducts['orderby'] = 'EP.product_order';
            $previewProducts['sort'] = 'ASC';
            $data['previewAllProduct'] = $this->common_model->get_records_array($previewProducts);
        //Get Company Logo for Preview
            $previewlogo['fields'] = ['cnf.value'];
            $previewlogo['table'] = CONFIG . ' as cnf';
            $previewlogo['match_and'] = 'cnf.config_key="general_settings"';
            $data['PreBZCompanyInfo'] = $this->common_model->get_records_array($previewlogo);
			//Get Estimate created user name
            if (!empty($data['editRecord'][0]['login_id']) && isset($data['editRecord'][0]['login_id']) && $data['editRecord'][0]['login_id'] != "") {
                $previewLoginUsr['fields'] = ['lgn.login_id, lgn.firstname, lgn.lastname, lgn.email'];
                $previewLoginUsr['table'] = LOGIN . ' as lgn';
                $previewLoginUsr['match_and'] = 'lgn.login_id=' . $data['editRecord'][0]['login_id'];
                $EstimateLoginInfo = $this->common_model->get_records_array($previewLoginUsr);
                $EstLoginInfo = $EstimateLoginInfo[0]['firstname'] . ' ' . $EstimateLoginInfo[0]['lastname'];
            } else {
                $EstLoginInfo = "";
            }
            $data['PreviewLoginInfo'] = $EstLoginInfo;
		} else {
            $this->session->set_flashdata('msg', ERROR_DANGER_DIV . lang('error') . ERROR_END_DIV);
            redirect($this->module); //Redirect On Listing page   
        }
		$data['drag'] = true;	//Set for Drag and Drop functionality Complete with Mehul
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }

    /*
      @Author 	: RJ(Rupesh Jorkar)
      @Desc   	: Make Copy of Edit Function 
      @Input 	:
      @Output	:
      @Date   	: 19/05/2016
     */
    public function preview($id,$estAction = NULL) {
	//Breadcrumb Code
		$this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('estimates'), 'Estimates');
        $this->breadcrumbs->push('Preview Estimate', ' ');
		$data = array();
		if ($id > 0) {
            $data['ctr_view'] = $this->viewname;
			/*
			 * Edit page Estimate Records
			 */
            $data['main_content'] = '/preview';
            $table = ESTIMATE_MASTER . ' as ESTMster';
            $match = "ESTMster.status != '3' && ESTMster.estimate_id = " . $id;
            $data['editRecord'] = $this->common_model->get_records($table, '', '', '', $match);
		//Page redirect on Estimate listing page if records are delete from estimate
			if(empty($data['editRecord']) && count($data['editRecord']) == 0)
			{
				$this->session->set_flashdata('msg', ERROR_DANGER_DIV . lang('error') . ERROR_END_DIV);
				redirect($this->viewname); //Redirect On Listing page   
				exit;
			}
			$ESTActionInfo = $this->session->userdata('ESTAction');
			$ESTChangeEmailTMP = $this->session->userdata('ESTChangeEmailTMP');
		//Get Prospect Owner 
			$data['prospect_owner'] = $this->common_model->getSystemUserData();
		//Code for Show Change Email Template Popup
			if(isset($ESTChangeEmailTMP) && !empty($ESTChangeEmailTMP) && count($ESTChangeEmailTMP) != 0)
			{
				if($data['editRecord'][0]['client_can_accept_online'] == 1)
				{
					$templateID = '38';
				} else {
					$templateID = '39';
				}
			// Get Template from Template Master
				$EmailTMPtable 			= EMAIL_TEMPLATE_MASTER . ' as et';
				$EmailTMPmatch 			= "et.template_id = ".$templateID;
				$EmailTMPfields 		= array("et.subject,et.body");
				$data['EmailTMPInfo']	= $this->common_model->get_records($EmailTMPtable,$EmailTMPfields,'','',$EmailTMPmatch);
				$ESTChngEmiTMP = $ESTChangeEmailTMP;
				$this->session->unset_userdata('ESTChangeEmailTMP');
			} else {
				$ESTChngEmiTMP = "";
			}
		//Set Edit Action for Download PDF, Print and Send Estimate
			if(isset($ESTActionInfo) && !empty($ESTActionInfo) && count($ESTActionInfo) != 0)
			{
				$estAction = $ESTActionInfo['EstAction'];
				$this->session->unset_userdata('ESTAction');
			} else {
				$estAction = "";
			}
			$data['estAction'] 		= $estAction;
			$data['ESTChngEmiTMP'] 	= $ESTChngEmiTMP;
			$data['incId'] = 0;
            $data['discount'] 	= 0;
            $data['subtotal'] 	= 0;
            $data['taxes'] 		= 0;
            $data['total'] 		= 0;
            $data['url'] 	= base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
		//Top side selected Header menu name pass
            $data['header'] 	= array('menu_module' => 'crm');
		//Get Country Record
            $cnt_table 			= COUNTRIES . ' as cm';
            $cnt_fields 		= array("cm.country_name,cm.country_id");
            $data['country_data'] = $this->common_model->get_records($cnt_table, $cnt_fields, '', '', '', '', '', '', '', '', '', '');
		//Get Client and Recipient Selected
            $EstClient_table 	= ESTIMATE_CLIENT . ' as EstClntTbl';
            $EstClient_match 	= "EstClntTbl.estimate_id = " . $id;
            $EstClient_fields 	= array("EstClntTbl.estimate_id, EstClntTbl.prospect_id,EstClntTbl.client_name, EstClntTbl.client_type, EstClntTbl.recipient_id, EstClntTbl.recipient_type, ");
            $EstClientArray 	= $this->common_model->get_records($EstClient_table, $EstClient_fields, '', '', $EstClient_match);
            $RecipientBlnkArray = array();
            $ClientBlnkArra = "";
            $selectedCondition 		= "";
            $data['client_name'] 	= "";
            if (count($EstClientArray) > 0) {
                //Get Selected Client Id 
                $ClientBlnkArra 	= $EstClientArray[0]['client_type'] . '_' . $EstClientArray[0]['prospect_id'];
                $data['client_name'] = $EstClientArray[0]['client_name'];
                //Create Condition for make Company filter
                if ($EstClientArray[0]['client_type'] == 'company') {
                    $selectedCondition = " AND company_id = " . $EstClientArray[0]['prospect_id'];
                } else {
                    $selectedCondition = "";
                }
                foreach ($EstClientArray as $EstClntID) {
                    $RecipientBlnkArray[] = $EstClntID['recipient_type'] . '_' . $EstClntID['recipient_id'];
                }
            }
			
            $data['EstClntArray'] 		= $ClientBlnkArra;
            $data['EstRecipientArray'] 	= $RecipientBlnkArray;

        //Get Client Information for Recipients
		    $table = PROSPECT_MASTER . ' as pro';
            $match = "pro.status_type = 3 AND pro.status = 1 AND pro.is_delete = 0 " . $selectedCondition;
            $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
            $data['RecipientClientInfo'] = $this->common_model->get_records($table, $fields, '', '', $match);
		//Get Contact Information for Recipients
            $table = CONTACT_MASTER . ' as con_mst';
            $match = "con_mst.status = 1 AND con_mst.is_delete = 0 " . $selectedCondition;
            $fields = array("con_mst.contact_id, con_mst.contact_name");
            $data['RecipientContactInfo'] = $this->common_model->get_records($table, $fields, '', '', $match);
		//Get Company Information For Client Select Box
            $ComInfo_table = COMPANY_MASTER . ' as com_mst';
            $ComInfo_match = "com_mst.status = 1";
            $ComInfo_fields = array("com_mst.company_id, com_mst.company_name");
            $data['company_info'] = $this->common_model->get_records($ComInfo_table, $ComInfo_fields, '', '', $ComInfo_match);
		//Get Client Information for Client Select Box
            $ClntInfo_table = PROSPECT_MASTER . ' as pro';
            $ClntInfo_match = "pro.status_type = 3 AND pro.status = 1 AND pro.is_delete = 0";
			$ClntInfo_fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
            $data['client_info'] = $this->common_model->get_records($ClntInfo_table, $ClntInfo_fields, '', '', $ClntInfo_match);
		//Get Contact Information for Client Select Box
            $ContactInfo_table = CONTACT_MASTER . ' as con_mst';
            $ContactInfo_match = "con_mst.status = 1";
            $ContactInfo_fields = array("con_mst.contact_id, con_mst.contact_name");
            $data['contact_info'] = $this->common_model->get_records($ContactInfo_table, $ContactInfo_fields, '', '', $ContactInfo_match);
		//Get Product Information
            $table = PRODUCT_MASTER . ' as product';
            $match = "";
            $fields = array("product.product_id,product.product_name,product.product_type");
            $data['product_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $fields = array("tax.tax_id,tax.tax_percentage");
            $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "tax.is_delete = 0");
		
		//Get Terms and Condition 	
			$TermsSettingData['fields'] 	= ['EstSet.estimate_settings_id, EstSet.name, EstSet.terms, EstSet.conditions, EstSet.status'];
			$TermsSettingData['table'] 		= ESTIMATE_SETTINGS . ' as EstSet';
			$TermsSettingData['match_and'] 	= 'EstSet.status = 1 AND EstSet.is_delete = 0';
			$data['TermsConditionDataArray']= $this->common_model->get_records_array($TermsSettingData);
		/*
		 * get grouped product data
		 */
            $table = PRODUCT_GROUP_MASTER . ' as PG';
            $match = "PG.is_delete = '0' AND PG.status = '1'";

            $fields = array("PG.product_group_id,PG.product_group_name");
            $data['group_info'] = $this->common_model->get_records($table, $fields, "", "", $match);
            /*
             * mapping data of products
             */
            $mapingProducts['fields'] = ['*'];
            $mapingProducts['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id', PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=PM.product_tax_id');
            $mapingProducts['join_type'] = 'left';
            $mapingProducts['table'] = ESTIMATE_PRODUCT . ' as EP';
            $mapingProducts['match_and'] = 'EP.estimate_id=' . $id . ' AND PM.is_delete="0" AND EP.product_group_id is null';
            $data['estimate_product'] = $this->common_model->get_records_array($mapingProducts);
			
			/*
             * mapping data of grouped Products
             */
            /*$mapingGropuedid['fields'] = ['product_group_id'];
            $mapingGropuedid['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id');
            $mapingGropuedid['join_type'] = 'left';
            $mapingGropuedid['table'] = ESTIMATE_PRODUCT . ' as EP';
            $mapingGropuedid['match_and'] = 'EP.estimate_id=' . $id . ' and EP.product_group_id is not null';
            $data['estimate_grouped_product'] = $this->common_model->get_records_array($mapingGropuedid);
            $data['group_ids'] = $group_ids = array();
            if (count($data['estimate_grouped_product']) > 0) {
                foreach ($data['estimate_grouped_product'] as $groupedId) {
                    $group_ids[] = $groupedId['product_group_id'];
                }
                $data['group_ids'] = array_unique($group_ids);
            }*/
			$prdGroupArray = array();
			$prdInfoArray = array();
			$mapingGropuedid['fields'] 		= ['*','product_group_id', 'est_prd_grp_id'];
            $mapingGropuedid['table'] 		= ESTIMATE_PRODUCT_GROUP . ' as EPGID';
            $mapingGropuedid['match_and'] 	= 'EPGID.estimate_id=' . $id ;
            $prd_group_array = $this->common_model->get_records_array($mapingGropuedid);
			if(count($prd_group_array) != 0){
				foreach($prd_group_array as $prd_group_id)
				{
				//Get Estimate Product id as per Product Group Auto id
					$estPrdArry['fields'] 		= ['*','est_prd_id', 'product_qty', 'product_id', 'product_group_id', 'est_prd_grp_id'];
					$estPrdArry['table'] 		= ESTIMATE_PRODUCT . ' as ESTPRD';
					$estPrdArry['match_and'] 	= 'ESTPRD.est_prd_grp_id=' . $prd_group_id['est_prd_grp_id'];
					$estPrdIdArry 				= $this->common_model->get_records_array($estPrdArry);
					$tempEstPrdIdArry = array();
					if(count($estPrdIdArry) != 0){
						foreach($estPrdIdArry as $estPrdId)
						{
							$tempEstPrdIdArry[] = $estPrdId['product_id'];
						}
					//Get Product information as per Product id
						$prdGroupArray[] 		= $prd_group_id;
						$group_info_products 	= $this->EstimateModel->getprdListByExistingGroupId($prd_group_id['est_prd_grp_id'],$prd_group_id['product_group_id'], $tempEstPrdIdArry,$id);
						$prdInfoArray[] 		= $group_info_products;
					}
				}
			}
			$data['group_ids'] 			= $prdGroupArray; 
			$data['group_info_products']= $prdInfoArray; 
			
        //Get Estimate Template Information
            $table = ESTIMATE_TEMPLATE . ' as est_temp';
            $match = "";
            $fields = array("est_temp.est_temp_id, est_temp.est_temp_name");
            $data['estimate_temp_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
	/**
	 * Images data
	 */
		$params['fields'] = ['*'];
		$params['table'] = ESTIMATE_FILES . ' as EM';
		$params['match_and'] = 'EM.estimate_id=' . $id . '';
		$data['estimate_files'] = $this->common_model->get_records_array($params);
	/*
	 * Below code for Estimate Preview
	 */
	$PreviewClientArray = array();  //Store Estimate Preview Option
		if (count($EstClientArray) > 0) {
			$PreviewClientType = $EstClientArray[0]['client_type'];
			$PreviewClientID = $EstClientArray[0]['prospect_id'];
			if ($PreviewClientType == 'client') {
			//Get Client Information for Recipients
				$clntPreview_table = PROSPECT_MASTER . ' as pro';
				$clntPreview_match = "pro.status_type = 3 AND prospect_id = " . $PreviewClientID;
				$clntPreview_fields = array("pro.*, cnt.country_name");
				$join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=pro.country_id');
				$GetPreClientData = $this->common_model->get_records($clntPreview_table, $clntPreview_fields, $join_tables, 'left', $clntPreview_match);

				$PreviewClientArray['name'] = $GetPreClientData[0]['prospect_name'];
				$PreviewClientArray['address1'] = $GetPreClientData[0]['address1'];
				$PreviewClientArray['address2'] = $GetPreClientData[0]['address2'];
				$PreviewClientArray['city'] = $GetPreClientData[0]['city'];
				$PreviewClientArray['state'] = $GetPreClientData[0]['state'];
				$PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
				$PreviewClientArray['postal_code'] = $GetPreClientData[0]['postal_code'];
				$PreviewClientArray['image'] = ""; //Image Field not available in Prospect Master table
			} else if ($PreviewClientType == 'contact') {
			//Get Contact Information for Recipients
				$conPreview_table = CONTACT_MASTER . ' as con_mst';
				$conPreview_match = "con_mst.status = 1 AND contact_id = " . $PreviewClientID;
				$conPreview_fields = array("con_mst.*, cnt.country_name");
				$join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=con_mst.country_id');
				$GetPreClientData = $this->common_model->get_records($conPreview_table, $conPreview_fields, $join_tables, 'left', $conPreview_match);

				$PreviewClientArray['name'] = $GetPreClientData[0]['contact_name'];
				$PreviewClientArray['address1'] = $GetPreClientData[0]['address1'];
				$PreviewClientArray['address2'] = $GetPreClientData[0]['address2'];
				$PreviewClientArray['city'] = $GetPreClientData[0]['city'];
				$PreviewClientArray['state'] = $GetPreClientData[0]['state'];
				$PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
				$PreviewClientArray['postal_code'] = $GetPreClientData[0]['postal_code'];
				$PreviewClientArray['image'] = $GetPreClientData[0]['image'];
			} else if ($PreviewClientType == 'company') {
			//Get Company Information For Client Select Box
				$ComPreview_table = COMPANY_MASTER . ' as com_mst';
				$ComPreview_match = "com_mst.status = 1 AND company_id = " . $PreviewClientID;
				$ComPreview_fields = array("cnt.country_name, com_mst.company_id, com_mst.company_name as CompanyName, com_mst.address1, com_mst.address2, com_mst.city, com_mst.state, com_mst.country_id, com_mst.postal_code");
				$join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=com_mst.country_id');
				$GetPreClientData = $this->common_model->get_records($ComPreview_table, $ComPreview_fields, $join_tables, 'left', $ComPreview_match);
				$PreviewClientArray['name'] 		= $GetPreClientData[0]['CompanyName'];
				$PreviewClientArray['address1'] 	= $GetPreClientData[0]['address1'];
				$PreviewClientArray['address2'] 	= $GetPreClientData[0]['address2'];
				$PreviewClientArray['city'] 		= $GetPreClientData[0]['city'];
				$PreviewClientArray['state'] 		= $GetPreClientData[0]['state'];
				$PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
				$PreviewClientArray['postal_code'] 	= $GetPreClientData[0]['postal_code'];
				$PreviewClientArray['CompanyName'] 	= $GetPreClientData[0]['CompanyName'];
			}
		}
            $data['PreviewClientInformation'] = $PreviewClientArray; //Push Client, Contact and Company information 
            //Get All Product information 	
            $previewProducts['fields'] = ['*, EP.product_description as prdEstProductDesc'];
            $previewProducts['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id', PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=EP.product_tax');
            $previewProducts['join_type'] = 'left';
            $previewProducts['table'] = ESTIMATE_PRODUCT . ' as EP';
            $previewProducts['match_and'] = 'EP.estimate_id=' . $id;
            $previewProducts['orderby'] = 'EP.product_order';
            $previewProducts['sort'] = 'ASC';
            $data['previewAllProduct'] = $this->common_model->get_records_array($previewProducts);
        //Get Company Logo for Preview
            $previewlogo['fields'] = ['cnf.value'];
            $previewlogo['table'] = CONFIG . ' as cnf';
            $previewlogo['match_and'] = 'cnf.config_key="general_settings"';
            $data['PreBZCompanyInfo'] = $this->common_model->get_records_array($previewlogo);
			//Get Estimate created user name
            if (!empty($data['editRecord'][0]['login_id']) && isset($data['editRecord'][0]['login_id']) && $data['editRecord'][0]['login_id'] != "") {
                $previewLoginUsr['fields'] = ['lgn.login_id, lgn.firstname, lgn.lastname, lgn.email'];
                $previewLoginUsr['table'] = LOGIN . ' as lgn';
                $previewLoginUsr['match_and'] = 'lgn.login_id=' . $data['editRecord'][0]['login_id'];
                $EstimateLoginInfo = $this->common_model->get_records_array($previewLoginUsr);
                $EstLoginInfo = $EstimateLoginInfo[0]['firstname'] . ' ' . $EstimateLoginInfo[0]['lastname'];
            } else {
                $EstLoginInfo = "";
            }
            $data['PreviewLoginInfo'] = $EstLoginInfo;
		} else {
            $this->session->set_flashdata('msg', ERROR_DANGER_DIV . lang('error') . ERROR_END_DIV);
            redirect($this->module); //Redirect On Listing page   
        }
		$data['drag'] = true;	//Set for Drag and Drop functionality Complete with Mehul
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }
	/*
      @Author : Rana Ritesh
      @Desc   : view option
      @Input  : Bunch of array
      @Output : Update Product order
      @Date   : 28/03/2016
     */
    public function view($id,$estAction = NULL) {
	//Breadcrumb Code
		$this->breadcrumbs->push(lang('crm'), '/');
        $this->breadcrumbs->push(lang('estimates'), 'Estimates');
        $this->breadcrumbs->push('View Estimate', ' ');
        $data = array();

        if ($id > 0) {
            $data['ctr_view'] = $this->viewname;
            $ESTActionInfo = $this->session->userdata('ESTAction');
            if(isset($ESTActionInfo) && !empty($ESTActionInfo) && count($ESTActionInfo) != 0)
            {
                $estAction = $ESTActionInfo['EstAction'];
                $this->session->unset_userdata('ESTAction');
            } else {
                $estAction = "";
            }
            $data['estAction'] = $estAction;
            $data['incId'] = 0;
            $data['discount'] = 0;
            $data['subtotal'] = 0;
            $data['taxes'] = 0;
            $data['total'] = 0;
            $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
//Top side selected Header menu name pass
            $data['header'] = array('menu_module' => 'crm');
//Get Country Record
            $cnt_table = COUNTRIES . ' as cm';
            $cnt_fields = array("cm.country_name,cm.country_id");
            $data['country_data'] = $this->common_model->get_records($cnt_table, $cnt_fields, '', '', '', '', '', '', '', '', '', '');
//Get Client and Recipient Selected
            $EstClient_table = ESTIMATE_CLIENT . ' as EstClntTbl';
            $EstClient_match = "EstClntTbl.estimate_id = " . $id;
            $EstClient_fields = array("EstClntTbl.estimate_id, EstClntTbl.prospect_id,EstClntTbl.client_name, EstClntTbl.client_type, EstClntTbl.recipient_id, EstClntTbl.recipient_type, ");
            $EstClientArray = $this->common_model->get_records($EstClient_table, $EstClient_fields, '', '', $EstClient_match);
            $RecipientBlnkArray = array();
            $ClientBlnkArra = "";
            $selectedCondition = "";
            $data['client_name'] = "";
            if (count($EstClientArray) > 0) {
                //Get Selected Client Id
                $ClientBlnkArra = $EstClientArray[0]['client_type'] . '_' . $EstClientArray[0]['prospect_id'];
                $data['client_name'] = $EstClientArray[0]['client_name'];
                //Create Condition for make Company filter
                if ($EstClientArray[0]['client_type'] == 'company') {
                    $selectedCondition = " AND company_id = " . $EstClientArray[0]['prospect_id'];
                } else {
                    $selectedCondition = "";
                }
                foreach ($EstClientArray as $EstClntID) {
                    $RecipientBlnkArray[] = $EstClntID['recipient_type'] . '_' . $EstClntID['recipient_id'];
                }
            }
            $data['EstClntArray'] = $ClientBlnkArra;
            $data['EstRecipientArray'] = $RecipientBlnkArray;
			
            //Get Client Information for Recipients
            $table = PROSPECT_MASTER . ' as pro';
            $match = "pro.status_type = 3" . $selectedCondition;
            $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
            $data['RecipientClientInfo'] = $this->common_model->get_records($table, $fields, '', '', $match);
            //Get Contact Information for Recipients
            $table = CONTACT_MASTER . ' as con_mst';
            $match = "con_mst.status = 1" . $selectedCondition;
            $fields = array("con_mst.contact_id, con_mst.contact_name");
            $data['RecipientContactInfo'] = $this->common_model->get_records($table, $fields, '', '', $match);

            //Get Company Information For Client Select Box
            $ComInfo_table = COMPANY_MASTER . ' as com_mst';
            $ComInfo_match = "com_mst.status = 1";
            $ComInfo_fields = array("com_mst.company_id, com_mst.company_name");
            $data['company_info'] = $this->common_model->get_records($ComInfo_table, $ComInfo_fields, '', '', $ComInfo_match);
            //Get Client Information for Client Select Box
            $ClntInfo_table = PROSPECT_MASTER . ' as pro';
            $ClntInfo_match = "pro.status_type = 3";
            $ClntInfo_fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
            $data['client_info'] = $this->common_model->get_records($ClntInfo_table, $ClntInfo_fields, '', '', $ClntInfo_match);
            //Get Contact Information for Client Select Box
            $ContactInfo_table = CONTACT_MASTER . ' as con_mst';
            $ContactInfo_match = "con_mst.status = 1";
            $ContactInfo_fields = array("con_mst.contact_id, con_mst.contact_name");
            $data['contact_info'] = $this->common_model->get_records($ContactInfo_table, $ContactInfo_fields, '', '', $ContactInfo_match);
//Get Product Information
            $table = PRODUCT_MASTER . ' as product';
            $match = "";
            $fields = array("product.product_id,product.product_name,product.product_type");
            $data['product_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $fields = array("tax.tax_id,tax.tax_percentage");
            $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "");
            /*
             * get grouped product data
             */
            $table = PRODUCT_GROUP_MASTER . ' as PG';
            $match = "";

            $fields = array("PG.product_group_id,PG.product_group_name");
            $data['group_info'] = $this->common_model->get_records($table, $fields, "", "", $match);
            /*
             * mapping data of products
             */
            $mapingProducts['fields'] = ['*'];
            $mapingProducts['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id', PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=EP.product_tax');
            $mapingProducts['join_type'] = 'left';
            $mapingProducts['table'] = ESTIMATE_PRODUCT . ' as EP';
            $mapingProducts['match_and'] = 'EP.estimate_id=' . $id . ' and EP.product_group_id is null';
            $data['estimate_product'] = $this->common_model->get_records_array($mapingProducts);
            /*
             * mapping data of grouped Products
             */
            $mapingGropuedid['fields'] = ['product_group_id'];
            $mapingGropuedid['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id');
            $mapingGropuedid['join_type'] = 'left';
            $mapingGropuedid['table'] = ESTIMATE_PRODUCT . ' as EP';
            $mapingGropuedid['match_and'] = 'EP.estimate_id=' . $id . ' and EP.product_group_id is not null';
            $data['estimate_grouped_product'] = $this->common_model->get_records_array($mapingGropuedid);
            $data['group_ids'] = $group_ids = array();
            if (count($data['estimate_grouped_product']) > 0) {
                foreach ($data['estimate_grouped_product'] as $groupedId) {
                    $group_ids[] = $groupedId['product_group_id'];
                }
                $data['group_ids'] = array_unique($group_ids);
            }
            $table = ESTIMATE_TEMPLATE . ' as est_temp';
            $match = "";
            $fields = array("est_temp.est_temp_id, est_temp.est_temp_name");
            $data['estimate_temp_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
            /**
             * Images data
             */
            $params['fields'] = ['*'];
            $params['table'] = ESTIMATE_FILES . ' as EM';
            $params['match_and'] = 'EM.estimate_id=' . $id . '';
            $data['estimate_files'] = $this->common_model->get_records_array($params);
            /*
             * View page Estimate Records
             */
            $data['main_content'] = '/view';
            $table = ESTIMATE_MASTER . ' as ESTMster';
            $match = "ESTMster.status != '3' && ESTMster.estimate_id = " . $id;
            $data['editRecord'] = $this->common_model->get_records($table, '', '', '', $match);
            //Page redirect on Estimate listing page if records are delete from estimate
            if(empty($data['editRecord']) && count($data['editRecord']) == 0)
            {
                $this->session->set_flashdata('msg', ERROR_DANGER_DIV . lang('error') . ERROR_END_DIV);
                redirect($this->viewname); //Redirect On Listing page
                exit;
            }
            /*
             * Below code for Estimate Preview
             */
            $PreviewClientArray = array();  //Store Estimate Preview Option
            if (count($EstClientArray) > 0) {
                $PreviewClientType = $EstClientArray[0]['client_type'];
                $PreviewClientID = $EstClientArray[0]['prospect_id'];
                if ($PreviewClientType == 'client') {
                    //Get Client Information for Recipients
                    $clntPreview_table = PROSPECT_MASTER . ' as pro';
                    $clntPreview_match = "pro.status_type = 3 AND prospect_id = " . $PreviewClientID;
                    $clntPreview_fields = array("pro.*, cnt.country_name");
                    $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=pro.country_id');
                    $GetPreClientData = $this->common_model->get_records($clntPreview_table, $clntPreview_fields, $join_tables, 'left', $clntPreview_match);

                    $PreviewClientArray['name'] 		= $GetPreClientData[0]['prospect_name'];
                    $PreviewClientArray['address1'] 	= $GetPreClientData[0]['address1'];
                    $PreviewClientArray['address2'] 	= $GetPreClientData[0]['address2'];
                    $PreviewClientArray['city'] 		= $GetPreClientData[0]['city'];
                    $PreviewClientArray['state'] 		= $GetPreClientData[0]['state'];
                    $PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
                    $PreviewClientArray['postal_code'] 	= $GetPreClientData[0]['postal_code'];
                    $PreviewClientArray['image'] 		= ""; //Image Field not available in Prospect Master table
                } else if ($PreviewClientType == 'contact') {
                    //Get Contact Information for Recipients
                    $conPreview_table = CONTACT_MASTER . ' as con_mst';
                    $conPreview_match = "con_mst.status = 1 AND contact_id = " . $PreviewClientID;
                    $conPreview_fields = array("con_mst.*, cnt.country_name");
                    $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=con_mst.country_id');
                    $GetPreClientData = $this->common_model->get_records($conPreview_table, $conPreview_fields, $join_tables, 'left', $conPreview_match);

                    $PreviewClientArray['name'] 		= $GetPreClientData[0]['contact_name'];
                    $PreviewClientArray['address1'] 	= $GetPreClientData[0]['address1'];
                    $PreviewClientArray['address2'] 	= $GetPreClientData[0]['address2'];
                    $PreviewClientArray['city'] 		= $GetPreClientData[0]['city'];
                    $PreviewClientArray['state'] 		= $GetPreClientData[0]['state'];
                    $PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
                    $PreviewClientArray['postal_code'] 	= $GetPreClientData[0]['postal_code'];
                    $PreviewClientArray['image'] 		= $GetPreClientData[0]['image'];
                } else if ($PreviewClientType == 'company') {
                //Get Company Information For Client Select Box
					$ComPreview_table = COMPANY_MASTER . ' as com_mst';
					$ComPreview_match = "com_mst.status = 1 AND company_id = " . $PreviewClientID;
					$ComPreview_fields = array("cnt.country_name, com_mst.company_id, com_mst.company_name as CompanyName, com_mst.address1, com_mst.address2, com_mst.city, com_mst.state, com_mst.country_id, com_mst.postal_code");
					$join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=com_mst.country_id');
					$GetPreClientData = $this->common_model->get_records($ComPreview_table, $ComPreview_fields, $join_tables, 'left', $ComPreview_match);
					$PreviewClientArray['name'] 		= $GetPreClientData[0]['CompanyName'];
					$PreviewClientArray['address1'] 	= $GetPreClientData[0]['address1'];
					$PreviewClientArray['address2'] 	= $GetPreClientData[0]['address2'];
					$PreviewClientArray['city'] 		= $GetPreClientData[0]['city'];
					$PreviewClientArray['state'] 		= $GetPreClientData[0]['state'];
					$PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
					$PreviewClientArray['postal_code'] 	= $GetPreClientData[0]['postal_code'];
					$PreviewClientArray['CompanyName'] 	= $GetPreClientData[0]['CompanyName'];
					$PreviewClientArray['image'] 		= "";
				}
            }
            $data['PreviewClientInformation'] = $PreviewClientArray; //Push Client, Contact and Company information
            //Get All Product information
            $previewProducts['fields'] = ['*'];
            $previewProducts['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id', PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=EP.product_tax');
            $previewProducts['join_type'] = 'left';
            $previewProducts['table'] = ESTIMATE_PRODUCT . ' as EP';
            $previewProducts['match_and'] = 'EP.estimate_id=' . $id;
            $previewProducts['orderby'] = 'EP.product_order';
            $previewProducts['sort'] = 'ASC';
            $data['previewAllProduct'] = $this->common_model->get_records_array($previewProducts);
            //Get Company Logo for Preview
            $previewlogo['fields'] = ['cnf.value'];
            $previewlogo['table'] = CONFIG . ' as cnf';
            $previewlogo['match_and'] = 'cnf.config_key="general_settings"';
            $data['PreBZCompanyInfo'] = $this->common_model->get_records_array($previewlogo);
            //Get Estimate created user name
            if (!empty($data['editRecord'][0]['login_id']) && isset($data['editRecord'][0]['login_id']) && $data['editRecord'][0]['login_id'] != "") {
                $previewLoginUsr['fields'] = ['lgn.login_id, lgn.firstname, lgn.lastname, lgn.email'];
                $previewLoginUsr['table'] = LOGIN . ' as lgn';
                $previewLoginUsr['match_and'] = 'lgn.login_id=' . $data['editRecord'][0]['login_id'];
                $EstimateLoginInfo = $this->common_model->get_records_array($previewLoginUsr);
                $EstLoginInfo = $EstimateLoginInfo[0]['firstname'] . ' ' . $EstimateLoginInfo[0]['lastname'];
            } else {
                $EstLoginInfo = "";
            }
            $data['PreviewLoginInfo'] = $EstLoginInfo;
        } else {
            $this->session->set_flashdata('msg', ERROR_DANGER_DIV . lang('error') . ERROR_END_DIV);

            redirect($this->module); //Redirect On Listing page
        }
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }


    /*
      @Author : RJ (Rupesh Jorkar)
      @Desc   : Update Product Order during Product preview option
      @Input  : Bunch of array
      @Output : Update Product order
      @Date   : 07/03/2016
     */
    public function ProductSortOrderUpdate() {
        $productOrderArray = $this->input->post('productOrderArray');
        $orderFlag = 1;
        if (!empty($productOrderArray) && count($productOrderArray) > 0) {
            foreach ($productOrderArray as $productOrder) {
                $est_prdOrderdata['product_order'] = $orderFlag;
                $where = array('est_prd_id ' => $productOrder);
                $success_update = $this->common_model->update(ESTIMATE_PRODUCT, $est_prdOrderdata, $where);
                $orderFlag++;
            }
        }
        //Update query 
        /* $data = mysql_real_escape_string($data);
          $where = array('estimate_id' => $id);
          $success_update = $this->common_model->update(ESTIMATE_MASTER, $estimate_data, $where); */
    }
    /*
      @Author : RJ (Rupesh Jorkar)
      @Desc   : Show all Client as per selected Company
      @Input  :
      @Output :
      @Date   : 02/03/2016
     */
	//3658 revision id have show all client and contact point
    public function ShowClientRelatedToCompany() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        }
		$company_id = $this->input->post('company_id');
        $selectedVal = $this->input->post('selectedinfo');
        if ($selectedVal == 'company') {
            $selectedCondition = " AND company_id = " . $company_id;
            $data['ShowData'] = 'RelatedToCompany';
        } elseif ($selectedVal == 'client') {
            $data['client_data']=$company_id;
            $selectedCondition = "";
            $data['ShowData'] = 'all';
        }else{
            $data['contact_data']=$company_id;
            $selectedCondition = "";
            $data['ShowData'] = 'all';
        }
    //blzdsk_prospect_master Client = company_id - status_type-3
    //Get Client Information as per Company ID
        $client_table = PROSPECT_MASTER . ' as pro';
        $client_match = "pro.status_type = 3 AND pro.status = 1 AND pro.is_delete = 0" . $selectedCondition;
        $client_fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($client_table, $client_fields, '', '', $client_match);
	//Get Contact Information
        $contact_table = CONTACT_MASTER . ' as con_mst';
        $contact_match = "con_mst.status = 1 AND con_mst.is_delete = 0 " . $selectedCondition;
        $contact_fields = array("con_mst.contact_id, con_mst.contact_name");
        $data['contact_info'] = $this->common_model->get_records($contact_table, $contact_fields, '', '', $contact_match);
		$this->load->view('estimateShowClient', $data);
    }
    /*
      @Author : Seema Tankariya
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 13/01/2016
     */
    public function deletedata($id) {
//Delete Record From Database
        if (!empty($id)) {
            $where = array('estimate_id' => $id);
            $estimatedata['status']= 3;
            $delete_suceess = $this->common_model->update(ESTIMATE_MASTER, $estimatedata, $where);
            /*$this->common_model->delete(ESTIMATE_PRODUCT, $where);
            $this->common_model->delete(ESTIMATE_FILES, $where);
            */
            if ($delete_suceess) {
			    $msg = lang('estimate_del_msg');
			  
            //$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
			$this->session->set_flashdata('msg', ERROR_SUCCESS_DIV . $msg . ERROR_END_DIV);
            }
			unset($id);
        }
		$redirectLink = $_SERVER['HTTP_REFERER'];
		if (strpos($redirectLink, 'Account/viewdata') !== false) {
			$sess_array = array('setting_current_tab' => 'Estimates');
            $this->session->set_userdata($sess_array);
			redirect($redirectLink);
		}
		elseif (strpos($redirectLink, 'CrmCompany/view') !== false) {
			$sess_array = array('setting_current_tab' => 'Estimates');
            $this->session->set_userdata($sess_array);
			redirect($redirectLink);
		}
		else{
			redirect($this->viewname); //Redirect On Listing page
		}
        
    }
    public function getProductBox($id) {
        $data['incId'] = $id;
        $fields = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "tax.is_delete = 0");
		if ($this->input->get('type') == 'new') {
            $this->load->view('files/estimateNewProductSection', $data);
        } else if ($this->input->get('type') == "group") {
            $table = PRODUCT_GROUP_MASTER . ' as PG';
            $match = "PG.is_delete = '0' AND PG.status = '1'";
			$fields = array("PG.product_group_id,PG.product_group_name");
            $data['group_info'] = $this->common_model->get_records($table, $fields, "", "", $match);
			$this->load->view('files/estimateProductGroupSection', $data);
        } else {
            $table = PRODUCT_MASTER . ' as product';
            $match = "status = '1' AND is_delete = '0'";
            $fields = array("product.product_id,product.product_name,product.product_type");
            $data['product_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
			$this->load->view('files/estimateProductSection', $data);
        }
    }
    function getProductById($id) {
		$curSymboleID = $this->input->get('curSymbol');
		$table = PRODUCT_MASTER . ' as product';
        $data['incId'] = $id;
        $match = "product.product_id=" . $id;
		$join_tables = array(PRODUCT_TAX_MASTER . ' as ptm' => 'product.product_tax_id = ptm.tax_id');
		$join_type = 'left';
	    $fields = array("product.is_delete,ptm.tax_id,ptm.tax_name,ptm.tax_percentage,product.currency_symbol,product.product_tax_id,product.product_id,product.product_name,product.product_description,sales_price_unit");
        $data['product_info'] = $this->common_model->get_records($table, $fields, $join_tables, $join_type, $match)[0];
		$oldPrdCurrencySymbol = $data['product_info']['currency_symbol'];
		$prdAmt = $data['product_info']['sales_price_unit'];
		if($oldPrdCurrencySymbol != $curSymboleID)
		{
			$oldCurCode = getCourrencyCode($oldPrdCurrencySymbol);
			$newCurCode = getCourrencyCode($curSymboleID);
			/*echo "<br>";echo "Old amount - ". $singleProductAmtSales.' - ';*/
			$prdAmt = helperConvertCurrency($prdAmt, $oldCurCode[0]['currency_code'], $newCurCode[0]['currency_code']);
		}
		echo json_encode(array('data' => $data['product_info'], 'calculatedPrice' => amtRound($prdAmt)));
    }
    function productCalculationTaxesQty() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        }
		$prdAmt 		= $this->input->post('amount');
		$discntOpt 		= $this->input->post('discountOption');
		$prdQtyAmt 		= ($this->input->post('qty') * $prdAmt);
	//Start Discount Calculation
		if($discntOpt == 'prsnt')
		{
			$disAmt = ($prdQtyAmt * $this->input->post('discount')) / 100 ;
		}
		else 
		{
			$disAmt = $this->input->post('discount');
		}
		$total = $prdQtyAmt - $disAmt;
        $table = PRODUCT_TAX_MASTER . ' as PTM';
        $match = "tax_id=" . $this->input->post('tax');
        $tax = 0;
        $taxData = $this->common_model->get_records($table, '', '', '', $match);
        if (count($taxData) > 0):
            $tax = $taxData[0]['tax_percentage'];
        endif;
        
        $taxAmt = (($total * $tax) / 100);
        echo json_encode(array('total' => $total + $taxAmt, 'tax' => $taxAmt));
        die;
    }
    function productCalculationSubTotal() {
		if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        }
		$dftSymbole = getDefaultCurrencyInfo();
		$data['discount'] 		= 0;
        $data['subtotal'] 		= 0;
        $data['taxes'] 			= 0;
        $data['total'] 			= 0;
        $data['discount_earned']= 0;
        $data['discountOption'] = 'prsnt';
        $data['cntSymbol'] 		= $dftSymbole['country_id'];
		$data['allTaxInArry']	= array();
		
		if($this->input->post('allTaxInArray'))
		{
			$data['allTaxInArry'] = $_REQUEST['allTaxInArray'];		//RJ Need to correct
		}
		$totalAmtArray = array();
		$singleDiscount = array();
		$singletax = array();
		$subTotal = array();
	//Get Discount Option
		if($this->input->post('discountOption')) {
			$data['discountOption'] = $this->input->post('discountOption');
		}
	//Get Country Symbol ID
		if($this->input->post('cntSymbol')) {
			$data['cntSymbol'] = $this->input->post('cntSymbol');
		}
	//Get Discount as per Single Amount
		if($this->input->post('discount')) {
			$data['discount'] = $this->input->post('discount');
		}
	//Calculate Discount as per Single Amount
		if (is_array($this->input->post('singleAmt'))) {
			$prdSngDiscountArray	= $this->input->post('prdSngDiscount');
			$prdsngDiscountOptArray	= $this->input->post('prdsngDiscountOpt');
			$singleAmtArray 	= $this->input->post('singleAmt');
			$singleTaxArray 	= $this->input->post('tax');
			$singleTaxIdArray 	= $this->input->post('tax_id');
			$singleQtyArray 	= $this->input->post('singleQuantity');
			$amtflag 			= 0;
			
			foreach($singleAmtArray as $singleAmtVal){
				$singleDiscountAmt 	= 0;
				$singletaxAmt 		= 0;
				$singletaxVal 		= 0;
			//Main Price and Quantity Multiplication
				$singleAmtInfo 		= $singleAmtVal * $singleQtyArray[$amtflag];
			//Get Tax amount from Database 
				$match = "txtm.tax_id = ".$singleTaxIdArray[$amtflag];
				$taxValue = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as txtm', array("txtm.tax_id,txtm.tax_percentage"), '', '', $match);
				if(isset($taxValue) && count($taxValue) != 0):
					$singletaxVal = $taxValue[0]['tax_percentage'];
				endif;
			//Calculate Discount amount as per Single Amount
				if(isset($prdSngDiscountArray[$amtflag]) && $prdSngDiscountArray[$amtflag] != "" && $prdSngDiscountArray[$amtflag] != 0)
				{
					if($prdsngDiscountOptArray[$amtflag] == 'prsnt')
					{
						$singleDiscountAmt = ($singleAmtInfo * amtRound($prdSngDiscountArray[$amtflag])) / 100;
					}
					if($prdsngDiscountOptArray[$amtflag] == 'amt')
					{
						$singleDiscountAmt = $prdSngDiscountArray[$amtflag];
					}
				}
				/* Remove Following code at the end
				if(isset($data['discount']) && $data['discount'] != "" && $data['discount'] != 0)
				{
					if($data['discountOption'] == 'prsnt')
					{
						$singleDiscountAmt = ($singleAmtInfo * amtRound($data['discount'])) / 100;
					}
				}*/
			//Deduct Discount From Single Amount 
				$singleDiscountDeduct = $singleAmtInfo - $singleDiscountAmt;
			//Delete Discount From main price and then set Sub Total Amount
				$subTotal[] = $singleDiscountDeduct;
			//Deduct Tax after calculate discount from single amount
				$singletaxAmt = ($singleDiscountDeduct * $singletaxVal) / 100;
			//Push final amount in array 
				$totalAmtArray[] = $singleDiscountDeduct + $singletaxAmt;
			//Single discount place in array for show
				$singleDiscount[] = $singleDiscountAmt;
			//Single tax place in array for show 
				$singletax[] = $singletaxAmt;
				$amtflag++;
			}
		}
	//pr($singletax);pr($singleDiscount);pr($totalAmtArray);
		/* Remove Following code at the end
		if($data['discountOption'] == 'amt')
		{
			$singleDiscountAmt = $data['discount'];
			$afterDiscountOption = array_sum($totalAmtArray) - $singleDiscountAmt;
		} else {
			$afterDiscountOption = array_sum($totalAmtArray);
		}*/
		
		$afterDiscountOption = array_sum($totalAmtArray);
		
		$data['total']			= $afterDiscountOption;
		$data['discount_earned']= array_sum($singleDiscount);
		$data['taxes']			= array_sum($singletax);
		$data['subtotal']		= array_sum($subTotal);
	//Currency data fetch
	    $table = COUNTRIES . ' as cnt';
        $match = "cnt.use_status = '1' AND is_delete_currency = 0";
        $fields = array("cnt.country_id,cnt.country_name, cnt.currency_name, cnt.currency_code, cnt.currency_symbol, cnt.use_status");
		
        $data['country_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        echo $this->load->view('files/estimateSubtotalSection', $data);
    }
    function getProductsListByGroupId($id) {
        $data['incId'] = $this->input->get('incVar');
        $fields = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "tax.is_delete = 0");
        $table = PRODUCT_GROUP_MASTER . ' as PG';
        $match = "PG.product_group_id=" . $id .' AND PM.status="1" AND PM.is_delete="0" AND PGR.is_delete="0"';
        $join_tables = array(PRODUCT_GROUP_RELATION . ' as PGR' => 'PGR.product_group_id=PG.product_group_id', PRODUCT_MASTER . ' as PM' => 'PM.product_id=PGR.product_id', PRODUCT_TAX_MASTER . ' as ptm' => 'PGR.product_tax_id = ptm.tax_id');
        $join_type = 'left';
        $fields = array("ptm.is_delete,ptm.tax_id,ptm.tax_name,ptm.tax_percentage, PG.product_group_id, PM.product_id,PM.product_name,PM.product_description,PM.sales_price_unit,PGR.product_group_status,PGR.*");
        $data['group_info_products'] = $this->common_model->get_records($table, $fields, $join_tables, $join_type, $match);
		
		$this->load->view('files/estimateGroupProductsBox', $data);
    }
	function getGroupDataByGroupId($id) {
		$table = PRODUCT_GROUP_MASTER . ' as PG';
        $match = "PG.product_group_id=" . $id ;
        $fields = array("PG.*");
        $data['groupArray'] = $this->common_model->get_records($table, $fields, '', '', $match);
		echo json_encode(array('data' => $data['groupArray'][0]));
		
	}
    function appendTextParagraph($incId) {
        $data['incId'] = $incId;
        $this->load->view('appendTextParagraph', $data);
    }
    /*
    @Author : Ritesh Rana
    @Desc   : Delete image
    @Input 	:
    @Output	:
    @Date   : 11/03/2016
   */
    public function deleteImage($id) {
        //Delete Record From Database
        if (!empty($id)) {
            $match = array("file_id"=>$id);
            $fields = array("file_name");
            $image_name     = $this->common_model->get_records(ESTIMATE_FILES,$fields,'','',$match);

            if(file_exists($this->config->item('estimate_img_url').$image_name[0]['file_name']))
            {

                unlink($this->config->item('estimate_img_url').$image_name[0]['file_name']);
            }
            $where = array('file_id' => $id);
            if ($this->common_model->delete(ESTIMATE_FILES, $where)) {
                echo json_encode(array('status' => 1, 'error' => ''));
                die;
            } else {
                echo json_encode(array('status' => 0, 'error' => 'Someting went wrong!'));
                die;
            }

            unset($id);
        }
    }


    /*
      @Author : Maulik Suthar
      @Desc   : download attachment function
      @Input 	:
      @Output	:
      @Date   : 18/02/2016
     */

    function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = ESTIMATE_FILES . ' as CM';
            $params['match_and'] = 'CM.file_id=' . $id . '';
            $cost_files = $this->common_model->get_records_array($params);
            if (count($cost_files) > 0) {
                $pth = file_get_contents(base_url($cost_files[0]['file_path'] . '/' . $cost_files[0]['file_name']));
                $this->load->helper('download');
                force_download($cost_files[0]['file_name'], $pth);
            }
            redirect($this->module);
        }
    }




    /*
      @Author 	: RJ(Rupesh Jorkar)
      @Desc   	: Store Widget(Drag and Drop Sequence) In Database.
      @Input 	:
      @Output	: Store Sequence in Database.
      @Date   	: 10/03/2016
     */
    function StoreWidgets() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        } else {
            $order = $this->input->post('order');
            $action = $this->input->post('action');
            $estimate_id = $this->input->post('estimate_id');
			
            if (isset($action) && $action == "content") {
                $data['est_content_widgets'] = json_encode($order);
                $where = array('estimate_id' => $estimate_id);
                $update = $this->common_model->update(ESTIMATE_MASTER, $data, $where);
			}
            if (isset($action) && $action == "header") {
                $data['est_header_widget'] = json_encode($order);
                $where = array('estimate_id' => $estimate_id);
                $update = $this->common_model->update(ESTIMATE_MASTER, $data, $where);
			}
        }
    }
	/*
      @Author 	: RJ(Rupesh Jorkar)
      @Desc   	: Send Estimate to Recipient.
      @Input 	:
      @Output	: 
      @Date   	: 12/03/2016
     */
	function SendEstimate($id)
	{
		$chngEmlTmp 		= '';
		$newEmailSubject 	= ''; 
		$newEmailTemplateBody = ''; 
		if ($this->input->post('chngEmlTmp')) {
            $chngEmlTmp = $this->input->post('chngEmlTmp');
        }
		if(isset($chngEmlTmp) && $chngEmlTmp != "")
		{
			if ($this->input->post('newEmailSubject')) {
				$newEmailSubject = $this->input->post('newEmailSubject');
			}
			if ($this->input->post('newEmailTemplateBody')) {
				$newEmailTemplateBody = $this->input->post('newEmailTemplateBody');
			}
		}
		
	//Get Estimate main records
        $table = ESTIMATE_MASTER . ' as em';
        $match = "em.estimate_id = " . $id;
        $EstInfo = $this->common_model->get_records($table, '', '', '', $match);

    //Get Client and Recipient Selected
        $EstClient_table = ESTIMATE_CLIENT . ' as EstClntTbl';
        $EstClient_match = "EstClntTbl.estimate_id = " . $id;
        $EstClient_fields = array("EstClntTbl.est_client_id, EstClntTbl.estimate_id, EstClntTbl.prospect_id, EstClntTbl.client_type, EstClntTbl.recipient_id, EstClntTbl.recipient_type, ");
        $EstRecipientArray = $this->common_model->get_records($EstClient_table, $EstClient_fields, '', '', $EstClient_match);
		$EstRecipientInfo = array();
		$InstallAction = "";
		//echo $this->router->class;
		//echo $this->router->fetch_method();
		if(count($EstRecipientArray) != 0)
		{
			$mailFlag = 0;
			//Get HTML from GeneratePrintPDF function
				$PDFHtml = $this->DownloadPDF($id,'StorePDF');
			foreach($EstRecipientArray as $EstRecipientID)
			{ $mailFlag ++;
			//Set Yes Flag for Create PDF in "uploads\estimate" folder
				/*if($mailFlag == 1)
				{
					//Get HTML from GeneratePrintPDF function
						$PDFHtml = $this->DownloadPDF($id,'StorePDF');
				}*/
			//Compare Id with old Contact
				$EstComtable = ESTIMATE_SEND_EST . ' as EstSnd';
				$EstCommatch = "EstSnd.recipient_id = " . $EstRecipientID['recipient_id'];
				$EstComfields = array("EstSnd.est_send_id, EstSnd.recipient_id");
				$ExistingSendId = $this->common_model->get_records($EstComtable, $EstComfields, '', '', $EstCommatch);
			//Set 1 in $InstallAction variable if its not added in database
				if(count($ExistingSendId) == 0)
				{
					$InstallAction = 1;
				}
				if($EstRecipientID['recipient_type'] == 'client')
				{
				//Get Client Information for Recipients
					$RecipientClntArray['fields'] 		= ['cm.email, cm.contact_id, cm.contact_name'];
					$RecipientClntArray['join_tables'] 	= array(CONTACT_MASTER . ' as cm' => 'cm.contact_id=orp.contact_id');
					$RecipientClntArray['join_type'] 	= 'left';
					$RecipientClntArray['table'] 		= OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orp';
					$RecipientClntArray['match_and'] 	= 'orp.prospect_id=' . $EstRecipientID['recipient_id'];
					$RecipientCntArray 					= $this->common_model->get_records_array($RecipientClntArray);
					
					if(!empty($RecipientCntArray) && count($RecipientCntArray) != 0)
					{
						foreach($RecipientCntArray as $RecipientInfo)
						{
							$EstRecipientInfo[] = $RecipientInfo['email'];
							if(isset($RecipientInfo['email']) && $RecipientInfo['email'] != "")
							{
								$this->sendMailToRecipient($RecipientInfo['email'], $EstRecipientID['est_client_id'], $EstInfo, $id, $chngEmlTmp, $newEmailSubject, $newEmailTemplateBody, $RecipientInfo['contact_name']);
							}
						}
					}
				}
				if ($EstRecipientID['recipient_type'] == 'contact') {
				//Get Contact Information for Recipients
					$table = CONTACT_MASTER . ' as con_mst';
					$match = "con_mst.status = 1 AND contact_id = " . $EstRecipientID['recipient_id'];
					$fields = array("con_mst.contact_id, con_mst.contact_name, con_mst.email");
					$RecipientCntInfo = $this->common_model->get_records($table, $fields, '', '', $match);
					if(count($RecipientCntInfo) != 0 && !empty($RecipientCntInfo))
					{
						$EstRecipientInfo[] = $RecipientCntInfo[0]['email'];
						if(isset($RecipientCntInfo[0]['email']) && $RecipientCntInfo[0]['email'] != "")
						{
							$this->sendMailToRecipient($RecipientCntInfo[0]['email'], $EstRecipientID['est_client_id'], $EstInfo, $id, $chngEmlTmp, $newEmailSubject, $newEmailTemplateBody, $RecipientCntInfo[0]['contact_name']);
						}
					}
				}
			//Add data in ESTIMATE_SEND_EST table if that not added 
				if(isset($InstallAction) && $InstallAction != "") {
					//Insert Data in Send Estimate Table
						$estSendInfo['estimate_id'] = $id;
						$estSendInfo['recipient_id'] = $EstRecipientID['recipient_id'];
						$estSendInfo['recipient_type'] = $EstRecipientID['recipient_type'];
						$estSendInfo['created_date'] = datetimeformat();
						$LastId = $this->common_model->insert(ESTIMATE_SEND_EST, $estSendInfo);
						$InstallAction = "";
				}
			//Set Yes Flag for Delete PDF from "uploads\estimate" folder
				if($mailFlag == count($EstRecipientArray))
				{
					$pdfEstFileName = "Estimates".$EstInfo[0]['estimate_auto_id'].".pdf";
					$pdfEstPath = FCPATH.'uploads/estimate/';
					if(file_exists($pdfEstPath.$pdfEstFileName)){
						unlink($pdfEstPath.$pdfEstFileName);
					}
				}
			}
		} 
		//$this->load->view('files/estSendEstimation', $data, true);
	}
	function sendMailToRecipient($recipientEmail,$est_client_id, $EstInfo, $id, $chngEmlTmp, $newEmailSubject, $newEmailTemplateBody, $contact_name){
		//echo $chngEmlTmp.' - '.$newEmailSubject.' - '.$newEmailTemplateBody;
		if(isset($recipientEmail) && $recipientEmail != "")
		{
			if($EstInfo[0]['client_can_accept_online'] == 1)
			{
			// Get Template from Template Master
				$table    = EMAIL_TEMPLATE_MASTER . ' as et';
				$match 	  = "et.template_id = 38 ";
				$fields   = array("et.subject,et.body");
				$template = $this->common_model->get_records($table,$fields,'','',$match);
				$liveLink = base_url().'EstimatesClient/ClientView/'.$id.'/'.$est_client_id;
				
				//$to = implode(',', $EstRecipientInfo);
				$to = $recipientEmail;
				if( isset($chngEmlTmp) && $chngEmlTmp != "" && $chngEmlTmp == 'takeEmailContent' )
				{
					$bodyLiveLink 	= str_replace("{LIVE_LINK}",$liveLink,$newEmailTemplateBody);
					$bodyReplace	= str_replace("{USER}",$contact_name,$bodyLiveLink);
					
					$subjectReplace	= "BLAZEDESK :: ".$newEmailSubject;
				} else {
					$bodyLiveLink = str_replace("{LIVE_LINK}",$liveLink,$template[0]['body']);
					$bodyReplace = str_replace("{USER}", $contact_name, $bodyLiveLink);
					$subjectReplace	= "BLAZEDESK :: ".$template[0]['subject'];
				}
				$body = str_replace("{SITE_NAME}",base_url(),$bodyReplace);
				$subject = $subjectReplace;

				if (send_mail($to,$subject,$body))
				{
					$msg = ERROR_SUCCESS_DIV. "Send Estimate To Recipient Successfully." . ERROR_END_DIV;
				} else {
				// error
					$msg = ERROR_DANGER_DIV . 'Error during Send Estimate Please try again.' . ERROR_END_DIV;
				}
				//return $msg;
				//echo $msg;
				//exit;
			} else {
				$pdfEstFileName = "Estimates".$EstInfo[0]['estimate_auto_id'].".pdf";
				$pdfEstPath = FCPATH.'uploads/estimate/';
				
			// Get Email configs from Email settings page
				$configs 		= getMailConfig(); 
			// Get Template from Template Master
				$ETMPDFtable 	= EMAIL_TEMPLATE_MASTER . ' as et';
				$ETMPDFmatch 	= "et.template_id = 39 ";
				$ETMPDFfields 	= array("et.subject,et.body");
				$ETMPDFInfo 	= $this->common_model->get_records($ETMPDFtable,$ETMPDFfields,'','',$ETMPDFmatch);
				
				$content 		= chunk_split(base64_encode($content));
				$mailto 		= $recipientEmail;
				//$mailto 		= 'blazedeskcrm@gmail.com';
				if( isset($chngEmlTmp) && $chngEmlTmp != "" && $chngEmlTmp == 'takeEmailContent' )
				{
					$subject	= "BLAZEDESK :: ".$newEmailSubject;
					$message	= str_replace("{USER}",$contact_name,$newEmailTemplateBody);
				} else {
					$subject 	= $ETMPDFInfo[0]['subject'];
					$message	= str_replace("{USER}",$contact_name,$ETMPDFInfo[0]['body']);
				}
				
				if(send_mail($mailto, $subject, $message, $pdfEstPath.$pdfEstFileName))
				{
					/*if(file_exists($pdfEstPath.$pdfEstFileName)){
						//unlink($pdfEstPath.$pdfEstFileName);
					}*/
					$msg = ERROR_SUCCESS_DIV . 'Send Estimate To Recipient Successfully.' . ERROR_END_DIV;
				} else {
				//Remove Created PDF from DownloadPDF function
					/*if(file_exists($pdfEstPath.$pdfEstFileName)){
						//unlink($pdfEstPath.$pdfEstFileName);
					}*/
					$msg = ERROR_DANGER_DIV . lang('error') . ERROR_END_DIV;
				}
				echo  $msg;
			}
		} else {
			//$this->session->set_flashdata('msg', ERROR_DANGER_DIV . lang('ERROR_NO_RECIPIENTS') . ERROR_END_DIV);
			//redirect(base_url().'Estimates/edit/'.$id, 'refresh'); 
		}
	}
	/*
      @Author 	: RJ(Rupesh Jorkar)
      @Desc   	: Remove this function at last RJ
      @Input 	:
      @Output	: Export in PDF view.
      @Date   	: 09/03/2016
     */
    function GeneratePrintPDF($id,$section = NULL) {
		$data = [];
    //Get Estimate main records
        $table = ESTIMATE_MASTER . ' as em';
        $match = "em.estimate_id = " . $id;
        $data['editRecord'] = $this->common_model->get_records($table, '', '', '', $match);

    //Get Client and Recipient Selected
        $EstClient_table = ESTIMATE_CLIENT . ' as EstClntTbl';
        $EstClient_match = "EstClntTbl.estimate_id = " . $id;
        $EstClient_fields = array("EstClntTbl.estimate_id, EstClntTbl.prospect_id, EstClntTbl.client_type, EstClntTbl.recipient_id, EstClntTbl.recipient_type, ");
        $EstClientArray = $this->common_model->get_records($EstClient_table, $EstClient_fields, '', '', $EstClient_match);
	/*
	 * Below code for Estimate Preview
	 */
		$PreviewClientArray = array();  //Store Estimate Preview Option
        if (count($EstClientArray) > 0) {
            $PreviewClientType = $EstClientArray[0]['client_type'];
            $PreviewClientID = $EstClientArray[0]['prospect_id'];
            if ($PreviewClientType == 'client') {
            //Get Client Information for Recipients
                $clntPreview_table = PROSPECT_MASTER . ' as pro';
                $clntPreview_match = "pro.status_type = 3 AND prospect_id = " . $PreviewClientID;
                $clntPreview_fields = array("pro.*, cnt.country_name");
                $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=pro.country_id');
                $GetPreClientData = $this->common_model->get_records($clntPreview_table, $clntPreview_fields, $join_tables, 'left', $clntPreview_match);

                $PreviewClientArray['name'] = $GetPreClientData[0]['prospect_name'];
                $PreviewClientArray['address1'] = $GetPreClientData[0]['address1'];
                $PreviewClientArray['address2'] = $GetPreClientData[0]['address2'];
                $PreviewClientArray['city'] = $GetPreClientData[0]['city'];
                $PreviewClientArray['state'] = $GetPreClientData[0]['state'];
                $PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
                $PreviewClientArray['postal_code'] = $GetPreClientData[0]['postal_code'];
                $PreviewClientArray['image'] = ""; //Image Field not available in Prospect Master table
            } else if ($PreviewClientType == 'contact') {
            //Get Contact Information for Recipients
                $conPreview_table = CONTACT_MASTER . ' as con_mst';
                $conPreview_match = "con_mst.status = 1 AND contact_id = " . $PreviewClientID;
                $conPreview_fields = array("con_mst.*, cnt.country_name");
                $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=con_mst.country_id');
                $GetPreClientData = $this->common_model->get_records($conPreview_table, $conPreview_fields, $join_tables, 'left', $conPreview_match);

                $PreviewClientArray['name'] = $GetPreClientData[0]['contact_name'];
                $PreviewClientArray['address1'] = $GetPreClientData[0]['address1'];
                $PreviewClientArray['address2'] = $GetPreClientData[0]['address2'];
                $PreviewClientArray['city'] = $GetPreClientData[0]['city'];
                $PreviewClientArray['state'] = $GetPreClientData[0]['state'];
                $PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
                $PreviewClientArray['postal_code'] = $GetPreClientData[0]['postal_code'];
                $PreviewClientArray['image'] = $GetPreClientData[0]['image'];
            } else if ($PreviewClientType == 'company') {
            //Get Company Information For Client Select Box
                $ComPreview_table = COMPANY_MASTER . ' as com_mst';
                $ComPreview_match = "com_mst.status = 1 AND company_id = " . $PreviewClientID;
                $ComPreview_fields = array("com_mst.company_id, com_mst.company_name");
                $GetPreClientData = $this->common_model->get_records($ComPreview_table, $ComPreview_fields, '', '', $ComPreview_match);

                $PreviewClientArray['name'] = $GetPreClientData[0]['company_name'];
                //Note: Following information not available in Table
                $PreviewClientArray['address1'] = "";
                $PreviewClientArray['address2'] = "";
                $PreviewClientArray['city'] = "";
                $PreviewClientArray['state'] = "";
                $PreviewClientArray['country_name'] = "";
                $PreviewClientArray['postal_code'] = "";
                $PreviewClientArray['image'] = "";
            }
        }
        $data['PreviewClientInformation'] = $PreviewClientArray; //Push Client, Contact and Company information 
    //Get All Product information 	
        $previewProducts['fields'] = ['*'];
        $previewProducts['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id', PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=EP.product_tax');
        $previewProducts['join_type'] = 'left';
        $previewProducts['table'] = ESTIMATE_PRODUCT . ' as EP';
        $previewProducts['match_and'] = 'EP.estimate_id=' . $id;
        $previewProducts['orderby'] = 'EP.product_order';
        $previewProducts['sort'] = 'ASC';
        $data['previewAllProduct'] = $this->common_model->get_records_array($previewProducts);
	//Get Company Logo for Preview
		$previewlogo['fields'] = ['cnf.value'];
		$previewlogo['table'] = CONFIG . ' as cnf';
		$previewlogo['match_and'] = 'cnf.config_key="general_settings"';
		$data['PreBZCompanyInfo'] = $this->common_model->get_records_array($previewlogo);
	//Get Estimate created user name
        if (!empty($data['editRecord'][0]['login_id']) && isset($data['editRecord'][0]['login_id']) && $data['editRecord'][0]['login_id'] != "") {
            $previewLoginUsr['fields'] = ['lgn.login_id, lgn.firstname, lgn.lastname, lgn.email'];
            $previewLoginUsr['table'] = LOGIN . ' as lgn';
            $previewLoginUsr['match_and'] = 'lgn.login_id=' . $data['editRecord'][0]['login_id'];
            $EstimateLoginInfo = $this->common_model->get_records_array($previewLoginUsr);
            $EstLoginInfo = $EstimateLoginInfo[0]['firstname'] . ' ' . $EstimateLoginInfo[0]['lastname'];
        } else {
            $EstLoginInfo = "";
        }
	//Get Company Logo for Preview
		$BZlogo['fields'] = ['cnf.value'];
		$BZlogo['table'] = CONFIG . ' as cnf';
		$BZlogo['match_and'] = 'cnf.config_key="general_settings"';
		$BZCompanyInfo = $this->common_model->get_records_array($BZlogo);
		$BZComInfoArray = array(json_decode($BZCompanyInfo[0]['value']));
			if(isset($BZComInfoArray[0]->profile_photo) && $BZComInfoArray[0]->profile_photo != "")
			{
				$BZComLogo = base_url().SETTINGS_PROFILE_PIC_UPLOAD_PATH."/".$BZComInfoArray[0]->profile_photo;
				$BZComIMG = '<img src="'.$BZComLogo.'" alt="" />';
			} else {
				$BZComIMG = "";
			}
		
		$data['PreviewLoginInfo'] = $EstLoginInfo;
        $data['main_content'] = '/estimatePreview';
        $data['section'] = $section;
		$dynamicHeader = 'Dynamic Data';
		$this->m_pdf->pdf->SetHTMLHeader('<div style="text-align: right;">'.$BZComIMG.'</div>', 'O');

		$this->m_pdf->pdf->SetHTMLFooter('<div style="text-align: center; font-family: Arial, Helvetica,sans-serif; font-weight: bold;font-size: 7pt; ">Blazedesk user company name</br>
Company street | Company post Code | City | State (if applicable)} | Country</br>
Vat Code | Tax Code (if applicable)
</div>');

        $html = $this->parser->parse('layouts/PDFTemplate', $data);
		if($section == 'pdf')
		{
        //this the the PDF filename that user will get to download
			$pdfFilePath = "Estimates.pdf";
        //load mPDF library
			//$this->load->library('m_pdf');
	/*		$html1 = '
<pageheader name="MyHeader1" content-left="" content-center="" content-right="My document" header-style="font-family: serif; font-size: 10pt; font-weight: bold; color: #000000;" line="on" />

<pagefooter name="MyFooter1" content-left="{DATE j-m-Y}" content-center="{PAGENO}/{nbpg}" content-right="My document" footer-style="font-family: serif; font-size: 8pt; font-weight: bold; font-style: italic; color: #000000;" />

<div>Now starts the document text... </div>
';*/
		
        //generate the PDF from the given html
			$this->m_pdf->pdf->WriteHTML($html);
        //download it.
			$this->m_pdf->pdf->Output($pdfFilePath, "D");
			//redirect('download/thassos_wonder_brochure', 'refresh'); 
		} elseif($section == 'PDFStore') {
			return $html;
		}else {
			//echo $html;
		}
    }
    public function upload_file($fileext=''){
        $str = file_get_contents('php://input');
        echo $filename = time().uniqid().".".$fileext;
        file_put_contents($this->config->item('estimate_img_url').'/'.$filename,$str);
    }
	function DownloadPDF($id,$section = NULL) {
	    $data = [];
    //Get Estimate main records
        $table = ESTIMATE_MASTER . ' as em';
        $match = "em.estimate_id = " . $id;
        $data['editRecord'] = $this->common_model->get_records($table, '', '', '', $match);
    //Get Client and Recipient Selected
        $EstClient_table = ESTIMATE_CLIENT . ' as EstClntTbl';
        $EstClient_match = "EstClntTbl.estimate_id = " . $id;
        $EstClient_fields = array("EstClntTbl.estimate_id, EstClntTbl.prospect_id, EstClntTbl.client_type, EstClntTbl.recipient_id, EstClntTbl.recipient_type, ");
        $EstClientArray = $this->common_model->get_records($EstClient_table, $EstClient_fields, '', '', $EstClient_match);
	/*
	 * Below code for Estimate Preview
	 */
        $PreviewClientArray = array();  //Store Estimate Preview Option
        if (count($EstClientArray) > 0) {
            $PreviewClientType = $EstClientArray[0]['client_type'];
            $PreviewClientID = $EstClientArray[0]['prospect_id'];
            if ($PreviewClientType == 'client') {
            //Get Client Information for Recipients
                $clntPreview_table = PROSPECT_MASTER . ' as pro';
                $clntPreview_match = "pro.status_type = 3 AND prospect_id = " . $PreviewClientID;
                $clntPreview_fields = array("pro.*, cnt.country_name, ComM.company_name  AS CompanyName");
                $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=pro.country_id', COMPANY_MASTER . ' as ComM' => 'ComM.company_id=pro.company_id');
				$GetPreClientData = $this->common_model->get_records($clntPreview_table, $clntPreview_fields, $join_tables, 'left', $clntPreview_match);
				
                $PreviewClientArray['name'] = $GetPreClientData[0]['prospect_name'];
                $PreviewClientArray['address1'] = $GetPreClientData[0]['address1'];
                $PreviewClientArray['address2'] = $GetPreClientData[0]['address2'];
                $PreviewClientArray['city'] = $GetPreClientData[0]['city'];
                $PreviewClientArray['state'] = $GetPreClientData[0]['state'];
                $PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
                $PreviewClientArray['postal_code'] = $GetPreClientData[0]['postal_code'];
                $PreviewClientArray['CompanyName'] = $GetPreClientData[0]['CompanyName'];
                $PreviewClientArray['image'] = ""; //Image Field not available in Prospect Master table
            } else if ($PreviewClientType == 'contact') {
            //Get Contact Information for Recipients
                $conPreview_table = CONTACT_MASTER . ' as con_mst';
                $conPreview_match = "con_mst.status = 1 AND contact_id = " . $PreviewClientID;
                $conPreview_fields = array("con_mst.*, cnt.country_name, ComM.company_name AS CompanyName");
                $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=con_mst.country_id', COMPANY_MASTER . ' as ComM' => 'ComM.company_id=con_mst.company_id');
                $GetPreClientData = $this->common_model->get_records($conPreview_table, $conPreview_fields, $join_tables, 'left', $conPreview_match);

                $PreviewClientArray['name'] = $GetPreClientData[0]['contact_name'];
                $PreviewClientArray['address1'] = $GetPreClientData[0]['address1'];
                $PreviewClientArray['address2'] = $GetPreClientData[0]['address2'];
                $PreviewClientArray['city'] = $GetPreClientData[0]['city'];
                $PreviewClientArray['state'] = $GetPreClientData[0]['state'];
                $PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
                $PreviewClientArray['postal_code'] = $GetPreClientData[0]['postal_code'];
                $PreviewClientArray['CompanyName'] = $GetPreClientData[0]['CompanyName'];
                $PreviewClientArray['image'] = $GetPreClientData[0]['image'];
            } else if ($PreviewClientType == 'company') {
            //Get Company Information For Client Select Box
                $ComPreview_table = COMPANY_MASTER . ' as com_mst';
                $ComPreview_match = "com_mst.status = 1 AND company_id = " . $PreviewClientID;
                $ComPreview_fields = array("cnt.country_name, com_mst.company_id, com_mst.company_name as CompanyName, com_mst.address1, com_mst.address2, com_mst.city, com_mst.state, com_mst.country_id, com_mst.postal_code");
                $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=com_mst.country_id');
				$GetPreClientData = $this->common_model->get_records($ComPreview_table, $ComPreview_fields, $join_tables, 'left', $ComPreview_match);
                $PreviewClientArray['name'] 		= $GetPreClientData[0]['CompanyName'];
                $PreviewClientArray['address1'] 	= $GetPreClientData[0]['address1'];
                $PreviewClientArray['address2'] 	= $GetPreClientData[0]['address2'];
                $PreviewClientArray['city'] 		= $GetPreClientData[0]['city'];
                $PreviewClientArray['state'] 		= $GetPreClientData[0]['state'];
                $PreviewClientArray['country_name'] = $GetPreClientData[0]['country_name'];
                $PreviewClientArray['postal_code'] 	= $GetPreClientData[0]['postal_code'];
                $PreviewClientArray['CompanyName'] 	= $GetPreClientData[0]['CompanyName'];
				$PreviewClientArray['image'] 		= "";
			}
        }
        $data['PreviewClientInformation'] = $PreviewClientArray; //Push Client, Contact and Company information
	//Get All Product information
        $previewProducts['fields'] = ['*'];
        $previewProducts['join_tables'] = array(PRODUCT_MASTER . ' as PM' => 'PM.product_id=EP.product_id', PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=EP.product_tax');
        $previewProducts['join_type'] 	= 'left';
        $previewProducts['table'] 		= ESTIMATE_PRODUCT . ' as EP';
        $previewProducts['match_and'] 	= 'EP.estimate_id=' . $id;
        $previewProducts['orderby'] 	= 'EP.product_order';
        $previewProducts['sort'] 		= 'ASC';
        $data['previewAllProduct'] 		= $this->common_model->get_records_array($previewProducts);
	//Get All Tax Value
		$Taxfields = array("tax.tax_percentage");
        $data['allTaxesArray'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $Taxfields, '', '', "tax.is_delete = 0");
	//Get Company Logo for Preview
        $previewlogo['fields'] 		= ['cnf.value'];
        $previewlogo['table'] 		= CONFIG . ' as cnf';
        $previewlogo['match_and'] 	= 'cnf.config_key="general_settings"';
        $data['PreBZCompanyInfo'] 	= $this->common_model->get_records_array($previewlogo);
    //Get Estimate created user name
		$EstLoginInfo = array();
        if (!empty($data['editRecord'][0]['login_id']) && isset($data['editRecord'][0]['login_id']) && $data['editRecord'][0]['login_id'] != "") {
            $previewLoginUsr['fields'] = ['role.role_name,lgn.login_id, lgn.firstname, lgn.lastname, lgn.telephone1, lgn.telephone2, lgn.email'];
            $previewLoginUsr['table'] 		= LOGIN . ' as lgn';
            $previewLoginUsr['match_and'] 	= 'lgn.login_id=' . $data['editRecord'][0]['login_id'];
			$previewLoginUsr['join_tables'] = array(ROLE_MASTER . ' as role' => 'role.role_id=lgn.user_type');
			$previewLoginUsr['join_type'] 	= 'left';
			$EstLoginInfo = $this->common_model->get_records_array($previewLoginUsr);
		} 
        $data['PreviewLoginInfo'] = $EstLoginInfo;
	//Get Estimate created user name 
	//Get added Estimate Symbol 
		$PDFCuntArray = array();
		if (!empty($data['editRecord'][0]['country_id_symbol']) && isset($data['editRecord'][0]['country_id_symbol']) && $data['editRecord'][0]['country_id_symbol'] != "") {
            $CuntPDFData['fields'] 		= ['cunt.*'];
            $CuntPDFData['table'] 		= COUNTRIES . ' as cunt';
            $CuntPDFData['match_and'] 	= 'cunt.country_id=' . $data['editRecord'][0]['country_id_symbol'];
            $PDFCuntArray = $this->common_model->get_records_array($CuntPDFData);
        } 
        $data['PDFCuntArray'] 			= $PDFCuntArray;
    //Get Terms And Condition	
		$TermsSettingData['fields'] 	= ['EstSet.*'];
		$TermsSettingData['table'] 		= ESTIMATE_SETTINGS . ' as EstSet';
		$TermsSettingData['match_and'] 	= 'EstSet.is_delete = 0';
		$data['TermsConditionDataArray']= $this->common_model->get_records_array($TermsSettingData);
    
		$data['main_content'] = '/files/estpdf';
        $data['section'] = $section;
		$html = $this->parser->parse('layouts/PDFTemplate', $data);
		$pdfFileName = "Estimates".$data['editRecord'][0]['estimate_auto_id'].".pdf";
		$pdfFilePath = FCPATH.'uploads/estimate/';
	//Estimate Header Data Array
		$PDFBZInformation 			= array();
		$BZQueryData['fields'] 		= ['cnf.value'];
		$BZQueryData['table'] 		= CONFIG . ' as cnf';
		$BZQueryData['match_and'] 	= 'cnf.config_key="general_settings"';
		$BZCompanyInfo = $this->common_model->get_records_array($BZQueryData);
		$BZComInfoArray = array(json_decode($BZCompanyInfo[0]['value']));
	//Get Dynamic Country name as per 
		if(!empty($BZComInfoArray) && isset($BZComInfoArray[0]->country_id) && $BZComInfoArray[0]->country_id != "")
		{
			$BZCntName['fields'] 		= ['conh.country_name'];
			$BZCntName['table'] 		= COUNTRIES . ' as conh';
			$BZCntName['match_and'] 	= 'conh.country_id='.$BZComInfoArray[0]->country_id;
			$BZCntName = $this->common_model->get_records_array($BZCntName);
			$PDFBZInformation['country_name'] = $BZCntName[0];
		}
		$PDFBZInformation['BZCompanyInfo'] = $BZComInfoArray;
		if($section == 'StorePDF')
		{
				$PDFHeaderHTML 	= $this->load->view('files/estpdfHeader', $PDFBZInformation, true);
			//Estimate Footer View
				$PDFFooterHTML 		= $this->load->view('files/estpdfFooter', $PDFBZInformation, true);
			//Set Header Footer and Content For PDF
				$this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
				$this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);
				$this->m_pdf->pdf->WriteHTML($html);
			//Store PDF in Estimate Folder
			//$StorePDF = $this->m_pdf->pdf->Output($pdfFilePath.$pdfFileName, 'F');
				$this->m_pdf->pdf->Output($pdfFilePath.$pdfFileName, 'F');
		} elseif ($section == 'print') {
			$html;
		} else {
			//Pass BZ information In 
					$PDFHeaderHTML 	= $this->load->view('files/estpdfHeader', $PDFBZInformation, true);
			//Estimate Footer View
					$PDFFooterHTML 	= $this->load->view('files/estpdfFooter', $PDFBZInformation, true);
			//Set Header Footer and Content For PDF
				$this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
				$this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);
				$this->m_pdf->pdf->WriteHTML($html);
			//Store PDF in Estimate Folder
				$this->m_pdf->pdf->Output($pdfFileName, "D");
		}
    }
	function showPrdTempSelectBox()
	{
		$table = ESTIMATE_TEMPLATE . ' as est_temp';
		$match = "";
		$fields = array("est_temp.est_temp_id, est_temp.est_temp_name");
		$data['estimate_temp_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
		
		$this->load->view('files/estimateProductTemplateSelectBox', $data);
	}
    /*
      @Author : RJ
      @Desc   : This function check Product Name is Unique or not.
      @Input  :
      @Output :
      @Date   : 25/05/2016
     */
	function checkProductNameUnique()
	{
		$prdName = $this->input->post('prdName');
		$table = PRODUCT_MASTER . ' as product';
        $match = "product.product_name = '".$prdName."' AND product.is_delete = '0'";
        $fields = array("product.product_id,product.product_name,product.product_type");
        $data['product_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
		if(!empty($data['product_info']) && count($data['product_info']) != 0 )
		{
			$prdData = 'notavailable';
		} else {
			$prdData = 'available';
		}
		echo json_encode(array('data' => $prdData, 'prdName' => $prdName));
	}
	/*
      @Author : RJ
      @Desc   : Create New Autograph
      @Input  :
      @Output :
      @Date   : 18/02/2016
     */
    function addNewAutograph()
	{
		$data = array();
		if($this->input->post('autogrphCnt'))
		{
			$data['autogrphCnt'] = $this->input->post('autogrphCnt') + 1;
		}
		$this->load->view('files/estimateAddNewAutograph', $data);
	}
	function prdAmntIncExcCalculation()
	{
		$taxId = $this->input->post('taxId');
		$amount = $this->input->post('amount');
		$fields = array("tax.tax_id,tax.tax_percentage");
		$match = "tax.is_delete = 0 AND tax_id = ". $taxId;
		$taxesArray = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', $match);
		//Tax Calculation
			//if($taxesArray[0]['tax_percentage'] != "" && $amount != "")
			//{
				$taxAmountCal = ($amount * $taxesArray[0]['tax_percentage'] ) / 100;
				$finalTaxIncluded = $amount + $taxAmountCal;
				$finalTaxExcluded = $amount; 
			//}
		echo json_encode(array('taxIncludedAmt' => $finalTaxIncluded, 'taxExcludedAmt' => $finalTaxExcluded));
	}
}