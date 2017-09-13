<?php

defined ('BASEPATH') OR exit('No direct script access allowed');

class InvoicesCron extends CI_Controller {

    function __construct() {
        parent::__construct ();
        $this->module     = $this->uri->segment (1);
        $this->viewname   = $this->uri->segment (2);
        $this->load->library(array('m_pdf'));
    }

    /*
      @Author : Niral Patel
      @Desc   : InvoicesCron index
      @Input  : 
      @Output :
      @Date   : 12/03/2016
     */

    public function index() {

       /* $master_user_id = $this->config->item('master_user_id');
        //$master_user_id = $data['user_info']['ID'];

        $table = SETUP_MASTER . ' as ct';
        $where_setup_data = array("ct.login_id" => $master_user_id);
        $fields = array("ct.*");
        $check_user_menu = $this->common_model->get_records_data($table, $fields, '', '', '', '', '', '', '', '', '', $where_setup_data);
        //pr($check_user_menu);exit;
        if (isset($check_user_menu[0]['is_pm']) && $check_user_menu[0]['is_pm'] == 0) {
            if (isset($check_user_menu[0]['is_crm']) && $check_user_menu[0]['is_crm'] == 1) {
                $msg = $this->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect(base_url()); //Redirect on Dashboard
            } elseif (isset($check_user_menu[0]['is_support']) && $check_user_menu[0]['is_support'] == 1) {
                $msg = $this->lang->line('DONT_HAVE_PAGE_PERMISSION');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect(base_url('Support')); //Redirect on Dashboard
            }
        }
*/
        $last_day_this_month  = date('t'); // last day of month
        $today_day = date('l');//weekday
        $today_date = date('d');//today day
        if($today_date == $last_day_this_month)
        {
            $ldt = "or date_format(recurring_time, '%d') > ".$today_date;
        }
        //Get invoice
        $table = PROJECT_INVOICES;
        $match = "is_delete = 0 and send_invoice_client = 1 and recurring != 0 and (recurring_time = '".$today_day."' or date_format(recurring_time, '%d') = ".$today_date." ".$ldt.")";
        $fields=array('invoice_id,invoice_code,recurring_time');
        $invoiceInfo = $this->common_model->get_records($table,$fields, '', '', $match);
       
        if(!empty($invoiceInfo))
        {
            foreach ($invoiceInfo as $row) {
                $this->send_invoice($row['invoice_id']);
                
            }
            echo 'done';

        } 
        echo 'Done';
    }

    
    /*
      @Author   : Niral Patel
      @Desc     : Send Invoice to Recipient.
      @Input    :
      @Output   : 
      @Date     : 12/03/2016
     */
    function send_invoice($id)
    {
        //Get Invoice main records
        $table = PROJECT_INVOICES . ' as em';
        $match = "em.invoice_id = " . $id;
        $invoiceInfo = $this->common_model->get_records($table, '', '', '', $match);

        //Get Client and Recipient Selected
        $Inv_client_table = PROJECT_INVOICE_CLIENTS . ' as invClient';
        $inv_client_match = "invClient.invoice_id = " . $id;
        $inv_client_fields = array("invClient.invoice_client_id, invClient.invoice_id, invClient.prospect_id, invClient.client_type, invClient.recipient_id, invClient.recipient_type, ");
        $EstRecipientArray = $this->common_model->get_records($Inv_client_table, $inv_client_fields, '', '', $inv_client_match);
       
        $EstRecipientInfo = array();
        $InstallAction = "";
        //echo $this->router->class;
        //echo $this->router->fetch_method();
        if(count($EstRecipientArray) != 0)
        {      //Get HTML from GeneratePrintPDF function
            $PDFHtml        = $this->DownloadPDF($id,'StorePDF');
            foreach($EstRecipientArray as $EstRecipientID)
            {
            //Compare Id with old Contact
                $EstComtable = PROJECT_INVOICE_SEND . ' as inv';
                $EstCommatch = "inv.recipient_id = " . $EstRecipientID['recipient_id'];
                $EstComfields = array("inv.invoice_send_id, inv.recipient_id");
                $ExistingSendId = $this->common_model->get_records($EstComtable, $EstComfields, '', '', $EstCommatch);
            //Set 1 in $InstallAction variable if its not added in database
                if(count($ExistingSendId) == 0)
                {
                    $InstallAction = 1;
                }
                if($EstRecipientID['recipient_type'] == 'client')
                {
                //Get Client Information for Recipients
                    $RecipientClntArray['fields']       = ['cm.email, cm.contact_id, cm.contact_name'];
                    $RecipientClntArray['join_tables']  = array(CONTACT_MASTER . ' as cm' => 'cm.contact_id=orp.contact_id');
                    $RecipientClntArray['join_type']    = 'left';
                    $RecipientClntArray['table']        = OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orp';
                    $RecipientClntArray['match_and']    = 'orp.prospect_id=' . $EstRecipientID['recipient_id'].' and cm.is_delete = 0';
                    $RecipientCntArray                  = $this->common_model->get_records_array($RecipientClntArray);
                    
                    if(!empty($RecipientCntArray) && count($RecipientCntArray) != 0)
                    {
                        foreach($RecipientCntArray as $RecipientInfo)
                        {
                            $EstRecipientInfo[] = $RecipientInfo['email'];
                            if(isset($RecipientInfo['email']) && $RecipientInfo['email'] != "")
                            {
                                $this->send_mail_to_recipient($RecipientInfo['email'], $EstRecipientID['invoice_client_id'], $invoiceInfo, $id,$RecipientInfo['contact_name']);
                            }
                        }
                    }
                }
                if ($EstRecipientID['recipient_type'] == 'contact') {
                //Get Contact Information for Recipients
                    $table = CONTACT_MASTER . ' as con_mst';
                    $match = "con_mst.status = 1 AND contact_id = " . $EstRecipientID['recipient_id'].' and con_mst.is_delete = 0';
                    $fields = array("con_mst.contact_id, con_mst.contact_name, con_mst.email");
                    $RecipientCntInfo = $this->common_model->get_records($table, $fields, '', '', $match);
                    if(count($RecipientCntInfo) != 0 && !empty($RecipientCntInfo))
                    {
                        $EstRecipientInfo[] = $RecipientCntInfo[0]['email'];
                        if(isset($RecipientCntInfo[0]['email']) && $RecipientCntInfo[0]['email'] != "")
                        {
                            $this->send_mail_to_recipient($RecipientCntInfo[0]['email'], $EstRecipientID['invoice_client_id'], $invoiceInfo, $id,$RecipientCntInfo[0]['contact_name']);
                        }
                    }
                }
            //Add data in PROJECT_INVOICE_SEND table if that not added 
                if(isset($InstallAction) && $InstallAction != "")
                    {
                    //Insert Data in Send Invoice Table
                        $estSendInfo['invoice_id'] = $id;
                        $estSendInfo['recipient_id'] = $EstRecipientID['recipient_id'];
                        $estSendInfo['recipient_type'] = $EstRecipientID['recipient_type'];
                        $estSendInfo['created_date'] = datetimeformat();
                        $LastId = $this->common_model->insert(PROJECT_INVOICE_SEND, $estSendInfo);
                        $InstallAction = "";
                    }
            }
            //Remove Created PDF from DownloadPDF function
            if(file_exists($pdfEstPath.$pdfEstFileName)){
                    unlink($pdfEstPath.$pdfEstFileName);
                }
        } 
        //$this->load->view('files/estSendEstimation', $data, true);
    }
    /*
      @Author   : Niral Patel
      @Desc     : Send Invoice to indivisual recipient.
      @Input    :
      @Output   : 
      @Date     : 22/03/2016
     */
    function send_mail_to_recipient($recipientEmail,$invoice_client_id, $invoiceInfo, $id,$contact_name){
        
        if(isset($recipientEmail) && $recipientEmail != "")
        {
                $pdfEstFileName = "Invoice".$invoiceInfo[0]['invoice_code'].".pdf";
                $pdfEstPath = FCPATH."uploads/projectManagement/InvoicePDF/";
            // Get Email configs from Email settings page
                $configs        = getMailConfig(); 
            
                // Get Template from Template Master
                $ETMPDFtable    = EMAIL_TEMPLATE_MASTER . ' as et';
                $ETMPDFmatch    = "et.template_id = 40 ";
                $ETMPDFfields   = array("et.subject,et.body");
                $ETMPDFInfo     = $this->common_model->get_records($ETMPDFtable,$ETMPDFfields,'','',$ETMPDFmatch);
                
                $content        = chunk_split(base64_encode($content));
                $mailto         = $recipientEmail;
                
                //$mailto       = 'blazedeskcrm@gmail.com';
                $subject        = $ETMPDFInfo[0]['subject'];
                //$message        = $ETMPDFInfo[0]['body'];
                $find = array(
                    '{USER}',
                );

                $replace = array(
                    'USER' => $contact_name,
                        //    'DATE' => $order_info['payment_company']
                );
                $format = $ETMPDFInfo[0]['body'];
                $message = str_replace(array("\r\n",
                    "\r",
                    "\n"), '<br />', preg_replace(array("/\s\s+/",
                    "/\r\r+/",
                    "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
                if(send_mail($mailto, $subject, $message, $pdfEstPath.$pdfEstFileName))
                {
                    
                    $msg = "<div class='alert alert-success text-center'>" . 'Send Invoice To Recipient Successfully.' . "</div>";
                } else {
                
                    
                    $msg = "<div class='alert alert-danger text-center'>" . lang('error') . "</div>";
                }
                $this->email->clear(TRUE);
                return $msg;
                //echo $msg;
            /*}*/
        } else {
            //$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('ERROR_NO_RECIPIENTS') . "</div>");
            //redirect(base_url().'Invoices/edit/'.$id, 'refresh'); 
        }
        
    }
    /*
      @Author   : Niral Patel
      @Desc     : Save pdf
      @Input    :
      @Output   : 
      @Date     : 22/03/2016
     */
    function DownloadPDF($id,$section = NULL) {
        $data = [];
        //Get Invoice main records
        $table = PROJECT_INVOICES . ' as em';
        $match = "em.invoice_id = " . $id;
        $data['editRecord'] = $this->common_model->get_records($table, '', '', '', $match);
        //Get Client and Recipient Selected
        $Inv_client_table = PROJECT_INVOICE_CLIENTS . ' as invClient';
        $inv_client_match = "invClient.invoice_id = " . $id;
        $inv_client_fields = array("invClient.invoice_id, invClient.prospect_id, invClient.client_type, invClient.recipient_id, invClient.recipient_type, ");
        $inv_client_array = $this->common_model->get_records($Inv_client_table, $inv_client_fields, '', '', $inv_client_match);
        
        /*
         * Below code for Invoice Preview
         */
        $preview_client_array = array();  //Store Invoice Preview Option
        if (count($inv_client_array) > 0) {
            $preview_client_type = $inv_client_array[0]['client_type'];
            $preview_client_id = $inv_client_array[0]['prospect_id'];
            if ($preview_client_type == 'client') {
            //Get Client Information for Recipients
                $clntPreview_table = PROSPECT_MASTER . ' as pro';
                $clntPreview_match = "pro.status_type = 3 AND prospect_id = " . $preview_client_id;
                $clntPreview_fields = array("pro.*, cnt.country_name, ComM.company_name  AS CompanyName");
                $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=pro.country_id', COMPANY_MASTER . ' as ComM' => 'ComM.company_id=pro.company_id');
                $get_pre_client_data = $this->common_model->get_records($clntPreview_table, $clntPreview_fields, $join_tables, 'left', $clntPreview_match);
                
                $preview_client_array['name'] = $get_pre_client_data[0]['prospect_name'];
                $preview_client_array['address1'] = $get_pre_client_data[0]['address1'];
                $preview_client_array['address2'] = $get_pre_client_data[0]['address2'];
                $preview_client_array['city'] = $get_pre_client_data[0]['city'];
                $preview_client_array['state'] = $get_pre_client_data[0]['state'];
                $preview_client_array['country_name'] = $get_pre_client_data[0]['country_name'];
                $preview_client_array['postal_code'] = $get_pre_client_data[0]['postal_code'];
                $preview_client_array['CompanyName'] = $get_pre_client_data[0]['CompanyName'];
                $preview_client_array['image'] = ""; //Image Field not available in Prospect Master table
            } else if ($preview_client_type == 'contact') {
            //Get Contact Information for Recipients
                $conPreview_table = CONTACT_MASTER . ' as con_mst';
                $conPreview_match = "con_mst.status = 1 AND contact_id = " . $preview_client_id;
                $conPreview_fields = array("con_mst.*, cnt.country_name, ComM.company_name AS CompanyName");
                $join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=con_mst.country_id', COMPANY_MASTER . ' as ComM' => 'ComM.company_id=con_mst.company_id');
                $get_pre_client_data = $this->common_model->get_records($conPreview_table, $conPreview_fields, $join_tables, 'left', $conPreview_match);

                $preview_client_array['name'] = $get_pre_client_data[0]['contact_name'];
                $preview_client_array['address1'] = $get_pre_client_data[0]['address1'];
                $preview_client_array['address2'] = $get_pre_client_data[0]['address2'];
                $preview_client_array['city'] = $get_pre_client_data[0]['city'];
                $preview_client_array['state'] = $get_pre_client_data[0]['state'];
                $preview_client_array['country_name'] = $get_pre_client_data[0]['country_name'];
                $preview_client_array['postal_code'] = $get_pre_client_data[0]['postal_code'];
                $preview_client_array['CompanyName'] = $get_pre_client_data[0]['CompanyName'];
                $preview_client_array['image'] = $get_pre_client_data[0]['image'];
            } else if ($preview_client_type == 'company') {
            //Get Company Information For Client Select Box
                $ComPreview_table = COMPANY_MASTER . ' as com_mst';
                $ComPreview_match = "com_mst.status = 1 AND company_id = " . $preview_client_id;
                $ComPreview_fields = array("com_mst.company_id, com_mst.company_name as CompanyName");
                //$join_tables = array(COUNTRIES . ' as cnt' => 'cnt.country_id=com_mst.country_id');
                $get_pre_client_data = $this->common_model->get_records($ComPreview_table, $ComPreview_fields, '', '', $ComPreview_match);
                $preview_client_array['name']         = $get_pre_client_data[0]['CompanyName'];
                $preview_client_array['CompanyName']  = $get_pre_client_data[0]['CompanyName'];
                $preview_client_array['image']        = "";
            }
        }
        $data['PreviewClientInformation'] = $preview_client_array; //Push Client, Contact and Company information
        //Get All Product information
        $preview_items['fields'] = ['*'];
        $preview_items['join_tables'] = array(PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=EP.tax_rate');
        $preview_items['join_type']   = 'left';
        $preview_items['table']       = PROJECT_INVOICES_ITEMS . ' as EP';
        $preview_items['match_and']   = 'EP.invoice_id=' . $id;
        $preview_items['orderby']     = 'EP.invoice_item_id';
        $preview_items['sort']        = 'ASC';
        $data['previewAllItems']      = $this->common_model->get_records_array($preview_items);
        
        $preview_product['fields'] = ['*,cunt.currency_symbol'];
        $preview_product['join_tables'] = array(COUNTRIES . ' as cunt' => 'cunt.country_id=EP.amount_per');
        $preview_product['join_type']   = 'left';
        $preview_product['table']       = PROJECT_INVOICES_PAYMENT . ' as EP';
        $preview_product['match_and']   = 'EP.invoice_id=' . $id;
        $preview_product['orderby']     = 'EP.invoice_payment_id';
        $preview_product['sort']        = 'ASC';
        $data['previewAllPayment']      = $this->common_model->get_records_array($preview_product);
        
        
        //Get Company Logo for Preview
        $preview_logo['fields']      = ['cnf.value'];
        $preview_logo['table']       = CONFIG . ' as cnf';
        $preview_logo['match_and']   = 'cnf.config_key="general_settings"';
        $data['PreBZCompanyInfo']   = $this->common_model->get_records_array($preview_logo);

        //Get Invoice created user name
        $inv_login_info = array();
        if (!empty($data['editRecord'][0]['created_by']) && isset($data['editRecord'][0]['created_by']) && $data['editRecord'][0]['created_by'] != "") {
            $preview_login_usr['fields'] = ['role.role_name,lgn.login_id, lgn.firstname, lgn.lastname, lgn.telephone1, lgn.telephone2, lgn.email'];
            $preview_login_usr['table']       = LOGIN . ' as lgn';
            $preview_login_usr['match_and']   = 'lgn.login_id=' . $data['editRecord'][0]['login_id'];
            $preview_login_usr['join_tables'] = array(ROLE_MASTER . ' as role' => 'role.role_id=lgn.user_type');
            $preview_login_usr['join_type']   = 'left';
            $inv_login_info = $this->common_model->get_records_array($preview_login_usr);

        } 
        $data['PreviewLoginInfo'] = $inv_login_info;

        //Get Invoice created user name    
        $PDF_cunt_array = array();
        if (!empty($data['editRecord'][0]['currency']) && isset($data['editRecord'][0]['currency']) && $data['editRecord'][0]['currency'] != "") {
            $cunt_PDF_data['fields']      = ['cunt.*'];
            $cunt_PDF_data['table']       = COUNTRIES . ' as cunt';
            $cunt_PDF_data['match_and']   = 'cunt.country_id=' . $data['editRecord'][0]['currency'];
            $PDF_cunt_array = $this->common_model->get_records_array($cunt_PDF_data);
        } 
        $data['PDF_cunt_array']           = $PDF_cunt_array;
    
        $data['main_content'] = '/Invoices/files/invoice_pdf';
        $data['section'] = $section;
        
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdf_file_name = "Invoice".$data['editRecord'][0]['invoice_code'].".pdf";
        $pdf_file_path = FCPATH.'uploads/projectManagement/InvoicePDF/';
        //Invoice Header Data Array
        $PDF_BZ_information           = array();
        $BZ_query_data['fields']      = ['cnf.value'];
        $BZ_query_data['table']       = CONFIG . ' as cnf';
        $BZ_query_data['match_and']   = 'cnf.config_key="general_settings"';
        $BZ_company_info = $this->common_model->get_records_array($BZ_query_data);

        $BZ_com_info_array = array(json_decode($BZ_company_info[0]['value']));
        //Get Dynamic Country name as per 
        if(!empty($BZ_com_info_array) && isset($BZ_com_info_array[0]->country_id) && $BZ_com_info_array[0]->country_id != "")
        {
            $BZ_cnt_name['fields']        = ['conh.country_name'];
            $BZ_cnt_name['table']         = COUNTRIES . ' as conh';
            $BZ_cnt_name['match_and']     = 'conh.country_id='.$BZ_com_info_array[0]->country_id;
            $BZ_cnt_name = $this->common_model->get_records_array($BZ_cnt_name);
            $PDF_BZ_information['country_name'] = $BZ_cnt_name[0];
        }
        $PDF_BZ_information['BZCompanyInfo'] = $BZ_com_info_array;
        if($section == 'StorePDF')
        {
            $PDF_header_HTML  = $this->load->view('/Invoices/files/invoice_pdf_header', $PDF_BZ_information, true);
            //Invoice Footer View
            $PDF_footer_HTML      = $this->load->view('/Invoices/files/invoice_pdf_footer', $PDF_BZ_information, true);
            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->SetHTMLHeader($PDF_header_HTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDF_footer_HTML);
            $this->m_pdf->pdf->WriteHTML($html);
            //Store PDF in Invoice Folder
            $store_PDF = $this->m_pdf->pdf->Output($pdf_file_path.$pdf_file_name, 'F');

            return $pdf_file_name;
        } elseif ($section == 'print') {
            $html;
        } else {
            //Pass BZ information In 
             $PDF_header_HTML  = $this->load->view('/Invoices/files/invoice_pdf_header', $PDF_BZ_information, true);
            //Invoice Footer View
            $PDF_footer_HTML  = $this->load->view('/Invoices/files/invoice_pdf_footer', $PDF_BZ_information, true);
            //Set Header Footer and Content For PDF
                $this->m_pdf->pdf->SetHTMLHeader($PDF_header_HTML, 'O');
                $this->m_pdf->pdf->SetHTMLFooter($PDF_footer_HTML);
                $this->m_pdf->pdf->WriteHTML($html);
            //Store PDF in Invoice Folder
                $this->m_pdf->pdf->Output($pdf_file_name, "D");
        }
        unset($data);
    }
}
