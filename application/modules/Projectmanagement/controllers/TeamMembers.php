<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TeamMembers extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = 'Projectmanagement';
        $this->viewname  = $this->router->fetch_class();
        check_project();
        $this->load->model('TeamMembers_model');
        $this->project_id = $this->session->userdata('PROJECT_ID');
    }

    /*
      @Author : Maulik Suthar
      @Desc   : listing of Team with their members
      @Input 	:
      @Output	:
      @Date   : 25/02/2016
     */

    public function index() {

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
//        $this->common_model->sampleSqlData();
//        die;
        $data['current_project_status'] = $this->session->has_userdata('PROJECT_STATUS') ? $this->session->userdata('PROJECT_STATUS') : '';
        $data['header'] = array('menu_module' => 'Projectmanagement');
        $data['main_content'] = '/' . $this->viewname . '/TeamMembers';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $data['js_content'] = '/' . $this->viewname . '/loadJsFiles';
        $data['teams'] = $this->TeamMembers_model->getTeamList($this->project_id);
        $data['project_id'] = $this->project_id;
        $teamData = array();
        $teamLeadData = array();
        if (count($data['teams']) > 0) {
            foreach ($data['teams'] as $team) {
                $teamData[$team['team_name']] = $this->TeamMembers_model->getTeamMemberList($this->project_id, $team['team_id']);
                $teamLeadData[$team['team_name']] = $this->TeamMembers_model->getTeamLeaderbyId($this->project_id, $team['team_id']);
            }
        }
        $data['team_data'] = $teamData;
        $data['team_lead_data'] = $teamLeadData;

        $data['project_manager'] = $this->TeamMembers_model->getProjectManager($this->project_id);

        if ($this->input->is_ajax_request()) {
            $this->load->view(get_class($this) . '/' . get_class($this), $data);
        } else {
            $this->parser->parse('layouts/ProjectmanagementTemplate', $data);
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : add Team view
      @Input 	:
      @Output	:
      @Date   : 25/02/2016
     */

    public function addTeam() {
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        $where = array('L.status' => 1, 'RM.role_name' => 'Team Leader', 'L.is_delete' => 0, 'L.status' => 1);
        $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
        $params['join_type'] = 'left';
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
        $data['team_leader'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], $where, '');
        $data['project_view'] = $this->module . '/' . $this->viewname;

        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
        $paramsj['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
        $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $paramsj['join_tables'], $params['join_type'], 'L.login_id NOT IN(select member_id from blzdsk_project_team_members where is_delete=0 AND project_id=' . $this->project_id . ' and team_id !=0) AND L.is_delete=0 AND RM.role_name!="Team Leader" AND RM.role_name!="Project Manager" and L.status=1 and is_pm_user=1', '');
        $data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER . ' as PTM', '', array(PROJECT_TEAM_MEMBERS . ' as TM' => 'TM.team_id=PTM.team_id'), 'inner', 'TM.is_delete=0 and PTM.is_delete=0', '', '', '', '', '', 'PTM.team_id');
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $this->load->view($this->viewname . '/Add', $data);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Insertion of New Team Data
      @Input 	:
      @Output	:
      @Date   : 29/01/2016
     */

    public function SaveTeamData() {
        $id = 0;


        if ($this->input->post('team_id')) {
            $id = $this->input->post('team_id');
        }
        $data['main_content'] = '/' . $this->viewname . '/TeamMembers';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        /*
         * validation for the team name uniqueness
         */

        $unsortedMemberIds = array();
        $validateTeamUnique = $this->TeamMembers_model->validateTeamUnique($this->input->post('team_name'), $id);
        if (count($validateTeamUnique) > 0) {
            $msg = $this->lang->line('team_name_error');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect($data['project_view']); //Redirect On Listing page
        }
        /*
         * post variables
         */
        $team_data['team_name'] = $this->input->post('team_name');
        $team_data['team_lead_id'] = $this->input->post('team_lead_id');
        $team_data['notify_members'] = $this->input->post('notify_members');
        $team_data['project_id'] = $this->project_id;
        $team_data['schedule_meeting'] = date('Y-m-d h:i:s', strtotime($this->input->post('schedule_meeting')));

        $team_data['status'] = 1;
        $membersDataTrans = array();
// if ($id > 0) { //update
// } else {
        $team_data['created_date'] = datetimeformat();
        $id = $this->common_model->insert(PROJECT_TEAM_MASTER, $team_data);
        if ($id > 0) {
            $membersId = $this->input->post('team_members');
            $membersReplicaId = $oldIds = $membersData = $tmp = array();
            if (count($membersId) > 0) {
                $where = "team_id=" . $id . " and project_id=" . $this->project_id . "";
                // $this->common_model->delete(PROJECT_TEAM_MEMBERS, $where);SELECT * FROM `blzdsk_project_assign_trans` WHERE `project_id` = "2" and `user_id` not in ("20,21,11,16")
                $getOldAsignMembers = $this->common_model->get_records(PROJECT_ASSIGN_MASTER, '', '', '', 'project_id="' . $this->project_id . '"', '');
                $oldIds = array();
                if (count($getOldAsignMembers) > 0) {
                    foreach ($getOldAsignMembers as $obj) {
                        $oldIds[] = $obj['user_id'];
                    }
                }

                $membersReplicaId = array_diff($membersId, $oldIds, array($this->input->post('team_lead_id')));
                array_push($membersId, $this->input->post('team_lead_id'));
                foreach ($membersId as $ids) {

                    $membersData[] = array('team_id' => $id, 'member_id' => $ids, 'project_id' => $this->project_id, 'created_date' => datetimeformat(), 'created_by' => $this->session->userdata('LOGGED_IN')['ID'], 'before_after' => $this->input->post('before_after'), 'remind_time' => $this->input->post('remind_time'), 'repeat' => $this->input->post('repeat'), 'remind_day' => $this->input->post('remind_day'), 'reminder' => $this->input->post('reminder'),'schedule_meeting'=> $team_data['schedule_meeting'],'notify_member'=>$this->input->post('notify_members'));
                    if ($this->input->post('notify_members') != null):
                        $this->notifyTeamMember($ids);
                        if ($team_data['schedule_meeting'] != null):
                            $this->scheduleMeeting($ids, $team_data['schedule_meeting']);
                        endif;
                    endif;
                }
                $unsortedMemberList = $this->TeamMembers_model->getallTeamMemberList($this->project_id);

                array_push($unsortedMemberList, $this->input->post('team_lead_id'));

                if (count($unsortedMemberList) > 0) {
                    foreach ($unsortedMemberList as $mlist) {
                        if (in_array($mlist['member_id'], $membersId)) {
                            $this->common_model->update(PROJECT_TEAM_MEMBERS, array('is_delete' => 1), "project_id=" . $this->project_id . "  and member_id=" . $mlist['member_id']);
                            $this->common_model->delete(PROJECT_PM_ASSIGN, 'project_id=' . $this->project_id . ' and user_id=' . $mlist['member_id']);
                        }
                    }
                }

                if (count($membersReplicaId) > 0) {
                    foreach ($membersReplicaId as $ids) {
                        $membersDataTrans[] = array('project_id' => $this->project_id, 'created_date' => datetimeformat(), 'user_id' => $ids);
                    }
                }
                $msg = $this->lang->line('team_success_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                $this->common_model->insert_batch(PROJECT_TEAM_MEMBERS, $membersData);
                if (count($membersReplicaId) > 0) {
                    //$where = array("project_id=" . $this->03 . "");
                    // $this->common_model->delete(PROJECT_ASSIGN_MASTER, $where);
                    $this->common_model->insert_batch(PROJECT_ASSIGN_MASTER, $membersDataTrans);
                }
            } else {
                $msg = $this->lang->line('team_member_empty_error');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
        }

        redirect($data['project_view']); //Redirect On Listing page
    }

    /*
      @Author : Maulik Suthar
      @Desc   : ajax request for the getting team list wise data
      @Input 	:
      @Output	:
      @Date   : 25/02/2016
     */

    function getTeamListbyId($id = null) {
        $data['leadId'] = '';
        $memberArr = array();
        $flag = $this->input->get('flag');
        $data['team_name'] = $this->input->get('team_name');
        if ($id > 0) {
            $data['id'] = $id;
            $data['teamData'] = $this->TeamMembers_model->getTeamMemberList($this->project_id, $id);
            $where = array('L.status' => 1, 'RM.role_name' => 'Team Leader', 'L.is_delete' => 0, 'L.status' => 1);

            $data['team_details'] = $this->common_model->get_records(PROJECT_TEAM_MASTER, '', '', '', 'team_id=' . $id, '');
            $data['id'] = $id;
        }
        $data['teamLeader'] = $this->TeamMembers_model->getTeamLeaderbyId($this->project_id, $id, null, 'Team Leader');
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
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
        $data['team_leader'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], $where, '');
        $data['project_view'] = $this->module . '/' . $this->viewname;
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
// $teamMemerWhere = 'L.login_id  not in (' . implode($memberArr) . ')';
        // $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], '', '');
        $paramsj['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
        $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $paramsj['join_tables'], $params['join_type'], 'L.login_id NOT IN(select member_id from blzdsk_project_team_members where is_delete=0 AND project_id=' . $this->project_id . ' and team_id !=0) AND L.is_delete=0 AND RM.role_name!="Team Leader" AND RM.role_name!="Project Manager" and L.status=1  and is_pm_user=1', '');
        if (count($data['team_members']) > 0) {
            $data['is_member'] = 1;
        } else {
            $data['is_member'] = '';
        }

        //$data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER, '', '', '', 'project_id="' . $this->project_id . '" and is_delete=0', '');
        //$data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER . ' as PTM', '', array(PROJECT_MASTER . ' as PM' => 'PM.project_id=PTM.project_id'), 'left', 'PM.is_delete=0', '');
        $data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER . ' as PTM', '', array(PROJECT_TEAM_MEMBERS . ' as TM' => 'TM.team_id=PTM.team_id'), 'inner', 'TM.is_delete=0 and PTM.is_delete=0', '', '', '', '', '', 'PTM.team_id');

        $data['project_view'] = $this->module . '/' . $this->viewname;
        if ($flag == 1) {
            $data['editTeam'] = 1;
        }
        $this->load->view($this->viewname . '/Add', $data);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : This will load view of the add team members
      @Input 	:
      @Output	:
      @Date   :25/02/2016
     */

    function addTeamMembers() {
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

        $data['id'] = '';
        $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
        $params['join_type'] = 'left';
        $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
// $teamMemerWhere = 'L.login_id  not in (' . implode($memberArr) . ')'; 'login_id not in(select member_id from blzdsk_'.PROJECT_TEAM_MEMBERS.' where project_id="'.$this->project_id.'")'
        // $data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER . ' as PTM', '', array(PROJECT_MASTER . ' as PM' => 'PM.project_id=PTM.project_id'), 'left', 'PM.is_delete=0', '');
        //$data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER . ' as PTM', '', '', '', 'PTM.is_delete=0 and PTM.project_id=' . $this->project_id, '');
        $data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER . ' as PTM', '', array(PROJECT_TEAM_MEMBERS . ' as TM' => 'TM.team_id=PTM.team_id'), 'inner', 'TM.is_delete=0 and PTM.is_delete=0 and PTM.project_id=' . $this->project_id, '', '', '', '', '', 'PTM.team_id');
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
            $paramsj['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
            $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $paramsj['join_tables'], $params['join_type'], 'L.login_id NOT IN(select member_id from blzdsk_project_team_members where is_delete=0 AND project_id=' . $this->project_id . ' and team_id !=0) AND L.is_delete=0 AND RM.role_name!="Team Leader" AND RM.role_name!="Project Manager" and L.status=1 and is_pm_user=1', '');
        } else {
            $paramsj['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
            $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $paramsj['join_tables'], $params['join_type'], 'L.login_id NOT IN(select member_id from blzdsk_project_team_members where is_delete=0 AND project_id=' . $this->project_id . ' and team_id !=0) AND L.is_delete=0 AND RM.role_name!="Team Leader" AND RM.role_name!="Project Manager" and L.status=1 and is_pm_user=1 ', '');

            // $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], 'L.is_delete=0', '');
        }
        $this->load->view($this->viewname . '/AddTeamMember', $data);
    }

    /*
      @Author : Maulik Suthar
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
            //  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            //redirect($data['project_view']);
            $id = 0;
        } else {
            $id = $this->input->post('team_id');
        }
        if (count($this->input->post('team_members')) == 0) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('team_member_empty_error') . "</div>");
            redirect($data['project_view']);
        }
        /*
         * post variables
         */
        $team_data['team_id'] = $this->input->post('team_id');
        $team_data['notify_members'] = $this->input->post('notify_members');
        $team_data['notify_tl'] = $this->input->post('notify_tl');
        $team_data['project_id'] = $this->project_id;
        $team_data['schedule_meeting'] = date('Y-m-d h:i:s', strtotime($this->input->post('schedule_meeting')));
        $team_data['status'] = 1;
        //  if ($id > 0) {
        $membersId = array_unique($this->input->post('team_members'));
        $membersData = array();
        if (count($membersId) > 0) {
//                $where = array("team_id=" . $id . " and project_id=" . $this->project_id . "");
//                $this->common_model->delete(PROJECT_TEAM_MEMBERS, $where);

            $where = "team_id=" . $id . " and project_id=" . $this->project_id . "";
            $oldIds = $membersReplicaId = array();

// $this->common_model->delete(PROJECT_TEAM_MEMBERS, $where);
            $getOldAsignMembers = $this->common_model->get_records(PROJECT_ASSIGN_MASTER, '', '', '', 'project_id="' . $this->project_id . '"', '');
            if (count($getOldAsignMembers) > 0) {
                foreach ($getOldAsignMembers as $obj) {
                    $oldIds[] = $obj['user_id'];
                }
            }
            $membersReplicaId = array_diff($membersId, $oldIds);


            foreach ($membersId as $ids) {
                $membersData[] = array('team_id' => $id, 'member_id' => $ids, 'project_id' => $this->project_id, 'created_date' => datetimeformat(), 'created_by' => $this->session->userdata('LOGGED_IN')['ID'], 'before_after' => $this->input->post('before_after'), 'remind_time' => $this->input->post('remind_time'), 'repeat' => $this->input->post('repeat'), 'remind_day' => $this->input->post('remind_day'), 'reminder' => $this->input->post('reminder'),'schedule_meeting'=> $team_data['schedule_meeting'],'notify_member'=>$this->input->post('notify_members'));
                if ($this->input->post('notify_members') != null):
                    $this->notifyTeamMember($ids);

                    if ($team_data['schedule_meeting'] != null && $team_data['schedule_meeting'] != ''):
                        $this->scheduleMeeting($ids, $team_data['schedule_meeting']);
                    endif;
                endif;
                if ($team_data['notify_tl'] != null):
                    $pmatch = "team_id =" . $id . " and project_id=" . $this->project_id;
                    $pfields = array("team_lead_id");
                    $pdata = $this->common_model->get_records(PROJECT_TEAM_MASTER, $pfields, '', '', $pmatch);
                    $this->scheduleMeeting($pdata[0]['team_lead_id'], $team_data['schedule_meeting']);
                endif;
            }
            if (count($membersReplicaId) > 0) {
                foreach ($membersReplicaId as $ids) {
                    $membersDataTrans[] = array('project_id' => $this->project_id, 'created_date' => datetimeformat(), 'user_id' => $ids);
                }
            }
            $unsortedMemberList = $this->TeamMembers_model->getallTeamMemberList($this->project_id);
            if (count($unsortedMemberList) > 0) {
                foreach ($unsortedMemberList as $mlist) {
                    if (in_array($mlist['member_id'], $membersId)) {
                        $this->common_model->update(PROJECT_TEAM_MEMBERS, array('is_delete' => 1), "project_id=" . $this->project_id . "  and member_id=" . $mlist['member_id']);
                        $this->common_model->delete(PROJECT_PM_ASSIGN, 'project_id=' . $this->project_id . ' and user_id=' . $mlist['member_id']);
                    }
                }
            }
            $memAry = array();
//            $memberDataRmv = $this->TeamMembers_model->getTeamMemberList($this->project_id, $id);
//
//            if (count($memberDataRmv) > 0 && $id > 0) {
//                foreach ($memberDataRmv as $mdata) {
//                    if (!in_array($mdata['member_id'], $membersId)) {
//                        $this->common_model->delete(PROJECT_ASSIGN_MASTER, 'user_id=' . $mdata['member_id'] . ' and project_id=' . $this->project_id);
//                    }
//                }
//            }

            if (count($membersReplicaId) > 0) {
                $where = array("project_id=" . $this->project_id . "");
                // $this->common_model->delete(PROJECT_ASSIGN_MASTER, $where);
                $this->common_model->insert_batch(PROJECT_ASSIGN_MASTER, $membersDataTrans);
            }
            $this->common_model->insert_batch(PROJECT_TEAM_MEMBERS, $membersData);
            $msg = $this->lang->line('team_member_success_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            $msg = $this->lang->line('team_member_empty_error');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
//        } else {
//            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
//        }

        redirect($data['project_view']); //Redirect On Listing page
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Function is used to add team members to the existing team
      @Input 	:
      @Output	:
      @Date   :25/02/2016
     */

    function removeTeamMembers() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        } else {
            $memberId = $this->input->post('memberId');
            $teamId = $this->input->post('teamId');
            $where = "project_id=" . $this->project_id . " and member_id=" . $memberId . " and team_id=" . $teamId;
            //$this->common_model->delete(PROJECT_TEAM_MEMBERS, $where);
            $this->common_model->update(PROJECT_TEAM_MEMBERS, array('is_delete' => 1), $where);
            json_encode(array('status' => 1));
            die;
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Function is used to remove exitsting team
      @Input 	:
      @Output	:
      @Date   :25/02/2016
     */

    function removeTeam() {
        if (!$this->input->is_ajax_request()) {
            exit("No Direct scripts are allowed");
        } else {
            $teamId = $this->input->post('teamId');
            $where = "project_id=" . $this->project_id . " and  team_id=" . $teamId;
            $memberData = $this->TeamMembers_model->getTeamMemberList($this->project_id, $teamId);
            $tlData = $this->TeamMembers_model->getTeamLeaderbyId($this->project_id, $teamId);
            $memberArr = array();
            $TlArr = array();
            if (count($memberData) > 0) {
                foreach ($memberData as $mdata) {
                    $this->common_model->delete(PROJECT_ASSIGN_MASTER, 'user_id=' . $mdata['member_id'] . ' and project_id=' . $this->project_id);
                }
            }
            if (count($tlData) > 0) {
                foreach ($tlData as $tdata) {
                    $this->common_model->delete(PROJECT_ASSIGN_MASTER, 'user_id=' . $tdata['member_id'] . ' and project_id=' . $this->project_id);
                }
            }
            //$this->common_model->delete(PROJECT_TEAM_MASTER, $where);
            //$this->common_model->delete(PROJECT_TEAM_MEMBERS, $where);
            $this->common_model->update(PROJECT_TEAM_MASTER, array('is_delete' => 1), $where);
            $this->common_model->update(PROJECT_TEAM_MEMBERS, array('is_delete' => 1), $where);
            json_encode(array('status' => 1));
            die;
        }
    }

    function assignProjectManager() {
        $data['main_content'] = '/' . $this->viewname . '/TeamMembers';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        if ($this->input->post()) {
            //" and user_id=" . $this->input->post('project_manager_id');
            $where = "project_id=" . $this->project_id;
            $pmId = $this->input->post('project_manager_id');

            $pmdata = $this->TeamMembers_model->getProjectManagerId($this->project_id);

            if (count($pmdata) > 0) {
                $this->common_model->delete(PROJECT_ASSIGN_MASTER, 'project_id=' . $this->project_id . ' and user_id=' . $pmdata[0]['user_id']);
             }

            $this->common_model->delete(PROJECT_PM_ASSIGN, $where);
            $this->common_model->insert(PROJECT_ASSIGN_MASTER, array('project_id' => $this->project_id, 'user_id' => $pmId));
            $this->common_model->insert(PROJECT_PM_ASSIGN, array('project_id' => $this->project_id, 'user_id' => $this->input->post('project_manager_id')));
            $msg = $this->lang->line('pm_asssign_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect($data['project_view']); //Redirect On Listing page
        } else {
            $where = array('L.status' => 1, 'RM.role_name' => 'Project Manager', 'L.is_delete' => 0);
            $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
            $params['join_type'] = 'left';
            $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
            $data['project_managers'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], $where, '');
            $this->load->view($this->viewname . '/selectProjectManager', $data);
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Function is used to add team members to the existing team
      @Input 	:
      @Output	:
      @Date   :25/02/2016
     */

    function editProjectTeam() {
                if (!$this->input->is_ajax_request()) {
            exit("No Direct Scripts are allowed");
        }

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
            $data['id'] = $id;
            $team_data['team_name'] = $this->input->post('team_name');
            $team_data['team_lead_id'] = $this->input->post('team_lead_id');
            $team_data['notify_members'] = $this->input->post('notify_members');
            $team_data['project_id'] = $this->project_id;
            $team_data['schedule_meeting'] = date('Y-m-d h:i:s', strtotime($this->input->post('schedule_meeting')));

            $team_data['status'] = 1;
            $membersDataTrans = array();
// if ($id > 0) { //update
// } else {
            $team_data['created_date'] = datetimeformat();
            $this->common_model->update(PROJECT_TEAM_MASTER, $team_data, 'team_id=' . $id);
            if ($id > 0) {
                $membersId = $this->input->post('team_members');
                $oldTeamMember = $this->TeamMembers_model->getTeamLeaderbyId($this->project_id, $id);
                if (count($oldTeamMember) > 0) {
                    foreach ($oldTeamMember as $oldTm) {
                        $this->common_model->delete(PROJECT_ASSIGN_MASTER, 'user_id=' . $oldTm['member_id'] . ' and project_id=' . $this->project_id);
                    }
                }
                $membersReplicaId = $oldIds = $membersData = $tmp = array();
                if (count($membersId) > 0) {

                    // SELECT * FROM `blzdsk_project_assign_trans` WHERE `project_id` = "2" and `user_id` not in ("20,21,11,16")
                    $getOldAsignMembers = $this->common_model->get_records(PROJECT_ASSIGN_MASTER, '', '', '', 'project_id="' . $this->project_id . '"', '');
                    $oldIds = array();
                    if (count($getOldAsignMembers) > 0) {
                        foreach ($getOldAsignMembers as $obj) {
                            $oldIds[] = $obj['user_id'];
                        }
                    }
                    $membersReplicaId = array_diff($membersId, $oldIds, array($this->input->post('team_lead_id')));

                    array_push($membersId, $this->input->post('team_lead_id'));

                    foreach ($membersId as $ids) {
                        $membersData[] = array('team_id' => $id, 'member_id' => $ids, 'project_id' => $this->project_id, 'created_date' => datetimeformat(), 'created_by' => $this->session->userdata('LOGGED_IN')['ID'], 'before_after' => $this->input->post('before_after'), 'remind_time' => $this->input->post('remind_time'), 'repeat' => $this->input->post('repeat'), 'remind_day' => $this->input->post('remind_day'), 'reminder' => $this->input->post('reminder'),'schedule_meeting'=> $team_data['schedule_meeting'],'notify_member'=>$this->input->post('notify_members'));
                        if ($this->input->post('notify_members') != null):
                            $this->notifyTeamMember($ids);
                            if ($team_data['schedule_meeting'] != null && $team_data['schedule_meeting'] != ''):
                                $this->scheduleMeeting($ids, $team_data['schedule_meeting']);
                            endif;
                        endif;
                    }
                    array_push($membersReplicaId, $this->input->post('team_lead_id'));

                    if (count($membersReplicaId) > 0) {
                        foreach ($membersReplicaId as $ids) {
                            $membersDataTrans[] = array('project_id' => $this->project_id, 'created_date' => datetimeformat(), 'user_id' => $ids);
                        }
                    }

                    $unsortedMemberList = $this->TeamMembers_model->getallTeamMemberList($this->project_id);

                    if (count($unsortedMemberList) > 0) {
                        foreach ($unsortedMemberList as $mlist) {
                            if (in_array($mlist['member_id'], $membersId)) {
                                $this->common_model->update(PROJECT_TEAM_MEMBERS, array('is_delete' => 1), "project_id=" . $this->project_id . "  and member_id=" . $mlist['member_id']);
                                $this->common_model->delete(PROJECT_PM_ASSIGN, 'project_id=' . $this->project_id . ' and user_id=' . $mlist['member_id']);
                            }
                        }
                    }
                    $memAry = array();
                    $memberDataRmv = $this->TeamMembers_model->getTeamMemberList($this->project_id, $id);

                    if (count($memberDataRmv) > 0) {
                        foreach ($memberDataRmv as $mdata) {
                            if (!in_array($mdata['member_id'], $membersId)) {
                                $this->common_model->delete(PROJECT_ASSIGN_MASTER, 'user_id=' . $mdata['member_id'] . ' and project_id=' . $this->project_id);
                            }
                        }
                    }

                    $msg = $this->lang->line('team_update_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    $where = "team_id=" . $id . " and project_id=" . $this->project_id . "";
                    $this->common_model->delete(PROJECT_TEAM_MEMBERS, $where);


                    $this->common_model->insert_batch(PROJECT_TEAM_MEMBERS, $membersData);
                    if (count($membersReplicaId) > 0) {
//                        $where = array("project_id=" . $this->project_id . "");
//                        $this->common_model->delete(PROJECT_ASSIGN_MASTER, $where);
                        $this->common_model->insert_batch(PROJECT_ASSIGN_MASTER, $membersDataTrans);
                    }
                } else {
                    $msg = $this->lang->line('team_member_empty_error');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            } else {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            }
            redirect($data['project_view']); //Redirect On Listing page
        } else {
            $where = array('L.status' => 1, 'RM.role_name' => 'Team Leader', 'L.is_delete' => 0, 'L.status' => 1);
            $params['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
            $params['join_type'] = 'left';
            $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
            $data['team_leader'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $params['join_tables'], $params['join_type'], $where, '');
            $data['project_view'] = $this->module . '/' . $this->viewname;
            $columns = array('L.login_id,L.firstname,L.lastname,L.status,RM.role_name,L.profile_photo');
            $paramsj['join_tables'] = array(ROLE_MASTER . ' as RM' => 'RM.role_id=L.user_type');
            $data['team_members'] = $this->common_model->get_records(LOGIN . ' as L', $columns, $paramsj['join_tables'], $params['join_type'], 'L.login_id NOT IN(select member_id from blzdsk_project_team_members where is_delete=0 AND project_id=' . $this->project_id . ' and team_id !=0) AND L.is_delete=0 AND RM.role_name!="Team Leader" AND RM.role_name!="Project Manager" and L.status=1 and is_pm_user=1 ', '');
            //$data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER, '', '', '', 'project_id="' . $this->project_id . '" and is_delete=0', '');
            //$data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER . ' as PTM', '', array(PROJECT_MASTER . ' as PM' => 'PM.project_id=PTM.project_id'), 'left', 'PM.is_delete=0', '');
            // $data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER . ' as PTM', '', '', '', 'PTM.is_delete=0 and PTM.project_id=' . $this->project_id, '');
            $data['team_list'] = $this->common_model->get_records(PROJECT_TEAM_MASTER . ' as PTM', '', array(PROJECT_TEAM_MEMBERS . ' as TM' => 'TM.team_id=PTM.team_id'), 'inner', 'TM.is_delete=0 and  PTM.is_delete=0 and PTM.project_id=' . $this->project_id, '', '', '', '', '', 'PTM.team_id');

            $data['project_view'] = $this->module . '/' . $this->viewname;
            $data['editTeam'] = 1;
            $this->load->view($this->viewname . '/Add', $data);
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Function is used to add team members to the existing team
      @Input 	:
      @Output	:
      @Date   :25/02/2016
     */

    function getTaskByUser($id) {
        $data['main_content'] = '/' . $this->viewname . '/TeamMembers';
        $data['project_view'] = $this->module . '/' . $this->viewname;
        if ($id > 0) {
            if (!empty($id)) {
                // 'pt.sub_task_id' => 0,
                $where = array('te.user_id' => $id, 'pt.project_id' => $this->project_id,'pt.is_delete'=>0);
            }
            $table = PROJECT_TASK_MASTER . ' as pt';
            $fields = array('ps.status_name,ps.status_color,group_concat(l.firstname," ",l.lastname) as user_name,pt.task_id,pt.description,pt.task_code,pt.task_name,tp.task_name as parent_task,pt.start_date,pt.due_date,pt.status,pt.sub_task_id,mi.milestone_name,mi.milestone_id,pt.deal_id,(select milestone_name from blzdsk_milestone_master as m left join blzdsk_project_task_master as t on t.milestone_id=m.milestone_id where t.sub_task_id=tp.sub_task_id and m.project_id=' . $this->project_id . ' group by tp.sub_task_id) as master_name');
            $join_table = array(
                PROJECT_STATUS . ' as ps' => 'ps.status_id=pt.status',
                PROJECT_TASK_MASTER . ' as tp' => 'pt.sub_task_id=tp.task_id',
                MILESTONE_MASTER . ' as mi' => 'mi.milestone_id=pt.milestone_id',
                PROJECT_TASK_TEAM_TRAN . ' as te' => 'te.task_id=pt.task_id',
                LOGIN . ' as l' => 'te.user_id=l.login_id');
            $group_by = 'te.task_id';
            $data['task_data'] = $this->common_model->get_records($table, $fields, $join_table, 'left', $where, '', '', '', '', '', $group_by);
            $this->load->view($this->viewname . '/taskView', $data);
        } else {
            redirect($data['project_view']);
        }
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Function is used to add team members to the existing team
      @Input 	:
      @Output	:
      @Date   :25/02/2016
     */

    private function notifyTeamMember($userId) {
        // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
        $ematch = "et.subject ='New Project Assigned' ";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
        $umatch = "login_id =" . $userId;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        //get Current Project Name
        $pmatch = "project_id =" . $this->project_id;
        $pfields = array("project_name");
        $pdata = $this->common_model->get_records(PROJECT_MASTER, $pfields, '', '', $pmatch);
        $email = $udata[0]['email'];
        $fullname = $udata[0]['fullname'];
        $projectName = $pdata[0]['project_name'];
        $find = array(
            '{USER}',
            '{PROJECT}',
                //    '{DATE}'
        );

        $replace = array(
            'USER' => $fullname,
            'PROJECT' => $projectName,
                //    'DATE' => $order_info['payment_company']
        );
        $format = $template[0]['body'];
        $body = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
        $subject = "BLAZEDESK :: " . $template[0]['subject'];
        send_mail($email, $subject, $body);
    }

    /*
      @Author : Maulik Suthar
      @Desc   : Function is used to add team members to the existing team
      @Input 	:
      @Output	:
      @Date   :25/02/2016
     */

    private function scheduleMeeting($userId, $date) {

        // Get Template from Template Master
        $etable = EMAIL_TEMPLATE_MASTER . ' as et';
        $ematch = "et.subject ='Project Meeting Scheduled' ";
        $efields = array("et.subject,et.body");
        $template = $this->common_model->get_records($etable, $efields, '', '', $ematch);
        //user details from id
        $umatch = "login_id =" . $userId;
        $ufields = array("concat(firstname,' ',lastname) as fullname,email");
        $udata = $this->common_model->get_records(LOGIN, $ufields, '', '', $umatch);
        //get Current Project Name
        $pmatch = "project_id =" . $this->project_id;
        $pfields = array("project_id,project_name");
        $pdata = $this->common_model->get_records(PROJECT_MASTER, $pfields, '', '', $pmatch);
        $email = $udata[0]['email'];
        $fullname = $udata[0]['fullname'];
        $projectName = $pdata[0]['project_name'];
        $find = array(
            '{USER}',
            '{PROJECT}',
            '{DATE}'
        );

        $replace = array(
            'USER' => $fullname,
            'PROJECT' => $projectName,
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
