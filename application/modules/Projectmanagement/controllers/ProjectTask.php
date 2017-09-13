<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ProjectTask extends CI_Controller {

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
      @Desc   : ProjectTask Index Page
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    public function index($page = '') {


        /* $master_user_id = $this->config->item('master_user_id');
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
        //Get uri segment for pagination
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);

        //count($cur_uri);
        $data['drag'] = true;
        //$this->session->unset_userdata('taskdashboardinnerWidgets');
        $data['header'] = array('menu_module' => 'Projectmanagement');
        $defaultDashboard = array('sectionLeft' => array('project_budget_count_view', 'budget_spent'), 'sectionRight' => array('today_task', 'Task_activities', 'messagebox_view'));
        $taskdashboardinnerWidgets = array('innerWidgets' => array('Task_header', 'tlists'));

        $data['current_project_status'] = $this->session->has_userdata('PROJECT_STATUS') ? $this->session->userdata('PROJECT_STATUS') : '';
        //Get Records From PROJECT_MASTER Table       
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->module . '/' . $this->viewname . '/index');
        $config['per_page'] = RECORD_PER_PAGE;
        $config["uri_segment"] = $cur_uri_segment;
        $data['page'] = !empty($page) ? $page : 0;
        //sorting
        $data['sortField'] = 'pt.task_id';
        $data['sortOrder'] = 'desc';
        if ($this->input->get('orderField') != '') {
            $data['sortField'] = $this->input->get('orderField');
        }
        if ($this->input->get('sortOrder') != '') {
            $data['sortOrder'] = $this->input->get('sortOrder');
        }
        $dbSearch = "";
        $change_status = "";
        $team_member = "";
        if ($this->input->get('search') != '') {
            $data['search'] = $term = $this->input->get('search');

            $searchFields = array('pt.task_code',
                'pt.task_name',
                'mi.milestone_name',
                'l.firstname',
                'l.lastname');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $term . "%'  or ";
            endforeach;
            $dbSearch = '(' . substr($dbSearch, 0, -3) . ')';
        }
        //Get Records From task Table  

        if ($this->input->get('change_status') != '') {
            $data['change_status'] = $change_status = $this->input->get('change_status');
        }
        $data['project_id'] = $this->project_id;
        if ($this->input->get('team_member') != '') {
            $data['team_member'] = $team_member = $this->input->get('team_member');
        }
        if (!empty($data['search']) || !empty($data['change_status']) || !empty($data['team_member'])) {
            $data['search_view'] = 1;
        }
        if (!empty($change_status) && !empty($team_member)) {
            $where = array('pt.status' => $change_status,
                'te.user_id' => $team_member,
                //'pt.sub_task_id' => 0,
                'pt.is_delete' => 0,
                'pt.project_id' => $this->project_id);
        } else if (!empty($change_status) || !empty($team_member)) {
            if (!empty($change_status)) {
                $where = array('pt.status' => $change_status,
                    //'pt.sub_task_id' => 0,
                    'pt.is_delete' => 0,
                    'pt.project_id' => $this->project_id);
            }
            if (!empty($team_member)) {
                $where = array('te.user_id' => $team_member,
                    //'pt.sub_task_id' => 0,
                    'pt.is_delete' => 0,
                    'pt.project_id' => $this->project_id);
            }
        } else {
            if (!empty($dbSearch)) {
                $where = array(
                    'pt.is_delete' => 0,
                    'pt.project_id' => $this->project_id);
            } else {
                $where = array('pt.sub_task_id' => 0,
                    'pt.is_delete' => 0,
                    'pt.project_id' => $this->project_id);
            }
        }

        $table = PROJECT_TASK_MASTER . ' as pt';
        $fields = array('ps.status_name,ps.status_color,group_concat(l.firstname," ",l.lastname) as user_name,pt.task_id,pt.description,pt.task_code,pt.task_name,tp.task_name as parent_task,pt.start_date,pt.due_date,pt.status,pt.sub_task_id,mi.milestone_name,mi.milestone_id,pt.deal_id');
        $join_table = array(
            PROJECT_STATUS . ' as ps' => 'ps.status_id=pt.status and ps.is_delete = 0',
            PROJECT_TASK_MASTER . ' as tp' => 'pt.sub_task_id=tp.task_id',
            MILESTONE_MASTER . ' as mi' => 'mi.milestone_id=pt.milestone_id',
            PROJECT_TASK_TEAM_TRAN . ' as te' => 'te.task_id=pt.task_id',
            LOGIN . ' as l' => 'te.user_id=l.login_id and l.is_delete = 0');
        $group_by = 'te.task_id';
        $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'task_id', 'desc', $group_by, $dbSearch, '', '', '1');

        $data['task_data'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', $config['per_page'], $data['page'], $data['sortField'], $data['sortOrder'], $group_by, $dbSearch);
        //pagination

        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        //get responsible employee
        $where_res = array('is_delete' => 0, 'status' => 1);
        $data['res_user'] = $this->common_model->get_records(LOGIN, '', '', '', $where_res, '');
        //pr($data['res_user']);exit;
        //get project

        $match = "pt.project_id = " . $this->project_id;
        $table = PROJECT_MASTER . ' as pt';
        $fields = array("CASE 
                        WHEN pt.client_type = 'contact' THEN ca.contact_name
                        WHEN pt.client_type = 'client' THEN p.prospect_name
                        WHEN pt.client_type = 'company' THEN co.company_name END AS prospect_name,
                        pt.project_id,pt.project_name,pt.status,pt.start_date,pt.due_date,project_budget");
        $join_table = array(PROSPECT_MASTER . ' as p' => 'p.prospect_id=pt.client_id ',
            CONTACT_MASTER . ' as ca' => 'ca.contact_id=pt.client_id ',
            COMPANY_MASTER . ' as co' => 'co.company_id=pt.client_id');
        $data['project_detail'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $match, '');

        //Project percentage
        $mi_group_by = 'c.milestone_id';
        $where_pr = array('c.is_delete' => 0, 'm.is_delete' => 0, 'm.project_id' => $this->project_id);
        $join_table_mi = array(MILESTONE_MASTER . ' as m' => 'c.milestone_id=m.milestone_id');
        $total_milistone_data = $this->common_model->get_records(PROJECT_TASK_MASTER . ' as c', array('m.milestone_id'), $join_table_mi, 'left', $where_pr, '', '', '', '', '', $mi_group_by);

        /* other task */
        /* $where_other = array('m.project_id' => $this->project_id);
          $wheresting_to1 = "m.milestone_id = 0";
          $table_mi_task = PROJECT_TASK_MASTER . ' as m';
          $fields_mi_task = array("COUNT(IF(m.status = '5',1,NULL)) AS completed,COUNT(IF(c.status = '5',1,NULL)) AS child_completed,COUNT(m.status) AS parent_task,COUNT(c.status) AS sub_task");
          $join_table_mi_task = array(PROJECT_TASK_MASTER . ' as c' => 'c.sub_task_id=m.task_id  AND c.milestone_id IS NULL');
          $other_count = $this->common_model->customQuery($table_mi_task, $fields_mi_task, $join_table_mi_task, 'left', $where_other, '', '', '', '', '', '', $wheresting_to1, '', '');
         */
        $query = "SELECT COUNT(IF(m.status = '5', 1, NULL)) AS completed, COUNT(IF(c.status = '5', 1, NULL)) AS child_completed, COUNT(m.status) AS parent_task, COUNT(c.status) AS sub_task FROM `blzdsk_project_task_master` AS `m` LEFT JOIN `blzdsk_project_task_master` AS `c` ON `c`.`sub_task_id`=`m`.`task_id` AND `c`.`milestone_id` IS NULL and `c`.`is_delete` = 0 WHERE `m`.`project_id` = " . $this->project_id . " AND m.milestone_id = 0 and m.is_delete = 0 ";
        $other_count = $this->common_model->customQuery($query);

        if (!empty($other_count)) {
            $total_milistone = count($total_milistone_data) + 1;
        } else {
            $total_milistone = count($total_milistone_data);
        }
        $milistone_per = 0;
        if (!empty($total_milistone_data)) {
            $per_devide = round(100 / $total_milistone, 2);

            foreach ($total_milistone_data as $row) {
                $milistone_id = $row['milestone_id'];
                $where_mi_task = array('m.milestone_id' => $milistone_id, 'm.is_delete' => 0);
                $table_mi_task = PROJECT_TASK_MASTER . ' as m';
                $fields_mi_task = array("COUNT(IF(m.status = '5',1,NULL)) AS completed,COUNT(IF(c.status = '5',1,NULL)) AS child_completed,COUNT(m.status) AS parent_task,COUNT(c.status) AS sub_task");
                $join_table_mi_task = array(PROJECT_TASK_MASTER . ' as c' => 'c.sub_task_id=m.task_id and c.is_delete = 0');
                $milistone_count = $this->common_model->get_records($table_mi_task, $fields_mi_task, $join_table_mi_task, 'left', $where_mi_task, '');

                if (!empty($milistone_count)) {
                    $total_task = $milistone_count[0]['parent_task'] + $milistone_count[0]['sub_task'];
                    $completed_task = $milistone_count[0]['completed'] + $milistone_count[0]['child_completed'];
                    if (!empty($total_task)) {
                        $task_per = round(($completed_task * 100) / $total_task, 2);
                        $milistone_per += round(($task_per * $per_devide) / 100, 2);
                    }
                }
            }
        }
        //echo $this->db->last_query();exit;
        if (!empty($other_count)) {
            $per_devide1 = round(100 / $total_milistone, 2);
            $total_other_task = $other_count[0]['parent_task'] + $other_count[0]['sub_task'];
            $completed_other_task = $other_count[0]['completed'] + $other_count[0]['child_completed'];
            if (!empty($total_other_task)) {
                $task_other_per = round(($completed_other_task * 100) / $total_other_task, 2);
                $milistone_per += round(($task_other_per * $per_devide1) / 100, 2);
            }
        }
        $data['project_per'] = $milistone_per;

        //Get count task status wise

        $table = PROJECT_STATUS . ' as ps';
        $fields = array('ps.status_id,ps.status_name,ps.status_color,ps.status_font_icon,COUNT(task_id) as total_task');
        $join_table = array(PROJECT_TASK_MASTER . ' as pt' => 'pt.status = ps.status_id and pt.is_delete=0 and pt.project_id =' . $this->project_id);
        $group_by = 'ps.status_id';
        $where = array('ps.is_delete' => 0);
        $data['project_tasks_status'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'status_order', 'asc', $group_by);

        //pr($data['project_tasks_status']);exit;
        //get today`s task

        $table = PROJECT_TASK_MASTER . ' as pt';
        $fields = array('task_id,task_name,status,start_date,due_date,status_color,status_name');
        $join_table = array(PROJECT_STATUS . ' as ps' => 'pt.status = ps.status_id');
        $group_by = 'ps.status_id';
        $where_to = array('pt.is_delete' => 0, 'project_id' => $this->project_id);
        $wheresting_to = "(start_date <='" . date('Y-m-d') . "' and due_date >='" . date('Y-m-d') . "')";
        $data['today_task'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where_to, '', '', '', 'project_id', 'desc', '', $wheresting_to, '', '');
        //pr($data['today_task']);exit;
        //get today`s activity
        $table_act = PROJECT_ACTIVITIES . ' as pa';
        $fields_act = array('pa.*,CONCAT(l.firstname, " ", l.lastname) AS user_name');
        $join_table_act = array(LOGIN . ' as l' => 'pa.user_id=l.login_id and l.is_delete = 0');
        $where_act = array('project_id' => $this->project_id);
        $data['activities_total'] = $this->common_model->get_records($table_act, $fields_act, $join_table_act, 'left', $where_act, '', '', '', 'activity_id', 'desc', '', '', '', '', '1');

        $data['activities'] = $this->common_model->get_records($table_act, $fields_act, $join_table_act, 'left', $where_act, '', 5, 0, 'activity_id', 'desc', '');

        //Get project status

        $where = array('is_delete' => 0);
        $data['project_status'] = $this->common_model->get_records(PROJECT_STATUS, array('status_id,status_name'), '', '', $where);
        //Get project assign user
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.login_id,concat(l.firstname," ",l.lastname) as name');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id  and l.is_delete = 0');
        $where = array('project_id' => $this->project_id, 'l.is_delete' => 0);
        $data['team_members'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
        $data['cur_project_id'] = $this->project_id;
        //load view
        $data['main_content'] = '/ProjectTask/ProjectTask';
        $data['milestone_view'] = $this->module . '/Milestone';
        $view = $this->input->get('view');
        if ($this->session->has_userdata('blazedesk_pm_taskdashboardWidgets') && $this->session->userdata('blazedesk_pm_taskdashboardWidgets') != '') {
            $data['widgets'] = $this->session->userdata('blazedesk_pm_taskdashboardWidgets');
        } else {
            $data['widgets'] = $defaultDashboard;
        }
        if ($this->session->has_userdata('taskdashboardinnerWidgets') && $this->session->userdata('taskdashboardinnerWidgets') != '') {
            $data['inner_widgets'] = $this->session->userdata('taskdashboardinnerWidgets');
        } else {
            $data['inner_widgets'] = $taskdashboardinnerWidgets;
        }
        if ($this->input->is_ajax_request()) {
            if (!empty($view)) {
                if ($view == 1) {
                    $this->load->view('/ProjectTask/List_view', $data);
                } else if ($view == 2 || $view == 3) {
                    $this->changeview($view, $dbSearch, $change_status, $team_member);
                }
                $data['view'] = $view;
            } else {
                $data['view'] = 1;
                $this->load->view('/ProjectTask/ProjectTask', $data);
            }
        } else {
            $data['view'] = 1;
            $data['main_content'] = '/ProjectTask/ProjectTask';
            $data['js_content'] = '/ProjectTask/loadJsFiles';
            $data['task_view'] = $this->module . '/ProjectTask';
            $this->parser->parse('layouts/ProjectTaskTemplate', $data);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Change view
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    public function changeview($view, $dbSearch = '', $change_status = '', $team_member = '') {
        $data['drag'] = true;
        //Get count task status wise
        $table = PROJECT_STATUS . ' as ps';
        $fields = array('ps.status_id,ps.status_name,ps.status_color,ps.status_font_icon,COUNT(task_id) as total_task');
        $join_table = array(PROJECT_TASK_MASTER . ' as pt' => 'pt.status = ps.status_id and pt.is_delete=0 and pt.project_id = ' . $this->project_id);
        //$where      = array('pt.project_id' => $this->project_id);
        $group_by = 'ps.status_id';
        $where = array('is_delete' => 0);
        $project_status = $this->common_model->get_records(PROJECT_STATUS, array('status_id,status_name,status_color,status_font_icon'), '', '', $where, '', '', '', 'status_order', 'asc');
        $data['project_status'] = $project_status;
        if ($view == 2) {
            //Get total task status

            $where = array('ps.is_delete' => 0);
            $data['project_tasks_status'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'status_order', 'asc', $group_by);
            //Get Records From PROJECT_MASTER Table       
            $where = array('pt.is_delete' => 0, 'pt.project_id' => $this->project_id);
            $table = PROJECT_TASK_MASTER . ' as pt';
            $fields = array('pt.task_id,pt.description,pt.task_code,pt.task_name,pt.start_date,pt.due_date,pt.status,pt.sub_task_id,mi.milestone_name,mi.milestone_id,l.firstname,group_concat(l.firstname," ",l.lastname) as employee_name');
            $group_by = 'te.task_id';
            $join_table = array(
                MILESTONE_MASTER . ' as mi' => 'mi.milestone_id=pt.milestone_id',
                PROJECT_TASK_TEAM_TRAN . ' as te' => 'te.task_id=pt.task_id',
                LOGIN . ' as l' => 'te.user_id=l.login_id  and l.is_delete = 0');

            if (!empty($change_status)) {
                $data['change_status'] = $change_status;
                $where = array('pt.status' => $change_status,
                    'pt.is_delete' => 0,
                    'pt.project_id' => $this->project_id);
                //search teammember wise
                if (!empty($team_member)) {
                    $team_array = array('te.user_id' => $team_member);
                    $where = array_merge($where, $team_array);
                }
                $task_data = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'task_id', 'desc', $group_by, $dbSearch);
                $pro_status[$change_status] = $task_data;
                $data['task_status_data'] = $pro_status;
            } else {
                $pro_status = array();
                if (!empty($project_status)) {
                    //$where = array('pt.is_delete'   => 0,'pt.project_id' => $this->project_id);
                    //search teammember wise
                    foreach ($project_status as $row) {
                        $where = array('pt.is_delete' => 0, 'pt.status' => $row['status_id'],
                            'pt.project_id' => $this->project_id);
                        if (!empty($team_member)) {
                            $team_array = array('te.user_id' => $team_member);
                            $where = array_merge($where, $team_array);
                        }
                        $task_data = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'task_id', 'desc', $group_by, $dbSearch);
                        $pro_status[$row['status_id']] = $task_data;
                    }
                }
                $data['task_status_data'] = $pro_status;
            }

            //load view
            $data['milestone_view'] = $this->module . '/Milestone';
            $data['task_view'] = $this->module . '/ProjectTask';
            $view = $this->input->get('view');
            $view = 2;

            $data['view'] = $view;
            $this->load->view('/ProjectTask/Agile_view', $data);
        } else {
            //Get total task status

            $where = array('ps.is_delete' => 0);
            $data['project_tasks_status'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'status_order', 'asc', $group_by);
            //load view
            $data['milestone_view'] = $this->module . '/Milestone';
            $data['task_view'] = $this->module . '/ProjectTask';
            $view = $this->input->get('view');
            $data['cal_view'] = $this->input->get('cal_view');
            if ($data['cal_view'] == 'week') {
                $data['cal_view'] = 'basicWeek';
            }
            if ($data['cal_view'] == 'day') {
                $data['cal_view'] = 'basicDay';
            }
            $st = $this->input->get('start');
            if (isset($st)) {
                $data['start'] = date('Y-m-d', strtotime($st));
            }

            $view = 3;
            $data['view'] = $view;
            $this->load->view('/ProjectTask/Gant_view', $data);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Grant view
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    public function grantview() {
        $data = array();
        //Get milistone
        $_GET['end'] = date('Y-m-d', strtotime('-1 day', strtotime($_GET['end'])));
        $where = array('is_delete' => 0,
            'project_id' => $this->project_id);
        $fields = array('milestone_id,milestone_name,start_date,due_date');
        $wheresting = "((start_date >='" . $_GET['start'] . "' and start_date <='" . $_GET['end'] . "') or (start_date <='" . $_GET['start'] . "' and start_date <='" . $_GET['end'] . "') and due_date >='" . $_GET['start'] . "')";
        $milestone = $this->common_model->get_records(MILESTONE_MASTER, $fields, '', '', $where, '', '', '', '', '', '', $wheresting, '', '');
        //Get task
        $dbSearch = "";
        $change_status = "";

        //$wheresting = '';
        //$wheresting    = '';
        if ($this->input->get('search') != '') {
            $term = $this->input->get('search');

            $searchFields = array('task_code',
                'task_name');
            foreach ($searchFields as $fields):
                $dbSearch .= " " . $fields . " like '%" . $term . "%'  or ";
            endforeach;

            $dbSearch = 'and (' . substr($dbSearch, 0, -3) . ') ';
            $wheresting .= $dbSearch;
        }
        //Get Records From task Table  

        if ($this->input->get('status') != '') {
            $change_status = $this->input->get('status');
            ;
        }
        if ($this->input->get('team_member') != '') {
            $team_member = $this->input->get('team_member');
            ;
        }
        if (!empty($milestone)) {
            foreach ($milestone as $row) {

                $data[] = array('id' => $row['milestone_id'],
                    'title' => $row['milestone_name'],
                    'start' => $row['start_date'],
                    'end' => date('Y-m-d', strtotime($row['due_date'] . ' + 1 days')),
                    'color' => '#f7974a',
                    'url' => base_url('Projectmanagement/Milestone/view_record/' . $row['milestone_id']));


                if (!empty($change_status)) {
                    $where_task = array('status' => $change_status,
                        'pt.is_delete' => 0,
                        'milestone_id' => $row['milestone_id']);
                } else {
                    $where_task = array('pt.is_delete' => 0, 'milestone_id' => $row['milestone_id']);
                }

                if (!empty($team_member)) {
                    $team_array = array('te.user_id' => $team_member);
                    $where_task = array_merge($where_task, $team_array);
                }
                /* if(!empty($term) || !empty($change_status) || !empty($team_member))
                  {
                  $data['search_view']=1;
                  } */
                $table = PROJECT_TASK_MASTER . ' as pt';
                $fields = array('ps.status_color,te.user_id,pt.task_id,pt.task_name,pt.start_date,pt.due_date,pt.status');
                //$group_by = 'te.task_id';
                $join_table = array(
                    PROJECT_TASK_TEAM_TRAN . ' as te' => 'te.task_id=pt.task_id',
                    PROJECT_STATUS . ' as ps' => 'ps.status_id=pt.status');
                $tasks = $this->common_model->get_records($table, $fields, $join_table, 'left', $where_task, '', '', '', '', '', '', $wheresting);

                if (!empty($tasks)) {
                    foreach ($tasks as $row1) {
                        $data[] = array('id' => $row1['task_id'],
                            'title' => $row1['task_name'],
                            'start' => $row1['start_date'],
                            'end' => date('Y-m-d', strtotime($row1['due_date'] . ' + 1 days')),
                            'color' => $row1['status_color'],
                            'url' => base_url('Projectmanagement/ProjectTask/view_record/' . $row1['task_id']));
                        //Get sub task
                        if (!empty($change_status)) {
                            $where_sub_task = array('status' => $change_status,
                                'pt.is_delete' => 0,
                                'sub_task_id' => $row1['task_id']);
                        } else {
                            $where_sub_task = array('pt.is_delete' => 0, 'sub_task_id' => $row1['task_id']);
                        }

                        if (!empty($team_member)) {
                            $team_array = array('te.user_id' => $team_member);
                            $where_sub_task = array_merge($where_sub_task, $team_array);
                        }
                        $table = PROJECT_TASK_MASTER . ' as pt';
                        $fields = array('ps.status_color,te.user_id,pt.task_id,pt.task_name,pt.start_date,pt.due_date,pt.status,pt.sub_task_id');
                        //$group_by = 'te.task_id';
                        $join_table = array(
                            PROJECT_TASK_TEAM_TRAN . ' as te' => 'te.task_id=pt.task_id',
                            PROJECT_STATUS . ' as ps' => 'ps.status_id=pt.status');
                        $sub_tasks = $this->common_model->get_records($table, $fields, $join_table, 'left', $where_sub_task, '', '', '', '', '', '', $wheresting);
                        //echo $this->db->last_query();exit;
                        if (!empty($sub_tasks)) {
                            foreach ($sub_tasks as $row2) {
                                $data[] = array('id' => $row2['task_id'],
                                    'title' => $row2['task_name'],
                                    'start' => $row2['start_date'],
                                    'end' => date('Y-m-d', strtotime($row2['due_date'] . ' + 1 days')),
                                    'color' => $row2['status_color'],
                                    'url' => base_url('Projectmanagement/ProjectTask/view_record/' . $row2['task_id']));
                            }
                        }
                    }
                }
            }
        }
        //Without milestone status
        if (!empty($change_status)) {
            $where_task = array('status' => $change_status,
                'pt.is_delete' => 0,
                'milestone_id' => '', 'project_id' => $this->project_id);
        } else {
            $where_task = array('pt.is_delete' => 0, 'milestone_id' => '', 'project_id' => $this->project_id);
        }

        if (!empty($team_member)) {
            $team_array = array('te.user_id' => $team_member);
            $where_task = array_merge($where_task, $team_array);
        }

        $table = PROJECT_TASK_MASTER . ' as pt';
        $fields = array('ps.status_color,te.user_id,pt.task_id,pt.task_name,pt.start_date,pt.due_date,pt.status');
        //$group_by = 'te.task_id';
        $join_table = array(
            PROJECT_TASK_TEAM_TRAN . ' as te' => 'te.task_id=pt.task_id',
            PROJECT_STATUS . ' as ps' => 'ps.status_id=pt.status');
        $othertasks = $this->common_model->get_records($table, $fields, $join_table, 'left', $where_task, '', '', '', '', '', '', $wheresting);

        if (!empty($othertasks)) {
            foreach ($othertasks as $row3) {
                $data[] = array('id' => $row3['task_id'],
                    'title' => $row3['task_name'],
                    'start' => $row3['start_date'],
                    'end' => date('Y-m-d', strtotime($row3['due_date'] . ' + 1 days')),
                    'color' => $row3['status_color'],
                    'url' => base_url('Projectmanagement/ProjectTask/view_record/' . $row3['task_id']));
                //Get sub task
                if (!empty($change_status)) {
                    $where1_sub_task = array('status' => $change_status,
                        'pt.is_delete' => 0,
                        'sub_task_id' => $row3['task_id']);
                } else {
                    $where1_sub_task = array('pt.is_delete' => 0, 'sub_task_id' => $row3['task_id']);
                }

                if (!empty($team_member)) {
                    $team_array = array('te.user_id' => $team_member);
                    $where1_sub_task = array_merge($where1_sub_task, $team_array);
                }
                $table = PROJECT_TASK_MASTER . ' as pt';
                $fields = array('ps.status_color,te.user_id,pt.task_id,pt.task_name,pt.start_date,pt.due_date,pt.status,pt.sub_task_id');
                //$group_by = 'te.task_id';
                $join_table = array(
                    PROJECT_TASK_TEAM_TRAN . ' as te' => 'te.task_id=pt.task_id',
                    PROJECT_STATUS . ' as ps' => 'ps.status_id=pt.status');
                $sub_tasks = $this->common_model->get_records($table, $fields, $join_table, 'left', $where1_sub_task, '', '', '', '', '', '', $wheresting);
                //echo $this->db->last_query();exit;
                if (!empty($sub_tasks)) {
                    foreach ($sub_tasks as $row2) {
                        $data[] = array('id' => $row2['task_id'],
                            'title' => $row2['task_name'],
                            'start' => $row2['start_date'],
                            'end' => date('Y-m-d', strtotime($row2['due_date'] . ' + 1 days')),
                            'color' => $row2['status_color'],
                            'url' => base_url('Projectmanagement/ProjectTask/view_record/' . $row2['task_id']));
                    }
                }
            }
        }
        echo json_encode($data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Function for notify member
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    function notifyTeamMember($userId, $taskId) {
        // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
        //$ematch = "et.subject ='Create task'";
        $ematch = "et.template_id = 36";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
        $umatch = "login_id =" . $userId;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        //Get loging user data
        $umatch = "login_id =" . $this->user_info['ID'];
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $log_udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);

        //get Current Project Name
        $pmatch = "project_id =" . $this->project_id;
        $pfields = array("project_name");
        $pdata = $this->common_model->get_records(PROJECT_MASTER, $pfields, '', '', $pmatch);
        $email = !empty($udata[0]['email']) ? $udata[0]['email'] : '';
        $fullname = !empty($udata[0]['fullname']) ? ucfirst($udata[0]['fullname']) : '';
        $logusername = !empty($log_udata[0]['fullname']) ? ucfirst($log_udata[0]['fullname']) : '';
        $projectName = !empty($pdata[0]['project_name']) ? $pdata[0]['project_name'] : '';
        //get task data
        $tmatch = "task_id =" . $taskId;
        $tfields = array("task_name,description");
        $taskdata = $this->common_model->get_records(PROJECT_TASK_MASTER, $tfields, '', '', $tmatch);
        $taskName = $taskdata[0]['task_name'];
        $taskDescription = $taskdata[0]['description'];
        $find = array(
            '{USER}',
            '{CREATE_USER}',
            '{PROJECT}',
            '{TASK_NAME}',
            '{DESCRIPTION}'

                //    '{DATE}'
        );

        $replace = array(
            'USER' => $fullname,
            'CREATE_USER' => $logusername,
            'PROJECT' => $projectName,
            'TASK_NAME' => $taskName,
            'DESCRIPTION' => $taskDescription,
                //    'DATE' => $order_info['payment_company']
        );
        $format = $template[0]['body'];
        $body = str_replace(array("\r\n",
            "\r",
            "\n"), '<br />', preg_replace(array("/\s\s+/",
            "/\r\r+/",
            "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
        $formatSubject = str_replace(array("\r\n",
            "\r",
            "\n"), '<br />', preg_replace(array("/\s\s+/",
            "/\r\r+/",
            "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $template[0]['subject']))));
        $subject = "Blazedesk :: " . $formatSubject;
        send_mail($email, $subject, $body);
    }

    /*
      @Author : Niral Patel
      @Desc   : Function for notify member
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    function notifyTeamMemberUpdate($userId, $taskId) {
        // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
        //$ematch = "et.subject ='Update task'";
        $ematch = "et.template_id = 37";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
        $umatch = "login_id =" . $userId;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        //Get loging user data
        $umatch = "login_id =" . $this->user_info['ID'];
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $log_udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);

        //get Current Project Name
        $pmatch = "project_id =" . $this->project_id;
        $pfields = array("project_name");
        $pdata = $this->common_model->get_records(PROJECT_MASTER, $pfields, '', '', $pmatch);
        $email = !empty($udata[0]['email']) ? $udata[0]['email'] : '';
        $fullname = !empty($udata[0]['fullname']) ? ucfirst($udata[0]['fullname']) : '';
        $logusername = !empty($log_udata[0]['fullname']) ? ucfirst($log_udata[0]['fullname']) : '';
        $projectName = !empty($pdata[0]['project_name']) ? $pdata[0]['project_name'] : '';
        //get task data
        $tmatch = "task_id =" . $taskId;
        $tfields = array("task_name,description");
        $taskdata = $this->common_model->get_records(PROJECT_TASK_MASTER, $tfields, '', '', $tmatch);
        $taskName = $taskdata[0]['task_name'];
        $taskDescription = $taskdata[0]['description'];
        $find = array(
            '{USER}',
            '{CREATE_USER}',
            '{PROJECT}',
            '{TASK_NAME}',
            '{DESCRIPTION}'

                //    '{DATE}'
        );

        $replace = array(
            'USER' => $fullname,
            'CREATE_USER' => $logusername,
            'PROJECT' => $projectName,
            'TASK_NAME' => $taskName,
            'DESCRIPTION' => $taskDescription,
                //    'DATE' => $order_info['payment_company']
        );
        $format = $template[0]['body'];
        $body = str_replace(array("\r\n",
            "\r",
            "\n"), '<br />', preg_replace(array("/\s\s+/",
            "/\r\r+/",
            "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

        $formatSubject = str_replace(array("\r\n",
            "\r",
            "\n"), '<br />', preg_replace(array("/\s\s+/",
            "/\r\r+/",
            "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $template[0]['subject']))));
        $subject = "Blazedesk :: " . $formatSubject;
        send_mail($email, $subject, $body);
    }

    /*
      @Author : Niral Patel
      @Desc   : Get header
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    public function get_home_header() {
        //get project
        $where = array('project_id' => $this->project_id);
        $data['project_detail'] = $this->common_model->get_records(PROJECT_MASTER, array('project_id',
            'project_name',
            'status',
            'start_date',
            'due_date',
            'project_budget'), '', '', $where, '');

        //Project percentage
        $mi_group_by = 'c.milestone_id';
        $where_pr = array('c.is_delete' => 0, 'm.is_delete' => 0, 'm.project_id' => $this->project_id);
        $join_table_mi = array(MILESTONE_MASTER . ' as m' => 'c.milestone_id=m.milestone_id');
        $total_milistone_data = $this->common_model->get_records(PROJECT_TASK_MASTER . ' as c', array('m.milestone_id'), $join_table_mi, 'left', $where_pr, '', '', '', '', '', $mi_group_by);

        /* other task */
        /* $where_other = array('m.project_id' => $this->project_id);
          $wheresting_to1 = "m.milestone_id = 0";
          $table_mi_task = PROJECT_TASK_MASTER . ' as m';
          $fields_mi_task = array("COUNT(IF(m.status = '5',1,NULL)) AS completed,COUNT(IF(c.status = '5',1,NULL)) AS child_completed,COUNT(m.status) AS parent_task,COUNT(c.status) AS sub_task");
          $join_table_mi_task = array(PROJECT_TASK_MASTER . ' as c' => 'c.sub_task_id=m.task_id  AND c.milestone_id IS NULL');
          $other_count = $this->common_model->customQuery($table_mi_task, $fields_mi_task, $join_table_mi_task, 'left', $where_other, '', '', '', '', '', '', $wheresting_to1, '', '');
         */
        $query = "SELECT COUNT(IF(m.status = '5', 1, NULL)) AS completed, COUNT(IF(c.status = '5', 1, NULL)) AS child_completed, COUNT(m.status) AS parent_task, COUNT(c.status) AS sub_task FROM `blzdsk_project_task_master` AS `m` LEFT JOIN `blzdsk_project_task_master` AS `c` ON `c`.`sub_task_id`=`m`.`task_id` AND `c`.`milestone_id` IS NULL and `c`.`is_delete` = 0 WHERE `m`.`project_id` = " . $this->project_id . " AND m.milestone_id = 0 and m.is_delete = 0 ";
        $other_count = $this->common_model->customQuery($query);

        if (!empty($other_count)) {
            $total_milistone = count($total_milistone_data) + 1;
        } else {
            $total_milistone = count($total_milistone_data);
        }
        $milistone_per = 0;
        if (!empty($total_milistone_data)) {
            $per_devide = round(100 / $total_milistone, 2);

            foreach ($total_milistone_data as $row) {
                $milistone_id = $row['milestone_id'];
                $where_mi_task = array('m.milestone_id' => $milistone_id, 'm.is_delete' => 0);
                $table_mi_task = PROJECT_TASK_MASTER . ' as m';
                $fields_mi_task = array("COUNT(IF(m.status = '5',1,NULL)) AS completed,COUNT(IF(c.status = '5',1,NULL)) AS child_completed,COUNT(m.status) AS parent_task,COUNT(c.status) AS sub_task");
                $join_table_mi_task = array(PROJECT_TASK_MASTER . ' as c' => 'c.sub_task_id=m.task_id and c.is_delete = 0');
                $milistone_count = $this->common_model->get_records($table_mi_task, $fields_mi_task, $join_table_mi_task, 'left', $where_mi_task, '');

                if (!empty($milistone_count)) {
                    $total_task = $milistone_count[0]['parent_task'] + $milistone_count[0]['sub_task'];
                    $completed_task = $milistone_count[0]['completed'] + $milistone_count[0]['child_completed'];
                    if (!empty($total_task)) {
                        $task_per = round(($completed_task * 100) / $total_task, 2);
                        $milistone_per += round(($task_per * $per_devide) / 100, 2);
                    }
                }
            }
        }
        //echo $this->db->last_query();exit;
        if (!empty($other_count)) {
            $per_devide1 = round(100 / $total_milistone, 2);
            $total_other_task = $other_count[0]['parent_task'] + $other_count[0]['sub_task'];
            $completed_other_task = $other_count[0]['completed'] + $other_count[0]['child_completed'];
            if (!empty($total_other_task)) {
                $task_other_per = round(($completed_other_task * 100) / $total_other_task, 2);
                $milistone_per += round(($task_other_per * $per_devide1) / 100, 2);
            }
        }
        $data['project_per'] = $milistone_per;
        /* end project per */
        //widgets
        $defaultDashboard = array('sectionLeft' => array('project_budget_count_view', 'budget_spent'), 'sectionRight' => array('today_task', 'Task_activities', 'messagebox_view'));
        $taskdashboardinnerWidgets = array('innerWidgets' => array('Task_header', 'tlists'));

        if ($this->session->has_userdata('blazedesk_pm_taskdashboardWidgets') && $this->session->userdata('blazedesk_pm_taskdashboardWidgets') != '') {
            $data['widgets'] = $this->session->userdata('blazedesk_pm_taskdashboardWidgets');
        } else {
            $data['widgets'] = $defaultDashboard;
        }
        if ($this->session->has_userdata('taskdashboardinnerWidgets') && $this->session->userdata('taskdashboardinnerWidgets') != '') {
            $data['inner_widgets'] = $this->session->userdata('taskdashboardinnerWidgets');
        } else {
            $data['inner_widgets'] = $taskdashboardinnerWidgets;
        }
        //get today`s task
        $where = array('is_delete' => 0, 'project_id' => $this->project_id);
        $wheresting = "(start_date <='" . date('Y-m-d') . "' and due_date >='" . date('Y-m-d') . "')";
        $data['today_task'] = $this->common_model->get_records(PROJECT_TASK_MASTER, array('task_id,task_name,status,start_date,due_date'), '', '', $where, '', '', '', 'project_id', 'desc', '', $wheresting, '', '');
        $this->load->view('/ProjectTask/Task_header', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Get activity
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    public function get_home_activity() {
        //get today`s activity
        $table = PROJECT_ACTIVITIES . ' as pa';
        $fields = array('pa.*,CONCAT(l.firstname, " ", l.lastname) AS user_name');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('l.is_delete' => 0, 'project_id' => $this->project_id);
        $data['activities_total'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'activity_id', 'desc', '', '', '', '', '1');

        $data['activities'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', 5, 0, 'activity_id', 'desc', '');
        $this->load->view('/ProjectTask/widgets/Task_activities', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Get today task
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    public function get_today_task() {
        //get today`s task
        $table = PROJECT_TASK_MASTER . ' as pt';
        $fields = array('task_id,task_name,status,start_date,due_date,status_color,status_name');
        $join_table = array(PROJECT_STATUS . ' as ps' => 'pt.status = ps.status_id');
        $group_by = 'ps.status_id';
        $where_to = array('pt.is_delete' => 0, 'pt.project_id' => $this->project_id);
        $wheresting_to = "(start_date <='" . date('Y-m-d') . "' and due_date >='" . date('Y-m-d') . "')";
        $data['today_task'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where_to, '', '', '', 'project_id', 'desc', '', $wheresting_to, '', '');
        $this->load->view('/ProjectTask/widgets/today_task', $data);
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
            redirect($this->module . '/ProjectTask'); //Redirect On Listing page
        }
        if ($this->input->post('task_id')) {
            $id = $edit = $this->input->post('task_id');
        }
        $sub_task_id = $this->input->post('sub_task_id');
        $insert_data['project_id'] = $this->project_id;
        $insert_data['task_name'] = ucfirst($this->input->post('task_name'));
        $insert_data['description'] = $this->input->post('description', FALSE);
        $insert_data['milestone_id'] = $this->input->post('milestone_id');
        $start_date = $this->input->post('start_date');
        $due_date = $this->input->post('due_date');
        $insert_data['start_date'] = '';
        $insert_data['due_date'] = '';
        if (!empty($start_date)) {
            $insert_data['start_date'] = date("Y-m-d", strtotime($start_date));
        }
        if (!empty($due_date)) {
            $insert_data['due_date'] = date("Y-m-d", strtotime($due_date));
        }

        if (!isset($sub_task_id)) {
            $in_project_scope = $this->input->post('in_project_scope');
            $insert_data['in_project_scope'] = !empty($in_project_scope) ? $in_project_scope : 0;
            $insert_data['deal_id'] = $this->input->post('deal_id');
            $notify_team = $this->input->post('notify_team');
            $insert_data['notify_team'] = !empty($notify_team) ? $notify_team : 0;
            $notify_project_manager = $this->input->post('notify_project_manager');
            $insert_data['notify_project_manager'] = !empty($notify_project_manager) ? $notify_project_manager : 0;
            $milestone_id = $this->input->post('milestone_id');
        }

        //sub task      
        if (!empty($sub_task_id)) {
            $insert_data['sub_task_id'] = $sub_task_id;
        }
        //Insert Record in Database
        if (!empty($id)) { //update
            $status_updated = '';
            $cur_status = $this->input->post('status');
            $table = PROJECT_TASK_MASTER;
            $match = "task_id = " . $id;
            $update_record = $this->common_model->get_records($table, array('task_id,status'), '', '', $match);
            if ($update_record[0]['status'] != $cur_status) {
                $status_updated = 1;
            }
            $insert_data['modified_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['modified_date'] = datetimeformat();
            $insert_data['status'] = $this->input->post('status');
            $where = array('task_id' => $id);
            $success_update = $this->common_model->update(PROJECT_TASK_MASTER, $insert_data, $where);
            //assign team member
            $team_member = $this->input->post('team_member');
            if (!empty($team_member)) {
                $where1 = array('task_id' => $id);
                $assign_teammember = $this->common_model->get_records(PROJECT_TASK_TEAM_TRAN, array('user_id'), '', '', $where1, '');

                $old_teammember = array();
                if (!empty($assign_teammember)) {
                    foreach ($assign_teammember as $row) {
                        $old_teammember[] = $row['user_id'];
                    }
                }

                //delete task
                $delete_teammember = array_diff($old_teammember, $team_member);
                //pr($delete_teammember);exit;
                if (!empty($delete_teammember)) {
                    $this->common_model->delete_where_in(PROJECT_TASK_TEAM_TRAN, $where1, 'user_id', $delete_teammember);
                }
                //insert_teammember
                $insert_teammember = array_diff($team_member, $old_teammember);
                if (!empty($insert_teammember)) {
                    foreach ($insert_teammember as $insert_teammember) {
                        $team_assign_data['task_id'] = $id;
                        $team_assign_data['user_id'] = $insert_teammember;
                        $team_assign_data['created_date'] = datetimeformat();
                        $this->common_model->insert(PROJECT_TASK_TEAM_TRAN, $team_assign_data);
                    }
                }
            }
            if ($success_update) {
                //Get Records From PROSPECT_MASTER Table
                $activity['activity'] = 'edited a task - ' . $insert_data['task_name'];
                $this->log_activity($activity);
                if (!empty($status_updated)) {
                    //get status detail
                    $match = "status_id = " . $cur_status;
                    $status_del = $this->common_model->get_records(PROJECT_STATUS, array('status_id', 'status_name'), '', '', $match);

                    //Add activity
                    if (!empty($status_del)) {
                        $status_text = !empty($status_del[0]['status_name']) ? $status_del[0]['status_name'] . '' : '';
                    }
                    $activity['activity'] = 'changed status <b>' . $status_text . '</b> - ' . $task[0]['task_name'];
                    $activity['status_id'] = $cur_status;
                    $this->log_activity($activity);
                }
                if (!empty($sub_task_id)) {
                    $msg = $this->lang->line('sub_task_update_msg');
                } else {
                    $msg = $this->lang->line('task_update_msg');
                }
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        } else { //insert
            $insert_data['task_code'] = $this->input->post('task_code');
            $insert_data['created_by'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
            $insert_data['created_date'] = datetimeformat();
            $where = array('is_delete' => 0);
            $project_status = $this->common_model->get_records(PROJECT_STATUS, array('status_id,status_name'), '', '', $where, '', 1, 0, 'status_order', 'asc');
            $insert_data['status'] = !empty($project_status[0]['status_id']) ? $project_status[0]['status_id'] : '1';
            $id = $this->common_model->insert(PROJECT_TASK_MASTER, $insert_data);
            $team_member = $this->input->post('team_member');
            if (!empty($team_member)) {
                foreach ($team_member as $team_member) {
                    $project_assign_data['task_id'] = $id;
                    $project_assign_data['user_id'] = $team_member;
                    $project_assign_data['created_date'] = datetimeformat();
                    $this->common_model->insert(PROJECT_TASK_TEAM_TRAN, $project_assign_data);
                }
            }

            if ($id) {
                $activity['activity'] = 'added new task - ' . $insert_data['task_name'];
                $activity['status_id'] = $insert_data['status'];
                $this->log_activity($activity);
                if (!empty($sub_task_id)) {
                    $msg = $this->lang->line('sub_task_add_msg');
                } else {
                    $msg = $this->lang->line('task_add_msg');
                }

                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        }
        //notify project manager
        if (!empty($notify_project_manager)) {
            //Get team member data
            $pmatch = "project_id =" . $this->project_id;
            $ufields = array("user_id");
            $assign_pm = $this->common_model->get_records(PROJECT_PM_ASSIGN, $ufields, '', '', $pmatch);
            if (!empty($assign_pm)) {
                foreach ($assign_pm as $row) {
                    if (!empty($row['user_id'])) {
                        if (!empty($edit)) {
                            $this->notifyTeamMemberUpdate($row['user_id'], $id);
                        } else {
                            $this->notifyTeamMember($row['user_id'], $id);
                        }
                    }
                }
            }
        }
        //Notify member
        if (!empty($notify_team)) {
            //Get team member data
            $pmatch = "project_id =" . $this->project_id;
            $ufields = array("member_id");
            $assign_team_member = $this->common_model->get_records(PROJECT_TEAM_MEMBERS, $ufields, '', '', $pmatch);
            if (!empty($assign_team_member)) {
                foreach ($assign_team_member as $row) {
                    if (!empty($row['member_id'])) {

                        if (!empty($edit)) {
                            $this->notifyTeamMemberUpdate($row['member_id'], $id);
                        } else {
                            $this->notifyTeamMember($row['member_id'], $id);
                        }
                    }
                }
            }
        }
        /* Upload code */
        $project_upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id;
        if (!is_dir($project_upload_dir)) {
            //create directory
            mkdir($project_upload_dir, 0777, TRUE);
        }
        $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('project_task_folder');
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
            $file_path = $this->config->item('project_upload_path') . 'Project0' . $this->project_id . '/' . $this->config->item('project_task_folder') . '/';
            $data['file_view'] = $this->module . '/ProjectTask';
            ;
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
            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $add_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $upload_file[] = ['file_name' => $add_files[$i],
                            'file_path' => $gallery_path[$i],
                            'task_id' => $id,
                            'created_date' => datetimeformat(),
                            'upload_status' => 1];
                    }
                }
            }
            if (count($upload_data) > 0) {
                foreach ($upload_data as $files) {
                    $upload_file[] = ['file_name' => $files['file_name'],
                        'file_path' => $file_path,
                        'task_id' => $id,
                        'upload_status' => 0,
                        'created_date' => datetimeformat()];
                }
            }

            if (count($upload_file) > 0) {
                $this->common_model->insert_batch(PROJECT_TASK_FILES, $upload_file);
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
            $this->common_model->delete(PROJECT_TASK_FILES, 'task_file_id IN(' . $dlStr . ')');
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        //end upload
        //end upload
        redirect($this->module . '/ProjectTask'); //Redirect On Listing page
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function add_record() {

        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }
        $data['modal_title'] = $this->lang->line('create_task');
        $data['submit_button_title'] = $this->lang->line('create_task');

        //Get project assign user
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('l.is_delete' => 0, 'project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
        //project detail
        $where_pro = array('project_id' => $this->project_id);
        $data['project_detail'] = $this->common_model->get_records(PROJECT_MASTER, array('project_id',
            'project_name',
            'status',
            'start_date',
            'due_date'), '', '', $where_pro, '');
        //Get milestone
        $where = array('is_delete' => 0, 'project_id' => $this->project_id, 'due_date >=' => date('Y-m-d'));
        $fields = array('milestone_id,milestone_name,start_date,due_date');
        $data['milestone'] = $this->common_model->get_records(MILESTONE_MASTER, $fields, '', '', $where, '');

        //Get deal Information
        $table = PROSPECT_MASTER . ' as pro';
        $match = "pro.status_type = 3 and pro.is_delete = 0";
        $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['deal_data'] = $this->common_model->get_records($table, $fields, '', '', $match);


        $data['task_view'] = $this->module . '/ProjectTask';
        $data['task_code'] = 'T' . rand();
        $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");
        //Pass TABLE Record In View
        $this->load->view('/ProjectTask/Add_task', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : View record
      @Input  : View id
      @Output : Give view record
      @Date   : 9/02/2016
     */

    public function view_record($id = '') {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        if (!empty($id)) {
            //Get Records From PROSPECT_MASTER Table
            //Get Records From task Table  
            $where = array('pt.task_id' => $id,
                'pt.project_id' => $this->project_id);
            $table = PROJECT_TASK_MASTER . ' as pt';
            $fields = array('pr.prospect_name,pt.in_project_scope,pt.notify_team,pt.notify_project_manager,group_concat(l.firstname," ",l.lastname) as user_name,ps.status_name,pt.task_id,pt.description,pt.task_code,pt.task_name,tp.task_name as parent_task,pt.start_date,pt.due_date,pt.status,pt.sub_task_id,mi.milestone_name,mi.milestone_id,pt.deal_id,ps.status_name,ps.status_color,pt.created_date,pt.modified_date');
            $join_table = array(
                PROJECT_TASK_MASTER . ' as tp' => 'pt.sub_task_id=tp.task_id',
                PROJECT_STATUS . ' as ps' => 'ps.status_id=pt.status',
                MILESTONE_MASTER . ' as mi' => 'mi.milestone_id=pt.milestone_id',
                PROJECT_TASK_TEAM_TRAN . ' as te' => 'te.task_id=pt.task_id',
                PROSPECT_MASTER . ' as pr' => 'pr.prospect_id=pt.deal_id',
                LOGIN . ' as l' => 'te.user_id=l.login_id');
            $group_by = 'te.task_id';
            $table = PROJECT_TASK_MASTER . ' as pt';
            $data['edit_record'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', '', '', $group_by);
            //get subtasks
            $data['sub_tasks'] = $this->common_model->get_records(PROJECT_TASK_MASTER . ' as pt', $fields, $join_table, 'left', array('pt.sub_task_id' => $id,
                'pt.project_id' => $this->project_id), '', '', '', '', '', $group_by);
            //get Work Log
            $fields_timesheet = array('pa.*,pt.task_name,concat(l.firstname," ",l.lastname) as username,l.profile_photo,rm.role_name,(select sum(spent_time) from blzdsk_project_timesheets where user_id=pa.user_id and task_id=' . $id . ') as total_spent_user,(select sum(spent_time) from blzdsk_project_timesheets where task_id=' . $id . ') as total_spent');
            $join_table_timesheet = array(LOGIN . ' as l' => 'pa.user_id=l.login_id',
                PROJECT_TASK_MASTER . ' as pt' => 'pa.task_id=pt.task_id',
                ROLE_MASTER . ' as rm' => 'rm.role_id=l.user_type');
            $group_by = 'pa.user_id';
            $data['time_sheets'] = $this->common_model->get_records(PROJECT_TIMESHEETS . ' as pa', $fields_timesheet, $join_table_timesheet, 'left', "pt.task_id = " . $id . " AND pa.is_delete=0 ", '', '', '', '', '', $group_by);
            //get assign team member
            $table = PROJECT_TASK_TEAM_TRAN;
            $match = "task_id = " . $id;
            $field = array('user_id');
            $assign_user = $this->common_model->get_records($table, $field, '', '', $match);
            $user_id = array();
            if (!empty($assign_user)) {
                foreach ($assign_user as $assign_user) {
                    $user_id[] = $assign_user['user_id'];
                }
            }
            $data['user_id'] = $user_id;
            $data['id'] = $id;
            $data['task_view'] = $this->module . '/ProjectTask';
            $data['modal_title'] = !empty($data['edit_record'][0]['task_name']) ? $data['edit_record'][0]['task_name'] : '';
            $data['submit_button_title'] = $this->lang->line('create_task');
            //Get project task file
            $match = "task_id = " . $id;
            $field = array('task_file_id,task_id,upload_status,file_name,file_path');
            $data['task_files'] = $this->common_model->get_records(PROJECT_TASK_FILES, $field, '', '', $match);
        }
        //Get Project Manager Data
        $this->load->model('TeamMembers_model');
        $data['project_manager'] = $this->TeamMembers_model->getProjectManager($this->project_id);

        //getting record of task related users
        $table = PROJECT_TASK_TEAM_TRAN . ' as pa';
        $fields = array('*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id', ROLE_MASTER . ' as rm' => 'l.user_type=rm.role_id');
        $where = array('l.is_delete' => 0, 'task_id' => $id);
        $data['memberlist'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
//        echo $this->db->last_query();
        //Pass TABLE Record In View
        $this->load->view('/ProjectTask/View_task', $data);
    }

    public function edit_record($id = '') {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }
        if (!empty($id)) {
            //Get Records From PROSPECT_MASTER Table
            $table = PROJECT_TASK_MASTER;
            $match = "task_id = " . $id;

            $edit_record = $this->common_model->get_records($table, '', '', '', $match);
            //get assign team member
            $table = PROJECT_TASK_TEAM_TRAN;
            $match = "task_id = " . $id;
            $field = array('user_id');
            $assign_user = $this->common_model->get_records($table, $field, '', '', $match);
            $user_id = array();
            if (!empty($assign_user)) {
                foreach ($assign_user as $assign_user) {
                    $user_id[] = $assign_user['user_id'];
                }
            }
            $data['user_id'] = $user_id;

            $data['id'] = $id;
            $data['edit_record'] = $edit_record;
            //Get project task file
            $match = "task_id = " . $id;
            $field = array('task_file_id,task_id,upload_status,file_name,file_path');
            $data['task_files'] = $this->common_model->get_records(PROJECT_TASK_FILES, $field, '', '', $match);

            $data['modal_title'] = $this->lang->line('update_task');
            $data['submit_button_title'] = $this->lang->line('update_task');
        }

        //Get user
        /* $where = array('status' => 1);
          $data['res_user']      = $this->common_model->get_records(LOGIN,'','','',$where,''); */
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
        //Get project status
        //project detail
        $where_pro = array('project_id' => $this->project_id);
        $data['project_detail'] = $this->common_model->get_records(PROJECT_MASTER, array('project_id',
            'project_name',
            'status',
            'start_date',
            'due_date'), '', '', $where_pro, '');
        $where = array('is_delete' => 0);
        $data['project_status'] = $this->common_model->get_records(PROJECT_STATUS, array('status_id,status_name'), '', '', $where, '', '', '', 'status_order', 'asc');
        //get deal data
        //Get deal Information
        $table = PROSPECT_MASTER . ' as pro';
        $match = "pro.status_type = 3";
        $fields = array("pro.prospect_id,pro.prospect_auto_id,pro.prospect_name");
        $data['deal_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

        //Get milestone
        $where = array('project_id' => $this->project_id);
        $data['milestone'] = $this->common_model->get_records(MILESTONE_MASTER, '', '', '', $where, '');
        $data['task_view'] = $this->module . '/ProjectTask';

        $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");
        //Pass TABLE Record In View
        $this->load->view('/ProjectTask/Add_task', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function add_subtask() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }
        $data['modal_title'] = $this->lang->line('create_sub_task');
        $data['submit_button_title'] = $this->lang->line('create_sub_task');

        //Get user
        /* $where = array('status' => 1);
          $data['res_user']      = $this->common_model->get_records(LOGIN,'','','',$where,''); */
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('l.is_delete' => 0, 'project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
        //Get task
        $where = array('sub_task_id' => 0, 'is_delete' => 0,
            'project_id' => $this->project_id, 'due_date >=' => date('Y-m-d'));
        //$wherestring = '(status = 1 or status = 2)';
        $data['task_data'] = $this->common_model->get_records(PROJECT_TASK_MASTER, '', '', '', $where, '', '', '', 'task_id', 'desc', '', '', '', '', '');
        //project detail
        $where_pro = array('project_id' => $this->project_id);
        $data['project_detail'] = $this->common_model->get_records(PROJECT_MASTER, array('project_id',
            'project_name',
            'status',
            'start_date',
            'due_date'), '', '', $where_pro, '');
        //Get milestone
        $where = array('is_delete' => 0, 'project_id' => $this->project_id, 'due_date >=' => date('Y-m-d'));
        $data['milestone'] = $this->common_model->get_records(MILESTONE_MASTER, '', '', '', $where, '');
        $data['task_view'] = $this->module . '/ProjectTask';
        $data['sub_task'] = 1;
        $data['task_code'] = 'T' . rand();
        $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");
        //Pass TABLE Record In View
        $this->load->view('/ProjectTask/Add_task', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Edit record
      @Input  : Edit id
      @Output : Give edit record
      @Date   : 18/01/2016
     */

    public function edit_subtask($id = '') {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }
        if (!empty($id)) {
            //Get Records From PROSPECT_MASTER Table
            $table = PROJECT_TASK_MASTER;
            $match = "task_id = " . $id;

            $edit_record = $this->common_model->get_records($table, '', '', '', $match);
            //get assign team member
            $table = PROJECT_TASK_TEAM_TRAN;
            $match = "task_id = " . $id;
            $field = array('user_id');
            $assign_user = $this->common_model->get_records($table, $field, '', '', $match);
            $user_id = array();
            if (!empty($assign_user)) {
                foreach ($assign_user as $assign_user) {
                    $user_id[] = $assign_user['user_id'];
                }
            }
            $data['user_id'] = $user_id;
            //Get assign milestone
            $table = MILESTONE_TASK_TRANS;
            $match = "task_id = " . $id;
            $field = array('milestone_id');
            $milestone_id = $this->common_model->get_records($table, $field, '', '', $match);
            if (!empty($milestone_id)) {
                $data['milestone_id'] = $milestone_id['0']['milestone_id'];
            }
            $data['id'] = $id;
            $data['edit_record'] = $edit_record;
            //project detail
            $where_pro = array('project_id' => $this->project_id);
            $data['project_detail'] = $this->common_model->get_records(PROJECT_MASTER, array('project_id',
                'project_name',
                'status',
                'start_date',
                'due_date'), '', '', $where_pro, '');
            //Get project task file
            $match = "task_id = " . $id;
            $field = array('task_file_id,task_id,upload_status,file_name,file_path');
            $data['task_files'] = $this->common_model->get_records(PROJECT_TASK_FILES, $field, '', '', $match);

            $data['modal_title'] = $this->lang->line('update_sub_task');
            $data['submit_button_title'] = $this->lang->line('update_sub_task');
        }

        //Get user
        /* $where = array('status' => 1);
          $data['res_user']      = $this->common_model->get_records(LOGIN,'','','',$where,''); */
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('project_id' => $this->project_id);
        $data['res_user'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '');
        //Get project status
        $where = array('is_delete' => 0);
        $data['project_status'] = $this->common_model->get_records(PROJECT_STATUS, array('status_id,status_name'), '', '', $where, '', '', '', 'status_order', 'asc');
        //Get task
        $where = array('sub_task_id' => 0,
            'project_id' => $this->project_id);
        //$wherestring = '(status = 1 or status = 2)';
        $data['task_data'] = $this->common_model->get_records(PROJECT_TASK_MASTER, '', '', '', $where, '', '', '', 'task_id', 'desc', '', '', '', '', '');
        //Get milestone
        $where = array('project_id' => $this->project_id);
        $data['milestone'] = $this->common_model->get_records(MILESTONE_MASTER, '', '', '', $where, '');
        $data['task_view'] = $this->module . '/ProjectTask';
        $data['sub_task'] = 1;
        $data['url'] = base_url($this->module . "/Filemanager/index/?dir=uploads/projectManagement/Project0" . $this->project_id . "/&modal=true");
        //Pass TABLE Record In View
        $this->load->view('/ProjectTask/Add_task', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Update is delete
      @Input  : Edit id
      @Output : Update is delete
      @Date   : 18/01/2016
     */

    public function delete_record($id = '') {
        if (!empty($id)) {
            //Get Records From PROSPECT_MASTER Table
            $table = PROJECT_TASK_MASTER;
            $match = "task_id = " . $id;
            $record = $this->common_model->get_records($table, '', '', '', $match);
            //Is delete record
            $update_data['is_delete'] = 1;
            $where = array('task_id' => $id);
            $this->common_model->update(PROJECT_TASK_MASTER, $update_data, $where);
            //sub task
            $where = array('sub_task_id' => $id);
            $this->common_model->update(PROJECT_TASK_MASTER, $update_data, $where);
            //timesheet
            $where = array('task_id' => $id);
            $this->common_model->update(PROJECT_TIMESHEETS, $update_data, $where);
            unset($id);
            if (!empty($record[0]['sub_task_id'])) {
                $msg = $this->lang->line('sub_task_del_msg');
            } else {
                $msg = $this->lang->line('task_del_msg');
            }
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        redirect($this->module . '/ProjectTask'); //Redirect On Listing page
    }

    /*
      @Author : Niral Patel
      @Desc   : Delete file
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    public function update_status() {
        $status = $this->input->post('status');
        $task_id = $this->input->post('task_id');
        if (!empty($task_id) && !empty($status)) {
            $table = PROJECT_TASK_MASTER;
            $match = "task_id = " . $task_id;
            $task = $this->common_model->get_records($table, array('task_id',
                'task_name'), '', '', $match);
            $where = array('task_id' => $task_id);
            $data['status'] = $status;
            $this->common_model->update(PROJECT_TASK_MASTER, $data, $where);
            //get status detail
            $match = "status_id = " . $status;
            $status_del = $this->common_model->get_records(PROJECT_STATUS, array('status_id', 'status_name'), '', '', $match);

            //Add activity
            if (!empty($status_del)) {
                $status_text = !empty($status_del[0]['status_name']) ? $status_del[0]['status_name'] . '' : '';
            }
            $activity['activity'] = 'changed status <b>' . $status_text . '</b> - ' . $task[0]['task_name'];
            $activity['status_id'] = $status;
            $this->log_activity($activity);
            echo 'done';
        }
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
            $match = "task_file_id = " . $id;
            $res = $this->common_model->get_records(PROJECT_TASK_FILES, array('task_file_id', 'file_name', 'file_path', 'upload_status'), '', '', $match);
            $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('project_task_folder') . '/';
            if (empty($res[0]['upload_status']) && !empty($res[0]['file_name']) && !empty($res[0]['file_path'])) {
                if (file_exists($upload_dir . $res[0]['file_name'])) {
                    unlink($res[0]['file_path'] . '/' . $res[0]['file_name']);
                }
            }
            $where = array('task_file_id' => $id);
            if ($this->common_model->delete(PROJECT_TASK_FILES, $where)) {
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
            $params['table'] = PROJECT_TASK_FILES . ' as PT';
            $params['match_and'] = 'PT.task_file_id=' . $id . '';
            $task_files = $this->common_model->get_records_array($params);
            if (count($task_files) > 0) {
                $pth = file_get_contents(base_url($task_files[0]['file_path'] . '/' . $task_files[0]['file_name']));
                $this->load->helper('download');
                force_download($task_files[0]['file_name'], $pth);
            }
            redirect($this->module . '/ProjectTask');
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
      @Desc   : Insert module activities
      @Input    :
      @Output   :
      @Date   : 29/01/2016
     */

    function log_activity($activity) {
        $activity['user_id'] = !empty($this->user_info['ID']) ? $this->user_info['ID'] : '';
        $activity['module'] = 'task';
        $activity['project_id'] = $this->project_id;
        $activity['activity_date'] = datetimeformat();
        $this->common_model->insert(PROJECT_ACTIVITIES, $activity);
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

        $upload_dir = $this->config->item('project_upload_root_url') . 'Project0' . $this->project_id . '/' . $this->config->item('project_task_folder');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            file_put_contents($upload_dir . '/' . $filename, $str);
        }
    }

    /*
     * 
     */

    public function dashboardWidgetsOrder() {
        if (!$this->input->is_ajax_request()) {
            exit("no direct scripts allowed");
        } else {
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];
            if ($this->input->get('resetWidgets')) {
                $defaultDashboard = array('sectionLeft' => array('project_budget_count_view', 'budget_spent'), 'sectionRight' => array('today_task', 'Task_activities', 'messagebox_view'));
                $data = array('dashboard_widgets' => json_encode($defaultDashboard));
                $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                $this->session->unset_userdata('blazedesk_pm_taskdashboardWidgets');
                $this->session->set_userdata('blazedesk_pm_taskdashboardWidgets', $defaultDashboard);
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
                    $data = array('blazedesk_pm_taskdashboardWidgets' => json_encode($widgetsArr));
                    $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                    $this->session->unset_userdata('blazedesk_pm_taskdashboardWidgets');
                    $this->session->set_userdata('blazedesk_pm_taskdashboardWidgets', $widgetsArr);
                }
                echo json_encode(array('status' => 1, 'type' => 'new'));
                die;
            }
        }
    }

    public function dashboardWidgetsInnerOrder() {
        if (!$this->input->is_ajax_request()) {
            exit("no direct scripts allowed");
        } else {
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];
            if ($this->input->get('resetWidgets')) {
                $defaultDashboard = array('innerWidgets' => array('Task_header', 'tlists'));
                $data = array('dashboard_widgets' => json_encode($defaultDashboard));
                $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                $this->session->unset_userdata('blazedesk_pm_taskdashboardinnerWidgets');
                $this->session->set_userdata('blazedesk_pm_taskdashboardinnerWidgets', $defaultDashboard);
                echo json_encode(array('status' => 1, 'type' => 'reset'));
                die;
            } else {
                $innerWidgets1 = $this->input->post('innerWidgets1');
                $widgetsArr = array();
                if (count($innerWidgets1) > 0) {
                    //  $taskdashboardinnerWidgets = array('innerWidgets' => array('Task_header', 'tlists'));

                    $defaultPMDashboard = array('innerWidgets' => $innerWidgets1);
                    $data = array('taskdashboardinnerWidgets' => json_encode($defaultPMDashboard));
                    $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                    $this->session->unset_userdata('taskdashboardinnerWidgets');
                    $this->session->set_userdata('taskdashboardinnerWidgets', $innerWidgets1);
                }
                echo json_encode(array('status' => 1, 'type' => 'new'));
                die;
            }
        }
    }

}
