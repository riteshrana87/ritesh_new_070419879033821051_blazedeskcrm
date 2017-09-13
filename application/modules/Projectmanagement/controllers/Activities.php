<?php

defined ('BASEPATH') OR exit('No direct script access allowed');

class Activities extends CI_Controller {

    function __construct() {
        parent::__construct ();
        check_project ();
        $this->module     = $this->uri->segment (1);
        $this->viewname   = $this->uri->segment (2);
        $this->user_info  = $this->session->userdata ('LOGGED_IN');  //Current Login information
        $this->project_id = $this->session->userdata ('PROJECT_ID');
    }

    /*
      @Author : Niral Patel
      @Desc   : Activities index
      @Input  : 
      @Output :
      @Date   : 16/02/2016
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
        $data['header']         = array('menu_module' => 'Projectmanagement');
        $data['main_content']   = '/' . $this->viewname . '/Activities';
        $data['milestone_view'] = $this->module . '/' . $this->viewname;
        
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
            $this->session->unset_userdata ('activitypaging_data');
        }
        $searchsort_session = $this->session->userdata ('activitypaging_data');
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
                $sortfield         = 'activity_id';
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
        //Query
        $params['join_type'] = 'left';
        $fields               = array('pa.*,CONCAT(l.firstname, " ", l.lastname) AS user_name,l.profile_photo');
        $join_tables           = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $table = PROJECT_ACTIVITIES . ' as pa';
        $where                 = array('project_id' => $this->project_id);
        
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim($searchtext));
            $match = ''; // array('CM.cost_name' => $searchtext, 'CM.cost_type' => $searchtext, 'CM.status' => $searchtext, 'CM.ammount' => $searchtext, 'SM.supplier_name' => $searchtext);
            $where_search = '(l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR pa.activity LIKE "%' . $searchtext . '%" )';
            if (!empty($groupFieldName) && !empty($groupFieldData)) {
                for ($i = 0; $i < count($groupFieldName); $i++) {
                    if (strlen($groupFieldData[$i]) > 0):
                        $where[$groupFieldName[$i]] = $groupFieldData[$i];
                    endif;
                }
            }
            $data['activities'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_search);
        } else {
            $match = '';

            if (!empty($groupFieldName) && !empty($groupFieldData)) {
                for ($i = 0; $i < count($groupFieldName); $i++) {
                    $where[$groupFieldName[$i]] = $groupFieldData[$i];
                }
            }
            $data['activities'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
       
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
        $this->session->set_userdata ('activitypaging_data', $sortsearchpage_data);
        //Get Records From Login Table 
        $where            = array('status' => 1);
        $data['res_user'] = $this->common_model->get_records (LOGIN, '', '', '', $where, '');

        //Get project status

        $where = array('is_delete' => 0);
        $data['project_status'] = $this->common_model->get_records(PROJECT_STATUS, array('status_id,status_name'), '', '', $where);
        //Get project assign user
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.login_id,concat(l.firstname," ",l.lastname) as name');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id  and l.is_delete = 0');
        $where = array('project_id' => $this->project_id);
        $data['team_members'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
        
        if ($this->input->is_ajax_request ()) {
            
            if ($this->input->post ('project_ajax')) {
                $this->load->view ('/' . $this->viewname . '/Activities', $data);
            } else {
                $this->load->view ('/' . $this->viewname . '/ActivitiesAjaxList', $data);
            }
        } else {
            $data['main_content'] = '/Activities/' . $this->viewname;
            $data['js_content']   = '/loadJsFiles';
            $this->parser->parse ('layouts/ProjectmanagementTemplate', $data);
        }
        
    }

}
