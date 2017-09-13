<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ProjectIncidents extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_project();
        $this->module = 'Projectmanagement';
        $this->viewname = $this->router->fetch_class();
        $this->user_info = $this->session->userdata('LOGGED_IN');  //Current Login information
        $this->project_id = $this->session->userdata('PROJECT_ID');
    }

    /*
      @Author : Niral Patel
      @Desc   : ProjectIncidents index
      @Input  :
      @Output :
      @Date   : 3/3/2016
     */

    public function index($page = '') {
        /* $master_user_id = $this->config->item('master_user_id');
          //$master_user_id = $data['user_info']['ID'];
          $table_user = LOGIN.' as lg';
          $where_user = array("lg.login_id" => $master_user_id);
          $fields_user = array("lg.*");
          $check_user_data = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);

          $table = SETUP_MASTER.' as ct';
          $where = "ct.login_id = ".$master_user_id." AND ct.email = '".$check_user_data[0]['email']."'";
          $fields = array("ct.*");
          $check_user_menu = $this->common_model->get_records_data($table,$fields,'','','','','','','','','',$where);

          // pr($check_user_menu);exit;
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

        //Get uri segment for pagination
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $data['header'] = array('menu_module' => 'Projectmanagement');
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
            $this->session->unset_userdata('incidentspaging_data');
        }
        $searchsort_session = $this->session->userdata('incidentspaging_data');
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
                $sortfield = 'incident_id';
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
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = $cur_uri_segment;
            $uri_segment = $page;
        }

        $data['project_incident_view'] = $this->module . '/' . $this->viewname;

        //Get Records From PROJECT_MASTER Table  

        $dbSearch = "";
        if (!empty($searchtext)) {
            $searchFields = array('pa.description',
                'pa.title',
                'pt.incident_type_name', 'l.firstname', 'l.lastname');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr($dbSearch, 0, -3) . ')';
        }
        $table = PROJECT_INCIDENTS . ' as pa';
        $fields = array('pa.*,pt.incident_type_name,concat(l.firstname," ",l.lastname) as responsible_name');
        $join_table = array(PROJECT_INCIDENTS_TYPE . ' as pt' => 'pa.type_id=pt.incident_type_id', LOGIN . ' as l' => 'pa.responsible=l.login_id');
        $where = array('pt.is_delete' => 0, 'pa.is_delete' => 0, 'pa.project_id' => $this->project_id);
        $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'incident_id', 'desc', '', $dbSearch, '', '', '1');

        //Get Records From MILESTONE_MASTER Table    
        $data['incidents_data'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $dbSearch);

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
        $this->session->set_userdata('incidentspaging_data', $sortsearchpage_data);
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $data['drag'] = true;
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('project_ajax')) {
                $this->load->view('/' . $this->viewname . '/ProjectIncidents', $data);
            } else {
                $this->load->view('/' . $this->viewname . '/IncidentsAjaxList', $data);
            }
        } else {
            $data['main_content'] = '/ProjectIncidents/' . $this->viewname;
            $data['js_content'] = '/loadJsFiles';
            $this->parser->parse('layouts/ProjectmanagementTemplate', $data);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert/update data
      @Input  : Post data/Update id
      @Output : Insert/update data
      @Date   : 3/3/2016
     */

    public function insertdata() {
        //pr($_POST);exit;

        if ($this->input->post('incident_id')) {
            $id = $this->input->post('incident_id');
        }
        //$display = $this->input->post ('display');
        $redirect_link = $this->input->post('redirect_link');
        if (!validateFormSecret()) {
            redirect($redirect_link); //Redirect On Listing page
        }
        $insert_data['project_id'] = $this->project_id;
        $insert_data['title'] = ucfirst($this->input->post('title'));
        $insert_data['type_id'] = $this->input->post('type_id');
        $insert_data['business_cases'] = $this->input->post('business_cases');
        $insert_data['business_subject'] = $this->input->post('business_subject');
        $insert_data['responsible'] = $this->input->post('responsible');
        $insert_data['deadline'] = date_format(date_create($this->input->post('deadline')), 'Y-m-d');
        $insert_data['incident_status'] = $this->input->post('incident_status');

        $insert_data['description'] = $this->input->post('description', FALSE);
        $insert_data['status'] = 1;

        //Insert Record in Database
        if (!empty($id)) { //update
            $insert_data['modified_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['modified_date'] = datetimeformat();
            $where = array('incident_id' => $id);
            $success_update = $this->common_model->update(PROJECT_INCIDENTS, $insert_data, $where);
            $msg = $this->lang->line('project_incidents_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

            if (strpos($redirect_link, 'Contact/view') !== false) {
                $sess_array = array('setting_current_tab' => 'Cases');
                $this->session->set_userdata($sess_array);
                $this->session->set_flashdata('message', $msg);
            }
        } else { //insert
            $insert_data['created_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['created_date'] = datetimeformat();
            $id = $this->common_model->insert(PROJECT_INCIDENTS, $insert_data);
            $msg = $this->lang->line('project_incidents_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

            if (strpos($redirect_link, 'Contact/view') !== false) {
                $sess_array = array('setting_current_tab' => 'Cases');
                $this->session->set_userdata($sess_array);
                $this->session->set_flashdata('message', $msg);
            }
        }
        //Upload data
        $project_upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id;
        if (!is_dir($project_upload_dir)) {
            //create directory
            mkdir($project_upload_dir, 0777, TRUE);
        }
        $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('project_incidents_folder');
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
            $file_path = $this->config->item('project_upload_path') . 'Project0' . $this->project_id . '/' . $this->config->item('project_incidents_folder') . '/';
            ;
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
            //pr($upload_data);exit;
            /* end */

            $upload_file = array();
            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $add_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $upload_file[] = ['file_name' => $add_files[$i],
                            'file_path' => $gallery_path[$i],
                            'incident_id' => $id,
                            'created_date' => datetimeformat(),
                            'upload_status' => 1];
                    }
                }
            }
            if (count($upload_data) > 0) {
                foreach ($upload_data as $files) {
                    $upload_file[] = ['file_name' => $files['file_name'],
                        'file_path' => $file_path,
                        'incident_id' => $id,
                        'upload_status' => 0,
                        'created_date' => datetimeformat()];
                }
            }

            if (count($upload_file) > 0) {
                $this->common_model->insert_batch(PROJECT_INCIDENTS_FILES, $upload_file);
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
            $this->common_model->delete(PROJECT_INCIDENTS_FILES, 'incident_file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        //end upload
        //redirect ($this->module . '/ProjectIncidents');
        //updated by sanket on 23-03-2016
        redirect($redirect_link);
    }

    /*
      @Author : Niral Patel
      @Desc   : Add record
      @Input  : Add id
      @Output : Give record
      @Date   : 18/01/2016
     */

    public function add_record($view = '') {
        if (!empty($view)) {
            $data['home'] = '1';
        } else {
            $data['home'] = '';
        }
        $data['modal_title'] = $this->lang->line('create_project_incident');
        $data['submit_button_title'] = $this->lang->line('create_project_incident');

        //Get project incidents type
        $where = array('is_delete' => 0);
        $data['type_data'] = $this->common_model->get_records(PROJECT_INCIDENTS_TYPE, '', '', '', $where, '', '', '', 'incident_type_id', 'desc', '');

        //Get project assign user
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('l.is_delete' => 0, 'project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
        $data['edit_record'] = $data['res_user'];
        $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");

        //url for filemanager
        $data['project_incident_view'] = $this->module . '/ProjectIncidents';
        $this->load->view('/ProjectIncidents/Add_incidents', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function edit_record($id = '') {
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = PROJECT_INCIDENTS;
            $match = "incident_id = " . $id;

            $edit_record = $this->common_model->get_records($table, '', '', '', $match);
            $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");

            $data['id'] = $id;
            $data['edit_record'] = $edit_record;
            //Get project incidents file
            $match = "incident_id = " . $id;
            $field = array('incident_file_id,incident_id,upload_status,file_name,file_path');
            $data['incidentds_files'] = $this->common_model->get_records(PROJECT_INCIDENTS_FILES, $field, '', '', $match);

            $data['modal_title'] = $this->lang->line('update_project_incident');
            $data['submit_button_title'] = $this->lang->line('update_project_incident');
        }
        //Get project incidents type
        $where = array('is_delete' => 0);
        $data['type_data'] = $this->common_model->get_records(PROJECT_INCIDENTS_TYPE, '', '', '', $where, '', '', '', 'incident_type_id', 'desc', '');

        //Get project assign user
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('l.is_delete' => 0, 'project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');


        $data['project_incident_view'] = $this->module . '/ProjectIncidents';
        $this->load->view('/ProjectIncidents/Add_incidents', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function view_record($id = '') {
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = PROJECT_INCIDENTS;
            $match = "incident_id = " . $id;

            $table = PROJECT_INCIDENTS . ' as pa';
            $fields = array('pa.*,pt.incident_type_name,concat(l.firstname," ",l.lastname) as responsible_name');
            $join_table = array(PROJECT_INCIDENTS_TYPE . ' as pt' => 'pa.type_id=pt.incident_type_id', LOGIN . ' as l' => 'pa.responsible=l.login_id');
            $where = array('pa.project_id' => $this->project_id);
            $edit_record = $this->common_model->get_records($table, $fields, $join_table, 'left', $match);
            //Get project incidet file
            $match = "incident_id = " . $id;
            $field = array('incident_file_id,incident_id,upload_status,file_name,file_path');
            $data['incidentds_files'] = $this->common_model->get_records(PROJECT_INCIDENTS_FILES, $field, '', '', $match);

            $data['id'] = $id;
            $data['edit_record'] = $edit_record;
            $data['modal_title'] = $this->lang->line('view_project_incident');
        }

        $data['project_incident_view'] = $this->module . '/ProjectIncidents';
        $this->load->view('/ProjectIncidents/View_incidents', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Update is delete
      @Input  : Edit id
      @Output : Update is delete
      @Date   : 18/01/2016
     */

    public function delete_record() {

        //$id = $this->uri->segment ('4');
        $id = $this->input->get('incident_id');
        $redirect_link = $this->input->get('link');
        if (!empty($id)) {

            //Is delete record
            $update_data['is_delete'] = 1;
            $where = array('incident_id' => $id);
            $this->common_model->update(PROJECT_INCIDENTS, $update_data, $where);

            unset($id);
            $msg = $this->lang->line('project_incidents_delete_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

            if (strpos($redirect_link, 'Contact/view') !== false) {
                $sess_array = array('setting_current_tab' => 'Cases');
                $this->session->set_userdata($sess_array);
                $this->session->set_flashdata('message', $msg);
            }
        }

        //redirect ($this->module . '/ProjectIncidents'); //Redirect On Listing page
        redirect($redirect_link);
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
            $match = "incident_file_id = " . $id;
            $res = $this->common_model->get_records(PROJECT_INCIDENTS_FILES, array('incident_file_id', 'file_name', 'file_path', 'upload_status'), '', '', $match);

            $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('project_incidents_folder') . '/';
            if (empty($res[0]['upload_status']) && !empty($res[0]['file_name']) && !empty($res[0]['file_path'])) {
                if (file_exists($upload_dir . $res[0]['file_name'])) {
                    unlink($res[0]['file_path'] . '/' . $res[0]['file_name']);
                }
            }
            $where = array('incident_file_id' => $id);
            if ($this->common_model->delete(PROJECT_INCIDENTS_FILES, $where)) {
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
            $params['table'] = PROJECT_INCIDENTS_FILES . ' as MF';
            $params['match_and'] = 'MF.incident_file_id=' . $id . '';
            $task_files = $this->common_model->get_records_array($params);
            if (count($task_files) > 0) {
                $pth = file_get_contents(base_url($task_files[0]['file_path'] . '/' . $task_files[0]['file_name']));
                $this->load->helper('download');
                force_download($task_files[0]['file_name'], $pth);
            }
            redirect($this->module . '/ProjectIncidents');
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
        $config['allowed_types'] = '*';
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
        $project_upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id;
        if (!is_dir($project_upload_dir)) {
            //create directory
            mkdir($project_upload_dir, 0777, TRUE);
        }

        $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('project_incidents_folder');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            file_put_contents($upload_dir . '/' . $filename, $str);
        }
    }

}
