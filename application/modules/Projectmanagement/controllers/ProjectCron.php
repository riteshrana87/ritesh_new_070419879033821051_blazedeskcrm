<?php

defined ('BASEPATH') OR exit('No direct script access allowed');

class ProjectCron extends CI_Controller {

    function __construct() {
        parent::__construct ();
        $this->module     = $this->uri->segment (1);
        $this->viewname   = $this->uri->segment (2);
    }

    /*
      @Author : Niral Patel
      @Desc   : ProjectCron index
      @Input  : 
      @Output :
      @Date   : 07/04/2016
     */

    public function index() {
        
    }
    /*
      @Author : Niral Patel
      @Desc   : ProjectCron index
      @Input  : 
      @Output :
      @Date   : 07/04/2016
     */
      public function send_notification_pm() 
      {
            $now   = date('Y-m-d'); // last day of month
            $table = PROJECT_TASK_MASTER .' as pt';
            $match = "pt.is_delete = 0 and pt.status != 4 or pt.status != 5 and pt.due_date > '" . $now."'";
            $field = array('pt.task_id,pt.project_id,pt.status,pt.due_date,l.email,pm.user_id');
            $join_table = array(
            PROJECT_PM_ASSIGN . ' as pm' => 'pm.project_id=pt.project_id',
            LOGIN . ' as l' => 'pm.user_id=l.login_id');
            $task_data = $this->common_model->get_records($table, $field, $join_table, 'left', $match, '', '', '', '', '','','', '', '', '');
            
            if(!empty($task_data))
            {
                foreach ($task_data as $row) {
                   if(!empty($row['user_id']))
                   {
                        $this->notifyTeamMemberUpdate($row['user_id'], $row['task_id'],$row['project_id']);
                   }
                }
            }
            echo 'Done';
      }
      /*
      @Author : Niral Patel
      @Desc   : Function for notify member
      @Input  :
      @Output :
      @Date   : 13/01/2016
     */

    function notifyTeamMemberUpdate($userId, $taskId, $projectId) {
        // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
        //$ematch = "et.subject ='Update task'";
        $ematch = "et.template_id = 51";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
        $umatch = "login_id =" . $userId;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        /*//Get loging user data
        $umatch = "login_id =" . $this->user_info['ID'];
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $log_udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);*/
        //get assign team member
        $table = PROJECT_TASK_TEAM_TRAN." as pt";
        $match = "task_id = " . $taskId;
        $field = array('user_id,group_concat(l.firstname," ",l.lastname) as user_name');
        $join_table = array(
                LOGIN . ' as l' => 'pt.user_id=l.login_id');
        $group_by = 'pt.task_id';
        $assign_user = $this->common_model->get_records($table, $field, $join_table, 'left', $match, '', '', '', '', '', $group_by);
        if(!empty($assign_user))
        {
            $logusername = $assign_user[0]['user_name'];
        }
        //get Current Project Name
        $pmatch = "project_id =" . $projectId;
        $pfields = array("project_name");
        $pdata = $this->common_model->get_records(PROJECT_MASTER, $pfields, '', '', $pmatch);
        $email = !empty($udata[0]['email']) ? $udata[0]['email'] : '';
        $fullname = !empty($udata[0]['fullname']) ? ucfirst($udata[0]['fullname']) : '';
        //$logusername = !empty($log_udata[0]['fullname']) ? ucfirst($log_udata[0]['fullname']) : '';
        $projectName = !empty($pdata[0]['project_name']) ? $pdata[0]['project_name'] : '';
        //get task data
        $tmatch = "task_id =" . $taskId;
        $tfields = array("pt.task_name,pt.description,pt.start_date,pt.due_date,ps.status_name");
        $join_table = array(
                PROJECT_STATUS . ' as ps' => 'ps.status_id=pt.status');
        $taskdata = $this->common_model->get_records(PROJECT_TASK_MASTER ." as pt", $tfields, $join_table, 'left', $tmatch);
        $taskName = $taskdata[0]['task_name'];
        $start_date = ($taskdata[0]['start_date'] != '0000-00-00')?configDateTime($taskdata[0]['start_date']):'';
        $due_date = ($taskdata[0]['due_date'] != '0000-00-00')?configDateTime($taskdata[0]['due_date']):'';
        $taskDescription = $taskdata[0]['description'];
        $find = array(
            '{USER}',
            '{CREATE_USER}',
            '{PROJECT}',
            '{TASK_NAME}',
            '{PROJECT_STATUS}',
            //'{START_DATE}',
            '{DUE_DATE}',
            //'{PROJECT_STATUS}',
            //'{DESCRIPTION}'

                //    '{DATE}'
        );

        $replace = array(
            'USER' => $fullname,
            'CREATE_USER' => $logusername,
            'PROJECT' => $projectName,
            'TASK_NAME' => $taskName,
            'PROJECT_STATUS' => !empty($taskdata[0]['status_name'])?$taskdata[0]['status_name']:'',
            //'START_DATE' => $start_date,
            'DUE_DATE' => $due_date,
            //'PROJECT_STATUS'=>!empty($taskdata[0]['status_name'])?$taskdata[0]['status_name']:'',
            //'DESCRIPTION' => $taskDescription,
                //    'DATE' => $order_info['payment_company']
        );
        $format = $template[0]['body'];
        $body = str_replace(array("\r\n",
            "\r",
            "\n"), '<br />', preg_replace(array("/\s\s+/",
            "/\r\r+/",
            "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
        $subject = "BLAZEDESK :: " . $template[0]['subject'];
        send_mail($email, $subject, $body);
    }
}
