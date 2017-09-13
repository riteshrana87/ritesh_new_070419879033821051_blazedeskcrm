<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SalesOverview extends CI_Controller {

    function __construct() {
        parent::__construct();
        if(checkPermission('SalesOverview','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->prefix = $this->db->dbprefix;
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(2);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : listing of SalesOverview Dashboard
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

    public function index() {
        /**
         * tasks pagination Function called before data variable
         */
        $dataSales = $this->salesPaginationData();
        $dataTask = $this->taskPaginationData();
        $data = array_merge($dataSales, $dataTask);

        $data['main_content'] = $this->module . '/SalesOverview';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $data['js_content'] = 'loadJsFiles';
        $data['task_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'crm');
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
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");

//FOR COUNT CLIENT
        $prospectWhere = 'status_type=2 and is_delete=0 and status=1 and created_by=' . $this->session->userdata('LOGGED_IN')['ID'];
        $data['count_clients'] = count($this->common_model->get_records(PROSPECT_MASTER, array('prospect_id'), '', '', $prospectWhere));

//FOR COUNT OPPORTUNITIES
        $opportunitiesWhere = 'status_type=1 and is_delete=0 and status=1 and created_by=' . $this->session->userdata('LOGGED_IN')['ID'];
        $data['count_opportunities'] = count($this->common_model->get_records(PROSPECT_MASTER, array('prospect_id'), '', '', $opportunitiesWhere));

//FOR COUNT LOST CLIENT
        $lostClientsWhere = 'status_type=4 and is_delete=0 and status=1 and created_by=' . $this->session->userdata('LOGGED_IN')['ID'];
        $data['count_lost_clients'] = count($this->common_model->get_records(PROSPECT_MASTER, array('prospect_id'), '', '', $lostClientsWhere));
//FOR GET GRAPH 
        $data['salesTargetProgressGraph'] = $this->salesTargetProgressGraph();
        $data['drag'] = true;
        /*
         * default drag and drop sales overview code starts over here
         */
        $defaultDashboard = array('widgetCalender', 'progressView', 'AjaxTasks', 'SalesListView');
        if ($this->session->has_userdata('salesoverview_dashboard_widgets')) {
            $data['widgets'] = $this->session->userdata('salesoverview_dashboard_widgets');
        } else {
            $data['widgets'] = $defaultDashboard;
        }
        $this->parser->parse('layouts/DashboardTemplate', $data);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : listing of SalesOverview Dashboard
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

    public function taskAjaxList() {
        $data = $this->taskPaginationData();
        $data['main_content'] = $this->module . '/SalesOverview';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $data['js_content'] = 'loadJsFiles';
        $page_url = $data['config']['base_url'] . '/' . $data['page'] . '?search=' . $data['search'];
        $data['pagination'] = $this->pagingConfig($data['config'], $page_url);
        $data['url'] = base_url("Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
        $this->load->view($this->module . '/AjaxTasks', $data);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : listing of SalesOverview Dashboard
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

    public function salesAjaxList() {
        $data = $this->salesPaginationData();
        $data['drag'] = true;
        $data['main_content'] = $this->module . '/SalesOverview';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $data['js_content'] = 'loadJsFiles';
        $page_url = $data['config1']['base_url'] . '/' . $data['page'] . '?search=' . $data['search'];
        $data['paginationSales'] = $this->pagingConfig($data['config1'], $page_url);
        $this->load->view($this->module . '/AjaxSales', $data);
    }

    /*
      @Author : Maulik Suthar
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

        //$config['first_link'] = '&lt;&lt;';
        // $config['last_link'] = '&gt;&gt;';
        $config['num_links'] = 2;

        $this->pagination->cur_page = 4;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /*
      @Author : Maulik Suthar
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
        $data['search'] = '';
        $data['tasksortField'] = 'task_id';
        $data['tasksortOrder'] = 'desc';
        $fields = array("td.task_id,td.task_name,td.importance,td.remember,td.task_description,td.start_date,"
            . "td.end_date");
        $where = array("td.is_delete" => "0", "td.status" => "1", 'td.created_by' => $this->session->userdata('LOGGED_IN')['ID']);
        if ($this->input->get('search') != '') {

            $data['search'] = $term = $this->input->get('search');
            $term = html_entity_decode(trim(addslashes($term)));
            $task_date = date('Y-m-d', strtotime($term));
            $dbSearch.='(td.task_name LIKE "%' . $term . '%" OR td.end_date LIKE "%' . $task_date . '%" )';
//            $searchFields = array('td.task_name');
//            foreach ($searchFields as $fields):
//                $dbSearch.=" " . $fields . " like '%" . $term . "%'  or ";
//            endforeach;
//            $dbSearch = substr($dbSearch, 0, -3);
        }
        $config['total_rows'] = count($this->common_model->get_records(TASK_MASTER . ' as td', $fields, '', '', $dbSearch, '', '', '', $data['tasksortField'], $data['tasksortOrder'], '', $where));
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
        $data['drag'] = true;
        $data['task_data'] = $this->common_model->get_records(TASK_MASTER . ' as td', $fields, '', '', $dbSearch, '', $config['per_page'], $data['page'], $data['tasksortField'], $data['tasksortOrder'], '', $where);
        $data['config'] = $config;
        return $data;
    }

    /*
      @Author : Maulik Suthar
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
        $data['lead_view'] = '/LEAD';
        $data['drag'] = true;
        $table = PROSPECT_MASTER . ' as pm';
        $data['opportunity_view'] = $this->viewname;
        $data['status'] = array(1 => lang('opportunity'), 2 => lang('lead'), 3 => lang('client'));
        $config1['base_url'] = site_url($data['project_view'] . '/salesAjaxList');
        $data['salessortField'] = 'creation_date';
        $data['salessortOrder'] = 'desc';
        $where_search = '';
        $data['search'] = '';


//        $dbSearch = "";
//        $params['join_tables'] = array(
//            OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc' => 'orc.prospect_id=pm.prospect_id',
//            CONTACT_MASTER . ' as cm' => 'cm.contact_id=orc.contact_id ');
//        $params['join_type'] = 'left';
//        $match = "";
//        $group_by = 'pm.prospect_id';
// $fields = array("pm.prospect_id,count(pm.prospect_id) as opp_count,pm.prospect_name,pm.prospect_auto_id, pm.status_type,(select count(pc.prospect_id) from ".$this->prefix.CONTACT_MASTER." as cm inner join ".$this->prefix.OPPORTUNITY_REQUIREMENT_CONTACTS." as pc on cm.contact_id=pc.contact_id where cm.is_delete=0 and pc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_count,(select cm.contact_name from ".$this->prefix.CONTACT_MASTER." as cm inner join ".$this->prefix.OPPORTUNITY_REQUIREMENT_CONTACTS." as pc on cm.contact_id=pc.contact_id where pc.primary_contact=1 and cm.is_delete=0 and pc.prospect_id=pm.prospect_id group by pm.prospect_id) as contact_name,pm.creation_date");
// $where = array("pm.is_delete" => "0", "pm.status" => "1");
        if ($this->input->get('search') != '') {
            $data['search'] = $searchtext = $this->input->get('search');
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $client_since = date('Y-m-d', strtotime($searchtext));
            $where_search = '(prospect_name LIKE "%' . $searchtext . '%" OR prospect_auto_id LIKE "%' . $searchtext . '%" OR status_type LIKE "%' . $searchtext . '%" OR creation_date LIKE "%' . $client_since . '%"  OR contact_name LIKE "%' . $searchtext . '%")';
        }
        $config1['total_rows'] = count($this->common_model->getSalesoverviewData('', '', $where_search));
        $config1['per_page'] = RECORD_PER_PAGE;
        $choice = $config1["total_rows"] / $config1["per_page"];
        $config1["num_links"] = floor($choice);

        if ($this->input->get('orderField') != '') {
            $data['salessortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['salessortOrder'] = $this->input->get('sortOrder');
        }


        $data['salePage'] = $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['prospect_data'] = $this->common_model->getSalesoverviewData($config1['per_page'], $data['page'], $where_search, $data['salessortField'], $data['salessortOrder']);
        $data['config1'] = $config1;
        return $data;
    }

    /*
      @Author : Maulik Suthar
      @Desc   : To Do Calender
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

    public function grantview() {
        $module = $this->input->get('module');
        $table = TASK_MASTER;
        if ($module == 'support') {
            $table = SUPPORT_TASK_MASTER;
        }
        $data = array();
//Get milistone
        $login_id = $this->session->userdata['LOGGED_IN']['ID'];
        $_GET['end'] = date('Y-m-d', strtotime('-1 day', strtotime($_GET['end'])));
        $color = '';
        $fields = array('task_id,task_name,start_date,end_date,status,importance');
        $wheresting = "((start_date >='" . $_GET['start'] . "' and start_date <='" . $_GET['end'] . "') or(end_date >='" . $_GET['start'] . "' and end_date <='" . $_GET['end'] . "')) and created_by=" . $login_id . " and  is_delete = 0";

        $tasks = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $wheresting, '', '');
        // echo $this->db->last_query();
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
                if ($module == 'support') {
                    $data[] = array('id' => $row1['task_id'], 'title' => $row1['task_name'], 'start' => $row1['start_date'], 'end' => $endDt, 'color' => $color, 'url' => base_url('SupportTask/viewtask/' . $row1['task_id']));
                } else {
                    $data[] = array('id' => $row1['task_id'], 'title' => $row1['task_name'], 'start' => $row1['start_date'], 'end' => $endDt, 'color' => $color, 'url' => base_url('Task/viewtask/' . $row1['task_id']));
                }
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

        if ($module != 'support') {
            if (!empty($meeting)) {
                foreach ($meeting as $row2) {
                    $meeting_date = date('Y-m-d', strtotime($row2['meeting_date']));
                    $data[] = array('id' => $row2['meeting_master_id'], 'title' => $row2['meet_title'], 'start' => $meeting_date, 'end' => $meeting_date, 'color' => '#e36705', 'url' => base_url('Meeting/view_meeting/' . $row2['meeting_master_id']));
                }
            }
        }

        $table = CAMPAIGN_MASTER . ' as ct';
        $where = array("ct.status" => "1");
        $fields = array("ct.campaign_id,ct.campaign_name,ct.start_date,ct.end_date,ct.campaign_auto_id,ct.campaign_type_id,ct.responsible_employee_id,ctm.camp_type_name");
        $join_tables = array('blzdsk_campaign_type_master as ctm' => 'ctm.camp_type_id = ct.campaign_type_id');
        $campaign_info = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);



        if (!empty($campaign_info)) {
            foreach ($campaign_info as $row2) {
                $start_date = date('Y-m-d', strtotime($row2['start_date']));
                $campaign_end_date = date('Y-m-d', strtotime($row2['end_date']));

                $data[] = array('id' => $row2['campaign_id'], 'title' => $row2['campaign_name'], 'start' => $start_date, 'end' => $campaign_end_date, 'color' => '#20B2AA', 'url' => base_url('Marketingcampaign/view_page/'.$row2['campaign_id']));
            }
        }


        echo json_encode($data);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Update Prospect status form the widget
      @Input 	:
      @Output	:
      @Date   : 09/02/2016
     */

    function update_status() {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $currtype = $this->input->post('currtype');
        $login_id = $this->session->userdata['LOGGED_IN']['ID'];
        $dataType = $this->input->post('dataType');
        $won = lang('won');
        $lost = lang('lost');
        $new = lang('new');
        $prospect = lang('prospect');

        if ($id > 0 && $id != '') {
            if ($type == $new && $currtype == $prospect) {
                $this->ProspectToLead($id);
                echo "done";
                //  $this->common_model->delete(LEAD_CONTACTS_TRAN, 'lead_id=' . $insertId . '');
//                $this->common_model->update(LEAD_MASTER, array('status_type' => 2, 'created_by' => $login_id), array('lead_id' => $insertId));
            } elseif ($type == $prospect && $currtype == $new) {

                $this->leadToProspect($id, $dataType);
                echo "done";
//                $query = 'INSERT INTO blzdsk_prospect_master(`prospect_auto_id`, `prospect_name`, `status_type`, `company_id`, `address1`, `address2`, `creation_date`, `postal_code`, `city`, `state`, `country_id`, `number_type1`, `phone_no`, `number_type2`, `phone_no2`, `prospect_owner_id`, `language_id`, `branch_id`, `estimate_prospect_worth`, `prospect_generate`, `campaign_id`, `description`, `file`, `contact_date`, `is_delete`, `created_date`, `modified_date`, `status`,`created_by`) select `prospect_auto_id`, `prospect_name`, `status_type`, `company_id`, `address1`, `address2`, `creation_date`, `postal_code`, `city`, `state`, `country_id`, `number_type1`, `phone_no`, `number_type2`, `phone_no2`, `prospect_owner_id`, `language_id`, `branch_id`, `estimate_prospect_worth`, `prospect_generate`, `campaign_id`, `description`, `file`, `contact_date`, `is_delete`, `created_date`, `modified_date`, `status`,`created_by` from blzdsk_lead_master where lead_id=' . $id . '';
//                $this->db->query($query);
//                $insertId = $this->db->insert_id();
////  $this->common_model->delete(LEAD_MASTER, 'lead_id=' . $id . '');
//                $this->common_model->update(LEAD_MASTER, array('is_delete' => 1, 'created_by' => $login_id), array('lead_id' => $id));
//                $this->common_model->update(PROSPECT_MASTER, array('status_type' => 1, 'created_by' => $login_id), array('prospect_id' => $insertId));
            } elseif ($type == $won && $currtype == $new) {

                $this->leadToProspect($id, $dataType);
                echo "done";
//                $match = "pm.lead_id = " . $id . "";
//                $table = LEAD_MASTER . ' as pm';
//                $LeadData = $this->common_model->get_records($table, '', '', '', $match);
//                if (count($LeadData) > 0) {
//                    $LeadDataIns = array(
//                        'prospect_auto_id' => $LeadData[0]['prospect_auto_id'],
//                        'prospect_name' => $LeadData[0]['prospect_name'],
//                        'status_type' => 3,
//                        'company_id' => $LeadData[0]['company_id'],
//                        'address1' => $LeadData[0]['address1'],
//                        'address2' => $LeadData[0]['address2'],
//                        'creation_date' => $LeadData[0]['creation_date'],
//                        'postal_code' => $LeadData[0]['postal_code'],
//                        'city' => $LeadData[0]['city'],
//                        'state' => $LeadData[0]['state'],
//                        'country_id' => $LeadData[0]['country_id'],
//                        'number_type1' => $LeadData[0]['number_type1'],
//                        'phone_no' => $LeadData[0]['phone_no'],
//                        'number_type2' => $LeadData[0]['number_type2'],
//                        'phone_no2' => $LeadData[0]['phone_no2'],
//                        'prospect_owner_id' => $LeadData[0]['prospect_owner_id'],
//                        'language_id' => $LeadData[0]['language_id'],
//                        'branch_id' => $LeadData[0]['branch_id'],
//                        'estimate_prospect_worth' => $LeadData[0]['estimate_prospect_worth'],
//                        'prospect_generate' => $LeadData[0]['prospect_generate'],
//                        'campaign_id' => $LeadData[0]['campaign_id'],
//                        'description' => $LeadData[0]['description'],
//                        'file' => $LeadData[0]['file'],
//                        'contact_date' => $LeadData[0]['contact_date'],
//                        'is_delete' => $LeadData[0]['is_delete'],
//                        'created_date' => $LeadData[0]['created_date'],
//                        'modified_date' => $LeadData[0]['modified_date'],
//                        'status' => $LeadData[0]['status'],
//                        'created_by' => $LeadData[0]['created_by'],
//                    );
//                    $insertId = $this->common_model->insert(PROSPECT_MASTER, $LeadDataIns);
//                }
//                $this->common_model->update(LEAD_MASTER, array('is_delete' => 1, 'created_by' => $login_id), array('lead_id' => $id));
//                $this->common_model->update(PROSPECT_MASTER, array('status_type' => 3, 'created_by' => $login_id), array('prospect_id' => $insertId));
            } elseif ($type == $lost && $currtype == $new) {
                $this->leadToProspect($id, $dataType);
                echo "done";
//                $match = "pm.lead_id = " . $id . "";
//                $table = LEAD_MASTER . ' as pm';
//                $LeadData = $this->common_model->get_records($table, '', '', '', $match);
//                if (count($LeadData) > 0) {
//                    $LeadDataIns = array(
//                        'prospect_auto_id' => $LeadData[0]['prospect_auto_id'],
//                        'prospect_name' => $LeadData[0]['prospect_name'],
//                        'status_type' => 4,
//                        'company_id' => $LeadData[0]['company_id'],
//                        'address1' => $LeadData[0]['address1'],
//                        'address2' => $LeadData[0]['address2'],
//                        'creation_date' => $LeadData[0]['creation_date'],
//                        'postal_code' => $LeadData[0]['postal_code'],
//                        'city' => $LeadData[0]['city'],
//                        'state' => $LeadData[0]['state'],
//                        'country_id' => $LeadData[0]['country_id'],
//                        'number_type1' => $LeadData[0]['number_type1'],
//                        'phone_no' => $LeadData[0]['phone_no'],
//                        'number_type2' => $LeadData[0]['number_type2'],
//                        'phone_no2' => $LeadData[0]['phone_no2'],
//                        'prospect_owner_id' => $LeadData[0]['prospect_owner_id'],
//                        'language_id' => $LeadData[0]['language_id'],
//                        'branch_id' => $LeadData[0]['branch_id'],
//                        'estimate_prospect_worth' => $LeadData[0]['estimate_prospect_worth'],
//                        'prospect_generate' => $LeadData[0]['prospect_generate'],
//                        'campaign_id' => $LeadData[0]['campaign_id'],
//                        'description' => $LeadData[0]['description'],
//                        'file' => $LeadData[0]['file'],
//                        'contact_date' => $LeadData[0]['contact_date'],
//                        'is_delete' => $LeadData[0]['is_delete'],
//                        'created_date' => $LeadData[0]['created_date'],
//                        'modified_date' => $LeadData[0]['modified_date'],
//                        'status' => $LeadData[0]['status'],
//                        'created_by' => $LeadData[0]['created_by'],
//                    );
//                    $insertId = $this->common_model->insert(PROSPECT_MASTER, $LeadDataIns);
//                }
//
////  $this->common_model->delete(LEAD_MASTER, 'lead_id=' . $id . '');
//                $this->common_model->update(LEAD_MASTER, array('is_delete' => 1, 'created_by' => $login_id), array('lead_id' => $id));
//                $this->common_model->update(PROSPECT_MASTER, array('status_type' => 4, 'created_by' => $login_id), array('prospect_id' => $insertId));
//                echo "done";
            } elseif ($type == $new && $currtype == $won) {

                $this->ProspectToLead($id, $dataType);
                echo "done";
            } else {
                $this->common_model->update(PROSPECT_MASTER, array('status_type' => $dataType, 'created_by' => $login_id), array('prospect_id' => $id));
                //  echo $this->db->last_query();
                echo "done";
            }
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : salesTargetProgressGraph
      @Input 	:
      @Output	:
      @Date   : 06/04/2016
     */
    /*
      function salesTargetProgressGraph() {

      $login_id = $this->session->userdata['LOGGED_IN']['ID'];
      $currency = $this->config->item('currency');
      $salesAmmount = "";
      $target = "";
      $converted_amount_target = "";
      $converted_amount_target = "";
      $country_code = "";
      $data['converted_amount'] = "";
      $data['converted_amount_target'] = "";
      $data['sales_difference'] = "";
      $data['sales_amount_by_user'] = "";


      $table = PROSPECT_MASTER . ' as pm';
      $match = " pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND MONTH(CURDATE()) and YEAR(pm.created_date) = YEAR(CURDATE()) ";
      // $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 and MONTH(CURDATE()) and YEAR(pm.created_date) = YEAR(CURDATE())";
      $fields = array(" SUM(em.value)  AS createdAccount , ");
      $params['join_tables'] = array(ESTIMATE_MASTER . ' as em' => 'em.estimate_id= pm.estimate_prospect_worth');
      $params['join_type'] = 'left';
      $sale_aacount_count = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '');

      $match = "sts.is_delete=0 AND  sts.status=1  AND sts.login_id = " . $login_id;
      $fields = array("sts.target , c.currency_symbol AS currency_symbol , sts.country_id");
      $params['join_tables'] = array(COUNTRIES . ' as c' => 'c.country_id = sts.country_id');
      $params['join_type'] = 'left';
      $data['sales_target'] = $this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $match, '');

      $gensettingWhere = "config_key='general_settings'";
      $defaultDashboard1 = $this->common_model->get_records(CONFIG, array('value'), '', '', $gensettingWhere);
      $generalSettings = (array) json_decode($defaultDashboard1[0]['value']);

      if(isset($generalSettings['default_currency']) && !empty($generalSettings['default_currency'])){
      $table	= COUNTRIES.' as c';
      $match = "c.country_id=".$generalSettings['default_currency'];
      $fields = array("c.currency_code,c.currency_symbol");
      $sale_aacount_count = $this->common_model->get_records($table, $fields, '', '', $match);
      $data['symbol'] = $sale_aacount_count[0]['currency_symbol'];

      }

      if (isset($sale_aacount_count[0]['createdAccount'])) {
      $salesAmmount = $sale_aacount_count[0]['createdAccount'];
      }

      if (isset($data['sales_target'][0]['target'])) {
      $target = $data['sales_target'][0]['target'];
      }

      if (isset($data['sales_target'][0]['country_id'])) {
      $country_code = getCourrencyCode($data['sales_target'][0]['country_id']);
      }

      //	pr($country_code[0]['currency_code']); exit;
      if ($salesAmmount != "" && $target != "") {

      $converted_amount = helperConvertCurrency($salesAmmount, $country_code[0]['currency_code'], $currency);
      $converted_amount_target = helperConvertCurrency($target, $country_code[0]['currency_code'], $currency);
      $data['converted_amount'] = $converted_amount;
      $data['converted_amount_target'] = $converted_amount_target;
      $data['sales_difference'] = $target - $salesAmmount;
      $data['sales_amount_by_user'] = $salesAmmount;
      }



      if (!empty($data['sales_target'][0]['target'])) {
      if ($data['converted_amount'] > 0) {
      $data['sales_percentage'] = round(($data['converted_amount'] * 100) / $data['converted_amount_target'], 2);
      } else {
      $data['sales_percentage'] = 0;
      }

      } else {
      $data['sales_percentage'] = 0;
      }
      return $data;
      }
     */

    function salesTargetProgressGraph() {

        $login_id = $this->session->userdata['LOGGED_IN']['ID'];
        $currencyData = $this->getDefaultCurrency();
        if (count($currencyData) > 0) {
            $currency = $currencyData['currency_code'];
            $symbol = $currencyData['symbol'];
            $data['symbol'] = $symbol;
        } else {
            $currency = $this->config->item('currency');

            $symbol = '$';
            $data['symbol'] = $symbol;
        }
        $salesAmmount = "";
        $target = "";
        $converted_amount_target = "";
        $converted_amount_target = "";
        $country_code = "";
        $salesAmmountArr = array();
        $data['converted_amount'] = "";
        $data['converted_amount_target'] = "";
        $data['sales_difference'] = "";
        $data['sales_amount_by_user'] = "";
//        $table = PROSPECT_MASTER . ' as pm';
////$match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.prospect_assign = ".$login_id;
//        $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id . ' and MONTH(CURDATE()) and YEAR(created_date) = YEAR(CURDATE())';
//        $fields = array(" SUM(`estimate_prospect_worth`)  AS createdAccount");
//        $sale_aacount_count = $this->common_model->get_records($table, $fields, '', '', $match);
//        

        $table = PROSPECT_MASTER . ' as pm';
        $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 AND pm.created_by = " . $login_id . ' and MONTH(CURDATE()) and YEAR(pm.created_date) = YEAR(CURDATE())';
        // $match = "pm.status_type = 3 AND pm.status =1 AND pm.is_delete=0 and MONTH(CURDATE()) and YEAR(pm.created_date) = YEAR(CURDATE())";
        $fields = array("em.value  AS createdAccount ,c.currency_code,em.estimate_id");
        $params['join_tables'] = array(ESTIMATE_MASTER . ' as em' => 'em.estimate_id= pm.estimate_prospect_worth', COUNTRIES . ' as c' => 'c.country_id = em.country_id_symbol');
        $params['join_type'] = 'left';
        $sale_aacount_count = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $match, '');
        //  pr($sale_aacount_count);
        // get Login user's sales Target
        $match = "sts.is_delete=0 AND  sts.status=1  AND sts.login_id = " . $login_id;
        $fields = array("sts.target , c.currency_symbol AS currency_symbol , sts.country_id");
        $params['join_tables'] = array(COUNTRIES . ' as c' => 'c.country_id = sts.country_id');
        $params['join_type'] = 'left';
        $data['sales_target'] = $this->common_model->get_records(SALES_TARGET_SETTINGS . ' as sts', $fields, $params['join_tables'], $params['join_type'], $match, '');
        if (count($sale_aacount_count) > 0) {
            foreach ($sale_aacount_count as $row) {
                if ($row['currency_code'] != $currency) {
                    $salesAmmountArr[] = helperConvertCurrency($row['createdAccount'], $row['currency_code'], $currency);
                } else {
                    $salesAmmountArr[] = $row['createdAccount'];
                }
            }
            $salesAmmount = array_sum($salesAmmountArr);
        } else {
            $salesAmmount = 0;
        }

        if (isset($data['sales_target'][0]['target'])) {
            $target = $data['sales_target'][0]['target'];
        }

        if (isset($data['sales_target'][0]['country_id'])) {
            $country_code = getCourrencyCode($data['sales_target'][0]['country_id']);
        }
        if ($target != "") {
            // echo $currency;
            if ($country_code[0]['currency_code'] != $currency) {
                $converted_amount = helperConvertCurrency($salesAmmount, $country_code[0]['currency_code'], $currency);
                $converted_amount_target = helperConvertCurrency($target, $country_code[0]['currency_code'], $currency);
            } else {
                $converted_amount = $salesAmmount;
                $converted_amount_target = $target;
            }
            //  echo $converted_amount_target;
            //echo $country_code[0]['currency_code'];
            $data['converted_amount'] = number_format($converted_amount, 2, '.', '');
            $data['converted_amount_target'] = number_format($converted_amount_target, 2, '.', '');
            $data['sales_difference'] = number_format($converted_amount_target - $converted_amount, 2, '.', '');
            $data['sales_amount_by_user'] = number_format($salesAmmount, 2, '.', '');
        }
        if ($data['sales_difference'] == 0) {
            $data['sales_difference'] = $target;
        }
        if (!empty($data['sales_target'][0]['target'])) {
            if ($data['converted_amount'] > 0) {
                $data['sales_percentage'] = round(($data['converted_amount'] * 100) / $data['converted_amount_target'], 2);
            } else {
                $data['sales_percentage'] = 0;
            }

//  $milistone_per += round(($task_per * $per_devide) / 100, 2);
//echo "PERCENTEAHE :".$data['sales_percentage']; exit;
        } else {
            $data['sales_percentage'] = 0;
        }


        return $data;
    }

    function view_meeting() {
        
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $data['modal_title'] = $this->lang->line('SCHEDULE_MEETING');
            $data['submit_button_title'] = $this->lang->line('SCHEDULE_MEETING');
            $data['sales_view'] = $this->viewname;
            $data['contact_id'] = '';
            $table1 = CONTACT_MASTER . ' as cm';
            $match1 = " cm.is_delete=0 and cm.status=1 ";
            $fields1 = array("cm.contact_id,cm.contact_name");
            $contact_participants = $data['contact_data'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);


            $participants_arr = [];
            foreach ($contact_participants as $con_participants) {
                $participants_arr[] = array('user_id' => $con_participants['contact_id'], 'user_name' => $con_participants['contact_name'], 'user_type' => '2');
            }
            $employee_participants = $data['system_employee'] = $this->common_model->getSystemUserData();

            $logged_in_user_id = $this->session->userdata('LOGGED_IN')['ID'];
            foreach ($employee_participants as $emp_participants) {
                if ($logged_in_user_id != $emp_participants['login_id']) {
                    $emp_name = $emp_participants['firstname'] . " " . $emp_participants['lastname'];
                    $participants_arr[] = array('user_id' => $emp_participants['login_id'], 'user_name' => $emp_name, 'user_type' => '1');
                }
            }

            $table_company = COMPANY_MASTER . ' as c';
            $match_company = " c.is_delete=0 and c.status=1 ";
            $fields_company = array("c.company_id,c.company_name");

            $data['company_data'] = $company_participants = $this->common_model->get_records($table_company, $fields_company, '', '', $match_company);

            foreach ($company_participants as $company_participants) {
                $participants_arr[] = array('user_id' => $company_participants['company_id'], 'user_name' => $company_participants['company_name'], 'user_type' => '3');
            }
            $data['company_id'] = '';
            $data['edited_id'] = '';
            $data['meeting_particiapnts'] = $participants_arr;
            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");
            $data['drag'] = true;
            $data['edit_participants'] = [];
            $data['form_action'] = 'addUpdateMeeting';
            $data['meeting_related_id'] = '';
            $data['display_from'] = 'dashboard_salesoverview';
            $data['main_content'] = '/AddEditMeetingNew';
            $this->load->view('Contact/AddEditMeetingNew', $data);

        }
        
    }

    function view_message() {
        if (!$this->input->is_ajax_request()) 
        {
            exit('No direct script access allowed');
        }else
        {
            $data['modal_title'] = $this->lang->line('SEND_MESSAGE');
            $data['submit_button_title'] = $this->lang->line('SEND_MESSAGE');
            $data['sales_view'] = $this->viewname;
            $login_id = $this->session->userdata('LOGGED_IN')['ID'];
            $user_data = $this->common_model->getSystemUserData();

            //remove logged in user from user
            foreach ($user_data as $user_key => $user_val) {
                if ($user_val['login_id'] == $login_id) {
                    unset($user_data[$user_key]);
                }
            }
            $data['drag'] = true;
            $data['user_data'] = $user_data;
            $data['display_from'] = 'dashboard_salesoverview';
            $data['main_content'] = '/SendMessage';
            $this->load->view('SendMessage', $data);

        }
        
    }

    function view_email($id = NULL) {
        if (!$this->input->is_ajax_request()) 
        {
                exit('No direct script access allowed');
        }else
        {
            $table = CONTACT_MASTER . ' as cm';
            $match = "cm.is_delete = 0";
            $fields = array("cm.contact_id,cm.contact_name");
            $data['contact_record'] = $this->common_model->get_records($table, $fields, '', '', $match);

            $data['url'] = base_url("/Projectmanagement/Filemanager/index/?dir=uploads/&modal=true");

            $data['email_template_data'] = $this->common_model->getEmailTemplateData();
            $data['contact_id'] = $id;

            $data['drag'] = true;
            $data['modal_title'] = lang('SEND_EMAIL');
            $data['main_content'] = '/ContactSendEmail';
            $this->load->view('/ContactSendEmail', $data);
        }
        
    }

    function sendMessageEmail() {

        $hdn_contact_id = $this->input->post('hdn_contact_id');
        $redirect_link = $this->input->post('redirect_link');
        $from_email = $_SESSION['LOGGED_IN']['EMAIL'];
        $from_name = $_SESSION['LOGGED_IN']['FIRSTNAME'] . " " . $_SESSION['LOGGED_IN']['LASTNAME'];

        if (!validateFormSecret()) {

            redirect($redirect_link); //Redirect On Listing page
        }

        $arr_receipent_email = $this->input->post('receipent_email');


        $contact_receipent_email = $this->getContactEmailbyId($arr_receipent_email);
//pr($_POST);exit;
        $email_subject = $this->input->post('email_subject');
        $email_contect = $this->input->post('email_content');

        $hdn_mark_as_important = $this->input->post('hdn_mark_as_important');

//pr($arr_receipent_email);exit;
//capture data in email communication
        $email_communication['comm_date'] = date('Y-m-d');
        $email_communication['comm_sender'] = $_SESSION['LOGGED_IN']['ID'];
//$email_communication['comm_receiver']  =implode(',',$arr_receipent_email); 
        $email_communication['comm_receiver'] = $arr_receipent_email;
        $email_communication['comm_subject'] = $email_subject;
        $email_communication['comm_content'] = $email_contect;
        $email_communication['comm_type'] = 3;
        $email_communication['is_delete'] = 0;
        $email_communication['comm_related_id'] = $arr_receipent_email;
        $email_communication['created_date'] = datetimeformat();

        $id = $this->common_model->insert(TBL_EMAIL_COMMUNICATION, $email_communication);

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

        $tmp_url = base_url() . "Contact";
        $uploadData = uploadImage('cost_files', EMAIL_EVENT_ATTACH_PATH, $tmp_url);

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
        $attach_arr_gallary = array();
        if ($this->input->post('gallery_path')) {
            $gallery_path = $this->input->post('gallery_path');
            $cost_files = $this->input->post('gallery_files');
            if (count($gallery_path) > 0) {
                for ($i = 0; $i < count($gallery_path); $i++) {
                    $attach_arr_gallary[] = FCPATH . $gallery_path[$i] . $cost_files[$i];
                    $costFiles[] = ['file_name' => $cost_files[$i], 'file_path' => $gallery_path[$i], 'email_communication_id' => $id, 'created_date' => datetimeformat(), 'upload_status' => 1];
                }
            }
        }
        $attach_arr_browse = array();
        if (count($uploadData) > 0) {
            foreach ($uploadData as $files) {
                $attach_arr_browse[] = FCPATH . "uploads/event_email_attach/" . $files['file_name'];
                $costFiles[] = ['file_name' => $files['file_name'], 'file_path' => EMAIL_PROSPECT_ATTACH_PATH, 'email_communication_id' => $id, 'upload_status' => 0, 'created_date' => date('Y-m-d h:i:s')];
            }
        }

        $email_atached_arr = array_merge($attach_arr_gallary, $attach_arr_browse);

        if (count($costFiles) > 0) {
            $where = array('email_prospect_id' => $id);


            if (!$this->common_model->insert_batch(TBL_EMAIL_COMMUNICATION_FILE_MASTER, $costFiles)) {

                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($this->module . '/Contact/'); //Redirect On Listing page
            }
        }

        if ($id) {

            //$CI = & get_instance();
            /* $configs = getMailConfig();

              $config['protocol'] = $configs['email_protocol'];
              $config['smtp_host'] = $configs['smtp_host']; //change this
              $config['smtp_port'] = $configs['smtp_port'];
              $config['smtp_user'] = $configs['smtp_user']; //change this
              $config['smtp_pass'] = $configs['smtp_pass']; //change this
             */


            /* $config['mailtype'] = 'html';
              $config['charset'] = 'iso-8859-1';
              $config['wordwrap'] = TRUE;
              $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8 */

            /* $CI->load->library('email', $config); // load email library

              $CI->email->set_header('MIME-Version', '1.0\r\n');
              $CI->email->set_header('Disposition-Notification-To', $from_email);



              $CI->email->from($from_email, $from_name);
              $CI->email->to($contact_receipent_email);

              // $this->email->cc($cc_email_address);
              //$this->email->bcc('them@their-example.com');

              if (count($email_atached_arr) > 0) {
              foreach ($email_atached_arr as $attach) {
              $this->email->attach($attach);
              }
              }


              $CI->email->subject($email_subject);
              $CI->email->message($email_contect); */

            $config = array();
            if ($hdn_mark_as_important == "1") {
                $config['priority'] = 1;
            }
            //set header
            $headers = array('MIME-Version' => '1.0\r\n', 'Disposition-Notification-To' => $from_email);

            $sent = send_mail1($contact_receipent_email, $email_subject, $email_contect, $email_atached_arr, $from_email, $from_name, $cc = '', $bcc = '', $headers, $config);

            if ($sent) {
                $msg = $this->lang->line('SENT_MAIL_SUCCESFFULLY');
                $this->session->set_flashdata('message', $msg);

                $email_communication['comm_date'] = date('Y-m-d');
                $email_communication['email_prospect_id'] = $id;
                $email_communication['comm_sender'] = $_SESSION['LOGGED_IN']['ID'];
                $email_communication['comm_receiver'] = $arr_receipent_email;
                $email_communication['comm_subject'] = $email_subject;
                $email_communication['comm_content'] = $email_contect;
                $email_communication['comm_type'] = 2;
                $email_communication['is_delete'] = 0;
                $email_communication['comm_related_id'] = $arr_receipent_email;
                $email_communication['created_date'] = datetimeformat();
                $this->common_model->insert(TBL_EMAIL_COMMUNICATION, $email_communication);
            } else {

                $msg = $this->lang->line('FAIL_WITH_SENDING_EMAIL');
                $this->session->set_flashdata('error', $msg);
            }
        } else {

            $msg = $this->lang->line('error');
            $this->session->set_flashdata('error', $msg);
        }


        $sess_array = array('setting_current_tab' => 'Events');
        $this->session->set_userdata($sess_array);
        unset($email_atached_arr);
        redirect($redirect_link);
    }

    function getContactEmailbyId($arr_contact_id) {
        //echo $arr_contact_id;exit;
        //$temp_arr_str = implode(",",$arr_contact_id);

        $table1 = CONTACT_MASTER . ' as l';
        $match1 = "l.contact_id IN (" . $arr_contact_id . ")";
        $fields1 = array("l.email");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        $str_email = '';
        foreach ($user_data as $user_email) {
            $str_email.= $user_email['email'] . ",";
        }
        $tmp_str_email = rtrim($str_email, ",");
        return $tmp_str_email;
    }

    private function leadToProspect($id, $statusType = 2) {

        $lead_id = $id;
        if (!empty($lead_id)) {
            $table = LEAD_MASTER . ' as lm';
            $match = "lm.lead_id = " . $lead_id;
            $fields = array("lm.prospect_auto_id,lm.prospect_name,lm.company_id,"
                . "lm.address1,lm.address2,lm.creation_date,lm.postal_code,lm.city,lm.state,lm.country_id,lm.number_type1,lm.phone_no,lm.number_type2,lm.phone_no2,lm.prospect_owner_id,lm.language_id,"
                . "lm.branch_id,lm.estimate_prospect_worth,lm.prospect_generate,lm.campaign_id,lm.description,"
                . "lm.file,lm.contact_date,lm.status,lm.created_by");
            $lead_data = $this->common_model->get_records($table, $fields, '', '', $match);
            $lead_data[0]['status_type'] = $statusType;
            $prospect_id = $this->common_model->insert(PROSPECT_MASTER, $lead_data[0]); // insert each row to PROSPECT table
            //COMPANY_ACCOUNTS_TRAN data update 
            $where = array('client_id' => $lead_id, 'status_type' => $statusType);
            $company_account_data['status_type'] = 1;
            $company_account_data['client_id'] = $prospect_id;
            $this->common_model->update(COMPANY_ACCOUNTS_TRAN, $company_account_data, $where);

            //OPPORTUNITY_REQUIREMENT data
            $opportunity_requirement['prospect_id'] = $prospect_id;
            $opportunity_requirement['requirement_description'] = $lead_data[0]['description'];
            $opportunity_requirement['created_date'] = datetimeformat();
            $opportunity_requirement['modified_date'] = datetimeformat();
            $opportunity_requirement['status'] = 1;
            $opportunity_requirement_id = $this->common_model->insert(OPPORTUNITY_REQUIREMENT, $opportunity_requirement);
            $where = array('lead_id' => $lead_id);
            $this->common_model->delete(LEAD_MASTER, $where);

            $table2 = LEAD_CONTACTS_TRAN . ' as lct';
            $match2 = "lct.lead_id = " . $lead_id;
            $fields2 = array("lct.primary_contact,lct.contact_id");
            $lead_contact_data = $this->common_model->get_records($table2, $fields2, '', '', $match2);
            if (count($lead_contact_data) > 0) {

                foreach ($lead_contact_data as $opportunity_contact_data) { // loop over results
                    $opportunity_contact_data['prospect_id'] = $prospect_id;
                    $opportunity_contact_data['requirement_id'] = $opportunity_requirement_id;
                    $opportunity_requirement['created_date'] = datetimeformat();
                    $opportunity_requirement['modified_date'] = datetimeformat();
                    $opportunity_requirement['status'] = 1;
                    $this->common_model->insert(OPPORTUNITY_REQUIREMENT_CONTACTS, $opportunity_contact_data); // insert each row to PROSPECT_CONTACTS_TRAN table
                }
            }

            $where = array('lead_id' => $lead_id);
            $this->common_model->delete(LEAD_CONTACTS_TRAN, $where);

            $table3 = LEAD_PRODUCTS_TRAN . ' as lpt';
            $match3 = "lpt.lead_id = " . $lead_id;
            $fields3 = array("lpt.product_id");
            $lead_product_data = $this->common_model->get_records($table3, $fields3, '', '', $match3);
            if (count($lead_product_data) > 0) {
                foreach ($lead_product_data as $opportunity_product_data) {
                    // loop over results
                    $opportunity_product_data['prospect_id'] = $prospect_id;
                    $this->common_model->insert(PROSPECT_PRODUCTS_TRAN, $opportunity_product_data); // insert each row to PROSPECT table
                }
            }
            $where = array('lead_id' => $lead_id);
            $this->common_model->delete(LEAD_PRODUCTS_TRAN, $where);

            $table3 = FILES_LEAD_MASTER . ' as flm';
            $match3 = "flm.lead_id = " . $lead_id;
            $fields3 = array("flm.file_name,flm.file_path,flm.upload_status,flm.type");
            $lead_file_data = $this->common_model->get_records($table3, $fields3, '', '', $match3);
            if (count($lead_file_data) > 0) {
                foreach ($lead_file_data as $opportunity_file_data) {
                    // loop over results
                    $opportunity_file_data['prospect_id'] = $prospect_id;
                    $this->common_model->insert(FILES_SALES_MASTER, $opportunity_file_data); // insert each row to PROSPECT table
                }
            }
            $where = array('lead_id' => $lead_id);
            $this->common_model->delete(FILES_LEAD_MASTER, $where);

            unset($lead_id);
        }
    }

    private function ProspectToLead($id, $statusType = 2) {
        $prospect_id = $id;
        if (!empty($prospect_id)) {
            $table = PROSPECT_MASTER . ' as pm';
            $match = "pm.prospect_id = " . $prospect_id;
            $fields = array("pm.prospect_auto_id,pm.prospect_name,pm.company_id,"
                . "pm.address1,pm.address2,pm.creation_date,pm.postal_code,pm.city,pm.state,pm.country_id,pm.number_type1,pm.phone_no,pm.number_type2,pm.phone_no2,pm.prospect_owner_id,pm.language_id,"
                . "pm.branch_id,pm.estimate_prospect_worth,pm.prospect_generate,pm.campaign_id,pm.description,"
                . "pm.file,pm.contact_date,pm.status,pm.created_by");
            $prospect_data = $this->common_model->get_records($table, $fields, '', '', $match);
            $prospect_data[0]['status_type'] = $statusType;
            if (count($prospect_data) > 0) {
                $lead_id = $this->common_model->insert(LEAD_MASTER, $prospect_data[0]); // insert each row to PROSPECT table
                //echo $this->db->last_query();
                //  die;
                //COMPANY_ACCOUNTS_TRAN data update 
                $where = array('client_id' => $prospect_id, 'status_type' => $statusType);
                $company_account_data['status_type'] = $statusType;
                $company_account_data['client_id'] = $lead_id;
                $this->common_model->update(COMPANY_ACCOUNTS_TRAN, $company_account_data, $where);

                //OPPORTUNITY_REQUIREMENT data
                $prospectDescData = $this->common_model->get_records(OPPORTUNITY_REQUIREMENT, '', '', '', 'prospect_id=' . $prospect_id);
//            $opportunity_requirement['prospect_id'] = $prospect_id;
//            $opportunity_requirement['requirement_description'] = $prospectDescData[0]['requirement_description'];
//            $opportunity_requirement['created_date'] = datetimeformat();
//            $opportunity_requirement['modified_date'] = datetimeformat();
//            $opportunity_requirement['status'] = 1;
                if (count($prospectDescData) > 0) {
                    $whereLead = array('lead_id' => $lead_id);
                    $leadUpdateData = array('description' => $prospectDescData[0]['requirement_description']);
                    $opportunity_requirement_id = $this->common_model->update(LEAD_MASTER, $leadUpdateData, $whereLead);
                    $this->common_model->delete(PROSPECT_MASTER, array('prospect_id' => $prospect_id));
                }
                $table2 = OPPORTUNITY_REQUIREMENT_CONTACTS . ' as orc';
                $match2 = "orc.prospect_id = " . $prospect_id;
                $fields2 = array("orc.primary_contact,orc.contact_id");
                $opportunity_contact_data = $this->common_model->get_records($table2, $fields2, '', '', $match2);

                $OreqContacts = array();
                if (count($opportunity_contact_data) > 0) {
                    foreach ($opportunity_contact_data as $lead_contact_data) { // loop over results
                        $OreqContacts['lead_id'] = $lead_id;
                        $OreqContacts['contact_id'] = $lead_contact_data['contact_id'];
                        $OreqContacts['created_date'] = datetimeformat();
                        $OreqContacts['modified_date'] = datetimeformat();
                        $OreqContacts['status'] = 1;
                        $this->common_model->insert(LEAD_CONTACTS_TRAN, $OreqContacts); // insert each row to PROSPECT_CONTACTS_TRAN table
                    }
                }
                $prospectWhere = array('prospect_id' => $prospect_id);
                $this->common_model->delete(OPPORTUNITY_REQUIREMENT_CONTACTS, $prospectWhere);

                $table3 = PROSPECT_PRODUCTS_TRAN . ' as ppt';
                $match3 = "ppt.prospect_id = " . $prospect_id;
                $fields3 = array("ppt.product_id");
                $prospect_product_data = $this->common_model->get_records($table3, $fields3, '', '', $match3);
                $prosProdData = array();
                if (count($prospect_product_data) > 0) {
                    foreach ($prospect_product_data as $lead_product_data) {
                        // loop over results
                        $prosProdData['lead_id'] = $lead_id;
                        $prosProdData['product_id'] = $lead_product_data['product_id'];
                        $this->common_model->insert(LEAD_PRODUCTS_TRAN, $prosProdData); // insert each row to PROSPECT table
                    }
                }

                $this->common_model->delete(PROSPECT_PRODUCTS_TRAN, $prospectWhere);

                $table3 = FILES_SALES_MASTER . ' as flm';
                $match3 = "flm.prospect_id= " . $prospect_id;
                $fields3 = array("flm.file_name,flm.file_path,flm.upload_status,flm.type");
                $prospect_file_data = $this->common_model->get_records($table3, $fields3, '', '', $match3);
                $filesArr = array();
                if (count($prospect_file_data) > 0) {

                    foreach ($prospect_file_data as $lead_file_data) {
                        // loop over results
                        $filesArr['lead_id'] = $lead_id;
                        $filesArr['file_name'] = $lead_file_data['file_name'];
                        $filesArr['file_path'] = $lead_file_data['file_path'];
                        $filesArr['upload_status'] = $lead_file_data['upload_status'];
                        $this->common_model->insert(FILES_LEAD_MASTER, $filesArr); // insert each row to PROSPECT table
                    }
                }
                $where = array('prospect_id' => $prospect_id);
                $this->common_model->delete(FILES_SALES_MASTER, $where);
                unset($lead_id);
                unset($prospect_id);
            }
        }
    }

    /*
     * salesoverview drag and drop functionality 
     */

    public function dashboardWidgetsOrder() {
        if (!$this->input->is_ajax_request()) {
            exit("no direct scripts allowed");
        } else {
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];
            if ($this->input->get('resetWidgets')) {

                $defaultDashboard = array('widgetCalender', 'progressView', 'AjaxTasks', 'SalesListView');
                $data = array('salesoverview_dashboard_widgets' => json_encode($defaultDashboard));
                $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                $this->session->unset_userdata('salesoverview_dashboard_widgets');
                $this->session->set_userdata('salesoverview_dashboard_widgets', $defaultDashboard);
                echo json_encode(array('status' => 1, 'type' => 'reset'));
                die;
            } else {
                $sortorder = $this->input->post('sortorder');
                if (count($sortorder) > 0) {

                    $data = array('salesoverview_dashboard_widgets' => json_encode($sortorder));
                    $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                    $this->session->unset_userdata('salesoverview_dashboard_widgets');
                    $this->session->set_userdata('salesoverview_dashboard_widgets', $sortorder);
                }
                echo json_encode(array('status' => 1, 'type' => 'new'));
                die;
            }
        }
    }

    /*
     * function is used to get default currency code and symbol
     */

    function getDefaultCurrency() {
        $gensettingWhere = "config_key='general_settings'";
        $defaultDashboard1 = $this->common_model->get_records(CONFIG, array('value'), '', '', $gensettingWhere);
        $generalSettings = (array) json_decode($defaultDashboard1[0]['value']);

        if (isset($generalSettings['default_currency']) && !empty($generalSettings['default_currency'])) {
            $table = COUNTRIES . ' as c';
            $match = "c.country_id=" . $generalSettings['default_currency'];
            $fields = array("c.currency_code,c.currency_symbol");
            $sale_aacount_count = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['symbol'] = $sale_aacount_count[0]['currency_symbol'];
            $data['currency_code'] = $sale_aacount_count[0]['currency_code'];
            return $data;
        }
    }

    /*
     * for sendign meessge 
     * Created By sanket
     */

    function insertMessage() {


        $redirect_link = $this->input->post('redirect_link');
        $created_by = $message_from = $this->session->userdata('LOGGED_IN')['ID'];


        if (!validateFormSecret()) {
            redirect($redirect_link);
        }

        $aray_user_id = $this->input->post('user_id');
        $data['id_send_from'] = $message_from;
        $data['message_subject'] = $this->input->post('note_subject');
        $data['created_by'] = $created_by;
        $data['message_description'] = $this->input->post('note_description');
        $data['created_date'] = datetimeformat();
        $data['modified_date'] = datetimeformat();
        $data['is_delete'] = 0;

        if (count($aray_user_id) > 0) {
            foreach ($aray_user_id as $user_id) {
                $data['id_send_to'] = $user_id;
                $flg_record = $this->common_model->insert(TBL_MESSAGE_MASTER, $data);
            }
        }

        if ($flg_record) {
            $from_email = $this->session->userdata('LOGGED_IN')['EMAIL'];
            $from_name = $this->session->userdata('LOGGED_IN')['FIRSTNAME'] . " " . $this->session->userdata('LOGGED_IN')['LASTNAME'];
            $email_send_to = $this->getLoginEmailbyId($aray_user_id);

            $CI = & get_instance();
            $configs = getMailConfig();

            $config['protocol'] = $configs['email_protocol'];
            $config['smtp_host'] = $configs['smtp_host']; //change this
            $config['smtp_port'] = $configs['smtp_port'];
            $config['smtp_user'] = $configs['smtp_user']; //change this
            $config['smtp_pass'] = $configs['smtp_pass']; //change this
            $config['mailtype'] = 'html';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8

            $template = systemTemplateDataBySlug(TEMPLATE_MESSAGE);
            $search = array('{MESSAGE_SUBJECT}', '{MESSAGE_DESCRIPTION}', '{FROM_NAME}');
            $replace = array(ucfirst($data['message_subject']), $data['message_description'], $from_name);
            $body1 = str_replace($search, $replace, $template[0]['body']);

            $subject = "BLAZEDESK NEW MESSAGE :: " . $data['message_subject'];

            $CI->load->library('email', $config);
            $CI->email->set_header('MIME-Version', '1.0\r\n');
            $CI->email->set_header('Disposition-Notification-To', $from_email);

            $CI->email->from($from_email, $from_name);
            $CI->email->to($email_send_to);

            $CI->email->subject($subject);
            $CI->email->message($body1);


            if ($CI->email->send()) {
                $msg = lang('SEND_MESSAGE_SUCCESS');
                $this->session->set_flashdata('message', $msg);
            } else {
                $msg = lang('FAIL_WITH_SENDING_EMAIL');
                $this->session->set_flashdata('error_task', $msg);
            }
        } else {
            $msg = lang('error_msg');
            $this->session->set_flashdata('error_task', $msg);
        }


        redirect($redirect_link);
    }

    function getLoginEmailbyId($arr_login_id) {
        $temp_arr_str = implode(",", $arr_login_id);


        $table1 = LOGIN . ' as l';
        $match1 = "l.login_id IN (" . rtrim($temp_arr_str, ',') . ")";
        $fields1 = array("l.email");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        $str_email = '';
        foreach ($user_data as $user_email) {
            $str_email.= $user_email['email'] . ",";
        }
        $tmp_str_email = rtrim($str_email, ",");
        return $tmp_str_email;
    }

}
