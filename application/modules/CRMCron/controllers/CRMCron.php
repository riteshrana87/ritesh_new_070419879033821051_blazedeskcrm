<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CRMCron extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname  = $this->router->fetch_class(); 
        date_default_timezone_set('Asia/Kolkata');
    }

    /*
      @Author : Niral Patel
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 26/01/2016
     */

    public function index() {
    }

    /*
      @Author : Niral Patel
      @Desc   : Send to do remainder to user
      @Input 	:
      @Output	:
      @Date   : 26/01/2016
     */

    public function to_do() {
        
        $now   = date('Y-m-d'); // current date
        $table = TASK_MASTER .' as pt';
        //$match = "pt.is_delete = 0 and pt.remember=1 and ((pt.start_date <= '" . $now."' and pt.end_date >= '" . $now."') or (pt.start_date < '" . $now."' and pt.end_date >= '" . $now."'))";
        /*Get tasks */
        $match = "pt.is_delete = 0 and pt.remember=1 and pt.end_date >= '" . $now."'";
        $field = array('pt.task_id,pt.created_date,pt.remember,pt.reminder_date,pt.reminder_time,pt.task_description,pt.importance,pt.task_name,pt.status,pt.start_date,pt.end_date,l.email,pt.created_by');
        $join_table = array(
        LOGIN . ' as l' => 'pt.created_by=l.login_id');
        $task_data = $this->common_model->get_records($table, $field, $join_table, 'left', $match, '', '', '', '', '','','', '', '', '');
     
       if(!empty($task_data))
       {
        
            foreach ($task_data as $row) {
                ///$this->notify_user($row['created_by'], $row);
                
                $current_time    = date('H:i').':00';
                $current_date    = date('Y-m-d');
                
                $current_date_time = $current_date." ".$current_time;
                
                $remind_date = $row['reminder_date'] ;
                $remind_time = $row['reminder_time'] ;
                
                $remind_date_time = $remind_date." ".$remind_time;
                
                if($current_date_time == $remind_date_time)
                {
                  $this->notify_user($row['created_by'], $row);
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

    function notify_user($user_id, $taskdata) {
         // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
        $ematch = "et.subject ='Update task'";
        $ematch = "et.template_id = 59";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
        //$template = systemTemplateDataBySlug(TEMPLATE_TO_DO_REMINDER);
        $umatch = "login_id =" . $user_id;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        $email = !empty($udata[0]['email']) ? $udata[0]['email'] : '';
        $fullname = !empty($udata[0]['fullname']) ? ucfirst($udata[0]['fullname']) : '';
        $taskName = $taskdata['task_name'];
        $start_date = ($taskdata['start_date'] != '0000-00-00')?date('m/d/Y',strtotime($taskdata['start_date'])):'';
        $due_date = ($taskdata['end_date'] != '0000-00-00')?date('m/d/Y',strtotime($taskdata['end_date'])):'';
        $taskDescription = $taskdata['task_description'];
        $find = array(
            '{USER}',
            '{TASK_NAME}',
            '{IMPORTANCE}',
            '{START_DATE}',
            '{END_DATE}',
            //'{PROJECT_STATUS}',
            //'{DESCRIPTION}'

                //    '{DATE}'
        );

        $replace = array(
            'USER' => $fullname,
            'TASK_NAME' => $taskName,
            'IMPORTANCE' => !empty($taskdata['importance'])?$taskdata['importance']:'',
            'START_DATE' => $start_date,
            'END_DATE' => $due_date,
            //'PROJECT_STATUS'=>!empty($taskdata['status_name'])?$taskdata['status_name']:'',
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
        $sent = send_mail($email, $subject, $body);
       if($sent)
        {
            echo  "mail sent";
        }else
        {
            echo "mail not sent";
            
        }  
        
    }
	/*
      @Author : Rupesh Jorkar(RJ)
      @Desc   : Function for user who created estimate notified before 1 day when due date is near and estimate is not marked as accepted
      @Input  : 
      @Output :
      @Date   : 21/04/2016
     */
    function estNotifyForDueDate($user_id, $taskdata) {
	//Get Email TEmplate Master Records
		$ETMmatch 	  	= "et.template_id = 66";
		$ETMfields   	= array("et.subject,et.body");
		$emailTemplate 	= $this->common_model->get_records(EMAIL_TEMPLATE_MASTER . ' as et',$ETMfields,'','',$ETMmatch);
	//Get Tomorrow Date
		$tomorrowDate = "'".date("Y-m-d", strtotime("tomorrow"))."'";
	//Get Estimate Records of Tomorrow 
		$match = "ESTM.due_date = " . $tomorrowDate;
        $fields = array("ESTM.*");
        $estArray = $this->common_model->get_records(ESTIMATE_MASTER ." as ESTM", $fields, '', '', $match);
		if(count($estArray) != 0 && !empty($estArray))
		{
			if(count($emailTemplate) != 0 && !empty($emailTemplate))
			{
				$subject 	= $emailTemplate[0]['subject'];
				$body 		= $emailTemplate[0]['body'];
				foreach($estArray as $estData)
				{
					if(isset($estData['login_id']) && $estData['login_id'] != 0)
					{
						$userInfo = getUserDetail($estData['login_id']);
						if(count($userInfo) != 0 && !empty($userInfo))
						{	
							$userName = $userInfo[0]['firstname'].' '.$userInfo[0]['lastname'].',';
						} else {	$userName = ",";	}
						$to = $userInfo[0]['email'];
						$ESTCode = $estData['estimate_auto_id'];
					
						$replaceUserContent = str_replace("{USER}",$userName,$body);
						$bodyContent 		= str_replace("{EST-CODE}",$ESTCode,$replaceUserContent);
					//Send Mail Code
						if (send_mail($to,$subject,$bodyContent))
							{
								//echo "mail send to " . $userName;
							}
					}
				}
			}
		}
		exit;
	}
    
    /*
      @Author : Sanket Jayani
      @Desc   : Send Schedule Meeting Reminder mail Notification.
      @Input  : 
      @Output :
      @Date   : 09/05/2016
     */
      
    public function remind_schedule_meeting() 
    {
       
        $now   = date('Y-m-d'); // current date
        $table = TBL_SCHEDULE_MEETING_MASTER .' as mm';
       
        $match = "mm.is_delete = 0 and mm.meeting_reminder= 1 and mm.meeting_date >= '" . $now."'";
        $field = array('mm.*');
        $join_table = array(
        LOGIN . ' as l' => 'mm.meet_user_id=l.login_id');
        $data_schedule_meeting = $this->common_model->get_records($table, $field, $join_table, 'left', $match, '', '', '', '', '','','', '', '', '');
        
        
       if(!empty($data_schedule_meeting))
       {
            foreach ($data_schedule_meeting as $row) {
                $row['created_by'] = $row['meet_user_id'];
                $current_time      = date('H:i').':00';
                $current_date      = date('Y-m-d');
                
                $today_date_time = $current_date." ".$current_time;
                
                $remind_date = $row['reminder_date'] ;
                $remind_time = $row['reminder_time'] ;
                
                $remind_date_time = $remind_date." ".$remind_time;
                
                if($today_date_time == $remind_date_time)
                {
                    $this->schedule_meeting_notify_user($row['created_by'], $row);
                }
            }
       }
       echo 'Done';
    }
    
    function schedule_meeting_notify_user($user_id, $meeting_data) 
    {
        $tbl_meeting_receiepent = TBL_SCHEDULE_MEETING_RECEIPENT . ' as mr';
        $matach_meeting_receipent = "mr.meeting_master_id=".$meeting_data['meeting_master_id'];
        $fields_meeting_receiepent = array("mr.user_id,mr.user_type");
        $data_meeting_receipent = $this->common_model->get_records($tbl_meeting_receiepent, $fields_meeting_receiepent, '', '', $matach_meeting_receipent);
        
        $id_meeting_contact = [];
        $id_meeting_employee = [];
        foreach ($data_meeting_receipent as $contact)
        {
            $participant_id = $contact['user_id'];
            $participant_type =  $contact['user_type'];
            if($participant_type == '2')
            {
                $id_meeting_contact[] = $participant_id;
            }else
            {
                $id_meeting_employee[] = $participant_id;
            }
        }
            
        $contact_email_str = ''; 
        $employee_email_str = '';
        if(count($id_meeting_contact) > 0)
        {
            $contact_email_str = $this->getContactEmailbyId($id_meeting_contact);
        }

        if(count($id_meeting_employee) > 0)
        {
            $employee_email_str = $this->getMultipleLoginUserEmail($id_meeting_employee);
        }

        $email_meeting_receipent = $contact_email_str.",".$employee_email_str.",".$meeting_data['additiona_receipent_email']; 

        $email_send_to = $email_meeting_receipent;
       
        $umatch = "login_id =" . $user_id;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        $from_email =  $udata[0]['email'];
        $from_name =  $udata[0]['fullname'];
        
        $template =  systemTemplateDataBySlug(TEMPLATE_SCHEDULE_MEETING);
        
        $search  = array('{MEETING_TITLE}', '{MEETING_DATE}', '{MEETING_TIME}','{MEETING_DESCRIPTION}','{MEETING_LOCATION}','{FROM_NAME}');
        $replace = array( ucfirst($meeting_data['meet_title']), $meeting_data['meeting_date'],$meeting_data['meeting_time'],$meeting_data['meeting_description'],$meeting_data['meeting_location'],$from_name);
        $body1 = str_replace($search,$replace, $template[0]['body']);

        $subject = "BLAZEDESK :: REMINDER FOR SCHEDULE MEETING " . ucwords($template[0]['subject']);
        $headers = array('MIME-Version'=>'1.0\r\n','Disposition-Notification-To'=>$from_email);

        
        $sent = send_mail1($email_send_to,$subject,$body1, '',$from_email,$from_name, '','',$headers,'');
        if($sent)
        {
            echo  "mail sent";
        }else
        {
            echo "mail not sent";
            
        }
    }
    
    function getMultipleLoginUserEmail($user_id)
    {
        $temp_arr_str = implode(",",$user_id);
        $table1 = LOGIN . ' as l';
        $match1 = "l.login_id IN (".$temp_arr_str.")";
        $fields1 = array("l.email");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        
        $str_email = '';
        foreach ($user_data as $user_email)
        {
            $str_email.= $user_email['email'].",";
        }
        $tmp_str_email = rtrim($str_email,",");
        return $tmp_str_email;
    }
    
    function getContactEmailbyId($arr_contact_id)
    {
        $temp_arr_str = implode(",",$arr_contact_id);
        
       
        $table1 = CONTACT_MASTER . ' as l';
        $match1 = "l.contact_id IN (".rtrim($temp_arr_str,',').")";
        $fields1 = array("l.email");
        $user_data = $this->common_model->get_records($table1, $fields1, '', '', $match1);
        
        $str_email = '';
        foreach ($user_data as $user_email)
        {
            $str_email.= $user_email['email'].",";
        }
        $tmp_str_email = rtrim($str_email,",");
        return $tmp_str_email;
    }
    
     /*
      @Author : Ishani Dave
      @Date   : 09/05/2016
     */

    
     public function support_to_do() {
        
        $now   = date('Y-m-d'); // current date
        $table = SUPPORT_TASK_MASTER .' as pt';
        //$match = "pt.is_delete = 0 and pt.remember=1 and ((pt.start_date <= '" . $now."' and pt.end_date >= '" . $now."') or (pt.start_date < '" . $now."' and pt.end_date >= '" . $now."'))";
        /*Get tasks */
        $match = "pt.is_delete = 0 and pt.remember=1 and pt.end_date >= '" . $now."'";
        $field = array('pt.task_id,pt.created_date,pt.remember,pt.reminder_date,pt.reminder_time,pt.task_description,pt.importance,pt.task_name,pt.status,pt.start_date,pt.end_date,l.email,pt.created_by');
        $join_table = array(
        LOGIN . ' as l' => 'pt.created_by=l.login_id');
        $task_data = $this->common_model->get_records($table, $field, $join_table, 'left', $match, '', '', '', '', '','','', '', '', '');
   
       if(!empty($task_data))
       {
            foreach ($task_data as $row) {
                /*Daily*/
               //$this->support_notify_user($row['created_by'], $row);
                $current_time    = date('H:i').':00';
                $current_date    = date('Y-m-d');
                
                $current_date_time = $current_date." ".$current_time;
                
                $remind_date = $row['reminder_date'] ;
                $remind_time = $row['reminder_time'] ;
                
                $remind_date_time = $remind_date." ".$remind_time;
                
                if($current_date_time == $remind_date_time)
                {
                  $this->support_notify_user($row['created_by'], $row);
                }
                             
            }
       }
       echo 'Done';
    }
   
    function support_notify_user($user_id, $taskdata) {
       
        // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
       $ematch = "et.template_id = 59";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
       //$template = systemTemplateDataBySlug(TEMPLATE_TO_DO_REMINDER);
        $umatch = "login_id =" . $user_id;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        $email = !empty($udata[0]['email']) ? $udata[0]['email'] : '';
        $fullname = !empty($udata[0]['fullname']) ? ucfirst($udata[0]['fullname']) : '';
        $taskName = $taskdata['task_name'];
        $start_date = ($taskdata['start_date'] != '0000-00-00')?date('m/d/Y',strtotime($taskdata['start_date'])):'';
        $due_date = ($taskdata['end_date'] != '0000-00-00')?date('m/d/Y',strtotime($taskdata['end_date'])):'';
        $taskDescription = $taskdata['task_description'];
        $find = array(
            '{USER}',
            '{TASK_NAME}',
            '{IMPORTANCE}',
            '{START_DATE}',
            '{END_DATE}',
 
        );

        $replace = array(
            'USER' => $fullname,
            'TASK_NAME' => $taskName,
            'IMPORTANCE' => !empty($taskdata['importance'])?$taskdata['importance']:'',
            'START_DATE' => $start_date,
            'END_DATE' => $due_date,
           
        );
        $format = $template[0]['body'];
        $body = str_replace(array("\r\n",
            "\r",
            "\n"), '<br />', preg_replace(array("/\s\s+/",
            "/\r\r+/",
            "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
        $subject = "BLAZEDESK :: " . $template[0]['subject'];
        
         $sent = send_mail($email, $subject, $body);
        if($sent)
        {
            echo  "mail sent";
        }else
        {
            echo "mail not sent";
            
        }
    }
    
    
     /*
      @Author : Ishani Dave
      @Date   : 09/05/2016
     */

    
     public function support_team() {
        
        $now   = date('Y-m-d'); // current date
        $table = SUPPORT_TEAM_MEMBER .' as stm';
        /*Get team members reminder details */
        $match = "stm.is_delete = 0 and stm.reminder = 1 and stm.schedule_meeting >= '" . $now."'";
        $field = array('stm.team_member_id,stm.created_date,stm.remind_time,stm.reminder,stm.before_after,stm.repeat,stm.remind_day,stm.status,stm.schedule_meeting,l.email,stm.created_by,l.login_id');
        $join_table = array(
        LOGIN . ' as l' => 'stm.created_by=l.login_id');
        $team_data = $this->common_model->get_records($table, $field, $join_table, 'left', $match, '', '', '', '', '','','', '', '', '');
       
       if(!empty($team_data))
       {
            foreach ($team_data as $row) {
                /*Daily*/
                $this->support_notify_teammember($row['created_by'], $row);
//               $this->support_notify_user($row['created_by'], $row);
                if($row['repeat'] == 'daily')
                {
                    
                        $current_time      = date('H:i').':00';
                        //$current_time      = '17:30:00';
                        $remind_before_min = $row['remind_day'];
                        $stime             = $row['remind_time'];
                        
                        if(empty($row['before_after']))
                        { 
                            $remainder_time   = date("H:i:s", strtotime( "$stime - $remind_before_min mins"));
                        }
                        else
                        {
                            $remainder_time   = date("H:i:s", strtotime( "$stime + $remind_before_min mins"));
                        }
                       
                         
                        //echo $current_time.' == '.$remainder_time;
                        //echo '<br>';
                        if($current_time == $remainder_time)
                        {
                            $this->support_notify_teammember($row['created_by'], $row);
                        }
                }
                /*count diffrence*/
              
                $start = strtotime($row['created_date']);
                $end = strtotime($now);

                $days_between = ceil(abs($end - $start) / 86400);
                /*weekly*/
                if($row['repeat'] == 'weekly')
                {
                    if($days_between%7 == 0)
                    {
                        $current_time      = date('H:i').':00';
                        //$current_time      = '23:30:00';
                        $remind_before_min = $row['remind_day'];
                        $stime             = $row['remind_time'];
                        if(empty($row['before_after']))
                        { 
                            $remainder_time   = date("H:i:s", strtotime( "$stime - $remind_before_min mins"));
                        }
                        else
                        {
                            $remainder_time   = date("H:i:s", strtotime( "$stime + $remind_before_min mins"));
                        }
                        
//                         $this->support_notify_user($row['created_by'], $row);
                        //echo $current_time.' == '.$remainder_time;
                        //echo '<br>';
                        if($current_time == $remainder_time)
                        {
                            $this->support_notify_teammember($row['created_by'], $row);
                        }
                    }
                }
                /*monthly*/
                if($row['repeat'] == 'monthly')
                {
                    if($days_between%30 == 0)
                    {
                        $current_time      = date('H:i').':00';
                        //$current_time      = '17:30:00';
                        $remind_before_min = $row['remind_day'];
                        $stime             = $row['remind_time'];
                        if(empty($row['before_after']))
                        { 
                            $remainder_time   = date("H:i:s", strtotime( "$stime - $remind_before_min mins"));
                        }
                        else
                        {
                            $remainder_time   = date("H:i:s", strtotime( "$stime + $remind_before_min mins"));
                        }

                        if($current_time == $remainder_time)
                        {
                            $this->support_notify_teammember($row['created_by'], $row);
                        }
                    }
                }
                /*yearly*/
                if($row['repeat'] == 'yearly')
                {
                    if($days_between%365 == 0)
                    {
                        $current_time      = date('H:i').':00';
                        //$current_time      = '23:30:00';
                        $remind_before_min = $row['remind_day'];
                        $stime             = $row['remind_time'];
                        if(empty($row['before_after']))
                        { 
                            $remainder_time   = date("H:i:s", strtotime( "$stime - $remind_before_min mins"));
                        }
                        else
                        {
                            $remainder_time   = date("H:i:s", strtotime( "$stime + $remind_before_min mins"));
                        }
//                        $this->support_notify_teammember($row['created_by'], $row);
                        //echo $current_time.' == '.$remainder_time;
                        //echo '<br>';
                        if($current_time == $remainder_time)
                        {
                            $this->support_notify_teammember($row['created_by'], $row);
                        }
                    }
                }
            }
       }
       echo 'Done';
    }
   
    function support_notify_teammember($user_id, $team_data) {
       
        // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
        //$ematch = "et.subject ='Update task'";
        $ematch = "et.template_id = 56";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
        $umatch = "login_id =" . $user_id;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        $email = !empty($udata[0]['email']) ? $udata[0]['email'] : '';
        $fullname = !empty($udata[0]['fullname']) ? ucfirst($udata[0]['fullname']) : '';
        $schedule_date = $team_data['schedule_meeting'];
       
        $find = array(
            '{USER}',
            '{DATE}',
           
        );

        $replace = array(
            'USER' => $fullname,
            'DATE' => $schedule_date,
           
        );
        $format = $template[0]['body'];
        $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
        $subject = "BLAZEDESK :: " . $template[0]['subject'];
        
         $sent = send_mail($email, $subject, $body);
        if($sent)
        {
            echo  "mail sent";
        }else
        {
            echo "mail not sent";
            
        }
    }

    /*
      @Author : Sanket Jayani
      @Desc   : Send Event Reminder mail Notification.
      @Input  : 
      @Output :
      @Date   : 09/05/2016
     */
    
    public function remind_event() 
    {
      
        $now   = date('Y-m-d'); // current date
        $table = TBL_EVENTS .' as mm';
       
        $match = "mm.is_delete = 0 and mm.event_remember= 1 and mm.event_date >= '" . $now."'";
        $field = array('mm.*');
        //$join_table = array(LOGIN . ' as l' => 'mm.meet_user_id=l.login_id');
        $data_schedule_meeting = $this->common_model->get_records($table, $field, '', 'left', $match, '', '', '', '', '','','', '', '', '');
        
       if(!empty($data_schedule_meeting))
       {
            foreach ($data_schedule_meeting as $row) {
               
                $row['created_by'] = $row['event_related_id'];
                
                $current_time      = date('H:i').':00';
                $current_date      = date('Y-m-d');
                
                $today_date_time = $current_date." ".$current_time;
                
                $remind_date = $row['reminder_date'] ;
                $remind_time = $row['reminder_time'] ;
                
                $remind_date_time = $remind_date." ".$remind_time;
                
                if($today_date_time == $remind_date_time)
                {
                    $this->event_notify_user($row['created_by'], $row);
                }
                
            }
       }
       echo 'Done';
    }

    
    function event_notify_user($user_id, $meeting_data) 
    {
       
        $umatch = "contact_id =" . $user_id;
        $ufields = array("contact_name,email");
        $udata = $this->common_model->get_records(CONTACT_MASTER, $ufields, '', '', $umatch);
        $email_send_to = $udata[0]['email'];
        
        $where = "config_key='email'";
        $fromEmail =$this->common_model->get_records(CONFIG, array('value'), '', '', $where);
        $from_email = $fromEmail[0]['value'];
        
        $where1 = "config_key='project_name'";
        $projectName = $this->common_model->get_records(CONFIG, array('value'), '', '', $where1);
        $from_name = $projectName[0]['value'];
       
        $template =  systemTemplateDataBySlug(TEMPLATE_EVENT_REMINDER);

        $search  = array('{EVENT_TITLE}', '{EVENT_DATE}', '{EVENT_PLACE}','{EVENT_NOTE}');
        $replace = array( ucfirst($meeting_data['event_title']), $meeting_data['event_date'],$meeting_data['event_place'],$meeting_data['event_note']);
        $body1 = str_replace($search,$replace, $template[0]['body']);

        $subject = "BLAZEDESK :: Event Reminder " . ucwords($template[0]['subject']);
        $headers = array('MIME-Version'=>'1.0\r\n','Disposition-Notification-To'=>$from_email);
        
        $sent = send_mail1($email_send_to,$subject,$body1, '',$from_email,$from_name, '','',$headers,'');
        if($sent)
        {
            echo  "mail sent";
        }else
        {
            echo "mail not sent";
            
        }
    }
    
	
 /*
    @Author : Brijesh Tiwari
    @Desc   : This function is for getting twitter follower count per month
    @Input  : 
    @Output : 
    @Date   : 13/05/2016
    */

 function checkTwitterAccountExists($username)
    {
        $headers = get_headers("https://twitter.com/".$username);
        
        
        if(strpos($headers[0], '200') == true ) 
        {
            return true;
        } else 
        {
            return false;
        }
    }

    function getTwitterCountPerMonth()
    {
        if (!$this->db->table_exists(TWITTER_MONTHLY_COUNT) )
            {
               $sql="CREATE TABLE IF NOT EXISTS blzdsk_twitter_monthly_count ( id int(11) NOT NULL AUTO_INCREMENT,  followers_count int(11) NOT NULL,  created_date datetime NOT NULL,  PRIMARY KEY (id))";
                $this->db->query($sql);
         }
            
        $twitter_user_name = $this->common_model->getSettingsData('twitter_username');
       
        if($twitter_user_name != '' && $this->checkTwitterAccountExists($twitter_user_name))
        {
          
            $tw_followers = 0;
            //$tw_username = 'c_metric'; 
            if(preg_match('/^[A-Za-z0-9_]{1,15}$/', $twitter_user_name))
            {
                $data = file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names='.$twitter_user_name); 
                $parsed =  json_decode($data,true);

                if($parsed[0]['followers_count'] != '')
                {
                    $tw_followers =  $parsed[0]['followers_count'];
                }else
                {
                    $tw_followers = '0';
                }
                
            }
            

            $table=TWITTER_MONTHLY_COUNT;
            $field['followers_count']=$tw_followers;
            $field['created_date']=datetimeformat();
            $task_data = $this->common_model->insert($table, $field);

            echo $task_data;
        }else
        {
            echo '0';
        }
      
    }
	/*
	 @Author : Mehul Patel
	 @Desc   : This function is for getting Current Current Amount with respect to Currency code
	 @Input  : insert the converted amount into Country table
	 @Output :
	 @Date   : 31/05/2016
	 */
	function getCurrentCurrency(){
		$from_currency    = 'INR';
		$amount            = 1;
		$convertedAmount ="";
		$table = COUNTRIES . ' as c';
		$match = "c.currency_code IS NOT NULL";
		$fields = array("c.currency_code");
		$currency_code = $this->common_model->get_records($table,$fields,'','',$match);
		foreach ($currency_code as $currencyCode){

			if($currencyCode['currency_code'] != 'INR'){
				$convertedAmount = $this->convertCurrencyAmount($amount, $from_currency,$currencyCode['currency_code']);
			}else{
				$convertedAmount=1;
			}
			
			if(!empty($convertedAmount)){
			//	$convertedAmount = round($convertedAmount);
				$data = array('currency_amount'=>$convertedAmount);
				$where = array('currency_code' => $currencyCode['currency_code']);
					
				$this->common_model->update(COUNTRIES, $data, $where);
				
			}

		}
		echo "Done.!!";
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : getConvertCurrencyAmount This is for testing only don't use for functionality 
	 @Input  :$amount , $from , $to
	 @Output :
	 @Date   : 31/05/2016
	 */
	function getConvertCurrencyAmount($amount, $from, $to){
		//	$from = "USD";
		//	$from_inr = 'INR';
		//	$amount = 100;
		//	$to = 'EUR';
		$getConvertedFromAmount = "";
		$getConvertedToAmount = "";
		// Convert into Indian currency
		$table = COUNTRIES . ' as c';
		$match = "c.currency_code='".$from."'";
		$fields = array("c.currency_amount");
		$getFromAmount = $this->common_model->get_records($table,$fields,'','',$match);

		if(!empty($getFromAmount[0]['currency_amount'])){		
			$getConvertedFromAmount= $amount / $getFromAmount[0]['currency_amount'];
		}
		
		// Convert into requested currency
		$table1 = COUNTRIES . ' as c';
		$match1 = "c.currency_code='".$to."'";
		$fields1 = array("c.currency_amount");
		$getToAmount = $this->common_model->get_records($table1,$fields1,'','',$match1);

		if(!empty($getToAmount[0]['currency_amount'])){

			$getConvertedToAmount= $getConvertedFromAmount * $getToAmount[0]['currency_amount'];
		}
		// echo "Converted Amount :".$getConvertedToAmount; exit;
		return $getConvertedToAmount;
	}
	/*
	 @Author : Mehul Patel
	 @Desc   : This function is for getting Current Current Amount with respect to Currency code
	 @Input  :$amount , $from , $to
	 @Output :
	 @Date   : 31/05/2016
	 */
	function convertCurrencyAmount($amount, $from, $to)
	{
		$url = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
		$data = file_get_contents($url);
		//print_r($data);exit;
		preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
		if(count($converted)>0){
			$converted = preg_replace("/[^0-9.]/", "", $converted[1]);
		}
		return $converted;
		//return round($converted, 3);
	}
	


}
