<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SupportTeam extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = $this->uri->segment(0);
        $this->viewname = $this->uri->segment(1);
       // check_project();
        $this->load->model('TeamMembers_model');
        $this->project_id = '';//$this->session->userdata('PROJECT_ID');
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : listing of Team with their members
      @Input 	:
      @Output	:
      @Date   : 08/03/2016
     */

    public function index() {

	    $this->breadcrumbs->push(lang('support'),'/Support');
		$this->breadcrumbs->push(lang('sup_team'),' ');
        $data['header'] = array('menu_module' => 'support');
        $data['main_content'] = $this->viewname;
        $data['project_view'] = $this->viewname;
        $data['js_content'] = '/loadJsFiles';
        $data['teams'] = $this->TeamMembers_model->getTeamList($this->project_id);
        //$data['project_id'] = $this->project_id;
        $teamData = array();
        $teamLeadData = array();
        if (count($data['teams']) > 0) {
            foreach ($data['teams'] as $team) {
                $teamData[$team['team_name']] = $this->TeamMembers_model->getTeamMemberList($team['team_id']);
                $teamLeadData[$team['team_name']] = $this->TeamMembers_model->getTeamLeaderbyId($team['team_id']);
            }
        }
        $data['team_data'] = $teamData;
        $data['team_lead_data'] = $teamLeadData;

        if ($this->input->is_ajax_request()) {
            $this->load->view(get_class($this) . '/' . get_class($this), $data);
        } else {
            $this->parser->parse('layouts/SupportTemplate', $data);
        }
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : add Team view
      @Input 	:
      @Output	:
      @Date   : 25/02/2016
     */

    public function addTeam() {
	
        $where = array('L.status' => 1, 'RM.role_name' => 'Team Leader','L.is_delete'=>0,"l.is_support_user=1");
        $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
        $params['join_type'] = 'left';
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
        $data['team_leader'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], $where, '');
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
        $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], 'L.login_id NOT IN(select member_id from blzdsk_support_team_members where is_delete=0 and team_id !=0) AND L.is_delete=0 AND L.user_type!=45 AND RM.role_name!="Team Leader" AND L.is_support_user=1 ','');
		
        $data['team_list'] = $this->common_model->get_records(SUPPORT_TEAM, '', '', '', 'is_delete=0', '');
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $this->load->view('Add', $data);
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : Insertion of New Team Data
      @Input 	:
      @Output	:
      @Date   : 29/01/2016
     */

    public function SaveTeamData() {
		/*pr($_POST);
		die('here');*/
        $id = 0;
		
        if ($this->input->post('team_id')) {
            $id = $this->input->post('team_id');
        }
        $data['main_content'] = '/' . $this->viewname . '/TeamMembers';
        $data['project_view'] = $this->module . '/' . $this->viewname;
		if (!$this->input->post('team_members')) {
			
						$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('select_team') . "</div>");
						redirect($data['project_view']);
		}
        /*
         * post variables
         */


        $team_data['team_name'] = $this->input->post('team_name');
        $team_data['team_lead_id'] = $this->input->post('team_lead_id');
        $team_data['notify_members'] = $this->input->post('notify_members');
        //$team_data['project_id'] = $this->project_id;
        $team_data['schedule_meeting'] = date('Y-m-d h:i:s', strtotime($this->input->post('schedule_meeting')));
        $team_data['status'] = 1;
        $membersDataTrans = array();
