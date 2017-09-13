<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
		date_default_timezone_set($this->session->userdata('LOGGED_IN')['TIMEZONE']);

    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : listing of SalesOverview Dashboard
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

    public function index() {
		
        $data['user_info'] = $this->session->userdata('LOGGED_IN');  //Current Login information

        /**
         * tasks pagination Function called before data variable
         */
		$this->breadcrumbs->push(lang('support'),'/Support');
		$this->breadcrumbs->push(lang('support_dashboard'),' ');
        $dataSales = $this->salesPaginationData();
        $dataTask = $this->taskPaginationData();
        $data = array_merge($dataSales, $dataTask);

        $data['main_content'] = $this->module . '/SupportView';
        $data['project_view'] = $this->module . '/' . $this->viewname;
		$data['js_content'] = 'loadJsFiles';
        $data['project_view'] = $this->viewname;

        $data['activities_total'] = 0;
        $data['activities'] = 0;

        /**
         * tasks pagination starts 
         */
        $page_url = $data['config']['base_url'] . '/' . $data['taskPage'];
        $page_url_sales = $data['config1']['base_url'] . '/' . $data['salePage'];
        $data['pagination'] = $this->pagingConfig($data['config'], $page_url);
        $data['paginationSales'] = $this->pagingConfig($data['config1'], $page_url_sales);

        /**
         * tesk pagination ends
         */
        /* for new ticket */
        $table1 = TICKET_MASTER . ' as tk';
        $match1 = "tk.is_delete=0 AND tk.status='1'";
        $fields1 = array("count(tk.ticket_id) as total_new_ticket");
        $total_ticket = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        $data['total_new_ticket'] = $total_ticket[0]['total_new_ticket'];

        /* end */
        /* for assign ticket */
        $table2 = TICKET_MASTER . ' as tk';
        $match2 = "tk.is_delete=0 AND tk.status='5'";
        $fields2 = array("count(tk.ticket_id) as total_assign_ticket");
        $total_ticket = $this->common_model->get_records($table2, $fields2, '', '', $match2);
        $data['total_assign_ticket'] = $total_ticket[0]['total_assign_ticket'];
        /* end */
        /* for onhold ticket */
        $table3 = TICKET_MASTER . ' as tk';
        $match3 = "tk.is_delete=0 AND tk.status='3'";
        $fields3 = array("count(tk.ticket_id) as total_onhols_ticket");
        $total_ticket = $this->common_model->get_records($table3, $fields3, '', '', $match3);
        $data['total_onhols_ticket'] = $total_ticket[0]['total_onhols_ticket'];
        /* end */
        /* for Running ticket */
        $table4 = TICKET_MASTER . ' as tk';
        $match4 = "tk.is_delete=0 AND tk.status='2'";
        $fields4 = array("count(tk.ticket_id) as total_running_ticket");
        $total_ticket = $this->common_model->get_records($table4, $fields4, '', '', $match4);
        $data['total_running_ticket'] = $total_ticket[0]['total_running_ticket'];
        /* end */
        /* for Completed ticket */
        $table5 = TICKET_MASTER . ' as tk';
        $match5 = "tk.is_delete=0 AND tk.status='4'";
        $fields5 = array("count(tk.ticket_id) as total_completed_ticket");
        $total_ticket = $this->common_model->get_records($table5, $fields5, '', '', $match5);
        $data['total_completed_ticket'] = $total_ticket[0]['total_completed_ticket'];
        /* end */
		
		 /* for open ticket */
        $table5 = TICKET_MASTER . ' as tk';
        $match5 = "tk.is_delete=0 AND (tk.status='2' OR tk.status='5')";
        $fields5 = array("count(tk.ticket_id) as total_open_ticket");
        $total_ticket = $this->common_model->get_records($table5, $fields5, '', '', $match5);
        $data['total_open_ticket'] = $total_ticket[0]['total_open_ticket'];
        /* end */
		
		/*for today count*/
			$table6 = TICKET_MASTER . ' as tk';
			$match6 = "tk.is_delete=0 AND tk.due_date = '".date('Y-m-d')."'";
			$fields6 = array("count(tk.ticket_id) as total_count");
			$total_count = $this->common_model->get_records($table6, $fields6, '', '', $match6);
			$data['total_due_ticket'] = $total_count[0]['total_count'];
			
			/*for overdue count*/
			$table7 = TICKET_MASTER . ' as tk';
			$match7 = "tk.is_delete=0 AND tk.due_date < '".date('Y-m-d')."'";
			$fields7 = array("count(tk.ticket_id) as total_count");
			$total_count = $this->common_model->get_records($table7, $fields7, '', '', $match7);
			$data['total_over_ticket'] = $total_count[0]['total_count'];
        /* TICKET DATA LISTING */

        $params3['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=tk.user_id', SUPPORT_STATUS . ' as ss' => 'ss.status_id=tk.status');
        $params3['join_type'] = 'left';
        $match3 = "tk.is_delete=0";
        $table3 = TICKET_MASTER . ' as tk';
        //$group_by3 = 'stm.member_id';
        $fields3 = array("tk.due_date,tk.created_date,tk.ticket_id,tk.ticket_subject,tk.ticket_desc,tk.status,l.firstname,l.lastname,ss.status_name");
        $data['ticket_data'] = $this->common_model->get_records($table3, $fields3, $params3['join_tables'], $params3['join_type'], $match3, '', '', '', '', '', '');
    
        /* TICKET DATA LISTING END */
        /* Ticket Recent Activity Start */
         $sortfield = 'activity_id';
        $sortby = 'desc';
     
        $params15['join_tables'] = array(LOGIN . ' as l' => 'l.login_id=ta.user_id',TICKET_MASTER . ' as tm' => 'ta.ticket_id=tm.ticket_id');
        $params15['join_type'] = 'left';
        $match15 = "";
        $table15 = TICKET_ACTIVITY . ' as ta';
        $fields15 = array("l.firstname,l.lastname,ta.activity,ta.activity_date,ta.ticket_id,tm.ticket_subject");
        $data['ticket_recent_data'] = $this->common_model->get_records($table15, $fields15, $params15['join_tables'], $params15['join_type'], $match15, '', '', '', $sortfield, $sortby, '');

        /* Ticket Recent Activity End */
        $data['header'] = array('menu_module' => 'support');
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $prospectWhere = 'status_type=3';
        $data['count_clients'] = count($this->common_model->get_records(PROSPECT_MASTER, array('prospect_id'), '', '', $prospectWhere));
        $opportunitiesWhere = 'status_type=2';
        $data['count_opportunities'] = count($this->common_model->get_records(PROSPECT_MASTER, array('prospect_id'), '', '', $opportunitiesWhere));
        $lostClientsWhere = 'status_type=1';
        $data['count_lost_clients'] = count($this->common_model->get_records(PROSPECT_MASTER, array('prospect_id'), '', '', $lostClientsWhere));
        $data['drag'] = true;
        /*
         * dashboard default drag and drop code starts
         */
        $defaultDashboard = array('sectionLeft' => array('btn','TicketBox', 'TicketTable'), 'sectionRight' => array('AjaxTasks', 'TaskCalendar', 'SupportActivities'));
        if ($this->session->has_userdata('dashboard_support_widgets')) {
            $data['widgets'] = $this->session->userdata('dashboard_support_widgets');
        } else {
            $data['widgets'] = $defaultDashboard;
        }
        $this->parser->parse('layouts/SupportTemplate', $data);
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : view for ticket
      @Input 	:
      @Output	:
      @Date   : 24/02/2016
     */

    public function add() {

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
        $table8 = COMPANY_MASTER . ' as cmp';
        $match8 = "";
        $fields8 = array("cmp.company_id,cmp.company_name");
        $data['company_data'] = $this->common_model->get_records($table8, $fields8, '', '', $match8);


        $this->load->view('AddFinal', $data);
        //$this->parser->parse('layouts/DashboardTemplate', $data);
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : add for ticket
      @Input 	:
      @Output	:
      @Date   : 24/02/2016
     */

    public function saveTicketData() {

        $this->form_validation->set_rules('campaign_name', 'Campaign Name', 'required');

        if ($this->input->post('campaign_name')) {
            $compaigndata['campaign_name'] = strip_slashes($this->input->post('campaign_name'));
        }
        $compaigndata['campaign_auto_id'] = $this->input->post('campaign_auto_id');
        $compaigndata['campaign_type_id'] = $this->input->post('campaign_type_id');
        $compaigndata['responsible_employee_id'] = $this->input->post('responsible_employee_id');
        $compaigndata['start_date'] = date_format(date_create($this->input->post('start_date')), 'Y-m-d');
        $compaigndata['end_date'] = date_format(date_create($this->input->post('end_date')), 'Y-m-d');
        $compaigndata['campaign_description'] = strip_slashes($this->input->post('campaign_description'));
        $file2 = $this->input->post('fileToUpload');
        $compaigndata['file'] = implode(",", $file2);
        $budget_requirement = '0';
        if ($this->input->post('budget_requirement') == 'on') {
            $budget_requirement = '1';
        }

        $compaigndata['budget_requirement'] = $budget_requirement;
        $compaigndata['budget_ammount'] = $this->input->post('budget_ammount');
        $campaign_supplier = '0';
        if ($this->input->post('campaign_supplier') == 'on') {
            $campaign_supplier = '1';
        }
        $compaigndata['campaign_supplier'] = $campaign_supplier;
        $revenue_goal = '0';
        if ($this->input->post('revenue_goal') == 'on') {
            $revenue_goal = '1';
        }
        $compaigndata['revenue_goal'] = $revenue_goal;
        $compaigndata['revenue_amount'] = $this->input->post('revenue_amount');
        $related_product = '';
        if ($this->input->post('related_product') == 'on') {
            $related_product = '1';
        }
        $compaigndata['related_product'] = $related_product;
        $compaigndata['supplier_id'] = $this->input->post('supplier_id');
        $compaigndata['product_id'] = $this->input->post('product_id');
        $compaigndata['campaign_group_id'] = $this->input->post('campaign_group_id');
        $compaigndata['status'] = '1';
        $compaigndata['created_date'] = datetimeformat();
        $compaigndata['modified_date'] = datetimeformat();
        //Insert Record in Database

        $success_insert = $this->common_model->insert(CAMPAIGN_MASTER, $compaigndata);
        $insert_id = $this->db->insert_id();
        $contact_id = $this->input->post('contact_id');
        for ($i = 0; $i < count($contact_id); $i++) {
            $campaign_receipents_tran['campaign_id'] = $insert_id;
            $campaign_receipents_tran['contact_id'] = $contact_id[$i];
            $campaign_receipents_tran['created_date'] = datetimeformat();
            $campaign_receipents_tran['modified_date'] = datetimeformat();
            $campaign_receipents_tran['status'] = '1';
            $this->common_model->insert(CAMPAIGN_RECEIPIENT_TRAN, $campaign_receipents_tran);
        }
        if ($success_insert) {
            $msg = $this->lang->line('campign_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }

        redirect($this->viewname);  //Redirect On Listing page
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : listing of Support Dashboard
      @Input 	:
      @Output	:
      @Date   : 03/03/2016
     */

    public function taskAjaxList() {
        $data = $this->taskPaginationData();
        $data['main_content'] = $this->module . '/SupportView';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $data['js_content'] = 'loadJsFiles';
        $page_url = $data['config']['base_url'] . '/' . $data['page'];
        $data['pagination'] = $this->pagingConfig($data['config'], $page_url);
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $this->load->view($this->module . '/AjaxTasks', $data);
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : listing of SalesOverview Dashboard
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

    public function salesAjaxList() {
        $data = $this->salesPaginationData();
        $data['main_content'] = $this->module . '/SupportView';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $data['js_content'] = 'loadJsFiles';
        $page_url = $data['config1']['base_url'] . '/' . $data['page'];
        $data['paginationSales'] = $this->pagingConfig($data['config1'], $page_url);
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $this->load->view($this->module . '/AjaxSales', $data);
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : Common pagination initialization
      @Input 	:
      @Output	:
      @Date   : 01/02/2016
     */

    private function pagingConfig($config, $page_url) {
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01 pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . $page_url . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';

        $this->pagination->cur_page = 4;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : Custom common Salespagination function for reducing code
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

     function taskPaginationData() {
        $this->load->library('pagination');
        $data['project_view'] = $this->module;
        //Get Records From COST_MASTER Table       
        //config variable for the pagination
        $config['base_url'] = site_url($data['project_view'] . '/taskAjaxList');
        $dbSearch = "";
        $data['tasksortField'] = 'task_id';
        $data['tasksortOrder'] = 'desc';
        $fields = array("td.task_id,td.task_name,td.importance,td.remember,td.task_description,td.start_date,"
            . "td.end_date");
        $created = $this->session->userdata('LOGGED_IN')['ID'];
        $where = array("td.is_delete" => "0", "td.status" => "1", "td.created_by" => $created);
        if ($this->input->get('search') != '') {
            $data['search'] = $term = $this->input->get('search');

            $searchFields = array('td.task_name,td.importance,td.remember,td.task_description,td.start_date,td.end_date');
            foreach ($searchFields as $fields):
                $dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
            endforeach;
            $dbSearch = substr($dbSearch, 0, -3);
        }
        $config['total_rows'] = count($this->common_model->get_records(SUPPORT_TASK_MASTER . ' as td', $fields, '', '', $dbSearch, '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where));
        $config['per_page'] = 5;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['tasksortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['tasksortOrder'] = $this->input->get('sortOrder');
        }

        $data['page'] = $data['taskPage'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['drag']=false;
        $data['task_data'] = $this->common_model->get_records(SUPPORT_TASK_MASTER . ' as td', $fields, '', '', $dbSearch, '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where);
        $data['config'] = $config;
        return $data;
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : Custom common Salespagination function for reducing code
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

    function salesPaginationData() {
        $this->load->library('pagination');
        $data['project_view'] = $this->module;
        //Get Records From COST_MASTER Table       
        //config variable for the pagination
        $table = PROSPECT_MASTER . ' as pm';

        $data['opportunity_view'] = $this->viewname;
        $data['status'] = array(1 => 'Prospect', 2 => 'Lead', 3 => 'Client');
        $config1['base_url'] = site_url($data['project_view'] . '/salesAjaxList');
        $data['salessortField'] = 'pm.prospect_id';
        $data['salessortOrder'] = 'desc';
        $dbSearch = "";
        $params['join_tables'] = array(OPPORTUNITY_REQUIREMENT_CONTACTS . ' as pc' => 'pm.prospect_id=pc.prospect_id');
        $params['join_type'] = 'inner';
        $match = "";
        $group_by = 'pm.prospect_id';
        $fields = array("pm.prospect_id,count(pm.prospect_id) as opp_count,pm.prospect_name,pm.prospect_auto_id, pm.status_type,count(pc.prospect_id) as contact_count,pc.contact_id,pm.creation_date");

        $config1['total_rows'] = count($this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $dbSearch, '', '', '', $data['salessortField'], $data['salessortOrder'], $group_by));
        $config1['per_page'] = RECORD_PER_PAGE;
        $choice = $config1["total_rows"] / $config1["per_page"];
        $config1["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['salessortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['salessortOrder'] = $this->input->get('sortOrder');
        }

        if ($this->input->get('search') != '') {
            $data['search'] = $term = $this->input->get('search');
            $searchFields = array('pm.prospect_name', 'pm.status_type');
            foreach ($searchFields as $fieldsarr):
                $dbSearch.=" " . $fieldsarr . " like '%" . $term . "%'  or ";
            endforeach;
            $dbSearch = substr($dbSearch, 0, -3);
        }
        $data['salePage'] = $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['prospect_data'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $dbSearch, '', $config1['per_page'], $data['page'], $data['salessortField'], $data['salessortOrder'], $group_by);
        $data['config1'] = $config1;
        return $data;
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : To Do Calender
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

    


    public function dashboardWidgetsOrder() {
        if (!$this->input->is_ajax_request()) {
            exit("no direct scripts allowed");
        } else {
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];
            if ($this->input->get('resetWidgets')) {

                $defaultDashboard = array('sectionLeft' => array('btn', 'TicketBox', 'TicketTable'), 'sectionRight' => array('AjaxTasks', 'TaskCalendar', 'SupportActivities'));
                $data = array('dashboard_support_widgets' => json_encode($defaultDashboard));
                $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                $this->session->unset_userdata('dashboard_support_widgets');
                $this->session->set_userdata('dashboard_support_widgets', $defaultDashboard);
                echo json_encode(array('status' => 1, 'type' => 'reset'));
                die;
            } else {
                $placeHolderDiv1 = $this->input->post('placeholder1');
                $innerWidgets1 = $this->input->post('innerWidgets1');
                $placeHolderDiv2 = $this->input->post('placeholder2');
                $innerWidgets2 = $this->input->post('innerWidgets2');
                $widgetsArr = array();
                $widgetsArr = array();
                if (count($innerWidgets1) > 0) {

                    $widgetsArr[$placeHolderDiv1] = $innerWidgets1;
                }
                if (count($innerWidgets2) > 0) {
                    $widgetsArr[$placeHolderDiv2] = $innerWidgets2;
                }
                if (count($widgetsArr) > 0) {
                    $data = array('dashboard_support_widgets' => json_encode($widgetsArr));
                    $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                    $this->session->unset_userdata('dashboard_support_widgets');
                    $this->session->set_userdata('dashboard_support_widgets', $widgetsArr);
                }
                echo json_encode(array('status' => 1, 'type' => 'new'));
                die;
            }
        }
    }
    
    //ishani dave
    
    public function grantview() {
       
        $table = SUPPORT_TASK_MASTER;
        $data = array();

        $login_id = $this->session->userdata['LOGGED_IN']['ID'];

       
       $_GET['end'] = date('Y-m-d', strtotime($_GET['end']));
            
               
        $color = '';
        $fields = array('task_id,task_name,start_date,end_date,status,importance');
        $wheresting = "(start_date >='" . $_GET['start'] . "' and end_date <='" . $_GET['end'] . "') and created_by=" . $login_id . " and  is_delete = 0 and (end_date >= '" . date('Y-m-d') . "')";

        $tasks = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $wheresting, '', '');
        // echo $this->db->last_query(); exit;
        if (!empty($tasks)) {
            foreach ($tasks as $row1) {
                if ($row1['importance'] == 'High') {
                    $color = '#E30512';
                } elseif ($row1['importance'] == 'Medium') {
                    $color = '#F6A342';
                } else if ($row1['importance'] == 'Low') {
                    $color = '#4EA426';
                }
                  $endDt = date('Y-m-d', date(strtotime("+1 day", strtotime($row1['end_date']))));
                    $data[] = array('id' => $row1['task_id'], 'title' => $row1['task_name'], 'start' => $row1['start_date'], 'end' => $endDt, 'color' => $color, 'url' => base_url('SupportTask/viewtask/' . $row1['task_id']));
                             
            }
        }
         $table_public = TBL_SCHEDULE_MEETING_MASTER . ' as pa, ' . TBL_SCHEDULE_MEETING_RECEIPENT . ' as mr';
        $fields_public = array('pa.meeting_master_id,pa.meet_title,pa.meeting_date,pa.created_date,pa.is_delete');
        $where_public = "(pa.meeting_date >='" . $_GET['start'] . "' and pa.meeting_date <='" . $_GET['end'] . "') and (pa.is_delete =  0 and pa.is_public = 1) and (pa.meeting_date >= '" . date('Y-m-d') . "')";
        $public_meeting = $this->common_model->get_records($table_public, $fields_public, '', '', '', '', '', '', '', '', 'pa.meeting_master_id', $where_public, '', '');

        $table_private = TBL_SCHEDULE_MEETING_MASTER . ' as pa, ' . TBL_SCHEDULE_MEETING_RECEIPENT . ' as mr';
        $fields_private = array('pa.meeting_master_id,pa.meet_title,pa.meeting_date,pa.created_date,pa.is_delete');
        $where_private = "(pa.meeting_date >='" . $_GET['start'] . "' and pa.meeting_date <='" . $_GET['end'] . "') and (pa.is_delete =  0 and pa.is_private = 1 and mr.is_delete= 0) and (mr.user_id= '$login_id' or pa.meet_user_id='$login_id') and (pa.meeting_date >= '" . date('Y-m-d') . "')";
        $private_meeting = $this->common_model->get_records($table_private, $fields_private, '', '', '', '', '', '', '', '', 'pa.meeting_master_id', $where_private);

        $meeting = array_unique(array_merge($public_meeting, $private_meeting), SORT_REGULAR);

  
            if (!empty($meeting)) {
                foreach ($meeting as $row2) {
                    $meeting_date = date('Y-m-d', strtotime($row2['meeting_date']));
                    $data[] = array('id' => $row2['meeting_master_id'], 'title' => $row2['meet_title'], 'start' => $meeting_date, 'end' => $meeting_date, 'color' => '#e36705', 'url' => base_url('Meeting/view_meeting/' . $row2['meeting_master_id']));
                }
            }
     
       
        echo json_encode($data);
    }
        
}
