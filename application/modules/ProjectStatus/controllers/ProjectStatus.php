<?php

defined ('BASEPATH') OR exit('No direct script access allowed');

class ProjectStatus extends CI_Controller {

    function __construct() {
        parent::__construct ();
        $this->viewname  = $this->router->fetch_class();
        $this->user_info = $this->session->userdata ('LOGGED_IN');  //Current Login information
    }

    /*
      @Author : Niral Patel
      @Desc   : ProjectStatus index
      @Input  : 
      @Output :
      @Date   : 27/01/2016
     */

    public function index($page='') {
        //Get uri segment for pagination
        $cur_uri= explode('/',$_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);

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
            $this->session->unset_userdata ('projectstatispaging_data');
        }
        $searchsort_session = $this->session->userdata ('projectstatispaging_data');
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
                $sortfield         = 'status_order';
                $sortby            = 'asc';
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
        $config['base_url']   = base_url ('/' . $this->viewname . '/index');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment           = 0;
        } else {
            $config['uri_segment'] = $cur_uri_segment;
            $uri_segment           = $page;
        }
        
        $data['project_status_view'] = '/' . $this->viewname;
        
        //Get Records From PROJECT_MASTER Table  
        
        $dbSearch = "";
        if (!empty($searchtext)) {
            $searchFields = array('status_name');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $searchtext . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr ($dbSearch, 0, -3) . ')';
        }
        $where               = array('is_delete' => 0);
        $table                = PROJECT_STATUS . ' as pa';
        $fields               = array('status_id,status_name,status_color,status_font_icon,default_status');
        $config['total_rows'] = $this->common_model->get_records (PROJECT_STATUS, $fields, '', '', $where, '', '', '', 'status_id', 'desc', '', $dbSearch, '', '', '1');
        
        //Get Records From MILESTONE_MASTER Table    
        $data['status_data'] = $this->common_model->get_records ($table, $fields, '', '',$where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $dbSearch);
        //echo $this->db->last_query();
        $this->ajax_pagination->initialize ($config);
        $data['pagination']  = $this->ajax_pagination->create_links ();
        $data['uri_segment'] = $uri_segment;
        /*$sortsearchpage_data = array(
            'sortfield'      => $data['sortfield'],
            'sortby'         => $data['sortby'],
            'searchtext'     => $data['searchtext'],
            'perpage'        => trim ($data['perpage']),
            'uri_segment'    => $uri_segment,
            'groupFieldName' => $this->input->post ('groupFieldName'),
            'groupFieldData' => $this->input->post ('groupFieldData'),
            'total_rows'     => $config['total_rows']);
        $this->session->set_userdata ('projectstatispaging_data', $sortsearchpage_data);*/
        
        if ($this->input->is_ajax_request ()) {
            
            if ($this->input->post ('project_ajax')) {
                $this->load->view ($this->viewname . '/ProjectStatus', $data);
            } else {
                $this->load->view ($this->viewname . '/ProjectStatusAjaxList', $data);
            }
        } else {
            $data['main_content'] = '/' . $this->viewname;
            $data['js_content']   = '/loadJsFiles';
            $this->parser->parse ('layouts/ProjectstatusTemplate', $data);
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
            redirect ('/ProjectStatus');//Redirect On Listing page
        }
        if ($this->input->post ('status_id')) {
            $id = $this->input->post ('status_id');
        }
        $display = $this->input->post ('display');
        
        $insert_data['status_name']      = ucfirst ($this->input->post ('status_name'));
        $insert_data['status_color']     = $this->input->post ('status_color');
        $insert_data['status_font_icon'] = $this->input->post ('status_font_icon');
        //Insert Record in Database
        if (!empty($id)) //update
        {
            $insert_data['modified_date'] = datetimeformat ();
            $insert_data['modified_by']  = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $where                        = array('status_id' => $id);
            $success_update               = $this->common_model->update (PROJECT_STATUS, $insert_data, $where);
            $msg                          = $this->lang->line ('status_update_msg');
            $this->session->set_flashdata ('msg', "<div class='alert alert-success text-center'>$msg</div>");
            
        } else //insert
        {
            $insert_data['created_date'] = datetimeformat ();
            $insert_data['created_by']  = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $id                          = $this->common_model->insert (PROJECT_STATUS, $insert_data);
            $msg                         = $this->lang->line ('status_add_msg');
            $this->session->set_flashdata ('msg', "<div class='alert alert-success text-center'>$msg</div>");

        }
        
        redirect ('/ProjectStatus');
    }

    /*
      @Author : Niral Patel
      @Desc   : Add record
      @Input  : Add id
      @Output : Give record
      @Date   : 18/01/2016
     */

    public function add_record() {

        $data['modal_title']         = $this->lang->line ('create_status');
        $data['submit_button_title'] = $this->lang->line ('create_status');
        //get font awesome
        $data['font_awesome_data']   = $this->get_font_awesome_icon();
        //url for filemanager
        $data['project_status_view'] = '/ProjectStatus';
        $this->load->view ('ProjectStatus/Add_status', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function edit_record($id='') {
        //Get Records From PROSPECT_MASTER Table
        if (!empty($id)) {
            $table = PROJECT_STATUS;
            $match = "status_id = " . $id;

            $edit_record = $this->common_model->get_records ($table, '', '', '', $match);

            $data['id']          = $id;
            $data['edit_record'] = $edit_record;
            
            $data['modal_title']         = $this->lang->line ('update_status');
            $data['submit_button_title'] = $this->lang->line ('update_status');
        }
        //get font awesome
        $data['font_awesome_data']   = $this->get_font_awesome_icon();
        $data['project_status_view'] = '/ProjectStatus';
        $this->load->view ('ProjectStatus/Add_status', $data);
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
            $table       = PROJECT_STATUS;
            $match       = "status_id = " . $id;
            $fields      = array('status_id,status_name,status_color,status_font_icon');
            $edit_record = $this->common_model->get_records ($table, $fields, '', '', $match);
            
            $data['id']          = $id;
            $data['edit_record'] = $edit_record;
            $data['modal_title'] = $this->lang->line ('update_status');
            
        }
        $data['project_status_view'] = '/ProjectStatus';
        $this->load->view ('ProjectStatus/View_ProjectStatus', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Delete recoed
      @Input  : Delete id
      @Output : Delete record
      @Date   : 27/01/2016
     */

    public function delete_record($id='') {
        
        //Delete Record From Database
        if (!empty($id)) {

            $table       = PROJECT_TASK_MASTER;
            $match       = "is_delete = 0 and status = " . $id;
            $fields      = array('status,task_id');
            $total_record = $this->common_model->get_records ($table, $fields,'', '', $match, '', '', '', '', '', '', '', '', '', '1');
            
            
            if(!empty($total_record))
            {
                unset($id);
                $msg = $this->lang->line ('status_not_deleted');
                $this->session->set_flashdata ('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
            else
            {
                //Is delete record
                $update_data['is_delete']      = 1;
                $where                         = array('status_id' => $id);
                $this->common_model->update (PROJECT_STATUS, $update_data, $where);
                 unset($id);
                $msg = $this->lang->line ('status_delete_msg');
                $this->session->set_flashdata ('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }           
        }
        redirect ('/ProjectStatus'); //Redirect On Listing page
    }
    /*
      @Author : Niral Patel
      @Desc   : Update status order
      @Input  : status id,status array
      @Output : 
      @Date   : 15/03/2016
     */
    function update_order()
    {
        $status_order = $this->input->post ('status_order');
        //pr($status_order);
        foreach ($status_order as $key => $value) {
            $update_data['status_order']   = $key+1;
            $where                         = array('status_id' => $value);
            $this->common_model->update (PROJECT_STATUS, $update_data, $where);
        }
        //exit;
    }
    /*
      @Author : Niral Patel
      @Desc   : get font awesome icon
      @Input  : file
      @Output : 
      @Date   : 17/03/2016
     */
    function get_font_awesome_icon()
    {
        $str = file_get_contents($this->config->item('project_upload_base_url').'ProjectStatus/'.'font-awesome.json');
       
        return $json = json_decode($str, true); // decode the JSON into an associative array
    }
}
