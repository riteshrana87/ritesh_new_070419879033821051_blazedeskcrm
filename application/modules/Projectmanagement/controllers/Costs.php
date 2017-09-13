<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Costs extends CI_Controller {

    public $data;

    function __construct() {
        parent::__construct();
        $this->module = 'Projectmanagement';
        $this->viewname = $this->router->fetch_class();
        check_project();
        $this->project_id = $this->session->userdata('PROJECT_ID');
    }

    /*
      @Author : Maulik Suthar
      @Desc   : listing of Cost Data with pagination
      @Input 	:
      @Output	:
      @Date   : 29/01/2016
     */

    public function index($page = '') {

        /*  $master_user_id = $this->config->item('master_user_id');
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
//        $data['footerJs'] = array(base_url('uploads/custom/js/projectmanagement/moment.js'), base_url('uploads/custom/js/projectmanagement/fullcalendar.js'), base_url('uploads/custom/js/jquery.blockUI.js'));
//        $data['headerCss'] = array(base_url('uploads/custom/css/projectmanagement/fullcalendar.css'));
        //Get uri segment for pagination
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $searchtext = '';
        $perpage = '';
        $where = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $data['groupFieldName'] = $groupFieldName = $this->input->post('groupFieldName');
        $data['groupFieldData'] = $groupFieldData = $this->input->post('groupFieldData');
        $data['header'] = array('menu_module' => 'Projectmanagement');

        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('costpaging_data');
        }
        $data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
        $searchsort_session = $this->session->userdata('costpaging_data');
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
                $sortfield = 'cost_id';
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
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url($this->module . '/' . $this->viewname . '/index');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = $cur_uri_segment;
            $uri_segment = $page;
        }
        $group_by = 'CM.cost_id';
        //Query
        $join_tables = array(COST_SUPPLIER_ASSIGNS . ' as CSA' => 'CSA.cost_id=CM.cost_id', SUPPLIER_MASTER . ' as SM' => 'SM.supplier_id=CSA.supplier_id');
        $params['join_type'] = 'left';
        $fields = array('cost_id' => 'CM.cost_id', 'cost_name' => 'CM.cost_name', 'CM.cost_type' => 'CM.cost_type', 'CM.cost_type' => 'CM.cost_type', 'CM.status' => 'CM.status', 'CM.ammount' => 'CM.ammount', 'group_concat(SM.supplier_name) as supplier_name' => 'group_concat(SM.supplier_name) as supplier_name');
        $table = COST_MASTER . ' as CM';
        $where['CM.project_id'] = $this->project_id;
        $where['CM.is_delete'] = 0;
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim($searchtext));
            $match = ''; // array('CM.cost_name' => $searchtext, 'CM.cost_type' => $searchtext, 'CM.status' => $searchtext, 'CM.ammount' => $searchtext, 'SM.supplier_name' => $searchtext);
            $where_search = '(CM.cost_name LIKE "%' . $searchtext . '%" OR CM.cost_type LIKE "%' . $searchtext . '%" OR CM.ammount LIKE "%' . $searchtext . '%" OR SM.supplier_name LIKE "%' . $searchtext . '%" )';
            if (!empty($groupFieldName) && !empty($groupFieldData)) {
                for ($i = 0; $i < count($groupFieldName); $i++) {
                    if (strlen($groupFieldData[$i]) > 0):
                        $where[$groupFieldName[$i]] = $groupFieldData[$i];
                    endif;
                }
            }
            $data['cost_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);
        } else {
            $match = '';

            if (!empty($groupFieldName) && !empty($groupFieldData)) {
                for ($i = 0; $i < count($groupFieldName); $i++) {
                    $where[$groupFieldName[$i]] = $groupFieldData[$i];
                }
            }
            $data['cost_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
        }
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
        $this->session->set_userdata('costpaging_data', $sortsearchpage_data);
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');

        //$data['sales_view'] = $this->viewname;
        //$data['facebook_count'] = $this->facebook_count();
        //$data['twiiter_count'] = $this->get_twitter_follower_count();
        //pr($data['pagination']);exit;
        //$data['popup'] = $this->load->view($this->viewname.'/Add',$data);
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $data['sales_view'] = $data['project_view'];

        //pr($data['today_task']);exit;
        //get today`s activity
        $table_act = PROJECT_ACTIVITIES . ' as pa';
        $fields_act = array('pa.*,CONCAT(l.firstname, " ", l.lastname) AS user_name');
        $join_table_act = array(LOGIN . ' as l' => 'pa.user_id=l.login_id and l.is_delete = 0');
        $where_act = array('project_id' => $this->project_id);
        $data['activities_total'] = $this->common_model->get_records($table_act, $fields_act, $join_table_act, 'left', $where_act, '', '', '', 'activity_id', 'desc', '', '', '', '', '1');
        $data['activities'] = $this->common_model->get_records($table_act, $fields_act, $join_table_act, 'left', $where_act, '', 5, 0, 'activity_id', 'desc', '');


        if ($this->input->is_ajax_request()) {
            if ($this->input->post('project_ajax')) {
                $this->load->view('/' . $this->viewname . '/Costs', $data);
            } else {
                $this->load->view($this->viewname . '/AjaxCosts', $data);
            }
        } else {
            //$data['main_content'] = '/SalesCampaign';
            $data['main_content'] = '/Costs/' . $this->viewname;
            $data['js_content'] = '/loadJsFiles';
            $data['drag'] = true;
            //$this->parser->parse('layouts/CampaignTemplate', $data);
            $this->parser->parse('layouts/ProjectmanagementTemplate', $data);
            // $this->parser->parse('layouts/ProspectTemplate', $data);
        }
    }

    //  $this->listCostsData();


    public function listCostsData() {
        $data['main_content'] = '/' . $this->viewname . '/Costs';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $data['js_content'] = '/' . $this->viewname . '/loadJsFiles';
        $this->load->library('pagination');
        $data['SortDefault'] = '<i class="fa fa-sort"></i>';
        $data['SortAsc'] = '<i class="fa fa-sort-desc"></i>';
        $data['SortDesc'] = '<i class="fa fa-sort-asc"></i>';
        $dbSearch = "";
        if ($this->input->get('search') != '') {
            $data['search'] = $term = $this->input->get('search');

            $searchFields = array('CM.cost_name', 'CM.cost_type', 'CM.status', 'CM.ammount', 'SM.supplier_name');
            foreach ($searchFields as $fields):
                $dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
            endforeach;
            $dbSearch = substr($dbSearch, 0, -3);
        }
        $params['join_tables'] = array(SUPPLIER_MASTER . ' as SM' => 'SM.supplier_id=CM.supplier_id');
        $params['join_type'] = 'left';
        $fields = array('cost_id' => 'CM.cost_id', 'cost_name' => 'CM.cost_name', 'CM.cost_type' => 'CM.cost_type', 'CM.cost_type' => 'CM.cost_type', 'CM.status' => 'CM.status', 'CM.ammount' => 'CM.ammount', 'SM.supplier_name' => 'SM.supplier_name');
        $config['total_rows'] = count($this->common_model->get_records(COST_MASTER . ' as CM', $fields, $params['join_tables'], $params['join_type'], $dbSearch, ''));
        $config['base_url'] = site_url($data['project_view'] . '/index');
        $config['per_page'] = RECORD_PER_PAGE;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        $data['sortField'] = 'cost_id';
        $data['sortOrder'] = 'desc';
        if ($this->input->get('orderField') != '') {
            $data['sortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['sortOrder'] = $this->input->get('sortOrder');
        }
        $data['page'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['cost_data'] = $this->common_model->get_records(COST_MASTER . ' as CM', $fields, $params['join_tables'], $params['join_type'], $dbSearch, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder']);
        $page_url = $config['base_url'] . '/' . $data['page'];
        $data['pagination'] = $this->pagingConfig($config, $page_url);
        $data['status'] = array('1' => 'Paid', '0' => 'Unpaid');
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('project_ajax')) {
                $this->load->view('/' . $this->viewname . '/Costs', $data);
            } else {
                $this->load->view($this->viewname . '/AjaxCosts', $data);
            }
        } else {
            $this->parser->parse('layouts/ProjectmanagementTemplate', $data);
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Insertion of Cost Data
      @Input 	:
      @Output	:
      @Date   : 29/01/2016
     */

    public function saveCostData() {
        $id = 0;

        if ($this->input->post('cost_id')) {
            $id = $this->input->post('cost_id');
        }
//        if (!validateFormSecret()) {
//            //   $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
//            redirect($this->module . '/Costs'); //Redirect On Listing page
//        }
        $data['main_content'] = '/' . $this->viewname . '/Costs';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        /*
         * post variables
         */
        $cost_data['cost_name'] = $this->input->post('cost_name');
        $cost_data['cost_code'] = $this->input->post('cost_code');
        $cost_data['task_id'] = $this->input->post('task_id');
        $cost_data['project_id'] = $this->project_id;
        $cost_data['created_date'] = $this->input->post('created_date');
        $cost_data['user_id'] = $this->input->post('user_id');
        $cost_data['start_date'] = date('Y-m-d', strtotime($this->input->post('start_date')));
        $cost_data['cost_type'] = $this->input->post('cost_type');
        $cost_data['due_date'] = date('Y-m-d', strtotime($this->input->post('due_date')));
        $cost_data['within_project'] = ($this->input->post('within_project')) ? $this->input->post('expense_supplier') : 0;
        $cost_data['ammount'] = $this->input->post('amount');
        $cost_data['product_id'] = $this->input->post('product_id');
        $cost_data['expense_supplier'] = ($this->input->post('expense_supplier')) ? $this->input->post('expense_supplier') : 0;
        $cost_data['description'] = $this->input->post('description');
        // $cost_data['supplier_id'] = $this->input->post('supplier_id');
        $cost_data['status'] = 1;
        if ($id > 0) { //update
            $cost_data['modified_date'] = datetimeformat();
            $where = array('cost_id' => $id);
            if ($this->common_model->update(COST_MASTER, $cost_data, $where)) {
                $msg = $this->lang->line('cost_update_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                $msg = $this->lang->line('BUDGET_CAMPAIGN_FAIL_MSG');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        } else {

            $cost_data['created_date'] = $this->input->post('created_date');
            $id = $this->common_model->insert(COST_MASTER, $cost_data);
            if ($id > 0) {
                $msg = $this->lang->line('cost_add_success_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($this->module . '/Costs'); //Redirect On Listing page
            }
        }
        /*
         * supplier date store code starts
         */
        $supplier_id = $this->input->post('supplier_id');
        if (count($supplier_id) > 0) {
            foreach ($supplier_id as $ids) {
                $supplierData[] = ['cost_id' => $id, 'supplier_id' => $ids];
            }
        }
        $this->common_model->delete(COST_SUPPLIER_ASSIGNS, "cost_id=" . $id);
        if (count($supplierData) > 0) {
            $this->common_model->insert_batch(COST_SUPPLIER_ASSIGNS, $supplierData);
        }
        /* custom uplod code starts
         * 
         */
        /* Upload code */
        $project_upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id;
        if (!is_dir($project_upload_dir)) {
            //create directory
            mkdir($project_upload_dir, 0777, TRUE);
        }
        $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('cost_folder');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            $file_name = array();
            $file_array1 = $this->input->post('file_data');

            $file_name = $_FILES['cost_files']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['cost_files']['name'][$key]);
                        unset($_FILES['cost_files']['type'][$key]);
                        unset($_FILES['cost_files']['tmp_name'][$key]);
                        unset($_FILES['cost_files']['error'][$key]);
                        unset($_FILES['cost_files']['size'][$key]);
                    }
                }
            }
            $_FILES['cost_files'] = $arr = array_map('array_values', $_FILES['cost_files']);
            /* ends
             *
             */
            $file_path = $this->config->item('project_upload_path') . 'Project0' . $this->project_id . '/' . $this->config->item('cost_folder') . '/';
            $uploadData = uploadImage('cost_files', $file_path, $data['project_view']);


            /* ritesh code */
            //
            $Marketingfiles = array();

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
                    array_push($uploadData, array('file_name' => $img));
                }
            }

            /* end
             * 
             */
            $costFiles = array();

            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $cost_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $costFiles[] = ['file_name' => $cost_files[$i], 'file_path' => $gallery_path[$i], 'cost_id' => $id, 'upload_status' => 0, 'created_date' => $this->input->post('created_date'), 'upload_status' => 1];
                    }
                }
            }
            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => $file_path, 'cost_id' => $id, 'upload_status' => 0, 'created_date' => $this->input->post('created_date')];
                }
            }


            if (count($costFiles) > 0) {
                $where = array('cost_id' => $id);
                //  $this->common_model->delete(COST_FILES, $where);
                if (!$this->common_model->insert_batch(COST_FILES, $costFiles)) {


                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                    redirect($this->module . '/Costs'); //Redirect On Listing page
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
                $this->common_model->delete(COST_FILES, 'cost_file_id IN(' . $dlStr . ')');
            }
            /*
             * SOFT DELETION CODE ENDS
             */
        }
        redirect($data['project_view']); //Redirect On Listing page
    }

    /*
      @Author : Ritesh Rana
      @Desc   : upload drag and drop file
      @Input 	:
      @Output	:
      @Date   : 04/03/2016
     */

    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        //project folder check
        $project_upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id;
        if (!is_dir($project_upload_dir)) {
            //create directory
            mkdir($project_upload_dir, 0777, TRUE);
        }

        $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('cost_folder');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            file_put_contents($upload_dir . '/' . $filename, $str);
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Add View of Cost Data
      @Input 	:
      @Output	:
      @Date   : 29/01/2016
     */

