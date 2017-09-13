<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Projectdashboard extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->module = 'Projectmanagement';
        $this->viewname = $this->router->fetch_class();
        $this->project_id = $this->session->userdata('PROJECT_ID');
        $this->user_info = $this->session->userdata('LOGGED_IN');  //Current Login information
        $latest_project = $this->common_model->get_records(PROJECT_MASTER, array('project_id'), '', '', 'is_delete=0', '', 1, 0, 'project_id', 'desc');
        $fields = array('pt.project_id');
        if($this->user_info['ROLE_TYPE'] == '39')
        {$where = array('pt.is_delete' => 0);}
        else
        {$where = array('pt.is_delete' => 0,'te.user_id'=>$this->user_info['ID']);}
        $group_by = 'pt.project_id';
        $join_table = array(
            PROJECT_ASSIGN_MASTER . ' as te' => 'te.project_id=pt.project_id',
            LOGIN . ' as l' => 'te.user_id=l.login_id  and l.is_delete = 0',
            );
        $latest_project = $this->common_model->get_records(PROJECT_MASTER.' as pt', $fields, $join_table, 'left', $where, '', 1, 0, 'project_id', 'desc',$group_by);
        //pr($latest_project);exit;
        if (count($latest_project) == 0) {

            /*$msg = lang('project_no_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            */redirect('Projectmanagement');
        }
        if (empty($this->project_id)) {

            if (count($latest_project) > 0) {
                $this->session->set_userdata('PROJECT_ID', $latest_project[0]['project_id']);
                $this->project_id = $this->session->userdata('PROJECT_ID');
            } else {
                /*$msg = lang('project_no_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");*/
                redirect('Projectmanagement');
            }
        }

    }

    /*
      @Author : Niral Patel
      @Desc   : Home Index Page
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    public function index() {
        $data['user_info'] = $this->session->userdata('LOGGED_IN');  //Current Login information
        $data['drag'] = true;
        $data['current_project_status'] = $this->session->has_userdata('PROJECT_STATUS') ? $this->session->userdata('PROJECT_STATUS') : '';
        /*
         * dashboard widgets default key
         */
        $dashWhere = "config_key='dashboard_pm_widgets'";
        $defaultDashboard = $this->common_model->get_records(CONFIG, array('value'), '', '', $dashWhere);
        $data['header'] = array('menu_module' => 'Projectmanagement');
        $size = $this->getDirectorySize($this->config->item('project_upload_path') . 'Project0' . $this->project_id);
        $data['file_usage'] = !empty($size) ? $this->sizeFormat($size['size']) : '';
        //Get upcoming task
        $table = PROJECT_TASK_MASTER . ' as pt';
        $fields = array('pt.task_id,pt.task_code,pt.task_name,pt.created_date');
        $where = array('pt.project_id' => $this->project_id);
        $wheresting_to = "due_date >='" . date('Y-m-d H:i:s') . "'";
        $data['upcoming_tasks'] = $this->common_model->get_records($table, $fields, '', '', $where, '', '6', 0, 'pt.task_id', 'desc', '', $wheresting_to);

        //get today`s activity
        $table = PROJECT_ACTIVITIES . ' as pa';
        $fields = array('pa.*,CONCAT(l.firstname, " ", l.lastname) AS user_name,l.profile_photo');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('project_id' => $this->project_id);
        $data['activities_total'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', 'activity_id', 'desc', '', '', '', '', '1');
        $data['activities'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', 5, 0, 'activity_id', 'desc', '');
        //Get count task status wise
        $table = PROJECT_STATUS . ' as ps';
        $fields = array('ps.status_id,ps.status_name,ps.status_color,ps.status_font_icon,COUNT(task_id) as total_task');
        $join_table = array(PROJECT_TASK_MASTER . ' as pt' => 'pt.status = ps.status_id and pt.project_id =' . $this->project_id);
        $group_by = 'ps.status_id';
        $data['project_tasks_status'] = $this->common_model->get_records($table, $fields, $join_table, 'left', '', '', '', '', '', '', $group_by);
        //Get total task pie chart data
        //Get count task status wise
        $table = PROJECT_STATUS . ' as ps';
        $fields = array('ps.status_id,ps.status_name,ps.status_color,ps.status_font_icon,COUNT(task_id) as total_task');
        $join_table = array(PROJECT_TASK_MASTER . ' as pt' => 'pt.status = ps.status_id and pt.project_id =' . $this->project_id);
        $group_by = 'ps.status_id';
        $project_tasks_status = $this->common_model->get_records($table, $fields, $join_table, 'left', '', '', '', '', '', '', $group_by);

        $total_task_count = 0;
        foreach ($project_tasks_status as $row) {
            $total_task_count += $row['total_task'];
        }

        $pie_chart_data_str = '';
        $status_color_str = '';
        $count_perc1 = 0;
        $count_perc2 = 0;
        $completed_status_str = '';
        if (!empty($total_task_count)) {

            foreach ($project_tasks_status as $project_tasksstatus) {
                if ($project_tasksstatus['status_id'] != 5) {
                    $count_perc1 += number_format(($project_tasksstatus['total_task'] * 100) / $total_task_count, 2);
                } else {
                    $count_perc2 = number_format(($project_tasksstatus['total_task'] * 100) / $total_task_count, 2);
                }

                $count_perc = number_format(($project_tasksstatus['total_task'] * 100) / $total_task_count, 2);
                if ($count_perc != 0.00) {
                    $pie_chart_data_str .= "{name: '" . $project_tasksstatus['status_name'] . "',  y: $count_perc},";
                    $status_color_str .= "'" . $project_tasksstatus['status_color'] . "',";
                }
            }
            if ($count_perc2 != 0.00) {
                $completed_status_str .= "{name: 'Completed',  y: $count_perc2},";
            }
            if ($count_perc1 != 0.00) {
                $completed_status_str .= "{name: 'Other Status',  y: $count_perc1}";
            }
            //$completed_status_str="{name: 'Completed',  y: $count_perc2},{name: 'Other Status',  y: $count_perc1}";
            $data['pie_completed_task_str'] = $completed_status_str;
            $data['status_color_str'] = rtrim($status_color_str, ",");
            $data['pie_chart_data_str'] = rtrim($pie_chart_data_str, ",");
            $data['completed_status'] = !empty($count_perc2) ? $count_perc2 : '0';
        }
        if ($this->session->has_userdata('dashboard_pm_widgets')) {
            $data['widgets'] = $this->session->userdata('dashboard_pm_widgets');
        } else {
            $data['widgets'] = (array) json_decode($defaultDashboard[0]['value']);
        }

        //Get project assign user
        $table = PROJECT_ASSIGN_MASTER . ' as pa';
        $fields = array('l.*');
        $join_table = array(LOGIN . ' as l' => 'pa.user_id=l.login_id');
        $where = array('l.is_delete' => 0, 'l.status' => 1, 'project_id' => $this->project_id);
        $data['active_user_count'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', '', '', '', '', '', '', '1');

        //load view
        $data['main_content'] = '/Projectdashboard/Projectdashboard';
        if ($this->input->is_ajax_request()) {
            $this->load->view('/' . $this->viewname . '/Projectdashboard', $data);
        } else {
            $data['js_content'] = '/Projectdashboard/loadJsFiles';
            $this->parser->parse('layouts/ProjectmanagementTemplate', $data);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : select project
      @Input  : project id
      @Output : Store in session
      @Date   : 18/01/2016
     */

    public function select_project($project_id = '') {
        //$project_id = $this->uri->segment(4);
        /*
         * below code is added for getting project status and set session accordingly
         * status 3 is completed status
         */
        $match = "pt.project_id = " . $project_id . ' and is_delete=0';
        $table = PROJECT_MASTER . ' as pt';

        $projectData = $this->common_model->get_records($table, '', '', '', $match, '');
        if (count($projectData) > 0) {
            $this->session->set_userdata('PROJECT_ID', $project_id);
            $this->session->set_userdata('PROJECT_STATUS', $projectData[0]['status']);
        }
        $this->session->set_userdata('PROJECT_ID', $project_id);
        echo 'done';
    }

    /*
      @Author : Niral Patel
      @Desc   : Calculate file size
      @Input  : directory path
      @Output : size array
      @Date   : 16/03/2016
     */

    function getDirectorySize($path) {
        if (is_dir($path)) {
            $totalsize = 0;
            $totalcount = 0;
            $dircount = 0;
            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    $nextpath = $path . '/' . $file;
                    if ($file != '.' && $file != '..' && !is_link($nextpath)) {
                        if (is_dir($nextpath)) {
                            $dircount++;
                            $result = $this->getDirectorySize($nextpath);
                            $totalsize += $result['size'];
                            $totalcount += $result['count'];
                            $dircount += $result['dircount'];
                        } elseif (is_file($nextpath)) {
                            $totalsize += filesize($nextpath);
                            $totalcount++;
                        }
                    }
                }
            }
            closedir($handle);
            $total['size'] = $totalsize;
            $total['count'] = $totalcount;
            $total['dircount'] = $dircount;
            return $total;
        } else {
            $total['size'] = 0;
            $total['count'] = 0;
            $total['dircount'] = 0;
            return $total;
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Give file size in format
      @Input  : File size
      @Output : In format(MB,KB..)
      @Date   : 16/03/2016
     */

    function sizeFormat($size) {
        if ($size < 1024) {
            return $size . " bytes";
        } else if ($size < (1024 * 1024)) {
            $size = round($size / 1024, 1);
            return $size . " KB";
        } else if ($size < (1024 * 1024 * 1024)) {
            $size = round($size / (1024 * 1024), 1);
            return $size . " MB";
        } else {
            $size = round($size / (1024 * 1024 * 1024), 1);
            return $size . " GB";
        }
    }

    /* function folderSize($dir) {
      $size = 0;

      foreach (glob (rtrim ($dir, '/') . '/*', GLOB_NOSORT) as $each) {
      $size += is_file ($each) ? filesize ($each) : $this->folderSize ($each);
      }

      return $size;
      } */
    /*
      @Author : Maulik Suthar
      @Desc   : Function is used to reformat dashboard widget
      @Input  : File size
      @Output : In format(MB,KB..)
      @Date   : 16/03/2016
     */

    public function dashboardWidgetsOrder() {
        if (!$this->input->is_ajax_request()) {
            exit("no direct scripts allowed");
        } else {
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];
            if ($this->input->get('resetWidgets')) {
                $defaultPMDashboard = array('widgetOrder' => array('toDoWidget', 'projectStatWidget', 'taskStatWidget', 'summaryWidget', 'recentActivitiesWidget', 'fileUsageWidget'));
                $data = array('dashboard_pm_widgets' => json_encode($defaultPMDashboard));
                $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                $this->session->unset_userdata('dashboard_pm_widgets');
                $this->session->set_userdata('dashboard_pm_widgets', $defaultPMDashboard);
                echo json_encode(array('status' => 1, 'type' => 'reset'));
                die;
            } else {
                $sortArr = $this->input->post('sortStr');

                if (count($sortArr) > 0) {

                    $defaultPMDashboard = array('widgetOrder' => $sortArr);

                    $data = array('dashboard_pm_widgets' => json_encode($defaultPMDashboard));
                    $this->common_model->update(LOGIN, $data, array('login_id' => $user_id));
                    $this->session->unset_userdata('dashboard_pm_widgets');
                    $this->session->set_userdata('dashboard_pm_widgets', $defaultPMDashboard);
                }
                echo json_encode(array('status' => 1, 'type' => 'new'));
                die;
            }
        }
    }
    function check_project()
    {
        $set_project = $this->input->post('set_project');
        $old_project_id = $this->input->post('oldid');
        if(isset($set_project))
        {
            $this->session->set_userdata('PROJECT_ID', $old_project_id);
        }
        else
        {
            
            $current = $this->session->userdata("PROJECT_ID");
            if($old_project_id != $current)
            {
                echo 1;
            }
            else
            { 
                if($this->session->userdata('PROJECT_STATUS') == 3)
                {
                    $check_class = $this->input->post('check_class');
                    if(!empty($check_class))
                    {echo 1;}
                    else
                    {echo 0;}
                }
                else
                {echo 0;}
            }
        }
        
    }
}
