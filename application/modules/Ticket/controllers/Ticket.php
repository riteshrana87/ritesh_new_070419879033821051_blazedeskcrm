<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket extends CI_Controller {

    public $viewname;

    function __construct() {
		
        parent::__construct();
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation', 'Session'));
		date_default_timezone_set($this->session->userdata('LOGGED_IN')['TIMEZONE']);
    
    }

    /*
      @Author : Ghelani Nikunj
      @Desc   : Common Model Index Page
      @Input  :
      @Output :
      @Date   : 03/01/2016
     */

    public function index() {

        //pr($pagination);die();
        $this->breadcrumbs->push(lang('support'), '/Support');
        $this->breadcrumbs->push(lang('ticket'), 'Ticket');
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');

        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('ticket_data');
        }

        $searchsort_session = $this->session->userdata('ticket_data');
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
                $sortfield = 'ticket_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/index';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {

            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }

        //
        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "";
        $fields1 = array("cm.contact_id,cm.contact_name");
        $data['prospect_owner'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        //Get Records From BRANCH_MASTER Table       
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = "";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);

        /* status data start */

        $table7 = SUPPORT_STATUS . ' as ss';
        $match7 = "ss.is_delete='0'";
        $fields7 = array("ss.status_id,ss.status_name");
        $data['status_data'] = $this->common_model->get_records($table7, $fields7, '', '', $match7);


        /* status data end */

        /* assign data start */

        $table15 = LOGIN . ' as l';
        $match15 = "l.is_delete = 0 AND l.status = 1";
        $fields15 = array("l.firstname,l.lastname,l.login_id");
        $data['assign'] = $this->common_model->get_records($table15, $fields15, '', '', $match15);

        /* assign data end */
        //Get Total Records From BRANCH_MASTER Table 
        //pr($total_ticket);

        $params['join_tables'] = array(CONTACT_MASTER . ' as cms' => 'tk.contact_id=cms.contact_id',
            SUPPORT_STATUS . ' as ss' => 'tk.status=ss.status_id',
            TICKET_SUPPORT_USER . ' as tsu' => 'tk.ticket_id=tsu.ticket_id',
            
        );
        $params['join_type'] = 'left';
        //$match = "lm.status_type=2";
        $table = TICKET_MASTER . ' as tk';
        $group_by = 'tk.ticket_id';
        $fields = array("tk.status,ss.status_id,ss.status_name,tk.due_date,tk.ticket_id,tk.ticket_subject,tk.ticket_desc,tk.status,tk.created_date,cms.contact_name,tsu.support_user");


        //$table  = CAMPAIGN_GROUP_MASTER.' as cg';
        //$fields = array("cg.campaign_group_id,cg.group_name");
        $where = "tk.is_delete=0";


        /* search from status */
        $data['select_status'] = "";
        if ($this->input->post('select_status') != "") {
            $data['select_status'] = $this->input->post('select_status');
            $where.=' AND tk.status=' . $data['select_status'];
        }
      
        /* search from assign user */
        $data['search_assign'] = "";
        if ($this->input->post('search_assign') != "") {
            $data['search_assign'] = $this->input->post('search_assign');
            $where.=' AND tsu.support_user=' . $data['search_assign'];
        }
        /* search from assign user */

        /* date wise search start */
        $data['search_creation_date_show'] = "";
        if ($this->input->post('search_creation_date') != "") {
            $data['search_creation_date_show'] = date_format(date_create($this->input->post('search_creation_date')), 'Y-m-d');
            $where.=' AND tk.created_date>="' . $data['search_creation_date_show'] . '"';
        }

        $data['creation_end_date_show'] = "";
        if ($this->input->post('creation_end_date') != "") {
            $data['creation_end_date_show'] = date_format(date_create($this->input->post('creation_end_date')), 'Y-m-d');
            $where.=' AND tk.created_date<="' . $data['creation_end_date_show'] . '"';
        }
        /* date wise search end */
        $table6 = TICKET_MASTER . ' as tk';
        $match6 = "tk.is_delete=0";
        $fields6 = array("count(tk.ticket_id) as total_ticket");
        $total_ticket = $this->common_model->get_records($table6, $fields6, '', '',$match6);
        $data['total_ticket'] = $total_ticket[0]['total_ticket'];

        /**/
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim($searchtext));
            $where_search = '(tk.ticket_subject LIKE "%' . $searchtext . '%" OR tk.ticket_desc LIKE "%' . $searchtext . '%" OR cms.contact_name LIKE "%' . $searchtext . '%" OR tk.status LIKE "%' . $searchtext . '%")';
            //$match=array('pm.prospect_name'=>$searchtext,'pm.prospect_auto_id'=>$searchtext,"pm.status_type"=>$searchtext,"pm.creation_date"=>$searchtext,"pc.contact_name"=>$searchtext);
            $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $where_search);
            //echo $this->db->last_query(); exit;

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_search);
        
           
        } else {

            $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
        }
        

        $data['header'] = array('menu_module' => 'support');
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('ticket_data', $sortsearchpage_data);

        $data['lead_view'] = $this->viewname;

        $data['sales_view'] = $this->viewname;
        $data['drag'] = true;
        if ($this->input->post('result_type') == 'ajax') {

            $this->load->view($this->viewname . '/AjaxTicketList', $data);
        } else {

            $data['main_content'] = $this->viewname . '/Ticketview';
            $this->parser->parse('layouts/SupportTemplate', $data);
           
        }
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 1/03/2016
     */

    public function view_record($id) {
if (!$this->input->is_ajax_request()) 
                {
                    exit('No direct script access allowed');
                }else
                {
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $data = array();
        $data['readonly'] = array("disabled" => "disabled");
        $data['project_view'] = 'viewdata';
        $redirect_link = $this->input->post('redirect_link');
        $data['main_content'] = '/Lead';
        $data['sales_view'] = $this->viewname;
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $data['modal_title'] = $this->lang->line('create_new_lead');
        $data['submit_button_title'] = $this->lang->line('create_lead');
        $params['join_tables'] = array(LEAD_CONTACTS_TRAN . ' as pc' => 'lm.lead_id=pc.lead_id');
        $params['join_type'] = 'left';
        $match = "";
        $table = LEAD_MASTER . ' as lm';
        $group_by = 'lm.lead_id';
        $fields = array("lm.lead_id,count(lm.lead_id) as opp_count,lm.prospect_name,lm.prospect_auto_id, lm.status_type,count(pc.lead_id) as contact_count,pc.contact_id");
        $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);
        //Get Records From CONTACT_MASTER Table
        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "";
        $fields1 = array("cm.contact_id,cm.contact_name");
        $data['prospect_owner'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        //Get Records From BRANCH_MASTER Table       
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = "";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
        //Get Records From CAMPAIGN_MASTER Table       
        $table3 = CAMPAIGN_MASTER . ' as cam';
        $match3 = "";
        $fields3 = array("cam.campaign_id,cam.campaign_name");
        $data['campaign'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);
        //Get Records From PRODUCT_MASTER Table       
        $table4 = PRODUCT_MASTER . ' as prm';
        $match4 = "";
        $fields4 = array("prm.product_id,prm.product_name");
        $data['product_data'] = $this->common_model->get_records($table4, $fields4, '', '', $match4);
        //Get Records From LEAD_MASTER Table       
        $table6 = LEAD_MASTER . ' as lm';
        $match6 = "lm.status_type=1";
        $fields6 = array("count(lm.lead_id) as total_lead");
        $total_opportunity = $this->common_model->get_records($table6, $fields6, '', '', $match6);
        $data['total_lead'] = $total_opportunity[0]['total_lead'];
        //Get Records From COUNTRIES Table       
        $table7 = COUNTRIES . ' as c';
        $match7 = "";
        $fields7 = array("c.country_id,c.country_name");
        $data['country_data'] = $this->common_model->get_records($table7, $fields7, '', '', $match7);
        //Get Records From COMPANY_MASTER Table     
        // GET records from contact master table

        $table8 = COMPANY_MASTER . ' as cmp';
        $match8 = "";
        $fields8 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table8, $fields8, '', '', $match8);

        $table9 = CONTACT_MASTER . ' as cm';
        $match9 = "";
        $fields9 = array("cm.contact_id,cm.contact_name");
        $data['contact_data'] = $this->common_model->get_records($table9, $fields9, '', '', $match9);

        $table10 = PROSPECT_MASTER . ' as pm';
        $match10 = "pm.status_type=3 and pm.is_delete=0 and pm.status=1";
        $fields10 = array("pm.prospect_id,pm.prospect_name");
        $data['client_data'] = $this->common_model->get_records($table10, $fields10, '', '', $match10);

        $table11 = PRODUCT_MASTER . ' as prm';
        $match11 = "prm.status=1";
        $fields11 = array("prm.product_id,prm.product_name");
        $data['product_data'] = $this->common_model->get_records($table11, $fields11, '', '', $match11);

        $table12 = TICKET_MASTER . ' as tk';
        $match12 = "tk.ticket_id = " . $id;
        $fields12 = array("*");
        $data['ticket_data'] = $this->common_model->get_records($table12, $fields12, '', '', $match12);

        $table13 = FILES_TICKET_MASTER . ' as tfk';
        $match13 = "tfk.ticket_id = " . $id;
        $fields13 = array("*");
        $data['img_data'] = $this->common_model->get_records($table13, $fields13, '', '', $match13);


        $table14 = SUPPORT_TEAM . ' as st';
        $match14 = "st.status=1";
        $fields14 = array("st.team_id,st.team_name");
        $data['team_data'] = $this->common_model->get_records($table14, $fields14, '', '', $match14);

        $params15['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=stm.member_id');
        $params15['join_type'] = 'left';
        $match15 = "stm.status=1 AND stm.is_delete=0";
        $table15 = SUPPORT_TEAM_MEMBER . ' as stm';
        $group_by15 = 'stm.member_id';
        $fields15 = array("l.firstname,l.lastname,stm.team_member_id,stm.member_id");
        $data['team_member_data'] = $this->common_model->get_records($table15, $fields15, $params15['join_tables'], $params15['join_type'], $match15, '', '', '', '', '', $group_by15);
      
		$params16['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=tk.support_user');
        $params16['join_type'] = 'left';
        $match16 = "tk.ticket_id = " . $id;
        $table16 = TICKET_SUPPORT_USER . ' as tk';
        $fields16 = array("l.firstname,l.lastname,tk.support_user");
         $data['ticket_data_team'] = $this->common_model->get_records($table16, $fields16, $params16['join_tables'], $params16['join_type'], $match16, '', '', '', '', '', '');
       
        $table17 = TICKET_MASTER . ' as tk';
        $match17 = "tk.ticket_id = " . $id;
        $fields17 = array("tk.contact_id");
        $data['ticket_data_contact'] = $this->common_model->get_records($table17, $fields17, '', '', $match17);

        $table18 = SUPPORT_STATUS . ' as ss';
        $match18 = "ss.is_delete=0";
        $fields18 = array("ss.status_name,ss.status_id");
        $data['status_data'] = $this->common_model->get_records($table18, $fields18, '', '', $match18);


        $table19 = SUPPORT_TYPE . ' as sty';
        $match19 = "sty.status=1 AND sty.is_delete=0";
        $fields19 = array("sty.support_type_id,sty.type");
        $data['type_data'] = $this->common_model->get_records($table19, $fields19, '', '', $match19);

        $table20 = SUPPORT_PRIORITY . ' as sp';
        $match20 = "sp.status=1 AND sp.is_delete=0";
        $fields20 = array("sp.support_priority_id,sp.priority");
        $data['priority_data'] = $this->common_model->get_records($table20, $fields20, '', '', $match20);

        $table21 = TICKET_MASTER . ' as tk';
        $match21 = "tk.is_delete=0";
        $fields21 = array("tk.ticket_id,tk.ticket_subject,tk.ticket_desc,tk.created_date,tk.contact_id");
        $data['left_ticket_data'] = $this->common_model->get_records($table21, $fields21, '', '', $match21);

        $params22['join_tables'] = array(CONTACT_MASTER . ' as c' => 'c.contact_id=tk.contact_id', SUPPORT_STATUS . ' as ss' => 'ss.status_id=tk.status');
        $params22['join_type'] = 'left';
        $table22 = TICKET_MASTER . ' as tk';
        $match22 = "tk.is_delete=0 AND tk.status='4'";
        $fields22 = array("c.contact_name,tk.ticket_id,tk.ticket_subject,tk.status,tk.ticket_desc,tk.created_date,tk.contact_id,ss.status_name");
        $data['left_ticket_data'] = $this->common_model->get_records($table22, $fields22, $params22['join_tables'], $params22['join_type'], $match22, '', '', '', '', '', '');


        $team_selected_id = array();
        if (!empty($data['ticket_data_team'])) {
            foreach ($data['ticket_data_team'] as $data_team_selected) {
                $team_selected_id[] = $data_team_selected['support_user'];
            }
        }
        $data['team_selected_users'] = $team_selected_id;
      
        $params23['join_tables'] = array(TICKET_CONTACT . ' as tct' => 'tct.contact_id=c.contact_id');
        $params23['join_type'] = 'left';
        $table23 = CONTACT_MASTER . ' as c';
        $match23 = "tct.ticket_id=" . $id;
        $fields23 = array("c.contact_name,tct.ticket_id,tct.contact_id");
        $data['left_ticket_data_contact'] = $this->common_model->get_records($table23, $fields23, $params23['join_tables'], $params23['join_type'], $match23, '', '', '', '', '', '');

        $contact_selected_id = array();
        if (!empty($data['left_ticket_data_contact'])) {
            foreach ($data['left_ticket_data_contact'] as $data_con_selected) {
                $contact_selected_id[] = $data_con_selected['contact_id'];
            }
        }
        $data['contact_selected_users'] = $contact_selected_id;


        $data['drag'] = true;
        $this->load->view('view_ticket', $data);
	}
    }

    public function edit_record($id) {
if (!$this->input->is_ajax_request()) 
                {
                    exit('No direct script access allowed');
                }else
                {
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $data = array();
        $data['id'] = $id;
        $data['project_view'] = 'Ticket/updatedata';
        $redirect_link = $this->input->post('redirect_link');
        $data['main_content'] = '/Lead';
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $data['modal_title'] = $this->lang->line('create_new_lead');
        $data['submit_button_title'] = $this->lang->line('create_lead');
        $params['join_tables'] = array(LEAD_CONTACTS_TRAN . ' as pc' => 'lm.lead_id=pc.lead_id');
        $params['join_type'] = 'left';
        $match = "";
        $table = LEAD_MASTER . ' as lm';
        $group_by = 'lm.lead_id';
        $fields = array("lm.lead_id,count(lm.lead_id) as opp_count,lm.prospect_name,lm.prospect_auto_id, lm.status_type,count(pc.lead_id) as contact_count,pc.contact_id");
        $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);
        //Get Records From CONTACT_MASTER Table
        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "";
        $fields1 = array("cm.contact_id,cm.contact_name");
        $data['prospect_owner'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        //Get Records From BRANCH_MASTER Table       
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = "";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
        //Get Records From CAMPAIGN_MASTER Table       
        $table3 = CAMPAIGN_MASTER . ' as cam';
        $match3 = "";
        $fields3 = array("cam.campaign_id,cam.campaign_name");
        $data['campaign'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);
        //Get Records From PRODUCT_MASTER Table       
        //Get Records From LEAD_MASTER Table       
        $table6 = LEAD_MASTER . ' as lm';
        $match6 = "lm.status_type=1";
        $fields6 = array("count(lm.lead_id) as total_lead");
        $total_opportunity = $this->common_model->get_records($table6, $fields6, '', '', $match6);
        $data['total_lead'] = $total_opportunity[0]['total_lead'];
        //Get Records From COUNTRIES Table       
        $table7 = COUNTRIES . ' as c';
        $match7 = "";
        $fields7 = array("c.country_id,c.country_name");
        $data['country_data'] = $this->common_model->get_records($table7, $fields7, '', '', $match7);
        //Get Records From COMPANY_MASTER Table     
        // GET records from contact master table

        $table8 = COMPANY_MASTER . ' as cmp';
        $match8 = "";
        $fields8 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table8, $fields8, '', '', $match8);

        $table9 = CONTACT_MASTER . ' as cm';
        $match9 = "";
        $fields9 = array("cm.contact_id,cm.contact_name");
        $data['contact_data'] = $this->common_model->get_records($table9, $fields9, '', '', $match9);

        $table10 = PROSPECT_MASTER . ' as pm';
        $match10 = "pm.status_type=3 and pm.is_delete=0 and pm.status=1";
        $fields10 = array("pm.prospect_id,pm.prospect_name");
        $data['client_data'] = $this->common_model->get_records($table10, $fields10, '', '', $match10);

        $table11 = PRODUCT_MASTER . ' as prm';
        $match11 = "prm.status=1 and prm.is_delete='0'";
        $fields11 = array("prm.product_id,prm.product_name");
        $data['product_data'] = $this->common_model->get_records($table11, $fields11, '', '', $match11);

        $table12 = TICKET_MASTER . ' as tk';
        $match12 = "tk.ticket_id = " . $id;
        $fields12 = array("*");
        $data['ticket_data'] = $this->common_model->get_records($table12, $fields12, '', '', $match12);

        /* for selected data */

        $table16 = TICKET_SUPPORT_USER . ' as tk';
        $match16 = "tk.ticket_id = " . $id;
        $fields16 = array("tk.support_user");
        $data['ticket_data_team'] = $this->common_model->get_records($table16, $fields16, '', '', $match16);

        $table17 = TICKET_MASTER . ' as tk';
        $match17 = "tk.ticket_id = " . $id;
        $fields17 = array("tk.contact_id");
        $data['ticket_data_contact'] = $this->common_model->get_records($table17, $fields17, '', '', $match17);

        $table18 = SUPPORT_TYPE . ' as sty';
        $match18 = "sty.status=1 AND sty.is_delete=0";
        $fields18 = array("sty.support_type_id,sty.type");
        $data['type_data'] = $this->common_model->get_records($table18, $fields18, '', '', $match18);

        $table19 = SUPPORT_PRIORITY . ' as sp';
        $match19 = "sp.status=1 AND sp.is_delete=0";
        $fields19 = array("sp.support_priority_id,sp.priority");
        $data['priority_data'] = $this->common_model->get_records($table19, $fields19, '', '', $match19);
        $team_selected_id = array();
        if (!empty($data['ticket_data_team'])) {
            foreach ($data['ticket_data_team'] as $data_team_selected) {
                $team_selected_id[] = $data_team_selected['support_user'];
            }
        }

        $data['team_selected_users'] = $team_selected_id;

        $table13 = FILES_TICKET_MASTER . ' as tfk';
        $match13 = "tfk.ticket_id = " . $id;
        $fields13 = array("*");
        $data['img_data'] = $this->common_model->get_records($table13, $fields13, '', '', $match13);
        $table14 = SUPPORT_TEAM . ' as st';
        $match14 = "st.status=1 AND st.is_delete=0";
        $fields14 = array("st.team_id,st.team_name");
        $data['team_data'] = $this->common_model->get_records($table14, $fields14, '', '', $match14);

        $params15['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=tu.support_user');
        $params15['join_type'] = 'left';
        $match15 = "tu.ticket_id=" . $id;
        $table15 = TICKET_SUPPORT_USER . ' as tu';
        $fields15 = array("l.firstname,l.lastname,l.login_id");
        $data['team_member_data'][] = $this->common_model->get_records($table15, $fields15, $params15['join_tables'], $params15['join_type'], $match15);
        $params20['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=tu.support_user');
        $params20['join_type'] = 'left';
        $match20 = "tu.ticket_id=" . $id;
        $table20 = TICKET_SUPPORT_USER . ' as tu';
        $fields20 = array("l.firstname,l.lastname,l.login_id");
        $data['team_member_data1'] = $this->common_model->get_records($table20, $fields20, $params20['join_tables'], $params20['join_type'],$match20);
     
        $table18 = SUPPORT_STATUS . ' as ss';
        $match18 = "ss.is_delete=0";
        $fields18 = array("ss.status_name,ss.status_id");
        $data['status_data'] = $this->common_model->get_records($table18, $fields18, '', '', $match18);

        $params22['join_tables'] = array(CONTACT_MASTER . ' as c' => 'c.contact_id=tk.contact_id', SUPPORT_STATUS . ' as ss' => 'ss.status_id=tk.status');
        $params22['join_type'] = 'left';
        $table22 = TICKET_MASTER . ' as tk';
        $match22 = "tk.is_delete=0 AND tk.status='4'";
        $fields22 = array("c.contact_name,tk.ticket_id,tk.ticket_subject,tk.status,tk.ticket_desc,tk.created_date,tk.contact_id,ss.status_name");
        $data['left_ticket_data'] = $this->common_model->get_records($table22, $fields22, $params22['join_tables'], $params22['join_type'], $match22, '', '', '', '', '', '');

        $params23['join_tables'] = array(TICKET_CONTACT . ' as tct' => 'tct.contact_id=c.contact_id');
        $params23['join_type'] = 'left';
        $table23 = CONTACT_MASTER . ' as c';
        $match23 = "tct.ticket_id=" . $id;
        $fields23 = array("c.contact_name,tct.ticket_id,tct.contact_id");
        $data['left_ticket_data_contact'] = $this->common_model->get_records($table23, $fields23, $params23['join_tables'], $params23['join_type'], $match23, '', '', '', '', '', '');

		/*for unclassified team member start*/
		$team_id='0';
		$table24 = SUPPORT_TEAM_MEMBER . ' as tm';
        $where24 = array("tm.is_delete" => "0", "tm.team_id" => $team_id);
		$group_by24 = 'tm.member_id';
        $fields24 = array("tm.member_id","l.firstname","l.lastname","l.login_id");
        $params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=tm.member_id');
        $params['join_type'] = 'left';
        $data['team_member_id'] = $this->common_model->get_records($table24, $fields24, $params['join_tables'],$params['join_type'], '', '', '', '', '','',$group_by24,$where24);
		
		
        $contact_selected_id = array();
        if (!empty($data['left_ticket_data_contact'])) {
            foreach ($data['left_ticket_data_contact'] as $data_con_selected) {
                $contact_selected_id[] = $data_con_selected['contact_id'];
            }
        }
        $data['contact_selected_users'] = $contact_selected_id;

        $data['drag'] = true;
        $data['sales_view'] = $this->viewname;
        $this->load->view('AddFinal', $data);
        //$this->parser->parse('layouts/DashboardTemplate', $data);
	}
    }

    public function add() {
			if (!$this->input->is_ajax_request()) 
                {
                    exit('No direct script access allowed');
                }else
                {
        $data = array();
        $data['project_view'] = $this->viewname;
        $redirect_link = $this->input->post('redirect_link');
        $data['main_content'] = '/Lead';
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $data['modal_title'] = $this->lang->line('create_new_lead');
        $data['submit_button_title'] = $this->lang->line('create_lead');
        $params['join_tables'] = array(LEAD_CONTACTS_TRAN . ' as pc' => 'lm.lead_id=pc.lead_id');
        $params['join_type'] = 'left';
        $match = "";
        $table = LEAD_MASTER . ' as lm';
        $group_by = 'lm.lead_id';
        $fields = array("lm.lead_id,count(lm.lead_id) as opp_count,lm.prospect_name,lm.prospect_auto_id, lm.status_type,count(pc.lead_id) as contact_count,pc.contact_id");
        $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '', '', '', '', '', $group_by);
        //Get Records From CONTACT_MASTER Table
        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "";
        $fields1 = array("cm.contact_id,cm.contact_name");
        $data['prospect_owner'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        //Get Records From BRANCH_MASTER Table       
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = "";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
        //Get Records From CAMPAIGN_MASTER Table       
        $table3 = CAMPAIGN_MASTER . ' as cam';
        $match3 = "";
        $fields3 = array("cam.campaign_id,cam.campaign_name");
        $data['campaign'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);
        //Get Records From PRODUCT_MASTER Table       
        //Get Records From LEAD_MASTER Table       
        $table6 = LEAD_MASTER . ' as lm';
        $match6 = "lm.status_type=1";
        $fields6 = array("count(lm.lead_id) as total_lead");
        $total_opportunity = $this->common_model->get_records($table6, $fields6, '', '', $match6);
        $data['total_lead'] = $total_opportunity[0]['total_lead'];
        //Get Records From COUNTRIES Table       
        $table7 = COUNTRIES . ' as c';
        $match7 = "";
        $fields7 = array("c.country_id,c.country_name");
        $data['country_data'] = $this->common_model->get_records($table7, $fields7, '', '', $match7);
        //Get Records From COMPANY_MASTER Table     
        // GET records from contact master table

        $table8 = COMPANY_MASTER . ' as cmp';
        $match8 = "";
        $fields8 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table8, $fields8, '', '', $match8);

        $table9 = CONTACT_MASTER . ' as cm';
        $match9 = "cm.is_delete=0 and cm.status=1";
        $fields9 = array("cm.contact_id,cm.contact_name");
        $data['contact_data'] = $this->common_model->get_records($table9, $fields9, '', '', $match9);

        $table10 = PROSPECT_MASTER . ' as pm';
        $match10 = "pm.status_type=3 and pm.is_delete=0 and pm.status=1";
        $fields10 = array("pm.prospect_id,pm.prospect_name");
        $data['client_data'] = $this->common_model->get_records($table10, $fields10, '', '', $match10);

        $table11 = PRODUCT_MASTER . ' as prms';
        $match11 = "prms.status=1 and prms.is_delete='0'";
        $fields11 = array("prms.product_id,prms.product_name");
        $data['product_data'] = $this->common_model->get_records($table11, $fields11, '', '', $match11);
        //pr($data['product_data']);die();
        $table14 = SUPPORT_TEAM . ' as st';
        $match14 = "st.status=1 AND st.is_delete=0";
        $fields14 = array("st.team_id,st.team_name");
        $data['team_data'] = $this->common_model->get_records($table14, $fields14, '', '', $match14);
		
		//pr($data['team_data']);die();


        $table16 = SUPPORT_TYPE . ' as sty';
        $match16 = "sty.status=1 AND sty.is_delete=0";
        $fields16 = array("sty.support_type_id,sty.type");
        $data['type_data'] = $this->common_model->get_records($table16, $fields16, '', '', $match16);

        $table17 = SUPPORT_PRIORITY . ' as sp';
        $match17 = "sp.status=1 AND sp.is_delete=0";
        $fields17 = array("sp.support_priority_id,sp.priority");
        $data['priority_data'] = $this->common_model->get_records($table17, $fields17, '', '', $match17);

        $table18 = SUPPORT_STATUS . ' as ss';
        $match18 = "ss.is_delete=0";
        $fields18 = array("ss.status_name,ss.status_id");
        $data['status_data'] = $this->common_model->get_records($table18, $fields18, '', '', $match18);

        $params19['join_tables'] = array(CONTACT_MASTER . ' as c' => 'c.contact_id=tk.contact_id', SUPPORT_STATUS . ' as ss' => 'ss.status_id=tk.status');
        $params19['join_type'] = 'left';
        $table19 = TICKET_MASTER . ' as tk';
        $match19 = "tk.is_delete=0 AND tk.status='4'";
        $fields19 = array("c.contact_name,tk.ticket_id,tk.ticket_subject,tk.status,tk.ticket_desc,tk.created_date,tk.contact_id,ss.status_name");
        $data['left_ticket_data'] = $this->common_model->get_records($table19, $fields19, $params19['join_tables'], $params19['join_type'], $match19, '', '', '', '', '', '');
		
		/*for unclassified team member start*/
		$team_id='0';
		$table20 = SUPPORT_TEAM_MEMBER . ' as tm';
        $where20 = array("tm.is_delete" => "0", "tm.team_id" => $team_id);
		$group_by20 = 'tm.member_id';
        $fields20 = array("tm.member_id","l.firstname","l.lastname","l.login_id");
        $params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=tm.member_id');
        $params['join_type'] = 'left';
        $data['team_member_id'] = $this->common_model->get_records($table20, $fields20, $params['join_tables'],$params['join_type'], '', '', '', '', '','',$group_by20,$where20);
		
       /*for unclassified team member end*/
	  
        $data['sales_view'] = $this->viewname;
        $data['drag'] = true;
        $this->load->view('AddFinal', $data);
	}
    }

    //get team member
    public function getTeam() {
        $data = array();
		
        $team_id = $this->input->post('id');
        //echo "here is id ".$team_id;die();
        if($team_id!=''){
        $table2 = SUPPORT_TEAM_MEMBER . ' as tm';
        $where2 = array("tm.is_delete" => "0", "tm.team_id" => $team_id);
		$group_by2 = 'tm.member_id';
        $fields2 = array("tm.member_id","l.firstname","l.lastname","l.login_id");
        $params['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=tm.member_id');
        $params['join_type'] = 'left';
        $data['team_member_id'] = $this->common_model->get_records($table2, $fields2, $params['join_tables'],$params['join_type'], '', '', '', '', '', '', $group_by2,$where2);
        $teammember_id = $data['team_member_id'];
       
      //  pr($data['team_info']); exit;
        //$this->load->view('userDropdown', $data);
       echo json_encode($teammember_id);
   }
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : Add Ticket to database
      @Input 	:
      @Output	:
      @Date   : 26/02/2016
     */

    public function saveTicketData() {
		
        $this->form_validation->set_rules('ticket_subject', 'Ticket Subject', 'required');
        $contact_implode = '';
        $team_member_implode = '';
        if ($this->input->post('ticket_subject')) {

            $compaigndata['ticket_subject'] = strip_slashes($this->input->post('ticket_subject'));
        }
        if ($this->input->post('contact_id')) {
            $contact_implode = implode(',', $this->input->post('contact_id'));
        }
        if ($this->input->post('team_member_id')) {
            $team_member_implode = implode(',', $this->input->post('team_member_id'));
        }
        $compaigndata['client_id'] = $this->input->post('client_id');
        $compaigndata['user_id'] = $this->session->userdata('LOGGED_IN')['ID'];
        //$compaigndata['contact_id'] = $contact_implode;
        $compaigndata['ticket_subject'] = $this->input->post('ticket_subject');
        $compaigndata['product_id'] = $this->input->post('product_id');
        $compaigndata['type'] = $this->input->post('type');
        $compaigndata['status'] = $this->input->post('status');
        $compaigndata['due_date'] = $this->input->post('due_date');
        $compaigndata['priority'] = $this->input->post('priority');
        $compaigndata['ticket_desc'] = $this->input->post('ticket_desc', false);
        $compaigndata['suport_team'] = $this->input->post('team_id');
        //$compaigndata['support_user'] = $team_member_implode;
        if ($this->input->post('email_notification') == null) {

            $email_notification = '0';
        } else {
            $email_notification = '1';
        }
        if ($this->input->post('agent_notify') == null) {

            $agent_notification = '0';
        } else {
            $agent_notification = '1';
        }
        $compaigndata['email_notification'] = $email_notification;
        $compaigndata['agent_notify'] = $agent_notification;
        $compaigndata['created_date'] = datetimeformat();
        //Insert Record in Database
        $success_insert = $this->common_model->insert(TICKET_MASTER, $compaigndata);
        $insert_id = $this->db->insert_id();
       
        /* ticket activity start */
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $ticketactivity['user_id'] = $user_id;
        $ticketactivity['ticket_id'] = $insert_id;
        $ticketactivity['activity'] = 'Added New Ticket';
        $ticketactivity['activity_date'] = datetimeformat();
        $success_insert = $this->common_model->insert(TICKET_ACTIVITY, $ticketactivity);
        /* ticket activity end */
        /* for contact data insert start */
        $contact_id = $this->input->post('contact_id');
        for ($i = 0; $i < count($contact_id); $i++) {
            $ticket_contact['ticket_id'] = $insert_id;
            $ticket_contact['contact_id'] = $contact_id[$i];
            $ticket_contact['created_date'] = datetimeformat();
            $ticket_contact['modified_date'] = datetimeformat();


            $this->common_model->insert(TICKET_CONTACT, $ticket_contact);
        }
        /* for contact data insert end */

        /* for support team user data insert start */
        $supportteamuser = $this->input->post('team_member_id');
        for ($i = 0; $i < count($supportteamuser); $i++) {
            $support_team_user['ticket_id'] = $insert_id;
            $support_team_user['support_user'] = $supportteamuser[$i];
            $support_team_user['created_date'] = datetimeformat();
            $support_team_user['modified_date'] = datetimeformat();


            $this->common_model->insert(TICKET_SUPPORT_USER, $support_team_user);
        }
        /* for support team user data insert end */

        if (!empty($insert_id)) {
            /* image upload code */
            $file_name = array();
            $file_array1 = $this->input->post('file_data');

            $file_name = $_FILES['fileUpload']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['fileUpload']['name'][$key]);
                        unset($_FILES['fileUpload']['type'][$key]);
                        unset($_FILES['fileUpload']['tmp_name'][$key]);
                        unset($_FILES['fileUpload']['error'][$key]);
                        unset($_FILES['fileUpload']['size'][$key]);
                    }
                }
            }

            $_FILES['fileUpload'] = $arr = array_map('array_values', $_FILES['fileUpload']);
            $data['lead_view'] = $this->viewname;
            $uploadData = uploadImage('fileUpload', ticket_upload_path, $data['lead_view']);

            $Marketingfiles = array();
            foreach ($uploadData as $dataname) {
                $Marketingfiles[] = $dataname['file_name'];
            }
            $marketing_file_str = implode(",", $Marketingfiles);

            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }
            if (!empty($marketing_file_str) && !empty($file_data)) {
                $marketingdata['file'] = $marketing_file_str . ',' . $file_data;
            } else if (!empty($marketing_file_str)) {
                $marketingdata['file'] = $marketing_file_str;
            } else {
                $marketingdata['file'] = $file_data;
            }
            $marketingdata['file_name'] = $file_data;
            if ($marketingdata['file_name'] != '') {
                $explodedData = explode(',', $marketingdata['file_name']);

                foreach ($explodedData as $img) {
                    array_push($uploadData, array('file_name' => $img));
                }
            }

            $costFiles = array();

            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $est_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $costFiles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'ticket_id' => $insert_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                    }
                }
            }

            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => Ticket_upload_path, 'ticket_id' => $insert_id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
            if (count($costFiles) > 0) {

                if (!$this->common_model->insert_batch(FILES_TICKET_MASTER, $costFiles)) {
                    $msg = $this->lang->line('ticket_add_msg');
                    $this->session->set_flashdata('msg', $msg);
                }
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
            $this->common_model->delete(FILES_TICKET_MASTER, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */

        $contact_id = $this->input->post('contact_id');
        if ($success_insert) {
            $msg = $this->lang->line('ticket_add_msg');
            $this->session->set_flashdata('msg', $msg);
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
            $this->common_model->delete(FILES_TICKET_MASTER, 'file_id IN(' . $dlStr . ')');
        }
		 /*
         * SOFT DELETION CODE ENDS
         */
		$redirectLink = $_SERVER['HTTP_REFERER'];
		if (strpos($redirectLink, 'Account/viewdata') !== false) {
			$sess_array = array('setting_current_tab' => 'SupportTickets');
            $this->session->set_userdata($sess_array);
			redirect($redirectLink);
		}
		elseif (strpos($redirectLink, 'CrmCompany/view') !== false) {
			$sess_array = array('setting_current_tab' => 'SupportTickets');
            $this->session->set_userdata($sess_array);
			redirect($redirectLink);
		}
		else{
			redirect($this->viewname);
		}
       
    }

    public function updatedata() {
		/*pr($_POST);
		die('here');*/
      
        $contact_implode = '';
        $team_member_implode = '';
        $id = $this->input->post('update_id');
        $this->form_validation->set_rules('ticket_subject', 'Ticket Subject', 'required');
        if ($this->input->post('contact_id')) {
            $contact_implode = implode(',', $this->input->post('contact_id'));
        }
        if ($this->input->post('team_member_id')) {
            $team_member_implode = implode(',', $this->input->post('team_member_id'));
        }
        if ($this->input->post('ticket_subject')) {
            $ticket_update['ticket_subject'] = strip_slashes($this->input->post('ticket_subject'));
        }
        $ticket_update['client_id'] = $this->input->post('client_id');
        $compaigndata['user_id'] = $this->session->userdata('LOGGED_IN')['ID'];
        //$ticket_update['contact_id'] = $contact_implode;
        $ticket_update['ticket_subject'] = $this->input->post('ticket_subject');
        $ticket_update['product_id'] = $this->input->post('product_id');
        $ticket_update['type'] = $this->input->post('type');
        $ticket_update['status'] = $this->input->post('status');
        $ticket_update['due_date'] = $this->input->post('due_date');
        $ticket_update['priority'] = $this->input->post('priority');
        $ticket_update['ticket_desc'] = $this->input->post('ticket_desc', false);
        $ticket_update['suport_team'] = $this->input->post('team_id');
        //$ticket_update['support_user'] = $team_member_implode;
        if ($this->input->post('email_notification') == null) {

            $email_notification = '0';
        } else {
            $email_notification = '1';
        }
        if ($this->input->post('agent_notify') == null) {

            $agent_notification = '0';
        } else {
            $agent_notification = '1';
        }
        $ticket_update['email_notification'] = $email_notification;
        $ticket_update['agent_notify'] = $agent_notification;
        $ticket_update['created_date'] = datetimeformat();
        //update Record in Database
        $where = array('ticket_id' => $id);
        $success_insert = $this->common_model->update(TICKET_MASTER, $ticket_update, $where);
        $insert_id = $this->db->insert_id();
        /* ticket activity start */
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        /* $firstname=$this->session->userdata('LOGGED_IN')['FIRSTNAME'];
          $lastname=$this->session->userdata('LOGGED_IN')['LASTNAME']; */
        $ticketactivity['user_id'] = $user_id;
        $ticketactivity['ticket_id'] = $id;
        $ticketactivity['activity'] = 'Updated New Ticket';
        $ticketactivity['activity_date'] = datetimeformat();
        $success_insert = $this->common_model->insert(TICKET_ACTIVITY, $ticketactivity);
        /* ticket activity end */
        $contact_id = $this->input->post('contact_id');
        $this->common_model->delete(TICKET_CONTACT, "ticket_id=" . $id);

        for ($i = 0; $i < count($contact_id); $i++) {
            $ticket_contact['ticket_id'] = $id;
            $ticket_contact['contact_id'] = $contact_id[$i];
            $ticket_contact['created_date'] = datetimeformat();
            $ticket_contact['modified_date'] = datetimeformat();
            $where = array('ticket_id' => $id);


            $this->common_model->insert(TICKET_CONTACT, $ticket_contact);
        }
        /* for contact data insert end */

        /* for support team user data insert start */
        $supportteamuser = $this->input->post('team_member_id');
        $this->common_model->delete(TICKET_SUPPORT_USER, "ticket_id=" . $id);
        for ($i = 0; $i < count($supportteamuser); $i++) {
            $support_team_user['ticket_id'] = $id;
            $support_team_user['support_user'] = $supportteamuser[$i];
            $support_team_user['created_date'] = datetimeformat();
            $support_team_user['modified_date'] = datetimeformat();
            $where = array('ticket_id' => $id);

            $this->common_model->insert(TICKET_SUPPORT_USER, $support_team_user);
        }
        /* for support team user data insert end */
        /* image upload */
        /* image upload code */
        $file_name = array();
        $file_array1 = $this->input->post('file_data');

        $file_name = $_FILES['fileUpload']['name'];
        if (count($file_name) > 0 && count($file_array1) > 0) {
            $differentedImage = array_diff($file_name, $file_array1);
            foreach ($file_name as $file) {
                if (in_array($file, $differentedImage)) {
                    $key_data[] = array_search($file, $file_name); // $key = 2;
                }
            }
            if (!empty($key_data)) {
                foreach ($key_data as $key) {
                    unset($_FILES['fileUpload']['name'][$key]);
                    unset($_FILES['fileUpload']['type'][$key]);
                    unset($_FILES['fileUpload']['tmp_name'][$key]);
                    unset($_FILES['fileUpload']['error'][$key]);
                    unset($_FILES['fileUpload']['size'][$key]);
                }
            }
        }

        $_FILES['fileUpload'] = $arr = array_map('array_values', $_FILES['fileUpload']);
        $data['lead_view'] = $this->viewname;
        $uploadData = uploadImage('fileUpload', ticket_upload_path, $data['lead_view']);

        $Marketingfiles = array();
        foreach ($uploadData as $dataname) {
            $Marketingfiles[] = $dataname['file_name'];
        }
        $marketing_file_str = implode(",", $Marketingfiles);

        $file2 = $this->input->post('fileToUpload');
        if (!(empty($file2))) {
            $file_data = implode(",", $file2);
        } else {
            $file_data = '';
        }
        if (!empty($marketing_file_str) && !empty($file_data)) {
            $marketingdata['file'] = $marketing_file_str . ',' . $file_data;
        } else if (!empty($marketing_file_str)) {
            $marketingdata['file'] = $marketing_file_str;
        } else {
            $marketingdata['file'] = $file_data;
        }
        $marketingdata['file_name'] = $file_data;
        if ($marketingdata['file_name'] != '') {
            $explodedData = explode(',', $marketingdata['file_name']);

            foreach ($explodedData as $img) {
                array_push($uploadData, array('file_name' => $img));
            }
        }

        $costFiles = array();

        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $est_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $costFiles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'ticket_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
                }
            }
        }

        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => Ticket_upload_path, 'ticket_id' => $id, 'upload_status' => 0, 'created_date' => datetimeformat()];
            }
        }
