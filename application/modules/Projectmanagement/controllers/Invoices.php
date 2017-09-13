<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = 'Projectmanagement';
        $this->viewname  = $this->router->fetch_class();
        $this->user_info = $this->session->userdata('LOGGED_IN');  //Current Login information
        $this->project_id = $this->session->userdata('PROJECT_ID');
        $this->load->library(array('m_pdf'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Invoices index
      @Input  :
      @Output :
      @Date   : 22/03/2016
     */

    public function index($page='') {

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
        }*/

        //Get uri segment for pagination
        $cur_uri= explode('/',$_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);

        $data['header'] = array('menu_module' => 'Projectmanagement');
        $data['invoice_view'] = $this->module . '/' . $this->viewname;
        $searchtext = '';
        $perpage = '';
        $where = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = RECORD_PER_PAGE;
        $data['groupFieldName'] = $groupFieldName = $this->input->post('groupFieldName');
        $data['groupFieldData'] = $groupFieldData = $this->input->post('groupFieldData');

        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('invoicespaging_data');
        }
        $data['status'] = array('1' => 'Paid',
            '0' => 'Unpaid');
        $searchsort_session = $this->session->userdata('invoicespaging_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {
                $sortfield = 'invoice_id';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = RECORD_PER_PAGE;
                $data['perpage'] = RECORD_PER_PAGE;
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url($this->module . '/' . $this->viewname . '/index');

       if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment           = 0;
        } else {
            $config['uri_segment'] = $cur_uri_segment;
            $uri_segment           = $page;
        }


        $dbSearch = "";
        if (!empty($searchtext)) {

            $searchFields = array('invoice_code', 'client_name', 'amount');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr($dbSearch, 0, -3) . ')';
        }
        $where = array('pt.is_delete' => 0);
        /* $config['total_rows'] = $this->common_model->get_records (PROJECT_INVOICES, '', '', '', $where, '', '', '', 'invoice_id', 'desc', '', $dbSearch, '', '', '1');
          //Get Records From PROJECT_INVOICES Table
          $data['invoice_data'] = $this->common_model->get_records (PROJECT_INVOICES, '', '', '', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $dbSearch);
         */$table = PROJECT_INVOICES . ' as pt';
        $fields = array('p.client_name,pt.project_id,pt.invoice_code,pt.total_payment,pt.amount,pt.created_date,pt.invoice_id');
        $join_table = array(PROJECT_INVOICE_CLIENTS . ' as p' => 'p.invoice_id=pt.invoice_id');
        $group_by =array('pt.invoice_id');
        $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'invoice_id', 'desc', $group_by, $dbSearch, '', '', '1');
        $data['invoice_data'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $dbSearch);
      //  echo $this->db->last_query();
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'groupFieldName' => $this->input->post('groupFieldName'),
            'groupFieldData' => $this->input->post('groupFieldData'),
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('invoicespaging_data', $sortsearchpage_data);
        //Get Records From Login Table 
        $where = array('status' => 1);
        $data['res_user'] = $this->common_model->get_records(LOGIN, '', '', '', $where, '');
        $data['drag'] = true;
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('project_ajax')) {
                $this->load->view('/' . $this->viewname . '/Invoices', $data);
            } else {
                $this->load->view('/' . $this->viewname . '/InvoicesAjaxList', $data);
            }
        } else {
            $data['main_content'] = '/Invoices/' . $this->viewname;
            $data['js_content'] = '/loadJsFiles';
            $this->parser->parse('layouts/ProjectstatusTemplate', $data);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert/update data
      @Input  : Post data/Update id
      @Output : Insert/update data
      @Date   : 22/03/2016
     */

    public function insertdata() {
        //pr($_POST);exit;
        if (!validateFormSecret()) {
            redirect($this->module . '/Invoices'); //Redirect On Listing page
        }
        if ($this->input->post('invoice_id')) {
            $id = $invoice_id = $this->input->post('invoice_id');
        }
        $display = $this->input->post('display');

        $insert_data['project_id'] = $this->input->post('not_associat_project_id');
        $insert_data['description'] = $this->input->post('description', FALSE);
        $prospect_arr = explode("_", $this->input->post('prospect_id'));

        if (!empty($prospect_arr)) {
            $insert_data['client_id'] = $prospect_arr[1];
        }
        $insert_data['not_associat_project_id'] = $this->input->post('not_associat_project_id');
        $show_in_client_area = $this->input->post('show_in_client_area');
        if ($this->input->post('show_in_client_area')) {
            $insert_data['show_in_client_area'] = $show_in_client_area;
        } else {
            $insert_data['show_in_client_area'] = 0;
        }
        $send_invoice_client = $this->input->post('send_invoice_client');
        if ($this->input->post('send_invoice_client')) {
            $insert_data['send_invoice_client'] = $send_invoice_client;
        } else {
            $insert_data['send_invoice_client'] = 0;
        }
        $auto_send = $this->input->post('auto_send');
        $insert_data['amount'] = $this->input->post('amount_total');
        $insert_data['total_payment'] = $this->input->post('total_payment');
        $auto_send = $this->input->post('auto_send');
        if ($this->input->post('auto_send')) {
            $insert_data['auto_send'] = $auto_send;
        }
        //$insert_data['auto_send']              = $this->input->post('auto_send');
        $insert_data['recurring'] = $this->input->post('recurring');
        if (!empty($insert_data['recurring']) && $insert_data['recurring'] == 1) {
            $insert_data['recurring_time'] = $this->input->post('recurring_time');
        } else if (!empty($insert_data['recurring']) && $insert_data['recurring'] == 2) {
            $recurring_time_month = $this->input->post('recurring_time_month');
            if (!empty($recurring_time_month)) {
                $insert_data['recurring_time'] = date("Y-m-d", strtotime($recurring_time_month));
            }
        } else {
            $insert_data['recurring_time'] = '';
        }
        $insert_data['currency'] = $this->input->post('currency');
        $notes = $this->input->post('notes_desc', FALSE);
        $insert_data['notes'] = !empty($notes) ? $notes : '';
        $insert_data['status'] = 1;

        //Insert Record in Database

        if (!empty($id)) { //update
            $insert_data['modified_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['modified_date'] = datetimeformat();
            $where = array('invoice_id' => $id);
            $success_update = $this->common_model->update(PROJECT_INVOICES, $insert_data, $where);
            //Delete invoie item
            $delete_item_id = $this->input->post('delete_item_id');
            if (!empty($delete_item_id)) {
                $delete_item = substr($delete_item_id, 0, -1);
                $delete_item_id = explode(',', $delete_item);
                $where1 = array('invoice_id' => $id);
                $this->common_model->delete_where_in(PROJECT_INVOICES_ITEMS, $where1, 'invoice_item_id', $delete_item_id);
                //echo $this->db->last_query();exit;
            }
            //update invoice item
            $where1 = array('invoice_id' => $id);
            $invoice_item = $this->common_model->get_records(PROJECT_INVOICES_ITEMS, array('invoice_item_id'), '', '', $where1, '');

            if (!empty($invoice_item)) {
                for ($i = 0; $i < count($invoice_item); $i++) {
                    # code...
                    $update_item['item_name'] = ucfirst($this->input->post('item_name_' . $invoice_item[$i]['invoice_item_id']));
                    $update_item['qty_hours'] = $this->input->post('qty_hours_' . $invoice_item[$i]['invoice_item_id']);
                    $update_item['rate'] = $this->input->post('rate_' . $invoice_item[$i]['invoice_item_id']);
                    $update_item['tax_rate'] = $this->input->post('tax_rate_' . $invoice_item[$i]['invoice_item_id']);
                    $update_item['discount'] = $this->input->post('discount_' . $invoice_item[$i]['invoice_item_id']);
                    $update_item['cost'] = $this->input->post('cost_' . $invoice_item[$i]['invoice_item_id']);
                    $where = array('invoice_item_id' => $invoice_item[$i]['invoice_item_id']);
                    $success_update = $this->common_model->update(PROJECT_INVOICES_ITEMS, $update_item, $where);
                }
            }
            //Delete invoie payment
            $delete_payment_id = $this->input->post('delete_payment_id');
            if (!empty($delete_payment_id)) {
                $delete_payment = substr($delete_payment_id, 0, -1);
                $delete_payment_id = explode(',', $delete_payment);
                $where1 = array('invoice_id' => $id);
                $this->common_model->delete_where_in(PROJECT_INVOICES_PAYMENT, $where1, 'invoice_payment_id', $delete_payment_id);
                //echo $this->db->last_query();exit;
            }
            //update invoice item
            $where1 = array('invoice_id' => $id);
            $invoice_payment = $this->common_model->get_records(PROJECT_INVOICES_PAYMENT, array('invoice_payment_id'), '', '', $where1, '');

            if (!empty($invoice_payment)) {
                for ($i = 0; $i < count($invoice_payment); $i++) {
                    # code...
                    $update_payment['amount'] = ucfirst($this->input->post('amount_' . $invoice_payment[$i]['invoice_payment_id']));
                    $update_payment['amount_per'] = $this->input->post('amount_per_' . $invoice_payment[$i]['invoice_payment_id']);
                    $due_on = $this->input->post('due_on_' . $invoice_payment[$i]['invoice_payment_id']);
                    if (!empty($due_on)) {
                        $update_payment['due_on'] = date("Y-m-d", strtotime($due_on));
                    }
                    $update_payment['notes'] = $this->input->post('notes_' . $invoice_payment[$i]['invoice_payment_id']);

                    $where = array('invoice_payment_id' => $invoice_payment[$i]['invoice_payment_id']);
                    $success_update = $this->common_model->update(PROJECT_INVOICES_PAYMENT, $update_payment, $where);
                }
            }
            $msg = $this->lang->line('invoice_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else { //insert
            $insert_data['invoice_code'] = $this->input->post('invoice_code');
            $insert_data['created_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['created_date'] = datetimeformat();
            //pr($insert_data);exit;
            $id = $this->common_model->insert(PROJECT_INVOICES, $insert_data);
            $msg = $this->lang->line('invoice_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        /*
         * Following Section for Insert Client And Recipients
         */

        if (isset($prospect_arr) && !empty($prospect_arr)) {
            $recipient_array = $this->input->post('recipient_id');
            if (count($recipient_array) > 0) {
                $where = array('invoice_id' => $id);
                $this->common_model->delete(PROJECT_INVOICE_CLIENTS, $where);

                foreach ($recipient_array as $recipient_id) {
                    $recipient_id_array = explode("_", $recipient_id);  //Create Array of Recipient 
                    $client_data['invoice_id'] = $id;
                    $client_data['recipient_type'] = $recipient_id_array[0];
                    $client_data['recipient_id'] = $recipient_id_array[1];
                    $client_data['client_type'] = $prospect_arr[0];
                    $client_data['client_name'] = $this->input->post('client_name');
                    $client_data['prospect_id'] = $prospect_arr[1];
                    $client_data['created_date'] = datetimeformat();
                    $client_data['modified_date'] = datetimeformat();
                    //Insert Client Query 
                    $this->common_model->insert(PROJECT_INVOICE_CLIENTS, $client_data);
                }
            }
        }
        //Insert new item limit
        $item_name = $this->input->post('item_name');
        $qty_hours = $this->input->post('qty_hours');
        $rate = $this->input->post('rate');
        $discount = $this->input->post('discount');
        $tax_rate = $this->input->post('tax_rate');
        $cost = $this->input->post('cost');
        for ($i = 0; $i < count($item_name); $i++) {
            $item_data['invoice_id'] = $id;
            $item_data['item_name'] = ucfirst($item_name[$i]);
            $item_data['qty_hours'] = $qty_hours[$i];
            $item_data['rate'] = $rate[$i];
            $item_data['tax_rate'] = $tax_rate[$i];
            $item_data['discount'] = $discount[$i];
            $item_data['cost'] = $cost[$i];
            $this->common_model->insert(PROJECT_INVOICES_ITEMS, $item_data);
        }
        //Insert payment limit
        $amount = $this->input->post('amount');
        $amount_per = $this->input->post('amount_per');
        $due_on = $this->input->post('due_on');
        $notes = $this->input->post('notes');

        for ($i = 0; $i < count($amount); $i++) {
            $payment_data['invoice_id'] = $id;
            $payment_data['amount'] = $amount[$i];
            $payment_data['amount_per'] = $amount_per[$i];
            $due_on = $due_on[$i];
            if (!empty($due_on)) {
                $payment_data['due_on'] = date("Y-m-d", strtotime($due_on));
            }

            $payment_data['notes'] = $notes[$i];
            $this->common_model->insert(PROJECT_INVOICES_PAYMENT, $payment_data);
        }
        //Upload data
        //Upload data
        $project_upload_dir = $this->config->item('project_upload_root_url') . 'ProjectInvoice';
        if (!is_dir($project_upload_dir)) {
            //create directory
            mkdir($project_upload_dir, 0777, TRUE);
        }
        $upload_dir = $this->config->item('project_upload_root_url') . 'ProjectInvoice';
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            $file_name = array();
            $file_array1 = $this->input->post('file_data');
            $file_name = $_FILES['addfile']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['addfile']['name'][$key]);
                        unset($_FILES['addfile']['type'][$key]);
                        unset($_FILES['addfile']['tmp_name'][$key]);
                        unset($_FILES['addfile']['error'][$key]);
                        unset($_FILES['addfile']['size'][$key]);
                    }
                }
            }
            $_FILES['addfile'] = $arr = array_map('array_values', $_FILES['addfile']);
            $file_path = $this->config->item('project_upload_path') . 'ProjectInvoice/';
            $data['file_view'] = $this->module . '/' . $this->viewname;
            $upload_data = uploadImage('addfile', $file_path, $data['file_view']);

            $ticketfiles = array();
            foreach ($upload_data as $dataname) {
                $ticketfiles[] = $dataname['file_name'];
            }
            $ticket_file_str = implode(",", $ticketfiles);

            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }

            $compaigndata['file_name'] = $file_data;
            if ($compaigndata['file_name'] != '') {
                $explodedData = explode(',', $compaigndata['file_name']);
                foreach ($explodedData as $img) {
                    array_push($upload_data, array('file_name' => $img));
                }
            }
            $upload_file = array();
            /* if ($this->input->post ('gallery_path')) {
              $gallery_path = $this->input->post ('gallery_path');
              $add_files    = $this->input->post ('gallery_files');
              if (count ($gallery_path) > 0) {
              for ($i = 0; $i < count ($gallery_path); $i++) {
              $upload_file[] = ['file_name'     => $add_files[$i],
              'file_path'     => $gallery_path[$i],
              'invoice_id'  => $id,
              'upload_status' => 0,
              'created_date'  => datetimeformat (),
              'upload_status' => 1];
              }
              }
              } */

            $estFIles = array();

            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $est_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $upload_file[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'invoice_id' => $id, 'created_date' => datetimeformat()];
                    }
                }
            }

            if (count($upload_data) > 0) {
                foreach ($upload_data as $files) {
                    $upload_file[] = ['file_name' => $files['file_name'],
                        'file_path' => $file_path,
                        'invoice_id' => $id,
                        'created_date' => datetimeformat()];
                }
            }

            if (count($upload_file) > 0) {
                $this->common_model->insert_batch(PROJECT_INVOICES_FILES, $upload_file);
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
            $this->common_model->delete(PROJECT_INVOICES_FILES, 'invoice_file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        //end upload
        /* send email to client */
        if (!empty($send_invoice_client) || !empty($auto_send)) {
            $this->send_invoice($id);
        }
        if (!empty($display)) {
            redirect($this->module . '/Invoices');
        } else {
            redirect($this->module . '/Invoices');
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Add record
      @Input  : Add id
      @Output : Give record
      @Date   : 18/01/2016
     */

    public function add_record($pid='') {
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true&module=invoice");
        $data['project_id'] = $pid;
        if (!empty($data['project_id'])) {
            //Get project data
            $table = PROJECT_MASTER;
            $match = "project_id = " . $data['project_id'];
            $fields = array('project_id,client_type,client_id');
            $data['project_data'] = $this->common_model->get_records($table, '', '', '', $match);
            $client_cond = '';
            $contact_cond = '';
        } else {
            $client_cond = 'and pro.is_delete = 0';
            $contact_cond = 'and con_mst.is_delete = 0';
        }
        $data['modal_title'] = $this->lang->line('create_invoice');
        $data['submit_button_title'] = $this->lang->line('create_invoice');

        //Get client data
        $where = array('is_delete' => 0);
        $fields = array('prospect_name', 'prospect_id');
        $data['clients_data'] = $this->common_model->get_records(PROSPECT_MASTER, '', '', '', $where, '');

        //Get project data
        $where = array('is_delete' => 0);
        $fields = array('project_name', 'project_id');
        $data['projects_data'] = $this->common_model->get_records(PROJECT_MASTER, '', '', '', $where, '');

        $fields = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "");
        //get currency
        $table = COUNTRIES . ' as cnt';
        $match = "cnt.use_status = '1' AND is_delete_currency = 0";
        $fields = array("cnt.country_id,cnt.country_name, cnt.currency_name, cnt.currency_code, cnt.currency_symbol, cnt.use_status");
        $data['country_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

        //Get invoice last record
        //$where                  = array('project_id'  => $this->project_id);
        //$wherestring            = '(status = 1 or status = 2)';
        $invoice_lat_record = $this->common_model->get_records(PROJECT_INVOICES, array('invoice_code'), '', '', '', '', '1', '0', 'invoice_id', 'desc', '', '', '', '', '');
        //pr($invoice_lat_record);exit;
        if (!empty($invoice_lat_record)) {
            $inv = str_split($invoice_lat_record[0]['invoice_code'], 5);
            $code = $inv[1] + 1;
            $invoice_code = 'INV00' . $code;
        } else {
            $invoice_code = 'INV001';
        }
        $data['invoice_code'] = $invoice_code;

        //Get Company Information
        $table = COMPANY_MASTER . ' as com_mst';
        $match = "com_mst.status = 1 and com_mst.is_delete = 0";
        $fields = array("com_mst.company_id, com_mst.company_name");
        $data['company_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Client Information
        $table = PROSPECT_MASTER . ' as pro';
        $match = "pro.is_delete = 0 and pro.status_type = 3 " . $client_cond;
        $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

        //Get Contact Information
        $table = CONTACT_MASTER . ' as con_mst';
        $match = "con_mst.is_delete = 0 and con_mst.status = 1  " . $contact_cond;
        $fields = array("con_mst.contact_id, con_mst.contact_name");
        $data['contact_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

        //url for filemanager
        $data['invoice_view'] = $this->module . '/Invoices';
        $data['drag'] = true;
        $this->load->view('/Invoices/Add_invoice', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function edit_record($id='') {
        
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = PROJECT_INVOICES;
            $match = "invoice_id = " . $id;

            $edit_record = $this->common_model->get_records($table, '', '', '', $match);
            //pr($edit_record);exit;
            //Get item
            $table = PROJECT_INVOICES_ITEMS;
            $match = "invoice_id = " . $id;
            $field = array('*');
            $data['item_details'] = $this->common_model->get_records($table, $field, '', '', $match);

            //Get payment
            $table = PROJECT_INVOICES_PAYMENT;
            $match = "invoice_id = " . $id;
            $field = array('*');
            $data['payment_details'] = $this->common_model->get_records($table, $field, '', '', $match);
            //pr($data['payment_details']);exit;
            //Get files
            $match = "invoice_id = " . $id;
            $field = array('invoice_file_id,invoice_id,file_name,file_path');
            $data['invoice_files'] = $this->common_model->get_records(PROJECT_INVOICES_FILES, $field, '', '', $match);
            //pr($data['invoice_files']);exit;
            //get currency
            $table = COUNTRIES . ' as cnt';
            $match = "cnt.use_status = '1' AND is_delete_currency = 0";
            $fields = array("cnt.country_id,cnt.country_name, cnt.currency_name, cnt.currency_code, cnt.currency_symbol, cnt.use_status");
            $data['country_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

            $data['edit_record'] = $edit_record;
            $data['modal_title'] = $this->lang->line('update_invoice');
            $data['submit_button_title'] = $this->lang->line('update_invoice');
        } else {
            $data['modal_title'] = $this->lang->line('create_invoice');
            $data['submit_button_title'] = $this->lang->line('create_invoice');
        }
        //Get client data
        $where = array('is_delete' => 0);
        $fields = array('prospect_name', 'prospect_id');
        $data['clients_data'] = $this->common_model->get_records(PROSPECT_MASTER, '', '', '', $where, '');

        //Get project data
        $where = array('is_delete' => 0);
        $fields = array('project_name', 'project_id');
        $data['projects_data'] = $this->common_model->get_records(PROJECT_MASTER, '', '', '', $where, '');
        //get tax
        $fields = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "");
        //Get Client Information
        $table = PROSPECT_MASTER . ' as pro';
        $match = "pro.status_type = 3";
        $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Company Information
        $table = COMPANY_MASTER . ' as com_mst';
        $match = "com_mst.status = 1";
        $fields = array("com_mst.company_id, com_mst.company_name");
        $data['company_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Contact Information
        $table = CONTACT_MASTER . ' as con_mst';
        $match = "con_mst.status = 1";
        $fields = array("con_mst.contact_id, con_mst.contact_name");
        $data['contact_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Client and Recipient Selected
        $EstClient_table = PROJECT_INVOICE_CLIENTS . ' as invClient';
        $EstClient_match = "invClient.invoice_id = " . $id;
        $EstClient_fields = array("invClient.invoice_id, invClient.prospect_id,invClient.client_name, invClient.client_type, invClient.recipient_id, invClient.recipient_type, ");
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
        //url for filemanager
        //$data['url']            = base_url ("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");

        $data['invoice_view'] = $this->module . '/Invoices';
        $data['drag'] = true;
        $this->load->view('/Invoices/Add_invoice', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function view_record($id='') {
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = PROJECT_INVOICES;
            $match = "invoice_id = " . $id;

            $edit_record = $this->common_model->get_records($table, '', '', '', $match);
            //pr($edit_record);exit;
            //Get item
            $table = PROJECT_INVOICES_ITEMS;
            $match = "invoice_id = " . $id;
            $field = array('*');
            $data['item_details'] = $this->common_model->get_records($table, $field, '', '', $match);

            //Get payment
            $table = PROJECT_INVOICES_PAYMENT;
            $match = "invoice_id = " . $id;
            $field = array('*');
            $data['payment_details'] = $this->common_model->get_records($table, $field, '', '', $match);
            //pr($data['payment_details']);exit;
            //Get files
            $match = "invoice_id = " . $id;
            $field = array('invoice_file_id,invoice_id,file_name,file_path');
            $data['invoice_files'] = $this->common_model->get_records(PROJECT_INVOICES_FILES, $field, '', '', $match);

            //get currency
            $table = COUNTRIES . ' as cnt';
            $match = "cnt.use_status = '1' AND is_delete_currency = 0";
            $fields = array("cnt.country_id,cnt.country_name, cnt.currency_name, cnt.currency_code, cnt.currency_symbol, cnt.use_status");
            $data['country_info'] = $this->common_model->get_records($table, $fields, '', '', $match);

            $data['edit_record'] = $edit_record;
            $data['modal_title'] = $this->lang->line('view_invoice');
            $data['submit_button_title'] = $this->lang->line('view_invoice');
        }
        //Get client data
        $where = array('is_delete' => 0);
        $fields = array('prospect_name', 'prospect_id');
        $data['clients_data'] = $this->common_model->get_records(PROSPECT_MASTER, '', '', '', $where, '');

        //Get project data
        $where = array('is_delete' => 0);
        $fields = array('project_name', 'project_id');
        $data['projects_data'] = $this->common_model->get_records(PROJECT_MASTER, '', '', '', $where, '');
        //get tax
        $fields = array("tax.tax_id,tax.tax_percentage");
        $data['taxes'] = $this->common_model->get_records(PRODUCT_TAX_MASTER . ' as tax', $fields, '', '', "");
        //Get Client Information
        $table = PROSPECT_MASTER . ' as pro';
        $match = "pro.status_type = 3";
        $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Company Information
        $table = COMPANY_MASTER . ' as com_mst';
        $match = "com_mst.status = 1";
        $fields = array("com_mst.company_id, com_mst.company_name");
        $data['company_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Contact Information
        $table = CONTACT_MASTER . ' as con_mst';
        $match = "con_mst.status = 1";
        $fields = array("con_mst.contact_id, con_mst.contact_name");
        $data['contact_info'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Client and Recipient Selected
        $EstClient_table = PROJECT_INVOICE_CLIENTS . ' as invClient';
        $EstClient_match = "invClient.invoice_id = " . $id;
        $EstClient_fields = array("invClient.invoice_id, invClient.prospect_id,invClient.client_name, invClient.client_type, invClient.recipient_id, invClient.recipient_type, ");
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
        //url for filemanager
        $data['invoice_view'] = $this->module . '/Invoices';
        $data['drag'] = true;
        $this->load->view('/Invoices/View_invoice', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Update is delete
      @Input  : Edit id
      @Output : Update is delete
      @Date   : 18/01/2016
     */

    public function delete_record($id='') {
        if (!empty($id)) {

            //Is delete record
            $update_data['is_delete'] = 1;
            $where = array('invoice_id' => $id);
            $this->common_model->update(PROJECT_INVOICES, $update_data, $where);

            unset($id);
            $msg = $this->lang->line('invoice_delete_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        redirect($this->module . '/Invoices'); //Redirect On Listing page
    }

    /*
      @Author : Niral Patel
      @Desc   : Delete file
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    public function delete_file($id) {
        if (!empty($id)) {

            $where = array('invoice_file_id' => $id);
            if ($this->common_model->delete(PROJECT_INVOICES_FILES, $where)) {
                echo json_encode(array('status' => 1,
                    'error' => ''));
                die;
            } else {
                echo json_encode(array('status' => 0,
                    'error' => 'Someting went wrong!'));
                die;
            }
            unset($id);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : download attachment function
      @Input    :
      @Output   :
      @Date   : 01/02/2016
     */

    function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = PROJECT_INVOICES_FILES . ' as MF';
            $params['match_and'] = 'MF.invoice_file_id=' . $id . '';
            $task_files = $this->common_model->get_records_array($params);
            if (count($task_files) > 0) {
                $pth = file_get_contents(base_url($task_files[0]['file_path'] . '/' . $task_files[0]['file_name']));
                $this->load->helper('download');
                force_download($task_files[0]['file_name'], $pth);
            }
            redirect($this->module . '/Invoices');
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Common Upload Function
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    public function upload_files($input, $path, $redirect, $file_name = NULL, $file_ext_tolower = FALSE, $encrypt_name = FALSE, $remove_spaces = FALSE, $detect_mime = TRUE) {

        $files = $_FILES;
        $FileDataArr = array();
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|png|pdf';
        $config['max_size'] = 10000;
        //$config['max_width'] = 1024;
        //$config['max_height'] = 768;
        $config['file_ext_tolower'] = $file_ext_tolower;
        $config['encrypt_name'] = $encrypt_name;
        $config['remove_spaces'] = $remove_spaces;
        $config['detect_mime'] = $detect_mime;
        if ($file_name != NULL) {
            $config['file_name'] = $file_name;
        }
        $tmpFile = count($_FILES[$input]['name']);

        if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
            for ($i = 0; $i < $tmpFile; $i++) {
                $_FILES[$input]['name'] = $files[$input]['name'][$i];
                $_FILES[$input]['type'] = $files[$input]['type'][$i];
                $_FILES[$input]['tmp_name'] = $files[$input]['tmp_name'][$i];
                $_FILES[$input]['error'] = $files[$input]['error'][$i];
                $_FILES[$input]['size'] = $files[$input]['size'][$i];

                $content = file_get_contents($_FILES[$input]['tmp_name']);
                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = 'Invalid File';
                    echo json_encode($json);
                    die;
                }
                $this->load->library('upload', $config);
                if ($this->upload->do_upload($input)) {
                    $FileDataArr[] = $this->upload->data();
                } else {
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . $this->upload->display_errors() . "</div>");
                    redirect($redirect);
                    die;
                }
            }
        }
        return $FileDataArr;
    }

    /*
      @Author : Niral Patel
      @Desc   : ajax upload file
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    public function file_upload($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        //project folder check
        $project_upload_dir = $this->config->item('project_upload_root_url') . 'ProjectInvoice';
        if (!is_dir($project_upload_dir)) {
            //create directory
            mkdir($project_upload_dir, 0777, TRUE);
        }

        $upload_dir = $this->config->item('project_upload_root_url') . 'ProjectInvoice';
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            file_put_contents($upload_dir . '/' . $filename, $str);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Show all Client as per selected Company
      @Input  :
      @Output :
      @Date   : 02/03/2016
     */

    public function show_client_related_recipients() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        }
        $company_id = $this->input->post('company_id');
        $selectedVal = $this->input->post('selectedinfo');
        if ($selectedVal == 'company') {
            $selectedCondition = " AND company_id = " . $company_id;
            $data['ShowData'] = 'RelatedToCompany';
        } elseif ($selectedVal == 'client') {
            $data['client_data'] = $company_id;
            $selectedCondition = "";
            $data['ShowData'] = 'all';
        } else {
            $data['contact_data'] = $company_id;
            $selectedCondition = "";
            $data['ShowData'] = 'all';
        }
        //blzdsk_prospect_master Client = company_id - status_type-3
        $ComClientIdArray = array();
        $ComContactArray = array();
        if (isset($selectedCondition) && $selectedCondition != "") {
            //Get Client Information as per Company ID
            $comClient_table = PROSPECT_MASTER . ' as pro';
            $comClient_match = "pro.status_type = 3 AND pro.is_delete = 0 and pro.status = 1 " . $selectedCondition;
            $comClient_fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
            $ComClient_info = $this->common_model->get_records($comClient_table, $comClient_fields, '', '', $comClient_match);
            if (count($ComClient_info) != 0) {
                foreach ($ComClient_info as $ComClientId) {
                    $ComClientIdArray[] = $ComClientId['prospect_id'];
                }
            }
            //Get Contact Information
            $comContact_table = CONTACT_MASTER . ' as con_mst';
            $comContact_match = "con_mst.status = 1 AND con_mst.is_delete = 0 " . $selectedCondition;
            $comContact_fields = array("con_mst.contact_id, con_mst.contact_name");
            $ComContact_info = $this->common_model->get_records($comContact_table, $comContact_fields, '', '', $comContact_match);
            if (count($ComContact_info) != 0) {
                foreach ($ComContact_info as $ComContactId) {
                    $ComContactArray[] = $ComContactId['contact_id'];
                }
            }
        }
        $data['CompanyClntIdArray'] = $ComClientIdArray;
        $data['CompanyContactIdArray'] = $ComContactArray;
        //Following code for show all the Records
        //Get Client Information as per Company ID
        $client_table = PROSPECT_MASTER . ' as pro';
        $client_match = "pro.status_type = 3 AND pro.is_delete = 0  AND pro.status = 1 ";
        $client_fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['client_info'] = $this->common_model->get_records($client_table, $client_fields, '', '', $client_match);
        //Get Contact Information
        $contact_table = CONTACT_MASTER . ' as con_mst';
        $contact_match = "con_mst.status = 1 AND con_mst.is_delete = 0 ";
        $contact_fields = array("con_mst.contact_id, con_mst.contact_name");
        $data['contact_info'] = $this->common_model->get_records($contact_table, $contact_fields, '', '', $contact_match);



        $this->load->view('/Invoices/invoiceShowClient', $data);
    }

    /*
      @Author   : Niral Patel
      @Desc     : Send Invoice to Recipient.
      @Input    :
      @Output   :
      @Date     : 22/03/2016
     */

    function send_invoice($id) {
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
        if (count($EstRecipientArray) != 0) {      //Get HTML from GeneratePrintPDF function
            $PDFHtml = $this->DownloadPDF($id, 'StorePDF');
            foreach ($EstRecipientArray as $EstRecipientID) {
                //Compare Id with old Contact
                $EstComtable = PROJECT_INVOICE_SEND . ' as inv';
                $EstCommatch = "inv.recipient_id = " . $EstRecipientID['recipient_id'];
                $EstComfields = array("inv.invoice_send_id, inv.recipient_id");
                $ExistingSendId = $this->common_model->get_records($EstComtable, $EstComfields, '', '', $EstCommatch);
                //Set 1 in $InstallAction variable if its not added in database
                if (count($ExistingSendId) == 0) {
                    $InstallAction = 1;
                }
                if ($EstRecipientID['recipient_type'] == 'client') {
                    //Get Client Information for Recipients
                    $RecipientClntArray['fields'] = ['cm.email, cm.contact_id, cm.contact_name'];
                    $RecipientClntArray['join_tables'] = array(CONTACT_MASTER . ' as cm' => 'cm.contact_id=orp.contact_id');
                    $RecipientClntArray['join_type'] = 'left';
                    $RecipientClntArray['table'] = OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orp';
                    $RecipientClntArray['match_and'] = 'orp.prospect_id=' . $EstRecipientID['recipient_id'] . ' and cm.is_delete = 0';
                    $RecipientCntArray = $this->common_model->get_records_array($RecipientClntArray);

                    if (!empty($RecipientCntArray) && count($RecipientCntArray) != 0) {
                        foreach ($RecipientCntArray as $RecipientInfo) {
                            $EstRecipientInfo[] = $RecipientInfo['email'];
                            if (isset($RecipientInfo['email']) && $RecipientInfo['email'] != "") {
                                $this->send_mail_to_recipient($RecipientInfo['email'], $EstRecipientID['invoice_client_id'], $invoiceInfo, $id, $RecipientInfo['contact_name']);
                            }
                        }
                    }
                }
                if ($EstRecipientID['recipient_type'] == 'contact') {
                    //Get Contact Information for Recipients
                    $table = CONTACT_MASTER . ' as con_mst';
                    $match = "con_mst.status = 1 AND contact_id = " . $EstRecipientID['recipient_id'] . ' and con_mst.is_delete = 0';
                    $fields = array("con_mst.contact_id, con_mst.contact_name, con_mst.email");
                    $RecipientCntInfo = $this->common_model->get_records($table, $fields, '', '', $match);
                    if (count($RecipientCntInfo) != 0 && !empty($RecipientCntInfo)) {
                        $EstRecipientInfo[] = $RecipientCntInfo[0]['email'];
                        if (isset($RecipientCntInfo[0]['email']) && $RecipientCntInfo[0]['email'] != "") {
                            $this->send_mail_to_recipient($RecipientCntInfo[0]['email'], $EstRecipientID['invoice_client_id'], $invoiceInfo, $id, $RecipientCntInfo[0]['contact_name']);
                        }
                    }
                }
                //Add data in PROJECT_INVOICE_SEND table if that not added 
                if (isset($InstallAction) && $InstallAction != "") {
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
            if (file_exists($pdfEstPath . $pdfEstFileName)) {
                unlink($pdfEstPath . $pdfEstFileName);
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

    function send_mail_to_recipient($recipientEmail, $invoice_client_id, $invoiceInfo, $id, $contact_name) {

        if (isset($recipientEmail) && $recipientEmail != "") {
            $pdfEstFileName = "Invoice" . $invoiceInfo[0]['invoice_code'] . ".pdf";
            $pdfEstPath = FCPATH . "uploads/projectManagement/InvoicePDF/";
            // Get Email configs from Email settings page
            $configs = getMailConfig();

            // Get Template from Template Master
            $ETMPDFtable = EMAIL_TEMPLATE_MASTER . ' as et';
            $ETMPDFmatch = "et.template_id = 40 ";
            $ETMPDFfields = array("et.subject,et.body");
            $ETMPDFInfo = $this->common_model->get_records($ETMPDFtable, $ETMPDFfields, '', '', $ETMPDFmatch);

            $content = chunk_split(base64_encode($content));
            $mailto = $recipientEmail;

            //$mailto       = 'blazedeskcrm@gmail.com';
            $subject = $ETMPDFInfo[0]['subject'];
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
            if (send_mail($mailto, $subject, $message, $pdfEstPath . $pdfEstFileName)) {

                $msg = "<div class='alert alert-success text-center'>" . lang('error') . "</div>";
            } else {


                $msg = "<div class='alert alert-danger text-center'>" . lang('send_clientinvoice') . "</div>";
            }
            $this->email->clear(TRUE);
            return $msg;
            //echo $msg;
            /* } */
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

    function DownloadPDF($id, $section = NULL) {
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
                $preview_client_array['name'] = $get_pre_client_data[0]['CompanyName'];
                $preview_client_array['CompanyName'] = $get_pre_client_data[0]['CompanyName'];
                $preview_client_array['image'] = "";
            }
        }
        $data['PreviewClientInformation'] = $preview_client_array; //Push Client, Contact and Company information
        //Get All Product information
        $preview_items['fields'] = ['*'];
        $preview_items['join_tables'] = array(PRODUCT_TAX_MASTER . ' as TM' => 'TM.tax_id=EP.tax_rate');
        $preview_items['join_type'] = 'left';
        $preview_items['table'] = PROJECT_INVOICES_ITEMS . ' as EP';
        $preview_items['match_and'] = 'EP.invoice_id=' . $id;
        $preview_items['orderby'] = 'EP.invoice_item_id';
        $preview_items['sort'] = 'ASC';
        $data['previewAllItems'] = $this->common_model->get_records_array($preview_items);

        $preview_product['fields'] = ['*,cunt.currency_symbol'];
        $preview_product['join_tables'] = array(COUNTRIES . ' as cunt' => 'cunt.country_id=EP.amount_per');
        $preview_product['join_type'] = 'left';
        $preview_product['table'] = PROJECT_INVOICES_PAYMENT . ' as EP';
        $preview_product['match_and'] = 'EP.invoice_id=' . $id;
        $preview_product['orderby'] = 'EP.invoice_payment_id';
        $preview_product['sort'] = 'ASC';
        $data['previewAllPayment'] = $this->common_model->get_records_array($preview_product);


        //Get Company Logo for Preview
        $preview_logo['fields'] = ['cnf.value'];
        $preview_logo['table'] = CONFIG . ' as cnf';
        $preview_logo['match_and'] = 'cnf.config_key="general_settings"';
        $data['PreBZCompanyInfo'] = $this->common_model->get_records_array($preview_logo);

        //Get Invoice created user name
        $inv_login_info = array();
        if (!empty($data['editRecord'][0]['created_by']) && isset($data['editRecord'][0]['created_by']) && $data['editRecord'][0]['created_by'] != "") {
            $preview_login_usr['fields'] = ['role.role_name,lgn.login_id, lgn.firstname, lgn.lastname, lgn.telephone1, lgn.telephone2, lgn.email'];
            $preview_login_usr['table'] = LOGIN . ' as lgn';
            $preview_login_usr['match_and'] = 'lgn.login_id=' . $data['editRecord'][0]['login_id'];
            $preview_login_usr['join_tables'] = array(ROLE_MASTER . ' as role' => 'role.role_id=lgn.user_type');
            $preview_login_usr['join_type'] = 'left';
            $inv_login_info = $this->common_model->get_records_array($preview_login_usr);
        }
        $data['PreviewLoginInfo'] = $inv_login_info;

        //Get Invoice created user name    
        $PDF_cunt_array = array();
        if (!empty($data['editRecord'][0]['currency']) && isset($data['editRecord'][0]['currency']) && $data['editRecord'][0]['currency'] != "") {
            $cunt_PDF_data['fields'] = ['cunt.*'];
            $cunt_PDF_data['table'] = COUNTRIES . ' as cunt';
            $cunt_PDF_data['match_and'] = 'cunt.country_id=' . $data['editRecord'][0]['currency'];
            $PDF_cunt_array = $this->common_model->get_records_array($cunt_PDF_data);
        }
        $data['PDF_cunt_array'] = $PDF_cunt_array;

        $data['main_content'] = '/Invoices/files/invoice_pdf';
        $data['section'] = $section;

        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdf_file_name = "Invoice" . $data['editRecord'][0]['invoice_code'] . ".pdf";
        $pdf_file_path = FCPATH . 'uploads/projectManagement/InvoicePDF/';
        //Invoice Header Data Array
        $PDF_BZ_information = array();
        $BZ_query_data['fields'] = ['cnf.value'];
        $BZ_query_data['table'] = CONFIG . ' as cnf';
        $BZ_query_data['match_and'] = 'cnf.config_key="general_settings"';
        $BZ_company_info = $this->common_model->get_records_array($BZ_query_data);

        $BZ_com_info_array = array(json_decode($BZ_company_info[0]['value']));
        //Get Dynamic Country name as per 
        if (!empty($BZ_com_info_array) && isset($BZ_com_info_array[0]->country_id) && $BZ_com_info_array[0]->country_id != "") {
            $BZ_cnt_name['fields'] = ['conh.country_name'];
            $BZ_cnt_name['table'] = COUNTRIES . ' as conh';
            $BZ_cnt_name['match_and'] = 'conh.country_id=' . $BZ_com_info_array[0]->country_id;
            $BZ_cnt_name = $this->common_model->get_records_array($BZ_cnt_name);
            $PDF_BZ_information['country_name'] = $BZ_cnt_name[0];
        }
        $PDF_BZ_information['BZCompanyInfo'] = $BZ_com_info_array;
        if ($section == 'StorePDF') {
            $PDF_header_HTML = $this->load->view('/Invoices/files/invoice_pdf_header', $PDF_BZ_information, true);
            //Invoice Footer View
            $PDF_footer_HTML = $this->load->view('/Invoices/files/invoice_pdf_footer', $PDF_BZ_information, true);
            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->SetHTMLHeader($PDF_header_HTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDF_footer_HTML);
            $this->m_pdf->pdf->WriteHTML($html);
            //Store PDF in Invoice Folder
            $store_PDF = $this->m_pdf->pdf->Output($pdf_file_path . $pdf_file_name, 'F');
            return $pdf_file_name;
        } elseif ($section == 'print') {
            $html;
        } else {
            //Pass BZ information In 
            $PDF_header_HTML = $this->load->view('/Invoices/files/invoice_pdf_header', $PDF_BZ_information, true);
            //Invoice Footer View
            $PDF_footer_HTML = $this->load->view('/Invoices/files/invoice_pdf_footer', $PDF_BZ_information, true);
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
