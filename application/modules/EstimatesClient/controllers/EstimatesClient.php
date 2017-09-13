<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EstimatesClient extends CI_Controller {

    public $viewname;

    function __construct() {
        parent::__construct();
        $this->viewname = $this->uri->segment(1);
        $this->load->model('EstimateModel');
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : show client view
      @Input 	:
      @Output	:
      @Date   : 09/03/2016
     */
    public function ClientView($id,$est_client_id) {
	    $table = ESTIMATE_CLIENT_APPROVAL.' as eca';
        $where = "est_client_id = ".$est_client_id." AND estimate_id = ".$id."  AND (eca.est_client_approval_status != '1' OR eca.est_client_approval_status != '0')";
		//$where = array("eca.est_client_approval_status" => "1","est_client_id"=>$est_client_id,"estimate_id"=>$id);
        $fields = array("eca.*");
        $data['client_data_appro'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
		if(!empty($data['client_data_appro']) && count($data['client_data_appro']) != 0){
            redirect(base_url().'EstimatesClient/error');
        }
	
	//Following Code is copy of Estimate Edit() function
    	$data = array();
        if ($id > 0) {
            $data['ctr_view'] = $this->viewname;
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
            $data['main_content'] = '/edit';
            $table = ESTIMATE_MASTER . ' as em';
            $match = "em.estimate_id = " . $id;
            $data['editRecord'] = $this->common_model->get_records($table, '', '', '', $match);
            $data['est_client_id']= $est_client_id;
            //pr($data['editRecord']);exit;
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
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect($this->module); //Redirect On Listing page   
        }
        //$this->parser->parse('layouts/DashboardTemplate', $data);
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }
    public function add_autograph() {
        /*
         * section for signature
         */
		$estimate_data['est_client_id'] = $this->input->post('est_client_id');
		$estimate_data['est_client_approval_status'] = $this->input->post('est_client_approval_status');
        $estimate_data['estimate_id'] = $this->input->post('estimate_id');
        $estimate_data['estimate_auto_id'] = $this->input->post('estimate_auto_id');
        $estimate_data['client_can_accept_online_textbox'] = $this->input->post('client_can_accept_online_textbox');
        $estimate_data['status'] = $this->input->post('hdn_submit_status');
		$estimate_data['signature'] = $this->input->post('signature-digital_old', false);
       // pr($estimate_data);exit;
        if (strlen($this->input->post('signature_type')) > 0) {
            if ($this->input->post('signature_type') == 1) {
                //file upload
                if ($_FILES['signature-file']['tmp_name'] != '') {
                    $dataImg = file_get_contents($_FILES['signature-file']['tmp_name']);
                    $type = $_FILES['signature-file']['type'];
                $estimate_data['signature'] = 'data:' . $type . ';base64,' . base64_encode($dataImg);
                }
            } else {

                $estimate_data['signature'] = $this->input->post('signature-digital_old', false);
            }
    }
        $estimate_data['signature_type'] = $this->input->post('signature_type');
        $success_insert = $this->common_model->insert(ESTIMATE_CLIENT_APPROVAL, $estimate_data);
	//Send Mail to User
		//Get Email TEmplate Master Records
			$ETMmatch 	  	= "et.template_id = 65";
			$ETMfields   	= array("et.subject,et.body");
			$emailTemplate 	= $this->common_model->get_records(EMAIL_TEMPLATE_MASTER . ' as et',$ETMfields,'','',$ETMmatch);
		if(count($emailTemplate) != 0 && !empty($emailTemplate))
		{
			$estCode	= $estimate_data['estimate_auto_id'];
			$estStatus	= $estimate_data['est_client_approval_status'];
			if($estStatus == 1)
			{
				$estimateStatus = 'Accept';
			} else {
				$estimateStatus = 'Decline';
			}
		//Get Estimate Data From Estimate Master 
			$estTable = ESTIMATE_MASTER . ' as ESTM';
			$estMatch = "ESTM.status = 1 AND estimate_id = " . $estimate_data['estimate_id'];
			$estFields = array("ESTM.login_id, ESTM.estimate_id, ESTM.estimate_auto_id ");
			$getEstimateData = $this->common_model->get_records($estTable, $estFields, '', '', $estMatch);
	//If Estimate available in database then its true
		if(count($getEstimateData) != 0 && !empty($getEstimateData))
		{
		//If Created Estimate have Login User id then if condition true
			if(isset($getEstimateData[0]['login_id']) && $getEstimateData[0]['login_id'] != "")
			{
			//Get User Information
				$userInfo = getUserDetail($getEstimateData[0]['login_id']);
				if(count($userInfo) != 0 && !empty($userInfo))
				{	
					$userName = $userInfo[0]['firstname'].' '.$userInfo[0]['lastname'].',';
				} else {	$userName = ",";	}
				$to = $userInfo[0]['email'];
			//Get Contact Information
				$conTable = CONTACT_MASTER . ' as con_mst';
				$conMatch = "con_mst.status = 1 AND contact_id = " . $estimate_data['est_client_id'];
				$conFields = array("con_mst.*");
				$getContactData = $this->common_model->get_records($conTable, $conFields, '', '', $conMatch);
			//Email Data
				$subject	= $emailTemplate[0]['subject'];
				$body 		= $emailTemplate[0]['body'];
			//Replace Data With Email Body
				$replaceUserContent = str_replace("{USER}",$userName,$body);
				$replaceEstCode		= str_replace("{EST-CODE}",$estCode,$replaceUserContent);
				$replaceClientName	= str_replace("{CLIENT_NAME}",'Client Name',$replaceEstCode);
				$bodyContent	= str_replace("{STATUS}",$estimateStatus,$replaceClientName);
				if(isset($to) && $to != "")
				{
					send_mail($to,$subject,$bodyContent);
				}
			}//Close Login id if condition 
		}
		}
        redirect(base_url().'EstimatesClient/welcome');
    }

   public function welcome(){
		$data['main_content'] = '/welcome';
        $this->parser->parse('layouts/EstimatesClientTemplate', $data);
		//$this->load->view('EstimatesClient/welcome');
    }
    public function error(){
        $data['main_content'] = '/error';
        $this->parser->parse('layouts/EstimatesClientTemplate', $data);
		//$this->load->view('EstimatesClient/error');
    }
    function productCalculationSubTotal() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        }
        $data['discount'] 		= 0;
        $data['subtotal'] 		= 0;
        $data['taxes'] 			= 0;
        $data['total'] 			= 0;
        $data['discount_earned']= 0;
        $data['discountOption'] = 'prsnt';

        $totalAmtArray = array();
        $singleDiscount = array();
        $singletax = array();
        $subTotal = array();
        //Get Discount Option
        if($this->input->post('discountOption')) {
            $data['discountOption'] = $this->input->post('discountOption');
        }
        //Get Discount as per Single Amount
        if($this->input->post('discount')) {
            $data['discount'] = $this->input->post('discount');
        }
        //Calculate Discount as per Single Amount
        if (is_array($this->input->post('singleAmt'))) {
            $singleAmtArray = $this->input->post('singleAmt');
            $singleTaxArray = $this->input->post('tax');
            $singleTaxIdArray = $this->input->post('tax_id');
            $singleQtyArray = $this->input->post('singleQuantity');
            $amtflag = 0;
            foreach($singleAmtArray as $singleAmtVal){
                $singleDiscountAmt = 0;
                $singletaxAmt = 0;
                $singletaxVal = 0;
                $singleAmtInfo = $singleAmtVal * $singleQtyArray[$amtflag];
                $subTotal[] = $singleAmtInfo;
                //Get Tax amount from Database
                $match = "txtm.tax_id = ".$singleTaxIdArray[$amtflag];
                $taxValue = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as txtm', array("txtm.tax_id,txtm.tax_percentage"), '', '', $match);
                if(isset($taxValue) && count($taxValue) != 0):
                    $singletaxVal = $taxValue[0]['tax_percentage'];
                endif;
                //Calculate Discount amount as per Single Amount
                if(isset($data['discount']) && $data['discount'] != "" && $data['discount'] != 0)
                {
                    if($data['discountOption'] == 'prsnt')
                    {
                        $singleDiscountAmt = ($singleAmtInfo * amtRound($data['discount'])) / 100;
                    }
                }
                //Deduct Discount From Single Amount
                $singleDiscountDeduct = $singleAmtInfo - $singleDiscountAmt;
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
        if($data['discountOption'] == 'amt')
        {
            $singleDiscountAmt = $data['discount'];
            $afterDiscountOption = array_sum($totalAmtArray) - $singleDiscountAmt;
        } else {
            $afterDiscountOption = array_sum($totalAmtArray);
        }

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
}