//pr($costFiles);exit;
        if (count($costFiles) > 0) {

            if (!$this->common_model->insert_batch(FILES_TICKET_MASTER, $costFiles)) {
                $msg = $this->lang->line('ticket_add_msg');
                $this->session->set_flashdata('msg', $msg);
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
            $this->common_model->delete(FILES_TICKET_MASTER, 'file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */


        $contact_id = $this->input->post('contact_id');

        if ($success_insert) {
            $msg = $this->lang->line('ticket_edit_msg');
            $this->session->set_flashdata('msg', $msg);
        }
		$redirectLink = $_SERVER['HTTP_REFERER'];
		if (strpos($redirectLink, 'Account/viewdata') !== false) {
			$sess_array = array('setting_current_tab' => 'SupportTickets');
            $this->session->set_userdata($sess_array);
			redirect($redirectLink);
		}
		elseif (strpos($redirectLink, 'CrmCompany/view') !== false) {
			$sess_array = array('setting_current_tab' => 'SupportTickets');
            $this->session->set_userdata($sess_array);
			redirect($redirectLink);
		}
		else{
			 redirect($this->viewname);  //Redirect On Listing page
		}
       
    }

    /*
      @Author : Ghelani Nikunj
      @Desc   : Ticket Delete
      @Input 	:
      @Output	:
      @Date   : 4/03/2016
     */

    public function deletedata($id) {

        $redirect_link = $this->input->get('link');
        $data['lead_view'] = $this->viewname;
        if (!empty($id)) {
            $where = array('ticket_id' => $id);
            $ticket_data['is_delete'] = 1;
            $delete_suceess = $this->common_model->update(TICKET_MASTER, $ticket_data, $where);
            /* ticket activity start */
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];
            /* $firstname=$this->session->userdata('LOGGED_IN')['FIRSTNAME'];
              $lastname=$this->session->userdata('LOGGED_IN')['LASTNAME']; */
            $ticketactivity['user_id'] = $user_id;
            $ticketactivity['ticket_id'] = $id;
            $ticketactivity['activity'] = 'Delete Ticket';
            $ticketactivity['activity_date'] = datetimeformat();
            $success_insert = $this->common_model->insert(TICKET_ACTIVITY, $ticketactivity);
            /* ticket activity end */
            if ($delete_suceess) {
                $msg = $this->lang->line('ticket_del_msg');
                $this->session->set_flashdata('msg', $msg);
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('error', $msg);
            }
            unset($id);
        }
		$redirectLink = $_SERVER['HTTP_REFERER'];
		if (strpos($redirectLink, 'Account/viewdata') !== false) {
			$sess_array = array('setting_current_tab' => 'SupportTickets');
            $this->session->set_userdata($sess_array);
			redirect($redirectLink);
		}
		elseif (strpos($redirectLink, 'CrmCompany/view') !== false) {
			$sess_array = array('setting_current_tab' => 'SupportTickets');
            $this->session->set_userdata($sess_array);
			redirect($redirectLink);
		}
		else{
			redirect($this->viewname); //Redirect On Listing page
		}
        
    }

    public function editdata($id) {
      
        //Get Records From CONTACT_MASTER Table
        $redirect_link = $this->input->post('redirect_link');
        $table1 = CONTACT_MASTER . ' as cm';
        $match1 = "";
        $fields1 = array("cm.contact_id,cm.contact_name");
        $data['prospect_owner'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        //Get Records From BRANCH_MASTER Table       
        $table2 = BRANCH_MASTER . ' as bm';
        $match2 = "";
        $fields2 = array("bm.branch_id,bm.branch_name");
        $data['branch_data'] = $this->common_model->get_records($table2, $fields2, '', '', $match2);
        //Get Records From CAMPAIGN_MASTER Table       
        $table3 = CAMPAIGN_MASTER . ' as cam';
        $match3 = "";
        $fields3 = array("cam.campaign_id,cam.campaign_name");
        $data['campaign'] = $this->common_model->get_records($table3, $fields3, '', '', $match3);
        //Get Records From PRODUCT_MASTER Table       
        $table4 = PRODUCT_MASTER . ' as prm';
        $match4 = "";
        $fields4 = array("prm.product_id,prm.product_name");
        $data['product_data'] = $this->common_model->get_records($table4, $fields4, '', '', $match4);
        //Get Records From PRODUCT_MASTER Table       
        $table5 = TASK_MASTER . ' as td';
        $match5 = "";
        $fields5 = array("td.task_id,td.task_name,td.importance,td.remember,td.task_description,td.start_date,"
            . "td.end_date");
        $data['task_data'] = $this->common_model->get_records($table5, $fields5, '', '', $match5);

        //Get Records From COUNTRIES Table       
        $table7 = COUNTRIES . ' as c';
        $match7 = "";
        $fields7 = array("c.country_id,c.country_name");
        $data['country_data'] = $this->common_model->get_records($table7, $fields7, '', '', $match7);
        //Get Records From COMPANY_MASTER Table       
        $table8 = COMPANY_MASTER . ' as cmp';
        $match8 = "";
        $fields8 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table8, $fields8, '', '', $match8);
        //Get Records From PROSPECT_MASTER Table
        $table9 = LEAD_MASTER . ' as lm';
        $match9 = "lm.lead_id = " . $id;
        $fields9 = array("lm.lead_id,lm.prospect_auto_id,lm.prospect_name,lm.company_id,"
            . "lm.address1,lm.address2,lm.creation_date,lm.postal_code,lm.city,lm.state,lm.country_id,lm.number_type,lm.phone_no,lm.prospect_owner_id,lm.language_id,"
            . "lm.branch_id,lm.estimate_prospect_worth,lm.prospect_generate,lm.campaign_id,lm.description,"
            . "lm.file,lm.contact_date,lm.status");
        $data['edit_record'] = $this->common_model->get_records($table9, $fields9, '', '', $match9);

        $table10 = LEAD_CONTACTS_TRAN . ' as pcm';
        $match10 = "pcm.lead_id = " . $id;
        $fields10 = array("pcm.primary_contact,pcm.contact_name,pcm.email_id,pcm.phone_no");
        $data['opportunity_contact_data'] = $this->common_model->get_records($table10, $fields10, '', '', $match10);

        $table11 = LEAD_PRODUCTS_TRAN . ' as pt';
        $match11 = "pt.lead_id = " . $id;
        $fields11 = array("pt.product_id");
        $dataopportunity_product_data = $this->common_model->get_records($table11, $fields11, '', '', $match11);
        $product_id = array();
        if (!empty($dataopportunity_product_data)) {
            foreach ($dataopportunity_product_data as $dataopportunity_product_data) {
                $product_id[] = $dataopportunity_product_data['product_id'];
            }
        }
        $data['opportunity_product_data'] = $product_id;
        //Get data from lead file master
        $fields12 = array("*");
        $table12 = FILES_LEAD_MASTER . ' as lf';
        $match12 = 'lf.lead_id=' . $id . '';
        $data['prospect_files'] = $this->common_model->get_records($table12, $fields12, '', '', $match12);

        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");

        $data['modal_title'] = $this->lang->line('update_lead');
        $data['submit_button_title'] = $this->lang->line('update_lead');
        $data['lead_view'] = $this->viewname;
        $this->load->view('AddEditLead', $data);
    }

    public function uploadImage($input, $path, $redirect, $file_name = null, $file_ext_tolower = false, $encrypt_name = false, $remove_spaces = false, $detect_mime = true) {
        $files = $_FILES;
        $FileDataArr = array();
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|png|pdf';
        $config['max_size'] = 10000;
//        $config['max_width'] = 1024;
//        $config['max_height'] = 768;
        $config['file_ext_tolower'] = $file_ext_tolower;
        $config['encrypt_name'] = $encrypt_name;
        $config['remove_spaces'] = $remove_spaces;
        $config['detect_mime'] = $detect_mime;
        if ($file_name != null) {
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
      @Author : Ghelani Nikunj
      @Desc   : Download image
      @Input 	:
      @Output	:
      @Date   : 18/02/2016
     */

    function download($id) {

        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = FILES_TICKET_MASTER . ' as CM';
            $params['match_and'] = 'CM.file_id=' . $id . '';
            $prospect_files = $this->common_model->get_records_array($params);
            if (count($prospect_files) > 0) {
                $pth = file_get_contents(base_url($prospect_files[0]['file_path'] . '/' . $prospect_files[0]['file_name']));
                $this->load->helper('download');
                force_download($prospect_files[0]['file_name'], $pth);
            }
            redirect($this->viewname);
        }
    }

    /*
      @Author : Ghelani Nikunj
      @Desc   : Delete image
      @Input 	:
      @Output	:
      @Date   : 18/02/2016
     */

    public function deleteImage($id) {
        //Delete Record From Database
        if (!empty($id)) {

            $where = array('file_id' => $id);
            if ($this->common_model->delete(FILES_LEAD_MASTER, $where)) {
                echo json_encode(array('status' => 1, 'error' => ''));
                die;
            } else {
                echo json_encode(array('status' => 0, 'error' => 'Someting went wrong!'));
                die;
            }

            unset($id);
        }
    }

    public function update_status() {

        $id = $this->input->post('id');
        $type = $this->input->post('type');
        if (!empty($id)) {
            $where = array('ticket_id' => $id);
            $ticket_data['status'] = $type;
            $delete_suceess = $this->common_model->update(TICKET_MASTER, $ticket_data, $where);
            $table1 = TICKET_MASTER . ' as tk';
            $match1 = "tk.is_delete=0 AND tk.status=" . $type;
            $fields1 = array("count(tk.ticket_id) as total_count");
            $total_count = $this->common_model->get_records($table1, $fields1, '', '', $match1);
            $data['total_count'] = $total_count[0]['total_count'];

            /* for new status count */
            $table2 = TICKET_MASTER . ' as tk';
            $match2 = "tk.is_delete=0 AND tk.status=1";
            $fields2 = array("count(tk.ticket_id) as total_count");
            $total_count = $this->common_model->get_records($table2, $fields2, '', '', $match2);
            $data['count_new'] = $total_count[0]['total_count'];

            /* for assign count */
            $table3 = TICKET_MASTER . ' as tk';
            $match3 = "tk.is_delete=0 AND tk.status=5";
            $fields3 = array("count(tk.ticket_id) as total_count");
            $total_count = $this->common_model->get_records($table3, $fields3, '', '', $match3);
            $data['count_assign'] = $total_count[0]['total_count'];

            /* for inprogress count */
            $table4 = TICKET_MASTER . ' as tk';
            $match4 = "tk.is_delete=0 AND tk.status=2";
            $fields4 = array("count(tk.ticket_id) as total_count");
            $total_count = $this->common_model->get_records($table4, $fields4, '', '', $match4);
            $data['count_inprogress'] = $total_count[0]['total_count'];

            /* for onhold count */
            $table5 = TICKET_MASTER . ' as tk';
            $match5 = "tk.is_delete=0 AND tk.status=3";
            $fields5 = array("count(tk.ticket_id) as total_count");
            $total_count = $this->common_model->get_records($table5, $fields5, '', '', $match5);
            $data['count_onhold'] = $total_count[0]['total_count'];

            /* for complete count */
            $table6 = TICKET_MASTER . ' as tk';
            $match6 = "tk.is_delete=0 AND tk.status=4";
            $fields6 = array("count(tk.ticket_id) as total_count");
            $total_count = $this->common_model->get_records($table6, $fields6, '', '', $match6);
            $data['count_complete'] = $total_count[0]['total_count'];

            /* for today count */
            $table6 = TICKET_MASTER . ' as tk';
            $match6 = "tk.is_delete=0 AND tk.due_date = '" . date('Y-m-d') . "'";
            $fields6 = array("count(tk.ticket_id) as total_count");
            $total_count = $this->common_model->get_records($table6, $fields6, '', '', $match6);
            $data['count_today'] = $total_count[0]['total_count'];

            /* for open count */
            $table8 = TICKET_MASTER . ' as tk';
            $match8 = "tk.is_delete=0 AND (tk.status='2' OR tk.status='5')";
            $fields8 = array("count(tk.ticket_id) as total_open");
            $total_open = $this->common_model->get_records($table8, $fields8, '', '', $match8);
            $data['count_open'] = $total_open[0]['total_open'];

            /* for overdue count */
            $table7 = TICKET_MASTER . ' as tk';
            $match7 = "tk.is_delete=0 AND tk.due_date > '" . date('Y-m-d') . "'";
            $fields7 = array("count(tk.ticket_id) as total_count");
            $total_count = $this->common_model->get_records($table7, $fields7, '', '', $match7);
            $data['count_overdue'] = $total_count[0]['total_count'];
            /* ticket activity start */
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];
            /* $firstname=$this->session->userdata('LOGGED_IN')['FIRSTNAME'];
              $lastname=$this->session->userdata('LOGGED_IN')['LASTNAME']; */
            $ticketactivity['user_id'] = $user_id;
            $ticketactivity['ticket_id'] = $id;
            $ticketactivity['activity'] = 'update ticket status';
            $ticketactivity['activity_date'] = datetimeformat();
            $success_insert = $this->common_model->insert(TICKET_ACTIVITY, $ticketactivity);
            /* ticket activity end */
            unset($id);
        }
        echo json_encode(array('status' => 1, 'count_today' => $data['count_today'], 'count_overdue' => $data['count_overdue'], 'count_data' => $data['total_count'], 'count_new' => $data['count_new'], 'count_assign' => $data['count_assign'], 'count_onhold' => $data['count_onhold'], 'count_complete' => $data['count_complete'], 'count_inprogress' => $data['count_inprogress'], 'count_open' => $data['count_open']));
    }

    public function upload_file($fileext = '') {

        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('Ticket_img_url') . '/' . $filename, $str);
    }
    
        public function grantview() {

        $data = array();
//Get milistone
        $login_id = $this->session->userdata['LOGGED_IN']['ID'];
        $_GET['end'] = date('Y-m-d', strtotime('-1 day', strtotime($_GET['end'])));
        $color = '';
        $fields = array('task_id,task_name,start_date,end_date,status,importance');
        $wheresting = "(start_date >='" . $_GET['start'] . "' and start_date <='" . $_GET['end'] . "') and created_by=" . $login_id . " and  is_delete = 0";

        $tasks = $this->common_model->get_records(SUPPORT_TASK_MASTER, $fields, '', '', '', '', '', '', '', '', '', $wheresting, '', '');

        if (!empty($tasks)) {
            foreach ($tasks as $row1) {
                if ($row1['importance'] == 'High') {
                    $color = '#E30512';
                } elseif ($row1['importance'] == 'Medium') {
                    $color = '#F6A342';
                } else if ($row1['importance'] == 'Low') {
                    $color = '#4EA426';
                }

                $data[] = array('id' => $row1['task_id'], 'title' => $row1['task_name'], 'start' => $row1['start_date'], 'end' => $row1['end_date'], 'color' => $color, 'url' => base_url('Task/viewtask/' . $row1['task_id']));
            }
        }

        $meeting_fields = array('meeting_master_id,meet_title,meet_date_time,created_date,is_delete');
        $meeting_wheresting = "(meet_date_time >='" . $_GET['start'] . "' and meet_date_time <='" . $_GET['end'] . "') and meet_user_id=" . $login_id . " and is_delete = 0";
        $meeting = $this->common_model->get_records(TBL_SCHEDULE_MEETING_MASTER, $meeting_fields, '', '', '', '', '', '', '', '', '', $meeting_wheresting, '', '');

        if (!empty($meeting)) {
            foreach ($meeting as $row2) {
                $meeting_date = date('Y-m-d', strtotime($row2['meet_date_time']));
                $data[] = array('id' => $row2['meeting_master_id'], 'title' => $row2['meet_title'], 'start' => $meeting_date, 'end' => $meeting_date, 'color' => '#e36705', 'url' => base_url('Meeting/view_meeting/' . $row2['meeting_master_id']));
            }
        }
        echo json_encode($data);
    }

}
