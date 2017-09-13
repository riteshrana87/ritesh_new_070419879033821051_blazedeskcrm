<?php

defined ('BASEPATH') OR exit('No direct script access allowed');

class ProjectIncidentsType extends CI_Controller {

    function __construct() {
        parent::__construct ();
        $this->module    = $this->uri->segment (1);
        $this->viewname  = $this->uri->segment (2);
        $this->user_info = $this->session->userdata ('LOGGED_IN');  //Current Login information
    }

    /*
      @Author : Niral Patel
      @Desc   : ProjectIncidentsType index
      @Input  : 
      @Output :
      @Date   : 27/01/2016
     */

    public function index() {

        /*$master_user_id = $this->config->item('master_user_id');
        //$master_user_id = $data['user_info']['ID'];
        $table_user = LOGIN.' as lg';
        $where_user = array("lg.login_id" => $master_user_id);
        $fields_user = array("lg.*");
        $check_user_data = $this->common_model->get_records($table_user,$fields_user,'','','','','','','','','',$where_user);

        $table = SETUP_MASTER.' as ct';
        //$where = array("ct.login_id" => $main_user_id);
        $where = "ct.login_id = ".$master_user_id." AND ct.email = '".$check_user_data[0]['email']."'";
        $fields = array("ct.*");
        $check_user_menu = $this->common_model->get_records_data($table,$fields,'','','','','','','','','',$where);

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
        $data['header']         = array('menu_module' => 'Projectmanagement');
        $searchtext             = '';
        $perpage                = '';
        $where                  = '';
        $searchtext             = $this->input->post ('searchtext');
        $sortfield              = $this->input->post ('sortfield');
        $sortby                 = $this->input->post ('sortby');
        $perpage                = RECORD_PER_PAGE;
        $data['groupFieldName'] = $groupFieldName = $this->input->post ('groupFieldName');
        $data['groupFieldData'] = $groupFieldData = $this->input->post ('groupFieldData');

        $allflag = $this->input->post ('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata ('projectincidentstypepaging_data');
        }
        $searchsort_session = $this->session->userdata ('projectincidentstypepaging_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby']    = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby']    = $searchsort_session['sortby'];
                $sortfield         = $searchsort_session['sortfield'];
                $sortby            = $searchsort_session['sortby'];
            } else {
                $sortfield         = 'incident_type_id';
                $sortby            = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby']    = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext         = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage']    = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage']    = trim ($searchsort_session['perpage']);
                $config['per_page'] = trim ($searchsort_session['perpage']);
            } else {
                $config['per_page'] = RECORD_PER_PAGE;
                $data['perpage']    = RECORD_PER_PAGE;
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url']   = base_url ($this->module . '/' . $this->viewname . '/index');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment           = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment           = $this->uri->segment (4);
        }
        
        $data['project_incidenttype_view'] = $this->module . '/' . $this->viewname;
        
        //Get Records From PROJECT_MASTER Table  
        
        $dbSearch = "";
        if (!empty($searchtext)) {
            $searchFields = array('incident_type_name');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr ($dbSearch, 0, -3) . ')';
        }
        $table                = PROJECT_INCIDENTS_TYPE . ' as pa';
        $fields               = array('incident_type_id,incident_type_name,created_date');
        $where                = array('is_delete'   => 0); 
        $config['total_rows'] = $this->common_model->get_records (PROJECT_INCIDENTS_TYPE, $fields, '', '', $where, '', '', '', 'incident_type_id', 'desc', '', $dbSearch, '', '', '1');
        //Get Records From MILESTONE_MASTER Table   
        $data['project_incidenttype_data'] = $this->common_model->get_records ($table, $fields, '', '', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $dbSearch);
        $this->ajax_pagination->initialize ($config);
        $data['pagination']  = $this->ajax_pagination->create_links ();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'      => $data['sortfield'],
            'sortby'         => $data['sortby'],
            'searchtext'     => $data['searchtext'],
            'perpage'        => trim ($data['perpage']),
            'uri_segment'    => $uri_segment,
            'groupFieldName' => $this->input->post ('groupFieldName'),
            'groupFieldData' => $this->input->post ('groupFieldData'),
            'total_rows'     => $config['total_rows']);
        $this->session->set_userdata ('projectincidentstypepaging_data', $sortsearchpage_data);
        
        if ($this->input->is_ajax_request ()) {
            
            if ($this->input->post ('project_ajax')) {
                $this->load->view ('/' .$this->viewname . '/ProjectIncidentsType', $data);
            } else {
                $this->load->view ('/' .$this->viewname . '/ProjectIncidentsTypeAjaxList', $data);
            }
        } else {
            $data['main_content'] = '/ProjectIncidentsType/' . $this->viewname;
            $data['js_content']   = '/loadJsFiles';
            $this->parser->parse ('layouts/ProjectTemplate', $data);
        }
        
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert/update data
      @Input  : Post data/Update id
      @Output : Insert/update data
      @Date   : 27/01/2016
     */

    public function insertdata() {
        if (!validateFormSecret()) {
            redirect($this->module . '/ProjectIncidentsType');//Redirect On Listing page
        }
        if ($this->input->post ('incident_type_id')) {
            $id = $this->input->post ('incident_type_id');
        }
        $display = $this->input->post ('display');
        
        $insert_data['incident_type_name'] = ucfirst ($this->input->post ('incident_type_name'));
        $insert_data['status']             = 1;
        //Insert Record in Database
        if (!empty($id)) //update
        {
            $insert_data['modified_by']   = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['modified_date'] = datetimeformat ();
            $where                        = array('incident_type_id' => $id);
            $success_update               = $this->common_model->update (PROJECT_INCIDENTS_TYPE, $insert_data, $where);
            $msg                          = $this->lang->line ('project_incidents_type_update_msg');
            $this->session->set_flashdata ('msg', "<div class='alert alert-success text-center'>$msg</div>");
            
        } else //insert
        {
            $insert_data['created_by']   = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['created_date'] = datetimeformat ();
            $id                          = $this->common_model->insert (PROJECT_INCIDENTS_TYPE, $insert_data);
            $msg                         = $this->lang->line ('project_incidents_type_add_msg');
            $this->session->set_flashdata ('msg', "<div class='alert alert-success text-center'>$msg</div>");

        }
        
        redirect ($this->module . '/ProjectIncidentsType');
    }

    /*
      @Author : Niral Patel
      @Desc   : Add record
      @Input  : Add id
      @Output : Give record
      @Date   : 18/01/2016
     */

    public function add_record() {

        $data['modal_title']         = $this->lang->line ('create_incident');
        $data['submit_button_title'] = $this->lang->line ('create_incident');
        
        //url for filemanager
        $data['project_incidenttype_view'] = $this->module . '/ProjectIncidentsType';
        $this->load->view ('/ProjectIncidentsType/Add_ProjectIncidentsType', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function edit_record() {
        $id = $this->uri->segment ('4');
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = PROJECT_INCIDENTS_TYPE;
            $match = "incident_type_id = " . $id;

            $edit_record = $this->common_model->get_records ($table, '', '', '', $match);

            $data['id']          = $id;
            $data['edit_record'] = $edit_record;
            
            $data['modal_title']         = $this->lang->line ('update_incident');
            $data['submit_button_title'] = $this->lang->line ('update_incident');
        }
        
        $data['project_incidenttype_view'] = $this->module . '/ProjectIncidentsType';
        $this->load->view ('/ProjectIncidentsType/Add_ProjectIncidentsType', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function view_record() {
        $id = $this->uri->segment ('4');
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table       = PROJECT_INCIDENTS_TYPE;
            $match       = "incident_type_id = " . $id;
            $fields      = array('incident_type_id,incident_type_name');
            $edit_record = $this->common_model->get_records ($table, $fields, '', '', $match);
            
            $data['id']          = $id;
            $data['edit_record'] = $edit_record;
            $data['modal_title'] = $this->lang->line ('view_incident');
            
        }
        $data['project_incidenttype_view'] = $this->module . '/ProjectIncidentsType';
        $this->load->view ('/ProjectIncidentsType/View_ProjectIncidentsType', $data);
    }

    
    /*
      @Author : Niral Patel
      @Desc   : Update is delete
      @Input  : Edit id
      @Output : Update is delete
      @Date   : 18/01/2016
     */

    public function delete_record() {
        $id = $this->uri->segment ('4');
        if (!empty($id)) {
            
            //Is delete record
            $update_data['is_delete']      = 1;
            $where                         = array('incident_type_id' => $id);
            $this->common_model->update (PROJECT_INCIDENTS_TYPE, $update_data, $where);
            //is delete project incidents
            $where                         = array('type_id' => $id);
            $this->common_model->update (PROJECT_INCIDENTS, $update_data, $where);
            unset($id);
            $msg = $this->lang->line ('project_incidents_type_delete_msg');
            $this->session->set_flashdata ('msg', "<div class='alert alert-success text-center'>$msg</div>");
            
        }
        redirect ($this->module . '/ProjectIncidentsType'); //Redirect On Listing page
    }
}