// if ($id > 0) { //update
// } else {
        $team_data['created_date'] = datetimeformat();
		
        $id = $this->common_model->insert(SUPPORT_TEAM, $team_data);
		
        if ($id > 0) {
			
            $membersId = $this->input->post('team_members');
            $membersReplicaId = $oldIds = $membersData = $tmp = array();
            
        
        $fields = array("member_id");
        $unsortedMemberList = $this->common_model->get_records(SUPPORT_TEAM_MEMBER, $fields);
       
               if (count($unsortedMemberList) > 0) {
                    foreach ($unsortedMemberList as $mlist) {
                        if (in_array($mlist['member_id'], $membersId)) {
                            $this->common_model->update(SUPPORT_TEAM_MEMBER, array('is_delete' => 1), "member_id=" . $mlist['member_id']);
                           
                        }
                    }
                }
            //echo $this->db->last_query(); exit;
            if (count($membersId) > 0) {
				
				
				
                foreach ($membersId as $ids) {
                    $membersData[] = array('team_id' => $id, 'member_id' => $ids, 'created_date' => datetimeformat(), 'created_by' => $this->session->userdata('LOGGED_IN')['ID'],'status'=>1,'schedule_meeting'=> $team_data['schedule_meeting']
                    ,'notify_member'=>$this->input->post('notify_members'),'before_after'=>$this->input->post('before_after'),'remind_time'=>$this->input->post('remind_time'),'repeat'=>$this->input->post('repeat')
                    ,'remind_day'=>$this->input->post('remind_day'),'reminder'=>($this->input->post('reminder')=='on')?1:0);
                    if ($this->input->post('notify_members') != null):
                        //$this->notifyTeamMember($ids);
                    endif;
                    if ($team_data['schedule_meeting'] != null):
                        $this->scheduleMeeting($ids, $team_data['schedule_meeting']);

                    endif;
                }

                $msg = $this->lang->line('team_success_msg');
				
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                $this->common_model->insert_batch(SUPPORT_TEAM_MEMBER, $membersData);
                if (count($membersReplicaId) > 0) {
                    //$where = array("project_id=" . $this->project_id . "");
                    // $this->common_model->delete(PROJECT_ASSIGN_MASTER, $where);
                    //   $this->common_model->insert_batch(PROJECT_ASSIGN_MASTER, $membersDataTrans);
                }
            } else {
                $msg = $this->lang->line('team_member_empty_error');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        } else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
        }

        $redirect_link = $_SERVER['HTTP_REFERER'];
        redirect($redirect_link);  //Redirect On Listing page //Redirect On Listing page
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : ajax request for the getting team list wise data
      @Input 	:
      @Output	:
      @Date   : 25/02/2016
     */

    function getTeamListbyId($id = null) {
		
        $data['leadId'] = '';
        $memberArr = array();
		$flag = $this->input->get('flag');
        if ($id > 0) {
            $data['id'] = $id;
            $data['teamData'] = $this->TeamMembers_model->getTeamMemberList($id);
			
            $where = array('L.status' => 1, 'RM.role_name' => 'Team Leader','L.is_delete' => 0,"l.is_support_user=1");
			            $data['team_details'] = $this->common_model->get_records(SUPPORT_TEAM, '', '', '', 'team_id=' . $id, '');
						/*pr($data['team_details']);
						die();*/

        }
        $data['teamLeader'] = $this->TeamMembers_model->getTeamLeaderbyId( $id);
       			//echo $this->db->last_query();

	   if (count($data['teamLeader']) > 0):
            $data['leadId'] = $data['teamLeader'][0]['member_id'];
        endif;
        if (count($data['teamData']) > 0):
            foreach ($data['teamData'] as $members):
                $memberArr[] = $members['member_id'];
            endforeach;
        endif;

        $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
        $params['join_type'] = 'left';
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo,');
        $data['team_leader'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], $where, '');
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
// $teamMemerWhere = 'L.login_id  not in (' . implode($memberArr) . ')';
        $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], 'L.is_delete=0 AND L.is_support_user=1', '');
        $data['team_list'] = $this->common_model->get_records(SUPPORT_TEAM, '', '', '', 'is_delete=0', '');
        $data['project_view'] = $this->module . '/' . $this->viewname;
		if ($flag == 1) {
            $data['editTeam'] = 1;
        }
        $this->load->view($this->viewname . '/Add', $data);
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : This will load view of the add team members
      @Input 	:
      @Output	:
      @Date   :25/02/2016
     */

    function addTeamMembers() {
		
        $data['id'] = '';
        $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
        $params['join_type'] = 'left';
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
// $teamMemerWhere = 'L.login_id  not in (' . implode($memberArr) . ')'; 'login_id not in(select member_id from blzdsk_'.PROJECT_TEAM_MEMBERS.' where project_id="'.$this->project_id.'")'
        $data['team_list'] = $this->common_model->get_records(SUPPORT_TEAM, '', '', '', 'is_delete=0', '');
        $data['project_view'] = $this->module . '/' . $this->viewname;
        if ($this->input->post('id')) {
            $data['leadId'] = '';
            $memberArr = array();
            $data['id'] = $id = $this->input->post('id');
//            if ($id > 0) {
//                $data['id'] = $id;
//                $data['teamData'] = $this->TeamMembers_model->getTeamMemberList($this->project_id, $id);
//            }
//            if (count($data['teamData']) > 0):
//                foreach ($data['teamData'] as $members):
//                    $memberArr[] = $members['member_id'];
//                endforeach;
//            endif;
            $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], 'login_id not in(select member_id from blzdsk_' . SUPPORT_TEAM . ' where team_id=' . $id . ' AND L.is_support_user=1)');
        } else {
            $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], 'L.is_delete=0 AND L.is_support_user=1', '');
        }
        $this->load->view($this->viewname . '/AddTeamMember', $data);
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : Function is used to add team members to the existing team
      @Input 	:
      @Output	:
      @Date   :25/02/2016
     */

    function SaveTeamMemberData() {
		
        $data['main_content'] = '/' . $this->viewname . '/TeamMembers';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $id = 0;
        if (!$this->input->post('team_id')) {
            //$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            //redirect($data['project_view']);
            $id = 0;
        } else {
            $id = $this->input->post('team_id');
        }
		

        /*
         * post variables
         */
        $team_data['team_id'] = $this->input->post('team_id');
        $team_data['notify_members'] = $this->input->post('notify_members');
        $team_data['notify_tl'] = $this->input->post('notify_tl');
        //$team_data['project_id'] = $this->project_id;
        $team_data['schedule_meeting'] = date('Y-m-d h:i:s', strtotime($this->input->post('schedule_meeting')));
        $team_data['status'] = 1;
        //  if ($id > 0) {
        $membersId = array_unique($this->input->post('team_members'));
        $membersData = array();
        if (count($membersId) > 0) {
//                $where = array("team_id=" . $id . " and project_id=" . $this->project_id . "");
//                $this->common_model->delete(PROJECT_TEAM_MEMBERS, $where);


            foreach ($membersId as $ids) {
				
	
                $membersData[] = array('team_id' => $id, 'member_id' => $ids, 'created_date' => datetimeformat(), 'created_by' => $this->session->userdata('LOGGED_IN')['ID'],'status'=>1,'schedule_meeting'=> $team_data['schedule_meeting'],'notify_member'=>$this->input->post('notify_members'),'before_after'=>$this->input->post('before_after'),'remind_time'=>$this->input->post('remind_time'),'repeat'=>$this->input->post('repeat')
                    ,'remind_day'=>$this->input->post('remind_day'),'reminder'=>($this->input->post('reminder')=='on')?1:0);
				
                if ($this->input->post('notify_members') != null):
                   // $this->notifyTeamMember($ids);

                    if ($team_data['schedule_meeting'] != null):
                        $this->scheduleMeeting($ids, $team_data['schedule_meeting']);

                    endif;
                endif;
                if ($team_data['notify_tl'] != null):
                    $pmatch = "team_id =" . $this->input->post('team_id');
                    $pfields = array("team_lead_id");
                    $pdata = $this->common_model->get_records(SUPPORT_TEAM, $pfields, '', '', $pmatch);
                    $this->scheduleMeeting($pdata[0]['team_lead_id'], $team_data['schedule_meeting']);
                endif;
            }
			/*pr($membersData);
			die('aa');*/
            $this->common_model->insert_batch(SUPPORT_TEAM_MEMBER, $membersData);
            $msg = $this->lang->line('member_success_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
			$msg = $this->lang->line('team_success_msg');
				//die($msg);
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            
        } else {
            $msg = $this->lang->line('team_member_empty_error');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        //  } else {
        //      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
        //  }

        redirect($data['project_view']); //Redirect On Listing page
    }
	function removeTeam() {
	
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        } else {
            $teamId = $this->input->post('teamId');
			
            $where = "team_id=" . $teamId;
            //$this->common_model->delete(PROJECT_TEAM_MASTER, $where);
            //$this->common_model->delete(PROJECT_TEAM_MEMBERS, $where);
            $this->common_model->update(SUPPORT_TEAM, array('is_delete' => 1), $where);
			
            $this->common_model->update(SUPPORT_TEAM_MEMBER, array('is_delete' => 1), $where);
			
            json_encode(array('status' => 1));
            die;
        }
    }

    function removeTeamMembers() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        } else {
            $memberId = $this->input->post('memberId');
            $teamId = $this->input->post('teamId');
            $where = "member_id=" . $memberId . " and team_id=" . $teamId;
            $this->common_model->delete(SUPPORT_TEAM_MEMBER, $where);
            //echo $this->db->last_query();
            json_encode(array('status' => 1));
            die;
        }
    }

    function editSupportTeam() {
		/*echo "<pre>";
		print_r($_POST);die('here');*/
        $data['main_content'] = '/' . $this->viewname . '/TeamMembers';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        if ($this->input->post()) {
            $id = 0;
            if ($this->input->post('team_id')) {
                $id = $this->input->post('team_id');
            }
            $data['main_content'] = '/' . $this->viewname . '/TeamMembers';
            $data['project_view'] = $this->module . '/' . $this->viewname;
            /*
             * post variables
             */
            $team_data['team_name'] = $this->input->post('team_name');
            $team_data['team_lead_id'] = $this->input->post('team_lead_id');
            $team_data['notify_members'] = $this->input->post('notify_members');
           
            $team_data['schedule_meeting'] = date('Y-m-d h:i:s', strtotime($this->input->post('schedule_meeting')));
            $team_data['status'] = 1;
            $membersDataTrans = array();
// if ($id > 0) { //update
// } else {
            $team_data['created_date'] = datetimeformat();
            $this->common_model->update(SUPPORT_TEAM, $team_data, 'team_id=' . $id);
            if ($id > 0) {
				
                $membersId = $this->input->post('team_members');

                $membersReplicaId = $oldIds = $membersData = $tmp = array();
                if (count($membersId) > 0) {

                    // SELECT * FROM `blzdsk_project_assign_trans` WHERE `project_id` = "2" and `user_id` not in ("20,21,11,16")
                    $getOldAsignMembers = $this->common_model->get_records(SUPPORT_TEAM_MEMBER, '', '', '', 'team_id='.$id, '');
                    $oldIds = array();
                    if (count($getOldAsignMembers) > 0) {
                        foreach ($getOldAsignMembers as $obj) {
                            $oldIds[] = $obj['user_id'];
                        }
                    }
                    $membersReplicaId = array_diff($membersId, $oldIds);

                    foreach ($membersId as $ids) {
                        $membersData[] = array('team_id' => $id, 'member_id' => $ids, 'created_date' => datetimeformat(), 'created_by' => $this->session->userdata('LOGGED_IN')['ID'], 'before_after' => $this->input->post('before_after'), 'remind_time' => $this->input->post('remind_time'), 'repeat' => $this->input->post('repeat'), 'remind_day' => $this->input->post('remind_day'), 'reminder' => $this->input->post('reminder'),'schedule_meeting'=> $team_data['schedule_meeting'],'notify_member'=>$this->input->post('notify_members'),'before_after'=>$this->input->post('before_after'),'remind_time'=>$this->input->post('remind_time'),'repeat'=>$this->input->post('repeat')
                    ,'remind_day'=>$this->input->post('remind_day'),'reminder'=>($this->input->post('reminder')=='on')?1:0);
                        if ($this->input->post('notify_members') != null):
                            //$this->notifyTeamMember($ids);
                            if ($team_data['schedule_meeting'] != null):
                                $this->scheduleMeeting($ids, $team_data['schedule_meeting']);
                            endif;
                        endif;
                    }

                    if (count($membersReplicaId) > 0) {
                        array_push($membersReplicaId, $this->input->post('team_lead_id'));
                        foreach ($membersReplicaId as $ids) {
                            $membersDataTrans[] = array('created_date' => datetimeformat(), 'user_id' => $ids);
                        }
                    }


                    $msg = $this->lang->line('team_success_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    $where = "team_id=" . $id . "";
                    $this->common_model->delete(SUPPORT_TEAM_MEMBER, $where);


                    $this->common_model->insert_batch(SUPPORT_TEAM_MEMBER, $membersData);
                    if (count($membersReplicaId) > 0) {
//                        $where = array("project_id=" . $this->project_id . "");
//                        $this->common_model->delete(PROJECT_ASSIGN_MASTER, $where);
                        //   $this->common_model->insert_batch(PROJECT_ASSIGN_MASTER, $membersDataTrans);
                    }
                } else {
                    $msg = $this->lang->line('team_member_empty_error');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                }
            } else {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            }
            redirect($data['project_view']); //Redirect On Listing page
        } else {
            $where = array('L.status' => 1, 'RM.role_name' => 'Team Leader','L.is_delete' => 0,"l.is_support_user=1");
            $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
            $params['join_type'] = 'left';
            $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
            $data['team_leader'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], $where, '');
            $data['project_view'] = $this->module . '/' . $this->viewname;
            $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
            $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'],'L.is_delete=0 AND L.user_type!=45 AND L.is_support_user=1', '');
            $data['team_list'] = $this->TeamMembers_model->getTeamList();
            $data['project_view'] = $this->module . '/' . $this->viewname;
            $data['editTeam'] = 1;
            $this->load->view($this->viewname . '/Add', $data);
        }
    }

    private function notifyTeamMember($userId) {
        // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
        $ematch = "et.template_id = 70";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
        $umatch = "login_id =" . $userId;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        //get Current Project Name
        $pmatch = "";
        $pfields = array("ticket_subject");
        $pdata = $this->common_model->get_records(TICKET_MASTER, $pfields, '', '', $pmatch);
        $email = $udata[0]['email'];
        $fullname = $udata[0]['fullname'];
        $ticketname = $pdata[0]['ticket_subject'];
        $find = array(
            '{USER}',
            '{TICKET}',
                //    '{DATE}'
        );

        $replace = array(
            'USER' => $fullname,
            'TICKET' => $ticketname,
                //    'DATE' => $order_info['payment_company']
        );
        $format = $template[0]['body'];
        $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
        $subject = "BLAZEDESK :: " . $template[0]['subject'];
        send_mail($email, $subject, $body);
    }

    private function scheduleMeeting($userId, $date) {
        // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
        $ematch = "et.subject ='Support Meeting Scheduled' ";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
        //user details from id
        $umatch = "login_id =" . $userId;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        //get Current Project Name
        
        $email = $udata[0]['email'];
        $fullname = $udata[0]['fullname'];
        
        $find = array(
            '{USER}',
            
            '{DATE}'
        );

        $replace = array(
            'USER' => $fullname,
            
            'DATE' => $date
        );
        $format = $template[0]['body'];
        $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
        $subject = "BLAZEDESK :: " . $template[0]['subject'];
        send_mail($email, $subject, $body);
    }
	 function teamNameValidate($id = 0) {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct script allowed");
        }
        $name = $this->input->post('team_name');
        $validateTeamUnique = $this->TeamMembers_model->validateTeamUnique($name, $id);

        if (count($validateTeamUnique) > 0) {
            echo json_encode(array('status' => 1));
            //  header("HTTP/1.0 404 Not Found");
        } else {
//            http_response_code(200);
            echo json_encode(array('status' => 0));
            // header("HTTP/1.1 200 Ok");
        }
    }

}