//
    function add() {
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        $data['project_view'] = $this->module . '/Costs/';
        $data['sales_view'] = $data['project_view'];

        $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");
        $data['action'] = $this->module . '/Costs/saveCostData';
        //Get user
        /* $where = array('status' => 1);
          $data['res_user']      = $this->common_model->get_records(LOGIN,'','','',$where,''); */
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('l.is_delete' => 0, 'project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
        $supplier_where = array('is_delete' => 0,);
        $data['supplier'] = $this->common_model->get_records(SUPPLIER_MASTER, '', '', '', $supplier_where, '');
        $task_where = array('is_delete' => 0, 'project_id' => $this->project_id);
        $data['tasks'] = $this->common_model->get_records(PROJECT_TASK_MASTER, '', '', '', $task_where, '');
        $simpleProduct['fields'] = ['*'];
        $simpleProduct['table'] = PRODUCT_MASTER . ' as PM';
        $simpleProduct['match_and'] = 'is_delete="0" and status=1';
        $data['products'] = $this->common_model->get_records_array($simpleProduct);

        $this->load->view('/Costs/Add', $data);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Edit view of Cost Data
      @Input 	:
      @Output	:
      @Date   : 29/01/2016
     */

    function edit($id) {
        
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        $data['project_view'] = $this->module . '/Costs';
        $data['sales_view'] = $data['project_view'];
        $data['action'] = $this->module . '/Costs/saveCostData';
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = COST_MASTER . ' as CM';
            $params['match_and'] = 'CM.cost_id=' . $id . '';
            $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");
            $data['edit_record'] = $this->common_model->get_records_array($params);
            $params['fields'] = ['*'];
            $params['table'] = COST_FILES . ' as CM';
            $params['match_and'] = 'CM.cost_id=' . $id . '';
            $data['cost_files'] = $this->common_model->get_records_array($params);
//Get user
            /* $where = array('status' => 1);
              $data['res_user']      = $this->common_model->get_records(LOGIN,'','','',$where,''); */
            $table = PROJECT_ASSIGN_MASTER . ' as pa';
            $fields = array('l.*');
            $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
            $where = array('l.is_delete' => 0, 'project_id' => $this->project_id);
            $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
            $supplier_where = array('is_delete' => 0,);

            $data['supplier'] = $this->common_model->get_records('supplier_master', '', '', '', $supplier_where, '');

            $task_where = array('is_delete' => 0, 'project_id' => $this->project_id);
            $data['tasks'] = $this->common_model->get_records(PROJECT_TASK_MASTER, '', '', '', $task_where, '');
            $simpleProduct['fields'] = ['*'];
            $simpleProduct['table'] = PRODUCT_MASTER . ' as PM';
            $simpleProduct['match_and'] = 'is_delete="0" and status=1';
            $data['products'] = $this->common_model->get_records_array($simpleProduct);
            $supplierData['fields'] = ['*'];
            $supplierData['table'] = COST_SUPPLIER_ASSIGNS . ' as CSA';
            $supplierData['match_and'] = 'cost_id=' . $id;
            $suppliers = $this->common_model->get_records_array($supplierData);
            $supplierIds = array();
            if (count($suppliers) > 0) {
                foreach ($suppliers as $sup) {
                    $supplierIds[] = $sup['supplier_id'];
                }
            }
            $data['supplier_ids'] = $supplierIds;

            if (count($data['edit_record']) > 0) {
                $data['id'] = $id;
                $this->load->view('/Costs/Edit', $data);
            } else {
                $msg = lang(cost_error_notfound);
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect($data['project_view']);
            }
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : view of Cost Data
      @Input 	:
      @Output	:
      @Date   : 29/01/2016
     */

    function view($id) {
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        $data['project_view'] = $this->module . '/Costs';
        $data['action'] = $this->module . '/Costs/saveCostData';
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = COST_MASTER . ' as CM';
            $params['match_and'] = 'CM.cost_id=' . $id . '';
            $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/&modal=true");
            $data['edit_record'] = $this->common_model->get_records_array($params);
            $params['fields'] = ['*'];
            $params['table'] = COST_FILES . ' as CM';
            $params['match_and'] = 'CM.cost_id=' . $id . '';
            $data['cost_files'] = $this->common_model->get_records_array($params);
            $where = array('status' => 1);
            $data['res_user'] = $this->common_model->get_records('login', '', '', '', $where, '');
            $supplier_where = array('status' => 1);
            $data['supplier'] = $this->common_model->get_records('supplier_master', '', '', '', $supplier_where, '');
            $task_where = array('is_delete' => 0, 'project_id' => $this->project_id);
            $data['tasks'] = $this->common_model->get_records(PROJECT_TASK_MASTER, '', '', '', $task_where, '');
            $supplierData['fields'] = ['*'];
            $supplierData['table'] = COST_SUPPLIER_ASSIGNS . ' as CSA';
            $supplierData['match_and'] = 'cost_id=' . $id;
            $supplierData['join_tables'] = array(SUPPLIER_MASTER . ' as SM' => 'SM.supplier_id=CSA.supplier_id');
            $supplierData['join_type'] = 'inner';
            $suppliers = $this->common_model->get_records_array($supplierData);
            $simpleProduct['fields'] = ['*'];
            $simpleProduct['table'] = PRODUCT_MASTER . ' as PM';
            $simpleProduct['match_and'] = 'is_delete="0" and status=1';
            $data['products'] = $this->common_model->get_records_array($simpleProduct);
            $supplierIds = array();
//            if (count($suppliers) > 0) {
//                foreach ($suppliers as $sup) {
//                    $supplierIds[] = $sup['supplier_id'];
//                }
//            }
            $data['supplier_data'] = $suppliers;
            if (count($data['edit_record']) > 0) {
                $data['id'] = $id;
                $this->load->view('/Costs/View', $data);
            } else {
                $msg = lang(cost_error_notfound);
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect($data['project_view']);
            }
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Delete Function for Cost Data
      @Input 	:
      @Output	:
      @Date   : 1/02/2016
     */

    public function delete($id) {
        //Delete Record From Database
        if (!empty($id)) {

            // $where = array('cost_id' => $id);
            // $this->common_model->delete(COST_FILES, $where);
            $where = array('cost_id' => $id);
            // $this->common_model->delete(COST_MASTER, $where);
            $this->common_model->update(COST_MASTER, array('is_delete' => 1), $where);
            unset($id);
            redirect($this->module . '/Costs'); //Redirect On Listing page
        } else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect($this->module . '/Costs'); //Redirect On Listing page
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Delete image
      @Input 	:
      @Output	:
      @Date   : 02/02/2016
     */

    public function deleteImage($id) {
        //Delete Record From Database
        if (!empty($id)) {
            $where = array('cost_file_id' => $id);
            $params['fields'] = ['*'];
            $params['table'] = COST_FILES . ' as CM';
            $params['match_and'] = 'cost_file_id=' . $id . '';
            $fileData = $this->common_model->get_records_array($params);
            if (count($fileData) > 0) {
                if ($fileData[0]['upload_status'] == 0) {
                    unlink(BASEPATH . '../' . $fileData[0]['file_path'] . '/' . $fileData[0]['file_name']);
                }
                if ($this->common_model->delete(COST_FILES, $where)) {
                    echo json_encode(array('status' => 1, 'error' => ''));
                    die;
                } else {
                    echo json_encode(array('status' => 0, 'error' => 'Someting went wrong!'));
                    die;
                }
            }

            unset($id);
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : download attachment function
      @Input 	:
      @Output	:
      @Date   : 01/02/2016
     */

    function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = COST_FILES . ' as CM';
            $params['match_and'] = 'CM.cost_file_id=' . $id . '';
            $cost_files = $this->common_model->get_records_array($params);
            if (count($cost_files) > 0) {
                $pth = file_get_contents(base_url($cost_files[0]['file_path'] . '/' . $cost_files[0]['file_name']));
                $this->load->helper('download');
                force_download($cost_files[0]['file_name'], $pth);
            }
            redirect($this->module . '/Costs');
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : dragDropImgSave function
      @Input 	:
      @Output	:
      @Date   : 01/02/2016
     */

    public function dragDropImgSave($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('Costs_img_url') . $filename, $str);
    }

}
